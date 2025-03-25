<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Include the header partial -->
    @include('livewire.partials.bulk-import.header')

    <div class="px-6 pb-6">
        <!-- Template information panel (expandable) -->
        @include('livewire.partials.bulk-import.template-info')

        <!-- File upload component -->
        @include('livewire.partials.bulk-import.file-upload')

        <!-- Import results - only shown when results are available -->
        @if (isset($importResults))
           
            @include('livewire.partials.bulk-import.import-results')
        @endif
    </div>
</div>

@script
    <script>
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('fileUploadProgress', progress => {
                // Update progress via Alpine.js
                if (window.Alpine) {
                    window.dispatchEvent(new CustomEvent('upload-progress-updated', {
                        detail: {
                            progress: progress
                        }
                    }));
                }
            });
        });
    </script>
@endscript
