@extends('layouts.master')
@section('page_title', 'Modern Exam Management')

@section('content')
@livewire('exams.modern-exam-management')
@endsection

@section('page_scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('examManagement', () => ({
            init() {
                this.$watch('$wire.refresh', () => {
                    this.initializeComponents()
                })
            },

            initializeComponents() {
                // Add any Alpine.js component initialization here if needed
            }
        }))
    })
</script>
@endsection