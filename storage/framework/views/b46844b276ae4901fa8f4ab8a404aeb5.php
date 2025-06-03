<div>
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <!-- Header Section -->
        <?php echo $__env->make('livewire.attendance.partials.view-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <!--[if BLOCK]><![endif]--><?php if(!$classId || !$sectionId): ?>
            <!-- Class and Section Selector -->
            <div class="p-6">
                <div class="mb-4">
                    <h2 class="text-base font-medium text-gray-800">Select Class & Section</h2>
                    <p class="mt-1 text-sm text-gray-500">Choose a class and section to view attendance records</p>
                </div>
                
                <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="class-select" class="block text-sm font-medium text-gray-700">Class</label>
                            <span class="text-xs text-gray-500">Required</span>
                        </div>
                        <select id="class-select" wire:model.live="classId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select Class</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="section-select" class="block text-sm font-medium text-gray-700">Section</label>
                            <span class="text-xs text-gray-500">Required</span>
                        </div>
                        <select id="section-select" wire:model.live="sectionId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" <?php echo e(!$classId ? 'disabled' : ''); ?>>
                            <option value="">Select Section</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($section->id); ?>"><?php echo e($section->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>
                
                <!--[if BLOCK]><![endif]--><?php if($classId && count($sections) === 0): ?>
                    <div class="mt-4 rounded-md bg-yellow-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">No sections available</h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    <p>No sections found for the selected class. Please create sections first.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php else: ?>
            <!-- Filter and Stats Section -->
            <?php echo $__env->make('livewire.attendance.partials.view-filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            
            <!-- Records Table Section -->
            <?php echo $__env->make('livewire.attendance.partials.view-records-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    
    <!-- Details Modal -->
    <?php echo $__env->make('livewire.attendance.partials.view-details-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Delete Confirmation Modal -->
    <?php echo $__env->make('livewire.attendance.partials.view-delete-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('notify', param => {
            toastr[param.type](param.message);
        });
        
        // Initialize date picker
        flatpickr("#date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('startDate', selectedDates[0].toISOString().slice(0, 10));
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('endDate', selectedDates[1].toISOString().slice(0, 10));
                }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/view-attendance.blade.php ENDPATH**/ ?>