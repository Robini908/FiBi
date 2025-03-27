@php
    use Illuminate\Support\Str;
@endphp

<div class="h-screen flex bg-gray-50" 
     x-data="{ 
        mobileSidebarOpen: false, 
        showUserInfo: false,
        setupListeners() {
            window.addEventListener('setTimeout', (e) => {
                setTimeout(() => {
                    @this.cancelTyping();
                }, e.detail.duration);
            });
        }
     }"
     x-init="setupListeners()"
>
    <!-- Left Sidebar - Contact List -->
    <div :class="{'translate-x-0': mobileSidebarOpen, '-translate-x-full': !mobileSidebarOpen}"
         class="fixed inset-y-0 left-0 lg:relative lg:translate-x-0 w-80 transform bg-white border-r border-gray-100 transition-transform duration-200 ease-in-out z-30 flex flex-col">
        
        <!-- Mobile Close Button -->
        <div class="lg:hidden absolute right-0 top-0 -mr-12 pt-2">
            <button @click="mobileSidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
                                </div>

        @include('messages.partials.sidebar')
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col w-0 overflow-hidden">
        <!-- Mobile Header -->
        <div class="lg:hidden">
            <div class="flex items-center justify-between bg-white px-4 py-2 border-b border-gray-100">
                <button @click="mobileSidebarOpen = true" class="p-2 -ml-3 text-gray-500 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                                </button>
                <h1 class="text-lg font-semibold text-gray-900">Messages</h1>
                <div class="w-6"></div>
                                </div>
                            </div>

        <!-- Chat Area -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Main Chat Section -->
            <div class="flex-1 flex flex-col min-w-0 bg-white">
                @if($view == 'chat')
                    <div 
                        x-data="{ showAnimation: false }" 
                        x-init="
                            showAnimation = true;
                            setTimeout(() => showAnimation = false, 500);
                        "
                        :class="{ 'animate-fade-in': showAnimation }"
                        class="h-full"
                    >
                        @include('messages.partials.chat-area')
                                    </div>
                                @else
                    <!-- Welcome/Empty State -->
                    <div class="flex-1 flex items-center justify-center bg-gray-50 p-6">
                        <div class="text-center max-w-sm">
                            <div class="mx-auto h-24 w-24 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <h3 class="mt-6 text-lg font-medium text-gray-900">Welcome to Messages</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-xs mx-auto">
                                Select a conversation from the sidebar or start a new chat to begin messaging
                            </p>
                            <button 
                                class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                onclick="document.querySelector('[x-ref=searchInput]').focus()"
                            >
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Start New Chat
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- User Info Sidebar -->
            <div x-show="showUserInfo" 
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="hidden lg:block w-80 border-l border-gray-100 bg-white overflow-y-auto"
                 x-cloak>
                @if($view == 'chat')
                    @include('messages.partials.user-info')
                @endif
            </div>
        </div>
    </div>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
    </style>
</div>
