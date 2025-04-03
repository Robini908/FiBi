<div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-gray-50 px-4 py-4 sm:px-6">
    <div class="flex flex-wrap items-center justify-between">
        <div class="flex items-center">
            <h3 class="text-lg font-medium text-gray-900">Attendance Analytics</h3>
            <!-- Real-time indicator -->
            <div class="ml-3 flex items-center">
                <span class="text-xs text-gray-500 mr-2">Last updated: {{ $lastUpdated ?? now()->format('Y-m-d H:i:s') }}</span>
                <span class="flex h-3 w-3 relative">
                    <span class="{{ $pollingEnabled ? 'animate-ping' : '' }} absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ $pollingEnabled ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                </span>
            </div>
        </div>
        <div class="flex space-x-2">
            <!-- Toggle Automatic Refresh -->
            <button wire:click="togglePolling" class="inline-flex items-center px-3 py-1.5 border {{ $pollingEnabled ? 'border-green-700 bg-green-600 text-white' : 'border-gray-300 text-gray-700 bg-white' }} text-xs font-medium rounded shadow-sm hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                {{ $pollingEnabled ? 'Auto-refresh On' : 'Auto-refresh Off' }}
            </button>
            
            <!-- Manual Refresh -->
            <button wire:click="refreshData" wire:loading.attr="disabled" wire:loading.class="opacity-75" class="inline-flex items-center px-3 py-1.5 border border-green-700 text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading.class="animate-spin" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span wire:loading.remove wire:target="refreshData">Refresh Data</span>
                <span wire:loading wire:target="refreshData">Refreshing...</span>
            </button>
            
            <!-- Export Button -->
            <button wire:click="exportReport" wire:loading.attr="disabled" wire:loading.class="opacity-75" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading.class="animate-spin" wire:loading.class.remove="hidden" wire:target="exportReport" class="hidden h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading.class="hidden" wire:target="exportReport" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span wire:loading.remove wire:target="exportReport">Export</span>
                <span wire:loading wire:target="exportReport">Exporting...</span>
            </button>
        </div>
    </div>
</div>

<div class="px-4 py-5 sm:p-6 bg-gray-50 border-b border-gray-200">
    <!-- Loading indicator for the entire section -->
    <div wire:loading wire:target="refreshData, classId, sectionId, startDate, endDate" class="absolute inset-0 bg-gray-200 bg-opacity-50 flex items-center justify-center z-10 rounded-md">
        <div class="bg-white p-4 rounded-lg shadow-lg flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-700">Loading...</span>
        </div>
    </div>
    
    <!-- Class/Section Selection and Date Range Filters -->
    <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-3 relative">
        <div>
            <label for="classId" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
            <div class="relative">
                <select id="classId" wire:model.live="classId" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                <div wire:loading wire:target="classId" class="absolute inset-y-0 right-0 pr-3 flex items-center z-10">
                    <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div>
            <label for="sectionId" class="block text-sm font-medium text-gray-700 mb-1">Section</label>
            <div class="relative">
                <select id="sectionId" wire:model.live="sectionId" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" {{ empty($classId) ? 'disabled' : '' }}>
                    <option value="">All Sections</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
                <div wire:loading wire:target="sectionId" class="absolute inset-y-0 right-0 pr-3 flex items-center z-10">
                    <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Date Range Selector -->
        <div>
            <label for="analytics-date-range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
            <div class="relative">
                <div class="flex items-center">
                    <input id="analytics-date-range" type="text" placeholder="Select date range" class="block w-full pr-10 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" value="{{ $startDate }} to {{ $endDate }}">
                    <div wire:loading wire:target="startDate, endDate" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="mt-2 flex flex-wrap gap-2">
                <button type="button" wire:click="setDateRange('last_7_days')" wire:loading.class="opacity-50" wire:target="setDateRange" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Last 7 Days
                </button>
                <button type="button" wire:click="setDateRange('last_30_days')" wire:loading.class="opacity-50" wire:target="setDateRange" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Last 30 Days
                </button>
                <button type="button" wire:click="setDateRange('this_month')" wire:loading.class="opacity-50" wire:target="setDateRange" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    This Month
                </button>
                <button type="button" wire:click="setDateRange('last_month')" wire:loading.class="opacity-50" wire:target="setDateRange" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Last Month
                </button>
                <button type="button" wire:click="setDateRange('this_term')" wire:loading.class="opacity-50" wire:target="setDateRange" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    This Term
                </button>
            </div>
        </div>
    </div>
    
    <!-- Quick Filters and Trend Options -->
    <div class="mt-4 flex flex-wrap gap-2">
        <div>
            <span class="text-sm font-medium text-gray-700 mr-2">Quick Periods:</span>
            <button type="button" wire:click="setDateRange('this_week')" wire:loading.class="opacity-50" wire:loading.attr="disabled" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500">
                <span wire:loading.remove wire:target="setDateRange">This Week</span>
                <span wire:loading wire:target="setDateRange">Loading...</span>
            </button>
            <button type="button" wire:click="setDateRange('this_term')" wire:loading.class="opacity-50" wire:loading.attr="disabled" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500">
                This Term
            </button>
            <button type="button" wire:click="setDateRange('academic_year')" wire:loading.class="opacity-50" wire:loading.attr="disabled" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-green-500">
                Academic Year
            </button>
        </div>
        
        @if($activeTab === 'trends')
            <div class="ml-auto">
                <span class="text-sm font-medium text-gray-700 mr-2">Period:</span>
                <div class="inline-block relative">
                    <select wire:model="periodType" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    <div wire:loading wire:target="periodType" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                
                <span class="text-sm font-medium text-gray-700 ml-4 mr-2">Metric:</span>
                <div class="inline-block relative">
                    <select wire:model="trendType" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        <option value="attendance_rate">Attendance Rate</option>
                        <option value="present_count">Present Count</option>
                        <option value="absent_count">Absent Count</option>
                        <option value="late_count">Late Count</option>
                    </select>
                    <div wire:loading wire:target="trendType" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Real-time Status Indicator -->
<div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-t border-gray-200">
    <div class="flex items-center text-sm text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Last updated: {{ now()->format('M d, Y h:i:s A') }}</span>
        <span wire:loading class="ml-2 inline-flex items-center">
            <svg class="animate-spin h-4 w-4 text-green-600 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Updating...</span>
        </span>
    </div>
    
    <div>
        <button type="button" wire:click="refreshData" wire:loading.class="opacity-50" wire:loading.attr="disabled" wire:target="refreshData" class="inline-flex items-center px-3 py-1.5 border border-green-600 rounded-md bg-green-600 text-white text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <svg wire:loading.remove wire:target="refreshData" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <svg wire:loading wire:target="refreshData" class="animate-spin h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span wire:loading.remove wire:target="refreshData">Refresh Now</span>
            <span wire:loading wire:target="refreshData">Refreshing...</span>
        </button>
    </div>
</div> 