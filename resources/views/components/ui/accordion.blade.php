@props([
    'items' => [],
    'collapsible' => true,
    'multiple' => false,
    'bordered' => true,
    'rounded' => 'md',
    'spaced' => false,
    'iconPosition' => 'right',
    'defaultOpen' => null,
])

@php
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-md';
    
    // Convert defaultOpen to array if string or number
    if (isset($defaultOpen) && !is_array($defaultOpen)) {
        $defaultOpen = [$defaultOpen];
    }
    
    $itemClass = $spaced ? 'mb-3' : 'border-b';
    $lastItemClass = $spaced ? 'mb-0' : 'border-b-0';
@endphp

<div 
    x-data="{ 
        openItems: {{ isset($defaultOpen) ? json_encode($defaultOpen) : '[]' }},
        
        isOpen(id) {
            return this.openItems.includes(id);
        },
        
        toggle(id) {
            if (this.isOpen(id)) {
                this.openItems = this.openItems.filter(item => item !== id);
            } else {
                @if(!$multiple)
                    this.openItems = [];
                @endif
                this.openItems.push(id);
            }
        }
    }"
    class="w-full {{ $bordered ? 'border border-gray-200 divide-y divide-gray-200' : 'divide-y divide-gray-200' }} {{ $roundedClasses }}"
    {{ $attributes }}
>
    @foreach($items as $index => $item)
        <div 
            class="{{ $index === count($items) - 1 ? $lastItemClass : $itemClass }}"
            :class="{ 'border-b-0': isOpen({{ $index }}) && !{{ $spaced ? 'true' : 'false' }} }"
            id="accordion-item-{{ $index }}"
        >
            <h3>
                <button 
                    type="button"
                    class="flex items-center justify-between w-full px-4 py-3 text-left text-gray-700 hover:bg-gray-50 focus:outline-none focus:bg-gray-50"
                    :class="{ 'bg-gray-50': isOpen({{ $index }}) }"
                    @click="toggle({{ $index }})"
                    :aria-expanded="isOpen({{ $index }})"
                    aria-controls="accordion-content-{{ $index }}"
                    @if(!$collapsible && $index === 0)
                        disabled
                    @endif
                >
                    <div class="flex items-center">
                        @if($iconPosition === 'left')
                            <span class="mr-2 transform transition-transform duration-200" :class="{ 'rotate-90': isOpen({{ $index }}) }">
                                <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @endif
                        
                        <span 
                            class="font-medium text-sm sm:text-base"
                            :class="{ 'font-semibold': isOpen({{ $index }}) }"
                        >
                            {{ $item['title'] ?? 'Accordion Item ' . ($index + 1) }}
                        </span>
                    </div>
                    
                    @if($iconPosition === 'right')
                        <span class="transform transition-transform duration-200" :class="{ 'rotate-180': isOpen({{ $index }}) }">
                            <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </button>
            </h3>
            
            <div 
                id="accordion-content-{{ $index }}"
                x-show="isOpen({{ $index }})"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                @if(!isset($defaultOpen) || !in_array($index, $defaultOpen))
                    style="display: none;"
                @endif
                class="px-4 pb-4 pt-0"
            >
                <div class="prose max-w-none text-gray-700 text-sm sm:text-base">
                    @if(isset($item['content']))
                        {!! $item['content'] !!}
                    @else
                        <p>Content for accordion item {{ $index + 1 }}.</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div> 