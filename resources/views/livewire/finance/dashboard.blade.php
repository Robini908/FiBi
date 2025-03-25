<div class="bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Dashboard Header -->
        <div class="pb-5 border-b border-gray-200 mb-6 flex flex-col md:flex-row justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-800">
                    @if($isStudent)
                        My Finance Dashboard
                    @elseif($isParent)
                        My Children's Finance Dashboard
                    @elseif($isTeacher)
                        Class Finance Overview
                    @else
                        Finance Dashboard
                    @endif
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    @if($isStudent)
                        Track your school fees and payment history
                    @elseif($isParent)
                        Monitor your children's school fees and payments
                    @elseif($isTeacher)
                        View financial information for your classes
                    @else
                        Overview of school financial operations
                    @endif
                </p>
            </div>
            
            <!-- Date Range Filters - Only visible to admin and accountant -->
            @if($isAdmin || $isAccountant)
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 items-end">
                <div>
                    <label for="dateRange" class="block text-sm font-medium text-gray-700">Date Range</label>
                    <select
                        wire:model.live="dateRange"
                        id="dateRange"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                    >
                        <option value="term">Current Term</option>
                        <option value="month">Current Month</option>
                        <option value="year">Current Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                
                @if($dateRange === 'term')
                <div class="flex space-x-3">
                    <div>
                        <label for="currentYear" class="block text-sm font-medium text-gray-700">Year</label>
                        <select
                            wire:model.live="currentYear"
                            id="currentYear"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                        >
                            @for($year = date('Y') - 5; $year <= date('Y') + 1; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label for="currentTerm" class="block text-sm font-medium text-gray-700">Term</label>
                        <select
                            wire:model.live="currentTerm"
                            id="currentTerm"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                        >
                            <option value="1">Term 1</option>
                            <option value="2">Term 2</option>
                            <option value="3">Term 3</option>
                        </select>
                    </div>
                </div>
                @endif
                
                @if($dateRange === 'custom')
                <div class="flex space-x-3">
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input 
                            wire:model="startDate"
                            type="date"
                            id="startDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                        >
                    </div>
                    
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input 
                            wire:model="endDate"
                            type="date"
                            id="endDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                        >
                    </div>
                    
                    <div class="self-end">
                        <button
                            wire:click="applyDateFilter"
                            type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Apply
                        </button>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Student-specific view -->
        @if($isStudent)
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">My Fee Information</h3>
                    
                    <!-- Fee Progress -->
                    <div class="mb-6">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Payment Progress</span>
                            <span class="text-sm font-medium text-gray-700">{{ number_format($paymentProgress, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ min($paymentProgress, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <!-- Fee Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Total Expected Fees</p>
                            <p class="text-xl font-semibold text-gray-900">KES {{ number_format($totalExpected, 2) }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Total Paid</p>
                            <p class="text-xl font-semibold text-green-600">KES {{ number_format($totalPaid, 2) }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Current Balance</p>
                            <p class="text-xl font-semibold {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                KES {{ number_format($balance, 2) }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Previous Arrears</p>
                            <p class="text-xl font-semibold {{ $totalArrears > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                KES {{ number_format($totalArrears, 2) }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Fee Breakdown -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-3">Fee Breakdown</h4>
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Type</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (KES)</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($feeBreakdown as $fee)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $fee['name'] }}
                                            @if(!$fee['is_mandatory'])
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Optional
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">
                                            {{ number_format($fee['amount'], 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                            @if($fee['is_mandatory'])
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $balance > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $balance > 0 ? 'Partial' : 'Paid' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Optional
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <div class="mt-6 text-right">
                        <a href="{{ route('finance.student-fee-payments') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Make Payment
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Parent-specific view -->
        @if($isParent)
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Children's Fee Information</h3>
                    
                    <!-- Fee Progress -->
                    <div class="mb-6">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Overall Payment Progress</span>
                            <span class="text-sm font-medium text-gray-700">{{ number_format($paymentProgress, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ min($paymentProgress, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Total Owed (All Children)</p>
                            <p class="text-xl font-semibold text-gray-900">KES {{ number_format($totalOwedByChildren, 2) }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Total Paid</p>
                            <p class="text-xl font-semibold text-green-600">KES {{ number_format($totalPaidByChildren, 2) }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Balance Due</p>
                            <p class="text-xl font-semibold {{ ($totalOwedByChildren - $totalPaidByChildren) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                KES {{ number_format($totalOwedByChildren - $totalPaidByChildren, 2) }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Per Child Fee Information -->
                    @foreach($childrenFeeData as $childId => $child)
                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-md font-medium text-gray-900">{{ $child['name'] }}</h4>
                                    <p class="text-sm text-gray-500">{{ $child['class'] }}</p>
                                </div>
                                <a href="{{ route('finance.student-fee-payments') }}?student_id={{ $childId }}" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Make Payment
                                </a>
                            </div>
                        </div>
                        
                        <div class="px-4 py-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Expected Fees</p>
                                    <p class="text-base font-semibold text-gray-900">KES {{ number_format($child['expected'], 2) }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Amount Paid</p>
                                    <p class="text-base font-semibold text-green-600">KES {{ number_format($child['paid'], 2) }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Balance</p>
                                    <p class="text-base font-semibold {{ $child['balance'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        KES {{ number_format($child['balance'], 2) }}
                                    </p>
                                </div>
                            </div>
                            
                            @if($child['arrears'] > 0)
                            <div class="bg-red-50 p-3 rounded-md mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Previous Arrears: KES {{ number_format($child['arrears'], 2) }}</h3>
                                        <div class="mt-1 text-sm text-red-700">
                                            <p>Your child has outstanding fees from previous terms that need to be cleared.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Recent Payments -->
                            @if(count($child['payment_history']) > 0)
                            <div>
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Recent Payments</h5>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($child['payment_history'] as $payment)
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->payment_date->format('d M, Y') }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 text-right">
                                                KES {{ number_format($payment->amount, 2) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                                {{ ucfirst($payment->payment_method) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        <!-- Teacher-specific view -->
        @if($isTeacher && isset($classFeeData))
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Class Fee Information</h3>
                    
                    <!-- Class Fee Data -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($classFeeData as $className => $data)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="text-md font-medium text-gray-900 mb-3">{{ $className }}</h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Expected Fees</p>
                                    <p class="text-lg font-semibold text-gray-900">KES {{ number_format($data['expected'], 2) }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Collected</p>
                                    <p class="text-lg font-semibold text-green-600">KES {{ number_format($data['collected'], 2) }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Outstanding Arrears</p>
                                    <p class="text-lg font-semibold {{ $data['arrears'] > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                        KES {{ number_format($data['arrears'], 2) }}
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Collection Rate</p>
                                    <div class="flex items-center">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($data['collection_rate'], 100) }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ number_format($data['collection_rate'], 1) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Stats Cards - Only visible to admin and accountant -->
        @if($isAdmin || $isAccountant)
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Account Balance Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Account Balance</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">KES {{ number_format($totalAccountsBalance, 2) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="{{ route('finance.accounts') }}" class="font-medium text-green-600 hover:text-green-500">View accounts <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>

            <!-- Expected Fees Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Expected Fee Income</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">KES {{ number_format($totalExpectedFees, 2) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="{{ route('finance.fee-structure') }}" class="font-medium text-blue-600 hover:text-blue-500">View fee structure <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>

            <!-- Collected Fees Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Collected Fees</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">KES {{ number_format($totalCollectedFees, 2) }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        {{ number_format($feeCollectionRate, 1) }}%
                                        <span class="sr-only">collection rate</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="{{ route('finance.student-fee-payments') }}" class="font-medium text-indigo-600 hover:text-indigo-500">View payments <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>

            <!-- Outstanding Arrears Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Outstanding Arrears</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">KES {{ number_format($totalArrears, 2) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="{{ route('finance.student-arrears') }}" class="font-medium text-red-600 hover:text-red-500">View arrears <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Fees Collection vs Expenses Chart -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Collection vs Expenses</h3>
                    <span class="text-sm text-gray-500">{{ $startDate }} to {{ $endDate }}</span>
                </div>
                <div class="px-4 py-5 sm:p-6 h-80" x-data="{
                    paymentsByDay: @js($paymentsByDay),
                    expensesByDay: @js($expensesByDay),
                    init() {
                        const ctx = this.$refs.collectionChart.getContext('2d');
                        
                        // Get all unique dates
                        const allDates = [...new Set([
                            ...Object.keys(this.paymentsByDay),
                            ...Object.keys(this.expensesByDay)
                        ])].sort();
                        
                        // Prepare data for the chart
                        const payments = allDates.map(date => this.paymentsByDay[date] || 0);
                        const expenses = allDates.map(date => this.expensesByDay[date] || 0);
                        
                        // Create the chart
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: allDates,
                                datasets: [
                                    {
                                        label: 'Fee Collections',
                                        data: payments,
                                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                        borderColor: 'rgba(79, 70, 229, 1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4
                                    },
                                    {
                                        label: 'Expenses',
                                        data: expenses,
                                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                                        borderColor: 'rgba(244, 63, 94, 1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'KES ' + value.toLocaleString();
                                            }
                                        }
                                    }
                                },
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                label += 'KES ' + context.parsed.y.toLocaleString();
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="collectionChart"></canvas>
                </div>
            </div>

            <!-- Fee Collections by Class Chart -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Fee Collections by Class</h3>
                </div>
                <div class="px-4 py-5 sm:p-6 h-80" x-data="{
                    feeCollectionsByClass: @js($feeCollectionsByClass),
                    init() {
                        const ctx = this.$refs.classChart.getContext('2d');
                        
                        // Prepare data for the chart
                        const classes = Object.keys(this.feeCollectionsByClass);
                        const collections = Object.values(this.feeCollectionsByClass);
                        
                        // Create the chart
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: classes,
                                datasets: [
                                    {
                                        label: 'Fee Collections',
                                        data: collections,
                                        backgroundColor: [
                                            'rgba(16, 185, 129, 0.7)',
                                            'rgba(59, 130, 246, 0.7)',
                                            'rgba(139, 92, 246, 0.7)',
                                            'rgba(236, 72, 153, 0.7)',
                                            'rgba(245, 158, 11, 0.7)',
                                            'rgba(6, 182, 212, 0.7)',
                                            'rgba(248, 113, 113, 0.7)',
                                            'rgba(52, 211, 153, 0.7)',
                                        ],
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'KES ' + value.toLocaleString();
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                label += 'KES ' + context.parsed.y.toLocaleString();
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="classChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Class Arrears Chart -->
        <div class="mt-8 bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Outstanding Arrears by Class</h3>
            </div>
            <div class="px-4 py-5 sm:p-6 h-64" x-data="{
                arrearsByClass: @js($arrearsByClass),
                init() {
                    const ctx = this.$refs.arrearsChart.getContext('2d');
                    
                    // Prepare data for the chart
                    const classes = Object.keys(this.arrearsByClass);
                    const arrears = Object.values(this.arrearsByClass);
                    
                    // Create the chart
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: classes,
                            datasets: [
                                {
                                    data: arrears,
                                    backgroundColor: [
                                        'rgba(236, 72, 153, 0.7)',
                                        'rgba(245, 158, 11, 0.7)',
                                        'rgba(6, 182, 212, 0.7)',
                                        'rgba(248, 113, 113, 0.7)',
                                        'rgba(52, 211, 153, 0.7)',
                                        'rgba(16, 185, 129, 0.7)',
                                        'rgba(59, 130, 246, 0.7)',
                                        'rgba(139, 92, 246, 0.7)',
                                    ],
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += 'KES ' + context.parsed.toLocaleString();
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }">
                <canvas x-ref="arrearsChart"></canvas>
            </div>
        </div>

        <!-- Recent Transactions Section -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent Fee Payments -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Fee Payments</h3>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 border-b border-gray-200">
                    <div class="grid grid-cols-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div>Student</div>
                        <div>Amount</div>
                        <div>Date</div>
                        <div>Method</div>
                    </div>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($recentPayments as $payment)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="grid grid-cols-4 items-center">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $payment->student->first_name ?? '' }} {{ $payment->student->last_name ?? 'Unknown Student' }}
                                </div>
                                <div class="text-sm text-gray-900 font-semibold">
                                    KES {{ number_format($payment->amount, 2) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $payment->payment_date->format('d M, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ ucfirst($payment->payment_method) }}
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-4 sm:px-6 text-center text-sm text-gray-500">
                        No recent payments found
                    </li>
                    @endforelse
                </ul>
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                    <div class="text-sm">
                        <a href="{{ route('finance.student-fee-payments') }}" class="font-medium text-indigo-600 hover:text-indigo-500">View all payments <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>

            <!-- Recent Expenses -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Expenses</h3>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 border-b border-gray-200">
                    <div class="grid grid-cols-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div>Recipient</div>
                        <div>Amount</div>
                        <div>Date</div>
                        <div>Purpose</div>
                    </div>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($recentExpenses as $expense)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="grid grid-cols-4 items-center">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $expense->recipient_name }}
                                </div>
                                <div class="text-sm text-gray-900 font-semibold">
                                    KES {{ number_format($expense->amount, 2) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $expense->payment_date->format('d M, Y') }}
                                </div>
                                <div class="text-sm text-gray-500 truncate">
                                    {{ $expense->purpose }}
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-4 sm:px-6 text-center text-sm text-gray-500">
                        No recent expenses found
                    </li>
                    @endforelse
                </ul>
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                    <div class="text-sm">
                        <a href="{{ route('finance.payment-vouchers') }}" class="font-medium text-indigo-600 hover:text-indigo-500">View all expenses <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush 