@props([
    'id' => '',
    'maxWidth' => '2xl',
    'title' => '',
    'icon' => null,
    'iconBackground' => 'bg-green-100',
    'iconColor' => 'text-green-600',
    'livewireOpen' => null,
    'livewireClose' => null,
    'showClose' => true,
    'padding' => 'p-6',
    'contentPadding' => 'p-0',
    'fullscreen' => false,
    'showFooter' => true
])

@php
$maxWidthClass = match($maxWidth) {
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
    default => 'sm:max-w-2xl',
};

$fullscreenClass = $fullscreen ? 'sm:max-w-full sm:m-0 sm:h-screen sm:rounded-none' : '';
@endphp

<div 
    x-data="{ 
        open: @if ($livewireOpen) @entangle($livewireOpen).live @else false @endif,
        animateIn: false
    }"
    x-init="$watch('open', value => {
        if (value) {
            document.body.classList.add('overflow-hidden');
            setTimeout(() => animateIn = true, 50);
        } else {
            animateIn = false;
            setTimeout(() => {
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }
    })"
    x-show="open"
    @if ($livewireClose)
    @keydown.escape.window="$wire.{{ $livewireClose }}()"
    @else
    @keydown.escape.window="open = false"
    @endif
    id="{{ $id }}"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none"
    x-cloak
>
    <div class="flex items-end justify-center min-h-screen text-center sm:block">
        <!-- Background overlay -->
        <div 
            x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" 
            @if ($livewireClose)
            @click="$wire.{{ $livewireClose }}()"
            @else
            @click="open = false"
            @endif
            aria-hidden="true"
        ></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div 
            x-show="open"
            x-bind:class="{ 'translate-y-0 opacity-100 sm:scale-100': animateIn, 'translate-y-4 opacity-0 sm:scale-95': !animateIn }"
            class="inline-block w-full px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle {{ $maxWidthClass }} {{ $fullscreenClass }} {{ $padding }} sm:w-full"
        >
            @if($showClose)
            <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                <button 
                    @if ($livewireClose)
                    @click="$wire.{{ $livewireClose }}()"
                    @else
                    @click="open = false"
                    @endif
                    type="button" 
                    class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @endif

            <div class="w-full">
                <!-- Header -->
                @if($title || $icon)
                <div class="flex items-start mb-4 space-x-4">
                    @if($icon)
                    <div class="flex-shrink-0 mx-auto sm:mx-0">
                        <div class="flex items-center justify-center rounded-full h-12 w-12 {{ $iconBackground }} sm:h-10 sm:w-10">
                            <div class="{{ $iconColor }}">
                                {!! $icon !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($title)
                    <div class="mt-0 text-center sm:text-left flex-grow">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            {{ $title }}
                        </h3>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Content -->
                <div class="{{ $contentPadding }}">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                @if(isset($footer) && $showFooter)
                <div class="mt-5 sm:mt-6">
                    {{ $footer }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div> 