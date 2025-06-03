<div class="rounded-lg bg-gray-50 p-4 mb-5 border border-gray-200 shadow-sm">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3 flex-1 md:flex md:justify-between">
            <div>
                <h3 class="text-sm font-medium text-gray-800">Editing Existing Record</h3>
                <div class="mt-2 text-sm text-gray-600">
                    <p>You are updating an existing attendance record for <?php echo e(Carbon\Carbon::parse($attendanceDate)->format('M d, Y')); ?> (<?php echo e($sessionTypes[$sessionType]); ?>).</p>
                </div>
            </div>
            <div class="mt-3 flex-shrink-0 md:mt-0 md:ml-6">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <?php echo e($existingRecord->attendanceDetails->count()); ?> entries
                </span>
                <span class="ml-2 text-sm text-gray-500">Last updated: <?php echo e($existingRecord->updated_at->diffForHumans()); ?></span>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/partials/take-existing-notice.blade.php ENDPATH**/ ?>