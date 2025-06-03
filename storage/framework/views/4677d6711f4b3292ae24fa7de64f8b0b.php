<!--[if BLOCK]><![endif]--><?php if($selectedSection): ?>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200"
         x-data="{ 
            showGradeMenu: null,
            editing: null,
            refreshing: false,
            lastUpdate: null,
            init() {
                Livewire.on('marks-updated', () => {
                    this.refreshing = true;
                    setTimeout(() => this.refreshing = false, 500);
                    this.lastUpdate = new Date().toLocaleTimeString();
                });
            }
         }"
         x-init="init()"
         :class="{ 'opacity-70': refreshing }"
         wire:poll.30s>

        
        <div class="bg-gradient-to-r from-green-600 to-teal-700 px-6 py-4 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-full p-2">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-medium text-white">Assign Marks</h2>
                        <p class="mt-1 text-sm text-white/80">
                            <?php echo e($sections->where('id', $selectedSection)->first()->name ?? 'Selected Section'); ?> • 
                            <?php echo e($selectedClassName ?? 'Selected Class'); ?>

                        </p>
                    </div>
                </div>
                
                
                <div x-show="lastUpdate" x-cloak class="flex items-center space-x-2">
                    <span class="text-xs text-white/80">Last updated: <span x-text="lastUpdate"></span></span>
                    <button @click="$wire.refreshMarks()" 
                            class="inline-flex items-center p-1 rounded-full bg-white/10 hover:bg-white/20 transition-colors duration-200 focus:outline-none"
                            :class="{ 'animate-spin': refreshing }">
                        <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            
            <div class="space-y-4">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div wire:key="student-<?php echo e($student->id); ?>" 
                         class="bg-white rounded-lg border <?php echo e($student->is_enrolled ? 'border-green-200' : 'border-red-200'); ?> p-4 relative hover:shadow-md transition-all duration-200"
                         :class="{ 'ring-2 ring-green-500 ring-offset-2': editing === <?php echo e($student->id); ?> || showGradeMenu === <?php echo e($student->id); ?> }">
                        <div class="flex items-center justify-between">
                            
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full <?php echo e($student->is_enrolled ? 'bg-gray-100' : 'bg-red-50'); ?> flex items-center justify-center">
                                        <span class="text-sm font-medium <?php echo e($student->is_enrolled ? 'text-gray-600' : 'text-red-600'); ?>">
                                            <?php echo e(substr($student->first_name ?? 'S', 0, 1)); ?><?php echo e(substr($student->last_name ?? 'T', 0, 1)); ?>

                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">
                                        <?php echo e($student->first_name ?? ''); ?> <?php echo e($student->middle_name ?? ''); ?> <?php echo e($student->last_name ?? ''); ?>

                                    </h3>
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span class="text-xs text-gray-500">ADM: <?php echo e($student->adm_no ?? 'N/A'); ?></span>
                                        <span class="text-xs px-1.5 py-0.5 rounded-full <?php echo e($student->is_enrolled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e($student->is_enrolled ? 'Enrolled' : 'Not Enrolled'); ?>

                                        </span>
                                        <!--[if BLOCK]><![endif]--><?php if(!$student->is_enrolled): ?>
                                            <span class="text-xs text-red-600">Cannot assign marks</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>

                            
                            <div class="flex items-center space-x-3">
                                <!--[if BLOCK]><![endif]--><?php if($student->is_enrolled): ?>
                                    <?php
                                        $existingMark = $marks[$student->id] ?? null;
                                        $specialGrade = $specialGrades[$student->id] ?? null;
                                    ?>

                                    
                                    <!--[if BLOCK]><![endif]--><?php if($specialGrade): ?>
                                        <div class="flex items-center space-x-2" data-mark-updated>
                                            <span class="cursor-pointer px-3 py-1.5 rounded-full text-sm font-medium transition-colors duration-200
                                                         <?php echo e($specialGrade == 'AB' ? 'bg-red-100 text-red-800' : 
                                                            ($specialGrade == 'EX' ? 'bg-blue-100 text-blue-800' : 
                                                            ($specialGrade == 'P' ? 'bg-green-100 text-green-800' : 
                                                             'bg-yellow-100 text-yellow-800'))); ?>">
                                                <?php echo e($specialGrade); ?>

                                            </span>
                                            <button wire:click="clearMark(<?php echo e($student->id); ?>)"
                                                    class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        
                                        <div class="relative" data-mark-updated>
                                            <div @click="editing = <?php echo e($student->id); ?>" class="cursor-pointer">
                                                <!--[if BLOCK]><![endif]--><?php if(isset($marks[$student->id])): ?>
                                                    <span class="px-3 py-1.5 bg-gray-100 rounded-lg text-sm font-medium text-gray-900">
                                                        <?php echo e($marks[$student->id]); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-3 py-1.5 border border-dashed border-gray-300 rounded-lg text-sm text-gray-500">
                                                        Add Mark
                                                    </span>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>

                                            
                                            <div x-show="editing === <?php echo e($student->id); ?>" 
                                                 x-cloak
                                                 @click.away="editing = null"
                                                 class="absolute right-0 top-0 z-50 bg-white rounded-lg shadow-lg border border-gray-200 p-4 w-48">
                                                <div class="space-y-4">
                                                    <div>
                                                        <label for="mark-<?php echo e($student->id); ?>" class="block text-xs font-medium text-gray-700 mb-1">
                                                            Enter Mark (0-100)
                                                        </label>
                                                        <input type="number"
                                                               id="mark-<?php echo e($student->id); ?>"
                                                               wire:model.defer="marks.<?php echo e($student->id); ?>"
                                                               min="0"
                                                               max="100"
                                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                               placeholder="Enter mark"
                                                               x-init="$el.focus()">
                                                    </div>
                                                    <div class="flex justify-end space-x-2">
                                                        <button @click="editing = null"
                                                                class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                            Cancel
                                                        </button>
                                                        <button wire:click="saveMark(<?php echo e($student->id); ?>)"
                                                                @click="editing = null"
                                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <button @click="showGradeMenu = <?php echo e($student->id); ?>; editing = null"
                                                class="inline-flex items-center px-2 py-1 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                            Special Grade
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                    
                                    <div x-show="showGradeMenu === <?php echo e($student->id); ?>" 
                                         x-cloak
                                         @click.away="showGradeMenu = null"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 transform scale-100"
                                         x-transition:leave-end="opacity-0 transform scale-95"
                                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                         style="top: 100%;">
                                        <div class="py-1">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['AB' => ['Absent', 'bg-red-50 text-red-900'], 
                                                     'EX' => ['Exempted', 'bg-blue-50 text-blue-900'],
                                                     'P' => ['Pass', 'bg-green-50 text-green-900'],
                                                     'F' => ['Fail', 'bg-yellow-50 text-yellow-900']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button wire:click="assignSpecialGrade('<?php echo e($code); ?>', <?php echo e($student->id); ?>)"
                                                        @click="showGradeMenu = null"
                                                        class="w-full text-left px-4 py-2 text-sm hover:<?php echo e(explode(' ', $details[1])[0]); ?> <?php echo e($specialGrades[$student->id] === $code ? $details[1] : 'text-gray-700'); ?> focus:outline-none">
                                                    <span class="font-medium"><?php echo e($code); ?></span>
                                                    <span class="ml-2 text-gray-500"><?php echo e($details[0]); ?></span>
                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                <?php else: ?>
                                    
                                    <div class="flex items-center space-x-3">
                                        <span class="px-3 py-1.5 bg-gray-100 rounded-lg text-sm text-gray-400 cursor-not-allowed">
                                            Not Enrolled
                                        </span>
                                        <button disabled
                                                class="inline-flex items-center px-2 py-1 border border-gray-200 rounded-md text-xs font-medium text-gray-400 bg-gray-50 cursor-not-allowed">
                                            <svg class="w-4 h-4 mr-1 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                            Special Grade
                                        </button>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-4">
                            <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">No Students Found</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no students in this section.</p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <!--[if BLOCK]><![endif]--><?php if(count($students) > 0): ?>
                <div class="mt-6 flex justify-end">
                    <button wire:click="assignMarks"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save All Marks
                    </button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
<?php else: ?>
    <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
            <svg class="w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">Select a Stream</h3>
        <p class="mt-1 text-md text-gray-500">Please select a stream to view students and assign marks.</p>
        <div class="mt-6">
            <button @click="currentStep--" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back to Stream Selection
            </button>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/exam-marks/students-list.blade.php ENDPATH**/ ?>