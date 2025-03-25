<!-- Arrears Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 sm:p-6 bg-gray-50">
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex flex-col">
            <div class="text-sm font-medium text-gray-500">
                @if($isStudent)
                    My Total Outstanding Arrears
                @elseif($isParent)
                    Children's Total Outstanding Arrears
                @else
                    Total Outstanding Arrears
                @endif
            </div>
            <div class="text-3xl font-bold text-gray-800">
                KES {{ number_format($totalArrearsAll, 2) }}
            </div>
            <div class="text-xs text-gray-500 mt-1">
                @if($isStudent)
                    Your unpaid arrears
                @elseif($isParent)
                    Your children's unpaid arrears
                @else
                    School-wide unpaid arrears
                @endif
            </div>
        </div>
    </div>
    
    @if($isStudent)
    <!-- For students, show a summary of payment status -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex flex-col">
            <div class="text-sm font-medium text-gray-500">Payment Status</div>
            <div class="text-base text-gray-700 mt-1">
                @if($totalArrearsAll > 0)
                    <span class="text-red-600 font-medium">Please clear your arrears</span>
                    <p class="text-xs text-gray-500 mt-1">Contact the accounts office for payment options</p>
                @else
                    <span class="text-green-600 font-medium">No outstanding arrears</span>
                    <p class="text-xs text-gray-500 mt-1">Your account is in good standing</p>
                @endif
            </div>
        </div>
    </div>
    @elseif($isParent)
    <!-- For parents, show most indebted child information if relevant -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex flex-col">
            <div class="text-sm font-medium text-gray-500">Payment Information</div>
            <div class="text-base text-gray-700 mt-1">
                @if($totalArrearsAll > 0)
                    <span class="text-red-600 font-medium">Action Required</span>
                    <p class="text-xs text-gray-500 mt-1">Please clear your children's outstanding arrears</p>
                @else
                    <span class="text-green-600 font-medium">No outstanding arrears</span>
                    <p class="text-xs text-gray-500 mt-1">All your children's accounts are in good standing</p>
                @endif
            </div>
        </div>
    </div>
    @elseif($classFilter)
    <!-- For staff with class filter applied -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex flex-col">
            <div class="text-sm font-medium text-gray-500">Class Arrears</div>
            <div class="text-3xl font-bold text-gray-800">
                KES {{ number_format($totalArrearsClass, 2) }}
            </div>
            <div class="text-xs text-gray-500 mt-1">
                Unpaid arrears for {{ $classes->firstWhere('id', $classFilter)?->name ?: 'selected class' }}
            </div>
        </div>
    </div>
    @else
    <!-- For staff with no class filter -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex flex-col">
            <div class="text-sm font-medium text-gray-500">Select a Class</div>
            <div class="text-base text-gray-600 mt-1">
                Filter by class to see class-specific arrears summary
            </div>
        </div>
    </div>
    @endif
</div> 