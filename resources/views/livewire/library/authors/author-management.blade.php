<div class="bg-gradient-to-r from-gray-100 to-gray-200 p-6 rounded-lg shadow-md">
    <!-- Header with title and description -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Author Management</h1>
                <p class="text-gray-600 text-sm">Manage authors who have contributed to books in the library</p>
            </div>
        </div>
        
        <!-- Action buttons -->
        <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
            @if($canCreate)
            <button 
                wire:click="createAuthor"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center space-x-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>New Author</span>
            </button>
            @endif
            
            @if($canExport)
            <button 
                wire:click="exportAuthors"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center space-x-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <span>Export</span>
            </button>
            @endif
            
            <button 
                wire:click="$dispatch('print-authors')"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded flex items-center space-x-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                </svg>
                <span>Print</span>
            </button>
        </div>
    </div>
    
    <!-- Flash messages -->
    @if(session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    @if(session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <!-- Filters include -->
    @include('partials.library.authors.filters')
    
    <!-- Authors table -->
    <div class="print-section">
        @include('partials.library.authors.table')
    </div>
    
    <!-- Pagination -->
    <div class="mt-4 no-print">
        @include('partials.library.authors.pagination', ['paginator' => $authors])
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Print functionality is handled in app.js
    });
</script>
@endpush 