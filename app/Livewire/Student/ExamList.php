<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\ExamClassSection;
use App\Models\Setting;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ExamList extends Component
{
    use WithPagination;

    public $currentExams = [];
    public $pastExams = [];
    public $selectedExamId = null;
    public $examDetails = null;
    public $studentId;
    public $classId;
    public $sectionId;
    
    public function mount()
    {
        // Get the authenticated student's record
        $this->studentId = Auth::user()->id;
        $studentRecord = StudentRecord::where('user_id', $this->studentId)
            ->where('is_graduated', 0)
            ->first();
            
        if ($studentRecord) {
            $this->classId = $studentRecord->my_class_id;
            $this->sectionId = $studentRecord->section_id;
            $this->loadExams();
        }
    }
    
    public function loadExams()
    {
        // Get current academic year and term
        $currentSession = \App\Models\Setting::where('key', 'current_session')->first()->value ?? date('Y').'-'.(date('Y')+1);
        $currentTerm = \App\Models\Setting::where('key', 'current_term')->first()->value ?? 'First Term';
        
        // Get exams for student's class and section
        $examIds = ExamClassSection::where('class_id', $this->classId)
            ->where(function($query) {
                $query->where('section_id', $this->sectionId)
                    ->orWhereNull('section_id');
            })
            ->distinct()
            ->pluck('exam_id')
            ->toArray();
            
        if (!empty($examIds)) {
            // Get current exams (this term & session)
            $this->currentExams = Exam::whereIn('id', $examIds)
                ->where('term', $currentTerm)
                ->where('year', $currentSession)
                ->orderBy('created_at', 'desc')
                ->get();
                
            // Get past exams
            $this->pastExams = Exam::whereIn('id', $examIds)
                ->where(function($query) use ($currentTerm, $currentSession) {
                    $query->where('term', '!=', $currentTerm)
                        ->orWhere('year', '!=', $currentSession);
                })
                ->orderBy('year', 'desc')
                ->orderBy('term', 'desc')
                ->get();
        }
    }
    
    public function viewExamDetails($examId)
    {
        $this->selectedExamId = $examId;
        
        // Get exam schedule details
        $this->examDetails = ExamClassSection::where('exam_id', $examId)
            ->where('class_id', $this->classId)
            ->where(function($query) {
                $query->where('section_id', $this->sectionId)
                    ->orWhereNull('section_id');
            })
            ->with(['subject', 'exam'])
            ->orderBy('exam_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
    }
    
    public function resetExamDetails()
    {
        $this->selectedExamId = null;
        $this->examDetails = null;
    }
    
    public function render()
    {
        return view('livewire.student.exam-list');
    }
} 