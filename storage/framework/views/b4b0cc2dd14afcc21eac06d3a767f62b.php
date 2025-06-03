<div class="overflow-hidden bg-white">
    <!-- Filters Section -->
    <div class="bg-white px-6 py-4 border-b border-gray-200">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <!-- Left section: Search box -->
            <div class="md:col-span-4">
                <label for="search-exams" class="block text-sm font-medium text-gray-700 mb-1">Search Exams</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input id="search-exams" wire:model.debounce.300ms="search" type="search" class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md" placeholder="Search by exam name...">
                </div>
            </div>

            <!-- Right section: Filters and pagination -->
            <div class="md:col-span-8">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="filter-year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <select id="filter-year" wire:model="filterYear" class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                            <option value="">All Years</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($yr); ?>"><?php echo e($yr); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>

                    <div>
                        <label for="filter-term" class="block text-sm font-medium text-gray-700 mb-1">Term</label>
                        <select id="filter-term" wire:model="filterTerm" class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                            <option value="">All Terms</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $terms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>

                    <div>
                        <label for="filter-grading" class="block text-sm font-medium text-gray-700 mb-1">Grading System</label>
                        <select id="filter-grading" wire:model="filterGrading" class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                            <option value="">All Grading Systems</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $gradingSystems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($gs->id); ?>"><?php echo e($gs->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>

                    <div>
                        <label for="per-page" class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                        <select id="per-page" wire:model="perPage" class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider w-1/4 bg-gray-50">
                        <div class="flex items-center">
                            <button wire:click="setSortField('name')" class="group inline-flex items-center hover:text-blue-700 focus:outline-none">
                                Name
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'name'): ?>
                                    <span class="ml-1.5 flex-shrink-0 text-blue-500">
                                        <!--[if BLOCK]><![endif]--><?php if($sortDirection === 'asc'): ?>
                                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        <?php else: ?>
                                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </button>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider w-1/12 bg-gray-50">
                        <div class="flex items-center">
                            <button wire:click="setSortField('term')" class="group inline-flex items-center hover:text-blue-700 focus:outline-none">
                                Term
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'term'): ?>
                                    <span class="ml-1.5 flex-shrink-0 text-blue-500">
                                        <!--[if BLOCK]><![endif]--><?php if($sortDirection === 'asc'): ?>
                                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        <?php else: ?>
                                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </button>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider w-1/12 bg-gray-50">
                        <div class="flex items-center">
                            <button wire:click="setSortField('year')" class="group inline-flex items-center hover:text-blue-700 focus:outline-none">
                                Year
                                <!--[if BLOCK]><![endif]--><?php if($sortField === 'year'): ?>
                                    <span class="ml-1.5 flex-shrink-0 text-blue-500">
                                        <!--[if BLOCK]><![endif]--><?php if($sortDirection === 'asc'): ?>
                                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        <?php else: ?>
                                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </button>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider w-1/6 bg-gray-50">Grading System</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider w-1/4 bg-gray-50">Classes & Sections</th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-700 uppercase tracking-wider w-1/6 bg-gray-50">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!--[if BLOCK]><![endif]--><?php if(isset($exams) && method_exists($exams, 'total') && $exams->total() > 0): ?>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($exam->name); ?></div>
                                <div class="text-xs text-gray-500">ID: <?php echo e($exam->id); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?php echo e($terms[$exam->term] ?? "Term {$exam->term}"); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <?php echo e($exam->year); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!--[if BLOCK]><![endif]--><?php if($exam->gradingSystem): ?>
                                    <button wire:click="showGradingSystemDetails(<?php echo e($exam->grading_system_id); ?>)" class="inline-flex items-center text-sm text-green-600 hover:text-green-900 focus:outline-none transition-colors duration-200 px-2 py-1 rounded-md hover:bg-green-50">
                                        <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                        </svg>
                                        <?php echo e($exam->gradingSystem->name); ?>

                                    </button>
                                <?php else: ?>
                                    <span class="text-sm text-gray-500 italic">Not specified</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </td>
                            <td class="px-6 py-4">
                                <!--[if BLOCK]><![endif]--><?php if($exam->examSchedules && $exam->examSchedules->count() > 0): ?>
                                    <?php
                                        // Group schedules by class
                                        $groupedSchedules = $exam->examSchedules->groupBy(fn($schedule) => $schedule->myClass->id ?? 'unknown');
                                    ?>

                                    <div class="space-y-2">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $groupedSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classId => $schedules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <!--[if BLOCK]><![endif]--><?php if($classId != 'unknown' && isset($schedules->first()->myClass)): ?>
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <?php echo e($schedules->first()->myClass->name); ?>

                                                    </span>

                                                    <!-- Show sections -->
                                                    <?php
                                                        $sections = $schedules->pluck('section')->filter()->unique('id');
                                                    ?>

                                                    <!--[if BLOCK]><![endif]--><?php if($sections->count() > 0): ?>
                                                        <span class="ml-2 text-xs text-gray-500">
                                                            (<?php echo e($sections->pluck('name')->join(', ')); ?>)
                                                        </span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php else: ?>
                                    <span class="text-sm text-gray-500">No classes assigned</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <button wire:click="showDetails(<?php echo e($exam->id); ?>)" class="p-1.5 bg-green-50 text-green-600 hover:text-green-900 hover:bg-green-100 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200" data-tippy-content="View Details">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <!--[if BLOCK]><![endif]--><?php if(Qs::isAdministratorOrTeacher()): ?>
                                        <button wire:click="edit(<?php echo e($exam->id); ?>)" class="p-1.5 bg-green-50 text-green-600 hover:text-green-900 hover:bg-green-100 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200" data-tippy-content="Edit Exam">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>

                                        <button wire:click="confirmDelete(<?php echo e($exam->id); ?>)" class="p-1.5 bg-red-50 text-red-600 hover:text-red-900 hover:bg-red-100 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200" data-tippy-content="Delete Exam">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center justify-center py-8">
                                <div class="bg-gray-100 rounded-full p-3 mb-2">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">No exams found</h3>
                                <p class="mt-1 text-sm text-gray-500 max-w-md">
                                    <!--[if BLOCK]><![endif]--><?php if($search || $filterYear || $filterTerm || $filterGrading): ?>
                                        No exams match your filter criteria. Try adjusting your filters.
                                    <?php else: ?>
                                        Get started by creating a new exam.
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </p>
                                <?php if(Qs::isAdministratorOrTeacher() && !$search && !$filterYear && !$filterTerm && !$filterGrading): ?>
                                    <div class="mt-6">
                                        <button wire:click="create" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Create New Exam
                                        </button>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if(isset($exams) && method_exists($exams, 'links')): ?>
        <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-white">
            <?php echo e($exams->links()); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>

<!-- Delete Confirmation Modal -->
<!--[if BLOCK]><![endif]--><?php if($confirmingDelete): ?>
<div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Delete Exam
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete this exam? This action cannot be undone and all related data will be permanently removed.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button wire:click="deleteExam" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                    Delete
                </button>
                <button wire:click="$set('confirmingDelete', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH C:\projects\MbukuErp\resources\views/livewire/exams/partials/exams-tab.blade.php ENDPATH**/ ?>