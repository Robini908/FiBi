<div class="bg-white border-t border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">
                Entries for {{ $selectedClass->name }}
            </h3>
            <button 
                wire:click="$set('viewEntriesMode', false)" 
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Close
            </button>
        </div>
    </div>
    
    <div class="px-6 py-4">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Total Students & Gender Balance -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-medium text-gray-900">Total Students & Gender Balance</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-500">Total Students</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $studentsCount }}
                        </span>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Male Students -->
                        <div class="border border-gray-200 rounded-md p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-gray-900">Male Students</h4>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $genderBalance['male'] }}
                                </span>
                            </div>
                            <div class="mt-3 max-h-32 overflow-y-auto text-xs text-gray-600 bg-gray-50 rounded-md p-3">
                                {{ $genderBalance['male_names'] ?: 'No male students in this class.' }}
                            </div>
                        </div>
                        
                        <!-- Female Students -->
                        <div class="border border-gray-200 rounded-md p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-pink-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-gray-900">Female Students</h4>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    {{ $genderBalance['female'] }}
                                </span>
                            </div>
                            <div class="mt-3 max-h-32 overflow-y-auto text-xs text-gray-600 bg-gray-50 rounded-md p-3">
                                {{ $genderBalance['female_names'] ?: 'No female students in this class.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Sharing Same Parent (Siblings) -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-medium text-gray-900">Parents with Multiple Students</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4">
                        @forelse($studentsByParent as $parent)
                            <div class="border border-gray-200 rounded-md p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $parent['parent_name'] }}</h4>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <ul class="divide-y divide-gray-200">
                                        @foreach($parent['students'] as $student)
                                            <li class="py-2">
                                                <div class="flex justify-between">
                                                    <span class="text-sm font-medium text-gray-900">{{ $student['name'] }}</span>
                                                    <span class="text-sm text-gray-500">{{ $student['year_admitted'] }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500">{{ $student['status'] }}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No parents with multiple students</h3>
                                <p class="mt-1 text-sm text-gray-500">There are no parents with multiple students in this class.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 