<div x-data="{ open: <?php if ((object) ('detailsModalOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('detailsModalOpen'->value()); ?>')<?php echo e('detailsModalOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('detailsModalOpen'); ?>')<?php endif; ?> }" 
    x-show="open" 
    class="fixed inset-0 overflow-y-auto z-50" 
    x-cloak 
    @keydown.escape.window="open = false">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="fixed inset-0 transition-opacity" 
            aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            
            <!--[if BLOCK]><![endif]--><?php if($detailsData): ?>
                <div class="bg-white">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <div class="border-b border-gray-200 pb-5">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Attendance Details
                                    </h3>
                                    <div class="mt-2 max-w-xl text-sm text-gray-500">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="font-medium">Class:</p>
                                                <p><?php echo e($detailsData->myClass->name); ?> <?php echo e($detailsData->section->name); ?></p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Date:</p>
                                                <p><?php echo e(\Carbon\Carbon::parse($detailsData->attendance_date)->format('D, M d, Y')); ?></p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Session:</p>
                                                <p><?php echo e(ucfirst(str_replace('_', ' ', $detailsData->session_type))); ?></p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Marked By:</p>
                                                <p><?php echo e($detailsData->markedBy->name ?? 'Unknown'); ?></p>
                                            </div>
                                        </div>
                                        
                                        <!--[if BLOCK]><![endif]--><?php if($detailsData->remarks): ?>
                                            <div class="mt-4">
                                                <p class="font-medium">Remarks:</p>
                                                <p class="mt-1 whitespace-pre-line"><?php echo e($detailsData->remarks); ?></p>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700">Students</h4>
                                    <div class="mt-2 overflow-y-auto max-h-96">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Student
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Status
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Time In
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Remarks
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $detailsData->attendanceDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-8 w-8">
                                                                    <img class="h-8 w-8 rounded-full object-cover" src="<?php echo e($detail->student->user->photo ?? asset('global_assets/images/user.png')); ?>" alt="<?php echo e($detail->student->name); ?>">
                                                                </div>
                                                                <div class="ml-4">
                                                                    <div class="text-sm font-medium text-gray-900">
                                                                        <?php echo e($detail->student->name); ?>

                                                                    </div>
                                                                    <div class="text-xs text-gray-500">
                                                                        <?php echo e($detail->student->adm_no); ?>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($statusColors[$detail->status]); ?>">
                                                                <?php echo e(ucfirst($detail->status)); ?>

                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <?php echo e($detail->time_in ? \Carbon\Carbon::parse($detail->time_in)->format('H:i') : '—'); ?>

                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <?php echo e($detail->remarks ?: '—'); ?>

                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                                            No student details available
                                                        </td>
                                                    </tr>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <a href="<?php echo e(route('attendance.edit', $detailsData->id)); ?>" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Edit
                        </a>
                        <button @click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/partials/view-details-modal.blade.php ENDPATH**/ ?>