<div>
    <!-- Student Rankings -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-medium text-gray-700">Student Attendance Rankings</h3>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission #</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($studentAttendanceData ?? [] as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $student['photo'] ?? asset('global_assets/images/user.png') }}" alt="{{ $student['name'] }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $student['name'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student['adm_no'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student['stats']['present_days'] ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student['stats']['absent_days'] ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student['stats']['late_days'] ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(isset($student['stats']['attendance_rate']))
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $student['stats']['attendance_rate'] >= 90 ? 'bg-green-100 text-green-800' : 
                                            ($student['stats']['attendance_rate'] >= 75 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $student['stats']['attendance_rate'] }}%
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <div class="flex flex-col items-center justify-center py-5">
                                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No student attendance data available</p>
                                        <p class="text-xs text-gray-400 mt-1">Select a class and section to view student attendance rankings</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Top Students Chart -->
    @if(isset($topStudents) && count($topStudents) > 0)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-medium text-gray-700">Top Attending Students</h3>
            </div>
            <div class="p-4">
                <div class="space-y-4">
                    @foreach($topStudents as $idx => $student)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $student['photo'] ?? asset('global_assets/images/user.png') }}" alt="{{ $student['name'] }}">
                            </div>
                            <div class="ml-4 flex-grow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $student['adm_no'] }}</div>
                                    </div>
                                    <div class="text-sm font-semibold 
                                        {{ $student['attendance_rate'] >= 90 ? 'text-green-600' : 
                                        ($student['attendance_rate'] >= 75 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $student['attendance_rate'] }}%
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                    <div class="h-2.5 rounded-full {{ $student['attendance_rate'] >= 90 ? 'bg-green-600' : 
                                        ($student['attendance_rate'] >= 75 ? 'bg-yellow-500' : 'bg-red-600') }}" 
                                        style="width: {{ $student['attendance_rate'] }}%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-xs text-gray-500">Present: {{ $student['present_days'] }}/{{ $student['total_days'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Student Ranking Chart -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Students by Attendance Rate</h3>
        <div class="h-80">
            @if(!empty($studentAttendanceData))
                <livewire:livewire-column-chart 
                    key="{{ $classId.$sectionId.now() }}-student-ranking"
                    :column-chart-model="$this->getStudentRankingChartModel()" 
                />
            @else
                <div class="flex items-center justify-center h-full">
                    <p class="text-gray-500">No student data available</p>
                </div>
            @endif
        </div>
    </div>
</div> 