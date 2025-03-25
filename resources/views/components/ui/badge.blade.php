@props([
    'label' => '',
    'type' => 'default',
    'size' => 'md',
    'rounded' => 'full',
    'outlined' => false,
    'icon' => '',
    'iconPosition' => 'left',
    'removable' => false,
    'onRemove' => '',
    'pill' => false,
])

@php
    // Type-based color classes
    $typeClasses = [
        'default' => [
            'solid' => 'bg-gray-100 text-gray-800',
            'outlined' => 'bg-white text-gray-800 border border-gray-300'
        ],
        'primary' => [
            'solid' => 'bg-green-100 text-green-800',
            'outlined' => 'bg-white text-green-800 border border-green-300' 
        ],
        'secondary' => [
            'solid' => 'bg-gray-200 text-gray-900',
            'outlined' => 'bg-white text-gray-800 border border-gray-400'
        ],
        'success' => [
            'solid' => 'bg-green-100 text-green-800',
            'outlined' => 'bg-white text-green-800 border border-green-300'
        ],
        'danger' => [
            'solid' => 'bg-red-100 text-red-800',
            'outlined' => 'bg-white text-red-800 border border-red-300'
        ],
        'warning' => [
            'solid' => 'bg-yellow-100 text-yellow-800',
            'outlined' => 'bg-white text-yellow-800 border border-yellow-300'
        ],
        'info' => [
            'solid' => 'bg-blue-100 text-blue-800',
            'outlined' => 'bg-white text-blue-800 border border-blue-300'
        ],
        'dark' => [
            'solid' => 'bg-gray-700 text-white',
            'outlined' => 'bg-white text-gray-800 border border-gray-700'
        ],
        'light' => [
            'solid' => 'bg-gray-50 text-gray-700',
            'outlined' => 'bg-white text-gray-700 border border-gray-200'
        ],
        'active' => [
            'solid' => 'bg-green-100 text-green-800',
            'outlined' => 'bg-white text-green-800 border border-green-300'
        ],
        'inactive' => [
            'solid' => 'bg-red-100 text-red-800',
            'outlined' => 'bg-white text-red-800 border border-red-300'
        ],
        'pending' => [
            'solid' => 'bg-yellow-100 text-yellow-800',
            'outlined' => 'bg-white text-yellow-800 border border-yellow-300'
        ],
        'processing' => [
            'solid' => 'bg-blue-100 text-blue-800',
            'outlined' => 'bg-white text-blue-800 border border-blue-300'
        ],
        'completed' => [
            'solid' => 'bg-green-100 text-green-800',
            'outlined' => 'bg-white text-green-800 border border-green-300'
        ],
        'cancelled' => [
            'solid' => 'bg-red-100 text-red-800',
            'outlined' => 'bg-white text-red-800 border border-red-300'
        ],
    ];
    
    // Get the appropriate class based on type and outlined state
    $colorClasses = $typeClasses[$type][$outlined ? 'outlined' : 'solid'] ?? $typeClasses['default'][$outlined ? 'outlined' : 'solid'];
    
    // Size classes
    $sizeClasses = [
        'xs' => 'text-xs px-1.5 py-0.5',
        'sm' => 'text-xs px-2 py-0.5',
        'md' => 'text-sm px-2.5 py-0.75',
        'lg' => 'text-base px-3 py-1',
    ][$size] ?? 'text-sm px-2.5 py-0.75';
    
    // Rounded classes - give precedence to pill prop
    $roundedClass = $pill ? 'rounded-full' : [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-full';
    
    // Icon margin classes
    $iconMarginClass = $icon && $label 
        ? ($iconPosition === 'left' ? 'mr-1' : 'ml-1')
        : '';
        
    // Get the remove button color classes based on type
    $removeButtonColorClasses = [
        'default' => 'text-gray-400 hover:text-gray-600',
        'primary' => 'text-green-500 hover:text-green-700',
        'secondary' => 'text-gray-400 hover:text-gray-600',
        'success' => 'text-green-500 hover:text-green-700',
        'danger' => 'text-red-500 hover:text-red-700',
        'warning' => 'text-yellow-500 hover:text-yellow-700',
        'info' => 'text-blue-500 hover:text-blue-700',
        'dark' => 'text-gray-200 hover:text-white',
        'light' => 'text-gray-400 hover:text-gray-600',
        'active' => 'text-green-500 hover:text-green-700',
        'inactive' => 'text-red-500 hover:text-red-700',
        'pending' => 'text-yellow-500 hover:text-yellow-700',
        'processing' => 'text-blue-500 hover:text-blue-700',
        'completed' => 'text-green-500 hover:text-green-700',
        'cancelled' => 'text-red-500 hover:text-red-700',
    ][$type] ?? 'text-gray-400 hover:text-gray-600';
@endphp

<span 
    {{ $attributes->merge(['class' => "inline-flex items-center {$colorClasses} {$sizeClasses} {$roundedClass} font-medium"]) }}
    @if ($removable && $onRemove) 
        x-data="{}" 
        @click="$dispatch('{{ $onRemove }}')"
    @endif
>
    @if ($icon && $iconPosition === 'left')
        <span class="inline-block {{ $iconMarginClass }}">{!! $icon !!}</span>
    @endif
    
    @if ($label)
        {{ $label }}
    @else
        {{ $slot }}
    @endif
    
    @if ($icon && $iconPosition === 'right')
        <span class="inline-block {{ $iconMarginClass }}">{!! $icon !!}</span>
    @endif
    
    @if ($removable)
        <button 
            type="button" 
            class="ml-1 -mr-0.5 flex-shrink-0 inline-flex {{ $removeButtonColorClasses }} focus:outline-none"
            @if (!$onRemove)
                @click="$el.parentNode.remove()"
            @endif
        >
            <span class="sr-only">Remove</span>
            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    @endif
</span> 