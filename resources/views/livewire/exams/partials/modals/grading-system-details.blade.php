@if($showGradingSystemDetailsModal)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <!-- Header -->
            <div class="bg-green-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-green-100">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Grading System Details
                </h3>
                <button wire:click="closeGradingSystemDetails" type="button" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[calc(100vh-200px)] overflow-y-auto">
                @if($selectedGradingSystem)
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-3 pb-2 border-b">Basic Information</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Name</span>
                                    <span class="block mt-1 text-sm text-gray-900">{{ $selectedGradingSystem->name }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Effective Date</span>
                                    <span class="block mt-1 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($selectedGradingSystem->effective_date)->format('d M, Y') }}
                                    </span>
                                </div>
                                <div class="col-span-2">
                                    <span class="block text-sm font-medium text-gray-500">Description</span>
                                    <span class="block mt-1 text-sm text-gray-900">
                                        {{ $selectedGradingSystem->description ?: 'No description provided' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Grade Ranges -->
                        <div>
                            <div class="flex justify-between items-center mb-3 pb-2 border-b">
                                <h4 class="text-md font-semibold text-gray-900">Grade Ranges</h4>
                                @if(Qs::isAdministratorOrTeacher())
                                <button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-colors duration-200">
                                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Add Grade Range
                                </button>
                                @endif
                            </div>

                            @if(isset($selectedGradingSystem->gradingRanges) && $selectedGradingSystem->gradingRanges->count() > 0)
                                <div class="overflow-x-auto mt-2 rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Score</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Score</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                                @if(Qs::isAdministratorOrTeacher())
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($selectedGradingSystem->gradingRanges as $range)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $range->grade }}</td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $range->min_score }}</td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $range->max_score }}</td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $range->remark }}</td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    @if($range->subject)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $range->subject->subject_name }}
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-gray-500">All Subjects</span>
                                                    @endif
                                                </td>
                                                @if(Qs::isAdministratorOrTeacher())
                                                <td class="px-6 py-3 whitespace-nowrap text-center text-sm font-medium">
                                                    <div class="flex items-center justify-center space-x-3">
                                                        <button class="text-indigo-600 hover:text-indigo-900 focus:outline-none transition-colors duration-200">
                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                            </svg>
                                                        </button>

                                                        <button class="text-red-600 hover:text-red-900 focus:outline-none transition-colors duration-200">
                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-4 bg-gray-50 rounded-lg text-center mt-2">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No grade ranges defined</h3>
                                    <p class="mt-1 text-sm text-gray-500">This grading system does not have any grade ranges defined yet.</p>

                                    @if(Qs::isAdministratorOrTeacher())
                                    <div class="mt-4">
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Add First Grade Range
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Creation Information -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-3 pb-2 border-b">System Information</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Created</span>
                                    <span class="block mt-1 text-sm text-gray-900">{{ $selectedGradingSystem->created_at->format('d M, Y H:i A') }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Last Updated</span>
                                    <span class="block mt-1 text-sm text-gray-900">{{ $selectedGradingSystem->updated_at->format('d M, Y H:i A') }}</span>
                                </div>
                                @if($selectedGradingSystem->created_by)
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Created By</span>
                                    <span class="block mt-1 text-sm text-gray-900">
                                        @php
                                            $creator = \App\User::find($selectedGradingSystem->created_by);
                                        @endphp
                                        {{ $creator ? $creator->name : 'Unknown User' }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No grading system data available</h3>
                        <p class="mt-1 text-sm text-gray-500">Unable to load the selected grading system details.</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                <button wire:click="closeGradingSystemDetails" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:w-auto sm:text-sm transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endif