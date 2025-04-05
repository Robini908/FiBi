<div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Export Options</h3>
        
        @if(!$selectedExamId || !$selectedClassId)
            <div class="rounded-md bg-yellow-50 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Please select an exam and class first</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>You need to select an exam and class before you can export the timetable.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- PDF Export -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 hover:border-green-200 hover:shadow-sm transition-all">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 rounded-md p-2 mr-3">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900">PDF Export</h4>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Export the exam timetable as a PDF document suitable for printing or sharing.</p>
                    
                    <div class="mb-4">
                        <div class="flex items-center h-5">
                            <input id="include_header" wire:model.live="configSchoolHeaderOnPrint" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                            <label for="include_header" class="ml-2 block text-sm text-gray-700">Include school header</label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <span class="text-sm font-medium text-gray-700">Page Orientation</span>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input id="portrait" wire:model.live="configPageOrientation" type="radio" value="portrait" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                <label for="portrait" class="ml-2 block text-sm text-gray-700">Portrait</label>
                            </div>
                            <div class="flex items-center">
                                <input id="landscape" wire:model.live="configPageOrientation" type="radio" value="landscape" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                <label for="landscape" class="ml-2 block text-sm text-gray-700">Landscape</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" wire:click="exportToPdf" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export as PDF
                    </button>
                </div>
                
                <!-- Excel Export -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 hover:border-green-200 hover:shadow-sm transition-all">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 rounded-md p-2 mr-3">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900">Excel Export</h4>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Export the exam timetable as an Excel spreadsheet for further customization or analysis.</p>
                    
                    <div class="mb-4">
                        <div class="flex items-center h-5">
                            <input id="include_instructions" wire:model.live="configShowInstructions" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                            <label for="include_instructions" class="ml-2 block text-sm text-gray-700">Include instructions</label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center h-5">
                            <input id="include_invigilators" wire:model.live="configShowInvigilators" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                            <label for="include_invigilators" class="ml-2 block text-sm text-gray-700">Include invigilators</label>
                        </div>
                    </div>
                    
                    <button type="button" wire:click="exportToExcel" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export as Excel
                    </button>
                </div>
                
                <!-- Print View -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 hover:border-green-200 hover:shadow-sm transition-all">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 rounded-md p-2 mr-3">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900">Print View</h4>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Open a print-friendly view of the exam timetable optimized for printing.</p>
                    
                    <div class="mb-4">
                        <div class="flex items-center h-5">
                            <input id="printable_format" wire:model.live="configPrintableFormat" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                            <label for="printable_format" class="ml-2 block text-sm text-gray-700">Use printable format</label>
                        </div>
                    </div>
                    
                    <button type="button" wire:click="printTimetable" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Open Print View
                    </button>
                </div>
            </div>
        @endif
    </div>
</div> 