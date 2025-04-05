<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <!-- Header -->
            <div class="bg-green-50 px-4 py-3 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Grading System Details
                </h3>
                <button wire:click="closeGradingSystemDetails" type="button" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                @if($selectedGradingSystem)
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $selectedGradingSystem->name }}</h4>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Effective Date:</p>
                                <p class="font-medium">{{ date('F d, Y', strtotime($selectedGradingSystem->effective_date)) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Created By:</p>
                                <p class="font-medium">
                                    {{ $selectedGradingSystem->created_by ? \App\User::find($selectedGradingSystem->created_by)->name ?? 'Unknown' : 'System' }}
                                </p>
                            </div>
                        </div>
                        @if($selectedGradingSystem->description)
                            <div class="mt-4">
                                <p class="text-gray-500">Description:</p>
                                <p class="text-sm mt-1">{{ $selectedGradingSystem->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Grading Ranges -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-md font-medium text-gray-900">Grade Ranges</h4>
                            @if(Qs::isAdministratorOrTeacher())
                                <button class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-md hover:bg-green-200">
                                    Manage Grades
                                </button>
                            @endif
                        </div>
                        @if($selectedGradingSystem->gradingRanges->count() > 0)
                            <div class="bg-gray-50 rounded-md overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Range</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($selectedGradingSystem->gradingRanges as $range)
                                            <tr>
                                                <td class="px-6 py-2 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $range->grade }}</div>
                                                </td>
                                                <td class="px-6 py-2 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ $range->min_mark }} - {{ $range->max_mark }}</div>
                                                </td>
                                                <td class="px-6 py-2 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ $range->points }}</div>
                                                </td>
                                                <td class="px-6 py-2 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ $range->subject_id ? $range->subject->name : 'All Subjects' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-2 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ $range->remark }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-md text-gray-500 italic">
                                No grade ranges defined for this grading system.
                                @if(Qs::isAdministratorOrTeacher())
                                    <a href="#" class="block mt-2 text-green-600 hover:underline text-sm">
                                        Add grade ranges now
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Associated Exams -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-2">Associated Exams</h4>
                        
                        @php
                            $associatedExams = \App\Models\Exam::where('grading_system_id', $selectedGradingSystem->id)->get();
                        @endphp
                        
                        @if($associatedExams->count() > 0)
                            <div class="bg-gray-50 p-4 rounded-md">
                                <ul class="space-y-2">
                                    @foreach($associatedExams as $exam)
                                        <li class="flex justify-between items-center">
                                            <div>
                                                <button 
                                                    wire:click="showDetails({{ $exam->id }})" 
                                                    class="text-blue-600 hover:underline font-medium"
                                                >
                                                    {{ $exam->name }}
                                                </button>
                                                <span class="text-xs text-gray-500 ml-2">
                                                    {{ isset($terms) && isset($exam->term) && isset($terms[$exam->term]) ? $terms[$exam->term] : 'Unknown Term' }}, {{ $exam->year }}
                                                </span>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $exam->classes->count() }} {{ Str::plural('class', $exam->classes->count()) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-md text-gray-500 italic">
                                No exams are currently using this grading system.
                            </div>
                        @endif
                    </div>
                </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">Grading system details not available</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    wire:click="closeGradingSystemDetails" 
                    type="button" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div> 