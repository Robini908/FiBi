<div class="bg-gray-50 min-h-screen py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        @include('livewire.finance.partials.allocation-header')
        
        <!-- Card container with shadow -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Filters and Summary -->
            <div class="border-b border-gray-100">
                @include('livewire.finance.partials.allocation-filters')
            </div>
            
            <!-- Allocations Table -->
            <div>
                @include('livewire.finance.partials.allocation-table')
            </div>
        </div>
        
        <!-- Allocation Form Modal -->
        @include('livewire.finance.partials.allocation-form-modal')
        
        <!-- Delete Confirmation Modal -->
        @include('livewire.finance.partials.allocation-delete-modal')
    </div>
</div> 