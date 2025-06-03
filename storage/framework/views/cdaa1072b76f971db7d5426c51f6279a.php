<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Header Section -->
    <?php echo $__env->make('livewire.finance.partials.payment-voucher-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Summary Section -->
    <?php echo $__env->make('livewire.finance.partials.payment-voucher-summary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Filters Section -->
    <?php echo $__env->make('livewire.finance.partials.payment-voucher-filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Table Section -->
    <div class="bg-white shadow-lg rounded-sm border border-gray-200 mb-8">
        <div class="p-3">
            <?php echo $__env->make('livewire.finance.partials.payment-voucher-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
    
    <!-- Form Modal -->
    <?php echo $__env->make('livewire.finance.partials.payment-voucher-form-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- View Modal -->
    <?php echo $__env->make('livewire.finance.partials.payment-voucher-view-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Delete Confirmation Modal -->
    <?php echo $__env->make('livewire.finance.partials.payment-voucher-delete-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Approve Confirmation Modal -->
    <?php echo $__env->make('livewire.finance.partials.payment-voucher-approve-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Pay Confirmation Modal -->
    <?php echo $__env->make('livewire.finance.partials.payment-voucher-pay-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Cancel Confirmation Modal -->
    <?php echo $__env->make('livewire.finance.partials.payment-voucher-cancel-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<!-- Alpine.js Print Script -->
<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('printVoucher', function () {
            let printContents = document.getElementById('voucher-to-print').innerHTML;
            let originalContents = document.body.innerHTML;
            
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            
            // Re-initialize Alpine after reprinting
            Alpine.initTree(document.body);
        });
    });
</script>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/payment-vouchers.blade.php ENDPATH**/ ?>