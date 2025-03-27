<!-- Payment Form -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">Record New Payment</h3>
        <button 
            wire:click="closePaymentForm" 
            class="text-gray-400 hover:text-gray-500 focus:outline-none"
            aria-label="Close payment form"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div class="p-6">
        <p class="text-sm text-gray-600 mb-4">Fill out the form below to record a payment for <span class="font-medium">{{ $student_name }}</span> for <span class="font-medium">{{ $academic_year }}</span>, Term <span class="font-medium">{{ $term }}</span>.</p>
        
        @if($hasPaymentPermission)
            <form wire:submit.prevent="savePayment" class="space-y-5">
            <!-- Amount -->
            <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount (Ksh) <span class="text-red-500">*</span></label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Ksh</span>
                    </div>
                    <input 
                        type="number" 
                        step="0.01" 
                        id="amount" 
                        wire:model="amount"
                            class="block w-full pl-12 pr-12 py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
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
                    <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date <span class="text-red-500">*</span></label>
                <div class="mt-1">
                    <input 
                        type="date" 
                        id="payment_date" 
                        wire:model="payment_date"
                            class="block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
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
                        <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year <span class="text-red-500">*</span></label>
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
                        <label for="term" class="block text-sm font-medium text-gray-700">Term <span class="text-red-500">*</span></label>
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
            
                <!-- Payment Method Selector with Icons -->
            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div 
                            wire:click="$set('payment_method', 'cash')" 
                            class="cursor-pointer p-3 rounded-lg border {{ $payment_method === 'cash' ? 'bg-green-50 border-green-500 ring-1 ring-green-500' : 'border-gray-300 hover:border-green-400' }} transition-all duration-150 ease-in-out"
                        >
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-6 h-6 {{ $payment_method === 'cash' ? 'text-green-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="mt-2 text-sm font-medium {{ $payment_method === 'cash' ? 'text-green-800' : 'text-gray-700' }}">Cash</span>
                            </div>
                        </div>

                        <div 
                            wire:click="$set('payment_method', 'cheque')" 
                            class="cursor-pointer p-3 rounded-lg border {{ $payment_method === 'cheque' ? 'bg-green-50 border-green-500 ring-1 ring-green-500' : 'border-gray-300 hover:border-green-400' }} transition-all duration-150 ease-in-out"
                        >
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-6 h-6 {{ $payment_method === 'cheque' ? 'text-green-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="mt-2 text-sm font-medium {{ $payment_method === 'cheque' ? 'text-green-800' : 'text-gray-700' }}">Cheque</span>
                            </div>
                        </div>

                        <div 
                            wire:click="$set('payment_method', 'bank_transfer')" 
                            class="cursor-pointer p-3 rounded-lg border {{ $payment_method === 'bank_transfer' ? 'bg-green-50 border-green-500 ring-1 ring-green-500' : 'border-gray-300 hover:border-green-400' }} transition-all duration-150 ease-in-out"
                        >
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-6 h-6 {{ $payment_method === 'bank_transfer' ? 'text-green-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                </svg>
                                <span class="mt-2 text-sm font-medium {{ $payment_method === 'bank_transfer' ? 'text-green-800' : 'text-gray-700' }}">Bank Transfer</span>
                            </div>
                        </div>

                        <div 
                            wire:click="$set('payment_method', 'mpesa')" 
                            class="cursor-pointer p-3 rounded-lg border {{ $payment_method === 'mpesa' ? 'bg-green-50 border-green-500 ring-1 ring-green-500' : 'border-gray-300 hover:border-green-400' }} transition-all duration-150 ease-in-out"
                        >
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-6 h-6 {{ $payment_method === 'mpesa' ? 'text-green-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="mt-2 text-sm font-medium {{ $payment_method === 'mpesa' ? 'text-green-800' : 'text-gray-700' }}">M-Pesa</span>
                            </div>
                        </div>
                    </div>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Conditional fields based on payment method -->
            @if($payment_method === 'cheque')
                    <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Cheque Details</h4>
                <div class="space-y-4">
                        <!-- Cheque Number -->
                    <div>
                                <label for="cheque_number" class="block text-sm font-medium text-gray-700">Cheque Number <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="cheque_number" 
                                wire:model="cheque_number"
                                    class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                required
                            >
                            @error('cheque_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Bank Name -->
                    <div>
                                <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="bank_name" 
                                wire:model="bank_name"
                                    class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                required
                            >
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Cheque Date -->
                    <div>
                                <label for="cheque_date" class="block text-sm font-medium text-gray-700">Cheque Date <span class="text-red-500">*</span></label>
                            <input 
                                type="date" 
                                id="cheque_date" 
                                wire:model="cheque_date"
                                    class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                required
                            >
                            @error('cheque_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @elseif($payment_method === 'bank_transfer')
                    <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Bank Transfer Details</h4>
                <div class="space-y-4">
                        <!-- Bank Name -->
                    <div>
                                <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="bank_name" 
                                wire:model="bank_name"
                                    class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                required
                            >
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Bank Slip Number -->
                    <div>
                                <label for="bank_slip_number" class="block text-sm font-medium text-gray-700">Bank Slip Number <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="bank_slip_number" 
                                wire:model="bank_slip_number"
                                    class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
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
                                    class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                            >
                            @error('bank_branch')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @elseif($payment_method === 'mpesa')
                    <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">M-Pesa Details</h4>
                <div class="space-y-4">
                        <!-- M-Pesa Transaction ID -->
                    <div>
                                <label for="mpesa_transaction_id" class="block text-sm font-medium text-gray-700">M-Pesa Transaction ID <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="mpesa_transaction_id" 
                                wire:model="mpesa_transaction_id"
                                    class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                placeholder="e.g. QKL12345MP"
                                required
                            >
                            @error('mpesa_transaction_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Phone Number -->
                    <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="phone_number"
                                wire:model="phone_number"
                                    class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                placeholder="e.g. 0712345678"
                                required
                            >
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                    
                        <!-- Transaction Time -->
                    <div>
                                <label for="mpesa_transaction_time" class="block text-sm font-medium text-gray-700">Transaction Time <span class="text-red-500">*</span></label>
                            <input 
                                type="datetime-local" 
                                    id="mpesa_transaction_time" 
                                    wire:model="mpesa_transaction_time"
                                    class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                                    required
                                >
                                @error('mpesa_transaction_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif
            
                <!-- Notes + Receipt -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                    <textarea 
                    id="notes"
                        wire:model="notes" 
                                class="mt-1 block w-full py-2 sm:text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500 shadow-sm"
                        rows="3" 
                    ></textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
                        <div>
                            <label for="receipt_attachment" class="block text-sm font-medium text-gray-700">Upload Receipt (optional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="receipt_attachment" class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                            <span>Upload a file</span>
                    <input 
                                                id="receipt_attachment" 
                                                wire:model="receipt_attachment"
                                                name="receipt_attachment" 
                                                type="file" 
                                                class="sr-only"
                                            >
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF up to 2MB</p>
                </div>
            </div>
                            @error('receipt_attachment')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
                            @if($receipt_attachment)
                                <p class="mt-2 text-sm text-green-600">File selected: {{ $receipt_attachment->getClientOriginalName() }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            
            <!-- Submit Button -->
                <div class="flex justify-end pt-4">
                    <button 
                        type="button" 
                        wire:click="closePaymentForm" 
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-3"
                    >
                        Cancel
                    </button>
                <button 
                    type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                        <svg wire:loading class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                        <span wire:loading.remove>Record Payment</span>
                        <span wire:loading>Processing...</span>
                </button>
            </div>
        </form>
    @else
            <div class="text-center py-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Permission Denied</h3>
                <p class="mt-1 text-sm text-gray-500">You do not have permission to record payments.</p>
    </div>
    @endif
    </div>
</div>