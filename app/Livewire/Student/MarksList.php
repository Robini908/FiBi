<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\ExamMarks;
use App\Models\Setting;
use App\Models\StudentRecord;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MarksList extends Component
{
    use WithPagination;

    public $studentId;
    public $studentRecordId;
    public $classId;
    public $sectionId;
    public $exams = [];
    public $currentExamId = null;
    public $subjects = [];
    public $marks = [];
    public $totalMarks = 0;
    public $obtainedMarks = 0;
    public $percentage = 0;
    public $grades = [];
    
    public function mount()
    {
        // Get the authenticated student's record
        $this->studentId = Auth::user()->id;
        $studentRecord = StudentRecord::where('user_id', $this->studentId)
            ->where('is_graduated', 0)
            ->first();
            
        if ($studentRecord) {
            $this->studentRecordId = $studentRecord->id;
            $this->classId = $studentRecord->my_class_id;
            $this->sectionId = $studentRecord->section_id;
            
            // Load exams for the student's class
            $this->loadExams();
            
            // Set first exam as default if available
            if ($this->exams->count() > 0) {
                $this->currentExamId = $this->exams->first()->id;
                $this->loadMarks();
            }
        }
    }
    
    public function loadExams()
    {
        // Get current academic year and term
        $currentSession = Setting::where('key', 'current_session')->first()->value ?? date('Y').'-'.(date('Y')+1);
        
        // Load all exams for student's class with results
        $this->exams = Exam::whereHas('examMarks', function($query) {
            $query->where('student_id', $this->studentRecordId);
        })->orderBy('year', 'desc')
          ->orderBy('term', 'asc')
          ->get();
    }
    
    public function updatedCurrentExamId()
    {
        if ($this->currentExamId) {
            $this->loadMarks();
        } else {
            $this->resetMarks();
        }
    }
    
    public function loadMarks()
    {
        if (!$this->currentExamId || !$this->studentRecordId) {
            return;
        }
        
        // Get subjects for the class
        $this->subjects = Subject::whereHas('examMarks', function($query) {
            $query->where('exam_id', $this->currentExamId)
                  ->where('student_id', $this->studentRecordId);
        })->get();
        
        // Get exam marks for each subject
        $this->marks = ExamMarks::where('exam_id', $this->currentExamId)
            ->where('student_id', $this->studentRecordId)
            ->with(['subject', 'exam', 'exam.gradingSystem'])
            ->get()
            ->keyBy('subject_id');
            
        // Calculate statistics
        $this->calculateStatistics();
    }
    
    public function resetMarks()
    {
        $this->marks = [];
        $this->totalMarks = 0;
        $this->obtainedMarks = 0;
        $this->percentage = 0;
    }
    
    public function calculateStatistics()
    {
        $this->totalMarks = 0;
        $this->obtainedMarks = 0;
        $this->percentage = 0;
        
        if (count($this->marks) > 0) {
            foreach ($this->marks as $mark) {
                $this->totalMarks += $mark->total_marks;
                $this->obtainedMarks += $mark->marks_obtained;
            }
            
            if ($this->totalMarks > 0) {
                $this->percentage = round(($this->obtainedMarks / $this->totalMarks) * 100, 2);
            }
        }
    }
    
    public function render()
    {
        return view('livewire.student.marks-list');
    }
} 