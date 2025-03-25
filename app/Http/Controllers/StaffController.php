<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Models\StaffRecord;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of staff members.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return redirect()->route('staff.index');
    }
    
    /**
     * Display the specified staff member.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return redirect()->route('staff.show', ['id' => $id]);
    }
    
    /**
     * Display staff qualifications.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function qualifications($id)
    {
        return redirect()->route('staff.qualifications', ['id' => $id]);
    }

    /**
     * Download a staff list report in CSV format.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadReport(Request $request)
    {
        $fileName = 'staff_report_' . date('Y-m-d') . '.csv';
        
        // Get filtered staff based on request
        $query = StaffRecord::with(['user', 'user.roles']);
        
        if ($request->has('role') && $request->role) {
            $query->whereHas('user.roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $staff = $query->get();
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $columns = ['Staff ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Qualification', 'Employment Date'];
        
        $callback = function() use($staff, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($staff as $record) {
                $row = [
                    $record->code,
                    $record->user->name,
                    $record->user->email,
                    $record->user->phone,
                    $record->user->roles->first()->name ?? 'N/A',
                    $record->is_active ? 'Active' : 'Inactive',
                    $record->qualification ?? 'N/A',
                    $record->emp_date ?? 'N/A',
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
} 