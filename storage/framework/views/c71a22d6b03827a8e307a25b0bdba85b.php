<div class="py-4">
    <!-- Page Title -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800"><?php echo e(__('Edit Student Information')); ?></h2>
<div>
            <button wire:click="dispatch('closeAction')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <?php echo e(__('Back to Students')); ?>

            </button>
        </div>
    </div>

    <!-- Success Message -->
    <?php echo $__env->make('livewire.partials.students.success-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Main Content -->
    <div x-data="{ 
        activeTab: 'personal',
        setActiveTab(tab) {
            this.activeTab = tab;
        }
    }">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button 
                    @click="setActiveTab('personal')" 
                    :class="{'border-green-500 text-green-600': activeTab === 'personal', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'personal'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <?php echo e(__('Personal Details')); ?>

                </button>
                <button 
                    @click="setActiveTab('academic')" 
                    :class="{'border-green-500 text-green-600': activeTab === 'academic', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'academic'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <?php echo e(__('Academic Details')); ?>

                </button>
                <button 
                    @click="setActiveTab('parent')" 
                    :class="{'border-green-500 text-green-600': activeTab === 'parent', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'parent'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <?php echo e(__('Parent Details')); ?>

            </button>
                <button 
                    @click="setActiveTab('password')" 
                    :class="{'border-green-500 text-green-600': activeTab === 'password', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'password'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <?php echo e(__('Password')); ?>

            </button>
            </nav>
                        </div>
    
        <!-- Content Panels -->
        <div class="bg-white rounded-lg shadow-sm">
            <!-- Personal Details Panel -->
            <div x-show="activeTab === 'personal'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <?php echo $__env->make('livewire.partials.students.personal-details', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
    
            <!-- Academic Details Panel -->
            <div x-show="activeTab === 'academic'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <?php echo $__env->make('livewire.partials.students.academic-details', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
    
            <!-- Parent Details Panel -->
            <div x-show="activeTab === 'parent'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <?php echo $__env->make('livewire.partials.students.parent-details', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
    
            <!-- Password Panel -->
            <div x-show="activeTab === 'password'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <?php echo $__env->make('livewire.partials.students.password-section', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
        </div>
    </div>
</div><?php /**PATH C:\projects\MbukuErp\resources\views/livewire/edit-student.blade.php ENDPATH**/ ?>