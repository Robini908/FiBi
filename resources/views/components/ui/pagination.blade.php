@props([
    'paginator' => null,
    'onEachSide' => 1,
    'showInfo' => true,
    'size' => 'md',
    'align' => 'center',
    'simple' => false,
])

@php
    // Size classes
    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-4 py-2 text-base',
    ][$size] ?? 'px-3 py-1.5 text-sm';
    
    $iconSizeClasses = [
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
    ][$size] ?? 'h-5 w-5';
    
    // Alignment classes
    $alignmentClasses = [
        'left' => 'justify-start',
        'center' => 'justify-center',
        'right' => 'justify-end',
        'between' => 'justify-between',
    ][$align] ?? 'justify-center';
    
    // Calculate the window of links to display
    $window = $onEachSide * 2;
    
    if ($paginator && !$paginator->hasPages()) {
        return;
    }
@endphp

@if ($paginator)
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        @if($showInfo && !$simple)
            <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                <span>Showing</span>
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                <span>to</span>
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                <span>of</span>
                <span class="font-medium">{{ $paginator->total() }}</span>
                <span>results</span>
            </div>
        @endif
        
        <nav class="flex {{ $alignmentClasses }} space-x-1" aria-label="Pagination">
            @if ($simple)
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center {{ $sizeClasses }} border border-gray-300 text-gray-300 bg-white rounded-md cursor-not-allowed">
                        <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1">Previous</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center {{ $sizeClasses }} border border-gray-300 text-gray-700 bg-white rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1">Previous</span>
                    </a>
                @endif
                
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center {{ $sizeClasses }} border border-gray-300 text-gray-700 bg-white rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <span class="mr-1">Next</span>
                        <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="relative inline-flex items-center {{ $sizeClasses }} border border-gray-300 text-gray-300 bg-white rounded-md cursor-not-allowed">
                        <span class="mr-1">Next</span>
                        <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            @else
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center justify-center {{ $sizeClasses }} border border-gray-300 text-gray-300 bg-white rounded-md cursor-not-allowed">
                        <span class="sr-only">Previous</span>
                        <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center justify-center {{ $sizeClasses }} border border-gray-300 text-gray-700 bg-white rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <span class="sr-only">Previous</span>
                        <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
                
                {{-- Page Links --}}
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="relative inline-flex items-center justify-center {{ $sizeClasses }} border border-green-500 bg-green-50 text-green-700 rounded-md font-medium">
                            {{ $page }}
                        </span>
                    @elseif ($page == 1 || 
                            $page == $paginator->lastPage() || 
                            ($page >= $paginator->currentPage() - $onEachSide && 
                            $page <= $paginator->currentPage() + $onEachSide))
                        <a href="{{ $url }}" class="relative inline-flex items-center justify-center {{ $sizeClasses }} border border-gray-300 text-gray-700 bg-white rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            {{ $page }}
                        </a>
                    @elseif ($page == $paginator->currentPage() - $onEachSide - 1 || 
                            $page == $paginator->currentPage() + $onEachSide + 1)
                        <span class="relative inline-flex items-center justify-center {{ $sizeClasses }} border border-transparent text-gray-500 bg-white cursor-default">
                            ...
                        </span>
                    @endif
                @endforeach
                
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center justify-center {{ $sizeClasses }} border border-gray-300 text-gray-700 bg-white rounded-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <span class="sr-only">Next</span>
                        <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="relative inline-flex items-center justify-center {{ $sizeClasses }} border border-gray-300 text-gray-300 bg-white rounded-md cursor-not-allowed">
                        <span class="sr-only">Next</span>
                        <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            @endif
        </nav>
    </div>
@else
    <!-- Simplified pagination for when not using Laravel's paginator -->
    <div 
        x-data="{
            currentPage: 1,
            totalPages: 1,
            
            next() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.$dispatch('page-changed', { page: this.currentPage });
                }
            },
            
            previous() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.$dispatch('page-changed', { page: this.currentPage });
                }
            },
            
            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                    this.$dispatch('page-changed', { page: this.currentPage });
                }
            }
        }"
        {{ $attributes }}
    >
        <nav class="flex {{ $alignmentClasses }} space-x-1" aria-label="Pagination">
            <!-- Previous button -->
            <button 
                @click="previous" 
                :disabled="currentPage <= 1"
                :class="{ 'cursor-not-allowed text-gray-300': currentPage <= 1, 'text-gray-700 hover:bg-gray-50': currentPage > 1 }"
                class="relative inline-flex items-center {{ $sizeClasses }} border border-gray-300 bg-white rounded-md focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
                <span class="sr-only">Previous</span>
                <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            
            <!-- Current page / total pages -->
            <span 
                class="relative inline-flex items-center {{ $sizeClasses }} border border-gray-300 bg-white text-gray-700 rounded-md"
                x-text="currentPage + ' / ' + totalPages"
            ></span>
            
            <!-- Next button -->
            <button 
                @click="next" 
                :disabled="currentPage >= totalPages"
                :class="{ 'cursor-not-allowed text-gray-300': currentPage >= totalPages, 'text-gray-700 hover:bg-gray-50': currentPage < totalPages }"
                class="relative inline-flex items-center {{ $sizeClasses }} border border-gray-300 bg-white rounded-md focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
                <span class="sr-only">Next</span>
                <svg class="{{ $iconSizeClasses }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        </nav>
    </div>
@endif 