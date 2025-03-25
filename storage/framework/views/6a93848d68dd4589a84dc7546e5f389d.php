<div class="bg-white rounded-lg shadow-sm overflow-hidden" x-data="{ processing: false }">
    <!-- Header with accent -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Delete Student Record
        </h3>
        <button 
            wire:click="closeAction" 
            class="text-gray-500 hover:text-gray-700 focus:outline-none transition-colors"
            aria-label="Close"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div class="p-6">
        <!-- Student Info Card -->
        <div class="mb-6 bg-gray-50 rounded-lg p-4">
            <div class="flex items-start">
                <!-- Student avatar or initials -->
                <div class="flex-shrink-0">
                    <!--[if BLOCK]><![endif]--><?php if($selectedStudent->photo): ?>
                        <img src="<?php echo e(asset($selectedStudent->photo)); ?>" alt="<?php echo e($selectedStudent->first_name); ?>" class="h-12 w-12 rounded-full object-cover">
                    <?php else: ?>
                        <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-green-700 font-medium text-sm"><?php echo e(substr($selectedStudent->first_name, 0, 1)); ?><?php echo e(substr($selectedStudent->last_name, 0, 1)); ?></span>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Student details -->
                <div class="ml-4 flex-1">
                    <h4 class="text-base font-medium text-gray-900"><?php echo e($selectedStudent->first_name); ?> <?php echo e($selectedStudent->middle_name); ?> <?php echo e($selectedStudent->last_name); ?></h4>
                    <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Admission No:</span> <?php echo e($selectedStudent->adm_no); ?>

                        </div>
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Class:</span> <?php echo e($selectedStudent->my_class->name ?? 'N/A'); ?> - <?php echo e($selectedStudent->section->name ?? 'N/A'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Warning Card -->
        <div class="mb-6 bg-red-50 border border-red-100 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 pt-0.5">
                    <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Warning: Permanent Action</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>This action will permanently delete the following data:</p>
                        <ul class="list-disc pl-5 mt-1 space-y-1">
                            <li>Student personal information</li>
                            <li>Academic records and grades</li>
                            <li>Attendance history</li>
                            <li>Financial records (fees, payments)</li>
                            <li>Parent/guardian information</li>
                        </ul>
                        <p class="mt-2 font-semibold">This action cannot be undone.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Confirmation Checkbox -->
        <div class="mb-6">
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    wire:model.live="confirmDelete" 
                    id="confirmDelete" 
                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                >
                <label for="confirmDelete" class="ml-2 block text-sm text-gray-700">
                    I understand that this action is permanent and cannot be reversed.
                </label>
            </div>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['confirmDelete'];
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
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
            <button 
                wire:click="cancelDelete" 
                class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
            >
                Cancel
            </button>
            
            <button 
                wire:click="confirmDelete" 
                x-on:click="processing = true"
                x-bind:disabled="processing || !$wire.confirmDelete"
                class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span x-show="!processing">Delete Permanently</span>
                <span x-show="processing">Processing...</span>
                <span x-show="processing" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/delete-confirmation.blade.php ENDPATH**/ ?>