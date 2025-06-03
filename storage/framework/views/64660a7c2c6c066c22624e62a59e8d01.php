

<?php $__env->startSection('title', $title ?? 'School Settings'); ?>

<?php $__env->startSection('page_title', 'School Settings'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <?php if(isset($breadcrumbs)): ?>
        <?php if (isset($component)) { $__componentOriginal8cf0999d3b47e512090d9f940d848847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cf0999d3b47e512090d9f940d848847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.breadcrumbs','data' => ['items' => $breadcrumbs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('ui.breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($breadcrumbs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cf0999d3b47e512090d9f940d848847)): ?>
<?php $attributes = $__attributesOriginal8cf0999d3b47e512090d9f940d848847; ?>
<?php unset($__attributesOriginal8cf0999d3b47e512090d9f940d848847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cf0999d3b47e512090d9f940d848847)): ?>
<?php $component = $__componentOriginal8cf0999d3b47e512090d9f940d848847; ?>
<?php unset($__componentOriginal8cf0999d3b47e512090d9f940d848847); ?>
<?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('super-admin.school-settings', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2451201666-0', $__slots ?? [], get_defined_vars());

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
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/pages/super_admin/school-settings.blade.php ENDPATH**/ ?>