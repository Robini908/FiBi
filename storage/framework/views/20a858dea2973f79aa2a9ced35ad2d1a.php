<div class="grid grid-cols-12 gap-6 mb-8">
    <!-- Total Vouchers -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="flex flex-col bg-white shadow-lg rounded-sm border border-gray-200 p-5 h-full">
            <div class="grow flex flex-col justify-center">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-500 text-sm mb-1">Total Vouchers</div>
                        <div class="text-3xl font-bold text-gray-800"><?php echo e($totalVouchers); ?></div>
                    </div>
                </div>
                <div class="mt-2 border-t pt-2 text-xs text-gray-500 flex justify-between">
                    <span>Pending: <?php echo e($pendingVouchers); ?></span>
                    <span>Approved: <?php echo e($approvedVouchers); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Amount -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="flex flex-col bg-white shadow-lg rounded-sm border border-gray-200 p-5 h-full">
            <div class="grow flex flex-col justify-center">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-500 text-sm mb-1">Total Amount</div>
                        <div class="text-3xl font-bold text-gray-800">KES <?php echo e(number_format($totalAmount, 2)); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paid Amount -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="flex flex-col bg-white shadow-lg rounded-sm border border-gray-200 p-5 h-full">
            <div class="grow flex flex-col justify-center">
                <div class="flex items-center">
                    <div class="rounded-full bg-emerald-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-500 text-sm mb-1">Paid Amount</div>
                        <div class="text-3xl font-bold text-gray-800">KES <?php echo e(number_format($paidAmount, 2)); ?></div>
                    </div>
                </div>
                <div class="mt-2 border-t pt-2 text-xs text-gray-500">
                    <span>Paid Vouchers: <?php echo e($paidVouchers); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="flex flex-col bg-white shadow-lg rounded-sm border border-gray-200 p-5 h-full">
            <div class="grow flex flex-col justify-center">
                <div class="flex items-center">
                    <div class="rounded-full bg-amber-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-500 text-sm mb-1">Cancelled Vouchers</div>
                        <div class="text-3xl font-bold text-gray-800"><?php echo e($cancelledVouchers); ?></div>
                    </div>
                </div>
                <div class="mt-2 border-t pt-2 text-xs space-y-1">
                    <div class="flex items-center justify-between">
                        <div class="text-gray-500">Overall Status</div>
                        <div class="text-green-500 font-medium"><?php echo e(number_format(($paidAmount / ($totalAmount ?: 1)) * 100, 1)); ?>% paid</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/payment-voucher-summary.blade.php ENDPATH**/ ?>