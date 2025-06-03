

<?php $__env->startSection('page_title', 'Contact Us'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="bg-white py-16 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Get in Touch</h2>
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl mt-2">
                    Contact Us
                </h1>
                <p class="mt-5 max-w-3xl mx-auto text-xl text-gray-500">
                    Get in touch with our team for any questions, support, or to schedule a demo.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Contact Section -->
    <?php echo $__env->make('outerpages.partials.contact', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <!-- Map Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Our Location</h2>
                <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-5xl">Visit our office</p>
                <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">We're located in the heart of Nairobi business district.</p>
            </div>
            <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                <div class="aspect-w-16 aspect-h-9">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.818133169469!2d36.8144003!3d-1.2866391!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f10d22f756be7%3A0x5b3a7e9be1d3346e!2sNairobi%2C%20Kenya!5e0!3m2!1sen!2sus!4v1667547890148!5m2!1sen!2sus" 
                        width="100%" 
                        height="500" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-full">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Section -->
    <?php echo $__env->make('outerpages.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/outerpages/contact.blade.php ENDPATH**/ ?>