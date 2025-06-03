

<?php $__env->startSection('page_title', 'Staff Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-6 px-4">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Staff Management</h1>
    
    <!-- Breadcrumbs -->
    <div class="flex items-center text-sm text-gray-500 mb-6 space-x-1">
        <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-green-600 transition-colors">Dashboard</a>
        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-600 font-medium">Staff Management</span>
    </div>
    
    <!-- Livewire Staff Management Component -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('staff.staff-list');

$__html = app('livewire')->mount($__name, $__params, 'lw-1054399812-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/pages/staff/manage.blade.php ENDPATH**/ ?>