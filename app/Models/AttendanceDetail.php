<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_record_id',
        'student_id',
        'status',
        'time_in',
        'time_out',
        'minutes_late',
        'remarks',
    ];

    protected $casts = [
        'time_in' => 'datetime:H:i',
        'time_out' => 'datetime:H:i',
    ];

    /**
     * Get the attendance record that owns this detail.
     */
    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class);
    }

    /**
     * Get the student that owns this attendance detail.
     */
    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }

    /**
     * Scope a query to only include records with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the color associated with the attendance status.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'present' => 'green',
            'absent' => 'red',
            'late' => 'yellow',
            'excused' => 'blue',
            'sick' => 'purple',
            'on_leave' => 'indigo',
            default => 'gray',
        };
    }

    /**
     * Get the icon associated with the attendance status.
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'present' => 'check-circle',
            'absent' => 'x-circle',
            'late' => 'clock',
            'excused' => 'exclamation-circle',
            'sick' => 'medical-symbol',
            'on_leave' => 'briefcase',
            default => 'question-mark-circle',
        };
    }

    /**
     * Get student attendance statistics for a specific time period.
     */
    public static function getStudentAttendanceStats($studentId, $startDate, $endDate)
    {
        $attendanceDetails = self::whereHas('attendanceRecord', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('attendance_date', [$startDate, $endDate]);
        })->where('student_id', $studentId)
          ->get();

        $totalDays = $attendanceDetails->count();
        $presentDays = $attendanceDetails->where('status', 'present')->count();
        $absentDays = $attendanceDetails->where('status', 'absent')->count();
        $lateDays = $attendanceDetails->where('status', 'late')->count();
        $excusedDays = $attendanceDetails->whereIn('status', ['excused', 'sick', 'on_leave'])->count();
        
        $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
        
        return [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'excused_days' => $excusedDays,
            'attendance_rate' => $attendanceRate,
        ];
    }
} 