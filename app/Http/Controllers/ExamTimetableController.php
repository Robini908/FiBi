<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamRecord;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamTimetableExport;

class ExamTimetableController extends Controller
{
    /**
     * Export exam timetable to PDF
     */
    public function exportPdf($examId, $classId, $sectionId = null)
    {
        $exam = Exam::findOrFail($examId);
        $myClass = MyClass::findOrFail($classId);
        $section = $sectionId ? Section::findOrFail($sectionId) : null;
        
        $settings = Setting::all()->flatMap(function($s) {
            return [$s->type => $s->description];
        });
        
        $examRecords = ExamRecord::where('exam_id', $examId)
            ->where('class_id', $classId)
            ->when($sectionId, function($query) use ($sectionId) {
                return $query->where('section_id', $sectionId);
            })
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
            
        $teachers = User::whereIn('id', $examRecords->pluck('invigilators')->flatten()->unique())
            ->role('teacher')
            ->get()
            ->keyBy('id');
        
        $data = [
            'exam' => $exam,
            'class' => $myClass,
            'section' => $section,
            'examRecords' => $examRecords,
            'teachers' => $teachers,
            'settings' => $settings,
        ];
        
        $pdf = PDF::loadView('exports.exam-timetable-pdf', $data);
        
        return $pdf->download('exam_timetable_'.$exam->name.'_'.$myClass->name.($section ? '_'.$section->name : '').'.pdf');
    }
    
    /**
     * Export exam timetable to Excel
     */
    public function exportExcel($examId, $classId, $sectionId = null)
    {
        $exam = Exam::findOrFail($examId);
        $myClass = MyClass::findOrFail($classId);
        $section = $sectionId ? Section::findOrFail($sectionId) : null;
        
        $filename = 'exam_timetable_'.$exam->name.'_'.$myClass->name.($section ? '_'.$section->name : '').'.xlsx';
        
        return Excel::download(new ExamTimetableExport($examId, $classId, $sectionId), $filename);
    }
    
    /**
     * Show printable view of exam timetable
     */
    public function printView($examId, $classId, $sectionId = null)
    {
        $exam = Exam::findOrFail($examId);
        $myClass = MyClass::findOrFail($classId);
        $section = $sectionId ? Section::findOrFail($sectionId) : null;
        
        $settings = Setting::all()->flatMap(function($s) {
            return [$s->type => $s->description];
        });
        
        $examRecords = ExamRecord::where('exam_id', $examId)
            ->where('class_id', $classId)
            ->when($sectionId, function($query) use ($sectionId) {
                return $query->where('section_id', $sectionId);
            })
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
            
        $teachers = User::whereIn('id', $examRecords->pluck('invigilators')->flatten()->unique())
            ->role('teacher')
            ->get()
            ->keyBy('id');
        
        return view('exports.exam-timetable-print', [
            'exam' => $exam,
            'class' => $myClass,
            'section' => $section,
            'examRecords' => $examRecords,
            'teachers' => $teachers,
            'settings' => $settings,
        ]);
    }
} 