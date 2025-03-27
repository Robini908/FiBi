@php
$warningIcon = '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
</svg>';
@endphp

<x-modal 
    id="delete-staff-modal"
    title="Delete Staff Member"
    :icon="$warningIcon"
    iconBackground="bg-red-100"
    iconColor="text-red-600"
    livewireOpen="showDeleteModal"
    livewireClose="closeDeleteModal"
    maxWidth="lg"
>
    <div class="mt-2">
        <p class="text-sm text-gray-500">
            Are you sure you want to delete this staff member? All data associated with this staff will be permanently removed.
            This action cannot be undone.
        </p>
    </div>

    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <x-button 
                variant="outline" 
                wire:click="closeDeleteModal"
            >
                Cancel
            </x-button>
            <x-button 
                variant="danger"
                wire:click="deleteStaff"
                loading="wire:loading wire:target=deleteStaff"
                loadingText="Deleting..."
            >
                Delete
            </x-button>
        </div>
    </x-slot>
</x-modal> 