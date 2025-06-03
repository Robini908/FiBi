
<?php $__env->startSection('page_title', 'Timetable Consolidator'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header bg-white">
        <div class="row">
            <div class="col-md-6">
                <h4 class="card-title"><i class="icon-calendar3"></i> Timetable Consolidator</h4>
            </div>
            <div class="col-md-6">
                <div class="float-md-right">
                    <a href="<?php echo e(route('tt.index')); ?>" class="btn btn-danger">
                        <i class="icon-arrow-left12"></i> Back to Timetables
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <p>This tool allows you to consolidate multiple timetables into a single view. You can select timetables from the same class across different academic sessions or from all classes to generate a consolidated timetable.</p>
            </div>
        </div>

        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('timetable.timetable-consolidator', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-541483332-0', $__slots ?? [], get_defined_vars());

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
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/pages/support_team/timetables/consolidator.blade.php ENDPATH**/ ?>