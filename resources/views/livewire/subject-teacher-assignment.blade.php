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
    
    <!-- Statistics Cards -->
    <div class="px-4 py-3 bg-white border-b border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Assignments -->
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Assignments</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">{{ $assignments->total() }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Active Assignments -->
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">Active</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">{{ $activeCount ?? '—' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Primary Assignments -->
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">Primary</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">{{ $primaryCount ?? '—' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Teachers with Assignments -->
            <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">Active Teachers</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900">{{ $teacherCount ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Table Section -->
    <div class="px-4 py-3">
        @if ($assignments->count() > 0)
            @include('livewire.partials.subject-teacher-assignment.table')
        @else
            @include('livewire.partials.subject-teacher-assignment.empty-state')
        @endif
        
        <!-- Pagination -->
        @if ($assignments->hasPages())
            <div class="mt-4">
                {{ $assignments->links() }}
            </div>
        @endif
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
