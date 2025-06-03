<!-- Academic Details Section -->
<div class="mb-6 bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="flex justify-between items-center p-4 bg-gray-50 border-b border-gray-100">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Academic Details
        </h3>
        <button 
            wire:click="$set('editAcademicDetails', <?php echo e(!$editAcademicDetails); ?>)" 
            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($editAcademicDetails ? 'M6 18L18 6M6 6l12 12' : 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'); ?>" />
            </svg>
            <?php echo e($editAcademicDetails ? 'Cancel' : 'Edit'); ?>

        </button>
    </div>
    <div class="p-5">
        <!--[if BLOCK]><![endif]--><?php if($editAcademicDetails): ?>
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Class -->
                    <div>
                        <label for="my_class_id" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                        <select wire:model.live="my_class_id" id="my_class_id" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Class</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['my_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message ?? 'This field is required.'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Section -->
                    <div>
                        <label for="section_id" class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                        <select wire:model.live="section_id" id="section_id" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Section</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($section->id); ?>"><?php echo e($section->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message ?? 'This field is required.'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Year Admitted -->
                    <div>
                        <label for="year_admitted" class="block text-sm font-medium text-gray-700 mb-1">Year Admitted</label>
                        <input type="number" wire:model="year_admitted" id="year_admitted" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['year_admitted'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message ?? 'Please enter a valid year.'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Dormitory -->
                    <div>
                        <label for="dorm_id" class="block text-sm font-medium text-gray-700 mb-1">Dormitory</label>
                        <select wire:model="dorm_id" id="dorm_id" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Dormitory</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $dorms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dorm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dorm->id); ?>"><?php echo e($dorm->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['dorm_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message ?? 'Please select a valid dormitory.'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- UPI Number -->
                    <div>
                        <label for="upi_number" class="block text-sm font-medium text-gray-700 mb-1">UPI Number</label>
                        <input type="text" wire:model="upi_number" id="upi_number" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['upi_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message ?? 'Please enter a valid UPI number.'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Admission Number -->
                    <div>
                        <label for="adm_no" class="block text-sm font-medium text-gray-700 mb-1">Admission Number</label>
                        <input type="text" wire:model="adm_no" id="adm_no" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['adm_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message ?? 'Please enter a valid admission number.'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- KCPE Marks -->
                    <div>
                        <label for="kcpe" class="block text-sm font-medium text-gray-700 mb-1">KCPE Marks</label>
                        <input type="number" wire:model="kcpe" id="kcpe" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['kcpe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message ?? 'Please enter valid KCPE marks.'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                
                <div class="mt-6 flex items-center justify-start space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Academic Details
                    </button>
                    <button type="button" wire:click="$set('editAcademicDetails', false)" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cancel
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Class</p>
                    <p class="text-sm text-gray-900"><?php echo e($student->myClass->name ?? 'Not assigned'); ?></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Section</p>
                    <p class="text-sm text-gray-900"><?php echo e($student->section->name ?? 'Not assigned'); ?></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Year Admitted</p>
                    <p class="text-sm text-gray-900"><?php echo e($year_admitted ?: 'Not provided'); ?></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Dormitory</p>
                    <p class="text-sm text-gray-900"><?php echo e($student->dorm->name ?? 'Not assigned'); ?></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">UPI Number</p>
                    <p class="text-sm text-gray-900"><?php echo e($upi_number ?: 'Not provided'); ?></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Admission Number</p>
                    <p class="text-sm text-gray-900"><?php echo e($adm_no ?: 'Not provided'); ?></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">KCPE Marks</p>
                    <p class="text-sm text-gray-900"><?php echo e($kcpe ?: 'Not provided'); ?></p>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/academic-details.blade.php ENDPATH**/ ?>