<div x-data="{ open: @entangle('showReceiptModal').defer }" x-show="open" class="fixed z-10 inset-0 overflow-y-auto" x-cloak>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            
            @if($selectedPayment)
            <div class="bg-white p-6" id="receipt-content">
                <!-- Receipt Header -->
                <div class="text-center border-b border-gray-200 pb-4 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">FEE PAYMENT RECEIPT</h2>
                            <p class="text-sm text-gray-600">Official Receipt</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-lg font-semibold text-gray-900">{{ config('app.name') }}</h3>
                            <p class="text-sm text-gray-600">{{ config('app.address', '123 School Street, Nairobi') }}</p>
                            <p class="text-sm text-gray-600">{{ config('app.phone', '+254 700 000000') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Receipt Details -->
                <div class="mb-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Receipt No:</p>
                            <p class="text-md font-medium text-gray-900">{{ $selectedPayment->receipt_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Date:</p>
                            <p class="text-md font-medium text-gray-900">{{ $selectedPayment->payment_date->format('d M, Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Student Information -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">STUDENT INFORMATION</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Name:</p>
                            <p class="text-md font-medium text-gray-900">{{ $student->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Admission No:</p>
                            <p class="text-md font-medium text-gray-900">{{ $student->admission_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Class:</p>
                            <p class="text-md font-medium text-gray-900">{{ $student->class->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Academic Year/Term:</p>
                            <p class="text-md font-medium text-gray-900">{{ $selectedPayment->academic_year }} / Term {{ $selectedPayment->term }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">PAYMENT DETAILS</h4>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (KES)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(!empty($paymentAllocations) && count($paymentAllocations) > 0)
                                @foreach($paymentAllocations as $allocation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $allocation->fee_structure->name ?? 'Fee Payment' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                        {{ number_format($allocation->amount, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Fee Payment
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                        {{ number_format($selectedPayment->amount, 2) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Total
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                    {{ number_format($selectedPayment->amount, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Payment Method -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600">Payment Method:</p>
                        <p class="text-md font-medium text-gray-900">{{ ucfirst($selectedPayment->payment_method) }}</p>
                        @if($selectedPayment->reference_number)
                            <p class="text-sm text-gray-600 mt-1">Reference: {{ $selectedPayment->reference_number }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Received By:</p>
                        <p class="text-md font-medium text-gray-900">{{ $selectedPayment->created_by_user->name ?? 'System' }}</p>
                    </div>
                </div>
                
                @if($selectedPayment->notes)
                <div class="mb-6 border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-600">Notes:</p>
                    <p class="text-sm text-gray-900">{{ $selectedPayment->notes }}</p>
                </div>
                @endif
                
                <!-- Balance Information -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Previous Balance:</p>
                            <p class="text-md font-medium text-gray-900">KES {{ number_format($selectedPayment->previous_balance, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Current Balance:</p>
                            <p class="text-md font-medium text-gray-900 
                                @if($selectedPayment->balance_after > 0) 
                                    text-red-600
                                @else
                                    text-green-600
                                @endif
                            ">
                                KES {{ number_format($selectedPayment->balance_after, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="border-t border-gray-200 pt-4 mt-6 text-center">
                    <p class="text-sm text-gray-500">Thank you for your payment.</p>
                    <p class="text-sm text-gray-500">This is a computer-generated receipt and does not require a signature.</p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    type="button" 
                    wire:click="printReceipt"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Receipt
                </button>
                <button 
                    type="button" 
                    wire:click="closeReceipt"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Close
                </button>
            </div>
            @else
            <div class="bg-white p-6 text-center">
                <p class="text-gray-500">No payment information available.</p>
            </div>
            @endif
        </div>
    </div>
</div> 