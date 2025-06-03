<div>
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <!-- Header Section -->
        <?php echo $__env->make('livewire.attendance.partials.student-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <!-- Statistics and Charts Section -->
        <?php echo $__env->make('livewire.attendance.partials.student-stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <!-- Attendance History Table Section -->
        <?php echo $__env->make('livewire.attendance.partials.student-history-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('livewire:init', function () {
        // Listen for notifications from the component
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').on('notify', param => {
            toastr[param.type](param.message);
        });
        
        // Initialize date picker
        flatpickr("#student-date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('startDate', selectedDates[0].toISOString().slice(0, 10));
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('endDate', selectedDates[1].toISOString().slice(0, 10));
                }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/student-attendance-history.blade.php ENDPATH**/ ?>