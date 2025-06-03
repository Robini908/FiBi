<div
    x-data="{ modalOpen: <?php if ((object) ('showCancelModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showCancelModal'->value()); ?>')<?php echo e('showCancelModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showCancelModal'); ?>')<?php endif; ?>.live }"
    x-on:keydown.escape.window="modalOpen = false"
    x-show="modalOpen"
    class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center transform px-4 sm:px-6"
    role="dialog"
    aria-modal="true"
    x-cloak
>
    <!-- Modal backdrop -->
    <div
        x-show="modalOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-out duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-gray-900 bg-opacity-70"
        @click="modalOpen = false"
    ></div>

    <!-- Modal dialog -->
    <div
        x-show="modalOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-out duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="bg-white rounded-lg shadow-lg overflow-auto max-w-lg w-full max-h-full"
        @click.stop
    >
        <!-- Modal header -->
        <div class="px-5 py-3 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="font-semibold text-red-600">Cancel Payment Voucher</div>
                <button class="text-gray-400 hover:text-gray-500" @click="modalOpen = false">
                    <div class="sr-only">Close</div>
                    <svg class="w-4 h-4 fill-current">
                        <path d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal content -->
        <div class="px-5 py-4">
            <div class="text-sm">
                <div class="font-medium text-gray-800 mb-3">Are you sure you want to cancel this payment voucher?</div>
                <div class="text-gray-600 mb-4">
                    <p>Cancelling this voucher will permanently mark it as invalid and prevent it from being processed further.</p>
                </div>
                
                <!-- Cancellation reason input -->
                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Cancellation <span class="text-red-500">*</span></label>
                    <textarea 
                        id="cancellation_reason"
                        wire:model="cancellationReason"
                        rows="3"
                        class="form-textarea block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                        placeholder="Please provide a reason for cancelling this voucher"
                    ></textarea>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cancellationReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Warning message -->
                <div class="bg-red-50 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Important Notice</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>This action cannot be undone. Once cancelled, the voucher cannot be reinstated.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal footer -->
        <div class="px-5 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
            <div class="flex space-x-3">
                <button 
                    type="button" 
                    class="btn-sm bg-white border-gray-200 hover:border-gray-300 text-gray-600"
                    @click="modalOpen = false"
                >
                    Go Back
                </button>
                <button 
                    type="button" 
                    class="btn-sm bg-red-600 hover:bg-red-700 text-white"
                    wire:click="cancelVoucher"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="cancelVoucher">Cancel Voucher</span>
                    <span wire:loading wire:target="cancelVoucher">
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
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/payment-voucher-cancel-modal.blade.php ENDPATH**/ ?>