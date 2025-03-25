@props([
    'items' => [],
    'separator' => 'chevron',
    'homeLink' => '/',
    'homeText' => 'Home',
    'showHome' => true,
    'truncate' => false,
    'responsive' => true,
])

@php
    $separatorIcons = [
        'chevron' => '<svg class="h-4 w-4 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                      </svg>',
        'slash' => '<span class="text-gray-400 mx-1">/</span>',
        'dot' => '<span class="text-gray-400 mx-1">•</span>',
        'dash' => '<span class="text-gray-400 mx-1">-</span>',
        'arrow' => '<svg class="h-4 w-4 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>',
    ];
    
    $separatorHtml = $separatorIcons[$separator] ?? $separatorIcons['chevron'];
@endphp

<nav aria-label="Breadcrumb" {{ $attributes }}>
    <ol class="flex items-center {{ $responsive ? 'flex-wrap' : '' }} space-x-1 sm:space-x-2">
        @if($showHome)
            <li>
                <div class="flex items-center">
                    <a href="{{ $homeLink }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                        <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="sr-only">{{ $homeText }}</span>
                    </a>
                </div>
            </li>
        @endif
        
        @foreach($items as $index => $item)
            <li>
                <div class="flex items-center">
                    @if($index > 0 || $showHome)
                        <div class="flex items-center mx-1">
                            {!! $separatorHtml !!}
                        </div>
                    @endif
                    
                    @if(isset($item['url']) && $index < count($items) - 1)
                        <a 
                            href="{{ $item['url'] }}" 
                            class="{{ $truncate ? 'truncate max-w-[100px] sm:max-w-xs' : '' }} text-sm font-medium text-gray-500 hover:text-gray-700"
                            @if(isset($item['title'])) title="{{ $item['title'] }}" @endif
                        >
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span 
                            class="{{ $truncate ? 'truncate max-w-[100px] sm:max-w-xs' : '' }} text-sm font-medium text-gray-900"
                            @if(isset($item['title'])) title="{{ $item['title'] }}" @endif
                        >
                            {{ $item['label'] }}
                        </span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav> 