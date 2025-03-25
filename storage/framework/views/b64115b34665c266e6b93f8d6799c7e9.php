<!-- Student Details View -->
<div class="bg-white rounded-lg overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-800 flex items-center">
                <svg class="h-5 w-5 mr-2 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                Student Details
            </h3>
        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php if(isset($selectedStudent) && $selectedStudent): ?>
    <!-- Student Profile Section -->
    <div class="p-6">
        <div class="flex flex-col md:flex-row">
            <!-- Student Photo Column -->
            <div class="w-full md:w-1/3 flex flex-col items-center mb-6 md:mb-0">
                <div class="relative w-40 h-40 rounded-full overflow-hidden mb-4 border-4 border-gray-100 shadow-sm">
                    <!--[if BLOCK]><![endif]--><?php if($selectedStudent->photo): ?>
                        <img src="<?php echo e(asset($selectedStudent->photo)); ?>" alt="<?php echo e($selectedStudent->first_name); ?>" class="h-full w-full object-cover">
                    <?php else: ?>
                        <div class="h-full w-full bg-green-50 flex items-center justify-center">
                            <span class="text-4xl font-bold text-green-600"><?php echo e(substr($selectedStudent->first_name ?? '', 0, 1)); ?><?php echo e(substr($selectedStudent->last_name ?? '', 0, 1)); ?></span>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Status Indicator -->
                <!--[if BLOCK]><![endif]--><?php if($selectedStudent->is_suspended): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 mb-2">
                        <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Suspended
                    </span>
                <?php elseif($selectedStudent->is_expelled): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 mb-2">
                        <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Expelled
                    </span>
                <?php elseif($selectedStudent->is_graduated): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-2">
                        <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Graduated
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-2">
                        <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Active
                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <h2 class="text-xl font-semibold text-gray-800 text-center">
                    <?php echo e($selectedStudent->first_name); ?> <?php echo e($selectedStudent->middle_name); ?> <?php echo e($selectedStudent->last_name); ?>

                </h2>
                <p class="text-sm text-gray-500 text-center">Admission #: <?php echo e($selectedStudent->adm_no); ?></p>
                
                <!-- Quick Actions -->
                <div class="mt-4 w-full flex flex-col space-y-2">
                    <button 
                        wire:click="editStudent(<?php echo e($selectedStudent->id); ?>)"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-colors"
                    >
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Profile
                    </button>
                    
                    <button 
                        wire:click="emailStudent(<?php echo e($selectedStudent->id); ?>)"
                        class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-colors"
                    >
                        <svg class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send Email
                    </button>
                </div>
            </div>
            
            <!-- Student Info Column -->
            <div class="w-full md:w-2/3 md:pl-8">
                <!-- Personal Information Section -->
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">
                        Personal Information
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Gender</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->gender); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date of Birth</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->dob ? date('d M, Y', strtotime($selectedStudent->dob)) : 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Age</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->dob ? \Carbon\Carbon::parse($selectedStudent->dob)->age . ' years' : 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Blood Group</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->bloodgroup->name ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone Number</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->phone ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->email ?? 'N/A'); ?></p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-sm text-gray-500">Address</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->address ?? $selectedStudent->town ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Academic Information Section -->
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">
                        Academic Information
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Class</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->my_class->name ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Section</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->section->name ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Admission Date</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->year_admitted ? date('d M, Y', strtotime($selectedStudent->year_admitted)) : 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">UPI Number</p>
                            <p class="text-sm font-medium"><?php echo e($selectedStudent->upi_number ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Parent/Guardian Information Section -->
                <div>
                    <h3 class="text-base font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">
                        Parent/Guardian Information
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Parent's Name</p>
                            <p class="text-sm font-medium">
                                <?php echo e(optional($selectedStudent->parent_detail)->parent_first_name ?? 'N/A'); ?>

                                <?php echo e(optional($selectedStudent->parent_detail)->parent_last_name ?? ''); ?>

                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">ID Number</p>
                            <p class="text-sm font-medium"><?php echo e(optional($selectedStudent->parent_detail)->parent_id_no ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone Number</p>
                            <p class="text-sm font-medium"><?php echo e(optional($selectedStudent->parent_detail)->parent_phone_number ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-sm font-medium"><?php echo e(optional($selectedStudent->parent_detail)->parent_email ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabs and Additional Information -->
    <div x-data="{ activeTab: 'attendance' }" class="px-6 pb-6">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button 
                    @click="activeTab = 'attendance'" 
                    :class="{ 'border-green-500 text-green-600': activeTab === 'attendance', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'attendance' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none"
                >
                    Attendance
                </button>
                <button 
                    @click="activeTab = 'fees'" 
                    :class="{ 'border-green-500 text-green-600': activeTab === 'fees', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'fees' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none"
                >
                    Fees & Payments
                </button>
                <button 
                    @click="activeTab = 'academic'" 
                    :class="{ 'border-green-500 text-green-600': activeTab === 'academic', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'academic' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none"
                >
                    Academic Records
                </button>
                <button 
                    @click="activeTab = 'discipline'" 
                    :class="{ 'border-green-500 text-green-600': activeTab === 'discipline', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'discipline' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none"
                >
                    Discipline Records
                </button>
            </nav>
        </div>
        
        <!-- Tab Content -->
        <div class="mt-4">
            <!-- Attendance Tab -->
            <div x-show="activeTab === 'attendance'" class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500 text-sm">Attendance records will be displayed here.</p>
            </div>
            
            <!-- Fees Tab -->
            <div x-show="activeTab === 'fees'" class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500 text-sm">Fee payment history will be displayed here.</p>
            </div>
            
            <!-- Academic Records Tab -->
            <div x-show="activeTab === 'academic'" class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500 text-sm">Academic records and exam scores will be displayed here.</p>
            </div>
            
            <!-- Discipline Records Tab -->
            <div x-show="activeTab === 'discipline'" class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500 text-sm">Discipline records will be displayed here.</p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- No Student Selected -->
    <div class="p-6 text-center">
        <div class="flex flex-col items-center justify-center py-12">
            <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No student selected</h3>
            <p class="mt-1 text-sm text-gray-500">Please select a student from the list to view details.</p>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/student-details.blade.php ENDPATH**/ ?>