<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolTimetable extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'class_id',
        'section_id',
        'academic_term',
        'academic_session',
        'is_active',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the class that owns the timetable.
     */
    public function myClass(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }

    /**
     * Get the section that owns the timetable (if specified).
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Get the time periods for this timetable.
     */
    public function periods(): HasMany
    {
        return $this->hasMany(TimetablePeriod::class, 'timetable_id');
    }

    /**
     * Get the schedule entries for this timetable.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(TimetableSchedule::class, 'timetable_id');
    }

    /**
     * Scope a query to only include active timetables.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by academic term.
     */
    public function scopeByTerm($query, $term)
    {
        return $query->where('academic_term', $term);
    }

    /**
     * Scope a query to filter by academic session.
     */
    public function scopeBySession($query, $session)
    {
        return $query->where('academic_session', $session);
    }

    /**
     * Scope to check if a timetable already exists for a term/session combination
     */
    public function scopeExistsForTermSession($query, $classId, $term, $session, $sectionId = null)
    {
        $query = $query->where('class_id', $classId)
                       ->where('academic_term', $term)
                       ->where('academic_session', $session);
                       
        if ($sectionId) {
            $query->where('section_id', $sectionId);
        } else {
            $query->whereNull('section_id');
        }
        
        return $query;
    }
    
    /**
     * Generate a unique timetable name based on class, term, and session
     */
    public static function generateUniqueName($className, $term, $session, $customName = null)
    {
        // Log inputs to debug
        \Log::debug("GenerateUniqueName called with: className={$className}, term={$term}, session={$session}, customName={$customName}");
        
        if ($customName) {
            // Check if custom name already exists
            $baseName = $customName;
            $count = 0;
            $nameToTry = $baseName;
            
            // Keep checking until we find a unique name by adding (1), (2), etc.
            while (self::where('name', $nameToTry)->exists()) {
                $count++;
                $nameToTry = "{$baseName} ({$count})";
            }
            
            \Log::debug("Custom name generated: {$nameToTry}");
            return $nameToTry;
        }
        
        // Generate a standard name format
        $baseName = "Timetable for {$className} ({$term}, {$session})";
        $count = 0;
        $nameToTry = $baseName;
        
        // Keep checking until we find a unique name by adding (1), (2), etc.
        while (self::where('name', $nameToTry)->exists()) {
            $count++;
            $nameToTry = "{$baseName} ({$count})";
        }
        
        \Log::debug("Auto-generated name: {$nameToTry}");
        return $nameToTry;
    }

    /**
     * Generate a standard timetable name based on class, term, and session without checking uniqueness
     */
    public static function generateStandardName($className, $term, $session)
    {
        return "Timetable for {$className} ({$term}, {$session})";
    }
}
