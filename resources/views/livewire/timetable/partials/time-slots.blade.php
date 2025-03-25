<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- Header section - Always visible -->
    <div class="px-5 py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0 border-b border-gray-100">
        <div class="flex items-center">
            <div class="bg-green-50 p-2 rounded-full mr-3">
                <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-medium text-gray-800">Time Slots</h2>
                <p class="text-xs text-gray-500">
                    @if(isset($timeSlots) && $timeSlots && $timeSlots->count() > 0)
                    {{ $timeSlots->count() }} periods defined
                    @else
                    No time slots defined yet
                    @endif
                </p>
            </div>
        </div>
        
        <!-- Show action buttons only when not in card mode -->
        @if(!$cardMode)
        <div class="flex space-x-3">
            <div x-data="{ showExportOptions: false }" class="relative">
                <button
                    @click="showExportOptions = !showExportOptions"
                    class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Export
                    <svg class="w-3.5 h-3.5 ml-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <!-- Export Options Dropdown -->
                <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                     x-show="showExportOptions"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     @click.away="showExportOptions = false"
                     style="display: none;">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <button wire:click="exportToPdf" class="flex items-center w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-100" role="menuitem">
                            <svg class="w-4 h-4 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Export as PDF
                        </button>
                        <button wire:click="exportToExcel" class="flex items-center w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-100" role="menuitem">
                            <svg class="w-4 h-4 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export as Excel
                        </button>
                        <button 
                            x-data="{
                                formatTime(timeString) {
                                    // Format time to h:i A (e.g., 8:30 AM)
                                    const time = new Date(`2000-01-01T${timeString}`);
                                    return time.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                                },
                                async copyToClipboard() {
                                    try {
                                        // Get the time slots data
                                        const data = await @this.prepareClipboardData();
                                        
                                        // Format the text
                                        let text = 'SCHOOL TIMETABLE - TIME SLOTS\n\n';
                                        
                                        // Add Weekday slots
                                        text += 'WEEKDAY PERIODS\n';
                                        text += 'Period Name\tStart Time\tEnd Time\tDuration\tType\n';
                                        data.weekdayTimeSlots.forEach(slot => {
                                            text += `${slot.period_name}\t${this.formatTime(slot.start_time)}\t${this.formatTime(slot.end_time)}\t${slot.duration} mins\t${slot.type}\n`;
                                        });
                                        
                                        // Add Weekend slots if available
                                        if (data.hasWeekendSlots) {
                                            text += '\nWEEKEND PERIODS\n';
                                            text += 'Period Name\tStart Time\tEnd Time\tDuration\tDay\n';
                                            data.weekendTimeSlots.forEach(slot => {
                                                text += `${slot.period_name}\t${this.formatTime(slot.start_time)}\t${this.formatTime(slot.end_time)}\t${slot.duration} mins\t${slot.category}\n`;
                                            });
                                        }
                                        
                                        // Add Prep slots if available
                                        if (data.hasPrepSlots) {
                                            text += '\nPREP PERIODS\n';
                                            text += 'Period Name\tStart Time\tEnd Time\tDuration\tType\n';
                                            data.prepTimeSlots.forEach(slot => {
                                                text += `${slot.period_name}\t${this.formatTime(slot.start_time)}\t${this.formatTime(slot.end_time)}\t${slot.duration} mins\t${slot.type}\n`;
                                            });
                                        }
                                        
                                        // Add timestamp
                                        const now = new Date();
                                        text += `\nGenerated on: ${now.toLocaleString()}`;
                                        
                                        // Copy to clipboard using the clipboard API or fallback
                                        if (navigator.clipboard) {
                                            await navigator.clipboard.writeText(text);
                                            // Show success notification
                                            this.toast('success', 'Time slots copied to clipboard!');
                                         } else {
                                            // Fallback for browsers without Clipboard API
                                            const textArea = document.createElement('textarea');
                                            textArea.value = text;
                                            textArea.style.position = 'fixed';
                                            textArea.style.left = '-999999px';
                                            textArea.style.top = '-999999px';
                                            document.body.appendChild(textArea);
                                            textArea.select();
                                            
                                            try {
                                                document.execCommand('copy');
                                                this.toast('success', 'Time slots copied to clipboard!');
                                            } catch (err) {
                                                console.error('Fallback: Oops, unable to copy', err);
                                                this.toast('error', 'Failed to copy to clipboard. Please try again.');
                                            }
                                            
                                            document.body.removeChild(textArea);
                                        }
                                    } catch (error) {
                                        console.error('Copy failed:', error);
                                        this.toast('error', 'Failed to copy to clipboard. Please try again.');
                                    }
                                },
                                toast(type, message) {
                                    // Let Livewire handle the toast
                                    @this.call('showToast', type, message);
                                }
                            }" 
                            @click="copyToClipboard"
                            class="flex items-center w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-100" 
                            role="menuitem">
                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            Copy to Clipboard
                        </button>
                    </div>
                </div>
            </div>
            
        <button 
            wire:click="createTimeSlot"
            class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm">
            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Time Slot
        </button>
            
            <button 
                wire:click="openAutoGenerateModal"
                class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Auto-Generate Slots
            </button>
        </div>
        @else
        <!-- Back button when in card mode -->
        <button 
            wire:click="backToMain"
            class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm">
            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Time Slots
        </button>
        @endif
    </div>
    
    <!-- Content area - Show cards based on mode -->
    @if($cardMode)
        <!-- Create/Edit Time Slot Card -->
        @if($showModal && ($activeCard === 'create' || $activeCard === 'edit'))
            <div class="animate-fade-in m-4 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center mb-4">
                    <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-green-50 text-green-600 mr-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </span>
                    <h3 class="text-lg font-medium text-gray-800">{{ $timeSlotForm['id'] ? 'Edit' : 'Create' }} Time Slot</h3>
            </div>
            
                <form wire:submit.prevent="saveTimeSlot" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Start Time -->
                    <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                </div>
                                <input type="time" id="start_time" wire:model="timeSlotForm.start_time" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 sm:text-sm">
                            </div>
                            @error('timeSlotForm.start_time') 
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                            @enderror
                    </div>

                        <!-- End Time -->
                    <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                </div>
                                <input type="time" id="end_time" wire:model="timeSlotForm.end_time" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 sm:text-sm">
                            </div>
                            @error('timeSlotForm.end_time') 
                                <p class="mt-1 text-xs text-red-600">{{ $message ?? 'Invalid end time format' }}</p> 
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Period Name -->
                    <div>
                        <label for="period_name" class="block text-sm font-medium text-gray-700 mb-1">Period Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" id="period_name" wire:model="timeSlotForm.name" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 sm:text-sm" placeholder="e.g. First Period, Lunch, etc.">
                        </div>
                        @error('timeSlotForm.period_name') 
                            <p class="mt-1 text-xs text-red-600">{{ $message ?? 'Invalid period name' }}</p> 
                        @enderror
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="backToMain" class="inline-flex justify-center items-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                        Cancel
                    </button>
                        <button type="submit" class="inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        {{ $timeSlotForm['id'] ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    @endif
    
        <!-- Auto Generate Card -->
        @if($showAutoGenerateModal && $activeCard === 'auto-generate')
            @include('livewire.timetable.partials.time-slots-auto-generate-form')
        @endif
        
        <!-- Delete Confirmation Card -->
        @if($showDeleteModal && $activeCard === 'delete')
            <div class="animate-fade-in m-4 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 text-red-600 mx-0">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Delete Time Slot</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete this time slot? This action cannot be undone. 
                                Any timetable entries using this time slot will need to be deleted first.
                            </p>
                        </div>
                        <div class="mt-4 flex space-x-3">
                            <button wire:click="backToMain" type="button" class="inline-flex justify-center items-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Cancel
                            </button>
                            <button wire:click="deleteTimeSlot" type="button" class="inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Time Slots Table - Only shown when not in card mode -->
        @if($timeSlots->count() > 0)
            <div class="px-5 py-4">
                <!-- Weekday Periods Section -->
                <div class="mb-6">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 w-4 h-4 bg-green-400 rounded-full mr-2"></div>
                        <h3 class="text-sm font-semibold text-gray-800">Weekday Periods</h3>
                        <span class="ml-2 text-xs text-gray-500">({{ $weekdayTimeSlots->count() }} periods)</span>
                    </div>

                <div class="overflow-x-auto bg-white rounded-lg border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-50 to-green-100">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    Period
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    Time
                                </th>
                                <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    Duration
                                </th>
                                    <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    Type
                                </th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                    Actions
                                </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($weekdayTimeSlots as $timeSlot)
                                    <tr class="hover:bg-gray-50 transition duration-150 {{ $this->getPeriodBgClass($timeSlot->period_name) }}">
                                <td class="px-4 py-3 text-sm text-gray-900 max-w-[150px] truncate">
                                            @if($timeSlot->period_name)
                                                <span class="font-medium {{ $this->getPeriodTextClass($timeSlot->period_name) }}">{{ $timeSlot->period_name }}</span>
                                            @else
                                                <span class="text-gray-500 italic">No Name</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-3 h-3 {{ $this->getPeriodIndicatorClass($timeSlot->period_name) }} rounded-full mr-1.5"></div>
                                                {{ date('h:i A', strtotime($timeSlot->start_time)) }} - {{ date('h:i A', strtotime($timeSlot->end_time)) }}
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                            @php
                                            $start = \Carbon\Carbon::parse($timeSlot->start_time);
                                            $end = \Carbon\Carbon::parse($timeSlot->end_time);
                                            $duration = $start->diffInMinutes($end);
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $duration < 30 ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $duration }} mins
                                            </span>
                                        </td>
                                        <td class="hidden sm:table-cell px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $this->getPeriodBgClass($timeSlot->period_name) }} {{ $this->getPeriodTextClass($timeSlot->period_name) }}">
                                                {{ ucfirst($this->getPeriodType($timeSlot->period_name)) }}
                                                </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex justify-end space-x-2">
                                            <button 
                                                wire:click="editTimeSlot({{ $timeSlot->id }})" 
                                                    class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button 
                                                    wire:click="confirmDelete({{ $timeSlot->id }})" 
                                                    class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                            
                            <!-- Weekend Periods Section -->
                @if($hasWeekendSlots)
                <div class="mb-6">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 w-4 h-4 bg-indigo-400 rounded-full mr-2"></div>
                        <h3 class="text-sm font-semibold text-gray-800">Weekend Periods</h3>
                        <span class="ml-2 text-xs text-gray-500">({{ $weekendTimeSlots->count() }} periods)</span>
                                        </div>
                    
                    <div class="overflow-x-auto bg-white rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Period
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Time
                                    </th>
                                    <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Duration
                                    </th>
                                    <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Day
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($weekendTimeSlots as $timeSlot)
                                    <tr class="hover:bg-gray-50 transition duration-150 bg-indigo-50 border-indigo-100">
                                        <td class="px-4 py-3 text-sm text-indigo-900 max-w-[150px] truncate">
                                    @if($timeSlot->period_name)
                                        <span class="font-medium">{{ $timeSlot->period_name }}</span>
                                    @else
                                        <span class="text-gray-500 italic">No Name</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                    <div class="flex items-center">
                                                <div class="flex-shrink-0 w-3 h-3 bg-indigo-400 rounded-full mr-1.5"></div>
                                                {{ date('h:i A', strtotime($timeSlot->start_time)) }} - {{ date('h:i A', strtotime($timeSlot->end_time)) }}
                                            </div>
                                        </td>
                                        <td class="hidden sm:table-cell px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                            @php
                                            $start = \Carbon\Carbon::parse($timeSlot->start_time);
                                            $end = \Carbon\Carbon::parse($timeSlot->end_time);
                                            $duration = $start->diffInMinutes($end);
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $duration }} mins
                                            </span>
                                        </td>
                                        <td class="hidden sm:table-cell px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                            @php
                                                $day = 'Weekend';
                                                if (str_contains(strtolower($timeSlot->period_name), 'saturday')) {
                                                    $day = 'Saturday';
                                                } elseif (str_contains(strtolower($timeSlot->period_name), 'sunday')) {
                                                    $day = 'Sunday';
                                                }
                                            @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $day }}
                                                </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex justify-end space-x-2">
                                            <button 
                                                wire:click="editTimeSlot({{ $timeSlot->id }})" 
                                                    class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button 
                                                    wire:click="confirmDelete({{ $timeSlot->id }})" 
                                                    class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                            @endif
                            
                            <!-- Prep Periods Section -->
                @if($hasPrepSlots)
                <div class="mb-6">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 w-4 h-4 bg-amber-400 rounded-full mr-2"></div>
                        <h3 class="text-sm font-semibold text-gray-800">Prep/Study Periods</h3>
                        <span class="ml-2 text-xs text-gray-500">({{ $prepTimeSlots->count() }} periods)</span>
                                        </div>
                    
                    <div class="overflow-x-auto bg-white rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-amber-50 to-amber-100">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Period
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Time
                                    </th>
                                    <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Duration
                                    </th>
                                    <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Type
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($prepTimeSlots as $timeSlot)
                                    <tr class="hover:bg-gray-50 transition duration-150 bg-amber-50 border-amber-100">
                                        <td class="px-4 py-3 text-sm text-amber-900 max-w-[150px] truncate">
                                            @if($timeSlot->period_name)
                                                <span class="font-medium">{{ $timeSlot->period_name }}</span>
                                            @else
                                                <span class="text-gray-500 italic">No Name</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-3 h-3 bg-amber-400 rounded-full mr-1.5"></div>
                                        {{ date('h:i A', strtotime($timeSlot->start_time)) }} - {{ date('h:i A', strtotime($timeSlot->end_time)) }}
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                    @php
                                    $start = \Carbon\Carbon::parse($timeSlot->start_time);
                                    $end = \Carbon\Carbon::parse($timeSlot->end_time);
                                    $duration = $start->diffInMinutes($end);
                                    @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        {{ $duration }} mins
                                            </span>
                                        </td>
                                        <td class="hidden sm:table-cell px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                            @php
                                                $prepType = 'Prep';
                                                if (str_contains(strtolower($timeSlot->period_name), 'morning')) {
                                                    $prepType = 'Morning Prep';
                                                } elseif (str_contains(strtolower($timeSlot->period_name), 'evening')) {
                                                    $prepType = 'Evening Prep';
                                                }
                                            @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                {{ $prepType }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-right whitespace-nowrap">
                                    <div class="flex justify-end space-x-2">
                                    <button 
                                        wire:click="editTimeSlot({{ $timeSlot->id }})" 
                                            class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button 
                                            wire:click="confirmDelete({{ $timeSlot->id }})" 
                                            class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                    </div>
                </div>
                            @endif
                            
                <!-- Legend for time slot types -->
                <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Time Slot Types</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-green-400 rounded-full mr-1.5"></div>
                            <span class="text-xs text-gray-700">Lesson Period</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-blue-400 rounded-full mr-1.5"></div>
                            <span class="text-xs text-gray-700">Break</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-yellow-400 rounded-full mr-1.5"></div>
                            <span class="text-xs text-gray-700">Lunch</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-gray-400 rounded-full mr-1.5"></div>
                            <span class="text-xs text-gray-700">Transition/Movement</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-purple-400 rounded-full mr-1.5"></div>
                            <span class="text-xs text-gray-700">Games/Activities</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-indigo-400 rounded-full mr-1.5"></div>
                            <span class="text-xs text-gray-700">Weekend</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-3 h-3 bg-amber-400 rounded-full mr-1.5"></div>
                            <span class="text-xs text-gray-700">Prep/Study Time</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="px-5 py-12 text-center">
                <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                    <h3 class="text-base font-medium text-gray-700 mb-1">No Time Slots Defined</h3>
                    <p class="text-sm text-gray-500 max-w-md mb-4">Define time slots to create your school's daily schedule. Each time slot represents a period in the day.</p>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <button 
                        wire:click="createTimeSlot"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                            Add Manually
                        </button>
                        <button 
                            wire:click="openAutoGenerateModal"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Auto-Generate
                    </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
    
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div> 