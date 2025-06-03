<div class="fixed inset-0 bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
<div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            <div class="absolute right-0 top-0 pr-4 pt-4">
                <button type="button" @click="showSettings = false" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Exam Timetable Settings</h3>
                    <p class="text-sm text-gray-500">Customize how exam timetables are displayed and generated.</p>
                </div>
            </div>
            
            <div class="mt-6 space-y-6">
                <!-- Display Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Display Settings</h4>
                    <div class="mt-3 space-y-3">
                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input id="show_invigilators" wire:model.live="configShowInvigilators" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="show_invigilators" class="font-medium text-gray-700">Show Invigilators</label>
                                <p class="text-gray-500">Display assigned invigilators in the timetable.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input id="show_instructions" wire:model.live="configShowInstructions" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="show_instructions" class="font-medium text-gray-700">Show Instructions</label>
                                <p class="text-gray-500">Display exam instructions in the timetable.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input id="printable_format" wire:model.live="configPrintableFormat" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="printable_format" class="font-medium text-gray-700">Printable Format</label>
                                <p class="text-gray-500">Optimize display for printing.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Generation Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Generation Settings</h4>
                    <div class="mt-3 space-y-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Default Start Time</label>
                            <input type="time" id="start_time" wire:model.live="configStartTime" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="time_per_paper" class="block text-sm font-medium text-gray-700">Default Time Per Paper (minutes)</label>
                            <input type="number" id="time_per_paper" wire:model.live="configTimePerPaper" min="30" max="240" step="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="break_time" class="block text-sm font-medium text-gray-700">Break Time Between Papers (minutes)</label>
                            <input type="number" id="break_time" wire:model.live="configBreakTime" min="0" max="60" step="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="papers_per_day" class="block text-sm font-medium text-gray-700">Papers Per Day</label>
                            <input type="number" id="papers_per_day" wire:model.live="configPapersPerDay" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="invigilators_per_room" class="block text-sm font-medium text-gray-700">Invigilators Per Room</label>
                            <input type="number" id="invigilators_per_room" wire:model.live="configInvigilatorsPerRoom" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input id="exclude_weekends" wire:model.live="configExcludeWeekends" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="exclude_weekends" class="font-medium text-gray-700">Exclude Weekends</label>
                                <p class="text-gray-500">Don't schedule exams on weekends.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input id="auto_assign_invigilators" wire:model.live="configAutoAssignInvigilators" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="auto_assign_invigilators" class="font-medium text-gray-700">Auto-assign Invigilators</label>
                                <p class="text-gray-500">Automatically assign subject teachers as invigilators.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                <button type="button" wire:click="saveSettings" @click="showSettings = false" class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm">
                    Save Settings
                </button>
                <button type="button" wire:click="resetSettings" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm">
                    Reset to Defaults
                </button>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/exam-timetable-settings-modal.blade.php ENDPATH**/ ?>