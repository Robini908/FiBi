<?php $__env->startSection('page_title', 'Grading System Details'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <nav class="text-sm" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex text-gray-500">
            <li class="flex items-center">
                <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-gray-700">Dashboard</a>
                <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                </svg>
            </li>
            <li class="flex items-center">
                <a href="<?php echo e(route('exams.grading-systems.index')); ?>" class="hover:text-gray-700">Grading Systems</a>
                <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                </svg>
            </li>
            <li class="text-gray-700"><?php echo e($gradingSystem->name); ?></li>
        </ol>
    </nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_action_buttons'); ?>
    <a 
        href="<?php echo e(route('exams.grading-systems.index')); ?>" 
        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
    >
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Back to List</span>
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <!-- Grading System Header -->
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
            <div class="flex items-center">
                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($gradingSystem->name); ?></h3>
                <span class="badge badge-outline ml-3">
                    <?php echo e(date('d M, Y', strtotime($gradingSystem->effective_date))); ?>

                </span>
            </div>
            <?php if($gradingSystem->description): ?>
                <p class="mt-2 text-sm text-gray-600"><?php echo e($gradingSystem->description); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="p-6">
            <!-- Basic Information -->
            <div class="mb-8">
                <h4 class="text-base font-medium text-gray-900 mb-3">Basic Information</h4>
                <div class="bg-gray-50 rounded-lg p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Name</p>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($gradingSystem->name); ?></p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Effective Date</p>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e(date('d M, Y', strtotime($gradingSystem->effective_date))); ?></p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Description</p>
                        <p class="mt-1 text-sm text-gray-900"><?php echo e($gradingSystem->description ?: 'No description available'); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Grade Ranges -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-base font-medium text-gray-900">Grade Ranges</h4>
                    <a 
                        href="<?php echo e(route('exams.grading-systems.ranges.edit', $gradingSystem->id)); ?>" 
                        class="btn btn-sm bg-green-100 hover:bg-green-200 text-green-800 border-green-200 gap-1"
                    >
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        <span>Edit Ranges</span>
                    </a>
                </div>
                
                <?php if($gradingSystem->gradingRanges->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-3 px-4">Grade</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-3 px-4">Min Score</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-3 px-4">Max Score</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-3 px-4">Points</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-3 px-4">Comment</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php $__currentLoopData = $gradingSystem->gradingRanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-medium text-gray-900"><?php echo e($range->grade); ?></td>
                                        <td class="py-3 px-4 text-sm text-gray-700"><?php echo e($range->min_score); ?></td>
                                        <td class="py-3 px-4 text-sm text-gray-700"><?php echo e($range->max_score); ?></td>
                                        <td class="py-3 px-4 text-sm text-gray-700"><?php echo e($range->points); ?></td>
                                        <td class="py-3 px-4 text-sm text-gray-700"><?php echo e($range->comment ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No grade ranges</h3>
                        <p class="mt-1 text-sm text-gray-500">This grading system doesn't have any grade ranges defined yet.</p>
                        <div class="mt-4">
                            <a 
                                href="<?php echo e(route('exams.grading-systems.ranges.create', $gradingSystem->id)); ?>" 
                                class="btn btn-sm bg-green-600 hover:bg-green-700 text-white border-none"
                            >
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add Grade Range
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
            <a 
                href="<?php echo e(route('exams.grading-systems.index')); ?>" 
                class="btn btn-outline mr-2"
            >
                Back to List
            </a>
            
            <a 
                href="<?php echo e(route('exams.grading-systems.edit', $gradingSystem->id)); ?>" 
                class="btn bg-green-600 hover:bg-green-700 text-white border-none"
            >
                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit Grading System
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\projects\MbukuErp\resources\views/exams/grading-systems/show.blade.php ENDPATH**/ ?>