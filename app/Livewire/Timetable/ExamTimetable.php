<?php

namespace App\Livewire\Timetable;

use App\Models\Exam;
use App\Models\ExamClassSection;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use App\User;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;
use Illuminate\Support\Facades\DB;

class ExamTimetable extends Component
{
    use WithPagination, WireToast;

    // Main properties
    public $selectedExamId = null;
    public $selectedClassId = null;
    public $selectedSectionId = null;
    public $exams = [];
    public $classes = [];
    public $sections = [];
    public $sessions = [];
    public $terms = ['First Term', 'Second Term', 'Third Term'];
    public $selectedTerm = '';
    public $selectedSession = '';
    public $examSchedules = [];
    public $subjects = [];
    public $invigilators = [];
    public $allTeachers = [];
    
    // Configurations
    public $configBreakTime = 15; // minutes
    public $configShowInvigilators = true;
    public $configAllowPaperGrouping = false;
    public $configAutoAssignInvigilators = true;
    public $configPapersPerDay = 3;
    public $configStartTime = '08:00';
    public $configTimePerPaper = 120; // minutes
    public $configShowInstructions = true;
    public $configDaysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    public $configPrintableFormat = true;
    public $configInvigilatorsPerRoom = 2;
    public $configExcludeWeekends = true;
    public $configSchoolHeaderOnPrint = true;
    public $configPageOrientation = 'landscape';
    
    // Form properties
    public $showScheduleForm = false;
    public $scheduleForm = [
        'id' => null,
        'exam_id' => null,
        'class_id' => null,
        'section_id' => null,
        'subject_id' => null,
        'exam_date' => null,
        'start_time' => '',
        'end_time' => '',
        'instructions' => '',
        'venue' => '',
        'invigilators' => [],
    ];
    
    // Generation settings
    public $generationInProgress = false;
    public $generationSettings = [
        'start_date' => null,
        'papers_per_day' => 3,
        'start_time' => '08:00',
        'duration_minutes' => 120,
        'break_minutes' => 15,
        'exclude_weekends' => true,
        'randomize_subjects' => false,
        'auto_assign_invigilators' => true,
    ];
    
    // Protected properties
    protected $listeners = [
        'refreshExamTimetable' => '$refresh',
    ];
    
    // Validation rules
    protected $rules = [
        'scheduleForm.exam_id' => 'required|exists:exams,id',
        'scheduleForm.class_id' => 'required|exists:my_classes,id',
        'scheduleForm.section_id' => 'nullable|exists:sections,id',
        'scheduleForm.subject_id' => 'required|exists:subjects,id',
        'scheduleForm.exam_date' => 'required|date',
        'scheduleForm.start_time' => 'required',
        'scheduleForm.end_time' => 'required|after:scheduleForm.start_time',
        'scheduleForm.instructions' => 'nullable|string',
        'scheduleForm.venue' => 'nullable|string',
        'scheduleForm.invigilators' => 'nullable|array',
        'scheduleForm.invigilators.*' => 'exists:users,id',
    ];

    public function mount()
    {
        $this->classes = MyClass::orderBy('name')->get();
        $this->selectedTerm = $this->terms[0];
        
        // Get the academic sessions (last 5 years and next 5 years)
        $currentYear = date('Y');
        $years = range($currentYear - 5, $currentYear + 5);
        $this->sessions = [];
        
        foreach ($years as $year) {
            $nextYear = $year + 1;
            $this->sessions[] = "$year-$nextYear";
        }
        
        $this->selectedSession = "$currentYear-" . ($currentYear + 1);
        
        // Load default settings from configuration if available
        $this->loadSettings();
        
        // Load exams for current term and session
        $this->refreshAvailableExams();
        
        // Load all teachers for invigilator assignment
        $this->allTeachers = User::where('user_type', 'teacher')->get();
    }
    
    /**
     * Load user settings from configuration
     */
    private function loadSettings()
    {
        // In a real app, these settings could be loaded from user preferences or global settings
        // For now, we'll use default values
    }
    
    /**
     * Save user settings to configuration
     */
    public function saveSettings()
    {
        // In a real app, you would save these settings to user preferences or global settings
        session()->flash('info', 'Exam timetable settings saved successfully');
    }
    
    /**
     * Reset settings to default values
     */
    public function resetSettings()
    {
        $this->configBreakTime = 15;
        $this->configShowInvigilators = true;
        $this->configAllowPaperGrouping = false;
        $this->configAutoAssignInvigilators = true;
        $this->configPapersPerDay = 3;
        $this->configStartTime = '08:00';
        $this->configTimePerPaper = 120;
        $this->configShowInstructions = true;
        $this->configDaysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $this->configPrintableFormat = true;
        $this->configInvigilatorsPerRoom = 2;
        $this->configExcludeWeekends = true;
        $this->configSchoolHeaderOnPrint = true;
        $this->configPageOrientation = 'landscape';
        
        session()->flash('info', 'Exam timetable settings reset to defaults');
    }
    
    /**
     * Update handler for class selection
     */
    public function updatedSelectedClassId($value)
    {
        $this->selectedSectionId = null;
        $this->sections = $value ? Section::where('my_class_id', $value)->orderBy('name')->get() : [];
        
        // After updating class, we need to refresh exams for this class
        $this->refreshAvailableExams();
        
        // Then load schedules and subjects
        $this->loadExamSchedules();
        $this->loadSubjects();
    }
    
    /**
     * Update handler for exam selection
     */
    public function updatedSelectedExamId()
    {
        $this->loadExamSchedules();
    }
    
    /**
     * Update handler for section selection
     */
    public function updatedSelectedSectionId()
    {
        // After updating section, we need to refresh exams for this class/section
        $this->refreshAvailableExams();
        
        // Then load schedules
        $this->loadExamSchedules();
    }
    
    /**
     * Update handler for term selection
     */
    public function updatedSelectedTerm()
    {
        $this->refreshAvailableExams();
        $this->loadExamSchedules();
    }
    
    /**
     * Update handler for session selection
     */
    public function updatedSelectedSession()
    {
        $this->refreshAvailableExams();
        $this->loadExamSchedules();
    }
    
    /**
     * Load exams based on current term and session
     * @deprecated Use refreshAvailableExams instead
     */
    private function loadExams()
    {
        // This method is deprecated, forwarding to the new implementation
        $this->refreshAvailableExams();
    }
    
    /**
     * Refresh the list of available exams based on selected class/section
     */
    private function refreshAvailableExams()
    {
        // Base query for term and session
        $query = Exam::where('term', $this->selectedTerm)
            ->where('year', $this->selectedSession);
        
        // If a class is selected, filter for exams available to this class
        if ($this->selectedClassId) {
            $classId = $this->selectedClassId;
            $sectionId = $this->selectedSectionId;
            
            // Get IDs of exams that have schedules for this class/section
            // First try to get exams already scheduled for this class
            $scheduledExamIds = DB::table('exam_class_section')
                ->where('class_id', $classId)
                ->when($sectionId, function($q) use ($sectionId) {
                    $q->where('section_id', $sectionId);
                })
                ->distinct()
                ->pluck('exam_id')
                ->toArray();
            
            // If no exams are scheduled yet, show all exams for the term/session
            // This allows creating new schedules for any exam
            if (empty($scheduledExamIds)) {
                // Don't apply additional filters - show all exams for term/session
            } else {
                // Otherwise, only show exams already assigned to this class
                $query->whereIn('id', $scheduledExamIds);
            }
        }
        
        // Get the filtered exams
        $exams = $query->orderBy('name')->get();
        $this->exams = $exams;
        
        // Reset selected exam if it's no longer in the list
        $selectedExamExists = false;
        foreach ($exams as $exam) {
            if ($exam->id == $this->selectedExamId) {
                $selectedExamExists = true;
                break;
            }
        }
        
        if (!$selectedExamExists) {
            $this->selectedExamId = null;
        }
        
        // Set first exam as selected if nothing is selected
        if (!$this->selectedExamId && $exams->count() > 0) {
            $this->selectedExamId = $exams->first()->id;
        }
    }
    
    /**
     * Load exam schedules for the selected exam and class
     */
    private function loadExamSchedules()
    {
        if (!$this->selectedClassId || !$this->selectedExamId) {
            $this->examSchedules = collect();
            return;
        }
        
        $query = ExamClassSection::where('exam_id', $this->selectedExamId)
            ->where('class_id', $this->selectedClassId)
            ->with(['subject', 'myClass', 'section']) // Eager load relationships
            ->orderBy('exam_date', 'asc')
            ->orderBy('start_time', 'asc');
        
        if ($this->selectedSectionId) {
            $query->where('section_id', $this->selectedSectionId);
        }
        
        $this->examSchedules = $query->get();
    }
    
    /**
     * Load subjects for the selected class
     */
    private function loadSubjects()
    {
        if ($this->selectedClassId) {
            // Try to get subjects assigned to this class first
            $classSubjects = TeacherSubjectAssignment::where('class_id', $this->selectedClassId)
                ->when($this->selectedSectionId, function ($query) {
                    return $query->where('section_id', $this->selectedSectionId);
                })
                ->pluck('subject_id')
                ->toArray();
                
            // If we found subjects assigned to this class, use them
            if (!empty($classSubjects)) {
                $this->subjects = Subject::whereIn('id', $classSubjects)
                    ->orderBy('subject_name')
                    ->get();
                return;
            }
        }
        
        // Fallback: load all subjects if none are specifically assigned to this class
        $this->subjects = Subject::orderBy('subject_name')->get();
    }
    
    /**
     * Load invigilators (teachers assigned to this class/subject)
     */
    private function loadInvigilators()
    {
        if (!$this->selectedClassId || !$this->scheduleForm['subject_id']) {
            $this->invigilators = [];
            return;
        }
        
        // Get teachers assigned to this class and subject
        $primaryTeachers = TeacherSubjectAssignment::where('class_id', $this->selectedClassId)
            ->where('subject_id', $this->scheduleForm['subject_id'])
            ->where('is_active', true);
            
        if ($this->selectedSectionId) {
            $primaryTeachers->where('section_id', $this->selectedSectionId);
        }
        
        $primaryTeacherIds = $primaryTeachers->pluck('teacher_id')->toArray();
        
        // Now get all teachers who teach this subject in any class
        $subjectTeachers = TeacherSubjectAssignment::where('subject_id', $this->scheduleForm['subject_id'])
            ->where('is_active', true)
            ->whereNotIn('teacher_id', $primaryTeacherIds)
            ->pluck('teacher_id')
            ->toArray();
            
        $allTeacherIds = array_merge($primaryTeacherIds, $subjectTeachers);
        
        $this->invigilators = User::whereIn('id', $allTeacherIds)->get();
        
        // Pre-select primary teachers as invigilators
        if ($this->configAutoAssignInvigilators) {
            $this->scheduleForm['invigilators'] = array_slice($primaryTeacherIds, 0, $this->configInvigilatorsPerRoom);
            
            // If we need more invigilators, add from subject teachers
            if (count($this->scheduleForm['invigilators']) < $this->configInvigilatorsPerRoom) {
                $neededCount = $this->configInvigilatorsPerRoom - count($this->scheduleForm['invigilators']);
                $additionalTeachers = array_slice($subjectTeachers, 0, $neededCount);
                $this->scheduleForm['invigilators'] = array_merge($this->scheduleForm['invigilators'], $additionalTeachers);
            }
        }
    }
    
    /**
     * Open the schedule form to add a new exam schedule
     */
    public function openScheduleForm()
    {
        $this->showScheduleForm = true;
        $this->scheduleForm = [
            'id' => null,
            'exam_id' => $this->selectedExamId,
            'class_id' => $this->selectedClassId,
            'section_id' => $this->selectedSectionId,
            'subject_id' => null,
            'exam_date' => date('Y-m-d'),
            'start_time' => $this->configStartTime,
            'end_time' => $this->calculateEndTime($this->configStartTime, $this->configTimePerPaper),
            'instructions' => '',
            'venue' => '',
            'invigilators' => [],
        ];
    }
    
    /**
     * Close the schedule form
     */
    public function closeScheduleForm()
    {
        $this->showScheduleForm = false;
    }
    
    /**
     * Calculate the end time based on start time and duration
     */
    private function calculateEndTime($startTime, $durationMinutes)
    {
        $start = strtotime($startTime);
        $end = date('H:i', $start + ($durationMinutes * 60));
        return $end;
    }
    
    /**
     * Update the end time when the start time changes
     */
    public function updatedScheduleFormStartTime($value)
    {
        $this->scheduleForm['end_time'] = $this->calculateEndTime($value, $this->configTimePerPaper);
    }
    
    /**
     * Update handler for subject selection in form
     */
    public function updatedScheduleFormSubjectId()
    {
        $this->loadInvigilators();
    }
    
    /**
     * Save an exam schedule
     */
    public function saveSchedule()
    {
        $this->validate();
        
        // Either create a new record or update an existing one
        if ($this->scheduleForm['id']) {
            $schedule = ExamClassSection::find($this->scheduleForm['id']);
        } else {
            $schedule = new ExamClassSection();
        }
        
        // Save the schedule details
        $schedule->exam_id = $this->scheduleForm['exam_id'];
        $schedule->class_id = $this->scheduleForm['class_id'];
        $schedule->section_id = $this->scheduleForm['section_id'];
        $schedule->subject_id = $this->scheduleForm['subject_id'];
        $schedule->exam_date = $this->scheduleForm['exam_date'];
        $schedule->start_time = $this->scheduleForm['start_time'];
        $schedule->end_time = $this->scheduleForm['end_time'];
        $schedule->instructions = $this->scheduleForm['instructions'];
        $schedule->venue = $this->scheduleForm['venue'];
        $schedule->invigilators = $this->scheduleForm['invigilators'];
        
        $schedule->save();
        
        // Reload the schedules
        $this->loadExamSchedules();
        $this->closeScheduleForm();
        
        session()->flash('success', 'Exam schedule saved successfully');
    }
    
    /**
     * Edit an exam schedule
     */
    public function editSchedule($id)
    {
        $schedule = ExamClassSection::findOrFail($id);
        
        $this->scheduleForm = [
            'id' => $schedule->id,
            'exam_id' => $schedule->exam_id,
            'class_id' => $schedule->class_id,
            'section_id' => $schedule->section_id,
            'subject_id' => $schedule->subject_id,
            'exam_date' => $schedule->exam_date,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'instructions' => $schedule->instructions,
            'venue' => $schedule->venue,
            'invigilators' => $schedule->invigilators ?: [],
        ];
        
        // Load subjects and invigilators for the form
        $this->loadSubjects();
        $this->loadInvigilators();
        
        $this->showScheduleForm = true;
    }
    
    /**
     * Delete an exam schedule
     */
    public function deleteSchedule($id)
    {
        ExamClassSection::destroy($id);
        $this->loadExamSchedules();
        session()->flash('success', 'Exam schedule deleted successfully');
    }
    
    /**
     * Auto-generate exam schedule based on settings
     */
    public function openGenerationSettings()
    {
        // Initialize generation settings with defaults
        $this->generationSettings = [
            'start_date' => date('Y-m-d'),
            'papers_per_day' => $this->configPapersPerDay,
            'start_time' => $this->configStartTime,
            'duration_minutes' => $this->configTimePerPaper,
            'break_minutes' => $this->configBreakTime,
            'exclude_weekends' => $this->configExcludeWeekends,
            'randomize_subjects' => false,
            'auto_assign_invigilators' => $this->configAutoAssignInvigilators,
        ];
        
        $this->generationInProgress = true;
    }
    
    /**
     * Generate the exam timetable automatically
     */
    public function generateTimetable()
    {
        if (!$this->selectedExamId || !$this->selectedClassId) {
            session()->flash('error', 'Please select an exam and class first');
            return;
        }
        
        $this->loadSubjects();
        
        if (empty($this->subjects)) {
            session()->flash('error', 'No subjects found for this class');
            return;
        }
        
        $startDate = strtotime($this->generationSettings['start_date'] ?? date('Y-m-d'));
        $startTime = $this->generationSettings['start_time'];
        $durationMinutes = $this->generationSettings['duration_minutes'];
        $breakMinutes = $this->generationSettings['break_minutes'];
        $papersPerDay = $this->generationSettings['papers_per_day'];
        $excludeWeekends = $this->generationSettings['exclude_weekends'];
        $autoAssignInvigilators = $this->generationSettings['auto_assign_invigilators'];
        
        $subjects = collect($this->subjects);
        if ($this->generationSettings['randomize_subjects']) {
            $subjects = $subjects->shuffle();
        }
        
        // Clear existing schedules first
        ExamClassSection::where('exam_id', $this->selectedExamId)
            ->where('class_id', $this->selectedClassId)
            ->when($this->selectedSectionId, function($query) {
                $query->where('section_id', $this->selectedSectionId);
            })
            ->delete();
        
        // Prepare default venue
        $class = MyClass::find($this->selectedClassId);
        $defaultVenue = $class ? $class->name . ' Classroom' : 'Examination Hall';
        
        // Generate schedules for each subject
        $currentDate = $startDate;
        $currentPaperOfDay = 0;
        $currentTime = $startTime;
        
        foreach ($subjects as $subject) {
            // If we've reached the max papers per day, go to the next day
            if ($currentPaperOfDay >= $papersPerDay) {
                $currentDate = strtotime('+1 day', $currentDate);
                $currentPaperOfDay = 0;
                $currentTime = $startTime;
                
                // Skip weekends if needed
                if ($excludeWeekends) {
                    $dayOfWeek = date('w', $currentDate);
                    if ($dayOfWeek == 0) { // Sunday
                        $currentDate = strtotime('+1 day', $currentDate);
                    } else if ($dayOfWeek == 6) { // Saturday
                        $currentDate = strtotime('+2 days', $currentDate);
                    }
                }
            }
            
            // Calculate end time
            $endTime = $this->calculateEndTime($currentTime, $durationMinutes);
            
            // Find invigilators if auto-assign is enabled
            $invigilators = [];
            if ($autoAssignInvigilators) {
                // Find teachers for this subject and class
                $subjectTeachers = TeacherSubjectAssignment::where('subject_id', $subject->id)
                    ->where('class_id', $this->selectedClassId)
                    ->when($this->selectedSectionId, function($query) {
                        $query->where('section_id', $this->selectedSectionId);
                    })
                    ->where('is_active', true)
                    ->pluck('teacher_id')
                    ->toArray();
                
                // If there are no specific teachers for this subject/class/section,
                // get any teachers assigned to this subject
                if (empty($subjectTeachers)) {
                    $subjectTeachers = TeacherSubjectAssignment::where('subject_id', $subject->id)
                        ->where('is_active', true)
                        ->pluck('teacher_id')
                        ->take($this->configInvigilatorsPerRoom)
                        ->toArray();
                }
                
                $invigilators = $subjectTeachers;
            }
            
            // Create the schedule
            $schedule = new ExamClassSection();
            $schedule->exam_id = $this->selectedExamId;
            $schedule->class_id = $this->selectedClassId;
            $schedule->section_id = $this->selectedSectionId;
            $schedule->subject_id = $subject->id;
            $schedule->exam_date = date('Y-m-d', $currentDate);
            $schedule->start_time = $currentTime;
            $schedule->end_time = $endTime;
            $schedule->venue = $defaultVenue;
            $schedule->instructions = "Exam for {$subject->subject_name}";
            $schedule->save();
            
            // Update invigilators separately if any were found
            if (!empty($invigilators)) {
                // Use update method to set the invigilators
                ExamClassSection::where('id', $schedule->id)
                    ->update(['invigilators' => json_encode($invigilators)]);
            }
            
            // Move to the next paper
            $currentPaperOfDay++;
            
            // Calculate next start time (previous end time + break)
            $endTimeSeconds = strtotime($endTime);
            $nextStartTimeSeconds = $endTimeSeconds + ($breakMinutes * 60);
            $currentTime = date('H:i', $nextStartTimeSeconds);
        }
        
        // Reload schedules
        $this->loadExamSchedules();
        $this->generationInProgress = false;
        
        session()->flash('success', 'Exam timetable generated successfully');
    }
    
    /**
     * Cancel the auto generation process
     */
    public function cancelGeneration()
    {
        $this->generationInProgress = false;
    }
    
    /**
     * Export the exam timetable to PDF
     */
    public function exportToPdf()
    {
        if (!$this->selectedExamId || !$this->selectedClassId) {
            session()->flash('error', 'Please select an exam and class first');
            return;
        }
        
        // This would typically redirect to a dedicated export controller
        session()->flash('info', 'Exporting to PDF...');
        
        // Redirect to a PDF export route (create this route and controller)
        return redirect()->route('exam.timetable.export.pdf', [
            'examId' => $this->selectedExamId,
            'classId' => $this->selectedClassId,
            'sectionId' => $this->selectedSectionId,
        ]);
    }
    
    /**
     * Export the exam timetable to Excel
     */
    public function exportToExcel()
    {
        if (!$this->selectedExamId || !$this->selectedClassId) {
            session()->flash('error', 'Please select an exam and class first');
            return;
        }
        
        // This would typically redirect to a dedicated export controller
        session()->flash('info', 'Exporting to Excel...');
        
        // Redirect to an Excel export route (create this route and controller)
        return redirect()->route('exam.timetable.export.excel', [
            'examId' => $this->selectedExamId,
            'classId' => $this->selectedClassId,
            'sectionId' => $this->selectedSectionId,
        ]);
    }
    
    /**
     * Print the exam timetable
     */
    public function printTimetable()
    {
        if (!$this->selectedExamId || !$this->selectedClassId) {
            session()->flash('error', 'Please select an exam and class first');
            return;
        }
        
        // This would typically redirect to a dedicated print view
        session()->flash('info', 'Opening print view...');
        
        // Redirect to a print view route (create this route and controller)
        return redirect()->route('exam.timetable.print', [
            'examId' => $this->selectedExamId,
            'classId' => $this->selectedClassId,
            'sectionId' => $this->selectedSectionId,
        ]);
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.timetable.exam-timetable');
    }
} 