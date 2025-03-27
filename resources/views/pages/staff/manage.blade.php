@extends('layouts.master')

@section('page_title', 'Staff Management')

@section('content')
<div class="container mx-auto py-6 px-4">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Staff Management</h1>
    
    <!-- Breadcrumbs -->
    <div class="flex items-center text-sm text-gray-500 mb-6 space-x-1">
        <a href="{{ route('dashboard') }}" class="hover:text-green-600 transition-colors">Dashboard</a>
        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-600 font-medium">Staff Management</span>
    </div>
    
    <!-- Livewire Staff Management Component -->
    @livewire('staff.staff-list')
</div>
@endsection 