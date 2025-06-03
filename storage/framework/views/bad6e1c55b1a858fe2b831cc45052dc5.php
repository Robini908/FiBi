
<?php $__env->startSection('page_title', 'Exam Timetable Manager'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header bg-white">
        <div class="row align-items-center">
            <div class="col-8">
                <h4 class="card-title mb-0">Exam Timetable Manager</h4>
                <div class="small text-muted">Create and manage exam schedules for different classes</div>
            </div>
            <div class="col-4 text-right">
                <a href="<?php echo e(route('tt.index')); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Timetables
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('timetable.exam-timetable');

$__html = app('livewire')->mount($__name, $__params, 'lw-2003183189-0', $__slots ?? [], get_defined_vars());

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
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/pages/support_team/timetables/exam-timetable.blade.php ENDPATH**/ ?>