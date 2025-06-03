<!-- Auto-Generate Timetable Card -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 ease-in-out"
    x-data="{
        activeTab: 'subject',
        isLoading: true,
        init() {
            // Give time for data to load
            setTimeout(() => {
                this.isLoading = false;
            }, 1000);
        }
    }">
    
    <!-- Card Header -->
    <div class="px-6 py-4 bg-white border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-green-100 rounded-full p-2">
                        <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-medium text-gray-900">Auto-Generate Timetable</h2>
                    <p class="text-sm text-gray-500">Configure settings to automatically generate a balanced timetable.</p>
                </div>
            </div>
            <button 
                wire:click="closeAutoGenerateModal"
                class="text-gray-400 hover:text-gray-500 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="isLoading" class="p-6">
        <div class="flex flex-col items-center justify-center py-12">
            <svg class="animate-spin h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-3 text-gray-600 text-sm font-medium">Loading timetable data...</p>
            <p class="text-xs text-gray-400 mt-1">This may take a moment</p>
        </div>
    </div>

    <!-- Content -->
    <div x-show="!isLoading" class="p-6">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                <button 
                    @click="activeTab = 'subject'"
                    :class="{'border-green-500 text-green-600': activeTab === 'subject', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'subject'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Subject Preferences
                </button>
                <button 
                    @click="activeTab = 'basic'"
                    :class="{'border-green-500 text-green-600': activeTab === 'basic', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'basic'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Basic Settings
                </button>
                <button 
                    @click="activeTab = 'advanced'"
                    :class="{'border-green-500 text-green-600': activeTab === 'advanced', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'advanced'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Advanced Options
                </button>
            </nav>
        </div>

        <form wire:submit.prevent="autoGenerateTimetable">
            <!-- Subject Preferences Tab -->
            <div x-show="activeTab === 'subject'" x-transition>
                <?php echo $__env->make('livewire.timetable.partials.auto-generate-modal-subject-preferences', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <!-- Basic Settings Tab -->
            <div x-show="activeTab === 'basic'" x-transition>
                <?php echo $__env->make('livewire.timetable.partials.auto-generate-modal-basic-settings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <!-- Advanced Options Tab -->
            <div x-show="activeTab === 'advanced'" x-transition>
                <?php echo $__env->make('livewire.timetable.partials.auto-generate-modal-advanced', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex items-center justify-between border-t border-gray-200 pt-6">
                <button 
                    type="button"
                    wire:click="closeAutoGenerateModal"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cancel
                </button>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        <span class="text-green-600 font-medium">Pro Tip:</span> 
                        Configure subject preferences for optimal scheduling
                    </span>
                    <button 
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-75 cursor-not-allowed">
                        <span wire:loading.remove>Generate Timetable</span>
                        <span wire:loading>
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generating...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/auto-generate-card.blade.php ENDPATH**/ ?>