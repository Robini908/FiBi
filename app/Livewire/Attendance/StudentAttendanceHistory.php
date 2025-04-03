<?php

namespace App\Livewire\Attendance;

use App\Models\AttendanceDetail;
use App\Models\AttendanceRecord;
use App\Models\StudentRecord;
use App\Models\Setting;
use App\Models\MyClass;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;

class StudentAttendanceHistory extends Component
{
    use WithPagination;

    public $studentId;
    public $student;
    public $startDate;
    public $endDate;
    public $termFilter = 'current';
    public $attendanceDetails = [];
    public $stats = [];
    public $attendanceThreshold;
    public $monthlyData = [];
    public $showTrend = true;
    public $showPieChart = true;
    public $statusColors = [
        'present' => '#16a34a', // green-600
        'absent' => '#dc2626', // red-600
        'late' => '#eab308', // yellow-500
        'excused' => '#2563eb', // blue-600
        'sick' => '#9333ea', // purple-600
        'on_leave' => '#4f46e5', // indigo-600
        'other' => '#6b7280', // gray-500
    ];
    public $searchTerm = '';
    public $sortField = 'attendance_date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $classes = [];
    public $selectedClassId = null;
    public $students = [];
    public $page = 1;

    public function mount($studentId = null)
    {
        $this->studentId = $studentId;
        
        // Set default date range to current term
        $this->setDateRangeForTerm('current');
        
        // Get attendance threshold from settings
        $this->attendanceThreshold = config('attendance.threshold', 80);
        
        // Load classes
        $this->loadClasses();
        
        if ($this->studentId) {
            $this->student = StudentRecord::with(['user', 'my_class', 'section'])->findOrFail($studentId);
            // Load attendance data
            $this->loadAttendanceData();
        } else {
            // Initialize empty stats when no student is selected
            $this->stats = [
                'total_days' => 0,
                'present_days' => 0,
                'absent_days' => 0,
                'late_days' => 0,
                'excused_days' => 0,
                'attendance_rate' => 0,
            ];
            
            $this->monthlyData = [];
        }
    }

    public function loadAttendanceData()
    {
        if (!$this->studentId) {
            return;
        }
        
        // Get attendance details for the student within date range
        $details = AttendanceDetail::with(['attendanceRecord'])
            ->whereHas('attendanceRecord', function ($query) {
                $query->whereBetween('attendance_date', [$this->startDate, $this->endDate]);
            })
            ->where('student_id', $this->studentId)
            ->get();
            
        // Calculate statistics
        $totalDays = $details->count();
        $presentDays = $details->where('status', 'present')->count();
        $absentDays = $details->where('status', 'absent')->count();
        $lateDays = $details->where('status', 'late')->count();
        $excusedDays = $details->whereIn('status', ['excused', 'sick', 'on_leave'])->count();
        
        $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
        
        $this->attendanceDetails = $details;
        $this->stats = [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'excused_days' => $excusedDays,
            'attendance_rate' => $attendanceRate,
        ];
        
        // Calculate monthly attendance data for trend chart
        $this->calculateMonthlyData();
    }

    public function calculateMonthlyData()
    {
        // Group attendance details by month
        $monthlyData = [];
        $start = Carbon::parse($this->startDate)->startOfMonth();
        $end = Carbon::parse($this->endDate)->endOfMonth();
        $period = Carbon::parse($start)->monthsUntil($end);
        
        // Initialize data for all months in range
        foreach ($period as $month) {
            $monthKey = $month->format('Y-m');
            $monthlyData[$monthKey] = [
                'month' => $month->format('M Y'),
                'total' => 0,
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
                'rate' => 0,
            ];
        }
        
        // Populate with actual data
        foreach ($this->attendanceDetails as $detail) {
            $date = Carbon::parse($detail->attendanceRecord->attendance_date);
            $monthKey = $date->format('Y-m');
            
            if (isset($monthlyData[$monthKey])) {
                $monthlyData[$monthKey]['total']++;
                
                switch ($detail->status) {
                    case 'present':
                        $monthlyData[$monthKey]['present']++;
                        break;
                    case 'absent':
                        $monthlyData[$monthKey]['absent']++;
                        break;
                    case 'late':
                        $monthlyData[$monthKey]['late']++;
                        break;
                    case 'excused':
                    case 'sick':
                    case 'on_leave':
                        $monthlyData[$monthKey]['excused']++;
                        break;
                }
                
                // Calculate rate
                $monthlyData[$monthKey]['rate'] = round(
                    ($monthlyData[$monthKey]['present'] / $monthlyData[$monthKey]['total']) * 100,
                    1
                );
            }
        }
        
        $this->monthlyData = array_values($monthlyData);
    }

    public function setTermFilter($term)
    {
        $this->termFilter = $term;
        $this->setDateRangeForTerm($term);
        $this->loadAttendanceData();
    }

    protected function setDateRangeForTerm($term)
    {
        $currentYear = Setting::where('key', 'current_session')->first()->value ?? date('Y');
        $currentTerm = (int)(Setting::where('key', 'current_term')->first()->value ?? 1);
        
        // Ensure term is between 1 and 3
        $currentTerm = max(1, min(3, $currentTerm));
        
        switch ($term) {
            case 'current':
                // Calculate the start month for the current term (term 1: month 1, term 2: month 4, term 3: month 7)
                $startMonth = (($currentTerm - 1) * 3) + 1;
                $this->startDate = Carbon::createFromDate($currentYear, $startMonth, 1)->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                break;
            case 'term1':
                $this->startDate = Carbon::createFromDate($currentYear, 1, 1)->format('Y-m-d');
                $this->endDate = Carbon::createFromDate($currentYear, 3, 31)->format('Y-m-d');
                break;
            case 'term2':
                $this->startDate = Carbon::createFromDate($currentYear, 4, 1)->format('Y-m-d');
                $this->endDate = Carbon::createFromDate($currentYear, 6, 30)->format('Y-m-d');
                break;
            case 'term3':
                $this->startDate = Carbon::createFromDate($currentYear, 7, 1)->format('Y-m-d');
                $this->endDate = Carbon::createFromDate($currentYear, 9, 30)->format('Y-m-d');
                break;
            case 'academic_year':
                $this->startDate = Carbon::createFromDate($currentYear, 1, 1)->format('Y-m-d');
                $this->endDate = Carbon::createFromDate($currentYear, 12, 31)->format('Y-m-d');
                break;
            case 'custom':
                // Keep existing date range
                break;
        }
    }

    public function updatedStartDate()
    {
        $this->termFilter = 'custom';
        $this->validateDateRange();
        $this->loadAttendanceData();
    }

    public function updatedEndDate()
    {
        $this->termFilter = 'custom';
        $this->validateDateRange();
        $this->loadAttendanceData();
    }

    protected function validateDateRange()
    {
        if (Carbon::parse($this->endDate)->lt(Carbon::parse($this->startDate))) {
            $this->endDate = $this->startDate;
        }
    }

    public function toggleTrend()
    {
        $this->showTrend = !$this->showTrend;
    }

    public function togglePieChart()
    {
        $this->showPieChart = !$this->showPieChart;
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getAttendancePieChartModel()
    {
        $pieChartModel = (new PieChartModel())
            ->setTitle('Attendance Summary')
            ->withoutLegend();
            
        if ($this->stats['total_days'] > 0) {
            $pieChartModel->addSlice('Present', $this->stats['present_days'], $this->statusColors['present'])
                ->addSlice('Absent', $this->stats['absent_days'], $this->statusColors['absent'])
                ->addSlice('Late', $this->stats['late_days'], $this->statusColors['late'])
                ->addSlice('Excused', $this->stats['excused_days'], $this->statusColors['excused']);
        }
        
        return $pieChartModel;
    }

    public function getAttendanceTrendModel()
    {
        $lineChartModel = (new LineChartModel())
            ->setTitle('Monthly Attendance Rate')
            ->setAnimated(true)
            ->withGrid()
            ->setSmoothCurve()
            ->setXAxisVisible(true)
            ->setYAxisVisible(true);
            
        if (count($this->monthlyData) > 0) {
            // Add attendance rate points
            foreach ($this->monthlyData as $index => $data) {
                if ($data['total'] > 0) {
                    $color = $data['rate'] >= $this->attendanceThreshold
                        ? $this->statusColors['present']
                        : $this->statusColors['absent'];
                        
                    $lineChartModel->addPoint(
                        $data['month'],
                        $data['rate'],
                        $color
                    );
                }
            }
            
            // Add the threshold as a series of points at the same value
            // We can't add a horizontal line directly, so we'll add a note about the threshold
            // in the title
            $lineChartModel->setTitle('Monthly Attendance Rate (Threshold: ' . $this->attendanceThreshold . '%)');
        }
        
        return $lineChartModel;
    }

    public function exportAttendance()
    {
        return redirect()->route('attendance.student.export', [
            'student_id' => $this->studentId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }

    public function loadClasses()
    {
        // Load all classes
        $this->classes = MyClass::orderBy('name')->get();
    }

    public function updatedSelectedClassId()
    {
        if ($this->selectedClassId) {
            // Reset studentId when class changes
            $this->studentId = null;
            $this->student = null;
            
            // Load students for the selected class
            $this->students = StudentRecord::where('my_class_id', $this->selectedClassId)
                ->orderBy('first_name')->orderBy('last_name')
                ->get();
                
            $this->dispatch('notify', [
                'type' => 'info',
                'message' => 'Students loaded. Please select a student to view attendance history.'
            ]);
        } else {
            $this->students = [];
        }
    }
    
    public function updatedStudentId()
    {
        if ($this->studentId) {
            $this->selectStudent($this->studentId);
        } else {
            $this->student = null;
            $this->attendanceDetails = [];
            $this->stats = [
                'total_days' => 0,
                'present_days' => 0,
                'absent_days' => 0,
                'late_days' => 0,
                'excused_days' => 0,
                'attendance_rate' => 0,
            ];
            $this->monthlyData = [];
        }
    }

    public function selectStudent($studentId)
    {
        $this->studentId = $studentId;
        $this->student = StudentRecord::with(['user', 'my_class', 'section'])->findOrFail($studentId);
        $this->loadAttendanceData();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Attendance data loaded for ' . $this->student->name
        ]);
    }

    public function getPaginatedAttendanceDetails()
    {
        if (empty($this->attendanceDetails)) {
            return [
                'items' => collect(),
                'total' => 0,
            ];
        }
        
        $query = collect($this->attendanceDetails)->sortBy(function($item) {
            $value = data_get($item, $this->sortField);
            return $this->sortDirection === 'asc' ? $value : -$value;
        });
        
        if ($this->searchTerm) {
            $query = $query->filter(function ($item) {
                return stripos($item->status, $this->searchTerm) !== false
                    || stripos($item->remarks, $this->searchTerm) !== false
                    || stripos($item->attendanceRecord->attendance_date, $this->searchTerm) !== false;
            });
        }
        
        // Manual pagination
        $page = $this->page ?? 1;
        $total = $query->count();
        $items = $query->slice(($page - 1) * $this->perPage, $this->perPage)->values();
        
        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    public function nextPage()
    {
        $this->page = ($this->page ?? 1) + 1;
    }
    
    public function previousPage()
    {
        $this->page = max(1, ($this->page ?? 1) - 1);
    }

    public function render()
    {
        $paginatedDetails = $this->getPaginatedAttendanceDetails();
        
        return view('livewire.attendance.student-attendance-history', [
            'paginatedDetails' => $paginatedDetails['items'],
            'totalRecords' => $paginatedDetails['total'],
            'classes' => $this->classes,
            'students' => $this->students,
        ]);
    }
} 