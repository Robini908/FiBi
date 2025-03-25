@props([
    'columns' => [],
    'rows' => [],
    'pagination' => null,
    'searchable' => true,
    'sortable' => true,
    'selectable' => false,
    'actions' => true,
    'striped' => true,
    'hoverable' => true,
    'compact' => false,
    'bordered' => false,
    'rounded' => true,
    'emptyState' => 'No data available'
])

<div
    x-data="{
        search: '',
        sortColumn: null,
        sortDirection: 'asc',
        selectedRows: [],
        selectAll: false,
        open: {},
        
        toggleSort(column) {
            if (!column.sortable) return;
            
            if (this.sortColumn === column.key) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column.key;
                this.sortDirection = 'asc';
            }
            
            this.rows = this.sortRows(this.filteredRows);
        },
        
        sortRows(rows) {
            if (!this.sortColumn) return rows;
            
            return [...rows].sort((a, b) => {
                const aValue = a[this.sortColumn];
                const bValue = b[this.sortColumn];
                
                if (aValue < bValue) return this.sortDirection === 'asc' ? -1 : 1;
                if (aValue > bValue) return this.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
        },
        
        filterRows(rows) {
            if (!this.search) return rows;
            
            const searchLower = this.search.toLowerCase();
            return rows.filter(row => 
                Object.values(row).some(value => 
                    value && value.toString().toLowerCase().includes(searchLower)
                )
            );
        },
        
        get filteredRows() {
            return this.filterRows(this.rows);
        },
        
        get displayedRows() {
            return this.sortRows(this.filteredRows);
        },
        
        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            
            if (this.selectAll) {
                this.selectedRows = this.filteredRows.map(row => row.id);
            } else {
                this.selectedRows = [];
            }
        },
        
        toggleRowSelection(rowId) {
            const index = this.selectedRows.indexOf(rowId);
            
            if (index === -1) {
                this.selectedRows.push(rowId);
            } else {
                this.selectedRows.splice(index, 1);
            }
            
            this.selectAll = this.filteredRows.length > 0 && 
                this.selectedRows.length === this.filteredRows.length;
        },
        
        isRowSelected(rowId) {
            return this.selectedRows.includes(rowId);
        },
        
        toggleRow(rowId) {
            this.open[rowId] = !this.open[rowId];
        }
    }"
    x-init="
        rows = {{ json_encode($rows) }};
    "
    class="w-full overflow-hidden"
>
    <!-- Search and Bulk Actions -->
    <div class="mb-4 flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
        @if($searchable)
            <div class="w-full md:w-1/3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        x-model="search"
                        placeholder="Search..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    />
                </div>
            </div>
        @endif
        
        @if($selectable)
            <div x-show="selectedRows.length > 0" class="flex space-x-2">
                {{ $bulkActions ?? '' }}
            </div>
        @endif
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto relative">
        <div class="@if($rounded) rounded-lg @endif @if($bordered) border border-gray-200 @endif shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @if($selectable)
                            <th scope="col" class="px-3 py-3 text-left">
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        x-model="selectAll" 
                                        @click="toggleSelectAll"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                    />
                                </div>
                            </th>
                        @endif
                        
                        @foreach($columns as $column)
                            <th 
                                scope="col" 
                                @if($sortable && ($column['sortable'] ?? true)) 
                                    @click="toggleSort({{ json_encode($column) }})"
                                    class="group cursor-pointer" 
                                @endif
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $compact ? 'py-2' : 'py-3' }}"
                            >
                                <div class="flex items-center space-x-1">
                                    <span>{{ $column['label'] }}</span>
                                    
                                    @if($sortable && ($column['sortable'] ?? true))
                                        <span class="ml-2 flex-none text-gray-400 group-hover:text-gray-500">
                                            <svg 
                                                x-show="sortColumn !== '{{ $column['key'] }}'"
                                                class="h-5 w-5" 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                viewBox="0 0 20 20" 
                                                fill="currentColor"
                                            >
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg 
                                                x-show="sortColumn === '{{ $column['key'] }}' && sortDirection === 'asc'"
                                                class="h-5 w-5" 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                viewBox="0 0 20 20" 
                                                fill="currentColor"
                                            >
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                            </svg>
                                            <svg 
                                                x-show="sortColumn === '{{ $column['key'] }}' && sortDirection === 'desc'"
                                                class="h-5 w-5" 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                viewBox="0 0 20 20" 
                                                fill="currentColor"
                                            >
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                        
                        @if($actions)
                            <th scope="col" class="relative px-6 {{ $compact ? 'py-2' : 'py-3' }}">
                                <span class="sr-only">Actions</span>
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="(row, index) in displayedRows" :key="row.id">
                        <tr :class="{ 
                            'bg-gray-50': striped && index % 2, 
                            'hover:bg-green-50': hoverable,
                            'bg-green-50': isRowSelected(row.id)
                        }">
                            @if($selectable)
                                <td class="px-3 {{ $compact ? 'py-2' : 'py-4' }} whitespace-nowrap">
                                    <input 
                                        type="checkbox" 
                                        :checked="isRowSelected(row.id)" 
                                        @click="toggleRowSelection(row.id)"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                    />
                                </td>
                            @endif
                            
                            @foreach($columns as $column)
                                <td class="px-6 {{ $compact ? 'py-2' : 'py-4' }} whitespace-nowrap">
                                    <template x-if="'{{ $column['format'] ?? '' }}' === 'image'">
                                        <img :src="row['{{ $column['key'] }}']" :alt="row['{{ $column['key'] }}']" class="h-10 w-10 rounded-full" />
                                    </template>
                                    <template x-if="'{{ $column['format'] ?? '' }}' === 'boolean'">
                                        <span 
                                            :class="row['{{ $column['key'] }}'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        >
                                            <span x-text="row['{{ $column['key'] }}'] ? 'Yes' : 'No'"></span>
                                        </span>
                                    </template>
                                    <template x-if="'{{ $column['format'] ?? '' }}' === 'badge'">
                                        <span 
                                            :class="{
                                                'bg-green-100 text-green-800': row['{{ $column['key'] }}'] === 'active' || row['{{ $column['key'] }}'] === 'completed',
                                                'bg-yellow-100 text-yellow-800': row['{{ $column['key'] }}'] === 'pending' || row['{{ $column['key'] }}'] === 'processing',
                                                'bg-red-100 text-red-800': row['{{ $column['key'] }}'] === 'inactive' || row['{{ $column['key'] }}'] === 'cancelled',
                                                'bg-blue-100 text-blue-800': row['{{ $column['key'] }}'] === 'new' || row['{{ $column['key'] }}'] === 'in_progress',
                                                'bg-gray-100 text-gray-800': row['{{ $column['key'] }}'] === 'draft' || row['{{ $column['key'] }}'] === 'archived'
                                            }"
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        >
                                            <span x-text="row['{{ $column['key'] }}']"></span>
                                        </span>
                                    </template>
                                    <template x-if="!['image', 'boolean', 'badge'].includes('{{ $column['format'] ?? '' }}')">
                                        <span x-text="row['{{ $column['key'] }}']"></span>
                                    </template>
                                </td>
                            @endforeach
                            
                            @if($actions)
                                <td class="px-6 {{ $compact ? 'py-2' : 'py-4' }} whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        {{ $rowActions ?? '' }}
                                    </div>
                                </td>
                            @endif
                        </tr>
                    </template>
                    
                    <tr x-show="displayedRows.length === 0">
                        <td colspan="{{ $selectable ? count($columns) + 2 : count($columns) + 1 }}" class="px-6 py-4 text-center text-gray-500">
                            {{ $emptyState }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($pagination)
        <div class="py-3 flex items-center justify-between">
            {{ $pagination }}
        </div>
    @endif
</div> 