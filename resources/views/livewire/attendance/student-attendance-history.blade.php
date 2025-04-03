<div>
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <!-- Header Section -->
        @include('livewire.attendance.partials.student-header')
        
        <!-- Statistics and Charts Section -->
        @include('livewire.attendance.partials.student-stats')
        
        <!-- Attendance History Table Section -->
        @include('livewire.attendance.partials.student-history-table')
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', function () {
        // Listen for notifications from the component
        @this.on('notify', param => {
            toastr[param.type](param.message);
        });
        
        // Initialize date picker
        flatpickr("#student-date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    @this.set('startDate', selectedDates[0].toISOString().slice(0, 10));
                    @this.set('endDate', selectedDates[1].toISOString().slice(0, 10));
                }
            }
        });
    });
</script>
@endpush 