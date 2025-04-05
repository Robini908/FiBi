<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Main container with Alpine.js data handling -->
    <div x-data="{ 
        activeTab: 'timetable',
        showSettings: false,
        showGeneration: false
    }">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-medium text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Exam Timetable Management
                </h1>
                
                <div class="flex space-x-3">
                    <button type="button" @click="showSettings = !showSettings" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </button>
                    
                    <button type="button" @click="showGeneration = true" wire:click="openGenerationSettings" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Auto Generate
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="bg-white border-b border-gray-200">
            <nav class="flex">
                <button @click="activeTab = 'timetable'" :class="{'border-green-500 text-green-600': activeTab === 'timetable', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'timetable'}" class="group inline-flex items-center py-4 px-4 border-b-2 font-medium text-sm">
                    <svg :class="{'text-green-500': activeTab === 'timetable', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'timetable'}" class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Timetable
                </button>
                
                <button @click="activeTab = 'export'" :class="{'border-green-500 text-green-600': activeTab === 'export', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'export'}" class="group inline-flex items-center py-4 px-4 border-b-2 font-medium text-sm">
                    <svg :class="{'text-green-500': activeTab === 'export', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'export'}" class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </nav>
        </div>

        <!-- Filter Bar -->
        @include('livewire.timetable.partials.exam-timetable-filters')
        
        <!-- Tab Content -->
        <div class="p-6">
            <!-- Timetable Tab Content -->
            <div x-show="activeTab === 'timetable'">
                @include('livewire.timetable.partials.exam-timetable-content')
            </div>
            
            <!-- Export Tab Content -->
            <div x-show="activeTab === 'export'">
                @include('livewire.timetable.partials.exam-timetable-export')
            </div>
        </div>
        
        <!-- Settings Modal -->
        <div x-show="showSettings" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
            @include('livewire.timetable.partials.exam-timetable-settings-modal')
        </div>
        
        <!-- Generation Settings Modal -->
        <div x-show="showGeneration" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
            @include('livewire.timetable.partials.exam-timetable-generation-modal')
        </div>
        
        <!-- Schedule Form Modal -->
        <div x-show="$wire.showScheduleForm" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
            @include('livewire.timetable.partials.exam-timetable-schedule-form')
        </div>
    </div>
</div> 