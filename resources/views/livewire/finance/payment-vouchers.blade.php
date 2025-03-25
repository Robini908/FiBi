<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Header Section -->
    @include('livewire.finance.partials.payment-voucher-header')
    
    <!-- Summary Section -->
    @include('livewire.finance.partials.payment-voucher-summary')
    
    <!-- Filters Section -->
    @include('livewire.finance.partials.payment-voucher-filters')
    
    <!-- Table Section -->
    <div class="bg-white shadow-lg rounded-sm border border-gray-200 mb-8">
        <div class="p-3">
            @include('livewire.finance.partials.payment-voucher-table')
        </div>
    </div>
    
    <!-- Form Modal -->
    @include('livewire.finance.partials.payment-voucher-form-modal')
    
    <!-- View Modal -->
    @include('livewire.finance.partials.payment-voucher-view-modal')
    
    <!-- Delete Confirmation Modal -->
    @include('livewire.finance.partials.payment-voucher-delete-modal')
    
    <!-- Approve Confirmation Modal -->
    @include('livewire.finance.partials.payment-voucher-approve-modal')
    
    <!-- Pay Confirmation Modal -->
    @include('livewire.finance.partials.payment-voucher-pay-modal')
    
    <!-- Cancel Confirmation Modal -->
    @include('livewire.finance.partials.payment-voucher-cancel-modal')
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
