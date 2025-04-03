<?php

namespace App\Http\Controllers;

use App\Models\SchoolTimetable;
use App\Models\TimetablePeriod;
use App\Models\TimetableSchedule;
use App\Models\Section;
use App\Exports\TimetableExport;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class TimetableExportController extends Controller
{
    /**
     * Export timetable as PDF
     *
     * @param  int  $timetableId
     * @param  int|null  $sectionId
     * @return \Illuminate\Http\Response
     */
    public function exportPdf($timetableId, $sectionId = null)
    {
        try {
            // Log the export attempt
            Log::info("PDF export requested for timetable ID: {$timetableId}, section ID: {$sectionId}");
            
            // Get the data needed for the PDF
            $data = $this->getTimetableData($timetableId, $sectionId);
            
            // Generate the PDF using barryvdh/laravel-dompdf
            $dompdfOptions = new \Dompdf\Options();
            $dompdfOptions->set('isHtml5ParserEnabled', true);
            $dompdfOptions->set('isRemoteEnabled', true);
            
            // Create direct Dompdf instance for more control
            $dompdf = new \Dompdf\Dompdf($dompdfOptions);
            $html = view('livewire.timetable.exports.timetable-pdf', $data)->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('a4', 'landscape');
            $dompdf->render();
            
            // Generate filename
            $filename = $this->generateExportFilename($data['timetable'], 'pdf');
            
            Log::info("PDF export successful for {$filename}");
            
            // Return as downloadable PDF
            return response($dompdf->output())
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            Log::error('Error exporting timetable to PDF: ' . $e->getMessage(), [
                'timetable_id' => $timetableId,
                'section_id' => $sectionId,
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Failed to generate PDF: ' . $e->getMessage()], 500);
            }
            
            // For standard browser requests, redirect back with error
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export timetable as Excel
     *
     * @param  int  $timetableId
     * @param  int|null  $sectionId
     * @return \Illuminate\Http\Response
     */
    public function exportExcel($timetableId, $sectionId = null)
    {
        try {
            // Log the export attempt
            Log::info("Excel export requested for timetable ID: {$timetableId}, section ID: {$sectionId}");
            
            // Get the data needed for the export
            $data = $this->getTimetableData($timetableId, $sectionId);
            
            // Generate filename
            $filename = $this->generateExportFilename($data['timetable'], 'xlsx');
            
            Log::info("Excel export successful for {$filename}");
            
            return Excel::download(new TimetableExport($data), $filename);
        } catch (\Exception $e) {
            Log::error('Error exporting timetable to Excel: ' . $e->getMessage(), [
                'timetable_id' => $timetableId,
                'section_id' => $sectionId,
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Failed to generate Excel file: ' . $e->getMessage()], 500);
            }
            
            // For standard browser requests, redirect back with error
            return redirect()->back()->with('error', 'Failed to generate Excel file: ' . $e->getMessage());
        }
    }

    /**
     * Get all data needed for the exports
     *
     * @param  int  $timetableId
     * @param  int|null  $sectionId
     * @return array
     */
    private function getTimetableData($timetableId, $sectionId = null)
    {
        Log::debug("Getting timetable data for timetable ID: {$timetableId}, section ID: {$sectionId}");
        
        // Get the timetable
        $timetable = SchoolTimetable::with('myClass')->findOrFail($timetableId);
        Log::debug("Found timetable: " . $timetable->name);
        
        // Get the section if provided
        $section = null;
        if ($sectionId) {
            $section = Section::findOrFail($sectionId);
            Log::debug("Found section: " . $section->name);
        }
        
        // Get periods ordered by start time
        $periods = TimetablePeriod::where('timetable_id', $timetableId)
            ->orderBy('start_time', 'asc')
            ->orderBy('period_order', 'asc')
            ->get();
        
        // Get all schedules for this timetable
        $schedules = TimetableSchedule::where('timetable_id', $timetableId)
            ->with(['subject', 'teacher', 'period'])
            ->get();
        
        // Days of the week to include
        $visibleDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        
        // Check if we have weekend entries
        $weekendEntries = $schedules->filter(function ($entry) {
            return in_array($entry->weekday, ['saturday', 'sunday']);
        });
        
        if ($weekendEntries->count() > 0) {
            $visibleDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        }
        
        // Build the timetable matrix
        $timetableMatrix = [];
        foreach ($visibleDays as $day) {
            $timetableMatrix[$day] = [];
            
            foreach ($periods as $period) {
                // Find entry for this day and period
                $entry = $schedules->first(function ($entry) use ($day, $period) {
                    return $entry->weekday === $day && $entry->period_id === $period->id;
                });
                
                $timetableMatrix[$day][$period->id] = $entry;
            }
        }
        
        // Get school settings
        $schoolName = Setting::where('key', 'school_name')->first()->value ?? 'School';
        $schoolLogo = Setting::where('key', 'school_logo')->first()->value ?? null;
        
        // Return all the data
        return [
            'timetable' => $timetable,
            'section' => $section,
            'periods' => $periods,
            'schedules' => $schedules,
            'visibleDays' => $visibleDays,
            'timetableMatrix' => $timetableMatrix,
            'schoolName' => $schoolName,
            'schoolLogo' => $schoolLogo
        ];
    }

    /**
     * Generate export filename
     *
     * @param  \App\Models\SchoolTimetable  $timetable
     * @param  string  $extension
     * @return string
     */
    private function generateExportFilename($timetable, $extension)
    {
        $className = optional($timetable->myClass)->name ?? 'Class';
        $session = $timetable->academic_session ?? date('Y');
        $term = $timetable->academic_term ?? 'Term';
        
        return "Timetable-{$className}-{$session}-{$term}." . $extension;
    }

    /**
     * Display print version of timetable
     *
     * @param  int  $timetableId
     * @param  int|null  $sectionId
     * @return \Illuminate\Http\Response
     */
    public function printView($timetableId, $sectionId = null)
    {
        try {
            // Log the print view attempt
            Log::info("Print view requested for timetable ID: {$timetableId}, section ID: {$sectionId}");
            
            // Get the data needed for the print view
            $data = $this->getTimetableData($timetableId, $sectionId);
            
            Log::info("Print view successfully generated");
            
            return view('livewire.timetable.exports.timetable-print', $data);
        } catch (\Exception $e) {
            Log::error('Error displaying print view: ' . $e->getMessage(), [
                'timetable_id' => $timetableId,
                'section_id' => $sectionId,
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Failed to generate print view: ' . $e->getMessage()], 500);
            }
            
            // For standard browser requests, show an error page
            return response()->view('errors.custom', [
                'message' => 'Failed to generate timetable print view',
                'details' => $e->getMessage(),
                'back_url' => url()->previous()
            ], 500);
        }
    }
} 