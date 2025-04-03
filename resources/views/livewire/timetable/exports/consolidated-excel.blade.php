<table>
    <thead>
        <tr>
            <th colspan="{{ count($visibleDays) + 2 }}">
                Consolidated Timetable - {{ $schoolName }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($visibleDays) + 2 }}">
                Grouped by: {{ ucfirst($group_by) }} | 
                Academic Sessions: {{ $timetables->pluck('academic_session')->unique()->join(', ') }} |
                Terms: {{ $timetables->pluck('academic_term')->unique()->join(', ') }}
            </th>
        </tr>
        <tr>
            <th>{{ ucfirst($group_by) }}</th>
            <th>Period</th>
            @foreach($visibleDays as $day)
                <th>{{ ucfirst($day) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @if($group_by === 'teacher')
            @foreach($consolidatedMatrix as $teacherId => $teacherData)
                @foreach($periods as $period)
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ count($periods) }}">{{ $teacherData['teacher']->name }}</td>
                        @endif
                        <td>{{ $period->name }} ({{ $period->start_time }} - {{ $period->end_time }})</td>
                        @foreach($visibleDays as $day)
                            @php
                                $cellData = $teacherData['days'][$day][$period->id] ?? null;
                                $hasEntries = $cellData && isset($cellData['entries']) && count($cellData['entries']) > 0;
                                $hasConflict = $cellData && isset($cellData['has_conflict']) && $cellData['has_conflict'];
                            @endphp
                            <td>
                                @if($hasEntries)
                                    @foreach($cellData['entries'] as $entry)
                                        {{ optional($entry->subject)->subject_name ?? 'Unknown Subject' }} 
                                        [{{ optional(optional($entry->timetable)->myClass)->name ?? 'Unknown' }}]
                                        {{ !$loop->last ? "\n" : "" }}
                                    @endforeach
                                @else
                                    Empty
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <td colspan="{{ count($visibleDays) + 2 }}">&nbsp;</td>
                </tr>
            @endforeach

        @elseif($group_by === 'class')
            @foreach($consolidatedMatrix as $classId => $classData)
                @foreach($periods as $period)
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ count($periods) }}">{{ $classData['class']->name }}</td>
                        @endif
                        <td>{{ $period->name }} ({{ $period->start_time }} - {{ $period->end_time }})</td>
                        @foreach($visibleDays as $day)
                            @php
                                $cellData = $classData['days'][$day][$period->id] ?? null;
                                $hasEntries = $cellData && isset($cellData['entries']) && count($cellData['entries']) > 0;
                                $hasConflict = $cellData && isset($cellData['has_conflict']) && $cellData['has_conflict'];
                            @endphp
                            <td>
                                @if($hasEntries)
                                    @foreach($cellData['entries'] as $entry)
                                        {{ optional($entry->subject)->subject_name ?? 'Unknown Subject' }} 
                                        [{{ optional($entry->teacher)->name ?? 'No Teacher' }}]
                                        {{ !$loop->last ? "\n" : "" }}
                                    @endforeach
                                @else
                                    Empty
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <td colspan="{{ count($visibleDays) + 2 }}">&nbsp;</td>
                </tr>
            @endforeach

        @elseif($group_by === 'subject')
            @foreach($consolidatedMatrix as $subjectId => $subjectData)
                @foreach($periods as $period)
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ count($periods) }}">{{ $subjectData['subject']->subject_name }} ({{ $subjectData['subject']->subject_code }})</td>
                        @endif
                        <td>{{ $period->name }} ({{ $period->start_time }} - {{ $period->end_time }})</td>
                        @foreach($visibleDays as $day)
                            @php
                                $cellData = $subjectData['days'][$day][$period->id] ?? null;
                                $hasEntries = $cellData && isset($cellData['entries']) && count($cellData['entries']) > 0;
                                $hasConflict = $cellData && isset($cellData['has_conflict']) && $cellData['has_conflict'];
                            @endphp
                            <td>
                                @if($hasEntries)
                                    @foreach($cellData['entries'] as $entry)
                                        {{ optional(optional($entry->timetable)->myClass)->name ?? 'Unknown Class' }} 
                                        [{{ optional($entry->teacher)->name ?? 'No Teacher' }}]
                                        {{ !$loop->last ? "\n" : "" }}
                                    @endforeach
                                @else
                                    Empty
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <td colspan="{{ count($visibleDays) + 2 }}">&nbsp;</td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="{{ count($visibleDays) + 2 }}">
                Generated on: {{ date('Y-m-d H:i:s') }}
            </td>
        </tr>
        <tr>
            <td colspan="{{ count($visibleDays) + 2 }}">
                Statistics: {{ $timetables->count() }} timetables, 
                {{ $periods->count() }} periods, 
                {{ $schedules->count() }} entries,
                {{ $conflicts }} conflicts
            </td>
        </tr>
    </tfoot>
</table> 