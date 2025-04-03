<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Timetable - {{ $timetable->name }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .school-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .school-logo {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }
        
        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #2d6a4f;
            margin: 0;
        }
        
        .report-title {
            font-size: 16px;
            color: #40916c;
            margin: 5px 0;
        }
        
        .timetable-info {
            font-size: 12px;
            color: #40916c;
            margin: 5px 0;
            text-align: center;
        }
        
        .timetable-info-item {
            margin: 5px 0;
        }
        
        .export-date {
            font-size: 11px;
            color: #666;
            margin-top: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        th {
            background-color: #2d6a4f;
            color: white;
            padding: 8px 5px;
            text-align: center;
            border: 1px solid #1b4332;
            font-weight: bold;
        }
        
        th.time-column {
            background-color: #1b4332;
            text-align: left;
        }
        
        th.current-day {
            background-color: #40916c;
        }
        
        td {
            padding: 7px 5px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        
        td.time-slot {
            background-color: #f8fff8;
            border-right: 2px solid #d8f3dc;
            font-weight: bold;
            color: #2d6a4f;
        }
        
        .period-time {
            font-size: 9px;
            color: #666;
            font-weight: normal;
        }
        
        tr:nth-child(even) td:not(.time-slot) {
            background-color: #fafafa;
        }
        
        .class-entry {
            padding: 5px;
            margin-bottom: 3px;
        }
        
        .entry-subject {
            font-weight: bold;
            color: #2d6a4f;
            font-size: 10px;
            margin-bottom: 3px;
        }
        
        .entry-teacher {
            font-size: 9px;
            color: #40916c;
            margin-bottom: 3px;
        }
        
        .entry-room {
            font-size: 9px;
            color: #2d6a4f;
        }
        
        .break-slot {
            background-color: #ffedd5;
        }
        
        .break-slot .entry-subject {
            color: #d97706;
        }
        
        .break-slot .entry-teacher {
            color: #b45309;
        }
        
        .assembly-slot {
            background-color: #dbeafe;
        }
        
        .assembly-slot .entry-subject {
            color: #1d4ed8;
        }
        
        .assembly-slot .entry-teacher {
            color: #2563eb;
        }
        
        .prep-slot {
            background-color: #e0e7ff;
        }
        
        .prep-slot .entry-subject {
            color: #4338ca;
        }
        
        .prep-slot .entry-teacher {
            color: #4f46e5;
        }
        
        .weekend-slot {
            background-color: #fce7f3;
        }
        
        .weekend-slot .entry-subject {
            color: #be185d;
        }
        
        .weekend-slot .entry-teacher {
            color: #db2777;
        }
        
        .empty-slot {
            font-style: italic;
            text-align: center;
            color: #aaa;
            font-size: 9px;
            padding: 8px 0;
        }
        
        .footer {
            text-align: center;
            font-size: 9px;
            color: #666;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eaeaea;
        }
        
        .legend {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        .legend-item {
            display: inline-block;
            margin-right: 15px;
        }
        
        .legend-color {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 5px;
            vertical-align: middle;
        }
        
        .normal-class {
            background-color: #d8f3dc;
        }
        
        .break-class {
            background-color: #ffedd5;
        }
        
        .assembly-class {
            background-color: #dbeafe;
        }
        
        .prep-class {
            background-color: #e0e7ff;
        }
        
        .weekend-class {
            background-color: #fce7f3;
        }
        
        .legend-text {
            display: inline-block;
            vertical-align: middle;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="school-info">
            @if($schoolLogo)
            <img src="{{ $schoolLogo }}" alt="{{ $schoolName }}" class="school-logo">
            @endif
            <div>
                <h1 class="school-name">{{ $schoolName }}</h1>
                <h2 class="report-title">Timetable Schedule</h2>
            </div>
        </div>
        
        <div class="timetable-info">
            <div class="timetable-info-item">
                <strong>Timetable:</strong> {{ $timetable->name }}
            </div>
            
            <div class="timetable-info-item">
                <strong>Class:</strong> {{ $timetable->myClass->name ?? 'Not specified' }}
                @if($section)
                | <strong>Section:</strong> {{ $section->name }}
                @endif
            </div>
            
            <div class="timetable-info-item">
                <strong>Academic Year:</strong> {{ $timetable->academic_session }}
                | <strong>Term:</strong> {{ $timetable->academic_term }}
            </div>
            
            <div class="export-date">
                Generated on {{ now()->format('d M Y, h:i A') }}
            </div>
        </div>
    </div>
    
    <!-- Color Legend -->
    <div class="legend">
        <div class="legend-item">
            <div class="legend-color normal-class"></div>
            <span class="legend-text">Regular Classes</span>
        </div>
        <div class="legend-item">
            <div class="legend-color break-class"></div>
            <span class="legend-text">Breaks & Lunch</span>
        </div>
        <div class="legend-item">
            <div class="legend-color assembly-class"></div>
            <span class="legend-text">Assembly</span>
        </div>
        <div class="legend-item">
            <div class="legend-color prep-class"></div>
            <span class="legend-text">Prep & Study</span>
        </div>
        <div class="legend-item">
            <div class="legend-color weekend-class"></div>
            <span class="legend-text">Weekend</span>
        </div>
    </div>
    
    <!-- The Timetable -->
    <table>
        <thead>
            <tr>
                <th class="time-column">Time Slot</th>
                @foreach($visibleDays as $day)
                    <th>{{ ucfirst($day) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($periods as $period)
                <tr>
                    <td class="time-slot">
                        {{ $period->period_name }}
                        <div class="period-time">
                            {{ date('h:i A', strtotime($period->start_time)) }} - {{ date('h:i A', strtotime($period->end_time)) }}
                        </div>
                    </td>
                    
                    @foreach($visibleDays as $day)
                        @php
                            $entry = $timetableMatrix[$day][$period->id] ?? null;
                            $slotClass = '';
                            
                            if ($entry && $entry->subject) {
                                $subjectName = strtolower($entry->subject->subject_name ?? '');
                                
                                if (str_contains($subjectName, 'break') || str_contains($subjectName, 'lunch') || str_contains($subjectName, 'recess')) {
                                    $slotClass = 'break-slot';
                                } elseif (str_contains($subjectName, 'assembly') || str_contains($subjectName, 'homeroom')) {
                                    $slotClass = 'assembly-slot';
                                } elseif (str_contains($subjectName, 'prep') || str_contains($subjectName, 'study')) {
                                    $slotClass = 'prep-slot';
                                } elseif (in_array($day, ['saturday', 'sunday'])) {
                                    $slotClass = 'weekend-slot';
                                }
                            } elseif (in_array($day, ['saturday', 'sunday'])) {
                                $slotClass = 'weekend-slot';
                            }
                            
                            // Also check period name
                            $periodName = strtolower($period->period_name ?? '');
                            if (!$slotClass) {
                                if (str_contains($periodName, 'break') || str_contains($periodName, 'lunch') || str_contains($periodName, 'recess')) {
                                    $slotClass = 'break-slot';
                                } elseif (str_contains($periodName, 'assembly') || str_contains($periodName, 'homeroom')) {
                                    $slotClass = 'assembly-slot';
                                } elseif (str_contains($periodName, 'prep') || str_contains($periodName, 'study')) {
                                    $slotClass = 'prep-slot';
                                }
                            }
                        @endphp
                        
                        <td class="{{ $slotClass }}">
                            @if($entry)
                                <div class="class-entry">
                                    <div class="entry-subject">{{ $entry->subject->subject_name ?? 'No Subject' }}</div>
                                    <div class="entry-teacher">{{ $entry->teacher->name ?? 'Not Assigned' }}</div>
                                    @if($entry->classroom)
                                        <div class="entry-room">{{ $entry->classroom }}</div>
                                    @endif
                                </div>
                            @else
                                <div class="empty-slot">No class scheduled</div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>This timetable is subject to change. Please check for updates regularly.</p>
        <p>Generated from {{ config('app.name', 'School Management System') }} on {{ now()->format('d M Y, h:i A') }}</p>
    </div>
</body>
</html> 