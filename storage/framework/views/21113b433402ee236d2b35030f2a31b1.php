
<?php $__env->startSection('page_title', 'Manage Subjects'); ?>
<?php $__env->startSection('content'); ?>

<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Content Container -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('all-subject-management-actions', ['lazy' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3548403594-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/pages/support_team/subjects/index.blade.php ENDPATH**/ ?>