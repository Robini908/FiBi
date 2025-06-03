<div class="bg-white rounded-lg shadow-sm overflow-hidden" x-data="{ processing: false }">
    <!-- Header with accent -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-green-50 to-white">
        <h3 class="text-lg font-medium text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Send Email to Student
        </h3>
        <button 
            wire:click="closeAction" 
            class="text-gray-500 hover:text-gray-700 focus:outline-none transition-colors"
            aria-label="Close"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div class="p-6">
        <!-- Recipient Details -->
        <div class="mb-6 bg-gray-50 rounded-lg p-4">
            <div class="flex items-start">
                <!-- Student avatar or initials -->
                <div class="flex-shrink-0">
                    <!--[if BLOCK]><![endif]--><?php if($selectedStudent->photo): ?>
                        <img src="<?php echo e(asset($selectedStudent->photo)); ?>" alt="<?php echo e($selectedStudent->first_name); ?>" class="h-12 w-12 rounded-full object-cover">
                    <?php else: ?>
                        <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-green-700 font-medium text-sm"><?php echo e(substr($selectedStudent->first_name, 0, 1)); ?><?php echo e(substr($selectedStudent->last_name, 0, 1)); ?></span>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Recipient details -->
                <div class="ml-4 flex-1">
                    <div class="flex items-center">
                        <h4 class="text-base font-medium text-gray-900"><?php echo e($selectedStudent->first_name); ?> <?php echo e($selectedStudent->last_name); ?></h4>
                        <div class="ml-3 px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                            <?php echo e($selectedStudent->adm_no); ?>

                        </div>
                    </div>
                    <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Email:</span> <?php echo e($selectedStudent->email ?? 'N/A'); ?>

                        </div>
                        <div class="text-gray-600">
                            <span class="font-medium text-gray-700">Class:</span> <?php echo e($selectedStudent->my_class->name ?? 'N/A'); ?> - <?php echo e($selectedStudent->section->name ?? 'N/A'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Email Composition Form -->
        <div class="space-y-6">
            <!-- Subject Line -->
            <div>
                <label for="emailSubject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <div>
                    <input 
                        type="text" 
                        wire:model.live="emailSubject" 
                        id="emailSubject" 
                        placeholder="Enter email subject..."
                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    >
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['emailSubject'];
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

            <!-- Email Content -->
            <div>
                <label for="notificationContent" class="block text-sm font-medium text-gray-700 mb-1">Email Content</label>
                <div>
                    <textarea 
                        wire:model="notificationContent" 
                        id="notificationContent" 
                        rows="6" 
                        placeholder="Compose your email message here..."
                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    ></textarea>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['notificationContent'];
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
                <p class="mt-2 text-xs text-gray-500">
                    You can use basic formatting in your message. To format text, use HTML tags.
                </p>
            </div>
            
            <!-- File Attachment -->
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Attachments</label>
                <div class="mt-1">
                    <div class="flex items-center">
                        <label 
                            for="file" 
                            class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none"
                        >
                            <div class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                                <span>Attach File</span>
                            </div>
                    <input id="file" type="file" wire:model.live="file" class="sr-only">
                </label>
            </div>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600"><?php echo e($message ?? 'Invalid file'); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            
            <!--[if BLOCK]><![endif]--><?php if($file): ?>
                        <div class="mt-3 flex items-center p-2 rounded-md bg-green-50 border border-green-100">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                            <div class="flex-1 text-sm text-green-700 truncate"><?php echo e($file->getClientOriginalName()); ?></div>
                            <button 
                                type="button" 
                                wire:click="removeFile" 
                                class="ml-2 text-green-600 hover:text-green-800"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
            
            <!-- CC Parent -->
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    wire:model.live="ccParent" 
                    id="ccParent" 
                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                >
                <label for="ccParent" class="ml-2 block text-sm text-gray-700">
                    Also send a copy to parent (<?php echo e($selectedStudent->parent_detail->parent_email ?? 'No parent email available'); ?>)
                </label>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row sm:justify-end gap-3">
            <button 
                wire:click="closeAction" 
                class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
            >
                Cancel
            </button>
            
            <button 
                wire:click="sendStudentMail(<?php echo e($selectedStudent->id); ?>)" 
                x-on:click="processing = true"
                x-bind:disabled="processing"
                class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span x-show="!processing">Send Email</span>
                <span x-show="processing">Sending...</span>
                <span x-show="processing" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/partials/students/send-email.blade.php ENDPATH**/ ?>