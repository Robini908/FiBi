@props([
    'title' => '',
    'value' => '',
    'subValue' => null,
    'icon' => null,
    'iconBg' => 'primary',
    'trend' => null,
    'trendValue' => null,
    'trendDirection' => null,
    'footer' => null,
    'variant' => 'default',
    'href' => null,
    'loading' => false,
    'bordered' => true,
    'elevated' => false,
    'rounded' => 'md',
])

@php
    // Variant styles
    $variants = [
        'default' => [
            'bg' => 'bg-white dark:bg-gray-800',
            'title' => 'text-gray-500 dark:text-gray-400',
            'value' => 'text-gray-900 dark:text-white',
            'subValue' => 'text-gray-500 dark:text-gray-400',
            'footer' => 'text-gray-500 dark:text-gray-400',
        ],
        'primary' => [
            'bg' => 'bg-green-50 dark:bg-green-900',
            'title' => 'text-green-700 dark:text-green-200',
            'value' => 'text-green-900 dark:text-white',
            'subValue' => 'text-green-600 dark:text-green-300',
            'footer' => 'text-green-600 dark:text-green-300',
        ],
        'secondary' => [
            'bg' => 'bg-gray-50 dark:bg-gray-700',
            'title' => 'text-gray-600 dark:text-gray-300',
            'value' => 'text-gray-900 dark:text-white',
            'subValue' => 'text-gray-500 dark:text-gray-400',
            'footer' => 'text-gray-500 dark:text-gray-400',
        ],
        'success' => [
            'bg' => 'bg-green-50 dark:bg-green-900',
            'title' => 'text-green-700 dark:text-green-200',
            'value' => 'text-green-900 dark:text-white',
            'subValue' => 'text-green-600 dark:text-green-300',
            'footer' => 'text-green-600 dark:text-green-300',
        ],
        'info' => [
            'bg' => 'bg-blue-50 dark:bg-blue-900',
            'title' => 'text-blue-700 dark:text-blue-200',
            'value' => 'text-blue-900 dark:text-white',
            'subValue' => 'text-blue-600 dark:text-blue-300',
            'footer' => 'text-blue-600 dark:text-blue-300',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50 dark:bg-yellow-900',
            'title' => 'text-yellow-700 dark:text-yellow-200',
            'value' => 'text-yellow-900 dark:text-white',
            'subValue' => 'text-yellow-600 dark:text-yellow-300',
            'footer' => 'text-yellow-600 dark:text-yellow-300',
        ],
        'danger' => [
            'bg' => 'bg-red-50 dark:bg-red-900',
            'title' => 'text-red-700 dark:text-red-200',
            'value' => 'text-red-900 dark:text-white',
            'subValue' => 'text-red-600 dark:text-red-300',
            'footer' => 'text-red-600 dark:text-red-300',
        ],
    ][$variant] ?? [
        'bg' => 'bg-white dark:bg-gray-800',
        'title' => 'text-gray-500 dark:text-gray-400',
        'value' => 'text-gray-900 dark:text-white',
        'subValue' => 'text-gray-500 dark:text-gray-400',
        'footer' => 'text-gray-500 dark:text-gray-400',
    ];
    
    // Icon background styles
    $iconBgClasses = [
        'primary' => 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-200',
        'secondary' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
        'success' => 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-200',
        'info' => 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-200',
        'warning' => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-800 dark:text-yellow-200',
        'danger' => 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-200',
        'light' => 'bg-gray-50 text-gray-500 dark:bg-gray-600 dark:text-gray-300',
        'dark' => 'bg-gray-800 text-gray-100 dark:bg-gray-900 dark:text-gray-100',
    ][$iconBg] ?? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-200';
    
    // Trend classes
    $trendClasses = '';
    if ($trendDirection === 'up') {
        $trendClasses = 'text-green-600 dark:text-green-400';
    } elseif ($trendDirection === 'down') {
        $trendClasses = 'text-red-600 dark:text-red-400';
    } elseif ($trendDirection === 'neutral') {
        $trendClasses = 'text-gray-500 dark:text-gray-400';
    }
    
    // Rounded corners
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-md';
    
    // Border and elevation
    $borderClass = $bordered ? 'border border-gray-200 dark:border-gray-700' : '';
    $elevationClass = $elevated ? 'shadow-md' : '';
    
    // Card type - clickable or static
    $cardTag = $href ? 'a' : 'div';
    $hrefAttr = $href ? 'href="' . $href . '"' : '';
    $clickableClasses = $href ? 'transition-colors hover:bg-gray-50 dark:hover:bg-gray-700' : '';
@endphp

<{{ $cardTag }} {{ $hrefAttr }} {{ $attributes->merge(['class' => "{$variants['bg']} {$borderClass} {$elevationClass} {$roundedClasses} {$clickableClasses} p-6 overflow-hidden"]) }}>
    @if($loading)
        <div class="animate-pulse">
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mb-4"></div>
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/4 mb-6"></div>
            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
        </div>
    @else
        <div class="flex justify-between items-start">
            <div>
                @if($title)
                    <p class="text-sm font-medium {{ $variants['title'] }} truncate">
                        {{ $title }}
                    </p>
                @endif
                
                <div class="mt-2 flex items-baseline">
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold {{ $variants['value'] }}">
                            {{ $value }}
                        </p>
                        
                        @if($subValue)
                            <p class="ml-2 text-sm {{ $variants['subValue'] }}">
                                {{ $subValue }}
                            </p>
                        @endif
                    </div>
                    
                    @if($trend || $trendValue)
                        <span class="ml-2 text-sm font-medium flex items-center {{ $trendClasses }}">
                            @if($trendDirection === 'up')
                                <svg class="self-center flex-shrink-0 h-4 w-4 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($trendDirection === 'down')
                                <svg class="self-center flex-shrink-0 h-4 w-4 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($trendDirection === 'neutral')
                                <svg class="self-center flex-shrink-0 h-4 w-4 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a1 1 0 01-1 1H3a1 1 0 110-2h14a1 1 0 011 1z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            
                            {{ $trendValue ?? $trend }}
                        </span>
                    @endif
                </div>
            </div>
            
            @if($icon)
                <div class="p-2.5 {{ $iconBgClasses }} rounded-full">
                    {!! $icon !!}
                </div>
            @endif
        </div>
        
        @if($footer)
            <div class="mt-4 text-sm {{ $variants['footer'] }}">
                {{ $footer }}
            </div>
        @endif
        
        @if($slot->isNotEmpty())
            <div class="mt-4">
                {{ $slot }}
            </div>
        @endif
    @endif
</{{ $cardTag }}> 