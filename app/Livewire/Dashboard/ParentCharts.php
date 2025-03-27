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

class ParentCharts extends Component
{
    public $showChildrenPerformance = true;
    public $showFeePaymentStatus = true;
    public $showAttendanceRecord = true;
    
    public function render()
    {
        $parent = Auth::user();
        
        // Children's academic performance comparison
        $childrenPerformance = (new ColumnChartModel())
            ->setTitle('My Children\'s Academic Performance')
            ->setAnimated(true)
            ->withDataLabels();
            
        // Get the parent's children
        $children = StudentRecord::where('parent_id_no', $parent->id)->with('user')->get();
        
        // For each child, add their average scores
        // This is simplified - in a real app, you'd fetch actual exam scores
        $subjects = ['Math', 'English', 'Science', 'History'];
        $colors = ['#3b82f6', '#16a34a', '#f97316', '#dc2626'];
        
        foreach ($children as $index => $child) {
            // Simulate scores for demonstration - replace with actual data
            $avgScore = rand(60, 95); // Random score between 60-95
            $childrenPerformance->addColumn($child->user->name, $avgScore, $colors[$index % count($colors)]);
        }
        
        // Fee payment status
        $feePaymentStatus = (new PieChartModel())
            ->setTitle('Fee Payment Status')
            ->setAnimated(true)
            ->withDataLabels();
            
        // Example data - replace with actual fee payment data
        $feePaymentStatus->addSlice('Paid', 75, '#16a34a')
            ->addSlice('Pending', 25, '#f97316');
            
        // Attendance record chart
        $attendanceRecord = (new LineChartModel())
            ->setTitle('Children\'s Attendance Trend')
            ->setAnimated(true)
            ->withDataLabels();
            
        // Example data for attendance over months
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $attendanceData = [95, 92, 88, 90, 94, 96]; // Percentage attendance
        
        foreach ($months as $index => $month) {
            $attendanceRecord->addPoint($month, $attendanceData[$index]);
        }
        
        return view('livewire.dashboard.parent-charts', [
            'childrenPerformance' => $childrenPerformance,
            'feePaymentStatus' => $feePaymentStatus,
            'attendanceRecord' => $attendanceRecord,
        ]);
    }
}
