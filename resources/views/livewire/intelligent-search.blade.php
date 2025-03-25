<div x-data="{ focused: false, hasResults: false }" class="relative w-full">
    <!-- Search Input -->
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
        <input 
            type="text" 
            wire:model.live.debounce.100ms="query" 
            placeholder="Search for a student..." 
            @focus="focused = true"
            @blur="setTimeout(() => { focused = false }, 200)"
            class="pl-10 pr-4 py-2 w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg text-sm text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:bg-white dark:focus:bg-gray-800 transition duration-200"
            x-on:input="hasResults = $event.target.value.length > 0"
        />
        <button 
            x-show="query" 
            @click="$wire.set('query', ''); hasResults = false" 
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" 
            x-cloak
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="absolute inset-y-0 right-0 flex items-center pr-3">
        <svg class="animate-spin h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <!-- Results Dropdown -->
    <div 
        x-show="focused && hasResults && $wire.results.length > 0" 
        class="absolute z-50 mt-2 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 max-h-96 overflow-y-auto"
        x-cloak
    >
        <ul class="py-1 divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($results as $student)
                <li>
                    <a 
                        href="{{ route('students.show', $student->id) }}" 
                        class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                    >
                        <!-- Student Photo -->
                        <div class="flex-shrink-0">
                            <img 
                                src="{{ $student->photo ?? asset('global_assets/images/user.png') }}" 
                                alt="{{ $student->first_name }} {{ $student->last_name }}"
                                class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-600"
                                onerror="this.src='{{ asset('global_assets/images/user.png') }}'"
                            />
                        </div>

                        <!-- Student Info -->
                        <div class="ml-4 flex-1">
                            <div class="font-medium text-sm text-gray-900 dark:text-white">
                                {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100">
                                    {{ $student->adm_no }}
                                </span>
                                @if($student->town)
                                    <span class="ml-2">{{ $student->town }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Arrow icon -->
                        <div class="ml-2 flex-shrink-0 text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- No Results Message -->
    <div 
        x-show="focused && hasResults && query.length > 0 && $wire.results.length === 0" 
        class="absolute z-50 mt-2 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 p-4 text-center"
        x-cloak
    >
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No students found matching your search.</p>
    </div>
</div>
