<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Models\ExamMarks;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\GradingSystem;
use App\Models\GradingRange;
use App\Helpers\Qs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ExamClassSection;

class ModernExamManagement extends Component
{
    use WithPagination, WithFileUploads, LivewireAlert;

    protected $paginationTheme = 'tailwind';

    // Error handling
    public $errorInfo = null;

    // Tab management
    public $activeTab = 'exams';
    
    // Active card tracking
    public $activeCard = null; // Possible values: 'examForm', 'examDetails', 'gradingForm', 'gradingDetails', 'confirmDelete'

    // Exam properties
    public $examId;
    public $examName;
    public $examYear;
    public $examTerm;
    public $gradingSystemId;
    public $selectedClasses = [];
    public $selectedSectionsByClass = [];
    public $instructions;
    public $exam_date;
    public $start_time;
    public $end_time;
    public $duration;

    // Modal states - ensure these match the blade template
    public $showExamFormModal = false;
    public $showDetailsModal = false;
    public $showGradingSystemFormModal = false;
    public $showGradingSystemDetailsModal = false;
    public $isCreating = false;
    public $isEditing = false;
    public $showExamDetails = false;
    public $showGradingForm = false;
    public $showGradingDetails = false;
    public $confirmingDelete = false;
    public $isEditingGradingSystem = false;
    public $confirmDeleteType = 'exam'; // Type of item being deleted: 'exam' or 'gradingSystem'

    // Selected items
    public $selectedExam;
    public $selectedGradingSystem;
    public $selectedClass;
    public $selectedSections = [];
    public $selectAllSections = false;
    
    // Grade Ranges for Grading System
    public $gradeRanges = [];

    // Grading System Form
    public $gradingSystemName;
    public $gradingSystemDescription;
    public $gradingSystemEffectiveDate;

    // Filters
    public $search = '';
    public $filterYear;
    public $filterTerm;
    public $filterGrading;

    // Pagination
    public $perPage = 10;

    // Sorting
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // School ID for superadmin
    public $schoolId;

    // Collection properties
    public $classes;
    public $subjects;
    public $sections = [];
    public $gradingSystems;

    // Terms list
    public $terms = [
        1 => 'First Term',
        2 => 'Second Term',
        3 => 'Third Term',
    ];

    // Protected listeners
    protected $listeners = [
        'refreshExams' => '$refresh',
        'confirmDelete',
        'deleteExam',
    ];

    /**
     * Component mount method
     */
    public function mount()
    {
        try {
            // Check permissions
            $this->checkPermissions();

            // Initialize data
            $this->initializeData();

            // Set current year as default
            $this->examYear = date('Y');

            // Set school ID for superadmin
            if (Qs::userIsTeamSA()) {
                $this->schoolId = session('admin_school_id', Auth::user()->school_id);
            }
        } catch (\Exception $e) {
            $this->errorInfo = "Error initializing component: " . $e->getMessage();
        }
    }

    /**
     * Check if user has permissions to access this component
     */
    private function checkPermissions()
    {
        // Redirect if not authorized to manage exams
        if (!Qs::isAdministratorOrTeacher() && !Qs::isStudent() && !Qs::isParent()) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page');
        }
    }

    /**
     * Initialize component data
     */
    private function initializeData()
    {
        // Load classes
        $this->classes = MyClass::orderBy('name')->get();

        // Load grading systems
        $this->gradingSystems = GradingSystem::orderBy('name')->get();

        // Load subjects
        $this->subjects = Subject::orderBy('subject_name')->get();
    }

    /**
     * Reset exam form fields (does not affect card states)
     */
    public function resetForm()
    {
        // Reset all form fields related to exams
        $this->reset([
            'examId', 'examName', 'examTerm', 'examYear', 'gradingSystemId',
            'selectedClasses', 'selectedSectionsByClass', 'selectedClass', 'selectedSections',
            'selectAllSections', 'showExamFormModal', 'instructions', 
            'exam_date', 'start_time', 'end_time', 'duration'
        ]);

        // Reset validation errors
        $this->resetValidation();
    }

    /**
     * Reset all card states to ensure only one card is visible at a time
     */
    public function resetCardStates()
    {
        // Reset all card state properties explicitly
        $this->isCreating = false;
        $this->isEditing = false;
        $this->showExamDetails = false;
        $this->showGradingForm = false;
        $this->showGradingDetails = false;
        $this->confirmingDelete = false;
        $this->isEditingGradingSystem = false;
        $this->selectedExam = null;
        $this->selectedGradingSystem = null;
    }

    /**
     * Switch active tab
     */
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Create new exam - opens the card
     */
    public function create()
    {
        // Reset form fields
        $this->resetForm();
        
        // Reset all card states first to ensure no other cards are visible
        $this->resetCardStates();
        
        // Then set the desired state
        $this->isCreating = true;
        $this->examYear = date('Y'); // Set current year
        $this->examTerm = 1; // Default to first term

        // Dispatch events to initialize tooltips and select2
        $this->dispatch('contentChanged');
        $this->dispatch('initializeSelect2');
    }

    /**
     * Save the exam data
     */
    public function store()
    {
        try {
            // Validate the exam data
            $this->validate([
                'examName' => 'required|string|max:100',
                'examYear' => 'required|numeric',
                'examTerm' => 'required',
                'gradingSystemId' => 'nullable|exists:grading_systems,id',
                'selectedClass' => 'required|exists:my_classes,id'
            ]);

            $examData = [
                'name' => $this->examName,
                'year' => $this->examYear,
                'term' => $this->examTerm,
                'grading_system_id' => $this->gradingSystemId,
                'school_id' => Qs::userIsTeamSA() ? $this->schoolId : Auth::user()->school_id,
                'instructions' => $this->instructions
            ];

            // Add optional fields if they exist
            if ($this->exam_date) {
                $examData['exam_date'] = $this->exam_date;
            }

            if ($this->start_time) {
                $examData['start_time'] = $this->start_time;
            }

            if ($this->end_time) {
                $examData['end_time'] = $this->end_time;
            }

            // Create or update the exam
            if ($this->isEditing) {
                $exam = Exam::findOrFail($this->examId);
                $exam->update($examData);

                // Delete existing class sections for this exam
                ExamClassSection::where('exam_id', $exam->id)->delete();
            } else {
                $exam = Exam::create($examData);
            }

            // Create pivot data for class and section assignments
            if (!empty($this->selectedSections)) {
                // If sections are selected, create one record per section
                foreach ($this->selectedSections as $sectionId) {
                    ExamClassSection::create([
                        'exam_id' => $exam->id,
                        'class_id' => $this->selectedClass,
                        'section_id' => $sectionId
                    ]);
                }
            } else {
                // If no sections selected, create a record for just the class
                ExamClassSection::create([
                    'exam_id' => $exam->id,
                    'class_id' => $this->selectedClass,
                    'section_id' => null
                ]);
            }

            $action = $this->isEditing ? 'updated' : 'created';
            $this->alert('success', "Exam {$action} successfully", [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);

            // Reset form and state
            $this->resetForm();

        } catch (\Exception $e) {
            $this->alert('error', "Error: " . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }

    /**
     * Edit existing exam
     */
    public function edit($id)
    {
        try {
            // Reset all card states first to ensure no other cards are visible
            $this->resetCardStates();
            
            // Find the exam
            $exam = Exam::findOrFail($id);

            // Set form fields
            $this->examId = $id;
            $this->examName = $exam->name;
            $this->examYear = $exam->year;
            $this->examTerm = $exam->term;
            $this->gradingSystemId = $exam->grading_system_id;
            $this->instructions = $exam->instructions;
            $this->exam_date = $exam->exam_date;
            $this->start_time = $exam->start_time;
            $this->end_time = $exam->end_time;

            // Get class-section associations
            $examSchedules = ExamClassSection::where('exam_id', $id)->get();

            if ($examSchedules->isNotEmpty()) {
                // Get the first class (for simplicity)
                $this->selectedClass = $examSchedules->first()->class_id;

                // Load sections for this class
                $this->updateSections();

                // Set selected sections
                $this->selectedSections = $examSchedules
                    ->where('class_id', $this->selectedClass)
                    ->where('section_id', '!=', null)
                    ->pluck('section_id')
                    ->toArray();
            }

            // Set editing state
            $this->isEditing = true;

            // Dispatch events to initialize tooltips and select2
            $this->dispatch('contentChanged');
            $this->dispatch('initializeSelect2');
        } catch (\Exception $e) {
            $this->alert('error', "Error loading exam: " . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }

    /**
     * Show exam details
     */
    public function showDetails($id)
    {
        try {
            // Reset all card states first to ensure no other cards are visible
            $this->resetCardStates();
            
            // Load the exam with its relationships
            $this->selectedExam = Exam::with([
                'gradingSystem',
                'classes',
                'examSchedules',
                'examSchedules.myClass',
                'examSchedules.section',
                'examSchedules.subject'
            ])->findOrFail($id);
            
            // Show exam details card
            $this->showExamDetails = true;

            // Dispatch event to initialize tooltips
            $this->dispatch('contentChanged');
        } catch (\Exception $e) {
            $this->alert('error', "Error loading exam details: " . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }

    /**
     * Close exam details
     */
    public function closeDetails()
    {
        // Reset all card states to ensure no cards are visible
        $this->resetCardStates();
        
        // Dispatch event to refresh the UI
        $this->dispatch('contentChanged');
    }

    /**
     * Confirm delete exam
     */
    public function confirmDelete($id)
    {
        // Reset all card states first to ensure no other cards are visible
        $this->resetCardStates();
        
        $this->examId = $id;
        $this->confirmingDelete = true;
        $this->confirmDeleteType = 'exam';

        // Dispatch event to initialize tooltips
        $this->dispatch('contentChanged');
    }

    /**
     * Delete an exam and its associations
     */
    public function deleteExam()
    {
        try {
            // Find the exam
            $exam = Exam::findOrFail($this->examId);

            // Delete associated schedules first
            ExamClassSection::where('exam_id', $exam->id)->delete();

            // Delete the exam
            $exam->delete();

            $this->alert('success', 'Exam deleted successfully', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);

            $this->confirmingDelete = false;
            $this->reset('examId');

        } catch (\Exception $e) {
            $this->alert('error', "Error deleting exam: " . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }

    /**
     * Show grading system form
     */
    public function showGradingSystemForm()
    {
        // Reset all card states first to ensure no other cards are visible
        $this->resetCardStates();
        
        // Reset form fields
        $this->resetGradingSystemForm();
        
        // Set the specific state we need
        $this->showGradingForm = true;
        
        // Default values
        $this->gradingSystemEffectiveDate = date('Y-m-d');
        
        // Dispatch event to initialize tooltips
        $this->dispatch('contentChanged');
    }

    /**
     * Reset grading system form fields without affecting card states
     */
    public function resetGradingSystemForm()
    {
        $this->reset([
            'gradingSystemName', 'gradingSystemDescription', 'gradingSystemEffectiveDate',
            'gradeRanges', 'selectedSubjects'
        ]);
        
        // Initialize empty array for grade ranges
        $this->gradeRanges = [];
        
        // Reset validation errors
        $this->resetValidation();
    }

    /**
     * Show grading system details
     */
    public function showGradingSystemDetails($id)
    {
        try {
            // Reset all card states first to ensure no other cards are visible
            $this->resetCardStates();
            
            // Load the grading system with its relationships
            $this->selectedGradingSystem = GradingSystem::with(['gradingRanges', 'subjects', 'exams'])
                ->findOrFail($id);
            
            // Set the correct states for both approaches (card and modal)
            $this->showGradingDetails = true;
            $this->showGradingSystemDetailsModal = true;
            
            // Dispatch event to initialize tooltips and UI components
            $this->dispatch('contentChanged');
            $this->dispatch('open-modal', 'grading-system-details');
        } catch (\Exception $e) {
            $this->alert('error', "Error loading grading system details: " . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }

    /**
     * Close grading system details
     */
    public function closeGradingSystemDetails()
    {
        // Reset all card states to ensure no cards are visible
        $this->resetCardStates();
        
        // Also reset modal state
        $this->showGradingSystemDetailsModal = false;
        
        // Dispatch event to refresh the UI
        $this->dispatch('contentChanged');
    }

    /**
     * Selected subjects for the grading system
     */
    public $selectedSubjects = [];

    /**
     * Add a new empty grade range
     */
    public function addGradeRange()
    {
        $this->gradeRanges[] = [
            'grade' => '',
            'min_score' => '',
            'max_score' => '',
            'remark' => ''
        ];
        
        // Dispatch an event to notify the UI that a new range was added
        $this->dispatch('gradeRangeAdded', [
            'index' => count($this->gradeRanges) - 1
        ]);
    }

    /**
     * Remove a grade range at the specified index
     */
    public function removeGradeRange($index)
    {
        if (isset($this->gradeRanges[$index])) {
            unset($this->gradeRanges[$index]);
            $this->gradeRanges = array_values($this->gradeRanges); // Re-index the array
        }
    }

    /**
     * Apply a predefined grade range template
     */
    public function applyTemplate($template)
    {
        // Clear existing grade ranges
        $this->gradeRanges = [];

        switch ($template) {
            case 'standard':
                $this->gradeRanges = [
                    ['grade' => 'A', 'min_score' => 90, 'max_score' => 100, 'remark' => 'Excellent'],
                    ['grade' => 'B', 'min_score' => 80, 'max_score' => 89.99, 'remark' => 'Very Good'],
                    ['grade' => 'C', 'min_score' => 70, 'max_score' => 79.99, 'remark' => 'Good'],
                    ['grade' => 'D', 'min_score' => 60, 'max_score' => 69.99, 'remark' => 'Fair'],
                    ['grade' => 'E', 'min_score' => 50, 'max_score' => 59.99, 'remark' => 'Pass'],
                    ['grade' => 'F', 'min_score' => 0, 'max_score' => 49.99, 'remark' => 'Fail']
                ];
                break;
                
            case 'letter':
                $this->gradeRanges = [
                    ['grade' => 'A+', 'min_score' => 97, 'max_score' => 100, 'remark' => 'Outstanding'],
                    ['grade' => 'A', 'min_score' => 93, 'max_score' => 96.99, 'remark' => 'Excellent'],
                    ['grade' => 'A-', 'min_score' => 90, 'max_score' => 92.99, 'remark' => 'Very Excellent'],
                    ['grade' => 'B+', 'min_score' => 87, 'max_score' => 89.99, 'remark' => 'Very Good'],
                    ['grade' => 'B', 'min_score' => 83, 'max_score' => 86.99, 'remark' => 'Good'],
                    ['grade' => 'B-', 'min_score' => 80, 'max_score' => 82.99, 'remark' => 'Above Average'],
                    ['grade' => 'C+', 'min_score' => 77, 'max_score' => 79.99, 'remark' => 'Average'],
                    ['grade' => 'C', 'min_score' => 73, 'max_score' => 76.99, 'remark' => 'Satisfactory'],
                    ['grade' => 'C-', 'min_score' => 70, 'max_score' => 72.99, 'remark' => 'Below Average'],
                    ['grade' => 'D+', 'min_score' => 67, 'max_score' => 69.99, 'remark' => 'Poor'],
                    ['grade' => 'D', 'min_score' => 63, 'max_score' => 66.99, 'remark' => 'Very Poor'],
                    ['grade' => 'D-', 'min_score' => 60, 'max_score' => 62.99, 'remark' => 'Passing'],
                    ['grade' => 'F', 'min_score' => 0, 'max_score' => 59.99, 'remark' => 'Fail']
                ];
                break;
                
            case 'percentage':
                $this->gradeRanges = [
                    ['grade' => '90-100%', 'min_score' => 90, 'max_score' => 100, 'remark' => 'Excellent'],
                    ['grade' => '80-89%', 'min_score' => 80, 'max_score' => 89.99, 'remark' => 'Very Good'],
                    ['grade' => '70-79%', 'min_score' => 70, 'max_score' => 79.99, 'remark' => 'Good'],
                    ['grade' => '60-69%', 'min_score' => 60, 'max_score' => 69.99, 'remark' => 'Satisfactory'],
                    ['grade' => '50-59%', 'min_score' => 50, 'max_score' => 59.99, 'remark' => 'Pass'],
                    ['grade' => '0-49%', 'min_score' => 0, 'max_score' => 49.99, 'remark' => 'Fail']
                ];
                break;
                
            case 'gpa':
                $this->gradeRanges = [
                    ['grade' => 'A', 'min_score' => 90, 'max_score' => 100, 'remark' => 'Excellent', 'gpa' => 4.0],
                    ['grade' => 'B+', 'min_score' => 85, 'max_score' => 89.99, 'remark' => 'Very Good', 'gpa' => 3.5],
                    ['grade' => 'B', 'min_score' => 80, 'max_score' => 84.99, 'remark' => 'Good', 'gpa' => 3.0],
                    ['grade' => 'C+', 'min_score' => 75, 'max_score' => 79.99, 'remark' => 'Fairly Good', 'gpa' => 2.5],
                    ['grade' => 'C', 'min_score' => 70, 'max_score' => 74.99, 'remark' => 'Satisfactory', 'gpa' => 2.0],
                    ['grade' => 'D+', 'min_score' => 65, 'max_score' => 69.99, 'remark' => 'Fair', 'gpa' => 1.5],
                    ['grade' => 'D', 'min_score' => 60, 'max_score' => 64.99, 'remark' => 'Pass', 'gpa' => 1.0],
                    ['grade' => 'F', 'min_score' => 0, 'max_score' => 59.99, 'remark' => 'Fail', 'gpa' => 0.0]
                ];
                break;
        }
        
        // Dispatch event to notify that grade ranges were added
        $this->dispatch('gradeRangesUpdated');
    }

    /**
     * Save or update a grading system
     */
    public function saveGradingSystem()
    {
        // Validate the form data
        $this->validate([
            'gradingSystemName' => 'required|string|max:255',
            'gradingSystemDescription' => 'nullable|string',
            'gradingSystemEffectiveDate' => 'required|date',
            'gradeRanges' => 'required|array|min:1',
            'gradeRanges.*.grade' => 'required|string|max:10',
            'gradeRanges.*.min_score' => 'required|numeric|min:0|max:100',
            'gradeRanges.*.max_score' => 'required|numeric|min:0|max:100',
            'gradeRanges.*.remark' => 'nullable|string|max:255',
            'selectedSubjects' => 'nullable|array',
            'selectedSubjects.*' => 'exists:subjects,id',
        ]);

        try {
            // Track if we were in exam creation/edit mode before saving
            $wasInExamForm = $this->isCreating || $this->isEditing;
            $wasCreating = $this->isCreating;
            $wasEditing = $this->isEditing;
            
            DB::beginTransaction();
            
            if ($this->isEditingGradingSystem) {
                // Update existing grading system
                $gradingSystem = GradingSystem::findOrFail($this->gradingSystemId);
                $gradingSystem->update([
                    'name' => $this->gradingSystemName,
                    'description' => $this->gradingSystemDescription,
                    'effective_date' => $this->gradingSystemEffectiveDate,
                    'updated_by' => Auth::id(),
                ]);
                
                // Delete existing grade ranges
                GradingRange::where('grading_system_id', $gradingSystem->id)->delete();
                
                // Delete existing subject associations
                $gradingSystem->subjects()->detach();
                
                $message = 'Grading system updated successfully';
            } else {
                // Create new grading system
                $gradingSystem = GradingSystem::create([
                    'name' => $this->gradingSystemName,
                    'description' => $this->gradingSystemDescription,
                    'effective_date' => $this->gradingSystemEffectiveDate,
                    'created_by' => Auth::id(),
                    'school_id' => Qs::userIsTeamSA() ? $this->schoolId : Auth::user()->school_id,
                ]);
                
                $message = 'Grading system created successfully';
            }
            
            // Create grade ranges
            foreach ($this->gradeRanges as $range) {
                $gradeRangeData = [
                    'grading_system_id' => $gradingSystem->id,
                    'grade' => $range['grade'],
                    'min_score' => $range['min_score'],
                    'max_score' => $range['max_score'],
                    'remark' => $range['remark'] ?? null,
                ];
                
                // Add GPA if provided
                if (isset($range['gpa'])) {
                    $gradeRangeData['gpa'] = $range['gpa'];
                }
                
                GradingRange::create($gradeRangeData);
            }
            
            // Associate with specific subjects if selected
            if (!empty($this->selectedSubjects)) {
                // For each selected subject, create a pivot record
                foreach ($this->selectedSubjects as $subjectId) {
                    $gradingSystem->subjects()->attach($subjectId, [
                        'additional_rules' => null,
                        'override_parent_rules' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            
            DB::commit();
            
            // Update grading systems list
            $this->gradingSystems = GradingSystem::orderBy('name')->get();
            
            // If creating a new system while inside an exam form, set it as selected
            if (!$this->isEditingGradingSystem && $wasInExamForm) {
                $this->gradingSystemId = $gradingSystem->id;
            }
            
            // Reset the grading system form
            $this->resetGradingSystemForm();
            
            // First reset all card states
            $this->resetCardStates();
            
            // Then restore exam form state if we were in it before
            if ($wasInExamForm) {
                if ($wasCreating) {
                    $this->isCreating = true;
                } else if ($wasEditing) {
                    $this->isEditing = true;
                }
                
                // Ensure no other cards are shown
                $this->showGradingForm = false;
                $this->showGradingDetails = false;
                $this->showExamDetails = false;
                $this->confirmingDelete = false;
            }
            
            // Success message
            $this->alert('success', $message, [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            
            // Dispatch event to initialize tooltips
            $this->dispatch('contentChanged');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', 'Error: ' . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }
    
    /**
     * Edit an existing grading system
     */
    public function editGradingSystem($id)
    {
        try {
            // Reset all card states first to ensure no other cards are visible
            $this->resetCardStates();
            
            // Find the grading system with its relationships
            $gradingSystem = GradingSystem::with(['gradingRanges', 'subjects'])->findOrFail($id);
            
            // Reset form fields
            $this->resetGradingSystemForm();
            
            // Set form fields
            $this->gradingSystemId = $id;
            $this->gradingSystemName = $gradingSystem->name;
            $this->gradingSystemDescription = $gradingSystem->description;
            $this->gradingSystemEffectiveDate = $gradingSystem->effective_date;
            
            // Set selected subjects
            $this->selectedSubjects = $gradingSystem->subjects->pluck('id')->toArray();
            
            // Set editing state
            $this->isEditingGradingSystem = true;
            
            // Set grade ranges
            $this->gradeRanges = $gradingSystem->gradingRanges->map(function($range) {
                $data = [
                    'grade' => $range->grade,
                    'min_score' => $range->min_score,
                    'max_score' => $range->max_score,
                    'remark' => $range->remark
                ];
                
                // Add GPA if it exists
                if (!is_null($range->gpa)) {
                    $data['gpa'] = $range->gpa;
                }
                
                return $data;
            })->toArray();
            
            // Show form card
            $this->showGradingForm = true;
            
            // Dispatch event to initialize tooltips and other UI components
            $this->dispatch('contentChanged');
            $this->dispatch('gradeRangesUpdated');
            
        } catch (\Exception $e) {
            $this->alert('error', 'Error loading grading system: ' . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }
    
    /**
     * Confirm delete grading system
     */
    public function confirmDeleteGradingSystem($id)
    {
        // Reset all card states first to ensure no other cards are visible
        $this->resetCardStates();
        
        $this->gradingSystemId = $id;
        $this->confirmingDelete = true;
        $this->confirmDeleteType = 'gradingSystem';
        
        // Dispatch event to initialize tooltips
        $this->dispatch('contentChanged');
    }
    
    /**
     * Delete a grading system
     */
    public function deleteGradingSystem()
    {
        try {
            // Check if grading system is in use
            $gradingSystem = GradingSystem::with('exams')->findOrFail($this->gradingSystemId);
            
            if ($gradingSystem->exams->count() > 0) {
                throw new \Exception('Cannot delete grading system that is being used by exams');
            }
            
            // Delete grade ranges first
            GradingRange::where('grading_system_id', $gradingSystem->id)->delete();
            
            // Delete grading system
            $gradingSystem->delete();
            
            // Update grading systems list
            $this->gradingSystems = GradingSystem::orderBy('name')->get();
            
            $this->alert('success', 'Grading system deleted successfully', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            
            // Reset state
            $this->confirmingDelete = false;
            $this->showGradingDetails = false;
            $this->reset('gradingSystemId');
            
        } catch (\Exception $e) {
            $this->alert('error', 'Error: ' . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }

    /**
     * Updated method for selectedClass
     */
    public function updatedSelectedClass()
    {
        $this->updateSections();
        $this->selectedSections = [];
        $this->selectAllSections = false;
    }

    /**
     * Update sections based on selected class
     */
    private function updateSections()
    {
        if (!$this->selectedClass) {
            $this->sections = [];
            return;
        }

        $this->sections = Section::where('my_class_id', $this->selectedClass)
            ->orderBy('name')
            ->get();
    }

    /**
     * Toggle select all sections
     */
    public function toggleSelectAllSections()
    {
        if ($this->selectAllSections) {
            // Convert to collection if it's an array
            $sectionsCollection = is_array($this->sections) ? collect($this->sections) : $this->sections;
            $this->selectedSections = $sectionsCollection->pluck('id')->toArray();
        } else {
            $this->selectedSections = [];
        }
    }

    /**
     * Calculate end time based on start time and duration
     */
    public function calculateEndTime()
    {
        if ($this->start_time && $this->duration) {
            $startDateTime = Carbon::createFromFormat('H:i', $this->start_time);
            $endDateTime = $startDateTime->copy()->addMinutes($this->duration);
            $this->end_time = $endDateTime->format('H:i');
        }
    }

    /**
     * Set sort field and direction
     */
    public function setSortField($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Retry loading data (for error recovery)
     */
    public function retryLoadData()
    {
        $this->errorInfo = null;
        $this->mount();
    }

    /**
     * Render the component
     */
    public function render()
    {
        try {
            // Build the query
            $query = Exam::with([
                    'gradingSystem',
                    'classes',
                    'examSchedules' => function($query) {
                        $query->with(['myClass', 'section', 'subject']);
                    }
                ]);

            // Apply filters
            if ($this->search) {
                $query->where('name', 'like', '%' . $this->search . '%');
            }

            if ($this->filterYear) {
                $query->where('year', $this->filterYear);
            }

            if ($this->filterTerm) {
                $query->where('term', $this->filterTerm);
            }

            if ($this->filterGrading) {
                $query->where('grading_system_id', $this->filterGrading);
            }

            // Apply sorting
            $query->orderBy($this->sortField, $this->sortDirection);

            // Get paginated results
            $exams = $query->paginate($this->perPage);

            // Get unique years for filter dropdown
            $years = Exam::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

            return view('livewire.exams.modern-exam-management', [
                'exams' => $exams,
                'years' => $years,
                'terms' => $this->terms,
                'gradingSystems' => $this->gradingSystems,
            ]);
        } catch (\Exception $e) {
            $this->errorInfo = "Error rendering component: " . $e->getMessage();
            return view('livewire.exams.modern-exam-management', [
                'exams' => collect(),
                'years' => collect(),
                'terms' => $this->terms,
                'gradingSystems' => collect(),
            ]);
        }
    }
}