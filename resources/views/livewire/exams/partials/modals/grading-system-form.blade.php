@if($showGradingSystemFormModal)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Header -->
            <div class="bg-green-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-green-100">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Create New Grading System
                </h3>
                <button wire:click="closeGradingSystemForm" type="button" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <form wire:submit.prevent="storeGradingSystem">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="space-y-4">
                        <div>
                            <label for="gradingSystemName" class="block text-sm font-medium text-gray-700">Grading System Name*</label>
                            <input 
                                type="text" 
                                wire:model="gradingSystemName" 
                                id="gradingSystemName" 
                                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="e.g., Standard Grading System"
                            >
                            @error('gradingSystemName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="gradingSystemDescription" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea 
                                wire:model="gradingSystemDescription" 
                                id="gradingSystemDescription" 
                                rows="3" 
                                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Provide a brief description of this grading system..."
                            ></textarea>
                            @error('gradingSystemDescription') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="gradingSystemEffectiveDate" class="block text-sm font-medium text-gray-700">Effective Date*</label>
                            <input 
                                type="date" 
                                wire:model="gradingSystemEffectiveDate" 
                                id="gradingSystemEffectiveDate" 
                                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            >
                            @error('gradingSystemEffectiveDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-md mt-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Note:</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>After creating the grading system, you'll need to define grade ranges separately.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer/Buttons -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button 
                        type="submit" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200"
                    >
                        Create Grading System
                    </button>
                    <button 
                        type="button" 
                        wire:click="closeGradingSystemForm" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif 