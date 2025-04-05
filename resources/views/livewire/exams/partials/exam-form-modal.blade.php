<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <!-- Header -->
            <div class="bg-green-50 px-4 py-3 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $isEditing ? 'Edit Exam' : 'Create New Exam' }}
                </h3>
                <button wire:click="resetForm" type="button" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <form wire:submit.prevent="store">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Left Column - Basic Info -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Exam Name</label>
                                <input 
                                    type="text" 
                                    wire:model="name" 
                                    id="name" 
                                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="End of Term Exam"
                                >
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                                <select 
                                    wire:model="term" 
                                    id="term" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                                    <option value="">Select Term</option>
                                    @foreach($terms as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('term') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                                <input 
                                    type="number" 
                                    wire:model="year" 
                                    id="year" 
                                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="{{ date('Y') }}"
                                >
                                @error('year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="grading_system_id" class="block text-sm font-medium text-gray-700">Grading System</label>
                                <div class="flex items-center space-x-2">
                                    <select 
                                        wire:model="grading_system_id" 
                                        id="grading_system_id" 
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                    >
                                        <option value="">Select Grading System</option>
                                        @foreach($gradingSystems as $gs)
                                            <option value="{{ $gs->id }}">{{ $gs->name }}</option>
                                        @endforeach
                                    </select>
                                    <button 
                                        type="button"
                                        wire:click="showGradingSystemForm"
                                        class="inline-flex items-center p-1.5 border border-transparent rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>
                                </div>
                                @error('grading_system_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Right Column - Class & Schedule -->
                        <div class="space-y-4">
                            <div>
                                <label for="selectedClass" class="block text-sm font-medium text-gray-700">Class</label>
                                <select 
                                    wire:model="selectedClass" 
                                    id="selectedClass" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedClass') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <div class="flex justify-between">
                                    <label class="block text-sm font-medium text-gray-700">Sections</label>
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            id="selectAllSections" 
                                            wire:model="selectAllSections" 
                                            wire:click="toggleSelectAllSections"
                                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded"
                                        >
                                        <label for="selectAllSections" class="ml-2 text-sm text-gray-600">Select All</label>
                                    </div>
                                </div>
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    @forelse($sections as $section)
                                        <div class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                id="section-{{ $section->id }}" 
                                                value="{{ $section->id }}" 
                                                wire:model="selectedSections"
                                                class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded"
                                            >
                                            <label for="section-{{ $section->id }}" class="ml-2 text-sm text-gray-600">
                                                {{ $section->name }}
                                            </label>
                                        </div>
                                    @empty
                                        <div class="col-span-2 text-sm text-gray-500">
                                            @if($selectedClass)
                                                No sections available for the selected class.
                                            @else
                                                Select a class to see available sections.
                                            @endif
                                        </div>
                                    @endforelse
                                </div>
                                @error('selectedSections') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="exam_date" class="block text-sm font-medium text-gray-700">Exam Date</label>
                                <input 
                                    type="date" 
                                    wire:model="exam_date" 
                                    id="exam_date" 
                                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                >
                                @error('exam_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                                    <input 
                                        type="time" 
                                        wire:model="start_time" 
                                        wire:change="calculateEndTime"
                                        id="start_time" 
                                        class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    >
                                    @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="duration" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                    <input 
                                        type="number" 
                                        wire:model="duration" 
                                        wire:change="calculateEndTime"
                                        id="duration" 
                                        min="15"
                                        step="15"
                                        placeholder="e.g., 60, 90, 120"
                                        class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    >
                                </div>
                            </div>
                            
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time (calculated automatically)</label>
                                <input 
                                    type="time" 
                                    wire:model="end_time" 
                                    id="end_time" 
                                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                >
                                @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">End time is calculated based on start time and duration. You can manually adjust if needed.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-4">
                        <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions</label>
                        <textarea 
                            wire:model="instructions" 
                            id="instructions" 
                            rows="3" 
                            class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Enter any special instructions for this exam..."
                        ></textarea>
                    </div>
                </div>

                <!-- Footer/Buttons -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        type="submit" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        {{ $isEditing ? 'Update Exam' : 'Create Exam' }}
                    </button>
                    <button 
                        type="button" 
                        wire:click="resetForm" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 