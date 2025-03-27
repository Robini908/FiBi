<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\StudentRecord;
use App\User;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Illuminate\Support\Facades\DB;

class AdminCharts extends Component
{
    public $showEnrollmentTrends = true;
    public $showGenderDistribution = true;
    public $showUserRoles = true;
    
    public function render()
    {
        // Enrollment trends by year
        $enrollmentTrends = (new LineChartModel())
            ->setTitle('Student Enrollment Trends')
            ->setAnimated(true)
            ->withDataLabels();
            
        // Get enrollment by year
        $enrollmentByYear = StudentRecord::select(DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as count'))
            ->groupBy('year')
            ->orderBy('year')
            ->get();
            
        foreach ($enrollmentByYear as $record) {
            $enrollmentTrends->addPoint($record->year, $record->count);
        }
        
        // Gender distribution
        $genderDistribution = (new PieChartModel())
            ->setTitle('Student Gender Distribution')
            ->setAnimated(true)
            ->withDataLabels();
            
        $maleCount = User::whereHas('roles', function($q) { 
            $q->where('name', 'student'); 
        })->where('gender', 'male')->count();
        
        $femaleCount = User::whereHas('roles', function($q) { 
            $q->where('name', 'student'); 
        })->where('gender', 'female')->count();
        
        $genderDistribution->addSlice('Male', $maleCount, '#3b82f6')
            ->addSlice('Female', $femaleCount, '#ec4899');
            
        // User roles distribution
        $userRoles = (new ColumnChartModel())
            ->setTitle('User Roles Distribution')
            ->setAnimated(true)
            ->withDataLabels();
            
        $admins = User::whereHas('roles', function($q) { 
            $q->where('name', 'admin'); 
        })->count();
        
        $teachers = User::whereHas('roles', function($q) { 
            $q->where('name', 'teacher'); 
        })->count();
        
        $students = User::whereHas('roles', function($q) { 
            $q->where('name', 'student'); 
        })->count();
        
        $parents = User::whereHas('roles', function($q) { 
            $q->where('name', 'parent'); 
        })->count();
        
        $userRoles->addColumn('Admin', $admins, '#9333ea')
            ->addColumn('Teacher', $teachers, '#16a34a')
            ->addColumn('Student', $students, '#3b82f6')
            ->addColumn('Parent', $parents, '#f97316');
            
        return view('livewire.dashboard.admin-charts', [
            'enrollmentTrends' => $enrollmentTrends,
            'genderDistribution' => $genderDistribution,
            'userRoles' => $userRoles,
        ]);
    }
}
