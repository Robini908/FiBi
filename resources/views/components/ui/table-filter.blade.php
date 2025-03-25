@props([
    'id' => 'table-filter-' . uniqid(),
    'filters' => [],
    'operators' => [
        'text' => [
            'contains' => 'Contains',
            'equals' => 'Equals',
            'starts_with' => 'Starts with',
            'ends_with' => 'Ends with',
            'is_empty' => 'Is empty',
            'is_not_empty' => 'Is not empty',
        ],
        'number' => [
            'equals' => 'Equals',
            'not_equals' => 'Not equals',
            'greater_than' => 'Greater than',
            'less_than' => 'Less than',
            'greater_than_or_equal' => 'Greater than or equal',
            'less_than_or_equal' => 'Less than or equal',
            'is_empty' => 'Is empty',
            'is_not_empty' => 'Is not empty',
        ],
        'date' => [
            'equals' => 'Equals',
            'not_equals' => 'Not equals',
            'greater_than' => 'After',
            'less_than' => 'Before',
            'between' => 'Between',
            'is_empty' => 'Is empty',
            'is_not_empty' => 'Is not empty',
        ],
        'boolean' => [
            'equals' => 'Equals',
            'not_equals' => 'Not equals',
        ],
        'select' => [
            'equals' => 'Equals',
            'not_equals' => 'Not equals',
            'is_empty' => 'Is empty',
            'is_not_empty' => 'Is not empty',
        ],
    ],
    'conjunctions' => [
        'and' => 'And',
        'or' => 'Or',
    ],
    'applyButtonText' => 'Apply Filters',
    'resetButtonText' => 'Reset Filters',
    'addFilterText' => 'Add Filter',
    'buttonSize' => 'sm',
    'variant' => 'primary',
    'showBorder' => true,
    'showShadow' => true,
    'rounded' => 'md',
    'maxHeight' => '500px',
])

@php
    // Rounded classes
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-md';
    
    // Button size classes
    $buttonSizeClasses = [
        'xs' => 'py-1 px-2 text-xs',
        'sm' => 'py-1.5 px-3 text-sm',
        'md' => 'py-2 px-4 text-sm',
        'lg' => 'py-2.5 px-5 text-base',
        'xl' => 'py-3 px-6 text-base',
    ][$buttonSize] ?? 'py-1.5 px-3 text-sm';
    
    // Button variant classes
    $buttonVariantClasses = [
        'primary' => 'bg-green-600 hover:bg-green-700 text-white',
        'secondary' => 'bg-gray-500 hover:bg-gray-600 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white',
        'info' => 'bg-blue-500 hover:bg-blue-600 text-white',
        'light' => 'bg-gray-200 hover:bg-gray-300 text-gray-700',
        'dark' => 'bg-gray-800 hover:bg-gray-900 text-white',
        'link' => 'bg-transparent hover:bg-gray-50 text-green-600 hover:text-green-700 hover:underline',
    ][$variant] ?? 'bg-green-600 hover:bg-green-700 text-white';
@endphp

<div 
    x-data="tableFilter({
        filters: {{ json_encode($filters) }},
        operators: {{ json_encode($operators) }},
        conjunctions: {{ json_encode($conjunctions) }},
        initialConditions: []
    })"
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'w-full']) }}
>
    <!-- Filter builder UI -->
    <div class="{{ $showBorder ? 'border border-gray-200 dark:border-gray-700' : '' }} {{ $showShadow ? 'shadow-sm' : '' }} {{ $roundedClasses }} bg-white dark:bg-gray-800 overflow-hidden">
        <!-- Filter header -->
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">Filters</h3>
            
            <div class="flex space-x-2">
                <button 
                    type="button"
                    @click="resetFilters()"
                    class="inline-flex items-center border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $buttonSizeClasses }} font-medium rounded-md text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800"
                >
                    {{ $resetButtonText }}
                </button>
                
                <button 
                    type="button"
                    @click="applyFilters()"
                    class="inline-flex items-center border border-transparent {{ $buttonVariantClasses }} {{ $buttonSizeClasses }} font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800"
                >
                    {{ $applyButtonText }}
                </button>
            </div>
        </div>
        
        <!-- Filter conditions -->
        <div class="p-4 overflow-y-auto" style="max-height: {{ $maxHeight }}">
            <div class="space-y-4" x-show="conditions.length > 0">
                <template x-for="(condition, index) in conditions" :key="index">
                    <div class="flex flex-wrap items-start space-x-2 space-y-2 sm:space-y-0">
                        <!-- Conjunction (AND/OR) - Show only after first condition -->
                        <template x-if="index > 0">
                            <div class="min-w-[80px]">
                                <select 
                                    x-model="condition.conjunction"
                                    class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                                    <template x-for="(label, value) in conjunctions" :key="value">
                                        <option :value="value" x-text="label"></option>
                                    </template>
                                </select>
                            </div>
                        </template>
                        
                        <!-- Field -->
                        <div class="min-w-[180px]">
                            <select 
                                x-model="condition.field"
                                @change="updateOperators(index)"
                                class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                            >
                                <option value="" disabled>Select field</option>
                                <template x-for="filter in availableFilters" :key="filter.field">
                                    <option :value="filter.field" x-text="filter.label"></option>
                                </template>
                            </select>
                        </div>
                        
                        <!-- Operator -->
                        <div class="min-w-[150px]">
                            <select 
                                x-model="condition.operator"
                                @change="checkIfValueNeeded(index)"
                                class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                            >
                                <option value="" disabled>Select operator</option>
                                <template x-for="(label, value) in condition.availableOperators" :key="value">
                                    <option :value="value" x-text="label"></option>
                                </template>
                            </select>
                        </div>
                        
                        <!-- Value input - changes based on field type -->
                        <div class="min-w-[200px]" x-show="condition.requiresValue">
                            <!-- Text input -->
                            <div x-show="condition.type === 'text'">
                                <input 
                                    type="text" 
                                    x-model="condition.value"
                                    class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                    placeholder="Enter value"
                                >
                            </div>
                            
                            <!-- Number input -->
                            <div x-show="condition.type === 'number'">
                                <input 
                                    type="number" 
                                    x-model="condition.value"
                                    class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                    placeholder="Enter value"
                                >
                            </div>
                            
                            <!-- Date input -->
                            <div x-show="condition.type === 'date' && condition.operator !== 'between'">
                                <input 
                                    type="date" 
                                    x-model="condition.value"
                                    class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                            </div>
                            
                            <!-- Date range input -->
                            <div x-show="condition.type === 'date' && condition.operator === 'between'" class="flex space-x-2">
                                <input 
                                    type="date" 
                                    x-model="condition.value"
                                    class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                                <input 
                                    type="date" 
                                    x-model="condition.value2"
                                    class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                            </div>
                            
                            <!-- Boolean input -->
                            <div x-show="condition.type === 'boolean'">
                                <select 
                                    x-model="condition.value"
                                    class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            </div>
                            
                            <!-- Select input -->
                            <div x-show="condition.type === 'select'">
                                <select 
                                    x-model="condition.value"
                                    class="block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                                    <option value="" disabled>Select option</option>
                                    <template x-for="option in condition.options" :key="option.value">
                                        <option :value="option.value" x-text="option.label"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Remove button -->
                        <div>
                            <button 
                                type="button" 
                                @click="removeCondition(index)"
                                class="inline-flex items-center p-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Empty state -->
            <div x-show="conditions.length === 0" class="text-center py-4">
                <p class="text-gray-500 dark:text-gray-400 text-sm">No filters applied</p>
            </div>
            
            <!-- Add filter button -->
            <div class="mt-4">
                <button 
                    type="button"
                    @click="addCondition()"
                    class="inline-flex items-center border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $buttonSizeClasses }} font-medium rounded-md text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800"
                >
                    <svg class="mr-2 -ml-1 h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $addFilterText }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tableFilter', (config) => ({
            availableFilters: config.filters,
            operators: config.operators,
            conjunctions: config.conjunctions,
            conditions: [],
            
            init() {
                // Initialize with empty condition or from provided initialConditions
                if (config.initialConditions && config.initialConditions.length > 0) {
                    this.conditions = config.initialConditions;
                    
                    // Make sure each condition has the correct operators
                    this.conditions.forEach((condition, index) => {
                        this.updateOperators(index);
                        this.checkIfValueNeeded(index);
                    });
                } else if (this.availableFilters.length > 0) {
                    this.addCondition();
                }
            },
            
            addCondition() {
                const defaultFilter = this.availableFilters[0];
                const fieldType = defaultFilter.type || 'text';
                const fieldOperators = this.operators[fieldType] || {};
                const defaultOperator = Object.keys(fieldOperators)[0] || '';
                
                this.conditions.push({
                    conjunction: 'and',
                    field: defaultFilter.field,
                    operator: defaultOperator,
                    value: '',
                    value2: '', // For date range
                    type: fieldType,
                    availableOperators: fieldOperators,
                    requiresValue: !['is_empty', 'is_not_empty'].includes(defaultOperator),
                    options: defaultFilter.options || []
                });
            },
            
            removeCondition(index) {
                this.conditions.splice(index, 1);
            },
            
            updateOperators(index) {
                const condition = this.conditions[index];
                const field = condition.field;
                const filter = this.availableFilters.find(f => f.field === field);
                
                if (filter) {
                    const fieldType = filter.type || 'text';
                    condition.type = fieldType;
                    condition.availableOperators = this.operators[fieldType] || {};
                    condition.operator = Object.keys(condition.availableOperators)[0] || '';
                    condition.options = filter.options || [];
                    
                    this.checkIfValueNeeded(index);
                }
            },
            
            checkIfValueNeeded(index) {
                const condition = this.conditions[index];
                condition.requiresValue = !['is_empty', 'is_not_empty'].includes(condition.operator);
                
                // Reset value if not needed
                if (!condition.requiresValue) {
                    condition.value = '';
                    condition.value2 = '';
                }
            },
            
            resetFilters() {
                this.conditions = [];
                if (this.availableFilters.length > 0) {
                    this.addCondition();
                }
                
                // Dispatch event
                this.$dispatch('filters-reset');
            },
            
            applyFilters() {
                // Create a simplified version of the conditions for the consumer
                const appliedFilters = this.conditions.map(condition => {
                    const result = {
                        conjunction: condition.conjunction,
                        field: condition.field,
                        operator: condition.operator
                    };
                    
                    if (condition.requiresValue) {
                        result.value = condition.value;
                        
                        if (condition.operator === 'between' && condition.type === 'date') {
                            result.value2 = condition.value2;
                        }
                    }
                    
                    return result;
                });
                
                // Remove unnecessary conjunction from first item
                if (appliedFilters.length > 0) {
                    delete appliedFilters[0].conjunction;
                }
                
                // Dispatch event with the filters
                this.$dispatch('filters-applied', {
                    filters: appliedFilters
                });
            }
        }));
    });
</script> 