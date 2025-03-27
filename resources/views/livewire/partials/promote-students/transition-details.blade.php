<div>
    <h2 class="text-lg font-medium text-gray-800 mb-2">Transition Details</h2>
    <p class="text-sm text-gray-600 mb-6">Review and confirm the details for transitioning your selected students.</p>

    @if (count($selectedStudents) > 0)
        <div class="relative" x-data="{ showStudentSidebar: false, animationPlaying: false }">
            <!-- Floating action button to view selected students -->
            <button type="button" 
                @click="showStudentSidebar = !showStudentSidebar" 
                class="fixed bottom-6 right-6 z-40 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 shadow-lg transform transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :class="{'rotate-45': showStudentSidebar}">
                <span x-show="!showStudentSidebar"><i class="fas fa-users text-lg"></i></span>
                <span x-show="showStudentSidebar"><i class="fas fa-times text-lg"></i></span>
            </button>

            <!-- Student sidebar overlay - Now closes sidebar when clicked -->
            <div x-show="showStudentSidebar" 
                 @click="showStudentSidebar = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-30"></div>

            <!-- Student sidebar - Prevent clicks from bubbling up to overlay -->
            <div x-show="showStudentSidebar" 
                 @click.stop
                 @keydown.escape.window="showStudentSidebar = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="fixed top-0 right-0 w-80 h-full bg-white shadow-xl z-40 overflow-y-auto">
                 
                <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 z-10">
                    <!-- Close button at the top right -->
                    <button @click="showStudentSidebar = false" 
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1 transition-all duration-200 transform hover:scale-110"
                            aria-label="Close sidebar">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                    
                    <h3 class="text-lg font-medium text-gray-800 flex items-center pr-8">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Selected Students
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ count($selectedStudents) }} student(s) selected for transition
                    </p>
                </div>
                
                <div class="p-4">
                    @php
                        $students = \App\Models\StudentRecord::whereIn('id', $selectedStudents)->get();
                    @endphp
                    
                    <ul class="space-y-3">
                        @foreach($students as $index => $student)
                            <li class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300 hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5"
                                x-transition:enter="transition ease-out duration-300 delay-{{ $index * 100 }}"
                                x-transition:enter-start="opacity-0 transform translate-x-6"
                                x-transition:enter-end="opacity-100 transform translate-x-0">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                    <span class="font-medium">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h4>
                                    <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                        <i class="fas fa-id-card text-gray-400 mr-1"></i>
                                        <span>{{ $student->adm_no }}</span>
                                    </div>
                                </div>
                                <!-- Quick actions for each student can be added here -->
                                <div class="ml-2">
                                    <button class="text-gray-400 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors duration-200 p-1 rounded-full hover:bg-blue-100" title="View student details">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    
                    <!-- Close button at the bottom with keyboard focus -->
                    <div class="mt-6 flex justify-center">
                        <button @click="showStudentSidebar = false" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-times mr-2"></i>
                            Close
                        </button>
                    </div>
                    
                    <!-- Keyboard shortcut hint -->
                    <div class="text-center text-xs text-gray-500 mt-3">
                        Press <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">ESC</kbd> to close
                    </div>
                </div>
            </div>

            <!-- Main transition details -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="mb-6">
                    <h3 class="text-md font-medium text-gray-800 mb-4">Transition Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Transition Year -->
                        <div>
                            <label for="transitionYear" class="block text-sm font-medium text-gray-700 mb-1">Transition Year</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="text" wire:model="transitionYear" id="transitionYear" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., {{ date('Y') }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Current academic year of the students</p>
                            @error('transitionYear') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Target Academic Year (calculated) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Academic Year</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-check text-gray-400"></i>
                                </div>
                                <input type="text" readonly 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 bg-gray-50 rounded-md shadow-sm text-gray-700"
                                    value="{{ $transitionType === 'repetition' ? $transitionYear : $transitionYear + 1 }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                @if($transitionType === 'repetition')
                                    Same year for repetition
                                @else
                                    Next academic year after transition
                                @endif
                            </p>
                        </div>
                        
                        <!-- Academic Period (optional) -->
                        <div>
                            <label for="academicPeriod" class="block text-sm font-medium text-gray-700 mb-1">Academic Period (Optional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <select wire:model="academicPeriod" id="academicPeriod" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Period (Optional)</option>
                                    <option value="Term 1">Term 1</option>
                                    <option value="Term 2">Term 2</option>
                                    <option value="Term 3">Term 3</option>
                                    <option value="Semester 1">Semester 1</option>
                                    <option value="Semester 2">Semester 2</option>
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Specify term or semester if applicable</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-md font-medium text-gray-800 mb-4">Class Information</h3>
                    
                            <div class="flex flex-col items-center justify-center p-3 bg-white rounded-md border border-blue-100">
                                @php
                                    $currentClass = $classes->firstWhere('id', $selectedClass);
                                    $targetClassObj = $classes->firstWhere('id', $targetClass);
                                @endphp
                                
                                @if ($currentClass && $targetClassObj)
                                    <!-- Visual path diagram with animation capability -->
                                    <div class="flex items-center justify-center w-full max-w-2xl py-8"
                                         x-data="{ animateTransition: false }"
                                         x-on:play-animation.window="animateTransition = true; setTimeout(() => animateTransition = false, 3000);">
                                
                                        <div class="text-center w-1/3">
                                            <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center border border-gray-300 mb-2 relative"
                                                 :class="{'transition-all duration-1000 ease-in-out transform scale-90 -translate-y-1': animateTransition}">
                                                <i class="fas fa-users text-gray-600 text-lg"></i>
                                                
                                                <!-- Animated students going from current to target class -->
                                                <template x-if="animateTransition">
                                                    @for ($i = 0; $i < min(count($selectedStudents), 5); $i++)
                                                        <div class="absolute top-1/2 left-1/2 w-4 h-4 rounded-full bg-blue-500 transition-all duration-2000 delay-{{ $i * 200 }}"
                                                            :class="{'transform -translate-x-32 translate-y-0': animateTransition}"
                                                            style="z-index: 30; transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);"></div>
                                                    @endfor
                                                </template>
                                            </div>
                                            <div class="font-medium text-gray-900"
                                                :class="{'transition-all duration-1000 ease-in-out opacity-50': animateTransition}">
                                                {{ $currentClass->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1"
                                                :class="{'transition-all duration-1000 ease-in-out opacity-50': animateTransition}">
                                        Current Class ({{ $transitionYear }})
                                            </div>
                                        </div>
                                        
                                        <!-- Arrow and transition type with animation -->
                                        <div class="w-1/3 flex flex-col items-center">
                                            @if ($transitionType === 'promotion')
                                                <div class="h-0.5 w-16 bg-emerald-300 my-8 relative" x-ref="arrow">
                                                    <div class="absolute inset-0 bg-emerald-500 origin-left transform scale-x-0 transition-transform duration-1000"
                                                        :class="{'scale-x-100': animateTransition}"></div>
                                                </div>
                                                <div class="bg-emerald-500 text-white text-xs rounded-full px-3 py-1 -mt-4 transition-all duration-500"
                                                     :class="{'scale-125 shadow-md': animateTransition}">
                                                    <i class="fas fa-arrow-up mr-1"></i> Promotion
                                                </div>
                                            @elseif ($transitionType === 'demotion')
                                        <div class="h-0.5 w-16 bg-red-300 my-8 relative" x-ref="arrow">
                                                    <div class="absolute inset-0 bg-red-500 origin-left transform scale-x-0 transition-transform duration-1000"
                                                        :class="{'scale-x-100': animateTransition}"></div>
                                                </div>
                                                <div class="bg-red-500 text-white text-xs rounded-full px-3 py-1 -mt-4 transition-all duration-500"
                                                     :class="{'scale-125 shadow-md': animateTransition}">
                                                    <i class="fas fa-arrow-down mr-1"></i> Demotion
                                                </div>
                                            @elseif ($transitionType === 'repetition')
                                        <div class="h-0.5 w-16 bg-amber-300 my-8 relative" x-ref="arrow">
                                                    <div class="absolute inset-0 bg-amber-500 origin-left transform scale-x-0 transition-transform duration-1000"
                                                        :class="{'scale-x-100': animateTransition}"></div>
                                                </div>
                                                <div class="bg-amber-500 text-white text-xs rounded-full px-3 py-1 -mt-4 transition-all duration-500"
                                                     :class="{'scale-125 shadow-md': animateTransition}">
                                                    <i class="fas fa-redo mr-1"></i> Repetition
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="text-center w-1/3">
                                            <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-2 relative transition-all duration-1000"
                                                 :class="{
                                                    'transform scale-110 shadow-lg': animateTransition,
                                                    'bg-emerald-100 border border-emerald-300': '{{ $transitionType }}' === 'promotion',
                                                    'bg-red-100 border border-red-300': '{{ $transitionType }}' === 'demotion',
                                                    'bg-amber-100 border border-amber-300': '{{ $transitionType }}' === 'repetition'
                                                 }">
                                                <i class="fas fa-users transition-all duration-500" 
                                                   :class="{
                                                        'transform scale-125': animateTransition,
                                                        'text-emerald-600': '{{ $transitionType }}' === 'promotion',
                                                        'text-red-600': '{{ $transitionType }}' === 'demotion',
                                                        'text-amber-600': '{{ $transitionType }}' === 'repetition'
                                                   }"></i>
                                            </div>
                                            <div class="font-medium transition-all duration-500"
                                                 :class="{
                                                    'transform scale-105': animateTransition,
                                                    'text-emerald-800': '{{ $transitionType }}' === 'promotion',
                                                    'text-red-800': '{{ $transitionType }}' === 'demotion',
                                                    'text-amber-800': '{{ $transitionType }}' === 'repetition'
                                                 }">{{ $targetClassObj->name }}</div>
                                            <div class="text-xs mt-1 transition-all duration-500"
                                                 :class="{
                                                    'text-emerald-600': '{{ $transitionType }}' === 'promotion',
                                                    'text-red-600': '{{ $transitionType }}' === 'demotion',
                                                    'text-amber-600': '{{ $transitionType }}' === 'repetition'
                                         }">Target Class ({{ $transitionType === 'repetition' ? $transitionYear : $transitionYear + 1 }})</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Play animation button -->
                                    <button type="button" 
                                    @click="animateTransition = true; setTimeout(() => animateTransition = false, 3000);"
                                    class="mt-2 inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-medium rounded-full hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-colors duration-200">
                                <i class="fas fa-play-circle mr-1"></i> Visualize Transition
                                    </button>
                                    
                                    <div class="mt-4 text-sm text-gray-700 text-center">
                                        <p>
                                            @if ($transitionType === 'promotion')
                                                <span class="text-gray-600">You are promoting</span> 
                                                <span class="font-medium text-blue-700">{{ count($selectedStudents) }} student(s)</span> 
                                                <span class="text-gray-600">from</span> 
                                                <span class="font-medium text-gray-700">{{ $currentClass->name }}</span> 
                                                <span class="text-gray-600">to</span> 
                                                <span class="font-medium text-emerald-600">{{ $targetClassObj->name }}</span>
                                        <span class="text-gray-600">for the academic year</span>
                                        <span class="font-medium text-blue-700">{{ $transitionYear + 1 }}</span>
                                            @elseif ($transitionType === 'demotion')
                                                <span class="text-gray-600">You are demoting</span> 
                                                <span class="font-medium text-blue-700">{{ count($selectedStudents) }} student(s)</span> 
                                                <span class="text-gray-600">from</span> 
                                                <span class="font-medium text-gray-700">{{ $currentClass->name }}</span> 
                                                <span class="text-gray-600">to</span> 
                                                <span class="font-medium text-red-600">{{ $targetClassObj->name }}</span>
                                        <span class="text-gray-600">for the academic year</span>
                                        <span class="font-medium text-blue-700">{{ $transitionYear + 1 }}</span>
                                            @elseif ($transitionType === 'repetition')
                                        <span class="text-gray-600">You are having</span> 
                                                <span class="font-medium text-blue-700">{{ count($selectedStudents) }} student(s)</span> 
                                        <span class="text-gray-600">repeat</span> 
                                                <span class="font-medium text-amber-600">{{ $currentClass->name }}</span>
                                        <span class="text-gray-600">for the academic year</span>
                                        <span class="font-medium text-blue-700">{{ $transitionYear }}</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                
                <!-- Reason for transition -->
                <div class="mb-6">
                    <h3 class="text-md font-medium text-gray-800 mb-4">Additional Information</h3>
                    
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                            Reason for {{ $transitionType === 'promotion' ? 'Promotion' : ($transitionType === 'demotion' ? 'Demotion' : 'Repetition') }}
                            @if($transitionType !== 'promotion') <span class="text-red-500">*</span> @endif
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 text-gray-400">
                                <i class="fas fa-comment-alt"></i>
                            </div>
                            <textarea wire:model="reason" id="reason" rows="3" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="{{ $transitionType === 'promotion' ? 'Optional reason for promotion' : 'Required reason for ' . $transitionType }}"></textarea>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            @if($transitionType === 'promotion')
                                The reason is optional for regular promotions.
                            @elseif($transitionType === 'demotion')
                                Please provide a valid reason for demoting these students.
                            @elseif($transitionType === 'repetition')
                                Please explain why these students need to repeat this class.
                        @endif
                        </p>
                        @error('reason') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <!-- Warning and information alert -->
                <div class="mb-6">
                    <div class="rounded-md bg-yellow-50 p-4 border border-yellow-100">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>This action will update student class assignments in the system. The changes will take effect immediately after confirmation.</p>
                                    <p class="mt-1">Once confirmed, students will be moved to their new class/section and all related data will be updated accordingly.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-100 text-blue-700 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">Please go back and select students to transition.</p>
                </div>
            </div>
        </div>
    @endif
</div>
