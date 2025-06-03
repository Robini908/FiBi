<!-- Fee Structure Table -->
<div class="bg-white shadow-lg rounded-sm border border-gray-200 mb-8">
    <div class="p-3">
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead class="text-xs font-semibold uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-left">Name</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Class</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Term/Year</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Category</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Amount</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Status</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Mandatory</div>
                        </th>
                        <th class="p-2 whitespace-nowrap">
                            <div class="font-semibold text-center">Actions</div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <!--[if BLOCK]><![endif]--><?php if($feeStructures->count() > 0): ?>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $feeStructures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feeStructure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="font-medium text-gray-800">
                                            <?php echo e($feeStructure->fee_name); ?>

                                        </div>
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-center">
                                        <?php echo e(($feeStructure->classroom) ? $feeStructure->classroom->name : 'All Classes'); ?>

                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-center">
                                        <?php echo e($feeStructure->term); ?> / <?php echo e($feeStructure->year); ?>

                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-center px-2 py-1 rounded-full text-xs font-medium 
                                        <?php if($feeStructure->category == 'Tuition'): ?>
                                            bg-blue-100 text-blue-800
                                        <?php elseif($feeStructure->category == 'Transport'): ?>
                                            bg-amber-100 text-amber-800
                                        <?php elseif($feeStructure->category == 'Boarding'): ?>
                                            bg-purple-100 text-purple-800
                                        <?php else: ?>
                                            bg-green-100 text-green-800
                                        <?php endif; ?>">
                                        <?php echo e($feeStructure->category); ?>

                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-center font-medium text-gray-800">
                                        KES <?php echo e(number_format($feeStructure->amount, 2)); ?>

                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-center">
                                        <!--[if BLOCK]><![endif]--><?php if($hasManagePermission): ?>
                                            <button wire:click="toggleStatus(<?php echo e($feeStructure->id); ?>)" 
                                                class="px-2 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($feeStructure->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                                <?php echo e($feeStructure->is_active ? 'Active' : 'Inactive'); ?>

                                            </button>
                                        <?php else: ?>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($feeStructure->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                                <?php echo e($feeStructure->is_active ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-center">
                                        <!--[if BLOCK]><![endif]--><?php if($hasManagePermission): ?>
                                            <button wire:click="toggleMandatory(<?php echo e($feeStructure->id); ?>)" 
                                                class="px-2 py-1 rounded-full text-xs font-medium
                                                <?php echo e($feeStructure->is_mandatory ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo e($feeStructure->is_mandatory ? 'Yes' : 'No'); ?>

                                            </button>
                                        <?php else: ?>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                <?php echo e($feeStructure->is_mandatory ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo e($feeStructure->is_mandatory ? 'Yes' : 'No'); ?>

                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-center space-x-1">
                                        <!-- View button - accessible to all users -->
                                        <button wire:click="viewFeeStructure(<?php echo e($feeStructure->id); ?>)" 
                                            class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded border border-gray-200 
                                            hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>

                                        <!--[if BLOCK]><![endif]--><?php if($hasManagePermission): ?>
                                            <!-- Edit button - for admins and accountants -->
                                            <button wire:click="editFeeStructure(<?php echo e($feeStructure->id); ?>)" 
                                                class="px-3 py-1.5 bg-blue-100 text-blue-600 rounded border border-blue-200 
                                                hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </button>

                                            <!-- Copy button - for admins and accountants -->
                                            <button wire:click="copyFeeStructure(<?php echo e($feeStructure->id); ?>)" 
                                                class="px-3 py-1.5 bg-green-100 text-green-600 rounded border border-green-200 
                                                hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                                </svg>
                                            </button>

                                            <!-- Delete button - for admins and accountants -->
                                            <button wire:click="confirmDeleteFeeStructure(<?php echo e($feeStructure->id); ?>)" 
                                                class="px-3 py-1.5 bg-red-100 text-red-600 rounded border border-red-200 
                                                hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="p-4 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No fee structures found</p>
                                    <p class="text-sm text-gray-500 mt-1"><?php echo e($search ? 'Try adjusting your search criteria.' : 'Create your first fee structure to get started.'); ?></p>
                                    
                                    <!--[if BLOCK]><![endif]--><?php if($hasManagePermission && !$search): ?>
                                        <button wire:click="openModal" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Add Fee Structure
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="px-4 py-3 border-t border-gray-200">
        <?php echo e($feeStructures->links()); ?>

    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/fee-structure-table.blade.php ENDPATH**/ ?>