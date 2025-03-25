<div class="px-6 py-4 border-b border-gray-200 bg-white">
    <div class="flex flex-col sm:flex-row justify-between items-center">
        <h1 class="text-xl font-semibold text-gray-800 mb-4 sm:mb-0 flex items-center">
            <svg class="w-7 h-7 mr-3 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>Student Management</span>
        </h1>
        <div class="flex items-center gap-3">
            <!-- Filter Button with Tooltip -->
            <div x-data="{ tooltip: false }" class="relative">
                <button type="button" @click="showFilters = !showFilters" 
                    @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                    class="relative inline-flex items-center justify-center p-2 rounded-full text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 border border-gray-200 shadow-sm transition-colors group">
                    <span class="absolute -top-1 -right-1" x-show="showFilters">
                        <span class="flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                    </span>
                    <svg class="h-5 w-5 group-hover:text-green-600 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span class="sr-only">Toggle Filters</span>
                </button>
                <!-- Tooltip -->
                <div x-show="tooltip" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1" 
                     class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded pointer-events-none whitespace-nowrap">
                    <span x-text="$root.showFilters ? 'Hide Filters' : 'Show Filters'">Show Filters</span>
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 h-2 w-2 rotate-45 bg-gray-900"></div>
                </div>
            </div>
            
            <!-- PDF Export Button with Tooltip -->
            <div x-data="{ tooltip: false }" class="relative">
                <button wire:click="generatePdfReport" wire:loading.attr="disabled"
                    @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                    class="relative inline-flex items-center justify-center p-2 rounded-full text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 border border-gray-200 shadow-sm transition-colors group">
                    <svg class="h-5 w-5 group-hover:text-red-600 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="sr-only">Generate PDF Report</span>
                    <div wire:loading wire:target="generatePdfReport" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-70 rounded-full">
                        <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
                <!-- Tooltip -->
                <div x-show="tooltip" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1" 
                     class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded pointer-events-none whitespace-nowrap">
                    Export PDF Report
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 h-2 w-2 rotate-45 bg-gray-900"></div>
                </div>
            </div>
            
            <!-- Add Student Button (Google-style FAB) with Tooltip -->
            <div x-data="{ tooltip: false }" class="relative">
                <button wire:click="openAddStudentForm"
                    @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                    class="relative inline-flex items-center justify-center p-3 rounded-full text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md transition-colors transform hover:scale-105">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span class="sr-only">Add Student</span>
                </button>
                <!-- Tooltip -->
                <div x-show="tooltip" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1" 
                     class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded pointer-events-none whitespace-nowrap">
                    Add New Student
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 h-2 w-2 rotate-45 bg-gray-900"></div>
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/header.blade.php ENDPATH**/ ?>