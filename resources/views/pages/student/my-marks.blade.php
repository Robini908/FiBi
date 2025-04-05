@extends('layouts.master')
@section('page_title', 'My Exam Marks')
@section('content')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
                        <svg class="inline-block w-6 h-6 mr-2 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        My Exam Marks
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">View your academic performance and exam results</p>
                </div>

                <!-- Student Mark Information -->
                <livewire:student.marks-list />
            </div>
        </div>
    </div>
</div>

@endsection 