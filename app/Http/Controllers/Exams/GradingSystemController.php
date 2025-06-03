<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Models\GradingSystem;
use Illuminate\Http\Request;

class GradingSystemController extends Controller
{
    /**
     * Display the specified grading system details.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\View\View
     */
    public function show(GradingSystem $gradingSystem)
    {
        // Load the grading ranges relationship
        $gradingSystem->load('gradingRanges');
        
        return view('exams.grading-systems.show', compact('gradingSystem'));
    }
    
    /**
     * Display a listing of the grading systems.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('exams.grading-systems.index');
    }
} 