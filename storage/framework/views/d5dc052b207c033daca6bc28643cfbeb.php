<?php
    use Illuminate\Support\Str;
?>

<!-- Top Navigation Bar -->
<header class="fixed right-0 top-0 md:left-64 left-0 bg-white dark:bg-gray-800 h-16 z-40 shadow-sm border-b border-gray-100 dark:border-gray-700">
    <div class="h-full px-4 sm:px-6 flex items-center justify-between">
        <!-- Left side -->
        <div class="flex items-center">
            <!-- Mobile menu button -->
            <button 
                @click="sidebarOpen = true" 
                type="button" 
                class="md:hidden text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 focus:outline-none transition-colors duration-200"
                aria-label="Open sidebar"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Search -->
            <div class="ml-4 w-full max-w-md">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('intelligent-search');

$__html = app('livewire')->mount($__name, $__params, 'lw-3827936656-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-2">
            <!-- Notifications Dropdown -->
            <div 
                x-data="{ open: false, unreadCount: <?php echo e(Auth::user()->unreadNotifications ? Auth::user()->unreadNotifications->count() : 0); ?> }" 
                class="relative"
                @notification-read.window="unreadCount = Math.max(0, unreadCount - 1)"
                @notification-received.window="unreadCount++"
            >
                <button 
                    @click="open = !open" 
                    class="p-2 text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200"
                    aria-label="View notifications"
                >
                    <span class="sr-only">Notifications</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <!-- Notification Badge -->
                    <span 
                        x-show="unreadCount > 0" 
                        x-text="unreadCount > 9 ? '9+' : unreadCount"
                        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-green-500 rounded-full"
                    ></span>
                </button>
                
                <!-- Notifications Dropdown Panel -->
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-50"
                    x-cloak
                >
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Notifications</h3>
                            <a href="<?php echo e(route('notifications.index')); ?>" class="text-xs font-medium text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">View all</a>
                        </div>
                        <!-- Livewire Notifications Component -->
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('notifications');

$__html = app('livewire')->mount($__name, $__params, 'lw-3827936656-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    </div>
                </div>
            </div>

            <!-- Messages Dropdown -->
            <div 
                x-data="{ 
                    open: false, 
                    unreadCount: <?php echo e(Auth::user()->unreadMessages ? Auth::user()->unreadMessages->count() : 0); ?>,
                    closeAndRedirect(userId) {
                        this.open = false;
                        window.location.href = '<?php echo e(route('messages.index')); ?>' + (userId ? '?userId=' + userId : '');
                    }
                }" 
                class="relative"
                @message-read.window="unreadCount = Math.max(0, unreadCount - 1)"
                @message-received.window="unreadCount++"
                @unreadCountUpdated.window="unreadCount = $event.detail.count"
            >
                <button 
                    @click="open = !open" 
                    class="p-2 text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200"
                    aria-label="View messages"
                >
                    <span class="sr-only">Messages</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <!-- Message Badge -->
                    <span 
                        x-show="unreadCount > 0" 
                        x-text="unreadCount > 9 ? '9+' : unreadCount"
                        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-green-500 rounded-full"
                    ></span>
                </button>
                
                <!-- Messages Dropdown Panel -->
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-50"
                    x-cloak
                >
                    <!-- Livewire Messages Component with click handling -->
                    <div @click.stop class="max-h-[500px] overflow-hidden rounded-lg">
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('messages', ['clickHandler' => 'function(userId) { $el.closest(\'[x-data]\')._x.$data.closeAndRedirect(userId); }']);

$__html = app('livewire')->mount($__name, $__params, 'lw-3827936656-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    </div>
                </div>
            </div>

            <!-- User Impersonation Component -->
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('impersonate-user');

$__html = app('livewire')->mount($__name, $__params, 'lw-3827936656-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" class="relative ml-2">
                <button 
                    @click="open = !open" 
                    class="flex items-center space-x-2 text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 rounded-full"
                    id="user-menu-button"
                    aria-expanded="false"
                    aria-haspopup="true"
                >
                    <div class="relative w-8 h-8 overflow-hidden bg-gray-200 dark:bg-gray-700 rounded-full ring-2 ring-white dark:ring-gray-800">
                        <img 
                            class="h-full w-full object-cover" 
                            src="<?php echo e(Auth::user()->photo ? asset(Auth::user()->photo) : asset('global_assets/images/user.png')); ?>" 
                            alt="<?php echo e(Auth::user()->name); ?>"
                            onerror="this.src='<?php echo e(asset('global_assets/images/user.png')); ?>'"
                        >
                    </div>
                    <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300 max-w-[100px] truncate"><?php echo e(Str::limit(Auth::user()->name, 15)); ?></span>
                    <svg class="hidden md:block h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Profile Dropdown Panel -->
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="user-menu-button"
                    tabindex="-1"
                    x-cloak
                >
                    <a href="<?php echo e(route('my_account')); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-200" role="menuitem">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>My Account</span>
                        </div>
                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-200" role="menuitem">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Sign out</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Spacer to prevent content from hiding under fixed header -->
<div class="h-16"></div>

<!-- Alert container for toasts -->
<div
    x-data="{ 
        toasts: [],
        add(toast) {
            this.toasts.push({ ...toast, id: Date.now() });
            if (toast.autoDismiss) {
                setTimeout(() => this.remove(this.toasts[this.toasts.length - 1].id), toast.autoDismiss);
            }
        },
        remove(id) {
            this.toasts = this.toasts.filter(toast => toast.id !== id);
        }
    }"
    x-init="window.addEventListener('toast', event => add(event.detail))"
    class="fixed top-4 right-4 z-50 space-y-3 w-full max-w-sm"
    aria-live="assertive"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg ring-1 overflow-hidden"
            :class="{
                'ring-green-500': toast.type === 'success',
                'ring-blue-500': toast.type === 'info',
                'ring-yellow-500': toast.type === 'warning',
                'ring-red-500': toast.type === 'danger' || toast.type === 'error'
            }"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Success Icon -->
                        <svg x-show="toast.type === 'success'" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <!-- Info Icon -->
                        <svg x-show="toast.type === 'info'" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <!-- Warning Icon -->
                        <svg x-show="toast.type === 'warning'" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <!-- Error Icon -->
                        <svg x-show="toast.type === 'danger' || toast.type === 'error'" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <div x-show="toast.title" x-text="toast.title" class="text-sm font-medium text-gray-900 dark:text-white"></div>
                        <div x-show="toast.message" class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-html="toast.message"></div>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button 
                            @click="remove(toast.id)" 
                            class="bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-offset-gray-800"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/partials/header.blade.php ENDPATH**/ ?>