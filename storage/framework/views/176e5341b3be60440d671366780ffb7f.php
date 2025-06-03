<div x-data="{ activeTab: 'students', showFilters: false }" class="bg-gray-50 min-h-screen p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Back button - shown when in any action view -->
        <!--[if BLOCK]><![endif]--><?php if($isEditingStudent || $isViewingDetails || $isDeleting || $isApproving || $isSuspendingStudent || $isExpellingStudent || $isAddingStudent): ?>
            <?php echo $__env->make('livewire.partials.students.back-button', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Main content area -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!--[if BLOCK]><![endif]--><?php if(!($isEditingStudent || $isViewingDetails || $isDeleting || $isApproving || $isSuspendingStudent || $isExpellingStudent || $isAddingStudent)): ?>
            <!-- Header -->
                <?php echo $__env->make('livewire.partials.students.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <!-- Advanced Filters -->
                <?php echo $__env->make('livewire.partials.students.advanced-filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <!-- Quick Search -->
                <?php echo $__env->make('livewire.partials.students.quick-search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <!-- Student List -->
                <?php echo $__env->make('livewire.partials.students.student-list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <!-- Pagination -->
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                    <?php echo e($students->links()); ?>

                </div>
            <?php elseif($isAddingStudent): ?>
                <!-- Admit Student Form -->
                <?php echo $__env->make('livewire.admit-student', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($isEditingStudent): ?>
                <!-- Edit Student View -->
                <?php echo $__env->make('livewire.partials.students.edit-student', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($isViewingDetails && $selectedStudent): ?>
                <!-- Send Email View -->
                <?php echo $__env->make('livewire.partials.students.send-email', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($isDeleting): ?>
                <!-- Delete Confirmation View -->
                <?php echo $__env->make('livewire.partials.students.delete-confirmation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($isExpellingStudent): ?>
                <!-- Student Expulsion View -->
                <?php echo $__env->make('livewire.partials.students.student-expulsion', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($isSuspendingStudent): ?>
                <!-- Student Suspension View -->
                <?php echo $__env->make('livewire.partials.students.student-suspension', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php elseif($isApproving): ?>
                <!-- Student Approval View -->
                <?php echo $__env->make('livewire.partials.students.student-approval', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/manage-students.blade.php ENDPATH**/ ?>