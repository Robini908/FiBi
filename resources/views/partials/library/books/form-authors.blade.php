<div class="space-y-6">
    <!-- Author Search and Selection -->
    <div>
        <label for="authorSearch" class="block text-sm font-medium text-gray-700">Search Authors</label>
        <div class="relative mt-1">
            <input 
                type="text" 
                wire:model.debounce.300ms="authorSearch" 
                id="authorSearch" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Search for authors by name">
                
            @if(count($availableAuthors) > 0)
                <div class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md max-h-60 overflow-auto">
                    <ul class="divide-y divide-gray-200">
                        @foreach($availableAuthors as $author)
                            <li class="p-2 hover:bg-gray-50 cursor-pointer" wire:click="addAuthor({{ $author['id'] }}, '{{ $author['name'] }}')">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $author['name'] }}</div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <p class="mt-1 text-xs text-gray-500">Type at least 2 characters to search for authors</p>
    </div>

    <!-- Selected Authors -->
    <div>
        <h4 class="text-sm font-medium text-gray-700">Selected Authors</h4>
        
        @if(count($selectedAuthors) === 0)
            <p class="mt-3 text-sm text-gray-500">No authors selected yet. Search for authors to add.</p>
        @else
            <div class="mt-3 overflow-hidden bg-white shadow sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($selectedAuthors as $index => $author)
                        <li class="px-4 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input 
                                        type="radio" 
                                        name="primaryAuthor" 
                                        id="primaryAuthor_{{ $author['id'] }}" 
                                        wire:click="setPrimaryAuthor({{ $author['id'] }})" 
                                        {{ $primaryAuthorId == $author['id'] ? 'checked' : '' }}
                                        class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                    
                                    <label for="primaryAuthor_{{ $author['id'] }}" class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $author['name'] }}</div>
                                        <div class="text-xs text-gray-500">
                                            @if($primaryAuthorId == $author['id'])
                                                <span class="text-green-600 font-medium">Primary Author</span>
                                            @else
                                                <span>Set as primary author</span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                                
                                <button 
                                    type="button" 
                                    wire:click="removeAuthor({{ $index }})" 
                                    class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            @error('selectedAuthors') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
            @enderror
            
            @error('primaryAuthorId') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
            @enderror
        @endif
    </div>
    
    <!-- Create Author Link -->
    <div class="pt-3">
        <div class="rounded-md bg-gray-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700">
                        Can't find the author you're looking for? 
                        <a href="{{ route('library.authors') }}" target="_blank" class="font-medium text-green-600 hover:text-green-500">Create a new author</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> 