<div class="mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col">
            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Upload Excel File</label>
            <div 
                x-data="{ 
                    fileName: '', 
                    isUploading: false,
                    isHovering: false
                }"
                x-on:livewire-upload-start="isUploading = true"
                x-on:livewire-upload-finish="isUploading = false"
                x-on:livewire-upload-error="isUploading = false"
                class="relative">
                
                <div 
                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md transition-colors"
                    :class="{
                        'border-green-300 bg-green-50': isHovering,
                        'border-gray-300 bg-gray-50': !isHovering
                    }"
                    x-on:dragover.prevent="isHovering = true"
                    x-on:dragleave.prevent="isHovering = false"
                    x-on:drop.prevent="isHovering = false">
                    
                    <div class="space-y-1 text-center">
                        <svg 
                            class="mx-auto h-12 w-12 transition-colors"
                            :class="fileName ? 'text-green-500' : 'text-gray-400'"
                            stroke="currentColor" 
                            fill="none" 
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        
                        <div class="flex text-sm text-gray-600">
                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                <span x-text="fileName || 'Upload a file'">Upload a file</span>
                                <input 
                                    id="file" 
                                    type="file" 
                                    class="sr-only" 
                                    accept=".xls,.xlsx,.csv" 
                                    wire:model="file"
                                    x-on:change="fileName = $event.target.files[0].name" />
                            </label>
                            <p class="pl-1" x-show="!fileName">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500" x-show="!fileName">Excel or CSV files only</p>
                        
                        <div x-show="fileName" class="mt-2 flex items-center justify-center">
                            <button 
                                type="button" 
                                class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                x-on:click="fileName = ''; $wire.file = null">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Progress bar -->
                <div x-show="isUploading" class="mt-4">
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">
                                    Uploading
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-green-600">
                                    <span x-text="$wire.uploadProgress || 0"></span>%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-200">
                            <div 
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 transition-all duration-300"
                                :style="`width: ${$wire.uploadProgress || 0}%`">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @error('file') 
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            
            <div class="mt-4">
                <button 
                    type="button" 
                    wire:click="processImport" 
                    wire:loading.attr="disabled" 
                    wire:target="processImport"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <svg 
                        wire:loading.remove 
                        wire:target="processImport" 
                        class="mr-2 h-4 w-4" 
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <svg 
                        wire:loading 
                        wire:target="processImport" 
                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="processImport">Process Import</span>
                    <span wire:loading wire:target="processImport">Processing...</span>
                </button>
            </div>
        </div>
    </div>
</div> 