<!-- Advanced Options Tab Content -->
<div class="space-y-5 py-3">
    <!-- Time Period Preferences Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h4 class="text-sm font-medium text-gray-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Time Period Preferences
            </h4>
            <p class="text-xs text-gray-500 mt-1 ml-7">Configure the boundaries between morning, midday, and afternoon periods</p>
        </div>

        <div class="p-5 space-y-6">
            <!-- Morning End Time Slider -->
            <div class="relative">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium text-gray-700">Morning End Time</label>
                    <span class="text-sm font-medium text-green-600">
                        {{ floor($autoGenerateForm['time_preferences']['morning_end']/60) }}:{{ str_pad($autoGenerateForm['time_preferences']['morning_end']%60, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-xs text-gray-500 w-10 text-right">6:00</span>
                    <div class="relative flex-1 mx-2">
                        <div class="absolute inset-0 flex items-center pointer-events-none">
                            <div class="w-full bg-gray-200 h-0.5"></div>
                        </div>
                        <input 
                            type="range" 
                            wire:model.defer="autoGenerateForm.time_preferences.morning_end" 
                            min="360" 
                            max="720" 
                            step="15"
                            class="w-full h-2 bg-green-100 rounded-lg appearance-none cursor-pointer accent-green-600 relative z-10">
                    </div>
                    <span class="ml-2 text-xs text-gray-500 w-10">12:00</span>
                </div>
                <p class="mt-1 text-xs text-gray-500">Subjects marked as "Morning" will be scheduled before this time</p>
            </div>
            
            <!-- Midday End Time Slider -->
            <div class="relative">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium text-gray-700">Midday End Time</label>
                    <span class="text-sm font-medium text-green-600">
                        {{ floor($autoGenerateForm['time_preferences']['midday_end']/60) }}:{{ str_pad($autoGenerateForm['time_preferences']['midday_end']%60, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-xs text-gray-500 w-10 text-right">11:00</span>
                    <div class="relative flex-1 mx-2">
                        <div class="absolute inset-0 flex items-center pointer-events-none">
                            <div class="w-full bg-gray-200 h-0.5"></div>
                        </div>
                        <input 
                            type="range" 
                            wire:model.defer="autoGenerateForm.time_preferences.midday_end" 
                            min="660" 
                            max="840" 
                            step="15"
                            class="w-full h-2 bg-green-100 rounded-lg appearance-none cursor-pointer accent-green-600 relative z-10">
                    </div>
                    <span class="ml-2 text-xs text-gray-500 w-10">14:00</span>
                </div>
                <p class="mt-1 text-xs text-gray-500">Subjects marked as "Midday" will be scheduled between morning and this time</p>
            </div>
            
            <!-- Afternoon End Time Slider -->
            <div class="relative">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium text-gray-700">Afternoon End Time</label>
                    <span class="text-sm font-medium text-green-600">
                        {{ floor($autoGenerateForm['time_preferences']['afternoon_end']/60) }}:{{ str_pad($autoGenerateForm['time_preferences']['afternoon_end']%60, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-xs text-gray-500 w-10 text-right">14:00</span>
                    <div class="relative flex-1 mx-2">
                        <div class="absolute inset-0 flex items-center pointer-events-none">
                            <div class="w-full bg-gray-200 h-0.5"></div>
                        </div>
                        <input 
                            type="range" 
                            wire:model.defer="autoGenerateForm.time_preferences.afternoon_end" 
                            min="840" 
                            max="1080" 
                            step="15"
                            class="w-full h-2 bg-green-100 rounded-lg appearance-none cursor-pointer accent-green-600 relative z-10">
                    </div>
                    <span class="ml-2 text-xs text-gray-500 w-10">18:00</span>
                </div>
                <p class="mt-1 text-xs text-gray-500">Subjects marked as "Afternoon" will be scheduled between midday and this time</p>
            </div>
            
            <!-- Enable Time Preferences -->
            <div class="pt-2 flex items-start border-t border-gray-100">
                <div class="flex items-center h-5">
                    <input type="checkbox" 
                        wire:model.defer="autoGenerateForm.enable_subject_time_preferences" 
                        id="enable_time_prefs_advanced" 
                        class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="enable_time_prefs_advanced" class="font-medium text-gray-700">Enable Subject Time Preferences</label>
                    <p class="text-gray-500 text-xs">Schedule subjects according to their preferred time of day</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Advanced Timetable Rules Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h4 class="text-sm font-medium text-gray-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Advanced Timetable Rules
            </h4>
            <p class="text-xs text-gray-500 mt-1 ml-7">Configure additional rules to fine-tune the timetable generation algorithm</p>
        </div>

        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Avoid Consecutive Subjects -->
                <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-green-50">
                    <div class="flex items-center h-5">
                        <input type="checkbox" 
                            wire:model.defer="autoGenerateForm.avoid_consecutive_subjects" 
                            id="avoid_consecutive" 
                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <span class="font-medium text-gray-700">Avoid Consecutive Subjects</span>
                        <p class="text-gray-500 text-xs">Try to avoid scheduling the same subject in consecutive periods</p>
                    </div>
                </label>
                
                <!-- Prioritize Primary Teachers -->
                <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-green-50">
                    <div class="flex items-center h-5">
                        <input type="checkbox" 
                            wire:model.defer="autoGenerateForm.prioritize_primary_teachers" 
                            id="prioritize_primary" 
                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <span class="font-medium text-gray-700">Prioritize Primary Teachers</span>
                        <p class="text-gray-500 text-xs">Prefer primary teachers over secondary teachers for subjects</p>
                    </div>
                </label>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Balance Teacher Workload -->
                <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-green-50">
                    <div class="flex items-center h-5">
                        <input type="checkbox" 
                            wire:model.defer="autoGenerateForm.balance_teacher_workload" 
                            id="balance_workload" 
                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <span class="font-medium text-gray-700">Balance Teacher Workload</span>
                        <p class="text-gray-500 text-xs">Distribute lessons evenly among teachers</p>
                    </div>
                </label>
                
                <!-- Ensure Daily Subject Variety -->
                <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-green-50">
                    <div class="flex items-center h-5">
                        <input type="checkbox" 
                            wire:model.defer="autoGenerateForm.ensure_daily_category_variety" 
                            id="ensure_variety" 
                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <span class="font-medium text-gray-700">Ensure Daily Category Variety</span>
                        <p class="text-gray-500 text-xs">Avoid scheduling too many subjects from the same category on one day</p>
                    </div>
                </label>
            </div>
            
            <!-- Full Width Option -->
            <div class="col-span-1 md:col-span-2">
                <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-green-50 bg-green-50">
                    <div class="flex items-center h-5">
                        <input type="checkbox" 
                            wire:model.defer="autoGenerateForm.enable_balanced_distribution" 
                            id="enable_balanced" 
                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <span class="font-medium text-gray-700">Enable Balanced Distribution</span>
                        <p class="text-gray-500 text-xs">Ensure subjects meet their weekly frequency targets</p>
                    </div>
                </label>
            </div>
        </div>
    </div>
</div> 