<div x-data="{ showDebug: false }" class="container mx-auto py-6 px-4 sm:px-0">


    <!-- Main Content -->
    <div class="max-w-5xl mx-auto">
        <!-- Header with page title and action buttons -->
        <?php echo $__env->make('livewire.partials.settings.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Settings Navigation Tabs -->
        <?php echo $__env->make('livewire.partials.settings.tabs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Settings Content Area -->
        <div class="mt-6">
            <!--[if BLOCK]><![endif]--><?php if($editingSettings): ?>
                <!-- Edit Mode -->
                <?php echo $__env->make('livewire.partials.settings.edit-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <!-- View Mode -->
                <?php echo $__env->make('livewire.partials.settings.view-mode', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/super-admin/school-settings.blade.php ENDPATH**/ ?>