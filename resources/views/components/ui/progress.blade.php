@props([
    'value' => 0,
    'min' => 0,
    'max' => 100,
    'size' => 'md',
    'color' => 'primary',
    'rounded' => 'full',
    'striped' => false,
    'animated' => false,
    'showValue' => false,
    'valuePosition' => 'right', // right, inside, top, bottom, hidden
    'label' => '',
    'indeterminate' => false,
])

@php
    // Make sure the value is between min and max
    $normalizedValue = max(min(floatval($value), floatval($max)), floatval($min));
    
    // Calculate percentage
    $percentage = $max > $min ? (($normalizedValue - $min) / ($max - $min)) * 100 : 0;
    
    // Size-based height classes
    $heightClasses = [
        'xs' => 'h-1',
        'sm' => 'h-2',
        'md' => 'h-3',
        'lg' => 'h-4',
        'xl' => 'h-6',
    ][$size] ?? 'h-3';
    
    // Value position logic
    $showValueInside = $valuePosition === 'inside' && $size !== 'xs' && $size !== 'sm';
    $showValueOutside = in_array($valuePosition, ['right', 'top', 'bottom']) && !$indeterminate;
    $valuePositionClasses = [
        'right' => 'ml-3 text-right',
        'top' => 'mb-1',
        'bottom' => 'mt-1',
    ][$valuePosition] ?? '';
    
    // Rounded classes
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-full';
    
    // Color classes for the progress bar
    $colorClasses = [
        'primary' => 'bg-green-500',
        'secondary' => 'bg-gray-400',
        'info' => 'bg-blue-500',
        'success' => 'bg-green-500',
        'warning' => 'bg-yellow-500',
        'danger' => 'bg-red-500',
        'dark' => 'bg-gray-800',
        'light' => 'bg-gray-200',
    ][$color] ?? 'bg-green-500';
    
    // Striped classes (using background gradients)
    $stripedClasses = $striped ? 'bg-gradient-to-r from-transparent via-white/20 to-transparent bg-[length:1rem_100%]' : '';
    
    // Animation classes
    $animationClasses = '';
    
    if ($indeterminate) {
        $animationClasses = 'relative overflow-hidden';
    } elseif ($animated && $striped) {
        $animationClasses = 'animate-progress-stripes';
    }
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($label || ($showValueOutside && $valuePosition === 'top'))
        <div class="flex justify-between items-center mb-1">
            @if($label)
                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
            @endif
            
            @if($showValueOutside && $valuePosition === 'top')
                <span class="text-sm text-gray-500">{{ $normalizedValue }}/{{ $max }}</span>
            @endif
        </div>
    @endif
    
    <div class="flex items-center">
        <div class="relative flex-grow bg-gray-200 {{ $roundedClasses }} {{ $heightClasses }} overflow-hidden">
            @if($indeterminate)
                <div class="absolute inset-0 {{ $colorClasses }} {{ $roundedClasses }}">
                    <div class="absolute inset-0 {{ $colorClasses }} animate-indeterminate-progress w-[50%] {{ $roundedClasses }} opacity-75"></div>
                </div>
            @else
                <div 
                    class="{{ $colorClasses }} {{ $roundedClasses }} {{ $stripedClasses }} {{ $animationClasses }} h-full transition-all duration-300 ease-out"
                    style="width: {{ $percentage }}%"
                    role="progressbar" 
                    aria-valuenow="{{ $normalizedValue }}" 
                    aria-valuemin="{{ $min }}" 
                    aria-valuemax="{{ $max }}"
                >
                    @if($showValueInside)
                        <div class="flex items-center justify-center h-full text-xs font-medium text-white px-2">
                            {{ round($percentage) }}%
                        </div>
                    @endif
                </div>
            @endif
        </div>
        
        @if($showValueOutside && $valuePosition === 'right')
            <span class="{{ $valuePositionClasses }} text-sm text-gray-500 min-w-[3rem]">
                {{ round($percentage) }}%
            </span>
        @endif
    </div>
    
    @if($showValueOutside && $valuePosition === 'bottom')
        <div class="{{ $valuePositionClasses }} text-sm text-gray-500 text-right">
            {{ round($percentage) }}%
        </div>
    @endif
</div>

<style>
    @keyframes progress-stripes {
        0% { background-position: 1rem 0; }
        100% { background-position: 0 0; }
    }
    
    @keyframes indeterminate-progress {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(200%); }
    }
    
    .animate-progress-stripes {
        animation: progress-stripes 1s linear infinite;
    }
    
    .animate-indeterminate-progress {
        animation: indeterminate-progress 1.5s ease-in-out infinite;
    }
</style> 