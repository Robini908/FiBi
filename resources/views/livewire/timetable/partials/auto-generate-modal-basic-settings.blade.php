<!-- Basic Settings Tab Content -->
<div class="space-y-5 py-3" x-data="{ 
    existingEntriesOption: @entangle('autoGenerateForm.respect_existing_entries').defer ? 'respect' : 'clear',
    init() {
        // Set the radio button status based on current values
        if (this.existingEntriesOption === 'clear') {
            $wire.set('autoGenerateForm.clear_existing', true);
            $wire.set('autoGenerateForm.respect_existing_entries', false);
        } else {
            $wire.set('autoGenerateForm.clear_existing', false);
            $wire.set('autoGenerateForm.respect_existing_entries', true);
        }
    }
}">
    <!-- Days Selection -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Days to Include</label>
        <div class="mt-2 grid grid-cols-3 sm:grid-cols-7 gap-2">
            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                <label class="inline-flex items-center px-3 py-2 border border-gray-100 rounded-md bg-gray-50 hover:bg-green-50 transition-colors duration-150 cursor-pointer">
                    <input type="checkbox" 
                           wire:model.defer="autoGenerateForm.days" 
                           value="{{ $day }}" 
                           class="rounded text-green-600 focus:ring-green-500 h-4 w-4 border-gray-300">
                    <span class="ml-2 text-sm text-gray-700 capitalize">{{ $day }}</span>
                </label>
            @endforeach
        </div>
        @error('autoGenerateForm.days') 
            <p class="mt-2 text-xs text-red-600">{{ $message }}</p> 
        @enderror
    </div>
    
    <!-- Max Subjects Per Day -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <label for="max_daily_subjects" class="block text-sm font-medium text-gray-700 mb-2">Maximum Subjects Per Day</label>
        <div class="relative">
            <input type="number" 
                min="1" 
                max="10" 
                wire:model.defer="autoGenerateForm.max_daily_subjects" 
                id="max_daily_subjects" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <span class="text-gray-500 sm:text-sm">subjects</span>
            </div>
        </div>
        <p class="mt-1 text-xs text-gray-500">Sets the maximum number of subjects to schedule on any day</p>
        @error('autoGenerateForm.max_daily_subjects') 
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
        @enderror
    </div>
    
    <!-- Existing Entries Options -->
    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
        <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            How to handle existing entries:
        </h4>
        
        <div class="space-y-4 mt-4">
            <!-- Clear Existing Option -->
            <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-white" :class="{'bg-white ring-2 ring-green-500': existingEntriesOption === 'clear', 'bg-gray-50': existingEntriesOption !== 'clear'}">
                <div class="flex items-center h-5">
                    <input type="radio" 
                        x-model="existingEntriesOption"
                        value="clear"
                        @change="$wire.set('autoGenerateForm.clear_existing', true); $wire.set('autoGenerateForm.respect_existing_entries', false);"
                        class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                </div>
                <div class="ml-3 text-sm">
                    <span class="font-medium text-gray-700">Clear Existing Entries</span>
                    <p class="text-gray-500">Remove all existing entries before generating new ones</p>
                </div>
            </label>
            
            <!-- Respect Existing Option -->
            <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-white" :class="{'bg-white ring-2 ring-green-500': existingEntriesOption === 'respect', 'bg-gray-50': existingEntriesOption !== 'respect'}">
                <div class="flex items-center h-5">
                    <input type="radio" 
                        x-model="existingEntriesOption"
                        value="respect"
                        @change="$wire.set('autoGenerateForm.clear_existing', false); $wire.set('autoGenerateForm.respect_existing_entries', true);"
                        class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                </div>
                <div class="ml-3 text-sm">
                    <span class="font-medium text-gray-700">Respect Existing Entries</span>
                    <p class="text-gray-500">Keep existing entries and only fill empty slots</p>
                </div>
            </label>
        </div>
        
        <div class="mt-3 text-xs text-green-700 bg-green-50 rounded-md p-2 border border-green-200">
            <p class="font-medium">Currently selected: 
                <span x-text="existingEntriesOption === 'clear' ? 'Clear all existing entries' : 'Respect existing entries'"></span>
            </p>
        </div>
        
        @error('autoGenerateForm.clear_existing') 
            <p class="mt-2 text-xs text-red-600">{{ $message }}</p> 
        @enderror
        @error('autoGenerateForm.respect_existing_entries') 
            <p class="mt-2 text-xs text-red-600">{{ $message }}</p> 
        @enderror
    </div>
</div> 