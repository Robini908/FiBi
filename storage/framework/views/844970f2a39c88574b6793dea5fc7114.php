<div 
    class="space-y-6" 
    x-data="{ 
        loading: false,
        actionInProgress: false,
        showLoadingOverlay(target) {
            this.loading = true;
            this.actionInProgress = true;
        }
    }"
    x-init="
        Livewire.hook('message.sent', (message, component) => {
            actionInProgress = true;
        });
        
        Livewire.hook('message.processed', (message, component) => {
            loading = false;
            actionInProgress = false;
        });
        
        Livewire.hook('message.failed', (message, component) => {
            loading = false;
            actionInProgress = false;
        });
    "
    wire:loading.class="relative"
>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Grading Systems</h2>
            <p class="mt-1 text-sm text-gray-500">Manage grading systems for exams and student assessment</p>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <button 
                wire:click="showGradingSystemForm" 
                @click="showLoadingOverlay('form')"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-70"
                x-bind:disabled="actionInProgress"
            >
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Add Grading System</span>
                
                <svg wire:loading wire:target="showGradingSystemForm" class="animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Global Loading Overlay -->
    <div 
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity duration-300"
        x-show="actionInProgress"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;"
    >
        <div class="bg-white p-6 rounded-lg shadow-xl flex flex-col items-center max-w-sm mx-auto">
            <span class="loading loading-spinner loading-lg text-green-600 mb-4"></span>
            <p class="text-gray-700 font-medium">Processing request...</p>
            <p class="text-sm text-gray-500 mt-1">This may take a moment</p>
        </div>
    </div>

    <!-- Grading Systems Grid -->
    <div 
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        wire:loading.class="opacity-50"
        x-bind:class="{ 'opacity-50': loading }"
    >
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $gradingSystems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-200">
                <div class="card-body p-5">
                    <h3 class="card-title text-lg font-semibold text-gray-900"><?php echo e($gs->name); ?></h3>
                    <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                        <?php echo e($gs->description ?: 'Grading system for ' . $gs->name); ?>

                    </p>
                    
                    <div class="divider my-2"></div>
                
                    <div class="space-y-2">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Effective from:</span>
                            <span class="badge badge-outline"><?php echo e(date('d M, Y', strtotime($gs->effective_date))); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Grades defined:</span>
                            <span class="badge <?php echo e($gs->gradingRanges->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?> gap-1">
                            <?php echo e($gs->gradingRanges->count()); ?>

                        </span>
                    </div>
                    
                    <!--[if BLOCK]><![endif]--><?php if($gs->gradingRanges->count() == 0): ?>
                            <div class="alert alert-warning mt-2 py-2 text-xs flex items-center">
                                <svg class="h-4 w-4 mr-1.5 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                                <span>No grade ranges defined yet</span>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                
                <div class="card-actions justify-between bg-gray-50 px-5 py-3 border-t border-gray-200">
                    <button 
                        wire:click="editGradingSystem(<?php echo e($gs->id); ?>)" 
                        wire:loading.attr="disabled"
                        @click="showLoadingOverlay('edit')"
                        class="btn btn-sm btn-ghost gap-1 text-gray-600 hover:text-green-700 transition-colors duration-150"
                        x-bind:disabled="actionInProgress"
                    >
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        <span>Edit</span>
                        <span class="loading loading-spinner loading-xs text-green-600" wire:loading wire:target="editGradingSystem(<?php echo e($gs->id); ?>)"></span>
                    </button>
                    
                    <a 
                        href="<?php echo e(route('exams.grading-systems.show', $gs->id)); ?>" 
                        class="btn btn-sm bg-green-600 hover:bg-green-700 text-white gap-1 border-none"
                    >
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        <span>View Details</span>
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full card bg-base-100 p-8 border-2 border-dashed border-gray-300 text-center">
                <div class="card-body items-center text-center">
                <svg class="mx-auto h-14 w-14 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="text-base font-medium text-gray-900 mb-1">No grading systems found</h3>
                <p class="text-sm text-gray-500 mb-6">Get started by creating your first grading system</p>
                <button 
                    wire:click="showGradingSystemForm" 
                        @click="showLoadingOverlay('form')"
                        class="btn bg-green-600 hover:bg-green-700 text-white gap-2 border-none"
                        x-bind:disabled="actionInProgress"
                >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                        <span>Create Grading System</span>
                        <span class="loading loading-spinner loading-xs" wire:loading wire:target="showGradingSystemForm"></span>
                </button>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    
    <!-- Pagination -->
    <!--[if BLOCK]><![endif]--><?php if(isset($gradingSystems) && method_exists($gradingSystems, 'links') && $gradingSystems->hasPages()): ?>
        <div class="mt-8">
            <div class="pagination-wrapper">
            <?php echo e($gradingSystems->links()); ?>

            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\projects\MbukuErp\resources\views/livewire/exams/partials/grading-tab.blade.php ENDPATH**/ ?>