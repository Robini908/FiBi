@props([
    'steps' => [],
    'currentStep' => 1,
    'size' => 'md',
    'variant' => 'default',
    'orientation' => 'horizontal',
    'numbered' => true,
    'clickable' => false,
    'connector' => true,
    'completed' => [],
])

@php
    // Size classes
    $sizeClasses = [
        'sm' => [
            'container' => 'gap-2',
            'item' => 'text-xs',
            'icon' => 'h-6 w-6',
            'connector' => 'h-0.5',
        ],
        'md' => [
            'container' => 'gap-3',
            'item' => 'text-sm',
            'icon' => 'h-8 w-8',
            'connector' => 'h-0.5',
        ],
        'lg' => [
            'container' => 'gap-4',
            'item' => 'text-base',
            'icon' => 'h-10 w-10', 
            'connector' => 'h-1',
        ],
        'xl' => [
            'container' => 'gap-6',
            'item' => 'text-lg',
            'icon' => 'h-12 w-12',
            'connector' => 'h-1',
        ],
    ][$size] ?? [
        'container' => 'gap-3',
        'item' => 'text-sm',
        'icon' => 'h-8 w-8',
        'connector' => 'h-0.5',
    ];
    
    // Variant classes
    $variantClasses = [
        'default' => [
            'active' => 'bg-green-600 text-white border-green-600',
            'completed' => 'bg-green-100 text-green-600 border-green-600',
            'pending' => 'bg-gray-100 text-gray-500 border-gray-300',
            'connector' => [
                'active' => 'bg-green-600',
                'inactive' => 'bg-gray-300',
            ],
        ],
        'outline' => [
            'active' => 'bg-white text-green-600 border-green-600',
            'completed' => 'bg-white text-green-600 border-green-600',
            'pending' => 'bg-white text-gray-500 border-gray-300',
            'connector' => [
                'active' => 'bg-green-600',
                'inactive' => 'bg-gray-300',
            ],
        ],
        'pills' => [
            'active' => 'bg-green-600 text-white rounded-full border-transparent',
            'completed' => 'bg-green-100 text-green-600 rounded-full border-transparent',
            'pending' => 'bg-gray-100 text-gray-500 rounded-full border-transparent',
            'connector' => [
                'active' => 'bg-green-600',
                'inactive' => 'bg-gray-300',
            ],
        ],
    ][$variant] ?? [
        'active' => 'bg-green-600 text-white border-green-600',
        'completed' => 'bg-green-100 text-green-600 border-green-600',
        'pending' => 'bg-gray-100 text-gray-500 border-gray-300',
        'connector' => [
            'active' => 'bg-green-600',
            'inactive' => 'bg-gray-300',
        ],
    ];
    
    // Orientation classes
    $orientationClasses = [
        'horizontal' => 'flex-row items-center',
        'vertical' => 'flex-col items-start',
    ][$orientation] ?? 'flex-row items-center';
    
    // Helper function to determine step status
    $getStepStatus = function($index) use ($currentStep, $completed) {
        $stepNumber = $index + 1;
        
        if (in_array($stepNumber, $completed) || $stepNumber < $currentStep) {
            return 'completed';
        } elseif ($stepNumber === $currentStep) {
            return 'active';
        } else {
            return 'pending';
        }
    };
    
    // Convert to array if steps is a number
    if (is_numeric($steps)) {
        $stepsArray = [];
        for ($i = 1; $i <= $steps; $i++) {
            $stepsArray[] = ['title' => "Step $i", 'description' => ''];
        }
        $steps = $stepsArray;
    }
    
    $stepCount = count($steps);
@endphp

<div 
    {{ $attributes->merge(['class' => 'w-full']) }}
    x-data="{
        currentStep: {{ $currentStep }},
        clickable: {{ $clickable ? 'true' : 'false' }},
        
        goToStep(step) {
            if (this.clickable) {
                this.currentStep = step;
                this.$dispatch('step-changed', { step: step });
            }
        },
        
        isCompletedStep(step) {
            return {{ json_encode($completed) }}.includes(step) || step < this.currentStep;
        }
    }"
>
    <div class="flex {{ $orientation === 'vertical' ? 'flex-col' : 'flex-row justify-between' }} {{ $orientation === 'horizontal' ? $sizeClasses['container'] : '' }}">
        @foreach($steps as $index => $step)
            @php
                $stepNumber = $index + 1;
                $status = $getStepStatus($index);
                $stepClass = $variantClasses[$status];
                $isLast = $index === $stepCount - 1;
                $stepTitle = $step['title'] ?? "Step $stepNumber";
                $stepDescription = $step['description'] ?? '';
            @endphp
            
            <div class="flex {{ $orientation === 'vertical' ? 'flex-row items-start' : 'flex-col items-center' }} {{ $orientation === 'vertical' && !$isLast ? 'pb-8' : '' }} {{ $clickable ? 'cursor-pointer' : '' }}" 
                @if($clickable) @click="goToStep({{ $stepNumber }})" @endif>
                
                <!-- Step container -->
                <div class="flex items-center">
                    <!-- Step indicator circle -->
                    <div class="flex-shrink-0 flex items-center justify-center {{ $sizeClasses['icon'] }} {{ $stepClass }} border-2 rounded-full">
                        @if($status === 'completed')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @elseif($numbered)
                            {{ $stepNumber }}
                        @endif
                    </div>
                    
                    @if($orientation === 'vertical')
                        <!-- Title and description for vertical orientation -->
                        <div class="ml-4">
                            <div class="font-medium {{ $sizeClasses['item'] }} {{ $status === 'active' ? 'text-green-600' : ($status === 'completed' ? 'text-green-600' : 'text-gray-500') }}">
                                {{ $stepTitle }}
                            </div>
                            @if($stepDescription)
                                <div class="text-xs text-gray-500 mt-1">{{ $stepDescription }}</div>
                            @endif
                        </div>
                    @endif
                </div>
                
                @if($orientation === 'horizontal')
                    <!-- Title and description for horizontal orientation -->
                    <div class="text-center mt-2">
                        <div class="font-medium {{ $sizeClasses['item'] }} {{ $status === 'active' ? 'text-green-600' : ($status === 'completed' ? 'text-green-600' : 'text-gray-500') }}">
                            {{ $stepTitle }}
                        </div>
                        @if($stepDescription)
                            <div class="text-xs text-gray-500 mt-1">{{ $stepDescription }}</div>
                        @endif
                    </div>
                @endif
                
                <!-- Connector line -->
                @if($connector && !$isLast)
                    @if($orientation === 'horizontal')
                        <div class="hidden sm:block absolute left-0 right-0 top-1/2 transform -translate-y-1/2 {{ $sizeClasses['connector'] }} bg-gray-200" style="left: calc(50% + {{ $sizeClasses['icon'] === 'h-6 w-6' ? '12px' : ($sizeClasses['icon'] === 'h-8 w-8' ? '16px' : ($sizeClasses['icon'] === 'h-10 w-10' ? '20px' : '24px')) }}); right: calc(50% + {{ $sizeClasses['icon'] === 'h-6 w-6' ? '12px' : ($sizeClasses['icon'] === 'h-8 w-8' ? '16px' : ($sizeClasses['icon'] === 'h-10 w-10' ? '20px' : '24px')) }});">
                            <div class="{{ $sizeClasses['connector'] }} {{ $status !== 'pending' ? $variantClasses['connector']['active'] : $variantClasses['connector']['inactive'] }}" style="width: 100%;"></div>
                        </div>
                    @else
                        <div class="absolute w-px h-full {{ $status !== 'pending' ? $variantClasses['connector']['active'] : $variantClasses['connector']['inactive'] }}" style="left: {{ $sizeClasses['icon'] === 'h-6 w-6' ? '12px' : ($sizeClasses['icon'] === 'h-8 w-8' ? '16px' : ($sizeClasses['icon'] === 'h-10 w-10' ? '20px' : '24px')) }}; top: {{ $sizeClasses['icon'] === 'h-6 w-6' ? '24px' : ($sizeClasses['icon'] === 'h-8 w-8' ? '32px' : ($sizeClasses['icon'] === 'h-10 w-10' ? '40px' : '48px')) }}; height: 24px;"></div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
    
    <!-- Step content (optional) -->
    {{ $slot }}
</div> 