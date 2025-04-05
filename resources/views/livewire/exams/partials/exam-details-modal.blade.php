<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <!-- Header -->
            <div class="bg-green-50 px-4 py-3 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Exam Details
                </h3>
                <button wire:click="closeExamDetails" type="button" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                @if($selectedExam)
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $selectedExam->name }}</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Term:</p>
                                <p class="font-medium">{{ isset($terms) && isset($selectedExam->term) && isset($terms[$selectedExam->term]) ? $terms[$selectedExam->term] : 'Unknown' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Year:</p>
                                <p class="font-medium">{{ $selectedExam->year }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Grading System:</p>
                                <p class="font-medium">
                                    @if($selectedExam->gradingSystem)
                                        <button 
                                            wire:click="showGradingSystemDetails({{ $selectedExam->grading_system_id }})" 
                                            class="text-green-600 hover:text-green-900 hover:underline"
                                        >
                                            {{ $selectedExam->gradingSystem->name }}
                                        </button>
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Classes and Sections -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-2">Classes and Sections</h4>
                        <div class="bg-gray-50 p-4 rounded-md">
                            @forelse($selectedExam->classes ?? collect() as $class)
                                <div class="mb-4">
                                    <h5 class="font-medium text-gray-800">{{ $class->name }}</h5>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($class->sections ?? collect() as $section)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $section->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 italic">No classes or sections assigned to this exam.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Schedule -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-2">Exam Schedule</h4>
                        
                        @if($selectedExam->examSchedules && $selectedExam->examSchedules->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class/Section</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($selectedExam->examSchedules as $schedule)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $schedule->myClass->name ?? 'N/A' }}
                                                    @if($schedule->section)
                                                        / {{ $schedule->section->name }}
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $schedule->subject->subject_name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($schedule->exam_date)
                                                        {{ date('M d, Y', strtotime($schedule->exam_date)) }}
                                                        @if($schedule->start_time && $schedule->end_time)
                                                            <br>
                                                            <span class="text-xs text-gray-500">
                                                                {{ date('h:i A', strtotime($schedule->start_time)) }} - 
                                                                {{ date('h:i A', strtotime($schedule->end_time)) }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        Not scheduled
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $schedule->venue ?? 'Not assigned' }}
                                                </td>
                                            </tr>
                                            @if($schedule->instructions)
                                                <tr>
                                                    <td colspan="4" class="px-6 py-2">
                                                        <div class="text-xs bg-yellow-50 p-2 rounded-md">
                                                            <span class="font-medium">Instructions:</span> {{ $schedule->instructions }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 italic">No schedule information available for this exam.</p>
                        @endif
                    </div>

                    <!-- Marks Summary (if available) -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-2">Marks Summary</h4>
                        @php
                            $marksCount = \App\Models\ExamMarks::where('exam_id', $selectedExam->id)->count();
                        @endphp
                        
                        @if($marksCount > 0)
                            <div class="bg-blue-50 p-4 rounded-md">
                                <p class="text-gray-700">
                                    <span class="font-medium">{{ $marksCount }}</span> marks entries found for this exam.
                                </p>
                                <a href="#" class="inline-block mt-2 text-blue-600 hover:underline text-sm">
                                    View detailed marks
                                </a>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-md text-gray-500 italic">
                                No marks entries found for this exam yet.
                                @if(Qs::isAdministratorOrTeacher())
                                    <a href="#" class="block mt-2 text-green-600 hover:underline text-sm">
                                        Assign marks now
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">Exam details not available</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                @if(Qs::isAdministratorOrTeacher() && $selectedExam)
                <button 
                    wire:click="edit({{ $selectedExam->id }})" 
                    type="button" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Edit Exam
                </button>
                @endif
                <button 
                    wire:click="closeExamDetails" 
                    type="button" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div> 