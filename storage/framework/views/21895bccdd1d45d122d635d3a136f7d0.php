<div class="bg-white py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Testimonials</h2>
            <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-5xl">Trusted by education leaders</p>
            <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">See how schools are transforming with Mbuku ERP</p>
        </div>

        <div class="mt-16"
            x-data="{ 
                testimonials: [
                    {
                        quote: 'Mbuku ERP has completely transformed how we manage our school operations. The student registration process is now seamless, and the analytics help us make data-driven decisions.',
                        author: 'Sarah Johnson',
                        position: 'Principal, Green Valley Academy',
                        image: '/assets/pics/principal1.jpg'
                    },
                    {
                        quote: 'The fee management module has reduced our administrative workload by 60%. Parents love the transparency, and we love the efficiency.',
                        author: 'Michael Omondi',
                        position: 'Finance Director, Nairobi International School',
                        image: '/assets/pics/finance-director.jpg'
                    },
                    {
                        quote: 'Implementation was smooth and the technical support is responsive. Our teachers quickly adapted to the system, and now they can focus more on teaching rather than paperwork.',
                        author: 'Dr. Elizabeth Wangari',
                        position: 'School Director, Excel High School',
                        image: '/assets/pics/director.jpg'
                    }
                ],
                activeIndex: 0
            }"
            x-init="setInterval(() => { activeIndex = (activeIndex + 1) % testimonials.length }, 8000)"
            class="relative"
        >
            <!-- Testimonial container with Google-style cards -->
            <div class="relative">
                <div class="absolute top-1/2 transform -translate-y-1/2 left-0 z-10 hidden md:block">
                    <button @click="activeIndex = (activeIndex - 1 + testimonials.length) % testimonials.length" 
                            class="bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow duration-300 focus:outline-none">
                        <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <div class="absolute top-1/2 transform -translate-y-1/2 right-0 z-10 hidden md:block">
                    <button @click="activeIndex = (activeIndex + 1) % testimonials.length" 
                            class="bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow duration-300 focus:outline-none">
                        <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <!-- Testimonial slides -->
                <div class="md:px-16">
                    <template x-for="(testimonial, index) in testimonials" :key="index">
                        <div x-show="activeIndex === index"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-x-8"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform translate-x-0"
                            x-transition:leave-end="opacity-0 transform -translate-x-8"
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-10"
                        >
                            <div class="flex flex-col md:flex-row md:items-start">
                                <div class="md:flex-shrink-0 mx-auto md:mx-0 mb-6 md:mb-0 md:mr-8">
                                    <div class="h-20 w-20 rounded-full overflow-hidden border-4 border-white shadow-sm">
                                        <img :src="testimonial.image" alt="" class="h-full w-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent(this.parentNode.parentNode.querySelector('div:nth-child(2)').textContent);">
                                    </div>
                                </div>
                                <div>
                                    <svg class="h-10 w-10 text-blue-100 mb-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"></path>
                                    </svg>
                                    <blockquote>
                                        <p class="text-xl font-medium text-gray-700 md:text-2xl leading-relaxed" x-text="testimonial.quote"></p>
                                        <div class="mt-6">
                                            <div class="font-semibold text-gray-900 text-lg" x-text="testimonial.author"></div>
                                            <div class="text-blue-600" x-text="testimonial.position"></div>
                                        </div>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Testimonial navigation dots -->
            <div class="mt-10 flex justify-center space-x-3">
                <template x-for="(testimonial, index) in testimonials" :key="index">
                    <button 
                        @click="activeIndex = index" 
                        :class="{'bg-blue-600': activeIndex === index, 'bg-gray-200': activeIndex !== index}"
                        class="h-2.5 w-2.5 rounded-full focus:outline-none transition-colors duration-300"
                    ></button>
                </template>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/outerpages/partials/testimonials.blade.php ENDPATH**/ ?>