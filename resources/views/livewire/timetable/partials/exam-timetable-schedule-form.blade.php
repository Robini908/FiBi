<div class="fixed inset-0 bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
<div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            <div class="absolute right-0 top-0 pr-4 pt-4">
                <button type="button" wire:click="closeScheduleForm" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ $scheduleForm['id'] ? 'Edit Exam Schedule' : 'Add Exam Schedule' }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ $scheduleForm['id'] ? 'Update the exam schedule details.' : 'Create a new entry in the exam timetable.' }}
                    </p>
                </div>
            </div>
            
            <form wire:submit.prevent="saveSchedule" class="mt-6 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Subject Selection -->
                    <div class="col-span-2">
                        <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                        <select id="subject_id" wire:model.live="scheduleForm.subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                        @error('scheduleForm.subject_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Date Selection -->
                    <div>
                        <label for="exam_date" class="block text-sm font-medium text-gray-700">Exam Date</label>
                        <input type="date" id="exam_date" wire:model="scheduleForm.exam_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        @error('scheduleForm.exam_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Venue -->
                    <div>
                        <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
                        <input type="text" id="venue" wire:model="scheduleForm.venue" placeholder="e.g. Main Hall" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        @error('scheduleForm.venue') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="time" id="start_time" wire:model.live="scheduleForm.start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        @error('scheduleForm.start_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="time" id="end_time" wire:model="scheduleForm.end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        @error('scheduleForm.end_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <!-- Invigilators -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Invigilators</label>
                    <p class="text-xs text-gray-500 mb-2">Select teachers who will invigilate this exam</p>
                    
                    <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md p-2">
                        @if(count($invigilators) > 0)
                            @foreach($invigilators as $teacher)
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="teacher_{{ $teacher->id }}" value="{{ $teacher->id }}" wire:model="scheduleForm.invigilators" class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <label for="teacher_{{ $teacher->id }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $teacher->name }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500 py-2">No teachers available for this subject. Please select a subject first.</p>
                        @endif
                    </div>
                    @error('scheduleForm.invigilators') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Instructions -->
                <div>
                    <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions</label>
                    <textarea id="instructions" wire:model="scheduleForm.instructions" rows="3" placeholder="Any special instructions for this exam..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"></textarea>
                    @error('scheduleForm.instructions') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Form Actions -->
                <div class="mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm">
                        {{ $scheduleForm['id'] ? 'Update Schedule' : 'Save Schedule' }}
                    </button>
                    <button type="button" wire:click="closeScheduleForm" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 