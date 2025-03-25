@props([
    'size' => 'md',
    'mode' => 'full', // full, icon, text
    'iconOnly' => false, // Deprecated, use mode="icon" instead
    'textOnly' => false, // Deprecated, use mode="text" instead
    'color' => 'default', // default, white, black, primary
    'href' => null,
])

@php
    // Handle deprecated props
    if ($iconOnly) {
        $mode = 'icon';
    } elseif ($textOnly) {
        $mode = 'text';
    }

    // Define sizes for different elements
    $sizes = [
        'xs' => [
            'container' => 'h-6',
            'icon' => 'h-5 w-5',
            'text' => 'text-sm',
            'spacing' => 'space-x-1',
        ],
        'sm' => [
            'container' => 'h-8',
            'icon' => 'h-6 w-6',
            'text' => 'text-base',
            'spacing' => 'space-x-1.5',
        ],
        'md' => [
            'container' => 'h-10',
            'icon' => 'h-8 w-8',
            'text' => 'text-xl',
            'spacing' => 'space-x-2',
        ],
        'lg' => [
            'container' => 'h-12',
            'icon' => 'h-10 w-10',
            'text' => 'text-2xl',
            'spacing' => 'space-x-3',
        ],
        'xl' => [
            'container' => 'h-16',
            'icon' => 'h-12 w-12',
            'text' => 'text-3xl',
            'spacing' => 'space-x-3',
        ],
    ][$size] ?? [
        'container' => 'h-10',
        'icon' => 'h-8 w-8',
        'text' => 'text-xl',
        'spacing' => 'space-x-2',
    ];
    
    // Define color schemes
    $colors = [
        'default' => [
            'icon' => 'text-green-600 dark:text-green-400',
            'text' => 'text-gray-900 dark:text-white',
        ],
        'white' => [
            'icon' => 'text-white',
            'text' => 'text-white',
        ],
        'black' => [
            'icon' => 'text-black dark:text-black',
            'text' => 'text-black dark:text-black',
        ],
        'primary' => [
            'icon' => 'text-green-600 dark:text-green-400',
            'text' => 'text-green-700 dark:text-green-300',
        ],
    ][$color] ?? [
        'icon' => 'text-green-600 dark:text-green-400',
        'text' => 'text-gray-900 dark:text-white',
    ];
    
    // Determine if we're using a link tag
    $tag = $href ? 'a' : 'div';
    $hrefAttr = $href ? 'href="' . $href . '"' : '';
@endphp

<{{ $tag }} 
    {{ $hrefAttr }} 
    {{ $attributes->merge(['class' => 'inline-flex items-center ' . $sizes['container']]) }}
>
    @if($mode !== 'text')
        <div class="flex-shrink-0 {{ $colors['icon'] }}">
            <svg class="{{ $sizes['icon'] }}" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.5 4.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
                <path d="M13.5 4.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
                <path d="M8.5 9.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
                <path d="M13.5 9.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
                <path d="M8.5 14.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
                <path d="M20.75 2h-17.5c-1.24 0-2.25 1.01-2.25 2.25v15.5c0 1.24 1.01 2.25 2.25 2.25h17.5c1.24 0 2.25-1.01 2.25-2.25v-15.5c0-1.24-1.01-2.25-2.25-2.25zm.25 17.75c0 .14-.11.25-.25.25h-17.5c-.14 0-.25-.11-.25-.25v-15.5c0-.14.11-.25.25-.25h17.5c.14 0 .25.11.25.25v15.5z"/>
                <path d="M16 14.5a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg>
        </div>
    @endif
    
    @if($mode !== 'icon')
        <div class="{{ $mode === 'full' ? $sizes['spacing'] : '' }} {{ $colors['text'] }} font-bold {{ $sizes['text'] }}">
            Mbuku School
        </div>
    @endif
</{{ $tag }}> 