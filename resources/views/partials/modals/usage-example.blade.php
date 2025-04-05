{{-- 
    This is an example of how to use Wire Elements Modal with your existing Livewire components.
    
    STEP 1: First, ensure you have the Wire Elements Modal component in your main layout:
    In resources/views/layouts/master.blade.php:
    
    <body>
        <!-- ... -->
        @livewire('wire-elements-modal')
        <!-- ... -->
    </body>
--}}

{{-- STEP 2: Replace your existing modal HTML with buttons that dispatch the openModal event --}}

{{-- Example: View Exam Details Button --}}
<button 
    wire:click="$dispatch('openModal', { component: 'exam-details-modal', arguments: { examId: {{ $exam->id }} } })"
    class="text-blue-600 hover:text-blue-900"
>
    <span class="sr-only">View details</span>
    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
    </svg>
</button>

{{-- Example: Delete Exam Button --}}
<button 
    wire:click="$dispatch('openModal', { component: 'delete-exam-confirmation-modal', arguments: { examId: {{ $exam->id }} } })"
    class="text-red-600 hover:text-red-900"
>
    <span class="sr-only">Delete</span>
    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
    </svg>
</button>

{{-- STEP 3: Listen for events emitted by the modal component --}}
@script
<script>
    // Listen for delete event
    $wire.on('examDeleted', (data) => {
        // Refresh the exams list or do other actions as needed
        $wire.refreshExams();
    });
    
    // Listen for edit event
    $wire.on('examUpdated', (data) => {
        // Refresh the exams list when an exam is updated
        $wire.refreshExams();
    });
</script>
@endscript

{{-- STEP 4: Remove all your old modal HTML code from your component --}}
{{-- The modals are now separate Livewire components! --}}

{{--
    CONVERSION CHECKLIST:
    
    1. Create new Livewire component extending ModalComponent
       - php artisan make:livewire ExamDetailsModal
       - Edit ExamDetailsModal.php to extend ModalComponent
       
    2. Move modal content to the component's view
       - Move HTML to resources/views/livewire/exam-details-modal.blade.php
       - Keep only the inner content (no need for fixed positioning, overlays, etc.)
       
    3. Replace modal toggles with openModal dispatch calls
       - wire:click="$dispatch('openModal', { component: 'exam-details-modal', arguments: { examId: ... } })"
       
    4. Add event listeners for modal actions
       - $wire.on('examDeleted', () => { ... })
       
    5. Remove old modal HTML and JavaScript
       - Delete the old fixed positioning divs, backdrops, etc.
       - Remove old show/hide toggle methods
--}} 