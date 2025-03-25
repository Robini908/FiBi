<!-- Confirmation Modal for Delete -->
<div x-data="{ confirmingDelete: false, accountId: null }"
     x-on:deleteaccount.window="confirmingDelete = true; accountId = $event.detail.id"
     x-show="confirmingDelete" 
     x-cloak
     class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-md w-full mx-auto" 
         x-on:click.away="confirmingDelete = false">
        <div class="py-4 px-6 bg-red-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-red-700">Confirm Delete</h3>
        </div>
        <div class="py-4 px-6">
            <p class="text-gray-700">Are you sure you want to delete this account? This action cannot be undone.</p>
            <p class="text-gray-500 text-sm mt-2">Note: Accounts with voteheads cannot be deleted. You may deactivate them instead.</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <button x-on:click="confirmingDelete = false" 
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Cancel
            </button>
            <button x-on:click="$wire.deleteAccount(accountId); confirmingDelete = false" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Delete
            </button>
        </div>
    </div>
</div> 