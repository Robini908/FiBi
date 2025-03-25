<div class="bg-white rounded-lg shadow-sm">
    <!-- Header Section -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between space-y-3 md:space-y-0">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    Student Fee Management
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    Record, track, and manage student fee payments with ease
                </p>
            </div>
            <div class="flex space-x-2">
                @if($hasManagePermission)
                <button 
                    @if($selectedStudentId) wire:click="openPaymentForm" @endif
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-50 transition {{ !$selectedStudentId ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ !$selectedStudentId ? 'disabled' : '' }}
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Record Payment
                </button>
                @endif
                
                <button 
                    wire:click="toggleFilters" 
                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 disabled:opacity-50 transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    {{ $showFilters ? 'Hide Filters' : 'Show Filters' }}
                </button>
                
                @if($hasManagePermission)
                <button 
                    wire:click="exportPayments" 
                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 disabled:opacity-50 transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters Section (Toggleable) -->
    <div x-data="{ show: @entangle('showFilters') }" x-show="show" x-transition.duration.250ms class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Box -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        id="search" 
                        class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md" 
                        placeholder="Receipt #, Student name...">
                </div>
            </div>

            <!-- Class Filter -->
            <div>
                <label for="classFilter" class="block text-sm font-medium text-gray-700">Class</label>
                <select 
                    wire:model.live="classFilter" 
                    id="classFilter" 
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                >
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Academic Year Filter -->
            <div>
                <label for="yearFilter" class="block text-sm font-medium text-gray-700">Academic Year</label>
                <select 
                    wire:model.live="yearFilter" 
                    id="yearFilter" 
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                >
                    <option value="">All Years</option>
                    @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <!-- Term Filter -->
            <div>
                <label for="termFilter" class="block text-sm font-medium text-gray-700">Term</label>
                <select 
                    wire:model.live="termFilter" 
                    id="termFilter" 
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                >
                    <option value="">All Terms</option>
                    <option value="1">Term 1</option>
                    <option value="2">Term 2</option>
                    <option value="3">Term 3</option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div>
                <label for="paymentStatusFilter" class="block text-sm font-medium text-gray-700">Status</label>
                <select 
                    wire:model.live="paymentStatusFilter" 
                    id="paymentStatusFilter" 
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                >
                    <option value="">All Statuses</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <!-- Date Filters -->
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input 
                    wire:model.live="startDate" 
                    type="date" 
                    id="startDate" 
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                >
            </div>
            
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                <input 
                    wire:model.live="endDate" 
                    type="date" 
                    id="endDate" 
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                >
            </div>
            
            <!-- Filter Actions -->
            <div class="flex items-end space-x-2">
                <button 
                    wire:click="applyFilters" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-50 transition"
                >
                    Apply Filters
                </button>
                <button 
                    wire:click="resetFilters" 
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 disabled:opacity-50 transition"
                >
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-6 py-4">
        @if(!$selectedStudentId)
            <!-- Student Selection View -->
            @include('livewire.finance.partials.payment-student-selector')
            
            <!-- Recent Payments Summary -->
            <div class="mt-6">
                <h2 class="text-lg font-medium text-gray-900">Recent Payments</h2>
                <div class="mt-2 flex flex-wrap gap-4">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 shadow-sm w-full sm:w-64">
                        <div class="text-sm font-medium text-gray-500">Total Payments</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalPaymentsCount }}</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 shadow-sm w-full sm:w-64">
                        <div class="text-sm font-medium text-gray-500">Total Amount</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900">Ksh {{ number_format($totalPaymentsAmount, 2) }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Payments Table -->
            @if($recentPayments->count() > 0)
                @include('livewire.finance.partials.payment-table')
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No payments found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by selecting a student or recording a new payment.</p>
                </div>
            @endif
        @else
            <!-- Student Fee Information -->
            @include('livewire.finance.partials.payment-student-info')
            
            <div class="mt-8 grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Payment Form -->
                <div class="md:col-span-5">
                    @include('livewire.finance.partials.payment-form')
                </div>
                
                <!-- Payment History -->
                <div class="md:col-span-7">
                    @include('livewire.finance.partials.payment-history')
                </div>
            </div>
        @endif
    </div>

    <!-- Modals -->
    @include('livewire.finance.partials.payment-receipt')
    @include('livewire.finance.partials.payment-actions-modal')
    
    <!-- Toast notification container -->
    <div 
        x-data="{ show: false, message: '', type: 'success' }"
        @notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => { show = false }, 3000)"
        x-show="show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-0 right-0 z-50 m-4 max-w-sm w-full bg-white rounded-lg shadow-lg border-l-4"
        :class="{ 
            'border-green-500': type === 'success',
            'border-red-500': type === 'error',
            'border-yellow-500': type === 'warning',
            'border-blue-500': type === 'info'
        }"
    >
        <div class="flex p-4">
            <div class="flex-shrink-0">
                <template x-if="type === 'success'">
                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="type === 'error'">
                    <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="type === 'warning'">
                    <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <template x-if="type === 'info'">
                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                </div>
            <div class="ml-3 w-0 flex-1">
                <p x-text="message" class="text-sm leading-5 font-medium text-gray-900"></p>
                    </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>
            </div>
        </div>
</div> 