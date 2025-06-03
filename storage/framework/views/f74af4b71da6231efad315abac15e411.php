
<?php $__env->startSection('page_title', 'Student Promotions and Demotions'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('graduate-students', ['lazy' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2873903269-0', $__slots ?? [], get_defined_vars());

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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/pages/support_team/students/graduation.blade.php ENDPATH**/ ?>