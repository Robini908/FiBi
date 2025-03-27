<div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="roleFilter" class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Role</label>
            <select 
                wire:model.live="selectedRole" 
                id="roleFilter" 
                class="bg-white border border-gray-300 text-gray-700 text-sm rounded-md focus:ring-green-500 focus:border-green-500 block w-full py-2 px-3 transition-colors"
            >
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="statusFilter" class="block mb-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Status</label>
            <select 
                wire:model.live="selectedStatus" 
                id="statusFilter" 
                class="bg-white border border-gray-300 text-gray-700 text-sm rounded-md focus:ring-green-500 focus:border-green-500 block w-full py-2 px-3 transition-colors"
            >
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <div class="flex items-end">
            <button 
                wire:click="resetFilters" 
                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
                <svg class="h-4 w-4 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Reset Filters
            </button>
        </div>
    </div>
    
    <!-- Active Filters -->
    @if($selectedRole || $selectedStatus)
        <div class="mt-3 flex flex-wrap gap-2 items-center">
            <span class="text-xs text-gray-500">Active filters:</span>
            
            @if($selectedRole)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                    Role: {{ ucfirst($selectedRole) }}
                    <button wire:click="$set('selectedRole', '')" class="ml-1.5 text-green-600 hover:text-green-800 focus:outline-none">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </span>
            @endif
            
            @if($selectedStatus)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                    Status: {{ ucfirst($selectedStatus) }}
                    <button wire:click="$set('selectedStatus', null)" class="ml-1.5 text-blue-600 hover:text-blue-800 focus:outline-none">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </span>
            @endif
        </div>
    @endif
</div> 