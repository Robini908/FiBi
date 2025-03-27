<div class="py-12 flex flex-col items-center justify-center text-center">
    <div class="rounded-full bg-gray-50 p-3 mb-4">
        <svg class="w-8 h-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
    </div>
    
    <h3 class="text-lg font-medium text-gray-800 mb-1">No assignments found</h3>
    
    <p class="text-sm text-gray-500 mb-4 max-w-md">
        @if(!empty($search) || !empty($filterTeacher) || !empty($filterSubject) || !empty($filterClass))
            No assignments match your current filter settings. Try adjusting your filters or create a new assignment.
        @else
            No teacher-subject assignments have been created yet. Get started by adding your first assignment.
        @endif
    </p>
    
    <div class="flex space-x-3">
        @if(!empty($search) || !empty($filterTeacher) || !empty($filterSubject) || !empty($filterClass) || !empty($filterStatus) || !empty($filterPrimary))
            <button 
                wire:click="clearFilters"
                type="button" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
                <svg class="-ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear Filters
            </button>
        @endif
        
        <button 
            wire:click="openModal"
            type="button" 
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        >
            <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Assignment
        </button>
    </div>
</div> 