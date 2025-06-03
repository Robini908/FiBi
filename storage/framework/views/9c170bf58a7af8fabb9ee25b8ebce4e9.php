<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'title' => null,
    'subtitle' => null,
    'elevated' => true,
    'bordered' => false,
    'rounded' => 'md',
    'padding' => 'normal',
    'headerClass' => '',
    'bodyClass' => '',
    'footerClass' => '',
    'actions' => null,
    'hasHeader' => true
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'title' => null,
    'subtitle' => null,
    'elevated' => true,
    'bordered' => false,
    'rounded' => 'md',
    'padding' => 'normal',
    'headerClass' => '',
    'bodyClass' => '',
    'footerClass' => '',
    'actions' => null,
    'hasHeader' => true
]); ?>
<?php foreach (array_filter(([
    'title' => null,
    'subtitle' => null,
    'elevated' => true,
    'bordered' => false,
    'rounded' => 'md',
    'padding' => 'normal',
    'headerClass' => '',
    'bodyClass' => '',
    'footerClass' => '',
    'actions' => null,
    'hasHeader' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $elevationClasses = $elevated ? 'shadow-md hover:shadow-lg transition-shadow duration-300' : '';
    $borderClasses = $bordered ? 'border border-gray-200' : '';
    
    $roundedSizes = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full'
    ];
    
    $roundedClass = $roundedSizes[$rounded] ?? $roundedSizes['md'];
    
    $paddingSizes = [
        'none' => 'p-0',
        'tight' => 'p-3',
        'normal' => 'p-4',
        'loose' => 'p-6',
        'extra' => 'p-8'
    ];
    
    $bodyPadding = $paddingSizes[$padding] ?? $paddingSizes['normal'];
    
    // Adjust header padding based on body padding
    $headerPadding = match($padding) {
        'none' => 'px-0 pt-0 pb-0',
        'tight' => 'px-3 pt-3 pb-2',
        'normal' => 'px-4 pt-4 pb-2',
        'loose' => 'px-6 pt-6 pb-3',
        'extra' => 'px-8 pt-8 pb-4',
        default => 'px-4 pt-4 pb-2'
    };
    
    // Adjust footer padding based on body padding
    $footerPadding = match($padding) {
        'none' => 'px-0 pt-0 pb-0',
        'tight' => 'px-3 pt-2 pb-3',
        'normal' => 'px-4 pt-2 pb-4',
        'loose' => 'px-6 pt-3 pb-6',
        'extra' => 'px-8 pt-4 pb-8',
        default => 'px-4 pt-2 pb-4'
    };
?>

<div <?php echo e($attributes->merge(['class' => "bg-white $elevationClasses $borderClasses $roundedClass overflow-hidden"])); ?>>
    <?php if($title && $hasHeader): ?>
        <div class="<?php echo e($headerPadding); ?> <?php echo e($headerClass); ?> border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900"><?php echo e($title); ?></h3>
                    <?php if($subtitle): ?>
                        <p class="mt-1 text-sm text-gray-500"><?php echo e($subtitle); ?></p>
                    <?php endif; ?>
                </div>
                <?php if($actions): ?>
                    <div class="ml-4 flex-shrink-0 flex">
                        <?php echo e($actions); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="<?php echo e($bodyPadding); ?> <?php echo e($bodyClass); ?>">
        <?php echo e($slot); ?>

    </div>
    
    <?php if(isset($footer)): ?>
        <div class="<?php echo e($footerPadding); ?> <?php echo e($footerClass); ?> border-t border-gray-100 bg-gray-50">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/components/ui/card.blade.php ENDPATH**/ ?>