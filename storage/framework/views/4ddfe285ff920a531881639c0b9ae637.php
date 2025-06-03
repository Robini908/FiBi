<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'items' => [],
    'separator' => 'chevron',
    'homeLink' => '/',
    'homeText' => 'Home',
    'showHome' => true,
    'truncate' => false,
    'responsive' => true,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'items' => [],
    'separator' => 'chevron',
    'homeLink' => '/',
    'homeText' => 'Home',
    'showHome' => true,
    'truncate' => false,
    'responsive' => true,
]); ?>
<?php foreach (array_filter(([
    'items' => [],
    'separator' => 'chevron',
    'homeLink' => '/',
    'homeText' => 'Home',
    'showHome' => true,
    'truncate' => false,
    'responsive' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $separatorIcons = [
        'chevron' => '<svg class="h-4 w-4 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                      </svg>',
        'slash' => '<span class="text-gray-400 mx-1">/</span>',
        'dot' => '<span class="text-gray-400 mx-1">•</span>',
        'dash' => '<span class="text-gray-400 mx-1">-</span>',
        'arrow' => '<svg class="h-4 w-4 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>',
    ];
    
    $separatorHtml = $separatorIcons[$separator] ?? $separatorIcons['chevron'];
?>

<nav aria-label="Breadcrumb" <?php echo e($attributes); ?>>
    <ol class="flex items-center <?php echo e($responsive ? 'flex-wrap' : ''); ?> space-x-1 sm:space-x-2">
        <?php if($showHome): ?>
            <li>
                <div class="flex items-center">
                    <a href="<?php echo e($homeLink); ?>" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                        <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="sr-only"><?php echo e($homeText); ?></span>
                    </a>
                </div>
            </li>
        <?php endif; ?>
        
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <div class="flex items-center">
                    <?php if($index > 0 || $showHome): ?>
                        <div class="flex items-center mx-1">
                            <?php echo $separatorHtml; ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($item['url']) && $index < count($items) - 1): ?>
                        <a 
                            href="<?php echo e($item['url']); ?>" 
                            class="<?php echo e($truncate ? 'truncate max-w-[100px] sm:max-w-xs' : ''); ?> text-sm font-medium text-gray-500 hover:text-gray-700"
                            <?php if(isset($item['title'])): ?> title="<?php echo e($item['title']); ?>" <?php endif; ?>
                        >
                            <?php echo e($item['label']); ?>

                        </a>
                    <?php else: ?>
                        <span 
                            class="<?php echo e($truncate ? 'truncate max-w-[100px] sm:max-w-xs' : ''); ?> text-sm font-medium text-gray-900"
                            <?php if(isset($item['title'])): ?> title="<?php echo e($item['title']); ?>" <?php endif; ?>
                        >
                            <?php echo e($item['label']); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav> <?php /**PATH C:\projects\MbukuErp\resources\views/components/ui/breadcrumb.blade.php ENDPATH**/ ?>