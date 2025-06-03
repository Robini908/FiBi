

<?php $__env->startSection('page_title', 'Messages'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Include the Livewire MessageManager Component -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('message-manager', [
        'userId' => request()->query('userId'),
        'filter' => request()->query('filter'),
        'view' => request()->query('view')
    ]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3352281726-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/messages/index.blade.php ENDPATH**/ ?>