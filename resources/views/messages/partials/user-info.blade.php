<!-- User Info Sidebar -->
<div class="h-full flex flex-col bg-white">
    <!-- Header -->
    <div class="p-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">User Information</h3>
            <button 
                @click="showUserInfo = false"
                class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-50"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    @if($selectedUser)
    <!-- User Profile -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col items-center text-center">
            <!-- User Avatar -->
            <div class="h-24 w-24 rounded-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center text-white text-3xl font-medium shadow-sm mb-4">
                {{ $this->getInitials($selectedUser->name) }}
            </div>
            
            <!-- User Name -->
            <h3 class="text-xl font-semibold text-gray-900">{{ $selectedUser->name }}</h3>
            
            <!-- Online Status -->
            <div class="mt-2 flex items-center">
                @if($this->isOnline($selectedUser->id))
                    <span class="flex h-3 w-3 relative mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <p class="text-sm text-green-600">Online</p>
                @else
                    <span class="h-3 w-3 rounded-full bg-gray-300 mr-2"></span>
                    <p class="text-sm text-gray-500">Offline</p>
                @endif
            </div>
            
            <!-- User Role Badge -->
            @if($selectedUser->roles && $selectedUser->roles->count() > 0)
                <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $selectedUser->roles->first()->name }}
                </div>
            @endif
        </div>
    </div>

    <!-- User Details -->
    <div class="p-6 space-y-4 flex-1 overflow-y-auto">
        <!-- Contact Information -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Contact Information</h4>
            <div class="space-y-3">
                <!-- Email -->
                @if($selectedUser->email)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 text-gray-400 mt-0.5">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-3 text-sm">
                            <p class="font-medium text-gray-900">Email</p>
                            <p class="text-gray-500 break-all">{{ $selectedUser->email }}</p>
                        </div>
                    </div>
                @endif

                <!-- Phone -->
                @if($selectedUser->phone)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 text-gray-400 mt-0.5">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="ml-3 text-sm">
                            <p class="font-medium text-gray-900">Phone</p>
                            <p class="text-gray-500">{{ $selectedUser->phone }}</p>
                        </div>
                    </div>
                @endif

                <!-- User Code -->
                @if($selectedUser->code)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 text-gray-400 mt-0.5">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                        </div>
                        <div class="ml-3 text-sm">
                            <p class="font-medium text-gray-900">User ID</p>
                            <p class="text-gray-500 font-mono">{{ $selectedUser->code }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Conversation Stats -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Conversation Stats</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-lg font-semibold text-gray-900">{{ is_array($messages) ? count($messages) : $messages->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Messages</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-lg font-semibold text-gray-900">
                        @php
                            $messageCount = is_array($messages) ? count($messages) : $messages->count();
                            $firstMessage = is_array($messages) ? (empty($messages) ? null : $messages[count($messages) - 1]) : $messages->last();
                            $daysSinceFirst = $firstMessage ? now()->diffInDays(is_array($firstMessage) ? \Carbon\Carbon::parse($firstMessage['created_at']) : $firstMessage->created_at) + 1 : 0;
                            $averagePerDay = $daysSinceFirst > 0 ? round($messageCount / $daysSinceFirst, 1) : 0;
                        @endphp
                        {{ $averagePerDay }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Avg. Messages/Day</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="p-4 border-t border-gray-100">
        <div class="space-y-3">
            <button class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Clear conversation
            </button>
        </div>
    </div>
    @else
    <!-- No User Selected State -->
    <div class="p-6 flex-1 flex flex-col items-center justify-center text-center">
        <div class="h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-4">
            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">No user selected</h3>
        <p class="mt-2 text-sm text-gray-500 max-w-xs">
            Select a conversation to view user information
        </p>
    </div>
    @endif
</div> 