<!-- Parent Details Section -->
<div class="mb-6 bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="flex justify-between items-center p-4 bg-gray-50 border-b border-gray-100">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Parent Details
        </h3>
        <button 
            wire:click="$set('editParentDetails', {{ !$editParentDetails }})" 
            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $editParentDetails ? 'M6 18L18 6M6 6l12 12' : 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' }}" />
            </svg>
            {{ $editParentDetails ? 'Cancel' : 'Edit' }}
        </button>
    </div>
    <div class="p-5" x-data="{ showNewPassword: false, showConfirmPassword: false, showOldPassword: false }">
        @if ($editParentDetails)
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Parent ID Number -->
                    <div>
                        <label for="parent_id_no" class="block text-sm font-medium text-gray-700 mb-1">Parent ID Number</label>
                        <input type="text" wire:model="parent_id_no" id="parent_id_no" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('parent_id_no') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="parent_first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" wire:model="parent_first_name" id="parent_first_name" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('parent_first_name') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid first name.' }}</p> @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="parent_middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" wire:model="parent_middle_name" id="parent_middle_name" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('parent_middle_name') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid middle name.' }}</p> @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="parent_last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" wire:model="parent_last_name" id="parent_last_name" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('parent_last_name') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid last name.' }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="parent_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" wire:model="parent_phone" id="parent_phone" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('parent_phone') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid phone number.' }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="parent_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="parent_email" id="parent_email" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('parent_email') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid email address.' }}</p> @enderror
                    </div>
                </div>

                <!-- Password Update Section -->
                <div class="mt-8">
                    <h4 class="text-base font-medium text-gray-800 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Change Password
                    </h4>

                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($isAdmin)
                                <!-- Admin: Only show new password and confirmation -->
                                <div>
                                    <label for="parent_new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                    <div class="relative">
                                        <input 
                                            :type="showNewPassword ? 'text' : 'password'" 
                                            wire:model="parent_new_password" 
                                            id="parent_new_password"
                                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10">
                                        <button 
                                            type="button" 
                                            @click="showNewPassword = !showNewPassword" 
                                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!showNewPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="showNewPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('parent_new_password') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid password.' }}</p> @enderror
                                </div>
                                <div>
                                    <label for="parent_new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                    <div class="relative">
                                        <input 
                                            :type="showConfirmPassword ? 'text' : 'password'" 
                                            wire:model="parent_new_password_confirmation" 
                                            id="parent_new_password_confirmation"
                                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10">
                                        <button 
                                            type="button" 
                                            @click="showConfirmPassword = !showConfirmPassword" 
                                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!showConfirmPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="showConfirmPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('parent_new_password_confirmation') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Password confirmation does not match.' }}</p> @enderror
                                </div>
                            @else
                                <!-- Parent: Show old password, new password, and confirmation -->
                                <div>
                                    <label for="parent_old_password" class="block text-sm font-medium text-gray-700 mb-1">Old Password</label>
                                    <div class="relative">
                                        <input 
                                            :type="showOldPassword ? 'text' : 'password'" 
                                            wire:model="parent_old_password" 
                                            id="parent_old_password"
                                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10">
                                        <button 
                                            type="button" 
                                            @click="showOldPassword = !showOldPassword" 
                                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!showOldPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="showOldPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('parent_old_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="parent_new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                    <div class="relative">
                                        <input 
                                            :type="showNewPassword ? 'text' : 'password'" 
                                            wire:model="parent_new_password" 
                                            id="parent_new_password"
                                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10">
                                        <button 
                                            type="button" 
                                            @click="showNewPassword = !showNewPassword" 
                                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!showNewPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="showNewPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('parent_new_password') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid password.' }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="parent_new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                    <div class="relative">
                                        <input 
                                            :type="showConfirmPassword ? 'text' : 'password'" 
                                            wire:model="parent_new_password_confirmation" 
                                            id="parent_new_password_confirmation"
                                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10">
                                        <button 
                                            type="button" 
                                            @click="showConfirmPassword = !showConfirmPassword" 
                                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!showConfirmPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="showConfirmPassword">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('parent_new_password_confirmation') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Password confirmation does not match.' }}</p> @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex items-center justify-start space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Parent Details
                    </button>
                    <button type="button" wire:click="$set('editParentDetails', {{ !$editParentDetails }})" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cancel
                    </button>
                </div>
            </form>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Parent ID Number</p>
                    <p class="text-sm text-gray-900">{{ $parent_id_no ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">First Name</p>
                    <p class="text-sm text-gray-900">{{ $parent_first_name ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Middle Name</p>
                    <p class="text-sm text-gray-900">{{ $parent_middle_name ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Last Name</p>
                    <p class="text-sm text-gray-900">{{ $parent_last_name ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Phone</p>
                    <p class="text-sm text-gray-900">{{ $parent_phone ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</p>
                    <p class="text-sm text-gray-900">{{ $parent_email ?: 'Not provided' }}</p>
                </div>
            </div>
        @endif
    </div>
</div> 