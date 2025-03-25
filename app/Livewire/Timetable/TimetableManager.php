<?php

namespace App\Livewire\Timetable;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SchoolTimetable;
use App\Models\TimetablePeriod;
use App\Models\TimetableSchedule;
use App\User;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class TimetableManager extends Component
{
    use WithPagination, WireToast;

    // Tab Management
    public string $activeTab = 'timetables';

    // Loading States
    public bool $isLoading = false;

    // Shared Properties
    public mixed $classes = [];
    public $selectedClassId = null;
    public mixed $sections = [];
    public $selectedSectionId = null;
    public array $terms = ['First Term', 'Second Term', 'Third Term'];
    public $selectedTerm = '';
    public array $sessions = [];
    public $selectedSession = '';
    
    // Timetable Records Management
    public mixed $timetableRecords = [];
    public $selectedTimetableRecord = null;
    public $isEditingRecord = false;
    public $showModal = false;
    public $timetableRecordForm = [
        'id' => null,
        'name' => '',
        'class_id' => null,
        'section_id' => null,
        'academic_term' => null,
        'academic_session' => null,
        'is_active' => false,
        'description' => '',
        'is_auto_generated' => true,
    ];
    
    // Time Slots Management
    public mixed $timeSlots = [];
    public $isEditingTimeSlot = false;
    public $timeSlotForm = [
        'id' => null,
        'timetable_id' => null,
        'start_time' => '',
        'end_time' => '',
        'period_name' => '',
        'period_order' => 0,
    ];
    
    // Entry Management
    public $showEntryModal = false;
    public $selectedDay = null;
    public $selectedTimeSlotId = null;
    public $scheduleEntry = [
        'id' => null,
        'timetable_id' => null,
        'period_id' => null,
        'subject_id' => null,
        'teacher_id' => null,
        'weekday' => null,
        'classroom' => '',
        'notes' => '',
    ];
    public $subjects = [];
    public $teachers = [];

    // Listeners
    protected $listeners = [
        'switchTab' => 'handleSwitchTab',
    ];

    protected $rules = [
        'timetableRecordForm.name' => 'required|string|max:100',
        'timetableRecordForm.class_id' => 'required|exists:my_classes,id',
        'timetableRecordForm.academic_term' => 'required|string',
        'timetableRecordForm.academic_session' => 'required|string',
        
        'timeSlotForm.start_time' => 'required',
        'timeSlotForm.end_time' => 'required|after:timeSlotForm.start_time',
        'timeSlotForm.period_name' => 'nullable|string',
        'timeSlotForm.period_order' => 'required|integer|min:0',
        
        'scheduleEntry.subject_id' => 'required|exists:subjects,id',
        'scheduleEntry.teacher_id' => 'nullable|exists:users,id',
        'scheduleEntry.classroom' => 'nullable|string|max:50',
        'scheduleEntry.notes' => 'nullable|string',
    ];
    
    public function mount()
    {
        $this->classes = MyClass::orderBy('name')->get();
        $this->selectedTerm = $this->terms[0];
        
        // Get last 10 years and next 10 years for session selection
        $currentYear = date('Y');
        $years = range($currentYear - 10, $currentYear + 10);
        $this->sessions = [];
        
        foreach ($years as $year) {
            $nextYear = $year + 1;
            $this->sessions[] = "$year-$nextYear";
        }
        
        $this->selectedSession = "$currentYear-" . ($currentYear + 1);
    }

    public function updatedSelectedClassId($value)
    {
        $this->selectedSectionId = null;
        $this->sections = $value ? Section::where('my_class_id', $value)->orderBy('name')->get() : [];
        $this->loadTimetableRecords();
    }
    
    public function updatedSelectedTerm()
    {
        $this->loadTimetableRecords();
    }
    
    public function updatedSelectedSession()
    {
        $this->loadTimetableRecords();
    }
    
    public function updatedSelectedTimetableRecord($value)
    {
        if ($value) {
            $this->timeSlots = TimetablePeriod::where('timetable_id', $value)
                ->ordered()
                ->get();
                
            $timetable = collect($this->timetableRecords)->firstWhere('id', $value);
            if ($timetable) {
                $message = "Selected Timetable: {$timetable['name']}";
                
                // Count time slots to guide next actions
                $timeSlotsCount = $this->timeSlots->count();
                if ($timeSlotsCount === 0) {
                    $message .= ". Next step: Add time slots using the 'Manage Time Slots' button";
                } else {
                    $message .= ". {$timeSlotsCount} time slots defined. Ready to view timetable.";
                }
                
                toast()->info($message)->push();
            }
        } else {
            $this->timeSlots = [];
        }
    }

    public function loadTimetableRecords()
    {
        if ($this->selectedClassId) {
            $this->timetableRecords = SchoolTimetable::where('class_id', $this->selectedClassId)
                ->where('academic_term', $this->selectedTerm)
                ->where('academic_session', $this->selectedSession)
                ->get();
                
            // Clear the selected timetable initially
            $this->selectedTimetableRecord = null;
            
            // Auto-select if there's only one record or select the active one
            if ($this->timetableRecords->count() == 1) {
                $this->selectedTimetableRecord = $this->timetableRecords->first()->id;
                toast()->info('Timetable automatically selected')->push();
            } elseif ($this->timetableRecords->count() > 1) {
                // Try to select the active timetable if exists
                $activeTimetable = $this->timetableRecords->where('is_active', true)->first();
                if ($activeTimetable) {
                    $this->selectedTimetableRecord = $activeTimetable->id;
                    toast()->info('Active timetable selected')->push();
                }
            }
        } else {
            $this->timetableRecords = [];
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function createTimetableRecord()
    {
        $this->showModal = true;
        $this->isEditingRecord = true;
        
        $selectedClass = $this->classes->where('id', $this->selectedClassId)->first();
        $className = optional($selectedClass)->name ?? 'Unknown Class';
        
        $suggestedName = SchoolTimetable::generateUniqueName($className, $this->selectedTerm, $this->selectedSession);
        
        $this->timetableRecordForm = [
            'id' => null,
            'name' => $suggestedName,
            'class_id' => $this->selectedClassId,
            'section_id' => $this->selectedSectionId,
            'academic_term' => $this->selectedTerm,
            'academic_session' => $this->selectedSession,
            'is_active' => false,
            'description' => '',
            'is_auto_generated' => true,
        ];
        
        toast()->info("A unique timetable name has been suggested. You can modify it if needed.")->push();
    }
    
    public function editTimetableRecord($id)
    {
        $this->showModal = true;
        $this->isEditingRecord = true;
        $record = SchoolTimetable::findOrFail($id);
        
        // Determine if the current name matches the standard auto-generated format
        $standardName = SchoolTimetable::generateStandardName(
            optional($this->classes->where('id', $record->class_id)->first())->name ?? 'Unknown Class',
            $record->academic_term,
            $record->academic_session
        );
        
        // Check if the current name is auto-generated or custom
        $isAutoGenerated = (preg_match('/^Timetable for .+ \(\w+ Term, \d{4}-\d{4}\)(\s\(\d+\))?$/', $record->name) === 1);
        
        $this->timetableRecordForm = [
            'id' => $record->id,
            'name' => $record->name,
            'class_id' => $record->class_id,
            'section_id' => $record->section_id,
            'academic_term' => $record->academic_term,
            'academic_session' => $record->academic_session,
            'is_active' => $record->is_active,
            'description' => $record->description ?? '',
            'is_auto_generated' => $isAutoGenerated,
        ];
    }
    
    public function saveTimetableRecord()
    {
        // Determine unique rule for name
        $nameUniqueRule = $this->timetableRecordForm['id'] 
            ? 'required|string|max:100|unique:school_timetables,name,' . $this->timetableRecordForm['id']
            : 'required|string|max:100|unique:school_timetables,name';
            
        $this->validate([
            'timetableRecordForm.name' => $nameUniqueRule,
            'timetableRecordForm.class_id' => 'required|exists:my_classes,id',
            'timetableRecordForm.academic_term' => 'required|string',
            'timetableRecordForm.academic_session' => 'required|string',
        ], [
            'timetableRecordForm.name.unique' => 'This timetable name already exists. Please choose a different name.'
        ]);
        
        try {
            // Check if a timetable already exists for this term/session combination
            $existingTimetable = null;
            
            if (!$this->timetableRecordForm['id']) {
                $existingTimetable = SchoolTimetable::existsForTermSession(
                    $this->timetableRecordForm['class_id'],
                    $this->timetableRecordForm['academic_term'],
                    $this->timetableRecordForm['academic_session'],
                    $this->timetableRecordForm['section_id']
                )->first();
                
                if ($existingTimetable) {
                    toast()->warning('A timetable already exists for this class, term and session. Only one timetable is allowed per term.')
                           ->push();
                    return;
                }
            }
            
            // Ensure name is unique
            $selectedClass = $this->classes->where('id', $this->timetableRecordForm['class_id'])->first();
            $className = optional($selectedClass)->name ?? 'Unknown Class';
            
            // When updating, only modify the name if it would conflict with another record
            if ($this->timetableRecordForm['id']) {
                $currentRecord = SchoolTimetable::find($this->timetableRecordForm['id']);
                
                // If name hasn't changed, keep the original name
                if ($currentRecord && $currentRecord->name === $this->timetableRecordForm['name']) {
                    $uniqueName = $currentRecord->name;
                } else {
                    // Name has changed, check for uniqueness
                    $uniqueName = SchoolTimetable::generateUniqueName(
                        $className, 
                        $this->timetableRecordForm['academic_term'], 
                        $this->timetableRecordForm['academic_session'], 
                        $this->timetableRecordForm['name']
                    );
                    
                    // Notify user if name was adjusted
                    if ($uniqueName !== $this->timetableRecordForm['name']) {
                        toast()->info("Timetable name was adjusted to '{$uniqueName}' to ensure uniqueness.")->push();
                    }
                }
            } else {
                // For new records, always ensure uniqueness
                $uniqueName = SchoolTimetable::generateUniqueName(
                    $className, 
                    $this->timetableRecordForm['academic_term'], 
                    $this->timetableRecordForm['academic_session'], 
                    $this->timetableRecordForm['name']
                );
                
                // Notify user if name was adjusted
                if ($uniqueName !== $this->timetableRecordForm['name']) {
                    toast()->info("Timetable name was adjusted to '{$uniqueName}' to ensure uniqueness.")->push();
                }
            }
            
            $data = [
                'name' => $uniqueName, // Use the potentially modified unique name
                'class_id' => $this->timetableRecordForm['class_id'],
                'section_id' => $this->timetableRecordForm['section_id'],
                'academic_term' => $this->timetableRecordForm['academic_term'],
                'academic_session' => $this->timetableRecordForm['academic_session'],
                'is_active' => $this->timetableRecordForm['is_active'],
                'description' => $this->timetableRecordForm['description'],
            ];
            
            if ($this->timetableRecordForm['id']) {
                $record = SchoolTimetable::findOrFail($this->timetableRecordForm['id']);
                $record->update($data);
                
                toast()->success('Timetable record updated successfully')->push();
            } else {
                $record = SchoolTimetable::create($data);
                
                // If set as active, deactivate others
                if ($record->is_active) {
                    SchoolTimetable::where('id', '!=', $record->id)
                        ->where('class_id', $record->class_id)
                        ->where('academic_term', $record->academic_term)
                        ->where('academic_session', $record->academic_session)
                        ->update(['is_active' => false]);
                }
                
                toast()->success('Timetable record created successfully')->push();
            }
            
            $this->closeModal();
            $this->loadTimetableRecords();
            $this->selectedTimetableRecord = $record->id;
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }

    public function cancelEditRecord()
    {
        $this->isEditingRecord = false;
        $this->timetableRecordForm = [
            'id' => null,
            'name' => '',
            'class_id' => $this->selectedClassId,
            'section_id' => $this->selectedSectionId,
            'academic_term' => $this->selectedTerm,
            'academic_session' => $this->selectedSession,
            'is_active' => false,
            'description' => '',
            'is_auto_generated' => true,
        ];
    }
    
    public function deleteTimetableRecord($id)
    {
        try {
            SchoolTimetable::findOrFail($id)->delete();
            toast()->success('Timetable record deleted successfully')->push();
            $this->loadTimetableRecords();
            $this->selectedTimetableRecord = null;
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }

    public function toggleActiveTimetable($id)
    {
        try {
            $record = SchoolTimetable::findOrFail($id);
            
            // If already active and trying to deactivate
            if ($record->is_active) {
                $record->update(['is_active' => false]);
                toast()->info('Timetable deactivated')->push();
            } else {
                // Deactivate all others in same class/term/session
                SchoolTimetable::where('class_id', $record->class_id)
                    ->where('academic_term', $record->academic_term)
                    ->where('academic_session', $record->academic_session)
                    ->update(['is_active' => false]);
                    
                // Activate the selected timetable
                $record->update(['is_active' => true]);
                toast()->success('Timetable activated successfully')->push();
            }
            
            $this->loadTimetableRecords();
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    public function createTimeSlot()
    {
        $this->isEditingTimeSlot = true;

        // Find the next available order number
        $nextOrder = 1;
        if (count($this->timeSlots) > 0) {
            $nextOrder = $this->timeSlots->max('period_order') + 1;
        }
        
        $this->timeSlotForm = [
            'id' => null,
            'timetable_id' => $this->selectedTimetableRecord,
            'start_time' => '',
            'end_time' => '',
            'period_name' => 'Period ' . $nextOrder,
            'period_order' => $nextOrder,
        ];
    }
    
    public function editTimeSlot($id)
    {
        $this->isEditingTimeSlot = true;
        $timeSlot = TimetablePeriod::findOrFail($id);
        
        $this->timeSlotForm = [
            'id' => $timeSlot->id,
            'timetable_id' => $timeSlot->timetable_id,
            'start_time' => $timeSlot->start_time,
            'end_time' => $timeSlot->end_time,
            'period_name' => $timeSlot->period_name,
            'period_order' => $timeSlot->period_order,
        ];
    }
    
    public function saveTimeSlot()
    {
        $this->validate([
            'timeSlotForm.start_time' => 'required',
            'timeSlotForm.end_time' => 'required|after:timeSlotForm.start_time',
            'timeSlotForm.period_order' => 'required|integer|min:0',
        ]);
        
        try {
            $data = [
                'timetable_id' => $this->selectedTimetableRecord,
                'start_time' => $this->timeSlotForm['start_time'],
                'end_time' => $this->timeSlotForm['end_time'],
                'period_name' => $this->timeSlotForm['period_name'],
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
            
            $this->cancelEditTimeSlot();
            $this->timeSlots = TimetablePeriod::where('timetable_id', $this->selectedTimetableRecord)
                ->ordered()
                ->get();
                
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }

    public function cancelEditTimeSlot()
    {
        $this->isEditingTimeSlot = false;
        $this->timeSlotForm = [
            'id' => null,
            'timetable_id' => null,
            'start_time' => '',
            'end_time' => '',
            'period_name' => '',
            'period_order' => 0,
        ];
    }
    
    public function deleteTimeSlot($id)
    {
        try {
            // Check if there are any entries using this time slot
            $hasEntries = TimetableSchedule::where('period_id', $id)->exists();
            
            if ($hasEntries) {
                toast()->warning('Cannot delete this time slot as it has timetable entries. Delete the entries first.')->push();
                return;
            }
            
            TimetablePeriod::findOrFail($id)->delete();
            toast()->success('Time slot deleted successfully')->push();
            
            $this->timeSlots = TimetablePeriod::where('timetable_id', $this->selectedTimetableRecord)
                ->ordered()
                ->get();
                
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    public function openEntryModal($day, $timeSlotId)
    {
        $this->selectedDay = $day;
        $this->selectedTimeSlotId = $timeSlotId;
        
        // Check if an entry already exists
        $existingEntry = TimetableSchedule::where('timetable_id', $this->selectedTimetableRecord)
            ->where('period_id', $timeSlotId)
            ->where('weekday', $day)
            ->first();
            
        if ($existingEntry) {
            $this->scheduleEntry = [
                'id' => $existingEntry->id,
                'timetable_id' => $existingEntry->timetable_id,
                'period_id' => $existingEntry->period_id,
                'subject_id' => $existingEntry->subject_id,
                'teacher_id' => $existingEntry->teacher_id,
                'weekday' => $existingEntry->weekday,
                'classroom' => $existingEntry->classroom,
                'notes' => $existingEntry->notes,
            ];
        } else {
            $this->scheduleEntry = [
                'id' => null,
                'timetable_id' => $this->selectedTimetableRecord,
                'period_id' => $timeSlotId,
                'subject_id' => null,
                'teacher_id' => null,
                'weekday' => $day,
                'classroom' => '',
                'notes' => '',
            ];
        }
        
        // Load subjects and teachers for dropdowns
        $timetable = SchoolTimetable::findOrFail($this->selectedTimetableRecord);
        $this->subjects = Subject::where('my_class_id', $timetable->class_id)->get();
        $this->teachers = User::role('teacher')->orderBy('name')->get();
        
        $this->showEntryModal = true;
    }
    
    public function saveEntry()
    {
        $this->validate([
            'scheduleEntry.subject_id' => 'required|exists:subjects,id',
            'scheduleEntry.teacher_id' => 'nullable|exists:users,id',
            'scheduleEntry.classroom' => 'nullable|string|max:50',
            'scheduleEntry.notes' => 'nullable|string',
        ]);
        
        try {
            $data = [
                'timetable_id' => $this->selectedTimetableRecord,
                'period_id' => $this->selectedTimeSlotId,
                'subject_id' => $this->scheduleEntry['subject_id'],
                'teacher_id' => $this->scheduleEntry['teacher_id'],
                'weekday' => $this->selectedDay,
                'classroom' => $this->scheduleEntry['classroom'],
                'notes' => $this->scheduleEntry['notes'],
                'is_recurring' => true,
                'specific_date' => null,
            ];
            
            if ($this->scheduleEntry['id']) {
                $entry = TimetableSchedule::findOrFail($this->scheduleEntry['id']);
                $entry->update($data);
                
                toast()->success('Timetable entry updated successfully')->push();
            } else {
                TimetableSchedule::create($data);
                
                toast()->success('Timetable entry created successfully')->push();
            }
            
            $this->closeEntryModal();
            $this->emit('refreshTimetable');
            
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    public function deleteEntry()
    {
        if (!$this->scheduleEntry['id']) {
            $this->closeEntryModal();
            return;
        }
        
        try {
            TimetableSchedule::findOrFail($this->scheduleEntry['id'])->delete();
            toast()->success('Timetable entry deleted successfully')->push();
            
            $this->closeEntryModal();
            $this->emit('refreshTimetable');
            
        } catch (\Exception $e) {
            toast()->danger('Error: ' . $e->getMessage())->push();
        }
    }
    
    public function closeEntryModal()
    {
        $this->showEntryModal = false;
        $this->selectedDay = null;
        $this->selectedTimeSlotId = null;
        $this->scheduleEntry = [
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
    
    public function render()
    {
        return view('livewire.timetable.timetable-manager');
    }

    /**
     * Handle tab switch events from child components
     */
    public function handleSwitchTab($tab)
    {
        $this->activeTab = $tab;
        toast()->info("Switched to {$tab} tab")->push();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->cancelEditRecord();
    }
} 