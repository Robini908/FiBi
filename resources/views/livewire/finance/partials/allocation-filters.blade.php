<div class="p-6">
    <!-- Search and Filters -->
    <div class="flex flex-col md:flex-row justify-between space-y-4 md:space-y-0 md:items-center">
        <div class="max-w-lg w-full lg:max-w-md">
            <label for="search" class="sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    id="search" 
                    name="search" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm shadow-sm transition-all duration-150" 
                    placeholder="Search allocations..." 
                    type="search"
                >
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
            <div>
                <button
                    type="button"
                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                    x-data="{ open: false }"
                    @click="open = !open"
                    x-on:click.away="open = false"
                >
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                    <svg class="-mr-1 ml-2 h-5 w-5 text-gray-400" x-bind:class="{ 'transform rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    
                    <!-- Filter dropdown -->
                    <div 
                        x-show="open" 
                        x-transition:enter="transition ease-out duration-100" 
                        x-transition:enter-start="transform opacity-0 scale-95" 
                        x-transition:enter-end="transform opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-75" 
                        x-transition:leave-start="transform opacity-100 scale-100" 
                        x-transition:leave-end="transform opacity-0 scale-95" 
                        class="origin-top-right absolute right-0 mt-2 w-72 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10"
                        style="margin-top: 40px;"
                        x-cloak
                    >
                        <div class="px-4 py-3">
                            <h3 class="text-sm font-medium text-gray-900">Finance Account</h3>
                            <div class="mt-2">
                                <select
                                    wire:model.live="financeAccountFilter"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                >
                                    <option value="">All Accounts</option>
                                    @foreach($financeAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
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
                        
                        <div class="px-4 py-3">
                            <h3 class="text-sm font-medium text-gray-900">Status</h3>
                            <div class="mt-2 grid grid-cols-1 gap-y-2">
                                <div class="flex items-center">
                                    <input
                                        id="status_pending"
                                        name="status_filter"
                                        wire:model.live="statusFilter"
                                        value="0"
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
                                        value="1"
                                        type="radio"
                                        class="h-4 w-4 border-gray-300 text-green-600 focus:ring-green-500"
                                    >
                                    <label for="status_approved" class="ml-3 text-sm text-gray-700">Approved</label>
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
                        
                        <div class="px-4 py-3">
                            <h3 class="text-sm font-medium text-gray-900">Academic Period</h3>
                            <div class="mt-2 space-y-3">
                                <div>
                                    <label for="year_filter" class="block text-sm text-gray-700">Year</label>
                                    <select
                                        id="year_filter"
                                        wire:model.live="yearFilter"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                    >
                                        <option value="">All Years</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="term_filter" class="block text-sm text-gray-700">Term</label>
                                    <select
                                        id="term_filter"
                                        wire:model.live="termFilter"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                    >
                                        <option value="">All Terms</option>
                                        <option value="1">Term 1</option>
                                        <option value="2">Term 2</option>
                                        <option value="3">Term 3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-4 py-3">
                            <div class="flex justify-between">
                                <button
                                    wire:click="resetFilters"
                                    type="button"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                                >
                                    Reset
                                </button>
                                <button
                                    @click="open = false"
                                    type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                                >
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </button>
            </div>
            
            <div>
                <button
                    wire:click="exportAllocations"
                    type="button"
                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                >
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>
    
    <!-- Filter Tags -->
    <div class="mt-4 flex flex-wrap gap-2">
        @if($search)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 shadow-sm">
                Search: {{ $search }}
                <button wire:click="$set('search', '')" type="button" class="ml-1 inline-flex flex-shrink-0 h-4 w-4 rounded-full p-1 text-green-600 hover:bg-green-200 hover:text-green-500 focus:outline-none focus:bg-green-500 focus:text-white transition-colors duration-150">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove search filter</span>
                </button>
            </span>
        @endif
        
        @if($financeAccountFilter)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 shadow-sm">
                Account: {{ $financeAccounts->where('id', $financeAccountFilter)->first()->name ?? 'Unknown' }}
                <button wire:click="$set('financeAccountFilter', '')" type="button" class="ml-1 inline-flex flex-shrink-0 h-4 w-4 rounded-full p-1 text-blue-600 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white transition-colors duration-150">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove account filter</span>
                </button>
            </span>
        @endif
        
        @if($voteheadFilter)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 shadow-sm">
                Votehead: {{ $voteheads->where('id', $voteheadFilter)->first()->name ?? 'Unknown' }}
                <button wire:click="$set('voteheadFilter', '')" type="button" class="ml-1 inline-flex flex-shrink-0 h-4 w-4 rounded-full p-1 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-500 focus:outline-none focus:bg-indigo-500 focus:text-white transition-colors duration-150">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove votehead filter</span>
                </button>
            </span>
        @endif
        
        @if($statusFilter !== '')
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 shadow-sm">
                Status: {{ $statusFilter == '1' ? 'Approved' : 'Pending' }}
                <button wire:click="$set('statusFilter', '')" type="button" class="ml-1 inline-flex flex-shrink-0 h-4 w-4 rounded-full p-1 text-purple-600 hover:bg-purple-200 hover:text-purple-500 focus:outline-none focus:bg-purple-500 focus:text-white transition-colors duration-150">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove status filter</span>
                </button>
            </span>
        @endif
        
        @if($yearFilter || $termFilter)
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 shadow-sm">
                Period: {{ $yearFilter ? $yearFilter : 'Any Year' }} {{ $termFilter ? '/ Term ' . $termFilter : '' }}
                <button wire:click="resetPeriodFilters" type="button" class="ml-1 inline-flex flex-shrink-0 h-4 w-4 rounded-full p-1 text-yellow-600 hover:bg-yellow-200 hover:text-yellow-500 focus:outline-none focus:bg-yellow-500 focus:text-white transition-colors duration-150">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                    <span class="sr-only">Remove period filter</span>
                </button>
            </span>
        @endif
    </div>
    
    <!-- Summary Stats -->
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Allocations</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $allocations->total() }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Approved Allocations</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $allocations->where('is_approved', true)->count() }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Amount</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">KES {{ number_format($allocations->sum('amount'), 2) }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 