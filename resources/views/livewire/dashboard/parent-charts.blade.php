<div class="mt-6">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        @if($showChildrenPerformance)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">My Children's Academic Performance</h3>
                <button wire:click="$toggle('showChildrenPerformance')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="h-80">
                <livewire:livewire-column-chart
                    :column-chart-model="$childrenPerformance"
                />
            </div>
        </div>
        @endif

        @if($showFeePaymentStatus)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Fee Payment Status</h3>
                <button wire:click="$toggle('showFeePaymentStatus')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="h-80">
                <livewire:livewire-pie-chart
                    :pie-chart-model="$feePaymentStatus"
                />
            </div>
        </div>
        @endif

        @if($showAttendanceRecord)
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Children's Attendance Trend</h3>
                <button wire:click="$toggle('showAttendanceRecord')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="h-80">
                <livewire:livewire-line-chart
                    :line-chart-model="$attendanceRecord"
                />
            </div>
        </div>
        @endif
    </div>
</div>
