<!-- Break & Lunch Settings -->
<div @click.stop class="space-y-5">
    <!-- Break Settings -->
    <div class="py-3">
        <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Break Settings
        </h4>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Break After Lessons -->
            <div>
                <label for="break_after_lessons" class="block text-xs font-medium text-gray-700 mb-1">Schedule Break After</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <div class="flex">
                        <input type="number" id="break_after_lessons" wire:model.live="autoGenerateForm.break_after_lessons" min="1" max="10"
                               class="pl-7 block w-full rounded-l-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                        <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-gray-200 bg-gray-50 text-gray-500 text-xs">
                            Lessons
                        </span>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['autoGenerateForm.break_after_lessons'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Break Duration -->
            <div>
                <label for="break_duration" class="block text-xs font-medium text-gray-700 mb-1">Break Duration</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex">
                        <input type="number" id="break_duration" wire:model.live="autoGenerateForm.break_duration" min="5" max="30"
                               class="pl-7 block w-full rounded-l-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                        <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-gray-200 bg-gray-50 text-gray-500 text-xs">
                            Minutes
                        </span>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['autoGenerateForm.break_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
    
    <!-- Lunch Settings -->
    <div class="py-3 border-t border-gray-100">
        <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-1.5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Lunch Settings
        </h4>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Lunch Time -->
            <div>
                <label for="lunch_time" class="block text-xs font-medium text-gray-700 mb-1">Lunch Time</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input type="time" id="lunch_time" wire:model.live="autoGenerateForm.lunch_time"
                           class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                </div>
                <p class="mt-1 text-xs text-gray-500">Default: 12:30 PM</p>
            </div>
            
            <!-- Lunch Duration -->
            <div>
                <label for="lunch_duration" class="block text-xs font-medium text-gray-700 mb-1">Lunch Duration</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex">
                        <input type="number" id="lunch_duration" wire:model.live="autoGenerateForm.lunch_duration" min="20" max="90"
                               class="pl-7 block w-full rounded-l-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                        <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-gray-200 bg-gray-50 text-gray-500 text-xs">
                            Minutes
                        </span>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['autoGenerateForm.lunch_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
    
    <!-- Games & Activities Settings -->
    <div class="py-3 border-t border-gray-100">
        <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-1.5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Games & Activities Settings
        </h4>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Games & Activities Time -->
            <div>
                <label for="extracurricular_start_time" class="block text-xs font-medium text-gray-700 mb-1">Games & Activities Start Time</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input type="time" id="extracurricular_start_time" wire:model.live="autoGenerateForm.extracurricular_start_time"
                           class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                </div>
                <p class="mt-1 text-xs text-gray-500">Default: 3:30 PM</p>
            </div>
            
            <!-- Games & Activities End Time -->
            <div>
                <label for="extracurricular_end_time" class="block text-xs font-medium text-gray-700 mb-1">Games & Activities End Time</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input type="time" id="extracurricular_end_time" wire:model.live="autoGenerateForm.extracurricular_end_time"
                           class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                </div>
                <p class="mt-1 text-xs text-gray-500">Default: 5:00 PM</p>
            </div>
        </div>
    </div>
    
    <!-- Movement Time Settings -->
    <div class="py-3 border-t border-gray-100">
        <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
            Movement Time Settings
        </h4>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Movement Time Duration -->
            <div>
                <label for="transition_duration" class="block text-xs font-medium text-gray-700 mb-1">Movement Time Duration</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex">
                        <input type="number" id="transition_duration" wire:model.live="autoGenerateForm.transition_duration" min="0" max="15"
                               class="pl-7 block w-full rounded-l-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                        <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-gray-200 bg-gray-50 text-gray-500 text-xs">
                            Minutes
                        </span>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['autoGenerateForm.transition_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-500">Movement time will be added between activities to allow students and teachers to transition between classes.</p>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/time-slots-advanced-settings.blade.php ENDPATH**/ ?>