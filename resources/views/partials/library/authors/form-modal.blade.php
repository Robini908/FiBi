<div>
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3 flex justify-between items-center text-white shadow">
                <h3 class="text-lg font-medium text-white drop-shadow" id="modal-headline">
                    {{ $authorId ? 'Edit Author' : 'Add New Author' }}
                </h3>
        <button type="button" class="text-white hover:text-gray-200 focus:outline-none" wire:click="closeModal">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Tabs Navigation -->
    <div class="bg-gray-100 px-4 py-3 border-b border-gray-200"
         x-data="{ activeTab: 'basic' }">
                <div class="flex space-x-4">
                    <button
                        type="button"
                        @click="activeTab = 'basic'"
                        :class="{'border-green-500 text-green-600': activeTab === 'basic', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'basic'}"
                        class="px-3 py-2 font-medium text-sm border-b-2 focus:outline-none flex items-center space-x-2"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Basic Information</span>
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'bio'"
                        :class="{'border-green-500 text-green-600': activeTab === 'bio', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'bio'}"
                        class="px-3 py-2 font-medium text-sm border-b-2 focus:outline-none flex items-center space-x-2"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Biography & Details</span>
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'image'"
                        :class="{'border-green-500 text-green-600': activeTab === 'image', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'image'}"
                        class="px-3 py-2 font-medium text-sm border-b-2 focus:outline-none flex items-center space-x-2"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Author Image</span>
                    </button>
                </div>
            </div>

    <form wire:submit="{{$authorId ? 'updateAuthor' : 'createAuthor'}}">
        <div class="bg-white px-4 py-5 sm:p-6" x-data="{ activeTab: 'basic' }">
                    <div x-show="activeTab === 'basic'" class="space-y-6">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Please provide the basic information about the author. Fields marked with <span class="text-red-500">*</span> are required.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Author Name <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input
                                        type="text"
                                        id="name"
                                wire:model="author.name"
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Full name of the author"
                                    >
                                </div>
                                @error('author.name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="nationality" class="block text-sm font-medium text-gray-700">
                                    Nationality
                                </label>
                                <div class="mt-1">
                                    <input
                                        type="text"
                                        id="nationality"
                                wire:model="author.nationality"
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Author's nationality"
                                    >
                                </div>
                                @error('author.nationality')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email Address
                                </label>
                                <div class="mt-1">
                                    <input
                                        type="email"
                                        id="email"
                                wire:model="author.email"
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="author@example.com"
                                    >
                                </div>
                                @error('author.email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="birth_date" class="block text-sm font-medium text-gray-700">
                                    Birth Date
                                </label>
                                <div class="mt-1">
                                    <input
                                        type="date"
                                        id="birth_date"
                                wire:model="author.birth_date"
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    >
                                </div>
                                @error('author.birth_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="death_date" class="block text-sm font-medium text-gray-700">
                                    Death Date (if applicable)
                                </label>
                                <div class="mt-1">
                                    <input
                                        type="date"
                                        id="death_date"
                                wire:model="author.death_date"
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    >
                                </div>
                                @error('author.death_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'bio'" class="space-y-6">
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Add more details about the author including their biography and online presence.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="biography" class="block text-sm font-medium text-gray-700">
                                    Biography
                                </label>
                                <div class="mt-1">
                                    <textarea
                                        id="biography"
                                wire:model="author.biography"
                                        rows="4"
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Detailed biography of the author"
                                    ></textarea>
                                </div>
                                @error('author.biography')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-6">
                                <label for="website" class="block text-sm font-medium text-gray-700">
                                    Website URL
                                </label>
                                <div class="mt-1">
                                    <input
                                        type="url"
                                        id="website"
                                wire:model="author.website"
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="https://author-website.com"
                                    >
                                </div>
                                @error('author.website')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="is_active"
                                            type="checkbox"
                                    wire:model="author.is_active"
                                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded"
                                        >
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_active" class="font-medium text-gray-700">Active</label>
                                        <p class="text-gray-500">Show this author in public listings</p>
                                    </div>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="is_featured"
                                            type="checkbox"
                                    wire:model="author.is_featured"
                                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded"
                                        >
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_featured" class="font-medium text-gray-700">Featured Author</label>
                                        <p class="text-gray-500">Highlight this author on the homepage</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'image'" class="space-y-6">
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        Upload a photo of the author. For best results, use a square image of at least 300 x 300 pixels.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-center justify-center">
                            @if($authorId && $imagePreview)
                                <div class="mb-4">
                                    <img src="{{ $imagePreview }}" alt="Author image preview" class="h-48 w-48 object-cover rounded-lg shadow-md">
                                </div>
                            @elseif($uploadedImage)
                                <div class="mb-4">
                                    <img src="{{ $uploadedImage->temporaryUrl() }}" alt="Author image preview" class="h-48 w-48 object-cover rounded-lg shadow-md">
                                </div>
                            @else
                                <div class="mb-4 bg-gray-100 rounded-lg h-48 w-48 flex items-center justify-center shadow-sm">
                                    <svg class="h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif

                            <div class="mt-4">
                                <label for="image" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 cursor-pointer">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    {{ $authorId && $imagePreview ? 'Change Image' : 'Upload Author Image' }}
                                    <input type="file" id="image" wire:model="uploadedImage" class="sr-only" accept="image/*">
                                </label>
                            </div>

                            @error('uploadedImage')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if($authorId && $imagePreview)
                                <div class="mt-4">
                                    <button 
                                        type="button" 
                                        wire:click="removeImage" 
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    >
                                        <svg class="-ml-0.5 mr-2 h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Remove Image
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse" x-data="{ activeTab: 'basic' }">
                    <button
                        type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150 ease-in-out"
                    >
                        {{ $authorId ? 'Update Author' : 'Create Author' }}
                    </button>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150 ease-in-out"
                    >
                        Cancel
                    </button>
                </div>
            </form>
</div> 