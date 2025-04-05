<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRecord extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'exam_id',
        'class_id',
        'section_id',
        'subject_id',
        'date',
        'start_time',
        'end_time',
        'venue',
        'invigilators',
        'instructions',
    ];
    
    protected $casts = [
        'date' => 'date',
        'invigilators' => 'array',
    ];
    
    /**
     * Get the exam this record belongs to
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    
    /**
     * Get the class this record belongs to
     */
    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }
    
    /**
     * Get the section this record belongs to
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    
    /**
     * Get the subject for this record
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    /**
     * Scope to filter exam records by date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
    
    /**
     * Format the start and end time as a range
     */
    public function getTimeRangeAttribute()
    {
        return $this->start_time . ' - ' . $this->end_time;
    }
    
    /**
     * Get the duration of the exam in minutes
     */
    public function getDurationMinutesAttribute()
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        
        // Calculate difference in seconds and convert to minutes
        return ($end - $start) / 60;
    }
} 