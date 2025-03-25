<div class="p-4 sm:p-6">
    <!-- Search and Filters Row -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        <!-- Search Field -->
        <div class="w-full md:w-2/5">
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="search" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out sm:text-sm"
                    placeholder="Search by voucher #, recipient, purpose..." 
                >
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2">
            <!-- Filter Dropdown -->
            <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block text-left">
                <button 
                    @click="open = !open" 
                    type="button" 
                    class="inline-flex justify-center items-center w-full px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                >
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                    <svg class="-mr-1 ml-2 h-5 w-5 text-gray-400" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <!-- Filter Dropdown Panel -->
                <div 
                    x-show="open" 
                    x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="transform opacity-0 scale-95" 
                    x-transition:enter-end="transform opacity-100 scale-100" 
                    x-transition:leave="transition ease-in duration-75" 
                    x-transition:leave-start="transform opacity-100 scale-100" 
                    x-transition:leave-end="transform opacity-0 scale-95" 
                    class="origin-top-right absolute right-0 mt-2 w-72 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50"
                    style="margin-top: 10px;"
                    x-cloak
                >
                    <!-- Votehead Filter -->
                    <div class="px-4 py-3">
                        <h3 class="text-sm font-medium text-gray-900">Votehead</h3>
                        <div class="mt-2">
                            <select
                                wire:model.live="voteheadFilter"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                            >
                                <option value="">All Voteheads</option>
                                @foreach($voteheads as $votehead)
                                    <option value="{{ $votehead->id }}">{{ $votehead->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Academic Period Filter -->
                    <div class="px-4 py-3">
                        <h3 class="text-sm font-medium text-gray-900">Academic Period</h3>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <label for="year_filter" class="sr-only">Year</label>
                                <select
                                    id="year_filter"
                                    wire:model.live="yearFilter"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                >
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="term_filter" class="sr-only">Term</label>
                                <select
                                    id="term_filter"
                                    wire:model.live="termFilter"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                >
                                    <option value="">All Terms</option>
                                    <option value="1">Term 1</option>
                                    <option value="2">Term 2</option>
                                    <option value="3">Term 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="px-4 py-3">
                        <h3 class="text-sm font-medium text-gray-900">Status</h3>
                        <div class="mt-2 grid grid-cols-1 gap-y-2">
                            <div class="flex items-center">
                                <input
                                    id="status_pending"
                                    name="status_filter"
                                    wire:model.live="statusFilter"
                                    value="pending"
                                    type="radio"
                                    class="h-4 w-4 border-gray-300 text-green-600 focus:ring-green-500"
                                >
                                <label for="status_pending" class="ml-3 text-sm text-gray-700">Pending</label>
                            </div>
                            <div class="flex items-center">
                                <input
                                    id="status_approved"
                                    name="status_filter"
                                    wire:model.live="statusFilter"
                                    value="approved"
                                    type="radio"
                                    class="h-4 w-4 border-gray-300 text-green-600 focus:ring-green-500"
                                >
                                <label for="status_approved" class="ml-3 text-sm text-gray-700">Approved</label>
                            </div>
                            <div class="flex items-center">
                                <input
                                    id="status_paid"
                                    name="status_filter"
                                    wire:model.live="statusFilter"
                                    value="paid"
                                    type="radio"
                                    class="h-4 w-4 border-gray-300 text-green-600 focus:ring-green-500"
                                >
                                <label for="status_paid" class="ml-3 text-sm text-gray-700">Paid</label>
                            </div>
                            <div class="flex items-center">
                                <input
                                    id="status_cancelled"
                                    name="status_filter"
                                    wire:model.live="statusFilter"
                                    value="cancelled"
                                    type="radio"
                                    class="h-4 w-4 border-gray-300 text-green-600 focus:ring-green-500"
                                >
                                <label for="status_cancelled" class="ml-3 text-sm text-gray-700">Cancelled</label>
                            </div>
                            <div class="flex items-center">
                                <input
                                    id="status_all"
                                    name="status_filter"
                                    wire:model.live="statusFilter"
                                    value=""
                                    type="radio"
                                    class="h-4 w-4 border-gray-300 text-green-600 focus:ring-green-500"
                                >
                                <label for="status_all" class="ml-3 text-sm text-gray-700">All Statuses</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Date Range Filter -->
                    <div class="px-4 py-3">
                        <h3 class="text-sm font-medium text-gray-900">Date Range</h3>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <label for="date_from" class="block text-xs text-gray-500 mb-1">From</label>
                                <input
                                    type="date"
                                    id="date_from"
                                    wire:model.live="dateFrom"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                >
                            </div>
                            <div>
                                <label for="date_to" class="block text-xs text-gray-500 mb-1">To</label>
                                <input
                                    type="date"
                                    id="date_to"
                                    wire:model.live="dateTo"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Actions -->
                    <div class="px-4 py-3">
                        <div class="flex justify-between">
                            <button
                                wire:click="resetFilters"
                                type="button"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                            >
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </button>
                            <button
                                @click="open = false"
                                type="button"
                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                            >
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($isAdmin || $isAccountant)
            <!-- Export Button -->
            <button
                wire:click="exportVouchers"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
            >
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                Export
            </button>
            @endif
        </div>
    </div>
    
    <!-- Active Filters -->
    <div class="mt-4 flex flex-wrap gap-2">
        @if($search)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <span>Search: {{ $search }}</span>
                <button wire:click="$set('search', '')" type="button" class="ml-2 inline-flex items-center justify-center rounded-full h-4 w-4 p-1 text-green-600 hover:bg-green-200">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove search filter</span>
                </button>
            </span>
        @endif
        
        @if($voteheadFilter)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                <span>Votehead: {{ $voteheads->where('id', $voteheadFilter)->first()->name ?? 'Unknown' }}</span>
                <button wire:click="$set('voteheadFilter', '')" type="button" class="ml-2 inline-flex items-center justify-center rounded-full h-4 w-4 p-1 text-blue-600 hover:bg-blue-200">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove votehead filter</span>
                </button>
            </span>
        @endif
        
        @if($yearFilter || $termFilter)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                <span>Period: {{ $yearFilter ? 'Year '.$yearFilter : '' }}{{ $yearFilter && $termFilter ? ', ' : '' }}{{ $termFilter ? 'Term '.$termFilter : '' }}</span>
                <button wire:click="$set('yearFilter', ''); $set('termFilter', '');" type="button" class="ml-2 inline-flex items-center justify-center rounded-full h-4 w-4 p-1 text-indigo-600 hover:bg-indigo-200">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove period filter</span>
                </button>
            </span>
        @endif
        
        @if($statusFilter)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                <span>Status: {{ ucfirst($statusFilter) }}</span>
                <button wire:click="$set('statusFilter', '')" type="button" class="ml-2 inline-flex items-center justify-center rounded-full h-4 w-4 p-1 text-purple-600 hover:bg-purple-200">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove status filter</span>
                </button>
            </span>
        @endif

        @if($dateFrom || $dateTo)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                <span>Date: {{ $dateFrom ? date('M d, Y', strtotime($dateFrom)) : 'Any' }} to {{ $dateTo ? date('M d, Y', strtotime($dateTo)) : 'Any' }}</span>
                <button wire:click="resetDateFilters" type="button" class="ml-2 inline-flex items-center justify-center rounded-full h-4 w-4 p-1 text-yellow-600 hover:bg-yellow-200">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove date filter</span>
                </button>
            </span>
        @endif
        
        @if($search || $voteheadFilter || $yearFilter || $termFilter || $statusFilter || $dateFrom || $dateTo)
            <button 
                wire:click="resetFilters"
                class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors duration-150"
            >
                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear all filters
            </button>
        @endif
    </div>
</div> 