<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade',
        'min_mark',
        'max_mark',
        'description',
        'grading_system_id',
    ];

    protected $casts = [
        'min_mark' => 'float',
        'max_mark' => 'float',
    ];

    /**
     * Get the grading system that owns the grade range.
     */
    public function gradingSystem(): BelongsTo
    {
        return $this->belongsTo(GradingSystem::class);
    }
} 