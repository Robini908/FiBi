<?php

namespace App\Livewire\Finance;

use App\Models\PaymentVoucher;
use App\Models\AccountVotehead;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Usernotnull\Toast\Concerns\WireToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\WithSorting;
use App\Helpers\Qs;

class PaymentVouchers extends Component
{
    use WithPagination, WithFileUploads, WireToast, WithSorting;
    
    // Component properties
    public $showModal = false;
    public $showViewModal = false;
    public $showDeleteModal = false;
    public $showApproveModal = false;
    public $showPayModal = false;
    public $showCancelModal = false;
    public $isEditing = false;
    public $isViewing = false;
    public $voucherId = null;
    public $search = '';
    public $voteheadFilter = '';
    public $yearFilter = '';
    public $termFilter = '';
    public $statusFilter = '';
    public $dateFrom = null;
    public $dateTo = null;
    public $cancellationReason = '';
    public $attachment = null;
    public $tempAttachmentPath = null;
    public $isAdmin = false;
    public $isAccountant = false;
    public $isStudent = false;
    public $isTeacher = false;
    
    // Form fields
    public $voucher_number;
    public $votehead_id;
    public $amount;
    public $cheque_number = null;
    public $recipient_name;
    public $recipient_id_number = null;
    public $recipient_phone = null;
    public $recipient_address = null;
    public $description;
    public $purpose;
    public $academic_year;
    public $term;
    public $payment_date;
    public $payment_method = 'cheque';
    
    // Validation rules
    protected function rules()
    {
        return [
            'votehead_id' => 'required|exists:account_voteheads,id',
            'amount' => 'required|numeric|min:1',
            'cheque_number' => $this->payment_method === 'cheque' ? 'required|string|max:50' : 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'recipient_id_number' => 'nullable|string|max:20',
            'recipient_phone' => 'nullable|string|max:15',
            'recipient_address' => 'nullable|string|max:200',
            'description' => 'required|string|max:500',
            'purpose' => 'required|string|max:100',
            'academic_year' => 'required|integer|min:2000|max:2100',
            'term' => 'required|integer|min:1|max:3',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,cheque,bank_transfer,mpesa',
            'attachment' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',
        ];
    }
    
    // Custom validation messages
    protected function messages()
    {
        return [
            'votehead_id.required' => 'Please select a votehead',
            'amount.required' => 'The amount is required',
            'amount.numeric' => 'The amount must be a number',
            'amount.min' => 'The amount must be greater than zero',
            'cheque_number.required' => 'The cheque number is required for cheque payments',
            'recipient_name.required' => 'The recipient name is required',
            'description.required' => 'Please provide a description',
            'purpose.required' => 'Please specify the purpose of this payment',
            'attachment.max' => 'The attachment must not exceed 2MB',
            'attachment.mimes' => 'The attachment must be a PDF, JPG, JPEG, or PNG file',
        ];
    }
    
    // Listeners for events
    protected $listeners = [
        'showVoucher', 
        'confirmDeleteVoucher', 
        'confirmApproveVoucher', 
        'confirmPayVoucher', 
        'confirmCancelVoucher',
        'exportVouchers'
    ];
    
    /**
     * Component initialization
     */
    public function mount()
    {
        // Set user roles
        $this->isAdmin = Qs::isAdministrator() || Qs::isAdmin();
        $this->isAccountant = Qs::isAccountant();
        $this->isStudent = Qs::isStudent();
        $this->isTeacher = Qs::isTeacher();
        
        // Set default academic year to current year
        $this->academic_year = date('Y');
        
        // Determine current term based on month
        $month = date('n');
        if ($month >= 1 && $month <= 4) {
            $this->term = 1;
        } elseif ($month >= 5 && $month <= 8) {
            $this->term = 2;
        } else {
            $this->term = 3;
        }
        
        $this->payment_date = date('Y-m-d');
        
        // Set initial sort field
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }
    
    /**
     * Reset the form fields
     */
    public function resetForm()
    {
        $this->reset([
            'votehead_id', 'amount', 'cheque_number', 'recipient_name',
            'recipient_id_number', 'recipient_phone', 'recipient_address',
            'description', 'purpose', 'payment_method', 'isEditing', 'voucherId',
            'attachment', 'tempAttachmentPath', 'cancellationReason'
        ]);
        
        // Set defaults
        $this->academic_year = date('Y');
        $month = date('n');
        if ($month >= 1 && $month <= 4) {
            $this->term = 1;
        } elseif ($month >= 5 && $month <= 8) {
            $this->term = 2;
        } else {
            $this->term = 3;
        }
        
        $this->payment_date = date('Y-m-d');
        $this->payment_method = 'cheque';
        
        // Reset validation errors
        $this->resetValidation();
    }
    
    /**
     * Open the create/edit modal
     */
    public function openVoucherModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }
    
    /**
     * Close the modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    /**
     * Handle the payment method change
     */
    public function updatedPaymentMethod()
    {
        if ($this->payment_method !== 'cheque') {
            $this->cheque_number = null;
        }
    }
    
    /**
     * Create a new payment voucher
     */
    public function saveVoucher()
    {
        if ($this->isEditing) {
            $this->updateVoucher();
            return;
        }
        
        try {
            $this->validate();
            
            // Check if votehead exists and has enough balance
            $votehead = AccountVotehead::findOrFail($this->votehead_id);
            
            if (!$votehead->is_active) {
                toast()
                    ->warning('Cannot create voucher for an inactive votehead')
                    ->push();
                return;
            }
            
            if ($votehead->balance < $this->amount) {
                toast()
                    ->warning("Insufficient funds in this votehead. Available balance: KES " . number_format($votehead->balance, 2))
                    ->push();
                return;
            }
            
            // Generate voucher number
            $voucher_number = PaymentVoucher::generateVoucherNumber();
            
            // Handle file upload if attachment exists
            $attachmentPath = null;
            if ($this->attachment) {
                try {
                    $attachmentPath = $this->attachment->store('voucher-attachments', 'public');
                    if (!$attachmentPath) {
                        \Log::error('Failed to store attachment file');
                    }
                } catch (\Exception $fileEx) {
                    \Log::error('Error uploading file: ' . $fileEx->getMessage());
                    toast()
                        ->warning('File upload failed: ' . $fileEx->getMessage())
                        ->push();
                }
            }
            
            // Create the voucher
            $voucher = PaymentVoucher::create([
                'voucher_number' => $voucher_number,
                'votehead_id' => $this->votehead_id,
                'amount' => $this->amount,
                'cheque_number' => $this->cheque_number,
                'recipient_name' => $this->recipient_name,
                'recipient_id_number' => $this->recipient_id_number,
                'recipient_phone' => $this->recipient_phone,
                'recipient_address' => $this->recipient_address,
                'description' => $this->description,
                'purpose' => $this->purpose,
                'academic_year' => $this->academic_year,
                'term' => $this->term,
                'payment_date' => $this->payment_date,
                'status' => 'pending',
                'is_cancelled' => false,
                'payment_method' => $this->payment_method,
                'attachment_path' => $attachmentPath,
                'created_by' => auth()->user()->name,
            ]);
            
            $this->closeModal();
            $this->showVoucher($voucher->id);
            
            toast()
                ->success('Payment voucher created successfully with number #' . $voucher_number)
                ->push();
        } catch (\Exception $e) {
            \Log::error('Error creating voucher: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            toast()
                ->danger('Error creating voucher: ' . $e->getMessage())
                ->push();
        }
    }
    
    /**
     * Load voucher data for editing
     */
    public function editVoucher($id)
    {
        try {
            $voucher = PaymentVoucher::findOrFail($id);
            
            // Check permissions
            if (!$this->isAdmin && !$this->isAccountant && $voucher->created_by !== auth()->user()->name) {
                toast()
                    ->warning('You do not have permission to edit this voucher')
                    ->push();
                return;
            }
            
            if ($voucher->status !== 'pending') {
                toast()
                    ->warning('Only pending vouchers can be edited')
                    ->push();
                return;
            }
            
            if ($voucher->is_cancelled) {
                toast()
                    ->warning('Cancelled vouchers cannot be edited')
                    ->push();
                return;
            }
            
            $this->voucherId = $voucher->id;
            $this->votehead_id = $voucher->votehead_id;
            $this->amount = $voucher->amount;
            $this->cheque_number = $voucher->cheque_number;
            $this->recipient_name = $voucher->recipient_name;
            $this->recipient_id_number = $voucher->recipient_id_number;
            $this->recipient_phone = $voucher->recipient_phone;
            $this->recipient_address = $voucher->recipient_address;
            $this->description = $voucher->description;
            $this->purpose = $voucher->purpose;
            $this->academic_year = $voucher->academic_year;
            $this->term = $voucher->term;
            $this->payment_date = $voucher->payment_date;
            $this->payment_method = $voucher->payment_method;
            $this->tempAttachmentPath = $voucher->attachment_path;
            
            $this->isEditing = true;
            $this->showModal = true;
        } catch (\Exception $e) {
            toast()
                ->danger('Error loading voucher: ' . $e->getMessage())
                ->push();
        }
    }
    
    /**
     * Update an existing voucher
     */
    public function updateVoucher()
    {
        try {
            $this->validate();
            
            $voucher = PaymentVoucher::findOrFail($this->voucherId);
            
            // Check if votehead exists and has enough balance if amount changed
            $votehead = AccountVotehead::findOrFail($this->votehead_id);
            
            if (!$votehead->is_active) {
                toast()
                    ->warning('Cannot assign voucher to an inactive votehead')
                    ->push();
                return;
            }
            
            // Only check balance if amount increased or votehead changed
            if ($this->votehead_id != $voucher->votehead_id || $this->amount > $voucher->amount) {
                $additionalAmount = $this->votehead_id == $voucher->votehead_id 
                    ? $this->amount - $voucher->amount 
                    : $this->amount;
                    
                if ($additionalAmount > 0 && $votehead->balance < $additionalAmount) {
                    toast()
                        ->warning("Insufficient funds in this votehead. Available balance: KES " . number_format($votehead->balance, 2))
                        ->push();
                    return;
                }
            }
            
            // Handle file upload if attachment exists
            $attachmentPath = $voucher->attachment_path;
            if ($this->attachment) {
                try {
                    // Delete old attachment if exists
                    if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                        Storage::disk('public')->delete($attachmentPath);
                    }
                    
                    $attachmentPath = $this->attachment->store('voucher-attachments', 'public');
                    if (!$attachmentPath) {
                        \Log::error('Failed to store updated attachment file');
                    }
                } catch (\Exception $fileEx) {
                    \Log::error('Error uploading updated file: ' . $fileEx->getMessage());
                    toast()
                        ->warning('File upload failed: ' . $fileEx->getMessage())
                        ->push();
                }
            }
            
            $voucher->update([
                'votehead_id' => $this->votehead_id,
                'amount' => $this->amount,
                'cheque_number' => $this->cheque_number,
                'recipient_name' => $this->recipient_name,
                'recipient_id_number' => $this->recipient_id_number,
                'recipient_phone' => $this->recipient_phone,
                'recipient_address' => $this->recipient_address,
                'description' => $this->description,
                'purpose' => $this->purpose,
                'academic_year' => $this->academic_year,
                'term' => $this->term,
                'payment_date' => $this->payment_date,
                'payment_method' => $this->payment_method,
                'attachment_path' => $attachmentPath,
                'updated_by' => auth()->user()->name,
            ]);
            
            $this->closeModal();
            
            toast()
                ->success('Payment voucher updated successfully')
                ->push();
        } catch (\Exception $e) {
            \Log::error('Error updating voucher: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            toast()
                ->danger('Error updating voucher: ' . $e->getMessage())
                ->push();
        }
    }
    
    /**
     * Show a single voucher details
     */
    public function showVoucher($id)
    {
        try {
            $this->voucherId = $id;
            $this->isViewing = true;
            $this->showViewModal = true;
        } catch (\Exception $e) {
            toast()
                ->danger('Error loading voucher: ' . $e->getMessage())
                ->push();
        }
    }
    
    /**
     * Close the voucher view modal
     */
    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->isViewing = false;
        $this->voucherId = null;
    }
    
    /**
     * Print the voucher
     */
    public function printVoucher()
    {
        // Logic to be handled by JavaScript in the view
        $this->dispatch('printVoucher');
    }
    
    /**
     * Confirm delete voucher
     */
    public function confirmDeleteVoucher($id)
    {
        $this->voucherId = $id;
        $this->showDeleteModal = true;
    }
    
    /**
     * Close delete confirmation modal
     */
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->voucherId = null;
    }
    
    /**
     * Delete a voucher
     */
    public function deleteVoucher()
    {
        try {
            $voucher = PaymentVoucher::findOrFail($this->voucherId);
            
            // Check permissions
            if (!$this->isAdmin && !$this->isAccountant && $voucher->created_by !== auth()->user()->name) {
                toast()
                    ->warning('You do not have permission to delete this voucher')
                    ->push();
                return;
            }
            
            if ($voucher->status !== 'pending') {
                toast()
                    ->warning('Only pending vouchers can be deleted')
                    ->push();
                return;
            }
            
            // Delete any attachments
            if ($voucher->attachment_path && Storage::disk('public')->exists($voucher->attachment_path)) {
                Storage::disk('public')->delete($voucher->attachment_path);
            }
            
            $voucher->delete();
            $this->closeDeleteModal();
            
            toast()
                ->success('Voucher deleted successfully')
                ->push();
        } catch (\Exception $e) {
            toast()
                ->danger('Error deleting voucher: ' . $e->getMessage())
                ->push();
        }
    }
    
    /**
     * Confirm approve voucher
     */
    public function confirmApproveVoucher($id)
    {
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()
                ->warning('You do not have permission to approve vouchers')
                ->push();
            return;
        }
        
        $this->voucherId = $id;
        $this->showApproveModal = true;
    }
    
    /**
     * Close approve confirmation modal
     */
    public function closeApproveModal()
    {
        $this->showApproveModal = false;
        $this->voucherId = null;
    }
    
    /**
     * Approve a voucher
     */
    public function approveVoucher()
    {
        try {
            $voucher = PaymentVoucher::findOrFail($this->voucherId);
            
            if ($voucher->status !== 'pending') {
                toast()
                    ->warning('Only pending vouchers can be approved')
                    ->push();
                return;
            }
            
            if ($voucher->is_cancelled) {
                toast()
                    ->warning('Cancelled vouchers cannot be approved')
                    ->push();
                return;
            }
            
            // Check if votehead has enough balance
            $votehead = $voucher->votehead;
            if ($votehead->balance < $voucher->amount) {
                toast()
                    ->warning("Insufficient funds in this votehead. Available balance: KES " . number_format($votehead->balance, 2))
                    ->push();
                return;
            }
            
            $voucher->approve(auth()->user()->name);
            $this->closeApproveModal();
            
            toast()
                ->success('Voucher approved successfully')
                ->push();
        } catch (\Exception $e) {
            toast()
                ->danger('Error approving voucher: ' . $e->getMessage())
                ->push();
        }
    }
    
    /**
     * Confirm mark voucher as paid
     */
    public function confirmPayVoucher($id)
    {
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()
                ->warning('You do not have permission to mark vouchers as paid')
                ->push();
            return;
        }
        
        $this->voucherId = $id;
        $this->showPayModal = true;
    }
    
    /**
     * Close pay confirmation modal
     */
    public function closePayModal()
    {
        $this->showPayModal = false;
        $this->voucherId = null;
    }
    
    /**
     * Mark a voucher as paid
     */
    public function markVoucherAsPaid()
    {
        try {
            $voucher = PaymentVoucher::findOrFail($this->voucherId);
            
            if ($voucher->status !== 'approved') {
                toast()
                    ->warning('Only approved vouchers can be marked as paid')
                    ->push();
                return;
            }
            
            if ($voucher->is_cancelled) {
                toast()
                    ->warning('Cancelled vouchers cannot be marked as paid')
                    ->push();
                return;
            }
            
            $voucher->markAsPaid(auth()->user()->name);
            $this->closePayModal();
            
            toast()
                ->success('Voucher marked as paid successfully')
                ->push();
        } catch (\Exception $e) {
            toast()
                ->danger('Error marking voucher as paid: ' . $e->getMessage())
                ->push();
        }
    }
    
    /**
     * Confirm cancel voucher
     */
    public function confirmCancelVoucher($id)
    {
        if (!$this->isAdmin && !$this->isAccountant) {
            toast()
                ->warning('You do not have permission to cancel vouchers')
                ->push();
            return;
        }
        
        $this->voucherId = $id;
        $this->showCancelModal = true;
    }
    
    /**
     * Close cancel confirmation modal
     */
    public function closeCancelModal()
    {
        $this->showCancelModal = false;
        $this->voucherId = null;
        $this->cancellationReason = '';
    }
    
    /**
     * Cancel a voucher
     */
    public function cancelVoucher()
    {
        $this->validate([
            'cancellationReason' => 'required|string|min:10|max:200',
        ], [
            'cancellationReason.required' => 'A reason for cancellation is required',
            'cancellationReason.min' => 'The reason must be at least 10 characters',
        ]);
        
        try {
            $voucher = PaymentVoucher::findOrFail($this->voucherId);
            
            if ($voucher->status === 'paid') {
                toast()
                    ->warning('Paid vouchers cannot be cancelled')
                    ->push();
                return;
            }
            
            if ($voucher->is_cancelled) {
                toast()
                    ->info('This voucher is already cancelled')
                    ->push();
                return;
            }
            
            $voucher->cancel(auth()->user()->name, $this->cancellationReason);
            $this->closeCancelModal();
            
            toast()
                ->success('Voucher cancelled successfully')
                ->push();
        } catch (\Exception $e) {
            toast()
                ->danger('Error cancelling voucher: ' . $e->getMessage())
                ->push();
        }
    }
    
    /**
     * Export vouchers (this would be implemented for CSV/Excel download)
     */
    public function exportVouchers()
    {
        toast()
            ->info('Export feature will be implemented soon')
            ->push();
    }
    
    /**
     * Apply filters
     */
    public function applyFilters()
    {
        $this->resetPage();
    }
    
    /**
     * Reset filters
     */
    public function resetFilters()
    {
        $this->reset(['voteheadFilter', 'yearFilter', 'termFilter', 'statusFilter', 'search', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }
    
    /**
     * Reset date filters
     */
    public function resetDateFilters()
    {
        $this->reset(['dateFrom', 'dateTo']);
        $this->resetPage();
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        // Get all active voteheads for filter dropdown
        $voteheads = AccountVotehead::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        // Get unique academic years from the vouchers
        $years = PaymentVoucher::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year')
            ->toArray();
            
        if (empty($years)) {
            $years = [date('Y')];
        }
        
        // Build the query
        $query = PaymentVoucher::with(['votehead.account'])
            ->when($this->voteheadFilter, function ($q) {
                $q->where('votehead_id', $this->voteheadFilter);
            })
            ->when($this->yearFilter, function ($q) {
                $q->where('academic_year', $this->yearFilter);
            })
            ->when($this->termFilter, function ($q) {
                $q->where('term', $this->termFilter);
            })
            ->when($this->statusFilter, function ($q) {
                if ($this->statusFilter === 'cancelled') {
                    $q->where('is_cancelled', true);
                } else {
                    $q->where('is_cancelled', false)->where('status', $this->statusFilter);
                }
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('payment_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('payment_date', '<=', $this->dateTo);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($qry) {
                    $qry->where('voucher_number', 'like', "%{$this->search}%")
                        ->orWhere('recipient_name', 'like', "%{$this->search}%")
                        ->orWhere('cheque_number', 'like', "%{$this->search}%")
                        ->orWhere('purpose', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhereHas('votehead', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%")
                                ->orWhere('code', 'like', "%{$this->search}%");
                        });
                });
            });
            
        // Apply sorting
        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Calculate summary statistics
        $totalVouchers = (clone $query)->count();
        $pendingVouchers = (clone $query)->where('status', 'pending')->where('is_cancelled', false)->count();
        $approvedVouchers = (clone $query)->where('status', 'approved')->where('is_cancelled', false)->count();
        $paidVouchers = (clone $query)->where('status', 'paid')->where('is_cancelled', false)->count();
        $cancelledVouchers = (clone $query)->where('is_cancelled', true)->count();
        $totalAmount = (clone $query)->where('is_cancelled', false)->sum('amount');
        $paidAmount = (clone $query)->where('status', 'paid')->where('is_cancelled', false)->sum('amount');
        
        // Get selected voucher details
        $selectedVoucher = null;
        if ($this->isViewing && $this->voucherId) {
            $selectedVoucher = PaymentVoucher::with('votehead.account')->find($this->voucherId);
        }
            
        // Payment methods for dropdown
        $paymentMethods = [
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'bank_transfer' => 'Bank Transfer',
            'mpesa' => 'M-Pesa'
        ];
        
        // Statuses for dropdown
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled'
        ];
        
        return view('livewire.finance.payment-vouchers', [
            'vouchers' => $query->paginate(10),
            'voteheads' => $voteheads,
            'years' => $years,
            'paymentMethods' => $paymentMethods,
            'statuses' => $statuses,
            'selectedVoucher' => $selectedVoucher,
            'totalVouchers' => $totalVouchers,
            'pendingVouchers' => $pendingVouchers,
            'approvedVouchers' => $approvedVouchers,
            'paidVouchers' => $paidVouchers,
            'cancelledVouchers' => $cancelledVouchers,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount
        ]);
    }
}
