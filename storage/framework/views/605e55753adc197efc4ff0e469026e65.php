<!-- Google-like Profile Header -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
    <!-- User info section -->
    <div class="px-6 py-6">
        <div class="text-center sm:text-left">
            <h2 class="text-xl font-medium text-gray-900"><?php echo e($name); ?></h2>
            <p class="text-gray-500 text-sm"><?php echo e(ucfirst($user->getRoleNames()->first() ?? 'User')); ?></p>
            
            <!--[if BLOCK]><![endif]--><?php if(!$isEditing): ?>
                <div class="mt-4">
                    <button 
                        wire:click="startEditing" 
                        type="button" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Profile
                    </button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="border-t border-gray-200 grid grid-cols-2 divide-x divide-gray-200">
        <div class="px-6 py-4 text-center">
            <span class="block text-sm font-medium text-gray-500">Member Since</span>
            <span class="block mt-1 text-sm text-gray-900">
                <?php echo e($user->created_at ? $user->created_at->format('M Y') : 'N/A'); ?>

            </span>
        </div>
        <div class="px-6 py-4 text-center">
            <span class="block text-sm font-medium text-gray-500">Last Update</span>
            <span class="block mt-1 text-sm text-gray-900">
                <?php echo e($user->updated_at ? $user->updated_at->format('M d, Y') : 'N/A'); ?>

            </span>
        </div>
    </div>
</div>

<!-- Navigation Sidebar -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <nav class="px-2 py-3 space-y-1">
        <a 
            href="#" 
            @click.prevent="activeTab = 'profile'" 
            :class="activeTab === 'profile' ? 'bg-gray-100 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
            class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
        >
            <svg 
                xmlns="http://www.w3.org/2000/svg" 
                :class="activeTab === 'profile' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'" 
                class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile Information
        </a>
        
        <a 
            href="#" 
            @click.prevent="activeTab = 'password'" 
            :class="activeTab === 'password' ? 'bg-gray-100 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
            class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
        >
            <svg 
                xmlns="http://www.w3.org/2000/svg" 
                :class="activeTab === 'password' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'" 
                class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Password & Security
        </a>
    </nav>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/user-profile/header.blade.php ENDPATH**/ ?>