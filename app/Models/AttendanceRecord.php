<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_date',
        'class_id',
        'section_id',
        'marked_by',
        'remarks',
        'academic_year',
        'term',
        'session_type',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    /**
     * Get the class that owns this attendance record.
     */
    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }

    /**
     * Get the section that owns this attendance record.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the user who marked this attendance.
     */
    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    /**
     * Get the attendance details for this record.
     */
    public function attendanceDetails()
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    /**
     * Scope a query to only include records for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    /**
     * Scope a query to only include records for a specific class.
     */
    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Scope a query to only include records for a specific section.
     */
    public function scopeForSection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    /**
     * Scope a query to only include records for the current academic year.
     */
    public function scopeCurrentAcademicYear($query)
    {
        $currentYear = Setting::where('key', 'current_session')->first()->value;
        return $query->where('academic_year', $currentYear);
    }

    /**
     * Scope a query to only include records for a specific term.
     */
    public function scopeForTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    /**
     * Get the attendance statistics for a specific class, section, and date range.
     */
    public static function getAttendanceStats($classId, $sectionId, $startDate, $endDate)
    {
        // Convert dates if they're string format
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        $stats = [
            'total_days' => 0,
            'present_count' => 0,
            'absent_count' => 0,
            'late_count' => 0,
            'excused_count' => 0,
            'attendance_rate' => 0,
        ];

        $records = self::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->with('attendanceDetails')
            ->get();

        $stats['total_days'] = $records->count();
        
        foreach ($records as $record) {
            $stats['present_count'] += $record->attendanceDetails()->where('status', 'present')->count();
            $stats['absent_count'] += $record->attendanceDetails()->where('status', 'absent')->count();
            $stats['late_count'] += $record->attendanceDetails()->where('status', 'late')->count();
            $stats['excused_count'] += $record->attendanceDetails()->whereIn('status', ['excused', 'sick', 'on_leave'])->count();
        }

        $totalEntries = $stats['present_count'] + $stats['absent_count'] + $stats['late_count'] + $stats['excused_count'];
        $stats['attendance_rate'] = $totalEntries > 0 ? round(($stats['present_count'] / $totalEntries) * 100, 2) : 0;

        return $stats;
    }
} 