<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $exam->name }} - Exam Timetable</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 100px;
            height: auto;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }
        .address {
            font-size: 12px;
            margin-bottom: 5px;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .details {
            margin-bottom: 20px;
        }
        .details-item {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            font-size: 10px;
            text-align: center;
            margin-top: 20px;
            font-style: italic;
        }
        .instructions {
            font-size: 12px;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(isset($settings['system_logo']))
        <img src="{{ $settings['system_logo'] }}" alt="School Logo" class="logo">
        @endif
        <div class="school-name">{{ $settings['system_name'] ?? 'School Management System' }}</div>
        <div class="address">{{ $settings['address'] ?? '' }}</div>
        <div>{{ $settings['phone'] ?? '' }}</div>
    </div>

    <h1>{{ $exam->name }} Timetable</h1>
    
    <div class="details">
        <div class="details-item"><strong>Class:</strong> {{ $class->name }}{{ $section ? ' - ' . $section->name : '' }}</div>
        <div class="details-item"><strong>Academic Year:</strong> {{ $exam->year }}</div>
        <div class="details-item"><strong>Term:</strong> {{ $exam->term }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Date</th>
                <th>Time</th>
                <th>Duration</th>
                <th>Venue</th>
                <th>Invigilators</th>
            </tr>
        </thead>
        <tbody>
            @forelse($examRecords as $record)
            <tr>
                <td>{{ $record->subject->subject_name ?? 'Unknown Subject' }}</td>
                <td>{{ $record->date->format('D, d M Y') }}</td>
                <td>{{ $record->time_range }}</td>
                <td>{{ $record->duration_minutes }} mins</td>
                <td>{{ $record->venue }}</td>
                <td>
                    @if(is_array($record->invigilators))
                        @foreach($record->invigilators as $teacherId)
                            {{ $teachers[$teacherId]->name ?? 'Unknown' }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    @endif
                </td>
            </tr>
            @if($record->instructions)
            <tr>
                <td colspan="6" class="instructions">
                    <strong>Instructions for {{ $record->subject->subject_name ?? 'this exam' }}:</strong><br>
                    {!! nl2br(e($record->instructions)) !!}
                </td>
            </tr>
            @endif
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No exam records found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="instructions">
        <strong>General Instructions:</strong>
        <ol>
            <li>All students must be present at least 15 minutes before the exam starts.</li>
            <li>Students must bring their own stationery and materials as required.</li>
            <li>No electronic devices are allowed in the examination room unless specified.</li>
            <li>Any form of malpractice will result in serious disciplinary action.</li>
        </ol>
    </div>

    <div class="footer">
        <p>Generated on {{ date('Y-m-d H:i:s') }} | {{ $settings['system_name'] ?? 'School Management System' }}</p>
    </div>
</body>
</html> 