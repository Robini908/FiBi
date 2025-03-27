<div class="bg-white">
    <!-- Top App Bar - Google Material Design inspired -->
    <div class="bg-white border-b border-gray-100 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-medium text-gray-800">
                    Student Fee Management
                </h1>
            </div>
                <div class="flex items-center space-x-3">
                    <!-- Action Buttons -->
                    @if($hasPaymentPermission)
                <button 
                        @if($selectedStudentId) 
                            wire:click="openPaymentForm" 
                        @endif
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-150 ease-in-out {{ !$selectedStudentId ? 'opacity-50 cursor-not-allowed' : '' }}"
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
                        class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-150 ease-in-out"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                        {{ $showFilters ? 'Hide Filters' : 'Show Filters' }}
                </button>
                
                @if($hasManagePermission)
                <button 
                    wire:click="exportPayments" 
                        class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-150 ease-in-out"
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
    </div>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Filters Section -->
        <div 
            x-data="{ show: @entangle('showFilters') }" 
            x-show="show" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="mb-6"
        >
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-800">Filters</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
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
                        placeholder="Search by name, admission no..."
                    >
                    <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
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
                <div wire:loading wire:target="classFilter" class="mt-1 text-sm text-gray-500">
                    Loading...
                </div>
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
                <label for="paymentStatusFilter" class="block text-sm font-medium text-gray-700">Payment Status</label>
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
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-150 ease-in-out"
                >
                    Apply Filters
                </button>
                <button 
                    wire:click="resetFilters" 
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all duration-150 ease-in-out"
                >
                    Reset
                </button>
                        </div>
                    </div>
            </div>
        </div>
    </div>

        <!-- Role-specific content -->
        @if(!$selectedStudentId)
            <!-- Dashboard View -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Stats Cards -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-50 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                        <div class="text-sm font-medium text-gray-500">Total Payments</div>
                            <div class="mt-1 text-xl font-semibold text-gray-900">{{ $totalPaymentsCount }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-50 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                        <div class="text-sm font-medium text-gray-500">Total Amount</div>
                            <div class="mt-1 text-xl font-semibold text-gray-900">Ksh {{ number_format($totalPaymentsAmount, 2) }}</div>
                        </div>
                    </div>
                </div>
                
                @if($hasManagePermission)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-50 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Collection Rate</div>
                            <div class="mt-1 text-xl font-semibold text-gray-900">{{ $totalPaymentsCount > 0 ? round(($totalPaymentsAmount / $totalPaymentsCount), 2) : 0 }} avg.</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Student Selection -->
            @include('livewire.finance.partials.payment-student-selector')
            
            <!-- Recent Payments -->
            @if($recentPayments->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mt-6">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-800">Recent Payments</h2>
                    </div>
                    <div class="overflow-x-auto">
                @include('livewire.finance.partials.payment-table')
                    </div>
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-100 mt-6">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-base font-medium text-gray-900">No payments found</h3>
                    <p class="mt-1 text-sm text-gray-500">Start by selecting a student or recording a new payment.</p>
                </div>
            @endif
            
        @else
            <!-- Student Fee Information -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-medium text-gray-900">{{ $student_name }}</h2>
                    <p class="text-sm text-gray-600">{{ $student_class }}</p>
                </div>
                <button 
                    wire:click="backToSelection" 
                    class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </button>
            </div>
            
            @include('livewire.finance.partials.payment-student-info')
            
            <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Payment Form -->
                @if($showPaymentForm && $hasPaymentPermission)
                    <div class="lg:col-span-5">
                    @include('livewire.finance.partials.payment-form')
                </div>
                    <div class="lg:col-span-7">
                        @include('livewire.finance.partials.payment-history')
                    </div>
                @else
                    <div class="lg:col-span-12">
                    @include('livewire.finance.partials.payment-history')
                </div>
                @endif
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