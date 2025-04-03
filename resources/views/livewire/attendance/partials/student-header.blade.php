<div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-gray-50 px-4 py-4 sm:px-6">
    <div class="flex flex-wrap items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Attendance History</h3>
            @if($student)
                <div class="mt-1 flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $student->user->photo ?? asset('global_assets/images/user.png') }}" alt="{{ $student->name }}">
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                        <div class="text-sm text-gray-500">{{ $student->adm_no }} | {{ $student->my_class->name }} {{ $student->section->name }}</div>
                    </div>
                </div>
            @else
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="class-selector" class="block text-sm font-medium text-gray-700">Select Class</label>
                        <div class="relative">
                            <select id="class-selector" wire:model.live="selectedClassId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <div wire:loading wire:target="selectedClassId" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="animate-spin h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="student-selector" class="block text-sm font-medium text-gray-700">Select Student</label>
                        <div class="relative">
                            <select id="student-selector" wire:model.live="studentId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md" {{ count($students) > 0 ? '' : 'disabled' }}>
                                <option value="">-- Select Student --</option>
                                @foreach($students as $studentOption)
                                    <option value="{{ $studentOption->id }}">{{ $studentOption->name }} ({{ $studentOption->adm_no }})</option>
                                @endforeach
                            </select>
                            <div wire:loading wire:target="studentId, updatedSelectedClassId" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="animate-spin h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        @if(count($students) === 0 && $selectedClassId)
                            <p class="mt-1 text-xs text-amber-600">No students found in this class</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="flex space-x-2">
            @if($student)
                <button wire:click="exportAttendance" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            @endif
        </div>
    </div>
</div>

<div class="px-4 py-5 sm:p-6">
    <!-- Term and Date Range Filters -->
    @if($student)
        <div class="bg-gray-50 p-4 rounded-md border border-gray-200 shadow-sm mb-6">
            <div class="grid grid-cols-1 gap-y-4 gap-x-4 md:grid-cols-2">
                <div>
                    <label for="termFilter" class="block text-sm font-medium text-gray-700 mb-1">Time Period</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="setTermFilter('current')" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded {{ $termFilter === 'current' ? 'bg-green-100 border-green-300 text-green-800' : 'bg-white text-gray-700 hover:bg-gray-50' }} focus:outline-none focus:ring-1 focus:ring-green-500">
                            Current Term
                        </button>
                        <button type="button" wire:click="setTermFilter('term1')" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded {{ $termFilter === 'term1' ? 'bg-green-100 border-green-300 text-green-800' : 'bg-white text-gray-700 hover:bg-gray-50' }} focus:outline-none focus:ring-1 focus:ring-green-500">
                            Term 1
                        </button>
                        <button type="button" wire:click="setTermFilter('term2')" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded {{ $termFilter === 'term2' ? 'bg-green-100 border-green-300 text-green-800' : 'bg-white text-gray-700 hover:bg-gray-50' }} focus:outline-none focus:ring-1 focus:ring-green-500">
                            Term 2
                        </button>
                        <button type="button" wire:click="setTermFilter('term3')" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded {{ $termFilter === 'term3' ? 'bg-green-100 border-green-300 text-green-800' : 'bg-white text-gray-700 hover:bg-gray-50' }} focus:outline-none focus:ring-1 focus:ring-green-500">
                            Term 3
                        </button>
                        <button type="button" wire:click="setTermFilter('academic_year')" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded {{ $termFilter === 'academic_year' ? 'bg-green-100 border-green-300 text-green-800' : 'bg-white text-gray-700 hover:bg-gray-50' }} focus:outline-none focus:ring-1 focus:ring-green-500">
                            Academic Year
                        </button>
                    </div>
                </div>
                
                <div>
                    <label for="student-date-range" class="block text-sm font-medium text-gray-700 mb-1">Custom Date Range</label>
                    <div class="flex items-center space-x-3">
                        <div class="relative flex-grow">
                            <input type="text" id="student-date-range" placeholder="Select date range" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ $startDate }} to {{ $endDate }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <button type="button" wire:click="loadAttendanceData" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div> 