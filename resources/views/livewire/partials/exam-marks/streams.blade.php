@if ($selectedClass && $selectedExam && $selectedSubject)
    {{-- Streams Selection with Google Material Design 3 --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-6 py-4 rounded-t-lg">
            <div class="flex items-center space-x-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-full p-2">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-medium text-white">Select Stream</h2>
                    <p class="mt-1 text-sm text-white/80">Choose a stream to view and assign marks to students</p>
                    @if($selectedClassName)
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $selectedClassName }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            {{-- Streams Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($sections as $section)
                    <div wire:key="section-{{ $section->id }}" 
                         x-data="{ loading: false }"
                         class="relative">
                        <input type="radio" 
                               wire:model.live="selectedSection"
                               id="section-{{ $section->id }}" 
                               value="{{ $section->id }}"
                               @change="loading = true; setTimeout(() => loading = false, 1000)"
                               class="peer sr-only">
                        
                        <label for="section-{{ $section->id }}" 
                               class="flex p-4 bg-white border rounded-xl cursor-pointer
                                      transition-all duration-200 ease-in-out
                                      peer-checked:border-purple-500 peer-checked:ring-1 peer-checked:ring-purple-500
                                      hover:bg-gray-50
                                      border-gray-200">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-purple-600">{{ substr($section->name ?? 'S', 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">{{ $section->name ?? 'Unknown Section' }}</h3>
                                        <div class="flex items-center mt-1 space-x-2">
                                            <span class="text-xs text-gray-500">
                                                {{ $section->students_count ?? 0 }} Students
                                            </span>
                                            @if(isset($section->enrolled_count))
                                                <span class="text-xs px-1.5 py-0.5 rounded-full {{ $section->enrolled_count > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $section->enrolled_count }} Enrolled
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ml-4 relative">
                                    <div class="w-5 h-5 border-2 rounded-full border-gray-300 
                                         peer-checked:border-purple-500 peer-checked:bg-purple-500"></div>
                                    
                                    {{-- Loading Spinner (Only shows for clicked stream) --}}
                                    <div x-show="loading" 
                                         x-cloak
                                         class="absolute top-0 right-0 w-5 h-5">
                                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-purple-500 border-t-transparent"></div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 mb-4">
                            <svg class="w-6 h-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">No Streams Available</h3>
                        <p class="mt-1 text-sm text-gray-500">Please select a different class or ensure streams are configured.</p>
                    </div>
                @endforelse
            </div>

            {{-- Selected Stream Summary --}}
            @if($selectedSection)
                <div class="mt-6 bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-purple-900">Selected Stream</div>
                                <div class="text-sm text-purple-700">
                                    {{ $sections->where('id', $selectedSection)->first()->name ?? 'Selected Stream' }}
                                    @if($selectedClassName)
                                        <span class="text-purple-500">•</span>
                                        <span class="text-purple-700">{{ $selectedClassName }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button wire:click="$set('selectedSection', null)" 
                                class="inline-flex items-center px-3 py-1.5 border border-purple-300 shadow-sm text-sm font-medium rounded-lg text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500
                                       transition-colors duration-200">
                            Change
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-100 mb-4">
            <svg class="w-8 h-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">Complete Previous Step</h3>
        <p class="mt-2 text-md text-gray-500">Please select a class, exam, and subject to continue.</p>
        <div class="mt-6">
            <button @click="currentStep--" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back to First Step
            </button>
        </div>
    </div>
@endif 