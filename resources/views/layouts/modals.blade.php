@extends('layouts.master')

@section('page_title', $title ?? 'Modal')

@push('styles')
<style>
    /* Custom styles for modal pages */
    .modal-content-wrapper {
        background-color: rgba(240, 249, 244, 0.3);
        min-height: 70vh;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        padding: 2rem 0;
    }
    
    .modal-demo-card {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 95%;
        width: 100%;
        overflow: hidden;
    }
    
    .modal-header {
        background: linear-gradient(to right, var(--tw-gradient-stops));
        --tw-gradient-from: #059669;
        --tw-gradient-to: #065f46;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    /* Role-based color accents */
    .admin-accent {
        border-left: 4px solid #4f46e5;
    }
    
    .teacher-accent {
        border-left: 4px solid #0ea5e9;
    }
    
    .student-accent {
        border-left: 4px solid #059669;
    }
    
    .parent-accent {
        border-left: 4px solid #f59e0b;
    }
    
    .librarian-accent {
        border-left: 4px solid #8b5cf6;
    }
    
    .accountant-accent {
        border-left: 4px solid #ec4899;
    }
</style>
@endpush

@section('content')
<div class="modal-content-wrapper">
    <div class="modal-demo-card">
        <div class="modal-header">
            <h2 class="text-lg font-medium text-white">
                {{ $modalTitle ?? $title ?? 'Modal Component' }}
            </h2>
        </div>
        <div class="modal-body">
            @yield('modal_content')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any modal-specific JavaScript here
        console.log('Modal layout loaded');
        
        // Example of handling modal events
        Livewire.on('modalClosed', (componentName) => {
            console.log(`Modal component ${componentName} was closed`);
        });
    });
</script>
@endpush 