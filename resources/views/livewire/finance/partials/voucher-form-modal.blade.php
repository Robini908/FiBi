<!-- Voucher Form Modal -->
<div 
    x-data="{ show: @entangle('showModal').defer }"
    x-show="show" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
            aria-hidden="true"
        ></div>

        <!-- Modal panel -->
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"
        >
            <!-- Modal header -->
            <div class="bg-green-50 px-4 py-3 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    {{ $isEditing ? 'Edit Payment Voucher' : 'Create New Payment Voucher' }}
                </h3>
                <button 
                    @click="show = false" 
                    type="button" 
                    class="text-gray-400 hover:text-gray-500 focus:outline-none"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Form content -->
            <form wire:submit.prevent="saveVoucher">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Votehead Selection -->
                        <div class="sm:col-span-3">
                            <label for="votehead_id" class="block text-sm font-medium text-gray-700">Votehead *</label>
                            <div class="mt-1">
                                <select 
                                    id="votehead_id" 
                                    wire:model="votehead_id"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    required
                                >
                                    <option value="">Select a Votehead</option>
                                    @foreach($voteheads as $votehead)
                                        <option value="{{ $votehead->id }}">
                                            {{ $votehead->name }} ({{ $votehead->account->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('votehead_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Amount -->
                        <div class="sm:col-span-3">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (KES) *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">KES</span>
                                </div>
                                <input 
                                    type="number" 
                                    id="amount" 
                                    wire:model="amount"
                                    step="0.01" 
                                    min="0" 
                                    class="focus:ring-green-500 focus:border-green-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                    placeholder="0.00"
                                    required
                                >
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Recipient Details -->
                        <div class="sm:col-span-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Recipient Details</h4>
                        </div>
                        
                        <!-- Recipient Name -->
                        <div class="sm:col-span-4">
                            <label for="recipient_name" class="block text-sm font-medium text-gray-700">Recipient Name *</label>
                            <div class="mt-1">
                                <input 
                                    type="text" 
                                    id="recipient_name" 
                                    wire:model="recipient_name"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    required
                                >
                            </div>
                            @error('recipient_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- ID Number -->
                        <div class="sm:col-span-2">
                            <label for="recipient_id_number" class="block text-sm font-medium text-gray-700">ID Number</label>
                            <div class="mt-1">
                                <input 
                                    type="text" 
                                    id="recipient_id_number" 
                                    wire:model="recipient_id_number"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                >
                            </div>
                            @error('recipient_id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Phone Number -->
                        <div class="sm:col-span-3">
                            <label for="recipient_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="mt-1">
                                <input 
                                    type="text" 
                                    id="recipient_phone" 
                                    wire:model="recipient_phone"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                >
                            </div>
                            @error('recipient_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Address -->
                        <div class="sm:col-span-3">
                            <label for="recipient_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <div class="mt-1">
                                <input 
                                    type="text" 
                                    id="recipient_address" 
                                    wire:model="recipient_address"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                >
                            </div>
                            @error('recipient_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Payment Details -->
                        <div class="sm:col-span-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Details</h4>
                        </div>
                        
                        <!-- Payment Method -->
                        <div class="sm:col-span-3">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                            <div class="mt-1">
                                <select 
                                    id="payment_method" 
                                    wire:model="payment_method"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    required
                                >
                                    @foreach($paymentMethods as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Cheque Number (Only for cheque payment) -->
                        <div class="sm:col-span-3" x-data="{ showChequeField: @entangle('payment_method').defer === 'cheque' }">
                            <label for="cheque_number" class="block text-sm font-medium text-gray-700">
                                Cheque Number <span x-show="showChequeField">*</span>
                            </label>
                            <div class="mt-1">
                                <input 
                                    type="text" 
                                    id="cheque_number" 
                                    wire:model="cheque_number"
                                    x-bind:required="showChequeField"
                                    x-bind:disabled="!showChequeField"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    x-bind:class="{'bg-gray-100': !showChequeField}"
                                >
                            </div>
                            @error('cheque_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Payment Date -->
                        <div class="sm:col-span-3">
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date *</label>
                            <div class="mt-1">
                                <input 
                                    type="date" 
                                    id="payment_date" 
                                    wire:model="payment_date"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    required
                                >
                            </div>
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Academic Period -->
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Academic Period *</label>
                            <div class="mt-1 grid grid-cols-2 gap-2">
                                <div>
                                    <select 
                                        id="academic_year" 
                                        wire:model="academic_year"
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        required
                                    >
                                        <option value="">Year</option>
                                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                    @error('academic_year')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <select 
                                        id="term" 
                                        wire:model="term"
                                        class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        required
                                    >
                                        <option value="">Term</option>
                                        <option value="1">Term 1</option>
                                        <option value="2">Term 2</option>
                                        <option value="3">Term 3</option>
                                    </select>
                                    @error('term')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Purpose -->
                        <div class="sm:col-span-6">
                            <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose *</label>
                            <div class="mt-1">
                                <input 
                                    type="text" 
                                    id="purpose" 
                                    wire:model="purpose"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Short purpose statement"
                                    required
                                >
                            </div>
                            @error('purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                            <div class="mt-1">
                                <textarea 
                                    id="description" 
                                    wire:model="description"
                                    rows="3" 
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Detailed description of payment purpose"
                                    required
                                ></textarea>
                            </div>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Attachment -->
                        <div class="sm:col-span-6">
                            <label for="attachment" class="block text-sm font-medium text-gray-700">
                                Attachment
                                <span class="text-xs text-gray-500">(PDF, JPG, JPEG, PNG - Max 2MB)</span>
                            </label>
                            <div class="mt-1">
                                <input 
                                    type="file" 
                                    id="attachment" 
                                    wire:model="attachment"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                >
                                <div wire:loading wire:target="attachment" class="text-sm text-gray-500 mt-1">
                                    Uploading...
                                </div>
                            </div>
                            @error('attachment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            @if($tempAttachmentPath)
                                <div class="mt-2 flex items-center space-x-2">
                                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm text-gray-700">Existing attachment</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Modal footer / action buttons -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        type="submit" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.class="hidden" wire:target="saveVoucher">
                            {{ $isEditing ? 'Update Voucher' : 'Create Voucher' }}
                        </span>
                        <span wire:loading wire:target="saveVoucher" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                    <button 
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click="closeModal"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 