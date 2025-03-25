<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use App\User;
use App\Repositories\SettingRepo;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectTeacherAssignment extends Component
{
    use WithPagination;
    
    // Properties for filtering
    public $academicYear;
    public $academicTerm;
    public $classId;
    public $sectionId;
    public $subjectId;
    public $teacherId;
    public $showInactive = false;
    
    // Form properties
    public $isModalOpen = false;
    public $isEditMode = false;
    public $currentAssignmentId;
    public $form = [
        'teacher_id' => '',
        'subject_id' => '',
        'class_id' => '',
        'section_id' => '',
        'academic_year_id' => '',
        'academic_term' => '',
        'is_primary' => true,
        'is_active' => true,
        'notes' => '',
    ];
    
    // Collection properties
    public $teachers;
    public $subjects;
    public $classes;
    public $sections = [];
    public $academicYears;
    public $academicTerms = ['Term 1', 'Term 2', 'Term 3'];
    
    // Settings repository
    protected $settingRepo;
    
    /**
     * Get the SettingRepo instance
     */
    protected function getSettingRepo()
    {
        if (!$this->settingRepo) {
            $this->settingRepo = new SettingRepo();
        }
        return $this->settingRepo;
    }
    
    // Listeners
    protected $listeners = ['refreshAssignments' => '$refresh'];
    
    public function mount()
    {
        $this->loadDropdownData();
        
        // Set defaults
        $currentSession = $this->getSettingRepo()->getSetting('current_session')->first();
        if ($currentSession) {
            $this->academicYear = $currentSession->description;
            $this->form['academic_year_id'] = $currentSession->description;
        }
        
        $this->academicTerm = 'Term 1';
        $this->form['academic_term'] = 'Term 1';
    }
    
    public function loadDropdownData()
    {
        // Fetch teachers correctly by joining with user_types table
        $this->teachers = User::whereHas('userType', function($query) {
            $query->where('title', 'teacher');
        })->orderBy('name')->get();
        
        $this->subjects = Subject::orderBy('subject_name')->get();
        $this->classes = MyClass::orderBy('name')->get();
        
        // Get academic years from settings
        $sessions = $this->getSettingRepo()->getSetting('session');
        $this->academicYears = collect($sessions)->map(function($item) {
            return (object)[
                'id' => $item->description,
                'year' => $item->description
            ];
        });
        
        if ($this->classId) {
            $this->loadSections();
        }
    }
    
    public function loadSections()
    {
        if ($this->classId) {
            $this->sections = Section::where('my_class_id', $this->classId)->orderBy('name')->get();
        } else {
            $this->sections = [];
        }
        
        $this->sectionId = null;
    }
    
    public function updatedClassId()
    {
        $this->loadSections();
        $this->form['class_id'] = $this->classId;
        $this->form['section_id'] = null;
    }
    
    public function updatedForm($value, $property)
    {
        if ($property === 'class_id' && $value) {
            $this->form['section_id'] = null;
            $this->sections = Section::where('my_class_id', $value)->orderBy('name')->get();
        }
    }
    
    public function openModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isModalOpen = true;
        $this->isEditMode = false;
    }
    
    public function closeModal()
    {
        $this->isModalOpen = false;
    }
    
    public function resetForm()
    {
        $this->form = [
            'teacher_id' => '',
            'subject_id' => '',
            'class_id' => $this->classId ?? '',
            'section_id' => $this->sectionId ?? '',
            'academic_year_id' => $this->academicYear ?? '',
            'academic_term' => $this->academicTerm ?? 'Term 1',
            'is_primary' => true,
            'is_active' => true,
            'notes' => '',
        ];
        $this->currentAssignmentId = null;
    }
    
    public function edit($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;
        $this->currentAssignmentId = $id;
        
        $assignment = TeacherSubjectAssignment::findOrFail($id);
        $this->form = [
            'teacher_id' => $assignment->teacher_id,
            'subject_id' => $assignment->subject_id,
            'class_id' => $assignment->class_id,
            'section_id' => $assignment->section_id,
            'academic_year_id' => $assignment->academic_year_id,
            'academic_term' => $assignment->academic_term,
            'is_primary' => $assignment->is_primary,
            'is_active' => $assignment->is_active,
            'notes' => $assignment->notes,
        ];
        
        // Load sections for the selected class
        if ($assignment->class_id) {
            $this->sections = Section::where('my_class_id', $assignment->class_id)->orderBy('name')->get();
        }
        
        $this->isModalOpen = true;
    }
    
    public function save()
    {
        // Pre-process academic_year_id to ensure it's in the correct format
        if (isset($this->form['academic_year_id'])) {
            // Clean up any whitespace
            $this->form['academic_year_id'] = trim($this->form['academic_year_id']);
            
            // If input contains a hyphen, ensure it's properly formatted
            if (strpos($this->form['academic_year_id'], '-') !== false) {
                // Normalize the format to "YYYY-YYYY" 
                $parts = explode('-', $this->form['academic_year_id']);
                if (count($parts) == 2) {
                    $this->form['academic_year_id'] = trim($parts[0]) . '-' . trim($parts[1]);
                }
            }
        }
        
        $this->validate([
            'form.teacher_id' => 'required|exists:users,id',
            'form.subject_id' => 'required|exists:subjects,id',
            'form.class_id' => 'required|exists:my_classes,id',
            'form.section_id' => 'nullable|exists:sections,id',
            'form.academic_year_id' => 'required|string|max:20',
            'form.academic_term' => 'required|in:Term 1,Term 2,Term 3',
            'form.is_primary' => 'boolean',
            'form.is_active' => 'boolean',
            'form.notes' => 'nullable|string|max:255',
        ]);
        
        // Check for duplicate assignment
        $query = TeacherSubjectAssignment::where('teacher_id', $this->form['teacher_id'])
            ->where('subject_id', $this->form['subject_id'])
            ->where('academic_year_id', $this->form['academic_year_id'])
            ->where('academic_term', $this->form['academic_term']);
            
        if ($this->form['class_id']) {
            $query->where('class_id', $this->form['class_id']);
        }
        
        if ($this->form['section_id']) {
            $query->where('section_id', $this->form['section_id']);
        }
        
        if ($this->isEditMode) {
            $query->where('id', '!=', $this->currentAssignmentId);
        }
        
        if ($query->exists()) {
            $this->addError('duplicate', 'This teacher is already assigned to this subject for the selected class/section in this term.');
            return;
        }
        
        if ($this->isEditMode) {
            $assignment = TeacherSubjectAssignment::findOrFail($this->currentAssignmentId);
            $assignment->update($this->form);
            $message = 'Assignment updated successfully!';
        } else {
            TeacherSubjectAssignment::create($this->form);
            $message = 'Teacher assigned to subject successfully!';
        }
        
        $this->isModalOpen = false;
        $this->resetForm();
        $this->dispatch('toast', ['type' => 'success', 'message' => $message]);
    }
    
    public function delete($id)
    {
        $assignment = TeacherSubjectAssignment::findOrFail($id);
        $assignment->delete();
        
        $this->dispatch('toast', [
            'type' => 'success', 
            'message' => 'Assignment removed successfully!'
        ]);
    }
    
    public function toggleStatus($id)
    {
        $assignment = TeacherSubjectAssignment::findOrFail($id);
        $assignment->update([
            'is_active' => !$assignment->is_active
        ]);
        
        $status = $assignment->is_active ? 'activated' : 'deactivated';
        $this->dispatch('toast', [
            'type' => 'success', 
            'message' => "Assignment {$status} successfully!"
        ]);
    }
    
    public function togglePrimary($id)
    {
        $assignment = TeacherSubjectAssignment::findOrFail($id);
        
        // If setting to primary, ensure no other primary exists for this subject/class/section
        if (!$assignment->is_primary) {
            TeacherSubjectAssignment::where('subject_id', $assignment->subject_id)
                ->where('class_id', $assignment->class_id)
                ->where('section_id', $assignment->section_id)
                ->where('academic_year_id', $assignment->academic_year_id)
                ->where('academic_term', $assignment->academic_term)
                ->where('id', '!=', $id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }
        
        $assignment->update([
            'is_primary' => !$assignment->is_primary
        ]);
        
        $status = $assignment->is_primary ? 'set as primary' : 'set as secondary';
        $this->dispatch('toast', [
            'type' => 'success', 
            'message' => "Assignment {$status} successfully!"
        ]);
    }
    
    /**
     * Reset all filter variables to their default state
     */
    public function resetFilters()
    {
        $currentSession = $this->getSettingRepo()->getSetting('current_session')->first();
        
        $this->academicYear = $currentSession ? $currentSession->description : null;
        $this->academicTerm = 'Term 1';
        $this->classId = null;
        $this->sectionId = null;
        $this->subjectId = null;
        $this->teacherId = null;
        $this->showInactive = false;
        
        $this->sections = [];
        
        $this->dispatch('toast', [
            'type' => 'info', 
            'message' => 'Filters have been reset'
        ]);
    }
    
    public function render()
    {
        $query = TeacherSubjectAssignment::query()
            ->with(['teacher', 'subject', 'myClass', 'section']);
            
        if ($this->academicYear) {
            $query->where('academic_year_id', $this->academicYear);
        }
        
        if ($this->academicTerm) {
            $query->where('academic_term', $this->academicTerm);
        }
        
        if ($this->classId) {
            $query->where('class_id', $this->classId);
        }
        
        if ($this->sectionId) {
            $query->where('section_id', $this->sectionId);
        }
        
        if ($this->subjectId) {
            $query->where('subject_id', $this->subjectId);
        }
        
        if ($this->teacherId) {
            $query->where('teacher_id', $this->teacherId);
        }
        
        if (!$this->showInactive) {
            $query->where('is_active', true);
        }
        
        $assignments = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('livewire.subject-teacher-assignment', [
            'assignments' => $assignments
        ]);
    }
}
