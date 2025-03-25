<!-- Weekend Classes and Prep Activities Settings -->
<div @click.stop class="space-y-5">
    <!-- Weekend Classes Settings -->
    <div class="py-3">
        <div class="flex items-center justify-between">
            <h4 class="text-xs font-semibold text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Weekend Classes
            </h4>
            <label class="inline-flex items-center cursor-pointer">
                <span class="text-xs text-gray-700 mr-2">Enable</span>
                <div class="relative">
                    <input type="checkbox" wire:model.live="autoGenerateForm.include_weekend_classes" class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                </div>
            </label>
        </div>
        
        <div x-data="{ show: false }" 
             x-init="$watch('$wire.autoGenerateForm.include_weekend_classes', value => { show = value })" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             class="mt-3 pt-3 border-t border-gray-100">
            
            <!-- Weekend Day Selection -->
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">Day for Weekend Classes</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg cursor-pointer" :class="{ 'ring-2 ring-indigo-500 bg-indigo-50': $wire.autoGenerateForm.weekend_day === 'saturday' }">
                        <input type="radio" wire:model.live="autoGenerateForm.weekend_day" value="saturday" class="hidden">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-indigo-500 mr-2" x-show="$wire.autoGenerateForm.weekend_day === 'saturday'"></span>
                            <span class="w-3 h-3 rounded-full border border-gray-300 mr-2" x-show="$wire.autoGenerateForm.weekend_day !== 'saturday'"></span>
                            <span class="text-xs">Saturday</span>
                        </div>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg cursor-pointer" :class="{ 'ring-2 ring-indigo-500 bg-indigo-50': $wire.autoGenerateForm.weekend_day === 'sunday' }">
                        <input type="radio" wire:model.live="autoGenerateForm.weekend_day" value="sunday" class="hidden">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-indigo-500 mr-2" x-show="$wire.autoGenerateForm.weekend_day === 'sunday'"></span>
                            <span class="w-3 h-3 rounded-full border border-gray-300 mr-2" x-show="$wire.autoGenerateForm.weekend_day !== 'sunday'"></span>
                            <span class="text-xs">Sunday</span>
                        </div>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg cursor-pointer" :class="{ 'ring-2 ring-indigo-500 bg-indigo-50': $wire.autoGenerateForm.weekend_day === 'both' }">
                        <input type="radio" wire:model.live="autoGenerateForm.weekend_day" value="both" class="hidden">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-indigo-500 mr-2" x-show="$wire.autoGenerateForm.weekend_day === 'both'"></span>
                            <span class="w-3 h-3 rounded-full border border-gray-300 mr-2" x-show="$wire.autoGenerateForm.weekend_day !== 'both'"></span>
                            <span class="text-xs">Both Days</span>
                        </div>
                    </label>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <!-- Weekend Class Start Time -->
                <div>
                    <label for="weekend_start_time" class="block text-xs font-medium text-gray-700 mb-1">Start Time</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="time" id="weekend_start_time" wire:model.live="autoGenerateForm.weekend_start_time"
                               class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                    </div>
                    @error('autoGenerateForm.weekend_start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Weekend Class End Time -->
                <div>
                    <label for="weekend_end_time" class="block text-xs font-medium text-gray-700 mb-1">End Time</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="time" id="weekend_end_time" wire:model.live="autoGenerateForm.weekend_end_time"
                               class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                    </div>
                    @error('autoGenerateForm.weekend_end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Weekend Lesson Duration -->
                <div>
                    <label for="weekend_lesson_duration" class="block text-xs font-medium text-gray-700 mb-1">Lesson Duration</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex">
                            <input type="number" id="weekend_lesson_duration" wire:model.live="autoGenerateForm.weekend_lesson_duration" min="30" max="120"
                                   class="pl-7 block w-full rounded-l-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                            <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-gray-200 bg-gray-50 text-gray-500 text-xs">
                                Minutes
                            </span>
                        </div>
                    </div>
                    @error('autoGenerateForm.weekend_lesson_duration') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <p class="mt-2 text-xs text-gray-500 italic">Weekend classes typically focus on additional tutoring, special subjects, or extracurricular activities.</p>
        </div>
    </div>
    
    <!-- Morning Prep Settings -->
    <div class="py-3 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <h4 class="text-xs font-semibold text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Morning Prep
            </h4>
            <label class="inline-flex items-center cursor-pointer">
                <span class="text-xs text-gray-700 mr-2">Enable</span>
                <div class="relative">
                    <input type="checkbox" wire:model.live="autoGenerateForm.include_morning_prep" class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                </div>
            </label>
        </div>
        
        <div x-data="{ show: false }" 
             x-init="$watch('$wire.autoGenerateForm.include_morning_prep', value => { show = value })" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             class="mt-3 pt-3 border-t border-gray-100">
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Morning Prep Start Time -->
                <div>
                    <label for="morning_prep_start_time" class="block text-xs font-medium text-gray-700 mb-1">Start Time</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="time" id="morning_prep_start_time" wire:model.live="autoGenerateForm.morning_prep_start_time"
                               class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                    </div>
                    @error('autoGenerateForm.morning_prep_start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Morning Prep End Time -->
                <div>
                    <label for="morning_prep_end_time" class="block text-xs font-medium text-gray-700 mb-1">End Time</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="time" id="morning_prep_end_time" wire:model.live="autoGenerateForm.morning_prep_end_time"
                               class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                    </div>
                    @error('autoGenerateForm.morning_prep_end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <p class="mt-2 text-xs text-gray-500 italic">Morning prep is usually scheduled before regular classes begin, allowing students to prepare for the day ahead.</p>
        </div>
    </div>
    
    <!-- Evening Prep Settings -->
    <div class="py-3 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <h4 class="text-xs font-semibold text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                Evening Prep
            </h4>
            <label class="inline-flex items-center cursor-pointer">
                <span class="text-xs text-gray-700 mr-2">Enable</span>
                <div class="relative">
                    <input type="checkbox" wire:model.live="autoGenerateForm.include_evening_prep" class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                </div>
            </label>
        </div>
        
        <div x-data="{ show: false }" 
             x-init="$watch('$wire.autoGenerateForm.include_evening_prep', value => { show = value })" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             class="mt-3 pt-3 border-t border-gray-100">
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Evening Prep Start Time -->
                <div>
                    <label for="evening_prep_start_time" class="block text-xs font-medium text-gray-700 mb-1">Start Time</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="time" id="evening_prep_start_time" wire:model.live="autoGenerateForm.evening_prep_start_time"
                               class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                    </div>
                    @error('autoGenerateForm.evening_prep_start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Evening Prep End Time -->
                <div>
                    <label for="evening_prep_end_time" class="block text-xs font-medium text-gray-700 mb-1">End Time</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="time" id="evening_prep_end_time" wire:model.live="autoGenerateForm.evening_prep_end_time"
                               class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                    </div>
                    @error('autoGenerateForm.evening_prep_end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <p class="mt-2 text-xs text-gray-500 italic">Evening prep sessions are typically for boarding schools, allowing students dedicated time for homework and study.</p>
        </div>
    </div>
    
    <!-- Information Box -->
    <div class="mt-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-100">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-xs text-gray-700">
                <p class="font-medium mb-1">Extended Schedule Options</p>
                <p>These settings are primarily designed for boarding schools and institutions with extended academic programs. Weekend classes and prep sessions will be generated as separate time slots in your timetable.</p>
                <p class="mt-1">Tip: Enable these options only if they are part of your school's regular schedule.</p>
            </div>
        </div>
    </div>
</div> 