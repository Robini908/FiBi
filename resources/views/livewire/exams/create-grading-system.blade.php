<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Basic Details -->
        <div class="bg-white rounded-md shadow-sm p-4 mb-5">
            <h4 class="text-md font-medium text-gray-900 mb-4">Basic Information</h4>
            
            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    wire:model.blur="name" 
                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                >
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea 
                    id="description" 
                    wire:model.blur="description" 
                    rows="3" 
                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                ></textarea>
                @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <!-- Status -->
            <div class="mb-4">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="is_active" 
                        wire:model.blur="is_active" 
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                    >
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
                @error('is_active') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <!-- Grade Ranges -->
        <div class="bg-white rounded-md shadow-sm p-4">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-md font-medium text-gray-900">Grade Ranges</h4>
                <button 
                    type="button" 
                    wire:click="addGradeRange" 
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Grade
                </button>
            </div>
            
            @error('gradeRanges') <span class="text-red-500 text-xs block mb-2">{{ $message }}</span> @enderror
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Mark (%)</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Mark (%)</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($gradeRanges as $index => $range)
                        <tr>
                            <!-- Grade -->
                            <td class="px-4 py-2 whitespace-nowrap">
                                <input 
                                    type="text" 
                                    wire:model.defer="gradeRanges.{{ $index }}.grade" 
                                    class="w-20 focus:ring-green-500 focus:border-green-500 shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="A"
                                >
                                @error("gradeRanges.{$index}.grade") <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                            </td>
                            
                            <!-- Min Mark -->
                            <td class="px-4 py-2 whitespace-nowrap">
                                <input 
                                    type="number" 
                                    min="0" 
                                    max="100" 
                                    step="0.01" 
                                    wire:model.defer="gradeRanges.{{ $index }}.min_mark" 
                                    class="w-24 focus:ring-green-500 focus:border-green-500 shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="70"
                                >
                                @error("gradeRanges.{$index}.min_mark") <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                            </td>
                            
                            <!-- Max Mark -->
                            <td class="px-4 py-2 whitespace-nowrap">
                                <input 
                                    type="number" 
                                    min="0" 
                                    max="100" 
                                    step="0.01" 
                                    wire:model.defer="gradeRanges.{{ $index }}.max_mark" 
                                    class="w-24 focus:ring-green-500 focus:border-green-500 shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="100"
                                >
                                @error("gradeRanges.{$index}.max_mark") <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                            </td>
                            
                            <!-- Description -->
                            <td class="px-4 py-2 whitespace-nowrap">
                                <input 
                                    type="text" 
                                    wire:model.defer="gradeRanges.{{ $index }}.description" 
                                    class="w-full focus:ring-green-500 focus:border-green-500 shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Excellent"
                                >
                                @error("gradeRanges.{$index}.description") <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-4 py-2 whitespace-nowrap text-right">
                                <button 
                                    type="button" 
                                    wire:click="removeGradeRange({{ $index }})" 
                                    class="text-red-600 hover:text-red-900 {{ count($gradeRanges) <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ count($gradeRanges) <= 1 ? 'disabled' : '' }}
                                >
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end pt-5">
            <button 
                type="submit" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="save">Create Grading System</span>
                <span wire:loading wire:target="save" class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating...
                </span>
            </button>
        </div>
    </form>
</div> 