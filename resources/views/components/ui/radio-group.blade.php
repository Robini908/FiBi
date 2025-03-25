@props([
    'name' => '',
    'id' => null,
    'label' => null,
    'options' => [],
    'selected' => null,
    'inline' => false,
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'error' => null,
    'helpText' => null,
    'size' => 'md',
    'color' => 'primary',
    'containerClass' => '',
])

@php
    // Generate an unique ID if not provided
    $id = $id ?? $name . '_' . uniqid();
    
    // Error state
    $hasError = $error !== null;
    
    // Layout classes for radio items container
    $layoutClasses = $inline 
        ? 'flex flex-wrap gap-4' 
        : 'space-y-2';
@endphp

<div class="{{ $containerClass }}">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="{{ $layoutClasses }}">
        @foreach($options as $option)
            @php
                $optionId = $id . '_' . $loop->index;
                $isChecked = ($selected !== null && $selected == $option['value']);
            @endphp
            
            <x-ui.radio
                :name="$name"
                :id="$optionId"
                :value="$option['value']"
                :label="$option['label']"
                :checked="$isChecked"
                :disabled="$disabled || ($option['disabled'] ?? false)"
                :readonly="$readonly"
                :required="$required && $loop->first"
                :size="$size"
                :color="$color"
            />
        @endforeach
    </div>
    
    @if($hasError)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @elseif($helpText)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $helpText }}</p>
    @endif
</div> 