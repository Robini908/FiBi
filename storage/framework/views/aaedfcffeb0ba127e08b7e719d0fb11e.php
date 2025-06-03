<div
    x-data="{ modalOpen: <?php if ((object) ('showViewModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showViewModal'->value()); ?>')<?php echo e('showViewModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showViewModal'); ?>')<?php endif; ?>.live }"
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
        class="bg-white rounded-lg shadow-lg overflow-auto max-h-[90vh] w-full max-w-3xl"
        @click.stop
    >
        <!--[if BLOCK]><![endif]--><?php if($selectedVoucher): ?>
        <div id="voucher-to-print">
            <!-- Modal header -->
            <div class="bg-green-50 px-6 pt-5 pb-4 sm:px-6 border-b border-gray-200 print:bg-white">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl leading-6 font-bold text-gray-800">
                        Payment Voucher Details
                    </h2>
                    <div class="flex space-x-2">
                        <button 
                            wire:click="printVoucher"
                            type="button"
                            class="btn-sm bg-green-600 hover:bg-green-700 text-white print:hidden"
                        >
                            <svg class="w-4 h-4 fill-current opacity-50 shrink-0 mr-1" viewBox="0 0 16 16">
                                <path d="M14 4.5V14a2 2 0 01-2 2H4a2 2 0 01-2-2V2a2 2 0 012-2h5.5L14 4.5zM3 3a1 1 0 011-1h5v2H9a1 1 0 01-1-1V3H3z" />
                            </svg>
                            <span>Print</span>
                        </button>
                        <button
                            @click="modalOpen = false"
                            class="text-gray-400 hover:text-gray-500 print:hidden"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Printable content -->
            <div class="px-6 py-4 bg-white sm:p-6 max-h-[calc(90vh-8rem)] overflow-y-auto">
                <!-- School header (only visible when printing) -->
                <div class="hidden print:block text-center mb-6">
                    <h2 class="text-xl font-bold">MBUKU SCHOOL</h2>
                    <p class="text-sm">P.O. Box 123, Nairobi</p>
                    <h3 class="text-lg font-bold mt-3">PAYMENT VOUCHER</h3>
                </div>

                <!-- Voucher number and status -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b pb-4 mb-4">
                    <div>
                        <span class="text-sm text-gray-500">Voucher Number:</span>
                        <span class="ml-2 font-bold text-green-700"><?php echo e($selectedVoucher->voucher_number); ?></span>
                    </div>
                    <div class="flex mt-2 md:mt-0">
                        <span class="text-sm text-gray-500 mr-2">Status:</span>
                        <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->is_cancelled): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Cancelled
                            </span>
                            <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->cancellation_reason): ?>
                                <span class="ml-2 text-xs text-gray-500">(<?php echo e($selectedVoucher->cancellation_reason); ?>)</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php elseif($selectedVoucher->status === 'pending'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        <?php elseif($selectedVoucher->status === 'approved'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Approved
                            </span>
                        <?php elseif($selectedVoucher->status === 'paid'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Paid
                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Two column layout for voucher details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left column -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Votehead</h3>
                            <p class="mt-1"><?php echo e($selectedVoucher->votehead->name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($selectedVoucher->votehead->code); ?></p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Amount</h3>
                            <p class="mt-1 font-bold text-green-700">KES <?php echo e(number_format($selectedVoucher->amount, 2)); ?></p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Payment Method</h3>
                            <p class="mt-1"><?php echo e(ucfirst($selectedVoucher->payment_method)); ?></p>
                            <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->payment_method === 'cheque' && $selectedVoucher->cheque_number): ?>
                                <p class="text-xs text-gray-500">Cheque #: <?php echo e($selectedVoucher->cheque_number); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Purpose</h3>
                            <p class="mt-1"><?php echo e($selectedVoucher->purpose); ?></p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Academic Period</h3>
                            <p class="mt-1">Year <?php echo e($selectedVoucher->academic_year); ?>, Term <?php echo e($selectedVoucher->term); ?></p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Payment Date</h3>
                            <p class="mt-1"><?php echo e(date('d M, Y', strtotime($selectedVoucher->payment_date))); ?></p>
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Recipient</h3>
                            <p class="mt-1 font-medium"><?php echo e($selectedVoucher->recipient_name); ?></p>
                            <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->recipient_id_number): ?>
                                <p class="text-xs text-gray-500">ID: <?php echo e($selectedVoucher->recipient_id_number); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->recipient_phone): ?>
                                <p class="text-xs text-gray-500">Phone: <?php echo e($selectedVoucher->recipient_phone); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->recipient_address): ?>
                                <p class="text-xs text-gray-500">Address: <?php echo e($selectedVoucher->recipient_address); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Description</h3>
                            <p class="mt-1"><?php echo e($selectedVoucher->description); ?></p>
                        </div>

                        <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->attachment_path): ?>
                        <div class="print:hidden">
                            <h3 class="text-sm font-medium text-gray-500">Attachment</h3>
                            <p class="mt-1">
                                <a href="<?php echo e(Storage::url($selectedVoucher->attachment_path)); ?>" target="_blank" class="text-green-600 hover:text-green-800 underline">
                                    View Attachment
                                </a>
                            </p>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Created By</h3>
                            <p class="mt-1"><?php echo e($selectedVoucher->created_by); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(date('d M, Y H:i', strtotime($selectedVoucher->created_at))); ?></p>
                        </div>

                        <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->approved_by): ?>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Approved By</h3>
                            <p class="mt-1"><?php echo e($selectedVoucher->approved_by); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(date('d M, Y H:i', strtotime($selectedVoucher->approved_at))); ?></p>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->paid_by): ?>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Paid By</h3>
                            <p class="mt-1"><?php echo e($selectedVoucher->paid_by); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(date('d M, Y H:i', strtotime($selectedVoucher->paid_at))); ?></p>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Signature section (only visible when printing) -->
                <div class="hidden print:block mt-8 border-t pt-4">
                    <div class="grid grid-cols-3 gap-4 mt-8">
                        <div class="text-center">
                            <div class="border-b border-gray-400 pb-8 mb-2"></div>
                            <p class="text-sm font-medium">Prepared By</p>
                        </div>
                        <div class="text-center">
                            <div class="border-b border-gray-400 pb-8 mb-2"></div>
                            <p class="text-sm font-medium">Approved By</p>
                        </div>
                        <div class="text-center">
                            <div class="border-b border-gray-400 pb-8 mb-2"></div>
                            <p class="text-sm font-medium">Received By</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal footer with action buttons - not printed -->
        <div class="print:hidden px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <!-- Actions based on voucher status -->
            <!--[if BLOCK]><![endif]--><?php if(!$selectedVoucher->is_cancelled): ?>
                <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->status === 'pending'): ?>
                    <!--[if BLOCK]><![endif]--><?php if($isAdmin || $isAccountant): ?>
                    <button 
                        wire:click="$dispatch('confirmApproveVoucher', { id: <?php echo e($selectedVoucher->id); ?> })"
                        class="btn-sm bg-green-100 border-green-200 hover:border-green-300 text-green-700 hover:text-green-800"
                    >
                        <svg class="w-4 h-4 fill-current opacity-50 shrink-0 mr-1" viewBox="0 0 16 16">
                            <path d="M10.72 5.712a.75.75 0 1 0-1.44-.424l-3.22 10.933a.75.75 0 0 0 1.44.424l3.22-10.933Z" />
                            <path d="M1.397 6.96a.75.75 0 0 1-.09-1.058L4.38 2.28a.75.75 0 0 1 1.149.093l2.68 3.84a.75.75 0 1 1-1.229.86L5 3.991 2.547 7.008a.75.75 0 0 1-1.15-.048Z" />
                            <path d="M15.385 9.011a.75.75 0 0 1-.09 1.058l-3.07 2.618a.75.75 0 0 1-1.149-.093l-2.08-2.973a.75.75 0 1 1 1.229-.86l1.559 2.224 2.452-2.084a.75.75 0 0 1 1.149.11Z" />
                        </svg>
                        <span>Approve</span>
                    </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php if($isAdmin || $isAccountant || $selectedVoucher->created_by === auth()->user()->name): ?>
                    <button 
                        wire:click="editVoucher(<?php echo e($selectedVoucher->id); ?>)"
                        class="btn-sm bg-indigo-100 border-indigo-200 hover:border-indigo-300 text-indigo-700 hover:text-indigo-800"
                    >
                        <svg class="w-4 h-4 fill-current opacity-50 shrink-0 mr-1" viewBox="0 0 16 16">
                            <path d="m11.7 7.3-3-3c-.4-.4-1-.4-1.4 0l-6 6c-.2.2-.3.4-.3.7v3c0 .6.4 1 1 1h3c.3 0 .5-.1.7-.3l6-6c.4-.4.4-1 0-1.4ZM9 4 12 7l-6 6H3V9.9L9 4Z" />
                        </svg>
                        <span>Edit</span>
                    </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php elseif($selectedVoucher->status === 'approved' && ($isAdmin || $isAccountant)): ?>
                    <button 
                        wire:click="$dispatch('confirmPayVoucher', { id: <?php echo e($selectedVoucher->id); ?> })"
                        class="btn-sm bg-green-100 border-green-200 hover:border-green-300 text-green-700 hover:text-green-800"
                    >
                        <svg class="w-4 h-4 fill-current opacity-50 shrink-0 mr-1" viewBox="0 0 16 16">
                            <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8Zm0 10c-1.1 0-2-.9-2-2 0-.8.4-1.4 1.1-1.7.7-.3 1.6-.3 2.2.2.6.5.7 1.4.4 2.1-.3.7-1 1.4-1.7 1.4Z" />
                        </svg>
                        <span>Mark as Paid</span>
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if($selectedVoucher->status !== 'paid' && ($isAdmin || $isAccountant)): ?>
                    <button 
                        wire:click="$dispatch('confirmCancelVoucher', { id: <?php echo e($selectedVoucher->id); ?> })"
                        class="btn-sm bg-red-100 border-red-200 hover:border-red-300 text-red-700 hover:text-red-800"
                    >
                        <svg class="w-4 h-4 fill-current opacity-50 shrink-0 mr-1" viewBox="0 0 16 16">
                            <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8Zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1Zm1-3H7V4h2v5Z" />
                        </svg>
                        <span>Cancel</span>
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <button
                @click="modalOpen = false"
                class="btn-sm bg-white border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-600"
            >
                <span>Close</span>
            </button>
        </div>
        <?php else: ?>
        <div class="p-6 text-center">
            <p>Loading voucher details...</p>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/payment-voucher-view-modal.blade.php ENDPATH**/ ?>