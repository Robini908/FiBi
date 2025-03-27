<div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
    <!-- Filter section with dropdowns -->
    <div x-data="{ open: true }" class="mb-2">
        <div class="flex justify-between items-center mb-3">
            <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                {{ open ? 'Hide Filters' : 'Show Filters' }}
                <svg class="ml-2 -mr-0.5 h-4 w-4" :class="{ 'transform rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            
            <!-- Quick Filter Tags -->
            <div class="flex flex-wrap gap-2">
                @if($academicYear)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Year: {{ $academicYear }}
                        <button wire:click="$set('academicYear', null)" class="ml-1.5 inline-flex text-green-500 hover:text-green-600 focus:outline-none">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($academicTerm)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Term: {{ $academicTerm }}
                        <button wire:click="$set('academicTerm', null)" class="ml-1.5 inline-flex text-blue-500 hover:text-blue-600 focus:outline-none">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($classId && $classes->firstWhere('id', $classId))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Class: {{ $classes->firstWhere('id', $classId)->name }}
                        <button wire:click="$set('classId', null)" class="ml-1.5 inline-flex text-purple-500 hover:text-purple-600 focus:outline-none">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($sectionId && $sections->firstWhere('id', $sectionId))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Section: {{ $sections->firstWhere('id', $sectionId)->name }}
                        <button wire:click="$set('sectionId', null)" class="ml-1.5 inline-flex text-indigo-500 hover:text-indigo-600 focus:outline-none">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($teacherId && $teachers->firstWhere('id', $teacherId))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Teacher: {{ $teachers->firstWhere('id', $teacherId)->name }}
                        <button wire:click="$set('teacherId', null)" class="ml-1.5 inline-flex text-yellow-500 hover:text-yellow-600 focus:outline-none">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($subjectId && $subjects->firstWhere('id', $subjectId))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Subject: {{ $subjects->firstWhere('id', $subjectId)->subject_name }}
                        <button wire:click="$set('subjectId', null)" class="ml-1.5 inline-flex text-red-500 hover:text-red-600 focus:outline-none">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($showInactive)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Including Inactive
                        <button wire:click="$set('showInactive', false)" class="ml-1.5 inline-flex text-gray-500 hover:text-gray-600 focus:outline-none">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($academicYear || $academicTerm || $classId || $sectionId || $teacherId || $subjectId || $showInactive)
                    <button wire:click="resetFilters" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Reset All
                    </button>
                @endif
            </div>
        </div>
        
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100" 
             x-transition:enter-start="transform opacity-0 scale-95" 
             x-transition:enter-end="transform opacity-100 scale-100" 
             x-transition:leave="transition ease-in duration-75" 
             x-transition:leave-start="transform opacity-100 scale-100" 
             x-transition:leave-end="transform opacity-0 scale-95" 
             class="mt-2 bg-white rounded-lg shadow-sm p-4 border border-gray-100">
            
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Academic Year Filter -->
                <div>
                    <label for="academicYear" class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <select id="academicYear" wire:model.live="academicYear" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                        <option value="">All Years</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
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
        </div>
    </div>
</div> 