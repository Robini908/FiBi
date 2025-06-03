<div>
    <h2 class="text-lg font-medium text-gray-800 mb-2">Select Class & Section</h2>
    <p class="text-sm text-gray-600 mb-6">Select the class and section containing the students you want to transition.</p>
    
    <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Class Selection -->
            <div>
                <label for="selectedClass" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-school text-blue-500 mr-2"></i>
                    Select Class
                </label>
                <div class="relative mt-1">
                    <select wire:model.live="selectedClass" id="selectedClass" 
                            class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg transition-colors duration-200 bg-gray-50 hover:bg-white">
                        <option value="">-- Select Class --</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--[if BLOCK]><![endif]--><?php if($transitionType === 'promotion'): ?>
                                <?php
                                    // For promotion, only show classes that have a next class to promote to
                                    $hasNextClass = $classes->where('id', '>', $class->id)->count() > 0;
                                ?>
                                <!--[if BLOCK]><![endif]--><?php if($hasNextClass): ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php elseif($transitionType === 'demotion'): ?>
                                <?php
                                    // For demotion, only show classes that have a previous class to demote to
                                    $hasPreviousClass = $classes->where('id', '<', $class->id)->count() > 0;
                                ?>
                                <!--[if BLOCK]><![endif]--><?php if($hasPreviousClass): ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php elseif($transitionType === 'repetition'): ?>
                                <!-- For repetition, show all classes -->
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-600">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedClass'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                
                <!-- Informational messages for available class options -->
                <!--[if BLOCK]><![endif]--><?php if($transitionType === 'promotion'): ?>
                    <!--[if BLOCK]><![endif]--><?php if($classes->where('id', '>', $classes->max('id'))->count() === 0 && $classes->count() > 0): ?>
                        <div class="mt-3 flex items-start bg-amber-50 rounded-md p-3 border border-amber-200">
                            <i class="fas fa-info-circle text-amber-500 mt-0.5 mr-2"></i>
                            <div class="text-sm text-amber-700">
                                <span class="font-medium">Note:</span> Only classes with a next higher class are available for promotion.
                                <!--[if BLOCK]><![endif]--><?php if($classes->count() > 0 && $classes->max('id') > 0): ?>
                                    <span class="block mt-1"><?php echo e($classes->firstWhere('id', $classes->max('id'))->name); ?> is the highest class and cannot be selected for promotion.</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    <?php elseif($classes->count() === 0): ?>
                        <div class="mt-3 flex items-start bg-red-50 rounded-md p-3 border border-red-200">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                            <div class="text-sm text-red-700">
                                No classes found in the system. Please add classes before proceeding.
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php elseif($transitionType === 'demotion'): ?>
                    <!--[if BLOCK]><![endif]--><?php if($classes->where('id', '<', $classes->min('id'))->count() === 0 && $classes->count() > 0): ?>
                        <div class="mt-3 flex items-start bg-amber-50 rounded-md p-3 border border-amber-200">
                            <i class="fas fa-info-circle text-amber-500 mt-0.5 mr-2"></i>
                            <div class="text-sm text-amber-700">
                                <span class="font-medium">Note:</span> Only classes with a previous lower class are available for demotion.
                                <?php if($classes->count() > 0 && $classes->min('id') > 0): ?>
                                    <span class="block mt-1"><?php echo e($classes->firstWhere('id', $classes->min('id'))->name); ?> is the lowest class and cannot be selected for demotion.</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    <?php elseif($classes->count() === 0): ?>
                        <div class="mt-3 flex items-start bg-red-50 rounded-md p-3 border border-red-200">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                            <div class="text-sm text-red-700">
                                No classes found in the system. Please add classes before proceeding.
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Section Selection -->
            <div>
                <label for="selectedSection" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <i class="fas fa-users text-blue-500 mr-2"></i>
                    Select Section
                </label>
                <div class="relative mt-1">
                    <select wire:model.live="selectedSection" id="selectedSection" 
                            class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg transition-colors duration-200 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed bg-gray-50 hover:bg-white"
                            <?php echo e(!$selectedClass ? 'disabled' : ''); ?>>
                        <option value="">-- Select Section --</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>"><?php echo e($section->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-600">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedSection'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                
                <!-- Help message for section selection -->
                <!--[if BLOCK]><![endif]--><?php if(!$selectedClass): ?>
                    <div class="mt-3 flex items-start bg-blue-50 rounded-md p-3 border border-blue-200">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                        <p class="text-sm text-blue-700">
                            Please select a class first to view available sections.
                        </p>
                    </div>
                <?php elseif($selectedClass && $sections->isEmpty()): ?>
                    <div class="mt-3 flex items-start bg-amber-50 rounded-md p-3 border border-amber-200">
                        <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 mr-2"></i>
                        <p class="text-sm text-amber-700">
                            No sections found for the selected class. Please add sections to this class first.
                        </p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        
        <!-- Target Class Information Box - Enhanced with clearer information -->
        <!--[if BLOCK]><![endif]--><?php if($selectedClass && $transitionType): ?>
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-blue-700 mb-3 flex items-center">
                    <i class="fas fa-route mr-2"></i>
                    Transition Path
                </h3>
                
                <div class="p-2 bg-white rounded-md border border-blue-100">
                    <!--[if BLOCK]><![endif]--><?php if($transitionType === 'promotion'): ?>
                        <?php
                            $currentClass = $classes->firstWhere('id', $selectedClass);
                            $nextClass = $classes->where('id', '>', $selectedClass)->first();
                        ?>
                        
                        <!--[if BLOCK]><![endif]--><?php if($currentClass && $nextClass): ?>
                            <div class="flex items-center justify-center">
                                <div class="text-center px-4 py-2">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($currentClass->name); ?></span>
                                    <p class="text-xs text-gray-500 mt-1">Current Class</p>
                                </div>
                                
                                <div class="px-3">
                                    <i class="fas fa-long-arrow-alt-right text-emerald-500"></i>
                                </div>
                                
                                <div class="text-center px-4 py-2 bg-emerald-50 rounded-md border border-emerald-100">
                                    <span class="text-sm font-medium text-emerald-700"><?php echo e($nextClass->name); ?></span>
                                    <p class="text-xs text-emerald-600 mt-1">Target Class</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center justify-center">
                                <div class="text-center px-4 py-2">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($currentClass->name); ?></span>
                                    <p class="text-xs text-gray-500 mt-1">Current Class</p>
                                </div>
                                
                                <div class="px-3">
                                    <i class="fas fa-ban text-red-500"></i>
                                </div>
                                
                                <div class="text-center px-4 py-2 bg-red-50 rounded-md border border-red-100">
                                    <span class="text-sm font-medium text-red-700">No Higher Class</span>
                                    <p class="text-xs text-red-600 mt-1">Cannot Promote</p>
                                </div>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php elseif($transitionType === 'demotion'): ?>
                        <?php
                            $currentClass = $classes->firstWhere('id', $selectedClass);
                            $previousClass = $classes->where('id', '<', $selectedClass)->sortByDesc('id')->first();
                        ?>
                        
                        <!--[if BLOCK]><![endif]--><?php if($currentClass && $previousClass): ?>
                            <div class="flex items-center justify-center">
                                <div class="text-center px-4 py-2">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($currentClass->name); ?></span>
                                    <p class="text-xs text-gray-500 mt-1">Current Class</p>
                                </div>
                                
                                <div class="px-3">
                                    <i class="fas fa-long-arrow-alt-right text-red-500"></i>
                                </div>
                                
                                <div class="text-center px-4 py-2 bg-red-50 rounded-md border border-red-100">
                                    <span class="text-sm font-medium text-red-700"><?php echo e($previousClass->name); ?></span>
                                    <p class="text-xs text-red-600 mt-1">Target Class</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center justify-center">
                                <div class="text-center px-4 py-2">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($currentClass->name); ?></span>
                                    <p class="text-xs text-gray-500 mt-1">Current Class</p>
                                </div>
                                
                                <div class="px-3">
                                    <i class="fas fa-ban text-red-500"></i>
                                </div>
                                
                                <div class="text-center px-4 py-2 bg-red-50 rounded-md border border-red-100">
                                    <span class="text-sm font-medium text-red-700">No Lower Class</span>
                                    <p class="text-xs text-red-600 mt-1">Cannot Demote</p>
                                </div>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php elseif($transitionType === 'repetition'): ?>
                        <?php
                            $currentClass = $classes->firstWhere('id', $selectedClass);
                        ?>
                        
                        <!--[if BLOCK]><![endif]--><?php if($currentClass): ?>
                            <div class="flex items-center justify-center">
                                <div class="text-center px-4 py-2">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($currentClass->name); ?></span>
                                    <p class="text-xs text-gray-500 mt-1">Current Class</p>
                                </div>
                                
                                <div class="px-3">
                                    <i class="fas fa-sync-alt text-amber-500"></i>
                                </div>
                                
                                <div class="text-center px-4 py-2 bg-amber-50 rounded-md border border-amber-100">
                                    <span class="text-sm font-medium text-amber-700"><?php echo e($currentClass->name); ?></span>
                                    <p class="text-xs text-amber-600 mt-1">Same Class (Repeat)</p>
                                </div>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/promote-students/class-section-selector.blade.php ENDPATH**/ ?>