<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimetableSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'timetable_id',
        'period_id',
        'subject_id',
        'teacher_id',
        'weekday',
        'classroom',
        'notes',
        'is_recurring',
        'specific_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_recurring' => 'boolean',
        'specific_date' => 'date',
    ];

    /**
     * The days of the week.
     *
     * @var array<int, string>
     */
    public static $weekdays = [
        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
    ];

    /**
     * Get the timetable that owns this schedule entry.
     */
    public function timetable(): BelongsTo
    {
        return $this->belongsTo(SchoolTimetable::class, 'timetable_id');
    }

    /**
     * Get the period that owns this schedule entry.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(TimetablePeriod::class, 'period_id');
    }

    /**
     * Get the subject that belongs to this schedule entry.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the teacher that belongs to this schedule entry.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Scope a query to filter by weekday.
     */
    public function scopeByWeekday($query, $weekday)
    {
        return $query->where('weekday', $weekday);
    }

    /**
     * Get a formatted representation of the schedule time.
     * 
     * @return string
     */
    public function getScheduleTimeAttribute(): string
    {
        $formattedDay = ucfirst($this->weekday);
        $periodTime = $this->period ? $this->period->formatted_time : '';
        
        if ($this->is_recurring) {
            return "{$formattedDay}, {$periodTime}";
        } else {
            return "{$this->specific_date->format('M d, Y')}, {$periodTime}";
        }
    }
}
