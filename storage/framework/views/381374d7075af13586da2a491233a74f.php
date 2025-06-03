<?php $__env->startSection('page_title', 'My Account'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
    <div class="card-body p-0">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user-profile', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1511765602-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                        </div>
                                    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/pages/support_team/my_account.blade.php ENDPATH**/ ?>