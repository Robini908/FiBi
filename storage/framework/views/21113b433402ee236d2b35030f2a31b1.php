
<?php $__env->startSection('page_title', 'Manage Subjects'); ?>
<?php $__env->startSection('content'); ?>

<div class="min-h-screen bg-gray-50 py-4">
    <div class="w-full px-2 sm:px-4 lg:px-6">
        <!-- Main Content Container -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-secondary-200">
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