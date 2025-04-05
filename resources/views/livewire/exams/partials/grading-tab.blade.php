<div class="overflow-hidden bg-white">
    <!-- Header section with create button -->
    <div class="bg-white px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Grading Systems</h3>
            <p class="mt-1 text-sm text-gray-500">Manage grading systems for exams and student assessment</p>
        </div>

        @if(Qs::isAdministratorOrTeacher())
        <div class="mt-4 sm:mt-0">
            <button wire:click="showGradingSystemForm" type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Grading System
            </button>
        </div>
        @endif
    </div>

    <!-- Grading systems list -->
    <div class="bg-white px-6 py-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($gradingSystems as $system)
                <div class="relative border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col">
                    <div class="px-4 py-5 bg-gray-50 border-b border-gray-200 sm:px-6 flex-grow-0">
                        <h3 class="text-lg font-medium text-gray-900">{{ $system->name }}</h3>
                    </div>
                    <div class="px-4 py-4 flex-grow">
                        <p class="text-sm text-gray-500 line-clamp-2 mb-2">
                            {{ $system->description ?: 'No description provided' }}
                        </p>
                        <div class="mt-2">
                            <span class="text-xs font-medium text-gray-500">Effective from:</span>
                            <span class="text-xs text-gray-900 ml-1">{{ \Carbon\Carbon::parse($system->effective_date)->format('d M, Y') }}</span>
                        </div>

                        @php
                            $gradesCount = isset($system->gradingRanges) ? $system->gradingRanges->count() : 0;
                        @endphp

                        <div class="mt-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-500">Grades defined:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gradesCount > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $gradesCount }}
                                </span>
                            </div>

                            @if($gradesCount > 0)
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach($system->gradingRanges->take(5)->sortBy('min_score')->reverse() as $range)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                            {{ $range->grade }}
                                        </span>
                                    @endforeach

                                    @if($gradesCount > 5)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            +{{ $gradesCount - 5 }} more
                                        </span>
                                    @endif
                                </div>
                            @else
                                <p class="text-xs text-yellow-600 mt-1">No grade ranges defined yet</p>
                            @endif
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex-grow-0">
                        <div class="flex justify-between items-center">
                            <button wire:click="showGradingSystemDetails({{ $system->id }})" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                View Details
                            </button>

                            <div class="text-xs text-gray-500">
                                Created: {{ $system->created_at->format('d M, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No grading systems found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new grading system.</p>

                    @if(Qs::isAdministratorOrTeacher())
                        <div class="mt-6">
                            <button wire:click="showGradingSystemForm" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Create First Grading System
                            </button>
                        </div>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>