<div class="bg-white rounded-lg shadow-sm" x-data="{ processing: false, showDisapprovalForm: false }">
    <!-- Modern header with status indicator -->
    <div class="relative overflow-hidden rounded-t-lg">
        <!-- Background gradient pattern -->
        <div class="absolute inset-0 bg-gradient-to-r from-green-50 to-white opacity-70"></div>
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-green-500"></div>
        
        <div class="relative px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Student Approval
            </h3>
            
            <div x-data="{ tooltip: false }" class="relative">
                <button 
                    wire:click="closeAction" 
                    @mouseenter="tooltip = true" @mouseleave="tooltip = false"
                    class="rounded-full p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors"
                    aria-label="Close"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <!-- Tooltip -->
                <div x-show="tooltip" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1" 
                     class="absolute bottom-full mb-2 right-0 px-2 py-1 bg-gray-900 text-white text-xs rounded pointer-events-none whitespace-nowrap">
                    Close
                    <div class="absolute top-full right-2 h-2 w-2 rotate-45 bg-gray-900"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <!-- Student Profile Card -->
        <div class="mb-6 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex items-center">
                    <!-- Student avatar with larger size -->
                    <div class="flex-shrink-0">
                        <!--[if BLOCK]><![endif]--><?php if($selectedStudent->photo): ?>
                            <img src="<?php echo e(asset($selectedStudent->photo)); ?>" alt="<?php echo e($selectedStudent->first_name); ?>" 
                                class="h-16 w-16 rounded-full object-cover border-2 border-green-100">
                        <?php else: ?>
                            <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-green-700 font-medium text-lg"><?php echo e(substr($selectedStudent->first_name, 0, 1)); ?><?php echo e(substr($selectedStudent->last_name, 0, 1)); ?></span>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <!-- Student name and details with improved typography -->
                    <div class="ml-5 flex-1">
                        <h4 class="text-lg font-medium text-gray-900"><?php echo e($selectedStudent->first_name); ?> <?php echo e($selectedStudent->last_name); ?></h4>
                        <p class="text-sm text-gray-500 mt-0.5">Admission No: <?php echo e($selectedStudent->adm_no); ?></p>
                    </div>
                    
                    <!-- Status indicator -->
                    <div class="ml-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Pending Approval
                        </span>
                    </div>
                </div>
                
                <!-- Detailed information in card format -->
                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="px-4 py-3 bg-gray-50 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Class & Section</p>
                        <p class="mt-1 text-sm font-medium text-gray-800"><?php echo e($selectedStudent->my_class->name ?? 'N/A'); ?> - <?php echo e($selectedStudent->section->name ?? 'N/A'); ?></p>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Year Admitted</p>
                        <p class="mt-1 text-sm font-medium text-gray-800"><?php echo e($selectedStudent->year_admitted); ?></p>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 truncate"><?php echo e($selectedStudent->email ?? 'N/A'); ?></p>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 rounded-lg sm:col-span-2 md:col-span-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Parent Information</p>
                        <div class="mt-1 flex items-center">
                            <svg class="h-4 w-4 text-gray-500 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="text-sm font-medium text-gray-800">
                                <?php echo e($selectedStudent->parent_detail->parent_first_name ?? 'N/A'); ?> <?php echo e($selectedStudent->parent_detail->parent_last_name ?? 'N/A'); ?>

                                <!--[if BLOCK]><![endif]--><?php if($selectedStudent->parent_detail && $selectedStudent->parent_detail->parent_phone_number): ?>
                                <span class="text-gray-500 ml-3">
                                    <svg class="inline-block h-3.5 w-3.5 text-gray-500 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <?php echo e($selectedStudent->parent_detail->parent_phone_number); ?>

                                </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Approval notice with improved design -->
        <div class="mb-6 p-5 bg-white border-l-4 border-l-green-500 border-t border-b border-r border-gray-200 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-800">Approval Confirmation</h4>
                    <p class="mt-1 text-sm text-gray-600">
                        Approving this student will:
                    </p>
                    <ul class="mt-2 text-sm text-gray-600 space-y-1 list-inside list-disc">
                        <li>Grant full access to school digital resources</li>
                        <li>Include them in all class activities and communications</li>
                        <li>Send a confirmation email to their parent/guardian</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Google-inspired Action buttons -->
        <div class="space-y-5">
            <!-- Primary actions in Google style - flat, minimal with proper spacing -->
            <div class="flex justify-end gap-3">
                <button 
                    wire:click="closeAction" 
                    x-bind:disabled="processing"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 rounded-md transition-colors disabled:opacity-50"
                >
                    Cancel
                </button>
                
                <button 
                    @click="showDisapprovalForm = !showDisapprovalForm" 
                    x-bind:disabled="processing"
                    class="px-4 py-2 text-sm font-medium"
                    :class="showDisapprovalForm ? 
                        'text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-md transition-colors disabled:opacity-50' : 
                        'text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 rounded-md transition-colors disabled:opacity-50'"
                >
                    <span x-text="showDisapprovalForm ? 'Cancel Disapproval' : 'Disapprove'">Disapprove</span>
                </button>
                
                <button 
                    wire:click="confirmApproval" 
                    x-on:click="processing = true"
                    x-bind:disabled="processing || showDisapprovalForm"
                    class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors disabled:opacity-50"
                >
                    <span x-show="!processing">Approve</span>
                    <span x-show="processing" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
            
            <!-- Disapproval Form - Google-style clean look -->
            <div 
                x-show="showDisapprovalForm" 
                x-transition:enter="transition ease-out duration-200" 
                x-transition:enter-start="opacity-0 transform -translate-y-2" 
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150" 
                x-transition:leave-start="opacity-100 transform translate-y-0" 
                x-transition:leave-end="opacity-0 transform -translate-y-2" 
                class="mt-4 p-5 bg-white rounded-md border border-gray-200"
            >
                <h4 class="text-base font-medium text-gray-800 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Reason for Disapproval
                </h4>
                <div class="space-y-4">
                    <div>
                        <textarea 
                            wire:model.live="disapprovalReason" 
                            rows="3" 
                            placeholder="Please provide a detailed reason for disapproval..."
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full text-sm border-gray-300 rounded-md"
                        ></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['disapprovalReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1.5 text-sm text-red-600 flex items-center">
                                <svg class="h-4 w-4 mr-1.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    
                    <!-- Google-style action button -->
                    <div class="flex justify-end">
                        <button 
                            wire:click="cancelApproval" 
                            x-on:click="processing = true"
                            x-bind:disabled="processing"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors disabled:opacity-50"
                        >
                            <span x-show="!processing">Confirm Disapproval</span>
                            <span x-show="processing" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/student-approval.blade.php ENDPATH**/ ?>