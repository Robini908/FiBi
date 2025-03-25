<!-- Student Action Dropdown -->
<div x-data="{ open: false }" class="relative inline-block text-left">
    <div>
        <button @click="open = !open" type="button"
            class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            id="options-menu" aria-expanded="true" aria-haspopup="true">
            Actions
            <svg class="-mr-1 ml-2 h-5 w-5"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div x-show="open" @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10">
        <!-- Basic Actions -->
        <div class="py-1">
            <button 
                wire:click="editStudent(<?php echo e($student->id); ?>)"
                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 w-full text-left">
                <svg class="mr-3 h-5 w-5 text-green-500 group-hover:text-green-700"
                    xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </button>

            <button 
                wire:click="viewStudent(<?php echo e($student->id); ?>)"
                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 w-full text-left">
                <svg class="mr-3 h-5 w-5 text-gray-500 group-hover:text-gray-700"
                    xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Details
            </button>
        </div>

        <!-- Status Actions -->
        <div class="py-1">
            <button 
                wire:click="approveStudent(<?php echo e($student->id); ?>)"
                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 w-full text-left">
                <svg class="mr-3 h-5 w-5 text-green-500 group-hover:text-green-700"
                    xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Approve
            </button>

            <button 
                wire:click="suspendStudent(<?php echo e($student->id); ?>)"
                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 w-full text-left <?php echo e($student->is_suspended ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                <?php echo e($student->is_suspended ? 'disabled' : ''); ?>>
                <svg class="mr-3 h-5 w-5 text-yellow-500 group-hover:text-yellow-700"
                    xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Suspension
            </button>

            <button 
                wire:click="studentExpulsion(<?php echo e($student->id); ?>)"
                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 w-full text-left <?php echo e($student->is_expelled ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                <?php echo e($student->is_expelled ? 'disabled' : ''); ?>>
                <svg class="mr-3 h-5 w-5 text-red-500 group-hover:text-red-700"
                    xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                Expulsion
            </button>

            <!--[if BLOCK]><![endif]--><?php if($student->is_expelled && !$student->is_expulsion_appealed): ?>
            <button 
                wire:click="appealExpulsion(<?php echo e($student->id); ?>)"
                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 w-full text-left">
                <svg class="mr-3 h-5 w-5 text-blue-500 group-hover:text-blue-700"
                    xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7l4-4m0 0l4 4m-4-4v18" />
                </svg>
                Appeal Expulsion
            </button>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Danger Actions -->
        <div class="py-1">
            <button 
                wire:click="deleteRecord(<?php echo e($student->id); ?>)"
                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 w-full text-left">
                <svg class="mr-3 h-5 w-5 text-red-500 group-hover:text-red-700"
                    xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
            </button>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/action-dropdown.blade.php ENDPATH**/ ?>