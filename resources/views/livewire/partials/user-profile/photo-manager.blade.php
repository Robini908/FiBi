<!-- Photo Management Component -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-medium text-gray-900">Profile Photo</h3>
        <p class="mt-1 text-sm text-gray-500">Update your profile picture</p>
    </div>
    
    <div class="p-6">
        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <!-- Current Photo Display -->
            <div class="flex-shrink-0">
                @if($tempPhoto)
                    <img 
                        src="{{ $tempPhoto->temporaryUrl() }}" 
                        alt="Preview" 
                        class="h-32 w-32 rounded-full object-cover ring-4 ring-white bg-gray-100 shadow"
                    >
                @else
                    <img 
                        src="{{ $photo }}" 
                        alt="{{ $name }}" 
                        class="h-32 w-32 rounded-full object-cover ring-4 ring-white bg-gray-100 shadow"
                    >
                @endif
            </div>
            
            <!-- Upload Controls -->
            <div class="flex-1 space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Change photo</h4>
                    <p class="mt-1 text-xs text-gray-500">
                        Upload a clear photo to help others recognize you. JPG, PNG or GIF up to 2MB.
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <label for="photo-upload" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Upload Photo
                    </label>
                    <input 
                        id="photo-upload" 
                        type="file" 
                        wire:model="tempPhoto" 
                        class="hidden"
                        accept="image/*"
                    >
                    
                    @if(!str_contains($photo, 'ui-avatars.com'))
                        <button 
                            type="button"
                            wire:click="removePhoto"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Remove
                        </button>
                    @endif
                </div>
                
                @foreach ($errors->get('tempPhoto') as $error)
                    <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
                @endforeach
                
                @if($tempPhoto)
                    <div class="flex items-center space-x-3">
                        <button 
                            type="button"
                            wire:click="savePhoto"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Photo
                        </button>
                        
                        <button 
                            type="button"
                            wire:click="cancelPhotoUpload"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            Cancel
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 