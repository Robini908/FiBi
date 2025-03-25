<div class="sm:flex sm:justify-between sm:items-center mb-8">
    <!-- Left: Title -->
    <div class="mb-4 sm:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Payment Vouchers</h1>
        <p class="text-sm text-gray-500 mt-1">Manage and track payment vouchers for school expenses</p>
    </div>

    <!-- Right: Actions -->
    <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
        <!-- Export button -->
        <div class="relative inline-flex">
            <button
                wire:click="$dispatch('exportVouchers')"
                class="btn bg-white border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-600"
            >
                <span class="hidden xs:block ml-2">Export</span>
                <svg class="w-4 h-4 fill-current text-gray-500 shrink-0" viewBox="0 0 16 16">
                    <path d="M15 7h-3V1H8v6H5l5 5 5-5z" />
                    <path d="M2 13h12v2H2z" />
                </svg>
            </button>
        </div>

        <!-- Add voucher button -->
        @if($isAdmin || $isAccountant)
        <button
            wire:click="openVoucherModal"
            class="btn bg-green-600 hover:bg-green-700 text-white"
        >
            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
            </svg>
            <span class="hidden xs:block ml-2">Add Voucher</span>
        </button>
        @endif
    </div>
</div> 