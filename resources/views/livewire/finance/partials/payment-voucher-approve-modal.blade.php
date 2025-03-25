<div
    x-data="{ modalOpen: @entangle('showApproveModal').live }"
    x-on:keydown.escape.window="modalOpen = false"
    x-show="modalOpen"
    class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center transform px-4 sm:px-6"
    role="dialog"
    aria-modal="true"
    x-cloak
>
    <!-- Modal backdrop -->
    <div
        x-show="modalOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-out duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-gray-900 bg-opacity-70"
        @click="modalOpen = false"
    ></div>

    <!-- Modal dialog -->
    <div
        x-show="modalOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-out duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="bg-white rounded-lg shadow-lg overflow-auto max-w-lg w-full max-h-full"
        @click.stop
    >
        <!-- Modal header -->
        <div class="px-5 py-3 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="font-semibold text-green-600">Approve Payment Voucher</div>
                <button class="text-gray-400 hover:text-gray-500" @click="modalOpen = false">
                    <div class="sr-only">Close</div>
                    <svg class="w-4 h-4 fill-current">
                        <path d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal content -->
        <div class="px-5 py-4">
            <div class="text-sm">
                <div class="font-medium text-gray-800 mb-3">Are you sure you want to approve this payment voucher?</div>
                <div class="text-gray-600 mb-6">
                    <p>Approving this voucher will allow it to be processed for payment.</p>
                    <div class="mt-4 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    Voucher details have been verified
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal footer -->
        <div class="px-5 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
            <div class="flex space-x-3">
                <button 
                    type="button" 
                    class="btn-sm bg-white border-gray-200 hover:border-gray-300 text-gray-600"
                    @click="modalOpen = false"
                >
                    Cancel
                </button>
                <button 
                    type="button" 
                    class="btn-sm bg-green-600 hover:bg-green-700 text-white"
                    wire:click="approveVoucher"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="approveVoucher">Approve Voucher</span>
                    <span wire:loading wire:target="approveVoucher">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div> 