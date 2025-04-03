<?php

namespace App\Livewire\Timetable;

use Livewire\Component;
use App\Models\SchoolTimetable;
use App\Models\TimetablePeriod;
use App\Models\TimetableSchedule;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class TimetableConsolidator extends Component
{
    // Timetable selection
    public $availableTimetables = [];
    public $selectedTimetableIds = [];
    
    // Configuration options
    public $consolidationOptions = [
        'group_by' => 'teacher', // Options: 'teacher', 'class', 'subject'
        'show_weekends' => false,
        'show_conflicts' => true,
        'highlight_conflicts' => true,
        'merge_similar_periods' => true,
        'include_empty_slots' => false,
    ];
    
    // Filtering options
    public $filterClass = '';
    public $filterAcademicSession = '';
    public $filterAcademicTerm = '';
    public $availableClasses = [];
    public $availableAcademicSessions = [];
    public $availableAcademicTerms = [];
    
    // Consolidated data
    public $consolidatedData = null;
    public $isConsolidated = false;
    public $consolidationStats = null;
    public $consolidationErrors = [];
    
    // UI control variables
    public $isLoading = false;
    public $selectedTab = 'selection'; // 'selection', 'preview', 'export'
    public $exportType = ''; // Used for loading state on exports
    
    // Validation rules
    protected $rules = [
        'selectedTimetableIds' => 'required|array|min:1',
        'consolidationOptions.group_by' => 'required|string|in:teacher,class,subject',
        'consolidationOptions.show_weekends' => 'boolean',
        'consolidationOptions.show_conflicts' => 'boolean',
        'consolidationOptions.highlight_conflicts' => 'boolean',
        'consolidationOptions.merge_similar_periods' => 'boolean',
        'consolidationOptions.include_empty_slots' => 'boolean',
    ];
    
    protected $messages = [
        'selectedTimetableIds.required' => 'Please select at least one timetable to consolidate.',
        'selectedTimetableIds.min' => 'Please select at least one timetable to consolidate.',
    ];
    
    public function mount()
    {
        $this->loadAvailableTimetables();
        $this->loadFilterOptions();
    }
    
    /**
     * Load available timetables for selection
     */
    public function loadAvailableTimetables()
    {
        try {
            $query = SchoolTimetable::with(['myClass'])
                ->orderBy('academic_session', 'desc')
                ->orderBy('academic_term', 'desc');
                
            // Apply class filter if provided
            if (!empty($this->filterClass)) {
                $query->where('class_id', $this->filterClass);
            }
            
            // Apply academic session filter if provided
            if (!empty($this->filterAcademicSession)) {
                $query->where('academic_session', $this->filterAcademicSession);
            }
            
            // Apply academic term filter if provided
            if (!empty($this->filterAcademicTerm)) {
                $query->where('academic_term', $this->filterAcademicTerm);
            }
            
            $this->availableTimetables = $query->get();
            
            // Reset selected timetables if none are available
            if (count($this->availableTimetables) === 0) {
                $this->selectedTimetableIds = [];
                // Store the message for the toast
                session()->flash('toast_message', 'No timetables found with the current filters. Try changing your filter criteria.');
                session()->flash('toast_type', 'warning');
                // Dispatch without parameters
                $this->dispatch('show-toast');
            }
        } catch (\Exception $e) {
            Log::error('Error loading available timetables: ' . $e->getMessage());
            // Store the message for the toast
            session()->flash('toast_message', 'Failed to load available timetables: ' . $e->getMessage());
            session()->flash('toast_type', 'error');
            // Dispatch without parameters
            $this->dispatch('show-toast');
        }
    }
    
    /**
     * Load filter options like classes, academic sessions and terms
     */
    public function loadFilterOptions()
    {
        try {
            // Load available classes
            $this->availableClasses = MyClass::orderBy('name')->get();
            
            // Load available academic sessions
            $this->availableAcademicSessions = SchoolTimetable::select('academic_session')
                ->distinct()
                ->orderBy('academic_session', 'desc')
                ->pluck('academic_session')
                ->toArray();
                
            // Load available academic terms
            $this->availableAcademicTerms = SchoolTimetable::select('academic_term')
                ->distinct()
                ->orderBy('academic_term')
                ->pluck('academic_term')
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Error loading filter options: ' . $e->getMessage());
        }
    }
    
    /**
     * Update filters and reload timetables
     */
    public function updateFilters()
    {
        $this->loadAvailableTimetables();
    }
    
    /**
     * Reset all filters
     */
    public function resetFilters()
    {
        $this->filterClass = '';
        $this->filterAcademicSession = '';
        $this->filterAcademicTerm = '';
        $this->loadAvailableTimetables();
    }
    
    /**
     * Generate consolidated timetable
     */
    public function consolidateTimetables()
    {
        $this->validate();
        
        $this->isLoading = true;
        $this->selectedTab = 'preview';
        $this->consolidationErrors = [];
        
        try {
            // Reset previous consolidated data
            $this->consolidatedData = null;
            $this->isConsolidated = false;
            
            // Get selected timetables with related data
            $selectedTimetables = SchoolTimetable::with(['myClass'])
                ->whereIn('id', $this->selectedTimetableIds)
                ->get();
                
            if ($selectedTimetables->isEmpty()) {
                $this->addConsolidationError('No valid timetables found with the provided IDs.');
                $this->isLoading = false;
                return;
            }
            
            // Initialize stats
            $this->consolidationStats = [
                'timetables' => $selectedTimetables->count(),
                'classes' => $selectedTimetables->pluck('myClass.name')->unique()->count(),
                'periods' => 0,
                'entries' => 0,
                'conflicts' => 0,
                'academic_sessions' => $selectedTimetables->pluck('academic_session')->unique()->join(', '),
                'academic_terms' => $selectedTimetables->pluck('academic_term')->unique()->join(', '),
            ];
            
            // Get all periods from selected timetables
            $allPeriods = $this->getAllPeriods($selectedTimetables->pluck('id')->toArray());
            $this->consolidationStats['periods'] = $allPeriods->count();
            
            // Get all schedules from selected timetables
            $allSchedules = $this->getAllSchedules($selectedTimetables->pluck('id')->toArray());
            $this->consolidationStats['entries'] = $allSchedules->count();
            
            // Define days of the week
            $visibleDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            if ($this->consolidationOptions['show_weekends']) {
                $visibleDays = array_merge($visibleDays, ['saturday', 'sunday']);
            }
            
            // Merge similar periods if enabled
            if ($this->consolidationOptions['merge_similar_periods']) {
                $allPeriods = $this->mergeSimilarPeriods($allPeriods);
            }
            
            // Build the consolidated timetable matrix
            $consolidatedMatrix = $this->buildConsolidatedMatrix(
                $allPeriods,
                $allSchedules,
                $visibleDays,
                $selectedTimetables
            );
            
            // Generate summary
            $schoolName = Setting::where('key', 'school_name')->first()->value ?? 'School';
            $schoolLogo = Setting::where('key', 'school_logo')->first()->value ?? null;
            
            // Final consolidated data
            $this->consolidatedData = [
                'timetables' => $selectedTimetables,
                'periods' => $allPeriods,
                'schedules' => $allSchedules,
                'visibleDays' => $visibleDays,
                'consolidatedMatrix' => $consolidatedMatrix,
                'schoolName' => $schoolName,
                'schoolLogo' => $schoolLogo,
                'group_by' => $this->consolidationOptions['group_by'],
                'conflicts' => $this->consolidationStats['conflicts'],
            ];
            
            $this->isConsolidated = true;
            
            // Log successful consolidation
            Log::info('Successfully consolidated ' . $selectedTimetables->count() . ' timetables');
                
        } catch (\Exception $e) {
            Log::error('Error consolidating timetables: ' . $e->getMessage());
            $this->addConsolidationError('Failed to consolidate timetables: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }
    
    /**
     * Get all periods from selected timetables
     */
    private function getAllPeriods(array $timetableIds)
    {
        return TimetablePeriod::whereIn('timetable_id', $timetableIds)
            ->orderBy('start_time', 'asc')
            ->orderBy('period_order', 'asc')
            ->get();
    }
    
    /**
     * Get all schedules from selected timetables
     */
    private function getAllSchedules(array $timetableIds)
    {
        return TimetableSchedule::whereIn('timetable_id', $timetableIds)
            ->with(['subject', 'teacher', 'period', 'timetable', 'timetable.myClass'])
            ->get();
    }
    
    /**
     * Merge similar periods by start/end time
     */
    private function mergeSimilarPeriods(Collection $periods)
    {
        // Group periods by start time and end time to find matches
        $groupedPeriods = $periods->groupBy(function($period) {
            return $period->start_time . '-' . $period->end_time;
        });
        
        // Merged periods collection
        $mergedPeriods = collect();
        
        foreach ($groupedPeriods as $timeSlot => $periodGroup) {
            // Use the first period from each group
            $mergedPeriods->push($periodGroup->first());
        }
        
        return $mergedPeriods->sortBy('start_time');
    }
    
    /**
     * Build consolidated timetable matrix
     */
    private function buildConsolidatedMatrix(
        Collection $periods,
        Collection $schedules,
        array $visibleDays,
        Collection $timetables
    ) {
        $matrix = [];
        $conflicts = 0;
        
        // Process according to grouping strategy
        switch ($this->consolidationOptions['group_by']) {
            case 'teacher':
                $matrix = $this->buildTeacherBasedMatrix($periods, $schedules, $visibleDays, $timetables, $conflicts);
                break;
                
            case 'class':
                $matrix = $this->buildClassBasedMatrix($periods, $schedules, $visibleDays, $timetables, $conflicts);
                break;
                
            case 'subject':
                $matrix = $this->buildSubjectBasedMatrix($periods, $schedules, $visibleDays, $timetables, $conflicts);
                break;
                
            default:
                $this->addConsolidationError('Invalid grouping option selected: ' . $this->consolidationOptions['group_by']);
                $matrix = [];
        }
        
        $this->consolidationStats['conflicts'] = $conflicts;
        
        return $matrix;
    }
    
    /**
     * Build teacher-based consolidated matrix
     */
    private function buildTeacherBasedMatrix(
        Collection $periods,
        Collection $schedules,
        array $visibleDays,
        Collection $timetables,
        &$conflicts
    ) {
        $matrix = [];
        $teachers = $schedules->pluck('teacher')->unique('id')->filter()->values();
        
        foreach ($teachers as $teacher) {
            $matrix[$teacher->id] = [
                'teacher' => $teacher,
                'days' => []
            ];
            
            foreach ($visibleDays as $day) {
                $matrix[$teacher->id]['days'][$day] = [];
                
                foreach ($periods as $period) {
                    // Find all entries for this teacher, day and period time slot
                    $entries = $schedules->filter(function ($entry) use ($teacher, $day, $period) {
                        return $entry->teacher_id === $teacher->id && 
                               $entry->weekday === $day &&
                               $entry->period &&
                               $entry->period->start_time === $period->start_time && 
                               $entry->period->end_time === $period->end_time;
                    });
                    
                    // Check for conflicts (multiple classes in the same time slot)
                    if ($entries->count() > 1 && $this->consolidationOptions['highlight_conflicts']) {
                        $conflicts++;
                    }
                    
                    $matrix[$teacher->id]['days'][$day][$period->id] = [
                        'period' => $period,
                        'entries' => $entries,
                        'has_conflict' => $entries->count() > 1
                    ];
                }
            }
        }
        
        return $matrix;
    }
    
    /**
     * Build class-based consolidated matrix
     */
    private function buildClassBasedMatrix(
        Collection $periods,
        Collection $schedules,
        array $visibleDays,
        Collection $timetables,
        &$conflicts
    ) {
        $matrix = [];
        $classes = $timetables->pluck('myClass')->unique('id')->filter()->values();
        
        foreach ($classes as $class) {
            $matrix[$class->id] = [
                'class' => $class,
                'days' => []
            ];
            
            // Find all relevant timetable IDs for this class
            $timetableIdsForClass = $timetables->where('class_id', $class->id)->pluck('id')->toArray();
            
            foreach ($visibleDays as $day) {
                $matrix[$class->id]['days'][$day] = [];
                
                foreach ($periods as $period) {
                    // Find all entries for this class, day and period time slot
                    $entries = $schedules->filter(function ($entry) use ($timetableIdsForClass, $day, $period) {
                        return in_array($entry->timetable_id, $timetableIdsForClass) && 
                               $entry->weekday === $day &&
                               $entry->period &&
                               $entry->period->start_time === $period->start_time && 
                               $entry->period->end_time === $period->end_time;
                    });
                    
                    // Check for conflicts (multiple teachers for the same subject in the same time slot)
                    if ($entries->groupBy('subject_id')->filter(function($group) {
                        return $group->groupBy('teacher_id')->count() > 1;
                    })->count() > 0) {
                        $conflicts++;
                    }
                    
                    $matrix[$class->id]['days'][$day][$period->id] = [
                        'period' => $period,
                        'entries' => $entries,
                        'has_conflict' => $entries->groupBy('subject_id')->filter(function($group) {
                            return $group->groupBy('teacher_id')->count() > 1;
                        })->count() > 0
                    ];
                }
            }
        }
        
        return $matrix;
    }
    
    /**
     * Build subject-based consolidated matrix
     */
    private function buildSubjectBasedMatrix(
        Collection $periods,
        Collection $schedules,
        array $visibleDays,
        Collection $timetables,
        &$conflicts
    ) {
        $matrix = [];
        $subjects = $schedules->pluck('subject')->unique('id')->filter()->values();
        
        foreach ($subjects as $subject) {
            $matrix[$subject->id] = [
                'subject' => $subject,
                'days' => []
            ];
            
            foreach ($visibleDays as $day) {
                $matrix[$subject->id]['days'][$day] = [];
                
                foreach ($periods as $period) {
                    // Find all entries for this subject, day and period time slot
                    $entries = $schedules->filter(function ($entry) use ($subject, $day, $period) {
                        return $entry->subject_id === $subject->id && 
                               $entry->weekday === $day &&
                               $entry->period &&
                               $entry->period->start_time === $period->start_time && 
                               $entry->period->end_time === $period->end_time;
                    });
                    
                    // Check for conflicts (same subject taught by different teachers at the same time)
                    if ($entries->groupBy('teacher_id')->count() > 1) {
                        $conflicts++;
                    }
                    
                    $matrix[$subject->id]['days'][$day][$period->id] = [
                        'period' => $period,
                        'entries' => $entries,
                        'has_conflict' => $entries->groupBy('teacher_id')->count() > 1
                    ];
                }
            }
        }
        
        return $matrix;
    }
    
    /**
     * Add consolidation error with timestamp
     */
    private function addConsolidationError($message)
    {
        $this->consolidationErrors[] = [
            'time' => now()->format('H:i:s'),
            'message' => $message
        ];
        
        // Store the message for the toast
        session()->flash('toast_message', $message);
        session()->flash('toast_type', 'error');
        // Dispatch without parameters
        $this->dispatch('show-toast');
    }
    
    /**
     * Export consolidated timetable as PDF
     */
    public function exportPDF()
    {
        if (!$this->isConsolidated || !$this->consolidatedData) {
            $this->addConsolidationError('Cannot export: No consolidated timetable data available.');
            return;
        }
        
        $this->exportType = 'pdf';
        
        try {
            // Create unique ID for this consolidated timetable
            $consolidationId = md5(json_encode([
                'timetables' => $this->selectedTimetableIds,
                'options' => $this->consolidationOptions,
                'timestamp' => now()->timestamp
            ]));
            
            // Store consolidation data in session
            session()->put('consolidated_timetable_' . $consolidationId, $this->consolidatedData);
            
            // Construct export URL
            $exportUrl = route('tt.consolidated.export.pdf', ['id' => $consolidationId]);
            
            // Store URL in session and trigger download
            session()->flash('download_url', $exportUrl);
            $this->dispatch('triggerDownload');
            
        } catch (\Exception $e) {
            Log::error('Error exporting consolidated timetable to PDF: ' . $e->getMessage());
            $this->addConsolidationError('Failed to export as PDF: ' . $e->getMessage());
        } finally {
            $this->exportType = '';
        }
    }
    
    /**
     * Export consolidated timetable as Excel
     */
    public function exportExcel()
    {
        if (!$this->isConsolidated || !$this->consolidatedData) {
            $this->addConsolidationError('Cannot export: No consolidated timetable data available.');
            return;
        }
        
        $this->exportType = 'excel';
        
        try {
            // Create unique ID for this consolidated timetable
            $consolidationId = md5(json_encode([
                'timetables' => $this->selectedTimetableIds,
                'options' => $this->consolidationOptions,
                'timestamp' => now()->timestamp
            ]));
            
            // Store consolidation data in session
            session()->put('consolidated_timetable_' . $consolidationId, $this->consolidatedData);
            
            // Construct export URL
            $exportUrl = route('tt.consolidated.export.excel', ['id' => $consolidationId]);
            
            // Store URL in session and trigger download
            session()->flash('download_url', $exportUrl);
            $this->dispatch('triggerDownload');
            
        } catch (\Exception $e) {
            Log::error('Error exporting consolidated timetable to Excel: ' . $e->getMessage());
            $this->addConsolidationError('Failed to export as Excel: ' . $e->getMessage());
        } finally {
            $this->exportType = '';
        }
    }
    
    /**
     * Print consolidated timetable
     */
    public function printTimetable()
    {
        if (!$this->isConsolidated || !$this->consolidatedData) {
            $this->addConsolidationError('Cannot print: No consolidated timetable data available.');
            return;
        }
        
        $this->exportType = 'print';
        
        try {
            // Create unique ID for this consolidated timetable
            $consolidationId = md5(json_encode([
                'timetables' => $this->selectedTimetableIds,
                'options' => $this->consolidationOptions,
                'timestamp' => now()->timestamp
            ]));
            
            // Store consolidation data in session
            session()->put('consolidated_timetable_' . $consolidationId, $this->consolidatedData);
            
            // Construct print URL
            $printUrl = route('tt.consolidated.print', ['id' => $consolidationId]);
            
            // Store URL in session and trigger print window
            session()->flash('print_url', $printUrl);
            $this->dispatch('openPrintWindow');
            
        } catch (\Exception $e) {
            Log::error('Error preparing consolidated timetable for printing: ' . $e->getMessage());
            $this->addConsolidationError('Failed to prepare print view: ' . $e->getMessage());
        } finally {
            $this->exportType = '';
        }
    }
    
    public function render()
    {
        return view('livewire.timetable.timetable-consolidator');
    }
} 