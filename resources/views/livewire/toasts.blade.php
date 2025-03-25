<div class="fixed z-50 top-4 right-4 space-y-4 w-80 md:w-96">
    @foreach($toasts as $toast)
        <div 
            wire:key="toast-{{ $toast['id'] }}"
            x-data="{ 
                show: true, 
                init() {
                    setTimeout(() => {
                        this.show = false;
                        setTimeout(() => @this.remove('{{ $toast['id'] }}'), 300);
                    }, {{ $toast['duration'] }});
                } 
            }" 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-8"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-8"
            class="relative"
        >
            <x-toast 
                :type="$toast['type']" 
                :message="$toast['message']" 
                :title="$toast['title']"
                :dismissible="true"
                :duration="99999999"
            />
        </div>
    @endforeach
</div> 