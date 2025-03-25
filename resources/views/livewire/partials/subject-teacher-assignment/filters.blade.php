<div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
    <!-- Filter section with dropdowns -->
    <div x-data="{ open: false }" class="mb-2">
        <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
            <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Filters
            <svg class="ml-2 -mr-0.5 h-4 w-4" :class="{ 'transform rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
        
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100" 
             x-transition:enter-start="transform opacity-0 scale-95" 
             x-transition:enter-end="transform opacity-100 scale-100" 
             x-transition:leave="transition ease-in duration-75" 
             x-transition:leave-start="transform opacity-100 scale-100" 
             x-transition:leave-end="transform opacity-0 scale-95" 
             class="mt-2 bg-white rounded-lg shadow-md p-4 border border-gray-100">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Academic Year Filter -->
                <div x-data="{ 
                    manualInput: false,
                    toggleManualInput() {
                        this.manualInput = !this.manualInput;
                        if (this.manualInput) {
                            this.$nextTick(() => {
                                this.$refs.yearFilterInput.focus();
                            });
                        }
                    }
                }">
                    <label for="academicYear" class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    
                    <div class="relative" x-show="!manualInput">
                        <div class="flex">
                            <div class="flex-grow">
                                <select id="academicYear" wire:model.live="academicYear" class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                                    <option value="">All Academic Years</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" @click="toggleManualInput" class="inline-flex items-center px-2 py-1 border border-l-0 border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="relative" x-show="manualInput">
                        <div class="flex">
                            <div class="flex-grow">
                                <input 
                                    x-ref="yearFilterInput"
                                    type="text" 
                                    wire:model.live="academicYear"
                                    class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm"
                                    placeholder="Enter academic year">
                            </div>
                            <button type="button" @click="toggleManualInput" class="inline-flex items-center px-2 py-1 border border-l-0 border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Academic Term Filter -->
                <div>
                    <label for="academicTerm" class="block text-sm font-medium text-gray-700 mb-1">Term</label>
                    <select id="academicTerm" wire:model.live="academicTerm" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                        <option value="">All Terms</option>
                        @foreach($academicTerms as $term)
                            <option value="{{ $term }}">{{ $term }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Class Filter -->
                <div>
                    <label for="classId" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                    <select id="classId" wire:model.live="classId" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Section Filter -->
                <div>
                    <label for="sectionId" class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                    <select id="sectionId" wire:model.live="sectionId" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm" {{ count($sections) ? '' : 'disabled' }}>
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Subject Filter -->
                <div>
                    <label for="subjectId" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <select id="subjectId" wire:model.live="subjectId" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Teacher Filter -->
                <div>
                    <label for="teacherId" class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                    <select id="teacherId" wire:model.live="teacherId" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Show Inactive Toggle -->
            <div class="mt-4 flex items-center">
                <label for="showInactive" class="inline-flex items-center cursor-pointer">
                    <span class="mr-3 text-sm font-medium text-gray-700">Show Inactive Assignments</span>
                    <div class="relative">
                        <input type="checkbox" wire:model.live="showInactive" id="showInactive" class="sr-only">
                        <div class="w-10 h-5 bg-gray-200 rounded-full shadow-inner"></div>
                        <div class="dot absolute w-5 h-5 bg-white rounded-full shadow -left-1 -top-0 transition {{ $showInactive ? 'transform translate-x-full bg-green-500' : '' }}"></div>
                    </div>
                </label>
            </div>
            
            <!-- Filter Actions -->
            <div class="mt-4 flex justify-end space-x-3">
                <button wire:click="resetFilters" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Filters
                </button>
            </div>
        </div>
    </div>
</div> 