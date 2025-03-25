@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => false,
    'icon' => true,
    'rounded' => 'md',
    'bordered' => false,
    'elevated' => false,
])

@php
    // Type-based styles
    $typeStyles = [
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-300',
            'text' => 'text-blue-800',
            'icon' => '<svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                      </svg>',
            'dismiss' => 'text-blue-500 hover:text-blue-700',
        ],
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-300',
            'text' => 'text-green-800',
            'icon' => '<svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                      </svg>',
            'dismiss' => 'text-green-500 hover:text-green-700',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-300',
            'text' => 'text-yellow-800',
            'icon' => '<svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>',
            'dismiss' => 'text-yellow-500 hover:text-yellow-700',
        ],
        'danger' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-300',
            'text' => 'text-red-800',
            'icon' => '<svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                      </svg>',
            'dismiss' => 'text-red-500 hover:text-red-700',
        ],
        'dark' => [
            'bg' => 'bg-gray-100',
            'border' => 'border-gray-400',
            'text' => 'text-gray-800',
            'icon' => '<svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                      </svg>',
            'dismiss' => 'text-gray-500 hover:text-gray-700',
        ],
        'light' => [
            'bg' => 'bg-gray-50',
            'border' => 'border-gray-200',
            'text' => 'text-gray-700',
            'icon' => '<svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                      </svg>',
            'dismiss' => 'text-gray-400 hover:text-gray-600',
        ],
    ];
    
    // Get style for the selected type
    $style = $typeStyles[$type] ?? $typeStyles['info'];
    
    // Build classes
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-md';
    
    $borderClass = $bordered ? 'border ' . $style['border'] : '';
    $elevationClass = $elevated ? 'shadow-md' : '';
@endphp

<div 
    x-data="{ open: true }" 
    x-show="open" 
    class="{{ $style['bg'] }} {{ $style['text'] }} p-4 {{ $borderClass }} {{ $roundedClass ?? $roundedClasses }} {{ $elevationClass }}"
    {{ $attributes }}
>
    <div class="flex">
        @if($icon)
            <div class="flex-shrink-0">
                {!! $style['icon'] !!}
            </div>
        @endif
        
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $style['text'] }}">{{ $title }}</h3>
            @endif
            
            <div class="@if($title) mt-2 @endif text-sm">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button 
                        type="button" 
                        @click="open = false" 
                        class="{{ $style['dismiss'] }} inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ $type === 'light' ? 'gray' : $type }}-50 focus:ring-{{ $type === 'light' ? 'gray' : $type }}-500"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div> 