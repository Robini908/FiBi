<div wire:poll.5000ms="loadNotifications">
    <div class="space-y-1 max-h-60 overflow-y-auto">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div 
                class="relative group hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                :class="{'bg-gray-50 dark:bg-gray-700': open === '<?php echo e($notification->id); ?>'}"
                x-data="{ open: false }"
            >
                <div class="flex items-start p-2.5 rounded-lg <?php echo e($notification->read_at ? 'opacity-75' : 'bg-green-50 dark:bg-green-900/10'); ?>">
                    <!-- Notification Icon -->
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full <?php echo e($notification->read_at ? 'bg-gray-200 dark:bg-gray-700' : 'bg-green-100 dark:bg-green-800'); ?>">
                            <svg class="<?php echo e($notification->read_at ? 'text-gray-500 dark:text-gray-400' : 'text-green-600 dark:text-green-300'); ?> h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Notification Content -->
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                            <?php echo e($notification->data['message'] ?? 'Notification'); ?>

                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <?php echo e($notification->created_at->diffForHumans()); ?>

                        </p>
                    </div>
                    
                    <!-- Notification Actions -->
                    <div class="ml-2 flex-shrink-0 flex">
                        <button 
                            type="button" 
                            @click="open = !open"
                            class="rounded-full p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Dropdown Actions Menu -->
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-1 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-10"
                    x-cloak
                >
                    <!--[if BLOCK]><![endif]--><?php if(!$notification->read_at): ?>
                        <button 
                            wire:click="markAsRead('<?php echo e($notification->id); ?>')" 
                            @click="open = false"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <svg class="mr-3 h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Mark as read
                        </button>
                    <?php else: ?>
                        <button 
                            wire:click="markAsUnread('<?php echo e($notification->id); ?>')" 
                            @click="open = false"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <svg class="mr-3 h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Mark as unread
                        </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <button 
                        wire:click="deleteNotification('<?php echo e($notification->id); ?>')"
                        @click="open = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                        <svg class="mr-3 h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-6 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No notifications yet</p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Mark All as Read Button -->
    <!--[if BLOCK]><![endif]--><?php if($notifications->count() > 0 && $unreadCount > 0): ?>
        <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-2 pb-1 text-center">
            <button 
                wire:click="markAllAsRead"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 hover:bg-green-200 dark:hover:bg-green-700 transition duration-150 ease-in-out"
            >
                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Mark all as read
            </button>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\projects\MbukuErp\resources\views/livewire/notifications.blade.php ENDPATH**/ ?>