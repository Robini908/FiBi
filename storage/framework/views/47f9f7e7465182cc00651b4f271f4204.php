<div x-data="{ filtersOpen: false }" class="mb-8">
    <!-- Filters button -->
    <div class="mb-4 sm:mb-0">
        <button
            @click.prevent="filtersOpen = !filtersOpen"
            class="btn bg-white border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-600"
        >
            <span class="sr-only">Filter</span><wbr>
            <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16">
                <path d="M9 15H7a1 1 0 010-2h2a1 1 0 010 2zM11 11H5a1 1 0 010-2h6a1 1 0 010 2zM13 7H3a1 1 0 010-2h10a1 1 0 010 2zM15 3H1a1 1 0 010-2h14a1 1 0 010 2z" />
            </svg>
            <span class="ml-2">Filter</span>
        </button>
    </div>

    <!-- Filters panel -->
    <div
        x-show="filtersOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-out duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="bg-white shadow-lg rounded-sm border border-gray-200 p-4 mb-4"
    >
        <div class="grid grid-cols-12 gap-4">
            <!-- Search field -->
            <div class="col-span-12 md:col-span-6 xl:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="search">Search</label>
                <div class="relative">
                    <input
                        id="search"
                        wire:model.live.debounce.300ms="search"
                        class="form-input w-full pl-9 pr-3 py-2 text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                        type="search"
                        placeholder="Voucher #, Recipient, Purpose..."
                    />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Votehead filter -->
            <div class="col-span-12 md:col-span-6 xl:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="votehead-filter">Votehead</label>
                <select
                    id="votehead-filter"
                    wire:model.live="voteheadFilter"
                    class="form-select w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                >
                    <option value="">All Voteheads</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $voteheads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $votehead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($votehead->id); ?>"><?php echo e($votehead->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <!-- Status filter -->
            <div class="col-span-12 md:col-span-6 xl:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="status-filter">Status</label>
                <select
                    id="status-filter"
                    wire:model.live="statusFilter"
                    class="form-select w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                >
                    <option value="">All Statuses</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <!-- Academic Year filter -->
            <div class="col-span-12 md:col-span-6 xl:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="year-filter">Academic Year</label>
                <select
                    id="year-filter"
                    wire:model.live="yearFilter"
                    class="form-select w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                >
                    <option value="">All Years</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <!-- Term filter -->
            <div class="col-span-12 md:col-span-6 xl:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="term-filter">Term</label>
                <select
                    id="term-filter"
                    wire:model.live="termFilter"
                    class="form-select w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                >
                    <option value="">All Terms</option>
                    <option value="1">Term 1</option>
                    <option value="2">Term 2</option>
                    <option value="3">Term 3</option>
                </select>
            </div>

            <!-- Date range filters -->
            <div class="col-span-12 md:col-span-6 xl:col-span-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <div class="flex space-x-2">
                    <div class="flex-1">
                        <input
                            type="date"
                            wire:model.live="dateFrom"
                            class="form-input w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            placeholder="From"
                        />
                    </div>
                    <div class="flex-1">
                        <input
                            type="date"
                            wire:model.live="dateTo"
                            class="form-input w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            placeholder="To"
                        />
                    </div>
                    <div>
                        <button 
                            wire:click="resetDateFilters"
                            class="btn-sm bg-gray-100 hover:bg-gray-200 text-gray-600"
                        >
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter actions -->
            <div class="col-span-12 flex justify-end">
                <div class="space-x-2">
                    <button 
                        wire:click="resetFilters"
                        class="btn-sm bg-white border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-600"
                    >
                        Reset Filters
                    </button>
                    <button 
                        wire:click="applyFilters"
                        class="btn-sm bg-green-600 hover:bg-green-700 text-white"
                    >
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/payment-voucher-filters.blade.php ENDPATH**/ ?>