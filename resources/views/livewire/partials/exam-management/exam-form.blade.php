<div x-data="examFormManager">
    <div class="mb-6">
        <h2 class="text-lg font-medium text-primary-700">{{ $isEditing ? 'Edit Exam' : 'Create New Exam' }}</h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ $isEditing ? 'Update the exam details below.' : 'Fill in the details to create a new exam.' }}
        </p>
    </div>

    <!-- Progress Indicator -->
    <div class="relative mb-8">
        <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
            <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary-500 transition-all duration-500"
                :style="{ width: progressPercentage() + '%' }"></div>
        </div>
        <div class="flex justify-between mt-2">
            <button @click="goToStep(1)" :class="{'text-primary-600 font-medium': currentStep === 1, 'text-gray-500': currentStep !== 1}" 
                class="text-sm flex items-center focus:outline-none transition-colors">
                <span class="flex items-center justify-center h-6 w-6 rounded-full mr-2" 
                    :class="{'bg-primary-100 text-primary-700': currentStep >= 1, 'bg-gray-200 text-gray-500': currentStep < 1}">1</span>
                Basic Info
            </button>
            <button @click="goToStep(2)" :class="{'text-primary-600 font-medium': currentStep === 2, 'text-gray-400': currentStep !== 2}"
                :disabled="currentStep < 2" class="text-sm flex items-center focus:outline-none transition-colors">
                <span class="flex items-center justify-center h-6 w-6 rounded-full mr-2" 
                    :class="{'bg-primary-100 text-primary-700': currentStep >= 2, 'bg-gray-200 text-gray-500': currentStep < 2}">2</span>
                Grading System
            </button>
            <button @click="goToStep(3)" :class="{'text-primary-600 font-medium': currentStep === 3, 'text-gray-400': currentStep !== 3}"
                :disabled="currentStep < 3" class="text-sm flex items-center focus:outline-none transition-colors">
                <span class="flex items-center justify-center h-6 w-6 rounded-full mr-2" 
                    :class="{'bg-primary-100 text-primary-700': currentStep >= 3, 'bg-gray-200 text-gray-500': currentStep < 3}">3</span>
                Classes & Sections
            </button>
        </div>
    </div>

    @if($showGradingSystemForm)
        <!-- Inline Grading System Creation Form -->
        <div class="google-card bg-primary-50 p-6 mb-6 border-l-4 border-primary-500">
            <h3 class="text-lg font-medium text-primary-900 mb-3">Create New Grading System</h3>
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Grading System Name -->
                <div class="sm:col-span-3">
                    <label for="gradingSystemName" class="block text-sm font-medium text-gray-700">Name</label>
                    <div class="mt-1">
                        <input type="text" wire:model="gradingSystemName" id="gradingSystemName" 
                               class="google-input w-full">
                    </div>
                    @error('gradingSystemName')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Effective Date -->
                <div class="sm:col-span-3">
                    <label for="gradingSystemEffectiveDate" class="block text-sm font-medium text-gray-700">Effective Date</label>
                    <div class="mt-1" x-data="datePicker">
                        <input type="date" x-ref="input" wire:model="gradingSystemEffectiveDate" id="gradingSystemEffectiveDate" 
                               class="google-input w-full">
                    </div>
                    @error('gradingSystemEffectiveDate')
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('gradingSystemEffectiveDate') }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-6">
                    <label for="gradingSystemDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <div class="mt-1">
                        <textarea wire:model="gradingSystemDescription" id="gradingSystemDescription" rows="3"
                                  class="google-input w-full"></textarea>
                    </div>
                    @error('gradingSystemDescription')
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('gradingSystemDescription') }}</p>
                    @enderror
                </div>

                <!-- Rules -->
                <div class="sm:col-span-6">
                    <label for="gradingSystemRules" class="block text-sm font-medium text-gray-700">Rules</label>
                    <div class="mt-1">
                        <textarea wire:model="gradingSystemRules" id="gradingSystemRules" rows="3"
                                  class="google-input w-full"></textarea>
                    </div>
                    @error('gradingSystemRules')
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('gradingSystemRules') }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4 flex justify-end space-x-3">
                <button type="button" wire:click="cancelGradingSystemForm" 
                        class="btn-secondary">
                    Cancel
                </button>
                <button type="button" wire:click="saveGradingSystem" 
                        class="btn-primary">
                    Save Grading System
                    <span wire:loading wire:target="saveGradingSystem" class="ml-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    @endif

    <!-- Sticky Create Grading System Button -->
    @if(!$showGradingSystemForm && !$grading_system_id)
        <div x-show="currentStep === 2" class="fixed bottom-4 right-4 z-10">
            <button type="button" 
                    wire:click="$set('grading_system_id', 'create_new')" 
                    class="btn-primary inline-flex items-center transition-transform transform hover:scale-105">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create New Grading System
            </button>
        </div>
    @endif

    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" @submit="submitForm">
        <!-- Step 1: Basic Info -->
        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="google-card p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Exam Details</h3>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <!-- Exam Name -->
            <div class="sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Exam Name</label>
                <div class="mt-1">
                    <input type="text" wire:model="name" id="name" 
                                class="google-input w-full">
                </div>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Term -->
            <div class="sm:col-span-3">
                <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                <div class="mt-1">
                    <select wire:model="term" id="term" 
                                    class="google-select w-full">
                        <option value="">Select Term</option>
                        @foreach($terms as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                @error('term')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Year -->
            <div class="sm:col-span-3">
                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                <div class="mt-1">
                    <input type="number" wire:model="year" id="year" 
                                class="google-input w-full">
                </div>
                @error('year')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" @click="nextStep" class="btn-primary">
                    Continue to Grading System
                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Step 2: Grading System -->
        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="google-card p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Grading System</h3>

            <!-- Grading System -->
                <div>
                    <label for="grading_system_id" class="block text-sm font-medium text-gray-700">Select a Grading System</label>
                    <div class="mt-2 flex items-center">
                    <select wire:model.live="grading_system_id" id="grading_system_id" 
                                class="google-select w-full">
                        <option value="">Select Grading System</option>
                        @foreach($gradingSystems as $system)
                            <option value="{{ $system->id }}">{{ $system->name }}</option>
                        @endforeach
                            <option value="create_new" class="font-medium text-primary-600">+ Create New Grading System</option>
                    </select>
                    <button type="button" 
                            wire:click="viewGradingSystemDetails" 
                                class="ml-2 inline-flex items-center p-1.5 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none"
                            {{ !$grading_system_id || $grading_system_id === 'create_new' ? 'disabled' : '' }}>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('grading_system_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <!-- Grading System Info Tooltip -->
                @if($grading_system_id && $grading_system_id !== 'create_new')
                        <div class="mt-3 bg-blue-50 p-3 rounded-lg">
                            <p class="text-sm text-blue-700 flex items-center">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Click the eye icon to view grading ranges for this system</span>
                        </p>
                    </div>
                @endif
            </div>
        </div>
            <div class="flex justify-between mt-6">
                <button type="button" @click="prevStep" class="btn-secondary flex items-center">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Exam Details
                </button>
                <button type="button" @click="nextStep" class="btn-primary flex items-center">
                    Continue to Classes
                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Step 3: Classes & Sections -->
        <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="google-card p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Assign to Classes and Sections</h3>
                
                <!-- Class Selection -->
                <div class="mb-6">
                    <label for="selectedClass" class="block text-sm font-medium text-gray-700">Select Class</label>
                    <div class="mt-1">
                        <select wire:model.live="selectedClass" id="selectedClass" class="google-select w-full">
                            <option value="">Select a Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('selectedClass')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sections Selection -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">Sections</label>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model.live="selectAllSections" id="selectAllSections" 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                   {{ count($sections) === 0 ? 'disabled' : '' }}>
                            <label for="selectAllSections" class="ml-2 text-sm text-gray-700">
                                Select All Sections
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-1 bg-white rounded-md shadow-sm border border-gray-300 p-4 max-h-60 overflow-y-auto">
                        @if(count($sections) > 0)
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($sections as $section)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="section-{{ $section->id }}" value="{{ $section->id }}" 
                                               wire:model.live="selectedSections" 
                                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <label for="section-{{ $section->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $section->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 py-2">Please select a class first to view available sections.</p>
                        @endif
                    </div>
                    
                    <p class="mt-2 text-xs text-gray-500">
                        These selections determine which classes and sections will be associated with this exam in the
                        <code class="bg-gray-100 px-1 py-0.5 rounded text-xs">exam_class_section</code> table.
                    </p>
                    
                    @error('selectedSections')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Exam Schedule Details -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Exam Scheduling Details</h4>
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
                        <!-- Exam Date -->
                        <div>
                            <label for="exam_date" class="block text-sm font-medium text-gray-700">Exam Date</label>
                            <div class="mt-1">
                                <input type="date" wire:model="exam_date" id="exam_date" 
                                      class="google-input w-full">
                            </div>
                            @error('exam_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                            <div class="mt-1">
                                <input type="time" wire:model="start_time" id="start_time" 
                                      class="google-input w-full">
                            </div>
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                            <div class="mt-1">
                                <input type="time" wire:model="end_time" id="end_time" 
                                      class="google-input w-full">
                            </div>
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="mt-4">
                        <label for="instructions" class="block text-sm font-medium text-gray-700">Exam Instructions</label>
                        <div class="mt-1">
                            <textarea wire:model="instructions" id="instructions" rows="3"
                                     class="google-input w-full"></textarea>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Optional instructions for students taking this exam.</p>
                        @error('instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-6">
                <button type="button" @click="prevStep" class="btn-secondary flex items-center">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Grading System
                </button>
                <button type="submit" class="btn-primary inline-flex items-center">
                    <span x-show="!isLoading">{{ $isEditing ? 'Update Exam' : 'Create Exam' }}</span>
                    <span x-show="isLoading">{{ $isEditing ? 'Updating...' : 'Creating...' }}</span>
                    <span x-show="isLoading" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
            </div>
        </div>
    </form>
</div>

<!-- Alpine JS Script for Exam Form -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('examFormManager', () => ({
            currentStep: 1,
            isLoading: false,
            
            progressPercentage() {
                return (this.currentStep - 1) * 50;
            },
            
            nextStep() {
                if (this.currentStep < 3) {
                    this.currentStep++;
                }
            },
            
            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                }
            },
            
            goToStep(step) {
                if (step >= 1 && step <= 3) {
                    this.currentStep = step;
                }
            },
            
            submitForm() {
                this.isLoading = true;
                // The actual submission is handled by Livewire
            }
        }));
        
        // Date picker component if needed
        Alpine.data('datePicker', () => ({
            init() {
                // Initialize date picker if needed
            }
        }));
    });
</script> 