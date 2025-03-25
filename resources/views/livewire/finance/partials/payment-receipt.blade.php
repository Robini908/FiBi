<!-- Payment Receipt Modal -->
<div 
    x-data="{ print: function() { window.print(); } }"
    x-show="$wire.showingReceipt" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div 
            x-show="$wire.showingReceipt" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="fixed inset-0 transition-opacity"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
        
        <div 
            x-show="$wire.showingReceipt" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full print:shadow-none print:transform-none print:my-0"
        >
            @if($selectedPayment)
                <div class="bg-white p-8 print:p-2" id="receipt-content">
                    <!-- Receipt Header -->
                    <div class="flex justify-between items-center mb-6 print:mb-2">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-20 w-auto" src="{{ asset('images/school-logo.png') }}" alt="School Logo" onerror="this.src='https://via.placeholder.com/80x80?text=Logo'; this.onerror=null;">
                            </div>
                            <div class="ml-4">
                                <h1 class="text-xl font-bold text-gray-900">{{ config('app.name', 'School ERP') }}</h1>
                                <p class="text-sm text-gray-600">{{ config('school.address', 'School Address') }}</p>
                                <p class="text-sm text-gray-600">{{ config('school.contact', 'Contact Info') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h2 class="text-xl font-bold text-green-600">RECEIPT</h2>
                            <p class="text-sm text-gray-600">#{{ $selectedPayment->receipt_number }}</p>
                            <p class="text-sm text-gray-600">Date: {{ $selectedPayment->payment_date->format('d M, Y') }}</p>
                        </div>
                    </div>

                    <!-- Student Info -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg print:bg-white print:p-2 print:border print:border-gray-200">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">STUDENT INFORMATION</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Name:</p>
                                <p class="text-base font-medium text-gray-900">{{ $selectedPayment->student->user->name ?? 'Unknown' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Admission No:</p>
                                <p class="text-base font-medium text-gray-900">{{ $selectedPayment->student->admission_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Class:</p>
                                <p class="text-base font-medium text-gray-900">{{ $selectedPayment->student->myClass->name ?? 'Unknown' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Academic Year & Term:</p>
                                <p class="text-base font-medium text-gray-900">{{ $selectedPayment->year }} - Term {{ $selectedPayment->term }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">PAYMENT DETAILS</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-3 py-3 text-sm text-gray-900">Fee Payment</td>
                                    <td class="px-3 py-3 text-sm text-gray-900 text-right">Ksh {{ number_format($selectedPayment->amount, 2) }}</td>
                                </tr>
                                <tr class="bg-gray-50 print:bg-white">
                                    <td class="px-3 py-3 text-sm font-medium text-gray-900">Total Amount Paid</td>
                                    <td class="px-3 py-3 text-base font-bold text-green-600 text-right">Ksh {{ number_format($selectedPayment->amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-6 grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg print:bg-white print:p-2 print:border print:border-gray-200">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">PAYMENT METHOD</h3>
                            <p class="text-base font-medium text-gray-900">
                                @if($selectedPayment->payment_method == 'cash') Cash
                                @elseif($selectedPayment->payment_method == 'cheque') Cheque
                                @elseif($selectedPayment->payment_method == 'bank_transfer') Bank Transfer
                                @elseif($selectedPayment->payment_method == 'mpesa') M-Pesa
                                @else {{ ucfirst($selectedPayment->payment_method) }}
                                @endif
                            </p>
                            
                            @if($selectedPayment->payment_method == 'cheque')
                                <p class="text-sm text-gray-600 mt-1">Cheque #: {{ $selectedPayment->cheque_number ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">Bank: {{ $selectedPayment->bank_name ?? 'N/A' }}</p>
                            @elseif($selectedPayment->payment_method == 'bank_transfer')
                                <p class="text-sm text-gray-600 mt-1">Bank: {{ $selectedPayment->bank_name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">Reference: {{ $selectedPayment->bank_slip_number ?? 'N/A' }}</p>
                            @elseif($selectedPayment->payment_method == 'mpesa')
                                <p class="text-sm text-gray-600 mt-1">Transaction ID: {{ $selectedPayment->mpesa_transaction_id ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">Phone Number: {{ $selectedPayment->phone_number ?? 'N/A' }}</p>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">PAYMENT STATUS</h3>
                            <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($selectedPayment->is_cancelled) bg-red-100 text-red-800
                                @elseif($selectedPayment->is_confirmed) bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                @if($selectedPayment->is_cancelled) Cancelled
                                @elseif($selectedPayment->is_confirmed) Confirmed
                                @else Pending Confirmation
                                @endif
                            </p>
                            
                            @if($selectedPayment->notes)
                                <div class="mt-2">
                                    <h3 class="text-sm font-medium text-gray-500">Notes</h3>
                                    <p class="text-sm text-gray-600">{{ $selectedPayment->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-gray-200 pt-4 mt-6 print:mt-2">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Received by: <span class="font-medium">{{ $selectedPayment->receivedBy->name ?? 'System' }}</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Issued on: {{ $selectedPayment->created_at->format('d M, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-center text-xs text-gray-500">
                            <p>This is a computer-generated receipt and does not require a physical signature.</p>
                            <p class="mt-1">Thank you for your payment!</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse print:hidden">
                    <button
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        x-on:click="print()"
                    >
                        Print Receipt
                    </button>
                    <button
                        type="button"
                        wire:click="closeReceiptModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Close
                    </button>
                </div>
            @else
                <div class="p-6 text-center">
                    <p class="text-gray-500">No payment details available</p>
                    <button
                        type="button"
                        wire:click="closeReceiptModal"
                        class="mt-3 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-4 sm:w-auto sm:text-sm"
                    >
                        Close
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #receipt-content, #receipt-content * {
            visibility: visible;
        }
        #receipt-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style> 