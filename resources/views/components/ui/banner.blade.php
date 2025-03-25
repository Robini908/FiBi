@props([
    'type' => 'default',
    'title' => null,
    'dismissible' => true,
    'sticky' => false,
    'icon' => null,
    'showClose' => true,
    'action' => null,
    'actionText' => null,
    'actionUrl' => '#',
    'position' => 'top',
])

@php
    // Banner types with their respective styles
    $types = [
        'default' => [
            'wrapper' => 'bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700',
            'container' => 'text-gray-500 dark:text-gray-400',
            'title' => 'text-gray-900 dark:text-white',
            'icon' => 'text-gray-400 dark:text-gray-500',
            'close' => 'text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400',
            'action' => 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600'
        ],
        'info' => [
            'wrapper' => 'bg-blue-50 dark:bg-blue-900/50 border-b border-blue-200 dark:border-blue-800',
            'container' => 'text-blue-700 dark:text-blue-300',
            'title' => 'text-blue-900 dark:text-blue-100',
            'icon' => 'text-blue-400 dark:text-blue-300',
            'close' => 'text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300',
            'action' => 'bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-800 dark:text-blue-200 dark:hover:bg-blue-700 border border-blue-300 dark:border-blue-700'
        ],
        'success' => [
            'wrapper' => 'bg-green-50 dark:bg-green-900/50 border-b border-green-200 dark:border-green-800',
            'container' => 'text-green-700 dark:text-green-300',
            'title' => 'text-green-900 dark:text-green-100',
            'icon' => 'text-green-400 dark:text-green-300',
            'close' => 'text-green-500 hover:text-green-600 dark:text-green-400 dark:hover:text-green-300',
            'action' => 'bg-green-50 text-green-700 hover:bg-green-100 dark:bg-green-800 dark:text-green-200 dark:hover:bg-green-700 border border-green-300 dark:border-green-700'
        ],
        'warning' => [
            'wrapper' => 'bg-yellow-50 dark:bg-yellow-900/50 border-b border-yellow-200 dark:border-yellow-800',
            'container' => 'text-yellow-700 dark:text-yellow-300',
            'title' => 'text-yellow-900 dark:text-yellow-100',
            'icon' => 'text-yellow-400 dark:text-yellow-300',
            'close' => 'text-yellow-500 hover:text-yellow-600 dark:text-yellow-400 dark:hover:text-yellow-300',
            'action' => 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100 dark:bg-yellow-800 dark:text-yellow-200 dark:hover:bg-yellow-700 border border-yellow-300 dark:border-yellow-700'
        ],
        'danger' => [
            'wrapper' => 'bg-red-50 dark:bg-red-900/50 border-b border-red-200 dark:border-red-800',
            'container' => 'text-red-700 dark:text-red-300',
            'title' => 'text-red-900 dark:text-red-100',
            'icon' => 'text-red-400 dark:text-red-300',
            'close' => 'text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300',
            'action' => 'bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-800 dark:text-red-200 dark:hover:bg-red-700 border border-red-300 dark:border-red-700'
        ],
    ];
    
    // Get the styles for the specified type
    $currentType = $types[$type] ?? $types['default'];
    
    // Position class
    $positionClass = [
        'top' => 'top-0 inset-x-0',
        'bottom' => 'bottom-0 inset-x-0',
    ][$position] ?? 'top-0 inset-x-0';
    
    // Default icons based on type
    $defaultIcons = [
        'default' => '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>',
        'info' => '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>',
        'success' => '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'warning' => '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>',
        'danger' => '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5-2.214 2.398-3.813 2.398H6.51c-1.6 0-2.947-.898-3.813-2.398C1.83 17.76 4.903 20 8.51 20c3.605 0 6.677-2.24 7.803-5.874M12 15.75h.007v.008H12v-.008z" /></svg>',
    ];
    
    // Set the appropriate icon
    $iconHtml = $icon ?? $defaultIcons[$type];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    @if($sticky)
    class="fixed z-50 {{ $positionClass }}"
    @else
    class="{{ $currentType['wrapper'] }}"
    @endif
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform -translate-y-full opacity-0"
    x-transition:enter-end="transform translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="transform translate-y-0 opacity-100"
    x-transition:leave-end="transform -translate-y-full opacity-0"
    {{ $attributes }}
>
    @if($sticky)
    <div class="{{ $currentType['wrapper'] }} shadow">
    @endif
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex flex-wrap items-center justify-between">
            <div class="flex items-center flex-1 min-w-0">
                @if($iconHtml)
                    <span class="{{ $currentType['icon'] }} mr-3">
                        {!! $iconHtml !!}
                    </span>
                @endif
                <div class="min-w-0 flex-1 {{ $currentType['container'] }}">
                    @if($title)
                        <p class="text-sm font-medium {{ $currentType['title'] }}">
                            {{ $title }}
                        </p>
                    @endif
                    
                    <p class="text-sm @if($title) mt-1 @endif">
                        {{ $slot }}
                    </p>
                </div>
            </div>
            
            <div class="flex-shrink-0 flex items-center mt-3 sm:mt-0 sm:ml-3">
                @if($action && $actionText)
                    <a href="{{ $actionUrl }}" class="rounded-md px-3 py-1.5 text-sm font-medium {{ $currentType['action'] }}">
                        {{ $actionText }}
                    </a>
                @endif
                
                @if($dismissible && $showClose)
                    <button 
                        type="button" 
                        @click="show = false"
                        class="ml-3 flex-shrink-0 {{ $currentType['close'] }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
    
    @if($sticky)
    </div>
    @endif
</div> 