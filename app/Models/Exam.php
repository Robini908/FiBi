<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Exam extends Model
{
    protected $fillable = ['name', 'term', 'year', 'grading_system_id'];

    // Define the relationship with GradingSystem
    public function gradingSystem(): BelongsTo
    {
        return $this->belongsTo(GradingSystem::class);
    }

    // Define the relationship with StudentRecord
    public function studentRecords(): HasMany
    {
        return $this->hasMany(StudentRecord::class);
    }

    // Get classes for this exam
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(MyClass::class, 'exam_class_section', 'exam_id', 'class_id')
            ->withPivot(['section_id', 'subject_id', 'exam_date', 'start_time', 'end_time', 'venue', 'invigilators', 'instructions'])
            ->withTimestamps();
    }

    // Get sections for this exam
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'exam_class_section', 'exam_id', 'section_id')
            ->withPivot(['class_id', 'subject_id', 'exam_date', 'start_time', 'end_time', 'venue', 'invigilators', 'instructions'])
            ->withTimestamps();
    }

    // Get subjects for this exam
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'exam_class_section', 'exam_id', 'subject_id')
            ->withPivot(['class_id', 'section_id', 'exam_date', 'start_time', 'end_time', 'venue', 'invigilators', 'instructions'])
            ->withTimestamps();
    }
    
    public function examMarks(): HasMany
    {
        return $this->hasMany(ExamMarks::class);
    }

    // Define the relationship with StudentResult
    public function studentResults(): HasMany
    {
        return $this->hasMany(StudentResult::class, 'exam_id', 'id'); 
    }

    // Get the exam schedules
    public function examSchedules(): HasMany
    {
        return $this->hasMany(ExamClassSection::class, 'exam_id');
    }
}
