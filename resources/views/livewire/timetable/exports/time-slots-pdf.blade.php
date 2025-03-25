<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Time Slots - {{ $schoolName }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
            background-color: #f8fff8;
            padding: 20px;
            border-radius: 5px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2d6a4f;
            margin: 0;
        }
        
        .report-title {
            font-size: 18px;
            color: #40916c;
            margin: 5px 0;
        }
        
        .timetable-info {
            font-size: 14px;
            color: #40916c;
            margin: 5px 0;
        }
        
        .export-date {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 5px;
        }
        
        .weekday-title {
            background-color: #d8f3dc;
            color: #2d6a4f;
            border-left: 5px solid #40916c;
        }
        
        .weekend-title {
            background-color: #dfe7fd;
            color: #364fc7;
            border-left: 5px solid #3b5bdb;
        }
        
        .prep-title {
            background-color: #fff3bf;
            color: #805b10;
            border-left: 5px solid #d97706;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 5px;
            overflow: hidden;
        }
        
        th {
            background-color: #f5f5f5;
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            color: #2d6a4f;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .even-row {
            background-color: #f9f9f9;
        }
        
        .duration-cell {
            text-align: center;
        }
        
        .type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            color: #fff;
            text-align: center;
            font-weight: bold;
        }
        
        .lesson-badge {
            background-color: #2d6a4f;
        }
        
        .break-badge {
            background-color: #4361ee;
        }
        
        .lunch-badge {
            background-color: #f59e0b;
        }
        
        .transition-badge {
            background-color: #6b7280;
        }
        
        .activities-badge {
            background-color: #8b5cf6;
        }
        
        .weekend-badge {
            background-color: #4f46e5;
        }
        
        .prep-badge {
            background-color: #d97706;
        }
        
        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }
        
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .legend-text {
            font-size: 10px;
            color: #495057;
        }
        
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-style: italic;
        }
        
        .summary-box {
            background-color: #f8fff8;
            border: 1px solid #d1e7dd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #2d6a4f;
            margin-bottom: 5px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dashed #e9ecef;
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            font-size: 12px;
            color: #495057;
        }
        
        .summary-value {
            font-size: 12px;
            font-weight: bold;
            color: #2d6a4f;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1 class="school-name">{{ $schoolName }}</h1>
        <h2 class="report-title">Timetable - Time Slots Report</h2>
        <p class="timetable-info">
            <strong>Timetable:</strong> {{ $timetable->name ?? 'N/A' }} | 
            <strong>Academic Year:</strong> {{ $timetable->academic_year ?? 'N/A' }}
            @if(!empty($timetable->term))
            | <strong>Term:</strong> {{ $timetable->term }}
            @endif
        </p>
        <p class="export-date">Generated on: {{ $exportDate }}</p>
    </div>
    
    <!-- Summary Statistics -->
    <div class="summary-box">
        <div class="summary-title">Time Slots Summary</div>
        <div class="summary-item">
            <span class="summary-label">Total Time Slots:</span>
            <span class="summary-value">{{ $timeSlots->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Weekday Periods:</span>
            <span class="summary-value">{{ $weekdayTimeSlots->count() }}</span>
        </div>
        @if($hasWeekendSlots)
        <div class="summary-item">
            <span class="summary-label">Weekend Periods:</span>
            <span class="summary-value">{{ $weekendTimeSlots->count() }}</span>
        </div>
        @endif
        @if($hasPrepSlots)
        <div class="summary-item">
            <span class="summary-label">Prep Periods:</span>
            <span class="summary-value">{{ $prepTimeSlots->count() }}</span>
        </div>
        @endif
    </div>
    
    <!-- Legend for period types -->
    <div class="legend">
        <div class="legend-item">
            <div class="legend-color" style="background-color: #2d6a4f;"></div>
            <span class="legend-text">Lesson</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #4361ee;"></div>
            <span class="legend-text">Break</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #f59e0b;"></div>
            <span class="legend-text">Lunch</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #6b7280;"></div>
            <span class="legend-text">Transition</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #8b5cf6;"></div>
            <span class="legend-text">Activities</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #4f46e5;"></div>
            <span class="legend-text">Weekend</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #d97706;"></div>
            <span class="legend-text">Prep</span>
        </div>
    </div>
    
    <!-- Weekday Time Slots -->
    <div class="section">
        <h3 class="section-title weekday-title">Weekday Periods ({{ $weekdayTimeSlots->count() }})</h3>
        <table>
            <thead>
                <tr>
                    <th width="30%">Period Name</th>
                    <th width="25%">Start Time</th>
                    <th width="25%">End Time</th>
                    <th width="10%">Duration</th>
                    <th width="10%">Type</th>
                </tr>
            </thead>
            <tbody>
                @forelse($weekdayTimeSlots as $index => $timeSlot)
                    @php
                        $start = \Carbon\Carbon::parse($timeSlot->start_time);
                        $end = \Carbon\Carbon::parse($timeSlot->end_time);
                        $duration = $start->diffInMinutes($end);
                        $type = '';
                        $badgeClass = '';
                        
                        if(str_contains(strtolower($timeSlot->period_name), 'lunch')) {
                            $type = 'Lunch';
                            $badgeClass = 'lunch-badge';
                        } elseif(str_contains(strtolower($timeSlot->period_name), 'break')) {
                            $type = 'Break';
                            $badgeClass = 'break-badge';
                        } elseif(str_contains(strtolower($timeSlot->period_name), 'movement') || str_contains(strtolower($timeSlot->period_name), 'transition')) {
                            $type = 'Transition';
                            $badgeClass = 'transition-badge';
                        } elseif(str_contains(strtolower($timeSlot->period_name), 'games') || str_contains(strtolower($timeSlot->period_name), 'activities')) {
                            $type = 'Activities';
                            $badgeClass = 'activities-badge';
                        } else {
                            $type = 'Lesson';
                            $badgeClass = 'lesson-badge';
                        }
                    @endphp
                    <tr class="{{ $index % 2 == 0 ? '' : 'even-row' }}">
                        <td>{{ $timeSlot->period_name ?? 'No Name' }}</td>
                        <td>{{ date('h:i A', strtotime($timeSlot->start_time)) }}</td>
                        <td>{{ date('h:i A', strtotime($timeSlot->end_time)) }}</td>
                        <td class="duration-cell">{{ $duration }} mins</td>
                        <td><span class="type-badge {{ $badgeClass }}">{{ $type }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No weekday periods defined</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Weekend Time Slots -->
    @if($hasWeekendSlots)
    <div class="section">
        <h3 class="section-title weekend-title">Weekend Periods ({{ $weekendTimeSlots->count() }})</h3>
        <table>
            <thead>
                <tr>
                    <th width="30%">Period Name</th>
                    <th width="25%">Start Time</th>
                    <th width="25%">End Time</th>
                    <th width="10%">Duration</th>
                    <th width="10%">Day</th>
                </tr>
            </thead>
            <tbody>
                @foreach($weekendTimeSlots as $index => $timeSlot)
                    @php
                        $start = \Carbon\Carbon::parse($timeSlot->start_time);
                        $end = \Carbon\Carbon::parse($timeSlot->end_time);
                        $duration = $start->diffInMinutes($end);
                        
                        $day = 'Weekend';
                        if (str_contains(strtolower($timeSlot->period_name), 'saturday')) {
                            $day = 'Saturday';
                        } elseif (str_contains(strtolower($timeSlot->period_name), 'sunday')) {
                            $day = 'Sunday';
                        }
                    @endphp
                    <tr class="{{ $index % 2 == 0 ? '' : 'even-row' }}">
                        <td>{{ $timeSlot->period_name ?? 'No Name' }}</td>
                        <td>{{ date('h:i A', strtotime($timeSlot->start_time)) }}</td>
                        <td>{{ date('h:i A', strtotime($timeSlot->end_time)) }}</td>
                        <td class="duration-cell">{{ $duration }} mins</td>
                        <td><span class="type-badge weekend-badge">{{ $day }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- Prep Time Slots -->
    @if($hasPrepSlots)
    <div class="section">
        <h3 class="section-title prep-title">Prep Periods ({{ $prepTimeSlots->count() }})</h3>
        <table>
            <thead>
                <tr>
                    <th width="30%">Period Name</th>
                    <th width="25%">Start Time</th>
                    <th width="25%">End Time</th>
                    <th width="10%">Duration</th>
                    <th width="10%">Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prepTimeSlots as $index => $timeSlot)
                    @php
                        $start = \Carbon\Carbon::parse($timeSlot->start_time);
                        $end = \Carbon\Carbon::parse($timeSlot->end_time);
                        $duration = $start->diffInMinutes($end);
                        
                        $prepType = 'Prep';
                        if (str_contains(strtolower($timeSlot->period_name), 'morning')) {
                            $prepType = 'Morning';
                        } elseif (str_contains(strtolower($timeSlot->period_name), 'evening')) {
                            $prepType = 'Evening';
                        }
                    @endphp
                    <tr class="{{ $index % 2 == 0 ? '' : 'even-row' }}">
                        <td>{{ $timeSlot->period_name ?? 'No Name' }}</td>
                        <td>{{ date('h:i A', strtotime($timeSlot->start_time)) }}</td>
                        <td>{{ date('h:i A', strtotime($timeSlot->end_time)) }}</td>
                        <td class="duration-cell">{{ $duration }} mins</td>
                        <td><span class="type-badge prep-badge">{{ $prepType }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <div class="footer">
        <p>{{ $schoolName }} | {{ $timetable->name ?? 'School Timetable' }} - Time Slots | Page 1</p>
        <p>This document is for official use and was generated from the school management system.</p>
    </div>
</body>
</html> 