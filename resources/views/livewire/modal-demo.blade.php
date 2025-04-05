@extends('layouts.modals')

@section('page_title', 'Wire Elements Modal Demo')
@php
$modalTitle = 'Wire Elements Modal Demo';
@endphp

@section('modal_content')
<div>
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Wire Elements Modal Package Demo</h1>
    
    <div class="mb-8">
        <h2 class="text-lg font-medium mb-4 text-gray-700">Basic Modal Examples</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Basic modal -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                <h3 class="font-medium text-gray-900 mb-2">Basic Modal</h3>
                <p class="text-sm text-gray-500 mb-4">Open a simple modal with no parameters</p>
                <button 
                    onclick="Livewire.dispatch('openModal', { component: 'example-modal' })"
                    class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    Open Modal
                </button>
            </div>
            
            <!-- Modal with User ID -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                <h3 class="font-medium text-gray-900 mb-2">With User ID</h3>
                <p class="text-sm text-gray-500 mb-4">Pass a user ID parameter to the modal</p>
                <button 
                    onclick="Livewire.dispatch('openModal', { component: 'example-modal', arguments: { userId: 123 } })"
                    class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    Open With User ID
                </button>
            </div>
            
            <!-- Modal with Advanced Options -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                <h3 class="font-medium text-gray-900 mb-2">Advanced Options</h3>
                <p class="text-sm text-gray-500 mb-4">Show advanced options (role-restricted)</p>
                <button 
                    onclick="Livewire.dispatch('openModal', { component: 'example-modal', arguments: { showAdvancedOptions: true } })"
                    class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    Show Advanced Options
                </button>
            </div>
        </div>
    </div>
    
    <div class="mb-8">
        <h2 class="text-lg font-medium mb-4 text-gray-700">Role-Based Modal Examples</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Role-specific modals - these will show the same modal but with different content based on role -->
            
            <!-- Admin Modal -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm admin-accent">
                <h3 class="font-medium text-gray-900 mb-2">Admin Modal</h3>
                <p class="text-sm text-gray-500 mb-4">Modal with admin-specific content</p>
                <button 
                    onclick="Livewire.dispatch('openModal', { component: 'example-modal', arguments: { title: 'Admin Dashboard Controls' } })"
                    class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Admin Modal
                </button>
            </div>
            
            <!-- Teacher Modal -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm teacher-accent">
                <h3 class="font-medium text-gray-900 mb-2">Teacher Modal</h3>
                <p class="text-sm text-gray-500 mb-4">Modal with teacher-specific content</p>
                <button 
                    onclick="Livewire.dispatch('openModal', { component: 'example-modal', arguments: { title: 'Teacher Dashboard Controls' } })"
                    class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Teacher Modal
                </button>
            </div>
            
            <!-- Student/Parent Modal -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm student-accent">
                <h3 class="font-medium text-gray-900 mb-2">Student/Parent Modal</h3>
                <p class="text-sm text-gray-500 mb-4">Modal with student/parent-specific content</p>
                <button 
                    onclick="Livewire.dispatch('openModal', { component: 'example-modal', arguments: { title: 'Student Dashboard' } })"
                    class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    Student/Parent Modal
                </button>
            </div>
        </div>
    </div>
    
    <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
        <h2 class="text-lg font-medium mb-2 text-gray-700">Inside Livewire Components</h2>
        <p class="text-sm text-gray-500 mb-4">When opening modals from within a Livewire component, use the $dispatch method instead:</p>
        <div class="bg-gray-100 rounded-md p-4">
            <pre class="text-sm text-gray-700"><code>&lt;button wire:click="$dispatch('openModal', {component: 'example-modal'})"&gt;
    Open Modal
&lt;/button&gt;

&lt;button wire:click="$dispatch('openModal', {component: 'example-modal', arguments: {userId: 123}})"&gt;
    Open With Parameters
&lt;/button&gt;</code></pre>
        </div>
    </div>
    
    <div>
        <h2 class="text-lg font-medium mb-2 text-gray-700">Modal Component Lifecycle Events</h2>
        <p class="text-sm text-gray-500 mb-4">Handle modal lifecycle events in your frontend:</p>
        
        <div class="bg-gray-100 rounded-md p-4 mb-4">
            <pre class="text-sm text-gray-700"><code>// Listen for modal closed event
Livewire.on('modalClosed', (componentName) => {
    console.log(`Modal component ${componentName} was closed`);
});</code></pre>
        </div>
        
        <p class="text-sm text-gray-500 mt-2">
            <strong>Note:</strong> For the modal closed event to be dispatched, you need to set <code>dispatch_close_event</code> to <code>true</code> in your modal component or in the configuration.
        </p>
    </div>
</div>
@endsection
