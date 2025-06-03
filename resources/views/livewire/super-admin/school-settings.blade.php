<div x-data="{ showDebug: false }" class="container mx-auto py-6 px-4 sm:px-0">


    <!-- Main Content -->
    <div class="max-w-5xl mx-auto">
        <!-- Header with page title and action buttons -->
        @include('livewire.partials.settings.header')

        <!-- Settings Navigation Tabs -->
        @include('livewire.partials.settings.tabs')

        <!-- Settings Content Area -->
        <div class="mt-6">
            @if ($editingSettings)
                <!-- Edit Mode -->
                @include('livewire.partials.settings.edit-form')
            @else
                <!-- View Mode -->
                @include('livewire.partials.settings.view-mode')
            @endif
        </div>
    </div>
</div>
