<!-- Bulk Assignment Modal -->
<div
    x-data="{ 
        show: false,
        init() {
            // Listen for show/hide from Livewire
            $wire.$watch('showBulkAssignModal', value => {
                this.show = value;
            });
        }
    }"
    x-show="show"
    x-cloak
    @keydown.escape.window="$wire.set('showBulkAssignModal', false)"
    class="relative z-50"
>
    <!-- Fixed overlay backdrop -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        </div>
        
    <!-- Modal dialog centered vertically and horizontally -->
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <!-- Modal panel -->
            <div x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            
                <!-- Close Button -->
                <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block z-10">
                <button 
                    type="button" 
                        @click="$wire.set('showBulkAssignModal', false)"
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Bulk Assign Classes
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Assign a subject and teacher to multiple time slots at once.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white px-4 pb-5 sm:px-6">
                <form wire:submit.prevent="saveBulkAssign">
                    <div class="space-y-4">
                        <!-- Days Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Days
                            </label>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                @foreach($days as $day)
                                    <label class="inline-flex items-center">
                                        <input 
                                            type="checkbox" 
                                            wire:model.live="bulkAssignForm.days" 
                                            value="{{ $day }}" 
                                            class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                        >
                                        <span class="ml-2 text-sm text-gray-700">{{ ucfirst($day) }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('bulkAssignForm.days') 
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        <!-- Periods Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Periods
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-40 overflow-y-auto">
                                @php
                                    // Get only periods that can be assigned classes
                                    $assignablePeriods = $assignablePeriods;
                                @endphp
                                
                                @forelse($assignablePeriods as $period)
                                    <label class="inline-flex items-center">
                                        <input 
                                            type="checkbox" 
                                            wire:model.live="bulkAssignForm.period_ids" 
                                            value="{{ $period->id }}" 
                                            class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                        >
                                        <span class="ml-2 text-sm text-gray-700">{{ $period->period_name }}</span>
                                    </label>
                                @empty
                                    <div class="col-span-3 text-center text-sm text-gray-500 py-4">
                                        No assignable periods found
                                    </div>
                                @endforelse
                            </div>
                            @error('bulkAssignForm.period_ids') 
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        <!-- Subject Selection -->
                        <div x-data="{ open: false, selected: '' }" @click.away="open = false">
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
                                            wire:click="$set('bulkAssignForm.subject_id', '{{ $subject->id }}')" 
                                            @click="selected = '{{ $subject->subject_name }}'; open = false"
                                            class="cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-green-50 {{ $bulkAssignForm['subject_id'] == $subject->id ? 'bg-green-100 text-green-900' : 'text-gray-900' }}"
                                        >
                                            <span class="block truncate">
                                                {{ $subject->subject_name }}
                                            </span>
                                            @if($bulkAssignForm['subject_id'] == $subject->id)
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
                            @error('bulkAssignForm.subject_id') 
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        <!-- Teacher Selection -->
                        <div x-data="{ open: false, selected: '' }" @click.away="open = false">
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
                                        wire:click="$set('bulkAssignForm.teacher_id', null)" 
                                        @click="selected = ''; open = false"
                                        class="cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-green-50 {{ $bulkAssignForm['teacher_id'] == null ? 'bg-green-100 text-green-900' : 'text-gray-900' }}"
                                    >
                                        <span class="block truncate">None</span>
                                        @if($bulkAssignForm['teacher_id'] == null)
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-green-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @foreach($teachers as $teacher)
                                        <div 
                                            wire:click="$set('bulkAssignForm.teacher_id', '{{ $teacher->id }}')" 
                                            @click="selected = '{{ $teacher->name }}'; open = false"
                                            class="cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-green-50 {{ $bulkAssignForm['teacher_id'] == $teacher->id ? 'bg-green-100 text-green-900' : 'text-gray-900' }}"
                                        >
                                            <span class="block truncate">
                                                {{ $teacher->name }}
                                            </span>
                                            @if($bulkAssignForm['teacher_id'] == $teacher->id)
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
                            @error('bulkAssignForm.teacher_id') 
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
                                    wire:model="bulkAssignForm.classroom" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="e.g. Room 101"
                                >
                            </div>
                            @error('bulkAssignForm.classroom') 
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>
                    
                        <!-- Action Buttons at the bottom -->
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button 
                            type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm"
                        >
                                Bulk Assign
                        </button>
                        <button 
                            type="button" 
                                @click="$wire.set('showBulkAssignModal', false)"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div> 