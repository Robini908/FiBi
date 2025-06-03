@extends('layouts.master')

@section('page_title', 'Edit Grading System')

@section('breadcrumbs')
    <nav class="text-sm" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex text-gray-500">
            <li class="flex items-center">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('exams.grading-systems.index') }}" class="hover:text-gray-700">Grading Systems</a>
                <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                </svg>
            </li>
            <li class="text-gray-700">Edit Grading System</li>
        </ol>
    </nav>
@endsection

@section('page_action_buttons')
    <a 
        href="{{ route('exams.grading-systems.index') }}" 
        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
        x-data=""
        @click.prevent="$dispatch('navigate-to', {route: '{{ route('exams.grading-systems.index') }}'})"
    >
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Back to List</span>
    </a>
@endsection

@section('content')
    <div 
        x-data="{
            loading: false,
            showSuccessMessage: false,
            successMessage: '',
            showErrorMessage: false,
            errorMessage: '',
            gradingSystemId: '{{ request()->route('gradingSystem') }}'
        }"
        x-init="
            Livewire.on('gradingSystemUpdated', (message) => {
                showSuccessMessage = true;
                successMessage = message;
                loading = false;
                
                // Redirect after short delay
                setTimeout(() => {
                    window.history.pushState({}, '', '{{ route('exams.grading-systems.index') }}');
                    $dispatch('navigate-to', {route: '{{ route('exams.grading-systems.index') }}'});
                }, 1500);
            });
            
            Livewire.on('errorOccurred', (message) => {
                showErrorMessage = true;
                errorMessage = message;
                loading = false;
            });
            
            window.addEventListener('navigate-to', (event) => {
                loading = true;
                setTimeout(() => {
                    window.location.href = event.detail.route;
                }, 100);
            });
        "
        @loading.class="opacity-50 pointer-events-none"
    >
        <!-- Success Message -->
        <div 
            x-show="showSuccessMessage" 
            x-transition 
            class="bg-green-50 border-l-4 border-green-400 p-4 mb-4"
            style="display: none;"
        >
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700" x-text="successMessage"></p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button 
                            @click="showSuccessMessage = false" 
                            class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Message -->
        <div 
            x-show="showErrorMessage" 
            x-transition 
            class="bg-red-50 border-l-4 border-red-400 p-4 mb-4"
            style="display: none;"
        >
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700" x-text="errorMessage"></p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button 
                            @click="showErrorMessage = false" 
                            class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loading Overlay -->
        <div 
            x-show="loading" 
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity duration-300"
            style="display: none;"
        >
            <div class="bg-white p-6 rounded-lg shadow-xl flex flex-col items-center max-w-sm mx-auto">
                <span class="loading loading-spinner loading-lg text-green-600 mb-4"></span>
                <p class="text-gray-700 font-medium">Processing your request...</p>
                <p class="text-sm text-gray-500 mt-1">This may take a moment</p>
            </div>
        </div>
        
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-5">Edit Grading System</h3>
                
                @livewire('exams.edit-grading-system', ['gradingSystem' => request()->route('gradingSystem')])
            </div>
        </div>
    </div>
@endsection 