<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Timetable - {{ $timetable->name }}</title>
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 0.5cm;
            }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .print-container {
            background-color: white;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            width: 80px;
            height: 80px;
            margin-right: 15px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2d6a4f;
            margin: 0;
        }
        
        .report-title {
            font-size: 20px;
            color: #40916c;
            margin: 5px 0;
        }
        
        .timetable-info {
            font-size: 14px;
            color: #40916c;
            margin: 5px 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        
        .timetable-info-item {
            display: flex;
            align-items: center;
        }
        
        .icon {
            width: 18px;
            height: 18px;
            margin-right: 6px;
        }
        
        .export-date {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        th {
            background-color: #2d6a4f;
            color: white;
            padding: 12px 8px;
            text-align: center;
            border: 1px solid #1b4332;
            font-weight: bold;
        }
        
        th.time-column {
            background-color: #1b4332;
            position: sticky;
            left: 0;
            z-index: 10;
            text-align: left;
        }
        
        th.current-day {
            background-color: #40916c;
        }
        
        td {
            padding: 10px 8px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        
        td.time-slot {
            background-color: #f8fff8;
            border-right: 2px solid #d8f3dc;
            font-weight: bold;
            color: #2d6a4f;
            position: sticky;
            left: 0;
            z-index: 5;
        }
        
        .period-time {
            font-size: 10px;
            color: #666;
            font-weight: normal;
        }
        
        tr:nth-child(even) td:not(.time-slot) {
            background-color: #fafafa;
        }
        
        .class-entry {
            padding: 8px;
            border-radius: 6px;
            margin-bottom: 4px;
            background-color: #d8f3dc;
            border-left: 3px solid #2d6a4f;
        }
        
        .entry-subject {
            font-weight: bold;
            color: #2d6a4f;
            font-size: 12px;
            margin-bottom: 4px;
        }
        
        .entry-teacher {
            font-size: 11px;
            color: #40916c;
            margin-bottom: 4px;
        }
        
        .entry-room {
            font-size: 10px;
            background-color: #fff;
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            color: #2d6a4f;
        }
        
        .break-slot .class-entry {
            background-color: #ffedd5;
            border-left: 3px solid #f59e0b;
        }
        
        .break-slot .entry-subject {
            color: #d97706;
        }
        
        .break-slot .entry-teacher {
            color: #b45309;
        }
        
        .assembly-slot .class-entry {
            background-color: #dbeafe;
            border-left: 3px solid #3b82f6;
        }
        
        .assembly-slot .entry-subject {
            color: #1d4ed8;
        }
        
        .assembly-slot .entry-teacher {
            color: #2563eb;
        }
        
        .prep-slot .class-entry {
            background-color: #e0e7ff;
            border-left: 3px solid #4f46e5;
        }
        
        .prep-slot .entry-subject {
            color: #4338ca;
        }
        
        .prep-slot .entry-teacher {
            color: #4f46e5;
        }
        
        .weekend-slot .class-entry {
            background-color: #fce7f3;
            border-left: 3px solid #db2777;
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
            font-size: 11px;
            padding: 12px 0;
        }
        
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eaeaea;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #2d6a4f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 14px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .print-button svg {
            margin-right: 8px;
            width: 18px;
            height: 18px;
        }
        
        .print-button:hover {
            background-color: #1b4332;
        }
        
        .metadata {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .timetable-details {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .detail-item {
            background-color: #f8fff8;
            border: 1px solid #d8f3dc;
            border-radius: 6px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #2d6a4f;
        }
        
        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }
        
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            margin-right: 8px;
        }
        
        .normal-class {
            background-color: #d8f3dc;
            border-left: 4px solid #2d6a4f;
        }
        
        .break-class {
            background-color: #ffedd5;
            border-left: 4px solid #f59e0b;
        }
        
        .assembly-class {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
        }
        
        .legend-text {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
        </svg>
        Print Timetable
    </button>
    
    <div class="print-container">
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
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span><strong>Timetable:</strong> {{ $timetable->name }}</span>
                </div>
                
                <div class="timetable-info-item">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span><strong>Class:</strong> {{ $timetable->myClass->name ?? 'Not specified' }}</span>
                </div>
                
                @if($section)
                <div class="timetable-info-item">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M2 15.5V12a4 4 0 018 0v3.5m0-3.5h4a4 4 0 110 8h-4v-8z" />
                    </svg>
                    <span><strong>Section:</strong> {{ $section->name }}</span>
                </div>
                @endif
                
                <div class="timetable-info-item">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span><strong>Academic Year:</strong> {{ $timetable->academic_session }}</span>
                </div>
                
                <div class="timetable-info-item">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span><strong>Term:</strong> {{ $timetable->academic_term }}</span>
                </div>
                
                <div class="timetable-info-item">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</span>
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
                <span class="legend-text">Assembly & Special Events</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #e0e7ff; border-left: 4px solid #4f46e5;"></div>
                <span class="legend-text">Prep & Study</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #fce7f3; border-left: 4px solid #db2777;"></div>
                <span class="legend-text">Weekend Classes</span>
            </div>
        </div>
        
        <!-- The Timetable -->
        <table>
            <thead>
                <tr>
                    <th class="time-column">Time Slot</th>
                    @foreach($visibleDays as $day)
                        @php
                            $isCurrentDay = strtolower(date('l')) == $day;
                            $headerClass = $isCurrentDay ? 'current-day' : '';
                        @endphp
                        <th class="{{ $headerClass }}">{{ ucfirst($day) }}</th>
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
    </div>
</body>
</html> 