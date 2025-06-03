<!-- Modal Container -->
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Modal backdrop with smooth transition -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

        <!-- Modal panel -->
    <div class="flex items-center justify-center min-h-screen py-6 px-4 text-center sm:p-0">
        <div class="relative bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-5xl mx-auto transform transition-all">
            <!-- Modal header -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-white mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    <h3 class="text-lg font-medium text-white">Bulk Teacher-Subject Assignment</h3>
                    </div>
                <button wire:click="closeBulkModal" class="text-white hover:text-primary-200 focus:outline-none">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                                    </div>
                                    
            <!-- Content area -->
            <div class="px-6 py-4 max-h-[calc(100vh-200px)] overflow-y-auto">
                @if($showBulkResults)
                    <!-- Results Display -->
                    <div class="bg-white p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Assignment Results</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Total Assignments -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="text-sm font-medium text-gray-500">Total</div>
                                <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $bulkAssignmentResults['total'] }}</div>
                            </div>
                            <!-- Created -->
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <div class="text-sm font-medium text-green-600">Created</div>
                                <div class="mt-1 text-2xl font-semibold text-green-700">{{ $bulkAssignmentResults['created'] }}</div>
                                    </div>
                            <!-- Skipped -->
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                <div class="text-sm font-medium text-yellow-600">Skipped</div>
                                <div class="mt-1 text-2xl font-semibold text-yellow-700">{{ $bulkAssignmentResults['skipped'] }}</div>
                                    </div>
                            <!-- Errors -->
                            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                <div class="text-sm font-medium text-red-600">Errors</div>
                                <div class="mt-1 text-2xl font-semibold text-red-700">{{ $bulkAssignmentResults['errors'] }}</div>
                                    </div>
                                </div>
                        <div class="mt-6 flex justify-end">
                                    <button 
                                        wire:click="closeBulkModal" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                    >
                                        Close
                                    </button>
                                </div>
                            </div>
                        @else
                    <!-- Assignment Form -->
                    <form wire:submit.prevent="saveBulkAssignments" class="space-y-6">
                        <!-- Academic Information -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Academic Period</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Academic Year -->
                                            <div>
                                    <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year*</label>
                                    <select 
                                        id="academic_year" 
                                        wire:model="bulkForm.academic_year_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                    >
                                        <option value="">Select Year</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year['id'] }}" {{ isset($year['is_current']) && $year['is_current'] ? 'class=font-semibold' : '' }}>
                                                {{ $year['name'] }} {{ isset($year['is_current']) && $year['is_current'] ? '(Current)' : '' }}
                                                            </option>
                                                    @endforeach
                                                </select>
                                                @error('bulkForm.academic_year_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>

                                            <!-- Academic Term -->
                                            <div>
                                    <label for="academic_term" class="block text-sm font-medium text-gray-700">Term*</label>
                                    <select 
                                        id="academic_term" 
                                        wire:model="bulkForm.academic_term"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                    >
                                        <option value="">Select Term</option>
                                                    @foreach($academicTerms as $term)
                                                        <option value="{{ $term }}">{{ $term }}</option>
                                                    @endforeach
                                                </select>
                                                @error('bulkForm.academic_term') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                        <!-- Class Selection -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Class Selection</h4>
                            <div>
                                <label for="class_id" class="block text-sm font-medium text-gray-700">Class*</label>
                                                        <select 
                                    id="class_id" 
                                    wire:model.live="bulkForm.selected_class_id"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                >
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                            @endforeach
                                                        </select>
                                @error('bulkForm.selected_class_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                                    </div>
                                                    
                            <!-- Section Selection -->
                            @if($availableSections->count() > 0)
                                <div class="mt-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Sections</label>
                                        <div class="space-x-2">
                                                            <button 
                                                                type="button" 
                                                wire:click="selectAllSections"
                                                class="text-sm text-primary-600 hover:text-primary-700"
                                                            >
                                                Select All
                                                            </button>
                                            <button 
                                                type="button" 
                                                wire:click="clearSectionSelection"
                                                class="text-sm text-gray-600 hover:text-gray-700"
                                            >
                                                Clear
                                            </button>
                                        </div>
                            </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                        @foreach($availableSections as $classId => $sections)
                                            @foreach($sections as $section)
                                                <label class="inline-flex items-center p-2 border rounded-md hover:bg-gray-50">
                                                <input 
                                                    type="checkbox" 
                                                        wire:model="bulkForm.section_ids" 
                                                        value="{{ $section->id }}"
                                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                                    >
                                                    <span class="ml-2 text-sm text-gray-700">{{ $section->name }}</span>
                                            </label>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            </div>
                            
                        <!-- Teacher-Subject Mappings -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-sm font-medium text-gray-900">Teacher-Subject Pairs</h4>
                                        <button 
                                            type="button" 
                                    wire:click="addTeacherSubjectMapping"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                >
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Pair
                                        </button>
                                    </div>
                                    
                                                <div class="space-y-3">
                                @foreach($teacherSubjectMappings as $index => $mapping)
                                    <div class="flex items-center space-x-4 bg-white p-3 rounded-lg border">
                                        <div class="flex-1">
                                            <select 
                                                wire:model="teacherSubjectMappings.{{ $index }}.teacherId"
                                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            >
                                                <option value="">Select Teacher</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                                    @endforeach
                                            </select>
                                                                </div>
                                        <div class="flex-1">
                                            <select 
                                                wire:model="teacherSubjectMappings.{{ $index }}.subjectId"
                                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            >
                                                <option value="">Select Subject</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                                @endforeach
                                            </select>
                                                            </div>
                                        @if(count($teacherSubjectMappings) > 1)
                                            <button 
                                                type="button"
                                                wire:click="removeTeacherSubjectMapping({{ $index }})"
                                                class="text-red-600 hover:text-red-700 focus:outline-none"
                                            >
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                                        @endif
                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                            
                                    <!-- Assignment Options -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Assignment Options</h4>
                            <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input 
                                        type="checkbox" 
                                        id="is_primary" 
                                            wire:model="bulkForm.is_primary" 
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                        >
                                    <label for="is_primary" class="ml-2 block text-sm text-gray-700">
                                        Set as Primary Teacher
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input 
                                        type="checkbox" 
                                        id="is_active" 
                                            wire:model="bulkForm.is_active" 
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                        >
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                        Set as Active
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input 
                                        type="checkbox" 
                                        id="override_existing" 
                                            wire:model="bulkForm.override_existing" 
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                        >
                                    <label for="override_existing" class="ml-2 block text-sm text-gray-700">
                                            Override Existing Assignments
                                        </label>
                                </div>
                                    </div>
                                </div>
                                
                                <!-- Notes -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                    <textarea 
                                id="notes"
                                        wire:model="bulkForm.notes" 
                                rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                placeholder="Add any additional notes about these assignments..."
                                    ></textarea>
                                </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                wire:click="closeBulkModal"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                            >
                                Assign Teachers
                            </button>
                        </div>
                    </form>
                @endif
                </div>
        </div>
    </div>
</div> 