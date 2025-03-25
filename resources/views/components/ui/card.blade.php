@props([
    'title' => null,
    'subtitle' => null,
    'elevated' => true,
    'bordered' => false,
    'rounded' => 'md',
    'padding' => 'normal',
    'headerClass' => '',
    'bodyClass' => '',
    'footerClass' => '',
    'actions' => null,
    'hasHeader' => true
])

@php
    $elevationClasses = $elevated ? 'shadow-md hover:shadow-lg transition-shadow duration-300' : '';
    $borderClasses = $bordered ? 'border border-gray-200' : '';
    
    $roundedSizes = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full'
    ];
    
    $roundedClass = $roundedSizes[$rounded] ?? $roundedSizes['md'];
    
    $paddingSizes = [
        'none' => 'p-0',
        'tight' => 'p-3',
        'normal' => 'p-4',
        'loose' => 'p-6',
        'extra' => 'p-8'
    ];
    
    $bodyPadding = $paddingSizes[$padding] ?? $paddingSizes['normal'];
    
    // Adjust header padding based on body padding
    $headerPadding = match($padding) {
        'none' => 'px-0 pt-0 pb-0',
        'tight' => 'px-3 pt-3 pb-2',
        'normal' => 'px-4 pt-4 pb-2',
        'loose' => 'px-6 pt-6 pb-3',
        'extra' => 'px-8 pt-8 pb-4',
        default => 'px-4 pt-4 pb-2'
    };
    
    // Adjust footer padding based on body padding
    $footerPadding = match($padding) {
        'none' => 'px-0 pt-0 pb-0',
        'tight' => 'px-3 pt-2 pb-3',
        'normal' => 'px-4 pt-2 pb-4',
        'loose' => 'px-6 pt-3 pb-6',
        'extra' => 'px-8 pt-4 pb-8',
        default => 'px-4 pt-2 pb-4'
    };
@endphp

<div {{ $attributes->merge(['class' => "bg-white $elevationClasses $borderClasses $roundedClass overflow-hidden"]) }}>
    @if($title && $hasHeader)
        <div class="{{ $headerPadding }} {{ $headerClass }} border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
                    @if($subtitle)
                        <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
                    @endif
                </div>
                @if($actions)
                    <div class="ml-4 flex-shrink-0 flex">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    <div class="{{ $bodyPadding }} {{ $bodyClass }}">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="{{ $footerPadding }} {{ $footerClass }} border-t border-gray-100 bg-gray-50">
            {{ $footer }}
        </div>
    @endif
</div> 