<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Overdue Books Report</title>
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
            font-weight: bold;
        }
        .overdue {
            color: #d9534f;
        }
        .critical {
            background-color: #ffdddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ config('app.name') }}</div>
        <div class="report-title">Overdue Books Report</div>
        <div class="report-subtitle">
            Generated on: {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}
            @if(isset($dateRange))
                <br>Period: {{ $dateRange }}
            @endif
        </div>
    </div>

    <div class="summary">
        Total Overdue Items: {{ count($overdueLoans) }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Book Title</th>
                <th>Borrower</th>
                <th>Class/Department</th>
                <th>Checkout Date</th>
                <th>Due Date</th>
                <th>Days Overdue</th>
                <th>Fine</th>
            </tr>
        </thead>
        <tbody>
            @forelse($overdueLoans as $index => $loan)
                <tr class="{{ $loan->days_overdue > 30 ? 'critical' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $loan->book->title }}
                        @if($loan->book_copy && $loan->book_copy->copy_number)
                            <small>(Copy #{{ $loan->book_copy->copy_number }})</small>
                        @endif
                    </td>
                    <td>{{ $loan->user->name }}</td>
                    <td>
                        @if($loan->user->student)
                            {{ $loan->user->student->class->name ?? 'N/A' }}
                        @elseif($loan->user->staff)
                            {{ $loan->user->staff->department ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $loan->issue_date->format('M d, Y') }}</td>
                    <td>{{ $loan->due_date->format('M d, Y') }}</td>
                    <td class="overdue">{{ $loan->days_overdue }}</td>
                    <td>{{ number_format($loan->fine_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No overdue items found.</td>
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