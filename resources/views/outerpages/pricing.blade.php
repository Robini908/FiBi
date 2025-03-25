@extends('layouts.login_master')

@section('page_title', 'Pricing')

@section('content')
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-700 to-green-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                    Simple, Transparent Pricing
                </h1>
                <p class="mt-5 max-w-3xl mx-auto text-xl text-green-100">
                    Choose the plan that's right for your school, with no hidden fees.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Pricing Section -->
    @include('outerpages.partials.pricing')
    
    <!-- FAQ Section -->
    <div class="bg-white py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold text-green-600 tracking-wide uppercase">FAQ</h2>
                <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-5xl">Frequently asked questions</p>
                <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">Find answers to common questions about our plans, features, and implementation.</p>
            </div>
            
            <div class="mt-12" x-data="{ activeTab: null }">
                <dl class="space-y-6 divide-y divide-gray-200">
                    <div class="pt-6" x-data="{ open: false }">
                        <dt>
                            <button @click="open = !open" type="button" class="text-left w-full flex justify-between items-start text-gray-400" aria-controls="faq-0" aria-expanded="false">
                                <span class="text-lg font-medium text-gray-900">What is included in the implementation and setup?</span>
                                <span class="ml-6 h-7 flex items-center">
                                    <svg class="h-6 w-6 transform" :class="{ '-rotate-180': open, 'rotate-0': !open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </button>
                        </dt>
                        <dd class="mt-2 pr-12" x-show="open" x-transition>
                            <p class="text-base text-gray-500">Our implementation package includes system configuration, data migration from your existing systems, user setup, and comprehensive training for your staff. We'll work with your team to ensure a smooth transition to Mbuku ERP.</p>
                        </dd>
                    </div>
                    
                    <div class="pt-6" x-data="{ open: false }">
                        <dt>
                            <button @click="open = !open" type="button" class="text-left w-full flex justify-between items-start text-gray-400" aria-controls="faq-1" aria-expanded="false">
                                <span class="text-lg font-medium text-gray-900">Can I upgrade or downgrade my plan later?</span>
                                <span class="ml-6 h-7 flex items-center">
                                    <svg class="h-6 w-6 transform" :class="{ '-rotate-180': open, 'rotate-0': !open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </button>
                        </dt>
                        <dd class="mt-2 pr-12" x-show="open" x-transition>
                            <p class="text-base text-gray-500">Yes, you can change your plan at any time. If you upgrade, we'll prorate the difference. If you downgrade, the new rate will apply at the start of your next billing cycle. All your data will be preserved when changing plans.</p>
                        </dd>
                    </div>
                    
                    <div class="pt-6" x-data="{ open: false }">
                        <dt>
                            <button @click="open = !open" type="button" class="text-left w-full flex justify-between items-start text-gray-400" aria-controls="faq-2" aria-expanded="false">
                                <span class="text-lg font-medium text-gray-900">What kind of support is provided?</span>
                                <span class="ml-6 h-7 flex items-center">
                                    <svg class="h-6 w-6 transform" :class="{ '-rotate-180': open, 'rotate-0': !open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </button>
                        </dt>
                        <dd class="mt-2 pr-12" x-show="open" x-transition>
                            <p class="text-base text-gray-500">All plans include email support with varying response times depending on your plan. Gold and Platinum plans include phone support. Our support team is available during business hours, and Platinum customers receive 24/7 emergency support.</p>
                        </dd>
                    </div>
                    
                    <div class="pt-6" x-data="{ open: false }">
                        <dt>
                            <button @click="open = !open" type="button" class="text-left w-full flex justify-between items-start text-gray-400" aria-controls="faq-3" aria-expanded="false">
                                <span class="text-lg font-medium text-gray-900">Is there a limit to the number of students or staff members?</span>
                                <span class="ml-6 h-7 flex items-center">
                                    <svg class="h-6 w-6 transform" :class="{ '-rotate-180': open, 'rotate-0': !open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </button>
                        </dt>
                        <dd class="mt-2 pr-12" x-show="open" x-transition>
                            <p class="text-base text-gray-500">The Silver plan supports up to 500 students and 50 staff members. The Gold plan supports up to 1,500 students and 150 staff. The Platinum plan has no limits on users. For larger institutions with specific needs, please contact us for custom Enterprise solutions.</p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-green-700 to-green-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    <span class="block">Ready to streamline your school management?</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-green-100">
                    Schedule a demo today and see how Mbuku ERP can transform your institution.
                </p>
                <div class="mt-8 flex justify-center">
                    <div class="inline-flex rounded-md shadow">
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-white hover:bg-gray-50">
                            Request a demo
                        </a>
                    </div>
                    <div class="ml-3 inline-flex">
                        <a href="{{ route('demo') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-500 bg-opacity-60 hover:bg-opacity-70">
                            Book a Demo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Section -->
    @include('outerpages.partials.footer')
@endsection
