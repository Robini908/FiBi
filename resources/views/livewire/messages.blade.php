@php
    use Illuminate\Support\Str;
@endphp

<!-- Messages Dropdown Component -->
<div wire:poll.5000ms="updateUnreadCount" class="overflow-y-auto max-h-[400px] divide-y divide-gray-100 dark:divide-gray-700">
    <!-- Header with Stats -->
    <div class="p-3 bg-gray-50 dark:bg-gray-700/30">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $totalUnread ?? 0 }} unread {{ Str::plural('message', $totalUnread ?? 0) }}</span>
            </div>
            <div>
                <a href="{{ route('messages.index') }}" class="text-xs font-medium text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                    View All
                </a>
            </div>
        </div>
    </div>

    <!-- Messages List -->
    <div class="space-y-0 divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($recentMessages as $message)
            <a 
                @if(isset($clickHandler))
                    x-on:click="eval('{{ $clickHandler }}').call(this, {{ $message->sender_id }})"
                @else
                    href="{{ route('messages.index', ['userId' => $message->sender_id]) }}"
                @endif
                class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150"
                wire:key="message-{{ $message->id }}"
            >
                <div class="flex items-start p-3 {{ $message->read_at ? '' : 'bg-green-50 dark:bg-green-900/10' }}">
                    <!-- User Avatar -->
                    <div class="flex-shrink-0">
                        @if($message->sender->photo)
                            <img 
                                src="{{ asset($message->sender->photo) }}" 
                                alt="{{ $message->sender->name }}" 
                                class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-600"
                            />
                        @else
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center text-white font-medium">
                                {{ $this->getInitials($message->sender->name) }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Message Content -->
                    <div class="ml-3 flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $message->sender->name }}
                            </p>
                            <div class="flex items-center space-x-1">
                                @if($message->created_at && $message->created_at->diffInHours() < 24 && !$message->read_at)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        New
                                    </span>
                                @endif
                                <p class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                    {{ $message->created_at ? $message->created_at->diffForHumans() : 'Unknown time' }}
                                </p>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ $message->message }}
                        </p>
                    </div>
                    
                    <!-- Mark as Read Button -->
                    @if(!$message->read_at)
                        <button 
                            wire:click.stop="markAsRead('{{ $message->id }}')"
                            class="ml-2 p-1 rounded-full text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-800/30 focus:outline-none focus:ring-2 focus:ring-green-500"
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
            <div class="py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No messages yet</p>
            </div>
        @endforelse
    </div>

    <!-- Footer Actions -->
    <div class="p-3 bg-gray-50 dark:bg-gray-700/30 flex justify-between items-center">
        <a 
            href="{{ route('messages.index', ['view' => 'new']) }}"
            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
        >
            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Message
        </a>
        
        <a 
            href="{{ route('messages.index', ['filter' => 'unread']) }}"
            class="text-xs font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
        >
            Unread Messages
        </a>
    </div>
</div>
