<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Library Circulation Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .report-subtitle {
            font-size: 11pt;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            color: #777;
        }
        .page-number {
            text-align: right;
            font-size: 9pt;
        }
        .summary {
            margin: 20px 0;
        }
        .summary-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .summary-table {
            width: 50%;
            margin-bottom: 25px;
        }
        .status-active {
            color: #28a745;
        }
        .status-returned {
            color: #17a2b8;
        }
        .status-overdue {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ config('app.name') }}</div>
        <div class="report-title">Library Circulation Report</div>
        <div class="report-subtitle">
            Generated on: {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}
            @if(isset($dateStart) && isset($dateEnd))
                <br>Period: {{ \Carbon\Carbon::parse($dateStart)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($dateEnd)->format('M d, Y') }}
            @endif
        </div>
    </div>

    @if(isset($includeSummary) && $includeSummary)
    <div class="summary">
        <div class="summary-title">Circulation Summary</div>
        <table class="summary-table">
            <tr>
                <td>Total Transactions:</td>
                <td><strong>{{ count($loans) }}</strong></td>
            </tr>
            <tr>
                <td>Books Checked Out:</td>
                <td><strong>{{ $loans->where('status', 'Active')->count() + $loans->where('status', 'Overdue')->count() }}</strong></td>
            </tr>
            <tr>
                <td>Books Returned:</td>
                <td><strong>{{ $loans->where('status', 'Returned')->count() }}</strong></td>
            </tr>
            <tr>
                <td>Overdue Items:</td>
                <td><strong>{{ $loans->where('status', 'Overdue')->count() }}</strong></td>
            </tr>
            <tr>
                <td>Total Fines Collected:</td>
                <td><strong>{{ number_format($loans->where('is_fine_paid', true)->sum('fine_amount'), 2) }}</strong></td>
            </tr>
            <tr>
                <td>Outstanding Fines:</td>
                <td><strong>{{ number_format($loans->where('is_fine_paid', false)->where('fine_amount', '>', 0)->sum('fine_amount'), 2) }}</strong></td>
            </tr>
        </table>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Book Title</th>
                <th>Copy #</th>
                <th>Borrower</th>
                <th>Class/Department</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Status</th>
                <th>Fine</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $index => $loan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $loan->bookCopy->book->title ?? 'Unknown' }}
                        @if($loan->bookCopy->book->subtitle)
                            <br><small>{{ $loan->bookCopy->book->subtitle }}</small>
                        @endif
                    </td>
                    <td>{{ $loan->bookCopy->copy_number ?? 'N/A' }}</td>
                    <td>{{ $loan->borrower->name ?? 'Unknown' }}</td>
                    <td>
                        @if($loan->borrower->student)
                            {{ $loan->borrower->student->class->name ?? 'N/A' }}
                        @elseif($loan->borrower->staff)
                            {{ $loan->borrower->staff->department ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $loan->issue_date ? $loan->issue_date->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $loan->due_date ? $loan->due_date->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $loan->return_date ? $loan->return_date->format('M d, Y') : 'N/A' }}</td>
                    <td class="status-{{ strtolower($loan->status) }}">{{ $loan->status }}</td>
                    <td>
                        @if($loan->fine_amount > 0)
                            {{ number_format($loan->fine_amount, 2) }}
                            @if($loan->is_fine_paid)
                                <span style="color: #28a745">(Paid)</span>
                            @else
                                <span style="color: #dc3545">(Unpaid)</span>
                            @endif
                        @else
                            0.00
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No circulation records found for the selected period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>
            This report contains confidential information and is intended only for authorized personnel.
            <br>
            &copy; {{ date('Y') }} {{ config('app.name') }} - Library Management System
        </p>
    </div>

    <div class="page-number">
        Page 1 of 1
    </div>
</body>
</html> 