<div>
<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
    <div class="sm:flex sm:items-start">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
            <!-- Icon based on user role -->
            @if(auth()->check())
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                    <!-- Admin icon -->
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                @elseif(auth()->user()->role === 'teacher')
                    <!-- Teacher icon -->
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                @elseif(auth()->user()->role === 'librarian')
                    <!-- Librarian icon -->
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                @elseif(auth()->user()->role === 'accountant')
                    <!-- Accountant icon -->
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @else
                    <!-- Student/Parent icon -->
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            @else
                <!-- Default icon for guests -->
                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @endif
        </div>
        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                {{ $title }}
            </h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    This is an example modal that demonstrates role-based content display.
                    @if(auth()->check())
                        You are logged in as <strong>{{ auth()->user()->name }}</strong> with role <strong>{{ auth()->user()->role ?? 'unknown' }}</strong>.
                    @else
                        You are not logged in.
                    @endif
                </p>
            </div>
            
            <!-- User ID display if provided -->
            @if($userId)
                <div class="mt-4 p-3 bg-gray-50 rounded-md">
                    <p class="text-sm font-medium text-gray-700">User ID: {{ $userId }}</p>
                </div>
            @endif
            
            <!-- Role-based content sections -->
            @if(auth()->check())
                <!-- Admin and SuperAdmin section -->
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                    <div class="mt-4 border-t pt-4">
                        <h4 class="font-medium text-green-600">Admin Controls</h4>
                        <div class="mt-2 space-y-3">
                            <div class="flex items-center">
                                <input id="admin-option1" name="admin-option1" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="admin-option1" class="ml-2 block text-sm text-gray-700">
                                    Enable advanced features
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="admin-option2" name="admin-option2" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="admin-option2" class="ml-2 block text-sm text-gray-700">
                                    Override security restrictions
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Teacher section -->
                @if(auth()->user()->role === 'teacher')
                    <div class="mt-4 border-t pt-4">
                        <h4 class="font-medium text-green-600">Teacher Controls</h4>
                        <div class="mt-2 space-y-3">
                            <div class="flex items-center">
                                <input id="teacher-option1" name="teacher-option1" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="teacher-option1" class="ml-2 block text-sm text-gray-700">
                                    Mark as reviewed
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="teacher-option2" name="teacher-option2" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="teacher-option2" class="ml-2 block text-sm text-gray-700">
                                    Send parent notification
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Librarian section -->
                @if(auth()->user()->role === 'librarian')
                    <div class="mt-4 border-t pt-4">
                        <h4 class="font-medium text-green-600">Library Controls</h4>
                        <div class="mt-2 space-y-3">
                            <div class="flex items-center">
                                <input id="lib-option1" name="lib-option1" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="lib-option1" class="ml-2 block text-sm text-gray-700">
                                    Mark as borrowed
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="lib-option2" name="lib-option2" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="lib-option2" class="ml-2 block text-sm text-gray-700">
                                    Schedule return reminder
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Accountant section -->
                @if(auth()->user()->role === 'accountant')
                    <div class="mt-4 border-t pt-4">
                        <h4 class="font-medium text-green-600">Financial Controls</h4>
                        <div class="mt-2 space-y-3">
                            <div class="flex items-center">
                                <input id="acc-option1" name="acc-option1" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="acc-option1" class="ml-2 block text-sm text-gray-700">
                                    Mark as paid
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="acc-option2" name="acc-option2" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="acc-option2" class="ml-2 block text-sm text-gray-700">
                                    Generate receipt
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Student/Parent section -->
                @if(auth()->user()->role === 'student' || auth()->user()->role === 'parent')
                    <div class="mt-4 border-t pt-4">
                        <h4 class="font-medium text-green-600">Actions</h4>
                        <div class="mt-2 space-y-3">
                            <div class="flex items-center">
                                <input id="student-option1" name="student-option1" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="student-option1" class="ml-2 block text-sm text-gray-700">
                                    Acknowledge receipt
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            
            <!-- Advanced options section -->
            @if($showAdvancedOptions)
                <div class="mt-6 bg-green-50 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Advanced Options</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>These options are only available to admin, teacher, and superadmin roles.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
    <button wire:click="save" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
        Save
    </button>
    <button wire:click="cancel" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
        Cancel
    </button>
</div>

<!-- Alpine.js script for handling unsaved changes warning -->
@script
<script>
    // Example of preventing accidental modal close when there are unsaved changes
    $wire.on('closingModalOnEscape', data => {
        // In a real app, you would check for form changes
        const isDirty = false; // Replace with actual dirty check
        
        if (isDirty && !confirm('You have unsaved changes. Are you sure you want to close this dialog?')) {
            data.closing = false;
        }
    });
    
    $wire.on('closingModalOnClickAway', data => {
        // In a real app, you would check for form changes
        const isDirty = false; // Replace with actual dirty check
        
        if (isDirty && !confirm('You have unsaved changes. Are you sure you want to close this dialog?')) {
            data.closing = false;
        }
    });
</script>
@endscript
</div>
