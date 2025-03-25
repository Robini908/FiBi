@props([
    'text' => '',
    'label' => 'Copy',
    'successLabel' => 'Copied!',
    'errorLabel' => 'Failed to copy',
    'size' => 'md',
    'timeout' => 2000,
    'variant' => 'primary',
    'iconOnly' => false,
    'tooltip' => true,
    'animated' => true,
    'position' => 'right',
])

@php
    // Button size classes
    $sizeClasses = [
        'xs' => 'text-xs py-1 px-2',
        'sm' => 'text-sm py-1 px-2',
        'md' => 'text-sm py-2 px-3',
        'lg' => 'text-base py-2 px-4',
        'xl' => 'text-lg py-3 px-5',
    ][$size] ?? 'text-sm py-2 px-3';
    
    // Icon only size adjustment
    $iconSizeClasses = [
        'xs' => 'p-1',
        'sm' => 'p-1.5',
        'md' => 'p-2',
        'lg' => 'p-2.5',
        'xl' => 'p-3',
    ][$size] ?? 'p-2';
    
    // Icon size classes
    $iconDimensions = [
        'xs' => 'w-3.5 h-3.5',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-7 h-7',
    ][$size] ?? 'w-5 h-5';
    
    // Button variant classes
    $variantClasses = [
        'primary' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white',
        'secondary' => 'bg-gray-500 hover:bg-gray-600 focus:ring-gray-400 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-400 text-white',
        'info' => 'bg-blue-500 hover:bg-blue-600 focus:ring-blue-400 text-white',
        'dark' => 'bg-gray-800 hover:bg-gray-900 focus:ring-gray-700 text-white',
        'light' => 'bg-gray-200 hover:bg-gray-300 focus:ring-gray-100 text-gray-800',
        'link' => 'bg-transparent hover:bg-gray-100 text-green-600 hover:text-green-700',
        'outline' => 'bg-white border border-gray-300 hover:bg-gray-50 text-gray-700',
    ][$variant] ?? 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white';
    
    // Success and error states
    $successClasses = 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white';
    $errorClasses = 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white';
    
    // Position classes for tooltip
    $positionClasses = [
        'top' => 'bottom-full left-1/2 transform -translate-x-1/2 mb-1',
        'bottom' => 'top-full left-1/2 transform -translate-x-1/2 mt-1',
        'left' => 'right-full top-1/2 transform -translate-y-1/2 mr-1',
        'right' => 'left-full top-1/2 transform -translate-y-1/2 ml-1',
    ][$position] ?? 'left-full top-1/2 transform -translate-y-1/2 ml-1';
@endphp

<div 
    x-data="{ 
        copied: false, 
        error: false,
        text: '{{ $text }}',
        timeout: null,
        
        copyToClipboard() {
            const textToCopy = this.text || this.$refs.content.innerText;
            
            try {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    this.copied = true;
                    this.error = false;
                    
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        this.copied = false;
                    }, {{ $timeout }});
                }).catch(() => {
                    this.fallbackCopyToClipboard(textToCopy);
                });
            } catch (err) {
                this.fallbackCopyToClipboard(textToCopy);
            }
        },
        
        fallbackCopyToClipboard(text) {
            // Fallback for older browsers
            try {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                const successful = document.execCommand('copy');
                document.body.removeChild(textArea);
                
                if (successful) {
                    this.copied = true;
                    this.error = false;
                } else {
                    this.error = true;
                    this.copied = false;
                }
                
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    this.copied = false;
                    this.error = false;
                }, {{ $timeout }});
            } catch (err) {
                this.error = true;
                
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    this.error = false;
                }, {{ $timeout }});
            }
        }
    }"
    class="inline-flex items-center"
>
    @if(!$text)
        <div x-ref="content" class="sr-only">{{ $slot }}</div>
    @endif
    
    <button 
        type="button"
        @click="copyToClipboard()"
        :class="{ 
            '{{ $successClasses }}': copied, 
            '{{ $errorClasses }}': error,
            '{{ $variantClasses }}': !copied && !error 
        }"
        class="relative inline-flex items-center justify-center border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $iconOnly ? $iconSizeClasses : $sizeClasses }} transition-all duration-150"
        {{ $attributes }}
    >
        <span class="flex items-center justify-center" x-show="!copied && !error">
            @if(!$iconOnly)
                <span class="mr-1.5">{{ $label }}</span>
            @endif
            
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconDimensions }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
            </svg>
        </span>
        
        <span class="flex items-center justify-center" x-show="copied">
            @if(!$iconOnly)
                <span class="mr-1.5">{{ $successLabel }}</span>
            @endif
            
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconDimensions }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </span>
        
        <span class="flex items-center justify-center" x-show="error">
            @if(!$iconOnly)
                <span class="mr-1.5">{{ $errorLabel }}</span>
            @endif
            
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $iconDimensions }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </span>
        
        @if($tooltip && $iconOnly)
            <div 
                x-cloak 
                x-show="!copied && !error" 
                class="absolute {{ $positionClasses }} bg-gray-900 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10"
                x-transition:enter="{{ $animated ? 'transition ease-out duration-200' : '' }}"
                x-transition:enter-start="{{ $animated ? 'opacity-0 scale-95' : '' }}"
                x-transition:enter-end="{{ $animated ? 'opacity-100 scale-100' : '' }}"
                x-transition:leave="{{ $animated ? 'transition ease-in duration-100' : '' }}"
                x-transition:leave-start="{{ $animated ? 'opacity-100 scale-100' : '' }}"
                x-transition:leave-end="{{ $animated ? 'opacity-0 scale-95' : '' }}"
            >
                {{ $label }}
            </div>
            
            <div 
                x-cloak 
                x-show="copied" 
                class="absolute {{ $positionClasses }} bg-green-600 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10"
                x-transition:enter="{{ $animated ? 'transition ease-out duration-200' : '' }}"
                x-transition:enter-start="{{ $animated ? 'opacity-0 scale-95' : '' }}"
                x-transition:enter-end="{{ $animated ? 'opacity-100 scale-100' : '' }}"
                x-transition:leave="{{ $animated ? 'transition ease-in duration-100' : '' }}"
                x-transition:leave-start="{{ $animated ? 'opacity-100 scale-100' : '' }}"
                x-transition:leave-end="{{ $animated ? 'opacity-0 scale-95' : '' }}"
            >
                {{ $successLabel }}
            </div>
            
            <div 
                x-cloak 
                x-show="error" 
                class="absolute {{ $positionClasses }} bg-red-600 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10"
                x-transition:enter="{{ $animated ? 'transition ease-out duration-200' : '' }}"
                x-transition:enter-start="{{ $animated ? 'opacity-0 scale-95' : '' }}"
                x-transition:enter-end="{{ $animated ? 'opacity-100 scale-100' : '' }}"
                x-transition:leave="{{ $animated ? 'transition ease-in duration-100' : '' }}"
                x-transition:leave-start="{{ $animated ? 'opacity-100 scale-100' : '' }}"
                x-transition:leave-end="{{ $animated ? 'opacity-0 scale-95' : '' }}"
            >
                {{ $errorLabel }}
            </div>
        @endif
    </button>
</div>

<style>
    [x-cloak] { display: none !important; }
</style> 