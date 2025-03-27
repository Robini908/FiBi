<div 
    x-data="{ showDebug: false }" 
    class="container mx-auto py-6 px-4 sm:px-0"
>
    <!-- Debug Toggle (Development Only) -->
    <div class="mb-4 flex justify-end">
        <button 
            @click="showDebug = !showDebug"
            class="text-xs text-gray-500 hover:text-gray-700 transition"
        >
            <span x-text="showDebug ? 'Hide Debug Info' : 'Show Debug Info'"></span>
        </button>
    </div>
    
    <!-- Debug Info -->
    <div 
        x-show="showDebug"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="mb-6 bg-gray-100 p-4 rounded-lg shadow-sm text-xs overflow-auto max-h-64"
    >
        <h4 class="font-semibold">Debug Info:</h4>
        <div>Available Groups: {{ implode(', ', $this->groups()) }}</div>
        <div>Active Group: {{ $activeGroup }}</div>
        <div>Settings for Active Group: {{ isset($this->settings()[$activeGroup]) ? count($this->settings()[$activeGroup]) : 0 }} items</div>
        
        @if(isset($this->settings()[$activeGroup]) && count($this->settings()[$activeGroup]) > 0)
            <div class="mt-2">
                <div class="font-semibold">Settings in {{ $activeGroup }}:</div>
                <ul class="list-disc pl-4">
                    @foreach($this->settings()[$activeGroup] as $setting)
                        <li>{{ $setting->key }} ({{ $setting->display_name }})</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto">
        <!-- Header with page title and action buttons -->
        @include('livewire.partials.settings.header')
        
        <!-- Settings Navigation Tabs -->
        @include('livewire.partials.settings.tabs')
        
        <!-- Settings Content Area -->
        <div class="mt-6">
            @if($editingSettings)
                <!-- Edit Mode -->
                @include('livewire.partials.settings.edit-form')
            @else
                <!-- View Mode -->
                @include('livewire.partials.settings.view-mode')
            @endif
        </div>
    </div>
</div>
