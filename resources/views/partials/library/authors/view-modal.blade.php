<div>
    <!-- Modal Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 px-4 py-3 flex justify-between items-center text-white shadow">
        <h3 class="text-lg font-medium text-white drop-shadow" id="modal-headline">
            Author Profile
        </h3>
        <button type="button" class="text-white hover:text-gray-200 focus:outline-none" wire:click="$dispatch('closeModal')">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Author Information -->
    @if(isset($selectedAuthor) && $selectedAuthor)
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Author Image & Quick Info -->
                <div class="flex flex-col items-center md:items-start md:w-1/3">
                    <div class="bg-gray-100 rounded-lg h-48 w-48 flex items-center justify-center shadow-md overflow-hidden">
                        @if($selectedAuthor->image_path)
                            <img src="{{ Storage::url($selectedAuthor->image_path) }}" alt="{{ $selectedAuthor->name }}" class="h-48 w-48 object-cover">
                        @else
                            <svg class="h-24 w-24 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                    </div>

                    <div class="mt-4 space-y-2 w-full">
                        <h4 class="text-lg font-medium text-gray-900 text-center md:text-left">Quick Info</h4>
                        
                        <div class="bg-gray-50 rounded-md p-3 space-y-3">
                            @if($selectedAuthor->is_featured)
                                <div class="flex items-center text-purple-700">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-sm">Featured Author</span>
                                </div>
                            @endif

                            <div class="flex items-center text-gray-700">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm">{{ $selectedAuthor->nationality ?? 'Nationality not specified' }}</span>
                            </div>

                            <div class="flex items-center text-gray-700">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm">
                                    @if($selectedAuthor->birth_date)
                                        {{ $selectedAuthor->birth_date->format('Y') }}
                                        @if($selectedAuthor->death_date)
                                            - {{ $selectedAuthor->death_date->format('Y') }}
                                        @endif
                                    @else
                                        Birth date not specified
                                    @endif
                                </span>
                            </div>

                            <div class="flex items-center text-gray-700">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                </svg>
                                <span class="text-sm">{{ $selectedAuthor->books_count ?? 0 }} {{ Str::plural('book', $selectedAuthor->books_count ?? 0) }}</span>
                            </div>

                            @if($selectedAuthor->is_active)
                                <div class="flex items-center text-green-700">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">Active</span>
                                </div>
                            @else
                                <div class="flex items-center text-red-700">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">Inactive</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(auth()->user()->hasAnyRole(['librarian', 'admin', 'superadmin']))
                        <div class="mt-4 flex space-x-2 w-full justify-center md:justify-start">
                            <button 
                                wire:click="openEditModal({{ $selectedAuthor->id }})"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit
                            </button>
                            <button 
                                wire:click="generateAuthorProfile({{ $selectedAuthor->id }})"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd" />
                                </svg>
                                PDF
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Author Details -->
                <div class="md:w-2/3 space-y-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            {{ $selectedAuthor->name }}
                        </h2>
                        
                        @if($selectedAuthor->email || $selectedAuthor->website)
                            <div class="mt-2 space-y-1">
                                @if($selectedAuthor->email)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                        <a href="mailto:{{ $selectedAuthor->email }}" class="text-indigo-600 hover:text-indigo-900">{{ $selectedAuthor->email }}</a>
                                    </div>
                                @endif
                                
                                @if($selectedAuthor->website)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                        </svg>
                                        <a href="{{ $selectedAuthor->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $selectedAuthor->website }}</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Biography -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900">Biography</h4>
                        <div class="mt-2 text-gray-600 prose max-w-none">
                            {{ $selectedAuthor->biography ?? 'No biography available.' }}
                        </div>
                    </div>

                    <!-- Books -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900">Books by this Author</h4>
                        @if(isset($selectedAuthor->books) && $selectedAuthor->books->count() > 0)
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($selectedAuthor->books as $book)
                                    <div class="flex items-start border border-gray-200 rounded-md p-3 bg-white hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex-shrink-0 h-12 w-10 bg-gray-200 overflow-hidden rounded shadow-sm">
                                            @if($book->cover_path)
                                                <img src="{{ Storage::url($book->cover_path) }}" alt="{{ $book->title }}" class="h-12 w-10 object-cover">
                                            @else
                                                <div class="h-12 w-10 flex items-center justify-center bg-gray-100">
                                                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3 overflow-hidden">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $book->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $book->publication_year }}</p>
                                            <p class="text-xs text-gray-500">{{ Str::limit($book->category->name, 25) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($selectedAuthor->books_count > 5)
                                <div class="mt-3 text-center">
                                    <span class="text-sm text-gray-500">Showing 5 of {{ $selectedAuthor->books_count }} books</span>
                                    <button 
                                        wire:click="generateAuthorProfile({{ $selectedAuthor->id }})" 
                                        class="ml-2 text-sm text-indigo-600 hover:text-indigo-900 font-medium"
                                    >
                                        See All in PDF
                                    </button>
                                </div>
                            @endif
                        @else
                            <div class="mt-2 text-gray-500 italic">
                                No books by this author in the library.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white px-4 py-5 sm:p-6">
            <div class="text-center text-gray-500">
                <p>Author information not available.</p>
            </div>
        </div>
    @endif

    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
        <button 
            type="button" 
            wire:click="$dispatch('closeModal')" 
            class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm transition-colors duration-150 ease-in-out"
        >
            Close
        </button>
    </div>
</div> 