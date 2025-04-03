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

class ViewAttendance extends Component
{
    use WithPagination;

    public $classId;
    public $sectionId;
    public $class;
    public $section;
    public $startDate;
    public $endDate;
    public $attendanceData = [];
    public $showDeleteModal = false;
    public $recordToDelete = null;
    public $selectedRecord = null;
    public $detailsModalOpen = false;
    public $detailsData = [];
    public $searchTerm = '';
    public $isAllPresent = false;
    public $attendanceStats = [
        'total_days' => 0,
        'present_count' => 0,
        'absent_count' => 0, 
        'late_count' => 0,
        'excused_count' => 0,
        'attendance_rate' => 0,
    ];
    public $recordsPerPage = 10;
    public $sortField = 'attendance_date';
    public $sortDirection = 'desc';
    public $filters = [
        'status' => '',
        'date_range' => '',
    ];
    public $statusColors = [
        'present' => 'bg-green-100 text-green-800 border-green-200',
        'absent' => 'bg-red-100 text-red-800 border-red-200',
        'late' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'excused' => 'bg-blue-100 text-blue-800 border-blue-200',
        'sick' => 'bg-purple-100 text-purple-800 border-purple-200',
        'on_leave' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
        'other' => 'bg-gray-100 text-gray-800 border-gray-200',
    ];
    public $classes = [];
    public $sections = [];

    protected $listeners = [
        'refreshAttendanceData' => 'loadAttendanceData',
        'confirmDelete',
    ];

    public function mount($classId = null, $sectionId = null)
    {
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        
        // Default to current month
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        
        // Load all classes if no class is selected
        if (!$this->classId) {
            $this->loadClasses();
            return;
        }
        
        $this->class = MyClass::find($classId);
        
        if (!$this->class) {
            return redirect()->route('attendance.index')
                ->with('error', 'Class not found');
        }
        
        $this->loadSections();
        
        if ($this->sectionId) {
            $this->section = Section::find($sectionId);
            
            if (!$this->section) {
                return redirect()->route('attendance.index')
                    ->with('error', 'Section not found');
            }
            
            $this->loadAttendanceData();
        }
    }

    public function loadAttendanceData()
    {
        // Return early if class or section is not selected
        if (!$this->classId || !$this->sectionId) {
            $this->attendanceData = [];
            $this->attendanceStats = [
                'total_days' => 0,
                'present_count' => 0,
                'absent_count' => 0, 
                'late_count' => 0,
                'excused_count' => 0,
                'attendance_rate' => 0,
            ];
            return;
        }
        
        // Get all attendance records for the class and section within the date range
        $query = AttendanceRecord::where('class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->whereBetween('attendance_date', [$this->startDate, $this->endDate])
            ->with(['attendanceDetails', 'markedBy']);
            
        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);
        
        $records = $query->get();
        
        // Calculate attendance statistics
        $this->attendanceStats = AttendanceRecord::getAttendanceStats(
            $this->classId,
            $this->sectionId,
            $this->startDate,
            $this->endDate
        );
        
        $this->attendanceData = $records;
    }

    public function updatedStartDate()
    {
        $this->validateDateRange();
        $this->loadAttendanceData();
    }

    public function updatedEndDate()
    {
        $this->validateDateRange();
        $this->loadAttendanceData();
    }

    protected function validateDateRange()
    {
        if (Carbon::parse($this->endDate)->lt(Carbon::parse($this->startDate))) {
            $this->endDate = $this->startDate;
        }
    }

    public function setDateRange($range)
    {
        $today = Carbon::today();
        
        switch ($range) {
            case 'today':
                $this->startDate = $today->format('Y-m-d');
                $this->endDate = $today->format('Y-m-d');
                break;
            case 'yesterday':
                $this->startDate = $today->subDay()->format('Y-m-d');
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
        
        $this->loadAttendanceData();
    }

    public function confirmDelete($recordId)
    {
        $this->recordToDelete = $recordId;
        $this->showDeleteModal = true;
    }

    public function deleteRecord()
    {
        try {
            $record = AttendanceRecord::findOrFail($this->recordToDelete);
            
            // Delete attendance details first
            $record->attendanceDetails()->delete();
            
            // Delete the attendance record
            $record->delete();
            
            $this->showDeleteModal = false;
            $this->recordToDelete = null;
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Attendance record has been deleted successfully!',
            ]);
            
            $this->loadAttendanceData();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to delete attendance record: ' . $e->getMessage(),
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->recordToDelete = null;
    }

    public function viewDetails($recordId)
    {
        $record = AttendanceRecord::with(['attendanceDetails.student', 'myClass', 'section', 'markedBy'])
            ->findOrFail($recordId);
            
        $this->detailsData = $record;
        $this->detailsModalOpen = true;
        
        // Check if all students are present
        $this->isAllPresent = $record->attendanceDetails->every(function ($detail) {
            return $detail->status === 'present';
        });
    }

    public function closeDetailsModal()
    {
        $this->detailsModalOpen = false;
        $this->detailsData = [];
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->loadAttendanceData();
    }

    public function applyFilter()
    {
        $this->loadAttendanceData();
    }

    public function resetFilters()
    {
        $this->filters = [
            'status' => '',
            'date_range' => '',
        ];
        
        $this->loadAttendanceData();
    }

    public function downloadAttendanceReport()
    {
        return redirect()->route('attendance.report', [
            'class_id' => $this->classId,
            'section_id' => $this->sectionId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }

    public function loadClasses()
    {
        $this->classes = MyClass::orderBy('name')->get();
    }

    public function loadSections()
    {
        $this->sections = Section::where('my_class_id', $this->classId)
            ->orderBy('name')
            ->get();
    }

    public function updatedClassId()
    {
        $this->class = MyClass::find($this->classId);
        $this->loadSections();
        $this->sectionId = null;
        $this->section = null;
        $this->attendanceData = [];
    }

    public function updatedSectionId()
    {
        if ($this->sectionId) {
            $this->section = Section::find($this->sectionId);
            $this->loadAttendanceData();
        } else {
            $this->section = null;
            $this->attendanceData = [];
        }
    }

    public function render()
    {
        return view('livewire.attendance.view-attendance');
    }
} 