<div x-show="showViewModal" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto" 
    style="display: none;">
    
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div 
            x-show="showViewModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6"
            @click.away="showViewModal = false">
            
            <!-- Modal content -->
            <div>
                <!-- Header -->
                <div class="flex items-start justify-between pb-3 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Fee Structure Details
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Viewing complete information about this fee.
                        </p>
                    </div>
                    <button @click="showViewModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="mt-4">
                    <!--[if BLOCK]><![endif]--><?php if($viewingFeeStructure): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Basic Information</h4>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Fee Name:</dt>
                                        <dd class="text-sm text-gray-900"><?php echo e($viewingFeeStructure->fee_name); ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Amount:</dt>
                                        <dd class="text-sm text-gray-900">KES <?php echo e(number_format($viewingFeeStructure->amount, 2)); ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Category:</dt>
                                        <dd class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php if($viewingFeeStructure->category == 'Tuition'): ?>
                                                    bg-blue-100 text-blue-800
                                                <?php elseif($viewingFeeStructure->category == 'Transport'): ?>
                                                    bg-amber-100 text-amber-800
                                                <?php elseif($viewingFeeStructure->category == 'Boarding'): ?>
                                                    bg-purple-100 text-purple-800
                                                <?php else: ?>
                                                    bg-green-100 text-green-800
                                                <?php endif; ?>">
                                                <?php echo e($viewingFeeStructure->category); ?>

                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Application Details -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Application Details</h4>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Class:</dt>
                                        <dd class="text-sm text-gray-900">
                                            <?php echo e(($viewingFeeStructure->classroom) ? $viewingFeeStructure->classroom->name : 'All Classes'); ?>

                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Academic Year:</dt>
                                        <dd class="text-sm text-gray-900"><?php echo e($viewingFeeStructure->year); ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Term:</dt>
                                        <dd class="text-sm text-gray-900"><?php echo e($viewingFeeStructure->term); ?></dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Status Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Status Information</h4>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Status:</dt>
                                        <dd class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php echo e($viewingFeeStructure->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                                <?php echo e($viewingFeeStructure->is_active ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Mandatory:</dt>
                                        <dd class="text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php echo e($viewingFeeStructure->is_mandatory ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo e($viewingFeeStructure->is_mandatory ? 'Yes' : 'No'); ?>

                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Audit Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Audit Information</h4>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Created By:</dt>
                                        <dd class="text-sm text-gray-900">
                                            <?php echo e($viewingFeeStructure->created_by ? $viewingFeeStructure->creator->name : 'System'); ?>

                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Created:</dt>
                                        <dd class="text-sm text-gray-900">
                                            <?php echo e($viewingFeeStructure->created_at->format('M d, Y H:i')); ?>

                                        </dd>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php if($viewingFeeStructure->updated_by): ?>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Last Updated By:</dt>
                                        <dd class="text-sm text-gray-900">
                                            <?php echo e($viewingFeeStructure->updator->name); ?>

                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Last Updated:</dt>
                                        <dd class="text-sm text-gray-900">
                                            <?php echo e($viewingFeeStructure->updated_at->format('M d, Y H:i')); ?>

                                        </dd>
                                    </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </dl>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <!--[if BLOCK]><![endif]--><?php if($viewingFeeStructure->description): ?>
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Description</h4>
                            <p class="text-sm text-gray-600">
                                <?php echo e($viewingFeeStructure->description); ?>

                            </p>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No fee structure selected</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Please select a fee structure to view its details.
                            </p>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Footer -->
                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                    <button @click="showViewModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                        Close
                    </button>
                    
                    <!--[if BLOCK]><![endif]--><?php if($viewingFeeStructure && $hasManagePermission): ?>
                        <button wire:click="editFeeStructure(<?php echo e($viewingFeeStructure->id); ?>)" class="ml-3 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Edit Fee
                        </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/fee-structure-view-modal.blade.php ENDPATH**/ ?>