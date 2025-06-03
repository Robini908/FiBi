<div class="bg-white rounded-lg shadow-sm overflow-hidden" x-data="{ processing: false }">
    <!-- Header with accent -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <?php echo e($selectedStudent->status === 'suspended' ? 'Reinstate Student' : 'Suspend Student'); ?>

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
                    <h4 class="text-base font-medium text-gray-900"><?php echo e($selectedStudent->first_name); ?> <?php echo e($selectedStudent->last_name); ?></h4>
                    <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Class:</span> <?php echo e($selectedStudent->my_class->name ?? 'N/A'); ?> - <?php echo e($selectedStudent->section->name ?? 'N/A'); ?>

                        </div>
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Admission No:</span> <?php echo e($selectedStudent->adm_no); ?>

                        </div>
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Admitted:</span> <?php echo e($selectedStudent->year_admitted); ?>

                        </div>
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Status:</span> 
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($selectedStudent->status === 'suspended' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800'); ?>">
                                <?php echo e(ucfirst($selectedStudent->status ?? 'Active')); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--[if BLOCK]><![endif]--><?php if($selectedStudent->status === 'suspended'): ?>
            <!-- Reinstatement Card -->
            <div class="mb-6 bg-green-50 border border-green-100 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            Are you sure you want to reinstate this student? This will restore their normal access and privileges.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:justify-center gap-3">
                <button 
                    wire:click="reinstateStudent" 
                    x-on:click="processing = true"
                    x-bind:disabled="processing"
                    class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="!processing">Reinstate Student</span>
                    <span x-show="processing">Processing...</span>
                    <span x-show="processing" class="ml-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                
                <button 
                    wire:click="closeAction" 
                    class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                >
                        Cancel
                    </button>
            </div>
        <?php else: ?>
            <!-- Suspension Card -->
            <div class="space-y-6">
                <div class="mb-6 bg-amber-50 border border-amber-100 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700">
                                Suspension is a temporary disciplinary measure. Please provide details below.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Suspension Form -->
                <div class="grid grid-cols-1 gap-6">
                <!-- Reason for Suspension -->
                <div>
                        <label for="suspensionReason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Suspension</label>
                        <div>
                            <textarea 
                                wire:model.live="suspensionReason" 
                                id="suspensionReason" 
                                rows="3" 
                                placeholder="Provide detailed reason for the suspension..."
                                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            ></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['suspensionReason'];
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
                </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type of Suspension -->
                <div>
                            <label for="suspensionType" class="block text-sm font-medium text-gray-700 mb-1">Type of Suspension</label>
                            <div>
                                <select 
                                    wire:model.live="suspensionType" 
                                    id="suspensionType" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                >
                            <option value="">Select Type</option>
                            <option value="dismissal">Dismissal</option>
                            <option value="withdrawal">Withdrawal</option>
                            <option value="permanent_exclusion">Permanent Exclusion</option>
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['suspensionType'];
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
                </div>

                <!-- Duration of Suspension -->
                <div>
                            <label for="suspensionEndDate" class="block text-sm font-medium text-gray-700 mb-1">Suspension End Date</label>
                            <div>
                                <input 
                                    type="date" 
                                    wire:model.live="suspensionEndDate" 
                                    id="suspensionEndDate" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                >
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['suspensionEndDate'];
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
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between gap-3 pt-4">
                    <button 
                        wire:click="confirmStudentSuspension" 
                        x-on:click="processing = true"
                        x-bind:disabled="processing"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span x-show="!processing">Suspend Student</span>
                        <span x-show="processing">Processing...</span>
                        <span x-show="processing" class="ml-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                    
                    <button 
                        wire:click="closeAction" 
                        class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/student-suspension.blade.php ENDPATH**/ ?>