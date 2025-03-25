<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['type' => 'success', 'message' => null, 'title' => null, 'dismissible' => true, 'duration' => 5000]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['type' => 'success', 'message' => null, 'title' => null, 'dismissible' => true, 'duration' => 5000]); ?>
<?php foreach (array_filter((['type' => 'success', 'message' => null, 'title' => null, 'dismissible' => true, 'duration' => 5000]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $typeClasses = [
        'success' => 'bg-green-50 text-green-800 border-green-400',
        'danger' => 'bg-red-50 text-red-800 border-red-400',
        'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-400',
        'info' => 'bg-blue-50 text-blue-800 border-blue-400',
        'debug' => 'bg-gray-50 text-gray-800 border-gray-400',
    ];

    $iconClasses = [
        'success' => 'text-green-400',
        'danger' => 'text-red-400',
        'warning' => 'text-yellow-400',
        'info' => 'text-blue-400',
        'debug' => 'text-gray-400',
    ];

    $icons = [
        'success' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />',
        'danger' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />',
        'warning' => '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />',
        'info' => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />',
        'debug' => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />',
    ];
?>

<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => show = false, <?php echo e($duration); ?>)" 
    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'rounded-md p-4 border-l-4 shadow-md',
        $typeClasses[$type] ?? $typeClasses['info'],
        'mb-4'
    ]); ?>"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
>
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 <?php if(isset($iconClasses[$type])): ?> <?php echo e($iconClasses[$type]); ?> <?php else: ?> <?php echo e($iconClasses['info']); ?> <?php endif; ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <!--[if BLOCK]><![endif]--><?php if(isset($icons[$type])): ?>
                    <?php echo $icons[$type]; ?>

                <?php else: ?>
                    <?php echo $icons['info']; ?>

                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </svg>
        </div>
        <div class="ml-3">
            <!--[if BLOCK]><![endif]--><?php if($title): ?>
                <h3 class="text-sm font-medium <?php if(isset($typeClasses[$type])): ?> <?php echo e(str_replace(['bg-', 'border-'], '', $typeClasses[$type])); ?> <?php else: ?> <?php echo e(str_replace(['bg-', 'border-'], '', $typeClasses['info'])); ?> <?php endif; ?>">
                    <?php echo $title; ?>

                </h3>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <div class="<?php if($title): ?> mt-2 <?php endif; ?> text-sm <?php if(isset($typeClasses[$type])): ?> <?php echo e(str_replace(['bg-', 'border-'], '', $typeClasses[$type])); ?> <?php else: ?> <?php echo e(str_replace(['bg-', 'border-'], '', $typeClasses['info'])); ?> <?php endif; ?>">
                <p><?php echo $message; ?></p>
            </div>
        </div>
        <!--[if BLOCK]><![endif]--><?php if($dismissible): ?>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button 
                        @click="show = false" 
                        type="button" 
                        class="inline-flex <?php if(isset($typeClasses[$type])): ?> <?php echo e(str_replace(['bg-', 'text-', 'border-'], ['hover:bg-', 'text-', ''], $typeClasses[$type])); ?> <?php else: ?> <?php echo e(str_replace(['bg-', 'text-', 'border-'], ['hover:bg-', 'text-', ''], $typeClasses['info'])); ?> <?php endif; ?> rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600 transition duration-150 ease-in-out">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/components/toast.blade.php ENDPATH**/ ?>