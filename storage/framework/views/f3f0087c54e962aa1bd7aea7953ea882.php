<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
        <!-- Search input -->
        <div class="flex-grow">
            <label for="filterSearch" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       id="filterSearch" 
                       class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                       placeholder="Search by name or admission number">
            </div>
        </div>

        <!-- Class filter -->
        <div class="w-full md:w-48">
            <label for="filterClass" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
            <select wire:model.live="classFilter" 
                    id="filterClass" 
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="">All Classes</option>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </select>
        </div>

        <!-- Suspension Type filter -->
        <div class="w-full md:w-48">
            <label for="filterType" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select wire:model.live="typeFilter" 
                    id="filterType" 
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="">All Types</option>
                <option value="temporary">Temporary</option>
                <option value="indefinite">Indefinite</option>
            </select>
        </div>
        
        <!-- Duration filter -->
        <div class="w-full md:w-48">
            <label for="filterDuration" class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
            <select wire:model.live="durationFilter" 
                    id="filterDuration" 
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="">Any Duration</option>
                <option value="short">Short (< 1 week)</option>
                <option value="medium">Medium (1-4 weeks)</option>
                <option value="long">Long (> 4 weeks)</option>
            </select>
        </div>
    </div>
    
    <!-- Active filters and reset button -->
    <div class="mt-4 flex items-center" x-data="{ hasFilters: false }" 
         x-init="hasFilters = $wire.search || $wire.classFilter || $wire.typeFilter || $wire.durationFilter"
         @search-changed.window="hasFilters = $wire.search || $wire.classFilter || $wire.typeFilter || $wire.durationFilter">
        
        <div class="flex flex-wrap gap-2" x-show="hasFilters">
            <!-- Search filter tag -->
            <div x-show="$wire.search" class="inline-flex rounded-full items-center py-1 pl-3 pr-1 bg-blue-100 text-sm font-medium text-blue-700">
                <span>Search: "{{ $wire.search }}"</span>
                <button type="button" wire:click="$set('search', '')" class="flex-shrink-0 ml-1 h-5 w-5 rounded-full inline-flex items-center justify-center text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white">
                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Class filter tag -->
            <div x-show="$wire.classFilter" class="inline-flex rounded-full items-center py-1 pl-3 pr-1 bg-blue-100 text-sm font-medium text-blue-700">
                <span>Class: {{ $wire.getClassName($wire.classFilter) }}</span>
                <button type="button" wire:click="$set('classFilter', '')" class="flex-shrink-0 ml-1 h-5 w-5 rounded-full inline-flex items-center justify-center text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white">
                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Type filter tag -->
            <div x-show="$wire.typeFilter" class="inline-flex rounded-full items-center py-1 pl-3 pr-1 bg-blue-100 text-sm font-medium text-blue-700">
                <span>Type: {{ $wire.typeFilter === 'temporary' ? 'Temporary' : 'Indefinite' }}</span>
                <button type="button" wire:click="$set('typeFilter', '')" class="flex-shrink-0 ml-1 h-5 w-5 rounded-full inline-flex items-center justify-center text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white">
                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Duration filter tag -->
            <div x-show="$wire.durationFilter" class="inline-flex rounded-full items-center py-1 pl-3 pr-1 bg-blue-100 text-sm font-medium text-blue-700">
                <span>Duration: {{ $wire.durationFilter === 'short' ? 'Short' : ($wire.durationFilter === 'medium' ? 'Medium' : 'Long') }}</span>
                <button type="button" wire:click="$set('durationFilter', '')" class="flex-shrink-0 ml-1 h-5 w-5 rounded-full inline-flex items-center justify-center text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white">
                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Reset all filters button -->
            <button wire:click="resetFilters" 
                    class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Reset all
                <svg class="ml-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
        
        <div x-show="!hasFilters" class="text-sm text-gray-500 italic">
            No filters applied
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/suspensions/filter-controls.blade.php ENDPATH**/ ?>