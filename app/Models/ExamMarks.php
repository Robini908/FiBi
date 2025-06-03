<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamMarks extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_id',
        'subject_id',
        'grading_range_id',
        'marks',
        'special_grade', // Add this field for special grades (X, Y, Z)
    ];

    /**
     * Relationship with StudentRecord
     */
    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }

    /**
     * Relationship with Exam
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /**
     * Relationship with Subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Relationship with GradingRange
     */
    public function gradingRange()
    {
        return $this->belongsTo(GradingRange::class, 'grading_range_id');
    }

    public function studentRecord()
    {
        return $this->hasOne(StudentRecord::class, 'id', 'student_id');
    }

    /**
     * Get the full name of the student.
     * You can customize this based on your StudentRecord model.
     */
    public function getStudentFullNameAttribute()
    {
        return "{$this->student->first_name} {$this->student->last_name}";
    }

    /**
     * Calculate the percentage of marks (assuming max marks is defined).
     */
    public function getPercentageAttribute($maxMarks = 100)
    {
        return ($this->marks / $maxMarks) * 100;
    }

    /**
     * Define special grades and their meanings.
     */
    public static function getSpecialGrades()
    {
        return [
            'AB' => 'Absent from the exam or a significant portion of it.',
            'EX' => 'Exempted from the exam for valid reasons.',
            'P' => 'Pass without specific marks.',
            'F' => 'Fail without specific marks.',
        ];
    }

    /**
     * Validation rules for ExamMarks.
     */
    public static function rules()
    {
        return [
            'student_id' => 'required|exists:student_records,id',
            'exam_id' => 'required|exists:exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'nullable|numeric|min:0|max:100',
            'special_grade' => 'nullable|in:AB,EX,P,F',
        ];
    }

    /**
     * Ensure either marks or special_grade is provided, but not both.
     */
    public static function validateMarksOrSpecialGrade($data)
    {
        if (empty($data['marks']) && empty($data['special_grade'])) {
            throw new \Exception('Either marks or a special grade must be provided.');
        }
        if (!empty($data['marks']) && !empty($data['special_grade'])) {
            throw new \Exception('Cannot provide both marks and a special grade.');
        }
    }
}