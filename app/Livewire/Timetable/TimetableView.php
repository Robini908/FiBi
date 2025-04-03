<?php

namespace App\Livewire\Timetable;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\SchoolTimetable;
use App\Models\Subject;
use App\Models\TimetablePeriod;
use App\Models\TimetableSchedule;
use App\Models\TeacherSubjectAssignment;
use App\Models\TimetableRecord;
use App\Models\TimetableEntry;
use App\User;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;
use Illuminate\Support\Collection;
use App\Livewire\Timetable\AutoGenerateService;
use App\Models\SubjectCategory;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Usernotnull\Toast\Toast;

class TimetableView extends Component
{
    use WireToast;

    /**
     * Component properties
     */
    // Models
    public ?int $timetableRecordId = null;
    public ?int $sectionId = null;
    
    // Computed Collections - these will be implemented as methods
    // instead of direct properties to avoid the getMorphClass error
    
    // UI State
    public string $activeDay;
    public array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    public array $timetableMatrix = [];
    public bool $showWeekendDays = false;
    public bool $showFilters = false;
    
    // Filters
    public $filterSubject = null;
    public $filterTeacher = null;
    public $filterDay = null;
    
    // Modal State
    public bool $showEntryModal = false;
    public bool $showBulkAssignModal = false;
    public bool $showAutoGenerateModal = false;
    
    // Form Data for entry modal
    public $entryForm = [
        'id' => null,
        'timetable_id' => null,
        'period_id' => null,
        'subject_id' => null,
        'teacher_id' => null,
        'classroom' => null,
        'weekday' => null,
        'notes' => null,
    ];
    
    // Form Data for bulk assign modal
    public $bulkAssignForm = [
        'days' => [],
        'period_ids' => [],
        'subject_id' => null,
        'teacher_id' => null,
        'classroom' => null,
        'notes' => null,
    ];
    
    // Form Data for auto-generate modal
    public $autoGenerateForm = [
        'days' => [],
        'max_daily_subjects' => 5,
        'avoid_consecutive_subjects' => true,
        'prioritize_primary_teachers' => true,
        'balance_teacher_workload' => true,
        'clear_existing' => false,
        'respect_existing_entries' => true,
        'enable_subject_time_preferences' => true,
        'ensure_daily_category_variety' => true,
        'enable_balanced_distribution' => true,
        'subject_preferences' => [],
        'min_category_per_day' => [],
        'time_preferences' => [
            'morning_end' => 600,  // 10:00 AM in minutes from midnight
            'midday_end' => 780,   // 1:00 PM in minutes from midnight
            'afternoon_end' => 960 // 4:00 PM in minutes from midnight
        ],
    ];
    
    // Other state variables
    protected $listeners = [
        'refreshTimetable' => 'refreshTimetable',
        'entryAdded' => '$refresh',
        'current-periods-updated' => '$refresh'
    ];
    
    /**
     * Get the timetable model instance
     * 
     * @return \App\Models\SchoolTimetable|null
     */
    public function getTimetableProperty()
    {
        return SchoolTimetable::with('myClass')->find($this->timetableRecordId);
    }
    
    /**
     * Get the section model instance
     * 
     * @return \App\Models\Section|null
     */
    public function getSectionProperty()
    {
        if (!$this->sectionId) {
            return null;
        }
        
        return Section::find($this->sectionId);
    }
    
    /**
     * Get the periods collection
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPeriodsProperty()
    {
        return $this->getFilteredPeriods();
    }
    
    /**
     * Get the schedules collection
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSchedulesProperty()
    {
        if (!$this->timetableRecordId) {
            return collect();
        }
        
        $query = TimetableSchedule::with(['teacher', 'subject'])
            ->where('timetable_id', $this->timetableRecordId);
            
        if ($this->filterSubject) {
            $query->where('subject_id', $this->filterSubject);
        }
        
        if ($this->filterTeacher) {
            $query->where('teacher_id', $this->filterTeacher);
        }
        
        if ($this->filterDay) {
            $query->where('weekday', $this->filterDay);
        }
            
        return $query->get();
    }
    
    /**
     * Get periods categorized by type
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getCategorizedPeriodsProperty()
    {
        if (empty($this->getPeriodsProperty())) {
            return collect();
        }
        
        return $this->getPeriodsProperty()->groupBy('period_type');
    }
    
    /**
     * Get all subjects for the dropdown
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSubjectsProperty()
    {
        if (!$this->timetable) {
            return collect();
        }
        
        $classId = $this->timetable->class_id;
        return $this->getSubjectsForClass($classId, $this->sectionId);
    }
    
    /**
     * Get all teachers for the dropdown
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTeachersProperty()
    {
        return User::role('teacher')->orderBy('name')->get();
    }
    
    // Model instances
    public ?SchoolTimetable $timetable = null;
    public ?Section $section = null;
    
    // Collections
    /** @var \Illuminate\Support\Collection */
    public $periods;
    /** @var \Illuminate\Support\Collection */
    public $schedules;
    /** @var \Illuminate\Support\Collection */
    public $categorizedPeriods;
    
    // Arrays
    public $selectedDay;
    public $selectedPeriodId;
    
    public $subjects = [];
    public $teachers = [];
    
    protected $rules = [
        'entryForm.subject_id' => 'required|exists:subjects,id',
        'entryForm.teacher_id' => 'nullable|exists:users,id',
        'entryForm.classroom' => 'nullable|string|max:50',
        'entryForm.notes' => 'nullable|string',
        
        // Bulk assignment rules
        'bulkAssignForm.days' => 'required|array|min:1',
        'bulkAssignForm.period_ids' => 'required|array|min:1',
        'bulkAssignForm.subject_id' => 'required|exists:subjects,id',
        'bulkAssignForm.teacher_id' => 'nullable|exists:users,id',
        'bulkAssignForm.classroom' => 'nullable|string|max:50',
    ];

    public function mount($timetableRecordId, $sectionId)
    {
        $this->timetableRecordId = $timetableRecordId;
        $this->sectionId = $sectionId;
        $this->activeDay = strtolower(date('l')); // Default to current day
        
        // Enhanced logging for debugging
        \Log::debug("Mounted TimetableView with parameters: timetableId={$timetableRecordId}, sectionId={$sectionId}");
        
        // Validate the timetable ID early
        if (empty($this->timetableRecordId)) {
            \Log::error("TimetableView mounted with empty timetableRecordId");
            toast()->danger('Error: Invalid timetable ID')->push();
        }
        
        // Set the timetable property
        $this->timetable = $this->getTimetableProperty();
        $this->section = $this->getSectionProperty();
        
        // Initialize the timetable matrix
        $this->buildTimetableMatrix();
        
        // Check if we have weekend slots and should show weekend days
        $this->checkWeekendSlots();
        
        if ($this->timetable) {
            \Log::debug("Timetable loaded successfully: {$this->timetable->id}");
        } else {
            \Log::warning("Failed to load timetable with ID: {$timetableRecordId}");
        }
    }
    
    /**
     * Get filtered periods
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFilteredPeriods()
    {
        if (!$this->timetableRecordId) {
            return collect();
        }
        
        return TimetablePeriod::where('timetable_id', $this->timetableRecordId)
            ->orderBy('start_time', 'asc')
            ->orderBy('period_order', 'asc')
            ->get();
    }
    
    /**
     * Check if there are weekend entries in the schedule
     */
    public function checkWeekendSlots()
    {
        $schedules = $this->getSchedulesProperty();
        
        // If no schedules loaded, return early
        if ($schedules->isEmpty()) {
            $this->showWeekendDays = false;
            return;
        }
        
        // Check if there are periods scheduled for weekend days
        $weekendEntries = $schedules->filter(function ($entry) {
            return in_array($entry->weekday, ['saturday', 'sunday']);
        });
        
        $this->showWeekendDays = $weekendEntries->count() > 0;
        
        // Update days array to include weekends if needed
        if ($this->showWeekendDays && count($this->days) === 5) {
            $this->days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        }
    }
    
    /**
     * Build the timetable matrix
     */
    public function buildTimetableMatrix()
    {
        $this->timetableMatrix = [];
        
        // If no timetable is loaded, return early
        if (!$this->timetableRecordId) {
            return;
        }
        
        $periods = $this->getPeriodsProperty();
        $schedules = $this->getSchedulesProperty();
        
        foreach ($this->days as $day) {
            $this->timetableMatrix[$day] = [];
            
            foreach ($periods as $period) {
                // Find entry for this day and period
                $entry = $schedules->first(function ($entry) use ($day, $period) {
                    return $entry->weekday === $day && $entry->period_id === $period->id;
                });
                
                $this->timetableMatrix[$day][$period->id] = $entry;
            }
        }
    }
    
    public function loadTimetableData()
    {
        try {
            // Load the timetable record as a single model instance, not a collection
        $this->timetable = SchoolTimetable::with('myClass')->find($this->timetableRecordId);
        
        if (!$this->timetable) {
            toast()->danger('Timetable not found. Please select a valid timetable.')->push();
            $this->redirect(route('timetable.list'));
            return;
        }
        
        // Allow section to be null if not required
        if ($this->sectionId) {
                // Ensure we're loading a single section model, not a collection
            $this->section = Section::find($this->sectionId);
            if (!$this->section) {
                toast()->warning('Section not found, viewing timetable without section filter')->push();
            }
            } else {
                // Make sure section is null, not an empty collection
                $this->section = null;
        }
        
        // Get periods in order by start time for better chronological display
            $periods = TimetablePeriod::where('timetable_id', $this->timetableRecordId)
            ->orderBy('start_time', 'asc')  // Primary sort by start_time
            ->orderBy('period_order', 'asc') // Secondary sort by period_order
            ->get();
        
            // Ensure periods is always initialized as a collection
            $this->periods = $periods instanceof Collection ? $periods : collect($periods);
        
        // Group periods by type for better organization
        $this->categorizedPeriods = $this->periods->groupBy(function($period) {
            $name = strtolower($period->period_name);
            
            if (str_contains($name, 'break') || str_contains($name, 'lunch') || str_contains($name, 'recess')) {
                return 'breaks';
            } elseif (str_contains($name, 'assembly') || str_contains($name, 'homeroom')) {
                return 'assembly';
            } elseif (str_contains($name, 'prep') || str_contains($name, 'study')) {
                return 'prep';
            } elseif (str_contains($name, 'saturday') || str_contains($name, 'sunday') || str_contains($name, 'weekend')) {
                return 'weekend';
            } else {
                return 'lessons';
            }
        });
        
        // Get all schedule entries for this timetable
            $schedules = TimetableSchedule::where('timetable_id', $this->timetableRecordId)
            ->with(['subject', 'teacher', 'period'])
            ->get();
        
            // Ensure schedules is always initialized as a collection
            $this->schedules = $schedules instanceof Collection ? $schedules : collect($schedules);
        
        // Build the timetable matrix
        $this->buildTimetableMatrix();
        
        // Preload subjects and teachers for dropdowns
        $this->loadDropdownData();
            
        } catch (\Exception $e) {
            // Log the error and provide user feedback
            \Log::error('Error loading timetable data: ' . $e->getMessage());
            toast()->danger('Error loading timetable data: ' . $e->getMessage())->push();
            
            // Initialize empty collections for safety
            $this->periods = collect();
            $this->schedules = collect();
            $this->categorizedPeriods = collect();
            $this->timetableMatrix = [];
        }
    }
    
    public function loadDropdownData()
    {
        // Load all subjects ordered by name
        $this->subjects = Subject::orderBy('subject_name')->get();

        // Initialize teachers collection
        $teachers = collect();
        
        // 1. First try to get teachers from the section if available
        if ($this->section) {
            // Get the section's teacher if assigned
            if ($this->section->teacher_id) {
                $sectionTeacher = User::find($this->section->teacher_id);
                if ($sectionTeacher) {
                    $teachers->push($sectionTeacher);
                    \Log::debug("Added section teacher: {$sectionTeacher->name}");
                }
            }
            
            // Get teachers associated with the class for this section
            if ($this->section->my_class) {
                $classTeachers = $this->section->my_class->teachers()->get();
                if ($classTeachers && $classTeachers->count() > 0) {
                    // Add without duplicates
                    $classTeachers->each(function($teacher) use ($teachers) {
                        if (!$teachers->contains('id', $teacher->id)) {
                            $teachers->push($teacher);
                            \Log::debug("Added class teacher: {$teacher->name}");
                        }
                    });
                }
            }
        }
        
        // 2. If we have a timetable with class_id, get teachers for that class
        if ($teachers->isEmpty() && $this->timetable && $this->timetable->class_id) {
            $class = MyClass::find($this->timetable->class_id);
            if ($class) {
                $classTeachers = $class->teachers()->get();
                if ($classTeachers && $classTeachers->count() > 0) {
                    $teachers = $classTeachers;
                    \Log::debug("Added {$classTeachers->count()} teachers from timetable class");
                }
            }
        }
        
        // 3. If still no teachers, get all teachers
        if ($teachers->isEmpty()) {
            // Use Spatie's role method to get teachers 
            $teachers = User::role('teacher')
            ->orderBy('name')
            ->get();
            \Log::debug("No teachers found in section/class, found {$teachers->count()} teachers total");
        }
        
        // Set the teachers collection
        $this->teachers = $teachers;
        
        // Ensure subjects is a collection
        if (!($this->subjects instanceof \Illuminate\Support\Collection)) {
            $this->subjects = collect($this->subjects);
        }
        
        // Ensure teachers is a collection
        if (!($this->teachers instanceof \Illuminate\Support\Collection)) {
            $this->teachers = collect($this->teachers);
        }
        
        // Get classroom information from section or timetable if available
        if ($this->section) {
            // Set default classroom based on section information
            if (empty($this->entryForm['classroom']) && isset($this->section->name)) {
                $className = optional($this->section->my_class)->name ?? '';
                $sectionName = $this->section->name;
                
                // Format: Class Name - Section Name (e.g., "Form 2 - A")
                if ($className && $sectionName) {
                    $this->entryForm['classroom'] = "$className - $sectionName";
                }
            }
        } elseif ($this->timetable && empty($this->entryForm['classroom'])) {
            // If no section but timetable has class, use class name
            $className = optional($this->timetable->myClass)->name ?? '';
            if ($className) {
                $this->entryForm['classroom'] = $className;
            }
        }
    }
    
    public function setActiveDay($day)
    {
        $this->activeDay = $day;
    }
    
    public function toggleWeekendDays()
    {
        $this->showWeekendDays = !$this->showWeekendDays;
    }
    
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }
    
    public function resetFilters()
    {
        $this->filterSubject = '';
        $this->filterTeacher = '';
        $this->filterDay = '';
    }
    
    public function getVisibleDays()
    {
        if (!$this->showWeekendDays) {
            return array_slice($this->days, 0, 5); // Mon-Fri only
        }
        return $this->days; // All days including weekends
    }
    
    /**
     * Determine if a period allows class assignment
     * 
     * @param mixed $period
     * @return bool
     */
    public function canAssignClass($period)
    {
        if (!$period) {
            return false;
        }
        
        // Get period name (handle both object and array)
        $periodName = '';
        
        if (is_object($period) && isset($period->period_name)) {
            $periodName = strtolower($period->period_name);
        } elseif (is_array($period) && isset($period['period_name'])) {
            $periodName = strtolower($period['period_name']);
        } elseif (is_array($period) && isset($period['attributes']['period_name'])) {
            $periodName = strtolower($period['attributes']['period_name']);
        }
        
        if (empty($periodName)) {
            return false;
        }
        
        // Define period types that should NOT allow class assignment
        $nonAssignableTypes = [
            'break', 'lunch', 'movement', 'recess', 
            'assembly', 'short break', 'lunch break',
            'transition', 'movement time', 'curriculum'
        ];
        
        // Check for any non-assignable keywords in the period name
        foreach ($nonAssignableTypes as $type) {
            if (str_contains($periodName, $type)) {
                return false;
            }
        }
        
        // Periods, Preps, Weekend classes, and other lessons should be assignable
        $assignableTypes = [
            'period', 'class', 'lesson', 'prep', 'saturday', 'sunday', 'weekend'
        ];
        
        foreach ($assignableTypes as $type) {
            if (str_contains($periodName, $type)) {
                return true;
            }
        }
        
        // By default, if not explicitly recognized, allow assignment
        return true;
    }
    
    public function openEntryModal($day, $periodId)
    {
        $this->selectedDay = $day;
        $this->selectedPeriodId = $periodId;
        
        // Find the period in the collection
        $periods = $this->getPeriodsProperty();
        $period = $periods->firstWhere('id', $periodId);
        
        // Check if period exists
        if (!$period) {
            toast()->danger('Period not found.')->push();
            return;
        }
        
        // Check if this period allows class assignment
        if (!$this->canAssignClass($period)) {
            toast()->warning("Cannot assign classes to '{$period->period_name}' periods.")->push();
            return;
        }
        
        // Check if an entry already exists
        $schedules = $this->getSchedulesProperty();
        $existingEntry = $schedules->first(function ($entry) use ($day, $periodId) {
            return $entry->weekday === $day && $entry->period_id === $periodId;
        });
        
        if ($existingEntry) {
            $this->entryForm = [
                'id' => $existingEntry->id,
                'timetable_id' => $existingEntry->timetable_id,
                'period_id' => $existingEntry->period_id,
                'subject_id' => $existingEntry->subject_id,
                'teacher_id' => $existingEntry->teacher_id,
                'weekday' => $existingEntry->weekday,
                'classroom' => $existingEntry->classroom,
                'notes' => $existingEntry->notes,
            ];
            
            toast()->info('Editing existing timetable entry for ' . ucfirst($day))->push();
        } else {
            // Default classroom from section
            $defaultClassroom = '';
            if ($this->section) {
                $className = optional($this->section->my_class)->name ?? '';
                $sectionName = $this->section->name ?? '';
                if ($className && $sectionName) {
                    $defaultClassroom = "$className - $sectionName";
                }
            }
            
            $this->entryForm = [
                'id' => null,
                'timetable_id' => $this->timetableRecordId,
                'period_id' => $periodId,
                'subject_id' => null,
                'teacher_id' => null,
                'weekday' => $day,
                'classroom' => $defaultClassroom,
                'notes' => '',
            ];
            
            toast()->info('Adding new timetable entry for ' . ucfirst($day))->push();
        }
        
        $this->showEntryModal = true;
    }
    
    /**
     * Get only the periods that can have classes assigned to them
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getAssignablePeriods()
    {
        // Ensure we're working with a collection
        if (!($this->periods instanceof \Illuminate\Support\Collection)) {
            $this->periods = collect($this->periods);
        }
        
        return $this->periods->filter(function ($period) {
            return $this->canAssignClass($period);
        });
    }
    
    public function openBulkAssignModal()
    {
        // Default classroom based on section information
        $defaultClassroom = '';
        if ($this->section) {
            $className = optional($this->section->my_class)->name ?? '';
            $sectionName = $this->section->name ?? '';
            if ($className && $sectionName) {
                $defaultClassroom = "$className - $sectionName";
            }
        }
        
        // Reset the bulk assign form
        $this->bulkAssignForm = [
            'days' => [],
            'period_ids' => [],
            'subject_id' => null,
            'teacher_id' => null,
            'classroom' => $defaultClassroom,
            'notes' => null,
        ];
        
        // Check if there are any assignable periods
        $assignablePeriods = $this->getAssignablePeriodsProperty();
        if ($assignablePeriods->isEmpty()) {
            toast()->warning('No assignable periods found. Please create some teaching periods first.')->push();
            return;
        }
        
        $this->showBulkAssignModal = true;
    }
    
    public function saveEntry()
    {
        $this->validate([
            'entryForm.subject_id' => 'required|exists:subjects,id',
            'entryForm.teacher_id' => 'nullable|exists:users,id',
            'entryForm.classroom' => 'nullable|string|max:50',
            'entryForm.notes' => 'nullable|string',
        ]);
        
        try {
            $data = [
                'timetable_id' => $this->timetableRecordId,
                'period_id' => $this->selectedPeriodId,
                'subject_id' => $this->entryForm['subject_id'],
                'teacher_id' => $this->entryForm['teacher_id'],
                'weekday' => $this->selectedDay,
                'classroom' => $this->entryForm['classroom'],
                'notes' => $this->entryForm['notes'],
                'is_recurring' => true,
                'specific_date' => null,
            ];
            
            if ($this->entryForm['id']) {
                $entry = TimetableSchedule::findOrFail($this->entryForm['id']);
                $entry->update($data);
                
                toast()->success('Timetable entry updated successfully')->push();
            } else {
                TimetableSchedule::create($data);
                
                toast()->success('Timetable entry created successfully')->push();
            }
            
            $this->closeEntryModal();
            $this->refreshTimetable();
            
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    public function saveBulkAssign()
    {
        $this->validate([
            'bulkAssignForm.days' => 'required|array|min:1',
            'bulkAssignForm.period_ids' => 'required|array|min:1',
            'bulkAssignForm.subject_id' => 'required|exists:subjects,id',
            'bulkAssignForm.teacher_id' => 'nullable|exists:users,id',
            'bulkAssignForm.classroom' => 'nullable|string|max:50',
        ]);
        
        try {
            $created = 0;
            $updated = 0;
            $errors = 0;
            $skipped = 0;
            
            // Get assignable periods
            $assignablePeriods = $this->getAssignablePeriodsProperty();
            
            foreach ($this->bulkAssignForm['days'] as $day) {
                foreach ($this->bulkAssignForm['period_ids'] as $periodId) {
                    // Skip if period is not assignable
                    if (!in_array($periodId, $assignablePeriods->pluck('id')->toArray())) {
                        $skipped++;
                        continue;
                    }
                    
                    // Check if entry already exists
                    $existingEntry = TimetableSchedule::where([
                        'timetable_id' => $this->timetableRecordId,
                        'period_id' => $periodId,
                        'weekday' => $day,
                    ])->first();
                    
                    $data = [
                        'timetable_id' => $this->timetableRecordId,
                        'period_id' => $periodId,
                        'subject_id' => $this->bulkAssignForm['subject_id'],
                        'teacher_id' => $this->bulkAssignForm['teacher_id'],
                        'weekday' => $day,
                        'classroom' => $this->bulkAssignForm['classroom'],
                        'notes' => $this->bulkAssignForm['notes'],
                        'is_recurring' => true,
                        'specific_date' => null,
                    ];
                    
                    if ($existingEntry) {
                        try {
                            $existingEntry->update($data);
                            $updated++;
                        } catch (\Exception $e) {
                            $errors++;
                        }
                    } else {
                        try {
                            TimetableSchedule::create($data);
                            $created++;
                        } catch (\Exception $e) {
                            $errors++;
                        }
                    }
                }
            }
            
            $message = "Bulk assignment complete: {$created} created, {$updated} updated";
            if ($skipped > 0) {
                $message .= ", {$skipped} skipped (non-assignable periods)";
            }
            if ($errors > 0) {
                $message .= ", {$errors} errors";
                toast()->warning($message)->push();
            } else {
                toast()->success($message)->push();
            }
            
            $this->closeBulkAssignModal();
            $this->refreshTimetable();
            
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    public function deleteEntry($entryId = null)
    {
        $id = $entryId ?? $this->entryForm['id'];
        
        if (!$id) {
            $this->closeEntryModal();
            return;
        }
        
        try {
            TimetableSchedule::findOrFail($id)->delete();
            toast()->success('Timetable entry deleted successfully')->push();
            
            $this->closeEntryModal();
            $this->loadTimetableData();
            
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    public function copyEntry($entryId)
    {
        try {
            $entry = TimetableSchedule::findOrFail($entryId);
            
            // Open the entry form with copied data but no ID (treat as new)
            $this->entryForm = [
                'id' => null, // New entry
                'timetable_id' => $entry->timetable_id,
                'period_id' => $entry->period_id,
                'subject_id' => $entry->subject_id,
                'teacher_id' => $entry->teacher_id,
                'weekday' => $entry->weekday,
                'classroom' => $entry->classroom,
                'notes' => $entry->notes,
            ];
            
            $this->selectedDay = $entry->weekday;
            $this->selectedPeriodId = $entry->period_id;
            
            toast()->info('Copied entry. Please select a new slot to paste')->push();
            $this->showEntryModal = true;
            
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    public function closeEntryModal()
    {
        $this->showEntryModal = false;
        $this->selectedDay = null;
        $this->selectedPeriodId = null;
        $this->entryForm = [
            'id' => null,
            'timetable_id' => null,
            'period_id' => null,
            'subject_id' => null,
            'teacher_id' => null,
            'weekday' => null,
            'classroom' => '',
            'notes' => '',
        ];
    }
    
    public function closeBulkAssignModal()
    {
        $this->showBulkAssignModal = false;
        $this->bulkAssignForm = [
            'days' => [],
            'period_ids' => [],
            'subject_id' => null,
            'teacher_id' => null,
            'classroom' => '',
            'notes' => null,
        ];
    }
    
    /**
     * Get all assignable periods
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssignablePeriodsProperty()
    {
        $periods = $this->getPeriodsProperty();
        
        return $periods->filter(function ($period) {
            return $this->canAssignClass($period);
        });
    }
    
    public function render()
    {
        // Debug timetable property
        $timetable = $this->getTimetableProperty();
        \Log::debug("In render - Timetable ID: " . ($timetable ? $timetable->id : 'null'));
        
        // Apply any filters and rebuild the timetable matrix
        if ($this->filterSubject || $this->filterTeacher || $this->filterDay) {
            $this->buildTimetableMatrix();
        }
        
        // Set the timetable property using getter
        $this->timetable = $this->getTimetableProperty();
        
        return view('livewire.timetable.timetable-view', [
            'filteredPeriods' => $this->getPeriodsProperty(),
            'visibleDays' => $this->getVisibleDays(),
            'assignablePeriods' => $this->getAssignablePeriodsProperty(),
            'timetable' => $this->timetable,
            'section' => $this->section,
            'schedules' => $this->getSchedulesProperty(),
            'subjects' => $this->getSubjectsProperty(),
            'teachers' => $this->getTeachersProperty(),
        ]);
    }
    
    /**
     * Switch to a different tab in the timetable interface
     */
    public function switchTab($tab)
    {
        $this->dispatch('switchTab', $tab);
    }
    
    /**
     * Get the currently selected subject name
     */
    public function getSelectedSubjectName()
    {
        if (empty($this->entryForm['subject_id'])) {
            return '';
        }
        
        // Convert to collection if needed
        $subjectsCollection = collect($this->subjects);
        
        // Find the subject with the matching ID
        $subject = $subjectsCollection->firstWhere('id', $this->entryForm['subject_id']);
        
        return optional($subject)->subject_name ?? '';
    }
    
    /**
     * Get the currently selected teacher name
     */
    public function getSelectedTeacherName()
    {
        if (empty($this->entryForm['teacher_id'])) {
            return '';
        }
        
        // Convert to collection if needed
        $teachersCollection = collect($this->teachers);
        
        // Find the teacher with the matching ID
        $teacher = $teachersCollection->firstWhere('id', $this->entryForm['teacher_id']);
        
        return optional($teacher)->name ?? '';
    }
    
    /**
     * Open the auto-generate modal and initialize its form data
     */
    public function openAutoGenerateModal()
    {
        // First, immediately show the modal with default settings
        $this->showAutoGenerateModal = true;
        
        // Check for existing entries before initializing the form
        $existingEntries = TimetableSchedule::where('timetable_id', $this->timetableRecordId)->count();
        $hasExistingEntries = $existingEntries > 0;
        
        // Initialize with basic defaults immediately
        $this->autoGenerateForm = [
            'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'max_daily_subjects' => 5,
            'avoid_consecutive_subjects' => true,
            'prioritize_primary_teachers' => true,
            'balance_teacher_workload' => true,
            'clear_existing' => !$hasExistingEntries, // Set to true only if no existing entries
            'respect_existing_entries' => $hasExistingEntries, // Set to true if existing entries
            'enable_subject_time_preferences' => true,
            'ensure_daily_category_variety' => true,
            'enable_balanced_distribution' => true,
            'subject_preferences' => [],
            'min_category_per_day' => [
                'Core' => 2,
                'Language' => 1,
                'Humanities' => 1,
                'Science' => 1
            ],
            'time_preferences' => [
                'morning_end' => 600,  // 10:00 AM in minutes from midnight
                'midday_end' => 780,   // 1:00 PM in minutes from midnight
                'afternoon_end' => 960 // 4:00 PM in minutes from midnight
            ],
        ];
        
        // Set fallback subject preferences immediately so there's initial data while loading
        $this->autoGenerateForm['subject_preferences'] = $this->getPreloadedSubjectPreferences();
        
        // Log information about existing entries for debugging
        \Log::info("Auto-generate modal opened with {$existingEntries} existing entries. Settings: clear_existing=" . 
                 ($this->autoGenerateForm['clear_existing'] ? 'true' : 'false') . 
                 ", respect_existing_entries=" . 
                 ($this->autoGenerateForm['respect_existing_entries'] ? 'true' : 'false'));
        
        // Load assignment data in the background
        $this->loadTeacherSubjectAssignments();
    }
    
    /**
     * Loads teacher subject assignments without blocking the UI
     * This is called after the modal is displayed to prevent flashing
     */
    private function loadTeacherSubjectAssignments()
    {
        try {
            // Get the current timetable record
            $timetableRecord = SchoolTimetable::find($this->timetableRecordId);
            
            if (!$timetableRecord) {
                Log::warning('Timetable record not found when trying to load assignments');
                return;
            }
            
            $classId = $timetableRecord->class_id;
            $sectionId = $this->sectionId;
            $academicSession = $timetableRecord->academic_session;
            $academicTerm = $timetableRecord->academic_term;
            
            // Log key details for debugging
            Log::info("Loading teacher subject assignments for timetable auto-generation", [
                'timetable_id' => $this->timetableRecordId,
                'class_id' => $classId,
                'section_id' => $sectionId,
                'academic_session' => $academicSession,
                'academic_term' => $academicTerm
            ]);
            
            // Get teacher subject assignments for this class/section with eager loading
            $query = TeacherSubjectAssignment::with(['teacher', 'subject.category'])
                ->where('class_id', $classId)
                ->where('is_active', true);
                
            // Apply section filter if provided (allowing both specific section and class-wide assignments)
            if ($sectionId) {
                $query->where(function($q) use ($sectionId) {
                    $q->where('section_id', $sectionId)
                      ->orWhereNull('section_id');
                });
            }
            
            // Apply academic year filter if available
            if ($academicSession) {
                $query->where(function($q) use ($academicSession) {
                    $q->where('academic_year_id', $academicSession)
                      ->orWhereNull('academic_year_id');
                });
            }
            
            // Apply academic term filter if available
            if ($academicTerm) {
                $query->where(function($q) use ($academicTerm) {
                    $q->where('academic_term', $academicTerm)
                      ->orWhereNull('academic_term');
                });
            }
            
            $assignments = $query->get();
            
            if ($assignments->isEmpty()) {
                Log::warning('No teacher subject assignments found for this class/section/term', [
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                    'academic_session' => $academicSession,
                    'academic_term' => $academicTerm
                ]);
                
                // Try a less restrictive query without term filter first
                $backupAssignments = TeacherSubjectAssignment::with(['teacher', 'subject.category'])
                    ->where('class_id', $classId)
                    ->where('is_active', true)
                    ->when($academicSession, function($q) use ($academicSession) {
                        $q->where(function($subq) use ($academicSession) {
                            $subq->where('academic_year_id', $academicSession)
                                ->orWhereNull('academic_year_id');
                        });
                    })
                    ->when($sectionId, function($q) use ($sectionId) {
                        $q->where(function($subquery) use ($sectionId) {
                            $subquery->where('section_id', $sectionId)
                                ->orWhereNull('section_id');
                        });
                    })
                    ->get();
                    
                if ($backupAssignments->isNotEmpty()) {
                    Log::info("Found {$backupAssignments->count()} backup teacher assignments without term filter");
                    $assignments = $backupAssignments;
                } else {
                    // As a last resort, try without any term/session filters
                    $finalBackupAssignments = TeacherSubjectAssignment::with(['teacher', 'subject.category'])
                        ->where('class_id', $classId)
                        ->where('is_active', true)
                        ->when($sectionId, function($q) use ($sectionId) {
                            $q->where(function($subquery) use ($sectionId) {
                                $subquery->where('section_id', $sectionId)
                                    ->orWhereNull('section_id');
                            });
                        })
                        ->get();
                        
                    if ($finalBackupAssignments->isNotEmpty()) {
                        Log::info("Found {$finalBackupAssignments->count()} final backup teacher assignments without any term/session filters");
                        $assignments = $finalBackupAssignments;
                } else {
                    Log::error("No teacher subject assignments found, even without term/session filters");
                    return;
                }
                }
            }
            
                Log::info("Found {$assignments->count()} teacher-subject assignments for class $classId" . 
                    ($sectionId ? ", section $sectionId" : ""));
            
            // Process assignments into subject preferences
            $subjectPreferences = [];
            
            foreach ($assignments as $assignment) {
                $subject = $assignment->subject;
                $teacher = $assignment->teacher;
                
                if (!$subject) {
                    Log::warning("Assignment {$assignment->id} has no valid subject");
                    continue;
                }
                
                if (!$teacher) {
                    Log::warning("Assignment {$assignment->id} has no valid teacher");
                    continue;
                }
                
                $subjectId = $subject->id;
                $categoryName = optional($subject->category)->name ?? 'Uncategorized';
                $categoryId = optional($subject->category)->id;
                
                // Skip if we already have a primary teacher for this subject
                // and the current assignment is not primary
                if (isset($subjectPreferences[$subjectId]) && 
                    $subjectPreferences[$subjectId]['is_primary'] && 
                    !$assignment->is_primary) {
                    continue;
                }
                
                // Override existing non-primary assignment with a primary one
                if (isset($subjectPreferences[$subjectId]) && 
                    !$subjectPreferences[$subjectId]['is_primary'] && 
                    $assignment->is_primary) {
                    // This is a better assignment (primary), so we'll replace the existing one
                } else if (isset($subjectPreferences[$subjectId])) {
                    // If both are primary or both are non-primary, prefer the latest one
                    if ($assignment->updated_at < $subjectPreferences[$subjectId]['updated_at']) {
                        continue;
                    }
                }
                
                // Create base preferences with more data from the assignment
                $preferences = [
                    'name' => $subject->subject_name,
                    'category' => $categoryName,
                    'category_id' => $categoryId,
                    'teacher_id' => $teacher->id,
                    'teacher_name' => $teacher->name,
                    'is_primary' => $assignment->is_primary,
                    'updated_at' => $assignment->updated_at,
                    'assignment_id' => $assignment->id,
                    'preferred_time' => 'any',
                    'max_consecutive' => 2,
                    'weekly_frequency' => 3,
                    'daily_limit' => 1
                ];
                
                // Customize based on subject type
                $this->customizeSubjectPreferences($preferences, $subject, $categoryName);
                
                // Add to preferences array
                $subjectPreferences[$subjectId] = $preferences;
                
                Log::debug("Added subject preference: {$subject->subject_name} with teacher {$teacher->name} " . 
                    ($assignment->is_primary ? '(PRIMARY)' : ''));
            }
            
            // Only update if we have data
            if (!empty($subjectPreferences)) {
                $this->autoGenerateForm['subject_preferences'] = $subjectPreferences;
                Log::info("Updated auto-generate form with " . count($subjectPreferences) . " subject preferences from teacher assignments");
            } else {
                Log::warning("No subject preferences could be created from assignments");
            }
            
        } catch (\Exception $e) {
            Log::error('Error loading teacher subject assignments: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            // Fallback preferences remain in place
        }
    }
    
    /**
     * Customize subject preferences based on subject type/category
     */
    private function customizeSubjectPreferences(array &$preferences, $subject, $categoryName)
    {
        // Convert category name to lowercase for consistent comparison
        $lowerCategoryName = strtolower($categoryName);
        $subjectName = strtolower($subject->subject_name);
        
        // Core subjects like Math/Science are best in morning
        if (str_contains($lowerCategoryName, 'math') || 
            str_contains($lowerCategoryName, 'science') || 
            str_contains($subjectName, 'math') || 
            str_contains($subjectName, 'science')) {
            $preferences['preferred_time'] = 'morning';
            $preferences['weekly_frequency'] = 5;
        }
        
        // Languages also benefit from morning slots
        elseif (str_contains($lowerCategoryName, 'language') || 
                str_contains($subjectName, 'english') || 
                str_contains($subjectName, 'language')) {
            $preferences['preferred_time'] = 'morning';
            $preferences['weekly_frequency'] = 5;
        }
        
        // Arts, creative subjects in afternoon
        elseif (str_contains($lowerCategoryName, 'art') || 
                str_contains($lowerCategoryName, 'music') || 
                str_contains($lowerCategoryName, 'creative')) {
            $preferences['preferred_time'] = 'afternoon';
            $preferences['weekly_frequency'] = 2;
            $preferences['max_consecutive'] = 3;
        }
        
        // Physical education in afternoon
        elseif (str_contains($lowerCategoryName, 'physical') || 
                str_contains($lowerCategoryName, 'sport') || 
                str_contains($subjectName, 'p.e') || 
                str_contains($subjectName, 'physical')) {
            $preferences['preferred_time'] = 'afternoon';
            $preferences['weekly_frequency'] = 2;
            $preferences['max_consecutive'] = 2;
        }
        
        // Computer classes
        elseif (str_contains($lowerCategoryName, 'computer') || 
                str_contains($lowerCategoryName, 'technology') || 
                str_contains($subjectName, 'computer') || 
                str_contains($subjectName, 'ict')) {
            $preferences['preferred_time'] = 'midday';
            $preferences['weekly_frequency'] = 2;
        }
    }
    
    /**
     * Get preloaded subject preferences for immediate display
     * This provides good default data to show immediately while actual data loads
     */
    private function getPreloadedSubjectPreferences()
    {
        $preloaded = [];
        
        // Add some common subjects with sensible defaults
        $commonSubjects = [
            ['id' => 'temp1', 'name' => 'Mathematics', 'category' => 'Core', 'time' => 'morning', 'freq' => 5],
            ['id' => 'temp2', 'name' => 'English', 'category' => 'Language', 'time' => 'morning', 'freq' => 5],
            ['id' => 'temp3', 'name' => 'Science', 'category' => 'Core', 'time' => 'morning', 'freq' => 4],
            ['id' => 'temp4', 'name' => 'History', 'category' => 'Humanities', 'time' => 'midday', 'freq' => 3],
            ['id' => 'temp5', 'name' => 'Physical Education', 'category' => 'Activity', 'time' => 'afternoon', 'freq' => 2],
            ['id' => 'temp6', 'name' => 'Art', 'category' => 'Creative', 'time' => 'afternoon', 'freq' => 2]
        ];
        
        foreach ($commonSubjects as $subject) {
            $preloaded[$subject['id']] = [
                'name' => $subject['name'],
                'category' => $subject['category'],
                'category_id' => null,
                'teacher_id' => null,
                'teacher_name' => 'Loading...',
                'preferred_time' => $subject['time'],
                'max_consecutive' => 2,
                'weekly_frequency' => $subject['freq'],
                'daily_limit' => 1
            ];
        }
        
        return $preloaded;
    }
    
    /**
     * Closes the auto-generate timetable modal
     */
    public function closeAutoGenerateModal()
    {
        $this->showAutoGenerateModal = false;
    }
    
    /**
     * Auto-generate the timetable based on teacher-subject assignments
     */
    public function autoGenerateTimetable()
    {
        $this->validate([
            'autoGenerateForm.days' => 'required|array|min:1',
            'autoGenerateForm.max_daily_subjects' => 'required|numeric|min:1|max:10',
        ]);
        
        try {
            // Validate subject preferences
            if (empty($this->autoGenerateForm['subject_preferences'])) {
                toast()->warning('No subject preferences found. Cannot auto-generate timetable.')->push();
                return;
            }
            
            // Basic validation for subject preferences
            $hasValidSubjects = false;
            foreach ($this->autoGenerateForm['subject_preferences'] as $subjectId => $preferences) {
                // Check that the subject exists
                $subject = Subject::find($subjectId);
                if (!$subject) {
                    continue;
                }
                
                // Check that we have at least one valid weekly frequency
                if ($preferences['weekly_frequency'] > 0) {
                    $hasValidSubjects = true;
                    break;
                }
            }
            
            if (!$hasValidSubjects) {
                toast()->warning('No valid subjects with positive weekly frequency found. Cannot auto-generate timetable.')->push();
                return;
            }
            
            // Get necessary information
            $timetable = $this->timetable;
            if (!$timetable) {
                toast()->danger('No timetable found for the current class.')->push();
                return;
            }
            
            $timetableId = $timetable->id;
            $classId = $timetable->class_id;
            $sectionId = $this->sectionId;
            
            // Get class and section names for display
            $className = '';
            if ($timetable->myClass) {
                $className = $timetable->myClass->name;
            }
            
            $sectionName = '';
            $section = $this->section;
            if ($section) {
                $sectionName = $section->name;
            }
            
            // Ensure that the min_category_per_day is properly set
            if (!isset($this->autoGenerateForm['min_category_per_day']) || empty($this->autoGenerateForm['min_category_per_day'])) {
                $this->autoGenerateForm['min_category_per_day'] = [
                    'Core' => 2,
                    'Language' => 1,
                    'Humanities' => 1,
                    'Science' => 1
                ];
            }
            
            // Log the auto-generate settings for debugging
            \Log::info("Auto-generate settings", [
                'days' => $this->autoGenerateForm['days'],
                'max_daily_subjects' => $this->autoGenerateForm['max_daily_subjects'],
                'clear_existing' => $this->autoGenerateForm['clear_existing'],
                'respect_existing_entries' => $this->autoGenerateForm['respect_existing_entries']
            ]);
            
            // Create the auto-generate service
            $autoGenerateService = new AutoGenerateService();
            
            // Run the auto-generate process
            $stats = $autoGenerateService->generate(
                $this->autoGenerateForm,
                $timetableId,
                $classId,
                $sectionId,
                $className,
                $sectionName
            );
            
            // Ensure stats is an array
            if (!is_array($stats)) {
                $stats = ['entries_created' => 0, 'skipped' => 0, 'conflicts' => 0];
                \Log::error("Auto-generate service returned a non-array value", [
                    'type' => gettype($stats),
                    'value' => $stats
                ]);
            }
            
            // Reload the timetable data
            $this->refreshTimetable();
            $this->closeAutoGenerateModal();
            
            // Show a success message with statistics
            if (isset($stats['entries_created']) && $stats['entries_created'] > 0 || 
                isset($stats['cleared']) && $stats['cleared'] > 0) {
                $message = "Auto-generated timetable: ";
                
                // Information about clearing or respecting existing entries
                if (isset($stats['cleared']) && $stats['cleared'] > 0) {
                    $message .= "{$stats['cleared']} existing entries cleared, ";
                }
                
                $message .= (isset($stats['entries_created']) ? $stats['entries_created'] : 0) . " new entries created";
                
                if (isset($stats['conflicts']) && $stats['conflicts'] > 0) {
                    $message .= ", {$stats['conflicts']} conflicts skipped";
                }
                if (isset($stats['skipped']) && $stats['skipped'] > 0) {
                    $message .= ", {$stats['skipped']} slots skipped";
                }
                
                toast()->success($message)->push();
            } else {
                toast()->warning("No entries created. Please check your settings and try again.")->push();
            }
            
        } catch (\Exception $e) {
            \Log::error("Auto-generate error: " . $e->getMessage());
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    /**
     * Check for teacher scheduling conflicts
     * 
     * @param string $day
     * @param int $periodId
     * @param int $teacherId
     * @return bool
     */
    protected function hasTeacherConflict($day, $periodId, $teacherId)
    {
        return TimetableSchedule::where('weekday', $day)
            ->where('period_id', $periodId)
            ->where('teacher_id', $teacherId)
            ->exists();
    }
    
    /**
     * Get effective timetable ID, falling back to timetable object if needed
     *
     * @return int|null
     */
    private function getEffectiveTimetableId()
    {
        // First try to use the direct property
        if (!empty($this->timetableRecordId)) {
            return $this->timetableRecordId;
        }
        
        // If that's empty, try to use the ID from the timetable object
        if (isset($this->timetable) && $this->timetable) {
            \Log::info("Using timetable object ID as fallback: {$this->timetable->id}");
            return $this->timetable->id;
        }
        
        // If both are empty, return null to indicate no valid ID
        return null;
    }
    
    /**
     * Print the timetable
     */
    public function printTimetable()
    {
        try {
            // Use the effective ID with fallback mechanism
            $timetableId = $this->getEffectiveTimetableId();
            $sectionId = $this->sectionId;
            
            if (empty($timetableId)) {
                \Log::error("Cannot print timetable - no valid timetable ID available");
                toast()->danger('Error: Cannot print - Timetable ID is missing')->push();
                return false;
            }
            
            // Check if timetable exists in database
            $timetable = SchoolTimetable::find($timetableId);
            if (!$timetable) {
                \Log::error("Cannot print timetable - timetable with ID {$timetableId} not found");
                toast()->danger('Error: Timetable not found')->push();
                return false;
            }
            
            // Generate URL for the print view
            try {
                $printUrl = route('tt.print', ['timetableId' => $timetableId, 'sectionId' => $sectionId]);
                
                // Log the generated URL for debugging
                \Log::info("Print URL generated: {$printUrl}");
                
                // Dispatch event to open the print window in a new tab
                // Using both methods for compatibility
                $this->dispatch('openPrintWindow', ['url' => $printUrl]);
                $this->dispatchBrowserEvent('openPrintWindow', ['url' => $printUrl]);
                
                // As a fallback, we'll also pass the URL to the browser's session storage
                // This allows a JavaScript fallback to pick it up if events fail
                session()->flash('print_url', $printUrl);
                
                // Return the URL for direct access if needed
                return $printUrl;
            } catch (\Exception $e) {
                // If route generation fails, try direct URL
                \Log::error("Error generating print URL: " . $e->getMessage());
                
                // Fallback to direct URL construction
                $printUrl = url("timetables/print/{$timetableId}" . ($sectionId ? "/{$sectionId}" : ""));
                \Log::info("Using fallback print URL: {$printUrl}");
                
                // Dispatch with fallback URL
                $this->dispatch('openPrintWindow', ['url' => $printUrl]);
                $this->dispatchBrowserEvent('openPrintWindow', ['url' => $printUrl]);
                session()->flash('print_url', $printUrl);
                
                return $printUrl;
            }
        } catch (\Exception $e) {
            \Log::error("Exception in printTimetable: " . $e->getMessage());
            toast()->danger('Error printing timetable: ' . $e->getMessage())->push();
            return false;
        }
    }
    
    /**
     * Export the timetable as PDF
     */
    public function exportPDF()
    {
        try {
            // Use the effective ID with fallback mechanism
            $timetableId = $this->getEffectiveTimetableId();
            $sectionId = $this->sectionId;
            
            if (empty($timetableId)) {
                \Log::error("Cannot export PDF - no valid timetable ID available");
                toast()->danger('Error: Cannot export - Timetable ID is missing')->push();
                return false;
            }
            
            // Check if timetable exists in database
            $timetable = SchoolTimetable::find($timetableId);
            if (!$timetable) {
                \Log::error("Cannot export PDF - timetable with ID {$timetableId} not found");
                toast()->danger('Error: Timetable not found')->push();
                return false;
            }
            
            // Generate URL for the PDF export
            try {
                $pdfUrl = route('tt.export.pdf', ['timetableId' => $timetableId, 'sectionId' => $sectionId]);
                
                // Log the generated URL for debugging
                \Log::info("PDF URL generated: {$pdfUrl}");
                
                // Dispatch event to trigger the download
                // Using both methods for compatibility
                $this->dispatch('triggerDownload', ['url' => $pdfUrl]);
                $this->dispatchBrowserEvent('triggerDownload', ['url' => $pdfUrl]);
                
                // As a fallback, we'll also pass the URL to the browser's session storage
                // This allows a JavaScript fallback to pick it up if events fail
                session()->flash('download_url', $pdfUrl);
                
                // Perform a direct redirect as a last resort
                // This will work even if JavaScript events fail
                return redirect()->to($pdfUrl);
            } catch (\Exception $e) {
                // If route generation fails, try direct URL
                \Log::error("Error generating PDF URL: " . $e->getMessage());
                
                // Fallback to direct URL construction
                $pdfUrl = url("timetables/export/pdf/{$timetableId}" . ($sectionId ? "/{$sectionId}" : ""));
                \Log::info("Using fallback PDF URL: {$pdfUrl}");
                
                // Dispatch with fallback URL
                $this->dispatch('triggerDownload', ['url' => $pdfUrl]);
                $this->dispatchBrowserEvent('triggerDownload', ['url' => $pdfUrl]);
                session()->flash('download_url', $pdfUrl);
                
                return redirect()->to($pdfUrl);
            }
        } catch (\Exception $e) {
            \Log::error("Exception in exportPDF: " . $e->getMessage());
            toast()->danger('Error exporting PDF: ' . $e->getMessage())->push();
            return false;
        }
    }
    
    /**
     * Export the timetable as Excel
     */
    public function exportExcel()
    {
        try {
            // Use the effective ID with fallback mechanism
            $timetableId = $this->getEffectiveTimetableId();
            $sectionId = $this->sectionId;
            
            if (empty($timetableId)) {
                \Log::error("Cannot export Excel - no valid timetable ID available");
                toast()->danger('Error: Cannot export - Timetable ID is missing')->push();
                return false;
            }
            
            // Check if timetable exists in database
            $timetable = SchoolTimetable::find($timetableId);
            if (!$timetable) {
                \Log::error("Cannot export Excel - timetable with ID {$timetableId} not found");
                toast()->danger('Error: Timetable not found')->push();
                return false;
            }
            
            // Generate URL for the Excel export
            try {
                $excelUrl = route('tt.export.excel', ['timetableId' => $timetableId, 'sectionId' => $sectionId]);
                
                // Log the generated URL for debugging
                \Log::info("Excel URL generated: {$excelUrl}");
                
                // Dispatch event to trigger the download
                // Using both methods for compatibility
                $this->dispatch('triggerDownload', ['url' => $excelUrl]);
                $this->dispatchBrowserEvent('triggerDownload', ['url' => $excelUrl]);
                
                // As a fallback, we'll also pass the URL to the browser's session storage
                // This allows a JavaScript fallback to pick it up if events fail
                session()->flash('download_url', $excelUrl);
                
                // Perform a direct redirect as a last resort
                // This will work even if JavaScript events fail
                return redirect()->to($excelUrl);
            } catch (\Exception $e) {
                // If route generation fails, try direct URL
                \Log::error("Error generating Excel URL: " . $e->getMessage());
                
                // Fallback to direct URL construction
                $excelUrl = url("timetables/export/excel/{$timetableId}" . ($sectionId ? "/{$sectionId}" : ""));
                \Log::info("Using fallback Excel URL: {$excelUrl}");
                
                // Dispatch with fallback URL
                $this->dispatch('triggerDownload', ['url' => $excelUrl]);
                $this->dispatchBrowserEvent('triggerDownload', ['url' => $excelUrl]);
                session()->flash('download_url', $excelUrl);
                
                return redirect()->to($excelUrl);
            }
        } catch (\Exception $e) {
            \Log::error("Exception in exportExcel: " . $e->getMessage());
            toast()->danger('Error exporting Excel: ' . $e->getMessage())->push();
            return false;
        }
    }

    /**
     * Get the current time slots based on the current time
     * This helps to highlight the current active periods
     * 
     * @return array
     */
    public function getCurrentTimeSlots()
    {
        $now = now();
        $currentDay = strtolower($now->format('l'));
        $currentTime = $now->format('H:i:s');
        
        $periods = $this->getPeriodsProperty();
        
        $currentPeriods = $periods->filter(function($period) use ($currentTime) {
            return $currentTime >= $period->start_time && $currentTime <= $period->end_time;
        });
        
        return [
            'day' => $currentDay,
            'time' => $currentTime,
            'periods' => $currentPeriods
        ];
    }

    /**
     * Get background color class for period based on its type
     * 
     * @param mixed $period
     * @return string
     */
    public function getPeriodTypeClass($period)
    {
        if (!$period) {
            return 'bg-gray-50';
        }
        
        $name = strtolower($period->period_name ?? '');
        
        if (str_contains($name, 'break') || str_contains($name, 'lunch') || str_contains($name, 'recess')) {
            return 'bg-green-50 text-green-700 border-green-100';
        } elseif (str_contains($name, 'assembly') || str_contains($name, 'homeroom')) {
            return 'bg-blue-50 text-blue-700 border-blue-100';
        } elseif (str_contains($name, 'prep') || str_contains($name, 'study')) {
            return 'bg-indigo-50 text-indigo-700 border-indigo-100';
        } elseif (str_contains($name, 'saturday') || str_contains($name, 'sunday') || str_contains($name, 'weekend')) {
            return 'bg-purple-50 text-purple-700 border-purple-100';
        } else {
            return 'bg-gray-50 text-gray-700 border-gray-100';
        }
    }

    /**
     * Get the next upcoming period
     * 
     * @return mixed
     */
    public function getNextPeriod()
    {
        $now = now();
        $currentTime = $now->format('H:i:s');
        
        $periods = $this->getPeriodsProperty();
        
        return $periods
            ->where('start_time', '>', $currentTime)
            ->sortBy('start_time')
            ->first();
    }

    /**
     * Refresh the current periods data without full page reload
     * This is called from Alpine.js to update the current period indicator
     */
    public function refreshCurrentPeriods()
    {
        $this->dispatch('current-periods-updated', [
            'current' => $this->getCurrentTimeSlots(),
            'next' => $this->getNextPeriod() ? [
                'name' => $this->getNextPeriod()->period_name,
                'start_time' => date('h:i A', strtotime($this->getNextPeriod()->start_time)),
                'diff' => \Carbon\Carbon::parse($this->getNextPeriod()->start_time)->diffForHumans(now(), ['parts' => 1, 'short' => true])
            ] : null
        ]);
    }

    /**
     * Get subjects for the current class and section
     * Prioritizing subjects that have teacher assignments
     * 
     * @param int $classId
     * @param int|null $sectionId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getSubjectsForClass($classId, $sectionId = null)
    {
        // Get the current timetable record to extract academic year and term
        $timetableRecord = SchoolTimetable::find($this->timetableRecordId);
        $academicSession = $timetableRecord ? $timetableRecord->academic_session : null;
        $academicTerm = $timetableRecord ? $timetableRecord->academic_term : null;
        
        \Log::debug("Getting subjects for class ID $classId" . 
            ($sectionId ? ", section ID $sectionId" : "") . 
            ", academic session: $academicSession, term: $academicTerm");
        
        // Get the subjects associated with teacher-subject assignments for this class/section
        $query = TeacherSubjectAssignment::where('class_id', $classId)
            ->where('is_active', true);
            
        // Apply section filter if provided (allowing both specific section and class-wide assignments)
        if ($sectionId) {
            $query->where(function($q) use ($sectionId) {
                    $q->where('section_id', $sectionId)
                      ->orWhereNull('section_id');
                });
        }
            
        // Filter by academic year if available
        if ($academicSession) {
            $query->where(function($q) use ($academicSession) {
                $q->where('academic_year_id', $academicSession)
                  ->orWhereNull('academic_year_id');
            });
        }
            
        // Filter by academic term if available
        if ($academicTerm) {
            $query->where(function($q) use ($academicTerm) {
                $q->where('academic_term', $academicTerm)
                  ->orWhereNull('academic_term');
            });
        }
            
        $subjectIds = $query->pluck('subject_id')->unique()->toArray();
            
        \Log::debug("Found " . count($subjectIds) . " subject IDs from teacher assignments");
        
        // If no subjects found from assignments, try less restricted query (by session only)
        if (empty($subjectIds) && $academicSession) {
            $backupQuery = TeacherSubjectAssignment::where('class_id', $classId)
                ->where('is_active', true);
                
            if ($sectionId) {
                $backupQuery->where(function($q) use ($sectionId) {
                    $q->where('section_id', $sectionId)
                      ->orWhereNull('section_id');
                });
            }
                
            $backupQuery->where(function($q) use ($academicSession) {
                $q->where('academic_year_id', $academicSession)
                  ->orWhereNull('academic_year_id');
            });
                
            $subjectIds = $backupQuery->pluck('subject_id')->unique()->toArray();
            
            \Log::debug("Found " . count($subjectIds) . " subject IDs from backup teacher assignments (by session only)");
        }
        
        // If still no subjects found, try with no filters
        if (empty($subjectIds)) {
            $finalBackupQuery = TeacherSubjectAssignment::where('class_id', $classId)
                ->where('is_active', true);
                
            if ($sectionId) {
                $finalBackupQuery->where(function($q) use ($sectionId) {
                    $q->where('section_id', $sectionId)
                      ->orWhereNull('section_id');
                });
            }
                
            $subjectIds = $finalBackupQuery->pluck('subject_id')->unique()->toArray();
            
            \Log::debug("Found " . count($subjectIds) . " subject IDs from final backup teacher assignments (no filters)");
        }
            
        // If still no subjects found from assignments, return all subjects as a fallback
        if (empty($subjectIds)) {
            \Log::warning("No subjects found from teacher assignments, returning all subjects as fallback");
            return Subject::with('category')
                ->orderBy('subject_name')
                ->limit(15) // Limit to prevent overwhelming the form
                ->get();
        }
            
        // Now get the full subject details with their categories
        $subjects = Subject::with('category')
            ->whereIn('id', $subjectIds)
            ->orderBy('subject_name')
            ->get();
            
        \Log::info("Retrieved {$subjects->count()} subjects for class ID $classId" . 
            ($sectionId ? ", section ID $sectionId" : ""));
            
        return $subjects;
    }

    /**
     * Refresh the timetable data without a full page reload
     */
    public function refreshTimetable()
    {
        // Rebuild the timetable matrix
        $this->buildTimetableMatrix();
        
        // Check if there are weekend slots
        $this->checkWeekendSlots();
        
        // Fire an event to let any JavaScript know we've refreshed
        $this->dispatch('timetableRefreshed');
    }

    /**
     * Get CSS classes for time preference match
     * 
     * @param int $subjectId
     * @param object $period
     * @return string
     */
    public function getTimePreferenceClass($subjectId, $period)
    {
        try {
            // If no subject, just return default
            if (empty($subjectId) || empty($period)) {
                return 'bg-white';
            }
            
            // Get subject preferences
            $subjectPreferences = $this->autoGenerateForm['subject_preferences'][$subjectId] ?? null;
            if (!$subjectPreferences) {
                return 'bg-white';
            }
            
            // Get preferred time
            $preferredTime = $subjectPreferences['preferred_time'] ?? 'any';
            if ($preferredTime === 'any') {
                return 'bg-white'; // No specific preference
            }
            
            // Determine time of day for this period
            $periodTime = $this->getPeriodTimeOfDay($period);
            
            // Perfect match - preferred time matches actual time
            if ($preferredTime === $periodTime) {
                return 'bg-green-50 border-green-200';  // Green for optimal match
            }
            
            // Acceptable match - adjacent time periods
            if (
                ($preferredTime === 'morning' && $periodTime === 'midday') || 
                ($preferredTime === 'midday' && ($periodTime === 'morning' || $periodTime === 'afternoon')) ||
                ($preferredTime === 'afternoon' && $periodTime === 'midday')
            ) {
                return 'bg-yellow-50 border-yellow-200';  // Yellow for acceptable match
            }
            
            // Poor match - opposite time periods
            return 'bg-red-50 border-red-200';  // Red for poor match
            
        } catch (\Exception $e) {
            \Log::error("Error in getTimePreferenceClass: " . $e->getMessage());
            return 'bg-white';
        }
    }
    
    /**
     * Determine the time of day for a period
     * 
     * @param object $period
     * @return string
     */
    private function getPeriodTimeOfDay($period)
    {
        try {
            // Convert start time to minutes since midnight
            $startTime = $period->start_time;
            $parts = explode(':', $startTime);
            $minutes = (intval($parts[0]) * 60) + intval($parts[1]);
            
            // Get midpoint of period for more accurate categorization
            $endTime = $period->end_time;
            $endParts = explode(':', $endTime);
            $endMinutes = (intval($endParts[0]) * 60) + intval($endParts[1]);
            
            // Use the midpoint of the period
            $midpointMinutes = ($minutes + $endMinutes) / 2;
            
            // Use time preferences from auto-generate form
            $timePreferences = $this->autoGenerateForm['time_preferences'];
            
            if ($midpointMinutes <= $timePreferences['morning_end']) {
                return 'morning';
            } elseif ($midpointMinutes <= $timePreferences['midday_end']) {
                return 'midday';
            } else {
                return 'afternoon';
            }
        } catch (\Exception $e) {
            \Log::error("Error determining time of day for period: " . $e->getMessage());
            return 'any'; // Default to any time if there's an error
        }
    }

    /**
     * Gets default subject preferences for the auto-generate form
     * 
     * @param \App\Models\Subject $subject
     * @return array
     */
    private function getDefaultSubjectPreferences($subject): array
    {
        try {
            // Get current academic session
            $currentSession = Setting::where('key', 'current_session')->first()->value ?? date('Y');
            
            // Initialize with default values
            $preferences = [
                'name' => $subject->subject_name,
                'category' => $subject->category ? $subject->category->name : 'Uncategorized',
                'category_id' => $subject->category ? $subject->category->id : null,
                'teacher_id' => null,
                'teacher_name' => null,
                'preferred_time' => 'any',
                'max_consecutive' => 2,
                'weekly_frequency' => 3,
                'daily_limit' => 1
            ];
            
            // Log the subject for debugging
            \Log::debug("Setting up preferences for subject: {$subject->subject_name} (ID: {$subject->id})");
            
            // Try to get teacher assignments for this subject
            $teacherAssignment = TeacherSubjectAssignment::where('subject_id', $subject->id)
                ->where('class_id', $this->timetable->class_id)
                ->when($this->sectionId, function($query) {
                    return $query->where(function($q) {
                        $q->where('section_id', $this->sectionId)
                          ->orWhereNull('section_id');
                    });
                })
                ->where('is_active', true)
                ->first();
                
            if ($teacherAssignment && $teacherAssignment->teacher_id) {
                $teacher = User::find($teacherAssignment->teacher_id);
                if ($teacher) {
                    $preferences['teacher_id'] = $teacher->id;
                    $preferences['teacher_name'] = $teacher->name;
                    \Log::debug("Found teacher {$teacher->name} for subject {$subject->subject_name}");
                }
            } else {
                \Log::debug("No teacher assignment found for subject {$subject->subject_name}");
            }
            
            // Set preferred time based on subject category
            if ($subject->category) {
                $categoryName = strtolower($subject->category->name);
                
                // Core subjects like Math/Science are best in morning
                if (str_contains($categoryName, 'math') || 
                    str_contains($categoryName, 'science') || 
                    str_contains($subject->subject_name, 'Math') || 
                    str_contains($subject->subject_name, 'Science')) {
                    $preferences['preferred_time'] = 'morning';
                    $preferences['weekly_frequency'] = 5; // Core subjects typically have higher frequency
                }
                
                // Languages also benefit from morning slots
                elseif (str_contains($categoryName, 'language') || 
                        str_contains($subject->subject_name, 'English') || 
                        str_contains($subject->subject_name, 'Language')) {
                    $preferences['preferred_time'] = 'morning';
                    $preferences['weekly_frequency'] = 5;
                }
                
                // Arts, creative subjects in afternoon
                elseif (str_contains($categoryName, 'art') || 
                        str_contains($categoryName, 'music') || 
                        str_contains($categoryName, 'creative')) {
                    $preferences['preferred_time'] = 'afternoon';
                    $preferences['weekly_frequency'] = 2;
                    $preferences['max_consecutive'] = 3; // Art classes often work better as longer blocks
                }
                
                // Physical education in afternoon
                elseif (str_contains($categoryName, 'physical') || 
                        str_contains($categoryName, 'sport') || 
                        str_contains($subject->subject_name, 'P.E') || 
                        str_contains($subject->subject_name, 'Physical')) {
                    $preferences['preferred_time'] = 'afternoon';
                    $preferences['weekly_frequency'] = 2;
                    $preferences['max_consecutive'] = 2;
                }
                
                // Computer classes
                elseif (str_contains($categoryName, 'computer') || 
                        str_contains($categoryName, 'technology') || 
                        str_contains($subject->subject_name, 'Computer') || 
                        str_contains($subject->subject_name, 'ICT')) {
                    $preferences['preferred_time'] = 'midday';
                    $preferences['weekly_frequency'] = 2;
                }
                
                // Default for other subjects
                else {
                    $preferences['preferred_time'] = 'any';
                    $preferences['weekly_frequency'] = 3;
                }
            }
            
            return $preferences;
        } catch (\Exception $e) {
            \Log::error("Error generating preferences for subject ID {$subject->id}: " . $e->getMessage());
            
            // Return safe defaults in case of any error
            return [
                'name' => $subject->subject_name ?? 'Unknown Subject',
                'category' => 'Uncategorized',
                'category_id' => null,
                'teacher_id' => null,
                'teacher_name' => null,
                'preferred_time' => 'any',
                'max_consecutive' => 2,
                'weekly_frequency' => 3,
                'daily_limit' => 1
            ];
        }
    }

    /**
     * Get all subject categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSubjectCategoriesProperty()
    {
        try {
            $categories = SubjectCategory::all();
            \Log::debug("Loaded ".count($categories)." subject categories for timetable view");
            return $categories;
        } catch (\Exception $e) {
            \Log::error("Error loading subject categories: " . $e->getMessage());
            return collect();
        }
    }
} 