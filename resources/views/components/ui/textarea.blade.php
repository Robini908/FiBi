@props([
    'name' => '',
    'id' => null,
    'value' => null,
    'label' => null,
    'placeholder' => '',
    'helpText' => null,
    'error' => null,
    'readonly' => false,
    'disabled' => false,
    'required' => false,
    'autofocus' => false,
    'rows' => 4,
    'maxlength' => null,
    'minlength' => null,
    'leadingIcon' => null,
    'trailingIcon' => null,
    'leadingAddOn' => null,
    'trailingAddOn' => null,
    'rounded' => 'md',
    'resize' => 'vertical', // none, vertical, horizontal, both
    'size' => 'md',
])

@php
    // Generate a unique ID if none provided
    $id = $id ?? $name . '_' . uniqid();
    
    // Validation state
    $hasError = $error !== null;
    
    // Base classes
    $baseClasses = 'form-textarea block w-full shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
    
    // Focus classes
    $focusClasses = $hasError 
        ? 'focus:ring-red-500 focus:border-red-500 dark:focus:ring-red-500 dark:focus:border-red-500' 
        : 'focus:ring-green-500 focus:border-green-500 dark:focus:ring-green-500 dark:focus:border-green-500';
    
    // State classes
    $stateClasses = $hasError 
        ? 'border-red-300 pr-10 text-red-900 placeholder-red-300 dark:border-red-600 dark:text-red-100 dark:placeholder-red-400'
        : '';
    
    // Disabled classes
    $disabledClasses = $disabled
        ? 'disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed dark:disabled:bg-gray-800 dark:disabled:text-gray-400'
        : '';
    
    // Readonly classes
    $readonlyClasses = $readonly
        ? 'bg-gray-100 text-gray-700 cursor-default dark:bg-gray-800 dark:text-gray-300'
        : '';
    
    // Rounded corners classes
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-md';
    
    // Size classes
    $sizeClasses = [
        'xs' => 'py-1 px-2 text-xs',
        'sm' => 'py-1.5 px-3 text-sm',
        'md' => 'py-2 px-4 text-sm',
        'lg' => 'py-3 px-4 text-base',
        'xl' => 'py-4 px-6 text-lg',
    ][$size] ?? 'py-2 px-4 text-sm';
    
    // Resize classes
    $resizeClasses = [
        'none' => 'resize-none',
        'vertical' => 'resize-y',
        'horizontal' => 'resize-x',
        'both' => 'resize',
    ][$resize] ?? 'resize-y';
    
    // Combine all classes
    $classes = [
        $baseClasses,
        $focusClasses,
        $stateClasses,
        $disabledClasses,
        $readonlyClasses,
        $roundedClasses,
        $sizeClasses,
        $resizeClasses,
    ];
    
    // Has add-ons?
    $hasLeadingAddOn = $leadingAddOn !== null || $leadingIcon !== null;
    $hasTrailingAddOn = $trailingAddOn !== null || $trailingIcon !== null || $hasError;
    
    // Adjust width when there are add-ons
    if ($hasLeadingAddOn || $hasTrailingAddOn) {
        $classes[] = 'flex-1';
        
        if ($hasLeadingAddOn) {
            $classes[] = 'rounded-l-none';
        }
        
        if ($hasTrailingAddOn) {
            $classes[] = 'rounded-r-none';
        }
    }
@endphp

<div {{ $attributes->only(['class', 'x-data', 'wire:key'])->class(['w-full']) }}>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div @class([
        'flex rounded-md shadow-sm',
        'outline outline-2 outline-offset-2 outline-red-500 dark:outline-red-400' => $hasError,
    ])>
        @if($hasLeadingAddOn)
            <div class="flex items-stretch">
                @if($leadingAddOn)
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-sm">
                        {{ $leadingAddOn }}
                    </span>
                @elseif($leadingIcon)
                    <div class="flex items-center pl-3 pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">
                            {!! $leadingIcon !!}
                        </span>
                    </div>
                @endif
            </div>
        @endif
        
        <textarea
            name="{{ $name }}"
            id="{{ $id }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            {{ $maxlength ? 'maxlength=' . $maxlength : '' }}
            {{ $minlength ? 'minlength=' . $minlength : '' }}
            {{ $attributes->except(['class', 'x-data', 'wire:key'])->class(implode(' ', $classes)) }}
        >{{ $value ?? $slot }}</textarea>
        
        @if($hasTrailingAddOn)
            <div class="flex items-stretch">
                @if($trailingAddOn)
                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-sm">
                        {{ $trailingAddOn }}
                    </span>
                @elseif($trailingIcon)
                    <div class="flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">
                            {!! $trailingIcon !!}
                        </span>
                    </div>
                @endif
                
                @if($hasError)
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @endif
            </div>
        @endif
    </div>
    
    @if($hasError)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
    
    @if($helpText && !$hasError)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $helpText }}</p>
    @endif
</div> 