<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consolidated Timetable - Print View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        /* Print-specific styles */
        @media print {
            body {
                padding: 0;
                font-size: 10px;
            }
            
            .no-print {
                display: none !important;
            }
            
            table {
                page-break-inside: avoid;
            }
            
            .page-break {
                page-break-after: always;
            }
            
            /* Ensure white background for better printing */
            * {
                background-color: white !important;
                color: black !important;
                border-color: #ddd !important;
            }
            
            /* Exception for conflicts */
            .conflict {
                background-color: #fee2e2 !important;
            }
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
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
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 8px;
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
        
        .period-name {
            font-weight: bold;
        }
        
        .period-time {
            color: #666;
        }
        
        .entry {
            padding: 5px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background-color: #fff;
        }
        
        .entry-subject {
            font-weight: bold;
        }
        
        .entry-details {
            font-size: 11px;
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
        
        .print-controls {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f0f9ff;
            border-radius: 5px;
            border: 1px solid #bae6fd;
        }
        
        button {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        button:hover {
            background-color: #2563eb;
        }
        
        .entity-header {
            margin-top: 30px;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f1f5f9;
            border-radius: 5px;
            border-left: 4px solid #4F46E5;
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
            border: 1px solid #ddd;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="print-controls no-print">
        <button onclick="window.print()">Print Timetable</button>
        <button onclick="window.close()">Close</button>
    </div>
    
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
            <div class="{{ !$loop->first ? 'page-break' : '' }}">
                <div class="entity-header">
                    <h3>{{ $teacherData['teacher']->name }}</h3>
                    <span>Teacher ID: {{ $teacherId }}</span>
                </div>
                
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
                                    
                                    <td class="{{ $hasConflict ? 'conflict' : '' }}">
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
            <div class="{{ !$loop->first ? 'page-break' : '' }}">
                <div class="entity-header">
                    <h3>{{ $classData['class']->name }}</h3>
                    <span>Class ID: {{ $classId }}
                    @if(!empty($classData['class']->section))
                        | Section: {{ $classData['class']->section->name }}
                    @endif
                    </span>
                </div>
                
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
                                    
                                    <td class="{{ $hasConflict ? 'conflict' : '' }}">
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
            <div class="{{ !$loop->first ? 'page-break' : '' }}">
                <div class="entity-header">
                    <h3>{{ $subjectData['subject']->subject_name }}</h3>
                    <span>Subject Code: {{ $subjectData['subject']->subject_code }} | ID: {{ $subjectId }}</span>
                </div>
                
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
                                    
                                    <td class="{{ $hasConflict ? 'conflict' : '' }}">
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
    
    <script>
        // Auto print when the page loads (optional)
        window.addEventListener('load', function() {
            // Uncomment to enable auto print
            // window.print();
        });
    </script>
</body>
</html> 