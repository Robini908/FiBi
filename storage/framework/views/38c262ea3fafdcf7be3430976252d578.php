<!-- Accounts Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 sm:p-6 bg-gray-50">
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex flex-col">
            <div class="text-sm font-medium text-gray-500">Total Accounts</div>
            <div class="text-3xl font-bold text-gray-800"><?php echo e($summary['totalAccounts']); ?></div>
            <div class="text-xs text-gray-500 mt-1">All registered finance accounts</div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex flex-col">
            <div class="text-sm font-medium text-gray-500">Active Accounts</div>
            <div class="text-3xl font-bold text-gray-800"><?php echo e($summary['activeAccounts']); ?></div>
            <div class="text-xs text-gray-500 mt-1">Accounts currently in use</div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex flex-col">
            <div class="text-sm font-medium text-gray-500">Total Current Balance</div>
            <div class="text-3xl font-bold <?php echo e($summary['totalBalance'] < 0 ? 'text-red-600' : 'text-green-600'); ?>">
                KES <?php echo e(number_format($summary['totalBalance'], 2)); ?>

            </div>
            <div class="text-xs text-gray-500 mt-1">Combined balance across all accounts</div>
        </div>
    </div>
</div> <?php /**PATH C:\projects\MbukuErp\resources\views/livewire/finance/partials/account-summary.blade.php ENDPATH**/ ?>