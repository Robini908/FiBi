@php
$createIcon = '<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
</svg>';
@endphp

<x-modal 
    id="create-staff-modal"
    title="Add New Staff Member"
    :icon="$createIcon"
    iconBackground="bg-green-100"
    iconColor="text-green-600"
    livewireOpen="showCreateModal"
    livewireClose="closeCreateModal"
    maxWidth="2xl"
    padding="p-0"
    contentPadding="p-0"
>
    <div class="px-4 pt-5 pb-4 sm:p-6">
        <form wire:submit.prevent="createStaff">
            <div class="space-y-6">
                <!-- Personal Information Section -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4 flex items-center">
                        <svg class="h-4 w-4 mr-1 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Personal Information
                    </h4>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input wire:model="name" type="text" id="name" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input wire:model="email" type="email" id="email" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input wire:model="phone" type="text" id="phone" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select wire:model="gender" id="gender" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                @error('gender') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea wire:model="address" id="address" rows="2" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Password -->
                            <div class="md:col-span-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input wire:model="password" type="password" id="password" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Photo -->
                            <div class="md:col-span-2">
                                <label for="photo" class="block text-sm font-medium text-gray-700">Profile Photo</label>
                                <div class="mt-1 flex items-center">
                                    <input wire:model="photo" type="file" id="photo" class="sr-only">
                                    <label for="photo" class="relative cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                        <span>Choose photo</span>
                                    </label>
                                    <span class="ml-3 text-xs text-gray-500">
                                        @if ($photo)
                                            {{ $photo->getClientOriginalName() }}
                                        @else
                                            No file selected
                                        @endif
                                    </span>
                                </div>
                                @error('photo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Employment Information Section -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4 flex items-center">
                        <svg class="h-4 w-4 mr-1 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Employment Information
                    </h4>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                <select wire:model="role" id="role" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Select Role</option>
                                    @foreach (\Spatie\Permission\Models\Role::whereIn('name', \App\Helpers\Qs::getStaffRoles())->get() as $r)
                                        <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                                    @endforeach
                                </select>
                                @error('role') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Employment Date -->
                            <div>
                                <label for="empDate" class="block text-sm font-medium text-gray-700">Employment Date</label>
                                <input wire:model="empDate" type="date" id="empDate" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('empDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Qualification -->
                            <div>
                                <label for="qualification" class="block text-sm font-medium text-gray-700">Qualification</label>
                                <input wire:model="qualification" type="text" id="qualification" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('qualification') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Departments -->
                            <div>
                                <label for="departments" class="block text-sm font-medium text-gray-700">Departments</label>
                                <input wire:model="departments" type="text" id="departments" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('departments') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Experience -->
                            <div class="md:col-span-2">
                                <label for="experience" class="block text-sm font-medium text-gray-700">Experience</label>
                                <textarea wire:model="experience" id="experience" rows="2" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                @error('experience') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Active Status -->
                            <div class="md:col-span-2">
                                <div class="flex items-center">
                                    <input wire:model="isActive" id="isActive" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <label for="isActive" class="ml-2 block text-sm text-gray-700">
                                        Active Status
                                    </label>
                                </div>
                                @error('isActive') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                <x-button 
                    variant="outline" 
                    type="button"
                    wire:click="closeCreateModal"
                >
                    Cancel
                </x-button>
                <x-button 
                    type="submit"
                    wire:loading
                    wire:target="createStaff"
                    loadingText="Saving..."
                >
                    Save Staff
                </x-button>
            </div>
        </form>
    </div>
</x-modal> 