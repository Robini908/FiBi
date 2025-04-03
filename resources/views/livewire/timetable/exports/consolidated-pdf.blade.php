<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consolidated Timetable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-top: 20px;
        }
        
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .summary {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8px;
        }
        
        th {
            background-color: #4F46E5;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 5px 2px;
            font-size: 9px;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 5px 2px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .header-cell {
            font-weight: bold;
            background-color: #f1f5f9;
        }
        
        .entity-name {
            font-weight: bold;
            font-size: 9px;
        }
        
        .entity-details {
            font-size: 7px;
            color: #666;
        }
        
        .period-name {
            font-weight: bold;
        }
        
        .period-time {
            color: #666;
        }
        
        .entry {
            padding: 3px;
            margin-bottom: 2px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        .entry-subject {
            font-weight: bold;
        }
        
        .entry-details {
            font-size: 7px;
            color: #666;
        }
        
        .conflict {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
        }
        
        .empty {
            text-align: center;
            color: #aaa;
            font-style: italic;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #666;
            padding: 5px 0;
            border-top: 1px solid #ddd;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .stats {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
            gap: 10px;
            justify-content: center;
        }
        
        .stat-item {
            text-align: center;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 5px;
            min-width: 80px;
        }
        
        .stat-label {
            font-size: 9px;
            color: #666;
        }
        
        .stat-value {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Consolidated Timetable</div>
        <div class="subtitle">{{ $schoolName }}</div>
        <div>Generated on {{ date('F d, Y') }}</div>
    </div>
    
    <div class="summary">
        <div style="text-align: center; margin-bottom: 10px;">
            <strong>Grouped by:</strong> {{ ucfirst($group_by) }} | 
            <strong>Academic Sessions:</strong> {{ $timetables->pluck('academic_session')->unique()->join(', ') }} | 
            <strong>Terms:</strong> {{ $timetables->pluck('academic_term')->unique()->join(', ') }}
        </div>
        
        <div class="stats">
            <div class="stat-item">
                <div class="stat-label">Timetables</div>
                <div class="stat-value">{{ $timetables->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Classes</div>
                <div class="stat-value">{{ $timetables->pluck('myClass.name')->unique()->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Periods</div>
                <div class="stat-value">{{ $periods->count() }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Entries</div>
                <div class="stat-value">{{ $schedules->count() }}</div>
            </div>
            <div class="stat-item" style="{{ $conflicts > 0 ? 'background-color: #fee2e2; color: #b91c1c;' : '' }}">
                <div class="stat-label">Conflicts</div>
                <div class="stat-value">{{ $conflicts }}</div>
            </div>
        </div>
    </div>
    
    @if($group_by === 'teacher')
        <!-- Teacher-based Timetable -->
        @foreach($consolidatedMatrix as $teacherId => $teacherData)
            <div {{ !$loop->first ? 'class=page-break' : '' }}>
                <h3>{{ $teacherData['teacher']->name }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th width="15%">Period</th>
                            @foreach($visibleDays as $day)
                                <th>{{ ucfirst($day) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($periods as $period)
                            <tr>
                                <td class="header-cell">
                                    <div class="period-name">{{ $period->name }}</div>
                                    <div class="period-time">{{ $period->start_time }} - {{ $period->end_time }}</div>
                                </td>
                                
                                @foreach($visibleDays as $day)
                                    @php
                                        $cellData = $teacherData['days'][$day][$period->id] ?? null;
                                        $hasEntries = $cellData && isset($cellData['entries']) && count($cellData['entries']) > 0;
                                        $hasConflict = $cellData && isset($cellData['has_conflict']) && $cellData['has_conflict'];
                                    @endphp
                                    
                                    <td style="{{ $hasConflict ? 'background-color: #fee2e2;' : '' }}">
                                        @if($hasEntries)
                                            @foreach($cellData['entries'] as $entry)
                                                <div class="entry {{ $hasConflict ? 'conflict' : '' }}">
                                                    <div class="entry-subject">
                                                        {{ optional($entry->subject)->subject_name ?? 'Unknown Subject' }}
                                                    </div>
                                                    <div class="entry-details">
                                                        Class: {{ optional(optional($entry->timetable)->myClass)->name ?? 'Unknown' }}
                                                        <br>
                                                        {{ $entry->timetable->academic_session ?? '' }}/{{ $entry->timetable->academic_term ?? '' }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="empty">Empty</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
        
    @elseif($group_by === 'class')
        <!-- Class-based Timetable -->
        @foreach($consolidatedMatrix as $classId => $classData)
            <div {{ !$loop->first ? 'class=page-break' : '' }}>
                <h3>{{ $classData['class']->name }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th width="15%">Period</th>
                            @foreach($visibleDays as $day)
                                <th>{{ ucfirst($day) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($periods as $period)
                            <tr>
                                <td class="header-cell">
                                    <div class="period-name">{{ $period->name }}</div>
                                    <div class="period-time">{{ $period->start_time }} - {{ $period->end_time }}</div>
                                </td>
                                
                                @foreach($visibleDays as $day)
                                    @php
                                        $cellData = $classData['days'][$day][$period->id] ?? null;
                                        $hasEntries = $cellData && isset($cellData['entries']) && count($cellData['entries']) > 0;
                                        $hasConflict = $cellData && isset($cellData['has_conflict']) && $cellData['has_conflict'];
                                    @endphp
                                    
                                    <td style="{{ $hasConflict ? 'background-color: #fee2e2;' : '' }}">
                                        @if($hasEntries)
                                            @foreach($cellData['entries'] as $entry)
                                                <div class="entry {{ $hasConflict ? 'conflict' : '' }}">
                                                    <div class="entry-subject">
                                                        {{ optional($entry->subject)->subject_name ?? 'Unknown Subject' }}
                                                    </div>
                                                    <div class="entry-details">
                                                        Teacher: {{ optional($entry->teacher)->name ?? 'No Teacher' }}
                                                        <br>
                                                        {{ $entry->timetable->academic_session ?? '' }}/{{ $entry->timetable->academic_term ?? '' }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="empty">Empty</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
        
    @elseif($group_by === 'subject')
        <!-- Subject-based Timetable -->
        @foreach($consolidatedMatrix as $subjectId => $subjectData)
            <div {{ !$loop->first ? 'class=page-break' : '' }}>
                <h3>{{ $subjectData['subject']->subject_name }}</h3>
                <p>Subject Code: {{ $subjectData['subject']->subject_code }}</p>
                <table>
                    <thead>
                        <tr>
                            <th width="15%">Period</th>
                            @foreach($visibleDays as $day)
                                <th>{{ ucfirst($day) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($periods as $period)
                            <tr>
                                <td class="header-cell">
                                    <div class="period-name">{{ $period->name }}</div>
                                    <div class="period-time">{{ $period->start_time }} - {{ $period->end_time }}</div>
                                </td>
                                
                                @foreach($visibleDays as $day)
                                    @php
                                        $cellData = $subjectData['days'][$day][$period->id] ?? null;
                                        $hasEntries = $cellData && isset($cellData['entries']) && count($cellData['entries']) > 0;
                                        $hasConflict = $cellData && isset($cellData['has_conflict']) && $cellData['has_conflict'];
                                    @endphp
                                    
                                    <td style="{{ $hasConflict ? 'background-color: #fee2e2;' : '' }}">
                                        @if($hasEntries)
                                            @foreach($cellData['entries'] as $entry)
                                                <div class="entry {{ $hasConflict ? 'conflict' : '' }}">
                                                    <div class="entry-subject">
                                                        {{ optional(optional($entry->timetable)->myClass)->name ?? 'Unknown Class' }}
                                                    </div>
                                                    <div class="entry-details">
                                                        Teacher: {{ optional($entry->teacher)->name ?? 'No Teacher' }}
                                                        <br>
                                                        {{ $entry->timetable->academic_session ?? '' }}/{{ $entry->timetable->academic_term ?? '' }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="empty">Empty</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif
    
    <div class="footer">
        <p>Consolidated Timetable - Generated on {{ date('Y-m-d H:i:s') }} - {{ $schoolName }}</p>
    </div>
</body>
</html> 