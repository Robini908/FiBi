@extends('layouts.master')
@section('page_title', 'Modern Exam Management')

@section('content')
@livewire('exams.modern-exam-management')
@endsection

@section('page_styles')
<style>
    /* Modal animations */
    .modal-fade-enter {
        opacity: 0;
    }
    .modal-fade-enter-active {
        opacity: 1;
        transition: opacity 0.25s ease-out;
    }
    .modal-fade-exit {
        opacity: 1;
    }
    .modal-fade-exit-active {
        opacity: 0;
        transition: opacity 0.25s ease-out;
    }
    
    /* Modal scale animations */
    .modal-scale-enter {
        transform: scale(0.95);
        opacity: 0;
    }
    .modal-scale-enter-active {
        transform: scale(1);
        opacity: 1;
        transition: all 0.25s ease-out;
    }
    .modal-scale-exit {
        transform: scale(1);
        opacity: 1;
    }
    .modal-scale-exit-active {
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.25s ease-out;
    }
</style>
@endsection

@section('page_scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized - setting up modal handling');
        
        // Setup global modal handlers
        Livewire.on('open-modal', (data) => {
            console.log('Opening modal:', data.modal);
            
            // Use setTimeout to ensure Alpine has time to process the modal state change
            setTimeout(() => {
                // Find all modal triggers and activate the appropriate one
                const modalName = data.modal;
                
                // Update the Alpine.js state directly
                const component = document.querySelector('[wire\\:id]').__livewire;
                
                if (modalName === 'examForm') {
                    component.$wire.set('showExamFormModal', true);
                } else if (modalName === 'examDetails') {
                    component.$wire.set('showDetailsModal', true);
                } else if (modalName === 'gradingSystemForm') {
                    component.$wire.set('showGradingSystemFormModal', true);
                } else if (modalName === 'gradingSystemDetails') {
                    component.$wire.set('showGradingSystemDetailsModal', true);
                } else if (modalName === 'confirmDelete') {
                    component.$wire.set('confirmingDelete', true);
                }
                
                // Force Alpine to update
                Alpine.effect(() => {
                    console.log('Modal state updated via Alpine');
                });
            }, 50);
        });
        
        // Event after a modal has been opened - use this to initialize components inside the modal
        Livewire.on('modalOpened', () => {
            console.log('Modal opened, initializing components');
            initializeModalComponents();
        });
        
        // Initialize Select2 if available
        Livewire.on('initializeSelect2', () => {
            console.log('Initializing Select2 components');
            initializeSelect2Components();
        });
    });
    
    // Helper functions
    function initializeModalComponents() {
        // Initialize any components in the modal
        if (typeof tippy !== 'undefined') {
            tippy('[data-tippy-content]', {
                theme: 'light-border',
                animation: 'scale',
                duration: 200,
                arrow: true
            });
        }
        
        // Add more component initializations as needed
    }
    
    function initializeSelect2Components() {
        // Wait for DOM to update
        setTimeout(() => {
            if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                $('.select2-element').each(function() {
                    let selectElement = $(this);
                    
                    if (!selectElement.data('select2')) {
                        selectElement.select2({
                            theme: 'tailwind',
                            dropdownCssClass: 'select2-dropdown-tailwind'
                        });
                        
                        // Handle selection events
                        selectElement.on('change', function() {
                            const name = $(this).attr('id').replace('select2-', '');
                            const value = $(this).val();
                            const component = document.querySelector('[wire\\:id]').__livewire;
                            component.$wire.set(name, value);
                        });
                    }
                });
            }
        }, 200);
    }
</script>
@endsection