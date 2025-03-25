<!-- Entry Modal -->
<div
    x-data="{ show: @entangle('showEntryModal') }"
    x-show="show"
    x-cloak
    class="fixed inset-0 overflow-y-auto z-50"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    @php
        // Make sure section is available in the modal
        $sectionId = $section->id ?? null;
        $sectionName = $section->name ?? null;
        $className = $section->my_class->name ?? null;
        
        // Get period details for better visualization
        $selectedPeriodDetails = null;
        if (isset($selectedPeriodId) && !empty($periods)) {
            $periodsCollection = collect($periods);
            $selectedPeriodDetails = $periodsCollection->firstWhere('id', $selectedPeriodId);
        }
        
        // Calculate duration for visual indicators if period details exist
        $durationMinutes = 0;
        $periodStartTime = '';
        $periodEndTime = '';
        $durationClass = '';
        
        if ($selectedPeriodDetails) {
            $start = \Carbon\Carbon::parse($selectedPeriodDetails->start_time);
            $end = \Carbon\Carbon::parse($selectedPeriodDetails->end_time);
            $durationMinutes = $start->diffInMinutes($end);
            $periodStartTime = date('h:i A', strtotime($selectedPeriodDetails->start_time));
            $periodEndTime = date('h:i A', strtotime($selectedPeriodDetails->end_time));
            
            if ($durationMinutes > 60) {
                $durationClass = 'bg-amber-100 text-amber-800';
            } elseif ($durationMinutes < 15) {
                $durationClass = 'bg-blue-100 text-blue-800';
            } else {
                $durationClass = 'bg-green-100 text-green-800';
            }
        }
    @endphp
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity"
            aria-hidden="true"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button 
                    type="button" 
                    @click="show = false"
                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Period details at the top of the modal -->
            @if($selectedPeriodDetails)
            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 flex items-center">
                            <span class="mr-2 flex-shrink-0">
                                <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            {{ $selectedPeriodDetails->period_name }}
                        </h2>
                        <div class="mt-1 flex items-center space-x-2">
                            <span class="text-sm text-gray-600">{{ $periodStartTime }} - {{ $periodEndTime }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $durationClass }}">
                                {{ $durationMinutes }} min
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Day</div>
                        <div class="text-sm font-medium text-gray-900 capitalize">{{ $selectedDay }}</div>
                    </div>
                </div>
                
                <!-- Visual duration indicator -->
                <div class="mt-3 w-full bg-white rounded-full h-2 overflow-hidden">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(100, ($durationMinutes / 120) * 100) }}%"></div>
                </div>
            </div>
            @endif
            
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $entryForm['id'] ? 'Edit' : 'Add' }} Timetable Entry
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Fill in the details below to {{ $entryForm['id'] ? 'update' : 'create' }} a class for this time slot.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white px-4 pb-5 sm:px-6">
                <form wire:submit.prevent="saveEntry">
                    <div class="space-y-4">
                    <!-- Subject Selection -->
                        <div x-data="{ 
                            open: false, 
                            selected: '{{ $this->getSelectedSubjectName() }}' 
                        }" @click.away="open = false">
                            <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative">
                                <button 
                                    type="button" 
                                    @click="open = !open"
                                    class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                                    <span class="block truncate">
                                        <span x-show="!selected" x-cloak>Select a subject</span>
                                        <span x-show="selected" x-text="selected"></span>
                                    </span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                </span>
                            </button>
                                <div 
                                    x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                    x-cloak
                                >
                                    @foreach($subjects as $subject)
                                        <div 
                                            wire:click="$set('entryForm.subject_id', '{{ $subject->id }}')"
                                            @click="selected = '{{ $subject->subject_name }}'; open = false"
                                            class="cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-green-50 {{ $entryForm['subject_id'] == $subject->id ? 'bg-green-100 text-green-900' : 'text-gray-900' }}"
                                        >
                                            <span class="block truncate">
                                                {{ $subject->subject_name }}
                                            </span>
                                            @if($entryForm['subject_id'] == $subject->id)
                                                <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-green-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('entryForm.subject_id') 
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                            @enderror
                    </div>

                    <!-- Teacher Selection -->
                        <div x-data="{ 
                            open: false, 
                            selected: '{{ $this->getSelectedTeacherName() }}' 
                        }" @click.away="open = false">
                            <label for="teacher_id" class="block text-sm font-medium text-gray-700">Teacher</label>
                            <div class="mt-1 relative">
                                <button 
                                    type="button" 
                                    @click="open = !open"
                                    class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                                    <span class="block truncate">
                                        <span x-show="!selected" x-cloak>Select a teacher (optional)</span>
                                        <span x-show="selected" x-text="selected"></span>
                                    </span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                </span>
                            </button>
                                <div 
                                    x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                    x-cloak
                                >
                                    <div 
                                        wire:click="$set('entryForm.teacher_id', null)" 
                                        @click="selected = ''; open = false"
                                        class="cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-green-50 {{ $entryForm['teacher_id'] == null ? 'bg-green-100 text-green-900' : 'text-gray-900' }}"
                                    >
                                        <span class="block truncate">None</span>
                                        @if($entryForm['teacher_id'] == null)
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-green-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @foreach($teachers as $teacher)
                                        <div 
                                            wire:click="$set('entryForm.teacher_id', '{{ $teacher->id }}')"
                                            @click="selected = '{{ $teacher->name }}'; open = false"
                                            class="cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-green-50 {{ $entryForm['teacher_id'] == $teacher->id ? 'bg-green-100 text-green-900' : 'text-gray-900' }}"
                                        >
                                            <div class="flex flex-col">
                                                <span class="block truncate font-medium">
                                                {{ $teacher->name }}
                                            </span>
                                                <span class="text-xs text-gray-500">
                                                    @if(isset($section) && $section->teacher_id == $teacher->id)
                                                        <span class="text-green-600 font-medium">Section Teacher</span>
                                                    @elseif(isset($section) && $section->my_class && $section->my_class->teachers->contains('id', $teacher->id)) 
                                                        <span class="text-blue-600">Class Teacher</span>
                                                    @else
                                                        Teacher
                                                    @endif
                                                </span>
                                            </div>
                                            @if($entryForm['teacher_id'] == $teacher->id)
                                                <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-green-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('entryForm.teacher_id') 
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        <!-- Classroom -->
                        <div>
                            <label for="classroom" class="block text-sm font-medium text-gray-700">Classroom</label>
                            <div class="mt-1">
                                <input 
                                    type="text" 
                                    id="classroom" 
                                    wire:model="entryForm.classroom" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="e.g. Room 101"
                                >
                            </div>
                            @error('entryForm.classroom') 
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                            @enderror
                    </div>

                        <!-- Notes -->
                    <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <div class="mt-1">
                                <textarea 
                                    id="notes" 
                                    wire:model="entryForm.notes" 
                                    rows="3" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Optional notes about this class"
                                ></textarea>
                            </div>
                            @error('entryForm.notes') 
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button 
                            type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            {{ $entryForm['id'] ? 'Update' : 'Add' }} Entry
                        </button>
                        
                        @if($entryForm['id'])
                            <button 
                                type="button" 
                                wire:click="deleteEntry"
                                class="w-full inline-flex justify-center rounded-md border border-red-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                Delete
                            </button>
                        @endif
                        
                        <button 
                            type="button" 
                            @click="show = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 