<!-- Search and Filters -->
<div class="p-4 sm:p-6 bg-white border-b border-gray-200">
    <div class="flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
        <div class="w-full md:w-1/3 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input 
                wire:model.debounce.300ms="search" 
                type="text" 
                placeholder="<?php echo e($isStudent ? 'Search descriptions or amounts...' : 'Search students, amounts, or descriptions...'); ?>" 
                class="w-full pl-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
            >
        </div>
        
        <div x-data="{ filtersOpen: false }" class="flex items-center space-x-2">
            <button 
                @click="filtersOpen = !filtersOpen"
                type="button" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filters
                <!--[if BLOCK]><![endif]--><?php if($classFilter || $yearFilter || $termFilter || $statusFilter): ?>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </button>
            
            <div 
                x-show="filtersOpen" 
                @click.away="filtersOpen = false" 
                x-transition:enter="transition ease-out duration-100" 
                x-transition:enter-start="transform opacity-0 scale-95" 
                x-transition:enter-end="transform opacity-100 scale-100" 
                x-transition:leave="transition ease-in duration-75" 
                x-transition:leave-start="transform opacity-100 scale-100" 
                x-transition:leave-end="transform opacity-0 scale-95" 
                class="origin-top-right absolute right-0 mt-40 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50" 
                style="display: none;"
            >
                <div class="py-1 px-3">
                    <div class="text-sm font-medium text-gray-900 pt-2">Filter Arrears</div>
                </div>
                
                <!--[if BLOCK]><![endif]--><?php if(($isAdmin || $isAccountant || $isParent) && !$isStudent): ?>
                <div class="py-2 px-3">
                    <label for="classFilter" class="block text-sm font-medium text-gray-700">Class</label>
                    <select 
                        wire:model="classFilter" 
                        id="classFilter" 
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                    >
                        <option value="">All Classes</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <div class="py-2 px-3">
                    <label for="yearFilter" class="block text-sm font-medium text-gray-700">Previous Year</label>
                    <select 
                        wire:model="yearFilter" 
                        id="yearFilter" 
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                    >
                        <option value="">All Years</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                
                <div class="py-2 px-3">
                    <label for="termFilter" class="block text-sm font-medium text-gray-700">Previous Term</label>
                    <select 
                        wire:model="termFilter" 
                        id="termFilter" 
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                    >
                        <option value="">All Terms</option>
                        <option value="1">Term 1</option>
                        <option value="2">Term 2</option>
                        <option value="3">Term 3</option>
                    </select>
                </div>
                
                <div class="py-2 px-3">
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700">Status</label>
                    <select 
                        wire:model="statusFilter" 
                        id="statusFilter" 
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                    >
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="cleared">Cleared</option>
                    </select>
                </div>
                
                <div class="py-2 px-3 flex justify-between">
                    <button 
                        wire:click="resetFilters"
                        type="button" 
                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        Reset Filters
                    </button>
                    
                    <button 
                        wire:click="applyFilters"
                        @click="filtersOpen = false"
                        type="button" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-sm font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Filters -->
    <!--[if BLOCK]><![endif]--><?php if($classFilter || $yearFilter || $termFilter || $statusFilter): ?>
    <div class="mt-4 flex flex-wrap items-center gap-2">
        <span class="text-sm text-gray-500">Active Filters:</span>
        
        <!--[if BLOCK]><![endif]--><?php if($classFilter): ?>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            Class: <?php echo e($classes->firstWhere('id', $classFilter)?->name ?: 'Unknown'); ?>

            <button wire:click="$set('classFilter', '')" class="ml-1 text-green-500 hover:text-green-600 focus:outline-none">
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <!--[if BLOCK]><![endif]--><?php if($yearFilter): ?>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            Year: <?php echo e($yearFilter); ?>

            <button wire:click="$set('yearFilter', '')" class="ml-1 text-green-500 hover:text-green-600 focus:outline-none">
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <!--[if BLOCK]><![endif]--><?php if($termFilter): ?>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            Term: <?php echo e($termFilter); ?>

            <button wire:click="$set('termFilter', '')" class="ml-1 text-green-500 hover:text-green-600 focus:outline-none">
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <!--[if BLOCK]><![endif]--><?php if($statusFilter): ?>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            Status: <?php echo e(ucfirst($statusFilter)); ?>

            <button wire:click="$set('statusFilter', '')" class="ml-1 text-green-500 hover:text-green-600 focus:outline-none">
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <button 
            wire:click="resetFilters"
            class="text-sm text-red-600 hover:text-red-800 font-medium focus:outline-none"
        >
            Clear All
        </button>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/student-arrears-filters.blade.php ENDPATH**/ ?>