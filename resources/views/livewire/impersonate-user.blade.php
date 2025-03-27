<!-- Impersonate User Component -->
<div>
    <!-- Impersonation Banner (Positioned below the header with proper spacing) -->
    @if (session('impersonated_by'))
        <div class="fixed top-16 inset-x-0 z-30 bg-gradient-to-r from-green-50 to-green-100 border-b border-green-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                <div class="flex items-center justify-between flex-wrap sm:flex-nowrap">
                    <div class="flex items-center flex-1 min-w-0">
                        <span class="flex p-1 bg-green-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <p class="ml-2 text-sm font-medium text-green-800 truncate">
                            <span class="md:hidden">Impersonating user account</span>
                            <span class="hidden md:inline">You are currently viewing the application as another user</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <button 
                            wire:click="stopImpersonating"
                            wire:loading.attr="disabled"
                            class="flex items-center justify-center px-3 py-1 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                        >
                            <span wire:loading.remove wire:target="stopImpersonating" class="flex items-center">
                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Return to Account
                            </span>
                            <span wire:loading wire:target="stopImpersonating" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Returning...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add spacing to prevent content from being hidden behind the banner -->
        <div class="h-10"></div>
    @endif

    <!-- Impersonation Button - Only visible in the header -->
    @if (Auth::check() && Qs::isAdministrator())
        <div x-data="{ isOpen: false, currentTab: 'search' }" class="relative">
            <!-- Impersonation Button -->
            <button 
                @click="isOpen = !isOpen"
                class="relative p-2 text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
                title="Impersonate User"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                
                <!-- Active Impersonation Indicator Badge -->
                @if (session('impersonated_by'))
                    <span class="absolute top-0 right-0 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                @endif
            </button>

            <!-- Dropdown Panel -->
            <div 
                x-show="isOpen" 
                @click.away="isOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-80 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 overflow-hidden"
                x-cloak
            >
                <!-- Panel Header -->
                <div class="px-4 py-3 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-900">User Impersonation</h3>
                        
                        <!-- Close Button -->
                        <button @click="isOpen = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    @if (session('impersonated_by'))
                        <p class="mt-1 text-xs text-green-600 font-medium">
                            Currently impersonating another user
                        </p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">
                            Temporarily access another user's account
                        </p>
                    @endif
                </div>
                
                <!-- Tab Navigation -->
                <div class="border-b border-gray-100">
                    <nav class="flex">
                        <button 
                            @click="currentTab = 'search'" 
                            :class="{'text-green-600 border-b-2 border-green-500': currentTab === 'search', 'text-gray-500 hover:text-gray-700': currentTab !== 'search'}"
                            class="flex-1 px-4 py-2 text-sm font-medium text-center focus:outline-none transition-colors duration-200"
                        >
                            Search Users
                        </button>
                        <button 
                            @click="currentTab = 'recent'; $wire.loadRecentUsers()" 
                            :class="{'text-green-600 border-b-2 border-green-500': currentTab === 'recent', 'text-gray-500 hover:text-gray-700': currentTab !== 'recent'}"
                            class="flex-1 px-4 py-2 text-sm font-medium text-center focus:outline-none transition-colors duration-200"
                        >
                            Recent Users
                        </button>
                    </nav>
                </div>

                <!-- Search Tab Content -->
                <div x-show="currentTab === 'search'">
                    <!-- Search Input -->
                    <div class="px-4 py-3 border-b border-gray-100">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                wire:model.live.debounce.300ms="search" 
                                type="text" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                placeholder="Search by name, email, or username" 
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- User List -->
                    <div class="max-h-60 overflow-y-auto" id="search-results">
                        @if ($search && $users->count())
                            <ul class="py-1">
                                @foreach ($users as $user)
                                    <li>
                                        <button 
                                            wire:click="impersonate({{ $user->id }})" 
                                            wire:loading.attr="disabled"
                                            class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors duration-150 flex items-center space-x-3"
                                        >
                                            <!-- User Avatar -->
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 overflow-hidden">
                                                    @if ($user->photo)
                                                        <img src="{{ asset($user->photo) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                                    @else
                                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- User Info -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                            </div>
                                            
                                            <!-- User Role Badge -->
                                            <div class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-800">
                                                {{ $user->roles->first()->name ?? 'User' }}
                                            </div>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @elseif ($search)
                            <div class="py-8 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No users found</p>
                                <p class="mt-1 text-xs text-gray-400">Try a different search term</p>
                            </div>
                        @else
                            <div class="py-8 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Enter a name, email, or username</p>
                                <p class="mt-1 text-xs text-gray-400">to search for a user to impersonate</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Users Tab Content -->
                <div x-show="currentTab === 'recent'" class="max-h-60 overflow-y-auto">
                    @if (count($recentlyImpersonated) > 0)
                        <ul class="py-1">
                            @foreach ($recentlyImpersonated as $recentUser)
                                <li>
                                    <button 
                                        wire:click="impersonate({{ $recentUser['id'] }})" 
                                        wire:loading.attr="disabled"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-50 transition-colors duration-150 flex items-center space-x-3"
                                    >
                                        <!-- User Avatar -->
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 overflow-hidden">
                                                @if (isset($recentUser['photo']) && $recentUser['photo'])
                                                    <img src="{{ asset($recentUser['photo']) }}" alt="{{ $recentUser['name'] }}" class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- User Info -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $recentUser['name'] }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $recentUser['email'] }}</p>
                                        </div>
                                        
                                        <!-- User Role Badge -->
                                        <div class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-800">
                                            {{ $recentUser['role'] ?? 'User' }}
                                        </div>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="py-8 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No recent users found</p>
                            <p class="mt-1 text-xs text-gray-400">Recent users will appear here after you impersonate them</p>
                        </div>
                    @endif
                </div>

                <!-- Informational Footer -->
                <div class="px-4 py-2 bg-gray-50 border-t border-gray-100">
                    <p class="text-xs text-gray-500 text-center">
                        <span class="font-medium">Note:</span> All actions taken while impersonating will be logged
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Indicator for Impersonation Actions -->
    <div wire:loading wire:target="impersonate" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
        <div class="bg-white p-4 rounded-lg shadow-xl flex items-center space-x-4">
            <svg class="animate-spin h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-gray-700 text-sm font-medium">Switching user...</span>
        </div>
    </div>
</div>