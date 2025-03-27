@php
    use Illuminate\Support\Str;
@endphp

<!-- Messages Sidebar -->
<div class="flex flex-col h-full bg-white">
    <!-- Sidebar Header -->
    <div class="p-4 border-b border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Messages</h2>
            <button 
                class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-50"
                title="New Message"
                onclick="document.querySelector('[x-ref=searchInput]').focus()"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
        
        <!-- Search -->
        <div class="relative">
            <input 
                type="text" 
                wire:model.debounce.300ms="searchTerm" 
                placeholder="Search messages..."
                x-ref="searchInput"
                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-full text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50 hover:bg-white transition-colors duration-200"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <!-- Loading indicator for search -->
            <div wire:loading wire:target="searchTerm" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Conversations List -->
    <div class="flex-1 overflow-y-auto">
        @if ($searchTerm)
            <!-- Search Results -->
            <div class="py-3">
                <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Search Results</h3>
                @forelse ($this->getSearchResults() as $user)
                    <button 
                        wire:click="selectUser({{ $user['id'] }})"
                        class="w-full px-4 py-3 flex items-center space-x-3 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition-colors duration-150 relative group 
                               {{ $selectedUserId == $user['id'] ? 'bg-green-50 hover:bg-green-50' : '' }}"
                    >
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center text-white text-lg font-medium shadow-sm">
                                    {{ $this->getInitials($user['name']) }}
                                </div>
                                @if($this->isOnline($user['id']))
                                    <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-400 ring-2 ring-white"></div>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 truncate group-hover:text-green-600">
                                    {{ $user['name'] }}
                                </p>
                            </div>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $user['email'] }}
                            </p>
                        </div>
                        @if ($this->getUnreadCount($user['id']) > 0)
                            <div class="absolute right-4">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-xs font-medium text-green-600">
                                    {{ $this->getUnreadCount($user['id']) }}
                                </span>
                            </div>
                        @endif
                    </button>
                @empty
                    <div class="px-4 py-8 text-center">
                        <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">No users found</p>
                        <p class="mt-1 text-xs text-gray-400">Try a different search term</p>
                    </div>
                @endforelse
            </div>
        @else
            <!-- Combined Message List -->
            <div class="py-3">
                <!-- Unread Messages (shown at the top) -->
                @if(count($unreadMessages) > 0)
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Unread Messages</h3>
                    @foreach($unreadMessages as $user)
                        <button 
                            wire:click="selectUser({{ $user->id }})"
                            wire:key="unread-{{ $user->id }}"
                            class="w-full px-4 py-3 flex items-center space-x-3 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition-colors duration-150 relative group bg-green-50
                                   {{ $selectedUserId == $user->id ? 'bg-green-100 hover:bg-green-100' : '' }}"
                        >
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center text-white text-lg font-medium shadow-sm">
                                        {{ $this->getInitials($user->name) }}
                                    </div>
                                    @if($this->isOnline($user->id))
                                        <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-400 ring-2 ring-white"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate group-hover:text-green-600">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                        {{ ($user->latestMessage && $user->latestMessage->created_at) ? $user->latestMessage->created_at->diffForHumans(null, true) : '' }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-xs font-medium text-gray-900 truncate">
                                        @if($user->latestMessage)
                                            {{ Str::limit($user->latestMessage->message, 50) }}
                                        @else
                                            <span class="text-gray-400">No message content</span>
                                        @endif
                                    </p>
                                    <span class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-green-100 text-xs font-medium text-green-600">
                                        {{ $this->getUnreadCount($user->id) }}
                                    </span>
                                </div>
                            </div>
                        </button>
                    @endforeach
                    
                    <!-- Divider between unread and recent messages -->
                    <div class="my-2 border-t border-gray-100"></div>
                @endif
                
                <!-- Recent Messages -->
                <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">All Messages</h3>
                @forelse($recentMessages as $user)
                    @if(!in_array($user->id, collect($unreadMessages)->pluck('id')->toArray()))
                        <button 
                            wire:click="selectUser({{ $user->id }})"
                            wire:key="recent-{{ $user->id }}"
                            class="w-full px-4 py-3 flex items-center space-x-3 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition-colors duration-150 relative group 
                                   {{ $selectedUserId == $user->id ? 'bg-green-50 hover:bg-green-50' : '' }}"
                        >
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center text-white text-lg font-medium shadow-sm">
                                        {{ $this->getInitials($user->name) }}
                                    </div>
                                    @if($this->isOnline($user->id))
                                        <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-400 ring-2 ring-white"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate group-hover:text-green-600">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                        {{ ($user->latestMessage && $user->latestMessage->created_at) ? $user->latestMessage->created_at->diffForHumans(null, true) : '' }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-xs text-gray-500 truncate">
                                        @if($user->latestMessage)
                                            @if($user->latestMessage->sender_id == auth()->id())
                                                <span class="text-gray-400">You:</span>
                                            @endif
                                            {{ Str::limit($user->latestMessage->message, 50) }}
                                        @else
                                            <span class="text-gray-400">No messages yet</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </button>
                    @endif
                @empty
                    @if(count($unreadMessages) == 0)
                        <div class="px-4 py-8 text-center">
                            <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">No messages yet</p>
                            <p class="mt-1 text-xs text-gray-400">Start a new conversation</p>
                        </div>
                    @endif
                @endforelse
            </div>
        @endif
    </div>
</div> 