<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\User;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Auth;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Illuminate\Support\Facades\DB;

class StudentCharts extends Component
{
    public $showAcademicPerformance = true;
    public $showAttendanceRecord = true;
    public $showSubjectDistribution = true;
    
    public function render()
    {
        $student = Auth::user();
        $studentRecord = StudentRecord::where('user_id', $student->id)->first();
        
        // Academic performance by subject
        $academicPerformance = (new ColumnChartModel())
            ->setTitle('My Academic Performance')
            ->setAnimated(true)
            ->withDataLabels();
            
        // Example data - replace with actual subject scores
        $subjects = ['Math', 'English', 'Science', 'History', 'Art', 'P.E.'];
        $scores = [85, 92, 78, 88, 95, 90]; // Example scores
        
        foreach ($subjects as $index => $subject) {
            $academicPerformance->addColumn($subject, $scores[$index], '#3b82f6');
        }
        
        // Attendance record
        $attendanceRecord = (new LineChartModel())
            ->setTitle('My Attendance')
            ->setAnimated(true)
            ->withDataLabels();
            
        // Example data for attendance over terms
        $terms = ['Term 1', 'Term 2', 'Term 3'];
        $attendanceData = [98, 95, 97]; // Percentage attendance
        
        foreach ($terms as $index => $term) {
            $attendanceRecord->addPoint($term, $attendanceData[$index]);
        }
        
        // Subject time distribution (study hours)
        $subjectDistribution = (new PieChartModel())
            ->setTitle('Study Time Distribution')
            ->setAnimated(true)
            ->withDataLabels();
            
        // Example data for study time allocation
        $subjectDistribution->addSlice('Math', 25, '#3b82f6')
            ->addSlice('English', 20, '#16a34a')
            ->addSlice('Science', 30, '#f97316')
            ->addSlice('History', 15, '#dc2626')
            ->addSlice('Other', 10, '#9333ea');
            
        return view('livewire.dashboard.student-charts', [
            'academicPerformance' => $academicPerformance,
            'attendanceRecord' => $attendanceRecord,
            'subjectDistribution' => $subjectDistribution,
        ]);
    }
}
