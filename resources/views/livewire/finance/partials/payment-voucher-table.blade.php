<div class="overflow-x-auto">
    <table class="table-auto w-full">
        <thead class="text-xs font-semibold uppercase text-gray-500 bg-gray-50">
            <tr>
                <th class="px-4 py-3 whitespace-nowrap">
                    <div class="font-semibold text-left cursor-pointer" wire:click="sortBy('voucher_number')">
                        Voucher #
                        @if($sortField === 'voucher_number')
                            @if($sortDirection === 'asc')
                                <svg class="w-3 h-3 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            @else
                                <svg class="w-3 h-3 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
                <th class="px-4 py-3 whitespace-nowrap">
                    <div class="font-semibold text-left cursor-pointer" wire:click="sortBy('recipient_name')">
                        Recipient
                        @if($sortField === 'recipient_name')
                            @if($sortDirection === 'asc')
                                <svg class="w-3 h-3 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            @else
                                <svg class="w-3 h-3 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
                <th class="px-4 py-3 whitespace-nowrap">
                    <div class="font-semibold text-left">Purpose</div>
                </th>
                <th class="px-4 py-3 whitespace-nowrap">
                    <div class="font-semibold text-left cursor-pointer" wire:click="sortBy('amount')">
                        Amount
                        @if($sortField === 'amount')
                            @if($sortDirection === 'asc')
                                <svg class="w-3 h-3 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            @else
                                <svg class="w-3 h-3 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
                <th class="px-4 py-3 whitespace-nowrap">
                    <div class="font-semibold text-left cursor-pointer" wire:click="sortBy('payment_date')">
                        Date
                        @if($sortField === 'payment_date')
                            @if($sortDirection === 'asc')
                                <svg class="w-3 h-3 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            @else
                                <svg class="w-3 h-3 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
                <th class="px-4 py-3 whitespace-nowrap">
                    <div class="font-semibold text-left">Status</div>
                </th>
                <th class="px-4 py-3 whitespace-nowrap">
                    <div class="font-semibold text-right">Actions</div>
                </th>
            </tr>
        </thead>
        <tbody class="text-sm divide-y divide-gray-100">
            @forelse ($vouchers as $voucher)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-left font-medium text-green-600">{{ $voucher->voucher_number }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-left">{{ $voucher->recipient_name }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-left max-w-xs truncate" title="{{ $voucher->purpose }}">{{ $voucher->purpose }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-left font-medium">KES {{ number_format($voucher->amount, 2) }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-left">{{ date('d M Y', strtotime($voucher->payment_date)) }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($voucher->is_cancelled)
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Cancelled
                            </div>
                        @elseif($voucher->status === 'pending')
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </div>
                        @elseif($voucher->status === 'approved')
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Approved
                            </div>
                        @elseif($voucher->status === 'paid')
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Paid
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex justify-end items-center space-x-1">
                            <!-- View button - visible to all -->
                            <button 
                                wire:click="showVoucher({{ $voucher->id }})"
                                class="btn-sm bg-white hover:bg-gray-50 border-gray-200 hover:border-gray-300 text-indigo-500 hover:text-indigo-600"
                                title="View Details"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            <!-- Edit button - only for admin, accountant or creator if voucher is pending -->
                            @if(($isAdmin || $isAccountant || $voucher->created_by === auth()->user()->name) && $voucher->status === 'pending' && !$voucher->is_cancelled)
                                <button 
                                    wire:click="editVoucher({{ $voucher->id }})"
                                    class="btn-sm bg-white hover:bg-gray-50 border-gray-200 hover:border-gray-300 text-blue-500 hover:text-blue-600"
                                    title="Edit Voucher"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            @endif

                            <!-- Approve button - only for admin/accountant if voucher is pending -->
                            @if(($isAdmin || $isAccountant) && $voucher->status === 'pending' && !$voucher->is_cancelled)
                                <button 
                                    wire:click="$dispatch('confirmApproveVoucher', { id: {{ $voucher->id }} })"
                                    class="btn-sm bg-white hover:bg-gray-50 border-gray-200 hover:border-gray-300 text-green-500 hover:text-green-600"
                                    title="Approve Voucher"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endif

                            <!-- Pay button - only for admin/accountant if voucher is approved -->
                            @if(($isAdmin || $isAccountant) && $voucher->status === 'approved' && !$voucher->is_cancelled)
                                <button 
                                    wire:click="$dispatch('confirmPayVoucher', { id: {{ $voucher->id }} })"
                                    class="btn-sm bg-white hover:bg-gray-50 border-gray-200 hover:border-gray-300 text-green-500 hover:text-green-600"
                                    title="Mark as Paid"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            @endif

                            <!-- Cancel button - only for admin/accountant if voucher is not paid or cancelled -->
                            @if(($isAdmin || $isAccountant) && $voucher->status !== 'paid' && !$voucher->is_cancelled)
                                <button 
                                    wire:click="$dispatch('confirmCancelVoucher', { id: {{ $voucher->id }} })"
                                    class="btn-sm bg-white hover:bg-gray-50 border-gray-200 hover:border-gray-300 text-red-500 hover:text-red-600"
                                    title="Cancel Voucher"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif

                            <!-- Delete button - only for admin, accountant or creator if voucher is pending -->
                            @if(($isAdmin || $isAccountant || $voucher->created_by === auth()->user()->name) && $voucher->status === 'pending' && !$voucher->is_cancelled)
                                <button 
                                    wire:click="$dispatch('confirmDeleteVoucher', { id: {{ $voucher->id }} })"
                                    class="btn-sm bg-white hover:bg-gray-50 border-gray-200 hover:border-gray-300 text-red-500 hover:text-red-600"
                                    title="Delete Voucher"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="mt-2 text-gray-500">No vouchers found</span>
                            <button 
                                wire:click="resetFilters"
                                class="mt-3 btn-sm bg-white border-gray-200 hover:border-gray-300 text-green-600"
                            >
                                Reset Filters
                            </button>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $vouchers->links() }}
</div> 