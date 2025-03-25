@props([
    'id' => 'slide-over-' . uniqid(),
    'title' => null,
    'subtitle' => null,
    'position' => 'right',
    'size' => 'md',
    'showClose' => true,
    'closeOnBackdrop' => true,
    'permanent' => false,
    'zIndex' => 'z-50',
])

@php
    // Size classes
    $sizes = [
        'xs' => 'max-w-xs',
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '6xl' => 'max-w-6xl',
        '7xl' => 'max-w-7xl',
        'full' => 'max-w-full',
    ][$size] ?? 'max-w-md';
    
    // Position classes
    $positionClasses = [
        'right' => 'right-0',
        'left' => 'left-0',
    ][$position] ?? 'right-0';
    
    // Transition classes based on position
    $transitionClasses = [
        'right' => [
            'enter' => 'transform transition ease-in-out duration-300 sm:duration-500',
            'enter-start' => 'translate-x-full',
            'enter-end' => 'translate-x-0',
            'leave' => 'transform transition ease-in-out duration-300 sm:duration-500',
            'leave-start' => 'translate-x-0',
            'leave-end' => 'translate-x-full',
        ],
        'left' => [
            'enter' => 'transform transition ease-in-out duration-300 sm:duration-500',
            'enter-start' => '-translate-x-full',
            'enter-end' => 'translate-x-0',
            'leave' => 'transform transition ease-in-out duration-300 sm:duration-500',
            'leave-start' => 'translate-x-0',
            'leave-end' => '-translate-x-full',
        ],
    ][$position] ?? [
        'enter' => 'transform transition ease-in-out duration-300 sm:duration-500',
        'enter-start' => 'translate-x-full',
        'enter-end' => 'translate-x-0',
        'leave' => 'transform transition ease-in-out duration-300 sm:duration-500',
        'leave-start' => 'translate-x-0',
        'leave-end' => 'translate-x-full',
    ];
@endphp

<div
    x-data="{
        open: false,
        init() {
            this.$watch('open', value => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
            
            // Listen for the slide-over-open event
            this.$el.addEventListener('slide-over-open', () => { this.open = true; });
        }
    }"
    x-on:keydown.escape.window="open = false"
    x-id="['slide-over-title']"
    {{ $attributes->merge(['class' => 'relative']) }}
    id="{{ $id }}"
>
    <!-- Trigger -->
    <div x-on:click="open = true">
        {{ $trigger ?? '' }}
    </div>
  
    <!-- Backdrop -->
    <div 
        x-show="open"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm {{ $zIndex }}"
        x-on:click="{{ $closeOnBackdrop ? 'open = false' : '' }}"
    ></div>
  
    <!-- Slide-over panel -->
    <div
        x-show="open"
        x-trap.noscroll="open"
        x-transition:enter="{{ $transitionClasses['enter'] }}"
        x-transition:enter-start="{{ $transitionClasses['enter-start'] }}"
        x-transition:enter-end="{{ $transitionClasses['enter-end'] }}"
        x-transition:leave="{{ $transitionClasses['leave'] }}"
        x-transition:leave-start="{{ $transitionClasses['leave-start'] }}"
        x-transition:leave-end="{{ $transitionClasses['leave-end'] }}"
        class="fixed inset-y-0 {{ $positionClasses }} {{ $sizes }} w-full flex {{ $zIndex }} pointer-events-none"
        x-cloak
    >
        <div class="flex flex-col h-full w-full bg-white dark:bg-gray-800 shadow-xl overflow-y-auto pointer-events-auto">
            <!-- Header -->
            @if($title || isset($header) || $showClose)
                <div class="px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        @if($title || isset($header))
                            <div>
                                @if($title)
                                    <h2 
                                        class="text-lg font-medium text-gray-900 dark:text-white" 
                                        :id="$id('slide-over-title')"
                                    >
                                        {{ $title }}
                                    </h2>
                                    
                                    @if($subtitle)
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $subtitle }}
                                        </p>
                                    @endif
                                @elseif(isset($header))
                                    {{ $header }}
                                @endif
                            </div>
                        @endif
                        
                        @if($showClose)
                            <div class="ml-3 h-7 flex items-center">
                                <button
                                    type="button"
                                    class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500"
                                    x-on:click="open = false"
                                >
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Body -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            @if(isset($footer))
                <div class="flex-shrink-0 px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div> 