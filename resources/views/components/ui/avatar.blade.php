@props([
    'src' => null,
    'alt' => '',
    'size' => 'md',
    'shape' => 'circle',
    'status' => null,
    'statusPosition' => 'bottom-right',
    'initials' => null,
    'icon' => null,
    'bordered' => false,
    'borderColor' => 'white',
    'bgColor' => 'gray-200',
    'textColor' => 'gray-700',
    'stacked' => false,
    'tooltip' => null,
])

@php
    // Size classes (square dimensions and font sizes)
    $sizeClasses = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-base',
        'lg' => 'w-12 h-12 text-lg',
        'xl' => 'w-16 h-16 text-xl',
        '2xl' => 'w-20 h-20 text-2xl',
    ][$size] ?? 'w-10 h-10 text-base';
    
    // Shape classes
    $shapeClasses = [
        'circle' => 'rounded-full',
        'square' => 'rounded-none',
        'rounded' => 'rounded-md',
    ][$shape] ?? 'rounded-full';
    
    // Status classes
    $statusClasses = [
        'online' => 'bg-green-500',
        'offline' => 'bg-gray-400',
        'busy' => 'bg-red-500',
        'away' => 'bg-yellow-500',
    ][$status] ?? '';
    
    // Status position classes
    $statusPositionClasses = [
        'top-right' => 'top-0 right-0',
        'top-left' => 'top-0 left-0',
        'bottom-right' => 'bottom-0 right-0',
        'bottom-left' => 'bottom-0 left-0',
    ][$statusPosition] ?? 'bottom-0 right-0';
    
    // Status size based on avatar size
    $statusSizeClasses = [
        'xs' => 'w-1.5 h-1.5',
        'sm' => 'w-2 h-2',
        'md' => 'w-2.5 h-2.5',
        'lg' => 'w-3 h-3',
        'xl' => 'w-4 h-4',
        '2xl' => 'w-5 h-5',
    ][$size] ?? 'w-2.5 h-2.5';
    
    // Border classes
    $borderClasses = $bordered ? 'ring-2 ring-' . $borderColor : '';
    
    // Background color for placeholder
    $bgColorClass = 'bg-' . $bgColor;
    
    // Text color for initials
    $textColorClass = 'text-' . $textColor;
    
    // If stacked, add specific classes
    $stackedClasses = $stacked ? '-ml-2 first:ml-0 border-2 border-white' : '';
@endphp

<div 
    {{ $attributes->merge(['class' => 'relative inline-flex flex-shrink-0 ' . $sizeClasses . ' ' . $shapeClasses . ' ' . $borderClasses . ' ' . $stackedClasses]) }}
    @if($tooltip) title="{{ $tooltip }}" data-tooltip="{{ $tooltip }}" @endif
>
    @if($src)
        <!-- Image avatar -->
        <img 
            src="{{ $src }}" 
            alt="{{ $alt }}" 
            class="w-full h-full object-cover {{ $shapeClasses }}"
            @if($tooltip) x-tooltip="{{ $tooltip }}" @endif
        >
    @elseif($initials)
        <!-- Initials avatar -->
        <div class="flex items-center justify-center w-full h-full {{ $bgColorClass }} {{ $textColorClass }} {{ $shapeClasses }} font-medium uppercase">
            {{ $initials }}
        </div>
    @elseif($icon)
        <!-- Icon avatar -->
        <div class="flex items-center justify-center w-full h-full {{ $bgColorClass }} {{ $shapeClasses }}">
            <span class="{{ $textColorClass }}">
                @if(is_string($icon))
                    <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        @if($icon === 'user')
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        @elseif($icon === 'users')
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        @endif
                    </svg>
                @else
                    {{ $icon }}
                @endif
            </span>
        </div>
    @else
        <!-- Fallback avatar (user icon) -->
        <div class="flex items-center justify-center w-full h-full {{ $bgColorClass }} {{ $shapeClasses }}">
            <svg class="w-1/2 h-1/2 {{ $textColorClass }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
        </div>
    @endif
    
    @if($status)
        <!-- Status indicator -->
        <span class="absolute {{ $statusPositionClasses }} block {{ $statusSizeClasses }} {{ $statusClasses }} rounded-full ring-2 ring-white"></span>
    @endif
    
    <!-- Extra slot for custom content -->
    {{ $slot ?? '' }}
</div> 