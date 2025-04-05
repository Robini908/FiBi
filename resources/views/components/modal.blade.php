@props([
    'id' => null,
    'show' => false,
    'maxWidth' => '2xl',
    'closeable' => true,
    'centered' => true,
    'backdrop' => true,
    'overflow' => 'auto',
    'wireClose' => null
])

@php
$id = $id ?? md5($attributes->wire('model'));

$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
    '7xl' => 'sm:max-w-7xl',
    'full' => 'sm:max-w-full',
][$maxWidth];
@endphp

<div
    x-data="{ show: @js($show) }"
    x-on:close.stop="show = false"
    @if($wireClose)
        @entangle($wireClose).live="show"
    @endif
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="fixed inset-0 {{ $overflow === 'auto' ? 'overflow-y-auto' : 'overflow-hidden' }} px-4 py-6 sm:px-0 z-50 flex items-center justify-center"
    style="display: none;"
>
    @if($backdrop)
    <div 
        x-show="show" 
        class="fixed inset-0 transform transition-all" 
        x-on:click="show = false" 
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
    </div>
    @endif

    <div
        x-show="show"
        class="bg-white rounded-lg overflow-hidden shadow-2xl transform transition-all w-full {{ $maxWidth }} sm:mx-auto"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        @click.away="show = false"
    >
        @if ($closeable)
        <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
            <button
                type="button"
                class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                x-on:click="show = false"
            >
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif

        {{ $slot }}
    </div>
</div> 