<!-- Main Sidebar Container -->
<aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-100 shadow-sm overflow-y-auto transition-transform duration-300 transform md:translate-x-0 z-40"
       :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
       x-data="{ activeMenu: null }"
       x-cloak>

    <!-- Brand Logo -->
    <div class="flex items-center justify-between h-16 bg-gradient-to-r from-green-600 to-green-700 px-4">
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center space-x-2">
            <span class="text-white text-lg font-semibold tracking-wide">MBUKU ERP-1.0</span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-white hover:text-gray-200 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- User Profile Summary -->
    <?php if(Auth::check()): ?>
    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="relative w-10 h-10 overflow-hidden bg-gray-200 rounded-full ring-2 ring-white">
                    <img
                        class="h-full w-full object-cover"
                        src="<?php echo e(Auth::user()->photo ? asset(Auth::user()->photo) : asset('global_assets/images/user.png')); ?>"
                        alt="<?php echo e(Auth::user()->name); ?>"
                        onerror="this.src='<?php echo e(asset('global_assets/images/user.png')); ?>'"
                    >
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate"><?php echo e(Auth::user()->name); ?></p>
                <p class="text-xs text-gray-500 truncate">
                    <?php echo e(Auth::user()->roles->first()->name ?? 'User'); ?>

                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Sidebar Menu -->
    <div class="py-4 h-full">
        <nav class="px-3 space-y-1.5">
            <!-- Dashboard -->
            <a href="<?php echo e(route('dashboard')); ?>"
               class="<?php echo e(Request::is('dashboard*') ? 'bg-green-50 text-green-700 border-l-4 border-green-500' : 'text-gray-600 hover:bg-gray-50 hover:text-green-700'); ?> group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-150 <?php echo e(!Qs::isLibrarian() || Qs::isAdministrator() ? '' : 'hidden'); ?>">
                <svg class="<?php echo e(Request::is('dashboard*') ? 'text-green-500' : 'text-gray-400 group-hover:text-green-500'); ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="truncate">
                    <?php if(Qs::isAdministrator()): ?>
                        Super Admin Dashboard
                    <?php elseif(Qs::isAdmin()): ?>
                        Admin Dashboard
                    <?php elseif(Qs::isTeacher()): ?>
                        Teacher Dashboard
                    <?php elseif(Qs::isParent()): ?>
                        Parent Dashboard
                    <?php elseif(Qs::isLibrarian()): ?>
                        Librarian Dashboard
                    <?php else: ?>
                        Dashboard
                    <?php endif; ?>
                </span>
            </a>

            <!-- Wire Elements Modal Demo (Developer Tools) -->
            <a href="<?php echo e(route('modal.demo')); ?>"
               class="<?php echo e(Request::is('modal-demo*') ? 'bg-green-50 text-green-700 border-l-4 border-green-500' : 'text-gray-600 hover:bg-gray-50 hover:text-green-700'); ?> group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-150">
                <svg class="<?php echo e(Request::is('modal-demo*') ? 'text-green-500' : 'text-gray-400 group-hover:text-green-500'); ?> mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="truncate">Modal Demo</span>
            </a>

            <!-- Registration Section -->
            <?php if((Qs::isAdministratorOrTeacher() || Qs::isParent() || Qs::isStudent()) && !Qs::isLibrarian()): ?>
            <div class="py-1">
                <button @click="activeMenu = activeMenu === 'registration' ? null : 'registration'"
                        class="text-gray-600 hover:bg-gray-50 hover:text-green-700 group w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-150">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="flex-1 truncate">
                        <?php if(Qs::isParent()): ?>
                            Student Registration
                        <?php else: ?>
                            Registration
                        <?php endif; ?>
                    </span>
                    <svg class="text-gray-400 ml-3 h-4 w-4 transform group-hover:text-green-500 transition-colors ease-in-out duration-150"
                         :class="{'rotate-90': activeMenu === 'registration'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'registration'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-10 space-y-1">
                    <a href="<?php echo e(Qs::isParent() || Qs::isStudent() ? route('parent.child-class') : route('classes.index')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['classes.index', 'classes.edit', 'parent.child.classes']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent() || Qs::isStudent()): ?>
                                Class Information
                            <?php else: ?>
                                Classes
                            <?php endif; ?>
                        </span>
                    </a>

                    <a href="<?php echo e(Qs::isParent() || Qs::isStudent() ? route('parent.child-dorm') : route('dorms.index')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['dorms.index', 'dorms.edit']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                Student Dormitory
                            <?php else: ?>
                                Dormitories
                            <?php endif; ?>
                        </span>
                    </a>

                    <?php if(Qs::isAdministrator() || Qs::isParent()): ?>
                    <a href="<?php echo e(route('students.manage-students')); ?>"
                       class="<?php echo e(Route::is('students.manage-students') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                Student Admissions
                            <?php else: ?>
                                Admissions
                            <?php endif; ?>
                        </span>
                    </a>

                    <a href="<?php echo e(Qs::isParent() || Qs::isStudent() ? route('parent.child-transition-status') : route('students.promotions_demotions')); ?>"
                       class="<?php echo e(Route::is('students.promotions_demotions') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                Student Promotions
                            <?php else: ?>
                                Promotions & Demotions
                            <?php endif; ?>
                        </span>
                    </a>

                    <a href="<?php echo e(Qs::isParent() || Qs::isStudent() ? route('parent.child-graduation') : route('students.graduation')); ?>"
                       class="<?php echo e(Route::is('students.graduation') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                Student Graduation
                            <?php else: ?>
                                Graduation
                            <?php endif; ?>
                        </span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Staff Management Section -->
            <?php if(Qs::userIsAdmin() && !Qs::isLibrarian()): ?>
            <div class="py-1">
                <button @click="activeMenu = activeMenu === 'staff' ? null : 'staff'"
                        class="text-gray-600 hover:bg-gray-50 hover:text-green-700 group w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-150">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="flex-1 truncate">Staff Management</span>
                    <svg class="text-gray-400 ml-3 h-4 w-4 transform group-hover:text-green-500 transition-colors ease-in-out duration-150"
                         :class="{'rotate-90': activeMenu === 'staff'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'staff'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-10 space-y-1">
                    <a href="<?php echo e(route('staff.manage')); ?>" class="<?php echo e(Route::is('staff.manage') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Staff List</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Academics Section -->
            <?php if((Qs::isAcademicStaff() || Qs::isAdministrator() || Qs::isParent()) && !Qs::isLibrarian()): ?>
            <div class="py-1">
                <button @click="activeMenu = activeMenu === 'academics' ? null : 'academics'"
                        class="text-gray-600 hover:bg-gray-50 hover:text-green-700 group w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-150">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="flex-1 truncate">
                        <?php if(Qs::isParent()): ?>
                            Student Academics
                        <?php elseif(Qs::isStudent()): ?>
                            My Academics
                        <?php else: ?>
                            Academics
                        <?php endif; ?>
                    </span>
                    <svg class="text-gray-400 ml-3 h-4 w-4 transform group-hover:text-green-500 transition-colors ease-in-out duration-150"
                         :class="{'rotate-90': activeMenu === 'academics'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'academics'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-10 space-y-1">
                    <a href="<?php echo e(route('subjects.index')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['subjects.index', 'subjects.edit']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                Student Subjects
                            <?php elseif(Qs::isStudent()): ?>
                                My Subjects
                            <?php else: ?>
                                Subjects
                            <?php endif; ?>
                        </span>
                    </a>

                    <a href="<?php echo e(route('grading_system.index')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['grading_system.index', 'grading_system.edit']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Grading System</span>
                    </a>

                    <a href="<?php echo e(route('tt.manager')); ?>"
                       class="<?php echo e(Route::is('tt.manager') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Timetable Manager</span>
                    </a>

                    <a href="<?php echo e(Qs::isAdministratorOrTeacher() ? route('exams.modern-management') : (Qs::isParent() ? route('parent.child-exams') : route('student.my-exams'))); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['exams.modern-management', 'parent.child-exams', 'student.my-exams']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <svg class="inline-block w-4 h-4 mr-1 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Modern Exam System
                        </span>
                    </a>

                    <a href="<?php echo e(Qs::isParent() ? route('parent.child-marks') : (Qs::isStudent() ? route('student.my-marks') : route('exams.assignExamMarks'))); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['exams.assignExamMarks', 'parent.child-marks', 'student.my-marks']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Marks Allocation</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Attendance Section -->
            <?php if((Qs::isAcademicStaff() || Qs::isAdministrator() || Qs::isParent()) && !Qs::isLibrarian()): ?>
            <div class="py-1">
                <button @click="activeMenu = activeMenu === 'attendance' ? null : 'attendance'"
                        class="text-gray-600 hover:bg-gray-50 hover:text-green-700 group w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-150">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="flex-1 truncate">
                        <?php if(Qs::isParent()): ?>
                            Student Attendance
                        <?php elseif(Qs::isStudent()): ?>
                            My Attendance
                        <?php else: ?>
                            Attendance
                        <?php endif; ?>
                    </span>
                    <svg class="text-gray-400 ml-3 h-4 w-4 transform group-hover:text-green-500 transition-colors ease-in-out duration-150"
                         :class="{'rotate-90': activeMenu === 'attendance'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'attendance'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-10 space-y-1">
                    
                    <?php if(Qs::isTeacher() || Qs::isAdministrator()): ?>
                    <a href="<?php echo e(route('attendance.take')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['attendance.take', 'attendance.take.class']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Take Attendance</span>
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('attendance.view')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['attendance.view', 'attendance.view.class']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                View Student Attendance
                            <?php elseif(Qs::isStudent()): ?>
                                View My Attendance
                            <?php else: ?>
                                View Attendance
                            <?php endif; ?>
                        </span>
                    </a>
                    
                    <?php if(!Qs::isStudent()): ?>
                    <a href="<?php echo e(route('attendance.student.history')); ?>"
                       class="<?php echo e(Route::is('attendance.student.history') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                Attendance History
                            <?php else: ?>
                                Student Attendance History
                            <?php endif; ?>
                        </span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(Qs::isAdministrator()): ?>
                    <a href="<?php echo e(route('attendance.analytics')); ?>"
                       class="<?php echo e(Route::is('attendance.analytics') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Attendance Analytics</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Library Section for Staff -->
            <?php if(auth()->user()->hasAnyRole(['librarian', 'teacher', 'admin', 'superadmin'])): ?>
            <div x-data="{ open: <?php echo e(request()->routeIs('library.*') && !request()->routeIs('library.catalog') && !request()->routeIs('library.my-books') && !request()->routeIs('library.my-children-books') ? 'true' : 'false'); ?> }">
                <button @click="open = !open"
                    class="w-full flex items-center py-3 px-4 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:bg-gray-50"
                    :class="{ 'bg-gray-50 text-gray-900': open }">
                    <svg class="mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="flex-1 truncate">Library Management</span>
                    <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{ 'rotate-90': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="ml-4 pl-4 border-l border-gray-200 space-y-1">
                    <a href="<?php echo e(route('library.books')); ?>"
                       class="<?php echo e(request()->routeIs('library.books') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Books</span>
                    </a>

                    <?php if(auth()->user()->hasAnyRole(['librarian', 'admin', 'superadmin'])): ?>
                    <a href="<?php echo e(route('library.authors')); ?>"
                       class="<?php echo e(request()->routeIs('library.authors') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Authors</span>
                    </a>

                    <a href="<?php echo e(route('library.categories')); ?>"
                       class="<?php echo e(request()->routeIs('library.categories') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Categories</span>
                    </a>

                    <a href="<?php echo e(route('library.inventory')); ?>"
                       class="<?php echo e(request()->routeIs('library.inventory') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Inventory</span>
                    </a>

                    <a href="<?php echo e(route('library.loans')); ?>"
                       class="<?php echo e(request()->routeIs('library.loans') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Loans</span>
                    </a>

                    <a href="<?php echo e(route('library.book-requests')); ?>"
                       class="<?php echo e(request()->routeIs('library.book-requests') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Book Requests</span>
                    </a>

                    <a href="<?php echo e(route('library.reports')); ?>"
                       class="<?php echo e(request()->routeIs('library.reports') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Reports</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Student Library Access -->
            <?php if(auth()->user()->hasRole('student')): ?>
            <div x-data="{ open: <?php echo e(request()->routeIs('library.catalog') || request()->routeIs('library.my-books') ? 'true' : 'false'); ?> }">
                <button @click="open = !open"
                    class="w-full flex items-center py-3 px-4 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:bg-gray-50"
                    :class="{ 'bg-gray-50 text-gray-900': open }">
                    <svg class="mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="flex-1 truncate">Library</span>
                    <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{ 'rotate-90': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="ml-4 pl-4 border-l border-gray-200 space-y-1">
                    <a href="<?php echo e(route('library.catalog')); ?>"
                       class="<?php echo e(request()->routeIs('library.catalog') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Browse Books</span>
                    </a>
                    <a href="<?php echo e(route('library.my-books')); ?>"
                       class="<?php echo e(request()->routeIs('library.my-books') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">My Borrowed Books</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Parent Library Access -->
            <?php if(auth()->user()->hasRole('parent')): ?>
            <div x-data="{ open: <?php echo e(request()->routeIs('library.catalog') || request()->routeIs('library.my-children-books') ? 'true' : 'false'); ?> }">
                <button @click="open = !open"
                    class="w-full flex items-center py-3 px-4 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:bg-gray-50"
                    :class="{ 'bg-gray-50 text-gray-900': open }">
                    <svg class="mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="flex-1 truncate">Children's Library</span>
                    <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{ 'rotate-90': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="ml-4 pl-4 border-l border-gray-200 space-y-1">
                    <a href="<?php echo e(route('library.catalog')); ?>"
                       class="<?php echo e(request()->routeIs('library.catalog') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Browse Books</span>
                    </a>
                    <a href="<?php echo e(route('library.my-children-books')); ?>"
                       class="<?php echo e(request()->routeIs('library.my-children-books') ? 'bg-gray-50 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-900'); ?> group flex items-center py-2 px-3 text-sm rounded-md">
                        <span class="truncate">Children's Books</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Finance Section -->
            <?php if((Qs::isAdministrator() || Qs::isAdmin() || Qs::isAccountant() || Qs::isParent()) && !Qs::isLibrarian()): ?>
            <div class="py-1">
                <button @click="activeMenu = activeMenu === 'finance' ? null : 'finance'"
                        class="text-gray-600 hover:bg-gray-50 hover:text-green-700 group w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-150">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1 truncate">
                        <?php if(Qs::isParent()): ?>
                            Student Finance
                        <?php elseif(Qs::isStudent()): ?>
                            My Finance
                        <?php else: ?>
                            Finance
                        <?php endif; ?>
                    </span>
                    <svg class="text-gray-400 ml-3 h-4 w-4 transform group-hover:text-green-500 transition-colors ease-in-out duration-150"
                         :class="{'rotate-90': activeMenu === 'finance'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'finance'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-10 space-y-1">

                    <?php if(Qs::isAdministrativeStaff()): ?>
                    <a href="<?php echo e(route('finance.dashboard')); ?>"
                       class="<?php echo e(Route::is('finance.dashboard') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Finance Dashboard</span>
                    </a>

                    <a href="<?php echo e(route('finance.accounts')); ?>"
                       class="<?php echo e(Route::is('finance.accounts*') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Finance Accounts</span>
                    </a>

                    <a href="<?php echo e(route('finance.voteheads')); ?>"
                       class="<?php echo e(Route::is('finance.voteheads*') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Voteheads</span>
                    </a>

                    <a href="<?php echo e(route('finance.fee-allocations')); ?>"
                       class="<?php echo e(Route::is('finance.fee-allocations*') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Fee Allocations</span>
                    </a>

                    <a href="<?php echo e(route('finance.fee-structure')); ?>"
                       class="<?php echo e(Route::is('finance.fee-structure*') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Fee Structure</span>
                    </a>

                    <a href="<?php echo e(route('finance.payment-vouchers')); ?>"
                       class="<?php echo e(Route::is('finance.payment-vouchers*') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Payment Vouchers</span>
                    </a>
                    <?php endif; ?>

                    <?php if(Qs::isStudent()): ?>
                    <a href="<?php echo e(route('finance.fee-payment')); ?>"
                       class="<?php echo e(Route::is('finance.fee-payment*') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Make Payment</span>
                    </a>
                    <?php endif; ?>

                    <a href="<?php echo e(Qs::isParent() ? route('finance.student-fee-payments.my-children') : route('finance.student-fee-payments')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['finance.student-fee-payments', 'finance.student-fee-payments.my-children']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                My Children's Payments
                            <?php elseif(Qs::isStudent()): ?>
                                My Fee Payments
                            <?php else: ?>
                                Student Fee Payments
                            <?php endif; ?>
                        </span>
                    </a>

                    <?php if(Qs::isAdministrator() || Qs::isAdmin() || Qs::isAccountant()): ?>
                    <a href="<?php echo e(route('finance.student-arrears')); ?>"
                       class="<?php echo e(Route::is('finance.student-arrears*') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Student Arrears</span>
                    </a>
                    <?php endif; ?>

                    <?php if(Qs::isParent() || Qs::isStudent()): ?>
                    <a href="<?php echo e(Qs::isParent() ? route('finance.arrears.my-children') : route('finance.arrears.my-arrears')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['finance.arrears.my-children', 'finance.arrears.my-arrears']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                My Children's Arrears
                            <?php else: ?>
                                My Fee Arrears
                            <?php endif; ?>
                        </span>
                    </a>
                    <?php endif; ?>

                    <?php if(Qs::isParent() || Qs::isStudent()): ?>
                    <a href="<?php echo e(Qs::isParent() ? route('finance.fee-structure.view-for-children') : route('finance.fee-structure.view-for-student')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['finance.fee-structure.view-for-children', 'finance.fee-structure.view-for-student']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">
                            <?php if(Qs::isParent()): ?>
                                View Fee Structure
                            <?php else: ?>
                                My Fee Structure
                            <?php endif; ?>
                        </span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Settings -->
            <?php if(Qs::isAdministrativeStaff() && !Qs::isLibrarian()): ?>
            <div class="py-1 mt-2">
                <button @click="activeMenu = activeMenu === 'settings' ? null : 'settings'"
                        class="text-gray-600 hover:bg-gray-50 hover:text-green-700 group w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-150">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="flex-1 truncate">Settings</span>
                    <svg class="text-gray-400 ml-3 h-4 w-4 transform group-hover:text-green-500 transition-colors ease-in-out duration-150"
                         :class="{'rotate-90': activeMenu === 'settings'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'settings'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-10 space-y-1">
                    <?php if(Qs::isAdministrator() || Qs::isAdmin()): ?>
                    <a href="<?php echo e(route('users.index')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Users</span>
                    </a>

                    <a href="<?php echo e(route('staff.manage')); ?>"
                       class="<?php echo e(in_array(Route::currentRouteName(), ['staff.manage']) ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Staff Management</span>
                    </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('settings')); ?>"
                       class="<?php echo e(Route::is('settings') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">Settings</span>
                    </a>

                    <a href="<?php echo e(route('school.settings')); ?>"
                       class="<?php echo e(Route::is('school.settings') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">School Settings</span>
                    </a>

                    <a href="<?php echo e(route('my_account')); ?>"
                       class="<?php echo e(Route::is('my_account') ? 'text-green-700 font-medium' : 'text-gray-600 hover:text-green-700'); ?> group flex items-center py-2 text-sm rounded-md">
                        <span class="truncate">My Account</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Divider -->
            <div class="py-2 mt-2">
                <div class="h-px bg-gray-200"></div>

                <!-- Sign Out Link -->
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="pt-2">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full text-left text-gray-600 hover:bg-gray-50 hover:text-red-600 group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors duration-150">
                        <svg class="text-gray-400 group-hover:text-red-500 mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="truncate">Sign Out</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</aside>

<!-- Mobile menu backdrop -->
<div x-show="sidebarOpen"
     class="fixed inset-0 z-30 bg-gray-600 bg-opacity-75 md:hidden"
     @click="sidebarOpen = false"
     x-cloak></div><?php /**PATH C:\projects\MbukuErp\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>