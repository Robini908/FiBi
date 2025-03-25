<div x-show="showFormModal" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto" 
    style="display: none;">
    
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div 
            x-show="showFormModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full sm:p-6"
            @click.away="showFormModal = false">
            
            <!-- Modal content -->
            <div>
                <!-- Header -->
                <div class="flex items-start justify-between pb-3 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $feeStructureId ? 'Edit Fee Structure' : 'Add New Fee Structure' }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $feeStructureId ? 'Update this fee structure details' : 'Fill in the form to create a new fee structure' }}
                        </p>
                    </div>
                    <button @click="showFormModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form content -->
                <form wire:submit.prevent="{{ $feeStructureId ? 'updateFeeStructure' : 'saveFeeStructure' }}">
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <!-- Fee Name -->
                        <div class="col-span-2 sm:col-span-1">
                            <label for="fee_name" class="block text-sm font-medium text-gray-700">Fee Name*</label>
                            <div class="mt-1">
                                <input 
                                    type="text" 
                                    id="fee_name" 
                                    wire:model="fee_name" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md @error('fee_name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="E.g., Tuition Fee, Library Fee, etc.">
                            </div>
                            @error('fee_name') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="col-span-2 sm:col-span-1">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (KES)*</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">KES</span>
                                </div>
                                <input 
                                    type="number" 
                                    id="amount" 
                                    wire:model="amount" 
                                    step="0.01"
                                    min="0"
                                    class="pl-12 focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md @error('amount') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('amount') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="col-span-2 sm:col-span-1">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category*</label>
                            <div class="mt-1">
                                <select 
                                    id="category" 
                                    wire:model="category" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md @error('category') border-red-300 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Class -->
                        <div class="col-span-2 sm:col-span-1">
                            <label for="classroom_id" class="block text-sm font-medium text-gray-700">Class*</label>
                            <div class="mt-1">
                                <select 
                                    id="classroom_id" 
                                    wire:model="classroom_id" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md @error('classroom_id') border-red-300 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">All Classes</option>
                                    @foreach($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('classroom_id') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year -->
                        <div class="col-span-2 sm:col-span-1">
                            <label for="year" class="block text-sm font-medium text-gray-700">Academic Year*</label>
                            <div class="mt-1">
                                <select 
                                    id="year" 
                                    wire:model="year" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md @error('year') border-red-300 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Select Year</option>
                                    @foreach($years as $yearOption)
                                        <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('year') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Term -->
                        <div class="col-span-2 sm:col-span-1">
                            <label for="term" class="block text-sm font-medium text-gray-700">Term*</label>
                            <div class="mt-1">
                                <select 
                                    id="term" 
                                    wire:model="term" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md @error('term') border-red-300 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Select Term</option>
                                    <option value="1">Term 1</option>
                                    <option value="2">Term 2</option>
                                    <option value="3">Term 3</option>
                                </select>
                            </div>
                            @error('term') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <div class="mt-1">
                                <textarea 
                                    id="description" 
                                    wire:model="description" 
                                    rows="3" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Add optional details about this fee..."></textarea>
                            </div>
                            @error('description') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Options -->
                        <div class="col-span-2 flex flex-col sm:flex-row sm:space-x-6">
                            <!-- Is Active -->
                            <div class="flex items-start mb-2 sm:mb-0">
                                <div class="flex items-center h-5">
                                    <input 
                                        id="is_active" 
                                        wire:model="is_active" 
                                        type="checkbox" 
                                        class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_active" class="font-medium text-gray-700">Active</label>
                                    <p class="text-gray-500">Make this fee immediately available</p>
                                </div>
                            </div>

                            <!-- Is Mandatory -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input 
                                        id="is_mandatory" 
                                        wire:model="is_mandatory" 
                                        type="checkbox" 
                                        class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_mandatory" class="font-medium text-gray-700">Mandatory</label>
                                    <p class="text-gray-500">Required for all students in this class</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                        <button 
                            type="button"
                            @click="showFormModal = false"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            {{ $feeStructureId ? 'Update Fee' : 'Save Fee' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 