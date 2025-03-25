<!-- Header and Filters Section -->
<!-- Debug: {{ $hasManagePermission ? 'Has Permission: true' : 'Has Permission: false' }} - isAdmin: {{ $isAdmin ? 'true' : 'false' }} - isAccountant: {{ $isAccountant ? 'true' : 'false' }} -->
<div class="sm:flex sm:justify-between sm:items-center mb-8">
    <!-- Left: Title -->
    <div class="mb-4 sm:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Fee Structure</h1>
        <p class="text-sm text-gray-500 mt-1">Manage school fees and configure payment structure for each class</p>
    </div>

    <!-- Right: Actions -->
    <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
        <!-- Export button -->
        <div class="relative inline-flex">
            <button
                wire:click="exportFeeStructures"
                class="btn bg-white border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-600"
            >
                <span class="hidden xs:block ml-2">Export</span>
                <svg class="w-4 h-4 fill-current text-gray-500 shrink-0" viewBox="0 0 16 16">
                    <path d="M15 7h-3V1H8v6H5l5 5 5-5z" />
                    <path d="M2 13h12v2H2z" />
                </svg>
            </button>
        </div>

        <!-- Add fee button -->
        @if($hasManagePermission)
        <button
            wire:click="openModal"
            class="btn bg-green-600 hover:bg-green-700 text-white"
        >
            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
            </svg>
            <span class="hidden xs:block ml-2">Add Fee</span>
        </button>
        @endif
    </div>
</div>

<!-- Filters Section -->
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
                        placeholder="Fee Name, Description..."
                    />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Class filter -->
            <div class="col-span-12 md:col-span-6 xl:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="class-filter">Class</label>
                <select
                    id="class-filter"
                    wire:model.live="classFilter"
                    class="form-select w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                >
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year filter -->
            <div class="col-span-12 md:col-span-6 xl:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="year-filter">Academic Year</label>
                <select
                    id="year-filter"
                    wire:model.live="yearFilter"
                    class="form-select w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                >
                    <option value="">All Years</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
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

            <!-- Category filter -->
            <div class="col-span-12 md:col-span-6 xl:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="category-filter">Category</label>
                <select
                    id="category-filter"
                    wire:model.live="categoryFilter"
                    class="form-select w-full text-sm border-gray-300 rounded-md focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
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
</div> 