<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- Header section with modern styling -->
    <div class="px-5 py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0 border-b border-gray-100">
        <div class="flex items-center">
            <div class="bg-green-50 p-2 rounded-full mr-3">
                <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-medium text-gray-800">Timetable View</h2>
                <p class="text-xs text-gray-500">
                    @if(count($periods) > 0)
                    Schedule with {{ count($periods) }} time slots
                    @else
                    No time slots defined yet
                    @endif
                </p>
            </div>
        </div>
        
        <div x-data="{ showExportOptions: false }" class="relative">
            <div class="flex items-center space-x-3">
                <button 
                    @click="showExportOptions = !showExportOptions"
                    class="relative inline-flex items-center px-3 py-2 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                    <svg class="w-3.5 h-3.5 ml-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div class="origin-top-right absolute right-0 mt-10 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" 
                     x-show="showExportOptions"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     @click.away="showExportOptions = false"
                     style="display: none;">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-100" role="menuitem">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                            </svg>
                            Print Timetable
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-100" role="menuitem">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Export as PNG
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-100" role="menuitem">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export as PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Days of week tabs -->
    <div class="px-4 pt-3">
        <div class="border-b border-gray-200 overflow-x-auto">
            <nav class="-mb-px flex space-x-5 sm:space-x-7" aria-label="Tabs">
                @foreach($days as $day)
                    @php
                        $isCurrentDay = strtolower(date('l')) == $day;
                        $activeClass = $isCurrentDay ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
                        $dayName = ucfirst($day);
                    @endphp
                    <a href="#{{ $day }}" class="group inline-flex items-center py-2.5 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeClass }} transition-colors duration-200">
                        @if($isCurrentDay)
                            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-600 mr-2 transition-colors duration-200">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        @else
                            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 text-gray-500 mr-2 group-hover:bg-gray-200 transition-colors duration-200">
                                {{ substr($dayName, 0, 1) }}
                            </span>
                        @endif
                        {{ $dayName }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
    
    <!-- Timetable Content -->
    <div class="p-4">
        @if(count($periods) > 0)
            <div class="overflow-x-auto shadow-sm rounded-lg border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 z-10 bg-gray-50 border-r border-gray-200">
                                Time Slot
                            </th>
                            @foreach($days as $day)
                                <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ ucfirst($day) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($periods as $timeSlot)
                            <tr class="hover:bg-gray-50 transition-colors duration-100">
                                <td class="px-3 py-3 whitespace-nowrap text-xs font-medium text-gray-900 border-r border-gray-200 bg-gray-50 sticky left-0 z-10">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-sm text-green-700">{{ $timeSlot->formatted_time }}</span>
                                        <span class="text-xs text-gray-500">{{ date('h:i A', strtotime($timeSlot->start_time)) }} - {{ date('h:i A', strtotime($timeSlot->end_time)) }}</span>
                                        @if($timeSlot->period_name)
                                        <div class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $timeSlot->period_name }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                @foreach($days as $day)
                                    @php
                                        $entry = $timetableMatrix[$day][$timeSlot->id] ?? null;
                                        
                                        $isCurrentTimeSlot = false;
                                        if (strtolower(date('l')) == $day) {
                                            $currentTime = time();
                                            $startTime = strtotime($timeSlot->start_time);
                                            $endTime = strtotime($timeSlot->end_time);
                                            $isCurrentTimeSlot = $currentTime >= $startTime && $currentTime <= $endTime;
                                        }
                                    @endphp
                                    <td class="p-2 text-sm border-b border-gray-100 {{ $isCurrentTimeSlot ? 'bg-green-50' : ($entry ? 'bg-white' : 'bg-gray-50') }}"
                                        wire:click="openEntryModal('{{ $day }}', {{ $timeSlot->id }})">
                                        @if($entry)
                                            <div class="p-2 rounded-lg border {{ $isCurrentTimeSlot ? 'bg-white border-green-300 shadow-sm ring-1 ring-green-100' : 'border-gray-200 hover:border-green-200' }} transition-all duration-200 cursor-pointer hover:shadow-sm">
                                                <div class="flex items-center">
                                                    <span class="flex-shrink-0 w-4 h-4 rounded-sm bg-green-100 text-green-700 mr-2 flex items-center justify-center">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                        </svg>
                                                    </span>
                                                    <div class="font-medium text-gray-900 truncate text-xs">{{ $entry['subject']['subject_name'] ?? 'N/A' }}</div>
                                                    @if($isCurrentTimeSlot)
                                                        <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <span class="h-1 w-1 rounded-full bg-green-600 animate-pulse mr-1"></span>
                                                            Now
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <div class="mt-2 pl-6 space-y-1">
                                                    @if(isset($entry['teacher']))
                                                        <div class="flex items-center text-xs text-gray-600">
                                                            <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            {{ $entry['teacher']['name'] ?? 'N/A' }}
                                                        </div>
                                                    @endif
                                                    @if(isset($entry['classroom']) && $entry['classroom'])
                                                        <div class="flex items-center text-xs text-gray-600">
                                                            <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            {{ $entry['classroom'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                @if(isset($entry['notes']) && $entry['notes'])
                                                    <div class="mt-2 pt-2 text-xs text-gray-500 border-t border-gray-100">
                                                        <p class="line-clamp-2">{{ $entry['notes'] }}</p>
                                                    </div>
                                                @endif
                                                
                                                <div class="mt-2 flex justify-end">
                                                    <button wire:click.stop="deleteEntry({{ $entry['id'] }})" 
                                                            class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium rounded text-red-600 hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-100">
                                                        <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-32 flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:bg-green-50 rounded transition-colors duration-200 border-2 border-dashed border-gray-200 hover:border-green-200"
                                                 wire:click="openEntryModal('{{ $day }}', {{ $timeSlot->id }})">
                                                <svg class="w-6 h-6 mb-1 text-gray-400 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                <span class="text-xs font-medium text-gray-500 hover:text-green-600">Add class</span>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty state with Google Material style -->
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No time slots defined</h3>
                <p class="mt-1 text-sm text-gray-500">To build your timetable, add time slots first</p>
                <div class="mt-3">
                    <button 
                        wire:click="switchTab('timeslots')"
                        class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Go to Time Slots
                    </button>
                </div>
            </div>
        @endif
    </div>
</div> 