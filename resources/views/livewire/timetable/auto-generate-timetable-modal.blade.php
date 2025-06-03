<div class="p-2">
    <div class="mb-4">
        <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    Auto-Generate Timetable
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        Intelligently generate a balanced timetable based on subjects, optimal teaching times, and teacher assignments.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-5">
        <nav class="-mb-px flex space-x-5" aria-label="Tabs">
            <button
                type="button"
                wire:click="setActiveTab('basic')"
                class="px-3 py-2.5 text-sm font-medium border-b-2 {{ $activeTab === 'basic' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Basic Settings
            </button>
            <button
                type="button"
                wire:click="setActiveTab('subject')"
                class="px-3 py-2.5 text-sm font-medium border-b-2 {{ $activeTab === 'subject' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Subject Preferences
            </button>
            <button
                type="button"
                wire:click="setActiveTab('advanced')"
                class="px-3 py-2.5 text-sm font-medium border-b-2 {{ $activeTab === 'advanced' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
            >
                Advanced Options
            </button>
        </nav>
    </div>

    <!-- Form content -->
    <div class="max-h-[60vh] overflow-y-auto px-1 py-2">
        <form wire:submit.prevent="generateTimetable">
            <!-- Basic Settings Tab -->
            <div x-data="{ 
                existingEntriesOption: @entangle('autoGenerateForm.respect_existing_entries').defer ? 'respect' : 'clear',
                init() {
                    // Set the radio button status based on current values
                    if (this.existingEntriesOption === 'clear') {
                        $wire.set('autoGenerateForm.clear_existing', true);
                        $wire.set('autoGenerateForm.respect_existing_entries', false);
                    } else {
                        $wire.set('autoGenerateForm.clear_existing', false);
                        $wire.set('autoGenerateForm.respect_existing_entries', true);
                    }
                }
            }" class="{{ $activeTab === 'basic' ? 'block' : 'hidden' }}">
                <!-- Days Selection -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Days to Include</label>
                    <div class="mt-2 grid grid-cols-3 sm:grid-cols-7 gap-2">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <label class="inline-flex items-center px-3 py-2 border border-gray-100 rounded-md bg-gray-50 hover:bg-green-50 transition-colors duration-150 cursor-pointer">
                                <input type="checkbox" 
                                       wire:model.defer="autoGenerateForm.days" 
                                       value="{{ $day }}" 
                                       class="rounded text-green-600 focus:ring-green-500 h-4 w-4 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700 capitalize">{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('autoGenerateForm.days') 
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p> 
                    @enderror
                </div>
                
                <!-- Max Subjects Per Day -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-4">
                    <label for="max_daily_subjects" class="block text-sm font-medium text-gray-700 mb-2">Maximum Subjects Per Day</label>
                    <div class="relative">
                        <input type="number" 
                            min="1" 
                            max="10" 
                            wire:model.defer="autoGenerateForm.max_daily_subjects" 
                            id="max_daily_subjects" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">subjects</span>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Sets the maximum number of subjects to schedule on any day</p>
                    @error('autoGenerateForm.max_daily_subjects') 
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                    @enderror
                </div>
                
                <!-- Existing Entries Options -->
                <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        How to handle existing entries:
                    </h4>
                    
                    <div class="space-y-4 mt-4">
                        <!-- Clear Existing Option -->
                        <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-white" :class="{'bg-white ring-2 ring-green-500': existingEntriesOption === 'clear', 'bg-gray-50': existingEntriesOption !== 'clear'}">
                            <div class="flex items-center h-5">
                                <input type="radio" 
                                    x-model="existingEntriesOption"
                                    value="clear"
                                    @change="$wire.set('autoGenerateForm.clear_existing', true); $wire.set('autoGenerateForm.respect_existing_entries', false);"
                                    class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-medium text-gray-700">Clear Existing Entries</span>
                                <p class="text-gray-500">Remove all existing entries before generating new ones</p>
                            </div>
                        </label>
                        
                        <!-- Respect Existing Option -->
                        <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-white" :class="{'bg-white ring-2 ring-green-500': existingEntriesOption === 'respect', 'bg-gray-50': existingEntriesOption !== 'respect'}">
                            <div class="flex items-center h-5">
                                <input type="radio" 
                                    x-model="existingEntriesOption"
                                    value="respect"
                                    @change="$wire.set('autoGenerateForm.clear_existing', false); $wire.set('autoGenerateForm.respect_existing_entries', true);"
                                    class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-medium text-gray-700">Respect Existing Entries</span>
                                <p class="text-gray-500">Keep existing entries and only fill empty slots</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Subject Preferences Tab -->
            <div class="{{ $activeTab === 'subject' ? 'block' : 'hidden' }}">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Subject Scheduling Preferences</h3>
                    <p class="text-xs text-gray-500 mb-4">Configure how many times each subject should appear in the weekly timetable and other preferences.</p>
                    
                    <div class="space-y-4">
                        @if(count($autoGenerateForm['subject_preferences']) > 0)
                            @foreach($autoGenerateForm['subject_preferences'] as $subjectId => $preferences)
                                @php
                                    $subjectName = $preferences['subject_name'] ?? 'Subject';
                                    $categoryName = $preferences['category'] ?? 'Uncategorized';
                                    $colorClass = $colorMapping[$categoryName] ?? $colorMapping['Optional'];
                                @endphp
                                <div class="border border-gray-200 rounded-md p-3 bg-gray-50 hover:bg-white transition-colors duration-150">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <span class="px-2 py-1 rounded-md text-xs font-medium {{ $colorClass }} mr-2">
                                                {{ $categoryName }}
                                            </span>
                                            <span class="font-medium text-sm text-gray-800">{{ $subjectName }}</span>
                                        </div>
                                        
                                        @if(isset($preferences['teacher_name']) && $preferences['teacher_name'])
                                            <span class="text-xs text-gray-500">
                                                Teacher: {{ $preferences['teacher_name'] }}
                                                @if($preferences['is_primary'])
                                                    <span class="ml-1 px-1.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">Primary</span>
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <!-- Weekly Frequency -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Weekly Frequency</label>
                                            <input 
                                                type="number" 
                                                wire:model.defer="autoGenerateForm.subject_preferences.{{ $subjectId }}.weekly_frequency" 
                                                min="0" 
                                                max="10" 
                                                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        
                                        <!-- Preferred Time of Day -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Preferred Time</label>
                                            <select 
                                                wire:model.defer="autoGenerateForm.subject_preferences.{{ $subjectId }}.preferred_time_of_day" 
                                                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                <option value="any">Any Time</option>
                                                <option value="morning">Morning</option>
                                                <option value="midday">Midday</option>
                                                <option value="afternoon">Afternoon</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Consecutive Periods -->
                                        <div>
                                            <label class="flex items-center">
                                                <input 
                                                    type="checkbox" 
                                                    wire:model.defer="autoGenerateForm.subject_preferences.{{ $subjectId }}.consecutive_periods" 
                                                    class="rounded text-green-600 focus:ring-green-500 h-4 w-4 border-gray-300">
                                                <span class="ml-2 text-xs text-gray-700">Allow Consecutive Periods</span>
                                            </label>
                                        </div>
                                        
                                        <!-- Spread Evenly -->
<div>
                                            <label class="flex items-center">
                                                <input 
                                                    type="checkbox" 
                                                    wire:model.defer="autoGenerateForm.subject_preferences.{{ $subjectId }}.spread_evenly" 
                                                    class="rounded text-green-600 focus:ring-green-500 h-4 w-4 border-gray-300">
                                                <span class="ml-2 text-xs text-gray-700">Spread Evenly Across Week</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-6 bg-gray-50 rounded-lg border border-gray-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No subjects available for this class/section.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Category Distribution -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Minimum Subjects per Category per Day</h3>
                    <p class="text-xs text-gray-500 mb-4">Set how many subjects from each category should appear daily at minimum.</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(['Core', 'Language', 'Humanities', 'Science', 'Arts', 'Physical', 'Technology', 'Optional'] as $category)
                            <div class="space-y-1">
                                <label class="block text-xs font-medium text-gray-700">{{ $category }}</label>
                                <input 
                                    type="number" 
                                    wire:model.defer="autoGenerateForm.min_category_per_day.{{ $category }}" 
                                    min="0" 
                                    max="5" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Advanced Options Tab -->
            <div class="{{ $activeTab === 'advanced' ? 'block' : 'hidden' }}">
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
                </div>
                
                <!-- Enable Subject Time Preferences -->
                <div class="p-5">
                    <label class="flex items-start p-3 border border-gray-200 rounded-md cursor-pointer transition-all duration-200 hover:bg-green-50">
                        <div class="flex items-center h-5">
                            <input type="checkbox" 
                                wire:model.defer="autoGenerateForm.enable_subject_time_preferences" 
                                id="enable_time_prefs" 
                                class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-700">Respect Subject Time Preferences</span>
                            <p class="text-gray-500 text-xs">Try to schedule subjects according to their preferred time of day</p>
                        </div>
                    </label>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Action Buttons -->
    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
        <button 
            type="button" 
            wire:click="generateTimetable" 
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
            Generate Timetable
        </button>
        <button 
            type="button" 
            wire:click="$dispatch('closeModal')" 
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Cancel
        </button>
    </div>
</div>
