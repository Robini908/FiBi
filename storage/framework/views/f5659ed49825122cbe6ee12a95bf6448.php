<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Header and Action Buttons Partial -->
    <?php echo $__env->make('livewire.finance.partials.student-arrears-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Arrears Summary Cards Partial -->
    <?php echo $__env->make('livewire.finance.partials.student-arrears-summary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Search and Filters Partial -->
    <?php echo $__env->make('livewire.finance.partials.student-arrears-filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Arrears Table Partial -->
    <?php echo $__env->make('livewire.finance.partials.student-arrears-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Add/Edit Arrear Modal Partial -->
    <?php echo $__env->make('livewire.finance.partials.student-arrears-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Generate Arrears Modal Partial -->
    <?php echo $__env->make('livewire.finance.partials.student-arrears-generate-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/student-arrears.blade.php ENDPATH**/ ?>