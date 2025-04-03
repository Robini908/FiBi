<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceDetail;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Setting;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    /**
     * Display the attendance management dashboard.
     */
    public function index()
    {
        $data['classes'] = MyClass::orderBy('name')->get();
        $data['page_title'] = 'Attendance Management';
        
        return view('pages.support_team.attendance.index', $data);
    }

    /**
     * Show the form for taking attendance for a specific class and section.
     */
    public function takeAttendance($class_id, $section_id)
    {
        $class = MyClass::find($class_id);
        $section = Section::find($section_id);
        
        if (!$class || !$section) {
            return redirect()->route('attendance.index')->with('error', 'Class or section not found');
        }
        
        $data['class'] = $class;
        $data['section'] = $section;
        $data['students'] = StudentRecord::where('my_class_id', $class_id)
            ->where('section_id', $section_id)
            ->orderBy('name')
            ->get();
        $data['today'] = Carbon::today()->format('Y-m-d');
        $data['session_types'] = ['morning', 'afternoon', 'whole_day'];
        $data['status_options'] = ['present', 'absent', 'late', 'excused', 'sick', 'on_leave', 'other'];
        $data['page_title'] = 'Take Attendance - ' . $class->name . ' ' . $section->name;
        
        // Check if attendance is already taken for today
        $existingRecord = AttendanceRecord::where('class_id', $class_id)
            ->where('section_id', $section_id)
            ->whereDate('attendance_date', Carbon::today())
            ->first();
            
        $data['existing_record'] = $existingRecord;
        
        if ($existingRecord) {
            $data['attendance_details'] = $existingRecord->attendanceDetails()->get()->keyBy('student_id');
        }
        
        return view('pages.support_team.attendance.take_attendance', $data);
    }

    /**
     * Store a newly created attendance record.
     */
    public function storeAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:my_classes,id',
            'section_id' => 'required|exists:sections,id',
            'attendance_date' => 'required|date',
            'session_type' => ['required', Rule::in(['morning', 'afternoon', 'whole_day'])],
            'student_status' => 'required|array',
            'student_status.*' => ['required', Rule::in(['present', 'absent', 'late', 'excused', 'sick', 'on_leave', 'other'])],
            'remarks' => 'nullable|string|max:500',
        ]);
        
        // Get current session and term
        $academic_year = Setting::where('key', 'current_session')->first()->value;
        $term = Setting::where('key', 'current_term')->first()->value ?? 1;
        
        DB::beginTransaction();
        
        try {
            // Check if attendance record already exists
            $existingRecord = AttendanceRecord::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->whereDate('attendance_date', $request->attendance_date)
                ->where('session_type', $request->session_type)
                ->first();
                
            if ($existingRecord) {
                // Update existing record
                $existingRecord->update([
                    'marked_by' => Auth::id(),
                    'remarks' => $request->remarks,
                ]);
                
                $attendanceRecord = $existingRecord;
                
                // Delete existing details to avoid duplicates
                $attendanceRecord->attendanceDetails()->delete();
            } else {
                // Create new attendance record
                $attendanceRecord = AttendanceRecord::create([
                    'attendance_date' => $request->attendance_date,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'marked_by' => Auth::id(),
                    'remarks' => $request->remarks,
                    'academic_year' => $academic_year,
                    'term' => $term,
                    'session_type' => $request->session_type,
                ]);
            }
            
            // Store attendance details for each student
            $attendanceDetails = [];
            
            foreach ($request->student_status as $student_id => $status) {
                $timeIn = null;
                $timeOut = null;
                $minutesLate = 0;
                
                // If student is late, record the time and minutes late
                if ($status === 'late' && isset($request->time_in[$student_id])) {
                    $timeIn = $request->time_in[$student_id];
                    
                    // Calculate minutes late if expected time is set
                    if (isset($request->expected_time) && $request->expected_time) {
                        $expectedTime = Carbon::parse($request->expected_time);
                        $actualTime = Carbon::parse($timeIn);
                        $minutesLate = $expectedTime->diffInMinutes($actualTime);
                    }
                }
                
                $attendanceDetails[] = [
                    'attendance_record_id' => $attendanceRecord->id,
                    'student_id' => $student_id,
                    'status' => $status,
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                    'minutes_late' => $minutesLate,
                    'remarks' => $request->student_remarks[$student_id] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Insert all attendance details at once
            AttendanceDetail::insert($attendanceDetails);
            
            DB::commit();
            
            return redirect()->route('attendance.view', ['class_id' => $request->class_id, 'section_id' => $request->section_id])
                ->with('success', 'Attendance has been recorded successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to record attendance: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * View attendance records for a specific class and section.
     */
    public function viewAttendance($class_id, $section_id)
    {
        $class = MyClass::find($class_id);
        $section = Section::find($section_id);
        
        if (!$class || !$section) {
            return redirect()->route('attendance.index')->with('error', 'Class or section not found');
        }
        
        $data['class'] = $class;
        $data['section'] = $section;
        $data['students'] = StudentRecord::where('my_class_id', $class_id)
            ->where('section_id', $section_id)
            ->orderBy('name')
            ->get();
            
        // Get attendance records for the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $data['records'] = AttendanceRecord::where('class_id', $class_id)
            ->where('section_id', $section_id)
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->orderBy('attendance_date', 'desc')
            ->with('attendanceDetails')
            ->get();
            
        $data['page_title'] = 'View Attendance - ' . $class->name . ' ' . $section->name;
        $data['start_date'] = $startOfMonth->format('Y-m-d');
        $data['end_date'] = $endOfMonth->format('Y-m-d');
        
        return view('pages.support_team.attendance.view_attendance', $data);
    }

    /**
     * Filter attendance records by date range.
     */
    public function filterAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:my_classes,id',
            'section_id' => 'required|exists:sections,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $class = MyClass::find($request->class_id);
        $section = Section::find($request->section_id);
        
        if (!$class || !$section) {
            return redirect()->route('attendance.index')->with('error', 'Class or section not found');
        }
        
        $data['class'] = $class;
        $data['section'] = $section;
        $data['students'] = StudentRecord::where('my_class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->orderBy('name')
            ->get();
            
        $data['records'] = AttendanceRecord::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
            ->orderBy('attendance_date', 'desc')
            ->with('attendanceDetails')
            ->get();
            
        $data['page_title'] = 'View Attendance - ' . $class->name . ' ' . $section->name;
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        
        return view('pages.support_team.attendance.view_attendance', $data);
    }

    /**
     * Show the attendance details for a specific date.
     */
    public function showAttendanceDetails($record_id)
    {
        $record = AttendanceRecord::with(['attendanceDetails.student', 'myClass', 'section', 'markedBy'])
            ->findOrFail($record_id);
            
        $data['record'] = $record;
        $data['page_title'] = 'Attendance Details - ' . $record->attendance_date->format('d M, Y');
        
        return view('pages.support_team.attendance.details', $data);
    }

    /**
     * Edit the attendance record for a specific date.
     */
    public function editAttendance($record_id)
    {
        $record = AttendanceRecord::with(['attendanceDetails.student', 'myClass', 'section'])
            ->findOrFail($record_id);
            
        $data['record'] = $record;
        $data['students'] = StudentRecord::where('my_class_id', $record->class_id)
            ->where('section_id', $record->section_id)
            ->orderBy('name')
            ->get();
        $data['attendance_details'] = $record->attendanceDetails()->get()->keyBy('student_id');
        $data['session_types'] = ['morning', 'afternoon', 'whole_day'];
        $data['status_options'] = ['present', 'absent', 'late', 'excused', 'sick', 'on_leave', 'other'];
        $data['page_title'] = 'Edit Attendance - ' . $record->attendance_date->format('d M, Y');
        
        return view('pages.support_team.attendance.edit_attendance', $data);
    }

    /**
     * Update the attendance record.
     */
    public function updateAttendance(Request $request, $record_id)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'session_type' => ['required', Rule::in(['morning', 'afternoon', 'whole_day'])],
            'student_status' => 'required|array',
            'student_status.*' => ['required', Rule::in(['present', 'absent', 'late', 'excused', 'sick', 'on_leave', 'other'])],
            'remarks' => 'nullable|string|max:500',
        ]);
        
        $record = AttendanceRecord::findOrFail($record_id);
        
        DB::beginTransaction();
        
        try {
            // Update attendance record
            $record->update([
                'attendance_date' => $request->attendance_date,
                'session_type' => $request->session_type,
                'marked_by' => Auth::id(),
                'remarks' => $request->remarks,
            ]);
            
            // Delete existing details
            $record->attendanceDetails()->delete();
            
            // Store updated attendance details for each student
            $attendanceDetails = [];
            
            foreach ($request->student_status as $student_id => $status) {
                $timeIn = null;
                $timeOut = null;
                $minutesLate = 0;
                
                // If student is late, record the time and minutes late
                if ($status === 'late' && isset($request->time_in[$student_id])) {
                    $timeIn = $request->time_in[$student_id];
                    
                    // Calculate minutes late if expected time is set
                    if (isset($request->expected_time) && $request->expected_time) {
                        $expectedTime = Carbon::parse($request->expected_time);
                        $actualTime = Carbon::parse($timeIn);
                        $minutesLate = $expectedTime->diffInMinutes($actualTime);
                    }
                }
                
                $attendanceDetails[] = [
                    'attendance_record_id' => $record->id,
                    'student_id' => $student_id,
                    'status' => $status,
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                    'minutes_late' => $minutesLate,
                    'remarks' => $request->student_remarks[$student_id] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Insert all attendance details at once
            AttendanceDetail::insert($attendanceDetails);
            
            DB::commit();
            
            return redirect()->route('attendance.show', $record_id)
                ->with('success', 'Attendance has been updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update attendance: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete an attendance record.
     */
    public function deleteAttendance($record_id)
    {
        try {
            $record = AttendanceRecord::findOrFail($record_id);
            $class_id = $record->class_id;
            $section_id = $record->section_id;
            
            // Delete attendance details first
            $record->attendanceDetails()->delete();
            
            // Delete the attendance record
            $record->delete();
            
            return redirect()->route('attendance.view', ['class_id' => $class_id, 'section_id' => $section_id])
                ->with('success', 'Attendance record has been deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete attendance record: ' . $e->getMessage());
        }
    }

    /**
     * Generate attendance report for a class/section.
     */
    public function attendanceReport(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:my_classes,id',
            'section_id' => 'required|exists:sections,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $class = MyClass::find($request->class_id);
        $section = Section::find($request->section_id);
        
        if (!$class || !$section) {
            return redirect()->route('attendance.index')->with('error', 'Class or section not found');
        }
        
        $data['class'] = $class;
        $data['section'] = $section;
        
        // Get all students in the class/section
        $students = StudentRecord::where('my_class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->orderBy('name')
            ->get();
            
        // Get all attendance records in the date range
        $records = AttendanceRecord::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
            ->orderBy('attendance_date')
            ->with('attendanceDetails')
            ->get();
            
        // Calculate statistics for each student
        $studentStats = [];
        
        foreach ($students as $student) {
            $stats = AttendanceDetail::getStudentAttendanceStats(
                $student->id,
                $request->start_date,
                $request->end_date
            );
            
            $studentStats[$student->id] = $stats;
        }
        
        // Calculate overall statistics
        $overallStats = AttendanceRecord::getAttendanceStats(
            $request->class_id,
            $request->section_id,
            $request->start_date,
            $request->end_date
        );
        
        $data['students'] = $students;
        $data['records'] = $records;
        $data['student_stats'] = $studentStats;
        $data['overall_stats'] = $overallStats;
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['page_title'] = 'Attendance Report - ' . $class->name . ' ' . $section->name;
        
        return view('pages.support_team.attendance.report', $data);
    }

    /**
     * Show the student attendance history.
     */
    public function studentAttendanceHistory($student_id)
    {
        $student = StudentRecord::findOrFail($student_id);
        
        // Get current academic year and term
        $academic_year = Setting::where('key', 'current_session')->first()->value;
        $term = Setting::where('key', 'current_term')->first()->value ?? 1;
        
        // Get attendance records for the current term
        $attendanceDetails = AttendanceDetail::with(['attendanceRecord', 'student'])
            ->whereHas('attendanceRecord', function ($query) use ($academic_year, $term) {
                $query->where('academic_year', $academic_year)
                      ->where('term', $term);
            })
            ->where('student_id', $student_id)
            ->get();
            
        // Calculate statistics
        $totalDays = $attendanceDetails->count();
        $presentDays = $attendanceDetails->where('status', 'present')->count();
        $absentDays = $attendanceDetails->where('status', 'absent')->count();
        $lateDays = $attendanceDetails->where('status', 'late')->count();
        $excusedDays = $attendanceDetails->whereIn('status', ['excused', 'sick', 'on_leave'])->count();
        
        $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
        
        $data['student'] = $student;
        $data['attendance_details'] = $attendanceDetails;
        $data['stats'] = [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'excused_days' => $excusedDays,
            'attendance_rate' => $attendanceRate,
        ];
        $data['page_title'] = 'Attendance History - ' . $student->name;
        
        return view('pages.support_team.attendance.student_history', $data);
    }

    /**
     * Export attendance records for a specific student.
     */
    public function exportStudentAttendance(Request $request, $student_id)
    {
        // Validate request parameters
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
        
        // Get student record
        $student = StudentRecord::with(['user', 'my_class', 'section'])->findOrFail($student_id);
        
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found');
        }
        
        // Get date range
        $startDate = $request->query('start_date') ? Carbon::parse($request->query('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->query('end_date') ? Carbon::parse($request->query('end_date')) : Carbon::now()->endOfMonth();
        
        // Get attendance records for the student within the date range
        $attendanceDetails = AttendanceDetail::where('student_id', $student_id)
            ->whereHas('attendanceRecord', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('attendance_date', [$startDate, $endDate]);
            })
            ->with('attendanceRecord')
            ->get();
            
        // Calculate statistics
        $totalRecords = $attendanceDetails->count();
        $presentCount = $attendanceDetails->where('status', 'present')->count();
        $absentCount = $attendanceDetails->where('status', 'absent')->count();
        $lateCount = $attendanceDetails->where('status', 'late')->count();
        $excusedCount = $attendanceDetails->whereIn('status', ['excused', 'sick', 'on_leave', 'other'])->count();
        
        $attendanceRate = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;
        
        // Prepare export data
        $exportData = [
            'student' => $student,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'attendance_details' => $attendanceDetails,
            'statistics' => [
                'total' => $totalRecords,
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'excused' => $excusedCount,
                'attendance_rate' => $attendanceRate,
            ]
        ];
        
        // For now, just download as a CSV file
        // In a real implementation, you might want to use a package like Laravel Excel
        
        $filename = 'attendance_' . $student->name . '_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $columns = ['Date', 'Session', 'Status', 'Time In', 'Minutes Late', 'Remarks'];
        
        $callback = function() use ($exportData, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add file header
            fputcsv($file, ['Attendance Report']);
            fputcsv($file, ['Student', $exportData['student']->name]);
            fputcsv($file, ['Admission No', $exportData['student']->adm_no ?? 'N/A']);
            fputcsv($file, ['Class', ($exportData['student']->my_class->name ?? '') . ' ' . ($exportData['student']->section->name ?? '')]);
            fputcsv($file, ['Period', $exportData['start_date'] . ' to ' . $exportData['end_date']]);
            fputcsv($file, ['']);
            
            // Add statistics
            fputcsv($file, ['Statistics']);
            fputcsv($file, ['Total Days', $exportData['statistics']['total']]);
            fputcsv($file, ['Present', $exportData['statistics']['present']]);
            fputcsv($file, ['Absent', $exportData['statistics']['absent']]);
            fputcsv($file, ['Late', $exportData['statistics']['late']]);
            fputcsv($file, ['Excused', $exportData['statistics']['excused']]);
            fputcsv($file, ['Attendance Rate', $exportData['statistics']['attendance_rate'] . '%']);
            fputcsv($file, ['']);
            
            // Add column headers
            fputcsv($file, $columns);
            
            // Add data rows
            foreach ($exportData['attendance_details'] as $detail) {
                $row = [
                    $detail->attendanceRecord->attendance_date ?? 'N/A',
                    $detail->attendanceRecord->session_type ?? 'N/A',
                    ucfirst($detail->status),
                    $detail->time_in,
                    $detail->minutes_late > 0 ? $detail->minutes_late : '',
                    $detail->remarks,
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
} 