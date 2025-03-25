<div class="bg-white rounded-lg shadow-sm overflow-hidden" x-data="{ processing: false }">
    <!-- Header with accent -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
            Student Expulsion
        </h3>
        <button 
            wire:click="closeAction" 
            class="text-gray-500 hover:text-gray-700 focus:outline-none transition-colors"
            aria-label="Close"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div class="p-6">
        <!-- Student Info Card -->
        <div class="mb-6 bg-gray-50 rounded-lg p-4">
            <div class="flex items-start">
                <!-- Student avatar or initials -->
                <div class="flex-shrink-0">
                    @if ($selectedStudent->photo)
                        <img src="{{ asset($selectedStudent->photo) }}" alt="{{ $selectedStudent->first_name }}" class="h-12 w-12 rounded-full object-cover">
                    @else
                        <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-green-700 font-medium text-sm">{{ substr($selectedStudent->first_name, 0, 1) }}{{ substr($selectedStudent->last_name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Student details -->
                <div class="ml-4 flex-1">
                    <h4 class="text-base font-medium text-gray-900">{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</h4>
                    <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Class:</span> {{ $selectedStudent->my_class->name ?? 'N/A' }} - {{ $selectedStudent->section->name ?? 'N/A' }}
                        </div>
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Admission No:</span> {{ $selectedStudent->adm_no }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Warning Card -->
        <div class="mb-6 bg-red-50 border border-red-100 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Warning: Permanent Action</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>You are about to expel <strong>{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</strong> from the school system.</p>
                        <p class="mt-2">This is a serious disciplinary action that will permanently remove the student from the school.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Basic Expulsion Form -->
        <div class="space-y-4">
                <div>
                    <label for="expulsionReason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Expulsion</label>
                    <div>
                        <textarea 
                            wire:model.live="expulsionReason" 
                            id="expulsionReason" 
                            rows="3" 
                            placeholder="Provide detailed reason for the expulsion..."
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        ></textarea>
                        @error('expulsionReason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                <label for="expulsionDate" class="block text-sm font-medium text-gray-700 mb-1">Effective Date</label>
                    <div>
                        <input 
                            type="date" 
                            wire:model.live="expulsionDate" 
                            id="expulsionDate" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        >
                        @error('expulsionDate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        wire:model.live="confirmExpulsion" 
                        id="confirmExpulsion" 
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                    >
                    <label for="confirmExpulsion" class="ml-2 block text-sm text-gray-700">
                        I confirm that all necessary procedures have been followed and this expulsion is justified.
                    </label>
                </div>
                @error('confirmExpulsion')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
            <button 
                wire:click="closeAction" 
                class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
            >
                Cancel
            </button>
            
                <button 
                wire:click="confirmStudentExpulsion" 
                    x-on:click="processing = true"
                    x-bind:disabled="processing"
                    class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <span x-show="!processing">Confirm Expulsion</span>
                    <span x-show="processing">Processing...</span>
                    <span x-show="processing" class="ml-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
        </div>
    </div>
</div> 