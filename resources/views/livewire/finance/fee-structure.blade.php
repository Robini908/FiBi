<!-- Fee Structure Component View -->
<div 
    x-data="{ 
        showFormModal: @entangle('showModal').live, 
        showViewModal: false, 
        showDeleteModal: false
    }"
    x-on:open-form-modal.window="showFormModal = true"
    x-on:close-form-modal.window="showFormModal = false"
    x-on:open-view-modal.window="showViewModal = true"
    x-on:close-view-modal.window="showViewModal = false"
    x-on:open-delete-modal.window="showDeleteModal = true"
    x-on:close-delete-modal.window="showDeleteModal = false"
    class="py-4 px-4 md:px-6 space-y-6">

    <!-- Page Header -->
    @include('livewire.finance.partials.fee-structure-header')

    <!-- Summary Cards -->
    @include('livewire.finance.partials.fee-structure-summary')
    
    <!-- Fee Structure Table -->
    @include('livewire.finance.partials.fee-structure-table')

    <!-- Modals -->
    @include('livewire.finance.partials.fee-structure-form-modal')
    @include('livewire.finance.partials.fee-structure-view-modal')
    @include('livewire.finance.partials.fee-structure-delete-modal')

    <!-- Toast Notifications -->
    
</div>
