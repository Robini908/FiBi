@props([
    'content' => '',
    'position' => 'top',
    'color' => 'dark',
    'size' => 'md',
    'arrow' => true,
    'delay' => 300, // delay in ms
    'trigger' => 'hover', // hover, click
    'maxWidth' => null,
    'animation' => true,
])

@php
    // Position classes for both the tooltip and the arrow
    $positionClasses = [
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        'top-start' => 'bottom-full left-0 mb-2',
        'top-end' => 'bottom-full right-0 mb-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'bottom-start' => 'top-full left-0 mt-2',
        'bottom-end' => 'top-full right-0 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'left-start' => 'right-full top-0 mr-2',
        'left-end' => 'right-full bottom-0 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
        'right-start' => 'left-full top-0 ml-2',
        'right-end' => 'left-full bottom-0 ml-2',
    ][$position] ?? 'bottom-full left-1/2 -translate-x-1/2 mb-2';
    
    // Arrow position classes
    $arrowPositionClasses = [
        'top' => 'bottom-[-6px] left-1/2 -translate-x-1/2 border-l-transparent border-r-transparent border-b-transparent',
        'top-start' => 'bottom-[-6px] left-3 border-l-transparent border-r-transparent border-b-transparent',
        'top-end' => 'bottom-[-6px] right-3 border-l-transparent border-r-transparent border-b-transparent',
        'bottom' => 'top-[-6px] left-1/2 -translate-x-1/2 border-l-transparent border-r-transparent border-t-transparent',
        'bottom-start' => 'top-[-6px] left-3 border-l-transparent border-r-transparent border-t-transparent',
        'bottom-end' => 'top-[-6px] right-3 border-l-transparent border-r-transparent border-t-transparent',
        'left' => 'right-[-6px] top-1/2 -translate-y-1/2 border-t-transparent border-r-transparent border-b-transparent',
        'left-start' => 'right-[-6px] top-3 border-t-transparent border-r-transparent border-b-transparent',
        'left-end' => 'right-[-6px] bottom-3 border-t-transparent border-r-transparent border-b-transparent',
        'right' => 'left-[-6px] top-1/2 -translate-y-1/2 border-t-transparent border-l-transparent border-b-transparent',
        'right-start' => 'left-[-6px] top-3 border-t-transparent border-l-transparent border-b-transparent',
        'right-end' => 'left-[-6px] bottom-3 border-t-transparent border-l-transparent border-b-transparent',
    ][$position] ?? 'bottom-[-6px] left-1/2 -translate-x-1/2 border-l-transparent border-r-transparent border-b-transparent';
    
    // Color classes for the tooltip
    $colorClasses = [
        'dark' => 'bg-gray-900 text-white',
        'light' => 'bg-white text-gray-900 border border-gray-200',
        'primary' => 'bg-green-600 text-white',
        'secondary' => 'bg-gray-600 text-white',
        'success' => 'bg-green-600 text-white',
        'danger' => 'bg-red-600 text-white',
        'warning' => 'bg-yellow-500 text-white',
        'info' => 'bg-blue-600 text-white',
    ][$color] ?? 'bg-gray-900 text-white';
    
    // Size classes
    $sizeClasses = [
        'sm' => 'py-1 px-2 text-xs',
        'md' => 'py-2 px-3 text-sm',
        'lg' => 'py-3 px-4 text-base',
    ][$size] ?? 'py-2 px-3 text-sm';
    
    // Max width style
    $maxWidthStyle = $maxWidth ? "max-width: {$maxWidth}px;" : '';
    
    // Animation classes
    $animationClasses = $animation ? 'transition-opacity duration-150' : '';
    
    // Generate a unique ID for this tooltip
    $tooltipId = 'tooltip-' . md5(uniqid(mt_rand(), true));
@endphp

<div 
    x-data="{
        open: false,
        timeout: null,
        
        toggle() {
            this.open = !this.open;
        },
        
        show() {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.open = true;
            }, {{ $delay }});
        },
        
        hide() {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.open = false;
            }, 100);
        }
    }"
    class="relative inline-block"
    @if($trigger === 'hover')
        @mouseenter="show"
        @mouseleave="hide"
        @focusin="show"
        @focusout="hide"
    @elseif($trigger === 'click')
        @click.stop="toggle"
        @click.away="open = false"
    @endif
>
    <!-- Trigger element -->
    <span {{ $attributes->class(['cursor-default inline-block']) }}>
        {{ $slot }}
    </span>
    
    <!-- Tooltip content -->
    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 whitespace-normal rounded-md shadow-lg text-center {{ $positionClasses }} {{ $colorClasses }} {{ $sizeClasses }} {{ $animationClasses }}"
        style="{{ $maxWidthStyle }}"
        role="tooltip"
    >
        @if(is_null($content) || empty($content))
            {{ $tooltip ?? 'Tooltip content' }}
        @else
            {{ $content }}
        @endif
        
        @if($arrow)
            <!-- Arrow element -->
            <div class="absolute w-0 h-0 border-4 {{ $arrowPositionClasses }} {{ str_replace(['bg-', 'border-', 'text-'], 'border-', explode(' ', $colorClasses)[0]) }}"></div>
        @endif
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style> 