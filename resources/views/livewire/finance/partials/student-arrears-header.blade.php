<!-- Header and Action Buttons -->
<div class="p-4 sm:p-6 bg-gradient-to-r from-green-50 to-green-100 border-b border-gray-200">
    <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                @if($isStudent)
                    My Fee Arrears
                @elseif($isParent)
                    My Children's Fee Arrears
                @else
                    Student Fee Arrears
                @endif
            </h1>
            <p class="text-gray-600 mt-1">
                @if($isStudent)
                    Track and manage your fee arrears from previous sessions
                @elseif($isParent)
                    Track your children's fee arrears from previous sessions
                @else
                    Track and manage student fee arrears from previous sessions
                @endif
            </p>
        </div>
        
        @if($isAdmin || $isAccountant)
        <div class="flex space-x-2">
            <button 
                wire:click="openModal"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Arrear
            </button>
            <button 
                wire:click="openBulkImportModal"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Generate Arrears
            </button>
        </div>
        @endif
    </div>
</div> 