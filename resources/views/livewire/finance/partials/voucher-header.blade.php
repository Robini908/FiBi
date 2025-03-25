<div class="pb-5 border-b border-gray-200 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center">
    <div>
        <h2 class="text-2xl font-bold leading-tight text-gray-800">Payment Vouchers</h2>
        <p class="mt-1 text-sm text-gray-600">Manage and track all outgoing payments</p>
    </div>
    
    <div class="mt-4 md:mt-0">
        <button 
            wire:click="openVoucherModal" 
            type="button" 
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        >
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            New Voucher
        </button>
    </div>
</div> 