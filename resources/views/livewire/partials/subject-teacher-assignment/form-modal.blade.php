<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ $currentAssignmentId ? 'Edit Assignment' : 'Create New Assignment' }}
                        </h3>
                        
                        <!-- Form -->
                        <form id="assignment-form" wire:submit.prevent="save" class="mt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Teacher Selection -->
                                <div>
                                    <label for="teacherId" class="block text-sm font-medium text-gray-700">Teacher*</label>
                                    <select id="teacherId" wire:model="form.teacher_id" class="mt-1 shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Select a teacher</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.teacher_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Subject Selection -->
                                <div>
                                    <label for="subjectId" class="block text-sm font-medium text-gray-700">Subject*</label>
                                    <select id="subjectId" wire:model="form.subject_id" class="mt-1 shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Select a subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->subject_name }} ({{ $subject->subject_code }})</option>
                                        @endforeach
                                    </select>
                                    @error('form.subject_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                        
                                <!-- Class Selection -->
                                <div>
                                    <label for="classId" class="block text-sm font-medium text-gray-700">Class*</label>
                                    <select id="classId" wire:model.live="form.class_id" class="mt-1 shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Select a class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.class_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                        
                                <!-- Section Selection -->
                                <div>
                                    <label for="sectionId" class="block text-sm font-medium text-gray-700">Section</label>
                                    <select id="sectionId" wire:model.live="form.section_id" class="mt-1 shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Select a section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @if(count($sections) === 0)
                                        <p class="mt-1 text-xs text-amber-600">Please select a class first to load available sections</p>
                                    @endif
                                    @error('form.section_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                        
                                <!-- Academic Year -->
                                <div>
                                    <label for="academicYearId" class="block text-sm font-medium text-gray-700">Academic Year*</label>
                                    <select id="academicYearId" wire:model="form.academic_year_id" class="mt-1 shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Select academic year</option>
                                        @foreach($academicYears as $academicYear)
                                            @if(is_array($academicYear))
                                                <option value="{{ $academicYear['id'] }}" {{ isset($academicYear['is_current']) && $academicYear['is_current'] ? 'class=font-bold' : '' }}>
                                                    {{ $academicYear['name'] }} {{ isset($academicYear['is_current']) && $academicYear['is_current'] ? '(Current)' : '' }}
                                                </option>
                                            @else
                                                <option value="{{ $academicYear->id }}">{{ $academicYear->year }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('form.academic_year_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Academic Term -->
                                <div>
                                    <label for="academicTerm" class="block text-sm font-medium text-gray-700">Academic Term*</label>
                                    <select id="academicTerm" wire:model="form.academic_term" class="mt-1 shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Select term</option>
                                        @foreach($academicTerms as $term)
                                            <option value="{{ $term }}">{{ $term }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.academic_term') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div class="flex items-center">
                                    <input id="isPrimary" wire:model="form.is_primary" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <label for="isPrimary" class="ml-2 block text-sm text-gray-700">
                                        Primary Assignment
                                    </label>
                                </div>
                            
                                <div class="flex items-center">
                                    <input id="isActive" wire:model="form.is_active" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <label for="isActive" class="ml-2 block text-sm text-gray-700">
                                        Active Assignment
                                    </label>
                                </div>
                            </div>
                                    
                            <!-- Notes -->
                            <div class="mt-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                <textarea id="notes" wire:model="form.notes" rows="3" class="mt-1 shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add any additional information about this assignment"></textarea>
                                @error('form.notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            
                            @error('duplicate')
                                <div class="mt-3 bg-red-50 p-2 rounded">
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                </div>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
                        
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <!-- Submit Button -->
                <button 
                    type="submit"
                    form="assignment-form"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    <span wire:loading.class="hidden" wire:target="save">
                        {{ $currentAssignmentId ? 'Update' : 'Create' }}
                    </span>
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
                
                <!-- Cancel Button -->
                <button 
                    wire:click="closeModal" 
                    type="button" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 