<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle max-w-5xl w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Advanced Bulk Teacher Assignment
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Create precise teacher-subject assignments across multiple classes and sections.
                        </p>
                        
                        @if ($showBulkResults)
                            <!-- Results Panel -->
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h4 class="text-base font-medium text-gray-900 mb-2">Assignment Results</h4>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                        <p class="text-xs font-medium text-gray-500 uppercase">Total Processed</p>
                                        <p class="mt-1 text-lg font-bold text-gray-900">{{ $bulkAssignmentResults['total'] }}</p>
                                    </div>
                                    
                                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                        <p class="text-xs font-medium text-green-500 uppercase">Created</p>
                                        <p class="mt-1 text-lg font-bold text-green-600">{{ $bulkAssignmentResults['created'] }}</p>
                                    </div>
                                    
                                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                        <p class="text-xs font-medium text-yellow-500 uppercase">Skipped</p>
                                        <p class="mt-1 text-lg font-bold text-yellow-600">{{ $bulkAssignmentResults['skipped'] }}</p>
                                    </div>
                                    
                                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                        <p class="text-xs font-medium text-red-500 uppercase">Errors</p>
                                        <p class="mt-1 text-lg font-bold text-red-600">{{ $bulkAssignmentResults['errors'] }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex justify-end">
                                    <button 
                                        wire:click="closeBulkModal" 
                                        type="button" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        Close
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Important Guidelines Notice -->
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <h4 class="text-base font-medium text-blue-800 flex items-center">
                                    <svg class="h-5 w-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Important Assignment Guidelines
                                </h4>
                                <ul class="mt-2 text-sm text-blue-700 space-y-1 list-disc list-inside">
                                    <li>Create specific teacher-subject pairings with the new interface</li>
                                    <li>Each subject should have <strong>only one primary teacher</strong> per class/section per term</li>
                                    <li>Select which classes and sections (optional) to apply each teacher-subject pairing to</li>
                                    <li>To replace existing primary teachers, check the "Override Existing" option</li>
                                    <li>For non-primary teacher assignments (assistants, etc.), uncheck "Primary Assignment"</li>
                                </ul>
                            </div>
                            
                            <!-- Advanced Information Toggle -->
                            <div x-data="{ showAdvanced: false }" class="mt-2">
                                <button @click="showAdvanced = !showAdvanced" type="button" class="text-blue-600 hover:text-blue-800 text-sm flex items-center focus:outline-none">
                                    <svg :class="{'rotate-90': showAdvanced}" class="h-4 w-4 mr-1 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span x-text="showAdvanced ? 'Hide advanced information' : 'Show advanced information'"></span>
                                </button>
                                
                                <div x-show="showAdvanced" x-cloak class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200 text-sm text-gray-700">
                                    <p class="font-medium mb-1">The system performs these validation checks:</p>
                                    <ol class="list-decimal list-inside space-y-1 ml-2">
                                        <li>Existing primary teacher assignments (prevents duplicates)</li>
                                        <li>Subject count per class/section vs. curriculum requirements</li>
                                        <li>Teacher workload across all assignments (warns if >20 assignments)</li>
                                        <li>Potential scheduling conflicts for teachers assigned to multiple classes</li>
                                        <li>Section-class relationship validation</li>
                                    </ol>
                                    <p class="mt-2">These checks help maintain data integrity and optimize the timetable generation process.</p>
                                </div>
                            </div>
                            
                            <!-- Form -->
                            <form id="bulk-assignment-form" wire:submit.prevent="saveBulkAssignments" class="mt-4">
                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Academic Settings -->
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <h4 class="text-base font-medium text-gray-900 mb-3">Academic Settings</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Academic Year -->
                                            <div>
                                                <label for="academicYearId" class="block text-sm font-medium text-gray-700">Academic Year*</label>
                                                <select id="academicYearId" wire:model="bulkForm.academic_year_id" class="mt-1 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    <option value="">Select academic year</option>
                                                    @foreach($academicYears as $academicYear)
                                                        @if(is_array($academicYear))
                                                            <option value="{{ $academicYear['id'] }}" {{ isset($academicYear['is_current']) && $academicYear['is_current'] ? 'class=font-bold' : '' }}>
                                                                {{ $academicYear['name'] }} {{ isset($academicYear['is_current']) && $academicYear['is_current'] ? '(Current)' : '' }}
                                                            </option>
                                                        @elseif(is_object($academicYear))
                                                            <option value="{{ $academicYear->id }}">{{ $academicYear->year }}</option>
                                                        @else
                                                            <option value="{{ $academicYear }}">{{ $academicYear }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('bulkForm.academic_year_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>

                                            <!-- Academic Term -->
                                            <div>
                                                <label for="academicTerm" class="block text-sm font-medium text-gray-700">Academic Term*</label>
                                                <select id="academicTerm" wire:model="bulkForm.academic_term" class="mt-1 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                    <option value="">Select term</option>
                                                    @foreach($academicTerms as $term)
                                                        <option value="{{ $term }}">{{ $term }}</option>
                                                    @endforeach
                                                </select>
                                                @error('bulkForm.academic_term') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Teacher-Subject Mapping -->
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                                        x-data="{
                                            mappings: @entangle('teacherSubjectMappings'),
                                            addMapping() {
                                                this.$wire.addTeacherSubjectMapping();
                                            },
                                            removeMapping(id) {
                                                this.$wire.removeTeacherSubjectMapping(id);
                                            }
                                        }"
                                    >
                                        <h4 class="text-base font-medium text-gray-900 mb-3">Teacher-Subject Mapping*</h4>
                                        <p class="text-sm text-gray-500 mb-4">Select which teachers will teach which subjects</p>
                                        
                                        <div class="space-y-3">
                                            @foreach($teacherSubjectMappings as $mappingId => $mapping)
                                                <div class="flex flex-col sm:flex-row gap-3 pb-4 border-b border-gray-200 last:border-b-0 last:pb-0">
                                                    <div class="w-full sm:w-1/2">
                                                        <label for="teacher-{{ $mappingId }}" class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                                                        <select 
                                                            id="teacher-{{ $mappingId }}" 
                                                            wire:model.live="teacherSubjectMappings.{{ $mappingId }}.teacherId" 
                                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                                        >
                                                            <option value="">Select a teacher</option>
                                                            @foreach($teachers as $teacher)
                                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error("teacherSubjectMappings.{$mappingId}.teacherId") 
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="w-full sm:w-1/2">
                                                        <label for="subject-{{ $mappingId }}" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                                        <div class="flex">
                                                            <select 
                                                                id="subject-{{ $mappingId }}" 
                                                                wire:model.live="teacherSubjectMappings.{{ $mappingId }}.subjectId" 
                                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                                            >
                                                                <option value="">Select a subject</option>
                                                                @foreach($subjects as $subject)
                                                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            
                                                            <button 
                                                                type="button" 
                                                                class="ml-2 mt-1 inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                                wire:click="removeTeacherSubjectMapping({{ $mappingId }})"
                                                                {{ count($teacherSubjectMappings) <= 1 ? 'disabled' : '' }}
                                                                class="{{ count($teacherSubjectMappings) <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            >
                                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        @error("teacherSubjectMappings.{$mappingId}.subjectId") 
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Add mapping button -->
                                        <div class="flex justify-center mt-3">
                                            <button 
                                                type="button" 
                                                wire:click="addTeacherSubjectMapping"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            >
                                                <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Add Another Teacher-Subject Pair
                                            </button>
                                        </div>
                                        
                                        @error('teacherSubjectMappings') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Class Selection -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-base font-medium text-gray-900 mb-3">Select Classes*</h4>
                                <p class="text-sm text-gray-500 mb-2">Select where to apply these teacher-subject assignments</p>
                                
                                <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md bg-white p-2">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach($classes as $class)
                                            <label class="inline-flex items-center">
                                                <input 
                                                    type="checkbox" 
                                                    value="{{ $class->id }}" 
                                                    wire:model.live="bulkForm.class_ids" 
                                                    wire:key="class-{{ $class->id }}"
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                >
                                                <span class="ml-2 text-sm text-gray-700">{{ $class->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                @error('bulkForm.class_ids') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                
                                <!-- Debug info for class selection -->
                                <div class="mt-2 text-xs text-gray-500">
                                    Selected classes: {{ implode(', ', $bulkForm['class_ids']) }}
                                </div>
                            </div>
                            
                            <!-- Section Selection - Only shown if classes are selected -->
                            @if(!empty($bulkForm['class_ids']))
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <h4 class="text-base font-medium text-gray-900 mb-3">Select Sections*</h4>
                                    <p class="text-sm text-gray-500 mb-2">Select the sections for each class</p>
                                    
                                    @if($processingSections)
                                        <div class="flex justify-center items-center py-4">
                                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="ml-2 text-sm text-gray-700">Loading sections...</span>
                                        </div>
                                    @else
                                        @if($availableSections->count() > 0)
                                            <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md bg-white p-2">
                                                <div class="space-y-3">
                                                    @foreach($availableSections as $classId => $classSections)
                                                        @if($classes->firstWhere('id', $classId))
                                                            <div class="border-b border-gray-200 pb-2 last:border-b-0 last:pb-0">
                                                                <h5 class="font-medium text-sm text-gray-900 mb-1">{{ $classes->firstWhere('id', $classId)->name }} Sections:</h5>
                                                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 pl-2">
                                                                    @foreach($classSections as $section)
                                                                        <label class="inline-flex items-center">
                                                                            <input 
                                                                                type="checkbox" 
                                                                                value="{{ $section->id }}" 
                                                                                wire:model="bulkForm.section_ids" 
                                                                                wire:key="section-{{ $section->id }}"
                                                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                                            >
                                                                            <span class="ml-2 text-sm text-gray-700">{{ $section->name }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            @error('bulkForm.section_ids') 
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                            @enderror
                                        @else
                                            <div class="text-sm text-red-600 bg-white p-3 border border-red-200 rounded-md flex items-center">
                                                <svg class="h-5 w-5 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                No sections available for the selected classes. Please select classes that have sections.
                                            </div>
                                        @endif
                                        
                                        <!-- Debug info for available sections -->
                                        <div class="mt-2 text-xs text-gray-500 flex items-center justify-between">
                                            <span>
                                            Available sections count: {{ $availableSections->count() }} | 
                                            Selected sections: {{ count($bulkForm['section_ids'] ?? []) }}
                                            </span>
                                            <button 
                                                type="button" 
                                                wire:click="debugSections" 
                                                class="text-xs text-blue-600 hover:text-blue-800 underline"
                                            >
                                                Debug Sections
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Additional Options -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-base font-medium text-gray-900 mb-3">Assignment Options</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <input 
                                            id="isPrimary" 
                                            wire:model="bulkForm.is_primary" 
                                            type="checkbox" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                        <label for="isPrimary" class="ml-2 block text-sm text-gray-700">
                                            Primary Assignment
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input 
                                            id="isActive" 
                                            wire:model="bulkForm.is_active" 
                                            type="checkbox" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                        <label for="isActive" class="ml-2 block text-sm text-gray-700">
                                            Active Assignment
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input 
                                            id="overrideExisting" 
                                            wire:model="bulkForm.override_existing" 
                                            type="checkbox" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                        <label for="overrideExisting" class="ml-2 block text-sm text-gray-700">
                                            Override Existing Assignments
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Notes -->
                                <div class="mt-4">
                                    <label for="bulkNotes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                    <textarea 
                                        id="bulkNotes" 
                                        wire:model="bulkForm.notes" 
                                        rows="2" 
                                        class="mt-1 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                        placeholder="Add any additional information about these assignments"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
            
            @if(!$showBulkResults)
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        form="bulk-assignment-form"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        <span wire:loading.class="hidden" wire:target="saveBulkAssignments">
                            Create Assignments
                        </span>
                        <span wire:loading wire:target="saveBulkAssignments">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                    
                    <!-- Cancel Button -->
                    <button 
                        wire:click="closeBulkModal" 
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            @endif
        </div>
    </div>
</div> 