<div>
    <!-- Staff Header -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
        <div class="bg-gradient-to-r from-green-50 to-gray-50 h-32 w-full relative border-b border-gray-100">
            <!-- Status Badge -->
            <div class="absolute top-4 right-4">
                @if(isset($staff) && $staff && $staff->is_active)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Active
                    </span>
                @elseif(isset($staff) && $staff)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Inactive
                    </span>
                @endif
            </div>
            
            <!-- Profile Image -->
            <div class="absolute -bottom-12 left-6">
                <div class="h-24 w-24 rounded-full border-4 border-white bg-white overflow-hidden shadow-sm">
                    @if($user->photo)
                        <img src="{{ $user->photo }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center bg-gray-50 text-gray-400">
                            <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="pt-16 px-6 pb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h1>
                    <div class="flex flex-wrap items-center gap-4 mt-1">
                        <div class="flex items-center text-gray-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            {{ $user->email }}
                        </div>
                        <div class="flex items-center text-gray-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $user->phone ?: 'No phone number' }}
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap mt-2 gap-2">
                        @foreach($user->roles as $role)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-green-50 text-green-700">
                                {{ $role->name }}
                            </span>
                        @endforeach
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-50 text-gray-700">
                            Code: {{ $staff->code }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 md:mt-0 flex space-x-3">
                    @if($staff->is_active)
                        <button
                            type="button"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            wire:click="updateStaffStatus(false)"
                            wire:confirm="Are you sure you want to deactivate this staff member?"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Deactivate
                        </button>
                    @else
                        <button
                            type="button"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            wire:click="updateStaffStatus(true)"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Activate
                        </button>
                    @endif
                    
                    <button
                        type="button"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        x-data=""
                        x-on:click="Livewire.emit('openModal', 'staff.edit-staff-modal', {{ json_encode(['staff_id' => $staff_id]) }})"
                    >
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="border-b border-gray-100">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button 
                    wire:click="setActiveTab('profile')"
                    class="{{ $activeTab === 'profile' ? 'text-green-600 border-green-500' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center"
                >
                    <svg class="w-4 h-4 mr-2 {{ $activeTab === 'profile' ? 'text-green-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </button>
                <button 
                    wire:click="setActiveTab('qualifications')"
                    class="{{ $activeTab === 'qualifications' ? 'text-green-600 border-green-500' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center"
                >
                    <svg class="w-4 h-4 mr-2 {{ $activeTab === 'qualifications' ? 'text-green-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Qualifications
                </button>
                <button 
                    wire:click="setActiveTab('attendance')"
                    class="{{ $activeTab === 'attendance' ? 'text-green-600 border-green-500' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center"
                >
                    <svg class="w-4 h-4 mr-2 {{ $activeTab === 'attendance' ? 'text-green-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Attendance
                </button>
                <button 
                    wire:click="setActiveTab('payments')"
                    class="{{ $activeTab === 'payments' ? 'text-green-600 border-green-500' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center"
                >
                    <svg class="w-4 h-4 mr-2 {{ $activeTab === 'payments' ? 'text-green-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Payments
                </button>
            </nav>
        </div>
        
        <!-- Tab Content -->
        <div>
            <!-- Profile Tab -->
            @if($activeTab === 'profile')
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="bg-white rounded-md border border-gray-100">
                            <div class="border-b border-gray-100 px-4 py-3">
                                <h3 class="text-base font-medium text-gray-800">Personal Information</h3>
                            </div>
                            <div class="p-4">
                                <dl class="grid grid-cols-1 gap-y-3">
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Full name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->name }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst($user->gender) }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Date of birth</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->dob ? date('F j, Y', strtotime($user->dob)) : 'Not specified' }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Blood group</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->bloodGroup ? $user->bloodGroup->name : 'Not specified' }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">State of origin</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->state ? $user->state->name : 'Not specified' }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Email address</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Phone number</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->phone ?: 'Not specified' }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Alternative phone</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->phone2 ?: 'Not specified' }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->address ?: 'Not specified' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        
                        <!-- Employment Information -->
                        <div class="bg-white rounded-md border border-gray-100">
                            <div class="border-b border-gray-100 px-4 py-3">
                                <h3 class="text-base font-medium text-gray-800">Employment Information</h3>
                            </div>
                            <div class="p-4">
                                <dl class="grid grid-cols-1 gap-y-3">
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Staff ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $staff->code }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Role</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            @foreach($user->roles as $role)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 mr-1">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Employment date</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $staff->emp_date ? date('F j, Y', strtotime($staff->emp_date)) : 'Not specified' }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Qualification</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $staff->qualification ?: 'Not specified' }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Departments</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $staff->departments ?: 'Not specified' }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Experience</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $staff->experience ?: 'Not specified' }}</dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                            @if($staff->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Inactive
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($activeTab === 'qualifications')
                <div class="p-6">
                    <livewire:staff.staff-qualifications :staff_id="$staff_id" />
                </div>
            @elseif($activeTab === 'attendance')
                <div class="p-6">
                    <div class="flex flex-col items-center justify-center py-10 bg-gray-50 rounded-md border border-gray-100">
                        <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-600 font-medium">Attendance Management</span>
                        <p class="text-gray-500 text-sm mt-1">Staff attendance tracking will be available soon</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                Coming Soon
                            </span>
                        </div>
                    </div>
                </div>
            @elseif($activeTab === 'payments')
                <div class="p-6">
                    <div class="flex flex-col items-center justify-center py-10 bg-gray-50 rounded-md border border-gray-100">
                        <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-gray-600 font-medium">Payroll Management</span>
                        <p class="text-gray-500 text-sm mt-1">Staff payment processing will be available soon</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                Coming Soon
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div> 