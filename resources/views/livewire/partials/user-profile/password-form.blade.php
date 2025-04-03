<!-- Modern Password Form -->
<div x-show="activeTab === 'password'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-6">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-medium text-gray-900">Password & Security</h2>
            <p class="mt-1 text-sm text-gray-500">Ensure your account is using a secure password</p>
        </div>
        
        <div class="p-6">
            <form wire:submit.prevent="updatePassword" class="space-y-8">
                <div>
                    <div class="max-w-xl">
                        <p class="text-sm text-gray-700 mb-6">
                            Strong passwords include a mix of letters, numbers, and special characters. 
                            Update your password regularly to keep your account secure.
                        </p>
                    </div>
                    
                    <div class="space-y-6 mt-6">
                        <!-- Current Password -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="current_password" class="block text-sm font-medium text-gray-700">
                                    Current Password
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm" x-data="{ showPassword: false }">
                                    <input 
                                        :type="showPassword ? 'text' : 'password'" 
                                        id="current_password" 
                                        wire:model.defer="current_password" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                                        autocomplete="current-password"
                                    >
                                    <button 
                                        type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 right-0 px-3 flex items-center bg-transparent border-0 focus:outline-none"
                                    >
                                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                @foreach ($errors->get('current_password') as $error)
                                    <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    New Password
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm" x-data="{ showPassword: false }">
                                    <input 
                                        :type="showPassword ? 'text' : 'password'" 
                                        id="password" 
                                        wire:model.defer="password" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                                        autocomplete="new-password"
                                    >
                                    <button 
                                        type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 right-0 px-3 flex items-center bg-transparent border-0 focus:outline-none"
                                    >
                                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                @foreach ($errors->get('password') as $error)
                                    <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <div class="bg-blue-50 rounded-md p-4 border border-blue-100">
                                    <h4 class="text-sm font-medium text-blue-800 mb-2">Password Requirements:</h4>
                                    <ul class="space-y-1 text-xs text-blue-700">
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Minimum 8 characters
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Include at least one uppercase letter
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Include at least one number
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Avoid using common words or personal information
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Confirm New Password
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm" x-data="{ showPassword: false }">
                                    <input 
                                        :type="showPassword ? 'text' : 'password'" 
                                        id="password_confirmation" 
                                        wire:model.defer="password_confirmation" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                                        autocomplete="new-password"
                                    >
                                    <button 
                                        type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 right-0 px-3 flex items-center bg-transparent border-0 focus:outline-none"
                                    >
                                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                @foreach ($errors->get('password_confirmation') as $error)
                                    <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 flex justify-end">
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Additional Security Features (for future implementation) -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-medium text-gray-900">Additional Security Features</h2>
            <p class="mt-1 text-sm text-gray-500">Enable additional security features to protect your account</p>
        </div>
        
        <div class="p-6">
            <div class="flex items-center justify-between py-4 border-b border-gray-200">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Two-Factor Authentication</h3>
                    <p class="mt-1 text-xs text-gray-500">Add an extra layer of security to your account</p>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Coming Soon
                    </span>
                </div>
            </div>
            
            <div class="flex items-center justify-between py-4 border-b border-gray-200">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Login Activity</h3>
                    <p class="mt-1 text-xs text-gray-500">Monitor recent logins to your account</p>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Coming Soon
                    </span>
                </div>
            </div>
            
            <div class="flex items-center justify-between py-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Session Management</h3>
                    <p class="mt-1 text-xs text-gray-500">Manage your active sessions and sign out remotely</p>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div>
    </div>
</div> 