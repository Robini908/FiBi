<?php

namespace App\Livewire;

use App\User;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;
use WireElements\Pro\Components\Modal\Modal;

class BulkSubjectTeacherAssignment extends ModalComponent
{
    public $bulkForm = [
        'academic_year_id' => '',
        'academic_term' => '',
        'class_id' => '',
        'section_ids' => [],
        'is_primary' => false,
        'is_active' => true,
        'override_existing' => false,
        'notes' => '',
    ];

    public $teacherSubjectMappings = [];
    public $showResults = false;
    public $results = [
        'total' => 0,
        'created' => 0,
        'skipped' => 0,
        'errors' => 0,
    ];

    protected $rules = [
        'bulkForm.academic_year_id' => 'required|string',
        'bulkForm.academic_term' => 'required|in:TERM_1,TERM_2,TERM_3',
        'bulkForm.class_id' => 'required|exists:my_classes,id',
        'bulkForm.section_ids' => 'required|array|min:1',
        'bulkForm.section_ids.*' => 'exists:sections,id',
        'bulkForm.is_primary' => 'boolean',
        'bulkForm.is_active' => 'boolean',
        'bulkForm.override_existing' => 'boolean',
        'bulkForm.notes' => 'nullable|string|max:500',
        'teacherSubjectMappings' => 'required|array|min:1',
        'teacherSubjectMappings.*.teacherId' => 'required|exists:users,id',
        'teacherSubjectMappings.*.subjectId' => 'required|exists:subjects,id',
    ];

    protected $messages = [
        'bulkForm.academic_year_id.required' => 'Please select an academic year.',
        'bulkForm.academic_term.required' => 'Please select a term.',
        'bulkForm.class_id.required' => 'Please select a class.',
        'bulkForm.section_ids.required' => 'Please select at least one section.',
        'bulkForm.section_ids.min' => 'Please select at least one section.',
        'teacherSubjectMappings.required' => 'Please add at least one teacher-subject pair.',
        'teacherSubjectMappings.min' => 'Please add at least one teacher-subject pair.',
        'teacherSubjectMappings.*.teacherId.required' => 'Please select a teacher for all pairs.',
        'teacherSubjectMappings.*.subjectId.required' => 'Please select a subject for all pairs.',
    ];

    public function mount()
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }
        
        Gate::authorize('manage-teacher-assignments');
        $this->addTeacherSubjectMapping();

        // Set default values
        $this->bulkForm['academic_year_id'] = 'YEAR_' . date('Y');
        $this->bulkForm['is_active'] = true;
    }

    public function getAcademicYearsProperty()
    {
        $currentYear = (int)date('Y');
        $years = [];
        
        // Generate 5 years (current year and 4 previous years)
        for ($i = 0; $i < 5; $i++) {
            $year = $currentYear - $i;
            $years[] = [
                'id' => 'YEAR_' . $year,
                'name' => (string)$year,
                'is_current' => $i === 0
            ];
        }
        
        return collect($years);
    }

    public function getAcademicTermsProperty()
    {
        return ['TERM_1', 'TERM_2', 'TERM_3'];
    }

    public function getClassesProperty()
    {
        return MyClass::with('sections')  // Eager load sections
            ->orderBy('name')
            ->get();
    }

    public function getAvailableSectionsProperty(): Collection
    {
        if (!$this->bulkForm['class_id']) {
            return collect();
        }

        return Section::where('class_room_id', $this->bulkForm['class_id'])
            ->with(['class']) // Eager load class relationship
            ->orderBy('name')
            ->get()
            ->groupBy('class_room_id');
    }

    public function getTeachersProperty()
    {
        return User::role('teacher')
            ->where('is_active', true)  // Only get active teachers
            ->orderBy('name')
            ->get(['id', 'name', 'email']); // Select only needed fields
    }

    public function getSubjectsProperty()
    {
        return Subject::where('is_active', true)  // Only get active subjects
            ->orderBy('subject_name')
            ->get(['id', 'subject_name']);  // Select only needed fields
    }

    public function updated($propertyName)
    {
        // Clear section selection when class changes
        if ($propertyName === 'bulkForm.class_id') {
            $this->bulkForm['section_ids'] = [];
        }

        $this->validateOnly($propertyName);
    }

    public function addTeacherSubjectMapping()
    {
        $this->teacherSubjectMappings[] = [
            'teacherId' => '',
            'subjectId' => '',
        ];
    }

    public function removeTeacherSubjectMapping($index)
    {
        if (count($this->teacherSubjectMappings) > 1) {
            unset($this->teacherSubjectMappings[$index]);
            $this->teacherSubjectMappings = array_values($this->teacherSubjectMappings);
        } else {
            $this->dispatch('toast', [
                'message' => 'At least one teacher-subject pair is required.',
                'type' => 'warning'
            ]);
        }
    }

    public function selectAllSections()
    {
        if ($this->availableSections->isEmpty()) {
            return;
        }

        $this->bulkForm['section_ids'] = $this->availableSections
            ->flatten()
            ->pluck('id')
            ->toArray();
    }

    public function clearSectionSelection()
    {
        $this->bulkForm['section_ids'] = [];
    }

    public function saveBulkAssignments()
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            $this->dispatch('toast', [
                'message' => 'Unauthorized action.',
                'type' => 'error'
            ]);
            return;
        }

        Gate::authorize('manage-teacher-assignments');
        $this->validate();

        try {
            DB::beginTransaction();

            $total = 0;
            $created = 0;
            $skipped = 0;
            $errors = 0;

            // Validate that sections belong to selected class
            $validSectionIds = Section::where('class_room_id', $this->bulkForm['class_id'])
                ->pluck('id')
                ->toArray();
            
            $invalidSections = array_diff($this->bulkForm['section_ids'], $validSectionIds);
            if (!empty($invalidSections)) {
                throw new \Exception('Invalid section selections detected.');
            }

            foreach ($this->bulkForm['section_ids'] as $sectionId) {
                foreach ($this->teacherSubjectMappings as $mapping) {
                    $total++;

                    try {
                        // Validate teacher exists and has teacher role
                        $teacher = User::findOrFail($mapping['teacherId']);
                        if (!$teacher->hasRole('teacher')) {
                            throw new \Exception("User {$teacher->id} is not a teacher.");
                        }

                        // Check for existing assignment
                        $existing = TeacherSubjectAssignment::where([
                            'teacher_id' => $mapping['teacherId'],
                            'subject_id' => $mapping['subjectId'],
                            'section_id' => $sectionId,
                            'academic_year_id' => $this->bulkForm['academic_year_id'],
                            'academic_term' => $this->bulkForm['academic_term'],
                        ])->first();

                        if ($existing && !$this->bulkForm['override_existing']) {
                            $skipped++;
                            continue;
                        }

                        $assignmentData = [
                            'teacher_id' => $mapping['teacherId'],
                            'subject_id' => $mapping['subjectId'],
                            'section_id' => $sectionId,
                            'academic_year_id' => $this->bulkForm['academic_year_id'],
                            'academic_term' => $this->bulkForm['academic_term'],
                            'is_primary' => $this->bulkForm['is_primary'],
                            'is_active' => $this->bulkForm['is_active'],
                            'notes' => $this->bulkForm['notes'],
                            'tenant_id' => Auth::user()->tenant_id, // Ensure tenant_id is set
                        ];

                        if ($existing) {
                            $existing->update($assignmentData);
                        } else {
                            TeacherSubjectAssignment::create($assignmentData);
                        }

                        $created++;
                    } catch (\Exception $e) {
                        $errors++;
                        \Log::error('Error in bulk teacher-subject assignment:', [
                            'error' => $e->getMessage(),
                            'mapping' => $mapping,
                            'section_id' => $sectionId,
                            'user_id' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

            $this->results = [
                'total' => $total,
                'created' => $created,
                'skipped' => $skipped,
                'errors' => $errors,
            ];

            $this->showResults = true;

            if ($errors > 0) {
                $this->dispatch('toast', [
                    'message' => "Assignments completed with {$errors} errors. Check logs for details.",
                    'type' => 'warning',
                ]);
            } else {
                $this->dispatch('toast', [
                    'message' => "Successfully created {$created} assignments" . ($skipped > 0 ? " (skipped {$skipped})" : ""),
                    'type' => 'success',
                ]);
            }

            $this->dispatch('teacher-assignments-updated');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in bulk teacher-subject assignment transaction:', [
                'error' => $e->getMessage(),
                'form_data' => $this->bulkForm,
                'mappings' => $this->teacherSubjectMappings,
                'user_id' => Auth::id(),
            ]);

            $this->dispatch('toast', [
                'message' => 'Failed to process assignments: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public static function modalMaxWidth(): string
    {
        return '5xl';
    }

    public function render()
    {
        return view('livewire.bulk-subject-teacher-assignment');
    }
}
