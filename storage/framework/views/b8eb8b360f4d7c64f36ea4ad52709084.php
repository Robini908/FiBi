<div>
    <div 
        x-data="{ 
            currentTime: '<?php echo e(date('H:i:s')); ?>',
            currentDay: '<?php echo e(strtolower(date('l'))); ?>',
            init() {
                this.startClock();
                this.checkCurrentPeriods();
                
                // Set up refresh interval for current period check
                setInterval(() => {
                    this.checkCurrentPeriods();
                }, 60000); // Check every minute
            },
            startClock() {
                setInterval(() => {
                    const now = new Date();
                    const hours = now.getHours().toString().padStart(2, '0');
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const seconds = now.getSeconds().toString().padStart(2, '0');
                    this.currentTime = `${hours}:${minutes}:${seconds}`;
                    this.$refs.clockDisplay.textContent = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                }, 1000);
            },
            checkCurrentPeriods() {
                // Tell Livewire to refresh the current periods data
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('refreshCurrentPeriods');
            },
            isPeriodActive(startTime, endTime) {
                const now = this.currentTime;
                return now >= startTime && now <= endTime;
            }
        }"
        class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header section with modern styling -->
        <div class="px-5 py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0 border-b border-gray-100">
            <div class="flex items-center">
                <div class="bg-green-50 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-medium text-gray-800">Timetable View</h2>
                    <p class="text-xs text-gray-500">
                        <!--[if BLOCK]><![endif]--><?php if($filteredPeriods->count() > 0): ?>
                        Schedule with <?php echo e($filteredPeriods->count()); ?> time slots
                        <?php else: ?>
                        No time slots defined yet
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </p>
                </div>
            </div>
            
            <div x-data="{ 
                    showExportOptions: false,
                    showErrorModal: false,
                    errorMessage: '',
                    isLoading: false,
                    exportType: null,
                    
                    timetableId: '<?php echo e($timetableRecordId); ?>',
                    sectionId: '<?php echo e($sectionId); ?>',
                    fallbackId: '<?php echo e(isset($timetable) && $timetable ? $timetable->id : ""); ?>',
                    
                    // Check if we have a valid ID
                    get effectiveId() {
                        return this.timetableId || this.fallbackId;
                    },
                    
                    // Export function
                    exportTimetable(type) {
                        this.showExportOptions = false;
                        this.exportType = type;
                        
                        // Validate timetable ID
                        if (!this.effectiveId) {
                            this.errorMessage = 'Cannot export - Timetable ID is missing. Please try refreshing the page or select a timetable first.';
                            this.showErrorModal = true;
                            return;
                        }
                        
                        // Set loading state
                        this.isLoading = true;
                        
                        let url = '';
                        // Construct URL based on export type
                        if (type === 'print') {
                            url = '<?php echo e(url('timetables/print')); ?>/' + this.effectiveId + (this.sectionId ? '/' + this.sectionId : '');
                        } else if (type === 'pdf') {
                            url = '<?php echo e(url('timetables/export/pdf')); ?>/' + this.effectiveId + (this.sectionId ? '/' + this.sectionId : '');
                        } else if (type === 'excel') {
                            url = '<?php echo e(url('timetables/export/excel')); ?>/' + this.effectiveId + (this.sectionId ? '/' + this.sectionId : '');
                        }
                        
                        // Create a fetch request to track loading state
                        if (url) {
                            if (type === 'pdf' || type === 'excel') {
                                // For downloads we need to create a form and submit it
                                const form = document.createElement('form');
                                form.method = 'GET';
                                form.action = url;
                                form.target = '_blank';
                                document.body.appendChild(form);
                                form.submit();
                                document.body.removeChild(form);
                                
                                // Set a timeout to hide the loading indicator
                                setTimeout(() => {
                                    this.isLoading = false;
                                }, 3000);
                            } else {
                                // For print view, just open in a new tab
                                window.open(url, '_blank');
                                this.isLoading = false;
                            }
                        }
                    }
                }" 
                class="relative">
                <div class="flex items-center space-x-3">
                    <button 
                        wire:click="$refresh"
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-green-600 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-all duration-200">
                        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                    
                    <button 
                        wire:click="openAutoGenerateModal"
                        type="button" 
                        class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-all duration-200"
                    >
                        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Auto-Generate
                    </button>
                    
                    <button 
                        @click="showExportOptions = !showExportOptions"
                        class="relative inline-flex items-center px-3 py-2 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-all duration-200"
                        :class="{ 'opacity-75 cursor-not-allowed': isLoading }"
                        :disabled="isLoading">
                        <template x-if="isLoading">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <template x-if="!isLoading">
                        <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        </template>
                        <span x-text="isLoading ? 'Exporting ' + (exportType === 'pdf' ? 'PDF' : exportType === 'excel' ? 'Excel' : 'Print') + '...' : 'Export'"></span>
                        <svg x-show="!isLoading" class="w-3.5 h-3.5 ml-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="origin-top-right absolute right-0 mt-10 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" 
                         x-show="showExportOptions && !isLoading"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="showExportOptions = false"
                         style="display: none;">
                        <div class="py-1" role="menu" aria-orientation="vertical">
                            <button 
                                type="button"
                                @click="exportTimetable('print')" 
                                class="flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-100" 
                                role="menuitem">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                                </svg>
                                Print Timetable
                            </button>
                            <button 
                                type="button"
                                @click="exportTimetable('pdf')" 
                                class="flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-100" 
                                role="menuitem">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export as PDF
                            </button>
                            <button 
                                type="button"
                                @click="exportTimetable('excel')" 
                                class="flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-100" 
                                role="menuitem">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Export as Excel
                            </button>
                        </div>
                        </div>
                    </div>
                    
                    <!-- Error Modal -->
                <div x-show="showErrorModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <!-- Background overlay -->
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        
                        <!-- Modal panel -->
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Export Error
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500" x-text="errorMessage"></p>
                                        </div>
                                        
                                        <div class="mt-3 bg-gray-50 p-3 rounded-md">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Try these steps:</h4>
                                            <ul class="text-xs text-gray-600 space-y-1 pl-4 list-disc">
                                    <li>Refresh the page and try again</li>
                                    <li>Return to the timetable list and select another timetable</li>
                                                <li>Contact support if the issue persists</li>
                                </ul>
                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" 
                                    @click="showErrorModal = false"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Days of week tabs -->
        <div class="px-4 pt-3">
            <div class="border-b border-gray-200 overflow-x-auto">
                <nav class="-mb-px flex space-x-5 sm:space-x-7" aria-label="Tabs">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $visibleDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isCurrentDay = strtolower(date('l')) == $day;
                            $activeClass = $activeDay === $day ? 'border-green-500 text-green-600' : ($isCurrentDay ? 'border-green-300 text-green-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300');
                            $dayName = ucfirst($day);
                        ?>
                        <a 
                            href="#" 
                            wire:click.prevent="setActiveDay('<?php echo e($day); ?>')"
                            class="group inline-flex items-center py-2.5 px-1 border-b-2 font-medium text-xs whitespace-nowrap <?php echo e($activeClass); ?> transition-colors duration-200">
                            <!--[if BLOCK]><![endif]--><?php if($isCurrentDay): ?>
                                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-600 mr-2 transition-colors duration-200">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            <?php else: ?>
                                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-gray-100 text-gray-500 mr-2 group-hover:bg-gray-200 transition-colors duration-200">
                                    <?php echo e(substr($dayName, 0, 1)); ?>

                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php echo e($dayName); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </nav>
            </div>
        </div>
        
        <!-- Timetable Content -->
        <div class="p-4">
            <!--[if BLOCK]><![endif]--><?php if($filteredPeriods->count() > 0): ?>
                <!-- Current time indicator -->
                <?php
                    $currentTimeData = $this->getCurrentTimeSlots();
                    $isCurrentDayVisible = in_array($currentTimeData['day'], $visibleDays);
                    $nextPeriod = $this->getNextPeriod();
                ?>
                
                <!--[if BLOCK]><![endif]--><?php if($isCurrentDayVisible && $currentTimeData['periods']->count() > 0): ?>
                <div class="mb-4 p-3 bg-green-50 border border-green-100 rounded-lg shadow-sm" id="current-period-indicator" wire:key="current-period-<?php echo e(now()->timestamp); ?>">
                    <div class="flex items-center">
                        <div class="mr-3 flex-shrink-0 p-2 bg-green-100 rounded-full">
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-green-800">Current Period</h3>
                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currentTimeData['periods']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currentPeriod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white text-green-800 border border-green-200">
                                        <span class="h-1.5 w-1.5 mr-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        <?php echo e($currentPeriod->period_name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                        <div class="ml-auto text-xs text-green-600">
                            <span class="font-medium" x-ref="clockDisplay"><?php echo e(date('h:i A')); ?></span>
                        </div>
                    </div>
                    
                    <!--[if BLOCK]><![endif]--><?php if($nextPeriod): ?>
                    <div class="mt-2 pt-2 border-t border-green-100 flex items-center">
                        <div class="mr-3 flex-shrink-0 p-1.5 bg-white rounded-full">
                            <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="text-xs text-green-700">
                            Next: <span class="font-medium"><?php echo e($nextPeriod->period_name); ?></span> at <span class="font-medium"><?php echo e(date('h:i A', strtotime($nextPeriod->start_time))); ?></span>
                            <span class="ml-1 text-green-500 text-xs">(in <?php echo e(\Carbon\Carbon::parse($nextPeriod->start_time)->diffForHumans(now(), ['parts' => 1, 'short' => true])); ?>)</span>
                        </div>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!-- Time filters -->
                <div class="mb-4 flex flex-wrap gap-2 items-center">
                    <button 
                        wire:click="toggleFilters"
                        class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <?php echo e($showFilters ? 'Hide Filters' : 'Show Filters'); ?>

                    </button>
                    
                    <button 
                        wire:click="openBulkAssignModal"
                        class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Bulk Assign
                    </button>
                    
                    <button 
                        wire:click="toggleWeekendDays" 
                        class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <?php echo e($showWeekendDays ? 'Hide Weekend Days' : 'Show Weekend Days'); ?>

                    </button>
                    
                    <div class="ml-auto">
                        <button 
                            type="button"
                            @click="exportTimetable('print')"
                            :class="{ 'opacity-75 cursor-not-allowed': isLoading }"
                            :disabled="isLoading"
                            class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <template x-if="isLoading && exportType === 'print'">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <template x-if="!(isLoading && exportType === 'print')">
                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                            </svg>
                            </template>
                            <span x-text="isLoading && exportType === 'print' ? 'Printing...' : 'Print'"></span>
                        </button>
                    </div>
                </div>
                
                <!--[if BLOCK]><![endif]--><?php if($showFilters): ?>
                <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex flex-wrap items-center gap-3">
                        <div>
                            <label for="filter-subject" class="block text-xs font-medium text-gray-700 mb-1">Subject</label>
                            <select id="filter-subject" wire:model="filterSubject" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-xs">
                                <option value="">All Subjects</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->subject_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                        </div>
                        
                        <div>
                            <label for="filter-teacher" class="block text-xs font-medium text-gray-700 mb-1">Teacher</label>
                            <select id="filter-teacher" wire:model="filterTeacher" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-xs">
                                <option value="">All Teachers</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                        </div>
                        
                        <div>
                            <label for="filter-day" class="block text-xs font-medium text-gray-700 mb-1">Day</label>
                            <select id="filter-day" wire:model="filterDay" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-xs">
                                <option value="">All Days</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $visibleDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($day); ?>"><?php echo e(ucfirst($day)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                        </div>
                        
                        <div class="mt-auto">
                            <button wire:click="resetFilters" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <div class="overflow-x-auto shadow-sm rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-700">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-white uppercase tracking-wider sticky left-0 z-10 border-r border-green-600">
                                    Time Slot
                                </th>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $visibleDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $isCurrentDay = strtolower(date('l')) == $day;
                                        $headerClass = $isCurrentDay ? 'bg-green-800 text-white' : 'text-white';
                                    ?>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium uppercase tracking-wider <?php echo e($headerClass); ?>">
                                        <!--[if BLOCK]><![endif]--><?php if($isCurrentDay): ?>
                                        <div class="flex justify-center items-center">
                                            <span class="flex h-2 w-2 mr-1.5">
                                                <span class="animate-ping absolute h-2 w-2 rounded-full bg-white opacity-75"></span>
                                                <span class="relative rounded-full h-2 w-2 bg-white"></span>
                                            </span>
                                            <?php echo e(ucfirst($day)); ?>

                                        </div>
                                        <?php else: ?>
                                            <?php echo e(ucfirst($day)); ?>

                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $filteredPeriods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeSlot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $typeClass = $this->getPeriodTypeClass($timeSlot);
                                    $canAssignClass = $this->canAssignClass($timeSlot);
                                    $periodStartTime = date('h:i A', strtotime($timeSlot->start_time));
                                    $periodEndTime = date('h:i A', strtotime($timeSlot->end_time));
                                    
                                    // Calculate duration for visual indicators
                                    $start = \Carbon\Carbon::parse($timeSlot->start_time);
                                    $end = \Carbon\Carbon::parse($timeSlot->end_time);
                                    $durationMinutes = $start->diffInMinutes($end);
                                    
                                    // Show longer periods with more height
                                    $rowHeight = 'h-32'; // Default
                                    if ($durationMinutes < 15) {
                                        $rowHeight = 'h-20'; // Short periods
                                    } elseif ($durationMinutes > 60) {
                                        $rowHeight = 'h-40'; // Long periods
                                    }
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-100">
                                    <td class="px-3 py-3 whitespace-nowrap text-xs font-medium text-gray-900 border-r border-gray-200 bg-gray-50 sticky left-0 z-10">
                                        <div class="flex flex-col">
                                            <div class="flex items-center">
                                                <span class="font-medium text-sm text-green-700"><?php echo e($timeSlot->period_name); ?></span>
                                                <!--[if BLOCK]><![endif]--><?php if($durationMinutes > 60): ?>
                                                    <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                        <?php echo e($durationMinutes); ?> min
                                                    </span>
                                                <?php elseif($durationMinutes < 15): ?>
                                                    <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <?php echo e($durationMinutes); ?> min
                                                    </span>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                            <span class="text-xs text-gray-500"><?php echo e($periodStartTime); ?> - <?php echo e($periodEndTime); ?></span>
                                            
                                            <!-- Visual duration indicator -->
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-green-500 h-1.5 rounded-full" style="width: <?php echo e(min(100, ($durationMinutes / 120) * 100)); ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $visibleDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $entry = $timetableMatrix[$day][$timeSlot->id] ?? null;
                                            
                                            $isCurrentTimeSlot = false;
                                            if (strtolower(date('l')) == $day) {
                                                $currentTime = time();
                                                $startTime = strtotime($timeSlot->start_time);
                                                $endTime = strtotime($timeSlot->end_time);
                                                $isCurrentTimeSlot = $currentTime >= $startTime && $currentTime <= $endTime;
                                            }
                                        ?>
                                        <td class="p-2 text-sm border-b border-gray-100 <?php echo e($isCurrentTimeSlot ? 'bg-green-50' : ($entry ? 'bg-white' : $typeClass)); ?> <?php echo e($rowHeight); ?>"
                                            <?php if($canAssignClass): ?>
                                            wire:click="openEntryModal('<?php echo e($day); ?>', <?php echo e($timeSlot->id); ?>)"
                                            <?php endif; ?>>
                                            <!--[if BLOCK]><![endif]--><?php if($entry): ?>
                                                <div class="h-full p-2 rounded-lg border <?php echo e($isCurrentTimeSlot ? 'bg-white border-green-300 shadow-sm ring-1 ring-green-100' : 'border-gray-200 hover:border-green-200'); ?> transition-all duration-200 cursor-pointer hover:shadow-sm">
                                                    <div class="flex items-center">
                                                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 text-green-700 mr-2 flex items-center justify-center">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                            </svg>
                                                        </span>
                                                        <div class="font-medium text-gray-900 truncate text-xs"><?php echo e($entry->subject->subject_name ?? 'N/A'); ?></div>
                                                        <!--[if BLOCK]><![endif]--><?php if($isCurrentTimeSlot): ?>
                                                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <span class="h-1.5 w-1.5 rounded-full bg-green-600 animate-pulse mr-1"></span>
                                                                Now
                                                            </span>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                    
                                                    <div class="mt-2 pl-6 space-y-1">
                                                        <!--[if BLOCK]><![endif]--><?php if(isset($entry->teacher)): ?>
                                                            <div class="flex items-center text-xs text-gray-600">
                                                                <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                                <?php echo e($entry->teacher->name ?? 'N/A'); ?>

                                                            </div>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        <!--[if BLOCK]><![endif]--><?php if(isset($entry->classroom) && $entry->classroom): ?>
                                                            <div class="flex items-center text-xs text-gray-600">
                                                                <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                                <?php echo e($entry->classroom); ?>

                                                            </div>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                    
                                                    <!--[if BLOCK]><![endif]--><?php if(isset($entry->notes) && $entry->notes): ?>
                                                        <div class="mt-2 pt-2 text-xs text-gray-500 border-t border-gray-100">
                                                            <p class="line-clamp-2"><?php echo e($entry->notes); ?></p>
                                                        </div>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    
                                                    <div class="mt-2 flex justify-end">
                                                        <button wire:click.stop="copyEntry(<?php echo e($entry->id); ?>)" 
                                                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium rounded text-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-gray-100 mr-1">
                                                            <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2z" />
                                                            </svg>
                                                            Copy
                                                        </button>
                                                        <button wire:click.stop="deleteEntry(<?php echo e($entry->id); ?>)" 
                                                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium rounded text-red-600 hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-100">
                                                            <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <!--[if BLOCK]><![endif]--><?php if($canAssignClass): ?>
                                                <div class="h-full flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:bg-green-50 rounded transition-colors duration-200 border-2 border-dashed border-gray-200 hover:border-green-200"
                                                     wire:click="openEntryModal('<?php echo e($day); ?>', <?php echo e($timeSlot->id); ?>)">
                                                    <svg class="w-6 h-6 mb-1 text-gray-400 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    <span class="text-xs font-medium text-gray-500 hover:text-green-600">Add class</span>
                                                </div>
                                                <?php else: ?>
                                                <div class="h-full flex flex-col items-center justify-center">
                                                    <span class="text-xs text-gray-400 italic"><?php echo e($timeSlot->period_name); ?></span>
                                                    <!--[if BLOCK]><![endif]--><?php if(str_contains(strtolower($timeSlot->period_name), 'break') || 
                                                        str_contains(strtolower($timeSlot->period_name), 'lunch')): ?>
                                                        <svg class="w-6 h-6 mt-2 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    <?php elseif(str_contains(strtolower($timeSlot->period_name), 'movement') || 
                                                            str_contains(strtolower($timeSlot->period_name), 'transition')): ?>
                                                        <svg class="w-6 h-6 mt-2 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                                        </svg>
                                                    <?php else: ?>
                                                        <svg class="w-6 h-6 mt-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                
                <!-- Added: Legend for period types -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <h3 class="text-xs font-medium text-gray-700 mb-2">Timetable Legend</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                            Break Periods
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            Assembly/Homeroom
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                            Prep/Study Time
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                            Weekend Classes
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            Long Period (>60 min)
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Short Period (<15 min)
                        </span>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty state for timetable -->
                <div class="text-center py-12 px-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No time slots</h3>
                    <p class="mt-1 text-sm text-gray-500">You need to create time slots in the "Time Slots" tab first.</p>
                    <div class="mt-6">
                        <button 
                            wire:click="switchTab('time-slots')"
                            type="button" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Time Slots
                        </button>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    
    <!-- After the timetable content section -->
    <div class="relative">
        <!-- Main timetable content -->
        <div class="transition-all duration-300 ease-in-out" :class="{ 'opacity-50 pointer-events-none': $wire.showAutoGenerateCard }">
            <!-- Your existing timetable content here -->
            <!--[if BLOCK]><![endif]--><?php if($filteredPeriods->count() > 0): ?>
                <!-- ... existing timetable content ... -->
            <?php else: ?>
                <!-- ... existing empty state content ... -->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Auto-generate card overlay -->
        <!--[if BLOCK]><![endif]--><?php if($showAutoGenerateCard && $activeCard === 'auto-generate'): ?>
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity z-40"></div>
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="w-full max-w-5xl transform transition-all">
                        <?php echo $__env->make('livewire.timetable.partials.auto-generate-card', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    
    <!-- Debug element to show modal state -->
    <div class="text-xs text-gray-500 ml-2">
        Modal state: <?php echo e($showAutoGenerateModal ? 'Showing' : 'Hidden'); ?>

    </div>
    
    <!-- Debug element for export IDs, visible only in development -->
    <!--[if BLOCK]><![endif]--><?php if(config('app.debug')): ?>
    <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800">
        <strong>DEBUG:</strong> 
        <ul>
            <li>timetableRecordId property: "<?php echo e($timetableRecordId); ?>" (<?php echo e(empty($timetableRecordId) ? 'EMPTY' : 'NOT EMPTY'); ?>)</li>
            <li>sectionId property: "<?php echo e($sectionId); ?>" (<?php echo e(empty($sectionId) ? 'EMPTY' : 'NOT EMPTY'); ?>)</li>
            <li>timetable object: <?php echo e(isset($timetable) ? ($timetable ? 'EXISTS - ID: '.$timetable->id.' / Name: '.$timetable->name : 'NULL') : 'NOT SET'); ?></li>
            <li>section object: <?php echo e(isset($section) ? ($section ? 'EXISTS - ID: '.$section->id.' / Name: '.$section->name : 'NULL') : 'NOT SET'); ?></li>
        </ul>
        
        <?php 
        $fallbackTimetableId = isset($timetable) && $timetable ? $timetable->id : null;
        ?>
        
        <div class="mt-2">
            <strong>Proposed Fix:</strong> 
            <!--[if BLOCK]><![endif]--><?php if(empty($timetableRecordId) && !empty($fallbackTimetableId)): ?>
                <span class="text-green-600">Using timetable object ID (<?php echo e($fallbackTimetableId); ?>) as fallback</span>
            <?php elseif(!empty($timetableRecordId)): ?>
                <span class="text-green-600">Using timetableRecordId property (<?php echo e($timetableRecordId); ?>)</span>
            <?php else: ?>
                <span class="text-red-600">No valid timetable ID available!</span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        
        <div class="mt-2">
            <strong>Manual URLs (JavaScript construction):</strong><br>
            <?php
            $effectiveId = !empty($timetableRecordId) ? $timetableRecordId : $fallbackTimetableId;
            ?>
            <!--[if BLOCK]><![endif]--><?php if(!empty($effectiveId)): ?>
                Print: <?php echo e(url("timetables/print")); ?>/<?php echo e($effectiveId); ?><?php echo e($sectionId ? '/'.$sectionId : ''); ?><br>
                PDF: <?php echo e(url("timetables/export/pdf")); ?>/<?php echo e($effectiveId); ?><?php echo e($sectionId ? '/'.$sectionId : ''); ?><br>
                Excel: <?php echo e(url("timetables/export/excel")); ?>/<?php echo e($effectiveId); ?><?php echo e($sectionId ? '/'.$sectionId : ''); ?>

            <?php else: ?>
                <span class="text-red-600">Cannot generate URLs - no valid timetable ID available</span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        
        <!--[if BLOCK]><![endif]--><?php if(!empty($effectiveId)): ?>
        <div class="mt-2">
            <strong>Route URLs (Laravel route() helper):</strong><br>
            Print: <?php echo e(route('tt.print', ['timetableId' => $effectiveId, 'sectionId' => $sectionId])); ?><br>
            PDF: <?php echo e(route('tt.export.pdf', ['timetableId' => $effectiveId, 'sectionId' => $sectionId])); ?><br>
            Excel: <?php echo e(route('tt.export.excel', ['timetableId' => $effectiveId, 'sectionId' => $sectionId])); ?>

        </div>
        <?php else: ?>
        <div class="mt-2 text-red-600">
            <strong>WARNING:</strong> Cannot generate route URLs because no valid timetable ID is available!
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <div class="mt-2 pt-2 border-t border-yellow-200">
            <strong>Actions:</strong>
            <ul class="list-disc pl-5 mt-1">
                <li>Try refreshing the page</li>
                <li>Check if the timetable exists in the database</li>
                <li>Verify the route parameter is being passed correctly</li>
            </ul>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <script>
        // Debug info for timetable export
        console.log('TimetableView initialized with timetableId:', '<?php echo e($timetableRecordId); ?>', 'sectionId:', '<?php echo e($sectionId); ?>');
        
        // Listen for both Livewire initialization events to ensure compatibility
        document.addEventListener('livewire:initialized', setupEventListeners);
        document.addEventListener('livewire:load', setupEventListeners);
        
        // Use direct event listeners as a fallback
        document.addEventListener('DOMContentLoaded', setupEventListeners);
        
        function setupEventListeners() {
            console.log('Setting up event listeners for timetable export');
            
            // Function to handle printing
            window.addEventListener('openPrintWindow', (event) => {
                try {
                    const url = event.detail.url || event.detail[0].url;
                    console.log('openPrintWindow event received, URL:', url);
                    
                    // Open the print view in a new tab
                    const printWindow = window.open(url, '_blank');
                    
                    // Focus the new window and print when content is loaded
                    if (printWindow) {
                        printWindow.focus();
                    } else {
                        console.error('Failed to open print window - popup blocker?');
                        // Show a user-friendly message if popup is blocked
                        alert('Print window was blocked. Please allow popups for this site to use the print feature.');
                    }
                } catch (err) {
                    console.error('Error handling print event:', err);
                }
            });
            
            // Function to handle downloads
            window.addEventListener('triggerDownload', (event) => {
                try {
                    const url = event.detail.url || event.detail[0].url;
                    console.log('triggerDownload event received, URL:', url);
                    
                    // Create a temporary link and trigger the download
                    const link = document.createElement('a');
                    link.href = url;
                    link.target = '_blank';
                    link.click();
                } catch (err) {
                    console.error('Error handling download event:', err);
                }
            });
            
            // Check for flash session values - used as a fallback
            checkForFlashUrls();
        }
        
        // Legacy event handler compatibility
        if (typeof window.Livewire !== 'undefined') {
            console.log('Livewire detected, setting up legacy event handlers');
            
            window.Livewire.on('openPrintWindow', (data) => {
                try {
                    const url = data.url || data[0].url;
                    console.log('Legacy openPrintWindow event received, URL:', url);
                    const printWindow = window.open(url, '_blank');
                    if (printWindow) printWindow.focus();
                } catch (err) {
                    console.error('Error handling legacy print event:', err);
                }
            });
            
            window.Livewire.on('triggerDownload', (data) => {
                try {
                    const url = data.url || data[0].url;
                    console.log('Legacy triggerDownload event received, URL:', url);
                    const link = document.createElement('a');
                    link.href = url;
                    link.target = '_blank';
                    link.click();
                } catch (err) {
                    console.error('Error handling legacy download event:', err);
                }
            });
        }
        
        // Check if we have flash session URLs to handle
        function checkForFlashUrls() {
            try {
                // This function checks session storage for URLs set by PHP
                const printUrl = <?php echo json_encode(session('print_url'), 15, 512) ?>;
                const downloadUrl = <?php echo json_encode(session('download_url'), 15, 512) ?>;
                
                console.log('Checking for flash URLs - print:', printUrl, 'download:', downloadUrl);
                
                if (typeof printUrl === 'string' && printUrl) {
                    console.log('Found print URL in session:', printUrl);
                    window.open(printUrl, '_blank');
                }
                
                if (typeof downloadUrl === 'string' && downloadUrl) {
                    console.log('Found download URL in session:', downloadUrl);
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.target = '_blank';
                    link.click();
                }
            } catch (err) {
                console.error('Error checking flash URLs:', err);
            }
        }
    </script>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/timetable-view.blade.php ENDPATH**/ ?>