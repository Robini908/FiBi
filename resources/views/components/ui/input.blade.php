@props([
    'type' => 'text',
    'name' => '',
    'id' => null,
    'value' => '',
    'label' => '',
    'placeholder' => '',
    'helper' => '',
    'error' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'leadingIcon' => '',
    'trailingIcon' => '',
    'leadingAddon' => '',
    'trailingAddon' => '',
    'size' => 'md',
    'rounded' => 'md',
    'hasError' => false,
    'wire:model' => null,
    'wire:model.defer' => null,
    'wire:model.lazy' => null,
    'wire:model.live' => null,
])

@php
    $id = $id ?? $name;
    
    // Error class handling
    $hasErrorClass = $hasError || $error ? true : false;
    
    // Base classes for the input
    $baseClasses = 'block w-full border focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-colors';
    
    // Additional classes based on error state
    $stateClasses = $hasErrorClass
        ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red-500'
        : 'border-gray-300 focus:border-green-300 focus:ring-green-200';
        
    // Size classes
    $sizeClasses = [
        'sm' => 'py-1.5 text-sm',
        'md' => 'py-2 text-base',
        'lg' => 'py-2.5 text-lg',
    ][$size] ?? 'py-2 text-base';
    
    // Rounded classes
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-md';
    
    // Icon padding classes
    $paddingLeftClass = $leadingIcon || $leadingAddon ? 'pl-10' : 'pl-3';
    $paddingRightClass = $trailingIcon || $trailingAddon ? 'pr-10' : 'pr-3';

    // Wire model directives handling
    $wireModelAttrs = [];
    if (isset($attributes['wire:model'])) {
        $wireModelAttrs['wire:model'] = $attributes['wire:model'];
    } elseif (isset($attributes['wire:model.defer'])) {
        $wireModelAttrs['wire:model.defer'] = $attributes['wire:model.defer'];
    } elseif (isset($attributes['wire:model.lazy'])) {
        $wireModelAttrs['wire:model.lazy'] = $attributes['wire:model.lazy'];
    } elseif (isset($attributes['wire:model.live'])) {
        $wireModelAttrs['wire:model.live'] = $attributes['wire:model.live'];
    }
@endphp

<div class="relative">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($leadingIcon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">
                    {!! $leadingIcon !!}
                </span>
            </div>
        @endif

        @if($leadingAddon)
            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-gray-500 sm:text-sm">{{ $leadingAddon }}</span>
            </div>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            @foreach($wireModelAttrs as $key => $value)
                {{ $key }}="{{ $value }}"
            @endforeach
            {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses} {$sizeClasses} {$roundedClasses} {$paddingLeftClass} {$paddingRightClass}"]) }}
        />

        @if($trailingIcon)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">
                    {!! $trailingIcon !!}
                </span>
            </div>
        @endif

        @if($trailingAddon)
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <span class="text-gray-500 sm:text-sm">{{ $trailingAddon }}</span>
            </div>
        @endif
    </div>

    @if($helper && !$error)
        <p class="mt-1 text-sm text-gray-500">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div> 