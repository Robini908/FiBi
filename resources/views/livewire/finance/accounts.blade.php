    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Header Section -->
    @include('livewire.finance.partials.account-header')
    
    <!-- Summary Section -->
    @include('livewire.finance.partials.account-summary')
    
    <!-- Filters Section -->
    @include('livewire.finance.partials.account-filters')
    
    <!-- Table Section -->
        <div class="bg-white shadow-lg rounded-sm border border-gray-200 mb-8">
            <div class="p-3">
            @include('livewire.finance.partials.account-table')
        </div>
    </div>

    <!-- Form Modal -->
    @include('livewire.finance.partials.account-form-modal')
    
    <!-- Delete Confirmation Modal -->
    @include('livewire.finance.partials.account-delete-modal')
</div>
