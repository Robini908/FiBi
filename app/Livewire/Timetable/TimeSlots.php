<?php

namespace App\Livewire\Timetable;

use App\Models\SchoolTimetable;
use App\Models\TimetablePeriod;
use App\Models\TimetableSchedule;
use Illuminate\Support\Collection;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TimeSlotsExport;

class TimeSlots extends Component
{
    use WireToast;

    public $timetableRecordId;
    public $timetable;
    public Collection $timeSlots;
    
    // Properties for categorized time slots
    public Collection $weekdayTimeSlots;
    public Collection $weekendTimeSlots;
    public Collection $prepTimeSlots;
    public $hasWeekendSlots = false;
    public $hasPrepSlots = false;
    
    public $showModal = false;
    public $showAutoGenerateModal = false;
    public $showDeleteModal = false;
    public $isEditing = false;
    public $timeSlotToDelete = null;
    public $cardMode = false; // Controls whether we're in card mode
    public $activeCard = null; // Which card is active: 'create', 'edit', 'auto-generate'
    
    public $timeSlotForm = [
        'id' => null,
        'timetable_id' => null,
        'start_time' => '',
        'end_time' => '',
        'period_name' => '',
        'period_order' => 0,
    ];
    
    public $autoGenerateForm = [
        'school_start_time' => '08:00',
        'school_end_time' => '16:00',
        'lesson_duration' => 40,      // minutes
        'break_duration' => 20,       // minutes
        'lunch_duration' => 60,       // minutes
        'extracurricular_start_time' => '15:30', // Games/Activities start time in HH:MM format
        'extracurricular_end_time' => '17:00',   // Games/Activities end time in HH:MM format
        'break_after_lessons' => 2,   // After how many lessons should a short break occur
        'lunch_time' => '12:30',      // Lunch time in HH:MM format
        'include_lunch' => true,      // Whether to include lunch break
        'include_breaks' => true,     // Whether to include short breaks
        'include_extracurricular' => false, // Whether to include games/activities break
        'transition_duration' => 5,   // minutes allocated for movement between classes
        'include_transitions' => true, // Whether to include transition times between periods
        
        // Weekend Classes Configuration
        'include_weekend_classes' => false, // Whether to include weekend classes
        'weekend_day' => 'saturday',   // Which weekend day to schedule classes (saturday/sunday/both)
        'weekend_start_time' => '09:00', // Weekend classes start time
        'weekend_end_time' => '13:00',  // Weekend classes end time 
        'weekend_lesson_duration' => 60, // Duration of weekend lessons (usually longer)
        
        // Prep Activities Configuration
        'include_morning_prep' => false, // Whether to include morning prep
        'morning_prep_start_time' => '06:30', // Morning prep start time
        'morning_prep_end_time' => '07:30',   // Morning prep end time
        
        'include_evening_prep' => false, // Whether to include evening prep
        'evening_prep_start_time' => '19:00', // Evening prep start time
        'evening_prep_end_time' => '21:00',   // Evening prep end time
    ];
    
    protected $rules = [
        'timeSlotForm.start_time' => 'required|date_format:H:i',
        'timeSlotForm.end_time' => 'required|date_format:H:i|after:timeSlotForm.start_time',
        'timeSlotForm.period_name' => 'nullable|string|max:50',
        'timeSlotForm.period_order' => 'required|integer|min:0',
    ];

    protected $autoGenerateRules = [
        'autoGenerateForm.school_start_time' => 'required|date_format:H:i',
        'autoGenerateForm.school_end_time' => 'required|date_format:H:i|after:autoGenerateForm.school_start_time',
        'autoGenerateForm.lesson_duration' => 'required|integer|min:20|max:120',
        'autoGenerateForm.break_duration' => 'required|integer|min:5|max:60',
        'autoGenerateForm.lunch_duration' => 'required|integer|min:30|max:120',
        'autoGenerateForm.extracurricular_start_time' => 'required|date_format:H:i',
        'autoGenerateForm.extracurricular_end_time' => 'required|date_format:H:i|after:autoGenerateForm.extracurricular_start_time',
        'autoGenerateForm.break_after_lessons' => 'required|integer|min:1|max:6',
        'autoGenerateForm.lunch_time' => 'required|date_format:H:i',
        'autoGenerateForm.transition_duration' => 'required|integer|min:0|max:15',
        
        // Weekend Classes Validation Rules
        'autoGenerateForm.weekend_start_time' => 'required_if:autoGenerateForm.include_weekend_classes,true|date_format:H:i',
        'autoGenerateForm.weekend_end_time' => 'required_if:autoGenerateForm.include_weekend_classes,true|date_format:H:i|after:autoGenerateForm.weekend_start_time',
        'autoGenerateForm.weekend_lesson_duration' => 'required_if:autoGenerateForm.include_weekend_classes,true|integer|min:30|max:120',
        
        // Prep Activities Validation Rules
        'autoGenerateForm.morning_prep_start_time' => 'required_if:autoGenerateForm.include_morning_prep,true|date_format:H:i',
        'autoGenerateForm.morning_prep_end_time' => 'required_if:autoGenerateForm.include_morning_prep,true|date_format:H:i|after:autoGenerateForm.morning_prep_start_time',
        'autoGenerateForm.evening_prep_start_time' => 'required_if:autoGenerateForm.include_evening_prep,true|date_format:H:i',
        'autoGenerateForm.evening_prep_end_time' => 'required_if:autoGenerateForm.include_evening_prep,true|date_format:H:i|after:autoGenerateForm.evening_prep_start_time',
    ];

    // Export variables
    protected $listeners = ['exportToPdf', 'exportToExcel', 'copyToClipboard'];

    public function mount()
    {
        \Log::debug("TimeSlots: Component mounted");
        $this->timeSlots = new Collection();
        $this->bootTimeSlotsComponent();
    }

    public function bootTimeSlotsComponent()
    {
        if (!$this->timetableRecordId) {
            $this->timetableRecordId = request()->get('timetable');
        }

        $this->setupAutoGenerateRules();
        $this->resetAutoGenerateForm();
        
        if ($this->timetableRecordId) {
        $this->loadData();
        }
    }
    
    private function setupAutoGenerateRules()
    {
        $this->autoGenerateRules = [
            'autoGenerateForm.school_start_time' => 'required|date_format:H:i',
            'autoGenerateForm.school_end_time' => 'required|date_format:H:i|after:autoGenerateForm.school_start_time',
            'autoGenerateForm.lesson_duration' => 'required|integer|min:10|max:120',
            'autoGenerateForm.break_duration' => 'required|integer|min:5|max:60',
            'autoGenerateForm.break_after_lessons' => 'required|integer|min:1|max:10',
            'autoGenerateForm.include_breaks' => 'boolean',
            'autoGenerateForm.include_transitions' => 'boolean',
            'autoGenerateForm.transition_duration' => 'required_if:autoGenerateForm.include_transitions,true|integer|min:1|max:30',
            'autoGenerateForm.include_lunch' => 'boolean',
            'autoGenerateForm.lunch_time' => 'required_if:autoGenerateForm.include_lunch,true|date_format:H:i',
            'autoGenerateForm.lunch_duration' => 'required_if:autoGenerateForm.include_lunch,true|integer|min:15|max:90',
            'autoGenerateForm.include_extracurricular' => 'boolean',
            'autoGenerateForm.extracurricular_start_time' => 'required_if:autoGenerateForm.include_extracurricular,true|date_format:H:i',
            'autoGenerateForm.extracurricular_end_time' => 'required_if:autoGenerateForm.include_extracurricular,true|date_format:H:i|after:autoGenerateForm.extracurricular_start_time',
            'autoGenerateForm.include_weekend_classes' => 'boolean',
            'autoGenerateForm.weekend_day' => 'required_if:autoGenerateForm.include_weekend_classes,true|in:saturday,sunday,both',
            'autoGenerateForm.weekend_start_time' => 'required_if:autoGenerateForm.include_weekend_classes,true|date_format:H:i',
            'autoGenerateForm.weekend_end_time' => 'required_if:autoGenerateForm.include_weekend_classes,true|date_format:H:i|after:autoGenerateForm.weekend_start_time',
            'autoGenerateForm.weekend_lesson_duration' => 'required_if:autoGenerateForm.include_weekend_classes,true|integer|min:30|max:180',
            'autoGenerateForm.include_morning_prep' => 'boolean',
            'autoGenerateForm.morning_prep_start_time' => 'required_if:autoGenerateForm.include_morning_prep,true|date_format:H:i',
            'autoGenerateForm.morning_prep_end_time' => 'required_if:autoGenerateForm.include_morning_prep,true|date_format:H:i|after:autoGenerateForm.morning_prep_start_time',
            'autoGenerateForm.include_evening_prep' => 'boolean',
            'autoGenerateForm.evening_prep_start_time' => 'required_if:autoGenerateForm.include_evening_prep,true|date_format:H:i',
            'autoGenerateForm.evening_prep_end_time' => 'required_if:autoGenerateForm.include_evening_prep,true|date_format:H:i|after:autoGenerateForm.evening_prep_start_time',
        ];
    }
    
    public function loadData()
    {
        $this->timetable = SchoolTimetable::find($this->timetableRecordId);
        
        if (!$this->timetable) {
            toast()->danger('Timetable record not found')->push();
            return;
        }
        
        // Get all time slots, ordered by start time
        $this->timeSlots = TimetablePeriod::where('timetable_id', $this->timetableRecordId)
            ->ordered()
            ->get();
            
        // Categorize the time slots
        $this->categorizeTimeSlots();
    }
    
    /**
     * Categorize time slots into weekday, weekend, and prep categories
     */
    private function categorizeTimeSlots()
    {
        // Initialize collections
        $this->weekdayTimeSlots = collect();
        $this->weekendTimeSlots = collect();
        $this->prepTimeSlots = collect();
        
        // Reset flags
        $this->hasWeekendSlots = false;
        $this->hasPrepSlots = false;
        
        // Skip if no time slots
        if ($this->timeSlots->isEmpty()) {
            return;
        }
        
        // Categorize each time slot based on its name
        foreach ($this->timeSlots as $slot) {
            $periodName = strtolower($slot->period_name ?? '');
            
            // Check for weekend classes (Saturday or Sunday in name)
            if (str_contains($periodName, 'saturday') || str_contains($periodName, 'sunday') || 
                str_contains($periodName, 'weekend')) {
                $this->weekendTimeSlots->push($slot);
                $this->hasWeekendSlots = true;
            }
            // Check for prep periods (Morning Prep or Evening Prep)
            else if (str_contains($periodName, 'prep')) {
                $this->prepTimeSlots->push($slot);
                $this->hasPrepSlots = true;
            }
            // Default to weekday periods
            else {
                $this->weekdayTimeSlots->push($slot);
            }
        }
        
        // Sort each category by period start time
        $this->weekdayTimeSlots = $this->weekdayTimeSlots->sortBy('start_time');
        $this->weekendTimeSlots = $this->weekendTimeSlots->sortBy('start_time');
        $this->prepTimeSlots = $this->prepTimeSlots->sortBy('start_time');
    }
    
    /**
     * Get period type based on its name for styling purposes
     */
    public function getPeriodType($periodName)
    {
        $lowerName = strtolower($periodName ?? '');
        
        // Define keywords for different period types
        $breakKeywords = ['break', 'recess', 'short break'];
        $lunchKeywords = ['lunch', 'meal', 'dining'];
        $movementKeywords = ['movement', 'transition'];
        $gameKeywords = ['game', 'extracurricular', 'activities', 'sport'];
        $prepKeywords = ['prep', 'study', 'revision'];
        $weekendKeywords = ['saturday', 'sunday', 'weekend'];
        
        // Return the type based on keywords
        foreach ($breakKeywords as $keyword) {
            if (str_contains($lowerName, $keyword)) return 'break';
        }
        
        foreach ($lunchKeywords as $keyword) {
            if (str_contains($lowerName, $keyword)) return 'lunch';
        }
        
        foreach ($movementKeywords as $keyword) {
            if (str_contains($lowerName, $keyword)) return 'transition';
        }
        
        foreach ($gameKeywords as $keyword) {
            if (str_contains($lowerName, $keyword)) return 'extracurricular';
        }
        
        foreach ($prepKeywords as $keyword) {
            if (str_contains($lowerName, $keyword)) return 'prep';
        }
        
        foreach ($weekendKeywords as $keyword) {
            if (str_contains($lowerName, $keyword)) return 'weekend';
        }
        
        // Default to lesson
        return 'lesson';
    }
    
    /**
     * Get CSS classes for period background based on type
     */
    public function getPeriodBgClass($periodName)
    {
        $type = $this->getPeriodType($periodName);
        
        return match($type) {
            'break' => 'bg-blue-50 border-blue-200',
            'lunch' => 'bg-yellow-50 border-yellow-200',
            'transition' => 'bg-gray-50 border-gray-200',
            'extracurricular' => 'bg-purple-50 border-purple-200',
            'prep' => 'bg-amber-50 border-amber-200',
            'weekend' => 'bg-indigo-50 border-indigo-200',
            default => 'bg-green-50 border-green-200', // lesson
        };
    }
    
    /**
     * Get CSS classes for period text color based on type
     */
    public function getPeriodTextClass($periodName)
    {
        $type = $this->getPeriodType($periodName);
        
        return match($type) {
            'break' => 'text-blue-700',
            'lunch' => 'text-yellow-700',
            'transition' => 'text-gray-700',
            'extracurricular' => 'text-purple-700',
            'prep' => 'text-amber-700',
            'weekend' => 'text-indigo-700',
            default => 'text-green-700', // lesson
        };
    }
    
    /**
     * Get CSS classes for period indicator based on type
     */
    public function getPeriodIndicatorClass($periodName)
    {
        $type = $this->getPeriodType($periodName);
        
        return match($type) {
            'break' => 'bg-blue-400',
            'lunch' => 'bg-yellow-400',
            'transition' => 'bg-gray-400',
            'extracurricular' => 'bg-purple-400',
            'prep' => 'bg-amber-400',
            'weekend' => 'bg-indigo-400',
            default => 'bg-green-400', // lesson
        };
    }
    
    public function createTimeSlot()
    {
        // Find the next available order number
        $nextOrder = 1;
        if ($this->timeSlots->count() > 0) {
            $nextOrder = $this->timeSlots->max('period_order') + 1;
        }
        
        $this->timeSlotForm = [
            'id' => null,
            'timetable_id' => $this->timetableRecordId,
            'start_time' => '',
            'end_time' => '',
            'period_name' => 'Period ' . $nextOrder,
            'period_order' => $nextOrder,
        ];
        
        $this->isEditing = false;
        $this->showModal = true;
        $this->cardMode = true;
        $this->activeCard = 'create';
    }
    
    public function editTimeSlot($id)
    {
        $timeSlot = TimetablePeriod::findOrFail($id);
        
        $this->timeSlotForm = [
            'id' => $timeSlot->id,
            'timetable_id' => $timeSlot->timetable_id,
            'start_time' => substr($timeSlot->start_time, 0, 5), // Format as HH:MM
            'end_time' => substr($timeSlot->end_time, 0, 5), // Format as HH:MM
            'name' => $timeSlot->period_name,
            'period_order' => $timeSlot->period_order,
        ];
        
        $this->isEditing = true;
        $this->showModal = true;
        $this->cardMode = true;
        $this->activeCard = 'edit';
    }
    
    public function saveTimeSlot()
    {
        $this->validate();
        
        try {
            $data = [
                'timetable_id' => $this->timetableRecordId,
                'start_time' => $this->timeSlotForm['start_time'],
                'end_time' => $this->timeSlotForm['end_time'],
                'period_name' => $this->timeSlotForm['name'],
                'period_order' => $this->timeSlotForm['period_order'],
            ];
            
            if ($this->timeSlotForm['id']) {
                $timeSlot = TimetablePeriod::findOrFail($this->timeSlotForm['id']);
                $timeSlot->update($data);
                toast()->success('Time slot updated successfully')->push();
            } else {
                TimetablePeriod::create($data);
                toast()->success('Time slot created successfully')->push();
            }
            
            $this->closeModal();
            $this->loadData();
            
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    public function deleteTimeSlot($id = null)
    {
        try {
            // If $id is null, use the timeSlotToDelete property
            $timeSlotId = $id ?? $this->timeSlotToDelete;
            
            // Check if there are any entries using this time slot
            $hasEntries = TimetableSchedule::where('period_id', $timeSlotId)->exists();
            
            if ($hasEntries) {
                toast()->warning('Cannot delete this time slot as it has timetable entries. Delete the entries first.')->push();
                $this->showDeleteModal = false;
                return;
            }
            
            TimetablePeriod::findOrFail($timeSlotId)->delete();
            toast()->success('Time slot deleted successfully')->push();
            $this->loadData();
            $this->showDeleteModal = false; // Close the delete modal
            $this->timeSlotToDelete = null; // Reset the delete tracker
            
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
            $this->showDeleteModal = false; // Close the modal even if there's an error
        }
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->cardMode = false;
        $this->activeCard = null;
    }
    
    public function updatedAutoGenerateForm($value, $key)
    {
        // This method will be called whenever any value in autoGenerateForm is updated
        \Log::debug("Auto-generate form updated: {$key} = {$value}");
        
        // Extract the actual property name from the parameter key
        $propertyName = explode('.', $key)[1] ?? $key;
        
        // Ensure checkbox values are properly handled as booleans
        if (in_array($propertyName, [
            'include_breaks', 
            'include_lunch', 
            'include_extracurricular',
            'include_transitions',
            'include_weekend_classes',
            'include_morning_prep',
            'include_evening_prep'
        ])) {
            $this->autoGenerateForm[$propertyName] = (bool)$value;
            \Log::debug("Boolean field updated: {$propertyName} = " . ($this->autoGenerateForm[$propertyName] ? 'true' : 'false'));
        }
        // Ensure numeric values are cast to integers
        else if (in_array($propertyName, [
            'lesson_duration', 
            'break_duration', 
            'lunch_duration', 
            'transition_duration',
            'break_after_lessons',
            'weekend_lesson_duration'
        ])) {
            $this->autoGenerateForm[$propertyName] = (int)$value;
            \Log::debug("Numeric field updated: {$propertyName} = {$this->autoGenerateForm[$propertyName]}");
        }
        // Handle other fields (strings)
        else {
            $this->autoGenerateForm[$propertyName] = $value;
            \Log::debug("String field updated: {$propertyName} = {$this->autoGenerateForm[$propertyName]}");
        }
        
        // When break-related options change, log the current break settings
        if (strpos($propertyName, 'break') !== false || $propertyName === 'include_transitions') {
            \Log::debug("Break settings updated", [
                'include_breaks' => $this->autoGenerateForm['include_breaks'] ?? false,
                'break_duration' => $this->autoGenerateForm['break_duration'] ?? 0,
                'break_after_lessons' => $this->autoGenerateForm['break_after_lessons'] ?? 0,
                'include_transitions' => $this->autoGenerateForm['include_transitions'] ?? false,
                'transition_duration' => $this->autoGenerateForm['transition_duration'] ?? 0
            ]);
        }
    }
    
    public function openAutoGenerateModal()
    {
        $this->showAutoGenerateModal = true;
        $this->cardMode = true;
        $this->activeCard = 'auto-generate';
        $this->loadData();
        
        // Prefill the auto-generate form with existing time slots data
        if ($this->timeSlots->count() > 0) {
            // Check for weekday time slots
            if ($this->weekdayTimeSlots->count() > 0) {
                // Find earliest and latest time slots
                $earliestSlot = $this->weekdayTimeSlots->sortBy('start_time')->first();
                $latestSlot = $this->weekdayTimeSlots->sortByDesc('end_time')->first();
                
                if ($earliestSlot && $latestSlot) {
                    $this->autoGenerateForm['school_start_time'] = substr($earliestSlot->start_time, 0, 5);
                    $this->autoGenerateForm['school_end_time'] = substr($latestSlot->end_time, 0, 5);
                }
                
                // Get average lesson duration from existing lesson periods
                $lessonPeriods = $this->weekdayTimeSlots->filter(function($slot) {
                    return !str_contains(strtolower($slot->period_name), 'break') && 
                           !str_contains(strtolower($slot->period_name), 'lunch') &&
                           !str_contains(strtolower($slot->period_name), 'assembly') &&
                           !str_contains(strtolower($slot->period_name), 'movement');
                });
                
                if ($lessonPeriods->count() > 0) {
                    $totalDuration = 0;
                    foreach ($lessonPeriods as $period) {
                        $start = \Carbon\Carbon::parse($period->start_time);
                        $end = \Carbon\Carbon::parse($period->end_time);
                        $totalDuration += $start->diffInMinutes($end);
                    }
                    $averageDuration = ceil($totalDuration / $lessonPeriods->count());
                    $this->autoGenerateForm['lesson_duration'] = min(max($averageDuration, 30), 90);
                }
                
                // Prefill short and long break durations
                $breakPeriods = $this->weekdayTimeSlots->filter(function($slot) {
                    return str_contains(strtolower($slot->period_name), 'break');
                });
                
                if ($breakPeriods->count() > 0) {
                    $totalDuration = 0;
                    $breakCount = 0;
                    foreach ($breakPeriods as $period) {
                        $start = \Carbon\Carbon::parse($period->start_time);
                        $end = \Carbon\Carbon::parse($period->end_time);
                        $duration = $start->diffInMinutes($end);
                        
                        // If it's a long break (lunch), prefill lunch duration
                        if ($duration > 20) {
                            $this->autoGenerateForm['lunch_duration'] = min(max($duration, 30), 90);
                            
                            // Set lunch time to the start time of this long break
                            $this->autoGenerateForm['lunch_time'] = substr($period->start_time, 0, 5);
                            $this->autoGenerateForm['include_lunch'] = true;
                        } else {
                            $totalDuration += $duration;
                            $breakCount++;
                        }
                    }
                    
                    if ($breakCount > 0) {
                        $averageBreakDuration = ceil($totalDuration / $breakCount);
                        $this->autoGenerateForm['break_duration'] = min(max($averageBreakDuration, 10), 30);
                        $this->autoGenerateForm['include_breaks'] = true;
                    }
                }
                
                // Check for transition periods
                $transitionPeriods = $this->weekdayTimeSlots->filter(function($slot) {
                    return str_contains(strtolower($slot->period_name), 'movement') || 
                           str_contains(strtolower($slot->period_name), 'transition');
                });
                
                if ($transitionPeriods->count() > 0) {
                    $totalDuration = 0;
                    foreach ($transitionPeriods as $period) {
                        $start = \Carbon\Carbon::parse($period->start_time);
                        $end = \Carbon\Carbon::parse($period->end_time);
                        $totalDuration += $start->diffInMinutes($end);
                    }
                    $averageDuration = ceil($totalDuration / $transitionPeriods->count());
                    $this->autoGenerateForm['transition_duration'] = min(max($averageDuration, 1), 15);
                    $this->autoGenerateForm['include_transitions'] = true;
                }
                
                // Check for extracurricular activities
                $extracurricularPeriods = $this->weekdayTimeSlots->filter(function($slot) {
                    return str_contains(strtolower($slot->period_name), 'game') || 
                           str_contains(strtolower($slot->period_name), 'sport') || 
                           str_contains(strtolower($slot->period_name), 'activit') || 
                           str_contains(strtolower($slot->period_name), 'extracurricular');
                });
                
                if ($extracurricularPeriods->count() > 0) {
                    $firstPeriod = $extracurricularPeriods->sortBy('start_time')->first();
                    $lastPeriod = $extracurricularPeriods->sortByDesc('end_time')->first();
                    
                    if ($firstPeriod && $lastPeriod) {
                        $this->autoGenerateForm['extracurricular_start_time'] = substr($firstPeriod->start_time, 0, 5);
                        $this->autoGenerateForm['extracurricular_end_time'] = substr($lastPeriod->end_time, 0, 5);
                        $this->autoGenerateForm['include_extracurricular'] = true;
                    }
                }
            }
            
            // Check for weekend time slots
            if ($this->weekendTimeSlots->count() > 0) {
                $this->autoGenerateForm['include_weekend_classes'] = true;
                
                // Determine weekend day setting
                $hasSaturday = $this->weekendTimeSlots->contains(function($slot) {
                    return str_contains(strtolower($slot->period_name), 'saturday');
                });
                
                $hasSunday = $this->weekendTimeSlots->contains(function($slot) {
                    return str_contains(strtolower($slot->period_name), 'sunday');
                });
                
                if ($hasSaturday && $hasSunday) {
                    $this->autoGenerateForm['weekend_day'] = 'both';
                } elseif ($hasSaturday) {
                    $this->autoGenerateForm['weekend_day'] = 'saturday';
                } elseif ($hasSunday) {
                    $this->autoGenerateForm['weekend_day'] = 'sunday';
                }
                
                // Find weekend class times
                $earliestSlot = $this->weekendTimeSlots->sortBy('start_time')->first();
                $latestSlot = $this->weekendTimeSlots->sortByDesc('end_time')->first();
                    
                    if ($earliestSlot && $latestSlot) {
                    $this->autoGenerateForm['weekend_start_time'] = substr($earliestSlot->start_time, 0, 5);
                    $this->autoGenerateForm['weekend_end_time'] = substr($latestSlot->end_time, 0, 5);
                }
                
                // Calculate average weekend lesson duration
                $totalDuration = 0;
                $lessonCount = 0;
                
                foreach ($this->weekendTimeSlots as $slot) {
                    if (!str_contains(strtolower($slot->period_name), 'break')) {
                        $start = \Carbon\Carbon::parse($slot->start_time);
                        $end = \Carbon\Carbon::parse($slot->end_time);
                        $totalDuration += $start->diffInMinutes($end);
                        $lessonCount++;
                    }
                }
                
                if ($lessonCount > 0) {
                    $averageDuration = ceil($totalDuration / $lessonCount);
                    $this->autoGenerateForm['weekend_lesson_duration'] = min(max($averageDuration, 30), 180);
                }
            }
            
            // Check for prep time slots
            if ($this->prepTimeSlots->count() > 0) {
                // Check for morning prep
                $morningPrepSlots = $this->prepTimeSlots->filter(function($slot) {
                    return str_contains(strtolower($slot->period_name), 'morning');
                });
                
                if ($morningPrepSlots->count() > 0) {
                    $morningPrep = $morningPrepSlots->first();
                    if ($morningPrep) {
                        $this->autoGenerateForm['morning_prep_start_time'] = substr($morningPrep->start_time, 0, 5);
                        $this->autoGenerateForm['morning_prep_end_time'] = substr($morningPrep->end_time, 0, 5);
                        $this->autoGenerateForm['include_morning_prep'] = true;
                    }
                }
                
                // Check for evening prep
                $eveningPrepSlots = $this->prepTimeSlots->filter(function($slot) {
                    return str_contains(strtolower($slot->period_name), 'evening');
                });
                
                if ($eveningPrepSlots->count() > 0) {
                    $eveningPrep = $eveningPrepSlots->first();
                    if ($eveningPrep) {
                        $this->autoGenerateForm['evening_prep_start_time'] = substr($eveningPrep->start_time, 0, 5);
                        $this->autoGenerateForm['evening_prep_end_time'] = substr($eveningPrep->end_time, 0, 5);
                        $this->autoGenerateForm['include_evening_prep'] = true;
                    }
                }
            }
        }
    }
    
    public function closeAutoGenerateModal()
    {
        $this->showAutoGenerateModal = false;
        $this->resetValidation();
        $this->cardMode = false;
        $this->activeCard = null;
    }
    
    /**
     * Reset the auto-generate form to default values
     */
    public function resetAutoGenerateForm()
    {
        // Reset to default values based on Kenyan school timings
        $this->autoGenerateForm = [
            'school_start_time' => '08:00',
            'school_end_time' => '16:00',
            'lesson_duration' => 40,
            'break_duration' => 20,
            'lunch_duration' => 60,
            'break_after_lessons' => 2,       // After how many lessons a short break should occur
            'lunch_time' => '12:30',
            'extracurricular_start_time' => '15:30',
            'extracurricular_end_time' => '17:00',
            'include_lunch' => true,          // Lunch is enabled by default
            'include_breaks' => true,         // Regular breaks are enabled by default
            'include_extracurricular' => false,
            'transition_duration' => 5,
            'include_transitions' => true,    // Movement periods are enabled by default
            
            // Weekend Classes Configuration - reset to defaults
            'include_weekend_classes' => false,
            'weekend_day' => 'saturday',
            'weekend_start_time' => '09:00',
            'weekend_end_time' => '13:00',
            'weekend_lesson_duration' => 60,
            
            // Prep Activities Configuration - reset to defaults
            'include_morning_prep' => false,
            'morning_prep_start_time' => '06:30',
            'morning_prep_end_time' => '07:30',
            
            'include_evening_prep' => false,
            'evening_prep_start_time' => '19:00',
            'evening_prep_end_time' => '21:00',
        ];
        
        toast()->success('Form reset to default values')->push();
    }
    
    /**
     * Generate time slots based on configuration settings
     */
    public function generateTimeSlots()
    {
        try {
            // Ensure proper data types before validation
            $this->ensureProperDataTypes();
            
            $this->validate($this->autoGenerateRules);
        
            // Initialize collections for new and existing slots
            $generatedSlots = [];
            $existingSlots = [];
            $order = 1;
            
            // Get existing time slots for comparison
            $existingTimeSlots = TimetablePeriod::where('timetable_id', $this->timetableRecordId)
                ->get()
                ->keyBy(function($slot) {
                    return $slot->start_time . '-' . $slot->end_time;
                });
            
            // Generate weekday slots
            $order = $this->generateWeekdaySlots($generatedSlots, $order);
            
            // Generate weekend slots if enabled
            if ($this->autoGenerateForm['include_weekend_classes'] ?? false) {
                $order = $this->generateWeekendSlots($generatedSlots, $order);
            }
            
            // Generate prep slots if enabled
            if (($this->autoGenerateForm['include_morning_prep'] ?? false) || 
                ($this->autoGenerateForm['include_evening_prep'] ?? false)) {
                $this->generatePrepSlots($generatedSlots, $order);
            }
            
            // Sort all slots by start time
            usort($generatedSlots, function($a, $b) {
                $timeA = strtotime($a['start_time']);
                $timeB = strtotime($b['start_time']);
                return $timeA <=> $timeB;
            });
            
            // Renumber the period_order field
            foreach ($generatedSlots as $index => $slot) {
                $generatedSlots[$index]['period_order'] = $index + 1;
            }
            
            // Process generated slots
            $newSlots = [];
            $updatedSlots = [];
            
            foreach ($generatedSlots as $slot) {
                $slotKey = $slot['start_time'] . '-' . $slot['end_time'];
                
                if (isset($existingTimeSlots[$slotKey])) {
                    // Update existing slot
                    $existingSlot = $existingTimeSlots[$slotKey];
                    $existingSlot->update([
                        'period_name' => $slot['period_name'],
                        'period_order' => $slot['period_order'],
                        'updated_at' => now()
                    ]);
                    $updatedSlots[] = $existingSlot;
                } else {
                    // Insert new slot
                    $newSlots[] = $slot;
                }
            }
            
            // Bulk insert new slots
            if (!empty($newSlots)) {
                TimetablePeriod::insert($newSlots);
            }
            
            // Prepare success message
            $message = [];
            if (count($newSlots) > 0) {
                $message[] = "Added " . count($newSlots) . " new time slots";
            }
            if (count($updatedSlots) > 0) {
                $message[] = "Updated " . count($updatedSlots) . " existing time slots";
            }
            
            if (empty($message)) {
                toast()->warning("No changes were made to the time slots.")->push();
            } else {
                toast()->success(implode(" and ", $message) . "!")->push();
            }
            
            // Refresh the data
            $this->loadData();
            $this->closeAutoGenerateModal();
            
        } catch (\Exception $e) {
            // Log detailed error information
            \Log::error("Error generating time slots: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Determine the type of error and provide appropriate guidance
            $errorMessage = "An error occurred while generating time slots.";
            
            if (strpos($e->getMessage(), 'date_format') !== false || 
                strpos($e->getMessage(), 'start_time') !== false || 
                strpos($e->getMessage(), 'end_time') !== false) {
                $errorMessage .= " Please check that all time values are in valid 24-hour format (HH:MM).";
            } else if (strpos($e->getMessage(), 'after:') !== false) {
                $errorMessage .= " Please ensure end times are after start times.";
            } else if (strpos($e->getMessage(), 'required') !== false) {
                $errorMessage .= " Some required fields are missing.";
            } else if (strpos(strtolower($e->getMessage()), 'type') !== false) {
                $errorMessage .= " There may be an issue with data types. Please check all numeric fields have valid numbers.";
            } else {
                $errorMessage .= " " . $e->getMessage();
            }
            
            toast()->danger($errorMessage)->push();
        }
    }
    
    /**
     * Generate regular weekday slots
     */
    private function generateWeekdaySlots(&$generatedSlots, &$order)
    {
        // Parse start and end times
        $currentTime = strtotime($this->autoGenerateForm['school_start_time']);
        $endTime = strtotime($this->autoGenerateForm['school_end_time']);
        $lunchTime = strtotime($this->autoGenerateForm['lunch_time'] ?? '12:30');
        $extracurricularStartTime = strtotime($this->autoGenerateForm['extracurricular_start_time'] ?? '15:30');
        $extracurricularEndTime = strtotime($this->autoGenerateForm['extracurricular_end_time'] ?? '17:00');
        
        // Properly cast numeric values to integers
        $lessonDuration = (int)$this->autoGenerateForm['lesson_duration'] * 60;
        $breakDuration = (int)$this->autoGenerateForm['break_duration'] * 60;
        $lunchDuration = (int)$this->autoGenerateForm['lunch_duration'] * 60;
        $extracurricularDuration = $extracurricularEndTime - $extracurricularStartTime;
        
        $breakAfterLessons = (int)$this->autoGenerateForm['break_after_lessons'];
        
        $includeLunch = (bool)($this->autoGenerateForm['include_lunch'] ?? false);
        $includeExtracurricular = (bool)($this->autoGenerateForm['include_extracurricular'] ?? false);
        $includeBreaks = (bool)($this->autoGenerateForm['include_breaks'] ?? false);
        $includeTransitions = (bool)($this->autoGenerateForm['include_transitions'] ?? false);
        $transitionDuration = (int)$this->autoGenerateForm['transition_duration'] * 60;
        
        // Define ideal windows for special activities
        $lunchWindowStart = $lunchTime - (20 * 60);
        $lunchWindowEnd = $lunchTime + (20 * 60);
        
        $extracurricularWindowStart = $extracurricularStartTime - (15 * 60);
        $extracurricularWindowEnd = $extracurricularStartTime + (15 * 60);
        
        $lessonCount = 0;
        $breakCounter = 0;
        $consecutiveLessonCount = 0;
        $lunchScheduled = false;
        $extracurricularScheduled = false;
        $lastSlotType = null;
        $lastSlotEndTime = $currentTime;
        
        // Pre-plan key timing events
        $keyEvents = [];
        
        if ($includeLunch) {
            $keyEvents[] = [
                'time' => $lunchTime,
                'type' => 'lunch',
                'duration' => $lunchDuration,
                'flexibility' => 20 * 60,
                'name' => 'Lunch Break',
                'priority' => 10
            ];
        }
        
        if ($includeExtracurricular) {
            $keyEvents[] = [
                'time' => $extracurricularStartTime,
                'type' => 'extracurricular',
                'duration' => $extracurricularDuration,
                'flexibility' => 15 * 60,
                'name' => 'Games & Activities',
                'priority' => 8
            ];
        }
        
        usort($keyEvents, function($a, $b) {
            return $a['time'] <=> $b['time'];
        });
        
        // Initialize planned break schedule
        $plannedBreaks = [];
        
        if ($includeBreaks && $breakAfterLessons > 0) {
            $estimatedLessonsPossible = floor(($endTime - $currentTime) / ($lessonDuration + ($includeTransitions ? $transitionDuration : 0)));
            $breakCount = floor($estimatedLessonsPossible / $breakAfterLessons);
            
            $breakInterval = $breakAfterLessons * ($lessonDuration + ($includeTransitions ? $transitionDuration : 0));
            
            for ($i = 1; $i <= $breakCount; $i++) {
                $plannedBreakTime = $currentTime + ($i * $breakInterval);
                
                $tooCloseToKeyEvent = false;
                foreach ($keyEvents as $event) {
                    if (abs($plannedBreakTime - $event['time']) < (30 * 60)) {
                        $tooCloseToKeyEvent = true;
                        break;
                    }
                }
                
                if (!$tooCloseToKeyEvent) {
                    $plannedBreaks[] = [
                        'time' => $plannedBreakTime,
                        'type' => 'break',
                        'duration' => $breakDuration,
                        'flexibility' => 10 * 60,
                        'name' => 'Short Break ' . $i,
                        'priority' => 5
                    ];
                }
            }
        }
        
        // Merge planned breaks with key events and sort by time
        $allEvents = array_merge($keyEvents, $plannedBreaks);
        usort($allEvents, function($a, $b) {
            $timeCompare = $a['time'] <=> $b['time'];
            if ($timeCompare !== 0) return $timeCompare;
            return $b['priority'] <=> $a['priority'];
        });
        
        while ($currentTime < $endTime) {
            $slotStartTime = $currentTime;
            $slotType = null;
            $slotEndTime = null;
            
            // Check if we're approaching a scheduled event
            $approachingEvent = null;
            foreach ($allEvents as $index => $event) {
                if (isset($event['processed']) && $event['processed']) {
                    continue;
                }
                
                if ($currentTime <= $event['time'] && 
                    ($event['time'] - $currentTime) <= ($event['flexibility'] + $lessonDuration)) {
                    
                    $approachingEvent = $event;
                    $approachingEvent['index'] = $index;
                    break;
                }
            }
            
            // Process the slot based on approaching event
            if ($approachingEvent && ($currentTime + 300) <= $approachingEvent['time']) {
                $timeToEvent = $approachingEvent['time'] - $currentTime;
                
                if ($timeToEvent >= $lessonDuration && $lastSlotType !== 'break' && $lastSlotType !== 'transition') {
                        $slotEndTime = $currentTime + $lessonDuration;
                        $consecutiveLessonCount++;
                        $lessonCount++;
                        $slotName = 'Period ' . $lessonCount;
                        $slotType = 'lesson';
                } else if ($timeToEvent >= $breakDuration && $includeBreaks && 
                          $lastSlotType !== 'break' && $lastSlotType !== 'transition' &&
                          $approachingEvent['type'] !== 'break') {
                    $slotEndTime = $currentTime + $breakDuration;
                    $slotName = 'Short Break';
                    $slotType = 'break';
                    $breakCounter++;
                    $consecutiveLessonCount = 0;
                } else if (($approachingEvent['time'] - $currentTime) <= 10 * 60) {
                    $slotStartTime = $approachingEvent['time'];
                    $currentTime = $slotStartTime;
                    $slotEndTime = $slotStartTime + $approachingEvent['duration'];
                    $slotName = $approachingEvent['name'];
                    $slotType = $approachingEvent['type'];
                    $allEvents[$approachingEvent['index']]['processed'] = true;
                    
                        $consecutiveLessonCount = 0;
                        
                    if ($approachingEvent['type'] === 'lunch') {
                            $lunchScheduled = true;
                    } else if ($approachingEvent['type'] === 'extracurricular') {
                            $extracurricularScheduled = true;
                        }
                } else {
                    $slotEndTime = min($currentTime + $lessonDuration, $approachingEvent['time']);
                    $consecutiveLessonCount++;
                    $lessonCount++;
                    $slotName = 'Period ' . $lessonCount;
                    $slotType = 'lesson';
                }
            } 
            else if ($approachingEvent && abs($currentTime - $approachingEvent['time']) < 300) {
                $slotStartTime = $approachingEvent['time'];
                $currentTime = $slotStartTime;
                $slotEndTime = $slotStartTime + $approachingEvent['duration'];
                $slotName = $approachingEvent['name'];
                $slotType = $approachingEvent['type'];
                $allEvents[$approachingEvent['index']]['processed'] = true;
                
                $consecutiveLessonCount = 0;
                
                if ($approachingEvent['type'] === 'lunch') {
                    $lunchScheduled = true;
                } else if ($approachingEvent['type'] === 'extracurricular') {
                    $extracurricularScheduled = true;
                }
            }
            else {
                if ($includeBreaks && $consecutiveLessonCount >= $breakAfterLessons && 
                    $lastSlotType !== 'break' && $lastSlotType !== 'transition') {
                    
                    $slotEndTime = $currentTime + $breakDuration;
                    $slotName = 'Short Break';
                    $slotType = 'break';
                    $breakCounter++;
                    $consecutiveLessonCount = 0;
                } else {
                    $slotEndTime = $currentTime + $lessonDuration;
                    $consecutiveLessonCount++;
                    $lessonCount++;
                    $slotName = 'Period ' . $lessonCount;
                    $slotType = 'lesson';
                }
            }
            
            if ($slotEndTime > $endTime) {
                $slotEndTime = $endTime;
            }
            
            if (($slotEndTime - $slotStartTime) < 300) {
                $currentTime = $slotEndTime;
                continue;
            }
            
            $generatedSlots[] = [
                'timetable_id' => $this->timetableRecordId,
                'start_time' => date('H:i:00', $slotStartTime),
                'end_time' => date('H:i:00', $slotEndTime),
                'period_name' => $slotName,
                'period_order' => $order++,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $currentTime = $slotEndTime;
            $lastSlotEndTime = $slotEndTime;
            
            if ($includeTransitions && $transitionDuration > 0 && 
                $currentTime < $endTime && $slotType === 'lesson') {
                
                $approachingSpecialEvent = false;
                foreach ($allEvents as $event) {
                    if (isset($event['processed']) && $event['processed']) {
                        continue;
                    }
                    
                    if (($event['time'] - $currentTime) <= (15 * 60)) {
                        $approachingSpecialEvent = true;
                        break;
                    }
                }
                
                if (!$approachingSpecialEvent) {
                $transitionStartTime = $slotEndTime;
                $transitionEndTime = $transitionStartTime + $transitionDuration;
                
                if ($transitionEndTime <= $endTime) {
                    $generatedSlots[] = [
                        'timetable_id' => $this->timetableRecordId,
                        'start_time' => date('H:i:00', $transitionStartTime),
                        'end_time' => date('H:i:00', $transitionEndTime),
                        'period_name' => 'Movement Time',
                        'period_order' => $order++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    $currentTime = $transitionEndTime;
                    $lastSlotType = 'transition';
                    } else {
                        $lastSlotType = $slotType;
                    }
                } else {
                    $lastSlotType = $slotType;
                }
            } else {
                $lastSlotType = $slotType;
            }
        }
        
        // Process any remaining key events
        foreach ($allEvents as $index => $event) {
            if (isset($event['processed']) && $event['processed']) {
                continue;
            }
            
            if ($event['priority'] >= 8) {
                $slotStartTime = $event['time'];
                $slotEndTime = $slotStartTime + $event['duration'];
                
                if ($slotStartTime < $endTime) {
                    $generatedSlots[] = [
                        'timetable_id' => $this->timetableRecordId,
                        'start_time' => date('H:i:00', $slotStartTime),
                        'end_time' => date('H:i:00', min($slotEndTime, $endTime + 60*60)),
                        'period_name' => $event['name'],
                        'period_order' => $order++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    if ($event['type'] === 'lunch') {
                        $lunchScheduled = true;
                    } else if ($event['type'] === 'extracurricular') {
                        $extracurricularScheduled = true;
                    }
                }
            }
        }
        
        // Return the new order counter
        return $order;
    }
    
    /**
     * Generate weekend slots
     */
    private function generateWeekendSlots(&$generatedSlots, &$order)
    {
        $weekendDays = $this->autoGenerateForm['weekend_day'] === 'both' 
            ? ['Saturday', 'Sunday'] 
            : [ucfirst($this->autoGenerateForm['weekend_day'])];
            
        foreach ($weekendDays as $index => $day) {
            // Add a 5-minute offset to Sunday slots to avoid duplicate time conflicts
            $timeOffset = ($day === 'Sunday' && $this->autoGenerateForm['weekend_day'] === 'both') ? 300 : 0;
            
            $currentTime = strtotime($this->autoGenerateForm['weekend_start_time']) + $timeOffset;
            $endTime = strtotime($this->autoGenerateForm['weekend_end_time']) + $timeOffset;
            
            if ($currentTime >= $endTime) {
                \Log::warning("Invalid weekend time range", [
                    'start' => date('H:i', $currentTime),
                    'end' => date('H:i', $endTime)
                ]);
                continue;
            }
            
            $lessonDuration = (int)$this->autoGenerateForm['weekend_lesson_duration'] * 60;
            $breakDuration = (int)$this->autoGenerateForm['break_duration'] * 60;
            $includeBreaks = (bool)($this->autoGenerateForm['include_breaks'] ?? false);
            $includeTransitions = (bool)($this->autoGenerateForm['include_transitions'] ?? false);
            $transitionDuration = (int)$this->autoGenerateForm['transition_duration'] * 60;
            
            $lessonCount = 0;
            $lastSlotType = null;
            $consecutiveLessonCount = 0;
            
            // Calculate the midpoint for a planned break
            $midpointTime = $currentTime + (($endTime - $currentTime) / 2);
            
            // Plan weekend breaks - more structured approach
            $plannedBreaks = [];
            
            // Always include a mid-session break if breaks are enabled
            if ($includeBreaks) {
                $plannedBreaks[] = [
                    'time' => $midpointTime,
                    'type' => 'break',
                    'duration' => $breakDuration,
                    'flexibility' => 15 * 60,
                    'name' => $day . ' Break',
                    'priority' => 7
                ];
                
                // Calculate additional breaks based on session length
                $totalSessionDuration = $endTime - $currentTime;
                // If session is longer than 2.5 hours, add another break
                if ($totalSessionDuration > 9000) {
                    // Calculate quarter points for additional breaks
                    $quarterPoint = $currentTime + ($totalSessionDuration / 4);
                    $threeQuarterPoint = $currentTime + (3 * $totalSessionDuration / 4);
                    
                    // Add quarter-point break if session is long enough
                    if (($quarterPoint - $currentTime) >= ($lessonDuration + $breakDuration)) {
                        $plannedBreaks[] = [
                            'time' => $quarterPoint,
                            'type' => 'break',
                            'duration' => $breakDuration,
                            'flexibility' => 10 * 60,
                            'name' => $day . ' First Break',
                            'priority' => 6
                        ];
                    }
                    
                    // Add three-quarter-point break if session is long enough
                    if (($endTime - $threeQuarterPoint) >= ($lessonDuration + $breakDuration)) {
                        $plannedBreaks[] = [
                            'time' => $threeQuarterPoint,
                            'type' => 'break',
                            'duration' => $breakDuration,
                            'flexibility' => 10 * 60,
                            'name' => $day . ' Second Break',
                            'priority' => 6
                        ];
                    }
                }
            }
            
            // Sort breaks by time
            usort($plannedBreaks, function($a, $b) {
                return $a['time'] <=> $b['time'];
            });
            
            while ($currentTime < $endTime) {
                $slotStartTime = $currentTime;
                $slotType = null;
                $slotEndTime = null;
                
                // Check if we're approaching a break
                $approachingBreak = null;
                foreach ($plannedBreaks as $index => $break) {
                    if (isset($break['processed']) && $break['processed']) {
                        continue;
                    }
                    
                    if ($currentTime <= $break['time'] && 
                        ($break['time'] - $currentTime) <= $break['flexibility']) {
                        
                        $approachingBreak = $break;
                        $approachingBreak['index'] = $index;
                        break;
                    }
                }
                
                // If approaching a break and have at least 5 minutes gap
                if ($approachingBreak && ($currentTime + 300) <= $approachingBreak['time']) {
                    // Check if we have time for a lesson before the break
                    $timeToBreak = $approachingBreak['time'] - $currentTime;
                    
                    if ($timeToBreak >= $lessonDuration && $lastSlotType !== 'break' && $lastSlotType !== 'transition') {
                        // Schedule a lesson before the break
                        $slotEndTime = $currentTime + $lessonDuration;
                        $lessonCount++;
                        $consecutiveLessonCount++;
                        $slotName = $day . ' Class ' . $lessonCount;
                        $slotType = 'lesson';
                    } else if ($timeToBreak <= 10 * 60) {
                        // We're very close to the break, schedule it now
                        $slotStartTime = $approachingBreak['time'];
                        $currentTime = $slotStartTime;
                        $slotEndTime = $slotStartTime + $approachingBreak['duration'];
                        $slotName = $approachingBreak['name'];
                    $slotType = 'break';
                        $plannedBreaks[$approachingBreak['index']]['processed'] = true;
                        $consecutiveLessonCount = 0;
                } else {
                        // Not enough time for a full lesson but too far from break
                        // Schedule a shorter lesson up to the break
                        $slotEndTime = $approachingBreak['time'];
                        $lessonCount++;
                        $consecutiveLessonCount++;
                        $slotName = $day . ' Short Class ' . $lessonCount;
                        $slotType = 'lesson';
                    }
                }
                // Check if it's time for a scheduled break
                else if ($approachingBreak && abs($currentTime - $approachingBreak['time']) < 300) {
                    $slotStartTime = $approachingBreak['time'];
                    $currentTime = $slotStartTime;
                    $slotEndTime = $slotStartTime + $approachingBreak['duration'];
                    $slotName = $approachingBreak['name'];
                    $slotType = 'break';
                    $plannedBreaks[$approachingBreak['index']]['processed'] = true;
                    $consecutiveLessonCount = 0;
                }
                // No planned break, check if we need a regular break based on consecutive lessons
                else if ($includeBreaks && $consecutiveLessonCount >= 2 && 
                         $lastSlotType !== 'break' && $lastSlotType !== 'transition') {
                    // Schedule a dynamic break
                    $slotEndTime = $currentTime + $breakDuration;
                    $slotName = $day . ' Short Break';
                    $slotType = 'break';
                    $consecutiveLessonCount = 0;
                } 
                // Regular lesson
                else {
                    $slotEndTime = $currentTime + $lessonDuration;
                    $lessonCount++;
                    $consecutiveLessonCount++;
                    $slotName = $day . ' Class ' . $lessonCount;
                    $slotType = 'lesson';
                }
                
                // Make sure the slot doesn't exceed the end time
                if ($slotEndTime > $endTime) {
                    $slotEndTime = $endTime;
                }
                
                // Skip if the duration is too short (less than 5 minutes)
                if (($slotEndTime - $slotStartTime) < 300) {
                    $currentTime = $slotEndTime;
                    continue;
                }
                
                // Add the slot to the collection
                $generatedSlots[] = [
                    'timetable_id' => $this->timetableRecordId,
                    'start_time' => date('H:i:00', $slotStartTime),
                    'end_time' => date('H:i:00', $slotEndTime),
                    'period_name' => $slotName,
                    'period_order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $currentTime = $slotEndTime;
                $lastSlotType = $slotType;
                
                // Add transition time after lessons if enabled
                if ($includeTransitions && $transitionDuration > 0 && 
                    $currentTime < $endTime && $slotType === 'lesson') {
                    
                    // Check if we're approaching a planned break
                    $approachingNextBreak = false;
                    foreach ($plannedBreaks as $break) {
                        if (isset($break['processed']) && $break['processed']) {
                            continue;
                        }
                        
                        if (($break['time'] - $currentTime) <= (10 * 60)) {
                            $approachingNextBreak = true;
                            break;
                        }
                    }
                    
                    if (!$approachingNextBreak) {
                    $transitionStartTime = $slotEndTime;
                    $transitionEndTime = $transitionStartTime + $transitionDuration;
                    
                    if ($transitionEndTime <= $endTime) {
                        $generatedSlots[] = [
                            'timetable_id' => $this->timetableRecordId,
                            'start_time' => date('H:i:00', $transitionStartTime),
                            'end_time' => date('H:i:00', $transitionEndTime),
                            'period_name' => $day . ' Movement Time',
                            'period_order' => $order++,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        
                        $currentTime = $transitionEndTime;
                        $lastSlotType = 'transition';
                        }
                    }
                }
            }
            
            // Process any remaining breaks that didn't get scheduled
            foreach ($plannedBreaks as $index => $break) {
                if (isset($break['processed']) && $break['processed']) {
                    continue;
                }
                
                // Only try to add medium-high priority breaks
                if ($break['priority'] >= 7) {
                    // Add the break at its scheduled time if it falls within the session time
                    if ($break['time'] >= $currentTime && $break['time'] < $endTime) {
                        $slotStartTime = $break['time'];
                        $slotEndTime = min($slotStartTime + $break['duration'], $endTime);
                        
                        // Only add if there's at least 5 minutes duration
                        if (($slotEndTime - $slotStartTime) >= 300) {
                            $generatedSlots[] = [
                                'timetable_id' => $this->timetableRecordId,
                                'start_time' => date('H:i:00', $slotStartTime),
                                'end_time' => date('H:i:00', $slotEndTime),
                                'period_name' => $break['name'],
                                'period_order' => $order++,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
            }
        }
        
        // Return the new order counter
        return $order;
    }
    
    /**
     * Generate prep time slots (morning and evening)
     */
    private function generatePrepSlots(&$generatedSlots, &$order)
    {
        // Generate morning prep if enabled
        if ($this->autoGenerateForm['include_morning_prep'] ?? false) {
            $startTime = strtotime($this->autoGenerateForm['morning_prep_start_time']);
            $endTime = strtotime($this->autoGenerateForm['morning_prep_end_time']);
            
            if ($startTime < $endTime) {
                $generatedSlots[] = [
                    'timetable_id' => $this->timetableRecordId,
                    'start_time' => date('H:i:00', $startTime),
                    'end_time' => date('H:i:00', $endTime),
                    'period_name' => 'Morning Prep',
                    'period_order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        // Generate evening prep if enabled
        if ($this->autoGenerateForm['include_evening_prep'] ?? false) {
            $startTime = strtotime($this->autoGenerateForm['evening_prep_start_time']);
            $endTime = strtotime($this->autoGenerateForm['evening_prep_end_time']);
            
            if ($startTime < $endTime) {
                $generatedSlots[] = [
                    'timetable_id' => $this->timetableRecordId,
                    'start_time' => date('H:i:00', $startTime),
                    'end_time' => date('H:i:00', $endTime),
                    'period_name' => 'Evening Prep',
                    'period_order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
    }
    
    public function render()
    {
        return view('livewire.timetable.time-slots');
    }
    
    /**
     * Switch to a different tab in the timetable interface
     */
    public function switchTab($tab)
    {
        $this->dispatch('switchTab', $tab);
    }

    public function confirmDelete($id)
    {
        $this->timeSlotToDelete = $id;
        $this->showDeleteModal = true;
        $this->cardMode = true;
        $this->activeCard = 'delete';
    }

    public function backToMain()
    {
        $this->showModal = false;
        $this->showAutoGenerateModal = false;
        $this->showDeleteModal = false;
        $this->cardMode = false;
        $this->activeCard = null;
        $this->resetValidation();
    }

    /**
     * Ensure all form values have the correct data types before validation
     */
    private function ensureProperDataTypes()
    {
        // Convert checkbox (boolean) values
        $booleanFields = [
            'include_breaks', 
            'include_lunch', 
            'include_extracurricular',
            'include_transitions',
            'include_weekend_classes',
            'include_morning_prep',
            'include_evening_prep'
        ];
        
        foreach ($booleanFields as $field) {
            if (isset($this->autoGenerateForm[$field])) {
                $this->autoGenerateForm[$field] = (bool)$this->autoGenerateForm[$field];
            } else {
                $this->autoGenerateForm[$field] = false; // Default to false if not set
            }
        }
        
        // Convert numeric values
        $numericFields = [
            'lesson_duration', 
            'break_duration', 
            'lunch_duration', 
            'transition_duration',
            'break_after_lessons',
            'weekend_lesson_duration'
        ];
        
        foreach ($numericFields as $field) {
            if (isset($this->autoGenerateForm[$field])) {
                $this->autoGenerateForm[$field] = (int)$this->autoGenerateForm[$field];
            }
        }
    }

    /**
     * Export the time slots to PDF
     */
    public function exportToPdf()
    {
        $this->loadData();
        
        // Get school name
        $schoolName = config('app.name', 'School Management System');
        
        // Get current date and time formatted
        $exportDate = now()->format('l, F j, Y, g:i A');
        
        // Configure PDF with proper options
        $config = [
            'format' => 'A4',
            'orientation' => 'landscape',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ];
        
        $pdf = \PDF::loadView('livewire.timetable.exports.time-slots-pdf', [
            'schoolName' => $schoolName,
            'exportDate' => $exportDate,
            'timeSlots' => $this->timeSlots,
            'weekdayTimeSlots' => $this->weekdayTimeSlots,
            'weekendTimeSlots' => $this->weekendTimeSlots,
            'prepTimeSlots' => $this->prepTimeSlots,
            'hasWeekendSlots' => $this->hasWeekendSlots,
            'hasPrepSlots' => $this->hasPrepSlots,
            'timetable' => $this->timetable
        ], [], $config);
        
        // Generate a unique filename with timestamp
        $filename = 'timetable_timeslots_' . now()->format('Ymd_His') . '.pdf';
        
        // Show success notification
        toast()->success('Time slots exported to PDF successfully!')->push();
        
        // Return the PDF for download
        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]
        );
    }
    
    /**
     * Export the time slots to Excel
     */
    public function exportToExcel()
    {
        $this->loadData();
        
        // Title row with column names
        $rows = [
            ['Category', 'Period Name', 'Start Time', 'End Time', 'Duration (mins)', 'Type']
        ];
        
        // Add weekday periods
        foreach ($this->weekdayTimeSlots as $slot) {
            $start = \Carbon\Carbon::parse($slot->start_time);
            $end = \Carbon\Carbon::parse($slot->end_time);
            $duration = $start->diffInMinutes($end);
            
            $rows[] = [
                'Weekday',
                $slot->period_name,
                date('h:i A', strtotime($slot->start_time)),
                date('h:i A', strtotime($slot->end_time)),
                $duration,
                $this->getPeriodType($slot->period_name)
            ];
        }
        
        // Add weekend periods if they exist
        if ($this->hasWeekendSlots) {
            foreach ($this->weekendTimeSlots as $slot) {
                $start = \Carbon\Carbon::parse($slot->start_time);
                $end = \Carbon\Carbon::parse($slot->end_time);
                $duration = $start->diffInMinutes($end);
                
                $day = 'Weekend';
                if (str_contains(strtolower($slot->period_name), 'saturday')) {
                    $day = 'Saturday';
                } elseif (str_contains(strtolower($slot->period_name), 'sunday')) {
                    $day = 'Sunday';
                }
                
                $rows[] = [
                    $day,
                    $slot->period_name,
                    date('h:i A', strtotime($slot->start_time)),
                    date('h:i A', strtotime($slot->end_time)),
                    $duration,
                    'Weekend Class'
                ];
            }
        }
        
        // Add prep periods if they exist
        if ($this->hasPrepSlots) {
            foreach ($this->prepTimeSlots as $slot) {
                $start = \Carbon\Carbon::parse($slot->start_time);
                $end = \Carbon\Carbon::parse($slot->end_time);
                $duration = $start->diffInMinutes($end);
                
                $prepType = 'Prep';
                if (str_contains(strtolower($slot->period_name), 'morning')) {
                    $prepType = 'Morning Prep';
                } elseif (str_contains(strtolower($slot->period_name), 'evening')) {
                    $prepType = 'Evening Prep';
                }
                
                $rows[] = [
                    'Prep',
                    $slot->period_name,
                    date('h:i A', strtotime($slot->start_time)),
                    date('h:i A', strtotime($slot->end_time)),
                    $duration,
                    $prepType
                ];
            }
        }
        
        // Prepare timetable information for the export
        $timetableInfo = [
            'name' => $this->timetable->name ?? 'School Timetable',
            'academic_year' => $this->timetable->academic_year ?? date('Y'),
            'term' => $this->timetable->term ?? ''
        ];
        
        // Generate a unique filename with timestamp
        $filename = 'timetable_timeslots_' . now()->format('Ymd_His') . '.xlsx';
        
        // Show success notification
        toast()->success('Time slots exported to Excel successfully!')->push();
        
        return Excel::download(new TimeSlotsExport($rows, $timetableInfo), $filename);
    }
    
    /**
     * Prepare data for clipboard copy
     */
    public function copyToClipboard()
    {
        $this->loadData();
        
        $tableData = [
            'weekdayTimeSlots' => $this->weekdayTimeSlots->map(function($slot) {
                $start = \Carbon\Carbon::parse($slot->start_time);
                $end = \Carbon\Carbon::parse($slot->end_time);
                $duration = $start->diffInMinutes($end);
                
                return [
                    'category' => 'Weekday',
                    'period_name' => $slot->period_name,
                    'start_time' => date('h:i A', strtotime($slot->start_time)),
                    'end_time' => date('h:i A', strtotime($slot->end_time)),
                    'duration' => $duration,
                    'type' => $this->getPeriodType($slot->period_name)
                ];
            }),
            'weekendTimeSlots' => $this->weekendTimeSlots->map(function($slot) {
                $start = \Carbon\Carbon::parse($slot->start_time);
                $end = \Carbon\Carbon::parse($slot->end_time);
                $duration = $start->diffInMinutes($end);
                
                $day = 'Weekend';
                if (str_contains(strtolower($slot->period_name), 'saturday')) {
                    $day = 'Saturday';
                } elseif (str_contains(strtolower($slot->period_name), 'sunday')) {
                    $day = 'Sunday';
                }
                
                return [
                    'category' => $day,
                    'period_name' => $slot->period_name,
                    'start_time' => date('h:i A', strtotime($slot->start_time)),
                    'end_time' => date('h:i A', strtotime($slot->end_time)),
                    'duration' => $duration,
                    'type' => 'Weekend Class'
                ];
            }),
            'prepTimeSlots' => $this->prepTimeSlots->map(function($slot) {
                $start = \Carbon\Carbon::parse($slot->start_time);
                $end = \Carbon\Carbon::parse($slot->end_time);
                $duration = $start->diffInMinutes($end);
                
                $prepType = 'Prep';
                if (str_contains(strtolower($slot->period_name), 'morning')) {
                    $prepType = 'Morning Prep';
                } elseif (str_contains(strtolower($slot->period_name), 'evening')) {
                    $prepType = 'Evening Prep';
                }
                
                return [
                    'category' => 'Prep',
                    'period_name' => $slot->period_name,
                    'start_time' => date('h:i A', strtotime($slot->start_time)),
                    'end_time' => date('h:i A', strtotime($slot->end_time)),
                    'duration' => $duration,
                    'type' => $prepType
                ];
            }),
            'hasWeekendSlots' => $this->hasWeekendSlots,
            'hasPrepSlots' => $this->hasPrepSlots
        ];
        
        // Return data for clipboard handling in frontend
        $this->dispatch('timeSlotsDataReady', $tableData);
        
        // Show success message
        toast()->success('Time slots data ready for copying!', 'Success')->push();
    }

    // Add new prepareClipboardData method
    public function prepareClipboardData()
    {
        $this->loadData();
        $formattedData = [
            'weekdayTimeSlots' => [],
            'weekendTimeSlots' => [],
            'prepTimeSlots' => [],
            'hasWeekendSlots' => $this->hasWeekendSlots,
            'hasPrepSlots' => $this->hasPrepSlots,
        ];

        foreach ($this->weekdayTimeSlots as $slot) {
            $formattedData['weekdayTimeSlots'][] = [
                'period_name' => $slot->period_name,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'duration' => $this->calculateDuration($slot->start_time, $slot->end_time),
                'type' => $this->getPeriodType($slot->period_name),
                'category' => 'Weekday'
            ];
        }

        foreach ($this->weekendTimeSlots as $slot) {
            $formattedData['weekendTimeSlots'][] = [
                'period_name' => $slot->period_name,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'duration' => $this->calculateDuration($slot->start_time, $slot->end_time),
                'type' => $this->getPeriodType($slot->period_name),
                'category' => 'Weekend'
            ];
        }

        foreach ($this->prepTimeSlots as $slot) {
            $formattedData['prepTimeSlots'][] = [
                'period_name' => $slot->period_name,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'duration' => $this->calculateDuration($slot->start_time, $slot->end_time),
                'type' => $this->getPeriodType($slot->period_name),
                'category' => 'Prep'
            ];
        }

        return $formattedData;
    }

    /**
     * Show a toast notification
     * 
     * @param string $type The type of toast: success, error, info, warning
     * @param string $message The message to display
     * @return void
     */
    public function showToast($type, $message)
    {
        // Map the type to the corresponding toast method
        switch ($type) {
            case 'success':
                toast()->success($message)->push();
                break;
            case 'error':
                toast()->danger($message)->push();
                break;
            case 'warning':
                toast()->warning($message)->push();
                break;
            case 'info':
            default:
                toast()->info($message)->push();
                break;
        }
    }

    /**
     * Calculate the duration between two times in minutes
     * 
     * @param string $startTime The start time
     * @param string $endTime The end time
     * @return int The duration in minutes
     */
    private function calculateDuration($startTime, $endTime)
    {
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);
        return $start->diffInMinutes($end);
    }
} 