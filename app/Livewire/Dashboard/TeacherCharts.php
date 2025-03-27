<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\User;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Models\ExamRecord;
use Illuminate\Support\Facades\Auth;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Illuminate\Support\Facades\DB;

class TeacherCharts extends Component
{
    public $showClassDistribution = true;
    public $showSubjectPerformance = true;
    public $showAttendanceTrend = true;
    
    public function render()
    {
        $teacher = Auth::user();
        
        // Class distribution - number of students per class taught by this teacher
        $classDistribution = (new ColumnChartModel())
            ->setTitle('My Classes Distribution')
            ->setAnimated(true)
            ->withDataLabels();
            
        $teacherClasses = MyClass::whereHas('teachers', function($query) use ($teacher) {
            $query->where('users.id', $teacher->id);
        })->get();
        
        foreach ($teacherClasses as $class) {
            $studentCount = StudentRecord::where('my_class_id', $class->id)->count();
            $classDistribution->addColumn($class->name, $studentCount, '#3b82f6');
        }
        
        // Subject performance
        $subjectPerformance = (new LineChartModel())
            ->setTitle('Subject Performance Trends')
            ->setAnimated(true)
            ->withDataLabels();
            
        // This is a simplified example - in a real app, you'd have more complex logic
        // to get actual performance data from your exam records
        $subjects = ['Math', 'English', 'Science', 'History'];
        $scores = [78, 82, 75, 90]; // Example data
        
        foreach ($subjects as $index => $subject) {
            $subjectPerformance->addPoint($subject, $scores[$index]);
        }
        
        // Attendance trend
        $attendanceTrend = (new PieChartModel())
            ->setTitle('Class Attendance Rate')
            ->setAnimated(true)
            ->withDataLabels();
            
        // Example data - you'd replace this with real attendance tracking
        $present = 85; // 85% attendance rate
        $absent = 15;  // 15% absence rate
        
        $attendanceTrend->addSlice('Present', $present, '#16a34a')
            ->addSlice('Absent', $absent, '#dc2626');
            
        return view('livewire.dashboard.teacher-charts', [
            'classDistribution' => $classDistribution,
            'subjectPerformance' => $subjectPerformance,
            'attendanceTrend' => $attendanceTrend,
        ]);
    }
}
