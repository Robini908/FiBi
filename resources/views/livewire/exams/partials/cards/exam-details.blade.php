<div class="bg-white rounded-lg overflow-hidden">
    <!-- Basic Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="text-base font-medium text-gray-900 mb-3">Exam Information</h4>
            <div class="bg-gray-50 p-4 rounded-md space-y-3">
                <div>
                    <span class="text-xs text-gray-500 block">Name:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $selectedExam->name }}</span>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="text-xs text-gray-500 block">Term:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $terms[$selectedExam->term] ?? 'Term '.$selectedExam->term }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 block">Year:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $selectedExam->year }}</span>
                    </div>
                </div>

                <div>
                    <span class="text-xs text-gray-500 block">Grading System:</span>
                    @if($selectedExam->gradingSystem)
                        <button 
                            wire:click="showGradingSystemDetails({{ $selectedExam->grading_system_id }})" 
                            class="text-sm text-blue-600 hover:text-blue-800 hover:underline focus:outline-none"
                        >
                            {{ $selectedExam->gradingSystem->name }}
                        </button>
                    @else
                        <span class="text-sm italic text-gray-500">No grading system assigned</span>
                    @endif
                </div>
                
                <div>
                    <span class="text-xs text-gray-500 block">Created:</span>
                    <span class="text-sm text-gray-900">{{ $selectedExam->created_at->format('d M, Y H:i') }}</span>
                </div>

                <div>
                    <span class="text-xs text-gray-500 block">Last Updated:</span>
                    <span class="text-sm text-gray-900">{{ $selectedExam->updated_at->format('d M, Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div>
            <h4 class="text-base font-medium text-gray-900 mb-3">Schedule Information</h4>
            <div class="bg-gray-50 p-4 rounded-md space-y-3">
                <div>
                    <span class="text-xs text-gray-500 block">Exam Date:</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $selectedExam->exam_date ? date('d M, Y', strtotime($selectedExam->exam_date)) : 'Not specified' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="text-xs text-gray-500 block">Start Time:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $selectedExam->start_time ? date('h:i A', strtotime($selectedExam->start_time)) : 'Not specified' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 block">End Time:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $selectedExam->end_time ? date('h:i A', strtotime($selectedExam->end_time)) : 'Not specified' }}
                        </span>
                    </div>
                </div>

                <div>
                    <span class="text-xs text-gray-500 block">Classes & Sections:</span>
                    <div class="mt-2 space-y-2">
                        @php
                            // Group schedules by class
                            $groupedSchedules = $selectedExam->examSchedules ? $selectedExam->examSchedules->groupBy(fn($schedule) => $schedule->myClass->id ?? 'unknown') : collect([]);
                        @endphp

                        @forelse($groupedSchedules as $classId => $schedules)
                            @if($classId != 'unknown' && isset($schedules->first()->myClass))
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $schedules->first()->myClass->name }}
                                    </span>

                                    <!-- Show sections -->
                                    @php
                                        $sections = $schedules->pluck('section')->filter()->unique('id');
                                    @endphp

                                    @if($sections->count() > 0)
                                        <span class="text-xs text-gray-500">
                                            ({{ $sections->pluck('name')->join(', ') }})
                                        </span>
                                    @endif
                                </div>
                            @endif
                        @empty
                            <span class="text-sm italic text-gray-500">No classes assigned</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions Section -->
    <div class="mt-6">
        <h4 class="text-base font-medium text-gray-900 mb-3">Instructions</h4>
        <div class="bg-gray-50 p-4 rounded-md">
            @if($selectedExam->instructions)
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $selectedExam->instructions }}</p>
            @else
                <p class="text-sm italic text-gray-500">No special instructions provided</p>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 pt-5 border-t border-gray-200 flex justify-end space-x-3">
        @if(Qs::isAdministratorOrTeacher())
            <button 
                wire:click="edit({{ $selectedExam->id }})"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
            >
                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit Exam
            </button>
            
            <button
                wire:click="confirmDelete({{ $selectedExam->id }})"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
            >
                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Delete
            </button>
        @endif
        
        <button
            wire:click="closeDetails"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
        >
            Close
        </button>
    </div>
</div> 