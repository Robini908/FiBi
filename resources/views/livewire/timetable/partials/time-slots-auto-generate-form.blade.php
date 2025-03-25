<!-- Auto-Generate Time Slots Form with Modern, Google-like UI -->
<div class="animate-fade-in m-4 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-green-100 border-b border-green-200">
        <div class="flex items-center">
            <div class="bg-white p-2 rounded-full shadow-sm mr-3">
                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-medium text-gray-800">Auto-Generate Time Slots</h2>
                <p class="text-xs text-gray-600">Configure your school schedule parameters</p>
            </div>
        </div>
    </div>
    
    <form wire:submit.prevent="generateTimeSlots" class="p-4">
        <!-- Main Settings Card -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
            <!-- Left Column: School Hours & Primary Settings -->
            <div class="sm:col-span-5 space-y-5">
                <!-- School Hours Card -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            School Hours
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <!-- School Start Time -->
                        <div>
                            <label for="school_start_time" class="block text-xs font-medium text-gray-700 mb-1">School Start Time</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="time" id="school_start_time" wire:model.live="autoGenerateForm.school_start_time"
                                       class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                            </div>
                        </div>
                        
                        <!-- School End Time -->
                        <div>
                            <label for="school_end_time" class="block text-xs font-medium text-gray-700 mb-1">School End Time</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="time" id="school_end_time" wire:model.live="autoGenerateForm.school_end_time"
                                       class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Lesson Settings Card -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Lesson Configuration
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <!-- Lesson Duration -->
                        <div>
                            <label for="lesson_duration" class="block text-xs font-medium text-gray-700 mb-1">Lesson Duration (minutes)</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="number" id="lesson_duration" wire:model.live="autoGenerateForm.lesson_duration" min="20" max="120"
                                       class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                            </div>
                            @error('autoGenerateForm.lesson_duration') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Activity Types Card -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Activity Types
                        </h3>
                    </div>
                    <div class="p-3">
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Include Breaks Checkbox -->
                            <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                <input id="include_breaks" type="checkbox" wire:model="autoGenerateForm.include_breaks" 
                                       class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <label for="include_breaks" class="ml-2 block text-xs text-gray-700">
                                    Short breaks
                                    @if(isset($autoGenerateForm['include_breaks']) && $autoGenerateForm['include_breaks'])
                                        <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            On
                                        </span>
                                    @else
                                        <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            Off
                                        </span>
                                    @endif
                                </label>
                            </div>
                            
                            <!-- Include Lunch Checkbox -->
                            <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                <input id="include_lunch" type="checkbox" wire:model="autoGenerateForm.include_lunch" 
                                       class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <label for="include_lunch" class="ml-2 block text-xs text-gray-700">
                                    Lunch break
                                    @if(isset($autoGenerateForm['include_lunch']) && $autoGenerateForm['include_lunch'])
                                        <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            On
                                        </span>
                                    @else
                                        <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            Off
                                        </span>
                                    @endif
                                </label>
                            </div>
                            
                            <!-- Include Extracurricular/Games Checkbox -->
                            <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                <input id="include_extracurricular" type="checkbox" wire:model="autoGenerateForm.include_extracurricular" 
                                       class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <label for="include_extracurricular" class="ml-2 block text-xs text-gray-700">
                                    Games & activities
                                    @if(isset($autoGenerateForm['include_extracurricular']) && $autoGenerateForm['include_extracurricular'])
                                        <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            On
                                        </span>
                                    @else
                                        <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            Off
                                        </span>
                                    @endif
                                </label>
                            </div>
                            
                            <!-- Include Transitions Checkbox -->
                            <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                                <input id="include_transitions" type="checkbox" wire:model="autoGenerateForm.include_transitions" 
                                       class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <label for="include_transitions" class="ml-2 block text-xs text-gray-700">
                                    Movement time
                                    @if(isset($autoGenerateForm['include_transitions']) && $autoGenerateForm['include_transitions'])
                                        <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            On
                                        </span>
                                    @else
                                        <span class="inline-flex items-center ml-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            Off
                                        </span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Preview & Advanced Settings -->
            <div class="sm:col-span-7 space-y-4">
                <!-- Schedule Preview Card -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-sm font-medium text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Live Preview
                        </h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                            Auto-updates
                        </span>
                    </div>
                    <div class="p-4">
                        @include('livewire.timetable.partials.time-slots-preview')
                    </div>
                </div>
                
                <!-- Advanced Settings Card -->
                <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                    <div @click="open = !open" class="px-4 py-2 bg-gray-50 border-b border-gray-200 flex justify-between items-center cursor-pointer">
                        <h3 class="text-sm font-medium text-gray-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Advanced Settings
                        </h3>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div x-show="open" x-transition class="p-4 divide-y divide-gray-200">
                        @include('livewire.timetable.partials.time-slots-advanced-settings')
                    </div>
                </div>
                
                <!-- Extended Schedule Settings Card -->
                <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                    <div @click="open = !open" class="px-4 py-2 bg-gradient-to-r from-indigo-50 to-indigo-100 border-b border-indigo-200 flex justify-between items-center cursor-pointer">
                        <h3 class="text-sm font-medium text-indigo-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Extended Schedule (Weekend & Prep)
                        </h3>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                Boarding Schools
                            </span>
                            <svg :class="{'rotate-180': open}" class="w-5 h-5 text-indigo-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div x-show="open" x-transition class="p-4 divide-y divide-gray-200">
                        @include('livewire.timetable.partials.time-slots-extended-schedule')
                    </div>
                </div>
                
                <!-- "How to use" tips -->
                <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                    <div @click="open = !open" class="px-4 py-2 bg-blue-50 border-b border-blue-200 flex justify-between items-center cursor-pointer">
                        <h3 class="text-sm font-medium text-blue-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tips & Examples
                        </h3>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-blue-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div x-show="open" x-transition class="p-4 bg-gradient-to-r from-blue-50 to-white">
                        @include('livewire.timetable.partials.time-slots-how-to-use')
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Action Buttons -->
        <div class="mt-6 pt-3 border-t border-gray-100 flex justify-between items-center">
            <button type="button" wire:click="backToMain" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Time Slots
            </button>
            
            <div class="flex space-x-3">
                <button type="button" wire:click="resetAutoGenerateForm" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset to Defaults
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Generate Time Slots
                </button>
            </div>
        </div>
    </form>
</div> 