<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-white border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create New Suspension
        </h3>
    </div>
    
    <div class="p-6">
        <!-- Student Selection -->
        <div class="mb-6">
            <label for="studentId" class="block text-sm font-medium text-gray-700 mb-1">Select Student</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <select wire:model.live="studentId" 
                        id="studentId" 
                        class="pl-10 focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <option value="">Select a student</option>
                    <!--[if BLOCK]><![endif]--><?php if(isset($students) && count($students) > 0): ?>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($student->id); ?>"><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?> (<?php echo e($student->adm_no); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                        <option disabled>No active students available</option>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['studentId'];
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
        
        <!-- Student Info Preview (only shown when a student is selected) -->
        <div x-data="{}" x-show="$wire.studentId" x-cloak class="mb-6 bg-gray-50 rounded-lg p-4">
            <div class="flex items-start">
                <!-- Student avatar or initials -->
                <div class="flex-shrink-0">
                    <!--[if BLOCK]><![endif]--><?php if($selectedStudent && $selectedStudent->photo): ?>
                        <img src="<?php echo e(asset($selectedStudent->photo)); ?>" alt="<?php echo e($selectedStudent->first_name); ?>" class="h-12 w-12 rounded-full object-cover border-2 border-gray-100 shadow-sm">
                    <?php elseif($selectedStudent): ?>
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center shadow-sm border-2 border-gray-100">
                            <span class="text-green-700 font-medium text-sm">
                                <?php echo e(substr($selectedStudent->first_name, 0, 1) . substr($selectedStudent->last_name, 0, 1)); ?>

                            </span>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Student details -->
                <!--[if BLOCK]><![endif]--><?php if($selectedStudent): ?>
                <div class="ml-4 flex-1">
                    <h4 class="text-base font-medium text-gray-900">
                        <?php echo e($selectedStudent->first_name); ?> <?php echo e($selectedStudent->last_name); ?>

                    </h4>
                    <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Admission No:</span> <?php echo e($selectedStudent->adm_no); ?>

                        </div>
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Class:</span> 
                            <?php echo e($selectedStudent->my_class->name ?? 'N/A'); ?> - <?php echo e($selectedStudent->section->name ?? 'N/A'); ?>

                        </div>
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Status:</span> 
                            <span class="<?php echo e($selectedStudent->status === 'active' ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e(ucfirst($selectedStudent->status)); ?>

                            </span>
                        </div>
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Gender:</span> <?php echo e(ucfirst($selectedStudent->gender)); ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <!-- Suspension Type -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Suspension Type</label>
            <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="relative bg-white rounded-lg border border-gray-200 p-4 cursor-pointer focus-within:ring-2 focus-within:ring-green-500"
                     :class="{'border-green-500 bg-green-50': $wire.suspensionType === 'temporary'}"
                     x-data="{}"
                     @click="$wire.set('suspensionType', 'temporary')">
                    <div class="flex">
                        <div class="flex items-center h-5">
                            <input type="radio" 
                                   wire:model.live="suspensionType" 
                                   id="suspension-type-temporary" 
                                   value="temporary"
                                   class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="suspension-type-temporary" class="font-medium text-gray-700">Temporary</label>
                            <p class="text-gray-500">Suspension for a specific time period with a defined end date.</p>
                        </div>
                    </div>
                </div>
                
                <div class="relative bg-white rounded-lg border border-gray-200 p-4 cursor-pointer focus-within:ring-2 focus-within:ring-green-500"
                     :class="{'border-green-500 bg-green-50': $wire.suspensionType === 'indefinite'}"
                     x-data="{}"
                     @click="$wire.set('suspensionType', 'indefinite')">
                    <div class="flex">
                        <div class="flex items-center h-5">
                            <input type="radio" 
                                   wire:model.live="suspensionType" 
                                   id="suspension-type-indefinite" 
                                   value="indefinite"
                                   class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="suspension-type-indefinite" class="font-medium text-gray-700">Indefinite</label>
                            <p class="text-gray-500">Suspension without a defined end date, pending further review.</p>
                        </div>
                    </div>
                </div>
            </div>
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

        <!-- Start Date -->
        <div class="mb-6">
            <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <input type="date" 
                       wire:model.live="startDate" 
                       id="startDate"
                       class="pl-10 focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                       min="<?php echo e(now()->toDateString()); ?>">
            </div>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['startDate'];
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
        
        <!-- End Date (only for temporary suspension) -->
        <div class="mb-6" x-data="{}" x-show="$wire.suspensionType === 'temporary'">
            <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <input type="date" 
                       wire:model.live="endDate" 
                       id="endDate"
                       class="pl-10 focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                       min="<?php echo e(now()->addDay()->toDateString()); ?>">
            </div>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['endDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            <p class="mt-1 text-xs text-gray-500">
                End date must be after the start date.
            </p>
        </div>
        
        <!-- Reason -->
        <div class="mb-6">
            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Suspension</label>
            <div class="mt-1">
                <textarea id="reason" 
                          wire:model.live="reason" 
                          rows="4" 
                          class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          placeholder="Provide detailed information about why the student is being suspended..."></textarea>
            </div>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['reason'];
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
        
        <!-- Notify Parent Checkbox -->
        <div class="mb-6">
            <div class="relative flex items-start">
                <div class="flex items-center h-5">
                    <input id="notifyParent" 
                           wire:model.live="notifyParent" 
                           type="checkbox" 
                           class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="notifyParent" class="font-medium text-gray-700">Notify Parent/Guardian</label>
                    <p class="text-gray-500">Send an email notification to the parent/guardian with the suspension details.</p>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <button wire:click="$set('isCreatingSuspension', false)" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <svg class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </button>
            
            <button wire:click="createSuspension" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Create Suspension
                <span wire:loading wire:target="createSuspension" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/suspensions/new-suspension-form.blade.php ENDPATH**/ ?>