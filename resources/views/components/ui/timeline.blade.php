@props([
    'items' => [],
    'align' => 'left',
    'lineColor' => 'primary',
    'iconSize' => 'md',
    'iconBorder' => true,
    'dateFormat' => null,
    'condensed' => false,
])

@php
    // Line color classes
    $lineColors = [
        'primary' => 'bg-green-500',
        'secondary' => 'bg-gray-400',
        'success' => 'bg-green-500',
        'info' => 'bg-blue-500',
        'warning' => 'bg-yellow-500',
        'danger' => 'bg-red-500',
        'dark' => 'bg-gray-800',
        'light' => 'bg-gray-200',
    ][$lineColor] ?? 'bg-green-500';
    
    // Icon size classes
    $iconSizes = [
        'xs' => 'w-4 h-4',
        'sm' => 'w-6 h-6',
        'md' => 'w-8 h-8',
        'lg' => 'w-10 h-10',
        'xl' => 'w-12 h-12',
    ][$iconSize] ?? 'w-8 h-8';
    
    // Alignment classes
    $alignmentClasses = [
        'left' => 'justify-start',
        'right' => 'justify-end',
        'center' => 'justify-center',
        'alternate' => 'justify-center', // Will be handled specially
    ][$align] ?? 'justify-start';
    
    // Spacing based on condensed prop
    $spacing = $condensed ? 'space-y-2' : 'space-y-6';
@endphp

<div class="relative {{ $spacing }}">
    @foreach($items as $index => $item)
        @php
            // Determine if the item should be on the left or right for alternate alignment
            $isLeft = $align !== 'alternate' || $index % 2 === 0;
            $itemAlignment = $align === 'alternate' 
                ? ($isLeft ? 'justify-end' : 'justify-start')
                : $alignmentClasses;
                
            // Format date if needed
            $date = isset($item['date']) 
                ? ($dateFormat ? date($dateFormat, strtotime($item['date'])) : $item['date']) 
                : null;
                
            // Calculate item colors
            $iconColor = $item['iconColor'] ?? $lineColor;
            $iconBg = [
                'primary' => 'bg-green-100 text-green-500',
                'secondary' => 'bg-gray-100 text-gray-500',
                'success' => 'bg-green-100 text-green-500',
                'info' => 'bg-blue-100 text-blue-500',
                'warning' => 'bg-yellow-100 text-yellow-500',
                'danger' => 'bg-red-100 text-red-500',
                'dark' => 'bg-gray-100 text-gray-800',
                'light' => 'bg-white text-gray-400',
            ][$iconColor] ?? 'bg-green-100 text-green-500';
            
            $itemIconBorder = $iconBorder ? 'border-2 border-white' : '';
        @endphp
        
        <div class="relative {{ $align === 'alternate' ? 'flex' : 'block' }} {{ $itemAlignment }}">
            <!-- Line -->
            @if(!$loop->first)
                <div class="absolute top-0 {{ $align === 'center' || $align === 'alternate' ? 'left-1/2 -translate-x-1/2' : 'left-4' }} -mt-3 h-6 w-0.5 {{ $lineColors }}"></div>
            @endif
            
            <!-- Content -->
            <div class="relative flex items-start {{ $align === 'alternate' ? ($isLeft ? 'flex-row-reverse' : 'flex-row') : 'flex-row' }} {{ $align === 'center' ? 'justify-center' : '' }}">
                <!-- Icon -->
                <div class="flex items-center justify-center {{ $iconSizes }} rounded-full {{ $iconBg }} {{ $itemIconBorder }} z-10 flex-shrink-0 {{ $condensed ? 'mt-1' : 'mt-0.5' }}">
                    @if(isset($item['icon']))
                        {!! $item['icon'] !!}
                    @elseif(isset($item['iconType']))
                        @if($item['iconType'] === 'dot')
                            <span class="inline-block w-2.5 h-2.5 rounded-full {{ str_replace('text', 'bg', $iconBg) }}"></span>
                        @elseif($item['iconType'] === 'check')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    @else
                        <span class="inline-block w-2.5 h-2.5 rounded-full {{ str_replace('text', 'bg', $iconBg) }}"></span>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="{{ $align === 'alternate' ? ($isLeft ? 'mr-4' : 'ml-4') : 'ml-4' }} flex-grow {{ $align === 'center' ? 'max-w-md' : '' }}">
                    <!-- Title and date -->
                    <div class="flex flex-col {{ $align === 'alternate' && !$isLeft ? 'items-start' : 'items-start' }} mb-1">
                        @if(isset($item['title']))
                            <h3 class="text-lg font-medium text-gray-900">{{ $item['title'] }}</h3>
                        @endif
                        
                        @if(isset($date))
                            <time class="text-sm text-gray-500">{{ $date }}</time>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="text-sm text-gray-700 pr-8">
                        @if(isset($item['content']))
                            @if(is_string($item['content']))
                                <p>{!! $item['content'] !!}</p>
                            @else
                                {{ $item['content'] }}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- If this is the last item, add a line that extends downward -->
            @if(!$loop->last)
                <div class="absolute bottom-0 {{ $align === 'center' || $align === 'alternate' ? 'left-1/2 -translate-x-1/2' : 'left-4' }} h-full w-0.5 {{ $lineColors }}"></div>
            @endif
        </div>
    @endforeach
</div> 