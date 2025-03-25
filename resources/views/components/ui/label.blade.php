@props([
    'for' => null,
    'required' => false,
    'type' => 'default',
    'size' => 'md',
    'pill' => false,
    'dot' => false,
    'dotPosition' => 'left',
    'dotColor' => null,
    'bordered' => false,
    'uppercase' => false,
    'as' => 'label',
])

@php
    // Type styles
    $types = [
        'default' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
        'primary' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
        'secondary' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
        'success' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
        'danger' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
        'light' => 'bg-gray-50 text-gray-600 dark:bg-gray-600 dark:text-gray-200',
        'dark' => 'bg-gray-700 text-white dark:bg-gray-800 dark:text-gray-200',
    ];
    
    // Border styles
    $borderTypes = [
        'default' => 'border-gray-300 dark:border-gray-600',
        'primary' => 'border-green-300 dark:border-green-600',
        'secondary' => 'border-gray-300 dark:border-gray-600',
        'success' => 'border-green-300 dark:border-green-600',
        'danger' => 'border-red-300 dark:border-red-600',
        'warning' => 'border-yellow-300 dark:border-yellow-600',
        'info' => 'border-blue-300 dark:border-blue-600',
        'light' => 'border-gray-200 dark:border-gray-700',
        'dark' => 'border-gray-600 dark:border-gray-800',
    ];
    
    // Dot color styles
    $dotColors = [
        'default' => 'bg-gray-500',
        'primary' => 'bg-green-500',
        'secondary' => 'bg-gray-500',
        'success' => 'bg-green-500',
        'danger' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info' => 'bg-blue-500',
        'light' => 'bg-gray-400',
        'dark' => 'bg-gray-800',
    ];
    
    // Size styles
    $sizes = [
        'xs' => 'text-xs py-0.5 px-1.5',
        'sm' => 'text-xs py-0.5 px-2',
        'md' => 'text-sm py-0.5 px-2.5',
        'lg' => 'text-sm py-1 px-3',
        'xl' => 'text-base py-1 px-4',
    ];
    
    // Pill styles (rounded corners)
    $roundedStyle = $pill ? 'rounded-full' : 'rounded';
    
    // Dot position
    $dotPositionClasses = [
        'left' => 'mr-1.5',
        'right' => 'ml-1.5',
    ];
    
    // Form label vs tag style
    $isFormLabel = $as === 'label' && $for !== null;
    
    // Combine all classes
    $classes = [];
    $classes[] = $sizes[$size] ?? $sizes['md'];
    
    if (!$isFormLabel) {
        $classes[] = $types[$type] ?? $types['default'];
        $classes[] = $roundedStyle;
        $classes[] = 'inline-flex items-center';
        $classes[] = $bordered ? 'border ' . ($borderTypes[$type] ?? $borderTypes['default']) : '';
        $classes[] = $uppercase ? 'uppercase tracking-wide' : '';
    } else {
        // Form label styles
        $classes[] = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1';
        $classes[] = $uppercase ? 'uppercase tracking-wide' : '';
        $classes[] = $required ? 'required' : '';
    }
    
    // Dot color
    $dotColorClass = $dotColor ? $dotColors[$dotColor] : ($dotColors[$type] ?? $dotColors['default']);
@endphp

@if($isFormLabel)
    <{{ $as }} for="{{ $for }}" {{ $attributes->merge(['class' => implode(' ', $classes)]) }}>
        {{ $slot }}
        @if($required)
            <span class="text-red-500 ml-1">*</span>
        @endif
    </{{ $as }}>
@else
    <{{ $as }} {{ $attributes->merge(['class' => implode(' ', $classes)]) }}>
        @if($dot && $dotPosition === 'left')
            <span class="flex-shrink-0 w-2 h-2 rounded-full {{ $dotColorClass }} {{ $dotPositionClasses['left'] }}"></span>
        @endif
        {{ $slot }}
        @if($dot && $dotPosition === 'right')
            <span class="flex-shrink-0 w-2 h-2 rounded-full {{ $dotColorClass }} {{ $dotPositionClasses['right'] }}"></span>
        @endif
    </{{ $as }}>
@endif 