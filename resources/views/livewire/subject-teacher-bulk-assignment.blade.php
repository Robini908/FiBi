<div>
    {{-- Success is as dangerous as failure. --}}

    <!-- Modal Trigger Button (for testing) -->
    @if(false)
    <button 
        wire:click="openBulkModal"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
    >
        Open Bulk Assignment Modal
    </button>
    @endif

    <!-- Bulk Assignment Modal -->
    <div x-data="{ 
        showResults: @entangle('showResults'),
        activeStep: 1,
        totalSteps: 3,
        nextStep() {
            if (this.activeStep < this.totalSteps) this.activeStep++;
        },
        prevStep() {
            if (this.activeStep > 1) this.activeStep--;
        },
        stepComplete(step) {
            if (step === 1) {
                return $wire.bulkForm.academic_year_id && $wire.bulkForm.academic_term;
            } else if (step === 2) {
                return $wire.bulkForm.class_id && $wire.bulkForm.section_ids.length > 0;
            }
            return false;
        }
    }">
                <!-- Modal Header -->
        <div class="flex items-center justify-between rounded-t-lg border-b border-gray-200 bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4">
            <h3 class="text-lg font-medium text-white">
                        Bulk Teacher-Subject Assignment
                    </h3>
                    <button 
                        type="button" 
                class="text-white/70 hover:text-white transition-colors duration-150"
                    wire:click="$dispatch('closeModal')"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Results Section (shown after submit) -->
                <div x-show="showResults" class="p-6 bg-white">
                    <div class="rounded-md bg-green-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">
                                    Bulk Assignment Completed
                                </h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>{{ $results['created'] }} assignments created</p>
                                    <p>{{ $results['skipped'] }} assignments skipped</p>
                                    <p>{{ $results['errors'] }} errors</p>
                                    <p>{{ $results['total'] }} total operations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="flex justify-end space-x-3">
                        <button 
                            type="button" 
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-150"
                        wire:click="$dispatch('closeModal')"
                        >
                            Close
                        </button>
                        <button 
                            type="button" 
                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors duration-150"
                            wire:click="$set('showResults', false)"
                        >
                            Make Another Assignment
                        </button>
                    </div>
                </div>

                <!-- Modal Body - Form -->
        <div x-show="!showResults" class="bg-gray-50">
            <!-- Progress Steps -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="w-full flex items-center">
                        <div class="relative flex items-center justify-center w-10 h-10 rounded-full 
                            peer-checked:bg-green-500 bg-primary-600 text-white">
                            1
                            <div class="absolute right-0 w-5 h-0.5" :class="activeStep > 1 ? 'bg-primary-600' : 'bg-gray-300'"></div>
                        </div>
                        <div class="relative flex-1 mx-2">
                            <div class="h-0.5 w-full" :class="activeStep > 1 ? 'bg-primary-600' : 'bg-gray-300'"></div>
                        </div>
                        <div class="relative flex items-center justify-center w-10 h-10 rounded-full 
                            transition-colors duration-200" 
                            :class="activeStep >= 2 ? 'bg-primary-600 text-white' : 'bg-gray-300 text-gray-700'">
                            2
                            <div class="absolute right-0 w-5 h-0.5" :class="activeStep > 2 ? 'bg-primary-600' : 'bg-gray-300'"></div>
                        </div>
                        <div class="relative flex-1 mx-2">
                            <div class="h-0.5 w-full" :class="activeStep > 2 ? 'bg-primary-600' : 'bg-gray-300'"></div>
                        </div>
                        <div class="relative flex items-center justify-center w-10 h-10 rounded-full 
                            transition-colors duration-200"
                            :class="activeStep >= 3 ? 'bg-primary-600 text-white' : 'bg-gray-300 text-gray-700'">
                            3
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between mt-2 text-xs text-gray-600">
                    <div class="w-1/3 text-center" :class="activeStep == 1 ? 'text-primary-700 font-medium' : ''">Academic Year & Term</div>
                    <div class="w-1/3 text-center" :class="activeStep == 2 ? 'text-primary-700 font-medium' : ''">Class & Sections</div>
                    <div class="w-1/3 text-center" :class="activeStep == 3 ? 'text-primary-700 font-medium' : ''">Teachers & Subjects</div>
                </div>
            </div>
            
                    <form wire:submit.prevent="saveBulkAssignments">
                <div class="p-6 max-h-[calc(100vh-220px)] overflow-y-auto">
                    <!-- Step 1: Academic Year and Term Selection -->
                    <div x-show="activeStep === 1">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Select Academic Period</h4>
                            
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">
                                        Academic Year <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="academic_year" 
                                    wire:model="bulkForm.academic_year_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                >
                                    <option value="">Select Academic Year</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year['id'] }}">{{ $year['name'] }} {{ $year['is_current'] ? '(Current)' : '' }}</option>
                                    @endforeach
                                </select>
                                @error('bulkForm.academic_year_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="academic_term" class="block text-sm font-medium text-gray-700 mb-1">
                                        Academic Term <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="academic_term" 
                                    wire:model="bulkForm.academic_term"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                >
                                    <option value="">Select Term</option>
                                    @foreach($academicTerms as $term)
                                        <option value="{{ $term }}">{{ $term }}</option>
                                    @endforeach
                                </select>
                                @error('bulkForm.academic_term')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Class and Section Selection -->
                    <div x-show="activeStep === 2">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Select Class and Sections</h4>
                            
                            <!-- Class Selection -->
                        <div class="mb-6">
                                <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Select Class <span class="text-red-500">*</span>
                            </label>
                                <select 
                                    id="class_id" 
                                    wire:model.live="bulkForm.class_id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                >
                                    <option value="">Select a Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('bulkForm.class_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                        </div>

                            <!-- Sections Selection -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Select Sections <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex space-x-2">
                                        <button 
                                            type="button"
                                            wire:click="selectAllSections"
                                            class="text-xs text-primary-600 hover:text-primary-800 transition-colors duration-150"
                                        >
                                            Select All
                                        </button>
                                        <button 
                                            type="button"
                                            wire:click="clearSectionSelection"
                                            class="text-xs text-primary-600 hover:text-primary-800 transition-colors duration-150"
                                        >
                                            Clear All
                                        </button>
                                    </div>
                                </div>

                                <div class="border border-gray-200 rounded-md p-4 bg-white">
                                    @if($processingSections)
                                        <div class="py-4 text-center">
                                            <svg class="animate-spin h-5 w-5 text-primary-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-500 mt-2 block">Loading sections...</span>
                                        </div>
                                    @elseif($bulkForm['class_id'] && $availableSections->isEmpty())
                                        <div class="py-4 text-center text-gray-500">
                                            No sections available for this class.
                                        </div>
                                    @elseif(!$bulkForm['class_id'])
                                        <div class="py-4 text-center text-gray-500">
                                            Please select a class to view available sections.
                                        </div>
                                    @else
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            @foreach($availableSections as $section)
                                                <label class="inline-flex items-center p-2 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                                                                <input 
                                                                    type="checkbox" 
                                                                    value="{{ $section->id }}" 
                                                                    wire:model="bulkForm.section_ids"
                                                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                                                >
                                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ $section->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                    @endif
                                </div>
                                @error('bulkForm.section_ids')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        </div>

                    <!-- Step 3: Teacher-Subject Mappings -->
                    <div x-show="activeStep === 3">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-medium text-gray-900">Teacher-Subject Mappings</h4>
                                <button 
                                    type="button"
                                    wire:click="addTeacherSubjectMapping"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150"
                                >
                                    <svg class="h-4 w-4 mr-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Add Mapping
                                </button>
                            </div>

                            <div class="space-y-4">
                                @foreach($teacherSubjectMappings as $index => $mapping)
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 border border-gray-200 rounded-md bg-gray-50">
                                        <div class="flex-1">
                                            <label for="teacher_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                Teacher <span class="text-red-500">*</span>
                                            </label>
                                            <select 
                                                id="teacher_{{ $index }}"
                                                wire:model="teacherSubjectMappings.{{ $index }}.teacherId"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                            >
                                                <option value="">Select Teacher</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                            @error("teacherSubjectMappings.{$index}.teacherId")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="flex-1">
                                            <label for="subject_{{ $index }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                Subject <span class="text-red-500">*</span>
                                            </label>
                                            <select 
                                                id="subject_{{ $index }}"
                                                wire:model="teacherSubjectMappings.{{ $index }}.subjectId"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                            >
                                                <option value="">Select Subject</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                                @endforeach
                                            </select>
                                            @error("teacherSubjectMappings.{$index}.subjectId")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="flex items-end justify-center sm:justify-end">
                                            @if(count($teacherSubjectMappings) > 1)
                                                <button 
                                                    type="button"
                                                    wire:click="removeTeacherSubjectMapping({{ $index }})"
                                                    class="inline-flex items-center p-1.5 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors duration-150"
                                                >
                                                    <svg class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                        </div>

                        <!-- Assignment Options -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h5 class="text-sm font-medium text-gray-900 mb-3">Assignment Options</h5>
                            
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="is_primary"
                                        wire:model="bulkForm.is_primary"
                                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                    >
                                        <span class="ml-2 text-sm text-gray-700">
                                        Set as primary teacher
                                        </span>
                                    </label>
                                
                                    <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="is_active"
                                        wire:model="bulkForm.is_active"
                                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                    >
                                        <span class="ml-2 text-sm text-gray-700">
                                        Active assignment
                                        </span>
                                    </label>
                                
                                    <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="override_existing"
                                        wire:model="bulkForm.override_existing"
                                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                    >
                                        <span class="ml-2 text-sm text-gray-700">
                                        Override existing primary assignments
                                        </span>
                                    </label>
                            </div>
                        </div>

                        <!-- Notes Field -->
                            <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Notes (Optional)
                            </label>
                            <textarea 
                                id="notes"
                                wire:model="bulkForm.notes"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                rows="2"
                                    placeholder="Add any additional notes about these assignments"
                            ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Navigation -->
                <div class="px-6 py-4 bg-white border-t border-gray-200 flex justify-between">
                    <button 
                        type="button" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150"
                        x-show="activeStep > 1"
                        @click="prevStep()"
                    >
                        Previous
                    </button>
                    
                    <div class="flex space-x-3">
                        <button 
                            type="button" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150"
                            wire:click="$dispatch('closeModal')"
                        >
                            Cancel
                        </button>
                        
                        <template x-if="activeStep < totalSteps">
                            <button 
                                type="button" 
                                class="px-4 py-2 bg-primary-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                                @click="nextStep()"
                                :disabled="!stepComplete(activeStep)"
                            >
                                Next
                            </button>
                        </template>
                        
                        <template x-if="activeStep === totalSteps">
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-primary-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-75"
                            >
                                <span wire:loading.remove wire:target="saveBulkAssignments">Save Assignments</span>
                                <span wire:loading wire:target="saveBulkAssignments" class="inline-flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </template>
                    </div>
                        </div>
                    </form>
        </div>
    </div>
</div>
