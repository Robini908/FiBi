@props([
    'size' => 'md',
    'color' => 'default', // default, white, black, primary
    'href' => null,
])

@php
    // Define sizes
    $sizes = [
        'xs' => 'h-4 w-4',
        'sm' => 'h-6 w-6',
        'md' => 'h-8 w-8',
        'lg' => 'h-10 w-10',
        'xl' => 'h-12 w-12',
        '2xl' => 'h-16 w-16',
    ][$size] ?? 'h-8 w-8';
    
    // Define color schemes
    $colors = [
        'default' => 'text-green-600 dark:text-green-400',
        'white' => 'text-white',
        'black' => 'text-black',
        'primary' => 'text-green-600 dark:text-green-400',
    ][$color] ?? 'text-green-600 dark:text-green-400';
    
    // Determine if we're using a link tag
    $tag = $href ? 'a' : 'span';
    $hrefAttr = $href ? 'href="' . $href . '"' : '';
@endphp

<{{ $tag }} {{ $hrefAttr }} {{ $attributes->merge(['class' => 'inline-flex ' . $colors]) }}>
    <svg class="{{ $sizes }}" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M8.5 4.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
        <path d="M13.5 4.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
        <path d="M8.5 9.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
        <path d="M13.5 9.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
        <path d="M8.5 14.5a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-2z"/>
        <path d="M20.75 2h-17.5c-1.24 0-2.25 1.01-2.25 2.25v15.5c0 1.24 1.01 2.25 2.25 2.25h17.5c1.24 0 2.25-1.01 2.25-2.25v-15.5c0-1.24-1.01-2.25-2.25-2.25zm.25 17.75c0 .14-.11.25-.25.25h-17.5c-.14 0-.25-.11-.25-.25v-15.5c0-.14.11-.25.25-.25h17.5c.14 0 .25.11.25.25v15.5z"/>
        <path d="M16 14.5a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </svg>
</{{ $tag }}> 