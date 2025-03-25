@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'placeholder' => 'Select an option',
    'options' => [],
    'value' => '',
    'helper' => '',
    'error' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'multiple' => false,
    'size' => 'md',
    'rounded' => 'md',
    'hasError' => false,
    'searchable' => false,
])

@php
    $id = $id ?? $name;
    
    // Error class handling
    $hasErrorClass = $hasError || $error ? true : false;
    
    // Base classes for the select
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
    
    // Padding classes
    $paddingClasses = 'pl-3 pr-10';
@endphp

<div class="relative" 
    @if($searchable) 
    x-data="{
        open: false,
        search: '',
        selectedOption: '{{ $value }}',
        selectedText: '',
        init() {
            // Set the initial selected text based on the provided value
            this.setInitialSelectedText();
            
            // Close dropdown when clicking outside
            this.$watch('open', value => {
                if (value) {
                    document.addEventListener('click', e => {
                        if (!this.$el.contains(e.target)) {
                            this.open = false;
                        }
                    });
                }
            });
        },
        setInitialSelectedText() {
            @if(!empty($options))
                // Find the option that matches the selected value and get its text
                const selectedOption = {{ json_encode($options) }}.find(option => 
                    option.value == '{{ $value }}' || option.id == '{{ $value }}'
                );
                if (selectedOption) {
                    this.selectedText = selectedOption.text || selectedOption.name || selectedOption.label;
                }
            @endif
        },
        selectOption(value, text) {
            this.selectedOption = value;
            this.selectedText = text;
            this.open = false;
            this.search = '';
            
            // Dispatch a change event on the hidden select
            const event = new Event('change', { bubbles: true });
            this.$refs.select.dispatchEvent(event);
        },
        get filteredOptions() {
            const searchLower = this.search.toLowerCase();
            if (!searchLower) return {{ json_encode($options) }};
            
            return {{ json_encode($options) }}.filter(option => {
                const text = option.text || option.name || option.label;
                return text.toLowerCase().includes(searchLower);
            });
        }
    }"
    @endif
>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    @if($searchable)
        <div class="relative">
            <div 
                @click="open = !open" 
                class="cursor-pointer {{ $baseClasses }} {{ $stateClasses }} {{ $sizeClasses }} {{ $roundedClasses }} {{ $paddingClasses }} {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }} flex items-center justify-between"
            >
                <span x-text="selectedText || '{{ $placeholder }}'"></span>
                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
            
            <select 
                x-ref="select" 
                name="{{ $name }}" 
                id="{{ $id }}" 
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $multiple ? 'multiple' : '' }}
                x-model="selectedOption"
                class="absolute opacity-0 -z-10 h-0"
                {{ $attributes }}
            >
                <option value="">{{ $placeholder }}</option>
                @foreach($options as $option)
                    <option 
                        value="{{ $option['value'] ?? $option['id'] ?? '' }}" 
                        {{ ($option['value'] ?? $option['id'] ?? '') == $value ? 'selected' : '' }}
                    >
                        {{ $option['text'] ?? $option['name'] ?? $option['label'] ?? '' }}
                    </option>
                @endforeach
            </select>
            
            <!-- Dropdown menu -->
            <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-100" 
                x-transition:enter-start="transform opacity-0 scale-95" 
                x-transition:enter-end="transform opacity-100 scale-100" 
                x-transition:leave="transition ease-in duration-75" 
                x-transition:leave-start="transform opacity-100 scale-100" 
                x-transition:leave-end="transform opacity-0 scale-95" 
                class="absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto"
            >
                <div class="p-2">
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Search..." 
                        class="w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                </div>
                <ul class="py-1 divide-y divide-gray-100">
                    <template x-for="option in filteredOptions" :key="option.value || option.id">
                        <li 
                            @click="selectOption(option.value || option.id, option.text || option.name || option.label)" 
                            :class="{'bg-green-50': selectedOption == (option.value || option.id)}"
                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 text-gray-900 hover:bg-green-100"
                        >
                            <div class="flex items-center">
                                <span x-text="option.text || option.name || option.label" class="block truncate"></span>
                            </div>
                            
                            <span 
                                x-show="selectedOption == (option.value || option.id)"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-green-600"
                            >
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </li>
                    </template>
                    
                    <li x-show="filteredOptions.length === 0" class="py-2 px-3 text-gray-500 text-sm">
                        No options found
                    </li>
                </ul>
            </div>
        </div>
    @else
        <div class="relative">
            <select
                name="{{ $name }}"
                id="{{ $id }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $multiple ? 'multiple' : '' }}
                {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses} {$sizeClasses} {$roundedClasses} {$paddingClasses} " . ($disabled ? 'opacity-50 cursor-not-allowed' : '')]) }}
            >
                <option value="">{{ $placeholder }}</option>
                @foreach($options as $option)
                    <option 
                        value="{{ $option['value'] ?? $option['id'] ?? '' }}" 
                        {{ ($option['value'] ?? $option['id'] ?? '') == $value ? 'selected' : '' }}
                    >
                        {{ $option['text'] ?? $option['name'] ?? $option['label'] ?? '' }}
                    </option>
                @endforeach
            </select>
            
            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    @endif
    
    @if($helper && !$error)
        <p class="mt-1 text-sm text-gray-500">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div> 