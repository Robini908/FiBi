<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- Header section with title and controls -->
    <div class="px-5 py-4 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
        <div class="flex items-center">
            <div class="bg-green-50 p-2 rounded-full mr-3">
                <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-medium text-gray-800">Teacher-Subject Assignments</h2>
                <p class="text-sm text-gray-500">Manage teacher assignments for subjects and classes</p>
            </div>
        </div>
        
        <div>
            <button wire:click="openModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Assignment
            </button>
        </div>
    </div>
    
    <!-- Filter Controls Section -->
    @include('livewire.partials.subject-teacher-assignment.filters')
    
    <!-- Data Table Section -->
    <div class="px-4 py-3">
        @if ($assignments->count() > 0)
            @include('livewire.partials.subject-teacher-assignment.table')
        @else
            @include('livewire.partials.subject-teacher-assignment.empty-state')
        @endif
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $assignments->links() }}
        </div>
    </div>
    
    <!-- Form Modal -->
    @if ($isModalOpen)
        @include('livewire.partials.subject-teacher-assignment.form-modal')
    @endif
    
    <!-- Alpine JS Handlers -->
    <script>
        document.addEventListener('livewire:initialized', function () {
            @this.on('toast', event => {
                Toast[event.type](event.message);
            });
        });
    </script>
</div>
