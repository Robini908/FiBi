<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-white border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Reinstate Student
        </h3>
    </div>
    
    <div class="p-6">
        <!-- Student Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <!-- Student avatar or initials -->
                <div class="flex-shrink-0">
                    @if ($student && $student->photo)
                        <img src="{{ asset($student->photo) }}" alt="{{ $student->first_name }}" class="h-12 w-12 rounded-full object-cover">
                    @else
                        <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-green-700 font-medium text-sm">
                                {{ $student ? substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1) : 'N/A' }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <!-- Student details -->
                <div class="ml-4 flex-1">
                    <h4 class="text-base font-medium text-gray-900">
                        {{ $student ? $student->first_name . ' ' . $student->last_name : 'Student not found' }}
                    </h4>
                    @if($student)
                        <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <div class="text-gray-600">
                                <span class="font-medium text-gray-700">Admission No:</span> {{ $student->adm_no }}
                            </div>
                            <div class="text-gray-600">
                                <span class="font-medium text-gray-700">Class:</span> 
                                {{ $student->my_class->name ?? 'N/A' }} - {{ $student->section->name ?? 'N/A' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Confirmation Message -->
        <div class="bg-green-50 rounded-lg p-4 mb-6 border border-green-100">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        You are about to reinstate this student, which will:
                    </p>
                    <ul class="mt-2 text-sm text-green-700 list-disc pl-5 space-y-1">
                        <li>Remove all suspension records</li>
                        <li>Allow the student to resume normal school activities</li>
                        <li>Reset the student's status to active</li>
                    </ul>
                    <p class="mt-2 text-sm font-medium text-green-700">
                        Are you sure you want to proceed?
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <button wire:click="$set('isReinstating', false)" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <svg class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </button>
            
            <button wire:click="confirmReinstatement" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Yes, Reinstate Student
                <span wire:loading wire:target="confirmReinstatement" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div> 