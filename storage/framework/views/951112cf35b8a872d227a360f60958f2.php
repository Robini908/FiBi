<!-- Fee Structure Component View -->
<div 
    x-data="{ 
        showFormModal: <?php if ((object) ('showModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'->value()); ?>')<?php echo e('showModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'); ?>')<?php endif; ?>.live, 
        showViewModal: false, 
        showDeleteModal: false
    }"
    x-on:open-form-modal.window="showFormModal = true"
    x-on:close-form-modal.window="showFormModal = false"
    x-on:open-view-modal.window="showViewModal = true"
    x-on:close-view-modal.window="showViewModal = false"
    x-on:open-delete-modal.window="showDeleteModal = true"
    x-on:close-delete-modal.window="showDeleteModal = false"
    class="py-4 px-4 md:px-6 space-y-6">

    <!-- Page Header -->
    <?php echo $__env->make('livewire.finance.partials.fee-structure-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Summary Cards -->
    <?php echo $__env->make('livewire.finance.partials.fee-structure-summary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Fee Structure Table -->
    <?php echo $__env->make('livewire.finance.partials.fee-structure-table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Modals -->
    <?php echo $__env->make('livewire.finance.partials.fee-structure-form-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('livewire.finance.partials.fee-structure-view-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('livewire.finance.partials.fee-structure-delete-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Toast Notifications -->
    
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/fee-structure.blade.php ENDPATH**/ ?>