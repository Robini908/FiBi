@props([
    'align' => 'right',
    'width' => 'w-48',
    'contentClasses' => 'py-1 bg-white',
    'trigger' => '',
    'persistent' => false,
    'closeOnClick' => true,
])

@php
    $alignmentClasses = [
        'left' => 'origin-top-left left-0',
        'top' => 'origin-top',
        'right' => 'origin-top-right right-0',
        'bottom' => 'origin-bottom',
    ][$align] ?? 'origin-top-right right-0';
@endphp

<div 
    x-data="{ 
        open: false,
        closeOnEsc(e) {
            if (e.key === 'Escape') {
                this.open = false;
            }
        },
        closeOnClickAway(e) {
            if (!this.$refs.panel.contains(e.target) && !{{ $persistent ? 'true' : 'false' }}) {
                this.open = false;
            }
        }
    }" 
    @keydown.window="closeOnEsc"
    @click.away="closeOnClickAway"
    class="relative"
>
    <!-- Dropdown trigger -->
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <!-- Dropdown menu -->
    <div
        x-show="open"
        x-ref="panel"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
        @if($closeOnClick)
        @click.outside="open = false"
        @endif
        x-cloak
    >
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $slot }}
        </div>
    </div>
</div> 