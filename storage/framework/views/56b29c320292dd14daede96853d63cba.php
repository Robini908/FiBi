<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="overflow-x-auto min-w-full">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <?php echo e($header ?? ''); ?>

            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php echo e($body ?? ''); ?>

            </tbody>
        </table>
    </div>
    
    <!--[if BLOCK]><![endif]--><?php if(isset($pagination)): ?>
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <?php echo e($pagination); ?>

    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/components/data-table.blade.php ENDPATH**/ ?>