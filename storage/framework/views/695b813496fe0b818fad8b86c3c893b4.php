<div class="bg-gray-50 min-h-screen">
    <!-- Main container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <?php echo $__env->make('livewire.attendance.partials.take-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <!-- Content area -->
        <div class="mt-6 pb-12">
            <!--[if BLOCK]><![endif]--><?php if(!$classId || !$sectionId): ?>
                <!-- Class and Section Selector when not selected -->
                <?php echo $__env->make('livewire.attendance.partials.take-class-selector', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <!-- Attendance Form when class and section are selected -->
                <form wire:submit.prevent="save">
                    <!-- Existing record notice -->
                    <!--[if BLOCK]><![endif]--><?php if($existingRecord): ?>
                        <?php echo $__env->make('livewire.attendance.partials.take-existing-notice', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
                    <div class="grid grid-cols-1 gap-y-6">
                        <!-- Date and Session Controls -->
                        <?php echo $__env->make('livewire.attendance.partials.take-date-controls', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                        <!-- Bulk Actions -->
                        <?php echo $__env->make('livewire.attendance.partials.take-bulk-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                        <!-- Students Table -->
                        <?php echo $__env->make('livewire.attendance.partials.take-students-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                        <!-- General Remarks -->
                        <?php echo $__env->make('livewire.attendance.partials.take-remarks-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                        <!-- Form Actions -->
                        <?php echo $__env->make('livewire.attendance.partials.take-form-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </form>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    
    <!-- Success Message Toast -->
    <?php echo $__env->make('livewire.attendance.partials.take-success-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('notify', param => {
            toastr[param.type](param.message);
        });
    });
</script>
<?php $__env->stopPush(); ?> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/take-attendance.blade.php ENDPATH**/ ?>