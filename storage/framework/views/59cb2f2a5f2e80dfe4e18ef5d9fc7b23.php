<!--[if BLOCK]><![endif]--><?php if($importResults && ($importResults['success'] || $importResults['errors'])): ?>
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Results header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Import Results
                </h3>
            </div>
            
            <!-- Statistics -->
            <div class="px-6 py-4 bg-white">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-500">Successfully Imported</h4>
                                <p class="mt-1 text-xl font-semibold text-gray-900"><?php echo e(count($importResults['success'] ?? [])); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-500">Failed Imports</h4>
                                <p class="mt-1 text-xl font-semibold text-gray-900"><?php echo e(count($importResults['errors'] ?? [])); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-500">Total Processed</h4>
                                <p class="mt-1 text-xl font-semibold text-gray-900"><?php echo e(count($importResults['success'] ?? []) + count($importResults['errors'] ?? [])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabs -->
            <div class="px-6 pt-4" x-data="{ activeTab: 'success' }">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button 
                            @click="activeTab = 'success'" 
                            :class="activeTab === 'success' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                            Successful Imports 
                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800"><?php echo e(count($importResults['success'] ?? [])); ?></span>
                        </button>
                        
                        <button 
                            @click="activeTab = 'errors'" 
                            :class="activeTab === 'errors' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                            Failed Imports 
                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800"><?php echo e(count($importResults['errors'] ?? [])); ?></span>
                        </button>
                    </nav>
                </div>
                
                <!-- Success List -->
                <div x-show="activeTab === 'success'" class="py-4">
                    <!--[if BLOCK]><![endif]--><?php if(count($importResults['success'] ?? []) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row #</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission #</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $importResults['success']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowNum => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($rowNum); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-sm font-medium text-green-700">
                                                        <?php echo e(substr($student['name'] ?? 'N/A', 0, 1)); ?>

                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900"><?php echo e($student['name'] ?? 'N/A'); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($student['admission_number'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($student['class'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Imported Successfully
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">No successful imports to display.</p>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Errors List -->
                <div x-show="activeTab === 'errors'" class="py-4">
                    <!--[if BLOCK]><![endif]--><?php if(count($importResults['errors'] ?? []) > 0): ?>
                        <ul class="divide-y divide-gray-200">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $importResults['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowNum => $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="py-4">
                                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-2">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800">
                                                    Error on Row <?php echo e($rowNum); ?>

                                                </h3>
                                                <div class="mt-2 text-sm text-red-700">
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <?php
                                                            // Safe error handling
                                                            $errorList = [];
                                                            
                                                            if (isset($error['errors']) && is_array($error['errors'])) {
                                                                $errorList = $error['errors'];
                                                            } elseif (isset($error['errors']) && is_string($error['errors'])) {
                                                                $errorList = [$error['errors']];
                                                            } elseif (is_string($error)) {
                                                                $errorList = [$error];
                                                            } elseif (is_array($error)) {
                                                                // Extract all string values that might be error messages
                                                                foreach ($error as $key => $value) {
                                                                    if (is_string($value) && $key !== 'row_data') {
                                                                        $errorList[] = $value;
                                                                    }
                                                                }
                                                            }
                                                            
                                                            // If we still don't have any errors, add a generic message
                                                            if (empty($errorList)) {
                                                                $errorList = ['Error details cannot be displayed properly. Please check the error report.'];
                                                            }
                                                        ?>
                                                        
                                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $errorList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $errorMsg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li><?php echo e($errorMsg); ?></li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!--[if BLOCK]><![endif]--><?php if(isset($error['row_data']) && is_array($error['row_data'])): ?>
                                        <div class="ml-8 mt-2">
                                            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Row Data:</h4>
                                            <div class="bg-gray-50 p-3 rounded-md">
                                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $error['row_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="text-sm">
                                                            <span class="font-medium text-gray-500"><?php echo e(ucfirst(str_replace('_', ' ', $field))); ?>:</span>
                                                            <span class="text-gray-900">
                                                                <!--[if BLOCK]><![endif]--><?php if(is_array($value)): ?>
                                                                    [Array]
                                                                <?php elseif(is_object($value)): ?>
                                                                    [Object]
                                                                <?php elseif(is_null($value)): ?>
                                                                    N/A
                                                                <?php else: ?>
                                                                    <?php echo e((string)$value); ?>

                                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                            </span>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </ul>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">No errors to display.</p>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <button 
                    type="button" 
                    wire:click="clearImportResults" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    Clear Results
                </button>
                
                <button 
                    type="button" 
                    wire:click="downloadErrorReport" 
                    class="ml-3 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    <?php if(empty($importResults['errors'])): ?> disabled <?php endif; ?>>
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Error Report
                </button>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/bulk-import/import-results.blade.php ENDPATH**/ ?>