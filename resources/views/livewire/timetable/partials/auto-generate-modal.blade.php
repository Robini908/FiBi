<!-- Auto-Generate Timetable Modal -->
<div
    x-data="{ 
        show: @entangle('showAutoGenerateModal'),
        activeTab: 'subject',
        isLoading: true,
        init() {
            // Set initial loading state
            this.isLoading = true;
            
            // Watch for modal visibility changes
            this.$watch('show', value => {
                if (value) {
                    // When modal is shown, indicate loading
                    this.isLoading = true;
                    
                    // Use Alpine's $nextTick to ensure the DOM is updated with loading state
                    this.$nextTick(() => {
                        // Give more time for data to load from the server
                        setTimeout(() => {
                            // Only mark as loaded if the modal is still open
                            if (this.show) {
                                this.isLoading = false;
                            }
                        }, 1000);
                    });
                }
            });
        }
    }"
    x-show="show"
    x-cloak
    class="fixed inset-0 overflow-y-auto z-50"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div 
            x-show="show" 
            class="fixed inset-0 transition-opacity"
            aria-hidden="true"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal panel - increased size -->
        <div 
            x-show="show"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
        >
            <!-- Close Button -->
            <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                <button 
                    type="button" 
                    @click="show = false"
                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Header -->
            <div class="bg-white px-5 pt-5 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Auto-Generate Timetable
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Intelligently generate a balanced timetable based on subjects, optimal teaching times, and teacher assignments.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation (Only visible after loading) -->
            <div x-show="!isLoading" class="bg-gray-50 border-t border-b border-gray-200">
                <div class="px-4 py-2">
                    <nav class="flex space-x-4">
                    <button 
                            type="button"
                        @click="activeTab = 'basic'" 
                            :class="{'px-4 py-2 text-sm font-medium rounded-md flex items-center transition-colors duration-200': true,
                                     'bg-green-100 text-green-700': activeTab === 'basic',
                                     'text-gray-500 hover:text-gray-700 hover:bg-gray-100': activeTab !== 'basic'}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        Basic Settings
                    </button>
                    <button 
                            type="button"
                            @click="activeTab = 'subject'" 
                            :class="{'px-4 py-2 text-sm font-medium rounded-md flex items-center transition-colors duration-200': true,
                                     'bg-green-100 text-green-700': activeTab === 'subject',
                                     'text-gray-500 hover:text-gray-700 hover:bg-gray-100': activeTab !== 'subject'}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Subject Preferences
                    </button>
                    <button 
                            type="button"
                        @click="activeTab = 'advanced'" 
                            :class="{'px-4 py-2 text-sm font-medium rounded-md flex items-center transition-colors duration-200': true,
                                     'bg-green-100 text-green-700': activeTab === 'advanced',
                                     'text-gray-500 hover:text-gray-700 hover:bg-gray-100': activeTab !== 'advanced'}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Advanced Options
                    </button>
                </nav>
            </div>
                        </div>
                        
            <div class="bg-white px-5 pb-5 max-h-[70vh] overflow-y-auto">
                <!-- Loading Spinner -->
                <div 
                    x-show="isLoading" 
                    class="flex flex-col items-center justify-center py-12"
                >
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                    <p class="mt-3 text-gray-600 text-sm font-medium">Loading timetable data...</p>
                    <p class="text-xs text-gray-400 mt-1">This may take a moment</p>
                </div>
                        
                <!-- Form content (hidden until fully loaded) -->
                <form wire:submit.prevent="autoGenerateTimetable" x-show="!isLoading" style="display: none;">
                    <!-- Basic Settings Tab (from partial) -->
                    <div x-show="activeTab === 'basic'">
                        @include('livewire.timetable.partials.auto-generate-modal-basic-settings')
                    </div>
                        
                    <!-- Subject Preferences Tab (from partial) -->
                    <div x-show="activeTab === 'subject'">
                        @include('livewire.timetable.partials.auto-generate-modal-subject-preferences')
                    </div>
                    
                    <!-- Advanced Options Tab (from partial) -->
                    <div x-show="activeTab === 'advanced'">
                        @include('livewire.timetable.partials.auto-generate-modal-advanced')
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="px-5 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between mt-4 -mx-5 rounded-b-lg">
                        <div>
                            <p class="text-xs text-gray-500">
                                <span class="text-green-600 font-medium">Pro Tip:</span> Use the Subject Preferences tab to customize how subjects are scheduled
                            </p>
                        </div>
                        <div class="flex">
                            <button type="button" 
                            @click="show = false"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Cancel
                        </button>
                            <button type="submit" 
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Generate Timetable
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 