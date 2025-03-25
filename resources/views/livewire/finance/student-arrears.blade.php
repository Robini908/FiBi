<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Header and Action Buttons Partial -->
    @include('livewire.finance.partials.student-arrears-header')
    
    <!-- Arrears Summary Cards Partial -->
    @include('livewire.finance.partials.student-arrears-summary')
    
    <!-- Search and Filters Partial -->
    @include('livewire.finance.partials.student-arrears-filters')
    
    <!-- Arrears Table Partial -->
    @include('livewire.finance.partials.student-arrears-table')
            </div>
            
<!-- Add/Edit Arrear Modal Partial -->
@include('livewire.finance.partials.student-arrears-modal')

<!-- Generate Arrears Modal Partial -->
@include('livewire.finance.partials.student-arrears-generate-modal')
