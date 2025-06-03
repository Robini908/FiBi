<!-- Arrears Table -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <!--[if BLOCK]><![endif]--><?php if(!$isStudent): ?>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Term</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (KES)</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <!--[if BLOCK]><![endif]--><?php if($isAdmin || $isAccountant): ?>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $arrears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arrear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <!--[if BLOCK]><![endif]--><?php if(!$isStudent): ?>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($arrear->student->name ?? 'Unknown Student'); ?>

                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo e($arrear->student->admission_number ?? 'N/A'); ?>

                                </div>
                            </div>
                        </div>
                    </td>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($arrear->myClass->name ?? 'N/A'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($arrear->previous_year); ?> (Term <?php echo e($arrear->previous_term); ?>)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        KES <?php echo e(number_format($arrear->amount, 2)); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <!--[if BLOCK]><![endif]--><?php if($arrear->is_cleared): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Cleared
                            </span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Pending
                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                    <!--[if BLOCK]><![endif]--><?php if($isAdmin || $isAccountant): ?>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex space-x-2">
                            <!--[if BLOCK]><![endif]--><?php if(!$arrear->is_cleared): ?>
                                <button 
                                    wire:click="editArrear(<?php echo e($arrear->id); ?>)" 
                                    class="text-indigo-600 hover:text-indigo-900"
                                    title="Edit"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button 
                                    onclick="confirm('Are you sure you want to mark this arrear as cleared?') || event.stopImmediatePropagation()" 
                                    wire:click="markArrearAsCleared(<?php echo e($arrear->id); ?>)" 
                                    class="text-green-600 hover:text-green-900"
                                    title="Mark as Cleared"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button 
                                    onclick="confirm('Are you sure you want to delete this arrear?') || event.stopImmediatePropagation()" 
                                    wire:click="deleteArrear(<?php echo e($arrear->id); ?>)" 
                                    class="text-red-600 hover:text-red-900"
                                    title="Delete"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            <?php else: ?>
                                <span class="text-gray-400 italic">No actions available</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </td>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="<?php echo e(($isStudent ? 4 : 5) + ($isAdmin || $isAccountant ? 1 : 0)); ?>" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        <!--[if BLOCK]><![endif]--><?php if($isAdmin || $isAccountant): ?>
                            No arrears found. Add an arrear or use "Generate Arrears" to create new records.
                        <?php else: ?>
                            No arrears found.
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                </tr>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="px-6 py-4">
    <?php echo e($arrears->links()); ?>

</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/student-arrears-table.blade.php ENDPATH**/ ?>