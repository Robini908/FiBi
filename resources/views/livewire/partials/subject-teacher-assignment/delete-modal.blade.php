<div x-data="{ open: true }" x-show="open" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div 
            x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            @click="open = false; $wire.closeBulkDeleteModal()"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div 
            x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
        >
            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button 
                    type="button" 
                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    @click="open = false; $wire.closeBulkDeleteModal()"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Confirm Bulk Deletion
                    </h3>
                    
                    @if(!$showBulkDeleteResults)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete <span class="font-semibold">{{ count($selectedAssignments) }}</span> selected teacher-subject assignments? This action cannot be undone.
                            </p>
                            
                            <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            Deleting these assignments will remove them from the system permanently. This may affect timetables, class schedules, and other related data.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-end">
                                <button 
                                    wire:click="closeBulkDeleteModal" 
                                    type="button" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3"
                                >
                                    Cancel
                                </button>
                                
                                <button 
                                    wire:click="bulkDelete" 
                                    wire:loading.attr="disabled"
                                    type="button" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                >
                                    <svg wire:loading class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Delete Assignments
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="mt-4">
                            <div class="flex items-center mb-4">
                                <svg class="h-6 w-6 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h4 class="text-lg font-medium text-green-800">Deletion Complete</h4>
                            </div>
                            
                            <p class="text-sm text-gray-500 mb-4">
                                The selected assignments have been processed. See the results below:
                            </p>
                            
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                    <p class="text-xs font-medium text-gray-500 uppercase">Total</p>
                                    <p class="mt-1 text-lg font-bold text-gray-800">{{ $bulkDeleteResults['total'] }}</p>
                                </div>
                                
                                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                    <p class="text-xs font-medium text-green-500 uppercase">Deleted</p>
                                    <p class="mt-1 text-lg font-bold text-green-600">{{ $bulkDeleteResults['deleted'] }}</p>
                                </div>
                                
                                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                    <p class="text-xs font-medium text-red-500 uppercase">Failed</p>
                                    <p class="mt-1 text-lg font-bold text-red-600">{{ $bulkDeleteResults['failed'] }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-end">
                                <button 
                                    wire:click="closeBulkDeleteModal" 
                                    type="button" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Close
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 