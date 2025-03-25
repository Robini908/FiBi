<!-- Payment Form -->
<div>
    <div class="py-4 px-6 bg-white rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Record New Payment</h3>
        <p class="text-sm text-gray-600 mb-4">Fill out the form below to record a new fee payment.</p>
    
    @if($hasManagePermission)
        <form wire:submit.prevent="savePayment" class="space-y-4">
            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount (Ksh) <span class="text-red-600">*</span></label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Ksh</span>
                    </div>
                    <input 
                        type="number" 
                        step="0.01" 
                        id="amount" 
                        wire:model="amount"
                        class="block w-full pl-12 pr-12 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                        placeholder="0.00"
                        required
                    >
                </div>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Payment Date -->
            <div>
                <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date <span class="text-red-600">*</span></label>
                <div class="mt-1">
                    <input 
                        type="date" 
                        id="payment_date" 
                        wire:model="payment_date"
                        class="block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                        required
                    >
                </div>
                @error('payment_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Academic Year & Term -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year <span class="text-red-600">*</span></label>
                        <select 
                        id="academic_year"
                            wire:model="academic_year" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                            required
                        >
                        @php
                            $currentYear = date('Y');
                            $years = range($currentYear, $currentYear - 2);
                        @endphp
                        
                        @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                        </select>
                    @error('academic_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="term" class="block text-sm font-medium text-gray-700">Term <span class="text-red-600">*</span></label>
                        <select 
                        id="term"
                            wire:model="term" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                            required
                        >
                            <option value="1">Term 1</option>
                            <option value="2">Term 2</option>
                            <option value="3">Term 3</option>
                        </select>
                    @error('term')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Payment Method -->
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method <span class="text-red-600">*</span></label>
                    <select 
                    id="payment_method"
                        wire:model="payment_method" 
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        required
                    >
                    <option value="">Select payment method</option>
                        <option value="cash">Cash</option>
                        <option value="cheque">Cheque</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="mpesa">M-Pesa</option>
                    </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Conditional fields based on payment method -->
            @if($payment_method === 'cheque')
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Cheque Details</h4>
                <div class="space-y-4">
                        <!-- Cheque Number -->
                    <div>
                            <label for="cheque_number" class="block text-sm font-medium text-gray-700">Cheque Number <span class="text-red-600">*</span></label>
                            <input 
                                type="text" 
                                id="cheque_number" 
                                wire:model="cheque_number"
                                class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                                required
                            >
                            @error('cheque_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Bank Name -->
                    <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name <span class="text-red-600">*</span></label>
                            <input 
                                type="text" 
                                id="bank_name" 
                                wire:model="bank_name"
                                class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                                required
                            >
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Cheque Date -->
                    <div>
                            <label for="cheque_date" class="block text-sm font-medium text-gray-700">Cheque Date <span class="text-red-600">*</span></label>
                            <input 
                                type="date" 
                                id="cheque_date" 
                                wire:model="cheque_date"
                                class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                                required
                            >
                            @error('cheque_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @elseif($payment_method === 'bank_transfer')
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Bank Transfer Details</h4>
                <div class="space-y-4">
                        <!-- Bank Name -->
                    <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name <span class="text-red-600">*</span></label>
                            <input 
                                type="text" 
                                id="bank_name" 
                                wire:model="bank_name"
                                class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                                required
                            >
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Bank Slip Number -->
                    <div>
                            <label for="bank_slip_number" class="block text-sm font-medium text-gray-700">Bank Slip Number <span class="text-red-600">*</span></label>
                            <input 
                                type="text" 
                                id="bank_slip_number" 
                                wire:model="bank_slip_number"
                                class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                                required
                            >
                            @error('bank_slip_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Bank Branch -->
                    <div>
                            <label for="bank_branch" class="block text-sm font-medium text-gray-700">Bank Branch</label>
                            <input 
                                type="text" 
                                id="bank_branch" 
                                wire:model="bank_branch"
                                class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                            >
                            @error('bank_branch')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @elseif($payment_method === 'mpesa')
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">M-Pesa Details</h4>
                <div class="space-y-4">
                        <!-- M-Pesa Transaction ID -->
                    <div>
                            <label for="mpesa_transaction_id" class="block text-sm font-medium text-gray-700">M-Pesa Transaction ID <span class="text-red-600">*</span></label>
                            <input 
                                type="text" 
                                id="mpesa_transaction_id" 
                                wire:model="mpesa_transaction_id"
                                class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g. QKL12345MP"
                                required
                            >
                            @error('mpesa_transaction_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Phone Number -->
                    <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-600">*</span></label>
                            <input 
                                type="text" 
                                id="phone_number"
                                wire:model="phone_number"
                                class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g. 0712345678"
                                required
                            >
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Transaction Time -->
                    <div>
                            <label for="transaction_time" class="block text-sm font-medium text-gray-700">Transaction Time</label>
                            <input 
                                type="datetime-local" 
                                id="transaction_time"
                                wire:model="transaction_time"
                                class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                            >
                            @error('transaction_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Receipt Attachment -->
            <div>
                <label for="receipt_attachment" class="block text-sm font-medium text-gray-700">Receipt Attachment (optional)</label>
                <div class="mt-1 flex items-center">
                    <input 
                        type="file" 
                        id="receipt_attachment"
                        wire:model="receipt_attachment"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                    >
                </div>
                <p class="mt-1 text-xs text-gray-500">Upload a scan or photo of the receipt (if available)</p>
                @error('receipt_attachment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                    <textarea 
                    id="notes"
                        wire:model="notes" 
                        rows="3" 
                    class="mt-1 block w-full sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500"
                    placeholder="Any remarks about this payment..."
                    ></textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Confirm Checkbox -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input 
                        id="confirm_payment"
                        wire:model="confirm_payment"
                        type="checkbox" 
                        class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded"
                        required
                    >
                </div>
                <div class="ml-3 text-sm">
                    <label for="confirm_payment" class="font-medium text-gray-700">I confirm this payment is accurate</label>
                    <p class="text-gray-500">By checking this box, you confirm that you have verified all details of this payment.</p>
                </div>
            </div>
            @error('confirm_payment')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            
            <!-- Submit Button -->
            <div class="pt-4">
                <button 
                    type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="savePayment">Record Payment</span>
                    <span wire:loading wire:target="savePayment" class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </form>
    @else
        <div class="rounded-md bg-yellow-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Permission Required</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>You don't have permission to record payments. Please contact your administrator.</p>
                    </div>
                </div>
            </div>
    </div>
    @endif
    </div>
</div>