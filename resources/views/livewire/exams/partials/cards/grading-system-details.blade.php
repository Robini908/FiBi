<div class="bg-white rounded-lg overflow-hidden">
    <!-- Basic Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="text-base font-medium text-gray-900 mb-3">System Information</h4>
            <div class="bg-gray-50 p-4 rounded-md space-y-3">
                <div>
                    <span class="text-xs text-gray-500 block">Name:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $selectedGradingSystem->name }}</span>
                </div>
                
                <div>
                    <span class="text-xs text-gray-500 block">Description:</span>
                    @if($selectedGradingSystem->description)
                        <p class="text-sm text-gray-700">{{ $selectedGradingSystem->description }}</p>
                    @else
                        <span class="text-sm italic text-gray-500">No description provided</span>
                    @endif
                </div>
                
                <div>
                    <span class="text-xs text-gray-500 block">Effective Date:</span>
                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($selectedGradingSystem->effective_date)->format('d M, Y') }}</span>
                </div>
                
                <div>
                    <span class="text-xs text-gray-500 block">Created:</span>
                    <span class="text-sm text-gray-900">{{ $selectedGradingSystem->created_at->format('d M, Y H:i') }}</span>
                </div>

                <div>
                    <span class="text-xs text-gray-500 block">Last Updated:</span>
                    <span class="text-sm text-gray-900">{{ $selectedGradingSystem->updated_at->format('d M, Y H:i') }}</span>
                </div>
                
                <!-- Usage Statistics -->
                @php
                    $examsUsingSystem = $selectedGradingSystem->exams ? $selectedGradingSystem->exams->count() : 0;
                @endphp
                <div>
                    <span class="text-xs text-gray-500 block">Used in:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $examsUsingSystem > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $examsUsingSystem }} {{ Str::plural('exam', $examsUsingSystem) }}
                    </span>
                </div>
                
                <!-- Subject Associations -->
                <div>
                    <span class="text-xs text-gray-500 block">Applied to Subjects:</span>
                    <div class="mt-1">
                        @if($selectedGradingSystem->subjects && $selectedGradingSystem->subjects->count() > 0)
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($selectedGradingSystem->subjects as $subject)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $subject->subject_name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm italic text-gray-500">All subjects (Default)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h4 class="text-base font-medium text-gray-900 mb-3">Grade Ranges</h4>
            <div class="bg-gray-50 p-4 rounded-md">
                @if($selectedGradingSystem->gradingRanges && $selectedGradingSystem->gradingRanges->count() > 0)
                    <!-- Visual Scale -->
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Grade Scale</h5>
                        <div class="h-8 w-full bg-gray-200 rounded-md relative overflow-hidden">
                            @foreach($selectedGradingSystem->gradingRanges->sortBy('min_score') as $range)
                                @php
                                    $startPercent = max(0, min(100, $range->min_score));
                                    $endPercent = max(0, min(100, $range->max_score));
                                    $width = $endPercent - $startPercent;
                                    $left = $startPercent;
                                    
                                    // Generate a color based on the score range
                                    $hue = 120 - ($startPercent * 1.2); // 120 (green) for high scores, 0 (red) for low scores
                                    $color = "hsla($hue, 70%, 50%, 0.7)";
                                @endphp
                                
                                <div class="absolute h-full flex items-center justify-center text-xs font-bold text-white overflow-hidden" 
                                     style="left: {{ $left }}%; width: {{ $width }}%; background-color: {{ $color }};">
                                    @if($width > 5)
                                        {{ $range->grade }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Grade</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Min Score (%)</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Max Score (%)</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Remark</th>
                                    @if($selectedGradingSystem->gradingRanges->first() && !is_null($selectedGradingSystem->gradingRanges->first()->gpa))
                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">GPA</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($selectedGradingSystem->gradingRanges->sortBy('min_score')->reverse() as $range)
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $range->grade }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{{ $range->min_score }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{{ $range->max_score }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{{ $range->remark ?: '-' }}</td>
                                        @if(!is_null($range->gpa))
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{{ $range->gpa }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No grade ranges have been defined for this system.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 pt-5 border-t border-gray-200 flex justify-end space-x-3">
        @if(Qs::isAdministratorOrTeacher())
            <button 
                wire:click="editGradingSystem({{ $selectedGradingSystem->id }})"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
            >
                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit
            </button>
            
            <button
                wire:click="confirmDeleteGradingSystem({{ $selectedGradingSystem->id }})"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                {{ $examsUsingSystem > 0 ? 'disabled title="Cannot delete grading systems in use"' : '' }}
            >
                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Delete
            </button>
        @endif
        
        <button
            wire:click="closeGradingSystemDetails"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
        >
            Close
        </button>
    </div>
</div>

<script>
    // Add keyboard shortcuts for the details view
    document.addEventListener('keydown', function(e) {
        // Escape key to close details
        if (e.key === 'Escape') {
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).closeGradingSystemDetails();
        }
        // Alt+E to edit
        if (e.altKey && e.key === 'e') {
            const editButton = document.querySelector('button[wire\\:click^="editGradingSystem"]');
            if (editButton && !editButton.disabled) {
                editButton.click();
            }
        }
    });
</script> 