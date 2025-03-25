<?php

namespace App\Livewire\Timetable;

use App\Models\TimetablePeriod;
use App\Models\TimetableSchedule;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolTimetable;
use App\Models\Subject;
use App\Models\StaffRecord;

class AutoGenerateService
{
    /**
     * Generate a timetable based on teacher-subject assignments
     *
     * @param array $config The configuration options for generation
     * @param int $timetableId The timetable ID
     * @param int $classId The class ID
     * @param int|null $sectionId The section ID (optional)
     * @param string|null $className The class name (for classroom)
     * @param string|null $sectionName The section name (for classroom)
     * @return array Statistics about the generation process
     */
    public function generate(
        array $config,
        int $timetableId,
        int $classId,
        ?int $sectionId,
        ?string $className,
        ?string $sectionName
    ): array {
        try {
            \Log::info("Starting auto-generation for timetable ID: {$timetableId}", [
                'class_id' => $classId,
                'section_id' => $sectionId,
                'config' => $config
            ]);
            
            // Initialize statistics
            $stats = [
                'entries_created' => 0,
                'conflicts' => 0,
                'teacher_conflicts' => 0,
                'category_conflicts' => 0,
                'subject_conflicts' => 0,
                'periods_processed' => 0,
                'days_processed' => 0,
                'validation_errors' => 0,
                'success' => false,
                'message' => '',
            ];
            
            // Get the timetable
            $timetable = SchoolTimetable::find($timetableId);
            if (!$timetable) {
                $stats['message'] = "Timetable not found";
                $stats['validation_errors']++;
                \Log::error("Timetable not found with ID: {$timetableId}");
                return $stats;
            }
            
            // Clear existing entries if requested
            if (isset($config['clear_existing']) && $config['clear_existing']) {
                $existingCount = TimetableSchedule::where('timetable_id', $timetableId)->count();
                TimetableSchedule::where('timetable_id', $timetableId)->delete();
                $stats['cleared'] = $existingCount;
                Log::info("Cleared {$existingCount} existing timetable entries for timetable ID {$timetableId}");
            } else if (isset($config['respect_existing_entries']) && $config['respect_existing_entries']) {
                $existingCount = TimetableSchedule::where('timetable_id', $timetableId)->count();
                Log::info("Respecting {$existingCount} existing timetable entries for timetable ID {$timetableId}");
            }
            
            // Get all assignable periods
            $allPeriods = $this->getAssignablePeriods($timetableId);
            if ($allPeriods->isEmpty()) {
                Log::warning("No assignable periods found for timetable ID {$timetableId}");
                return $stats;
            }
            
            // Get all teacher-subject assignments and group them by subject
            $allAssignments = $this->getRelevantTeacherAssignments($classId, $sectionId);
            if ($allAssignments->isEmpty()) {
                Log::warning("No teacher subject assignments found for class ID {$classId} and section ID {$sectionId}");
                return $stats;
            }

            // Group assignments by subject category to allow for category-specific rules
            $assignmentsByCategory = $this->groupAssignmentsByCategory($allAssignments);
            
            // Create a teacher workload tracking array
            $teacherWorkload = [];
            
            // Track weekly subject distribution
            $weeklySubjectCount = [];
            
            // Get existing entries to respect if configured
            $existingEntries = collect();
            if (isset($config['respect_existing_entries']) && $config['respect_existing_entries']) {
                $existingEntries = TimetableSchedule::where('timetable_id', $timetableId)->get();
                Log::info("Loaded {$existingEntries->count()} existing entries to respect");
            }
            
            // Track the categories scheduled each day
            $dailyCategoryTracker = [];
            
            // For each selected day
            foreach ($config['days'] as $day) {
                // Skip non-weekday values
                if (!in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])) {
                    continue;
                }
                
                // Initialize tracking for this day
                $dailyCategoryTracker[$day] = [];
                
                // Get periods for this day and sort them by start time
                $dayPeriods = $allPeriods->sortBy('start_time');
                if ($dayPeriods->isEmpty()) {
                    continue;
                }

                // Map periods to time of day (morning, midday, afternoon)
                $periodsByTimeOfDay = $this->mapPeriodsToTimeOfDay($dayPeriods, $config['time_preferences']);
                
                // Track subjects used today to avoid exceeding daily limits
                $subjectsUsedToday = [];
                $categoriesUsedToday = [];
                $consecutiveCategories = [];
                
                // For each period in this day
                foreach ($dayPeriods as $period) {
                    $periodId = $period->id;
                    $timeOfDay = $this->getPeriodTimeOfDay($period, $config['time_preferences']);
                    
                    // Check if this slot already has an entry that we should respect
                    if ($config['respect_existing_entries']) {
                        $existingEntry = $existingEntries->first(function($entry) use ($day, $periodId) {
                            return $entry->weekday === $day && $entry->period_id === $periodId;
                        });
                        
                        if ($existingEntry) {
                            Log::debug("Respecting existing entry for {$day}, period {$periodId}");
                            continue;
                        }
                    }
                    
                    // Skip if we've reached max subjects per day
                    if (count($subjectsUsedToday) >= $config['max_daily_subjects']) {
                        $stats['skipped']++;
                        continue;
                    }
                    
                    // If we need to ensure daily category variety, prioritize missing categories
                    $prioritizedCategories = [];
                    if (isset($config['ensure_daily_category_variety']) && $config['ensure_daily_category_variety']) {
                        // Make sure min_category_per_day is set, use empty array if not
                        $minCategoriesPerDay = $config['min_category_per_day'] ?? [];
                        
                        // If min_category_per_day is empty, set some sensible defaults
                        if (empty($minCategoriesPerDay)) {
                            $minCategoriesPerDay = [
                                'Core' => 2,
                                'Language' => 1,
                                'Humanities' => 1,
                                'Science' => 1
                            ];
                        }
                        
                        foreach ($minCategoriesPerDay as $category => $minCount) {
                            $currentCount = isset($categoriesUsedToday[$category]) ? $categoriesUsedToday[$category] : 0;
                            if ($currentCount < $minCount) {
                                $prioritizedCategories[] = $category;
                            }
                        }
                    }

                    // Find a suitable assignment for this slot considering time of day preferences
                    $assignment = $this->findSuitableAssignmentAdvanced(
                        $allAssignments,
                        $assignmentsByCategory,
                        $day,
                        $periodId,
                        $timeOfDay,
                        $subjectsUsedToday,
                        $categoriesUsedToday,
                        $consecutiveCategories,
                        $teacherWorkload,
                        $weeklySubjectCount,
                        $config,
                        $prioritizedCategories
                    );
                    
                    if (!$assignment) {
                        $stats['skipped']++;
                        continue;
                    }
                    
                    // Create the timetable entry
                    try {
                        $classroom = '';
                        if ($sectionId && $className && $sectionName) {
                            $classroom = "{$className} - {$sectionName}";
                        } elseif ($className) {
                            $classroom = $className;
                        }
                        
                        // Get the subject category for tracking
                        $subjectCategory = $this->getSubjectCategory($assignment->subject_id);
                        
                        // Use our new method to create the timetable entry
                        $entry = $this->createTimetableEntry(
                            $timetableId,
                            $periodId,
                            $day,
                            $assignment->subject_id,
                            $classId,
                            $sectionId,
                            $classroom,
                            $config,
                            $stats
                        );
                        
                        if (!$entry) {
                            continue;
                        }
                        
                        // Track this subject to avoid consecutive assignments
                        $subjectsUsedToday[$assignment->subject_id] = isset($subjectsUsedToday[$assignment->subject_id]) 
                            ? $subjectsUsedToday[$assignment->subject_id] + 1 
                            : 1;
                        
                        // Track category usage
                        if ($subjectCategory) {
                            $categoriesUsedToday[$subjectCategory] = isset($categoriesUsedToday[$subjectCategory]) 
                                ? $categoriesUsedToday[$subjectCategory] + 1 
                                : 1;
                            
                            // Update the daily category tracker
                            if (!isset($dailyCategoryTracker[$day][$subjectCategory])) {
                                $dailyCategoryTracker[$day][$subjectCategory] = 0;
                            }
                            $dailyCategoryTracker[$day][$subjectCategory]++;
                            
                            // Track consecutive assignments for this category
                            $consecutiveCategories[$subjectCategory] = isset($consecutiveCategories[$subjectCategory]) 
                                ? $consecutiveCategories[$subjectCategory] + 1 
                                : 1;
                            
                            // Reset consecutive count for other categories
                            foreach (array_keys($consecutiveCategories) as $cat) {
                                if ($cat !== $subjectCategory) {
                                    $consecutiveCategories[$cat] = 0;
                                }
                            }
                        }
                        
                        // Track teacher workload
                        if (!isset($teacherWorkload[$assignment->teacher_id])) {
                            $teacherWorkload[$assignment->teacher_id] = 0;
                        }
                        $teacherWorkload[$assignment->teacher_id]++;
                        
                        // Track weekly subject count
                        if (!isset($weeklySubjectCount[$assignment->subject_id])) {
                            $weeklySubjectCount[$assignment->subject_id] = 0;
                        }
                        $weeklySubjectCount[$assignment->subject_id]++;
                        
                        $stats['entries_created']++;
                    } catch (\Exception $e) {
                        Log::error("Failed to create timetable entry: " . $e->getMessage());
                        $stats['conflicts']++;
                    }
                }
            }
            
            // Check if we've met the weekly frequency requirements
            if ($config['enable_balanced_distribution']) {
                $this->balanceRemainingFrequencies(
                    $config, 
                    $timetableId, 
                    $allAssignments, 
                    $assignmentsByCategory,
                    $weeklySubjectCount, 
                    $dailyCategoryTracker,
                    $teacherWorkload,
                    $stats,
                    $className,
                    $sectionName
                );
            }
            
            $stats['success'] = true;
            $stats['message'] = "Timetable generation completed successfully";
            return $stats;
        } catch (\Exception $e) {
            \Log::error("Exception in generateTimetable: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'timetable_id' => $timetableId,
                'class_id' => $classId,
                'section_id' => $sectionId
            ]);
            
            return [
                'success' => false,
                'message' => "An error occurred: " . $e->getMessage(),
                'entries_created' => $stats['entries_created'] ?? 0,
                'conflicts' => $stats['conflicts'] ?? 0,
                'teacher_conflicts' => $stats['teacher_conflicts'] ?? 0,
                'validation_errors' => 1,
            ];
        }
    }
    
    /**
     * Get all assignable periods
     * 
     * @param int $timetableId
     * @return Collection
     */
    private function getAssignablePeriods(int $timetableId): Collection
    {
        $periods = TimetablePeriod::where('timetable_id', $timetableId)
            ->ordered()
            ->get();
            
        return $periods->filter(function ($period) {
            return $this->canAssignClass($period);
        });
    }
    
    /**
     * Determine if a period allows class assignment
     * 
     * @param TimetablePeriod $period
     * @return bool
     */
    private function canAssignClass($period): bool
    {
        if (!$period) {
            return false;
        }
        
        $periodName = strtolower($period->period_name);
        
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
    
    /**
     * Get relevant teacher-subject assignments for this class/section
     * 
     * @param int $classId
     * @param int|null $sectionId
     * @return Collection
     */
    private function getRelevantTeacherAssignments(int $classId, ?int $sectionId = null): Collection
    {
        try {
            $query = TeacherSubjectAssignment::with(['teacher', 'subject.category'])
                ->where('class_id', $classId)
            ->where('is_active', true);
            
        if ($sectionId) {
                // Allow assignments with matching section_id OR null section_id (class-wide assignments)
                $query->where(function($q) use ($sectionId) {
                    $q->where('section_id', $sectionId)
                      ->orWhereNull('section_id');
                });
            }
            
            $assignments = $query->get();
            
            Log::debug("Found {$assignments->count()} teacher-subject assignments for class {$classId}" . 
                      ($sectionId ? ", section {$sectionId}" : ""));
                      
            return $assignments;
        } catch (\Exception $e) {
            Log::error("Error getting teacher assignments: " . $e->getMessage());
            return collect();
        }
    }
    
    /**
     * Group assignments by subject category
     *
     * @param Collection|array $assignments
     * @return array
     */
    private function groupAssignmentsByCategory($assignments): array
    {
        // Ensure we have a collection to work with
        if (is_array($assignments)) {
            $assignments = collect($assignments);
        }
        
        $groupedAssignments = [];
        
        foreach ($assignments as $assignment) {
            // Skip if subject or category is missing
            if (!$assignment->subject) {
                continue;
            }
            
            $category = $this->getSubjectCategory($assignment->subject_id);
            
            // Use "Uncategorized" if category is not found
            if (!$category) {
                $category = 'Uncategorized';
            }
            
            if (!isset($groupedAssignments[$category])) {
                $groupedAssignments[$category] = [];
            }
            
            $groupedAssignments[$category][] = $assignment;
        }
        
        return $groupedAssignments;
    }
    
    /**
     * Get the subject category name
     *
     * @param int $subjectId
     * @return string|null
     */
    private function getSubjectCategory(int $subjectId): ?string
    {
        try {
        $subject = \App\Models\Subject::with('category')->find($subjectId);
        
        if (!$subject || !$subject->category) {
            return null;
        }
        
            return $subject->category->name;
        } catch (\Exception $e) {
            Log::error("Error getting subject category: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Map periods to time of day
     *
     * @param Collection $periods
     * @param array $timePreferences
     * @return array
     */
    private function mapPeriodsToTimeOfDay(Collection $periods, array $timePreferences): array
    {
        $periodsByTimeOfDay = [
            'morning' => [],
            'midday' => [],
            'afternoon' => [],
        ];
        
        foreach ($periods as $period) {
            $timeOfDay = $this->getPeriodTimeOfDay($period, $timePreferences);
            $periodsByTimeOfDay[$timeOfDay][] = $period->id;
        }
        
        return $periodsByTimeOfDay;
    }
    
    /**
     * Determine the time of day for a period
     *
     * @param object $period
     * @param array $timePreferences
     * @return string
     */
    private function getPeriodTimeOfDay($period, array $timePreferences): string
    {
        try {
        // Convert start time to minutes since midnight
        $startTime = $period->start_time;
        $parts = explode(':', $startTime);
        $minutes = (intval($parts[0]) * 60) + intval($parts[1]);
        
            // Get midpoint of period (better than just using start time)
            $endTime = $period->end_time;
            $endParts = explode(':', $endTime);
            $endMinutes = (intval($endParts[0]) * 60) + intval($endParts[1]);
            
            // Use the midpoint of the period for more accurate categorization
            $midpointMinutes = ($minutes + $endMinutes) / 2;
            
            $timeOfDay = 'any';
            if ($midpointMinutes <= $timePreferences['morning_end']) {
                $timeOfDay = 'morning';
            } elseif ($midpointMinutes <= $timePreferences['midday_end']) {
                $timeOfDay = 'midday';
        } else {
                $timeOfDay = 'afternoon';
            }
            
            \Log::debug("Period {$period->period_name} (starts at {$startTime}, ends at {$endTime}) categorized as {$timeOfDay}");
            
            return $timeOfDay;
        } catch (\Exception $e) {
            \Log::error("Error determining time of day for period: " . $e->getMessage());
            return 'any'; // Default to any time if there's an error
        }
    }
    
    /**
     * Find a suitable assignment based on various conditions and priorities
     *
     * @param Collection|array $allAssignments
     * @param array $assignmentsByCategory
     * @param string $day
     * @param int $periodId
     * @param string $timeOfDay
     * @param array $subjectsUsedToday
     * @param array $categoriesUsedToday
     * @param array $consecutiveCategories
     * @param array $teacherWorkload
     * @param array $weeklySubjectCount
     * @param array $config
     * @param array $prioritizedCategories
     * @return TeacherSubjectAssignment|null
     */
    private function findSuitableAssignmentAdvanced(
        $allAssignments,
        array $assignmentsByCategory,
        string $day,
        int $periodId,
        string $timeOfDay,
        array $subjectsUsedToday,
        array $categoriesUsedToday,
        array $consecutiveCategories,
        array $teacherWorkload,
        array $weeklySubjectCount,
        array $config,
        array $prioritizedCategories = []
    ) {
        // Ensure we have a collection to work with
        if (is_array($allAssignments)) {
            $allAssignments = collect($allAssignments);
        }

        // Find teachers who are already scheduled for this period
        $busyTeachers = $this->findBusyTeachers($day, $periodId);
        
        \Log::debug("Finding assignment for day={$day}, period={$periodId}, timeOfDay={$timeOfDay}");
        \Log::debug("Busy teachers: " . implode(', ', $busyTeachers));
        
        // If we're prioritizing specific categories, try those first
        if (!empty($prioritizedCategories) && isset($config['min_category_per_day'])) {
            \Log::debug("Prioritizing categories: " . implode(', ', $prioritizedCategories));
            foreach ($prioritizedCategories as $category) {
                if (!isset($assignmentsByCategory[$category]) || empty($assignmentsByCategory[$category])) {
                    continue;
                }
                
                $assignment = $this->findBestAssignmentInCategory(
                    $assignmentsByCategory[$category],
                    $busyTeachers,
                    $subjectsUsedToday,
                    $categoriesUsedToday,
                    $consecutiveCategories,
                    $teacherWorkload,
                    $weeklySubjectCount,
                    $timeOfDay,
                    $category,
                    $config
                );
                
                if ($assignment) {
                    \Log::debug("Found prioritized category assignment for {$category}: Subject ID {$assignment->subject_id}");
                    return $assignment;
                }
            }
        }
        
        // Time preference score for ranking subjects (more granular prioritization)
        $timePreferenceScores = [
            'exact_match' => [], // Perfect time preference match
            'any_time' => [],    // Subject with "any" time preference
            'off_time' => [],    // Not preferred time but acceptable
            'avoid_time' => []   // Time that should be avoided (lowest priority)
        ];
        
        // If we have subject time preferences enabled, try to match time of day
        if ($config['enable_subject_time_preferences']) {
            \Log::debug("Subject time preferences enabled, preferred time of day: {$timeOfDay}");
            
            // Score all valid subjects by time preference match
            foreach ($allAssignments as $assignment) {
                $subjectId = $assignment->subject_id;
                
                // Skip if no preferences for this subject
                if (!isset($config['subject_preferences'][$subjectId])) {
                    continue;
                }
                
                // Get subject details for logging
                $subjectName = $config['subject_preferences'][$subjectId]['name'] ?? "Subject #{$subjectId}";
                
                // Check if teacher is busy
                if (in_array($assignment->teacher_id, $busyTeachers)) {
                    \Log::debug("Teacher {$assignment->teacher_id} is busy, skipping subject {$subjectName}");
                    continue;
                }
                
                // Check if we've reached daily limit for this subject
                $currentDailyCount = $subjectsUsedToday[$subjectId] ?? 0;
                $dailyLimit = $config['subject_preferences'][$subjectId]['daily_limit'] ?? 1;
                if ($currentDailyCount >= $dailyLimit) {
                    \Log::debug("Subject {$subjectName} has reached daily limit ({$currentDailyCount}/{$dailyLimit}), skipping");
                    continue;
                }
                
                // Check weekly frequency
                $currentWeeklyCount = $weeklySubjectCount[$subjectId] ?? 0;
                $weeklyFrequency = $config['subject_preferences'][$subjectId]['weekly_frequency'] ?? 3;
                if ($currentWeeklyCount >= $weeklyFrequency) {
                    \Log::debug("Subject {$subjectName} has reached weekly frequency ({$currentWeeklyCount}/{$weeklyFrequency}), skipping");
                    continue;
                }
                
                // Check preferred time slot
                $preferredTime = $config['subject_preferences'][$subjectId]['preferred_time'] ?? 'any';
                
                // Score by time preference match
                if ($preferredTime === 'any') {
                    $timePreferenceScores['any_time'][] = $assignment;
                    \Log::debug("Subject {$subjectName} has 'any' time preference, adding to any_time list");
                } else if ($preferredTime === $timeOfDay) {
                    $timePreferenceScores['exact_match'][] = $assignment;
                    \Log::debug("Subject {$subjectName} has exact time preference match for {$timeOfDay}, adding to exact_match list");
                } else if (
                    ($preferredTime === 'morning' && $timeOfDay === 'midday') || 
                    ($preferredTime === 'midday' && ($timeOfDay === 'morning' || $timeOfDay === 'afternoon')) ||
                    ($preferredTime === 'afternoon' && $timeOfDay === 'midday')
                ) {
                    $timePreferenceScores['off_time'][] = $assignment;
                    \Log::debug("Subject {$subjectName} has off-time preference ({$preferredTime} vs {$timeOfDay}), adding to off_time list");
                } else {
                    $timePreferenceScores['avoid_time'][] = $assignment;
                    \Log::debug("Subject {$subjectName} should avoid this time ({$preferredTime} vs {$timeOfDay}), adding to avoid_time list");
                }
            }
            
            // Try each time preference category in order
            foreach (['exact_match', 'any_time', 'off_time', 'avoid_time'] as $preferenceCategory) {
                if (empty($timePreferenceScores[$preferenceCategory])) {
                    continue;
                }
                
                \Log::debug("Checking {$preferenceCategory} time preference category with " . count($timePreferenceScores[$preferenceCategory]) . " subjects");
                
                // Sort by weekly frequency needs (subjects that need more days get priority)
                usort($timePreferenceScores[$preferenceCategory], function($a, $b) use ($config, $weeklySubjectCount) {
                    $aSubjectId = $a->subject_id;
                    $bSubjectId = $b->subject_id;
                    
                    // Get current and target frequencies
                    $aCurrentCount = $weeklySubjectCount[$aSubjectId] ?? 0;
                    $bCurrentCount = $weeklySubjectCount[$bSubjectId] ?? 0;
                    
                    $aTargetFreq = $config['subject_preferences'][$aSubjectId]['weekly_frequency'] ?? 3;
                    $bTargetFreq = $config['subject_preferences'][$bSubjectId]['weekly_frequency'] ?? 3;
                    
                    // Calculate remaining lessons needed
                    $aRemaining = max(0, $aTargetFreq - $aCurrentCount);
                    $bRemaining = max(0, $bTargetFreq - $bCurrentCount);
                    
                    // Sort by most remaining first
                    return $bRemaining <=> $aRemaining;
                });
                
                foreach ($timePreferenceScores[$preferenceCategory] as $assignment) {
                $subjectId = $assignment->subject_id;
                    $subjectName = $config['subject_preferences'][$subjectId]['name'] ?? "Subject #{$subjectId}";
                    
                    // Check max consecutive rule if we have category data
                    $category = $this->getSubjectCategory($subjectId);
                    if ($category && isset($consecutiveCategories[$category])) {
                        $currentConsecutive = $consecutiveCategories[$category];
                        $maxConsecutive = $config['subject_preferences'][$subjectId]['max_consecutive'] ?? 2;
                        if ($currentConsecutive >= $maxConsecutive) {
                            \Log::debug("Subject {$subjectName} has reached max consecutive ({$currentConsecutive}/{$maxConsecutive}), skipping");
                    continue;
                        }
                }
                
                    \Log::debug("Selected subject {$subjectName} from {$preferenceCategory} category");
                    return $assignment;
                }
            }
        }
        
        // Fallback to any available assignment
        // Prioritize based on teacher workload if enabled
        if ($config['balance_teacher_workload']) {
            \Log::debug("Using teacher workload balancing as fallback");
            $teachersByWorkload = [];
            
            foreach ($allAssignments as $assignment) {
                $teacherId = $assignment->teacher_id;
                
                // Skip busy teachers
                if (in_array($teacherId, $busyTeachers)) {
                    continue;
                }
                
                $workload = $teacherWorkload[$teacherId] ?? 0;
                
                if (!isset($teachersByWorkload[$workload])) {
                    $teachersByWorkload[$workload] = [];
                }
                
                $teachersByWorkload[$workload][] = $assignment;
            }
            
            // Sort by workload (lowest first)
            ksort($teachersByWorkload);
            
            // Try each workload level
            foreach ($teachersByWorkload as $workload => $assignments) {
                foreach ($assignments as $assignment) {
                    $subjectId = $assignment->subject_id;
                    $subjectName = isset($config['subject_preferences'][$subjectId]) ? 
                                   $config['subject_preferences'][$subjectId]['name'] ?? "Subject #{$subjectId}" : 
                                   "Subject #{$subjectId}";
                    
                    // Check if we've reached daily limit for this subject
                    if (isset($config['subject_preferences'][$subjectId])) {
                        $currentDailyCount = $subjectsUsedToday[$subjectId] ?? 0;
                        $dailyLimit = $config['subject_preferences'][$subjectId]['daily_limit'] ?? 1;
                        if ($currentDailyCount >= $dailyLimit) {
                            continue;
                        }
                    }
                    
                    \Log::debug("Selected subject {$subjectName} based on teacher workload balancing (workload: {$workload})");
                    return $assignment;
                }
            }
        } else {
            \Log::debug("Using simple assignment fallback");
            // Just find any assignment that works
            foreach ($allAssignments as $assignment) {
                // Skip busy teachers
                if (in_array($assignment->teacher_id, $busyTeachers)) {
                    continue;
                }
                
                $subjectId = $assignment->subject_id;
                $subjectName = isset($config['subject_preferences'][$subjectId]) ? 
                               $config['subject_preferences'][$subjectId]['name'] ?? "Subject #{$subjectId}" : 
                               "Subject #{$subjectId}";
                
                // Check if we've reached daily limit for this subject
                if (isset($config['subject_preferences'][$subjectId])) {
                    $currentDailyCount = $subjectsUsedToday[$subjectId] ?? 0;
                    $dailyLimit = $config['subject_preferences'][$subjectId]['daily_limit'] ?? 1;
                    if ($currentDailyCount >= $dailyLimit) {
                        continue;
                    }
                }
                
                \Log::debug("Selected subject {$subjectName} as fallback with no special criteria");
                return $assignment;
            }
        }
        
        \Log::debug("No suitable assignment found for this slot");
        return null;
    }
    
    /**
     * Find the best assignment within a specific category
     *
     * @param array $assignments Assignments in this category
     * @param array $busyTeachers List of busy teacher IDs
     * @param array $subjectsUsedToday Already used subjects today
     * @param array $categoriesUsedToday Already used categories today
     * @param array $consecutiveCategories Consecutive count by category
     * @param array $teacherWorkload Current teacher workload
     * @param array $weeklySubjectCount Weekly count by subject
     * @param string $timeOfDay Current time of day
     * @param string $category Category being processed
     * @param array $config Configuration array
     * @return TeacherSubjectAssignment|null
     */
    private function findBestAssignmentInCategory(
        array $assignments,
        array $busyTeachers,
        array $subjectsUsedToday,
        array $categoriesUsedToday,
        array $consecutiveCategories,
        array $teacherWorkload,
        array $weeklySubjectCount,
        string $timeOfDay,
        string $category,
        array $config
    ) {
        // Check if we've reached max consecutive for this category
        $currentConsecutive = $consecutiveCategories[$category] ?? 0;
        $maxConsecutiveForCategory = 2; // Default max consecutive
        
        // Find the most restrictive max_consecutive from subjects in this category
        foreach ($assignments as $assignment) {
            if (isset($config['subject_preferences'][$assignment->subject_id])) {
                $subjectMaxConsecutive = $config['subject_preferences'][$assignment->subject_id]['max_consecutive'] ?? 2;
                $maxConsecutiveForCategory = min($maxConsecutiveForCategory, $subjectMaxConsecutive);
            }
        }
        
        if ($currentConsecutive >= $maxConsecutiveForCategory) {
            return null;
        }
        
        // Check if we've reached daily category limit
        $currentDailyCount = $categoriesUsedToday[$category] ?? 0;
        
        // Determine reasonable daily category limit based on subjects
        $dailyCategoryLimit = 0;
        foreach ($assignments as $assignment) {
            if (isset($config['subject_preferences'][$assignment->subject_id])) {
                $dailyCategoryLimit += $config['subject_preferences'][$assignment->subject_id]['daily_limit'] ?? 1;
            } else {
                $dailyCategoryLimit += 1; // Default to 1 if no preferences
            }
        }
        
        if ($currentDailyCount >= $dailyCategoryLimit) {
            return null;
        }
        
        // Filter to available assignments
        $availableAssignments = [];
        foreach ($assignments as $assignment) {
            // Skip if teacher is already scheduled for this period
            if (in_array($assignment->teacher_id, $busyTeachers)) {
                continue;
            }
            
            $subjectId = $assignment->subject_id;
            
            // Skip if avoiding consecutive subjects and this subject was just used
            if ($config['avoid_consecutive_subjects'] && 
                isset($subjectsUsedToday[$subjectId]) &&
                $subjectsUsedToday[$subjectId] > 0) {
                continue;
            }
            
            // Skip if we've reached the subject's daily limit
            if (isset($config['subject_preferences'][$subjectId])) {
                $dailyLimit = $config['subject_preferences'][$subjectId]['daily_limit'] ?? 1;
            
                if (isset($subjectsUsedToday[$subjectId]) && 
                    $subjectsUsedToday[$subjectId] >= $dailyLimit) {
                    continue;
            }
            
            // If using balanced distribution, check weekly frequency
            if ($config['enable_balanced_distribution']) {
                    $weeklyFrequency = $config['subject_preferences'][$subjectId]['weekly_frequency'] ?? 3;
                
                    if (isset($weeklySubjectCount[$subjectId]) && 
                        $weeklySubjectCount[$subjectId] >= $weeklyFrequency) {
                        continue;
                    }
                }
            }
            
            $availableAssignments[] = $assignment;
        }
        
        if (empty($availableAssignments)) {
            return null;
        }
        
        // Convert to collection for easier sorting
        $availableCollection = collect($availableAssignments);
        
        // Prioritize primary teachers if configured
        if ($config['prioritize_primary_teachers']) {
            $primaryAssignments = $availableCollection->where('is_primary', true);
            if ($primaryAssignments->isNotEmpty()) {
                $availableCollection = $primaryAssignments;
            }
        }
        
        // Balance teacher workload if configured
        if ($config['balance_teacher_workload']) {
            // Sort assignments by teacher workload (least busy teachers first)
            $availableCollection = $availableCollection->sortBy(function($assignment) use ($teacherWorkload) {
                return $teacherWorkload[$assignment->teacher_id] ?? 0;
            });
        }
        
        // Sort by weekly frequency if enabled
        if ($config['enable_balanced_distribution']) {
            $availableCollection = $availableCollection->sortBy(function($assignment) use ($weeklySubjectCount) {
                return $weeklySubjectCount[$assignment->subject_id] ?? 0;
            });
        }
        
        // Return the best match
        return $availableCollection->first();
    }
    
    /**
     * Balance weekly subject frequencies by adding more lessons where needed
     *
     * @param array $config
     * @param int $timetableId
     * @param Collection|array $allAssignments
     * @param array $assignmentsByCategory
     * @param array $weeklySubjectCount
     * @param array $dailyCategoryTracker
     * @param array $teacherWorkload
     * @param array &$stats
     * @param string|null $className
     * @param string|null $sectionName
     */
    private function balanceRemainingFrequencies(
        array $config,
        int $timetableId,
        $allAssignments,
        array $assignmentsByCategory,
        array $weeklySubjectCount,
        array $dailyCategoryTracker,
        array $teacherWorkload,
        array &$stats,
        ?string $className,
        ?string $sectionName
    ) {
        // Ensure we have a collection to work with
        if (is_array($allAssignments)) {
            $allAssignments = collect($allAssignments);
        }
        
        $needsBalancing = false;
        
        // Check if any subjects need more lessons to meet their weekly frequency
        foreach ($allAssignments as $assignment) {
            $subjectId = $assignment->subject_id;
            
            // Skip if no preferences for this subject
            if (!isset($config['subject_preferences'][$subjectId])) {
                continue;
            }
            
            $weeklyFrequency = $config['subject_preferences'][$subjectId]['weekly_frequency'] ?? 3;
            $currentCount = $weeklySubjectCount[$subjectId] ?? 0;
            
                if ($currentCount < $weeklyFrequency) {
                    $needsBalancing = true;
                break;
            }
        }
        
        if (!$needsBalancing) {
            return;
        }
        
        // Find day-period combinations that could accommodate additional lessons
        $schedules = TimetableSchedule::where('timetable_id', $timetableId)->get();
        $occupiedSlots = [];
        
        foreach ($schedules as $schedule) {
            $slot = $schedule->weekday . '-' . $schedule->period_id;
            $occupiedSlots[$slot] = true;
        }
        
        // Get all timetable periods
        $periods = $this->getAssignablePeriods($timetableId);
        if ($periods->isEmpty()) {
            return;
        }
        
        // Sort subjects by how far they are from meeting their weekly frequency
        $subjectsNeedingMoreLessons = [];
        
        foreach ($allAssignments as $assignment) {
            $subjectId = $assignment->subject_id;
            
            // Skip if no preferences for this subject
            if (!isset($config['subject_preferences'][$subjectId])) {
                continue;
            }
            
            $weeklyFrequency = $config['subject_preferences'][$subjectId]['weekly_frequency'] ?? 3;
            $currentCount = $weeklySubjectCount[$subjectId] ?? 0;
            
            if ($currentCount < $weeklyFrequency) {
                if (!isset($subjectsNeedingMoreLessons[$subjectId])) {
                    $subjectsNeedingMoreLessons[$subjectId] = [
                        'needed' => $weeklyFrequency - $currentCount,
                        'assignment' => $assignment,
                        'name' => $config['subject_preferences'][$subjectId]['name'] ?? 'Subject #' . $subjectId
                    ];
                }
            }
        }
        
        // Sort by most needed first
        uasort($subjectsNeedingMoreLessons, function($a, $b) {
            return $b['needed'] <=> $a['needed'];
        });
        
        // For each subject needing more lessons, try to find slots
        foreach ($subjectsNeedingMoreLessons as $subjectId => $data) {
            $needed = $data['needed'];
            $assignment = $data['assignment'];
            $subjectName = $data['name'];
            
            Log::debug("Balancing: Subject {$subjectName} needs {$needed} more lessons");
            
            // Try each day
            foreach ($config['days'] as $day) {
                // Skip if we've added enough lessons already
                if ($needed <= 0) {
                    break;
                }
                
                // Get the days that already have this subject
                $daysWithSubject = $schedules
                    ->where('subject_id', $subjectId)
                    ->pluck('weekday')
                    ->unique()
                    ->toArray();
                
                // If this day already has this subject, skip to ensure variety
                if (in_array($day, $daysWithSubject) && $config['ensure_daily_subject_variety']) {
                    continue;
                }
                
                // Get periods that aren't occupied on this day
                $availablePeriods = $periods->filter(function($period) use ($day, $occupiedSlots) {
                    $slot = $day . '-' . $period->id;
                    return !isset($occupiedSlots[$slot]);
                })->sortBy('start_time');
                
                // Check for a free slot
                foreach ($availablePeriods as $period) {
                    $periodId = $period->id;
                    $slot = $day . '-' . $periodId;
                    
                    // Skip if the slot is now occupied
                    if (isset($occupiedSlots[$slot])) {
                        continue;
                    }
                    
                    // Check if the teacher is already busy in this period
                    $teacherId = $assignment->teacher_id;
                    $busyTeachers = $this->findBusyTeachers($day, $periodId);
                    
                    if (in_array($teacherId, $busyTeachers)) {
                        continue;
                    }
                    
                    // This slot works - create an entry
                    try {
                        $classroom = '';
                        if ($className && $sectionName) {
                            $classroom = "{$className} - {$sectionName}";
                        } elseif ($className) {
                            $classroom = $className;
                        }
                        
                        TimetableSchedule::create([
                            'timetable_id' => $timetableId,
                            'period_id' => $periodId,
                            'subject_id' => $subjectId,
                            'teacher_id' => $teacherId,
                            'weekday' => $day,
                            'classroom' => $classroom,
                            'notes' => "Auto-balanced on " . now()->format('Y-m-d H:i'),
                            'is_recurring' => true,
                            'specific_date' => null,
                        ]);
                        
                        // Mark this slot as occupied
                        $occupiedSlots[$slot] = true;
                        
                        // Update teacher workload
                        if (!isset($teacherWorkload[$teacherId])) {
                            $teacherWorkload[$teacherId] = 0;
                        }
                        $teacherWorkload[$teacherId]++;
                        
                        $stats['entries_created']++;
                        $needed--;
                        
                        Log::debug("Added balancing slot for {$subjectName} on {$day}");
                        
                        // Break out of the period loop - we've found a slot for this day
                        break;
                    } catch (\Exception $e) {
                        Log::error("Failed to create balanced timetable entry: " . $e->getMessage());
                        $stats['conflicts']++;
                    }
                }
            }
        }
    }

    /**
     * Find busy teachers for a specific day and period
     * 
     * @param string $day
     * @param int $periodId
     * @return array
     */
    private function findBusyTeachers(string $day, int $periodId): array
    {
        // Query existing timetable entries for this day and period
        $busyTeacherIds = TimetableSchedule::where('weekday', $day)
            ->where('period_id', $periodId)
            ->whereNotNull('teacher_id')
            ->pluck('teacher_id')
            ->toArray();
            
        return $busyTeacherIds;
    }

    /**
     * Find a teacher for a subject based on assignments and availability
     *
     * @param int $subjectId
     * @param int $classId
     * @param int|null $sectionId
     * @param array $busyTeachers
     * @param bool $prioritizePrimary
     * @return int|null
     */
    private function findTeacherForSubject(
        int $subjectId,
        int $classId,
        ?int $sectionId,
        array $busyTeachers,
        bool $prioritizePrimary = true
    ): ?int {
        try {
            // Get the current timetable to extract academic session and term
            $timetable = SchoolTimetable::find($subjectId);
            $currentSession = $timetable ? $timetable->academic_session : null;
            $currentTerm = $timetable ? $timetable->academic_term : null;
            
            if (!$currentSession) {
                // Fallback to get from settings
                $settingRepo = new \App\Repositories\SettingRepo();
                $currentSessionSetting = $settingRepo->getSetting('current_session')->first();
                if ($currentSessionSetting) {
                    $currentSession = $currentSessionSetting->description;
                }
            }
            
            // Find teachers assigned to this subject for this class/section
            $query = TeacherSubjectAssignment::where('subject_id', $subjectId)
                ->where('class_id', $classId)
                ->where('is_active', true);
                
            // Apply section filter if provided (include both section-specific and class-wide assignments)
            if ($sectionId) {
                $query->where(function($q) use ($sectionId) {
                    $q->where('section_id', $sectionId)
                      ->orWhereNull('section_id');
                });
            }
            
            // Filter by current academic year if available
            if ($currentSession) {
                $query->where(function($q) use ($currentSession) {
                    $q->where('academic_year_id', $currentSession)
                      ->orWhereNull('academic_year_id');
                });
            }
            
            // Filter by current academic term if available
            if ($currentTerm) {
                $query->where(function($q) use ($currentTerm) {
                    $q->where('academic_term', $currentTerm)
                      ->orWhereNull('academic_term');
                });
            }
            
            $assignments = $query->get();
            
            if ($assignments->isEmpty()) {
                \Log::warning("No teacher assignments found for subject ID {$subjectId}", [
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                    'academic_session' => $currentSession,
                    'academic_term' => $currentTerm
                ]);
                
                // Try a less restrictive query without term/session filters as fallback
                $backupAssignments = TeacherSubjectAssignment::where('subject_id', $subjectId)
                    ->where('class_id', $classId)
                    ->where('is_active', true)
                    ->when($sectionId, function($q) use ($sectionId) {
                        $q->where(function($subquery) use ($sectionId) {
                            $subquery->where('section_id', $sectionId)
                                ->orWhereNull('section_id');
                        });
                    })
                    ->get();
                    
                if ($backupAssignments->isNotEmpty()) {
                    \Log::info("Found {$backupAssignments->count()} backup teacher assignments without term/session filters");
                    $assignments = $backupAssignments;
                } else {
                    return null;
                }
            }
            
            \Log::debug("Found {$assignments->count()} teacher assignments for subject ID {$subjectId}");
            
            // Prioritize section-specific assignments
            $sectionSpecificAssignments = $assignments->filter(function($assignment) use ($sectionId) {
                return $sectionId && $assignment->section_id === $sectionId;
            });
            
            // Target assignments collection (section specific if available, otherwise all)
            $targetAssignments = $sectionSpecificAssignments->isNotEmpty() ? $sectionSpecificAssignments : $assignments;
            
            // 1. Try to find a primary teacher who is not busy with section-specific assignment
            if ($prioritizePrimary) {
                $primaryTeacher = $targetAssignments->first(function($assignment) use ($busyTeachers) {
                    return $assignment->is_primary && !in_array($assignment->teacher_id, $busyTeachers);
                });
                
                if ($primaryTeacher) {
                    \Log::debug("Found primary teacher ID {$primaryTeacher->teacher_id} for subject ID {$subjectId}");
                    return $primaryTeacher->teacher_id;
                }
            }
            
            // 2. Try any available teacher from target assignments
            $availableTeacher = $targetAssignments->first(function($assignment) use ($busyTeachers) {
                return !in_array($assignment->teacher_id, $busyTeachers);
            });
            
            if ($availableTeacher) {
                \Log::debug("Found available teacher ID {$availableTeacher->teacher_id} for subject ID {$subjectId}");
                return $availableTeacher->teacher_id;
            }
            
            // 3. If all teachers in target assignments are busy, try the full assignments pool
            if ($targetAssignments !== $assignments) {
                if ($prioritizePrimary) {
                    $primaryTeacher = $assignments->first(function($assignment) use ($busyTeachers) {
                        return $assignment->is_primary && !in_array($assignment->teacher_id, $busyTeachers);
                    });
                    
                    if ($primaryTeacher) {
                        \Log::debug("Found primary teacher from full pool ID {$primaryTeacher->teacher_id} for subject ID {$subjectId}");
                        return $primaryTeacher->teacher_id;
                    }
                }
                
                $availableTeacher = $assignments->first(function($assignment) use ($busyTeachers) {
                    return !in_array($assignment->teacher_id, $busyTeachers);
                });
                
                if ($availableTeacher) {
                    \Log::debug("Found available teacher from full pool ID {$availableTeacher->teacher_id} for subject ID {$subjectId}");
                    return $availableTeacher->teacher_id;
                }
            }
            
            // 4. If all teachers are busy, just take the first one (override busy status)
            if ($prioritizePrimary) {
                $primaryTeacher = $targetAssignments->firstWhere('is_primary', true);
                if ($primaryTeacher) {
                    \Log::debug("Using busy primary teacher ID {$primaryTeacher->teacher_id} for subject ID {$subjectId}");
                    return $primaryTeacher->teacher_id;
                }
                
                // Check full pool if needed
                if ($targetAssignments !== $assignments) {
                    $primaryTeacher = $assignments->firstWhere('is_primary', true);
                    if ($primaryTeacher) {
                        \Log::debug("Using busy primary teacher from full pool ID {$primaryTeacher->teacher_id} for subject ID {$subjectId}");
                        return $primaryTeacher->teacher_id;
                    }
                }
            }
            
            // 5. Last resort - take any teacher
            $firstTeacher = $targetAssignments->first() ?: $assignments->first();
            if ($firstTeacher) {
                \Log::debug("Using teacher ID {$firstTeacher->teacher_id} for subject ID {$subjectId} as last resort");
                return $firstTeacher->teacher_id;
            }
            
            return null;
        } catch (\Exception $e) {
            \Log::error("Error finding teacher for subject: " . $e->getMessage(), [
                'subject_id' => $subjectId,
                'class_id' => $classId,
                'section_id' => $sectionId,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Create a timetable entry
     *
     * @param int $timetableId
     * @param int $periodId
     * @param string $day
     * @param int $subjectId
     * @param int $classId
     * @param int|null $sectionId
     * @param string $classroom
     * @param array $config
     * @param array &$stats
     * @return TimetableSchedule|null
     */
    private function createTimetableEntry(
        int $timetableId,
        int $periodId,
        string $day,
        int $subjectId,
        int $classId,
        ?int $sectionId,
        string $classroom,
        array $config,
        array &$stats
    ) {
        try {
            // Find busy teachers for this period
            $busyTeachers = $this->findBusyTeachers($day, $periodId);
            \Log::debug("Found " . count($busyTeachers) . " busy teachers for day=$day, period=$periodId");
            
            // Find an appropriate teacher using our new method
            $teacherId = $this->findTeacherForSubject(
                $subjectId,
                $classId,
                $sectionId,
                $busyTeachers,
                $config['prioritize_primary_teachers'] ?? true
            );
            
            if (!$teacherId) {
                \Log::warning("No teacher found for subject ID $subjectId in timetable ID $timetableId", [
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                    'day' => $day,
                    'period_id' => $periodId
                ]);
                
                // Try to find a fallback teacher if no specific teacher is found
                if ($config['allow_unassigned_teachers'] ?? false) {
                    \Log::info("Creating entry with unassigned teacher (null) as fallback");
                    // Continue with null teacher ID
                } else {
                    $stats['teacher_conflicts']++;
                    return null;
                }
            }
            
            // Get subject name for logging
            $subjectName = Subject::find($subjectId)?->name ?? "Subject #$subjectId";
            $teacherName = $teacherId ? (StaffRecord::find($teacherId)?->name ?? "Teacher #$teacherId") : "Unassigned";
            
            // Create the timetable entry
            $entry = new TimetableSchedule();
            $entry->timetable_id = $timetableId;
            $entry->period_id = $periodId;
            $entry->subject_id = $subjectId;
            $entry->teacher_id = $teacherId;
            $entry->classroom = $classroom;
            $entry->weekday = $day;
            $entry->notes = 'Auto-generated';
            $entry->save();
            
            \Log::info("Created timetable entry: Day=$day, Period=$periodId, Subject=$subjectName, Teacher=$teacherName", [
                'timetable_id' => $timetableId,
                'entry_id' => $entry->id
            ]);
            
            $stats['entries_created']++;
            return $entry;
        } catch (\Exception $e) {
            \Log::error("Failed to create timetable entry: " . $e->getMessage(), [
                'timetable_id' => $timetableId,
                'day' => $day,
                'period_id' => $periodId,
                'subject_id' => $subjectId,
                'class_id' => $classId,
                'section_id' => $sectionId,
                'trace' => $e->getTraceAsString()
            ]);
            $stats['conflicts']++;
            return null;
        }
    }
} 