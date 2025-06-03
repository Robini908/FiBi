<div class="border-b border-gray-200 bg-white px-6 py-5 shadow-sm">
    <div class="flex flex-wrap items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">
                <!--[if BLOCK]><![endif]--><?php if($classId && $sectionId && isset($class) && isset($section)): ?>
                    Take Attendance - <?php echo e($class->name ?? 'Class'); ?> <?php echo e($section->name ?? 'Section'); ?>

                <?php else: ?>
                    Take Attendance
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </h1>
            <p class="mt-1 text-sm text-gray-500 flex items-center">
                <svg class="mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                <?php echo e(now()->format('l, F j, Y')); ?>

            </p>
        </div>
        
        <!--[if BLOCK]><![endif]--><?php if($classId && $sectionId): ?>
            <div class="flex space-x-3 items-center mt-3 sm:mt-0">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <svg class="mr-1 h-3.5 w-3.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                    </svg>
                    <?php echo e(count($students)); ?> Students
                </span>
                
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($existingRecord ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                    <!--[if BLOCK]><![endif]--><?php if($existingRecord): ?>
                        <svg class="mr-1 h-3.5 w-3.5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Updated
                    <?php else: ?>
                        <svg class="mr-1 h-3.5 w-3.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                        New Record
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </span>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/partials/take-header.blade.php ENDPATH**/ ?>