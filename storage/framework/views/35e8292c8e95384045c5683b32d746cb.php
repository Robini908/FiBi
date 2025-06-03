<div class="space-y-4 bg-white rounded-lg shadow overflow-hidden">
    <!--[if BLOCK]><![endif]--><?php if(isset($this->settings()[$activeGroup])): ?>
        <div class="overflow-hidden">
            <div class="divide-y divide-gray-100">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->settings()[$activeGroup]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="py-4 px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <div class="flex-1 mb-2 sm:mb-0">
                                <h3 class="text-sm font-medium text-gray-900"><?php echo e($setting->display_name); ?></h3>
                                <!--[if BLOCK]><![endif]--><?php if($setting->description): ?>
                                    <p class="text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div class="text-sm font-medium">
                                <!--[if BLOCK]><![endif]--><?php switch($setting->type):
                                    case ('boolean'): ?>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium <?php echo e($setting->value == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e($setting->value == 1 ? 'Enabled' : 'Disabled'); ?>

                                        </span>
                                        <?php break; ?>
                                        
                                    <?php case ('date'): ?>
                                        <span class="text-gray-800">
                                            <?php echo e(\Carbon\Carbon::parse($setting->value)->format('d M, Y')); ?>

                                        </span>
                                        <?php break; ?>
                                        
                                    <?php case ('academic_year'): ?>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <?php echo e(str_replace(' to ', ' - ', $setting->value)); ?>

                                        </span>
                                        <?php break; ?>
                                        
                                    <?php case ('file'): ?>
                                        <!--[if BLOCK]><![endif]--><?php if(strpos($setting->key, 'logo') !== false): ?>
                                            <div class="flex items-center">
                                                <img src="<?php echo e(asset($setting->value)); ?>" alt="Logo" class="h-8 w-auto object-contain">
                                                <span class="text-xs text-gray-500 ml-2 truncate max-w-xs"><?php echo e($setting->value); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-800 truncate max-w-xs"><?php echo e($setting->value); ?></span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <?php break; ?>
                                        
                                    <?php case ('select'): ?>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-gray-100 text-gray-800">
                                            <?php echo e($setting->value); ?>

                                        </span>
                                        <?php break; ?>
                                        
                                    <?php default: ?>
                                        <span class="text-gray-800"><?php echo e($setting->value); ?></span>
                                <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    <?php else: ?>
        <div class="p-6 flex items-center justify-center h-32">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900"><?php echo e(__('No settings found')); ?></h3>
                <p class="mt-1 text-sm text-gray-500"><?php echo e(__('No settings found for this group.')); ?></p>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/settings/view-mode.blade.php ENDPATH**/ ?>