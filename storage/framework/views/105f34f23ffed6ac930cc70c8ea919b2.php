<!-- Modern Profile Form -->
<div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6">
    <!-- Profile information section -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
            <p class="mt-1 text-sm text-gray-500">Update your personal information and contact details</p>
        </div>

        <!--[if BLOCK]><![endif]--><?php if($isEditing): ?>
            <div class="p-6">
                <form wire:submit.prevent="updateProfile" class="space-y-8">
                    <div class="space-y-6">
                        <!-- Name -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Full Name
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input 
                                        type="text" 
                                        id="name" 
                                        value="<?php echo e($name); ?>" 
                                        disabled 
                                        class="block w-full rounded-md border-gray-300 bg-gray-50 text-gray-500 pr-10 focus:outline-none sm:text-sm"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Name cannot be changed. Contact administrator for assistance.</p>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="username" class="block text-sm font-medium text-gray-700">
                                    Username
                                </label>
                                <!--[if BLOCK]><![endif]--><?php if(!$user->username): ?>
                                    <div class="mt-1">
                                        <input 
                                            type="text" 
                                            id="username" 
                                            wire:model="username" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $errors->get('username'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <p class="mt-2 text-sm text-red-600"><?php echo e($error); ?></p>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php else: ?>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input 
                                            type="text" 
                                            id="username" 
                                            value="<?php echo e($username); ?>" 
                                            disabled 
                                            class="block w-full rounded-md border-gray-300 bg-gray-50 text-gray-500 pr-10 focus:outline-none sm:text-sm"
                                        >
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="pt-6">
                            <h3 class="text-md font-medium text-gray-900 border-b pb-2 mb-4">Contact Information</h3>
                        </div>

                        <!-- Email -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email Address
                                </label>
                                <div class="mt-1">
                                    <input 
                                        type="email" 
                                        id="email" 
                                        wire:model="email" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $errors->get('email'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="mt-2 text-sm text-red-600"><?php echo e($error); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    Primary Phone Number
                                </label>
                                <div class="mt-1">
                                    <input 
                                        type="text" 
                                        id="phone" 
                                        wire:model="phone" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $errors->get('phone'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="mt-2 text-sm text-red-600"><?php echo e($error); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="mobile" class="block text-sm font-medium text-gray-700">
                                    Mobile Phone
                                </label>
                                <div class="mt-1">
                                    <input 
                                        type="text" 
                                        id="mobile" 
                                        wire:model="mobile" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $errors->get('mobile'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="mt-2 text-sm text-red-600"><?php echo e($error); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="address" class="block text-sm font-medium text-gray-700">
                                    Address
                                </label>
                                <div class="mt-1">
                                    <textarea 
                                        id="address" 
                                        wire:model="address" 
                                        rows="3" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    ></textarea>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $errors->get('address'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="mt-2 text-sm text-red-600"><?php echo e($error); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                        <button 
                            type="button" 
                            wire:click="cancelEditing" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-200">
                <dl>
                    <!-- Name -->
                    <div class="px-6 py-5 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Full name</dt>
                        <dd class="text-sm text-gray-900 col-span-2"><?php echo e($name); ?></dd>
                    </div>
                    
                    <!-- Username -->
                    <div class="px-6 py-5 grid grid-cols-3 gap-4 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Username</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            <!--[if BLOCK]><![endif]--><?php if($username): ?>
                                <?php echo e($username); ?>

                            <?php else: ?>
                                <span class="text-gray-400 italic">Not set</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </dd>
                    </div>
                    
                    <!-- Email -->
                    <div class="px-6 py-5 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Email address</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            <!--[if BLOCK]><![endif]--><?php if($email): ?>
                                <?php echo e($email); ?>

                            <?php else: ?>
                                <span class="text-gray-400 italic">Not set</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </dd>
                    </div>
                    
                    <!-- Phone -->
                    <div class="px-6 py-5 grid grid-cols-3 gap-4 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Phone number</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            <!--[if BLOCK]><![endif]--><?php if($phone): ?>
                                <?php echo e($phone); ?>

                            <?php else: ?>
                                <span class="text-gray-400 italic">Not set</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </dd>
                    </div>
                    
                    <!-- Alternative Phone -->
                    <div class="px-6 py-5 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Mobile number</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            <!--[if BLOCK]><![endif]--><?php if($mobile): ?>
                                <?php echo e($mobile); ?>

                            <?php else: ?>
                                <span class="text-gray-400 italic">Not set</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </dd>
                    </div>
                    
                    <!-- Address -->
                    <div class="px-6 py-5 grid grid-cols-3 gap-4 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            <!--[if BLOCK]><![endif]--><?php if($address): ?>
                                <?php echo e($address); ?>

                            <?php else: ?>
                                <span class="text-gray-400 italic">Not set</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </dd>
                    </div>
                </dl>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/user-profile/profile-form.blade.php ENDPATH**/ ?>