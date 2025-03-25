<!-- Personal Details Section -->
<div class="mb-6 bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="flex justify-between items-center p-4 bg-gray-50 border-b border-gray-100">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Personal Details
        </h3>
        <button 
            wire:click="$toggle('editPersonalDetails')" 
            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $editPersonalDetails ? 'M6 18L18 6M6 6l12 12' : 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' }}" />
            </svg>
            {{ $editPersonalDetails ? 'Cancel' : 'Edit' }}
        </button>
    </div>
    <div class="p-5" x-data="{ showPassword: false }">
        @if ($editPersonalDetails)
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" wire:model="first_name" id="first_name" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" wire:model="middle_name" id="middle_name" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('middle_name') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Invalid middle name' }}</p> @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" wire:model="last_name" id="last_name" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Invalid last name' }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email" id="email" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Invalid email format' }}</p> @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select wire:model="gender" id="gender" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please select a gender' }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" wire:model="phone" id="phone" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Invalid phone number format' }}</p> @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" wire:model="dob" id="dob" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('dob') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Invalid date format' }}</p> @enderror
                    </div>

                    <!-- Nationality -->
                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                        <select wire:model="nationality" id="nationality" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="Kenyan">Kenyan</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('nationality') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please select a nationality' }}</p> @enderror
                    </div>

                    <!-- State -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <input type="text" wire:model="state" id="state" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('state') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Invalid state name' }}</p> @enderror
                    </div>

                    <!-- Town -->
                    <div>
                        <label for="town" class="block text-sm font-medium text-gray-700 mb-1">Town</label>
                        <input type="text" wire:model="town" id="town" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('town') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Invalid town name' }}</p> @enderror
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <label for="bg_id" class="block text-sm font-medium text-gray-700 mb-1">Blood Group</label>
                        <select wire:model="bg_id" id="bg_id" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Blood Group</option>
                            @foreach ($bloodGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('bg_id') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please select a blood group' }}</p> @enderror
                    </div>
                </div>
                
                <div class="mt-6 flex items-center justify-start space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Personal Details
                    </button>
                    <button type="button" wire:click="$toggle('editPersonalDetails')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cancel
                    </button>
                </div>
            </form>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">First Name</p>
                    <p class="text-sm text-gray-900">{{ $first_name ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Middle Name</p>
                    <p class="text-sm text-gray-900">{{ $middle_name ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Last Name</p>
                    <p class="text-sm text-gray-900">{{ $last_name ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</p>
                    <p class="text-sm text-gray-900">{{ $email ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Gender</p>
                    <p class="text-sm text-gray-900">{{ $gender ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Phone</p>
                    <p class="text-sm text-gray-900">{{ $phone ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Date of Birth</p>
                    <p class="text-sm text-gray-900">{{ $dob ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nationality</p>
                    <p class="text-sm text-gray-900">{{ $nationality ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">State</p>
                    <p class="text-sm text-gray-900">{{ $state ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Town</p>
                    <p class="text-sm text-gray-900">{{ $town ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Blood Group</p>
                    <p class="text-sm text-gray-900">{{ $student->bloodgroup->name ?? 'Not provided' }}</p>
                </div>
            </div>
        @endif
    </div>
</div> 