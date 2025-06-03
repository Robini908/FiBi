<form wire:submit.prevent="store">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column - Basic Info -->
        <div class="space-y-4">
            <div>
                <label for="examName" class="block text-sm font-medium text-gray-700">Exam Name*</label>
                <input
                    type="text"
                    wire:model="examName"
                    id="examName"
                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    placeholder="End of Term Exam"
                >
                @error('examName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="examTerm" class="block text-sm font-medium text-gray-700">Term*</label>
                <select
                    wire:model="examTerm"
                    id="examTerm"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                >
                    <option value="">Select Term</option>
                    @foreach($terms as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('examTerm') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="examYear" class="block text-sm font-medium text-gray-700">Year*</label>
                <input
                    type="number"
                    wire:model="examYear"
                    id="examYear"
                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    placeholder="{{ date('Y') }}"
                >
                @error('examYear') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="gradingSystemId" class="block text-sm font-medium text-gray-700">Grading System</label>
                <div class="flex items-center space-x-2">
                    <select
                        wire:model="gradingSystemId"
                        id="gradingSystemId"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                    >
                        <option value="">Select Grading System</option>
                        @foreach($gradingSystems as $gs)
                            <option value="{{ $gs->id }}">{{ $gs->name }}</option>
                        @endforeach
                    </select>
                    <button
                        type="button"
                        wire:click="showGradingSystemForm"
                        class="inline-flex items-center p-1.5 border border-transparent rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                        title="Add New Grading System"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>
                @error('gradingSystemId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Right Column - Class & Schedule -->
        <div class="space-y-4">
            <div>
                <label for="selectedClass" class="block text-sm font-medium text-gray-700">Class*</label>
                <select
                    wire:model="selectedClass"
                    id="selectedClass"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                >
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                @error('selectedClass') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
                <div class="mt-2 p-3 bg-gray-50 rounded-md max-h-32 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-3">
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
                            <div class="col-span-2 text-sm text-gray-500 py-2">
                                @if($selectedClass)
                                    No sections available for the selected class.
                                @else
                                    Select a class to see available sections.
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
                @error('selectedSections') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="exam_date" class="block text-sm font-medium text-gray-700">Exam Date</label>
                <input
                    type="date"
                    wire:model="exam_date"
                    id="exam_date"
                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                >
                @error('exam_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
                    @error('start_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700">Duration (min)</label>
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
                    @error('duration') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time <span class="text-xs text-gray-500">(calculated)</span></label>
                <input
                    type="time"
                    wire:model="end_time"
                    id="end_time"
                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                >
                @error('end_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="mt-6">
        <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions</label>
        <textarea
            wire:model="instructions"
            id="instructions"
            rows="3"
            class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            placeholder="Enter any special instructions for this exam..."
        ></textarea>
    </div>

    <!-- Footer/Buttons -->
    <div class="mt-6 pt-5 border-t border-gray-200 flex justify-end space-x-3">
        <button
            type="button"
            wire:click="resetForm"
            class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors duration-200"
        >
            Cancel
        </button>
        <button
            type="submit"
            class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors duration-200"
        >
            {{ $isEditing ? 'Update Exam' : 'Create Exam' }}
        </button>
    </div>
</form> 