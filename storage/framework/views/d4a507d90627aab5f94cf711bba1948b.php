<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'id' => null,
    'show' => false,
    'maxWidth' => '2xl',
    'closeable' => true,
    'centered' => true,
    'backdrop' => true,
    'overflow' => 'auto',
    'wireClose' => null
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'id' => null,
    'show' => false,
    'maxWidth' => '2xl',
    'closeable' => true,
    'centered' => true,
    'backdrop' => true,
    'overflow' => 'auto',
    'wireClose' => null
]); ?>
<?php foreach (array_filter(([
    'id' => null,
    'show' => false,
    'maxWidth' => '2xl',
    'closeable' => true,
    'centered' => true,
    'backdrop' => true,
    'overflow' => 'auto',
    'wireClose' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
$id = $id ?? md5($attributes->wire('model'));

$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
    '7xl' => 'sm:max-w-7xl',
    'full' => 'sm:max-w-full',
][$maxWidth];
?>

<div
    x-data="{ show: <?php echo \Illuminate\Support\Js::from($show)->toHtml() ?> }"
    x-on:close.stop="show = false"
    <?php if($wireClose): ?>
        <?php if ((object) ($wireClose) instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($wireClose->value()); ?>')<?php echo e($wireClose->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($wireClose); ?>')<?php endif; ?>.live="show"
    <?php endif; ?>
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="<?php echo e($id); ?>"
    class="fixed inset-0 <?php echo e($overflow === 'auto' ? 'overflow-y-auto' : 'overflow-hidden'); ?> px-4 py-6 sm:px-0 z-50 flex items-center justify-center"
    style="display: none;"
>
    <!--[if BLOCK]><![endif]--><?php if($backdrop): ?>
    <div 
        x-show="show" 
        class="fixed inset-0 transform transition-all" 
        x-on:click="show = false" 
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div
        x-show="show"
        class="bg-white rounded-lg overflow-hidden shadow-2xl transform transition-all w-full <?php echo e($maxWidth); ?> sm:mx-auto"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        @click.away="show = false"
    >
        <!--[if BLOCK]><![endif]--><?php if($closeable): ?>
        <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
            <button
                type="button"
                class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                x-on:click="show = false"
            >
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <?php echo e($slot); ?>

    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/components/modal.blade.php ENDPATH**/ ?>