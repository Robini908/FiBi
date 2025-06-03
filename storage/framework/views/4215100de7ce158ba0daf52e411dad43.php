<div>
    <!-- Control Buttons -->
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-medium text-gray-800">
                <!--[if BLOCK]><![endif]--><?php if($selectedExamId && !empty($exams)): ?>
                    <?php echo e(collect($exams)->firstWhere('id', $selectedExamId)->name); ?> Exam Timetable
                <?php else: ?>
                    Exam Timetable
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </h2>
            <p class="text-sm text-gray-500">
                <!--[if BLOCK]><![endif]--><?php if($selectedClassId && !empty($classes)): ?>
                    <?php echo e(collect($classes)->firstWhere('id', $selectedClassId)->name); ?>

                    <!--[if BLOCK]><![endif]--><?php if($selectedSectionId && !empty($sections)): ?>
                        - <?php echo e(collect($sections)->firstWhere('id', $selectedSectionId)->name); ?>

                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </p>
        </div>
        
        <div>
            <button type="button" 
                wire:click="openScheduleForm"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                <?php if(!$selectedExamId || !$selectedClassId): ?> disabled <?php endif; ?>
            >
                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Schedule
            </button>
        </div>
    </div>

    <!-- Timetable Content -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!--[if BLOCK]><![endif]--><?php if(count($examSchedules) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <!--[if BLOCK]><![endif]--><?php if($configShowInvigilators): ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invigilators</th>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                            <!--[if BLOCK]><![endif]--><?php if($configShowInstructions): ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructions</th>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $examSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo e(date('D, M d, Y', strtotime($schedule->exam_date))); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($schedule->subject->subject_name ?? 'Not specified'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e(date('h:i A', strtotime($schedule->start_time))); ?> - <?php echo e(date('h:i A', strtotime($schedule->end_time))); ?>

                                </td>
                                <!--[if BLOCK]><![endif]--><?php if($configShowInvigilators): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex -space-x-1 overflow-hidden">
                                            <!--[if BLOCK]><![endif]--><?php if(isset($schedule->invigilators) && count($schedule->invigilators) > 0): ?>
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $schedule->invigilators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invigilatorId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $teacher = \App\User::find($invigilatorId);
                                                        $initials = $teacher ? substr($teacher->name, 0, 1) . substr(strrchr($teacher->name, ' '), 1, 1) : 'TI';
                                                    ?>
                                                    <span title="<?php echo e($teacher ? $teacher->name : 'Unknown Teacher'); ?>" class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500 text-xs text-white">
                                                        <?php echo e($initials); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            <?php else: ?>
                                                <span class="text-gray-400 text-xs">No invigilators assigned</span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </td>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($schedule->venue ?? 'Not specified'); ?>

                                </td>
                                <!--[if BLOCK]><![endif]--><?php if($configShowInstructions): ?>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                        <?php echo e($schedule->instructions ?? 'No special instructions'); ?>

                                    </td>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" wire:click="editSchedule('<?php echo e($schedule->id); ?>')" class="text-green-600 hover:text-green-900 mr-3">
                                        Edit
                                    </button>
                                    <button type="button" wire:click="deleteSchedule('<?php echo e($schedule->id); ?>')" class="text-red-600 hover:text-red-900" onclick="confirm('Are you sure you want to delete this schedule?') || event.stopImmediatePropagation()">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-12 px-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No exam schedules found</h3>
                <!--[if BLOCK]><![endif]--><?php if($selectedExamId && $selectedClassId): ?>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new exam schedule or use the auto-generate feature.</p>
                    <div class="mt-6 flex justify-center">
                        <button type="button" 
                            wire:click="openScheduleForm"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Schedule
                        </button>
                        <button type="button" 
                            @click="showGeneration = true" 
                            wire:click="openGenerationSettings"
                            class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Auto Generate
                        </button>
                    </div>
                <?php else: ?>
                    <p class="mt-1 text-sm text-gray-500">Please select an exam and class to view or create schedules.</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/exam-timetable-content.blade.php ENDPATH**/ ?>