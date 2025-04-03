<div x-data="{ 
    downloadUrl: '',
    printUrl: '',
    showToast: false,
    toastMessage: '',
    toastType: 'info', // 'info', 'success', 'warning', 'error'
    isProcessing: false,
    processingMessage: 'Processing your request...',
    
    initializeEvents() {
        // Handle download events
        Livewire.on('triggerDownload', () => {
            this.downloadUrl = @json(session('download_url'));
            if (this.downloadUrl) {
                // Force download in a new window to ensure it works
                const downloadWindow = window.open(this.downloadUrl, '_blank');
                if (!downloadWindow) {
                    this.showAlert('Your browser blocked the download. Please allow popups for this site.');
                }
            } else {
                this.showAlert('Download URL not available. Please try again.');
            }
        });
        
        // Handle print events
        Livewire.on('openPrintWindow', () => {
            this.printUrl = @json(session('print_url'));
            if (this.printUrl) {
                // Open print view in a new tab
                const printWindow = window.open(this.printUrl, '_blank');
                if (!printWindow) {
                    this.showAlert('Your browser blocked the print window. Please allow popups for this site.');
                }
            } else {
                this.showAlert('Print URL not available. Please try again.');
            }
        });
        
        // Handle toast notifications
        Livewire.on('show-toast', () => {
            this.toastMessage = @json(session('toast_message'));
            this.toastType = @json(session('toast_type')) || 'info';
            
            if (this.toastMessage) {
                this.showToast = true;
                setTimeout(() => {
                    this.showToast = false;
                }, 5000); // Hide toast after 5 seconds
            }
        });
        
        // Handle loading states
        Livewire.hook('message.sent', (message, component) => {
            this.isProcessing = true;
            this.updateProcessingMessage();
        });
        
        Livewire.hook('message.processed', (message, component) => {
            this.isProcessing = false;
        });
        
        Livewire.hook('message.failed', (message, component) => {
            this.isProcessing = false;
            this.showAlert('The operation failed. Please try again.');
        });
    },
    
    updateProcessingMessage() {
        if (this.$wire.isLoading) {
            this.processingMessage = 'Consolidating timetables... This may take a moment.';
        } else if (this.$wire.exportType === 'pdf') {
            this.processingMessage = 'Generating PDF export...';
        } else if (this.$wire.exportType === 'excel') {
            this.processingMessage = 'Generating Excel export...';
        } else if (this.$wire.exportType === 'print') {
            this.processingMessage = 'Preparing print view...';
        } else {
            this.processingMessage = 'Processing your request...';
        }
    },
    
    showAlert(message) {
        this.toastMessage = message;
        this.toastType = 'error';
        this.showToast = true;
        setTimeout(() => {
            this.showToast = false;
        }, 5000);
    }
}" x-init="initializeEvents()">
    <!-- Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-4 right-4 z-50 max-w-md"
         @click="showToast = false">
        <div class="flex items-center p-4 rounded-lg shadow-lg" 
             :class="{
                'bg-gray-50 text-gray-800 border-l-4 border-gray-500': toastType === 'info',
                'bg-green-50 text-green-800 border-l-4 border-green-500': toastType === 'success',
                'bg-yellow-50 text-yellow-800 border-l-4 border-yellow-500': toastType === 'warning',
                'bg-red-50 text-red-800 border-l-4 border-red-500': toastType === 'error'
             }">
            <div class="flex-shrink-0 mr-3">
                <template x-if="toastType === 'info'">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="toastType === 'success'">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="toastType === 'warning'">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <template x-if="toastType === 'error'">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </div>
            <div>
                <p class="text-sm font-medium" x-text="toastMessage"></p>
            </div>
            <div class="ml-auto pl-3">
                <button @click="showToast = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Timetable Consolidator</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Combine multiple timetables into a consolidated view</p>
            </div>
            
            <div class="flex space-x-2">
                <!-- Tabs -->
                <div class="hidden sm:block">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <button type="button" 
                            wire:click="$set('selectedTab', 'selection')" 
                            class="{{ $selectedTab === 'selection' ? 'bg-green-100 text-green-700' : 'text-gray-500 hover:text-gray-700' }} px-3 py-2 font-medium text-sm rounded-md">
                            Select Timetables
                        </button>
                        <button type="button" 
                            wire:click="$set('selectedTab', 'preview')" 
                            class="{{ $selectedTab === 'preview' ? 'bg-green-100 text-green-700' : 'text-gray-500 hover:text-gray-700' }} px-3 py-2 font-medium text-sm rounded-md"
                            {{ !$isConsolidated ? 'disabled' : '' }}>
                            Preview Results
                        </button>
                    </nav>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-200">
            <!-- Tab Content -->
            @if ($selectedTab === 'selection')
                @include('livewire.timetable.partials.consolidator-selection')
            @elseif ($selectedTab === 'preview' && $isConsolidated)
                @include('livewire.timetable.partials.consolidator-preview')
            @elseif ($selectedTab === 'preview' && !$isConsolidated)
                <div class="p-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No Consolidated Timetable Available</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Please select timetables and configuration options, then click "Generate Consolidated Timetable" to see results.
                    </p>
                    <button type="button" 
                        wire:click="$set('selectedTab', 'selection')" 
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Go to Selection
                    </button>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Error Log Section -->
    @if (!empty($consolidationErrors))
        <div class="mt-6">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Processing Log</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Recent messages and errors</p>
                    </div>
                    <button type="button" 
                        wire:click="$set('consolidationErrors', [])" 
                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Clear Log
                    </button>
                </div>
                <div class="border-t border-gray-200">
                    <div class="bg-gray-50 overflow-y-auto max-h-40">
                        <ul class="divide-y divide-gray-200">
                            @foreach($consolidationErrors as $error)
                                <li class="px-4 py-2 text-sm">
                                    <span class="text-gray-500">{{ $error['time'] }}: </span>
                                    <span class="text-red-600">{{ $error['message'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Loading Overlay - New Implementation -->
    <div x-show="isProcessing" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-center">
                <svg class="animate-spin h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <p class="mt-4 text-center text-gray-700" x-text="processingMessage"></p>
            <div class="mt-4 flex justify-end">
                <button 
                    @click="isProcessing = false"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 