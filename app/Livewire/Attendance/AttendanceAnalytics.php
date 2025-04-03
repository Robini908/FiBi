<?php

namespace App\Livewire\Attendance;

use App\Models\AttendanceRecord;
use App\Models\AttendanceDetail;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\Setting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;

class AttendanceAnalytics extends Component
{
    public $classId;
    public $sectionId;
    public $startDate;
    public $endDate;
    public $activeTab = 'overview';
    public $periodType = 'weekly';
    public $trendType = 'present';
    public $selectedClass = null;
    public $selectedSection = null;
    public $classes = [];
    public $sections = [];
    public $timelineData = [];
    public $comparisonData = [];
    public $studentAttendanceData = [];
    public $topStudents = [];
    public $attendanceThreshold;
    public $statusColors = [
        'present' => '#16a34a', // green-600
        'absent' => '#dc2626', // red-600
        'late' => '#eab308', // yellow-500
        'excused' => '#2563eb', // blue-600
        'sick' => '#9333ea', // purple-600
        'on_leave' => '#4f46e5', // indigo-600
        'other' => '#6b7280', // gray-500
    ];
    public $statusLabels = [
        'present' => 'Present',
        'absent' => 'Absent',
        'late' => 'Late',
        'excused' => 'Excused',
        'sick' => 'Sick',
        'on_leave' => 'On Leave',
        'other' => 'Other',
    ];
    public $overviewData = [];
    public $isLoading = false;
    public $loadingSection = null;
    public $lastUpdated = null;
    public $pollingEnabled = true;
    public $pollingInterval = 30000; // 30 seconds by default
    public $automaticRefresh = true;

    public function mount()
    {
        // Set default values
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->attendanceThreshold = config('attendance.threshold', 80);
        $this->lastUpdated = now()->format('Y-m-d H:i:s');
        
        // Load all classes
        $this->classes = MyClass::orderBy('name')->get();
        
        // Initialize empty sections
        $this->sections = collect([]);
        
        if (count($this->classes) > 0) {
            $this->selectedClass = $this->classes[0];
            $this->classId = $this->selectedClass->id;
            $this->loadSections();
        }
    }

    public function loadSections()
    {
        if ($this->classId) {
            $this->sections = Section::where('my_class_id', $this->classId)->get();
            
            if (count($this->sections) > 0) {
                $this->selectedSection = $this->sections[0];
                $this->sectionId = $this->selectedSection->id;
            } else {
                $this->selectedSection = null;
                $this->sectionId = null;
            }
        }
    }

    public function updatedClassId()
    {
        $this->loadSections();
        $this->sectionId = null;
        $this->notify('info', 'Class selection updated, loading data...');
        $this->loadData();
    }

    public function updatedSectionId()
    {
        $this->notify('info', 'Section selection updated, loading data...');
        $this->loadData();
    }

    public function updatedStartDate()
    {
        $this->validateDateRange();
        $this->loadData();
    }

    public function updatedEndDate()
    {
        $this->validateDateRange();
        $this->loadData();
    }

    public function updatedPeriodType()
    {
        $this->loadData();
    }

    public function updatedTrendType()
    {
        $this->loadData();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->loadingSection = $tab;
        $this->loadData();
    }

    protected function validateDateRange()
    {
        if (Carbon::parse($this->endDate)->lt(Carbon::parse($this->startDate))) {
            $this->endDate = $this->startDate;
        }
    }

    public function setDateRange($range)
    {
        $this->isLoading = true;
        $this->loadingSection = 'dateRange';
        
        $today = Carbon::today();
        
        switch ($range) {
            case 'last_7_days':
                $this->startDate = $today->copy()->subDays(6)->format('Y-m-d');
                $this->endDate = $today->format('Y-m-d');
                break;
            case 'last_30_days':
                $this->startDate = $today->copy()->subDays(29)->format('Y-m-d');
                $this->endDate = $today->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = $today->copy()->startOfMonth()->format('Y-m-d');
                $this->endDate = $today->format('Y-m-d');
                break;
            case 'last_month':
                $lastMonth = $today->copy()->subMonth();
                $this->startDate = $lastMonth->copy()->startOfMonth()->format('Y-m-d');
                $this->endDate = $lastMonth->copy()->endOfMonth()->format('Y-m-d');
                break;
            case 'this_term':
                // Logic for current term dates if available
                $this->startDate = $today->copy()->subMonths(3)->format('Y-m-d');
                $this->endDate = $today->format('Y-m-d');
                break;
            case 'academic_year':
                // Logic for academic year
                $this->startDate = $today->copy()->subMonths(9)->format('Y-m-d');
                $this->endDate = $today->format('Y-m-d');
                break;
        }
        
        $this->loadData();
        $this->loadingSection = null;
        $this->isLoading = false;
    }

    public function loadData()
    {
        $this->isLoading = true;
        
        try {
            $this->loadOverviewData();
            $this->loadTrendData();
            $this->loadStudentData();
            $this->loadComparisonData();
            
            $this->dispatch('data-refreshed', [
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            $this->notify('error', 'Error loading data: ' . $e->getMessage());
            
            logger()->error('Error in AttendanceAnalytics::loadData', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        $this->isLoading = false;
    }

    protected function loadOverviewData()
    {
        // Get overall statistics
        $stats = AttendanceRecord::getAttendanceStats(
            $this->classId,
            $this->sectionId,
            $this->startDate,
            $this->endDate
        );
        
        // Get student count
        $studentCount = StudentRecord::where('my_class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->count();
            
        // Get attendance by day of week
        $dayOfWeekData = AttendanceDetail::join('attendance_records', 'attendance_details.attendance_record_id', '=', 'attendance_records.id')
            ->where('attendance_records.class_id', $this->classId)
            ->where('attendance_records.section_id', $this->sectionId)
            ->whereBetween('attendance_records.attendance_date', [$this->startDate, $this->endDate])
            ->select(
                DB::raw('DAYOFWEEK(attendance_records.attendance_date) as day_of_week'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN attendance_details.status = "present" THEN 1 ELSE 0 END) as present_count')
            )
            ->groupBy('day_of_week')
            ->get()
            ->keyBy('day_of_week')
            ->toArray();
            
        // Format day of week data for chart
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $dayOfWeekStats = [];
        
        foreach ($daysOfWeek as $index => $day) {
            $dayNum = $index + 2; // DAYOFWEEK() returns 1 for Sunday, 2 for Monday, etc.
            if ($dayNum > 7) $dayNum = 1; // Adjust for Sunday
            
            $dayData = $dayOfWeekData[$dayNum] ?? ['total' => 0, 'present_count' => 0];
            $rate = $dayData['total'] > 0 ? round(($dayData['present_count'] / $dayData['total']) * 100, 1) : 0;
            
            $dayOfWeekStats[$day] = [
                'total' => $dayData['total'],
                'present' => $dayData['present_count'],
                'rate' => $rate,
            ];
        }
        
        // Get top 5 students with best attendance
        $this->topStudents = $this->getTopStudentsByAttendance(5);
        
        $this->overviewData = [
            'stats' => $stats,
            'student_count' => $studentCount,
            'day_of_week_stats' => $dayOfWeekStats,
        ];
    }

    protected function loadTrendData()
    {
        $dateFormat = '%Y-%m-%d';
        $groupBy = 'day';
        
        if ($this->periodType === 'monthly') {
            $dateFormat = '%Y-%m';
            $groupBy = 'month';
        } elseif ($this->periodType === 'weekly') {
            $dateFormat = '%x-W%v'; // ISO year and week number
            $groupBy = 'week';
        }
        
        // Get attendance trend data
        $trendData = AttendanceDetail::join('attendance_records', 'attendance_details.attendance_record_id', '=', 'attendance_records.id')
            ->where('attendance_records.class_id', $this->classId)
            ->where('attendance_records.section_id', $this->sectionId)
            ->whereBetween('attendance_records.attendance_date', [$this->startDate, $this->endDate])
            ->select(
                DB::raw("DATE_FORMAT(attendance_records.attendance_date, '$dateFormat') as period"),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN attendance_details.status = "present" THEN 1 ELSE 0 END) as present_count'),
                DB::raw('SUM(CASE WHEN attendance_details.status = "absent" THEN 1 ELSE 0 END) as absent_count'),
                DB::raw('SUM(CASE WHEN attendance_details.status = "late" THEN 1 ELSE 0 END) as late_count'),
                DB::raw('SUM(CASE WHEN attendance_details.status IN ("excused", "sick", "on_leave") THEN 1 ELSE 0 END) as excused_count')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->toArray();
            
        // For weekly/monthly views, ensure we have data points for all periods
        if ($groupBy === 'week' || $groupBy === 'month') {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);
            $periods = [];
            
            if ($groupBy === 'week') {
                $current = $start->copy()->startOfWeek();
                while ($current->lte($end)) {
                    $weekKey = $current->format('Y-\WW');
                    $periods[$weekKey] = [
                        'period' => $weekKey,
                        'total' => 0,
                        'present_count' => 0,
                        'absent_count' => 0,
                        'late_count' => 0,
                        'excused_count' => 0,
                    ];
                    $current->addWeek();
                }
            } else { // month
                $current = $start->copy()->startOfMonth();
                while ($current->lte($end)) {
                    $monthKey = $current->format('Y-m');
                    $periods[$monthKey] = [
                        'period' => $monthKey,
                        'total' => 0,
                        'present_count' => 0,
                        'absent_count' => 0,
                        'late_count' => 0,
                        'excused_count' => 0,
                    ];
                    $current->addMonth();
                }
            }
            
            // Merge actual data with periods
            foreach ($trendData as $data) {
                if (isset($periods[$data['period']])) {
                    $periods[$data['period']] = $data;
                }
            }
            
            $trendData = array_values($periods);
        }
        
        $this->timelineData = $trendData;
    }

    protected function loadStudentData()
    {
        // Get all students in the class/section
        $students = StudentRecord::where('my_class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->orderBy('first_name')->orderBy('last_name')
            ->get();
            
        // Calculate statistics for each student
        $studentData = [];
        
        foreach ($students as $student) {
            $stats = AttendanceDetail::getStudentAttendanceStats(
                $student->id,
                $this->startDate,
                $this->endDate
            );
            
            $studentData[] = [
                'id' => $student->id,
                'name' => $student->name,
                'adm_no' => $student->adm_no ?? '',
                'photo' => $student->user->photo ?? null,
                'stats' => $stats,
            ];
        }
        
        // Sort students by attendance rate
        usort($studentData, function($a, $b) {
            return $b['stats']['attendance_rate'] <=> $a['stats']['attendance_rate'];
        });
        
        $this->studentAttendanceData = $studentData;
    }

    protected function loadComparisonData()
    {
        // Get all sections in the selected class
        $sections = Section::where('my_class_id', $this->classId)->get();
        
        $comparisonData = [];
        
        foreach ($sections as $section) {
            $stats = AttendanceRecord::getAttendanceStats(
                $this->classId,
                $section->id,
                $this->startDate,
                $this->endDate
            );
            
            $comparisonData[] = [
                'section_id' => $section->id,
                'section_name' => $section->name,
                'stats' => $stats,
            ];
        }
        
        // Sort sections by attendance rate
        usort($comparisonData, function($a, $b) {
            return $b['stats']['attendance_rate'] <=> $a['stats']['attendance_rate'];
        });
        
        $this->comparisonData = $comparisonData;
    }

    protected function getTopStudentsByAttendance($limit = 5)
    {
        // Get all students in the class/section
        $students = StudentRecord::where('my_class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->get();
            
        $studentStats = [];
        
        foreach ($students as $student) {
            $stats = AttendanceDetail::getStudentAttendanceStats(
                $student->id,
                $this->startDate,
                $this->endDate
            );
            
            // Only include students with at least one attendance record
            if ($stats['total_days'] > 0) {
                $studentStats[] = [
                    'id' => $student->id,
                    'name' => $student->name,
                    'adm_no' => $student->adm_no ?? '',
                    'photo' => $student->user->photo ?? null,
                    'attendance_rate' => $stats['attendance_rate'],
                    'present_days' => $stats['present_days'],
                    'total_days' => $stats['total_days'],
                ];
            }
        }
        
        // Sort by attendance rate and take top N
        usort($studentStats, function($a, $b) {
            return $b['attendance_rate'] <=> $a['attendance_rate'];
        });
        
        return array_slice($studentStats, 0, $limit);
    }

    public function getOverviewChartModel()
    {
        $pieChartModel = (new PieChartModel())
            ->setTitle('Attendance Overview')
            ->withoutLegend();
            
        if (isset($this->overviewData['stats'])) {
            $stats = $this->overviewData['stats'];
            
            if ($stats['total_days'] > 0) {
                $pieChartModel->addSlice('Present', $stats['present_count'], $this->statusColors['present'])
                    ->addSlice('Absent', $stats['absent_count'], $this->statusColors['absent'])
                    ->addSlice('Late', $stats['late_count'], $this->statusColors['late'])
                    ->addSlice('Excused', $stats['excused_count'], $this->statusColors['excused']);
            }
        }
        
        return $pieChartModel;
    }

    public function getDayOfWeekChartModel()
    {
        $columnChartModel = (new ColumnChartModel())
            ->setTitle('Attendance by Day of Week')
            ->withoutLegend()
            ->setAnimated(true)
            ->withGrid();
            
        if (isset($this->overviewData['day_of_week_stats'])) {
            $dayStats = $this->overviewData['day_of_week_stats'];
            
            foreach ($dayStats as $day => $stats) {
                $columnChartModel->addColumn($day, $stats['rate'], $this->statusColors['present']);
            }
        }
        
        return $columnChartModel;
    }

    public function getTrendChartModel()
    {
        $columnChartModel = (new ColumnChartModel())
            ->setTitle('Attendance Trend')
            ->setAnimated(true)
            ->withGrid();
            
        // For better visualization, we'll do different things based on the number of data points
        if (count($this->timelineData) > 0) {
            // For a single trend type (non-all), we can show a simple column chart by period
            if ($this->trendType !== 'all') {
                $statusType = $this->trendType;
                $statusColor = $this->statusColors[$statusType];
                
            foreach ($this->timelineData as $data) {
                $total = $data['total'] > 0 ? $data['total'] : 1; // Avoid division by zero
                
                    // Determine which count to use based on the selected trend type
                    $count = 0;
                    $label = '';
                    
                    switch ($statusType) {
                        case 'present':
                            $count = $data['present_count'];
                            $label = 'Present';
                            break;
                        case 'absent':
                            $count = $data['absent_count'];
                            $label = 'Absent';
                            break;
                        case 'late':
                            $count = $data['late_count'];
                            $label = 'Late';
                            break;
                        case 'excused':
                            $count = $data['excused_count'];
                            $label = 'Excused';
                            break;
                    }
                    
                    $rate = round(($count / $total) * 100, 1);
                    
                    // Use a shorter format for the period display if we have many periods
                    $periodDisplay = $data['period'];
                    if (count($this->timelineData) > 5 && $this->periodType === 'daily') {
                        // For daily format, show just the day
                        $periodDisplay = substr($data['period'], -2);
                    } elseif ($this->periodType === 'weekly') {
                        // For weekly format, show week number
                        $periodDisplay = 'W' . substr($data['period'], -2);
                    }
                    
                    $columnChartModel->addColumn(
                        $periodDisplay, 
                        $rate, 
                        $statusColor
                    );
                }
            } else {
                // For 'all' trend type, we'll show a multi-column chart
                // We need to group the data differently to avoid too many columns
                
                // Group data by status first, then by period to make chart more readable
                $statusTypes = ['present', 'absent', 'late', 'excused'];
                $colors = [
                    'present' => $this->statusColors['present'],
                    'absent' => $this->statusColors['absent'],
                    'late' => $this->statusColors['late'],
                    'excused' => $this->statusColors['excused']
                ];
                
                // Only use a subset of periods if there are too many
                $maxPeriodsToShow = 6;
                $periodsToUse = $this->timelineData;
                
                if (count($periodsToUse) > $maxPeriodsToShow) {
                    // Take the most recent periods
                    $periodsToUse = array_slice($periodsToUse, -$maxPeriodsToShow);
                }
                
                foreach ($periodsToUse as $data) {
                    $total = $data['total'] > 0 ? $data['total'] : 1; // Avoid division by zero
                    
                    // Use a shorter format for the period display
                    $periodDisplay = $data['period'];
                    if ($this->periodType === 'daily') {
                        // For daily format, show just the day
                        $periodDisplay = substr($data['period'], -2);
                    } elseif ($this->periodType === 'weekly') {
                        // For weekly format, show week number
                        $periodDisplay = 'W' . substr($data['period'], -2);
                    }
                    
                    // Add each status type as a separate group
                    foreach ($statusTypes as $type) {
                        $countKey = $type . '_count';
                        
                        // Handle 'excused' specially since it's a grouped status
                        if ($type === 'excused' && !isset($data[$countKey])) {
                            $countKey = 'excused_count';
                        }
                        
                        if (isset($data[$countKey])) {
                            $rate = round(($data[$countKey] / $total) * 100, 1);
                            
                    $columnChartModel->addColumn(
                                $periodDisplay . ' (' . ucfirst($type) . ')', 
                                $rate,
                                $colors[$type]
                            );
                        }
                    }
                }
            }
        }
        
        return $columnChartModel;
    }

    public function getSectionComparisonChartModel()
    {
        $columnChartModel = (new ColumnChartModel())
            ->setTitle('Section Comparison')
            ->withoutLegend()
            ->setAnimated(true)
            ->withGrid();
            
        if (count($this->comparisonData) > 0) {
            foreach ($this->comparisonData as $data) {
                $columnChartModel->addColumn(
                    $data['section_name'],
                    $data['stats']['attendance_rate'],
                    $data['section_id'] == $this->sectionId
                        ? '#16a34a' // Highlight current section
                        : '#86efac' // Lighter green for other sections
                );
            }
        }
        
        return $columnChartModel;
    }

    public function getStudentRankingChartModel()
    {
        $columnChartModel = (new ColumnChartModel())
            ->setTitle('Top Students by Attendance')
            ->withoutLegend()
            ->setAnimated(true)
            ->withGrid();
            
        // Get top 10 students
        $topStudents = array_slice($this->studentAttendanceData, 0, 10);
        
        foreach ($topStudents as $student) {
            $color = $student['stats']['attendance_rate'] >= $this->attendanceThreshold
                ? '#16a34a' // Green for above threshold
                : '#dc2626'; // Red for below threshold
                
            $columnChartModel->addColumn(
                $student['name'],
                $student['stats']['attendance_rate'],
                $color
            );
        }
        
        return $columnChartModel;
    }

    public function getListeners()
    {
        return [
            'refreshData' => 'refreshData',
            'echo:attendance,AttendanceUpdated' => 'handleAttendanceUpdated',
        ];
    }

    public function handleAttendanceUpdated()
    {
        // When attendance records are updated elsewhere, refresh the data
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->isLoading = true;
        
        $this->loadData();
        
        $this->dispatch('data-refreshed', [
            'timestamp' => now()->toDateTimeString()
        ]);
        
        $this->notify('success', 'Data refreshed successfully');
        
        $this->isLoading = false;
    }

    public function togglePolling()
    {
        $this->pollingEnabled = !$this->pollingEnabled;
        $this->dispatch('polling-toggled', [
            'enabled' => $this->pollingEnabled
        ]);
    }

    public function setPollingInterval($milliseconds)
    {
        $this->pollingInterval = $milliseconds;
        $this->dispatch('polling-interval-changed', [
            'interval' => $this->pollingInterval
        ]);
    }

    public function exportReport()
    {
        $this->isLoading = true;
        $this->loadingSection = 'export';
        
        // Here you would implement the export functionality
        // For now we'll just simulate a delay
        usleep(500000); // 0.5 second delay
        
        $this->loadingSection = null;
        $this->isLoading = false;
        
        // Show success message
        $this->notify('success', 'Report exported successfully!');
    }

    public function render()
    {
        return view('livewire.attendance.attendance-analytics');
    }

    protected function notify($type, $message)
    {
        // Dispatch to Livewire event listener
        $this->dispatch('notify', [
            'type' => $type,
            'message' => $message
        ]);
        
        // Also dispatch to Alpine.js via browser event
        // Using the 'to' method with 'null' makes it a browser event
        $this->dispatch('notify', [
            'type' => $type,
            'message' => $message
        ])->to(null);
    }
} 