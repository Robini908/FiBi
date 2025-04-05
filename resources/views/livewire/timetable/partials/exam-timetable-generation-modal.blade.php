<div class="fixed inset-0 bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
<div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            <div class="absolute right-0 top-0 pr-4 pt-4">
                <button type="button" @click="showGeneration = false" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Auto-Generate Exam Timetable</h3>
                    <p class="text-sm text-gray-500">Configure settings for automatically generating the exam timetable.</p>
                </div>
            </div>
            
            <div class="mt-6 space-y-6">
                @if(!$selectedExamId || !$selectedClassId)
                    <div class="rounded-md bg-yellow-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Missing Selection</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Please select an exam and class before generating a timetable.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" id="start_date" wire:model="generationSettings.start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">The first day of exams</p>
                        </div>
                        
                        <div>
                            <label for="papers_per_day" class="block text-sm font-medium text-gray-700">Papers Per Day</label>
                            <input type="number" id="papers_per_day" wire:model="generationSettings.papers_per_day" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Maximum number of exams scheduled per day</p>
                        </div>
                        
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                            <input type="time" id="start_time" wire:model="generationSettings.start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Time of the first exam each day</p>
                        </div>
                        
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                            <input type="number" id="duration_minutes" wire:model="generationSettings.duration_minutes" min="30" max="240" step="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Default duration for each exam</p>
                        </div>
                        
                        <div>
                            <label for="break_minutes" class="block text-sm font-medium text-gray-700">Break Time (minutes)</label>
                            <input type="number" id="break_minutes" wire:model="generationSettings.break_minutes" min="0" max="60" step="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Break time between exams</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="exclude_weekends" wire:model="generationSettings.exclude_weekends" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="exclude_weekends" class="font-medium text-gray-700">Exclude Weekends</label>
                                    <p class="text-gray-500">Don't schedule exams on weekends</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="randomize_subjects" wire:model="generationSettings.randomize_subjects" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="randomize_subjects" class="font-medium text-gray-700">Randomize Subjects</label>
                                    <p class="text-gray-500">Randomly order subjects instead of alphabetically</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="auto_assign_invigilators" wire:model="generationSettings.auto_assign_invigilators" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="auto_assign_invigilators" class="font-medium text-gray-700">Auto-assign Invigilators</label>
                                    <p class="text-gray-500">Automatically assign subject teachers as invigilators</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="mt-6 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                <button type="button" wire:click="generateTimetable" @click="showGeneration = false" class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm" @if(!$selectedExamId || !$selectedClassId) disabled @endif>
                    Generate Timetable
                </button>
                <button type="button" wire:click="cancelGeneration" @click="showGeneration = false" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 