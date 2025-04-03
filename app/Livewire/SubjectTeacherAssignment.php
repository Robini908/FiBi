<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use App\User;
use App\Repositories\SettingRepo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Assignment;
// Add imports for export functionality
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeacherAssignmentsExport;
use Barryvdh\DomPDF\Facade\Pdf;

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
    
    // Bulk assignment properties
    public $isBulkModalOpen = false;
    public $bulkForm = [
        'teacher_ids' => [],
        'subject_ids' => [],
        'class_ids' => [],
        'section_ids' => [],
        'academic_year_id' => '',
        'academic_term' => '',
        'is_primary' => true,
        'is_active' => true,
        'notes' => '',
        'override_existing' => false,
    ];
    
    // Teacher-Subject Mapping Properties
    public $teacherSubjectMappings = [];
    
    public $availableSections;
    public $processingSections = false;
    public $bulkAssignmentResults = [
        'created' => 0,
        'skipped' => 0,
        'errors' => 0,
        'total' => 0,
    ];
    public $showBulkResults = false;
    
    // Bulk deletion properties
    public $selectedAssignments = [];
    public $selectAll = false;
    public $isBulkDeleteModalOpen = false;
    public $bulkDeleteResults = [
        'deleted' => 0,
        'failed' => 0,
        'total' => 0,
    ];
    public $showBulkDeleteResults = false;
    
    // Collection properties
    public $teachers;
    public $subjects;
    public $classes;
    public $sections = [];
    public $academicTerms = ['Term 1', 'Term 2', 'Term 3'];
    
    // Settings repository
    protected $settingRepo;
    
    // Filter properties
    public $filterTeacher = '';
    public $filterSubject = '';
    public $filterClass = '';
    public $filterAcademicYear = '';
    public $filterStatus = '';
    public $filterPrimary = '';
    public $perPage = 10;
    public $search = '';
    
    // Statistics properties
    public $activeCount;
    public $primaryCount;
    public $teacherCount;
    
    // Hardcoded academic years (until AcademicYear model is available)
    public $academicYears = [];
    
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
    protected $listeners = [
        'refreshAssignments' => '$refresh',
        'reset-form-state' => 'handleResetFormState',
        'confirmBulkAssignment' => 'processBulkAssignmentConfirmed'
    ];
    
    /**
     * Initialize the component and load necessary data
     */
    public function mount()
    {
        // Initialize collections and properties
        $this->availableSections = collect();
        $this->teacherSubjectMappings = [];
        
        // Get current academic year from settings
        $currentSession = $this->getSettingRepo()->getSetting('current_session')->first();
        $currentYear = $currentSession ? $currentSession->description : '2023-2024';
        
        // Initialize with default values for filter
        $this->academicYear = $currentYear;
        $this->filterAcademicYear = $currentYear;
        
        // Set default academic term
        $this->academicTerm = 'Term 1';
        
        // Load collection data
        $this->loadCollections();
        
        // Set default per page value
        $this->perPage = 10;
        
        // Initialize statistics
        $this->calculateStatistics();
        
        // Log initial state
        \Log::info('SubjectTeacherAssignment component mounted', [
            'academicYear' => $this->academicYear,
            'academicTerm' => $this->academicTerm,
            'filterAcademicYear' => $this->filterAcademicYear,
            'currentSession' => $currentSession ? $currentSession->description : 'Not set'
        ]);
    }
    
    /**
     * Load collections for dropdowns
     */
    public function loadCollections()
    {
        // Load teachers (users with role = teacher)
        $this->teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->orderBy('name')->get();
        
        // Load subjects
        $this->subjects = Subject::orderBy('subject_name')->get();
        
        // Load classes
        $this->classes = MyClass::orderBy('name')->get();
        
        // Set up hardcoded academic years (could be replaced with a model in the future)
        $currentYear = date('Y');
        $this->academicYears = [
            ($currentYear-1) . '-' . $currentYear,
            $currentYear . '-' . ($currentYear+1),
            ($currentYear+1) . '-' . ($currentYear+2)
        ];
        
        // Log loaded data
        \Log::info('Loaded collections for SubjectTeacherAssignment', [
            'teachers' => $this->teachers->count(),
            'subjects' => $this->subjects->count(),
            'classes' => $this->classes->count()
        ]);
    }
    
    public function loadDropdownData()
    {
        // Fetch teachers correctly by using Spatie's roles
        $this->teachers = User::whereHas('roles', function($query) {
            $query->where('name', 'teacher');
        })->orderBy('name')->get();
        
        $this->subjects = Subject::orderBy('subject_name')->get();
        $this->classes = MyClass::orderBy('name')->get();
        
        // Get academic years from settings
        $sessions = $this->getSettingRepo()->getSetting('session');
        
        // Cast each item explicitly to ensure types are correct
        $this->academicYears = collect($sessions)->map(function($item) {
            // Ensure we're working with the correct type
            $description = is_object($item) && property_exists($item, 'description') 
                ? $item->description 
                : (is_array($item) && isset($item['description']) 
                    ? $item['description'] 
                    : '');
                    
            return (object)[
                'id' => $description,
                'year' => $description
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
        // Load sections for the selected class
        if ($this->classId) {
            $this->sections = Section::where('my_class_id', $this->classId)->orderBy('name')->get();
            
            // Set the first section as default if available
            if (is_object($this->sections) && count($this->sections) > 0) {
                $section = $this->sections->first();
                if ($section) {
                    $this->sectionId = $section->id;
                } else {
                    $this->sectionId = null;
                }
            } else {
                $this->sectionId = null;
            }
        } else {
            $this->sections = [];
            $this->sectionId = null;
        }
        
        // Update the form with the selected class and section
        $this->form['class_id'] = $this->classId;
        $this->form['section_id'] = $this->sectionId;
    }
    
    public function updatedForm($value, $property)
    {
        if ($property === 'class_id' && $value) {
            $this->form['section_id'] = null;
            $this->sections = Section::where('my_class_id', $value)->orderBy('name')->get();
            
            // Debug info
            $sectionsCount = is_object($this->sections) ? $this->sections->count() : count($this->sections);
            $this->dispatch('toast', [
                'type' => 'info', 
                'message' => 'Loaded ' . $sectionsCount . ' sections for class ID ' . $value
            ]);
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
        $this->resetValidation();
        $this->resetForm();
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
        
        // Keep the current academic year
        if (!$this->form['academic_year_id']) {
            // Get current academic year from settings or use default
            $currentSession = $this->getSettingRepo()->getSetting('current_session')->first();
            if ($currentSession) {
                $this->form['academic_year_id'] = $currentSession->description;
            } else {
                $this->form['academic_year_id'] = '2023-2024'; // default value
            }
        }
    }
    
    public function edit($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;
        $this->currentAssignmentId = $id;
        
        $assignment = TeacherSubjectAssignment::with(['teacher', 'subject', 'myClass', 'section'])
            ->findOrFail($id);
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
        // Debug data
        $this->dispatch('toast', [
            'type' => 'info', 
            'message' => 'Attempting to save assignment...'
        ]);
        
        // Validate using Laravel's validation system
        $this->validate([
            'form.teacher_id' => 'required',
            'form.subject_id' => 'required',
            'form.class_id' => 'required',
            'form.academic_year_id' => 'required',
            'form.academic_term' => 'required',
        ], [
            'form.teacher_id.required' => 'Teacher is required',
            'form.subject_id.required' => 'Subject is required',
            'form.class_id.required' => 'Class is required',
            'form.academic_year_id.required' => 'Academic year is required',
            'form.academic_term.required' => 'Academic term is required',
        ]);
        
        try {
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
            
            if ($this->isEditMode && $this->currentAssignmentId) {
                $query->where('id', '!=', $this->currentAssignmentId);
            }
            
            if ($query->exists()) {
                $this->addError('duplicate', 'This teacher is already assigned to this subject for the selected class/section in this term.');
                $this->dispatch('toast', [
                    'type' => 'error', 
                    'message' => 'This teacher is already assigned to this subject for the selected class/section in this term.'
                ]);
                return;
            }
            
            if ($this->isEditMode && $this->currentAssignmentId) {
                $assignment = TeacherSubjectAssignment::findOrFail($this->currentAssignmentId);
                
                // Log the data being updated
                \Log::info('Updating assignment #' . $this->currentAssignmentId, $this->form);
                
                $result = $assignment->update($this->form);
                
                if ($result) {
                    $message = 'Assignment updated successfully!';
                    
                    // First dispatch success message, then close modal and reset form
                    $this->dispatch('toast', ['type' => 'success', 'message' => $message]);
                    $this->isModalOpen = false;
                    $this->resetForm();
                    $this->resetValidation();
                } else {
                    throw new \Exception('Failed to update assignment');
                }
            } else {
                // Log the data being created
                \Log::info('Creating new assignment', $this->form);
                
                $assignment = TeacherSubjectAssignment::create($this->form);
                
                if ($assignment) {
                    $message = 'Teacher assigned to subject successfully!';
                    
                    // First dispatch success message, then close modal and reset form
                    $this->dispatch('toast', ['type' => 'success', 'message' => $message]);
                    $this->isModalOpen = false;
                    $this->resetForm();
                    $this->resetValidation();
                } else {
                    throw new \Exception('Failed to create assignment');
                }
            }
            
            // After successful save, refresh the component
            $this->dispatch('refreshAssignments');
            
        } catch (\Exception $e) {
            // Log the error with detailed info
            \Log::error('Error saving assignment: ' . $e->getMessage(), [
                'form_data' => $this->form,
                'exception' => $e->getTraceAsString()
            ]);
            
            // Display error message
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Error saving assignment: ' . $e->getMessage()
            ]);
        }
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
        // Ensure academic year and term are set to default values if not specified
        if (empty($this->academicYear)) {
            $currentSession = $this->getSettingRepo()->getSetting('current_session')->first();
            $this->academicYear = $currentSession ? $currentSession->description : '2023-2024';
        }
        
        if (empty($this->academicTerm)) {
            $this->academicTerm = 'Term 1';
        }
        
        // Get filtered assignments query
        $query = $this->getFilteredQuery();
        $assignments = $query->with(['teacher', 'subject', 'myClass', 'section'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
        
        // Calculate statistics
        $this->calculateStatistics();
        
        // Log rendering information
        \Log::info('Rendering assignments', [
            'academicYear' => $this->academicYear,
            'academicTerm' => $this->academicTerm,
            'count' => $assignments->total()
        ]);
        
        return view('livewire.subject-teacher-assignment', [
            'assignments' => $assignments
        ]);
    }
    
    public function calculateStatistics()
    {
        $baseQuery = TeacherSubjectAssignment::query();
        
        // Apply academic year filter to statistics if set
        if ($this->filterAcademicYear) {
            $baseQuery->where('academic_year_id', $this->filterAcademicYear);
        }
        
        $this->activeCount = (clone $baseQuery)->where('is_active', true)->count();
        $this->primaryCount = (clone $baseQuery)->where('is_primary', true)->count();
        $this->teacherCount = (clone $baseQuery)
            ->where('is_active', true)
            ->distinct('teacher_id')
            ->count('teacher_id');
    }
    
    /**
     * Setup hardcoded academic years
     */
    private function setupAcademicYears()
    {
        // Create a range of years dynamically
        $startYear = 2018;
        $endYear = date('Y') + 2; // Current year plus 2 future years
        $academicYears = [];
        
        for ($year = $startYear; $year < $endYear; $year++) {
            $academicYearStr = $year . '-' . ($year + 1);
            $academicYears[] = [
                'id' => $academicYearStr,
                'name' => $academicYearStr,
                'is_current' => ($academicYearStr === '2023-2024') // Mark current year
            ];
        }
        
        $this->academicYears = $academicYears;
    }
    
    /**
     * Handle reset form state event
     */
    public function handleResetFormState()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isModalOpen = false;
        $this->isEditMode = false;
        $this->currentAssignmentId = null;
    }

    /**
     * Open bulk assignment modal
     */
    public function openBulkModal()
    {
        // Reset any previous validation errors
        $this->resetValidation();
        
        // Reset the bulk form
        $this->bulkForm = [
            'teacher_ids' => [],
            'subject_ids' => [],
            'class_ids' => [],
            'section_ids' => [],
            'academic_year_id' => $this->academicYear ?? '',
            'academic_term' => $this->academicTerm ?? 'Term 1',
            'is_primary' => true,
            'is_active' => true,
            'notes' => '',
            'override_existing' => false,
        ];
        
        // Initialize teacher-subject mappings with one empty mapping
        $this->teacherSubjectMappings = [
            0 => [
                'teacherId' => '',
                'subjectId' => ''
            ]
        ];
        
        // Reset results display
        $this->showBulkResults = false;
        $this->bulkAssignmentResults = [
            'created' => 0,
            'skipped' => 0,
            'errors' => 0,
            'total' => 0,
        ];
        
        // Reset sections
        $this->availableSections = collect();
        $this->processingSections = false;
        
        // Open the modal
        $this->isBulkModalOpen = true;
        
        // Debug logging
        \Log::info('Opened bulk assignment modal', [
            'teacherSubjectMappings' => $this->teacherSubjectMappings,
            'academicYear' => $this->academicYear,
            'academicTerm' => $this->academicTerm
        ]);
    }

    /**
     * Close the bulk assignment modal
     */
    public function closeBulkModal()
    {
        $this->isBulkModalOpen = false;
        $this->resetBulkForm();
        $this->teacherSubjectMappings = []; // Reset mappings
    }

    /**
     * Reset bulk assignment form
     */
    public function resetBulkForm()
    {
        // Get current academic year from settings
        $currentSession = $this->getSettingRepo()->getSetting('current_session')->first();
        $currentYear = $currentSession ? $currentSession->description : '2023-2024';
        
        $this->bulkForm = [
            'teacher_ids' => [],
            'subject_ids' => [],
            'class_ids' => [],
            'section_ids' => [],
            'academic_year_id' => $currentYear,
            'academic_term' => 'Term 1',
            'is_primary' => true,
            'is_active' => true,
            'notes' => '',
            'override_existing' => false,
        ];

        $this->availableSections = collect();
        $this->processingSections = false;
        $this->bulkAssignmentResults = [
            'created' => 0,
            'skipped' => 0,
            'errors' => 0,
            'total' => 0,
        ];
        $this->showBulkResults = false;
        $this->teacherSubjectMappings = []; // Reset mappings
    }

    /**
     * Sets a teacher or subject ID for a specific mapping
     */
    public function setTeacherSubjectMapping($mappingId, $field, $value)
    {
        // Initialize the mapping if it doesn't exist
        if (!isset($this->teacherSubjectMappings[$mappingId])) {
            $this->teacherSubjectMappings[$mappingId] = [
                'teacherId' => '',
                'subjectId' => ''
            ];
        }
        
        // Set the value based on field
        if ($field === 'teacherId') {
            $this->teacherSubjectMappings[$mappingId]['teacherId'] = $value;
        } elseif ($field === 'subjectId') {
            $this->teacherSubjectMappings[$mappingId]['subjectId'] = $value;
        }
    }
    
    /**
     * Removes a teacher-subject mapping
     */
    public function removeTeacherSubjectMapping($mappingId)
    {
        if (isset($this->teacherSubjectMappings[$mappingId])) {
            unset($this->teacherSubjectMappings[$mappingId]);
        }
    }
    
    /**
     * Save bulk assignments
     */
    public function saveBulkAssignments()
    {
        // Validate that we have at least one teacher-subject mapping
        if (empty($this->teacherSubjectMappings)) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Please add at least one teacher-subject mapping.'
            ]);
            return;
        }
        
        // Check for incomplete mappings
        $incomplete = false;
        foreach ($this->teacherSubjectMappings as $mappingId => $mapping) {
            if (empty($mapping['teacherId']) || empty($mapping['subjectId'])) {
                $incomplete = true;
                // Add specific validation errors
                if (empty($mapping['teacherId'])) {
                    $this->addError("teacherSubjectMappings.{$mappingId}.teacherId", 'Please select a teacher');
                }
                if (empty($mapping['subjectId'])) {
                    $this->addError("teacherSubjectMappings.{$mappingId}.subjectId", 'Please select a subject');
                }
            }
        }
        
        if ($incomplete) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Please complete all teacher-subject mappings.'
            ]);
            return;
        }
        
        // Validate form inputs
        $this->validate([
            'bulkForm.academic_year_id' => 'required',
            'bulkForm.academic_term' => 'required',
            'bulkForm.class_ids' => 'required|array|min:1',
            'bulkForm.section_ids' => 'required|array|min:1',
        ], [
            'bulkForm.academic_year_id.required' => 'Please select an academic year.',
            'bulkForm.academic_term.required' => 'Please select an academic term.',
            'bulkForm.class_ids.required' => 'Please select at least one class.',
            'bulkForm.class_ids.min' => 'Please select at least one class.',
            'bulkForm.section_ids.required' => 'Please select at least one section for each class.',
            'bulkForm.section_ids.min' => 'Please select at least one section for each class.',
        ]);
        
        // Perform validations for potential issues
        $validationResults = $this->validateBulkAssignments();
        if (!empty($validationResults['error'])) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => $validationResults['error']
            ]);
            return;
        }
        
        // Display warnings if any and ask for confirmation
        if (!empty($validationResults['warning'])) {
            $this->dispatch('confirm-bulk-assignment', [
                'message' => $validationResults['warning'],
                'callback' => 'confirmBulkAssignment'
            ]);
            return;
        }
        
        // If no warnings, proceed directly with the assignment process
        $this->processBulkAssignmentConfirmed();
    }

    /**
     * Validate bulk assignments for potential issues
     * 
     * @return array
     */
    private function validateBulkAssignments()
    {
        $result = [
            'error' => null,
            'warning' => null
        ];
        
        // Check if any teacher-subject mappings are provided
        if (empty($this->teacherSubjectMappings)) {
            $result['error'] = 'Please add at least one teacher-subject mapping.';
            return $result;
        }
        
        // Check if any classes are selected
        if (empty($this->bulkForm['class_ids'])) {
            $result['error'] = 'Please select at least one class.';
            return $result;
        }
        
        // Check if any sections are selected
        if (empty($this->bulkForm['section_ids'])) {
            $result['error'] = 'Please select at least one section.';
            return $result;
        }
        
        // Check if section selections are all valid for the selected classes
        if (!empty($this->bulkForm['section_ids'])) {
            $validSectionIds = Section::whereIn('my_class_id', $this->bulkForm['class_ids'])
                ->pluck('id')
                ->toArray();
                
            $invalidSections = array_diff($this->bulkForm['section_ids'], $validSectionIds);
            
            if (!empty($invalidSections)) {
                $result['error'] = 'Some selected sections do not belong to the selected classes.';
                return $result;
            }
        }
        
        // Check for incomplete mappings
        foreach ($this->teacherSubjectMappings as $mappingId => $mapping) {
            if (empty($mapping['teacherId']) || empty($mapping['subjectId'])) {
                if (empty($mapping['teacherId'])) {
                    $result['error'] = 'Please select a teacher for all mappings.';
                }
                if (empty($mapping['subjectId'])) {
                    $result['error'] = $result['error'] 
                        ? $result['error'] . ' Please select a subject for all mappings.' 
                        : 'Please select a subject for all mappings.';
                }
                return $result;
            }
        }
        
        // Check for potential workload issues
        $workloadWarning = $this->checkTeacherWorkload();
        if ($workloadWarning) {
            if (empty($result['warning'])) {
                $result['warning'] = $workloadWarning;
            } else {
                $result['warning'] .= ' ' . $workloadWarning;
            }
        }
        
        // Count how many teacher-subject mappings are being created
        $mappingCount = count($this->teacherSubjectMappings);
        $sectionCount = count($this->bulkForm['section_ids']);
        
        // Calculate total potential assignments
        $totalPotentialAssignments = $mappingCount * $sectionCount;
        
        // Warn if creating a large number of assignments
        if ($totalPotentialAssignments > 50) {
            if (empty($result['warning'])) {
                $result['warning'] = "You are about to create up to {$totalPotentialAssignments} assignments.";
            } else {
                $result['warning'] .= " You are about to create up to {$totalPotentialAssignments} assignments.";
            }
        }
        
        return $result;
    }

    /**
     * Check teacher workload and potential scheduling conflicts
     * 
     * @return string|null
     */
    private function checkTeacherWorkload()
    {
        $warnings = [];
        $maxRecommendedAssignments = 20;
        $teacherIds = [];
        
        // Get all unique teacher IDs from mappings
        foreach ($this->teacherSubjectMappings as $mapping) {
            if (!empty($mapping['teacherId']) && !in_array($mapping['teacherId'], $teacherIds)) {
                $teacherIds[] = $mapping['teacherId'];
            }
        }
        
        // Calculate current and potential new assignments for each teacher
        foreach ($teacherIds as $teacherId) {
            // Get current assignment count
            $currentAssignments = TeacherSubjectAssignment::where('teacher_id', $teacherId)
                ->where('academic_year_id', $this->bulkForm['academic_year_id'])
                ->where('academic_term', $this->bulkForm['academic_term'])
                ->count();
            
            // Count potential new assignments for this teacher
            $teacherMappingsCount = 0;
            foreach ($this->teacherSubjectMappings as $mapping) {
                if ($mapping['teacherId'] == $teacherId) {
                    $teacherMappingsCount++;
                }
            }
            
            $potentialNewAssignments = 0;
            if (!empty($this->bulkForm['section_ids'])) {
                $potentialNewAssignments = $teacherMappingsCount * count($this->bulkForm['section_ids']);
            } else {
                $potentialNewAssignments = $teacherMappingsCount * count($this->bulkForm['class_ids']);
            }
            
            $totalPotentialAssignments = $currentAssignments + $potentialNewAssignments;
            
            // Check if teacher will exceed recommended assignments
            if ($totalPotentialAssignments > $maxRecommendedAssignments) {
                // Get teacher name
                $teacher = User::find($teacherId);
                $teacherName = $teacher ? $teacher->name : "Teacher #{$teacherId}";
                
                $warnings[] = "{$teacherName} will have {$totalPotentialAssignments} assignments (currently has {$currentAssignments}).";
                
                // Check for scheduling conflicts
                $schedulingWarning = $this->checkSchedulingConflicts($teacherId);
                if ($schedulingWarning) {
                    $warnings[] = $schedulingWarning;
                }
            }
        }
        
        return !empty($warnings) ? implode(' ', $warnings) : null;
    }

    /**
     * Check potential scheduling conflicts for a teacher
     * 
     * @param int $teacherId
     * @return string|null
     */
    private function checkSchedulingConflicts(int $teacherId)
    {
        // Get teacher name
        $teacher = User::find($teacherId);
        $teacherName = $teacher ? $teacher->name : "Teacher #{$teacherId}";
        
        // Get existing classes/sections this teacher is assigned to
        $existingAssignments = TeacherSubjectAssignment::where('teacher_id', $teacherId)
            ->where('academic_year_id', $this->bulkForm['academic_year_id'])
            ->where('academic_term', $this->bulkForm['academic_term'])
            ->select('class_id', 'section_id')
            ->distinct()
            ->get();
            
        $existingClassSectionCount = $existingAssignments->count();
        
        // Count new unique class/section combinations from mappings
        $newClassSections = [];
        foreach ($this->teacherSubjectMappings as $mapping) {
            if ($mapping['teacherId'] == $teacherId) {
                if (empty($this->bulkForm['section_ids'])) {
                    foreach ($this->bulkForm['class_ids'] as $classId) {
                        $key = "{$classId}_null";
                        $newClassSections[$key] = true;
                    }
                } else {
                    foreach ($this->bulkForm['section_ids'] as $sectionId) {
                        // Get class ID for this section
                        $section = Section::find($sectionId);
                        if ($section) {
                            $classId = $section->my_class_id;
                            $key = "{$classId}_{$sectionId}";
                            $newClassSections[$key] = true;
                        }
                    }
                }
            }
        }
        
        $newClassSectionCount = count($newClassSections);
        $totalClassSectionCount = $existingClassSectionCount + $newClassSectionCount;
        
        // Warn if teacher has a high number of different classes/sections
        if ($totalClassSectionCount > 8) {
            return "{$teacherName} will be teaching in {$totalClassSectionCount} different classes/sections, which may cause scheduling conflicts.";
        }
        
        return null;
    }

    /**
     * Process bulk assignments after confirmation
     */
    public function processBulkAssignmentConfirmed()
    {
        try {
            \DB::beginTransaction();
            
            $total = 0;
            $created = 0;
            $skipped = 0;
            $errors = 0;
            
            // Process academic year format
            $academicYear = $this->bulkForm['academic_year_id'];
            if (strpos($academicYear, '-') !== false) {
                $parts = explode('-', $academicYear);
                if (count($parts) == 2) {
                    $academicYear = trim($parts[0]) . '-' . trim($parts[1]);
                }
            }
            
            // Process each teacher-subject mapping
            foreach ($this->teacherSubjectMappings as $mapping) {
                $teacherId = $mapping['teacherId'];
                $subjectId = $mapping['subjectId'];
                
                // If no sections selected, create assignments for all classes without sections
                if (empty($this->bulkForm['section_ids'])) {
                    foreach ($this->bulkForm['class_ids'] as $classId) {
                        $total++;
                        
                        // Check if assignment already exists
                        $exists = TeacherSubjectAssignment::where([
                            'teacher_id' => $teacherId,
                            'subject_id' => $subjectId,
                            'class_id' => $classId,
                            'section_id' => null,
                            'academic_year_id' => $academicYear,
                            'academic_term' => $this->bulkForm['academic_term'],
                        ])->exists();
                        
                        if ($exists && !$this->bulkForm['override_existing']) {
                            $skipped++;
                            continue;
                        }
                        
                        // If overriding, delete existing assignment
                        if ($exists && $this->bulkForm['override_existing']) {
                            TeacherSubjectAssignment::where([
                                'teacher_id' => $teacherId,
                                'subject_id' => $subjectId,
                                'class_id' => $classId,
                                'section_id' => null,
                                'academic_year_id' => $academicYear,
                                'academic_term' => $this->bulkForm['academic_term'],
                            ])->delete();
                        }
                        
                        try {
                            // Verify this assignment is still valid after any previous assignments in this session
                            if (!$this->isValidAssignment($classId, null, $subjectId, $academicYear, $this->bulkForm['academic_term'])) {
                                $skipped++;
                                continue;
                            }
                            
                            // Handle primary assignments
                            if ($this->bulkForm['is_primary']) {
                                // Remove primary flag from any existing assignments for this subject/class/section
                                TeacherSubjectAssignment::where([
                                    'subject_id' => $subjectId,
                                    'class_id' => $classId,
                                    'section_id' => null,
                                    'academic_year_id' => $academicYear,
                                    'academic_term' => $this->bulkForm['academic_term'],
                                    'is_primary' => true,
                                ])->update(['is_primary' => false]);
                            }
                            
                            // Create the assignment
                            TeacherSubjectAssignment::create([
                                'teacher_id' => $teacherId,
                                'subject_id' => $subjectId,
                                'class_id' => $classId,
                                'section_id' => null,
                                'academic_year_id' => $academicYear,
                                'academic_term' => $this->bulkForm['academic_term'],
                                'is_primary' => $this->bulkForm['is_primary'],
                                'is_active' => $this->bulkForm['is_active'],
                                'notes' => $this->bulkForm['notes'],
                            ]);
                            
                            $created++;
                        } catch (\Exception $e) {
                            \Log::error('Error creating assignment: ' . $e->getMessage(), [
                                'teacher_id' => $teacherId,
                                'subject_id' => $subjectId,
                                'class_id' => $classId,
                                'section_id' => null,
                                'academic_year' => $academicYear,
                                'term' => $this->bulkForm['academic_term'],
                            ]);
                            
                            $errors++;
                        }
                    }
                } else {
                    // Create assignments for selected sections
                    foreach ($this->bulkForm['section_ids'] as $sectionId) {
                        $total++;
                        
                        // Get the class ID for this section
                        $section = Section::find($sectionId);
                        if (!$section) {
                            $errors++;
                            continue;
                        }
                        
                        $classId = $section->my_class_id;
                        
                        // Verify this is a valid class selection (section belongs to selected classes)
                        if (!in_array($classId, $this->bulkForm['class_ids'])) {
                            $skipped++;
                            continue;
                        }
                        
                        // Check if assignment already exists
                        $exists = TeacherSubjectAssignment::where([
                            'teacher_id' => $teacherId,
                            'subject_id' => $subjectId,
                            'class_id' => $classId,
                            'section_id' => $sectionId,
                            'academic_year_id' => $academicYear,
                            'academic_term' => $this->bulkForm['academic_term'],
                        ])->exists();
                        
                        if ($exists && !$this->bulkForm['override_existing']) {
                            $skipped++;
                            continue;
                        }
                        
                        // If overriding, delete existing assignment
                        if ($exists && $this->bulkForm['override_existing']) {
                            TeacherSubjectAssignment::where([
                                'teacher_id' => $teacherId,
                                'subject_id' => $subjectId,
                                'class_id' => $classId,
                                'section_id' => $sectionId,
                                'academic_year_id' => $academicYear,
                                'academic_term' => $this->bulkForm['academic_term'],
                            ])->delete();
                        }
                        
                        try {
                            // Verify this assignment is still valid after any previous assignments in this session
                            if (!$this->isValidAssignment($classId, $sectionId, $subjectId, $academicYear, $this->bulkForm['academic_term'])) {
                                $skipped++;
                                continue;
                            }
                            
                            // Handle primary assignments
                            if ($this->bulkForm['is_primary']) {
                                // Remove primary flag from any existing assignments for this subject/class/section
                                TeacherSubjectAssignment::where([
                                    'subject_id' => $subjectId,
                                    'class_id' => $classId,
                                    'section_id' => $sectionId,
                                    'academic_year_id' => $academicYear,
                                    'academic_term' => $this->bulkForm['academic_term'],
                                    'is_primary' => true,
                                ])->update(['is_primary' => false]);
                            }
                            
                            // Create the assignment
                            TeacherSubjectAssignment::create([
                                'teacher_id' => $teacherId,
                                'subject_id' => $subjectId,
                                'class_id' => $classId,
                                'section_id' => $sectionId,
                                'academic_year_id' => $academicYear,
                                'academic_term' => $this->bulkForm['academic_term'],
                                'is_primary' => $this->bulkForm['is_primary'],
                                'is_active' => $this->bulkForm['is_active'],
                                'notes' => $this->bulkForm['notes'],
                            ]);
                            
                            $created++;
                        } catch (\Exception $e) {
                            \Log::error('Error creating assignment: ' . $e->getMessage(), [
                                'teacher_id' => $teacherId,
                                'subject_id' => $subjectId,
                                'class_id' => $classId,
                                'section_id' => $sectionId,
                                'academic_year' => $academicYear,
                                'term' => $this->bulkForm['academic_term'],
                            ]);
                            
                            $errors++;
                        }
                    }
                }
            }
            
            // Commit the transaction
            \DB::commit();
            
            // Update statistics
            $this->calculateStatistics();
            
            // Update results
            $this->bulkAssignmentResults = [
                'created' => $created,
                'skipped' => $skipped,
                'errors' => $errors,
                'total' => $total,
            ];
            
            $this->showBulkResults = true;
            
            // Display success message
            $this->dispatch('toast', [
                'type' => 'success', 
                'message' => "Successfully created $created teacher-subject assignments."
            ]);
            
        } catch (\Exception $e) {
            // Rollback on error
            \DB::rollBack();
            
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Error creating assignments: ' . $e->getMessage()
            ]);
            
            \Log::error('Bulk assignment error: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if an assignment is valid in the current context
     * 
     * @param int $classId
     * @param int|null $sectionId
     * @param int $subjectId
     * @param string $academicYear
     * @param string $academicTerm
     * @return bool
     */
    private function isValidAssignment($classId, $sectionId, $subjectId, $academicYear, $academicTerm)
    {
        // Check if this is a primary assignment and there's already a primary teacher
        if ($this->bulkForm['is_primary']) {
            $existsPrimary = TeacherSubjectAssignment::where([
                'subject_id' => $subjectId,
                'class_id' => $classId,
                'section_id' => $sectionId,
                'academic_year_id' => $academicYear,
                'academic_term' => $academicTerm,
                'is_primary' => true
            ])->exists();
            
            // Skip if a primary teacher already exists and we're not overriding
            if ($existsPrimary && !$this->bulkForm['override_existing']) {
                return false;
            }
        }
        
        // Check if assignment would create multiple primary teachers for a subject
        if ($this->bulkForm['is_primary']) {
            // Count existing primary teachers for this subject (excluding ones that would be overridden)
            $existingCount = TeacherSubjectAssignment::where([
                'subject_id' => $subjectId,
                'class_id' => $classId,
                'section_id' => $sectionId,
                'academic_year_id' => $academicYear,
                'academic_term' => $academicTerm,
                'is_primary' => true
            ])->count();
            
            // If there's already a primary teacher and we're not overriding, this isn't valid
            if ($existingCount > 0 && !$this->bulkForm['override_existing']) {
                return false;
            }
        }
        
        // If we made it here, the assignment is valid
        return true;
    }

    /**
     * Toggle select all assignments
     */
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            // Get all assignment IDs that match current filters
            $query = TeacherSubjectAssignment::query();
            
            // Apply filters
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
            
            $this->selectedAssignments = $query->pluck('id')->map(function($id) {
                return (string) $id;
            })->toArray();
        } else {
            $this->selectedAssignments = [];
        }
    }
    
    /**
     * Open bulk delete modal
     */
    public function openBulkDeleteModal()
    {
        if (count($this->selectedAssignments) === 0) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Please select at least one assignment to delete.'
            ]);
            return;
        }
        
        $this->showBulkDeleteResults = false;
        $this->isBulkDeleteModalOpen = true;
    }
    
    /**
     * Close bulk delete modal
     */
    public function closeBulkDeleteModal()
    {
        $this->isBulkDeleteModalOpen = false;
        $this->bulkDeleteResults = [
            'deleted' => 0,
            'failed' => 0,
            'total' => 0,
        ];
    }
    
    /**
     * Delete assignments in bulk
     */
    public function bulkDelete()
    {
        if (count($this->selectedAssignments) === 0) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'No assignments selected for deletion.'
            ]);
            return;
        }
        
        try {
            $deleted = 0;
            $failed = 0;
            $total = count($this->selectedAssignments);
            
            // Begin transaction for bulk operation
            \DB::beginTransaction();
            
            foreach ($this->selectedAssignments as $id) {
                try {
                    $assignment = TeacherSubjectAssignment::find($id);
                    if ($assignment) {
                        $assignment->delete();
                        $deleted++;
                    } else {
                        $failed++;
                    }
                } catch (\Exception $e) {
                    \Log::error("Failed to delete assignment #$id: " . $e->getMessage());
                    $failed++;
                }
            }
            
            // Commit the transaction
            \DB::commit();
            
            // Update results
            $this->bulkDeleteResults = [
                'deleted' => $deleted,
                'failed' => $failed,
                'total' => $total,
            ];
            
            $this->showBulkDeleteResults = true;
            $this->selectedAssignments = [];
            $this->selectAll = false;
            
            // Display success message
            $this->dispatch('toast', [
                'type' => 'success', 
                'message' => "Successfully deleted $deleted assignments."
            ]);
            
        } catch (\Exception $e) {
            // Rollback on error
            \DB::rollBack();
            
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Error during bulk deletion: ' . $e->getMessage()
            ]);
            
            \Log::error('Bulk deletion error: ' . $e->getMessage());
        }
    }
    
    /**
     * Export assignments to Excel
     */
    public function exportToExcel()
    {
        try {
            $query = $this->getFilteredQuery();
            
            // Create export with filtered data
            return Excel::download(new TeacherAssignmentsExport($query), 'teacher-assignments.xlsx');
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Failed to export data: ' . $e->getMessage()
            ]);
            
            \Log::error('Excel export error: ' . $e->getMessage());
        }
    }
    
    /**
     * Export assignments to PDF
     */
    public function exportToPdf()
    {
        try {
            $query = $this->getFilteredQuery();
            $assignments = $query->with(['teacher', 'subject', 'myClass', 'section'])->get();
            
            $pdf = PDF::loadView('exports.teacher-assignments-pdf', [
                'assignments' => $assignments,
                'academicYear' => $this->academicYear,
                'academicTerm' => $this->academicTerm,
            ]);
            
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, 'teacher-assignments.pdf');
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Failed to export PDF: ' . $e->getMessage()
            ]);
            
            \Log::error('PDF export error: ' . $e->getMessage());
        }
    }
    
    /**
     * Get filtered query based on current filters
     */
    private function getFilteredQuery()
    {
        $query = TeacherSubjectAssignment::query();
        
        // Get current academic year from settings if not specified
        if (empty($this->academicYear)) {
            $currentSession = $this->getSettingRepo()->getSetting('current_session')->first();
            $this->academicYear = $currentSession ? $currentSession->description : '2023-2024';
        }
        
        // Apply academic year filter
        $query->where('academic_year_id', $this->academicYear);
        
        // Apply academic term filter - use Term 1 as default if not set
        if (empty($this->academicTerm)) {
            $this->academicTerm = 'Term 1';
        }
        $query->where('academic_term', $this->academicTerm);
        
        // Apply optional filters only if they are set and not empty
        if (!empty($this->classId)) {
            $query->where('class_id', $this->classId);
        }
        
        if (!empty($this->sectionId)) {
            $query->where('section_id', $this->sectionId);
        }
        
        if (!empty($this->subjectId)) {
            $query->where('subject_id', $this->subjectId);
        }
        
        if (!empty($this->teacherId)) {
            $query->where('teacher_id', $this->teacherId);
        }
        
        if (!$this->showInactive) {
            $query->where('is_active', true);
        }
        
        // For specific selection of assignments
        if (!empty($this->selectedAssignments)) {
            $query->whereIn('id', $this->selectedAssignments);
        }
        
        // Log the SQL query being executed (for debugging)
        $bindings = $query->getBindings();
        $sql = str_replace('?', "'%s'", $query->toSql());
        $sql = vsprintf($sql, $bindings);
        \Log::info('Assignment query', [
            'sql' => $sql,
            'academicYear' => $this->academicYear,
            'academicTerm' => $this->academicTerm
        ]);
        
        return $query;
    }

    /**
     * Handle updates to the class_ids field in the bulk form
     */
    public function updatedBulkFormClassIds($value)
    {
        // Set a flag to show loading indicator
        $this->processingSections = true;
        $this->availableSections = collect(); // Initialize as a collection
        
        // Clear previous section selections whenever the classes change
        $this->bulkForm['section_ids'] = [];
        
        // Debug log the class_ids to make sure we're receiving them
        \Log::info('Class IDs updated for bulk assignment', [
            'class_ids' => $this->bulkForm['class_ids'],
            'value' => $value,
            'has_class_ids' => !empty($this->bulkForm['class_ids']),
            'class_ids_count' => count($this->bulkForm['class_ids'])
        ]);
        
        if (!empty($this->bulkForm['class_ids'])) {
            try {
                $availableSections = collect();
                
                // Load sections for each selected class
                foreach ($this->bulkForm['class_ids'] as $classId) {
                    $class = MyClass::find($classId);
                    if ($class) {
                        // Use the relationship defined in MyClass model
                        $sections = $class->sections()->orderBy('name')->get();
                        if ($sections->count() > 0) {
                            $availableSections->put($classId, $sections);
                        }
                    }
                }
                
                $this->availableSections = $availableSections;
                
                $totalSections = $availableSections->flatten(1)->count();
                
                \Log::info('Loaded sections for bulk assignment', [
                    'class_ids' => $this->bulkForm['class_ids'],
                    'sections_count' => $totalSections,
                    'grouped_count' => $availableSections->count()
                ]);
                
                // User feedback
                if ($totalSections > 0) {
                    $this->dispatch('toast', [
                        'type' => 'success', 
                        'message' => "Loaded {$totalSections} sections for selected classes"
                    ]);
                } else {
                    $this->dispatch('toast', [
                        'type' => 'warning', 
                        'message' => "No sections found for the selected classes. Please select classes that have sections."
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Error loading sections for bulk assignment', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // User feedback
                $this->dispatch('toast', [
                    'type' => 'error', 
                    'message' => 'Error loading sections: ' . $e->getMessage()
                ]);
            }
        } else {
            // Reset sections when no classes are selected
            $this->bulkForm['section_ids'] = [];
            $this->availableSections = collect();
            
            // User feedback
            $this->dispatch('toast', [
                'type' => 'info', 
                'message' => 'No classes selected, sections cleared'
            ]);
        }
        
        // Turn off the loading indicator
        $this->processingSections = false;
    }

    /**
     * Add a new teacher-subject mapping
     */
    public function addTeacherSubjectMapping()
    {
        // Initialize the array if it's not already
        if (!is_array($this->teacherSubjectMappings)) {
            $this->teacherSubjectMappings = [];
        }
        
        // Find the next available index
        $mappingId = 0;
        
        // If there are existing mappings, find the max index and add 1
        if (count($this->teacherSubjectMappings) > 0) {
            $mappingId = max(array_keys($this->teacherSubjectMappings)) + 1;
        }
        
        // Add the new mapping
        $this->teacherSubjectMappings[$mappingId] = [
            'teacherId' => '',
            'subjectId' => ''
        ];
        
        // Debug logging
        \Log::info('Added new teacher-subject mapping', [
            'mappingId' => $mappingId,
            'mappingsCount' => count($this->teacherSubjectMappings)
        ]);
        
        // Show a notification
        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'Added new teacher-subject mapping'
        ]);
    }

    /**
     * Get the total subject count for a class
     * 
     * @param int $classId
     * @return int
     */
    private function getSubjectCountForClass($classId)
    {
        // In a real implementation, this would query the curriculum/subjects for the class
        // For now, we'll use a placeholder that returns a default value
        
        // Get all subjects associated with this class
        $subjectCount = Subject::whereHas('classes', function ($query) use ($classId) {
            $query->where('my_class_id', $classId);
        })->count();
        
        // Return the count or a minimum default value
        return max($subjectCount, 8);
    }

    /**
     * Debug method for sections
     */
    public function debugSections()
    {
        if ($this->availableSections && $this->availableSections->count() > 0) {
            $sectionInfo = [];
            
            foreach ($this->availableSections as $classId => $sections) {
                $sectionInfo[] = [
                    'class_id' => $classId,
                    'class_name' => $this->classes->firstWhere('id', $classId)->name ?? 'Unknown',
                    'sections_count' => $sections->count(),
                    'first_section' => $sections->first() ? [
                        'id' => $sections->first()->id,
                        'name' => $sections->first()->name
                    ] : null
                ];
            }
            
            Log::info('Section debugging', [
                'section_info' => $sectionInfo,
                'total_classes' => $this->availableSections->count(),
                'total_sections' => $this->availableSections->flatten()->count()
            ]);
            
            $this->dispatch('toast', [
                'type' => 'info',
                'message' => 'Sections debug info logged. Check Laravel logs.'
            ]);
        } else {
            Log::info('No sections available to debug', [
                'bulk_form_class_ids' => $this->bulkForm['class_ids'] ?? []
            ]);
            
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'No sections available to debug.'
            ]);
        }
    }
}
