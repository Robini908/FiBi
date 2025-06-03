<div class="relative overflow-hidden bg-white pt-16 pb-24 sm:pt-24 sm:pb-32">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-y-16 gap-x-8 lg:grid-cols-2 lg:items-center">
            <!-- Text Content -->
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block text-blue-600">Mbuku ERP</span>
                    <span class="block mt-2">School Management System</span>
                </h1>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Simplify your school administration with our all-in-one education management platform. From student registration to academic performance analytics, we've got everything you need.
                </p>
                <div class="mt-10 flex items-center gap-x-6">
                    <a href="<?php echo e(route('login')); ?>" 
                       class="rounded-lg bg-blue-600 px-5 py-3 text-base font-medium text-white shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                        Login
                    </a>
                    <a href="<?php echo e(route('demo')); ?>" 
                       class="rounded-lg bg-white px-5 py-3 text-base font-medium text-blue-600 shadow-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                        Book a Demo
                    </a>
                    <a href="<?php echo e(route('pricing')); ?>" 
                       class="text-base font-medium text-gray-600 hover:text-blue-600 transition-all duration-200">
                        View Pricing <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
            
            <!-- Hero Image -->
            <div class="mx-auto max-w-xl lg:mx-0 lg:max-w-none lg:flex-auto">
                <div class="relative rounded-2xl bg-gray-50 p-8 shadow-xl ring-1 ring-gray-200 overflow-hidden">
                    <!-- Abstract colored shapes in background -->
                    <div class="absolute inset-0 opacity-10 blur-3xl" aria-hidden="true">
                        <div class="absolute right-1/4 top-0 h-64 w-64 rounded-full bg-blue-500"></div>
                        <div class="absolute left-1/3 bottom-0 h-40 w-40 rounded-full bg-green-400"></div>
                        <div class="absolute right-1/2 top-1/2 h-80 w-80 rounded-full bg-yellow-300"></div>
                    </div>

                    <div class="relative">
                        <img 
                            src="<?php echo e(asset('assets/pics/erpuse.png')); ?>" 
                            alt="Mbuku ERP Platform" 
                            class="rounded-lg shadow-lg object-cover w-full h-auto"
                        >
                        <button type="button" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-300 group rounded-lg">
                            <span class="sr-only">Watch demo video</span>
                            <div class="bg-white rounded-full p-4 shadow-lg transform transition-transform group-hover:scale-110">
                                <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5.14v14l11-7-11-7z" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative pattern at bottom (optional) -->
    <div class="absolute inset-x-0 bottom-0 -z-10 h-24 bg-gradient-to-t from-gray-50"></div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/outerpages/partials/hero.blade.php ENDPATH**/ ?>