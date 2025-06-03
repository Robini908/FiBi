

<?php $__env->startSection('page_title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hero Section -->
    <?php echo $__env->make('outerpages.partials.hero', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Features Section -->
    <?php echo $__env->make('outerpages.partials.features', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Testimonials Section -->
    <?php echo $__env->make('outerpages.partials.testimonials', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Demo CTA Section -->
    <div class="bg-gradient-to-r from-green-700 to-green-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                        Ready to see Mbuku ERP in action?
                    </h2>
                    <p class="mt-3 text-xl text-green-100 max-w-3xl">
                        Schedule a personalized demo with our product specialists and discover how our solution can transform your school's administration.
                    </p>
                </div>
                <div class="mt-5 flex lg:mt-0 lg:ml-10">
                    <a href="<?php echo e(route('demo')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-green-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Book a Demo
                        <svg class="ml-3 -mr-1 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Section -->
    <?php echo $__env->make('outerpages.partials.contact', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Footer Section -->
    <?php echo $__env->make('outerpages.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/outerpages/landing.blade.php ENDPATH**/ ?>