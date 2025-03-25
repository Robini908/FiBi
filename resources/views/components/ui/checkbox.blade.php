@props([
    'name' => '',
    'id' => null,
    'value' => '',
    'label' => null,
    'checked' => false,
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'error' => null,
    'helpText' => null,
    'size' => 'md',
    'labelPosition' => 'right',
    'color' => 'primary',
    'indeterminate' => false,
    'containerClass' => '',
])

@php
    // Generate an unique ID if not provided
    $id = $id ?? $name . '_' . uniqid();
    
    // Error state
    $hasError = $error !== null;
    
    // Size classes
    $sizeClasses = [
        'xs' => 'h-3 w-3',
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
        'xl' => 'h-7 w-7',
    ][$size] ?? 'h-5 w-5';
    
    // Text size based on checkbox size
    $textSizeClasses = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'md' => 'text-sm',
        'lg' => 'text-base',
        'xl' => 'text-lg',
    ][$size] ?? 'text-sm';
    
    // Color classes
    $colorClasses = [
        'primary' => 'text-green-600 focus:ring-green-500',
        'secondary' => 'text-gray-600 focus:ring-gray-500',
        'success' => 'text-green-600 focus:ring-green-500',
        'danger' => 'text-red-600 focus:ring-red-500',
        'warning' => 'text-yellow-600 focus:ring-yellow-500',
        'info' => 'text-blue-600 focus:ring-blue-500',
        'light' => 'text-gray-300 focus:ring-gray-400',
        'dark' => 'text-gray-800 focus:ring-gray-700',
    ][$color] ?? 'text-green-600 focus:ring-green-500';
    
    // Focus and error styles
    $focusClasses = $hasError 
        ? 'focus:ring-red-500 border-red-300 dark:border-red-600' 
        : 'border-gray-300 dark:border-gray-600 ' . $colorClasses;
    
    // Common classes
    $baseClasses = 'form-checkbox rounded shadow-sm focus:ring-opacity-50 transition duration-150 ease-in-out dark:bg-gray-800 dark:checked:bg-current';
    
    // Disabled and readonly classes
    $stateClasses = [];
    
    if ($disabled) {
        $stateClasses[] = 'disabled:opacity-50 disabled:cursor-not-allowed';
    }
    
    if ($readonly) {
        $stateClasses[] = 'cursor-default pointer-events-none';
    }
    
    // Combine all checkbox classes
    $checkboxClasses = implode(' ', [$baseClasses, $sizeClasses, $focusClasses, implode(' ', $stateClasses)]);
    
    // If indeterminate, we'll need to use JavaScript
    $indeterminateScript = $indeterminate ? "document.getElementById('$id').indeterminate = true;" : '';
@endphp

@if($indeterminateScript)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            {!! $indeterminateScript !!}
        });
        document.addEventListener('livewire:load', function() {
            {!! $indeterminateScript !!}
        });
    </script>
@endif

<div class="flex items-{{ $labelPosition === 'right' ? 'center' : 'start' }} {{ $containerClass }}">
    <div class="flex items-center h-5">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ $value }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly onclick="return false;" @endif
            @if($required) required @endif
            class="{{ $checkboxClasses }}"
            {{ $attributes->except(['class', 'wire:model', 'x-model']) }}
        />
    </div>
    
    @if($label)
        <div class="ml-3 text-{{ $labelPosition === 'right' ? 'left' : 'right' }}">
            <label for="{{ $id }}" class="font-medium {{ $textSizeClasses }} text-gray-700 dark:text-gray-300 {{ $disabled ? 'opacity-50' : '' }}">
                {{ $label }}
                @if($required)
                    <span class="text-red-500">*</span>
                @endif
            </label>
            
            @if($helpText && !$hasError)
                <p class="text-gray-500 dark:text-gray-400 {{ $textSizeClasses === 'text-xs' ? 'text-xs' : 'text-sm' }}">
                    {{ $helpText }}
                </p>
            @endif
            
            @if($hasError)
                <p class="mt-1 text-red-600 dark:text-red-400 {{ $textSizeClasses === 'text-xs' ? 'text-xs' : 'text-sm' }}">
                    {{ $error }}
                </p>
            @endif
        </div>
    @endif
</div> 