<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentTransition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'from_academic_year',
        'to_academic_year',
        'academic_period',
        'from_class_id',
        'from_section_id',
        'to_class_id',
        'to_section_id',
        'transition_type',
        'is_active',
        'reason',
        'created_by',
        'effective_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'effective_date' => 'datetime',
        'from_academic_year' => 'integer',
        'to_academic_year' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the student associated with this transition.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }

    /**
     * Get the original class for this transition.
     */
    public function fromClass(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'from_class_id');
    }

    /**
     * Get the original section for this transition.
     */
    public function fromSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'from_section_id');
    }

    /**
     * Get the target class for this transition.
     */
    public function toClass(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'to_class_id');
    }

    /**
     * Get the target section for this transition.
     */
    public function toSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'to_section_id');
    }

    /**
     * Get the user who created this transition.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Apply this transition to update the student's class and section
     */
    public function applyTransition(): bool
    {
        // Get the student record
        $student = $this->student;
        
        if (!$student) {
            return false;
        }
        
        // Deactivate all other active transitions for this student
        self::where('student_id', $this->student_id)
            ->where('id', '!=', $this->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);
            
        // Update the student's class and section
        $student->my_class_id = $this->to_class_id;
        $student->section_id = $this->to_section_id;
        
        // If this is a graduation, mark the student as graduated
        if ($this->transition_type === 'graduation') {
            $student->grad = 1;
            $student->grad_date = $this->effective_date;
        }
        
        return $student->save();
    }

    /**
     * Scope a query to only include active transitions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include transitions for a specific academic year.
     */
    public function scopeForAcademicYear($query, $year)
    {
        return $query->where('to_academic_year', $year);
    }

    /**
     * Create a new transition and automatically apply it.
     */
    public static function createAndApply(array $attributes): ?self
    {
        $transition = self::create($attributes);
        
        if ($transition) {
            $transition->applyTransition();
            return $transition;
        }
        
        return null;
    }
}