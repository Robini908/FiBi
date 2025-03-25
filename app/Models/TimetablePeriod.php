<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimetablePeriod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'timetable_id',
        'start_time',
        'end_time',
        'period_name',
        'period_order',
    ];

    /**
     * Get the timetable that owns this period.
     */
    public function timetable(): BelongsTo
    {
        return $this->belongsTo(SchoolTimetable::class, 'timetable_id');
    }

    /**
     * Get the schedule entries for this period.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(TimetableSchedule::class, 'period_id');
    }

    /**
     * Get formatted time range.
     *
     * @return string
     */
    public function getFormattedTimeAttribute(): string
    {
        $start = date('g:i A', strtotime($this->start_time));
        $end = date('g:i A', strtotime($this->end_time));
        
        return $this->period_name 
            ? "{$this->period_name} ({$start} - {$end})" 
            : "{$start} - {$end}";
    }

    /**
     * Get the duration of the period in minutes.
     *
     * @return int
     */
    public function getDurationMinutesAttribute(): int
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        
        return ($end - $start) / 60;
    }

    /**
     * Scope a query to order by period_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('period_order', 'asc');
    }
}
