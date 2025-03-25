<div class="fixed inset-0 overflow-y-auto z-50" x-data>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.closeModal()" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button wire:click="closeModal" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $isEditMode ? 'Edit Assignment' : 'Add New Assignment' }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ $isEditMode ? 'Update the teacher-subject assignment details' : 'Assign a teacher to a subject for a specific class/section' }}
                    </p>
                </div>
            </div>
            
            <div class="mt-5">
                <form wire:submit.prevent="save">
                    @if(session()->has('error'))
                        <div class="rounded-md bg-red-50 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">{{ session('error') }}</h3>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @error('duplicate')
                        <div class="rounded-md bg-red-50 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">{{ $message }}</h3>
                                </div>
                            </div>
                        </div>
                    @enderror
                    
                    <!-- Two Column Grid for Form Inputs -->
                    <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                        <!-- Subject -->
                        <div>
                            <label for="form.subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                            <select id="form.subject_id" wire:model.live="form.subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                            @error('form.subject_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Teacher -->
                        <div>
                            <label for="form.teacher_id" class="block text-sm font-medium text-gray-700">Teacher</label>
                            <select id="form.teacher_id" wire:model.live="form.teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            @error('form.teacher_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Class -->
                        <div>
                            <label for="form.class_id" class="block text-sm font-medium text-gray-700">Class</label>
                            <select id="form.class_id" wire:model.live="form.class_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('form.class_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Section -->
                        <div>
                            <label for="form.section_id" class="block text-sm font-medium text-gray-700">Section</label>
                            <select id="form.section_id" wire:model.live="form.section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm" {{ count($sections) ? '' : 'disabled' }}>
                                <option value="">All Sections</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            @error('form.section_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Academic Year -->
                        <div x-data="{ 
                            open: false,
                            manualInput: false,
                            selectedYear: @entangle('form.academic_year_id'),
                            toggleManualInput() {
                                this.manualInput = !this.manualInput;
                                if (this.manualInput) {
                                    this.$nextTick(() => {
                                        this.$refs.yearInput.focus();
                                    });
                                }
                            }
                        }">
                            <label for="form.academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                            
                            <div class="relative mt-1" x-show="!manualInput">
                                <div class="flex">
                                    <div class="flex-grow">
                                        <select id="form.academic_year_id" wire:model.live="form.academic_year_id" class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                                            <option value="">Select Year</option>
                                            @foreach($academicYears as $year)
                                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" @click="toggleManualInput" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span class="ml-1">Edit</span>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="relative mt-1" x-show="manualInput">
                                <div class="flex">
                                    <div class="flex-grow">
                                        <input 
                                            x-ref="yearInput"
                                            type="text" 
                                            wire:model.live="form.academic_year_id" 
                                            class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm"
                                            placeholder="Enter academic year (e.g. 2025-2026)"
                                            pattern="[0-9]{4}-[0-9]{4}"
                                            title="Format should be YYYY-YYYY (e.g., 2025-2026)">
                                    </div>
                                    <button type="button" @click="toggleManualInput" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                        <span class="ml-1">Select</span>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Format: YYYY-YYYY without spaces (e.g., 2025-2026)</p>
                            </div>
                            
                            @error('form.academic_year_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Academic Term -->
                        <div>
                            <label for="form.academic_term" class="block text-sm font-medium text-gray-700">Term</label>
                            <select id="form.academic_term" wire:model.live="form.academic_term" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                                <option value="">Select Term</option>
                                @foreach($academicTerms as $term)
                                    <option value="{{ $term }}">{{ $term }}</option>
                                @endforeach
                            </select>
                            @error('form.academic_term') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="mt-4">
                        <label for="form.notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="form.notes" wire:model="form.notes" rows="3" class="shadow-sm focus:ring-green-500 focus:border-green-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Optional notes about this assignment"></textarea>
                        @error('form.notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Checkboxes -->
                    <div class="mt-4 space-y-3">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="form.is_primary" wire:model="form.is_primary" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="form.is_primary" class="font-medium text-gray-700">Primary Teacher</label>
                                <p class="text-gray-500">Mark as the primary teacher for this subject in this class/section</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="form.is_active" wire:model="form.is_active" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="form.is_active" class="font-medium text-gray-700">Active</label>
                                <p class="text-gray-500">Indicates if this assignment is currently active</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm">
                            {{ $isEditMode ? 'Update' : 'Save' }}
                        </button>
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 