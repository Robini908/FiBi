<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle max-w-5xl w-full">
            <div class="bg-blue-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-white p-2 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-white">
                            Bulk Teacher Assignment
                        </h2>
                        <p class="text-sm text-blue-100 mt-1">
                            Efficiently assign teachers to multiple sections in a class
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white px-6 pt-5 pb-4">
                        <!--[if BLOCK]><![endif]--><?php if($showBulkResults): ?>
                            <!-- Results Panel -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                                <div class="px-5 py-4 bg-gray-50 border-b border-gray-200">
                                    <h4 class="text-base font-medium flex items-center text-gray-700">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        Assignment Results
                                    </h4>
                                </div>
                                
                                <div class="p-5">
                                    <p class="text-sm text-gray-600 mb-4">The following assignments have been processed:</p>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                                            <div class="flex items-center">
                                                <div class="p-2 rounded-full bg-gray-100">
                                                    <svg class="h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-xs font-medium text-gray-500 uppercase">Total Processed</p>
                                                    <p class="text-lg font-bold text-gray-900"><?php echo e($bulkAssignmentResults['total']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                                            <div class="flex items-center">
                                                <div class="p-2 rounded-full bg-green-100">
                                                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                        <p class="text-xs font-medium text-green-500 uppercase">Created</p>
                                                    <p class="text-lg font-bold text-green-600"><?php echo e($bulkAssignmentResults['created']); ?></p>
                                                </div>
                                            </div>
                                    </div>
                                    
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                                            <div class="flex items-center">
                                                <div class="p-2 rounded-full bg-yellow-100">
                                                    <svg class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                        <p class="text-xs font-medium text-yellow-500 uppercase">Skipped</p>
                                                    <p class="text-lg font-bold text-yellow-600"><?php echo e($bulkAssignmentResults['skipped']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                                            <div class="flex items-center">
                                                <div class="p-2 rounded-full bg-red-100">
                                                    <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-xs font-medium text-red-500 uppercase">Errors</p>
                                                    <p class="text-lg font-bold text-red-600"><?php echo e($bulkAssignmentResults['errors']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                    <button 
                                        wire:click="closeBulkModal" 
                                        type="button" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150"
                                    >
                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Complete
                                    </button>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Important Guidelines Notice -->
                            <div class="mb-6">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 overflow-hidden">
                                    <div class="px-4 py-3 bg-blue-600 bg-opacity-10">
                                        <h4 class="text-base font-semibold text-blue-800 flex items-center">
                                    <svg class="h-5 w-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                            Assignment Guidelines
                                </h4>
                                    </div>
                                    <div class="p-4">
                                        <ul class="mt-1 space-y-2">
                                            <li class="flex items-start">
                                                <svg class="h-5 w-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-sm text-gray-700">Create specific teacher-subject pairings</span>
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="h-5 w-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-sm text-gray-700">Each subject should have <strong>only one primary teacher</strong> per class/section per term</span>
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="h-5 w-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-sm text-gray-700">Select sections from a class and apply teacher-subject pairings</span>
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="h-5 w-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-sm text-gray-700">To replace existing primary teachers, check the "Override Existing" option</span>
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="h-5 w-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-sm text-gray-700">For non-primary teacher assignments (assistants, etc.), uncheck "Primary Assignment"</span>
                                            </li>
                                </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Advanced Information Toggle -->
                            <div x-data="{ showAdvanced: false }" class="mb-6">
                                <button 
                                    @click="showAdvanced = !showAdvanced" 
                                    type="button" 
                                    class="flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200 text-sm focus:outline-none"
                                >
                                    <svg 
                                        :class="{'transform rotate-180': showAdvanced}" 
                                        class="w-4 h-4 mr-1.5 transition-transform duration-200" 
                                        xmlns="http://www.w3.org/2000/svg" 
                                        viewBox="0 0 20 20" 
                                        fill="currentColor"
                                    >
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    <span x-text="showAdvanced ? 'Hide validation information' : 'Show validation information'"></span>
                                </button>
                                
                                <div x-show="showAdvanced" x-cloak class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200 text-sm text-gray-700">
                                    <h5 class="font-medium text-gray-900 mb-2">System validation checks:</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 h-5 w-5 text-green-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <p class="ml-2">Primary teacher assignment validation</p>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 h-5 w-5 text-green-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <p class="ml-2">Subject count vs. curriculum requirements</p>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 h-5 w-5 text-green-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <p class="ml-2">Teacher workload warnings (>20 assignments)</p>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 h-5 w-5 text-green-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <p class="ml-2">Scheduling conflicts detection</p>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-xs text-gray-500">These validations help maintain data integrity and optimize the timetable generation process.</p>
                                </div>
                            </div>
                            
                            <!-- Form -->
                            <form id="bulk-assignment-form" wire:submit.prevent="saveBulkAssignments" class="mt-4">
                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Academic Settings -->
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                                        <div class="px-5 py-4 bg-gray-50 border-b border-gray-200">
                                            <h4 class="text-base font-medium flex items-center text-gray-700">
                                                <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Academic Period
                                            </h4>
                                        </div>
                                        <div class="p-5">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Academic Year -->
                                            <div>
                                                    <label for="academicYearId" class="block text-sm font-medium text-gray-700 mb-1">Academic Year*</label>
                                                    <div class="relative rounded-md shadow-sm">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                        <select 
                                                            id="academicYearId" 
                                                            wire:model="bulkForm.academic_year_id" 
                                                            class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                        >
                                                    <option value="">Select academic year</option>
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $academicYear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <!--[if BLOCK]><![endif]--><?php if(is_array($academicYear)): ?>
                                                            <option value="<?php echo e($academicYear['id']); ?>" <?php echo e(isset($academicYear['is_current']) && $academicYear['is_current'] ? 'class=font-bold' : ''); ?>>
                                                                <?php echo e($academicYear['name']); ?> <?php echo e(isset($academicYear['is_current']) && $academicYear['is_current'] ? '(Current)' : ''); ?>

                                                            </option>
                                                        <?php elseif(is_object($academicYear)): ?>
                                                            <option value="<?php echo e($academicYear->id); ?>"><?php echo e($academicYear->year); ?></option>
                                                        <?php else: ?>
                                                            <option value="<?php echo e($academicYear); ?>"><?php echo e($academicYear); ?></option>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </select>
                                                    </div>
                                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['bulkForm.academic_year_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> 
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>

                                            <!-- Academic Term -->
                                            <div>
                                                    <label for="academicTerm" class="block text-sm font-medium text-gray-700 mb-1">Academic Term*</label>
                                                    <div class="relative rounded-md shadow-sm">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                            </svg>
                                                        </div>
                                                        <select 
                                                            id="academicTerm" 
                                                            wire:model="bulkForm.academic_term" 
                                                            class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                        >
                                                    <option value="">Select term</option>
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $academicTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($term); ?>"><?php echo e($term); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </select>
                                                    </div>
                                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['bulkForm.academic_term'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> 
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Teacher-Subject Mapping -->
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
                                        x-data="{
                                            mappings: <?php if ((object) ('teacherSubjectMappings') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('teacherSubjectMappings'->value()); ?>')<?php echo e('teacherSubjectMappings'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('teacherSubjectMappings'); ?>')<?php endif; ?>,
                                            addMapping() {
                                                this.$wire.addTeacherSubjectMapping();
                                            },
                                            removeMapping(id) {
                                                this.$wire.removeTeacherSubjectMapping(id);
                                            }
                                        }"
                                    >
                                        <div class="px-5 py-4 bg-gray-50 border-b border-gray-200">
                                            <h4 class="text-base font-medium flex items-center text-gray-700">
                                                <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                Teacher-Subject Mapping
                                            </h4>
                                        </div>
                                        
                                        <div class="p-5">
                                            <p class="text-sm text-gray-600 mb-4">Assign teachers to specific subjects with the pairings below:</p>
                                            
                                            <div class="space-y-4">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $teacherSubjectMappings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mappingId => $mapping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="flex flex-col sm:flex-row gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 relative">
                                                    <div class="w-full sm:w-1/2">
                                                        <label for="teacher-<?php echo e($mappingId); ?>" class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                                                            <div class="relative rounded-md shadow-sm">
                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                </div>
                                                        <select 
                                                            id="teacher-<?php echo e($mappingId); ?>" 
                                                            wire:model.live="teacherSubjectMappings.<?php echo e($mappingId); ?>.teacherId" 
                                                                    class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                        >
                                                            <option value="">Select a teacher</option>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                        </select>
                                                            </div>
                                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["teacherSubjectMappings.{$mappingId}.teacherId"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> 
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                    
                                                    <div class="w-full sm:w-1/2">
                                                        <label for="subject-<?php echo e($mappingId); ?>" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                                            <div class="relative rounded-md shadow-sm">
                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                                    </svg>
                                                                </div>
                                                            <select 
                                                                id="subject-<?php echo e($mappingId); ?>" 
                                                                wire:model.live="teacherSubjectMappings.<?php echo e($mappingId); ?>.subjectId" 
                                                                    class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                            >
                                                                <option value="">Select a subject</option>
                                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->subject_name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            </select>
                                                            
                                                                <!--[if BLOCK]><![endif]--><?php if(count($teacherSubjectMappings) > 1): ?>
                                                            <button 
                                                                type="button" 
                                                                        class="absolute inset-y-0 right-0 flex items-center px-2 text-red-500 hover:text-red-700 focus:outline-none"
                                                                wire:click="removeTeacherSubjectMapping(<?php echo e($mappingId); ?>)"
                                                            >
                                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        </div>
                                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["teacherSubjectMappings.{$mappingId}.subjectId"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> 
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                        
                                                        <!-- Mapping number indicator -->
                                                        <div class="absolute top-0 right-0 -mt-3 -mr-3 bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium">
                                                            <?php echo e($loop->iteration); ?>

                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                        
                                        <!-- Add mapping button -->
                                            <div class="mt-4 flex justify-center">
                                            <button 
                                                type="button" 
                                                wire:click="addTeacherSubjectMapping"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150"
                                            >
                                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Add Another Teacher-Subject Pair
                                            </button>
                                        </div>
                                        
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['teacherSubjectMappings'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                                <div class="mt-2 bg-red-50 border-l-4 border-red-500 p-4">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm text-red-700"><?php echo e($message); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Class Details -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                                <div class="px-5 py-4 bg-gray-50 border-b border-gray-200">
                                    <h4 class="text-base font-medium flex items-center text-gray-700">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Class Details
                                    </h4>
                                </div>
                                
                                <div class="p-5">
                                    <p class="text-sm text-gray-600 mb-4">Select a class for teacher assignment:</p>
                                    
                                    <div>
                                        <label for="class_section_id" class="block text-sm font-medium text-gray-700 mb-1">Class Section</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <select 
                                                id="class_section_id" 
                                                wire:model.live="classSectionId" 
                                                class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            >
                                                <option value="">Select a class section</option>
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $classSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($classSection->id); ?>"><?php echo e($classSection->class->name); ?> <?php echo e($classSection->section->name ?? ''); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            </select>
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['classSectionId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['bulkForm.selected_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                            <p class="mt-1 text-sm text-red-600">Please select a class section</p> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>

                            <!-- Assignment Options -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                                <div class="px-5 py-4 bg-gray-50 border-b border-gray-200">
                                    <h4 class="text-base font-medium flex items-center text-gray-700">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Assignment Options
                                    </h4>
                                </div>
                                
                                <div class="p-5">
                                    <p class="text-sm text-gray-600 mb-4">Configure how these assignments will be applied:</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                    <div class="flex items-center">
                                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 w-full">
                                                    <div class="flex items-start">
                                                        <div class="flex items-center h-5">
                                        <input 
                                            id="isPrimary" 
                                            wire:model="bulkForm.is_primary" 
                                            type="checkbox" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label for="isPrimary" class="font-medium text-gray-700">Primary Assignment</label>
                                                            <p class="text-gray-500">Teacher will be the primary instructor for this subject</p>
                                                        </div>
                                                    </div>
                                                </div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 w-full">
                                                    <div class="flex items-start">
                                                        <div class="flex items-center h-5">
                                        <input 
                                            id="isActive" 
                                            wire:model="bulkForm.is_active" 
                                            type="checkbox" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label for="isActive" class="font-medium text-gray-700">Active Assignment</label>
                                                            <p class="text-gray-500">Teacher will be actively teaching (not archived)</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    
                                        <div class="space-y-4">
                                    <div class="flex items-center">
                                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 w-full">
                                                    <div class="flex items-start">
                                                        <div class="flex items-center h-5">
                                        <input 
                                            id="overrideExisting" 
                                            wire:model="bulkForm.override_existing" 
                                            type="checkbox" 
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label for="overrideExisting" class="font-medium text-gray-700">Override Existing</label>
                                                            <p class="text-gray-500">Replace any existing assignments for these subjects</p>
                                                        </div>
                                                    </div>
                                    </div>
                                </div>
                                
                                            <div>
                                                <label for="bulkNotes" class="block text-sm font-medium text-gray-700 mb-1">Assignment Notes</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                    <textarea 
                                        id="bulkNotes" 
                                        wire:model="bulkForm.notes" 
                                        rows="2" 
                                                        class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                        placeholder="Add any additional information about these assignments"
                                    ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
            
            <!--[if BLOCK]><![endif]--><?php if(!$showBulkResults): ?>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 sm:flex sm:flex-row-reverse">
                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        form="bulk-assignment-form"
                        class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150"
                    >
                        <svg wire:loading.class="hidden" wire:target="saveBulkAssignments" class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <svg wire:loading wire:target="saveBulkAssignments" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.class="hidden" wire:target="saveBulkAssignments">
                            Create Assignments
                        </span>
                        <span wire:loading wire:target="saveBulkAssignments">
                            Processing...
                        </span>
                    </button>
                    
                    <!-- Cancel Button -->
                    <button 
                        wire:click="closeBulkModal" 
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150"
                    >
                        <svg class="w-5 h-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
</div>
</div>
</div><?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/subject-teacher-assignment/bulk-modal.blade.php ENDPATH**/ ?>