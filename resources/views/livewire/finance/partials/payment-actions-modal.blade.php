<!-- Payment Cancel Confirmation Modal -->
<div 
    x-data="{ show: false }" 
    x-init="
        $watch('$wire.open_cancel_modal', value => { 
            if (value) { 
                show = true;
                document.body.classList.add('overflow-hidden');
            } else {
                setTimeout(() => { show = false; }, 200);
                document.body.classList.remove('overflow-hidden');
            }
        });
    "
    x-show="show"
    x-transition.opacity.duration.300ms
    @keydown.escape.window="$wire.closeCancelModal()"
    class="fixed inset-0 z-50 overflow-y-auto"
    x-cloak
>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            @click="$wire.closeCancelModal()" 
            class="fixed inset-0 transition-opacity"
            aria-hidden="true"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal panel -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" 
            aria-modal="true" 
            aria-labelledby="cancel-payment-modal"
        >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="cancel-payment-modal">
                            Cancel Payment
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to cancel this payment? This action cannot be undone and will be logged for audit purposes.
                            </p>
                            
                            <div class="mt-4">
                                <label for="cancelReason" class="block text-sm font-medium text-gray-700">Reason for Cancellation <span class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <textarea 
                                        id="cancelReason" 
                                        name="cancelReason" 
                                        rows="3" 
                                        wire:model.defer="cancellation_reason" 
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Provide a reason for cancelling this payment"
                                        required
                                        x-init="$nextTick(() => { $el.focus() })"
                                    ></textarea>
                                </div>
                                @error('cancellation_reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    type="button" 
                    wire:click="processCancelPaymentAction" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.class="inline-flex" wire:loading.class.remove="hidden" wire:target="processCancelPaymentAction" class="hidden mr-2">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span wire:loading.class="hidden" wire:target="processCancelPaymentAction">Confirm Cancellation</span>
                    <span wire:loading.class.remove="hidden" wire:loading class="hidden" wire:target="processCancelPaymentAction">Processing...</span>
                </button>
                <button 
                    type="button" 
                    wire:click="closeCancelModal" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Confirmation Modal -->
<div 
    x-data="{ show: false }" 
    x-init="
        $watch('$wire.open_confirm_modal', value => { 
            if (value) { 
                show = true;
                document.body.classList.add('overflow-hidden');
            } else {
                setTimeout(() => { show = false; }, 200);
                document.body.classList.remove('overflow-hidden');
            }
        });
    "
    x-show="show"
    x-transition.opacity.duration.300ms
    @keydown.escape.window="$wire.closeConfirmModal()"
    class="fixed inset-0 z-50 overflow-y-auto"
    x-cloak
>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            @click="$wire.closeConfirmModal()" 
            class="fixed inset-0 transition-opacity"
            aria-hidden="true"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal panel -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" 
            aria-modal="true" 
            aria-labelledby="confirm-payment-modal"
        >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="confirm-payment-modal">
                            Confirm Payment
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to confirm this payment? This will mark the payment as verified and update the student's fee status.
                            </p>
                            
                            <div class="mt-4">
                                <label for="confirmationNote" class="block text-sm font-medium text-gray-700">Additional Notes (Optional)</label>
                                <div class="mt-1">
                                    <textarea 
                                        id="confirmationNote" 
                                        name="confirmationNote" 
                                        rows="2" 
                                        wire:model.defer="confirmationNote" 
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Any additional notes regarding this payment confirmation"
                                        x-init="$nextTick(() => { $el.focus() })"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    type="button" 
                    wire:click="confirmPaymentAction" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.class="inline-flex" wire:loading.class.remove="hidden" wire:target="confirmPaymentAction" class="hidden mr-2">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span wire:loading.class="hidden" wire:target="confirmPaymentAction">Confirm Payment</span>
                    <span wire:loading.class.remove="hidden" wire:loading class="hidden" wire:target="confirmPaymentAction">Processing...</span>
                </button>
                <button 
                    type="button" 
                    wire:click="closeConfirmModal" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
<div 
    x-data="{ show: false }" 
    x-init="
        $watch('$wire.showingEditModal', value => { 
            if (value) { 
                show = true; 
                document.body.classList.add('overflow-hidden');
            } else {
                setTimeout(() => { show = false; }, 200);
                document.body.classList.remove('overflow-hidden');
            }
        });
    "
    x-show="show"
    x-transition.opacity.duration.300ms
    @keydown.escape.window="$wire.closeEditModal()"
    class="fixed inset-0 z-50 overflow-y-auto"
    x-cloak
>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            @click="$wire.closeEditModal()" 
            class="fixed inset-0 transition-opacity"
            aria-hidden="true"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal panel -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" 
            aria-modal="true" 
            aria-labelledby="edit-payment-modal"
        >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="edit-payment-modal">
                            Edit Payment
                        </h3>
                        <div class="mt-4 space-y-4">
                            <!-- Amount -->
                            <div>
                                <label for="editAmount" class="block text-sm font-medium text-gray-700">Amount (Ksh) <span class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        id="editAmount" 
                                        wire:model.defer="editData.amount" 
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        required
                                        x-init="$nextTick(() => { $el.focus() })"
                                    >
                                </div>
                                @error('editData.amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Payment Date -->
                            <div>
                                <label for="editPaymentDate" class="block text-sm font-medium text-gray-700">Payment Date <span class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <input 
                                        type="date" 
                                        id="editPaymentDate" 
                                        wire:model.defer="editData.payment_date" 
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        required
                                    >
                                </div>
                                @error('editData.payment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Notes -->
                            <div>
                                <label for="editNotes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <div class="mt-1">
                                    <textarea 
                                        id="editNotes" 
                                        wire:model.defer="editData.notes" 
                                        rows="3" 
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Any notes about this payment"
                                    ></textarea>
                                </div>
                                @error('editData.notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Edit Reason -->
                            <div>
                                <label for="editReason" class="block text-sm font-medium text-gray-700">Reason for Edit <span class="text-red-500">*</span></label>
                                <div class="mt-1">
                                    <textarea 
                                        id="editReason" 
                                        wire:model.defer="editReason" 
                                        rows="2" 
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Why are you editing this payment?"
                                        required
                                    ></textarea>
                                </div>
                                @error('editReason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    type="button" 
                    wire:click="updatePayment" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.class="inline-flex" wire:loading.class.remove="hidden" wire:target="updatePayment" class="hidden mr-2">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span wire:loading.class="hidden" wire:target="updatePayment">Update Payment</span>
                    <span wire:loading.class.remove="hidden" wire:loading class="hidden" wire:target="updatePayment">Updating...</span>
                </button>
                <button 
                    type="button" 
                    wire:click="closeEditModal" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 