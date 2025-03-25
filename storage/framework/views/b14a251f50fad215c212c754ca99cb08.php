<!-- Appeal Expulsion Modal -->
<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

<div class="fixed inset-0 overflow-y-auto">
    <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="relative bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Appeal Expulsion
                    </h3>
                    <div class="mt-2">
                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedStudent) && $selectedStudent): ?>
                        <p class="text-sm text-gray-500">
                            You are appealing the expulsion of <?php echo e($selectedStudent->first_name); ?> <?php echo e($selectedStudent->last_name); ?>. 
                            Please provide a detailed reason for this appeal.
                        </p>
                        
                        <!-- Appeal Reason Textarea -->
                        <div class="mt-4">
                            <label for="appealReason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Appeal</label>
                            <textarea
                                wire:model.defer="appealReason"
                                id="appealReason"
                                rows="4"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Enter detailed reason for appealing this expulsion..."
                            ></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['appealReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Student Expulsion Details -->
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700">Expulsion Details</h4>
                            <div class="mt-2 bg-gray-50 p-3 rounded-md text-sm text-gray-700">
                                <p><span class="font-medium">Reason:</span> <?php echo e($selectedStudent->expulsion_reason ?? 'N/A'); ?></p>
                                <p><span class="font-medium">Date:</span> <?php echo e(isset($selectedStudent->expulsion_date) ? $selectedStudent->expulsion_date->format('M d, Y') : 'N/A'); ?></p>
                                <p><span class="font-medium">Type:</span> <?php echo e(ucfirst($selectedStudent->expulsion_type ?? 'N/A')); ?></p>
                            </div>
                        </div>
                        <?php else: ?>
                        <p class="text-sm text-gray-500">
                            No student selected. Please select a student to appeal their expulsion.
                        </p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Notice Section -->
                        <div class="mt-4 bg-blue-50 p-3 rounded-md border border-blue-100">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        This appeal will be reviewed by school administration. The appeal process may take up to 5 business days.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <!--[if BLOCK]><![endif]--><?php if(isset($selectedStudent) && $selectedStudent): ?>
                <button 
                    wire:click="submitAppeal" 
                    wire:loading.attr="disabled"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    <span wire:loading.remove wire:target="submitAppeal">Submit Appeal</span>
                    <span wire:loading wire:target="submitAppeal" class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <button 
                    @click="showAppealModal = false" 
                    type="button" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/appeal-expulsion-modal.blade.php ENDPATH**/ ?>