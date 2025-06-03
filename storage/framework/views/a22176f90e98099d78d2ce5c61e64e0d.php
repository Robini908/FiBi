

<?php $__env->startSection('page_title', 'Payment Vouchers'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('finance.payment-vouchers', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4064638319-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/pages/finance/payment-vouchers.blade.php ENDPATH**/ ?>