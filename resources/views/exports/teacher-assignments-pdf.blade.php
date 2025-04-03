<!DOCTYPE html>
<html>
@php
    use Illuminate\Support\Str;
@endphp
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Teacher Subject Assignments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details-item {
            display: inline-block;
            margin-right: 20px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            border: 1px solid #E5E7EB;
        }
        td {
            padding: 8px;
            font-size: 10px;
            border: 1px solid #E5E7EB;
        }
        tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .primary-yes {
            background-color: #FEF3C7;
            color: #92400E;
            text-align: center;
            font-weight: bold;
        }
        .primary-no {
            text-align: center;
        }
        .status-active {
            background-color: #DCFCE7;
            color: #166534;
            text-align: center;
            font-weight: bold;
        }
        .status-inactive {
            background-color: #F3F4F6;
            color: #4B5563;
            text-align: center;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #6B7280;
            margin-top: 30px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Teacher Subject Assignments Report</div>
        <div class="subtitle">Mbuku School Management System</div>
        <div class="subtitle">Generated on {{ date('F d, Y') }}</div>
    </div>
    
    <div class="details">
        <div class="details-item">Academic Year: {{ $academicYear ?? 'All' }}</div>
        <div class="details-item">Term: {{ $academicTerm ?? 'All' }}</div>
        <div class="details-item">Total Records: {{ $assignments->count() }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="15%">Teacher</th>
                <th width="15%">Subject</th>
                <th width="15%">Class</th>
                <th width="10%">Section</th>
                <th width="10%">Academic Year</th>
                <th width="8%">Term</th>
                <th width="8%">Primary</th>
                <th width="8%">Status</th>
                <th width="11%">Notes</th>
            </tr>
        </thead>
        <tbody>
            @if($assignments->count() > 0)
                @foreach($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->teacher->name ?? 'Unknown' }}</td>
                        <td>{{ $assignment->subject->subject_name ?? 'Unknown' }}</td>
                        <td>{{ $assignment->myClass->name ?? 'Unknown' }}</td>
                        <td>{{ $assignment->section->name ?? 'N/A' }}</td>
                        <td>{{ $assignment->academic_year_id }}</td>
                        <td>{{ $assignment->academic_term }}</td>
                        <td class="{{ $assignment->is_primary ? 'primary-yes' : 'primary-no' }}">
                            {{ $assignment->is_primary ? 'Yes' : 'No' }}
                        </td>
                        <td class="{{ $assignment->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $assignment->is_active ? 'Active' : 'Inactive' }}
                        </td>
                        <td>{{ Str::limit($assignment->notes, 30) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" style="text-align: center;">No assignments found</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <div class="footer">
        <p>This is an automatically generated report. Report generated on {{ date('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html> 