    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Header Section -->
    <?php echo $__env->make('livewire.finance.partials.account-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Summary Section -->
    <?php echo $__env->make('livewire.finance.partials.account-summary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Filters Section -->
    <?php echo $__env->make('livewire.finance.partials.account-filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Table Section -->
        <div class="bg-white shadow-lg rounded-sm border border-gray-200 mb-8">
            <div class="p-3">
            <?php echo $__env->make('livewire.finance.partials.account-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    <!-- Form Modal -->
    <?php echo $__env->make('livewire.finance.partials.account-form-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Delete Confirmation Modal -->
    <?php echo $__env->make('livewire.finance.partials.account-delete-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/accounts.blade.php ENDPATH**/ ?>