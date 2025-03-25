<div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden">
    <!-- Card Header with Status Indicator -->
    <div class="relative">
        <!-- Status Bar - Red for indefinite, Yellow for temporary -->
        <div class="{{ $student->suspension_type === 'indefinite' ? 'bg-red-500' : 'bg-amber-500' }} h-1.5 w-full absolute top-0 left-0"></div>
        
        <div class="pt-1.5 px-5 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <!-- Student Photo/Avatar -->
                <div class="flex-shrink-0">
                    @if ($student->photo)
                        <img src="{{ asset($student->photo) }}" alt="{{ $student->first_name }}" 
                             class="h-12 w-12 rounded-full object-cover border-2 border-gray-100 shadow-sm">
                    @else
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center shadow-sm border-2 border-gray-100">
                            <span class="text-green-700 font-medium text-sm">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <!-- Student Info -->
                <div class="ml-3">
                    <h3 class="text-base font-medium text-gray-900 line-clamp-1">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h3>
                    <div class="flex items-center space-x-2 mt-0.5">
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $student->adm_no }}
                        </span>
                        <span class="text-xs {{ $student->suspension_type === 'indefinite' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }} px-2 py-0.5 rounded-full capitalize">
                            {{ $student->suspension_type }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Download Button -->
            <button wire:click="downloadStudentSuspension({{ $student->id }})" 
                    class="inline-flex items-center p-1.5 border border-gray-300 rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors shadow-sm"
                    title="Download Suspension PDF">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                </svg>
                <span class="sr-only">Download</span>
            </button>
        </div>
    </div>
    
    <!-- Divider -->
    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
    
    <!-- Card Body -->
    <div class="p-5">
        <!-- Suspension Info -->
        <div class="space-y-2.5">
            <!-- Reason -->
            <div>
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Reason for Suspension</div>
                <p class="text-sm text-gray-800 line-clamp-2">{{ $student->suspension_reason }}</p>
            </div>
            
            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Start Date</div>
                    <p class="text-sm text-gray-800 flex items-center">
                        <svg class="h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $student->suspension_date ? $student->suspension_date->format('M j, Y') : 'N/A' }}
                    </p>
                </div>
                
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">End Date</div>
                    <p class="text-sm text-gray-800 flex items-center">
                        <svg class="h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $student->suspension_end_date ? $student->suspension_end_date->format('M j, Y') : 'Indefinite' }}
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Suspension Progress -->
        @if($student->suspension_type === 'temporary' && $student->suspension_end_date)
            <div class="mt-5">
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Suspension Progress</span>
                    <span>
                        @php
                            $startDate = \Carbon\Carbon::parse($student->suspension_date);
                            $endDate = \Carbon\Carbon::parse($student->suspension_end_date);
                            $totalDays = $startDate->diffInDays($endDate);
                            $daysElapsed = $startDate->diffInDays(now());
                            $progress = $totalDays > 0 ? min(100, ($daysElapsed / $totalDays) * 100) : 0;
                        @endphp
                        {{ round($progress) }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <div class="text-xs text-gray-600">
                        <span class="font-medium">Elapsed:</span>
                        <span>{{ $daysElapsed }} days</span>
                    </div>
                    <div class="text-xs text-gray-600">
                        <span class="font-medium">Remaining:</span>
                        <span>{{ max(0, $totalDays - $daysElapsed) }} days</span>
                    </div>
                </div>
            </div>
        @else
            <!-- Indefinite suspension info -->
            <div class="mt-5 bg-red-50 rounded-md p-3">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-red-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-red-800">Indefinite Suspension</p>
                        <p class="text-xs text-red-700 mt-0.5">
                            Suspended for {{ \Carbon\Carbon::parse($student->suspension_date)->diffForHumans(null, true) }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-5 flex space-x-3">
            <button wire:click="reinstate({{ $student->id }})"
                    class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all shadow-sm">
                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Reinstate
                <span wire:loading wire:target="reinstate({{ $student->id }})" class="ml-1">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>

            @if($student->suspension_type === 'temporary')
                <button wire:click="extendSuspension({{ $student->id }})"
                        class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                    <svg class="mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Extend
                    <span wire:loading wire:target="extendSuspension({{ $student->id }})" class="ml-1">
                        <svg class="animate-spin h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            @endif
        </div>
    </div>
</div> 