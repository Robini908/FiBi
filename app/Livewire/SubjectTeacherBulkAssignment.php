<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use App\User;
use App\Helpers\Qs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubjectTeacherBulkAssignment extends Component
{
    // Basic properties
    public $teachers = [];
    public $subjects = [];
    public $classes = [];
    public $sections = [];
    public $academicYears = [];
    public $academicTerms = ['Term 1', 'Term 2', 'Term 3'];
    
    // Form properties
    public $bulkForm = [
        'academic_year_id' => '',
        'academic_term' => '',
        'class_id' => '',
        'section_ids' => [],
        'is_primary' => true,
        'is_active' => true,
        'override_existing' => false,
        'notes' => '',
    ];
    
    // Teacher-subject mappings
    public $teacherSubjectMappings = [];
    
    // UI state
    public $availableSections;
    public $showResults = false;
    public $results = [
        'total' => 0,
        'created' => 0,
        'skipped' => 0,
        'errors' => 0
    ];

    protected $rules = [
        'bulkForm.academic_year_id' => 'required',
        'bulkForm.academic_term' => 'required',
        'bulkForm.class_id' => 'required|exists:my_classes,id',
        'teacherSubjectMappings' => 'required|array|min:1',
    ];

    protected function checkAccess()
    {
        if (Qs::isSuperAdmin()) {
            return true;
        }
        
        if (!Auth::user() || !(Qs::isAdmin() || Auth::user()->can('manage-teacher-assignments'))) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'You are not authorized to perform this action.'
            ]);
            return redirect()->route('dashboard');
        }

        return true;
    }

    /**
     * Initialize the component
     */
    public function mount()
    {
        $this->checkAccess();
        $this->loadCollections();
        
        $currentYear = date('Y');
        $this->bulkForm['academic_year_id'] = $currentYear . '-' . ($currentYear + 1);
        $this->bulkForm['academic_term'] = 'Term 1';
        
        $this->teacherSubjectMappings = [
            1 => ['teacherId' => '', 'subjectId' => '']
        ];
        
        $this->availableSections = collect();
    }

    /**
     * Load necessary collections for dropdowns
     */
    private function loadCollections()
    {
        try {
            // Load teachers (users with teacher role)
            $this->teachers = User::role('teacher')
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(function($teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'email' => $teacher->email
                    ];
                })
                ->toArray();
            
            // Load subjects
            $this->subjects = Subject::where('is_active', true)
                ->orderBy('subject_name')
                ->get()
                ->map(function($subject) {
                    return [
                        'id' => $subject->id,
                        'subject_name' => $subject->subject_name
                    ];
                })
                ->toArray();
            
            // Load classes
            $this->classes = MyClass::orderBy('name')
                ->get()
                ->map(function($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name
                    ];
                })
                ->toArray();
            
            // Set up academic years
            $currentYear = date('Y');
            $this->academicYears = [
                [
                    'id' => ($currentYear-1) . '-' . $currentYear,
                    'name' => ($currentYear-1) . '-' . $currentYear,
                ],
                [
                    'id' => $currentYear . '-' . ($currentYear+1),
                    'name' => $currentYear . '-' . ($currentYear+1),
                    'is_current' => true
                ],
                [
                    'id' => ($currentYear+1) . '-' . ($currentYear+2),
                    'name' => ($currentYear+1) . '-' . ($currentYear+2),
                ]
            ];

            Log::info('Collections loaded successfully', [
                'teachers_count' => count($this->teachers),
                'subjects_count' => count($this->subjects),
                'classes_count' => count($this->classes),
                'user_role' => Qs::getUserRole()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading collections: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'user_role' => Qs::getUserRole()
            ]);
            
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Error loading data. Please try again.'
            ]);
        }
    }

    /**
     * Handle updating the selected class
     */
    public function updatedBulkFormClassId($value)
    {
        // Reset section selection
        $this->bulkForm['section_ids'] = [];
        
        if (empty($value)) {
            $this->availableSections = collect();
            return;
        }
        
        // Load sections for the selected class
        $sections = Section::where('my_class_id', $value)
            ->orderBy('name')
            ->get();
            
        if ($sections->count() > 0) {
            $this->availableSections = collect([$value => $sections]);
        } else {
            $this->availableSections = collect();
        }
    }

    /**
     * Add a new teacher-subject mapping
     */
    public function addTeacherSubjectMapping()
    {
        $nextIndex = empty($this->teacherSubjectMappings) ? 1 : max(array_keys($this->teacherSubjectMappings)) + 1;
        
        $this->teacherSubjectMappings[$nextIndex] = [
            'teacherId' => '',
            'subjectId' => ''
        ];
    }

    /**
     * Remove a teacher-subject mapping
     */
    public function removeTeacherSubjectMapping($index)
    {
        // Don't remove if it's the last mapping
        if (count($this->teacherSubjectMappings) > 1) {
            unset($this->teacherSubjectMappings[$index]);
        }
    }

    /**
     * Select all sections for the current class
     */
    public function selectAllSections()
    {
        if (empty($this->bulkForm['class_id'])) {
            return;
        }
        
        $classId = $this->bulkForm['class_id'];
        
        if (!isset($this->availableSections[$classId])) {
            return;
        }
        
        $this->bulkForm['section_ids'] = $this->availableSections[$classId]->pluck('id')->toArray();
    }

    /**
     * Clear all selected sections
     */
    public function clearSectionSelection()
    {
        $this->bulkForm['section_ids'] = [];
    }

    /**
     * Save bulk assignments
     */
    public function saveBulkAssignments()
    {
        // Recheck authorization before saving
        $this->checkAccess();
        
        $this->validate();
        
        foreach ($this->teacherSubjectMappings as $index => $mapping) {
            $this->validate([
                "teacherSubjectMappings.{$index}.teacherId" => 'required|exists:users,id',
                "teacherSubjectMappings.{$index}.subjectId" => 'required|exists:subjects,id',
            ]);
        }
        
        try {
            DB::beginTransaction();
            
            $total = 0;
            $created = 0;
            $skipped = 0;
            $errors = 0;
            
            foreach ($this->teacherSubjectMappings as $mapping) {
                $teacherId = $mapping['teacherId'];
                $subjectId = $mapping['subjectId'];
                
                // Get sections to process
                $sectionsToProcess = empty($this->bulkForm['section_ids']) 
                    ? [null] 
                    : $this->bulkForm['section_ids'];
                
                foreach ($sectionsToProcess as $sectionId) {
                    $total++;
                    
                    try {
                        // Check for existing assignment
                        $exists = TeacherSubjectAssignment::where([
                            'teacher_id' => $teacherId,
                            'subject_id' => $subjectId,
                            'class_id' => $this->bulkForm['class_id'],
                            'section_id' => $sectionId,
                            'academic_year_id' => $this->bulkForm['academic_year_id'],
                            'academic_term' => $this->bulkForm['academic_term'],
                        ])->exists();
                        
                        if ($exists && !$this->bulkForm['override_existing']) {
                            $skipped++;
                            continue;
                        }
                        
                        // If primary, remove other primary assignments
                        if ($this->bulkForm['is_primary'] && $this->bulkForm['override_existing']) {
                            TeacherSubjectAssignment::where([
                                'subject_id' => $subjectId,
                                'class_id' => $this->bulkForm['class_id'],
                                'section_id' => $sectionId,
                                'academic_year_id' => $this->bulkForm['academic_year_id'],
                                'academic_term' => $this->bulkForm['academic_term'],
                                'is_primary' => true
                            ])->update(['is_primary' => false]);
                        }
                        
                        // Create or update assignment
                        TeacherSubjectAssignment::updateOrCreate(
                            [
                                'teacher_id' => $teacherId,
                                'subject_id' => $subjectId,
                                'class_id' => $this->bulkForm['class_id'],
                                'section_id' => $sectionId,
                                'academic_year_id' => $this->bulkForm['academic_year_id'],
                                'academic_term' => $this->bulkForm['academic_term'],
                            ],
                            [
                                'is_primary' => $this->bulkForm['is_primary'],
                                'is_active' => $this->bulkForm['is_active'],
                                'notes' => $this->bulkForm['notes'],
                                'tenant_id' => Auth::user()->tenant_id ?? null,
                            ]
                        );
                        
                        $created++;
                    } catch (\Exception $e) {
                        Log::error('Error in bulk assignment: ' . $e->getMessage(), [
                            'teacher_id' => $teacherId,
                            'subject_id' => $subjectId,
                            'class_id' => $this->bulkForm['class_id'],
                            'section_id' => $sectionId,
                            'user_id' => Auth::id(),
                        ]);
                        $errors++;
                    }
                }
            }
            
            DB::commit();
            
            // Update results
            $this->results = [
                'total' => $total,
                'created' => $created,
                'skipped' => $skipped,
                'errors' => $errors
            ];
            
            $this->showResults = true;
            
            // Emit success event
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => "Bulk assignment completed: {$created} created, {$skipped} skipped, {$errors} errors"
            ]);
            
            // Emit refresh event
            $this->dispatch('teacher-assignments-updated');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Bulk assignment error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'An error occurred during bulk assignment'
            ]);
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.bulk-subject-teacher-assignment');
    }
}
