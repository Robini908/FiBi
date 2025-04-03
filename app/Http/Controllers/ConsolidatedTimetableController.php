<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Exports\ConsolidatedTimetableExport;
use Maatwebsite\Excel\Facades\Excel;

class ConsolidatedTimetableController extends Controller
{
    /**
     * Export consolidated timetable as PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        try {
            $consolidationId = $request->query('id');
            
            if (!$consolidationId) {
                return response()->json(['error' => 'Missing consolidation ID parameter'], 400);
            }
            
            $consolidatedData = session('consolidated_timetable_' . $consolidationId);
            
            if (!$consolidatedData) {
                return response()->json(['error' => 'Consolidated timetable data not found'], 404);
            }
            
            // Configure Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            
            // Get HTML content from view
            $html = view('livewire.timetable.exports.consolidated-pdf', [
                'consolidatedData' => $consolidatedData
            ])->render();
            
            // Load HTML to Dompdf
            $dompdf->loadHtml($html);
            
            // Set paper size to landscape A4
            $dompdf->setPaper('A4', 'landscape');
            
            // Render PDF
            $dompdf->render();
            
            // Get the file name
            $groupBy = ucfirst($consolidatedData['group_by']);
            $fileName = "Consolidated_Timetable_{$groupBy}_" . date('Y-m-d_H-i-s') . '.pdf';
            
            // Return PDF as download
            return $dompdf->stream($fileName, ['Attachment' => true]);
            
        } catch (\Exception $e) {
            Log::error('Error exporting consolidated timetable to PDF: ' . $e->getMessage());
            
            // Return error based on request type
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to export as PDF: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to export as PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Export consolidated timetable as Excel
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        try {
            $consolidationId = $request->query('id');
            
            if (!$consolidationId) {
                return response()->json(['error' => 'Missing consolidation ID parameter'], 400);
            }
            
            $consolidatedData = session('consolidated_timetable_' . $consolidationId);
            
            if (!$consolidatedData) {
                return response()->json(['error' => 'Consolidated timetable data not found'], 404);
            }
            
            // Get the file name
            $groupBy = ucfirst($consolidatedData['group_by']);
            $fileName = "Consolidated_Timetable_{$groupBy}_" . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Export to Excel
            return Excel::download(new ConsolidatedTimetableExport($consolidatedData), $fileName);
            
        } catch (\Exception $e) {
            Log::error('Error exporting consolidated timetable to Excel: ' . $e->getMessage());
            
            // Return error based on request type
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to export as Excel: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to export as Excel: ' . $e->getMessage());
        }
    }
    
    /**
     * Show print view for consolidated timetable
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function printView(Request $request)
    {
        try {
            $consolidationId = $request->query('id');
            
            if (!$consolidationId) {
                return response()->json(['error' => 'Missing consolidation ID parameter'], 400);
            }
            
            $consolidatedData = session('consolidated_timetable_' . $consolidationId);
            
            if (!$consolidatedData) {
                return response()->json(['error' => 'Consolidated timetable data not found'], 404);
            }
            
            // Return print view
            return view('livewire.timetable.exports.consolidated-print', [
                'consolidatedData' => $consolidatedData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing print view for consolidated timetable: ' . $e->getMessage());
            
            return response()->view('errors.generic', [
                'error' => 'Failed to load print view: ' . $e->getMessage()
            ], 500);
        }
    }
} 