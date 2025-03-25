<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'rounded' => false,
    'outlined' => false,
    'elevated' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'href' => null,
    'fullWidth' => false
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'rounded' => false,
    'outlined' => false,
    'elevated' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'href' => null,
    'fullWidth' => false
]); ?>
<?php foreach (array_filter(([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'rounded' => false,
    'outlined' => false,
    'elevated' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'href' => null,
    'fullWidth' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $baseClasses = 'inline-flex items-center justify-center transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $variantClasses = [
        'primary' => $outlined 
            ? 'border border-green-500 text-green-500 hover:bg-green-50 focus:ring-green-500' 
            : 'bg-green-500 text-white hover:bg-green-600 focus:ring-green-500',
        'secondary' => $outlined 
            ? 'border border-gray-500 text-gray-500 hover:bg-gray-50 focus:ring-gray-400' 
            : 'bg-gray-500 text-white hover:bg-gray-600 focus:ring-gray-400',
        'success' => $outlined 
            ? 'border border-green-500 text-green-500 hover:bg-green-50 focus:ring-green-500' 
            : 'bg-green-500 text-white hover:bg-green-600 focus:ring-green-500',
        'danger' => $outlined 
            ? 'border border-red-500 text-red-500 hover:bg-red-50 focus:ring-red-500' 
            : 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500',
        'warning' => $outlined 
            ? 'border border-amber-500 text-amber-500 hover:bg-amber-50 focus:ring-amber-500' 
            : 'bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-500',
        'info' => $outlined 
            ? 'border border-cyan-500 text-cyan-500 hover:bg-cyan-50 focus:ring-cyan-500' 
            : 'bg-cyan-500 text-white hover:bg-cyan-600 focus:ring-cyan-500',
        'light' => $outlined 
            ? 'border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-300' 
            : 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-300',
        'dark' => $outlined 
            ? 'border border-gray-800 text-gray-800 hover:bg-gray-50 focus:ring-gray-700' 
            : 'bg-gray-800 text-white hover:bg-gray-900 focus:ring-gray-700',
    ];
    
    $sizeClasses = [
        'xs' => 'text-xs px-2.5 py-1.5',
        'sm' => 'text-sm px-3 py-2 leading-4',
        'md' => 'text-sm px-4 py-2',
        'lg' => 'text-base px-5 py-2.5',
        'xl' => 'text-lg px-6 py-3',
    ];
    
    $roundedClasses = $rounded ? 'rounded-full' : 'rounded-md';
    $elevatedClasses = $elevated ? 'shadow-md hover:shadow-lg' : '';
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer';
    $fullWidthClasses = $fullWidth ? 'w-full' : '';
    
    $iconSizeClasses = [
        'xs' => 'h-3.5 w-3.5',
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-5 w-5',
        'xl' => 'h-6 w-6',
    ];
    
    $iconMarginClasses = [
        'xs' => $iconPosition === 'left' ? 'mr-1' : 'ml-1',
        'sm' => $iconPosition === 'left' ? 'mr-1.5' : 'ml-1.5',
        'md' => $iconPosition === 'left' ? 'mr-2' : 'ml-2',
        'lg' => $iconPosition === 'left' ? 'mr-2' : 'ml-2',
        'xl' => $iconPosition === 'left' ? 'mr-2.5' : 'ml-2.5',
    ];
    
    $classes = $baseClasses . ' ' . 
        ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . 
        ($sizeClasses[$size] ?? $sizeClasses['md']) . ' ' . 
        $roundedClasses . ' ' . 
        $elevatedClasses . ' ' . 
        $disabledClasses . ' ' . 
        $fullWidthClasses;
?>

<!--[if BLOCK]><![endif]--><?php if($href): ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?>>
        <!--[if BLOCK]><![endif]--><?php if($loading): ?>
            <svg class="animate-spin <?php echo e($iconPosition === 'left' ? $iconMarginClasses[$size] : ''); ?> <?php echo e($iconSizeClasses[$size]); ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        <?php elseif($icon && $iconPosition === 'left'): ?>
            <span class="<?php echo e($iconMarginClasses[$size]); ?>">
                <?php echo $icon; ?>

            </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php echo e($slot); ?>

        <!--[if BLOCK]><![endif]--><?php if($icon && $iconPosition === 'right'): ?>
            <span class="<?php echo e($iconMarginClasses[$size]); ?>">
                <?php echo $icon; ?>

            </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?> <?php if($disabled): ?> disabled <?php endif; ?>>
        <!--[if BLOCK]><![endif]--><?php if($loading): ?>
            <svg class="animate-spin <?php echo e($iconPosition === 'left' ? $iconMarginClasses[$size] : ''); ?> <?php echo e($iconSizeClasses[$size]); ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        <?php elseif($icon && $iconPosition === 'left'): ?>
            <span class="<?php echo e($iconMarginClasses[$size]); ?>">
                <?php echo $icon; ?>

            </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php echo e($slot); ?>

        <!--[if BLOCK]><![endif]--><?php if($icon && $iconPosition === 'right'): ?>
            <span class="<?php echo e($iconMarginClasses[$size]); ?>">
                <?php echo $icon; ?>

            </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </button>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--> <?php /**PATH C:\projects\MbukuErp\resources\views/components/ui/button.blade.php ENDPATH**/ ?>