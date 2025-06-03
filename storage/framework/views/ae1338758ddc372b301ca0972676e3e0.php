<div class="px-4 py-5 sm:p-6">
    <!--[if BLOCK]><![endif]--><?php if($student): ?>
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
        <div class="px-4 py-4 sm:px-6 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900">Attendance Records</h3>
                <div class="flex flex-wrap items-center space-x-2">
                    <div class="relative">
                        <input wire:model.debounce.300ms="searchTerm" type="text" placeholder="Search records..." class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sort('attendance_date')">
                            <div class="flex items-center space-x-1">
                                <span>Date</span>
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'attendance_date'): ?>
                                    <span>
                                        <!--[if BLOCK]><![endif]--><?php if($sortDirection === 'asc'): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Session
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
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $paginatedDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e(\Carbon\Carbon::parse($detail->attendanceRecord->attendance_date)->format('D, M d, Y')); ?>

                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo e(\Carbon\Carbon::parse($detail->attendanceRecord->attendance_date)->format('l')); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php if($detail->status === 'present'): ?>
                                        bg-green-100 text-green-800
                                    <?php elseif($detail->status === 'absent'): ?>
                                        bg-red-100 text-red-800
                                    <?php elseif($detail->status === 'late'): ?>
                                        bg-yellow-100 text-yellow-800
                                    <?php elseif($detail->status === 'excused'): ?>
                                        bg-blue-100 text-blue-800
                                    <?php elseif($detail->status === 'sick'): ?>
                                        bg-purple-100 text-purple-800
                                    <?php elseif($detail->status === 'on_leave'): ?>
                                        bg-indigo-100 text-indigo-800
                                    <?php else: ?>
                                        bg-gray-100 text-gray-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $detail->status))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($detail->attendanceRecord->session_type === 'morning' ? 'bg-blue-100 text-blue-800' : 
                                    ($detail->attendanceRecord->session_type === 'afternoon' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800')); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $detail->attendanceRecord->session_type))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($detail->time_in ? \Carbon\Carbon::parse($detail->time_in)->format('H:i') : '—'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="max-w-xs truncate">
                                    <?php echo e($detail->remarks ?: '—'); ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="mt-2 font-medium text-gray-900">No attendance records found</span>
                                    <p class="mt-1 text-gray-500">Try adjusting your date range or filters</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>
        
        <!--[if BLOCK]><![endif]--><?php if($totalRecords > 0): ?>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Showing <?php echo e(count($paginatedDetails)); ?> of <?php echo e($totalRecords); ?> records
                    </div>
                    <div class="flex-1 flex justify-between sm:justify-end">
                        <!--[if BLOCK]><![endif]--><?php if(($page ?? 1) > 1): ?>
                            <button wire:click="previousPage" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <?php if(($page ?? 1) * $perPage < $totalRecords): ?>
                            <button wire:click="nextPage" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/attendance/partials/student-history-table.blade.php ENDPATH**/ ?>