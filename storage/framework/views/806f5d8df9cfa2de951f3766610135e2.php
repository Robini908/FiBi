<div
    x-data="{ modalOpen: <?php if ((object) ('showPayModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showPayModal'->value()); ?>')<?php echo e('showPayModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showPayModal'); ?>')<?php endif; ?>.live }"
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
                <div class="font-semibold text-green-600">Mark Payment Voucher as Paid</div>
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
                <div class="font-medium text-gray-800 mb-3">Are you sure you want to mark this voucher as paid?</div>
                <div class="text-gray-600 mb-4">
                    <p>This action confirms that payment has been made to the recipient.</p>
                </div>
                
                <!-- Payment details summary -->
                <!--[if BLOCK]><![endif]--><?php if($selectedVoucher): ?>
                <div class="bg-gray-50 rounded-md p-4 mb-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-medium text-gray-500">Voucher Number</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($selectedVoucher->voucher_number); ?></dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-sm text-green-600 font-medium">KES <?php echo e(number_format($selectedVoucher->amount, 2)); ?></dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-medium text-gray-500">Recipient</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($selectedVoucher->recipient_name); ?></dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e(ucfirst($selectedVoucher->payment_method)); ?>

                                <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->payment_method === 'cheque' && $selectedVoucher->cheque_number): ?>
                                    (<?php echo e($selectedVoucher->cheque_number); ?>)
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </dd>
                        </div>
                    </dl>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <div class="bg-yellow-50 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>This will finalize the payment process. Please ensure that the funds have been disbursed before proceeding.</p>
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
                    Cancel
                </button>
                <button 
                    type="button" 
                    class="btn-sm bg-green-600 hover:bg-green-700 text-white"
                    wire:click="markVoucherAsPaid"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="markVoucherAsPaid">Confirm Payment</span>
                    <span wire:loading wire:target="markVoucherAsPaid">
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
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/payment-voucher-pay-modal.blade.php ENDPATH**/ ?>