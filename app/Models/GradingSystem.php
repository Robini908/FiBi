<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GradingSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'class_type_id',
        'is_default',
        'academic_term',
        'academic_year',
        'created_by',
        'pass_mark',
        'effective_date',
        'rules',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'effective_date' => 'datetime',
    ];

    public function classType(): BelongsTo
    {
        return $this->belongsTo(ClassType::class, 'class_type_id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'grading_system_subject', 'grading_system_id', 'subject_id')
            ->withPivot('additional_rules', 'override_parent_rules')
            ->withTimestamps();
    }

    public function gradingRanges(): HasMany
    {
        return $this->hasMany(GradingRange::class);
    }

    // Add the relationship to GradingGrade
    public function grades(): HasMany
    {
        return $this->hasMany(GradingGrade::class);
    }
    
    /**
     * Get the grade ranges for the grading system.
     */
    public function gradeRanges(): HasMany
    {
        return $this->hasMany(GradeRange::class);
    }
}
