<!-- Main Sidebar Container -->
<aside class="fixed inset-y-4 left-4 bg-white w-64 rounded-xl shadow-xl transition-transform duration-300 transform md:translate-x-0 z-50" 
       :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
       x-data="{ activeMenu: null }"
       x-cloak>
    
    <!-- Brand Logo -->
    <div class="flex items-center justify-between h-16 bg-green-700 px-4 rounded-t-xl">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <span class="text-white text-lg font-semibold">MBUKU ERP-1.0</span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-white hover:text-gray-200">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Sidebar Menu -->
    <div class="overflow-y-auto h-[calc(100vh-8rem)] rounded-b-xl">
        <nav class="mt-2 px-2 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="{{ Request::is('dashboard*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="{{ Request::is('dashboard*') ? 'text-green-500' : 'text-gray-400 group-hover:text-green-500' }} mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>
                    @if (Qs::isAdministrator())
                        Super Admin Dashboard
                    @elseif (Qs::isAdmin())
                        Admin Dashboard
                    @elseif (Qs::isTeacher())
                        Teacher Dashboard
                    @elseif (Qs::isParent())
                        Parent Dashboard
                    @else
                        Dashboard
                    @endif
                </span>
            </a>

            <!-- Registration Section -->
            @if (Qs::isAdministratorOrTeacher() || Qs::isParent() || Qs::isStudent())
            <div>
                <button @click="activeMenu = activeMenu === 'registration' ? null : 'registration'" 
                        class="text-gray-600 hover:bg-green-50 hover:text-green-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="flex-1">
                        @if (Qs::isParent())
                            Student Registration
                        @else
                            Registration
                        @endif
                    </span>
                    <svg class="text-gray-400 ml-3 h-5 w-5 transform group-hover:text-green-500 transition-colors ease-in-out duration-150" 
                         :class="{'rotate-90': activeMenu === 'registration'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'registration'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-4 space-y-1">
                    <a href="{{ Qs::isParent() || Qs::isStudent() ? route('parent.child-class') : route('classes.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['classes.index', 'classes.edit', 'parent.child.classes']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::isParent() || Qs::isStudent())
                                Class Information
                            @else
                                Classes
                            @endif
                        </span>
                    </a>

                    <a href="{{ Qs::isParent() || Qs::isStudent() ? route('parent.child-dorm') : route('dorms.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['dorms.index', 'dorms.edit']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::isParent())
                                Student Dormitory
                            @else
                                Dormitories
                            @endif
                        </span>
                    </a>

                    @if (Qs::isAdministrator() || Qs::isParent())
                    <a href="{{ route('students.manage-students') }}"
                       class="{{ Route::is('students.manage-students') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::isParent())
                                Student Admissions
                            @else
                                Admissions
                            @endif
                        </span>
                    </a>

                    <a href="{{ Qs::isParent() || Qs::isStudent() ? route('parent.child-transition-status') : route('students.promotions_demotions') }}"
                       class="{{ Route::is('students.promotions_demotions') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::isParent())
                                Student Promotions
                            @else
                                Promotions & Demotions
                            @endif
                        </span>
                    </a>

                    <a href="{{ Qs::isParent() || Qs::isStudent() ? route('parent.child-graduation') : route('students.graduation') }}"
                       class="{{ Route::is('students.graduation') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::isParent())
                                Student Graduation
                            @else
                                Graduation
                            @endif
                        </span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Staff Management Section -->
            @if (Qs::isAdministrator() || Qs::isAdmin())
            <div>
                <button @click="activeMenu = activeMenu === 'staff' ? null : 'staff'"
                        class="{{ Request::is('staff*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="{{ Request::is('staff*') ? 'text-green-500' : 'text-gray-400 group-hover:text-green-500' }} mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="flex-1">Staff Management</span>
                    <svg class="text-gray-400 ml-3 h-5 w-5 transform group-hover:text-green-500 transition-colors ease-in-out duration-150" 
                         :class="{'rotate-90': activeMenu === 'staff'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'staff'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-4 space-y-1">
                    <a href="{{ route('staff.index') }}"
                       class="{{ Route::is('staff.index') && !request()->query('status') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>All Staff</span>
                    </a>
                    
                    <a href="{{ route('staff.index') }}?status=active"
                       class="{{ Route::is('staff.index') && request()->query('status') == 'active' ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Active Staff</span>
                    </a>
                    
                    <a href="{{ route('staff.index') }}?status=inactive"
                       class="{{ Route::is('staff.index') && request()->query('status') == 'inactive' ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Inactive Staff</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Academics Section -->
            @if (Qs::isAcademicStaff() || Qs::isAdministrator() || Qs::isParent())
            <div>
                <button @click="activeMenu = activeMenu === 'academics' ? null : 'academics'"
                        class="text-gray-600 hover:bg-green-50 hover:text-green-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="flex-1">
                        @if (Qs::isParent())
                            Student Academics
                        @elseif (Qs::isStudent())
                            My Academics
                        @else
                            Academics
                        @endif
                    </span>
                    <svg class="text-gray-400 ml-3 h-5 w-5 transform group-hover:text-green-500 transition-colors ease-in-out duration-150" 
                         :class="{'rotate-90': activeMenu === 'academics'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'academics'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-4 space-y-1">
                    <a href="{{ route('subjects.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['subjects.index', 'subjects.edit']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::isParent())
                                Student Subjects
                            @elseif (Qs::isStudent())
                                My Subjects
                            @else
                                Subjects
                            @endif
                        </span>
                    </a>

                    <a href="{{ route('grading_system.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['grading_system.index', 'grading_system.edit']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Grading System</span>
                    </a>

                    <a href="{{ route('tt.manager') }}"
                       class="{{ Route::is('tt.manager') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Timetable Manager</span>
                    </a>

                    <a href="{{ Qs::isParent() ? route('parent.child-exams') : (Qs::isStudent() ? route('student.my-exams') : route('exams.set')) }}"
                       class="{{ in_array(Route::currentRouteName(), ['exams.set', 'parent.child-exams', 'student.my-exams']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Exam Management</span>
                    </a>

                    <a href="{{ Qs::isParent() ? route('parent.child-marks') : (Qs::isStudent() ? route('student.my-marks') : route('exams.assignExamMarks')) }}"
                       class="{{ in_array(Route::currentRouteName(), ['exams.assignExamMarks', 'parent.child-marks', 'student.my-marks']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Marks Allocation</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Finance Section -->
            @if (Qs::isAdministrator() || Qs::isAdmin() || Qs::isAccountant() || Qs::isParent())
            <div>
                <button @click="activeMenu = activeMenu === 'finance' ? null : 'finance'"
                        class="{{ Request::is('finance*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="{{ Request::is('finance*') ? 'text-green-500' : 'text-gray-400 group-hover:text-green-500' }} mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">
                        @if (Qs::isParent())
                            Student Finance
                        @elseif (Qs::isStudent())
                            My Finance
                        @else
                            Finance
                        @endif
                    </span>
                    <svg class="text-gray-400 ml-3 h-5 w-5 transform group-hover:text-green-500 transition-colors ease-in-out duration-150" 
                         :class="{'rotate-90': activeMenu === 'finance'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'finance'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-4 space-y-1">
                    
                    @if (Qs::isAdministrator() || Qs::isAdmin() || Qs::isAccountant())
                    <a href="{{ route('finance.dashboard') }}"
                       class="{{ Route::is('finance.dashboard') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Finance Dashboard</span>
                    </a>
                    
                    <a href="{{ route('finance.accounts') }}"
                       class="{{ Route::is('finance.accounts*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Finance Accounts</span>
                    </a>
                    
                    <a href="{{ route('finance.voteheads') }}"
                       class="{{ Route::is('finance.voteheads*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Voteheads</span>
                    </a>
                    
                    <a href="{{ route('finance.fee-allocations') }}"
                       class="{{ Route::is('finance.fee-allocations*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Fee Allocations</span>
                    </a>
                    
                    <a href="{{ route('finance.fee-structure') }}"
                       class="{{ Route::is('finance.fee-structure*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Fee Structure</span>
                    </a>
                    
                    <a href="{{ route('finance.payment-vouchers') }}"
                       class="{{ Route::is('finance.payment-vouchers*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Payment Vouchers</span>
                    </a>
                    @endif
                    
                    <a href="{{ Qs::isParent() ? route('finance.student-fee-payments.my-children') : route('finance.student-fee-payments') }}"
                       class="{{ in_array(Route::currentRouteName(), ['finance.student-fee-payments', 'finance.student-fee-payments.my-children']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if(Qs::isParent())
                                My Children's Payments
                            @elseif(Qs::isStudent())
                                My Fee Payments
                            @else
                                Student Fee Payments
                            @endif
                        </span>
                    </a>
                    
                    @if (Qs::isAdministrator() || Qs::isAdmin() || Qs::isAccountant())
                    <a href="{{ route('finance.student-arrears') }}"
                       class="{{ Route::is('finance.student-arrears*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Student Arrears</span>
                    </a>
                    @endif
                    
                    @if (Qs::isParent() || Qs::isStudent())
                    <a href="{{ Qs::isParent() ? route('finance.arrears.my-children') : route('finance.arrears.my-arrears') }}"
                       class="{{ in_array(Route::currentRouteName(), ['finance.arrears.my-children', 'finance.arrears.my-arrears']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if(Qs::isParent())
                                My Children's Arrears
                            @else
                                My Fee Arrears
                            @endif
                        </span>
                    </a>
                    @endif
                    
                    @if (Qs::isParent() || Qs::isStudent())
                    <a href="{{ Qs::isParent() ? route('finance.fee-structure.view-for-children') : route('finance.fee-structure.view-for-student') }}"
                       class="{{ in_array(Route::currentRouteName(), ['finance.fee-structure.view-for-children', 'finance.fee-structure.view-for-student']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if(Qs::isParent())
                                View Fee Structure
                            @else
                                My Fee Structure
                            @endif
                        </span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Settings -->
            @if (Qs::isAdministrativeStaff())
            <div>
                <button @click="activeMenu = activeMenu === 'settings' ? null : 'settings'"
                        class="text-gray-600 hover:bg-green-50 hover:text-green-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="flex-1">Settings</span>
                    <svg class="text-gray-400 ml-3 h-5 w-5 transform group-hover:text-green-500 transition-colors ease-in-out duration-150" 
                         :class="{'rotate-90': activeMenu === 'settings'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'settings'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-4 space-y-1">
                    @if (Qs::isAdministrator() || Qs::isAdmin())
                    <a href="{{ route('users.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Users</span>
                    </a>
                    @endif

                    <a href="{{ route('settings') }}"
                       class="{{ Route::is('settings') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>School Settings</span>
                    </a>

                    <a href="{{ route('my_account') }}"
                       class="{{ Route::is('my_account') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>My Account</span>
                    </a>
                </div>
            </div>
            @endif
        </nav>
    </div>
</aside>

<!-- Mobile menu backdrop -->
<div x-show="sidebarOpen" 
     class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden" 
     @click="sidebarOpen = false"
     x-cloak></div>