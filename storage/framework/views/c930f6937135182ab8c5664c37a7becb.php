<!--[if BLOCK]><![endif]--><?php if($showDetailsModal): ?>
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <!-- Header -->
            <div class="bg-green-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-green-100">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Exam Details
                </h3>
                <button wire:click="closeExamDetails" type="button" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[calc(100vh-200px)] overflow-y-auto">
                <!--[if BLOCK]><![endif]--><?php if($selectedExam): ?>
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-3 pb-2 border-b">Basic Information</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Exam Name</span>
                                    <span class="block mt-1 text-sm text-gray-900"><?php echo e($selectedExam->name); ?></span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Term</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        <?php echo e(isset($terms[$selectedExam->term]) ? $terms[$selectedExam->term] : 'Term ' . $selectedExam->term); ?>

                                    </span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Year</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                        <?php echo e($selectedExam->year); ?>

                                    </span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Grading System</span>
                                    <!--[if BLOCK]><![endif]--><?php if($selectedExam->gradingSystem): ?>
                                        <button
                                            wire:click="showGradingSystemDetails(<?php echo e($selectedExam->grading_system_id); ?>)"
                                            class="inline-flex items-center mt-1 text-sm text-green-600 hover:text-green-900 focus:outline-none transition-colors duration-200"
                                        >
                                            <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                            </svg>
                                            <?php echo e($selectedExam->gradingSystem->name); ?>

                                        </button>
                                    <?php else: ?>
                                        <span class="block mt-1 text-sm text-gray-500">Not specified</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- Exam Schedule -->
                                <!--[if BLOCK]><![endif]--><?php if($selectedExam->exam_date || $selectedExam->start_time || $selectedExam->end_time): ?>
                                <div class="col-span-2 mt-2">
                                    <span class="block text-sm font-medium text-gray-500">Exam Schedule</span>
                                    <div class="flex flex-wrap gap-4 mt-1">
                                        <!--[if BLOCK]><![endif]--><?php if($selectedExam->exam_date): ?>
                                        <span class="inline-flex items-center text-sm text-gray-900">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            <?php echo e(\Carbon\Carbon::parse($selectedExam->exam_date)->format('d M, Y')); ?>

                                        </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                        <!--[if BLOCK]><![endif]--><?php if($selectedExam->start_time && $selectedExam->end_time): ?>
                                        <span class="inline-flex items-center text-sm text-gray-900">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            <?php echo e(\Carbon\Carbon::parse($selectedExam->start_time)->format('h:i A')); ?> -
                                            <?php echo e(\Carbon\Carbon::parse($selectedExam->end_time)->format('h:i A')); ?>

                                        </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <!-- Classes and Sections -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-3 pb-2 border-b">Classes & Sections</h4>

                            <!--[if BLOCK]><![endif]--><?php if($selectedExam->examSchedules && $selectedExam->examSchedules->count() > 0): ?>
                                <?php
                                    // Group schedules by class
                                    $groupedSchedules = $selectedExam->examSchedules->groupBy(function($schedule) {
                                        return $schedule->myClass->id ?? 'unknown';
                                    });
                                ?>

                                <div class="space-y-3">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $groupedSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classId => $schedules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <!--[if BLOCK]><![endif]--><?php if($classId != 'unknown' && isset($schedules->first()->myClass)): ?>
                                            <div class="p-3 bg-gray-50 rounded-md">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <?php echo e($schedules->first()->myClass->name); ?>

                                                </span>

                                                <!-- Show sections -->
                                                <?php
                                                    $sections = $schedules->pluck('section')->filter()->unique('id');
                                                ?>

                                                <!--[if BLOCK]><![endif]--><?php if($sections->count() > 0): ?>
                                                    <div class="mt-2 ml-2">
                                                        <span class="text-xs text-gray-500">Sections:</span>
                                                        <div class="flex flex-wrap gap-2 mt-1">
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                                    <?php echo e($section->name); ?>

                                                                </span>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                        </div>
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            <?php else: ?>
                                <div class="text-sm text-gray-500 py-2">No classes assigned to this exam.</div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Instructions -->
                        <!--[if BLOCK]><![endif]--><?php if($selectedExam->instructions): ?>
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-3 pb-2 border-b">Instructions</h4>
                            <div class="p-3 bg-yellow-50 rounded-md text-sm text-gray-800">
                                <?php echo e($selectedExam->instructions); ?>

                            </div>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Creation Information -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-3 pb-2 border-b">System Information</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Created</span>
                                    <span class="block mt-1 text-sm text-gray-900"><?php echo e($selectedExam->created_at->format('d M, Y H:i A')); ?></span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Last Updated</span>
                                    <span class="block mt-1 text-sm text-gray-900"><?php echo e($selectedExam->updated_at->format('d M, Y H:i A')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No exam data available</h3>
                        <p class="mt-1 text-sm text-gray-500">Unable to load the selected exam details.</p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                <!--[if BLOCK]><![endif]--><?php if($selectedExam && Qs::isAdministratorOrTeacher()): ?>
                <button wire:click="edit(<?php echo e($selectedExam->id ?? 0); ?>)" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                    Edit Exam
                </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <button wire:click="closeExamDetails" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH C:\projects\MbukuErp\resources\views/livewire/exams/partials/modals/exam-details.blade.php ENDPATH**/ ?>