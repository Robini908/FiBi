<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'items' => [],
    'collapsible' => true,
    'multiple' => false,
    'bordered' => true,
    'rounded' => 'md',
    'spaced' => false,
    'iconPosition' => 'right',
    'defaultOpen' => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'items' => [],
    'collapsible' => true,
    'multiple' => false,
    'bordered' => true,
    'rounded' => 'md',
    'spaced' => false,
    'iconPosition' => 'right',
    'defaultOpen' => null,
]); ?>
<?php foreach (array_filter(([
    'items' => [],
    'collapsible' => true,
    'multiple' => false,
    'bordered' => true,
    'rounded' => 'md',
    'spaced' => false,
    'iconPosition' => 'right',
    'defaultOpen' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-md';
    
    // Convert defaultOpen to array if string or number
    if (isset($defaultOpen) && !is_array($defaultOpen)) {
        $defaultOpen = [$defaultOpen];
    }
    
    $itemClass = $spaced ? 'mb-3' : 'border-b';
    $lastItemClass = $spaced ? 'mb-0' : 'border-b-0';
?>

<div 
    x-data="{ 
        openItems: <?php echo e(isset($defaultOpen) ? json_encode($defaultOpen) : '[]'); ?>,
        
        isOpen(id) {
            return this.openItems.includes(id);
        },
        
        toggle(id) {
            if (this.isOpen(id)) {
                this.openItems = this.openItems.filter(item => item !== id);
            } else {
                <?php if(!$multiple): ?>
                    this.openItems = [];
                <?php endif; ?>
                this.openItems.push(id);
            }
        }
    }"
    class="w-full <?php echo e($bordered ? 'border border-gray-200 divide-y divide-gray-200' : 'divide-y divide-gray-200'); ?> <?php echo e($roundedClasses); ?>"
    <?php echo e($attributes); ?>

>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div 
            class="<?php echo e($index === count($items) - 1 ? $lastItemClass : $itemClass); ?>"
            :class="{ 'border-b-0': isOpen(<?php echo e($index); ?>) && !<?php echo e($spaced ? 'true' : 'false'); ?> }"
            id="accordion-item-<?php echo e($index); ?>"
        >
            <h3>
                <button 
                    type="button"
                    class="flex items-center justify-between w-full px-4 py-3 text-left text-gray-700 hover:bg-gray-50 focus:outline-none focus:bg-gray-50"
                    :class="{ 'bg-gray-50': isOpen(<?php echo e($index); ?>) }"
                    @click="toggle(<?php echo e($index); ?>)"
                    :aria-expanded="isOpen(<?php echo e($index); ?>)"
                    aria-controls="accordion-content-<?php echo e($index); ?>"
                    <?php if(!$collapsible && $index === 0): ?>
                        disabled
                    <?php endif; ?>
                >
                    <div class="flex items-center">
                        <?php if($iconPosition === 'left'): ?>
                            <span class="mr-2 transform transition-transform duration-200" :class="{ 'rotate-90': isOpen(<?php echo e($index); ?>) }">
                                <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        <?php endif; ?>
                        
                        <span 
                            class="font-medium text-sm sm:text-base"
                            :class="{ 'font-semibold': isOpen(<?php echo e($index); ?>) }"
                        >
                            <?php echo e($item['title'] ?? 'Accordion Item ' . ($index + 1)); ?>

                        </span>
                    </div>
                    
                    <?php if($iconPosition === 'right'): ?>
                        <span class="transform transition-transform duration-200" :class="{ 'rotate-180': isOpen(<?php echo e($index); ?>) }">
                            <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    <?php endif; ?>
                </button>
            </h3>
            
            <div 
                id="accordion-content-<?php echo e($index); ?>"
                x-show="isOpen(<?php echo e($index); ?>)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                <?php if(!isset($defaultOpen) || !in_array($index, $defaultOpen)): ?>
                    style="display: none;"
                <?php endif; ?>
                class="px-4 pb-4 pt-0"
            >
                <div class="prose max-w-none text-gray-700 text-sm sm:text-base">
                    <?php if(isset($item['content'])): ?>
                        <?php echo $item['content']; ?>

                    <?php else: ?>
                        <p>Content for accordion item <?php echo e($index + 1); ?>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/components/ui/accordion.blade.php ENDPATH**/ ?>