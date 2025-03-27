<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <div class="flex items-center">
                    <h2 class="text-lg font-medium text-gray-800">Fee Information</h2>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $student_class }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-600">
                    Academic Year: {{ $academic_year ?? date('Y') }} | Term: {{ $term ?? '1' }}
                </p>
            </div>
            
            @if($hasPaymentPermission && !$showPaymentForm)
            <button 
                wire:click="openPaymentForm" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-medium text-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-150 ease-in-out"
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
            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                <div class="flex items-start">
                    <div class="p-3 rounded-full bg-green-50 mr-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Total Fees</div>
                        <div class="mt-1 text-xl font-semibold text-gray-900">Ksh {{ number_format($total_fees ?? 0, 2) }}</div>
                        <div class="mt-1 text-xs text-gray-500">Total fees for {{ $student_class }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                <div class="flex items-start">
                    <div class="p-3 rounded-full bg-blue-50 mr-4">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Amount Paid</div>
                        <div class="mt-1 text-xl font-semibold text-gray-900">Ksh {{ number_format($paid_amount ?? 0, 2) }}</div>
                        <div class="mt-1 text-xs text-gray-500">Total paid for {{ $academic_year ?? date('Y') }}, Term {{ $term ?? '1' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                <div class="flex items-start">
                    <div class="p-3 rounded-full {{ $fee_balance > 0 ? 'bg-red-50' : 'bg-green-50' }} mr-4">
                        <svg class="h-6 w-6 {{ $fee_balance > 0 ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Balance</div>
                        <div class="mt-1 text-xl font-semibold {{ $fee_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
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
            </div>
        </div>
        
        <!-- Payment Progress -->
        @if($total_fees > 0)
            <div class="mt-8 bg-white p-5 rounded-lg shadow-sm border border-gray-100">
                <div class="flex justify-between items-center text-sm font-medium text-gray-700 mb-2">
                    <span>Payment Progress</span>
                    <span class="bg-gray-100 py-1 px-2 rounded-full text-xs">{{ number_format($paymentPercentage, 0) }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full {{ $paymentPercentage < 50 ? 'bg-red-500' : ($paymentPercentage < 100 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                         style="width: {{ $paymentPercentage }}%"></div>
                </div>
                <div class="mt-2 grid grid-cols-3 text-xs text-gray-500">
                    <div>0%</div>
                    <div class="text-center">50%</div>
                    <div class="text-right">100%</div>
                </div>
            </div>
        @endif
    </div>
</div> 