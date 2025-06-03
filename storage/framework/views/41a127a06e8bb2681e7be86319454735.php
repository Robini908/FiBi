<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'id' => 'slide-over-' . uniqid(),
    'title' => null,
    'subtitle' => null,
    'position' => 'right',
    'size' => 'md',
    'showClose' => true,
    'closeOnBackdrop' => true,
    'permanent' => false,
    'zIndex' => 'z-50',
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'id' => 'slide-over-' . uniqid(),
    'title' => null,
    'subtitle' => null,
    'position' => 'right',
    'size' => 'md',
    'showClose' => true,
    'closeOnBackdrop' => true,
    'permanent' => false,
    'zIndex' => 'z-50',
]); ?>
<?php foreach (array_filter(([
    'id' => 'slide-over-' . uniqid(),
    'title' => null,
    'subtitle' => null,
    'position' => 'right',
    'size' => 'md',
    'showClose' => true,
    'closeOnBackdrop' => true,
    'permanent' => false,
    'zIndex' => 'z-50',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    // Size classes
    $sizes = [
        'xs' => 'max-w-xs',
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '6xl' => 'max-w-6xl',
        '7xl' => 'max-w-7xl',
        'full' => 'max-w-full',
    ][$size] ?? 'max-w-md';
    
    // Position classes
    $positionClasses = [
        'right' => 'right-0',
        'left' => 'left-0',
    ][$position] ?? 'right-0';
    
    // Transition classes based on position
    $transitionClasses = [
        'right' => [
            'enter' => 'transform transition ease-in-out duration-300 sm:duration-500',
            'enter-start' => 'translate-x-full',
            'enter-end' => 'translate-x-0',
            'leave' => 'transform transition ease-in-out duration-300 sm:duration-500',
            'leave-start' => 'translate-x-0',
            'leave-end' => 'translate-x-full',
        ],
        'left' => [
            'enter' => 'transform transition ease-in-out duration-300 sm:duration-500',
            'enter-start' => '-translate-x-full',
            'enter-end' => 'translate-x-0',
            'leave' => 'transform transition ease-in-out duration-300 sm:duration-500',
            'leave-start' => 'translate-x-0',
            'leave-end' => '-translate-x-full',
        ],
    ][$position] ?? [
        'enter' => 'transform transition ease-in-out duration-300 sm:duration-500',
        'enter-start' => 'translate-x-full',
        'enter-end' => 'translate-x-0',
        'leave' => 'transform transition ease-in-out duration-300 sm:duration-500',
        'leave-start' => 'translate-x-0',
        'leave-end' => 'translate-x-full',
    ];
?>

<div
    x-data="{
        open: false,
        init() {
            this.$watch('open', value => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
            
            // Listen for the slide-over-open event
            this.$el.addEventListener('slide-over-open', () => { this.open = true; });
        }
    }"
    x-on:keydown.escape.window="open = false"
    x-id="['slide-over-title']"
    <?php echo e($attributes->merge(['class' => 'relative'])); ?>

    id="<?php echo e($id); ?>"
>
    <!-- Trigger -->
    <div x-on:click="open = true">
        <?php echo e($trigger ?? ''); ?>

    </div>
  
    <!-- Backdrop -->
    <div 
        x-show="open"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm <?php echo e($zIndex); ?>"
        x-on:click="<?php echo e($closeOnBackdrop ? 'open = false' : ''); ?>"
    ></div>
  
    <!-- Slide-over panel -->
    <div
        x-show="open"
        x-trap.noscroll="open"
        x-transition:enter="<?php echo e($transitionClasses['enter']); ?>"
        x-transition:enter-start="<?php echo e($transitionClasses['enter-start']); ?>"
        x-transition:enter-end="<?php echo e($transitionClasses['enter-end']); ?>"
        x-transition:leave="<?php echo e($transitionClasses['leave']); ?>"
        x-transition:leave-start="<?php echo e($transitionClasses['leave-start']); ?>"
        x-transition:leave-end="<?php echo e($transitionClasses['leave-end']); ?>"
        class="fixed inset-y-0 <?php echo e($positionClasses); ?> <?php echo e($sizes); ?> w-full flex <?php echo e($zIndex); ?> pointer-events-none"
        x-cloak
    >
        <div class="flex flex-col h-full w-full bg-white dark:bg-gray-800 shadow-xl overflow-y-auto pointer-events-auto">
            <!-- Header -->
            <?php if($title || isset($header) || $showClose): ?>
                <div class="px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <?php if($title || isset($header)): ?>
                            <div>
                                <?php if($title): ?>
                                    <h2 
                                        class="text-lg font-medium text-gray-900 dark:text-white" 
                                        :id="$id('slide-over-title')"
                                    >
                                        <?php echo e($title); ?>

                                    </h2>
                                    
                                    <?php if($subtitle): ?>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($subtitle); ?>

                                        </p>
                                    <?php endif; ?>
                                <?php elseif(isset($header)): ?>
                                    <?php echo e($header); ?>

                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($showClose): ?>
                            <div class="ml-3 h-7 flex items-center">
                                <button
                                    type="button"
                                    class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500"
                                    x-on:click="open = false"
                                >
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Body -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6">
                <?php echo e($slot); ?>

            </div>
            
            <!-- Footer -->
            <?php if(isset($footer)): ?>
                <div class="flex-shrink-0 px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
                    <?php echo e($footer); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/components/ui/slide-over.blade.php ENDPATH**/ ?>