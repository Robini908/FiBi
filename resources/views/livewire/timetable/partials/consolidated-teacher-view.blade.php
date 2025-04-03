<div class="min-w-full">
    @if(empty($consolidatedData['consolidatedMatrix']))
        <div class="p-6 text-center text-gray-500">
            No teacher data available for the selected timetables.
        </div>
    @else
        <div class="bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Teacher
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Period
                            </th>
                            @foreach($consolidatedData['visibleDays'] as $day)
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ ucfirst($day) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($consolidatedData['consolidatedMatrix'] as $teacherId => $teacherData)
                            @foreach($consolidatedData['periods'] as $period)
                                <tr>
                                    @if($loop->first)
                                        <td rowspan="{{ count($consolidatedData['periods']) }}" class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 align-top border-r">
                                            <div class="font-bold">{{ $teacherData['teacher']->name }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $teacherId }}</div>
                                        </td>
                                    @endif
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 border-r">
                                        <div>{{ $period->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $period->start_time }} - {{ $period->end_time }}</div>
                                    </td>
                                    @foreach($consolidatedData['visibleDays'] as $day)
                                        @php
                                            $cellData = $teacherData['days'][$day][$period->id] ?? null;
                                            $hasEntries = $cellData && isset($cellData['entries']) && count($cellData['entries']) > 0;
                                            $hasConflict = $cellData && isset($cellData['has_conflict']) && $cellData['has_conflict'] && $consolidationOptions['highlight_conflicts'];
                                        @endphp
                                        <td class="px-2 py-2 whitespace-nowrap text-sm border text-gray-500 {{ $hasConflict ? 'bg-red-50' : '' }}">
                                            @if($hasEntries)
                                                <div class="space-y-1">
                                                    @foreach($cellData['entries'] as $entry)
                                                        <div class="p-1 rounded border {{ $hasConflict ? 'border-red-300 bg-red-100' : 'border-gray-200 bg-white' }}">
                                                            <div class="font-medium">
                                                                {{ optional($entry->subject)->subject_name ?? 'Unknown Subject' }}
                                                            </div>
                                                            <div class="text-xs mt-1">
                                                                Class: {{ optional(optional($entry->timetable)->myClass)->name ?? 'Unknown' }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif($consolidationOptions['include_empty_slots'])
                                                <div class="text-xs text-gray-400 text-center">
                                                    Empty
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr class="h-4 bg-gray-100">
                                <td colspan="{{ count($consolidatedData['visibleDays']) + 2 }}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div> 