<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'MBUKU ERP')); ?> - <?php echo $__env->yieldContent('page_title', 'School Management System'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    
    <style>
        :root {
            --color-primary: #1a73e8;
            --color-primary-light: #4285f4;
            --color-secondary: #34a853;
            --color-accent: #fbbc05;
            --color-error: #ea4335;
            --color-surface: #ffffff;
            --color-background: #f8f9fa;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--color-background);
        }
        h1, h2, h3, h4, h5, h6, .heading-font {
            font-family: 'Google Sans', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('toasts', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1362297866-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    <!-- Navbar -->
    <header class="sticky top-0 z-30 w-full bg-white shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="<?php echo e(route('landing')); ?>" class="flex items-center">
                        <img src="<?php echo e(asset('assets/pics/mbukulogo.png')); ?>" alt="Logo" class="h-8 w-auto">
                        <span class="ml-3 text-gray-900 text-xl font-medium heading-font">Mbuku ERP</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex md:items-center md:space-x-8">
                    <a href="<?php echo e(route('landing')); ?>" class="text-gray-600 hover:text-primary px-3 py-2 text-sm font-medium transition duration-150">Home</a>
                    <a href="<?php echo e(route('pricing')); ?>" class="text-gray-600 hover:text-primary px-3 py-2 text-sm font-medium transition duration-150">Pricing</a>
                    <a href="<?php echo e(route('contact')); ?>" class="text-gray-600 hover:text-primary px-3 py-2 text-sm font-medium transition duration-150">Contact</a>
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-600 hover:text-primary px-3 py-2 text-sm font-medium transition duration-150">Login</a>
                    <a href="<?php echo e(route('demo')); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-md text-sm font-medium transition duration-150 shadow-sm">Book a Demo</a>
                </nav>
                
                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-900 hover:bg-gray-100 focus:outline-none"
                        aria-controls="mobile-menu"
                        aria-expanded="false"
                    >
                        <span class="sr-only">Open main menu</span>
                        <!-- Icon when menu is closed -->
                        <svg :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Icon when menu is open -->
                        <svg :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div 
            x-show="mobileMenuOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="md:hidden bg-white shadow-lg absolute w-full"
            id="mobile-menu"
        >
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="<?php echo e(route('landing')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">Home</a>
                <a href="<?php echo e(route('pricing')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">Pricing</a>
                <a href="<?php echo e(route('contact')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">Contact</a>
                <a href="<?php echo e(route('login')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900">Login</a>
                <a href="<?php echo e(route('demo')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-blue-500 hover:bg-blue-50 hover:text-blue-700">Book a Demo</a>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>       
    </main>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\projects\MbukuErp\resources\views/layouts/login_master.blade.php ENDPATH**/ ?>