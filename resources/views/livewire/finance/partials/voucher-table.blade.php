<div class="overflow-x-auto">
    @if($vouchers->count() > 0)
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('voucher_number')">
                    <div class="flex items-center space-x-1">
                        <span>Voucher #</span>
                        @if($sortField === 'voucher_number')
                            <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                @if($sortDirection === 'asc')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                @endif
                            </svg>
                        @endif
                    </div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                    <div class="flex items-center space-x-1">
                        <span>Date</span>
                        @if($sortField === 'created_at')
                            <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                @if($sortDirection === 'asc')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                @endif
                            </svg>
                        @endif
                    </div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Votehead</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('amount')">
                    <div class="flex items-center space-x-1">
                        <span>Amount</span>
                        @if($sortField === 'amount')
                            <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                @if($sortDirection === 'asc')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                @endif
                            </svg>
                        @endif
                    </div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($vouchers as $voucher)
            <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $voucher->voucher_number }}</div>
                    <div class="text-xs text-gray-500">{{ $voucher->payment_method }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $voucher->created_at->format('M d, Y') }}</div>
                    <div class="text-xs text-gray-500">{{ $voucher->created_at->format('h:i A') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $voucher->votehead->name ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-500">{{ $voucher->votehead->account->name ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $voucher->recipient_name }}</div>
                    <div class="text-xs text-gray-500 truncate max-w-xs">{{ $voucher->purpose }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">KES {{ number_format($voucher->amount, 2) }}</div>
                    <div class="text-xs text-gray-500">{{ $voucher->academic_year }} Term {{ $voucher->term }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($voucher->is_cancelled)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Cancelled
                        </span>
                    @else
                        @if($voucher->status === 'pending')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @elseif($voucher->status === 'approved')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Approved
                            </span>
                        @elseif($voucher->status === 'paid')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Paid
                            </span>
                        @endif
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                        <!-- View Button -->
                        <button 
                            wire:click="showVoucher({{ $voucher->id }})" 
                            class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150"
                            title="View details"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        
                        @if(!$voucher->is_cancelled && $voucher->status === 'pending' && ($isAdmin || $isAccountant || $voucher->created_by === auth()->user()->name))
                        <!-- Edit Button -->
                        <button 
                            wire:click="editVoucher({{ $voucher->id }})" 
                            class="text-blue-600 hover:text-blue-900 transition-colors duration-150"
                            title="Edit voucher"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        @endif
                        
                        @if(!$voucher->is_cancelled && $voucher->status === 'pending' && ($isAdmin || $isAccountant))
                        <!-- Approve Button -->
                        <button 
                            wire:click="confirmApproveVoucher({{ $voucher->id }})" 
                            class="text-green-600 hover:text-green-900 transition-colors duration-150"
                            title="Approve voucher"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        @endif
                        
                        @if(!$voucher->is_cancelled && $voucher->status === 'approved' && ($isAdmin || $isAccountant))
                        <!-- Mark as Paid Button -->
                        <button 
                            wire:click="confirmPayVoucher({{ $voucher->id }})" 
                            class="text-green-600 hover:text-green-900 transition-colors duration-150"
                            title="Mark as paid"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                        @endif
                        
                        @if(!$voucher->is_cancelled && $voucher->status !== 'paid' && ($isAdmin || $isAccountant))
                        <!-- Cancel Button -->
                        <button 
                            wire:click="confirmCancelVoucher({{ $voucher->id }})" 
                            class="text-red-600 hover:text-red-900 transition-colors duration-150"
                            title="Cancel voucher"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        @endif
                        
                        @if(!$voucher->is_cancelled && $voucher->status === 'pending' && ($isAdmin || $voucher->created_by === auth()->user()->name))
                        <!-- Delete Button -->
                        <button 
                            wire:click="confirmDeleteVoucher({{ $voucher->id }})" 
                            class="text-red-600 hover:text-red-900 transition-colors duration-150"
                            title="Delete voucher"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $vouchers->links() }}
    </div>
    @else
    <div class="p-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
            <svg class="h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
        </div>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No vouchers found</h3>
        <p class="mt-1 text-sm text-gray-500">
            @if($search || $voteheadFilter || $yearFilter || $termFilter || $statusFilter || $dateFrom || $dateTo)
                Try adjusting your filters to find what you're looking for.
            @else
                Get started by creating your first payment voucher.
            @endif
        </p>
        @if(($isAdmin || $isAccountant) && !($search || $voteheadFilter || $yearFilter || $termFilter || $statusFilter || $dateFrom || $dateTo))
        <div class="mt-6">
            <button
                wire:click="openModal"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
            >
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Voucher
            </button>
        </div>
        @endif
    </div>
    @endif
</div> 