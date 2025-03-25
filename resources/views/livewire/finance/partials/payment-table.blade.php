<div class="overflow-x-auto mt-6">
    <div class="align-middle inline-block min-w-full">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Fee Payment Records</h3>
                    <p class="mt-1 text-sm text-gray-600">All fee payments for current academic year</p>
                </div>
                <div class="mt-1 flex sm:mt-0 sm:ml-4">
                    <div class="w-full lg:w-64">
                        <label for="filter_class" class="sr-only">Filter by Class</label>
                        <select 
                            id="filter_class"
                            wire:model="filter_class"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 sm:text-sm"
                        >
                            <option value="">All Classes</option>
                            @foreach($classes ?? [] as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ml-2 w-full lg:w-64">
                        <label for="filter_status" class="sr-only">Filter by Status</label>
                        <select 
                            id="filter_status"
                            wire:model="filter_status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 sm:text-sm"
                        >
                            <option value="">All Statuses</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Receipt #
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Method
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                </tr>
            </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $payment->receipt_number }}
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $payment->student->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $payment->student->class->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ date('d M Y', strtotime($payment->payment_date)) }}
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            Ksh {{ number_format($payment->amount, 2) }}
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($payment->payment_method == 'cash') bg-green-100 text-green-800
                                    @elseif($payment->payment_method == 'cheque') bg-blue-100 text-blue-800
                                    @elseif($payment->payment_method == 'bank_transfer') bg-purple-100 text-purple-800
                                    @elseif($payment->payment_method == 'mpesa') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </span>
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($payment->status == 'cancelled') bg-red-100 text-red-800
                                    @elseif($payment->status == 'confirmed') bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- View Receipt -->
                                <button 
                                        wire:click="viewReceipt({{ $payment->id }})" 
                                        class="text-blue-600 hover:text-blue-900"
                                    title="View Receipt"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </button>
                                
                                    @if($hasManagePermission)
                                        @if($payment->status == 'pending')
                                            <!-- Confirm Payment -->
                                        <button 
                                            wire:click="confirmPayment({{ $payment->id }})" 
                                                class="text-green-600 hover:text-green-900"
                                            title="Confirm Payment"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    
                                        @if($payment->status != 'cancelled')
                                            <!-- Edit Payment -->
                                    <button 
                                        wire:click="editPayment({{ $payment->id }})" 
                                                class="text-yellow-600 hover:text-yellow-900"
                                        title="Edit Payment"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                            <!-- Cancel Payment -->
                                    <button 
                                                wire:click="cancelPayment({{ $payment->id }})" 
                                                class="text-red-600 hover:text-red-900"
                                        title="Cancel Payment"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm font-medium text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p>No payments found</p>
                                    <p class="text-xs mt-1">Try changing your filters or recording new payments</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
            </tbody>
        </table>
            
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
    </div>
    </div>
</div> 