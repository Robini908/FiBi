<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
    <!-- Title -->
    <div class="sm:col-span-6">
        <label for="title" class="block text-sm font-medium text-gray-700">Book Title <span class="text-red-500">*</span></label>
        <div class="mt-1">
            <input 
                type="text" 
                wire:model="title" 
                id="title" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Enter book title">
        </div>
        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Subtitle -->
    <div class="sm:col-span-6">
        <label for="subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
        <div class="mt-1">
            <input 
                type="text" 
                wire:model="subtitle" 
                id="subtitle" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Enter subtitle (if any)">
        </div>
        @error('subtitle') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- ISBN and ISBN-13 -->
    <div class="sm:col-span-3">
        <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
        <div class="mt-1">
            <input 
                type="text" 
                wire:model="isbn" 
                id="isbn" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Enter ISBN">
        </div>
        @error('isbn') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-3">
        <label for="isbn13" class="block text-sm font-medium text-gray-700">ISBN-13</label>
        <div class="mt-1">
            <input 
                type="text" 
                wire:model="isbn13" 
                id="isbn13" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Enter ISBN-13">
        </div>
        @error('isbn13') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Category -->
    <div class="sm:col-span-3">
        <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
        <div class="mt-1">
            <select 
                wire:model="category_id" 
                id="category_id" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                <option value="">Select a Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Language -->
    <div class="sm:col-span-3">
        <label for="language" class="block text-sm font-medium text-gray-700">Language <span class="text-red-500">*</span></label>
        <div class="mt-1">
            <input 
                type="text" 
                wire:model="language" 
                id="language" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Enter language">
        </div>
        @error('language') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Cover Image -->
    <div class="sm:col-span-6">
        <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image</label>
        <div class="mt-1 flex items-center space-x-4">
            @if ($existing_cover_image)
                <div class="flex-shrink-0 h-32 w-24 bg-gray-100 rounded-md overflow-hidden">
                    <img src="{{ Storage::url($existing_cover_image) }}" alt="Cover" class="h-full w-full object-cover">
                </div>
            @elseif ($newCoverImage)
                <div class="flex-shrink-0 h-32 w-24 bg-gray-100 rounded-md overflow-hidden">
                    <img src="{{ $newCoverImage->temporaryUrl() }}" alt="Temp Cover" class="h-full w-full object-cover">
                </div>
            @else
                <div class="flex-shrink-0 h-32 w-24 bg-gray-100 rounded-md flex items-center justify-center">
                    <svg class="h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif

            <div>
                <label for="cover_image_input" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 cursor-pointer">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    {{ $existing_cover_image || $newCoverImage ? 'Change Cover' : 'Upload Cover' }}
                </label>
                <input id="cover_image_input" wire:model="newCoverImage" type="file" class="sr-only" accept="image/*">
                
                @if ($existing_cover_image || $newCoverImage)
                    <button 
                        type="button"
                        wire:click="removeCoverImage"
                        class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Remove
                    </button>
                @endif
            </div>
        </div>
        @error('newCoverImage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Description -->
    <div class="sm:col-span-6">
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <div class="mt-1">
            <textarea 
                id="description" 
                wire:model="description" 
                rows="4" 
                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Enter book description"></textarea>
        </div>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div> 