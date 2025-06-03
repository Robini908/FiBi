

<?php $__env->startSection('page_title', 'Book a Demo'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="bg-white py-16 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Live Demonstration</h2>
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl mt-2">
                    Book a Demo
                </h1>
                <p class="mt-5 max-w-3xl mx-auto text-xl text-gray-500">
                    See Mbuku ERP in action with a personalized demonstration tailored to your school's needs.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Notification Messages -->
    <?php if(session('success')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="rounded-md bg-green-50 p-4 border border-green-100 shadow-sm"
                x-data="{ show: true }" 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
            >
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            <?php echo e(session('success')); ?>

                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button @click="show = false" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Demo Booking Section -->
    <div class="bg-white py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-16">
                <!-- Left column - Form -->
                <div class="bg-white p-8 rounded-lg shadow-lg"
                    x-data="{
                        name: '',
                        email: '',
                        phone: '',
                        school: '',
                        position: '',
                        message: '',
                        preferredDate: '',
                        errors: {
                            name: false,
                            email: false,
                            phone: false,
                            school: false,
                            position: false,
                            preferredDate: false
                        },
                        validateName() {
                            this.errors.name = this.name.length < 3;
                            return !this.errors.name;
                        },
                        validateEmail() {
                            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            this.errors.email = !re.test(this.email);
                            return !this.errors.email;
                        },
                        validatePhone() {
                            this.errors.phone = this.phone.length < 10;
                            return !this.errors.phone;
                        },
                        validateSchool() {
                            this.errors.school = this.school.length < 3;
                            return !this.errors.school;
                        },
                        validatePosition() {
                            this.errors.position = this.position.length < 3;
                            return !this.errors.position;
                        },
                        validateDate() {
                            this.errors.preferredDate = !this.preferredDate;
                            return !this.errors.preferredDate;
                        },
                        submitForm() {
                            if(this.validateName() && this.validateEmail() && this.validatePhone() && 
                               this.validateSchool() && this.validatePosition() && this.validateDate()) {
                                // Submit the form
                                document.getElementById('demoForm').submit();
                            }
                        }
                    }"
                >
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Schedule Your Demo</h2>
                    <form id="demoForm" action="<?php echo e(route('demo.book')); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <div class="mt-1">
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    x-model="name"
                                    @blur="validateName()"
                                    class="py-3 px-4 block w-full shadow-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    :class="{ 'border-red-300': errors.name }"
                                    value="<?php echo e(old('name')); ?>"
                                >
                                <p x-show="errors.name" class="mt-1 text-sm text-red-600">Please enter your full name</p>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <div class="mt-1">
                                    <input 
                                        type="email" 
                                        name="email" 
                                        id="email" 
                                        x-model="email"
                                        @blur="validateEmail()"
                                        class="py-3 px-4 block w-full shadow-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        :class="{ 'border-red-300': errors.email }"
                                        value="<?php echo e(old('email')); ?>"
                                    >
                                    <p x-show="errors.email" class="mt-1 text-sm text-red-600">Please enter a valid email address</p>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <div class="mt-1">
                                    <input 
                                        type="text" 
                                        name="phone" 
                                        id="phone" 
                                        x-model="phone"
                                        @blur="validatePhone()"
                                        class="py-3 px-4 block w-full shadow-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        :class="{ 'border-red-300': errors.phone }"
                                        value="<?php echo e(old('phone')); ?>"
                                    >
                                    <p x-show="errors.phone" class="mt-1 text-sm text-red-600">Please enter a valid phone number</p>
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="school" class="block text-sm font-medium text-gray-700">School/Institution Name</label>
                                <div class="mt-1">
                                    <input 
                                        type="text" 
                                        name="school" 
                                        id="school" 
                                        x-model="school"
                                        @blur="validateSchool()"
                                        class="py-3 px-4 block w-full shadow-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        :class="{ 'border-red-300': errors.school }"
                                        value="<?php echo e(old('school')); ?>"
                                    >
                                    <p x-show="errors.school" class="mt-1 text-sm text-red-600">Please enter your school/institution name</p>
                                    <?php $__errorArgs = ['school'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700">Your Position</label>
                                <div class="mt-1">
                                    <input 
                                        type="text" 
                                        name="position" 
                                        id="position" 
                                        x-model="position"
                                        @blur="validatePosition()"
                                        class="py-3 px-4 block w-full shadow-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        :class="{ 'border-red-300': errors.position }"
                                        value="<?php echo e(old('position')); ?>"
                                    >
                                    <p x-show="errors.position" class="mt-1 text-sm text-red-600">Please enter your position</p>
                                    <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="preferredDate" class="block text-sm font-medium text-gray-700">Preferred Demo Date</label>
                            <div class="mt-1">
                                <input 
                                    type="date" 
                                    name="preferredDate" 
                                    id="preferredDate" 
                                    x-model="preferredDate"
                                    @blur="validateDate()"
                                    class="py-3 px-4 block w-full shadow-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    :class="{ 'border-red-300': errors.preferredDate }"
                                    min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
                                    value="<?php echo e(old('preferredDate')); ?>"
                                >
                                <p x-show="errors.preferredDate" class="mt-1 text-sm text-red-600">Please select a preferred date for your demo</p>
                                <?php $__errorArgs = ['preferredDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Additional Information</label>
                            <div class="mt-1">
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="4" 
                                    x-model="message"
                                    class="py-3 px-4 block w-full shadow-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="Tell us about your school's specific needs or any questions you have"
                                ><?php echo e(old('message')); ?></textarea>
                                <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div>
                            <button 
                                type="button"
                                @click="submitForm()"
                                class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                Book Your Demo
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Right column - Info -->
                <div class="mt-12 lg:mt-0">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">What to Expect From Your Demo</h2>
                    
                    <div class="space-y-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Personalized Presentation</h3>
                                <p class="mt-1 text-gray-500">Our product specialist will customize the demo to focus on the modules and features most relevant to your school's needs.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Q&A Session</h3>
                                <p class="mt-1 text-gray-500">You'll have the opportunity to ask questions and discuss your specific requirements with our education technology expert.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Pricing Information</h3>
                                <p class="mt-1 text-gray-500">We'll provide detailed information about our pricing plans and help you determine which is the best fit for your institution.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Implementation Timeline</h3>
                                <p class="mt-1 text-gray-500">We'll walk you through the onboarding process, data migration, and training schedule to give you a clear picture of the implementation journey.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-10 p-6 bg-green-50 rounded-lg">
                        <h3 class="text-lg font-bold text-gray-900">Demo Sessions Typically Last 45-60 Minutes</h3>
                        <p class="mt-2 text-gray-600">We recommend having key decision-makers and department heads join the demo to get the most value. You can invite as many team members as you'd like!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testimonial Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">What Our Customers Say</h2>
                <p class="mt-4 text-lg text-gray-500">Schools that have implemented Mbuku ERP after a demo have seen significant improvements in their administrative processes.</p>
            </div>
            <div class="mt-12 max-w-lg mx-auto grid gap-8 lg:grid-cols-2 lg:max-w-none">
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-xl font-semibold text-gray-900">Greenwood Academy</p>
                            <p class="mt-3 text-base text-gray-500">
                                "The demo showed us exactly how Mbuku ERP could solve our specific challenges. Within three months of implementation, we've reduced administrative workload by 40% and improved fee collection rates."
                            </p>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=John+Smith&background=0D8ABC&color=fff" alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">John Smith</p>
                                <p class="text-sm text-gray-500">Principal, Greenwood Academy</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-xl font-semibold text-gray-900">Sunrise International School</p>
                            <p class="mt-3 text-base text-gray-500">
                                "The personalized demo helped us understand how much time we could save with Mbuku ERP. The transition was smooth, and the support team has been responsive to our needs throughout the process."
                            </p>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Mary+Johnson&background=0D8ABC&color=fff" alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Mary Johnson</p>
                                <p class="text-sm text-gray-500">Administrative Director, Sunrise International School</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Section -->
    <?php echo $__env->make('outerpages.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.login_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/outerpages/demo.blade.php ENDPATH**/ ?>