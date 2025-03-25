<!-- Academic Details Section -->
<div class="mb-6 bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="flex justify-between items-center p-4 bg-gray-50 border-b border-gray-100">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Academic Details
        </h3>
        <button 
            wire:click="$set('editAcademicDetails', {{ !$editAcademicDetails }})" 
            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $editAcademicDetails ? 'M6 18L18 6M6 6l12 12' : 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' }}" />
            </svg>
            {{ $editAcademicDetails ? 'Cancel' : 'Edit' }}
        </button>
    </div>
    <div class="p-5">
        @if ($editAcademicDetails)
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Class -->
                    <div>
                        <label for="my_class_id" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                        <select wire:model.live="my_class_id" id="my_class_id" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Class</option>
                            @foreach ($myClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('my_class_id') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'This field is required.' }}</p> @enderror
                    </div>

                    <!-- Section -->
                    <div>
                        <label for="section_id" class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                        <select wire:model.live="section_id" id="section_id" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'This field is required.' }}</p> @enderror
                    </div>

                    <!-- Year Admitted -->
                    <div>
                        <label for="year_admitted" class="block text-sm font-medium text-gray-700 mb-1">Year Admitted</label>
                        <input type="number" wire:model="year_admitted" id="year_admitted" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('year_admitted') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid year.' }}</p> @enderror
                    </div>

                    <!-- Dormitory -->
                    <div>
                        <label for="dorm_id" class="block text-sm font-medium text-gray-700 mb-1">Dormitory</label>
                        <select wire:model="dorm_id" id="dorm_id" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Dormitory</option>
                            @foreach ($dorms as $dorm)
                                <option value="{{ $dorm->id }}">{{ $dorm->name }}</option>
                            @endforeach
                        </select>
                        @error('dorm_id') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please select a valid dormitory.' }}</p> @enderror
                    </div>

                    <!-- UPI Number -->
                    <div>
                        <label for="upi_number" class="block text-sm font-medium text-gray-700 mb-1">UPI Number</label>
                        <input type="text" wire:model="upi_number" id="upi_number" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('upi_number') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid UPI number.' }}</p> @enderror
                    </div>

                    <!-- Admission Number -->
                    <div>
                        <label for="adm_no" class="block text-sm font-medium text-gray-700 mb-1">Admission Number</label>
                        <input type="text" wire:model="adm_no" id="adm_no" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('adm_no') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter a valid admission number.' }}</p> @enderror
                    </div>

                    <!-- KCPE Marks -->
                    <div>
                        <label for="kcpe" class="block text-sm font-medium text-gray-700 mb-1">KCPE Marks</label>
                        <input type="number" wire:model="kcpe" id="kcpe" 
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('kcpe') <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Please enter valid KCPE marks.' }}</p> @enderror
                    </div>
                </div>
                
                <div class="mt-6 flex items-center justify-start space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Academic Details
                    </button>
                    <button type="button" wire:click="$set('editAcademicDetails', false)" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cancel
                    </button>
                </div>
            </form>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Class</p>
                    <p class="text-sm text-gray-900">{{ $student->myClass->name ?? 'Not assigned' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Section</p>
                    <p class="text-sm text-gray-900">{{ $student->section->name ?? 'Not assigned' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Year Admitted</p>
                    <p class="text-sm text-gray-900">{{ $year_admitted ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Dormitory</p>
                    <p class="text-sm text-gray-900">{{ $student->dorm->name ?? 'Not assigned' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">UPI Number</p>
                    <p class="text-sm text-gray-900">{{ $upi_number ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Admission Number</p>
                    <p class="text-sm text-gray-900">{{ $adm_no ?: 'Not provided' }}</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-md">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">KCPE Marks</p>
                    <p class="text-sm text-gray-900">{{ $kcpe ?: 'Not provided' }}</p>
                </div>
            </div>
        @endif
    </div>
</div> 