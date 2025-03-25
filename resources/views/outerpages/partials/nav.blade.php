<div class="relative bg-white" x-data="{ open: false, flyoutMenu: false, flyoutMenuContent: '' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center border-b-2 border-gray-100 py-6 md:justify-start md:space-x-10">
            <div class="flex justify-start lg:w-0 lg:flex-1">
                <a href="{{ route('landing') }}">
                    <span class="sr-only">Mbuku ERP</span>
                    <div class="flex items-center">
                        <img class="h-8 w-auto sm:h-10" src="{{ asset('assets/pics/logo.png') }}" alt="Mbuku ERP">
                        <span class="ml-3 text-xl font-bold text-gray-900">Mbuku ERP</span>
                    </div>
                </a>
            </div>
            <div class="-mr-2 -my-2 md:hidden">
                <button @click="open = true" type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500">
                    <span class="sr-only">Open menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
            <nav class="hidden md:flex space-x-10">
                <a href="{{ route('landing') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">Home</a>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @mouseenter="open = true" @mouseleave="setTimeout(() => open = false, 300)" type="button" class="text-gray-500 group inline-flex items-center rounded-md bg-white text-base font-medium hover:text-gray-900 focus:outline-none">
                        <span>Solutions</span>
                        <svg :class="{ 'rotate-180': open, 'rotate-0': !open }" class="ml-2 h-5 w-5 text-gray-400 group-hover:text-gray-500 transition-transform duration-150 ease-in-out" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div 
                        x-show="open" 
                        @mouseleave="open = false"
                        @mouseenter="open = true"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute z-10 -ml-4 mt-3 w-screen max-w-md transform px-2 sm:px-0 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2"
                    >
                        <div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                            <div class="relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8">
                                <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                                    <svg class="h-6 w-6 flex-shrink-0 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                                    </svg>
                                    <div class="ml-4">
                                        <p class="text-base font-medium text-gray-900">Student Management</p>
                                        <p class="mt-1 text-sm text-gray-500">Comprehensive student record management, including admissions, attendance, and more.</p>
                                    </div>
                                </a>

                                <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                                    <svg class="h-6 w-6 flex-shrink-0 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                    </svg>
                                    <div class="ml-4">
                                        <p class="text-base font-medium text-gray-900">Exam Analysis</p>
                                        <p class="mt-1 text-sm text-gray-500">Detailed exam analytics with customizable reports and performance tracking.</p>
                                    </div>
                                </a>

                                <a href="#" class="-m-3 flex items-start rounded-lg p-3 hover:bg-gray-50">
                                    <svg class="h-6 w-6 flex-shrink-0 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                    </svg>
                                    <div class="ml-4">
                                        <p class="text-base font-medium text-gray-900">Finance Management</p>
                                        <p class="mt-1 text-sm text-gray-500">Manage fee collection, expenses, and budgeting with our finance module.</p>
                                    </div>
                                </a>
                            </div>
                            <div class="bg-gray-50 px-5 py-5 sm:px-8 sm:py-8">
                                <div>
                                    <h3 class="text-base font-medium text-gray-500">Recent Articles</h3>
                                    <ul role="list" class="mt-4 space-y-4">
                                        <li class="truncate text-base">
                                            <a href="#" class="font-medium text-gray-900 hover:text-gray-700">
                                                Improving Student Performance with Analytics
                                            </a>
                                        </li>
                                        <li class="truncate text-base">
                                            <a href="#" class="font-medium text-gray-900 hover:text-gray-700">
                                                Streamlining School Fee Collection Process
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-5 text-sm">
                                    <a href="#" class="font-medium text-green-600 hover:text-green-500">
                                        View all articles <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('pricing') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">Pricing</a>
                <a href="{{ route('demo') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">Book Demo</a>
                <a href="{{ route('contact') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">Contact</a>
            </nav>
            <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">
                <a href="{{ route('login') }}" class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900">
                    Sign in
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on mobile menu state. -->
    <div 
        x-show="open" 
        x-transition:enter="duration-200 ease-out"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="duration-100 ease-in"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute inset-x-0 top-0 origin-top-right transform p-2 transition md:hidden z-20"
    >
        <div class="divide-y-2 divide-gray-50 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
            <div class="px-5 pt-5 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <img class="h-8 w-auto" src="{{ asset('assets/pics/logo.png') }}" alt="Mbuku ERP">
                    </div>
                    <div class="-mr-2">
                        <button @click="open = false" type="button" class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500">
                            <span class="sr-only">Close menu</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mt-6">
                    <nav class="grid gap-y-8">
                        <a href="#" class="-m-3 flex items-center rounded-md p-3 hover:bg-gray-50">
                            <svg class="h-6 w-6 flex-shrink-0 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                            </svg>
                            <span class="ml-3 text-base font-medium text-gray-900">Student Management</span>
                        </a>

                        <a href="#" class="-m-3 flex items-center rounded-md p-3 hover:bg-gray-50">
                            <svg class="h-6 w-6 flex-shrink-0 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            <span class="ml-3 text-base font-medium text-gray-900">Exam Analysis</span>
                        </a>

                        <a href="#" class="-m-3 flex items-center rounded-md p-3 hover:bg-gray-50">
                            <svg class="h-6 w-6 flex-shrink-0 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                            <span class="ml-3 text-base font-medium text-gray-900">Finance Management</span>
                        </a>
                    </nav>
                </div>
            </div>
            <div class="space-y-6 py-6 px-5">
                <div class="grid grid-cols-2 gap-y-4 gap-x-8">
                    <a href="{{ route('landing') }}" class="text-base font-medium text-gray-900 hover:text-gray-700">Home</a>
                    <a href="{{ route('pricing') }}" class="text-base font-medium text-gray-900 hover:text-gray-700">Pricing</a>
                    <a href="{{ route('demo') }}" class="text-base font-medium text-gray-900 hover:text-gray-700">Book Demo</a>
                    <a href="{{ route('contact') }}" class="text-base font-medium text-gray-900 hover:text-gray-700">Contact</a>
                    <a href="#" class="text-base font-medium text-gray-900 hover:text-gray-700">Blog</a>
                </div>
                <div>
                    <p class="mt-6 text-center text-base font-medium text-gray-500">
                        Need an account?
                        <a href="{{ route('login') }}" class="text-green-600 hover:text-green-500">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> 