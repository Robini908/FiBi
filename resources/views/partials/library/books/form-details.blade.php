<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
    <!-- Publisher -->
    <div class="sm:col-span-3">
        <label for="publisher" class="block text-sm font-medium text-gray-700">Publisher</label>
        <div class="mt-1">
            <input 
                type="text" 
                wire:model="publisher" 
                id="publisher" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Enter publisher name">
        </div>
        @error('publisher') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Publication Date -->
    <div class="sm:col-span-3">
        <label for="publication_date" class="block text-sm font-medium text-gray-700">Publication Date</label>
        <div class="mt-1">
            <input 
                type="date" 
                wire:model="publication_date" 
                id="publication_date" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
        </div>
        @error('publication_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Edition -->
    <div class="sm:col-span-3">
        <label for="edition" class="block text-sm font-medium text-gray-700">Edition</label>
        <div class="mt-1">
            <input 
                type="text" 
                wire:model="edition" 
                id="edition" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="E.g., First Edition, 2nd Edition">
        </div>
        @error('edition') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Pages -->
    <div class="sm:col-span-3">
        <label for="pages" class="block text-sm font-medium text-gray-700">Number of Pages</label>
        <div class="mt-1">
            <input 
                type="number" 
                wire:model="pages" 
                id="pages" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                min="1"
                placeholder="Enter number of pages">
        </div>
        @error('pages') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Dewey Decimal -->
    <div class="sm:col-span-3">
        <label for="dewey_decimal" class="block text-sm font-medium text-gray-700">Dewey Decimal</label>
        <div class="mt-1">
            <input 
                type="text" 
                wire:model="dewey_decimal" 
                id="dewey_decimal" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="E.g., 813.54">
        </div>
        @error('dewey_decimal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Call Number -->
    <div class="sm:col-span-3">
        <label for="call_number" class="block text-sm font-medium text-gray-700">Call Number</label>
        <div class="mt-1">
            <input 
                type="text" 
                wire:model="call_number" 
                id="call_number" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Enter library call number">
        </div>
        @error('call_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Replacement Cost -->
    <div class="sm:col-span-3">
        <label for="replacement_cost" class="block text-sm font-medium text-gray-700">Replacement Cost</label>
        <div class="mt-1">
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input 
                    type="number" 
                    wire:model="replacement_cost" 
                    id="replacement_cost" 
                    class="focus:ring-green-500 focus:border-green-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                    placeholder="0.00"
                    step="0.01"
                    min="0">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">USD</span>
                </div>
            </div>
        </div>
        @error('replacement_cost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Table of Contents -->
    <div class="sm:col-span-6">
        <label for="table_of_contents" class="block text-sm font-medium text-gray-700">Table of Contents</label>
        <div class="mt-1">
            <textarea 
                id="table_of_contents" 
                wire:model="table_of_contents" 
                rows="4" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Enter book's table of contents"></textarea>
        </div>
        @error('table_of_contents') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Additional Settings -->
    <div class="sm:col-span-6">
        <fieldset>
            <legend class="text-sm font-medium text-gray-700">Book Settings</legend>
            <div class="mt-2 space-y-4">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            id="is_reference_only" 
                            wire:model="is_reference_only" 
                            type="checkbox" 
                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_reference_only" class="font-medium text-gray-700">Reference Only</label>
                        <p class="text-gray-500">Book can only be used within the library and cannot be loaned.</p>
                    </div>
                </div>

                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            id="is_featured" 
                            wire:model="is_featured" 
                            type="checkbox" 
                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_featured" class="font-medium text-gray-700">Featured Book</label>
                        <p class="text-gray-500">Featured books are displayed prominently in the library catalog.</p>
                    </div>
                </div>

                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            id="is_active" 
                            wire:model="is_active" 
                            type="checkbox" 
                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Active</label>
                        <p class="text-gray-500">Inactive books are not displayed in the library catalog.</p>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div> 