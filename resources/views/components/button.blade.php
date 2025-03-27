@props([
    'type' => 'button',
    'variant' => 'primary', 
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'loadingText' => null,
    'fullWidth' => false,
    'href' => null,
    'disabled' => false,
    'wire:click' => null,
    'x-on:click' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center focus:outline-none transition-colors duration-150 font-medium rounded-md';

$variantClasses = match($variant) {
    'primary' => 'border border-transparent shadow-sm text-white bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500',
    'secondary' => 'border border-transparent text-green-700 bg-green-100 hover:bg-green-200 focus:ring-2 focus:ring-offset-2 focus:ring-green-500',
    'outline' => 'border border-gray-300 shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-green-500',
    'danger' => 'border border-transparent shadow-sm text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
    'danger-outline' => 'border border-red-300 shadow-sm text-red-700 bg-white hover:bg-red-50 focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
    'ghost' => 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:ring-2 focus:ring-gray-200',
    'link' => 'text-green-600 hover:text-green-800 underline',
    default => 'border border-transparent shadow-sm text-white bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500',
};

$sizeClasses = match($size) {
    'xs' => 'px-2.5 py-1.5 text-xs',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-4 py-2 text-base',
    'xl' => 'px-6 py-3 text-base',
    default => 'px-4 py-2 text-sm',
};

$fullWidthClass = $fullWidth ? 'w-full' : '';
$disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : '';

$classes = "{$baseClasses} {$variantClasses} {$sizeClasses} {$fullWidthClass} {$disabledClass}";

$loadingIcon = '
<svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
';

$attributes = $attributes->merge(['type' => $type, 'class' => $classes]);

// Handle wire:click
if (isset($attributes['wire:click'])) {
    $wireClick = $attributes['wire:click'];
} elseif (isset($attributes['wire:click.prevent'])) {
    $wireClick = $attributes['wire:click.prevent'];
} else {
    $wireClick = null;
}
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes }}>
        @if($loading)
            {!! $loadingIcon !!}
            {{ $loadingText ?? $slot }}
        @else
            @if($icon && $iconPosition === 'left')
                <span class="mr-2">{!! $icon !!}</span>
            @endif
            {{ $slot }}
            @if($icon && $iconPosition === 'right')
                <span class="ml-2">{!! $icon !!}</span>
            @endif
        @endif
    </a>
@else
    <button {{ $attributes }} @if($disabled) disabled @endif>
        @if($loading)
            {!! $loadingIcon !!}
            {{ $loadingText ?? $slot }}
        @else
            @if($icon && $iconPosition === 'left')
                <span class="mr-2">{!! $icon !!}</span>
            @endif
            {{ $slot }}
            @if($icon && $iconPosition === 'right')
                <span class="ml-2">{!! $icon !!}</span>
            @endif
        @endif
    </button>
@endif 