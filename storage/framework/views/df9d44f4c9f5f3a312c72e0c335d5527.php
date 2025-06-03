<form wire:submit.prevent="updateSettings" class="space-y-6">
    <div x-data="{ focusedField: null }" class="space-y-6 bg-white rounded-lg shadow overflow-hidden">
        <!--[if BLOCK]><![endif]--><?php if(isset($this->settings()[$activeGroup])): ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->settings()[$activeGroup]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="py-4 px-6 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <div class="flex-1 mb-3 sm:mb-0">
                            <label for="<?php echo e($setting->key); ?>" class="block text-sm font-medium text-gray-700">
                                <?php echo e($setting->display_name); ?>

                            </label>
                            <!--[if BLOCK]><![endif]--><?php if($setting->description): ?>
                                <p class="text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <div class="sm:w-80">
                            <!--[if BLOCK]><![endif]--><?php switch($setting->type):
                                case ('textarea'): ?>
                                    <div 
                                        @focusin="focusedField = '<?php echo e($setting->key); ?>'" 
                                        @focusout="focusedField = null"
                                        :class="{'ring-2 ring-blue-500 ring-opacity-50': focusedField === '<?php echo e($setting->key); ?>'}"
                                        class="relative transition-all duration-150 rounded-md shadow-sm"
                                    >
                                        <textarea 
                                            id="<?php echo e($setting->key); ?>" 
                                            wire:model="formValues.<?php echo e($setting->key); ?>" 
                                            rows="3" 
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-0 focus:border-blue-500"
                                        ></textarea>
                                    </div>
                                    <?php break; ?>

                                <?php case ('boolean'): ?>
                                    <div class="flex items-center">
                                        <button 
                                            type="button" 
                                            wire:click="$set('formValues.<?php echo e($setting->key); ?>', '<?php echo e($formValues[$setting->key] == '1' ? '0' : '1'); ?>')"
                                            class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 <?php echo e($formValues[$setting->key] == '1' ? 'bg-blue-600' : 'bg-gray-200'); ?>"
                                            role="switch" 
                                            aria-checked="<?php echo e($formValues[$setting->key] == '1' ? 'true' : 'false'); ?>"
                                        >
                                            <span 
                                                aria-hidden="true" 
                                                class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 <?php echo e($formValues[$setting->key] == '1' ? 'translate-x-5' : 'translate-x-0'); ?>"
                                            ></span>
                                        </button>
                                        <span class="ml-2 text-sm text-gray-700">
                                            <?php echo e($formValues[$setting->key] == '1' ? 'Enabled' : 'Disabled'); ?>

                                        </span>
                                    </div>
                                    <?php break; ?>

                                <?php case ('select'): ?>
                                    <div 
                                        @focusin="focusedField = '<?php echo e($setting->key); ?>'" 
                                        @focusout="focusedField = null"
                                        :class="{'ring-2 ring-blue-500 ring-opacity-50': focusedField === '<?php echo e($setting->key); ?>'}"
                                        class="relative transition-all duration-150 rounded-md shadow-sm"
                                    >
                                        <select 
                                            id="<?php echo e($setting->key); ?>" 
                                            wire:model="formValues.<?php echo e($setting->key); ?>" 
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-0 focus:border-blue-500"
                                        >
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $setting->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($option); ?>"><?php echo e($option); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                    </div>
                                    <?php break; ?>

                                <?php case ('date'): ?>
                                    <div 
                                        @focusin="focusedField = '<?php echo e($setting->key); ?>'" 
                                        @focusout="focusedField = null"
                                        :class="{'ring-2 ring-blue-500 ring-opacity-50': focusedField === '<?php echo e($setting->key); ?>'}"
                                        class="relative transition-all duration-150 rounded-md shadow-sm"
                                        x-data="{}"
                                    >
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input 
                                            x-init="flatpickr($el, {dateFormat: 'Y-m-d'})"
                                            type="text" 
                                            id="<?php echo e($setting->key); ?>" 
                                            wire:model="formValues.<?php echo e($setting->key); ?>" 
                                            class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-0 focus:border-blue-500"
                                        >
                                    </div>
                                    <?php break; ?>

                                <?php case ('academic_year'): ?>
                                    <div 
                                        @focusin="focusedField = '<?php echo e($setting->key); ?>'" 
                                        @focusout="focusedField = null"
                                        :class="{'ring-2 ring-blue-500 ring-opacity-50': focusedField === '<?php echo e($setting->key); ?>'}"
                                        class="relative transition-all duration-150 rounded-md shadow-sm"
                                        x-data="{}"
                                    >
                                        <select 
                                            id="<?php echo e($setting->key); ?>" 
                                            wire:model="formValues.<?php echo e($setting->key); ?>" 
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-0 focus:border-blue-500"
                                        >
                                            <?php
                                                $currentYear = date('Y');
                                                $startYear = $currentYear - 5;
                                                $endYear = $currentYear + 5;
                                            ?>
                                            
                                            <!--[if BLOCK]><![endif]--><?php for($year = $startYear; $year <= $endYear; $year++): ?>
                                                <option value="<?php echo e($year); ?> to <?php echo e($year + 1); ?>"><?php echo e($year); ?> - <?php echo e($year + 1); ?></option>
                                            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                    </div>
                                    <?php break; ?>

                                <?php case ('number'): ?>
                                    <div 
                                        @focusin="focusedField = '<?php echo e($setting->key); ?>'" 
                                        @focusout="focusedField = null"
                                        :class="{'ring-2 ring-blue-500 ring-opacity-50': focusedField === '<?php echo e($setting->key); ?>'}"
                                        class="relative transition-all duration-150 rounded-md shadow-sm"
                                    >
                                        <input 
                                            type="number" 
                                            id="<?php echo e($setting->key); ?>" 
                                            wire:model="formValues.<?php echo e($setting->key); ?>" 
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-0 focus:border-blue-500"
                                        >
                                    </div>
                                    <?php break; ?>

                                <?php case ('file'): ?>
                                    <div 
                                        @focusin="focusedField = '<?php echo e($setting->key); ?>'" 
                                        @focusout="focusedField = null"
                                        :class="{'ring-2 ring-blue-500 ring-opacity-50': focusedField === '<?php echo e($setting->key); ?>'}"
                                        class="relative transition-all duration-150 rounded-md shadow-sm"
                                    >
                                        <div class="flex">
                                            <input 
                                                type="text" 
                                                id="<?php echo e($setting->key); ?>" 
                                                wire:model="formValues.<?php echo e($setting->key); ?>" 
                                                class="block w-full border-gray-300 rounded-l-md shadow-sm focus:ring-0 focus:border-blue-500"
                                            >
                                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <?php break; ?>
                                    
                                <?php default: ?>
                                    <div 
                                        @focusin="focusedField = '<?php echo e($setting->key); ?>'" 
                                        @focusout="focusedField = null"
                                        :class="{'ring-2 ring-blue-500 ring-opacity-50': focusedField === '<?php echo e($setting->key); ?>'}"
                                        class="relative transition-all duration-150 rounded-md shadow-sm"
                                    >
                                        <input 
                                            type="text" 
                                            id="<?php echo e($setting->key); ?>" 
                                            wire:model="formValues.<?php echo e($setting->key); ?>" 
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-0 focus:border-blue-500"
                                        >
                                    </div>
                            <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['formValues.' . $setting->key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        <?php else: ?>
            <div class="p-6 flex items-center justify-center h-32">
                <p class="text-sm text-gray-500"><?php echo e(__('No settings found for this group.')); ?></p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="flex justify-end space-x-3 pt-4">
        <button 
            type="button" 
            wire:click="cancelEditing" 
            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm"
        >
            <?php echo e(__('Cancel')); ?>

        </button>
        <button 
            type="submit" 
            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm"
        >
            <?php echo e(__('Save Settings')); ?>

        </button>
    </div>
</form> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/settings/edit-form.blade.php ENDPATH**/ ?>