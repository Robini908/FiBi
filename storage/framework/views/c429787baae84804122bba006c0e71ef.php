<div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Filters Panel -->
        <div class="md:col-span-1 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h4 class="text-base font-medium text-gray-900 mb-3">Filter Timetables</h4>
            
            <!-- Class Filter -->
            <div class="mb-4">
                <label for="filterClass" class="block text-sm font-medium text-gray-700">Class</label>
                <select id="filterClass" wire:model.live="filterClass" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                    <option value="">All Classes</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $availableClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
            
            <!-- Academic Session Filter -->
            <div class="mb-4">
                <label for="filterAcademicSession" class="block text-sm font-medium text-gray-700">Academic Session</label>
                <select id="filterAcademicSession" wire:model.live="filterAcademicSession" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                    <option value="">All Sessions</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $availableAcademicSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($session); ?>"><?php echo e($session); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
            
            <!-- Academic Term Filter -->
            <div class="mb-4">
                <label for="filterAcademicTerm" class="block text-sm font-medium text-gray-700">Academic Term</label>
                <select id="filterAcademicTerm" wire:model.live="filterAcademicTerm" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                    <option value="">All Terms</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $availableAcademicTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($term); ?>"><?php echo e($term); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
            
            <!-- Filter Controls -->
            <div class="flex justify-between mt-4">
                <button type="button" 
                    wire:click="updateFilters" 
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Apply Filters
                </button>
                <button type="button" 
                    wire:click="resetFilters" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Reset
                </button>
            </div>
        </div>
        
        <!-- Timetable Selection -->
        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h4 class="text-base font-medium text-gray-900 mb-3">Select Timetables to Consolidate</h4>
            
            <!--[if BLOCK]><![endif]--><?php if($availableTimetables->isEmpty()): ?>
                <div class="bg-yellow-50 text-yellow-700 p-4 rounded-md border border-yellow-300">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium">No timetables available</h3>
                            <p class="text-sm mt-1">Try changing your filter criteria or create new timetables first.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="overflow-y-auto max-h-96 bg-white border border-gray-300 rounded-md">
                    <ul class="divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $availableTimetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="p-3 hover:bg-gray-50">
                                <label class="flex items-start">
                                    <div class="flex items-center h-5 mt-1">
                                        <input type="checkbox" 
                                            wire:model.live="selectedTimetableIds" 
                                            value="<?php echo e($timetable->id); ?>" 
                                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <p class="font-medium text-gray-700"><?php echo e($timetable->name); ?></p>
                                        <div class="text-gray-500 flex flex-wrap gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Class: <?php echo e(optional($timetable->myClass)->name ?? 'Unknown'); ?>

                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Session: <?php echo e($timetable->academic_session ?? 'Unknown'); ?>

                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Term: <?php echo e($timetable->academic_term ?? 'Unknown'); ?>

                                            </span>
                                        </div>
                                    </div>
                                </label>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                </div>
                
                <div class="mt-2 text-sm text-gray-500">
                    Selected: <?php echo e(count($selectedTimetableIds)); ?> of <?php echo e($availableTimetables->count()); ?> timetables
                </div>
                
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedTimetableIds'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        
        <!-- Configuration Options -->
        <div class="md:col-span-1 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h4 class="text-base font-medium text-gray-900 mb-3">Consolidation Options</h4>
            
            <!-- Group By Option -->
            <div class="mb-4">
                <label for="groupBy" class="block text-sm font-medium text-gray-700">Group By</label>
                <select id="groupBy" wire:model.live="consolidationOptions.group_by" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                    <option value="teacher">Teacher</option>
                    <option value="class">Class</option>
                    <option value="subject">Subject</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">
                    <!--[if BLOCK]><![endif]--><?php if($consolidationOptions['group_by'] === 'teacher'): ?>
                        Shows all classes each teacher is teaching
                    <?php elseif($consolidationOptions['group_by'] === 'class'): ?>
                        Shows all subjects taught in each class
                    <?php elseif($consolidationOptions['group_by'] === 'subject'): ?>
                        Shows all instances where each subject is taught
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </p>
            </div>
            
            <!-- Weekend Options -->
            <div class="mb-3">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="showWeekends" wire:model.live="consolidationOptions.show_weekends" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="showWeekends" class="font-medium text-gray-700">Include Weekends</label>
                        <p class="text-gray-500">Show Saturday and Sunday in the timetable</p>
                    </div>
                </div>
            </div>
            
            <!-- Conflict Options -->
            <div class="mb-3">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="showConflicts" wire:model.live="consolidationOptions.show_conflicts" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="showConflicts" class="font-medium text-gray-700">Show Conflicts</label>
                        <p class="text-gray-500">Display scheduling conflicts</p>
                    </div>
                </div>
            </div>
            
            <div class="mb-3 pl-7">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="highlightConflicts" wire:model.live="consolidationOptions.highlight_conflicts" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded" <?php echo e(!$consolidationOptions['show_conflicts'] ? 'disabled' : ''); ?>>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="highlightConflicts" class="font-medium text-gray-700 <?php echo e(!$consolidationOptions['show_conflicts'] ? 'text-gray-400' : ''); ?>">Highlight Conflicts</label>
                        <p class="text-gray-500 <?php echo e(!$consolidationOptions['show_conflicts'] ? 'text-gray-400' : ''); ?>">Make conflicts stand out visually</p>
                    </div>
                </div>
            </div>
            
            <!-- Period Options -->
            <div class="mb-3">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="mergePeriods" wire:model.live="consolidationOptions.merge_similar_periods" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="mergePeriods" class="font-medium text-gray-700">Merge Similar Periods</label>
                        <p class="text-gray-500">Combine periods with the same start/end times</p>
                    </div>
                </div>
            </div>
            
            <!-- Empty Slots Option -->
            <div class="mb-3">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="includeEmptySlots" wire:model.live="consolidationOptions.include_empty_slots" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="includeEmptySlots" class="font-medium text-gray-700">Include Empty Slots</label>
                        <p class="text-gray-500">Show periods with no assigned classes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Generate Button -->
    <div class="mt-6 flex justify-center">
        <button type="button" 
            wire:click="consolidateTimetables" 
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
            <?php echo e(count($selectedTimetableIds) < 1 ? 'disabled' : ''); ?>>
            <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
            </svg>
            Generate Consolidated Timetable
        </button>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/consolidator-selection.blade.php ENDPATH**/ ?>