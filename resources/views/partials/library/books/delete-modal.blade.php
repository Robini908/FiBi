<!-- Delete confirmation modal -->
<x-modal id="delete-modal" :show="$deleteModalOpen" maxWidth="lg" wire-close="deleteModalOpen">
    <div class="bg-white">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-white drop-shadow-sm">
                    Confirm Book Deletion
                </h3>
            </div>
        </div>
        
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <!-- Warning icon -->
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Delete Book
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete this book? This action cannot be undone and will permanently remove the book from the library system. All associated data including copies, loans, and reservations will also be deleted.
                        </p>
                    </div>
                </div>
            </div>
            
            @if($book && $book->title)
            <div class="mt-5 bg-red-50 p-4 rounded-md border border-red-100">
                <h4 class="text-md font-medium text-red-800 mb-2">Book Details:</h4>
                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-700">{{ $book->title }}</p>
                    @if($book->subtitle)
                        <p class="text-sm text-gray-600">{{ $book->subtitle }}</p>
                    @endif
                    
                    @if(count($book->authors) > 0)
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">By:</span> 
                        @foreach($book->authors as $index => $author)
                            {{ $author->name }}@if($index < count($book->authors) - 1), @endif
                        @endforeach
                    </p>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-2">
                        <p class="text-sm text-gray-600"><span class="font-medium">ISBN:</span> {{ $book->isbn ?? 'N/A' }}</p>
                        
                        @if($book->category)
                        <p class="text-sm text-gray-600"><span class="font-medium">Category:</span> {{ $book->category->name }}</p>
                        @endif
                        
                        @if($book->total_copies > 0)
                        <p class="text-sm text-gray-600"><span class="font-medium">Total Copies:</span> {{ $book->total_copies }}</p>
                        @endif
                        
                        @if(isset($book->copies_available))
                        <p class="text-sm text-gray-600"><span class="font-medium">Available Copies:</span> {{ $book->copies_available }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
            <button wire:click="delete" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Book
            </button>
            <button type="button" wire:click="closeModals" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </button>
        </div>
    </div>
</x-modal> 