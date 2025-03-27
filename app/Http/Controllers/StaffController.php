<?php

namespace App\Http\Controllers;

use App\Models\StaffRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class StaffController extends Controller
{
    /**
     * Download a report of all staff
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadReport()
    {
        $staff = StaffRecord::with('user')->get();
        
        // Generate CSV data
        $csvData = [];
        $csvData[] = ['Name', 'Email', 'Phone', 'Gender', 'Role', 'Employment Date', 'Status'];
        
        foreach ($staff as $member) {
            $csvData[] = [
                $member->user->name ?? 'N/A',
                $member->user->email ?? 'N/A',
                $member->user->phone ?? 'N/A',
                $member->user->gender ?? 'N/A',
                $member->user->roles->first()->name ?? 'N/A',
                $member->emp_date ? date('Y-m-d', strtotime($member->emp_date)) : 'N/A',
                $member->is_active ? 'Active' : 'Inactive'
            ];
        }
        
        // Create CSV content
        $content = '';
        foreach ($csvData as $row) {
            $content .= implode(',', $row) . "\n";
        }
        
        // Create response with CSV content
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="staff_report.csv"',
        ];
        
        return Response::make($content, 200, $headers);
    }
} 