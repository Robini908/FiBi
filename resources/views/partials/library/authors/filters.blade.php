<div x-data="{ filtersOpen: false }" class="relative mb-6">
    <!-- Filter Toggle Button -->
    <button 
        @click="filtersOpen = !filtersOpen"
        class="absolute right-0 top-0 -mt-2 -mr-2 z-10 flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-md border border-gray-200 text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
        aria-label="Toggle filters"
    >
        <template x-if="!filtersOpen">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
        </template>
        <template x-if="filtersOpen">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </template>
    </button>

    <!-- Active Filters Badge -->
    <div 
        x-cloak
        x-show="!filtersOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        class="absolute right-0 top-0 mt-8 -mr-2 z-10"
    >
        @if(!empty($search) || !empty($selectedNationalities) || $showOnlyFeatured || $showOnlyActive || $showOnlyWithBooks)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                {{ (!empty($search) ? 1 : 0) + 
                   (!empty($selectedNationalities) ? 1 : 0) + 
                   ($showOnlyFeatured ? 1 : 0) + 
                   ($showOnlyActive ? 1 : 0) +
                   ($showOnlyWithBooks ? 1 : 0) }} active
            </span>
        @endif
    </div>

    <!-- Expandable Filter Panel -->
    <div 
        x-cloak
        x-show="filtersOpen" 
        x-transition:enter="transition ease-out duration-200" 
        x-transition:enter-start="opacity-0 transform -translate-y-4" 
        x-transition:enter-end="opacity-100 transform translate-y-0" 
        x-transition:leave="transition ease-in duration-150" 
        x-transition:leave-start="opacity-100 transform translate-y-0" 
        x-transition:leave-end="opacity-0 transform -translate-y-4" 
        class="bg-white shadow-md rounded-lg p-4 border border-gray-100"
    >
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-gray-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filters
            </h3>
            
            <button wire:click="resetFilters" type="button" 
                class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200">
                <svg class="mr-1 h-3 w-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Reset
            </button>
        </div>

        <div class="space-y-3">
            <!-- Primary Search & Filters -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <!-- Search Input -->
                <div class="md:col-span-5">
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM8.586 10l-1.293-1.293a1 1 0 00-1.414 1.414L7.172 12l-1.293 1.293a1 1 0 101.414 1.414L8.586 12l1.293-1.293a1 1 0 00-1.414-1.414L7.172 10z" />
                            </svg>
                        </div>
                        <input wire:model.debounce.300ms="search" type="text" id="author-search" 
                            class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 pr-10 py-2 text-sm border-gray-300 rounded-md" 
                            placeholder="Search by name, nationality, or biography...">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button wire:click="$set('search', '')" type="button" class="{{ empty($search) ? 'hidden' : 'block' }} text-gray-400 hover:text-gray-500">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            
                <!-- Nationality Filter -->
                <div class="md:col-span-4">
                    <select wire:model="selectedNationalities" id="nationality-filter" multiple
                        class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 rounded-md">
                        <option value="">All Nationalities</option>
                        @foreach($nationalities as $nationality)
                            <option value="{{ $nationality }}">{{ $nationality }}</option>
                        @endforeach
                    </select>
                </div>
            
                <!-- Sort & Direction Controls -->
                <div class="md:col-span-3">
                    <div class="flex">
                        <select wire:model="sortField" id="sort-order"
                            class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 rounded-l-md">
                            <option value="name">Name</option>
                            <option value="created_at">Date Added</option>
                            <option value="nationality">Nationality</option>
                            <option value="books_count">Books Count</option>
                        </select>
                    
                        <!-- Sort Direction Button -->
                        <button wire:click="toggleSortDirection" class="relative inline-flex items-center justify-center px-2 py-2 border border-l-0 border-gray-300 text-sm leading-4 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            @if($sortDirection === 'asc')
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                                </svg>
                            @else
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                </svg>
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Filters Row -->
            <div class="flex flex-wrap -mx-1">
                <div class="px-1 w-1/2 sm:w-1/3">
                    <label class="flex items-center justify-between px-3 py-2 rounded-md border border-gray-200 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors duration-200 w-full">
                        <span class="text-xs font-medium text-gray-700">Active Only</span>
                        <input wire:model="showOnlyActive" id="only-active" type="checkbox" 
                            class="h-4 w-4 text-green-600 focus:ring-green-500 focus:ring-opacity-25 border-gray-300 rounded">
                    </label>
                </div>
                
                <div class="px-1 w-1/2 sm:w-1/3">
                    <label class="flex items-center justify-between px-3 py-2 rounded-md border border-gray-200 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors duration-200 w-full">
                        <span class="text-xs font-medium text-gray-700">Featured</span>
                        <input wire:model="showOnlyFeatured" id="only-featured" type="checkbox" 
                            class="h-4 w-4 text-green-600 focus:ring-green-500 focus:ring-opacity-25 border-gray-300 rounded">
                    </label>
                </div>
                
                <div class="px-1 w-1/2 sm:w-1/3">
                    <label class="flex items-center justify-between px-3 py-2 rounded-md border border-gray-200 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors duration-200 w-full">
                        <span class="text-xs font-medium text-gray-700">With Books</span>
                        <input wire:model="showOnlyWithBooks" id="only-with-books" type="checkbox" 
                            class="h-4 w-4 text-green-600 focus:ring-green-500 focus:ring-opacity-25 border-gray-300 rounded">
                    </label>
                </div>
            </div>
            
            @if(auth()->user()->hasAnyRole(['librarian', 'admin', 'superadmin']))
            <!-- Librarian/Admin Actions -->
            <div class="pt-3 border-t border-gray-200">
                <div class="flex flex-wrap -mx-1">
                    <!-- Export to Excel -->
                    <div class="px-1 w-1/2 sm:w-1/4 mb-2">
                        <button wire:click="exportToExcel" type="button" 
                            class="w-full inline-flex items-center justify-center px-2 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200">
                            <svg class="h-3.5 w-3.5 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Export</span>
                        </button>
                    </div>
                    
                    <!-- Print Data -->
                    <div class="px-1 w-1/2 sm:w-1/4 mb-2">
                        <button wire:click="printData" type="button" 
                            class="w-full inline-flex items-center justify-center px-2 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200">
                            <svg class="h-3.5 w-3.5 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Print</span>
                        </button>
                    </div>
                    
                    <!-- Add New Author Button -->
                    <div class="px-1 w-1/2 sm:w-1/4 mb-2">
                        <button wire:click="openCreateModal" type="button" 
                            class="w-full inline-flex items-center justify-center px-2 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200">
                            <svg class="h-3.5 w-3.5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Add Author</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Active Filters Display -->
            @if(!empty($search) || !empty($selectedNationalities) || $showOnlyActive || $showOnlyFeatured || $showOnlyWithBooks)
            <div class="flex flex-wrap items-center pt-2">
                <span class="text-xs font-medium text-gray-500 mr-1 mb-1">Active:</span>
                
                @if(!empty($search))
                <span class="mr-1 mb-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ Str::limit($search, 15) }}
                    <button wire:click="$set('search', '')" class="ml-1 flex-shrink-0 inline-flex text-green-500 focus:outline-none">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
                @endif
                
                @if(!empty($selectedNationalities))
                <span class="mr-1 mb-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ count($selectedNationalities) }} {{ Str::plural('nationality', count($selectedNationalities)) }}
                    <button wire:click="$set('selectedNationalities', [])" class="ml-1 flex-shrink-0 inline-flex text-blue-500 focus:outline-none">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
                @endif
                
                @if($showOnlyActive)
                <span class="mr-1 mb-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Active Only
                    <button wire:click="$set('showOnlyActive', false)" class="ml-1 flex-shrink-0 inline-flex text-yellow-500 focus:outline-none">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
                @endif
                
                @if($showOnlyFeatured)
                <span class="mr-1 mb-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    Featured
                    <button wire:click="$set('showOnlyFeatured', false)" class="ml-1 flex-shrink-0 inline-flex text-purple-500 focus:outline-none">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
                @endif
                
                @if($showOnlyWithBooks)
                <span class="mr-1 mb-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    With Books
                    <button wire:click="$set('showOnlyWithBooks', false)" class="ml-1 flex-shrink-0 inline-flex text-indigo-500 focus:outline-none">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style> 