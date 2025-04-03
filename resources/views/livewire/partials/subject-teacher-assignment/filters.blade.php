<div class="px-6 py-4 bg-white border-b border-gray-100">
    <!-- Filter section with Material Design -->
    <div x-data="{ open: true }" class="mb-2">
        <div class="flex flex-wrap justify-between items-center mb-3">
            <!-- Filter Toggle Button -->
            <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 rounded-full shadow-sm text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                <svg class="mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span x-text="open ? 'Hide Filters' : 'Show Filters'"></span>
                <svg class="ml-2 h-5 w-5 text-gray-500" :class="{ 'transform rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            
            <!-- Quick Filter Chips (Google-style) -->
            <div class="flex flex-wrap gap-2 mt-2 sm:mt-0">
                @if($academicYear)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                        Year: {{ $academicYear }}
                        <button wire:click="$set('academicYear', null)" class="ml-1.5 inline-flex text-blue-500 hover:text-blue-700 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($academicTerm)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-50 text-green-700">
                        Term: {{ $academicTerm }}
                        <button wire:click="$set('academicTerm', null)" class="ml-1.5 inline-flex text-green-500 hover:text-green-700 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($classId && $classes->firstWhere('id', $classId))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-50 text-purple-700">
                        Class: {{ $classes->firstWhere('id', $classId)->name }}
                        <button wire:click="$set('classId', null)" class="ml-1.5 inline-flex text-purple-500 hover:text-purple-700 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($sectionId && $sections->firstWhere('id', $sectionId))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700">
                        Section: {{ $sections->firstWhere('id', $sectionId)->name }}
                        <button wire:click="$set('sectionId', null)" class="ml-1.5 inline-flex text-indigo-500 hover:text-indigo-700 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($teacherId && $teachers->firstWhere('id', $teacherId))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-50 text-yellow-700">
                        Teacher: {{ $teachers->firstWhere('id', $teacherId)->name }}
                        <button wire:click="$set('teacherId', null)" class="ml-1.5 inline-flex text-yellow-500 hover:text-yellow-700 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($subjectId && $subjects->firstWhere('id', $subjectId))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-50 text-red-700">
                        Subject: {{ $subjects->firstWhere('id', $subjectId)->subject_name }}
                        <button wire:click="$set('subjectId', null)" class="ml-1.5 inline-flex text-red-500 hover:text-red-700 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($showInactive)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-50 text-gray-700">
                        Including Inactive
                        <button wire:click="$set('showInactive', false)" class="ml-1.5 inline-flex text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                @endif
                
                @if($academicYear || $academicTerm || $classId || $sectionId || $teacherId || $subjectId || $showInactive)
                    <button wire:click="resetFilters" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                        <svg class="mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset All
                    </button>
                @endif
            </div>
        </div>
        
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="transform opacity-0 scale-95" 
             x-transition:enter-end="transform opacity-100 scale-100" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="transform opacity-100 scale-100" 
             x-transition:leave-end="transform opacity-0 scale-95" 
             class="mt-4 bg-white rounded-lg shadow-sm p-5 border border-gray-100">
            
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Academic Year Filter -->
                <div>
                    <label for="academicYear" class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <div class="relative rounded-md shadow-sm">
                        <select id="academicYear" wire:model.live="academicYear" class="block w-full rounded-md border-gray-300 pr-10 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">All Years</option>
                        @foreach($academicYears as $year)
                            @if(is_array($year))
                                    <option value="{{ $year['id'] }}" {{ ($year['id'] == $academicYear) ? 'selected' : '' }}>
                                        {{ $year['name'] }} {{ isset($year['is_current']) && $year['is_current'] ? '(Current)' : '' }}
                                    </option>
                                @elseif(is_object($year))
                                    <option value="{{ $year->id }}" {{ ($year->id == $academicYear) ? 'selected' : '' }}>
                                        {{ $year->year }}
                                    </option>
                            @else
                                    <option value="{{ $year }}" {{ ($year == $academicYear) ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                            @endif
                        @endforeach
                    </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Academic Term Filter -->
                <div>
                    <label for="academicTerm" class="block text-sm font-medium text-gray-700 mb-1">Term</label>
                    <div class="relative rounded-md shadow-sm">
                        <select id="academicTerm" wire:model.live="academicTerm" class="block w-full rounded-md border-gray-300 pr-10 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">All Terms</option>
                        @foreach($academicTerms as $term)
                                <option value="{{ $term }}" {{ ($term == $academicTerm) ? 'selected' : '' }}>{{ $term }}</option>
                        @endforeach
                    </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Class Filter -->
                <div>
                    <label for="classId" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                    <div class="relative rounded-md shadow-sm">
                        <select id="classId" wire:model.live="classId" class="block w-full rounded-md border-gray-300 pr-10 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Section Filter -->
                <div>
                    <label for="sectionId" class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                    <div class="relative rounded-md shadow-sm">
                        <select id="sectionId" wire:model.live="sectionId" class="block w-full rounded-md border-gray-300 pr-10 focus:border-blue-500 focus:ring-blue-500 text-sm" {{ count($sections) ? '' : 'disabled' }}>
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Subject Filter -->
                <div>
                    <label for="subjectId" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <div class="relative rounded-md shadow-sm">
                        <select id="subjectId" wire:model.live="subjectId" class="block w-full rounded-md border-gray-300 pr-10 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Teacher Filter -->
                <div>
                    <label for="teacherId" class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                    <div class="relative rounded-md shadow-sm">
                        <select id="teacherId" wire:model.live="teacherId" class="block w-full rounded-md border-gray-300 pr-10 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Show Inactive Toggle - Material Design Switch -->
            <div class="mt-5 flex items-center">
                <label for="showInactive" class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="showInactive" id="showInactive" class="sr-only">
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-700">Show Inactive Assignments</span>
                </label>
            </div>
        </div>
    </div>
</div> 