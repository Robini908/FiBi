<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-5">
    <!-- Header with stats -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <div class="flex items-center justify-between flex-wrap sm:flex-nowrap">
            <div class="flex items-center">
                <h4 class="text-base font-medium text-gray-700">Students</h4>
                <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <?php echo e(count($students)); ?> Total
                </span>
            </div>
            
            <div class="flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <div class="inline-flex items-center bg-white rounded-full pl-1 pr-2 py-1 border border-gray-200 shadow-sm text-xs">
                        <span class="flex h-5 w-5 rounded-full items-center justify-center bg-green-100 text-green-800 font-medium mr-1.5" x-text="Object.values($wire.studentStatus).filter(status => status === 'present').length">0</span>
                        <span class="text-gray-600">Present</span>
                    </div>
                    <div class="inline-flex items-center bg-white rounded-full pl-1 pr-2 py-1 border border-gray-200 shadow-sm text-xs">
                        <span class="flex h-5 w-5 rounded-full items-center justify-center bg-gray-100 text-gray-800 font-medium mr-1.5" x-text="Object.values($wire.studentStatus).filter(status => status === 'absent').length">0</span>
                        <span class="text-gray-600">Absent</span>
                    </div>
                    <div class="inline-flex items-center bg-white rounded-full pl-1 pr-2 py-1 border border-gray-200 shadow-sm text-xs">
                        <span class="flex h-5 w-5 rounded-full items-center justify-center bg-gray-200 text-gray-800 font-medium mr-1.5" x-text="Object.values($wire.studentStatus).filter(status => status === 'late').length">0</span>
                        <span class="text-gray-600">Late</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search bar -->
    <div class="px-6 py-3 bg-white border-b border-gray-100">
        <div class="relative rounded-md shadow-sm max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 pr-3 py-2 border-gray-200 rounded-md" placeholder="Search students...">
        </div>
    </div>
    
    <!--[if BLOCK]><![endif]--><?php if(count($students) > 0): ?>
        <div class="overflow-x-auto">
            <div class="grid grid-cols-1 divide-y divide-gray-100">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $filteredStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div wire:key="student-<?php echo e($student['id']); ?>" class="group hover:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="px-6 py-3 flex flex-col sm:flex-row sm:items-center">
                            <!-- Student info -->
                            <div class="flex items-center flex-grow mb-3 sm:mb-0">
                                <div class="flex-shrink-0 h-12 w-12 overflow-hidden rounded-full border-2 border-gray-200">
                                    <img class="h-12 w-12 rounded-full object-cover" 
                                        src="<?php echo e($student['photo'] ?? asset('global_assets/images/user.png')); ?>" 
                                        alt="<?php echo e($student['name']); ?>" loading="lazy">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($student['name']); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($student['adm_no'] ?? '—'); ?></div>
                                </div>
                            </div>
                            
                            <!-- Quick status buttons -->
                            <div class="flex flex-wrap gap-2 items-center sm:justify-end">
                                <button type="button" 
                                    wire:click="$set('studentStatus.<?php echo e($student['id']); ?>', 'present')" 
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                                        <?php echo e($studentStatus[$student['id']] === 'present' ? 'bg-green-100 text-green-800 ring-2 ring-green-600' : 'bg-gray-50 text-gray-700 hover:bg-green-50'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 <?php echo e($studentStatus[$student['id']] === 'present' ? 'text-green-600' : 'text-gray-400'); ?>" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Present
                                </button>
                                <button type="button" 
                                    wire:click="$set('studentStatus.<?php echo e($student['id']); ?>', 'absent')" 
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                                        <?php echo e($studentStatus[$student['id']] === 'absent' ? 'bg-gray-200 text-gray-800 ring-2 ring-gray-600' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 <?php echo e($studentStatus[$student['id']] === 'absent' ? 'text-gray-600' : 'text-gray-400'); ?>" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Absent
                                </button>
                                <button type="button" 
                                    wire:click="$set('studentStatus.<?php echo e($student['id']); ?>', 'late'); $set('showLateTime.<?php echo e($student['id']); ?>', true)" 
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                                        <?php echo e($studentStatus[$student['id']] === 'late' ? 'bg-gray-300 text-gray-800 ring-2 ring-gray-500' : 'bg-gray-50 text-gray-700 hover:bg-gray-100'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 <?php echo e($studentStatus[$student['id']] === 'late' ? 'text-gray-700' : 'text-gray-400'); ?>" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Late
                                </button>
                                
                                <!-- More options dropdown -->
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button type="button" 
                                        @click="open = !open" 
                                        class="inline-flex items-center px-2 py-1.5 rounded-full text-xs font-medium bg-gray-50 text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" 
                                        @click.away="open = false" 
                                        x-transition:enter="transition ease-out duration-100" 
                                        x-transition:enter-start="transform opacity-0 scale-95" 
                                        x-transition:enter-end="transform opacity-100 scale-100" 
                                        x-transition:leave="transition ease-in duration-75" 
                                        x-transition:leave-start="transform opacity-100 scale-100" 
                                        x-transition:leave-end="transform opacity-0 scale-95" 
                                        class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                        style="display: none;">
                                        <div class="py-1" role="menu" aria-orientation="vertical">
                                            <button wire:click="$set('studentStatus.<?php echo e($student['id']); ?>', 'excused')" 
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 <?php echo e($studentStatus[$student['id']] === 'excused' ? 'bg-gray-50 font-medium' : ''); ?>" 
                                                role="menuitem">
                                                Excused
                                            </button>
                                            <button wire:click="$set('studentStatus.<?php echo e($student['id']); ?>', 'sick')" 
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 <?php echo e($studentStatus[$student['id']] === 'sick' ? 'bg-gray-50 font-medium' : ''); ?>" 
                                                role="menuitem">
                                                Sick
                                            </button>
                                            <button wire:click="$set('studentStatus.<?php echo e($student['id']); ?>', 'on_leave')" 
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 <?php echo e($studentStatus[$student['id']] === 'on_leave' ? 'bg-gray-50 font-medium' : ''); ?>" 
                                                role="menuitem">
                                                On Leave
                                            </button>
                                            <button wire:click="$set('studentStatus.<?php echo e($student['id']); ?>', 'other')" 
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 <?php echo e($studentStatus[$student['id']] === 'other' ? 'bg-gray-50 font-medium' : ''); ?>" 
                                                role="menuitem">
                                                Other
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional details (time in & remarks) - collapsed by default but expanded when needed -->
                        <div x-data="{ expanded: <?php echo e($studentStatus[$student['id']] === 'late' || !empty($studentRemarks[$student['id']]) ? 'true' : 'false'); ?> }"
                             x-show="expanded || <?php echo e($studentStatus[$student['id']] === 'late' ? 'true' : 'false'); ?>"
                             class="px-6 pb-3 pt-0 grid grid-cols-1 sm:grid-cols-2 gap-4 ml-16"
                             style="<?php echo e(($studentStatus[$student['id']] === 'late' || !empty($studentRemarks[$student['id']])) ? '' : 'display: none;'); ?>">
                            
                            <!-- Time in field - show only for late students -->
                            <!--[if BLOCK]><![endif]--><?php if($studentStatus[$student['id']] === 'late'): ?>
                                <div>
                                    <label for="time-in-<?php echo e($student['id']); ?>" class="block text-xs font-medium text-gray-700 mb-1">Time In</label>
                                    <div class="relative rounded-md shadow-sm max-w-[140px]">
                                        <input type="time" id="time-in-<?php echo e($student['id']); ?>" wire:model.live="timeIn.<?php echo e($student['id']); ?>" 
                                            class="focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            
                            <!-- Remarks field -->
                            <div class="<?php echo e($studentStatus[$student['id']] === 'late' ? 'sm:col-span-1' : 'sm:col-span-2'); ?>">
                                <label for="remarks-<?php echo e($student['id']); ?>" class="block text-xs font-medium text-gray-700 mb-1">Remarks</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" id="remarks-<?php echo e($student['id']); ?>" wire:model.live="studentRemarks.<?php echo e($student['id']); ?>" 
                                        class="focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                        placeholder="Add remarks (optional)">
                                </div>
                            </div>
                            
                            <!-- Close details button -->
                            <button x-show="expanded && <?php echo e($studentStatus[$student['id']] !== 'late' ? 'true' : 'false'); ?>" 
                                    @click="expanded = false" 
                                    class="text-xs text-gray-500 hover:text-gray-700 sm:col-span-2 inline-flex items-center" 
                                    type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                                Close details
                            </button>
                        </div>
                        
                        <!-- "Add remarks" button (show only if not expanded) -->
                        <div x-data="{ expanded: <?php echo e($studentStatus[$student['id']] === 'late' || !empty($studentRemarks[$student['id']]) ? 'true' : 'false'); ?> }"
                             x-show="!expanded && <?php echo e($studentStatus[$student['id']] !== 'late' ? 'true' : 'false'); ?>"
                             class="pl-16 pb-3 pt-0"
                             style="<?php echo e(($studentStatus[$student['id']] === 'late' || !empty($studentRemarks[$student['id']])) ? 'display: none;' : ''); ?>">
                            <button @click="expanded = true" class="text-xs text-green-600 hover:text-green-800 inline-flex items-center" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Add remarks
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    <?php else: ?>
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No students</h3>
            <p class="mt-1 text-sm text-gray-500">No students are enrolled in this class/section.</p>
            <div class="mt-6">
                <a href="<?php echo e(route('students.create')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Students
                </a>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/partials/take-students-table.blade.php ENDPATH**/ ?>