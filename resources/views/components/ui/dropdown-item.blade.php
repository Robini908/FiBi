@props([
    'type' => 'link',
    'href' => '#',
    'icon' => '',
    'iconPosition' => 'left',
    'disabled' => false,
    'closeOnClick' => true,
])

@php
    $baseClasses = 'block w-full px-4 py-2 text-left text-sm leading-5 focus:outline-none transition duration-150 ease-in-out';
    $activeClasses = 'text-gray-900 bg-gray-100 hover:bg-gray-200 focus:bg-gray-200';
    $inactiveClasses = 'text-gray-700 hover:bg-gray-100 focus:bg-gray-100';
    $disabledClasses = 'text-gray-400 cursor-not-allowed pointer-events-none';
    
    $classes = $disabled 
        ? "{$baseClasses} {$disabledClasses}" 
        : "{$baseClasses} " . ($attributes->has('active') ? $activeClasses : $inactiveClasses);
@endphp

@if ($type === 'link')
    <a 
        href="{{ $disabled ? '#' : $href }}" 
        {{ $attributes->merge(['class' => $classes]) }} 
        @if($closeOnClick && !$disabled) @click="open = false" @endif
    >
        @if ($icon && $iconPosition === 'left')
            <span class="inline-block mr-2">{!! $icon !!}</span>
        @endif
        
        {{ $slot }}
        
        @if ($icon && $iconPosition === 'right')
            <span class="inline-block ml-2">{!! $icon !!}</span>
        @endif
    </a>
@elseif ($type === 'button')
    <button 
        {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }} 
        @if($disabled) disabled @endif
        @if($closeOnClick && !$disabled) @click="open = false" @endif
    >
        @if ($icon && $iconPosition === 'left')
            <span class="inline-block mr-2">{!! $icon !!}</span>
        @endif
        
        {{ $slot }}
        
        @if ($icon && $iconPosition === 'right')
            <span class="inline-block ml-2">{!! $icon !!}</span>
        @endif
    </button>
@elseif ($type === 'divider')
    <div class="border-t border-gray-200 my-1"></div>
@elseif ($type === 'header')
    <div class="px-4 py-2 text-xs text-gray-500 uppercase tracking-wider font-semibold">
        {{ $slot }}
    </div>
@endif 