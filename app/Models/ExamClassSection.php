<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamClassSection extends Model
{
    protected $table = 'exam_class_section';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'exam_id', 
        'class_id', 
        'section_id', 
        'exam_date', 
        'start_time', 
        'end_time', 
        'instructions',
        'subject_id',
        'venue',
        'invigilators'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'exam_date' => 'date',
        'invigilators' => 'array',
    ];

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function myClass()
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Convert invigilators to and from JSON storage format
     */
    public function setInvigilatorsAttribute($value)
    {
        $this->attributes['invigilators'] = is_array($value) ? json_encode($value) : $value;
    }
    
    public function getInvigilatorsAttribute($value)
    {
        if (!$value) return [];
        return is_string($value) ? json_decode($value, true) : $value;
    }
}
