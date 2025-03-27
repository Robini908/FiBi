@php
$userIcon = '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
</svg>';
@endphp

<x-modal 
    id="view-staff-modal"
    title="Staff Details"
    :icon="$userIcon"
    iconBackground="bg-green-100"
    iconColor="text-green-600"
    livewireOpen="showViewModal"
    livewireClose="closeViewModal"
    maxWidth="2xl"
    contentPadding="px-4 py-5 sm:px-6"
>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Image -->
        <div class="md:col-span-1 flex justify-center">
            <div class="w-40 h-40 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                @if ($viewingStaff && $viewingStaff->profile_photo_path)
                    <img src="{{ Storage::url($viewingStaff->profile_photo_path) }}" alt="{{ $viewingStaff->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-400">
                        <svg class="h-16 w-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Staff Info -->
        <div class="md:col-span-2 space-y-4">
            <!-- Personal Information -->
            <div class="bg-gray-50 px-4 py-4 sm:px-6 rounded-lg border border-gray-100">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                    <svg class="h-4 w-4 mr-1 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Personal Information
                </h4>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                    <div class="col-span-1">
                        <dt class="text-xs font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $viewingStaff->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="text-xs font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $viewingStaff->email ?? 'N/A' }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="text-xs font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $viewingStaff->phone ?? 'N/A' }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="text-xs font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $viewingStaff->gender ?? 'N/A' }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-xs font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $viewingStaff->address ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Employment Information -->
            <div class="bg-gray-50 px-4 py-4 sm:px-6 rounded-lg border border-gray-100">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                    <svg class="h-4 w-4 mr-1 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Employment Information
                </h4>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                    <div class="col-span-1">
                        <dt class="text-xs font-medium text-gray-500">Role</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($viewingStaff && $viewingStaff->roles && $viewingStaff->roles->isNotEmpty())
                                {{ ucfirst($viewingStaff->roles->first()->name) }}
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="text-xs font-medium text-gray-500">Employment Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if ($viewingStaff && $viewingStaff->emp_date)
                                {{ \Carbon\Carbon::parse($viewingStaff->emp_date)->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="text-xs font-medium text-gray-500">Qualification</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $viewingStaff->qualification ?? 'N/A' }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="text-xs font-medium text-gray-500">Departments</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $viewingStaff->departments ?? 'N/A' }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-xs font-medium text-gray-500">Experience</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $viewingStaff->experience ?? 'N/A' }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="text-xs font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm">
                            @if ($viewingStaff && $viewingStaff->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <x-button 
                variant="outline" 
                wire:click="closeViewModal"
            >
                Close
            </x-button>
            <x-button 
                wire:click="editStaff({{ $viewingStaff->id ?? 0 }})"
            >
                Edit
            </x-button>
        </div>
    </x-slot>
</x-modal> 