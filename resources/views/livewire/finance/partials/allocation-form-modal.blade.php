<div
    x-data="{ open: $wire.entangle('showAllocationModal') }"
    x-show="open"
    class="fixed inset-0 overflow-y-auto z-50"
    x-on:keydown.escape.window="open = false"
    style="display: none;"
    x-cloak
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity"
            aria-hidden="true"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-headline"
        >
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button
                    type="button"
                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                    wire:click="closeAllocationModal"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Header -->
            <div class="sm:flex sm:items-start border-b border-gray-100 pb-4 mb-4">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                        @if($editAllocationId && !$isEditing)
                            View Fee Allocation
                        @elseif($editAllocationId)
                            Edit Fee Allocation
                        @else
                            New Fee Allocation
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($editAllocationId && !$isEditing)
                            View details for this fee allocation
                        @elseif($editAllocationId)
                            Update information for this fee allocation
                        @else
                            Create a new fee allocation to distribute funds to voteheads
                        @endif
                    </p>
                </div>
            </div>

            <div class="w-full">
                <form wire:submit.prevent="{{ $editAllocationId && $isEditing ? 'updateAllocation' : ($editAllocationId ? null : 'createAllocation') }}">
                    <div class="space-y-5">
                        <!-- Account balance info card (only shows when account is selected) -->
                        @if($form['finance_account_id'] && $accountBalance > 0)
                            <div class="bg-blue-50 border border-blue-100 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1 md:flex md:justify-between">
                                        <p class="text-sm text-blue-700">
                                            <span class="font-medium">{{ $accountName }}</span> has a balance of <span class="font-medium">KES {{ number_format($accountBalance, 2) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Finance Account -->
                        <div>
                            <label for="finance_account_id" class="block text-sm font-medium text-gray-700">Finance Account</label>
                            <select
                                id="finance_account_id"
                                wire:model.live="form.finance_account_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm transition-colors duration-150 @error('form.finance_account_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                {{ !$isEditing || $form['is_approved'] ? 'disabled' : '' }}
                            >
                                <option value="">Select Account</option>
                                @foreach($financeAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} (Balance: KES {{ number_format($account->current_balance, 2) }})</option>
                                @endforeach
                            </select>
                            @error('form.finance_account_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Votehead -->
                        <div>
                            <label for="votehead_id" class="block text-sm font-medium text-gray-700">Votehead</label>
                            <select
                                id="votehead_id"
                                wire:model="form.votehead_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm transition-colors duration-150 @error('form.votehead_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                {{ !$isEditing || $form['is_approved'] ? 'disabled' : '' }}
                                {{ count($availableVoteheads) === 0 ? 'disabled' : '' }}
                            >
                                <option value="">{{ count($availableVoteheads) === 0 ? 'Select an account first' : 'Select Votehead' }}</option>
                                @foreach($availableVoteheads as $votehead)
                                    <option value="{{ $votehead->id }}">{{ $votehead->name }} ({{ $votehead->category ?? 'No category' }})</option>
                                @endforeach
                            </select>
                            @error('form.votehead_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (KES)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">KES</span>
                                </div>
                                <input
                                    type="number"
                                    id="amount"
                                    step="0.01"
                                    wire:model="form.amount"
                                    class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm transition-colors duration-150 @error('form.amount') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="0.00"
                                    {{ !$isEditing || $form['is_approved'] ? 'disabled' : '' }}
                                >
                            </div>
                            @error('form.amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Academic Year & Term -->
                        <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                            <div>
                                <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year</label>
                                <select
                                    id="academic_year"
                                    wire:model="form.academic_year"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm transition-colors duration-150 @error('form.academic_year') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    {{ !$isEditing || $form['is_approved'] ? 'disabled' : '' }}
                                >
                                    <option value="">Select Year</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                @error('form.academic_year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                                <select
                                    id="term"
                                    wire:model="form.term"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm transition-colors duration-150 @error('form.term') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    {{ !$isEditing || $form['is_approved'] ? 'disabled' : '' }}
                                >
                                    <option value="">Select Term</option>
                                    <option value="1">Term 1</option>
                                    <option value="2">Term 2</option>
                                    <option value="3">Term 3</option>
                                </select>
                                @error('form.term')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea
                                id="description"
                                wire:model="form.description"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm transition-colors duration-150 @error('form.description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Purpose of this allocation"
                                {{ !$isEditing || $form['is_approved'] ? 'disabled' : '' }}
                            ></textarea>
                            @error('form.description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors duration-150"
                                wire:click="closeAllocationModal"
                            >
                                {{ $editAllocationId && !$isEditing ? 'Close' : 'Cancel' }}
                            </button>
                            
                            @if(!$editAllocationId || ($editAllocationId && $isEditing && !$form['is_approved']))
                                <button
                                    type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors duration-150"
                                    wire:loading.attr="disabled"
                                >
                                    <span wire:loading.class="hidden" wire:target="{{ $editAllocationId && $isEditing ? 'updateAllocation' : 'createAllocation' }}">
                                        {{ $editAllocationId && $isEditing ? 'Update Allocation' : 'Create Allocation' }}
                                    </span>
                                    <span wire:loading wire:target="{{ $editAllocationId && $isEditing ? 'updateAllocation' : 'createAllocation' }}" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 