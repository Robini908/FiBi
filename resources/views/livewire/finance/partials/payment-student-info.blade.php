<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white flex flex-col md:flex-row justify-between">
        <div>
            <div class="flex items-center">
                <h2 class="text-lg font-medium text-gray-900">{{ $student_name }}</h2>
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $student_class }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-600">
                Academic Year: {{ $academic_year ?? date('Y') }} | Term: {{ $term ?? '1' }}
            </p>
        </div>
        
        <div class="mt-4 md:mt-0 flex space-x-2">
            <button 
                wire:click="clearStudent" 
                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Selection
            </button>
            
            @if($hasManagePermission)
            <button 
                wire:click="openModal" 
                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Record Payment
            </button>
            @endif
        </div>
    </div>
    
    <div class="p-6">
        <!-- Fee Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 shadow-sm">
                <div class="flex justify-between items-center">
                    <div class="text-sm font-medium text-gray-500">Total Fees</div>
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="mt-1 text-2xl font-semibold text-gray-900">Ksh {{ number_format($total_fees ?? 0, 2) }}</div>
                <div class="mt-1 text-xs text-gray-500">Total fees for {{ $student_class }}</div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 shadow-sm">
                <div class="flex justify-between items-center">
                    <div class="text-sm font-medium text-gray-500">Amount Paid</div>
                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="mt-1 text-2xl font-semibold text-gray-900">Ksh {{ number_format($paid_amount ?? 0, 2) }}</div>
                <div class="mt-1 text-xs text-gray-500">Total paid for {{ $academic_year ?? date('Y') }}, Term {{ $term ?? '1' }}</div>
            </div>
            
            <div class="@if($fee_balance > 0) bg-gradient-to-br from-red-50 to-red-100 @else bg-gradient-to-br from-green-50 to-green-100 @endif rounded-lg p-4 shadow-sm">
                <div class="flex justify-between items-center">
                    <div class="text-sm font-medium text-gray-500">Balance</div>
                    <svg class="h-8 w-8 @if($fee_balance > 0) text-red-500 @else text-green-500 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="mt-1 text-2xl font-semibold @if($fee_balance > 0) text-red-600 @else text-green-600 @endif">
                    Ksh {{ number_format($fee_balance ?? 0, 2) }}
                </div>
                <div class="mt-1 text-xs text-gray-500">
                    @if($fee_balance > 0)
                        Outstanding balance
                    @else
                        Fully paid
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Payment Progress Bar -->
        @if($total_fees > 0)
            <div class="mt-6">
                <div class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                    <span>Payment Progress</span>
                    <span>{{ number_format(($paid_amount / $total_fees) * 100, 0) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($paid_amount / $total_fees) * 100 }}%"></div>
                </div>
            </div>
        @endif
    </div>
</div> 