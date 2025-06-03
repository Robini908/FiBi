@extends('layouts.master')
@section('page_title', 'Manage Subjects')
@section('content')

<div class="min-h-screen bg-gray-50 py-4">
    <div class="w-full px-2 sm:px-4 lg:px-6">
        <!-- Main Content Container -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-secondary-200">
            <livewire:all-subject-management-actions lazy />
        </div>
    </div>
</div>

@endsection
