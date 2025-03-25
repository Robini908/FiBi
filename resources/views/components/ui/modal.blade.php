@props([
    'id' => 'modal',
    'maxWidth' => '2xl',
    'title' => null,
    'closeButton' => true,
    'centered' => true,
    'fullScreen' => false,
    'persistent' => false,
    'zIndex' => 50
])

@php
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
][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

<div
    x-data="{ 
        open: false,
        openModal() {
            this.open = true;
            document.body.classList.add('overflow-hidden');
        },
        closeModal() {
            this.open = false;
            document.body.classList.remove('overflow-hidden');
        },
        init() {
            this.$watch('open', value => {
                if (value === true) {
                    this.$nextTick(() => {
                        this.$refs.modalPanel.focus();
                        document.addEventListener('keydown', e => {
                            if (e.key === 'Escape' && !{{ $persistent ? 'true' : 'false' }}) {
                                this.closeModal();
                            }
                        });
                    });
                }
            });
        }
    }"
    x-on:open-modal.window="$event.detail == '{{ $id }}' ? openModal() : null"
    x-on:close-modal.window="$event.detail == '{{ $id }}' ? closeModal() : null"
    x-on:close.stop="if(!{{ $persistent ? 'true' : 'false' }}) closeModal()"
    x-on:keydown.escape.window="if(!{{ $persistent ? 'true' : 'false' }}) closeModal()"
    x-on:keydown.tab.prevent="$event.shiftKey || $nextTick(() => $refs.modalPanel.focus())"
    x-on:keydown.shift.tab.prevent="$nextTick(() => $refs.modalPanel.focus())"
    x-id="['modal-title']"
    class="relative z-{{ $zIndex }}"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-cloak
>
    <!-- Background overlay -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        @if(!$persistent)
        @click="closeModal()"
        @endif
    ></div>

    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="fixed inset-0 z-10 overflow-y-auto"
    >
        <div class="flex {{ $centered ? 'items-center' : 'items-end' }} justify-center min-h-full p-4 text-center sm:p-0">
            <div 
                x-ref="modalPanel"
                class="{{ $fullScreen ? 'w-screen h-screen m-0 rounded-none' : 'relative w-full ' . $maxWidth . ' rounded-lg' }} bg-white text-left overflow-hidden shadow-xl transform transition-all sm:my-8"
                @if(!$persistent)
                @click.outside="closeModal()"
                @endif
                tabindex="-1"
                x-bind:aria-labelledby="$id('modal-title')"
            >
                @if($title || $closeButton)
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    @if($title)
                    <h3 class="text-lg font-medium text-gray-900" x-bind:id="$id('modal-title')">
                        {{ $title }}
                    </h3>
                    @endif
                    
                    @if($closeButton)
                    <button 
                        type="button" 
                        class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" 
                        @click="closeModal()"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    @endif
                </div>
                @endif

                <div class="px-6 py-4">
                    {{ $slot }}
                </div>

                @if(isset($footer))
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    {{ $footer }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    window.openModal = (id) => {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: id }));
    }
    
    window.closeModal = (id) => {
        window.dispatchEvent(new CustomEvent('close-modal', { detail: id }));
    }
</script> 