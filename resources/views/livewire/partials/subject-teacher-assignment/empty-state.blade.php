<div class="py-12 flex flex-col items-center justify-center text-center">
    <div class="bg-white rounded-full p-4 mb-6 shadow-sm">
        <svg class="w-16 h-16 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
    </div>
    
    <h3 class="text-xl font-medium text-gray-800 mb-2">No Assignments Found</h3>
    
    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-50 text-blue-700 mb-3">
        <svg class="mr-2 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        </svg>
        Viewing: {{ $academicYear }} - {{ $academicTerm }}
    </div>
    
    <p class="text-base text-gray-500 mb-6 max-w-lg">
        @if(!empty($search) || !empty($filterTeacher) || !empty($filterSubject) || !empty($filterClass))
            No assignments match your current filter settings. Try adjusting your filters or create a new assignment.
        @else
            No teacher-subject assignments have been created for {{ $academicYear }} - {{ $academicTerm }}. Get started by adding your first assignment.
        @endif
    </p>
    
    <div class="flex flex-col sm:flex-row gap-3">
        @if(!empty($search) || !empty($filterTeacher) || !empty($filterSubject) || !empty($filterClass) || !empty($filterStatus) || !empty($filterPrimary))
            <button 
                wire:click="clearFilters"
                type="button" 
                class="inline-flex items-center justify-center px-5 py-2 rounded-full shadow-sm text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                <svg class="mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear Filters
            </button>
        @endif
        
        <button 
            wire:click="openModal"
            type="button" 
            class="inline-flex items-center justify-center px-5 py-2 rounded-full shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
            <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Assignment
        </button>
    </div>
</div> 