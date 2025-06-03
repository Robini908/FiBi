<!-- Main Content Container -->
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <h3 class="text-2xl leading-6 font-medium text-gray-900">
                Bulk Teacher-Subject Assignment
            </h3>
            <div class="mt-3 sm:mt-0 sm:ml-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Card Container -->
        <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden"
            x-data="{
                activeTab: 'academic',
                isLoading: true,
                init() {
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 1000);
                }
            }">
            
            <!-- Card Header -->
            <div class="px-6 py-4 bg-white border-b border-gray-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-100 rounded-full p-2">
                            <svg class="w-6 h-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-medium text-gray-900">Bulk Teacher-Subject Assignment</h2>
                        <p class="text-sm text-gray-500">Assign multiple teachers to subjects across different sections.</p>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="isLoading" class="p-6">
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="animate-spin h-12 w-12 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-3 text-gray-600 text-sm font-medium">Loading assignment data...</p>
                    <p class="text-xs text-gray-400 mt-1">This may take a moment</p>
                </div>
            </div>

            <!-- Content -->
            <div x-show="!isLoading" class="p-6">
                @if($showResults)
                    <!-- Results Display -->
                    <div class="bg-white rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Assignment Results</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Total Assignments -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="text-sm font-medium text-gray-500">Total</div>
                                <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $results['total'] }}</div>
                            </div>
                            <!-- Created -->
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <div class="text-sm font-medium text-green-600">Created</div>
                                <div class="mt-1 text-2xl font-semibold text-green-700">{{ $results['created'] }}</div>
                            </div>
                            <!-- Skipped -->
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                <div class="text-sm font-medium text-yellow-600">Skipped</div>
                                <div class="mt-1 text-2xl font-semibold text-yellow-700">{{ $results['skipped'] }}</div>
                            </div>
                            <!-- Errors -->
                            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                <div class="text-sm font-medium text-red-600">Errors</div>
                                <div class="mt-1 text-2xl font-semibold text-red-700">{{ $results['errors'] }}</div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <a 
                                href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                            >
                                Return to Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                            <button 
                                @click="activeTab = 'academic'"
                                :class="{'border-primary-500 text-primary-600': activeTab === 'academic', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'academic'}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Academic Period
                            </button>
                            <button 
                                @click="activeTab = 'class'"
                                :class="{'border-primary-500 text-primary-600': activeTab === 'class', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'class'}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Class Selection
                            </button>
                            <button 
                                @click="activeTab = 'assignments'"
                                :class="{'border-primary-500 text-primary-600': activeTab === 'assignments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'assignments'}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Teacher-Subject Pairs
                            </button>
                        </nav>
                    </div>

                    <form wire:submit.prevent="saveBulkAssignments" class="space-y-6">
                        <!-- Academic Period Tab -->
                        <div x-show="activeTab === 'academic'" x-transition>
                            <div class="bg-gray-50 rounded-lg p-4">
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
                        </div>

                        <!-- Class Selection Tab -->
                        <div x-show="activeTab === 'class'" x-transition>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div>
                                    <label for="class_id" class="block text-sm font-medium text-gray-700">Class*</label>
                                    <select 
                                        id="class_id" 
                                        wire:model.live="bulkForm.class_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                    >
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class['id'] }}">{{ $class['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('bulkForm.class_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
                        </div>

                        <!-- Teacher-Subject Pairs Tab -->
                        <div x-show="activeTab === 'assignments'" x-transition>
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
                                                        <option value="{{ $teacher['id'] }}">{{ $teacher['name'] }}</option>
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
                                                        <option value="{{ $subject['id'] }}">{{ $subject['subject_name'] }}</option>
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

                                <!-- Assignment Options -->
                                <div class="mt-6 space-y-4">
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

                                <!-- Notes -->
                                <div class="mt-6">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                    <textarea
                                        id="notes"
                                        wire:model="bulkForm.notes"
                                        rows="3"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                        placeholder="Add any additional notes about these assignments..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                            <a
                                href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                            >
                                Back to Dashboard
                            </a>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-500">
                                    <span class="text-primary-600 font-medium">Pro Tip:</span> 
                                    Select multiple sections to assign teachers in bulk
                                </span>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-75 cursor-not-allowed"
                                >
                                    <span wire:loading.remove>Assign Teachers</span>
                                    <span wire:loading>
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Assigning...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
