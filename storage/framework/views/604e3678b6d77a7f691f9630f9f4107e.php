<!-- Modal for Adding/Editing Account -->
<!--[if BLOCK]><![endif]--><?php if($showModal): ?>

<?php
    // $message is automatically available inside @error directive
?>

<div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-lg w-full mx-auto" 
         x-on:click.away="$wire.closeModal()">
        <!-- Modal header -->
        <div class="py-4 px-6 bg-green-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-green-700"><?php echo e($isEditing ? 'Edit Account' : 'Add New Account'); ?></h3>
                <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal body -->
        <form wire:submit.prevent="<?php echo e($isEditing ? 'updateAccount' : 'createAccount'); ?>">
            <div class="py-4 px-6 space-y-4">
                <!-- Account Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Account Name</label>
                    <input type="text" id="name" wire:model="name" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Account Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">
                        Account Code
                        <!--[if BLOCK]><![endif]--><?php if(!$isEditing): ?>
                        <span class="text-xs text-gray-500 ml-1">(Auto-generated)</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </label>
                    <input type="text" id="code" wire:model="code" 
                           <?php echo e(!$isEditing ? 'readonly' : ''); ?>

                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm <?php echo e(!$isEditing ? 'bg-gray-100' : ''); ?>">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Initial Balance -->
                <div>
                    <label for="initial_balance" class="block text-sm font-medium text-gray-700">Initial Balance (KES)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">KES</span>
                        </div>
                        <input type="number" step="0.01" id="initial_balance" wire:model="initial_balance" 
                               class="pl-12 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['initial_balance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" wire:model="description" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"></textarea>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Status -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" wire:model="is_active" 
                           class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Account is active
                    </label>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" wire:click="closeModal" 
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <?php echo e($isEditing ? 'Update Account' : 'Create Account'); ?>

                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/account-form-modal.blade.php ENDPATH**/ ?>