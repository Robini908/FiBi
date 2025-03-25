<div class="bg-white py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Pricing</h2>
            <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-5xl">Simple, transparent pricing</p>
            <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">Choose the right pricing plan for your school's needs. All plans include installation and training.</p>
        </div>

        <div class="mt-12 sm:mt-16 relative" x-data="{ annual: true }">
            <!-- Toggle -->
            <div class="max-w-lg mx-auto mb-12 flex justify-center">
                <div class="relative bg-gray-100 p-1 flex rounded-full">
                    <button @click="annual = false" :class="{ 'bg-white shadow-sm': !annual, 'text-gray-500': annual }" class="relative py-2 px-6 rounded-full text-sm font-medium whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-blue-500 focus:z-10 transition-colors duration-200">
                        Monthly
                    </button>
                    <button @click="annual = true" :class="{ 'bg-white shadow-sm': annual, 'text-gray-500': !annual }" class="ml-1 relative py-2 px-6 rounded-full text-sm font-medium whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-blue-500 focus:z-10 transition-colors duration-200">
                        Annual <span class="text-blue-500 font-medium">Save 20%</span>
                    </button>
                </div>
            </div>

            <!-- Pricing cards -->
            <div class="space-y-12 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-x-8">
                <!-- Silver Plan -->
                <div class="relative p-8 bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">Silver</h3>
                        <p class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight" x-text="annual ? 'Kshs 120,000' : 'Kshs 12,500'"></span>
                            <span class="ml-1 text-xl font-semibold" x-text="annual ? '/year' : '/month'"></span>
                        </p>
                        <p class="mt-6 text-gray-500">Perfect for small schools with basic management needs.</p>

                        <!-- Feature list -->
                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Student Registration</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Exams Management</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Basic Fee Collection</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Standard Support (Email)</span>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('demo') }}" class="mt-8 block w-full bg-white border border-gray-300 rounded-lg py-3 px-6 text-center font-medium text-blue-600 hover:bg-gray-50 hover:border-blue-600 transition-colors duration-200">Book a Demo</a>
                </div>

                <!-- Gold Plan -->
                <div class="relative p-8 bg-blue-50 border border-blue-200 rounded-2xl shadow-sm flex flex-col">
                    <div class="absolute -top-5 inset-x-0 flex justify-center">
                        <span class="inline-flex rounded-full bg-blue-600 px-4 py-1 text-xs font-semibold text-white">Most Popular</span>
                    </div>

                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">Gold</h3>
                        <div class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight" x-text="annual ? 'Kshs 240,000' : 'Kshs 25,000'"></span>
                            <span class="ml-1 text-xl font-semibold" x-text="annual ? '/year' : '/month'"></span>
                        </div>
                        <p class="mt-6 text-gray-500">Ideal for medium-sized schools with comprehensive needs.</p>

                        <!-- Feature list -->
                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">All Silver features</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Advanced Finance Module</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Human Resources</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Library Management</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Priority Support (Email & Phone)</span>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('demo') }}" class="mt-8 block w-full bg-blue-600 border border-transparent rounded-lg py-3 px-6 text-center font-medium text-white hover:bg-blue-700 transition-colors duration-200 shadow-sm">Book a Demo</a>
                </div>

                <!-- Platinum Plan -->
                <div class="relative p-8 bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">Platinum</h3>
                        <p class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight" x-text="annual ? 'Kshs 400,000' : 'Kshs 41,500'"></span>
                            <span class="ml-1 text-xl font-semibold" x-text="annual ? '/year' : '/month'"></span>
                        </p>
                        <p class="mt-6 text-gray-500">Complete solution for large institutions with advanced needs.</p>

                        <!-- Feature list -->
                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">All Gold features</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Multi-campus Support</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Advanced Analytics & Reporting</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">API Access</span>
                            </li>
                            <li class="flex">
                                <svg class="flex-shrink-0 w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="ml-3 text-gray-500">Premium Support (24/7)</span>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('demo') }}" class="mt-8 block w-full bg-white border border-gray-300 rounded-lg py-3 px-6 text-center font-medium text-blue-600 hover:bg-gray-50 hover:border-blue-600 transition-colors duration-200">Book a Demo</a>
                </div>
            </div>

            <!-- Enterprise -->
            <div class="mt-16 border-t border-gray-200 pt-16 lg:grid lg:grid-cols-3 lg:gap-x-8">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Enterprise plan</h2>
                    <p class="mt-3 text-sm text-gray-500">For large educational systems and networks with custom requirements.</p>
                </div>
                <div class="mt-8 lg:mt-0 lg:col-span-2">
                    <div class="flex items-center">
                        <h3 class="text-lg font-medium text-gray-900">Custom pricing</h3>
                        <span class="ml-4 px-4 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Coming soon</span>
                    </div>
                    <p class="mt-3 text-sm text-gray-500">Contact our sales team for a custom quote based on your specific needs and scale.</p>
                    <div class="mt-6">
                        <a href="{{ route('contact') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            Contact sales <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 