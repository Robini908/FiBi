<div class="bg-white p-4 rounded-lg shadow-sm mb-6" x-data="{ filtersOpen: false }">
    <div class="flex justify-between items-center">
        <button 
            @click="filtersOpen = !filtersOpen" 
            type="button"
            class="flex items-center text-sm text-gray-600 hover:text-gray-900 focus:outline-none"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
            </svg>
            <span x-text="filtersOpen ? 'Hide Filters' : 'Show Filters'"></span>
        </button>
        
        <!-- Active Filters Display -->
        <div class="flex flex-wrap items-center space-x-2">
            @foreach ($activeFilters as $key => $value)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $key }}: {{ $value }}
                    <button 
                        wire:click="clearFilter('{{ strtolower($key) }}')" 
                        type="button" 
                        class="flex-shrink-0 ml-1 h-4 w-4 rounded-full inline-flex items-center justify-center text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none"
                    >
                        <span class="sr-only">Remove filter for {{ $key }}</span>
                        <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                        </svg>
                    </button>
                </span>
            @endforeach
            
            @if (count($activeFilters) > 0)
                <button 
                    wire:click="resetFilters" 
                    type="button" 
                    class="inline-flex items-center px-2 py-1 border border-gray-300 rounded-md text-xs font-medium bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Clear All
                </button>
            @endif
        </div>
    </div>
    
    <!-- Filters Panel -->
    <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
        <div>
            <label for="teacherFilter" class="block text-sm font-medium text-gray-700">Filter by Teacher</label>
            <select 
                wire:model.live="teacherFilter" 
                id="teacherFilter" 
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
            >
                <option value="">All Teachers</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="sessionFilter" class="block text-sm font-medium text-gray-700">Filter by Session</label>
            <select 
                wire:model.live="sessionFilter" 
                id="sessionFilter" 
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
            >
                <option value="">All Sessions</option>
                @foreach ($this->getYearsRange() as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div> 