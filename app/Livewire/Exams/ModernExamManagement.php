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

    // Selected items
    public $selectedExam;
    public $selectedGradingSystem;
    public $selectedClass;
    public $selectedSections = [];
    public $selectAllSections = false;

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
     * Reset all form fields
     */
    public function resetForm()
    {
        $this->reset([
            'examId', 'examName', 'examTerm', 'examYear', 'gradingSystemId',
            'selectedClasses', 'selectedSectionsByClass', 'selectedClass', 'selectedSections',
            'selectAllSections', 'isCreating', 'isEditing', 'showExamFormModal',
            'instructions', 'exam_date', 'start_time', 'end_time', 'duration'
        ]);

        // Reset validation errors
        $this->resetValidation();
    }

    /**
     * Switch active tab
     */
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Create new exam - opens the modal
     */
    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
        $this->examYear = date('Y'); // Set current year
        $this->examTerm = 1; // Default to first term
        $this->showExamFormModal = true;

        // Dispatch events to initialize tooltips and select2 in modal
        $this->dispatch('modalOpened');
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
            $this->showExamFormModal = false;

        } catch (\Exception $e) {
            $this->alert('error', "Error saving exam: " . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }

    /**
     * Load exam for editing
     */
    public function edit($id)
    {
        try {
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
            $this->isCreating = false;
            $this->isEditing = true;
            $this->showExamFormModal = true;

            // Dispatch events to initialize tooltips and select2 in modal
            $this->dispatch('modalOpened');
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
            // Load the exam with its relationships
            $this->selectedExam = Exam::with([
                'gradingSystem',
                'classes',
                'examSchedules',
                'examSchedules.myClass',
                'examSchedules.section',
                'examSchedules.subject'
            ])->findOrFail($id);

            $this->showDetailsModal = true;

            // Dispatch event to initialize tooltips in modal
            $this->dispatch('modalOpened');
        } catch (\Exception $e) {
            $this->alert('error', "Error loading exam details: " . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }

    /**
     * Close exam details modal
     */
    public function closeExamDetails()
    {
        $this->selectedExam = null;
        $this->showDetailsModal = false;
    }

    /**
     * Confirm delete exam
     */
    public function confirmDelete($id)
    {
        $this->examId = $id;
        $this->confirmingDelete = true;

        // Dispatch event to initialize tooltips in modal
        $this->dispatch('modalOpened');
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
        $this->showGradingSystemFormModal = true;

        // Dispatch event to initialize tooltips in modal
        $this->dispatch('modalOpened');
    }

    /**
     * Close grading system form
     */
    public function closeGradingSystemForm()
    {
        $this->showGradingSystemFormModal = false;
        $this->reset(['gradingSystemName', 'gradingSystemDescription', 'gradingSystemEffectiveDate']);
    }

    /**
     * Store new grading system
     */
    public function storeGradingSystem()
    {
        $this->validate([
            'gradingSystemName' => 'required|string|max:255',
            'gradingSystemDescription' => 'nullable|string',
            'gradingSystemEffectiveDate' => 'required|date',
        ]);

        try {
            $gradingSystem = GradingSystem::create([
                'name' => $this->gradingSystemName,
                'description' => $this->gradingSystemDescription,
                'effective_date' => $this->gradingSystemEffectiveDate,
                'created_by' => Auth::id(),
            ]);

            // Update grading systems list
            $this->gradingSystems = GradingSystem::orderBy('name')->get();

            // Set the newly created system as selected
            $this->gradingSystemId = $gradingSystem->id;

            // Close form
            $this->closeGradingSystemForm();

            // Success message
            $this->alert('success', 'Grading system created successfully', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);

        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred: ' . $e->getMessage(), [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
            ]);
        }
    }

    /**
     * Show grading system details
     */
    public function showGradingSystemDetails($id)
    {
        try {
            $this->selectedGradingSystem = GradingSystem::with(['gradingRanges.subject'])->findOrFail($id);
            $this->showGradingSystemDetailsModal = true;

            // Dispatch event to initialize tooltips in modal
            $this->dispatch('modalOpened');
        } catch (\Exception $e) {
            $this->alert('error', "Error loading grading system: " . $e->getMessage(), [
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
        $this->selectedGradingSystem = null;
        $this->showGradingSystemDetailsModal = false;
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
            $this->selectedSections = $this->sections->pluck('id')->toArray();
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