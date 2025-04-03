<div class="bg-gray-50 min-h-screen">
    <!-- Main container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        @include('livewire.attendance.partials.take-header')
        
        <!-- Content area -->
        <div class="mt-6 pb-12">
            @if(!$classId || !$sectionId)
                <!-- Class and Section Selector when not selected -->
                @include('livewire.attendance.partials.take-class-selector')
            @else
                <!-- Attendance Form when class and section are selected -->
                <form wire:submit.prevent="save">
                    <!-- Existing record notice -->
                    @if($existingRecord)
                        @include('livewire.attendance.partials.take-existing-notice')
                    @endif
            
                    <div class="grid grid-cols-1 gap-y-6">
                        <!-- Date and Session Controls -->
                        @include('livewire.attendance.partials.take-date-controls')
                        
                        <!-- Bulk Actions -->
                        @include('livewire.attendance.partials.take-bulk-actions')
                        
                        <!-- Students Table -->
                        @include('livewire.attendance.partials.take-students-table')
                        
                        <!-- General Remarks -->
                        @include('livewire.attendance.partials.take-remarks-form')
                        
                        <!-- Form Actions -->
                        @include('livewire.attendance.partials.take-form-actions')
                    </div>
                </form>
            @endif
        </div>
    </div>
    
    <!-- Success Message Toast -->
    @include('livewire.attendance.partials.take-success-message')
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('notify', param => {
            toastr[param.type](param.message);
        });
    });
</script>
@endpush 