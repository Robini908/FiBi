<div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm" x-data="{ 
    initialized: false,
    selectedTab: @entangle('activeTab'),
    currentView: 'main',  /* Possible values: main, records, timeslots, timetable */
    previousView: [],
    transition: false,
    
    goToView(view) {
        this.transition = true;
        setTimeout(() => {
            this.previousView.push(this.currentView);
            this.currentView = view;
            this.transition = false;
        }, 300);
    },
    
    goBack() {
        if (this.previousView.length > 0) {
            this.transition = true;
            setTimeout(() => {
                this.currentView = this.previousView.pop();
                this.transition = false;
            }, 300);
        }
    },
    
    hasBackHistory() {
        return this.previousView.length > 0;
    }
}" x-init="setTimeout(() => initialized = true, 500)">

    <!-- Loading overlay -->
    <div x-show="$wire.isLoading" class="fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl shadow-xl flex items-center space-x-4">
            <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-lg font-medium text-gray-700">Loading...</span>
        </div>
    </div>

    <!-- Header -->
    <div class="relative bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Back button (shown only if there's history) -->
            <div class="flex items-center">
            <button 
                x-show="hasBackHistory()" 
                x-on:click="goBack()"
                    class="mr-3 p-2 rounded-full hover:bg-gray-100 transition-colors duration-200 focus:outline-none"
                type="button">
                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            
            <!-- Title that changes based on current view -->
                <h1 class="text-xl font-medium text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span x-text="
                    currentView === 'main' ? 'School Timetable Management' : 
                    currentView === 'records' ? 'Timetable Records' : 
                    currentView === 'timeslots' ? 'Time Slots' : 
                    'View Timetable'
                "></span>
            </h1>
            </div>
            
            <!-- Loading indicator -->
            <div class="flex items-center" wire:loading>
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-600">Loading...</span>
            </div>
        </div>
        
        <!-- Progress indicator -->
        <div class="mt-4 h-1 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-green-500 transition-all duration-500 rounded-full"
                 x-bind:style="{ 
                     width: currentView === 'main' ? '25%' : 
                            currentView === 'records' ? '50%' : 
                            currentView === 'timeslots' ? '75%' : 
                            '100%' 
                 }">
            </div>
        </div>
    </div>
    
    <!-- Card container with transitions -->
    <div class="relative overflow-hidden" 
         x-bind:class="{ 'h-0 opacity-0': transition, 'opacity-100': !transition }" 
         style="transition: opacity 300ms, height 300ms">
        
        <!-- Main Selection View -->
        <div x-show="currentView === 'main'" class="p-6 space-y-6 bg-gray-50">
            <div class="text-center mb-6">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h2 class="mt-4 text-xl font-medium text-gray-800">Manage Your School Timetables</h2>
                <p class="mt-2 text-gray-500">Select options below to get started</p>
            </div>
            
            <!-- Filter UI - Google-inspired compact card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-medium text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Options
                    </h3>
                    
                    <!-- Collapse/Expand button could be added here if needed -->
                </div>
                
                <div class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <!-- Class Selection -->
                        <div>
                            <label for="class" class="block text-xs font-medium text-gray-700 mb-1">Class</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                                <select id="class" wire:model.live="selectedClassId" class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500 text-xs py-2 bg-gray-50 hover:bg-white transition-colors duration-200">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Section Selection -->
                        <div x-bind:class="{'opacity-50': !$wire.selectedClassId}">
                            <label for="section" class="block text-xs font-medium text-gray-700 mb-1">Section</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                                <select id="section" wire:model.live="selectedSectionId" class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500 text-xs py-2 bg-gray-50 hover:bg-white transition-colors duration-200" @if(empty($sections)) disabled @endif>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
                        <!-- Term Selection -->  
                        <div>
                            <label for="term" class="block text-xs font-medium text-gray-700 mb-1">Term</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                                <select id="term" wire:model.live="selectedTerm" class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500 text-xs py-2 bg-gray-50 hover:bg-white transition-colors duration-200">
                            @foreach($terms as $term)
                                <option value="{{ $term }}">{{ $term }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                        <!-- Session Selection -->
                        <div>
                            <label for="session" class="block text-xs font-medium text-gray-700 mb-1">Session</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                                <select id="session" wire:model.live="selectedSession" class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500 text-xs py-2 bg-gray-50 hover:bg-white transition-colors duration-200">
                            @foreach($sessions as $session)
                                <option value="{{ $session }}">{{ $session }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
                    <!-- Timetable Selection - Full Width -->
            @if($selectedClassId)
                    <div class="mt-3">
                        <label for="timetable_record" class="block text-xs font-medium text-gray-700 mb-1">Timetable</label>
                        <div class="flex items-center space-x-3">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                                <select id="timetable_record" wire:model.live="selectedTimetableRecord" class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500 text-xs py-2 bg-gray-50 hover:bg-white transition-colors duration-200">
                        <option value="">Select Timetable</option>
                        @forelse($timetableRecords as $record)
                            <option value="{{ $record->id }}">{{ $record->name }} {{ $record->is_active ? '(Active)' : '' }}</option>
                        @empty
                            <option value="" disabled>No timetables available</option>
                        @endforelse
                    </select>
                </div>
                
                @if(empty($timetableRecords) || count($timetableRecords) === 0)
                            <button wire:click="createTimetableRecord" class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-colors duration-200">
                                <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                                Create Timetable
                    </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Action Cards -->
            <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2">
                <!-- Timetable Records Card -->
                <div class="bg-white rounded-xl border border-gray-100 hover:border-green-200 shadow-sm hover:shadow transition-all duration-300 overflow-hidden group">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors duration-300">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-base font-medium text-gray-800 mb-2">Timetable Records</h3>
                        <p class="text-gray-500 text-sm mb-5 h-12">Create and manage timetable records for your classes</p>
                        <button 
                            x-on:click="goToView('records'); $wire.switchTab('timetables')"
                            class="w-full mt-2 inline-flex justify-center items-center px-4 py-2.5 rounded-full text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!$wire.selectedClassId"
                        >
                            Manage Records
                        </button>
                    </div>
                </div>
                
                <!-- Time Slots Card -->
                <div class="bg-white rounded-xl border border-gray-100 hover:border-green-200 shadow-sm hover:shadow transition-all duration-300 overflow-hidden group">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors duration-300">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-medium text-gray-800 mb-2">Time Slots</h3>
                        <p class="text-gray-500 text-sm mb-5 h-12">Manage periods and time slots for selected timetable</p>
                        
                        @if($selectedTimetableRecord)
                        <div class="mb-2 text-xs text-green-600 font-medium">
                            <span>Selected: {{ collect($timetableRecords)->firstWhere('id', $selectedTimetableRecord)->name ?? 'Unknown' }}</span>
                        </div>
                        @endif
                        
                        <button 
                            x-on:click="goToView('timeslots'); $wire.switchTab('timeslots')"
                            class="w-full mt-2 inline-flex justify-center items-center px-4 py-2.5 rounded-full text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!$wire.selectedTimetableRecord"
                        >
                            Manage Time Slots
                        </button>
                    </div>
                </div>
                
                <!-- View Timetable Card -->
                <div class="bg-white rounded-xl border border-gray-100 hover:border-green-200 shadow-sm hover:shadow transition-all duration-300 overflow-hidden group">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors duration-300">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        </div>
                        <h3 class="text-base font-medium text-gray-800 mb-2">View Timetable</h3>
                        <p class="text-gray-500 text-sm mb-5 h-12">View and manage the complete timetable with entries</p>
                        
                        @if($selectedTimetableRecord)
                        <div class="mb-2 text-xs text-green-600 font-medium">
                            <span>Selected: {{ collect($timetableRecords)->firstWhere('id', $selectedTimetableRecord)->name ?? 'Unknown' }}</span>
                        </div>
                        @endif
                        
                        <button 
                            x-on:click="goToView('timetable'); $wire.switchTab('entries')"
                            class="w-full mt-2 inline-flex justify-center items-center px-4 py-2.5 rounded-full text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!$wire.selectedTimetableRecord"
                        >
                            View Timetable
                        </button>
                    </div>
                </div>
                
                <!-- Timetable Consolidator Card -->
                <div class="bg-white rounded-xl border border-gray-100 hover:border-green-200 shadow-sm hover:shadow transition-all duration-300 overflow-hidden group">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors duration-300">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-medium text-gray-800 mb-2">Timetable Consolidator</h3>
                        <p class="text-gray-500 text-sm mb-5 h-12">Combine multiple timetables into a unified view</p>
                        
                        <a href="{{ route('tt.consolidator') }}" class="w-full block text-center mt-2 px-4 py-2.5 rounded-full text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            Open Consolidator
                        </a>
                    </div>
                </div>
                
                <!-- Exam Timetable Card -->
                <div class="bg-white rounded-xl border border-gray-100 hover:border-green-200 shadow-sm hover:shadow transition-all duration-300 overflow-hidden group">
                    <div class="p-6">
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors duration-300">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-medium text-gray-800 mb-2">Exam Timetable</h3>
                        <p class="text-gray-500 text-sm mb-5 h-12">Create and manage exam schedules for selected class and exam</p>
                        
                        <a href="{{ route('exam.timetable') }}" class="w-full block text-center mt-2 px-4 py-2.5 rounded-full text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            Manage Exam Timetable
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Timetable Records View -->
        <div x-show="currentView === 'records'" class="p-6 bg-gray-50">
            @livewire('timetable.timetable-records', [
                'classId' => $selectedClassId,
                'term' => $selectedTerm,
                'session' => $selectedSession
            ], key('timetable-records-' . $selectedClassId . '-' . $selectedTerm . '-' . $selectedSession))
        </div>
        
        <!-- Time Slots View -->
        <div x-show="currentView === 'timeslots'" class="p-6 bg-gray-50">
            @livewire('timetable.time-slots', [
                'timetableRecordId' => $selectedTimetableRecord
            ], key('time-slots-' . $selectedTimetableRecord))
        </div>
        
        <!-- Timetable View -->
        <div x-show="currentView === 'timetable'" class="p-6 bg-gray-50">
            @livewire('timetable.timetable-view', [
                'timetableRecordId' => $selectedTimetableRecord,
                'sectionId' => $selectedSectionId
            ], key('timetable-view-' . $selectedTimetableRecord . '-' . $selectedSectionId))
        </div>
    </div>
</div> 