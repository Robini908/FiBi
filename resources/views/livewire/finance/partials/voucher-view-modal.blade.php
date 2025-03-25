<!-- Voucher View Modal -->
<div 
    x-data="{ show: @entangle('showViewModal').defer }"
    x-show="show" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
            aria-hidden="true"
        ></div>

        <!-- Modal panel -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"
        >
            @if($selectedVoucher)
                <!-- Modal header -->
                <div class="bg-green-50 px-4 py-3 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Payment Voucher #{{ $selectedVoucher->voucher_number }}
                    </h3>
                    <button 
                        @click="show = false" 
                        type="button" 
                        class="text-gray-400 hover:text-gray-500 focus:outline-none"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Voucher Content -->
                <div class="bg-white px-4 pt-5 pb-6 sm:p-6">
                    <!-- Voucher Status Banner -->
                    <div class="mb-6 rounded-md p-3 {{ $selectedVoucher->getStatusClass() }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($selectedVoucher->status === 'paid')
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($selectedVoucher->status === 'approved')
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($selectedVoucher->status === 'pending')
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($selectedVoucher->status === 'cancelled')
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium {{ $selectedVoucher->getStatusTextClass() }}">
                                    {{ ucfirst($selectedVoucher->status) }}
                                </h3>
                                <div class="mt-1 text-sm {{ $selectedVoucher->getStatusTextClass() }}">
                                    @if($selectedVoucher->status === 'paid')
                                        This voucher has been paid on {{ $selectedVoucher->payment_date ? date('d M Y', strtotime($selectedVoucher->payment_date)) : '-' }}.
                                    @elseif($selectedVoucher->status === 'approved')
                                        This voucher has been approved and is ready for payment.
                                    @elseif($selectedVoucher->status === 'pending')
                                        This voucher is pending approval.
                                    @elseif($selectedVoucher->status === 'cancelled')
                                        This voucher has been cancelled and cannot be processed.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2 mb-6">
                        <!-- Left Column: Voucher Details -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Voucher Details</h4>
                                <div class="mt-2 border-t border-gray-200 pt-2">
                                    <dl class="divide-y divide-gray-200">
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Voucher Number</dt>
                                            <dd class="text-sm font-semibold text-gray-900">{{ $selectedVoucher->voucher_number }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Created On</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->created_at->format('d M Y, h:i A') }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Created By</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->created_by ? $selectedVoucher->creator->name : '-' }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Academic Period</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->academic_year }} - Term {{ $selectedVoucher->term }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Recipient Information</h4>
                                <div class="mt-2 border-t border-gray-200 pt-2">
                                    <dl class="divide-y divide-gray-200">
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->recipient_name }}</dd>
                                        </div>
                                        @if($selectedVoucher->recipient_id_number)
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">ID Number</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->recipient_id_number }}</dd>
                                        </div>
                                        @endif
                                        @if($selectedVoucher->recipient_phone)
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->recipient_phone }}</dd>
                                        </div>
                                        @endif
                                        @if($selectedVoucher->recipient_address)
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->recipient_address }}</dd>
                                        </div>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Payment Details -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Payment Details</h4>
                                <div class="mt-2 border-t border-gray-200 pt-2">
                                    <dl class="divide-y divide-gray-200">
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                                            <dd class="text-sm font-semibold text-gray-900">KES {{ number_format($selectedVoucher->amount, 2) }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Votehead</dt>
                                            <dd class="text-sm text-gray-900">
                                                {{ $selectedVoucher->votehead->name }}
                                                <p class="text-xs text-gray-500">{{ $selectedVoucher->votehead->account->name }}</p>
                                            </dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                            <dd class="text-sm text-gray-900">{{ ucfirst($selectedVoucher->payment_method) }}</dd>
                                        </div>
                                        @if($selectedVoucher->payment_method === 'cheque' && $selectedVoucher->cheque_number)
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Cheque Number</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->cheque_number }}</dd>
                                        </div>
                                        @endif
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->payment_date ? date('d M Y', strtotime($selectedVoucher->payment_date)) : '-' }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Approval Information</h4>
                                <div class="mt-2 border-t border-gray-200 pt-2">
                                    <dl class="divide-y divide-gray-200">
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->approved_by ? $selectedVoucher->approver->name : '-' }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Approved On</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->approved_at ? date('d M Y, h:i A', strtotime($selectedVoucher->approved_at)) : '-' }}</dd>
                                        </div>
                                        @if($selectedVoucher->status === 'paid')
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Paid By</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->paid_by ? $selectedVoucher->payer->name : '-' }}</dd>
                                        </div>
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">Paid On</dt>
                                            <dd class="text-sm text-gray-900">{{ $selectedVoucher->paid_at ? date('d M Y, h:i A', strtotime($selectedVoucher->paid_at)) : '-' }}</dd>
                                        </div>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Purpose and Description -->
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500">Purpose and Description</h4>
                        <div class="mt-2 border-t border-gray-200 pt-2">
                            <div class="py-2">
                                <dt class="text-sm font-medium text-gray-500">Purpose</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $selectedVoucher->purpose }}</dd>
                            </div>
                            <div class="py-2">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $selectedVoucher->description }}</dd>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Attachment (if any) -->
                    @if($selectedVoucher->attachment_path)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500">Attachment</h4>
                        <div class="mt-2 border-t border-gray-200 pt-2">
                            <div class="py-2">
                                <a href="{{ route('finance.vouchers.download-attachment', $selectedVoucher->id) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                   target="_blank">
                                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Download Attachment
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Modal footer / action buttons -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    @if($selectedVoucher->status === 'pending' && ($isAdmin || $isAccountant))
                        <button 
                            wire:click="approveVoucher({{ $selectedVoucher->id }})" 
                            type="button" 
                            class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm"
                        >
                            Approve Voucher
                        </button>
                    @endif

                    @if($selectedVoucher->status === 'approved' && ($isAdmin || $isAccountant))
                        <button 
                            wire:click="markVoucherAsPaid({{ $selectedVoucher->id }})" 
                            type="button" 
                            class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm"
                        >
                            Mark as Paid
                        </button>
                    @endif

                    <button 
                        @click="show = false" 
                        type="button" 
                        class="mt-3 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm"
                    >
                        Close
                    </button>

                    @if($selectedVoucher->status !== 'cancelled' && ($isAdmin || $isAccountant))
                        <button 
                            wire:click="confirmCancelVoucher({{ $selectedVoucher->id }})" 
                            type="button" 
                            class="mt-3 mr-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel Voucher
                        </button>
                    @endif
                </div>
            @else
                <div class="p-6 text-center">
                    <p class="text-gray-500">No voucher selected.</p>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        @click="show = false" 
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Close
                    </button>
                </div>
            @endif
        </div>
    </div>
</div> 