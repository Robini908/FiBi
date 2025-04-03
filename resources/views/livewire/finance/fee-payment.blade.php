<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800">Student Fee Payment</h2>
    </div>

    <div class="p-6">
        @if(!$student_id)
            <!-- Student Selection Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-800">Select Student</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Please select a student to proceed with fee payment.</p>

                    <!-- Student search will be implemented here -->
                    <div class="mt-4">
                        <a href="{{ route('finance.student-fee-payments') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Go to Student Fee Payments
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Student Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-800">Student Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student_name }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Class</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student_class }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Admission Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $admission_number }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Academic Year</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $academic_year }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Total Fees</dt>
                                    <dd class="mt-1 text-sm text-gray-900">Ksh {{ number_format($total_fees, 2) }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Paid Amount</dt>
                                    <dd class="mt-1 text-sm text-gray-900">Ksh {{ number_format($paid_amount, 2) }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Balance</dt>
                                    <dd class="mt-1 text-sm font-medium {{ $fee_balance > 0 ? 'text-red-600' : 'text-green-600' }}">Ksh {{ number_format($fee_balance, 2) }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                                    <dd class="mt-1">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $paymentPercentage }}%"></div>
                                            </div>
                                            <span class="ml-2 text-sm text-gray-900">{{ $paymentPercentage }}%</span>
                                        </div>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Options -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Payment Form -->
                @if($showPaymentForm && $hasPaymentPermission)
                    <div class="md:col-span-7">
                        @include('livewire.finance.partials.payment-form')
                    </div>
                    <div class="md:col-span-5">
                        <!-- Direct M-Pesa Payment -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-800">Pay with M-Pesa</h3>
                                <button
                                    wire:click="toggleMpesaPaymentForm"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($showDirectMpesaPayment)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                </button>
                            </div>
                            @if($showDirectMpesaPayment)
                                <div class="p-6">
                                    <p class="text-sm text-gray-600 mb-4">Pay directly using M-Pesa. Enter the amount and phone number to receive the payment prompt.</p>

                                    <form wire:submit.prevent="initiateDirectMpesaPayment" class="space-y-4">
                                        <!-- Amount -->
                                        <div>
                                            <label for="mpesa_amount" class="block text-sm font-medium text-gray-700">Amount (Ksh) <span class="text-red-500">*</span></label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Ksh</span>
                                                </div>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    id="mpesa_amount"
                                                    wire:model="amount"
                                                    class="block w-full pl-12 pr-12 py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                                    placeholder="0.00"
                                                    required
                                                >
                                            </div>
                                            @error('amount')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Phone Number -->
                                        <div>
                                            <label for="mpesa_phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                                            <div class="mt-1">
                                                <input
                                                    type="text"
                                                    id="mpesa_phone"
                                                    wire:model="phone_number"
                                                    class="block w-full py-2 px-3 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                                    placeholder="2547XXXXXXXX"
                                                    required
                                                >
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">Enter phone number in format: 2547XXXXXXXX</p>
                                            @error('phone_number')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="flex justify-end pt-4">
                                            <button
                                                type="submit"
                                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                            >
                                                <svg wire:loading class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span wire:loading.remove>Pay with M-Pesa</span>
                                                <span wire:loading>Processing...</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="p-6 text-center">
                                    <p class="text-sm text-gray-600 mb-4">Click the button below to make a direct M-Pesa payment.</p>
                                    <button
                                        wire:click="toggleMpesaPaymentForm"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                    >
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Pay with M-Pesa
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Payments -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-lg font-medium text-gray-800">Recent Payments</h3>
                            </div>
                            <div class="p-6">
                                @if(count($recentPayments) > 0)
                                    <div class="flow-root">
                                        <ul class="-my-5 divide-y divide-gray-200">
                                            @foreach($recentPayments as $payment)
                                                <li class="py-4">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            @if($payment->payment_method === 'mpesa')
                                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100 text-green-600">
                                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                    </svg>
                                                                </span>
                                                            @elseif($payment->payment_method === 'bank_transfer')
                                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600">
                                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                                                    </svg>
                                                                </span>
                                                            @elseif($payment->payment_method === 'cheque')
                                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-yellow-100 text-yellow-600">
                                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 text-gray-600">
                                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                                    </svg>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                                Ksh {{ number_format($payment->amount, 2) }} - {{ ucfirst($payment->payment_method) }}
                                                            </p>
                                                            <p class="text-sm text-gray-500 truncate">
                                                                {{ $payment->payment_date }} |
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                    {{ ucfirst($payment->status) }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <button
                                                                type="button"
                                                                class="inline-flex items-center p-1.5 border border-transparent rounded-full shadow-sm text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                            >
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No payments found</h3>
                                        <p class="mt-1 text-sm text-gray-500">No payment records found for this student.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif(!$hasPaymentPermission)
                    <div class="md:col-span-12">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Permission Denied</h3>
                                <p class="mt-1 text-sm text-gray-500">You do not have permission to make payments.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="md:col-span-12">
                        <!-- Recent Payments -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-800">Payment History</h3>
                                <button
                                    wire:click="openPaymentForm"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                >
                                    Make Payment
                                </button>
                            </div>
                            <div class="p-6">
                                @if(count($recentPayments) > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                                    <th scope="col" class="relative px-6 py-3">
                                                        <span class="sr-only">Actions</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($recentPayments as $payment)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->payment_date }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Ksh {{ number_format($payment->amount, 2) }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($payment->payment_method) }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                {{ ucfirst($payment->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->transaction_id ?? 'N/A' }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <button class="text-green-600 hover:text-green-900">View</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No payments found</h3>
                                        <p class="mt-1 text-sm text-gray-500">No payment records found for this student.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
