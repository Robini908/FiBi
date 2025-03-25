<!-- Class Total Summary (shows only when filtering by year and term) -->
@if($yearFilter && $termFilter && !empty($classTotals))
<div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
    <h2 class="text-lg font-medium text-gray-700 mb-3">Fee Summary for Year {{ $yearFilter }}, Term {{ $termFilter }}</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($classes as $class)
            @if(isset($classTotals[$class->id]))
            <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-600">{{ $class->name }}</div>
                <div class="font-semibold text-lg text-gray-800">KES {{ number_format($classTotals[$class->id], 2) }}</div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endif 

<div class="grid grid-cols-12 gap-6 mb-8">
    <!-- Total Fee Types -->
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
                        <div class="text-gray-500 text-sm mb-1">Fee Types</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $feeStructures->total() }}</div>
                    </div>
                </div>
                <div class="mt-2 border-t pt-2 text-xs text-gray-500 flex justify-between">
                    <span>Active: {{ $feeStructures->where('is_active', true)->count() }}</span>
                    <span>Inactive: {{ $feeStructures->where('is_active', false)->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Categories -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="flex flex-col bg-white shadow-lg rounded-sm border border-gray-200 p-5 h-full">
            <div class="grow flex flex-col justify-center">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-500 text-sm mb-1">Fee Categories</div>
                        <div class="text-3xl font-bold text-gray-800">{{ count($categories) }}</div>
                    </div>
                </div>
                <div class="mt-2 border-t pt-2 text-xs text-gray-500">
                    <span>Most common: {{ $feeStructures->groupBy('category')->sortByDesc(function($items) { return $items->count(); })->keys()->first() ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mandatory vs Optional -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="flex flex-col bg-white shadow-lg rounded-sm border border-gray-200 p-5 h-full">
            <div class="grow flex flex-col justify-center">
                <div class="flex items-center">
                    <div class="rounded-full bg-emerald-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-500 text-sm mb-1">Mandatory Fees</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $feeStructures->where('is_mandatory', true)->count() }}</div>
                    </div>
                </div>
                <div class="mt-2 border-t pt-2 text-xs text-gray-500">
                    <span>Optional Fees: {{ $feeStructures->where('is_mandatory', false)->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Fee Summary -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="flex flex-col bg-white shadow-lg rounded-sm border border-gray-200 p-5 h-full">
            <div class="grow flex flex-col justify-center">
                <div class="flex items-center">
                    <div class="rounded-full bg-amber-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-500 text-sm mb-1">Selected Class Total</div>
                        <div class="text-3xl font-bold text-gray-800">
                            @if($totalMandatoryFees !== null)
                                KES {{ number_format($totalMandatoryFees, 2) }}
                            @else
                                --
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-2 border-t pt-2 text-xs text-gray-500">
                    <span>
                        @if($classFilter && $yearFilter && $termFilter)
                            Mandatory fees for selected class & term
                        @else
                            Select class, year & term to view total
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div> 