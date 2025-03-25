<div wire:poll.5000ms="updateUnreadCount">
    <div class="space-y-1 max-h-60 overflow-y-auto">
        @forelse($recentMessages as $message)
            <a href="{{ route('messages.index') }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                <div class="flex items-start p-3 {{ $message->read_at ? '' : 'bg-green-50 dark:bg-green-900/10' }}">
                    <!-- User Avatar/Initial -->
                    <div class="flex-shrink-0">
                        @if($message->sender->photo)
                            <img src="{{ $message->sender->photo }}" 
                                 alt="{{ $message->sender->name }}" 
                                 class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-600" />
                        @else
                            <div class="flex items-center justify-center h-10 w-10 rounded-full {{ $message->read_at ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200' }}">
                                <span class="font-medium text-sm">
                                    {{ $this->getInitials($message->sender->name) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Message Content -->
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center">
                            {{ $message->sender->name }}
                            @if($message->created_at->diffInHours() < 24 && !$message->read_at)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                    New
                                </span>
                            @endif
                        </p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ $message->message }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            {{ $message->created_at->diffForHumans() }}
                        </p>
                    </div>
                    
                    <!-- Mark as Read Button -->
                    @if(!$message->read_at)
                        <button 
                            wire:click.stop="markAsRead('{{ $message->id }}')" 
                            class="ml-2 flex-shrink-0 p-1 rounded-full text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-800/30 focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                            <span class="sr-only">Mark as read</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    @endif
                </div>
            </a>
        @empty
            <div class="py-6 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No messages yet</p>
            </div>
        @endforelse
    </div>

    <!-- New Message Button -->
    <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-2 pb-1 text-center">
        <a href="{{ route('messages.index') }}"
           class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 hover:bg-green-200 dark:hover:bg-green-700 transition duration-150 ease-in-out"
        >
            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New message
        </a>
    </div>
</div>
