<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0 border-b border-gray-100">
        <div class="flex items-center">
            <div class="bg-green-50 p-2 rounded-full mr-3">
                <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-medium text-gray-800">Timetable Records</h2>
                <p class="text-xs text-gray-500">
                    <!--[if BLOCK]><![endif]--><?php if($timetableRecords->isNotEmpty()): ?>
                    <?php echo e($timetableRecords->count()); ?> records found
                    <?php else: ?>
                    No records found for selected criteria
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </p>
            </div>
        </div>
        
        <button 
            wire:click="createTimetableRecord"
            class="<?php echo e($classId ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-200 text-gray-500 cursor-not-allowed'); ?> inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm"
            <?php echo e($classId ? '' : 'disabled'); ?>>
            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create New Timetable
        </button>
    </div>
    
    <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
        <div class="m-4 bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center mb-3">
                <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-full bg-green-50 text-green-600 mr-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </span>
                <h3 class="text-base font-medium text-gray-800"><?php echo e($timetableRecordForm['id'] ? 'Edit' : 'Create'); ?> Timetable Record</h3>
            </div>
            
            <form wire:submit.prevent="saveTimetableRecord">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="name" class="block text-sm font-medium text-gray-800">Timetable Name</label>
                            
                            <!--[if BLOCK]><![endif]--><?php if(isset($timetableRecordForm['is_auto_generated']) && $timetableRecordForm['is_auto_generated']): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Auto-generated
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Custom
                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div x-data="{
                            init() {
                                $watch('$wire.timetableRecordForm.name', value => {
                                    if (document.activeElement && document.activeElement.id === 'name') {
                                        $wire.timetableRecordForm.is_auto_generated = false;
                                    }
                                })
                            }
                        }">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                                <input type="text" id="name" wire:model.defer="timetableRecordForm.name" 
                                       wire:change="$set('timetableRecordForm.is_auto_generated', false)"
                                       placeholder="Enter timetable name" 
                                       class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-sm py-2 bg-white">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Names are automatically generated but can be customized. Duplicates will be made unique by adding a number.
                            </p>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['timetableRecordForm.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message ?? 'Invalid timetable name'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            </div>
                            <input type="text" id="description" wire:model.defer="timetableRecordForm.description" placeholder="Optional description" 
                                   class="pl-7 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-green-500 focus:border-green-500 text-xs py-2 bg-white">
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['timetableRecordForm.description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message ?? 'Invalid description'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <div class="col-span-full">
                        <div class="flex items-center mt-1">
                            <input id="is_active" type="checkbox" wire:model.defer="timetableRecordForm.is_active" 
                                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="is_active" class="ml-2 block text-xs text-gray-700">Set as active timetable</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end space-x-3">
                    <button type="button" 
                            wire:click="closeModal" 
                            class="inline-flex justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex justify-center px-3 py-2 border border-transparent shadow-sm text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        <?php echo e($timetableRecordForm['id'] ? 'Update' : 'Create'); ?>

                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <div class="p-4">
        <!--[if BLOCK]><![endif]--><?php if($timetableRecords->isEmpty()): ?>
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No timetable records</h3>
                <p class="mt-1 text-sm text-gray-500">Create a new timetable to get started</p>
                <div class="mt-3">
                    <button 
                        wire:click="createTimetableRecord"
                        class="<?php echo e($classId ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-200 text-gray-500 cursor-not-allowed'); ?> inline-flex items-center px-3 py-2 text-xs font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm"
                        <?php echo e($classId ? '' : 'disabled'); ?>>
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create New Timetable
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto shadow-sm rounded-lg border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $timetableRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-800"><?php echo e($record->name); ?></span>
                                        
                                        <?php
                                            // Check if the name follows the auto-generated pattern
                                            $isAutoGenerated = (preg_match('/^Timetable for .+ \(\w+ Term, \d{4}-\d{4}\)(\s\(\d+\))?$/', $record->name) === 1);
                                        ?>
                                        
                                        <!--[if BLOCK]><![endif]--><?php if($isAutoGenerated): ?>
                                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Auto
                                            </span>
                                        <?php else: ?>
                                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Custom
                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        
                                        <span class="ml-1" x-data="{ showTooltip: false }">
                                            <svg @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" class="w-4 h-4 text-gray-400 hover:text-gray-600 cursor-help" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div x-show="showTooltip" x-transition class="absolute z-10 w-64 px-3 py-2 ml-3 -mt-1 text-xs text-white bg-gray-800 rounded-lg shadow-lg">
                                                Timetable names are unique and automatically generated based on class, term, and session. If duplicate names exist, a sequential number is added (e.g., "(1)").
                                            </div>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500"><?php echo e($record->description ?? '-'); ?></td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <!--[if BLOCK]><![endif]--><?php if($record->is_active): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-right font-medium space-x-1">
                                    <button wire:click="editTimetableRecord(<?php echo e($record->id); ?>)" class="text-green-600 hover:text-green-900 focus:outline-none">
                                        Edit
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button wire:click="confirmDeleteTimetableRecord(<?php echo e($record->id); ?>)" class="text-red-600 hover:text-red-900 focus:outline-none">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    
    <!-- Confirmation Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showDeleteConfirmation): ?>
        <div class="fixed inset-0 z-30 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-base leading-6 font-medium text-gray-900" id="modal-title">
                                    Delete Timetable Record
                                </h3>
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500">
                                        Are you sure you want to delete this timetable record? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="deleteTimetableRecord" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-3 py-2 bg-red-600 text-xs font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-xs">
                            Delete
                        </button>
                        <button type="button" wire:click="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-3 py-2 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-xs">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/timetable/partials/timetable-records.blade.php ENDPATH**/ ?>