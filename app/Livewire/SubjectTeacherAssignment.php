<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use App\User;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeacherAssignmentsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class SubjectTeacherAssignment extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    // Basic properties
    public $teachers = [];
    public $subjects = [];
    public $classes = [];
    public $sections = [];
    public $academicYears = [];
    public $academicTerms = ['Term 1', 'Term 2', 'Term 3'];
    
    // Class/section selection
    public $classSections = [];
    public $classSectionId = null;
    
    // Properties for filtering
    public $classId;
    public $sectionId;
    public $academicYear;
    public $academicTerm;
    public $filterAcademicYear;
    public $search = '';
    public $activeFilter = 'all';
    public $primaryFilter = 'all';
    public $selectedIds = [];
    public $selectedAssignments = [];
    public $selectAll = false;
    public $perPage = 10;
    public $subjectId;
    public $teacherId;
    public $showInactive = false;
    
    // Statistics
    public $totalCount = 0;
    public $activeCount = 0;
    public $primaryCount = 0;
    public $teacherCount = 0;
    
    // Modal and form states
    public $isModalOpen = false;
    public $isBulkModalOpen = false;
    public $isBulkDeleteModalOpen = false;
    public $showFilters = false;
    public $isEditMode = false;
    public $currentAssignmentId = null;
    public $processingSections = false;
    public $showBulkResults = false;
    public $bulkAssignmentResults = [
        'created' => 0,
        'skipped' => 0,
        'errors' => 0,
        'total' => 0,
    ];
    public $availableSections = [];
    public $teacherSubjectMappings = [];
    public $showBulkDeleteResults = false;
    public $bulkDeleteResults = [
        'deleted' => 0,
        'failed' => 0,
        'total' => 0,
    ];
    
    // Form properties
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
    public $bulkForm = [
        'teacher_ids' => [],
        'subject_ids' => [],
        'selected_class_id' => '',
        'class_ids' => [],
        'section_ids' => [],
        'academic_year_id' => '',
        'academic_term' => '',
        'is_primary' => true,
        'is_active' => true,
        'notes' => '',
        'override_existing' => false,
    ];
    
    // Filter properties
    public $filterTeacher = '';
    public $filterSubject = '';
    public $filterClass = '';
    public $filterStatus = '';
    public $filterPrimary = '';
    
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
        // Initialize default form values or load from query parameters
        $this->classId = request()->query('class_id');
        $this->sectionId = request()->query('section_id');
        
        // Set default academic year and term
        $this->setupAcademicYears();
        
        // Get current academic year from settings
        $currentYear = date('Y');
        $this->academicYear = ($currentYear) . '-' . ($currentYear + 1);
        $this->academicTerm = 'Term 1';
        
        // Initialize form with defaults
        $this->form['academic_year_id'] = $this->academicYear;
        $this->form['academic_term'] = $this->academicTerm;
        
        // Optionally preselect class and section from URL parameters
        if ($this->classId) {
            $this->form['class_id'] = $this->classId;
            $this->loadSections();
            
            if ($this->sectionId) {
                $this->form['section_id'] = $this->sectionId;
            }
        }
        
        // Load data collections
        $this->loadCollections();
        $this->loadClassSections();
        
        // Set default per page value
        $this->perPage = 10;
        
        // Initialize statistics
        $this->calculateStatistics();
        
        // Log initial state
        \Log::info('SubjectTeacherAssignment component mounted', [
            'academicYear' => $this->academicYear,
            'academicTerm' => $this->academicTerm,
            'filterAcademicYear' => $this->filterAcademicYear,
            'currentSession' => $this->getSetting('current_session')->first() ? $this->getSetting('current_session')->first()->description : 'Not set'
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
        $sessions = $this->getSetting('session');
        
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
            $currentSession = $this->getSetting('current_session')->first();
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
        $currentSession = $this->getSetting('current_session')->first();
        
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
            $currentSession = $this->getSetting('current_session')->first();
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
     * Open the bulk assignment modal
     */
    public function openBulkModal()
    {
        // Reset validation and form
        $this->resetValidation();
        $this->resetBulkForm();
        
        // Set default values for the bulk form
        $this->bulkForm['academic_year_id'] = $this->academicYear;
        $this->bulkForm['academic_term'] = $this->academicTerm;
        
        // Initialize with a default teacher-subject mapping
        $this->teacherSubjectMappings = [
            1 => [
                'teacherId' => '',
                'subjectId' => ''
            ]
        ];
        
        // Make sure available sections is initialized as a collection
        $this->availableSections = collect();
        
        // Reset results display
        $this->showBulkResults = false;
        
        // Set processing sections flag to false
        $this->processingSections = false;
        
        // Open the modal
        $this->isBulkModalOpen = true;
        
        // Emit an event for debugging
        $this->dispatch('isBulkModalOpenChanged', true);
        
        // Log the action
        \Log::info('Bulk assignment modal opened', [
            'user_id' => auth()->id(),
            'timestamp' => now(),
            'modal_state' => $this->isBulkModalOpen
        ]);
    }

    /**
     * Close the bulk assignment modal
     */
    public function closeBulkModal()
    {
        // Close the modal
        $this->isBulkModalOpen = false;
        
        // Reset form data
        $this->resetBulkForm();
        
        // Emit an event for debugging
        $this->dispatch('isBulkModalOpenChanged', false);
        
        // Log the action
        \Log::info('Bulk assignment modal closed', [
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]);
    }

    /**
     * Reset bulk assignment form fields and related data
     */
    public function resetBulkForm()
    {
        // Initialize all bulk form fields with empty/default values
        $this->bulkForm = [
            'teacher_ids' => [],
            'subject_ids' => [],
            'selected_class_id' => '',
            'class_ids' => [],
            'section_ids' => [],
            'academic_year_id' => $this->academicYear ?? date('Y') . '-' . (date('Y') + 1),
            'academic_term' => $this->academicTerm ?? 'Term 1',
            'is_primary' => true,
            'is_active' => true,
            'notes' => '',
            'override_existing' => false,
        ];

        // Reset related data
        $this->teacherSubjectMappings = [];
        $this->availableSections = collect();
        $this->processingSections = false;
        
        // Reset results data
        $this->bulkAssignmentResults = [
            'created' => 0,
            'skipped' => 0,
            'errors' => 0,
            'total' => 0,
        ];
        $this->showBulkResults = false;
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
     * Handle updating the selected class in bulk form
     */
    public function updatedBulkFormSelectedClassId($classId)
    {
        // Reset section selection when changing class
        $this->bulkForm['section_ids'] = [];
        
        // Skip if no class selected
        if (empty($classId)) {
            $this->availableSections = collect();
            return;
        }
        
        // Show loading indicator
        $this->processingSections = true;
        
        try {
            // Load sections for the selected class
            $this->availableSections = collect();
            $sections = Section::where('my_class_id', $classId)->get();
            
            // Group sections by class ID
            if ($sections->count() > 0) {
                $this->availableSections = collect([$classId => $sections]);
            }
            
            // Log the loaded sections
            \Log::info("Loaded sections for class ID $classId", [
                'section_count' => $sections->count(),
            ]);
        } catch (\Exception $e) {
            // Log any errors
            \Log::error("Error loading sections: " . $e->getMessage());
        } finally {
            // Hide loading indicator
            $this->processingSections = false;
        }
    }

    /**
     * Select all sections for the selected class
     */
    public function selectAllSections()
    {
        if (empty($this->bulkForm['selected_class_id'])) {
            return;
        }
        
        $classId = $this->bulkForm['selected_class_id'];
        
        // Check if we have sections for this class
        if (!isset($this->availableSections[$classId])) {
            return;
        }
        
        // Select all section IDs for this class
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
     * Validate and save bulk assignments
     */
    public function saveBulkAssignments()
    {
        // Authorize the action
        if (!auth()->user()->hasRole(['admin', 'superadmin', 'teacher'])) {
            $this->notify('error', 'You do not have permission to perform bulk teacher assignments.');
            return;
        }
        
        // Validate basic form data
        $this->validate([
            'bulkForm.academic_year_id' => 'required',
            'bulkForm.academic_term' => 'required',
            'bulkForm.class_ids' => 'required|array|min:1',
            'teacherSubjectMappings' => 'required|array|min:1',
        ], [
            'teacherSubjectMappings.required' => 'Please add at least one teacher-subject pair.',
            'teacherSubjectMappings.min' => 'Please add at least one teacher-subject pair.',
            'bulkForm.class_ids.required' => 'Please select at least one class.',
            'bulkForm.class_ids.min' => 'Please select at least one class.',
            'bulkForm.academic_year_id.required' => 'Please select an academic year.',
            'bulkForm.academic_term.required' => 'Please select an academic term.',
        ]);
        
        // Validate each mapping has both teacher and subject
        foreach ($this->teacherSubjectMappings as $index => $mapping) {
            $this->validate([
                "teacherSubjectMappings.{$index}.teacherId" => 'required',
                "teacherSubjectMappings.{$index}.subjectId" => 'required',
            ], [
                "teacherSubjectMappings.{$index}.teacherId.required" => 'Please select a teacher.',
                "teacherSubjectMappings.{$index}.subjectId.required" => 'Please select a subject.',
            ]);
        }

        // Process bulk assignments
        $this->processBulkAssignmentConfirmed();
    }

    /**
     * Process bulk assignments after confirmation
     */
    public function processBulkAssignmentConfirmed()
    {
        try {
            // Debug info
            \Log::info('Processing bulk assignments', [
                'mappings' => $this->teacherSubjectMappings,
                'class_ids' => $this->bulkForm['class_ids'],
                'section_ids' => $this->bulkForm['section_ids'] ?? [],
                'academic_year' => $this->bulkForm['academic_year_id'],
                'academic_term' => $this->bulkForm['academic_term'],
                'is_primary' => $this->bulkForm['is_primary'],
                'is_active' => $this->bulkForm['is_active'],
                'override_existing' => $this->bulkForm['override_existing'],
            ]);
            
            // Initialize counters
            $created = 0;
            $skipped = 0;
            $errors = 0;
            $total = 0;
            
            DB::beginTransaction();
            
            // For each teacher-subject pair
            foreach ($this->teacherSubjectMappings as $mapping) {
                $teacherId = $mapping['teacherId'];
                $subjectId = $mapping['subjectId'];
                
                // Get teacher and subject names for logging
                $teacher = $this->teachers->firstWhere('id', $teacherId);
                $subject = $this->subjects->firstWhere('id', $subjectId);
                
                if (!$teacher || !$subject) {
                    $errors++;
                    $total++;
                    continue;
                }
                
                // Process each selected class
                foreach ($this->bulkForm['class_ids'] as $classId) {
                    // Find the class object
                    $class = $this->classes->firstWhere('id', $classId);
                    if (!$class) {
                        $errors++;
                        $total++;
                        continue;
                    }
                    
                    // If sections are selected, use only those for this class
                    $sectionsForClass = $this->availableSections[$classId] ?? collect();
                    $selectedSectionIds = $this->bulkForm['section_ids'];
                    
                    // If no sections are specified and the class has sections, skip
                    if (empty($selectedSectionIds) && $sectionsForClass->count() > 0) {
                        $skipped++;
                        $total++;
                        continue;
                    }
                    
                    // If no sections for this class, create a class-wide assignment
                    if ($sectionsForClass->count() === 0) {
                        $this->createOrUpdateAssignment(
                            $teacherId, 
                            $subjectId, 
                            $classId, 
                            null, 
                            $this->bulkForm['is_primary'],
                            $this->bulkForm['is_active'],
                            $this->bulkForm['override_existing'],
                            $total, 
                            $created, 
                            $skipped, 
                            $errors
                        );
                    } else {
                        // Process selected sections that belong to this class
                        $classSectionIds = $sectionsForClass->pluck('id')->toArray();
                        $sectionsToProcess = array_intersect($selectedSectionIds, $classSectionIds);
                        
                        // If no sections selected for this class, skip
                        if (empty($sectionsToProcess)) {
                            $skipped++;
                            $total++;
                            continue;
                        }
                        
                        // Process each selected section for this class
                        foreach ($sectionsToProcess as $sectionId) {
                            $this->createOrUpdateAssignment(
                                $teacherId, 
                                $subjectId, 
                                $classId, 
                                $sectionId, 
                                $this->bulkForm['is_primary'],
                                $this->bulkForm['is_active'],
                                $this->bulkForm['override_existing'],
                                $total, 
                                $created, 
                                $skipped, 
                                $errors
                            );
                        }
                    }
                }
            }
            
            DB::commit();
            
            // Update results
            $this->bulkAssignmentResults = [
                'created' => $created,
                'skipped' => $skipped,
                'errors' => $errors,
                'total' => $total,
            ];
            
            $this->showBulkResults = true;
            
            // Show toast message
            $this->notify(
                'success', 
                "Bulk assignment completed: {$created} created, {$skipped} skipped, {$errors} errors"
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            \Log::error('Error in bulk assignment: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Show error message
            $this->notify(
                'error', 
                "An error occurred during bulk assignment: " . $e->getMessage()
            );
        }
    }
    
    /**
     * Creates or updates a teacher-subject assignment
     */
    private function createOrUpdateAssignment($teacherId, $subjectId, $classId, $sectionId, $isPrimary, $isActive, $overrideExisting, &$total, &$created, &$skipped, &$errors)
    {
        $total++;
        
        try {
            // Skip if validation fails for this specific assignment
            if (!$this->isValidAssignment($classId, $sectionId, $subjectId, $this->bulkForm['academic_year_id'], $this->bulkForm['academic_term'])) {
                $skipped++;
                return;
            }
            
            // If primary and override is enabled, remove any existing primary assignments
            if ($isPrimary && $overrideExisting) {
                TeacherSubjectAssignment::where([
                    'subject_id' => $subjectId,
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                    'academic_year_id' => $this->bulkForm['academic_year_id'],
                    'academic_term' => $this->bulkForm['academic_term'],
                    'is_primary' => true
                ])->update(['is_primary' => false]);
            }
            
            // Check if assignment already exists
            $existing = TeacherSubjectAssignment::where([
                'teacher_id' => $teacherId,
                'subject_id' => $subjectId,
                'class_id' => $classId,
                'section_id' => $sectionId,
                'academic_year_id' => $this->bulkForm['academic_year_id'],
                'academic_term' => $this->bulkForm['academic_term'],
            ])->first();
            
            if ($existing) {
                // Update if exists
                $existing->update([
                    'is_primary' => $isPrimary,
                    'is_active' => $isActive,
                    'notes' => $this->bulkForm['notes'],
                ]);
            } else {
                // Create new assignment
                TeacherSubjectAssignment::create([
                    'teacher_id' => $teacherId,
                    'subject_id' => $subjectId,
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                    'academic_year_id' => $this->bulkForm['academic_year_id'],
                    'academic_term' => $this->bulkForm['academic_term'],
                    'is_primary' => $isPrimary,
                    'is_active' => $isActive,
                    'notes' => $this->bulkForm['notes'],
                ]);
            }
            
            $created++;
        } catch (\Exception $e) {
            \Log::error('Error creating/updating assignment: ' . $e->getMessage(), [
                'teacher_id' => $teacherId,
                'subject_id' => $subjectId,
                'class_id' => $classId,
                'section_id' => $sectionId,
            ]);
            $errors++;
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
            
            $this->selectedIds = $query->pluck('id')->map(function($id) {
                return (string) $id;
            })->toArray();
        } else {
            $this->selectedIds = [];
        }
    }
    
    /**
     * Open bulk delete modal
     */
    public function openBulkDeleteModal()
    {
        if (count($this->selectedIds) === 0) {
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
        $this->showBulkDeleteResults = false;
    }
    
    /**
     * Delete selected assignments in bulk
     */
    public function bulkDelete()
    {
        if (count($this->selectedIds) === 0) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'No assignments selected for deletion'
            ]);
            return;
        }
        
        try {
            $deleted = 0;
            $failed = 0;
            $total = count($this->selectedIds);
            
            // Begin transaction for bulk operation
            \DB::beginTransaction();
            
            foreach ($this->selectedIds as $id) {
                try {
                    $assignment = TeacherSubjectAssignment::find($id);
                    
                    if ($assignment) {
                        // Check if user has permission to delete
                        if (Gate::allows('delete', $assignment)) {
                        $assignment->delete();
                        $deleted++;
                        } else {
                            $failed++;
                        }
                    } else {
                        $failed++;
                    }
                } catch (\Exception $e) {
                    \Log::error('Error deleting assignment: ' . $e->getMessage(), [
                        'id' => $id,
                        'exception' => $e,
                    ]);
                    $failed++;
                }
            }
            
            // Commit transaction
            \DB::commit();
            
            // Update results
            $this->bulkDeleteResults = [
                'deleted' => $deleted,
                'failed' => $failed,
                'total' => $total,
            ];
            
            $this->showBulkDeleteResults = true;
            $this->selectedIds = [];
            $this->selectAll = false;
            
            // Dispatch toast
            $this->dispatch('toast', [
                'type' => 'success', 
                'message' => "Deleted {$deleted} assignments successfully" . ($failed > 0 ? ", {$failed} failed" : "")
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            \DB::rollBack();
            
            \Log::error('Bulk delete error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Error during bulk delete: ' . $e->getMessage()
            ]);
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
            $currentSession = $this->getSetting('current_session')->first();
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
        if (!empty($this->selectedIds)) {
            $query->whereIn('id', $this->selectedIds);
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
     * Add a new teacher-subject mapping
     */
    public function addTeacherSubjectMapping()
    {
        // Find the next available index
        $nextIndex = empty($this->teacherSubjectMappings) ? 1 : max(array_keys($this->teacherSubjectMappings)) + 1;
        
        // Add a new empty mapping
        $this->teacherSubjectMappings[$nextIndex] = [
            'teacherId' => '',
            'subjectId' => ''
        ];
        
        // Log the action
        \Log::info('Added new teacher-subject mapping', [
            'mappingId' => $nextIndex,
            'totalMappings' => count($this->teacherSubjectMappings)
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
                'bulk_form_class_ids' => $this->bulkForm['selected_class_id'] ?? []
            ]);
            
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'No sections available to debug.'
            ]);
        }
    }

    /**
     * Load all class sections (class + section combinations)
     */
    public function loadClassSections()
    {
        $this->classSections = DB::table('sections')
            ->join('my_classes', 'sections.my_class_id', '=', 'my_classes.id')
            ->select(
                'sections.id as id',
                'my_classes.id as class_id',
                'my_classes.name as class_name',
                'sections.name as section_name'
            )
            ->orderBy('my_classes.name')
            ->orderBy('sections.name')
            ->get();
            
        // Add class information to each section
        foreach ($this->classSections as $key => $section) {
            $this->classSections[$key]->class = (object)[
                'id' => $section->class_id,
                'name' => $section->class_name
            ];
            
            $this->classSections[$key]->section = (object)[
                'id' => $section->id,
                'name' => $section->section_name
            ];
        }
    }
    
    /**
     * Handle changes to classSectionId
     */
    public function updatedClassSectionId($value)
    {
        if (empty($value)) {
            return;
        }
        
        // Find the class section in the collection
        $classSection = collect($this->classSections)->firstWhere('id', $value);
        
        if ($classSection) {
            // Update the bulkForm with the selected class and section
            $this->bulkForm['selected_class_id'] = $classSection->class_id;
            $this->bulkForm['section_ids'] = [$value]; // Set the section ID
            
            // For debugging
            $this->dispatch('toast', [
                'type' => 'info', 
                'message' => "Selected class section: {$classSection->class_name} {$classSection->section_name}"
            ]);
        }
    }

    /**
     * Get settings from the database
     * 
     * @param string $key The setting key to get
     * @return \Illuminate\Support\Collection
     */
    protected function getSetting($key)
    {
        // This is a simplified version without the SettingRepo dependency
        // You should replace this with the appropriate way to get settings in your application
        
        // For 'session', return a collection of academic years
        if ($key === 'session') {
            return collect($this->academicYears);
        }
        
        // For 'current_session', return a collection with the current session
        if ($key === 'current_session') {
            $currentYear = date('Y');
            $currentAcademicYear = ($currentYear) . '-' . ($currentYear + 1);
            
            return collect([
                (object)[
                    'description' => $currentAcademicYear,
                    'is_current' => true
                ]
            ]);
        }
        
        // Default
        return collect([]);
    }

    /**
     * Display a notification to the user
     */
    public function notify($type, $message, $title = null)
    {
        // Dispatch toast event for displaying notifications
        $data = [
            'type' => $type,
            'message' => $message
        ];
        
        if ($title) {
            $data['title'] = $title;
        }
        
        $this->dispatch('toast', $data);
    }

    /**
     * Handle updating the selected classes in bulk form
     */
    public function updatedBulkFormClassIds($classIds)
    {
        // Reset section selection when changing classes
        $this->bulkForm['section_ids'] = [];
        
        // Skip if no classes selected
        if (empty($classIds)) {
            $this->availableSections = collect();
            return;
        }
        
        // Show loading indicator
        $this->processingSections = true;
        
        try {
            // Load sections for all selected classes
            $this->availableSections = collect();
            
            foreach ($classIds as $classId) {
                $sections = Section::where('my_class_id', $classId)->get();
                
                // Add sections to collection grouped by class ID
                if ($sections->count() > 0) {
                    $this->availableSections[$classId] = $sections;
                }
            }
            
            // Log the loaded sections
            \Log::info("Loaded sections for classes", [
                'class_ids' => $classIds,
                'section_count' => $this->availableSections->flatten(1)->count(),
            ]);
        } catch (\Exception $e) {
            // Log any errors
            \Log::error("Error loading sections: " . $e->getMessage());
        } finally {
            // Hide loading indicator
            $this->processingSections = false;
        }
    }
}


