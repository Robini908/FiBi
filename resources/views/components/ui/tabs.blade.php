@props([
    'tabs' => [],
    'defaultTab' => 0,
    'variant' => 'underline',
    'alignment' => 'left',
    'iconPosition' => 'left',
    'fullWidth' => false,
    'isLazy' => true,
    'contentClass' => 'py-4',
])

@php
    // Set a unique ID for this tabs component
    $id = 'tabs-' . md5(json_encode($tabs) . rand());
    
    // Alignment classes
    $alignmentClasses = [
        'left' => 'justify-start',
        'center' => 'justify-center',
        'right' => 'justify-end',
        'between' => 'justify-between',
        'around' => 'justify-around',
        'evenly' => 'justify-evenly',
    ][$alignment] ?? 'justify-start';
    
    // Variant classes
    $baseTabClasses = 'inline-flex items-center px-4 py-2 text-sm font-medium';
    
    $variantTabClasses = [
        'underline' => [
            'list' => 'border-b border-gray-200 space-x-8 flex',
            'tab' => [
                'active' => $baseTabClasses . ' border-b-2 border-green-500 text-green-600 -mb-px',
                'inactive' => $baseTabClasses . ' text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent',
            ],
        ],
        'pills' => [
            'list' => 'flex space-x-2',
            'tab' => [
                'active' => $baseTabClasses . ' bg-green-100 text-green-700 rounded-md',
                'inactive' => $baseTabClasses . ' text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md',
            ],
        ],
        'buttons' => [
            'list' => 'inline-flex p-1 bg-gray-100 rounded-md',
            'tab' => [
                'active' => $baseTabClasses . ' bg-white shadow rounded-md text-gray-700',
                'inactive' => $baseTabClasses . ' text-gray-500 hover:text-gray-700',
            ],
        ],
    ][$variant] ?? [
        'list' => 'border-b border-gray-200 space-x-8 flex',
        'tab' => [
            'active' => $baseTabClasses . ' border-b-2 border-green-500 text-green-600 -mb-px',
            'inactive' => $baseTabClasses . ' text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent',
        ],
    ];
    
    // Width classes for tabs
    $widthClasses = $fullWidth ? 'flex-1 text-center' : '';
@endphp

<div 
    x-data="{ 
        activeTab: {{ $defaultTab }},
        tabs: {{ json_encode($tabs) }},
        isLazy: {{ $isLazy ? 'true' : 'false' }},
        visitedTabs: [{{ $defaultTab }}],
        
        init() {
            // Get tab from URL hash if present
            const hash = window.location.hash.substr(1);
            const tabIndex = this.tabs.findIndex(tab => tab.id === hash);
            
            if (tabIndex >= 0) {
                this.activeTab = tabIndex;
                this.visitedTabs.push(tabIndex);
            }
            
            this.$watch('activeTab', value => {
                if (!this.visitedTabs.includes(value)) {
                    this.visitedTabs.push(value);
                }
                
                // Update URL hash if tab has an ID
                if (this.tabs[value].id) {
                    history.replaceState(null, null, `#${this.tabs[value].id}`);
                }
            });
        },
        
        isTabActive(index) {
            return this.activeTab === index;
        },
        
        isTabVisited(index) {
            return this.visitedTabs.includes(index);
        },
        
        activateTab(index) {
            this.activeTab = index;
        }
    }"
    class="w-full"
>
    <!-- Tab navigation -->
    <div class="{{ $variantTabClasses['list'] }} {{ $alignmentClasses }}">
        <template x-for="(tab, index) in tabs" :key="index">
            <button 
                type="button"
                @click="activateTab(index)"
                :class="[
                    isTabActive(index) ? '{{ $variantTabClasses['tab']['active'] }}' : '{{ $variantTabClasses['tab']['inactive'] }}',
                    '{{ $widthClasses }}'
                ]"
                :aria-selected="isTabActive(index)"
                :id="`${tab.id ?? '{{ $id }}' + '-' + index}-tab`"
                :aria-controls="`${tab.id ?? '{{ $id }}' + '-' + index}-panel`"
                role="tab"
            >
                <template x-if="tab.icon && '{{ $iconPosition }}' === 'left'">
                    <span class="mr-2" x-html="tab.icon"></span>
                </template>
                
                <span x-text="tab.label"></span>
                
                <template x-if="tab.icon && '{{ $iconPosition }}' === 'right'">
                    <span class="ml-2" x-html="tab.icon"></span>
                </template>
                
                <template x-if="tab.badge">
                    <span 
                        class="ml-2 px-2 py-0.5 text-xs rounded-full"
                        :class="isTabActive(index) ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-700'"
                        x-text="tab.badge"
                    ></span>
                </template>
            </button>
        </template>
    </div>
    
    <!-- Tab panels -->
    <div class="{{ $contentClass }}">
        <template x-for="(tab, index) in tabs" :key="index">
            <div
                x-show="isTabActive(index)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                :id="`${tab.id ?? '{{ $id }}' + '-' + index}-panel`"
                :aria-labelledby="`${tab.id ?? '{{ $id }}' + '-' + index}-tab`"
                role="tabpanel"
                tabindex="0"
            >
                <template x-if="!isLazy || isTabVisited(index)">
                    <div x-html="tab.content"></div>
                </template>
            </div>
        </template>
    </div>
</div> 