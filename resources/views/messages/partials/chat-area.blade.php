<!-- Chat Area -->
<div class="flex flex-col h-full bg-white">
    <!-- Chat Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-white sticky top-0 z-10">
        <div class="flex items-center min-w-0">
            <button 
                class="lg:hidden -ml-2 p-2 text-gray-400 hover:text-gray-600 focus:outline-none"
                @click="mobileSidebarOpen = true"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <div class="flex items-center space-x-3 min-w-0">
                <div class="relative">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center text-white text-lg font-medium shadow-sm">
                        {{ $selectedUser ? $this->getInitials($selectedUser->name) : 'NA' }}
                    </div>
                    @if($selectedUser && $this->isOnline($selectedUser->id))
                        <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-400 ring-2 ring-white"></div>
                    @endif
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg font-medium text-gray-900 truncate">{{ $selectedUser ? $selectedUser->name : 'Select a conversation' }}</h2>
                    <p class="text-sm text-gray-500">
                        @if($otherUserTyping)
                            <span class="inline-flex items-center text-green-600">
                                <span>typing</span>
                                <span class="ml-1 flex space-x-1">
                                    <span class="animate-bounce">.</span>
                                    <span class="animate-bounce" style="animation-delay: 0.2s">.</span>
                                    <span class="animate-bounce" style="animation-delay: 0.4s">.</span>
                                </span>
                            </span>
                        @elseif($selectedUser)
                            {{ $this->isOnline($selectedUser->id) ? 'Online' : 'Last seen ' . (now()->subMinutes(rand(5, 120)))->diffForHumans() }}
                        @else
                            Start a new conversation
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-2">
            <button 
                class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                @click="showUserInfo = !showUserInfo"
                title="View user information"
            >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    title="More options"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100" 
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    x-cloak
                >
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Search messages</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Mark all as read</a>
                    <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Clear conversation</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div 
        class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" 
        id="messages-container"
        x-data="{
            init() {
                this.scrollToBottom();
                this.$el.addEventListener('DOMNodeInserted', this.scrollToBottom);
            },
            scrollToBottom() {
                const container = document.getElementById('messages-container');
                if (container) {
                    const shouldScroll = container.scrollTop + container.clientHeight >= container.scrollHeight - 100;
                    if (shouldScroll) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            }
        }"
    >
        @php
            $lastDate = null;
            $lastSenderId = null;
            $groupedMessages = [];
            $currentGroup = null;
            $lastDateShown = null;
            
            // Group messages by sender and time proximity
            foreach ($messages as $index => $message) {
                $messageDate = \Carbon\Carbon::parse($message['created_at'])->toDateString();
                
                // Start a new group if:
                // 1. Different date
                // 2. Different sender
                // 3. Time gap > 2 minutes
                $newDate = $messageDate != $lastDate;
                $newSender = $lastSenderId !== $message['sender_id'];
                $timeGap = $index > 0 ? \Carbon\Carbon::parse($message['created_at'])->diffInMinutes(\Carbon\Carbon::parse($messages[$index-1]['created_at'])) > 2 : false;
                
                if ($newDate || $newSender || $timeGap) {
                    if ($currentGroup) {
                        $groupedMessages[] = $currentGroup;
                    }
                    $currentGroup = [
                        'sender_id' => $message['sender_id'],
                        'date' => $messageDate,
                        'messages' => []
                    ];
                }
                
                $currentGroup['messages'][] = $message;
                $lastDate = $messageDate;
                $lastSenderId = $message['sender_id'];
            }
            
            // Add the last group
            if ($currentGroup) {
                $groupedMessages[] = $currentGroup;
            }
        @endphp

        @foreach ($groupedMessages as $group)
            @php
                $isCurrentUser = $group['sender_id'] == auth()->id();
                $dateObj = \Carbon\Carbon::parse($group['date']);
                $formattedDate = $group['date'];
                $currentDate = \Carbon\Carbon::today()->toDateString();
                $yesterdayDate = \Carbon\Carbon::yesterday()->toDateString();
            @endphp

            <!-- Date Separator -->
            @if ($formattedDate != $lastDateShown ?? null)
                <div class="flex items-center justify-center my-6">
                    <div class="px-4 py-2 rounded-full bg-white shadow-sm text-xs font-medium text-gray-500">
                        @if ($formattedDate == $currentDate)
                            Today
                        @elseif ($formattedDate == $yesterdayDate)
                            Yesterday
                        @else
                            {{ $dateObj->format('F j, Y') }}
                        @endif
                    </div>
                </div>
                @php
                    $lastDateShown = $formattedDate;
                @endphp
            @endif

            <!-- Message Group -->
            <div class="flex items-end group {{ $isCurrentUser ? 'justify-end' : 'justify-start' }} space-x-2">
                @if(!$isCurrentUser && $selectedUser)
                    <div class="flex-shrink-0 mb-2 self-end">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center text-white text-sm shadow-sm">
                            {{ $this->getInitials($selectedUser->name) }}
                        </div>
                    </div>
                @endif

                <div class="max-w-md">
                    <!-- Message Bubbles -->
                    <div class="space-y-1">
                        @foreach ($group['messages'] as $message)
                            <div class="group relative">
                                <!-- Message Content -->
                                <div class="{{ $isCurrentUser ? 'bg-green-500 text-white ml-auto rounded-2xl rounded-tr-sm' : 'bg-white text-gray-900 rounded-2xl rounded-tl-sm' }} px-4 py-2 shadow-sm max-w-md">
                                    @if ($editReply && $messageBeingRepliedTo && $message['id'] == $messageBeingRepliedTo->id)
                                        <div class="min-w-[200px]">
                                            <textarea 
                                                wire:model="editedMessage"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"
                                                rows="3"
                                            >{{ $message['message'] }}</textarea>
                                            <div class="mt-2 flex space-x-2">
                                                <button 
                                                    wire:click="updateMessage({{ $message['id'] }})"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                >
                                                    Save
                                                </button>
                                                <button 
                                                    wire:click="cancelEdit"
                                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-full shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                >
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <p class="whitespace-pre-wrap break-words">{{ $message['message'] }}</p>
                                    @endif
                                </div>

                                <!-- Message Actions -->
                                <div class="absolute {{ $isCurrentUser ? 'right-full mr-2' : 'left-full ml-2' }} bottom-1 opacity-0 group-hover:opacity-100 transition-opacity flex items-center space-x-1">
                                    @if($message['sender_id'] == auth()->id())
                                        <button 
                                            wire:click="editMessage({{ $message['id'] }})"
                                            class="p-1 rounded-full bg-white shadow-sm text-gray-400 hover:text-gray-600 focus:outline-none"
                                            title="Edit message"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    @endif
                                    <button 
                                        wire:click="deleteMessage({{ $message['id'] }})"
                                        class="p-1 rounded-full bg-white shadow-sm text-gray-400 hover:text-gray-600 focus:outline-none"
                                        title="Delete message"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Timestamp for group -->
                    <div class="mt-1 text-xs {{ $isCurrentUser ? 'text-right' : 'text-left' }} text-gray-400">
                        {{ \Carbon\Carbon::parse($group['messages'][count($group['messages'])-1]['created_at'])->format('g:i A') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Message Input -->
    <div class="border-t border-gray-100 bg-white p-4">
        <div class="flex items-end space-x-4">
            <div class="flex-1 min-w-0">
                <textarea 
                    wire:model="message" 
                    wire:keydown="startTyping"
                    wire:keyup="stopTyping"
                    @keydown.enter.prevent="$event.shiftKey || $wire.sendMessage()"
                    rows="1"
                    placeholder="Type your message..."
                    class="block w-full resize-none rounded-lg border-0 bg-gray-50 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-500 sm:text-sm sm:leading-6 transition-colors duration-200"
                    style="min-height: 24px; max-height: 96px;"
                ></textarea>
                <p class="mt-1 text-xs text-gray-500">Press Enter to send, Shift+Enter for new line</p>
            </div>
            <div class="flex-shrink-0">
                <button 
                    wire:click="sendMessage"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg wire:loading.remove class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <svg wire:loading class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for auto-scrolling to bottom -->
<script>
    const messagesContainer = document.getElementById('messages-container');
    
    // Initial scroll to bottom
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Observe changes to scroll to bottom when new messages arrive
    const observer = new MutationObserver(() => {
        if (messagesContainer) {
            const shouldAutoScroll = messagesContainer.scrollTop + messagesContainer.clientHeight >= messagesContainer.scrollHeight - 100;
            if (shouldAutoScroll) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        }
    });

    if (messagesContainer) {
        observer.observe(messagesContainer, {
            childList: true,
            subtree: true
        });
    }
</script> 