<div 
    x-data="{ 
        init() {
            console.log('Alpine component initialized');
            
            // Listen for Livewire updates to make sure only one card is visible
            this.$watch('$wire.isCreating', (value) => {
                if(value) {
                    console.log('Creating exam card is now visible');
                }
            });
            
            this.$watch('$wire.isEditing', (value) => {
                if(value) {
                    console.log('Editing exam card is now visible');
                }
            });
            
            this.$watch('$wire.showExamDetails', (value) => {
                if(value) {
                    console.log('Exam details card is now visible');
                }
            });
            
            this.$watch('$wire.showGradingForm', (value) => {
                if(value) {
                    console.log('Grading system form card is now visible');
                }
            });
            
            this.$watch('$wire.showGradingDetails', (value) => {
                if(value) {
                    console.log('Grading system details card is now visible');
                }
            });
            
            this.$watch('$wire.confirmingDelete', (value) => {
                if(value) {
                    console.log('Delete confirmation card is now visible');
                }
            });
        }
    }"
    class="max-w-full bg-white overflow-hidden border border-gray-200 rounded-lg shadow-sm"
>
    <!-- Error information -->
    @if($errorInfo)
    <div class="m-4">
        <div class="p-4 rounded-md bg-red-50">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Error loading exam management</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ $errorInfo }}</p>
                    </div>
                    <div class="mt-4">
                        <button wire:click="retryLoadData" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Retry Loading
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else

    <!-- Header section -->
    <div class="px-6 py-5 border-b border-gray-200 bg-white">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Exam Management</h2>
                <p class="mt-1 text-sm text-gray-500">Create and manage exams, set grading systems, and view reports.</p>
            </div>
            @if(Qs::isAdministratorOrTeacher())
            <div class="mt-4 sm:mt-0">
                <button wire:click="create" type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create New Exam
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Tabs navigation -->
    <div class="px-6 pt-4 border-b border-gray-200 bg-white">
        <nav class="-mb-px flex space-x-6 overflow-x-auto">
            <button wire:click="setActiveTab('exams')"
                class="{{ $activeTab == 'exams' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                Exams
            </button>
            <button wire:click="setActiveTab('gradingSystems')"
                class="{{ $activeTab == 'gradingSystems' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                </svg>
                Grading Systems
            </button>
            <button wire:click="setActiveTab('marks')"
                class="{{ $activeTab == 'marks' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                </svg>
                Exam Marks
            </button>
            <button wire:click="setActiveTab('reports')"
                class="{{ $activeTab == 'reports' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd" />
                </svg>
                Reports
            </button>
        </nav>
    </div>

    <!-- Card for exam/grading system form (replaces modal) -->
    @if($isCreating || $isEditing || $showGradingForm)
    <div class="transition-all transform duration-300 ease-in-out bg-white border-b border-gray-200">
        <div class="p-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Card Header -->
                <div class="bg-green-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-green-100">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        @if($isCreating)
                            Create New Exam
                        @elseif($isEditing)
                            Edit Exam
                        @elseif($showGradingForm)
                            {{ $isEditingGradingSystem ? 'Edit Grading System' : 'Create New Grading System' }}
                        @endif
                    </h3>
                    <button wire:click="resetForm" type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 rounded-full p-1 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    @if($isCreating || $isEditing)
                        @include('livewire.exams.partials.cards.exam-form')
                    @elseif($showGradingForm)
                        @include('livewire.exams.partials.cards.grading-system-form')
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Card for exam/grading system details (replaces modal) -->
    @if($showExamDetails || $showGradingDetails)
    <div class="transition-all transform duration-300 ease-in-out bg-white border-b border-gray-200">
        <div class="p-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Card Header -->
                <div class="bg-blue-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-blue-100">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        @if($showExamDetails)
                            Exam Details
                        @elseif($showGradingDetails)
                            Grading System Details
                        @endif
                    </h3>
                    <button wire:click="closeDetails" type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full p-1 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    @if($showExamDetails)
                        @include('livewire.exams.partials.cards.exam-details')
                    @elseif($showGradingDetails)
                        @include('livewire.exams.partials.cards.grading-system-details')
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Confirmation Card (replaces delete confirmation modal) -->
    @if($confirmingDelete)
    <div class="transition-all transform duration-300 ease-in-out bg-white border-b border-gray-200">
        <div class="p-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Card Header -->
                <div class="bg-red-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-red-100">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Confirm Delete</h3>
                    <button wire:click="$set('confirmingDelete', false)" type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-full p-1 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Confirmation</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    @if($confirmDeleteType === 'gradingSystem')
                                        Are you sure you want to delete this grading system? This action cannot be undone.
                                    @else
                                        Are you sure you want to delete this exam? This action cannot be undone.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        @if($confirmDeleteType === 'gradingSystem')
                            <button wire:click="deleteGradingSystem" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                                Delete
                            </button>
                        @else
                            <button wire:click="deleteExam" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                                Delete
                            </button>
                        @endif
                        <button wire:click="$set('confirmingDelete', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tab content -->
    <div class="py-4 px-6 bg-gray-50">
        @if($activeTab == 'exams')
            <div class="transition-all transform duration-300 ease-in-out">
                @include('livewire.exams.partials.exams-tab')
            </div>
        @elseif($activeTab == 'gradingSystems')
            <div class="transition-all transform duration-300 ease-in-out">
                @include('livewire.exams.partials.grading-tab')
            </div>
        @elseif($activeTab == 'marks')
            <div class="transition-all transform duration-300 ease-in-out">
                @include('livewire.exams.partials.marks-tab')
            </div>
        @elseif($activeTab == 'reports')
            <div class="transition-all transform duration-300 ease-in-out">
                @include('livewire.exams.partials.reports-tab')
            </div>
        @endif
    </div>
    @endif
</div>

<!-- Initialize scripts -->
<script>
document.addEventListener('livewire:initialized', function () {
    // Initialize tooltips on page load
    initializeTippy();

    // Listen for select2 initialization
    Livewire.on('initializeSelect2', () => {
        // Wait for DOM to update
        setTimeout(function() {
            // Initialize all select2 elements
            if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                $('.select2-element').each(function() {
                    let selectElement = $(this);

                    selectElement.select2({
                        theme: 'tailwind',
                        dropdownCssClass: 'select2-dropdown-tailwind'
                    });

                    // Handle selection events
                    selectElement.on('change', function() {
                        const name = $(this).attr('id').replace('select2-', '');
                        const value = $(this).val();
                        Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).set(name, value);
                    });
                });
            }

            // Re-initialize tooltips after DOM updates
            initializeTippy();
        }, 200);
    });

    // Re-initialize tooltips when sections load or change
    Livewire.on('contentChanged', () => {
        setTimeout(initializeTippy, 100);
    });
});

// Function to initialize tooltips
function initializeTippy() {
    if (typeof tippy !== 'undefined') {
        tippy('[data-tippy-content]', {
            theme: 'light-border',
            animation: 'scale',
            duration: 200,
            arrow: true,
            placement: 'top'
        });
    }
}
</script>