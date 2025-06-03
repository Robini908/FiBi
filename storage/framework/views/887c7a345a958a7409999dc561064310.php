<div
    x-data="{ 
        modalOpen: <?php if ((object) ('showModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'->value()); ?>')<?php echo e('showModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'); ?>')<?php endif; ?>
    }"
    x-show="modalOpen"
    class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center transform px-4 sm:px-6"
    role="dialog"
    aria-modal="true"
    x-cloak
>
    <!-- Modal backdrop -->
    <div
        x-show="modalOpen"
        class="absolute inset-0 bg-gray-900 bg-opacity-70"
        @click="$wire.closeModal()"
    ></div>

    <!-- Modal dialog -->
    <div
        x-show="modalOpen"
        class="bg-white rounded-lg shadow-lg overflow-auto max-h-[90vh] w-full max-w-4xl"
        @click.stop
    >
        <!-- Modal header -->
        <div class="bg-green-50 px-6 pt-5 pb-4 sm:px-6 border-b border-gray-200">
            <h2 class="text-xl leading-6 font-bold text-gray-800">
                <?php echo e($isEditing ? 'Edit Payment Voucher' : 'Create New Payment Voucher'); ?>

            </h2>
            <p class="text-sm text-gray-500 mt-1">
                <?php echo e($isEditing ? 'Modify existing payment voucher details' : 'Fill in the details to create a new payment voucher'); ?>

            </p>
        </div>

        <!-- Modal content -->
        <div class="px-6 py-4 bg-white sm:p-6 max-h-[calc(90vh-8rem)] overflow-y-auto">
            <form id="payment-voucher-form" wire:submit.prevent="saveVoucher" class="space-y-4">
                <!-- Form grid with 2 columns -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left column -->
                    <div class="space-y-4">
                        <!-- Votehead -->
                        <div>
                            <label for="votehead_id" class="block text-sm font-medium text-gray-700 mb-1">Votehead <span class="text-red-500">*</span></label>
                            <select 
                                id="votehead_id" 
                                wire:model="votehead_id" 
                                class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                                <option value="">Select a votehead</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $voteheads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $votehead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($votehead->id); ?>"><?php echo e($votehead->name); ?> - [Balance: KES <?php echo e(number_format($votehead->balance, 2)); ?>]</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['votehead_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (KES) <span class="text-red-500">*</span></label>
                            <input 
                                type="number" 
                                step="0.01" 
                                id="amount" 
                                wire:model="amount" 
                                placeholder="Enter amount" 
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                            <select 
                                id="payment_method" 
                                wire:model.live="payment_method" 
                                class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Cheque Number (conditional based on payment method) -->
                        <!--[if BLOCK]><![endif]--><?php if($payment_method === 'cheque'): ?>
                        <div>
                            <label for="cheque_number" class="block text-sm font-medium text-gray-700 mb-1">Cheque Number <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="cheque_number" 
                                wire:model="cheque_number" 
                                placeholder="Enter cheque number" 
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cheque_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message ?? 'Cheque number is required'); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Purpose -->
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="purpose" 
                                wire:model="purpose" 
                                placeholder="Enter payment purpose" 
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Academic Year & Term -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">Academic Year <span class="text-red-500">*</span></label>
                                <input 
                                    type="number" 
                                    id="academic_year" 
                                    wire:model="academic_year" 
                                    placeholder="YYYY" 
                                    class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                >
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div>
                                <label for="term" class="block text-sm font-medium text-gray-700 mb-1">Term <span class="text-red-500">*</span></label>
                                <select 
                                    id="term" 
                                    wire:model="term" 
                                    class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                >
                                    <option value="1">Term 1</option>
                                    <option value="2">Term 2</option>
                                    <option value="3">Term 3</option>
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['term'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                        
                        <!-- Payment Date -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Payment Date <span class="text-red-500">*</span></label>
                            <input 
                                type="date" 
                                id="payment_date" 
                                wire:model="payment_date" 
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="space-y-4">
                        <!-- Recipient Name -->
                        <div>
                            <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-1">Recipient Name <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="recipient_name" 
                                wire:model="recipient_name" 
                                placeholder="Enter recipient's full name" 
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['recipient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Recipient ID Number -->
                        <div>
                            <label for="recipient_id_number" class="block text-sm font-medium text-gray-700 mb-1">Recipient ID Number</label>
                            <input 
                                type="text" 
                                id="recipient_id_number" 
                                wire:model="recipient_id_number" 
                                placeholder="Enter ID number (optional)" 
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['recipient_id_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Recipient Phone -->
                        <div>
                            <label for="recipient_phone" class="block text-sm font-medium text-gray-700 mb-1">Recipient Phone</label>
                            <input 
                                type="text" 
                                id="recipient_phone" 
                                wire:model="recipient_phone" 
                                placeholder="Enter phone number (optional)" 
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['recipient_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Recipient Address -->
                        <div>
                            <label for="recipient_address" class="block text-sm font-medium text-gray-700 mb-1">Recipient Address</label>
                            <input 
                                type="text" 
                                id="recipient_address" 
                                wire:model="recipient_address" 
                                placeholder="Enter address (optional)" 
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            >
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['recipient_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                            <textarea 
                                id="description" 
                                wire:model="description" 
                                rows="4" 
                                placeholder="Enter detailed description of payment" 
                                class="form-textarea w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            ></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Attachment -->
                        <div>
                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Attachment</label>
                            <div class="mt-1 flex items-center">
                                <input 
                                    type="file" 
                                    id="attachment" 
                                    wire:model.live="attachment" 
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                                >
                            </div>
                            <div wire:loading wire:target="attachment" class="text-xs text-gray-500 mt-1">
                                Uploading...
                            </div>
                            <p class="mt-1 text-xs text-gray-500">PDF, JPG, JPEG, or PNG (max 2MB)</p>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            
                            <!--[if BLOCK]><![endif]--><?php if($tempAttachmentPath): ?>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    Existing attachment:
                                    <a href="<?php echo e(Storage::url($tempAttachmentPath)); ?>" target="_blank" class="ml-1 text-green-600 hover:text-green-500">
                                        View
                                    </a>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <div class="text-xs text-gray-500">
                <span class="text-red-500">*</span> Required fields
            </div>
            <div class="flex space-x-3">
                <button
                    type="button"
                    class="btn-sm bg-white border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-600"
                    wire:click="closeModal"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    class="btn-sm bg-green-600 hover:bg-green-700 text-white"
                    wire:loading.attr="disabled"
                    wire:click="saveVoucher"
                >
                    <span wire:loading.remove wire:target="saveVoucher">
                        <?php echo e($isEditing ? 'Update Voucher' : 'Create Voucher'); ?>

                    </span>
                    <span wire:loading wire:target="saveVoucher">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/payment-voucher-form-modal.blade.php ENDPATH**/ ?>