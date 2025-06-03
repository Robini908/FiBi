<div x-data="{ isReinstating: <?php if ((object) ('isReinstating') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isReinstating'->value()); ?>')<?php echo e('isReinstating'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isReinstating'); ?>')<?php endif; ?>, isExtendingSuspension: <?php if ((object) ('isExtendingSuspension') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isExtendingSuspension'->value()); ?>')<?php echo e('isExtendingSuspension'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isExtendingSuspension'); ?>')<?php endif; ?>, isCreatingSuspension: <?php if ((object) ('isCreatingSuspension') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isCreatingSuspension'->value()); ?>')<?php echo e('isCreatingSuspension'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isCreatingSuspension'); ?>')<?php endif; ?> }" 
     class="bg-white rounded-lg shadow-md overflow-hidden" 
     wire:poll.10s>
    
    <!-- Header Section with gradient accent -->
    <div class="bg-gradient-to-r from-green-50 to-white border-b border-gray-200 px-6 py-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-medium text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Suspension Management
            </h2>
                <p class="text-sm text-gray-500 mt-1">Manage student suspensions, track reinstatements, and extend suspension periods.</p>
            </div>
            
            <div class="flex space-x-3">
                <button wire:click="$toggle('isCreatingSuspension')" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create Suspension
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="p-6">
        

        <!-- Filter Controls -->
        <div x-show="!isReinstating && !isExtendingSuspension && !isCreatingSuspension" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="mt-6">
            
            <?php echo $__env->make('livewire.partials.suspensions.filter-controls', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <!-- Main Content -->
        <div wire:poll.1s="checkSuspensions" class="mt-6">
            <!-- Suspended Students Grid -->
            <div x-show="!isReinstating && !isExtendingSuspension && !isCreatingSuspension" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                
                <!--[if BLOCK]><![endif]--><?php if($suspendedStudents->isEmpty()): ?>
                    <?php echo $__env->make('livewire.partials.suspensions.empty-state', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php else: ?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $suspendedStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div wire:key="student-<?php echo e($student->id); ?>">
                                <?php echo $__env->make('livewire.partials.suspensions.suspended-student-card', ['student' => $student], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Create New Suspension Form -->
            <div x-show="isCreatingSuspension" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">
                
                <?php echo $__env->make('livewire.partials.suspensions.new-suspension-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            
            <!-- Reinstate Student Form -->
            <div x-show="isReinstating" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">
                
                <?php echo $__env->make('livewire.partials.suspensions.reinstate-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            
            <!-- Extend Suspension Form -->
            <div x-show="isExtendingSuspension" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">
                
                <?php echo $__env->make('livewire.partials.suspensions.extend-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/manage-suspensions.blade.php ENDPATH**/ ?>