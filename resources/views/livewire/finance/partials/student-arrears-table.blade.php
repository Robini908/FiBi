<!-- Arrears Table -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                @if(!$isStudent)
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                @endif
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Term</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (KES)</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                @if($isAdmin || $isAccountant)
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($arrears as $arrear)
                <tr class="hover:bg-gray-50">
                    @if(!$isStudent)
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $arrear->student->name ?? 'Unknown Student' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $arrear->student->admission_number ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $arrear->myClass->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $arrear->previous_year }} (Term {{ $arrear->previous_term }})
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        KES {{ number_format($arrear->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($arrear->is_cleared)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Cleared
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Pending
                            </span>
                        @endif
                    </td>
                    @if($isAdmin || $isAccountant)
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex space-x-2">
                            @if(!$arrear->is_cleared)
                                <button 
                                    wire:click="editArrear({{ $arrear->id }})" 
                                    class="text-indigo-600 hover:text-indigo-900"
                                    title="Edit"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button 
                                    onclick="confirm('Are you sure you want to mark this arrear as cleared?') || event.stopImmediatePropagation()" 
                                    wire:click="markArrearAsCleared({{ $arrear->id }})" 
                                    class="text-green-600 hover:text-green-900"
                                    title="Mark as Cleared"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button 
                                    onclick="confirm('Are you sure you want to delete this arrear?') || event.stopImmediatePropagation()" 
                                    wire:click="deleteArrear({{ $arrear->id }})" 
                                    class="text-red-600 hover:text-red-900"
                                    title="Delete"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @else
                                <span class="text-gray-400 italic">No actions available</span>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ ($isStudent ? 4 : 5) + ($isAdmin || $isAccountant ? 1 : 0) }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        @if($isAdmin || $isAccountant)
                            No arrears found. Add an arrear or use "Generate Arrears" to create new records.
                        @else
                            No arrears found.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="px-6 py-4">
    {{ $arrears->links() }}
</div> 