<div>
    <div 
        x-data="{ 
            currentTime: '{{ date('H:i:s') }}',
            currentDay: '{{ strtolower(date('l')) }}',
            init() {
                this.startClock();
                this.checkCurrentPeriods();
                
                // Set up refresh interval for current period check
                setInterval(() => {
                    this.checkCurrentPeriods();
                }, 60000); // Check every minute
            },
            startClock() {
                setInterval(() => {
                    const now = new Date();
                    const hours = now.getHours().toString().padStart(2, '0');
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const seconds = now.getSeconds().toString().padStart(2, '0');
                    this.currentTime = `${hours}:${minutes}:${seconds}`;
                    this.$refs.clockDisplay.textContent = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                }, 1000);
            },
            checkCurrentPeriods() {
                // Tell Livewire to refresh the current periods data
                @this.call('refreshCurrentPeriods');
            },
            isPeriodActive(startTime, endTime) {
                const now = this.currentTime;
                return now >= startTime && now <= endTime;
            }
        }"
        class="bg-white rounded-xl shadow-sm overflow-hidden">
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
                        @if($filteredPeriods->count() > 0)
                        Schedule with {{ $filteredPeriods->count() }} time slots
                        @else
                        No time slots defined yet
                        @endif
                    </p>
                </div>
            </div>
            
            <div x-data="{ showExportOptions: false }" class="relative">
                <div class="flex items-center space-x-3">
                    <button 
                        wire:click="$refresh"
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-green-600 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-all duration-200">
                        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                    
                    <button 
                        wire:click="openAutoGenerateModal"
                        type="button" 
                        class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-all duration-200"
                    >
                        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Auto-Generate
                    </button>
                    
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
                    @foreach($visibleDays as $day)
                        @php
                            $isCurrentDay = strtolower(date('l')) == $day;
                            $activeClass = $activeDay === $day ? 'border-green-500 text-green-600' : ($isCurrentDay ? 'border-green-300 text-green-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300');
                            $dayName = ucfirst($day);
                        @endphp
                        <a 
                            href="#" 
                            wire:click.prevent="setActiveDay('{{ $day }}')"
                            class="group inline-flex items-center py-2.5 px-1 border-b-2 font-medium text-xs whitespace-nowrap {{ $activeClass }} transition-colors duration-200">
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
            @if($filteredPeriods->count() > 0)
                <!-- Current time indicator -->
                @php
                    $currentTimeData = $this->getCurrentTimeSlots();
                    $isCurrentDayVisible = in_array($currentTimeData['day'], $visibleDays);
                    $nextPeriod = $this->getNextPeriod();
                @endphp
                
                @if($isCurrentDayVisible && $currentTimeData['periods']->count() > 0)
                <div class="mb-4 p-3 bg-green-50 border border-green-100 rounded-lg shadow-sm" id="current-period-indicator" wire:key="current-period-{{ now()->timestamp }}">
                    <div class="flex items-center">
                        <div class="mr-3 flex-shrink-0 p-2 bg-green-100 rounded-full">
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-green-800">Current Period</h3>
                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                @foreach($currentTimeData['periods'] as $currentPeriod)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white text-green-800 border border-green-200">
                                        <span class="h-1.5 w-1.5 mr-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        {{ $currentPeriod->period_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="ml-auto text-xs text-green-600">
                            <span class="font-medium" x-ref="clockDisplay">{{ date('h:i A') }}</span>
                        </div>
                    </div>
                    
                    @if($nextPeriod)
                    <div class="mt-2 pt-2 border-t border-green-100 flex items-center">
                        <div class="mr-3 flex-shrink-0 p-1.5 bg-white rounded-full">
                            <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="text-xs text-green-700">
                            Next: <span class="font-medium">{{ $nextPeriod->period_name }}</span> at <span class="font-medium">{{ date('h:i A', strtotime($nextPeriod->start_time)) }}</span>
                            <span class="ml-1 text-green-500 text-xs">(in {{ \Carbon\Carbon::parse($nextPeriod->start_time)->diffForHumans(now(), ['parts' => 1, 'short' => true]) }})</span>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                <!-- Time filters -->
                <div class="mb-4 flex flex-wrap gap-2 items-center">
                    <button 
                        wire:click="toggleFilters"
                        class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        {{ $showFilters ? 'Hide Filters' : 'Show Filters' }}
                    </button>
                    
                    <button 
                        wire:click="openBulkAssignModal"
                        class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Bulk Assign
                    </button>
                    
                    <button 
                        wire:click="toggleWeekendDays" 
                        class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $showWeekendDays ? 'Hide Weekend Days' : 'Show Weekend Days' }}
                    </button>
                    
                    <div class="ml-auto">
                        <button 
                            wire:click="printTimetable" 
                            class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
                
                @if($showFilters)
                <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex flex-wrap items-center gap-3">
                        <div>
                            <label for="filter-subject" class="block text-xs font-medium text-gray-700 mb-1">Subject</label>
                            <select id="filter-subject" wire:model="filterSubject" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-xs">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="filter-teacher" class="block text-xs font-medium text-gray-700 mb-1">Teacher</label>
                            <select id="filter-teacher" wire:model="filterTeacher" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-xs">
                                <option value="">All Teachers</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="filter-day" class="block text-xs font-medium text-gray-700 mb-1">Day</label>
                            <select id="filter-day" wire:model="filterDay" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-xs">
                                <option value="">All Days</option>
                                @foreach($visibleDays as $day)
                                    <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mt-auto">
                            <button wire:click="resetFilters" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="overflow-x-auto shadow-sm rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-700">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-white uppercase tracking-wider sticky left-0 z-10 border-r border-green-600">
                                    Time Slot
                                </th>
                                @foreach($visibleDays as $day)
                                    @php
                                        $isCurrentDay = strtolower(date('l')) == $day;
                                        $headerClass = $isCurrentDay ? 'bg-green-800 text-white' : 'text-white';
                                    @endphp
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium uppercase tracking-wider {{ $headerClass }}">
                                        @if($isCurrentDay)
                                        <div class="flex justify-center items-center">
                                            <span class="flex h-2 w-2 mr-1.5">
                                                <span class="animate-ping absolute h-2 w-2 rounded-full bg-white opacity-75"></span>
                                                <span class="relative rounded-full h-2 w-2 bg-white"></span>
                                            </span>
                                            {{ ucfirst($day) }}
                                        </div>
                                        @else
                                            {{ ucfirst($day) }}
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($filteredPeriods as $timeSlot)
                                @php
                                    $typeClass = $this->getPeriodTypeClass($timeSlot);
                                    $canAssignClass = $this->canAssignClass($timeSlot);
                                    $periodStartTime = date('h:i A', strtotime($timeSlot->start_time));
                                    $periodEndTime = date('h:i A', strtotime($timeSlot->end_time));
                                    
                                    // Calculate duration for visual indicators
                                    $start = \Carbon\Carbon::parse($timeSlot->start_time);
                                    $end = \Carbon\Carbon::parse($timeSlot->end_time);
                                    $durationMinutes = $start->diffInMinutes($end);
                                    
                                    // Show longer periods with more height
                                    $rowHeight = 'h-32'; // Default
                                    if ($durationMinutes < 15) {
                                        $rowHeight = 'h-20'; // Short periods
                                    } elseif ($durationMinutes > 60) {
                                        $rowHeight = 'h-40'; // Long periods
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-100">
                                    <td class="px-3 py-3 whitespace-nowrap text-xs font-medium text-gray-900 border-r border-gray-200 bg-gray-50 sticky left-0 z-10">
                                        <div class="flex flex-col">
                                            <div class="flex items-center">
                                                <span class="font-medium text-sm text-green-700">{{ $timeSlot->period_name }}</span>
                                                @if($durationMinutes > 60)
                                                    <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                        {{ $durationMinutes }} min
                                                    </span>
                                                @elseif($durationMinutes < 15)
                                                    <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $durationMinutes }} min
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $periodStartTime }} - {{ $periodEndTime }}</span>
                                            
                                            <!-- Visual duration indicator -->
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ min(100, ($durationMinutes / 120) * 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($visibleDays as $day)
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
                                        <td class="p-2 text-sm border-b border-gray-100 {{ $isCurrentTimeSlot ? 'bg-green-50' : ($entry ? 'bg-white' : $typeClass) }} {{ $rowHeight }}"
                                            @if($canAssignClass)
                                            wire:click="openEntryModal('{{ $day }}', {{ $timeSlot->id }})"
                                            @endif>
                                            @if($entry)
                                                <div class="h-full p-2 rounded-lg border {{ $isCurrentTimeSlot ? 'bg-white border-green-300 shadow-sm ring-1 ring-green-100' : 'border-gray-200 hover:border-green-200' }} transition-all duration-200 cursor-pointer hover:shadow-sm">
                                                    <div class="flex items-center">
                                                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 text-green-700 mr-2 flex items-center justify-center">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                            </svg>
                                                        </span>
                                                        <div class="font-medium text-gray-900 truncate text-xs">{{ $entry->subject->subject_name ?? 'N/A' }}</div>
                                                        @if($isCurrentTimeSlot)
                                                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <span class="h-1.5 w-1.5 rounded-full bg-green-600 animate-pulse mr-1"></span>
                                                                Now
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="mt-2 pl-6 space-y-1">
                                                        @if(isset($entry->teacher))
                                                            <div class="flex items-center text-xs text-gray-600">
                                                                <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                                {{ $entry->teacher->name ?? 'N/A' }}
                                                            </div>
                                                        @endif
                                                        @if(isset($entry->classroom) && $entry->classroom)
                                                            <div class="flex items-center text-xs text-gray-600">
                                                                <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                                {{ $entry->classroom }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    @if(isset($entry->notes) && $entry->notes)
                                                        <div class="mt-2 pt-2 text-xs text-gray-500 border-t border-gray-100">
                                                            <p class="line-clamp-2">{{ $entry->notes }}</p>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="mt-2 flex justify-end">
                                                        <button wire:click.stop="copyEntry({{ $entry->id }})" 
                                                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium rounded text-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-gray-100 mr-1">
                                                            <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2z" />
                                                            </svg>
                                                            Copy
                                                        </button>
                                                        <button wire:click.stop="deleteEntry({{ $entry->id }})" 
                                                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium rounded text-red-600 hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-100">
                                                            <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                @if($canAssignClass)
                                                <div class="h-full flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:bg-green-50 rounded transition-colors duration-200 border-2 border-dashed border-gray-200 hover:border-green-200"
                                                     wire:click="openEntryModal('{{ $day }}', {{ $timeSlot->id }})">
                                                    <svg class="w-6 h-6 mb-1 text-gray-400 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    <span class="text-xs font-medium text-gray-500 hover:text-green-600">Add class</span>
                                                </div>
                                                @else
                                                <div class="h-full flex flex-col items-center justify-center">
                                                    <span class="text-xs text-gray-400 italic">{{ $timeSlot->period_name }}</span>
                                                    @if(str_contains(strtolower($timeSlot->period_name), 'break') || 
                                                        str_contains(strtolower($timeSlot->period_name), 'lunch'))
                                                        <svg class="w-6 h-6 mt-2 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @elseif(str_contains(strtolower($timeSlot->period_name), 'movement') || 
                                                            str_contains(strtolower($timeSlot->period_name), 'transition'))
                                                        <svg class="w-6 h-6 mt-2 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-6 h-6 mt-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                @endif
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Added: Legend for period types -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <h3 class="text-xs font-medium text-gray-700 mb-2">Timetable Legend</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                            Break Periods
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            Assembly/Homeroom
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                            Prep/Study Time
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                            Weekend Classes
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            Long Period (>60 min)
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Short Period (<15 min)
                        </span>
                    </div>
                </div>
            @else
                <!-- Empty state for timetable -->
                <div class="text-center py-12 px-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No time slots</h3>
                    <p class="mt-1 text-sm text-gray-500">You need to create time slots in the "Time Slots" tab first.</p>
                    <div class="mt-6">
                        <button 
                            wire:click="switchTab('time-slots')"
                            type="button" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Time Slots
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    @include('livewire.timetable.partials.entry-modal')
    @include('livewire.timetable.partials.bulk-assign-modal')
    @include('livewire.timetable.partials.auto-generate-modal')
    
    <!-- Debug element to show modal state -->
    <div class="text-xs text-gray-500 ml-2">
        Modal state: {{ $showAutoGenerateModal ? 'Showing' : 'Hidden' }}
    </div>
</div> 