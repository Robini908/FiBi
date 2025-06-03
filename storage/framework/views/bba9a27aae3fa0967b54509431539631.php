<div>
    <!-- Real-time notification banner -->
    <div x-data="{ show: false, message: '', type: 'info' }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         @notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => { show = false }, 5000)"
         class="mb-4 rounded-md p-4"
         :class="{
             'bg-green-50 border border-green-200': type === 'success',
             'bg-blue-50 border border-blue-200': type === 'info',
             'bg-red-50 border border-red-200': type === 'error',
             'bg-yellow-50 border border-yellow-200': type === 'warning'
         }">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg x-show="type === 'success'" class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <svg x-show="type === 'info'" class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <svg x-show="type === 'warning'" class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <svg x-show="type === 'error'" class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p x-text="message" class="text-sm font-medium" :class="{
                    'text-green-800': type === 'success',
                    'text-blue-800': type === 'info',
                    'text-red-800': type === 'error',
                    'text-yellow-800': type === 'warning'
                }"></p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button @click="show = false" class="inline-flex p-1.5 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2" :class="{
                        'text-green-500 hover:bg-green-100 focus:ring-green-600': type === 'success',
                        'text-blue-500 hover:bg-blue-100 focus:ring-blue-600': type === 'info',
                        'text-red-500 hover:bg-red-100 focus:ring-red-600': type === 'error',
                        'text-yellow-500 hover:bg-yellow-100 focus:ring-yellow-600': type === 'warning'
                    }">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <!-- Header and Filters Section -->
        <?php echo $__env->make('livewire.attendance.partials.analytics-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <!-- Analytics Tabs Navigation -->
        <div class="px-4 py-2 sm:px-6 border-b border-gray-200 bg-gray-50">
            <div class="flex overflow-x-auto space-x-4 pb-1">
                <button wire:click="setTab('overview')" wire:loading.class="opacity-50" wire:loading.attr="disabled" wire:target="setTab" class="px-3 py-2 font-medium text-sm rounded-md whitespace-nowrap <?php echo e($activeTab === 'overview' ? 'bg-green-100 text-green-800' : 'text-gray-500 hover:text-gray-700'); ?> focus:outline-none">
                    <span wire:loading.remove wire:target="setTab('overview')">Overview</span>
                    <span wire:loading wire:target="setTab('overview')" class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
                <button wire:click="setTab('trends')" wire:loading.class="opacity-50" wire:loading.attr="disabled" wire:target="setTab" class="px-3 py-2 font-medium text-sm rounded-md whitespace-nowrap <?php echo e($activeTab === 'trends' ? 'bg-green-100 text-green-800' : 'text-gray-500 hover:text-gray-700'); ?> focus:outline-none">
                    <span wire:loading.remove wire:target="setTab('trends')">Trends & Patterns</span>
                    <span wire:loading wire:target="setTab('trends')" class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
                <button wire:click="setTab('students')" wire:loading.class="opacity-50" wire:loading.attr="disabled" wire:target="setTab" class="px-3 py-2 font-medium text-sm rounded-md whitespace-nowrap <?php echo e($activeTab === 'students' ? 'bg-green-100 text-green-800' : 'text-gray-500 hover:text-gray-700'); ?> focus:outline-none">
                    <span wire:loading.remove wire:target="setTab('students')">Student Rankings</span>
                    <span wire:loading wire:target="setTab('students')" class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
                <button wire:click="setTab('comparison')" wire:loading.class="opacity-50" wire:loading.attr="disabled" wire:target="setTab" class="px-3 py-2 font-medium text-sm rounded-md whitespace-nowrap <?php echo e($activeTab === 'comparison' ? 'bg-green-100 text-green-800' : 'text-gray-500 hover:text-gray-700'); ?> focus:outline-none">
                    <span wire:loading.remove wire:target="setTab('comparison')">Class Comparison</span>
                    <span wire:loading wire:target="setTab('comparison')" class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
            </div>
        </div>
        
        <!-- Tab Content Section -->
        <div class="p-4 sm:p-6">
            <div x-show="true" x-data="{ tab: <?php if ((object) ('activeTab') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('activeTab'->value()); ?>')<?php echo e('activeTab'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('activeTab'); ?>')<?php endif; ?> }">
                <!-- Overview Tab Content -->
                <div x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="relative">
                        <div wire:loading wire:target="loadData, refreshData" class="absolute inset-0 bg-white bg-opacity-75 rounded-lg flex items-center justify-center z-10">
                            <div class="text-center p-6">
                                <svg class="animate-spin h-10 w-10 text-green-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-600">Loading overview data...</p>
                            </div>
                        </div>
                    <?php echo $__env->make('livewire.attendance.partials.analytics-overview', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                
                <!-- Trends Tab Content -->
                <div x-show="tab === 'trends'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="relative">
                        <div wire:loading wire:target="loadData, refreshData, periodType, trendType" class="absolute inset-0 bg-white bg-opacity-75 rounded-lg flex items-center justify-center z-10">
                            <div class="text-center p-6">
                                <svg class="animate-spin h-10 w-10 text-green-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-600">Loading trend data...</p>
                            </div>
                        </div>
                    <?php echo $__env->make('livewire.attendance.partials.analytics-trends', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                
                <!-- Students Tab Content -->
                <div x-show="tab === 'students'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="relative">
                        <div wire:loading wire:target="loadData, refreshData" class="absolute inset-0 bg-white bg-opacity-75 rounded-lg flex items-center justify-center z-10">
                            <div class="text-center p-6">
                                <svg class="animate-spin h-10 w-10 text-green-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-600">Loading student data...</p>
                            </div>
                        </div>
                    <?php echo $__env->make('livewire.attendance.partials.analytics-students', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                
                <!-- Comparison Tab Content -->
                <div x-show="tab === 'comparison'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="relative">
                        <div wire:loading wire:target="loadData, refreshData" class="absolute inset-0 bg-white bg-opacity-75 rounded-lg flex items-center justify-center z-10">
                            <div class="text-center p-6">
                                <svg class="animate-spin h-10 w-10 text-green-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-600">Loading comparison data...</p>
                            </div>
                        </div>
                    <?php echo $__env->make('livewire.attendance.partials.analytics-comparison', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('livewire:init', function () {
        // Initialize notifications
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').on('notify', param => {
            toastr[param.type](param.message);
        });
        
        // Initialize date picker
        flatpickr("#analytics-date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('startDate', selectedDates[0].toISOString().slice(0, 10));
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('endDate', selectedDates[1].toISOString().slice(0, 10));
                }
            }
        });
        
        // Setup polling for real-time updates
        let pollingInterval;
        
        function startPolling(interval) {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
            
            pollingInterval = setInterval(() => {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('refreshData');
            }, interval);
        }
        
        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }
        
        // Start polling on load if enabled
        if (window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('pollingEnabled')) {
            startPolling(window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('pollingInterval'));
        }
        
        // Handle polling toggle
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').on('polling-toggled', param => {
            if (param.enabled) {
                toastr.success('Auto-refresh turned on');
                startPolling(window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('pollingInterval'));
            } else {
                toastr.info('Auto-refresh turned off');
                stopPolling();
            }
        });
        
        // Handle polling interval changes
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').on('polling-interval-changed', param => {
            if (window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('pollingEnabled')) {
                startPolling(param.interval);
                toastr.success(`Refresh interval set to ${param.interval / 1000} seconds`);
            }
        });
        
        // Notify of data refresh
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').on('data-refreshed', param => {
            toastr.info(`Data refreshed at ${param.timestamp}`);
        });
        
        // Stop polling when user leaves the page
        window.addEventListener('beforeunload', () => {
            stopPolling();
        });
        
        // Resume polling when tab becomes visible again
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible' && window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('pollingEnabled')) {
                startPolling(window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('pollingInterval'));
            } else {
                stopPolling();
            }
        });
    });
</script>
<?php $__env->stopPush(); ?> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/attendance-analytics.blade.php ENDPATH**/ ?>