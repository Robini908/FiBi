

<?php $__env->startSection('page_title', 'Finance Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('finance.dashboard', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1063193301-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/pages/finance/dashboard.blade.php ENDPATH**/ ?>