<?php
    use Illuminate\Support\Str;
?>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">
            <?php echo e($isEditing ? 'Edit Class' : 'Add New Class'); ?>

        </h3>
    </div>
    
    <form wire:submit.prevent="saveClass" class="px-6 py-4">
        <div x-data="{ formDirty: false }" 
             x-init="
                $watch('formDirty', value => {
                    if (value) {
                        window.addEventListener('beforeunload', (event) => {
                            event.preventDefault();
                            event.returnValue = '';
                        });
                    } else {
                        window.removeEventListener('beforeunload');
                    }
                })
             "
        >
            <!-- Loading Indicator -->
            <!--[if BLOCK]><![endif]--><?php if($loading): ?>
                <div class="flex items-center justify-center py-6">
                    <div class="flex flex-col items-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        <span class="mt-2 text-sm text-gray-500">Saving class, please wait...</span>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Class Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Class Name</label>
                <div class="mt-1">
                    <input 
                        type="text" 
                        id="name" 
                        wire:model.live="name" 
                        @input="formDirty = true"
                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="Enter class name"
                    >
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Streams Section -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-base font-medium text-gray-900">Streams for <?php echo e($name ?: 'this class'); ?></h4>
                    <div>
                        <!-- Stream count badge -->
                        <!--[if BLOCK]><![endif]--><?php if(count($streams) > 0): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php echo e(count($streams)); ?> <?php echo e(Str::plural('stream', count($streams))); ?>

                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">Add one or more streams to this class.</p>

                <!-- Add Stream Input Section -->
                <div class="mt-4 flex rounded-md shadow-sm">
                    <div class="relative flex items-stretch flex-grow focus-within:z-10">
                        <input 
                            type="text" 
                            wire:model.live="streamName" 
                            class="focus:ring-blue-500 focus:border-blue-500 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300"
                            placeholder="Add New Stream" 
                            @input="formDirty = true"
                            @keydown.enter.prevent="$wire.addStream()"
                            <?php if($streamEditMode): ?> disabled <?php endif; ?>
                        >
                    </div>
                    <button 
                        type="button" 
                        wire:click="addStream" 
                        class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        <?php if($streamEditMode): ?> disabled <?php endif; ?>
                    >
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Add Stream</span>
                    </button>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['streamName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                <!-- Streams List -->
                <div class="mt-4 border border-gray-200 rounded-md divide-y divide-gray-200" x-data="{ dragging: null }">
                    <!--[if BLOCK]><![endif]--><?php if(empty($streams)): ?>
                        <div class="p-4 text-center text-sm text-gray-500">
                            No streams added yet. Start by adding a stream for this class.
                        </div>
                    <?php else: ?>
                        <ul role="list">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $streams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stream): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="p-4 flex items-center justify-between hover:bg-gray-50">
                                    <div class="flex items-center min-w-0 flex-1">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4 min-w-0 flex-1">
                                            <!--[if BLOCK]><![endif]--><?php if($streamEditMode && $selectedStream === $index): ?>
                                                <div class="mt-1">
                                                    <input 
                                                        type="text" 
                                                        wire:model.live="streamName" 
                                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                        placeholder="Edit Stream Name" 
                                                        @input="formDirty = true"
                                                    >
                                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['streamName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                                    <div class="mt-2 flex space-x-2">
                                                        <button 
                                                            type="button" 
                                                            wire:click="updateStream" 
                                                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                        >
                                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Save
                                                        </button>
                                                        <button 
                                                            type="button" 
                                                            wire:click="$set('streamEditMode', false); $set('streamName', '')" 
                                                            class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                        >
                                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-sm font-medium text-gray-900 truncate"><?php echo e($stream['name']); ?></p>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex space-x-2">
                                        <button 
                                            type="button" 
                                            wire:click="editStream(<?php echo e($index); ?>)" 
                                            class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-indigo-600 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        >
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="removeStream(<?php echo e($index); ?>)" 
                                            class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-red-600 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        >
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </ul>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <!-- Form Dirty Alert -->
            <div 
                x-show="formDirty"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-6 rounded-md bg-yellow-50 p-4"
            >
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Unsaved changes</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>You have unsaved changes. Please save or cancel to avoid losing your work.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <button 
                    type="button" 
                    wire:click="resetForm" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="saveClass">
                        <?php echo e($isEditing ? 'Update Class' : 'Save Class'); ?>

                    </span>
                    <span wire:loading wire:target="saveClass" class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/class-management/class-form.blade.php ENDPATH**/ ?>