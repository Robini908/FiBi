<?php

namespace App\Livewire\Attendance;

use App\Models\AttendanceRecord;
use App\Models\AttendanceDetail;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AttendanceReport extends Component
{
    use WithPagination;

    public $classId;
    public $sectionId;
    public $class;
    public $section;
    public $startDate;
    public $endDate;
    public $students = [];
    public $attendanceRecords = [];
    public $studentStats = [];
    public $overallStats = [];
    public $attendanceDates = [];
    public $reportGenerated = false;
    public $statusColors = [
        'present' => 'bg-green-100 text-green-800',
        'absent' => 'bg-red-100 text-red-800',
        'late' => 'bg-yellow-100 text-yellow-800',
        'excused' => 'bg-blue-100 text-blue-800',
        'sick' => 'bg-purple-100 text-purple-800',
        'on_leave' => 'bg-indigo-100 text-indigo-800',
        'other' => 'bg-gray-100 text-gray-800',
    ];
    public $searchTerm = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $showCharts = true;
    public $attendanceThreshold;
    public $exportingPdf = false;

    public function mount($classId, $sectionId, $startDate = null, $endDate = null)
    {
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        $this->class = MyClass::find($classId);
        $this->section = Section::find($sectionId);
        
        if (!$this->class || !$this->section) {
            return redirect()->route('attendance.index')
                ->with('error', 'Class or section not found');
        }
        
        // Default to current month if dates not provided
        $this->startDate = $startDate ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = $endDate ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        
        // Get attendance threshold from settings
        $this->attendanceThreshold = config('attendance.threshold', 80);
        
        // Load students for this class/section
        $this->loadStudents();
        
        // Generate report if dates are provided
        if ($startDate && $endDate) {
            $this->generateReport();
        }
    }

    public function loadStudents()
    {
        $this->students = StudentRecord::where('my_class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->orderBy('first_name')->orderBy('last_name')
            ->get()
            ->toArray();
    }

    public function generateReport()
    {
        $this->validateDateRange();
        
        // Get all students in the class/section
        $students = StudentRecord::where('my_class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();
            
        // Get all attendance records in the date range
        $records = AttendanceRecord::where('class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->whereBetween('attendance_date', [$this->startDate, $this->endDate])
            ->orderBy('attendance_date')
            ->with('attendanceDetails')
            ->get();
            
        // Extract unique dates from attendance records
        $this->attendanceDates = $records->pluck('attendance_date')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->unique()->values()->toArray();
            
        // Calculate statistics for each student
        $studentStats = [];
        
        foreach ($students as $student) {
            $stats = AttendanceDetail::getStudentAttendanceStats(
                $student->id,
                $this->startDate,
                $this->endDate
            );
            
            $studentStats[$student->id] = $stats;
        }
        
        // Calculate overall statistics
        $overallStats = AttendanceRecord::getAttendanceStats(
            $this->classId,
            $this->sectionId,
            $this->startDate,
            $this->endDate
        );
        
        $this->students = $students;
        $this->attendanceRecords = $records;
        $this->studentStats = $studentStats;
        $this->overallStats = $overallStats;
        $this->reportGenerated = true;
    }

    protected function validateDateRange()
    {
        if (Carbon::parse($this->endDate)->lt(Carbon::parse($this->startDate))) {
            $this->endDate = $this->startDate;
        }
    }

    public function getStudentAttendanceStatus($studentId, $date)
    {
        foreach ($this->attendanceRecords as $record) {
            if (Carbon::parse($record->attendance_date)->format('Y-m-d') === $date) {
                $detail = $record->attendanceDetails->firstWhere('student_id', $studentId);
                if ($detail) {
                    return $detail->status;
                }
            }
        }
        
        return null;
    }

    public function setDateRange($range)
    {
        $today = Carbon::today();
        
        switch ($range) {
            case 'today':
                $this->startDate = $today->format('Y-m-d');
                $this->endDate = $today->format('Y-m-d');
                break;
            case 'this_week':
                $this->startDate = $today->startOfWeek()->format('Y-m-d');
                $this->endDate = $today->endOfWeek()->format('Y-m-d');
                break;
            case 'last_week':
                $this->startDate = $today->subWeek()->startOfWeek()->format('Y-m-d');
                $this->endDate = $today->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = $today->startOfMonth()->format('Y-m-d');
                $this->endDate = $today->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->startDate = $today->subMonth()->startOfMonth()->format('Y-m-d');
                $this->endDate = $today->endOfMonth()->format('Y-m-d');
                break;
            case 'this_term':
                // Logic for current term dates if available
                $this->startDate = $today->subMonths(3)->format('Y-m-d');
                $this->endDate = $today->format('Y-m-d');
                break;
        }
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        
        // Regenerate report with new sorting
        $this->generateReport();
    }

    public function toggleCharts()
    {
        $this->showCharts = !$this->showCharts;
    }

    public function exportPdf()
    {
        $this->exportingPdf = true;
        
        return redirect()->route('attendance.export.pdf', [
            'class_id' => $this->classId,
            'section_id' => $this->sectionId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }

    public function exportExcel()
    {
        return redirect()->route('attendance.export.excel', [
            'class_id' => $this->classId,
            'section_id' => $this->sectionId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }

    public function render()
    {
        return view('livewire.attendance.attendance-report');
    }
} 