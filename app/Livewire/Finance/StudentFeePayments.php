<?php

namespace App\Livewire\Finance;

use App\Helpers\Qs;
use App\Models\FeeStructure;
use App\Models\StudentFeePayment;
use App\Models\StudentRecord;
use App\Models\StudentArrear;
use App\Models\MyClass;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Usernotnull\Toast\Concerns\WireToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentFeePayments extends Component
{
    use WithPagination, WithFileUploads, WireToast;
    
    // Role-based access control properties
    public $isAdmin = false;
    public $isAccountant = false;
    public $isTeacher = false;
    public $isStudent = false;
    public $hasManagePermission = false;
    
    // Component properties
    public $search = '';
    public $filter_class = '';
    public $filter_status = '';
    public $yearFilter = '';
    public $termFilter = '';
    public $showFilters = false;
    public $selectedStudentId = null;
    public $selectedStudent = null;
    public $student_name = null;
    public $student_class = null;
    public $totalPaymentsCount = 0;
    public $totalPaymentsAmount = 0;
    public $classes = [];
    public $students = [];
    
    // Receipt & Payment viewing properties
    public $showingReceipt = false;
    public $receipt = null;
    public $selectedPayment = null;
    
    // Confirmation properties
    public $open_confirm_modal = false;
    public $confirmingPaymentId = null;
    public $payment_amount = 0;
    public $confirmationNote = '';
    
    // Cancel properties
    public $open_cancel_modal = false;
    public $cancellingPaymentId = null;
    public $cancellation_reason = '';

    // Edit properties
    public $showingEditModal = false;
    public $editingPaymentId = null;
    public $editData = [];
    public $editReason = '';

    // Form properties
    public $amount;
    public $payment_date;
    public $academic_year;
    public $term;
    public $payment_method = 'cash';
    public $cheque_number;
    public $bank_name;
    public $cheque_date;
    public $bank_slip_number;
    public $bank_branch;
    public $mpesa_transaction_id;
    public $phone_number;
    public $mpesa_transaction_time;
    public $receipt_attachment;
    public $notes;
    public $is_confirmed = false;
    
    // Fee information properties
    public $total_fees = 0;
    public $paid_amount = 0;
    public $fee_balance = 0;
    public $paymentPercentage = 0;
    
    // Validation rules
    protected $rules = [
        'amount' => 'required|numeric|min:1',
        'payment_date' => 'required|date',
        'academic_year' => 'required|integer|min:2000|max:2100',
        'term' => 'required|integer|min:1|max:3',
        'payment_method' => 'required|in:cash,cheque,bank_transfer,mpesa',
        'cheque_number' => 'nullable|required_if:payment_method,cheque|string|max:50',
        'bank_name' => 'nullable|required_if:payment_method,cheque,bank_transfer|string|max:100',
        'cheque_date' => 'nullable|required_if:payment_method,cheque|date',
        'bank_slip_number' => 'nullable|required_if:payment_method,bank_transfer|string|max:50',
        'bank_branch' => 'nullable|required_if:payment_method,bank_transfer|string|max:100',
        'mpesa_transaction_id' => 'nullable|required_if:payment_method,mpesa|string|max:50',
        'phone_number' => 'nullable|required_if:payment_method,mpesa|string|max:15',
        'mpesa_transaction_time' => 'nullable|required_if:payment_method,mpesa|date',
        'notes' => 'nullable|string|max:500',
        'is_confirmed' => 'boolean',
        'receipt_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'cancellation_reason' => 'required_when:open_cancel_modal,true|min:3|max:255',
        'editReason' => 'required_when:showingEditModal,true|min:3|max:255',
        'confirmationNote' => 'nullable|string|max:255',
    ];
    
    public function getRules()
    {
        $rules = $this->rules;
        
        // Dynamically update rules based on payment method
        if ($this->payment_method === 'cash') {
            unset($rules['cheque_number'], $rules['bank_name'], $rules['cheque_date'], 
                  $rules['bank_slip_number'], $rules['bank_branch'],
                  $rules['mpesa_transaction_id'], $rules['phone_number'], $rules['mpesa_transaction_time']);
        } elseif ($this->payment_method === 'cheque') {
            unset($rules['bank_slip_number'], $rules['bank_branch'],
                  $rules['mpesa_transaction_id'], $rules['phone_number'], $rules['mpesa_transaction_time']);
        } elseif ($this->payment_method === 'bank_transfer') {
            unset($rules['cheque_number'], $rules['cheque_date'],
                  $rules['mpesa_transaction_id'], $rules['phone_number'], $rules['mpesa_transaction_time']);
        } elseif ($this->payment_method === 'mpesa') {
            unset($rules['cheque_number'], $rules['bank_name'], $rules['cheque_date'], 
                  $rules['bank_slip_number'], $rules['bank_branch']);
        }
        
        return $rules;
    }
    
    // Listeners
    protected $listeners = ['refreshComponent' => '$refresh'];
    
    public function mount()
    {
        $this->setUserRoles();
        $this->loadClasses();
        $this->loadSummaryData();
        
        // Set default academic year to current year
        $this->academic_year = date('Y');
        
        // Determine current term based on month (customize as needed)
        $month = date('n');
        if ($month >= 1 && $month <= 4) {
            $this->term = 1;
        } elseif ($month >= 5 && $month <= 8) {
            $this->term = 2;
        } else {
            $this->term = 3;
        }
        
        $this->payment_date = date('Y-m-d');
        
        // Pre-fill form if student_id parameter was passed
        if (request()->has('student_id')) {
            $this->selectedStudentId = request()->input('student_id');
            $this->loadStudentDetails();
        }
    }
    
    /**
     * Set user roles based on authenticated user
     */
    private function setUserRoles()
    {
        $this->isAdmin = Qs::isAdministrator();
        $this->isAccountant = Qs::isAccountant();
        $this->isTeacher = Qs::isTeacher();
        $this->isStudent = Qs::isStudent();
        
        // Determine if user has permission to manage payments
        $this->hasManagePermission = $this->isAdmin || $this->isAccountant;
    }
    
    /**
     * Load available classes
     */
    private function loadClasses()
    {
        $this->classes = MyClass::orderBy('name')->get();
    }
    
    /**
     * Load summary data for dashboard
     */
    private function loadSummaryData()
    {
        $query = $this->getPaymentsQuery();
        
        $this->totalPaymentsCount = $query->count();
        $this->totalPaymentsAmount = $query->sum('amount');
    }
    
    /**
     * Build the query for payments with filters
     */
    private function getPaymentsQuery()
    {
        $query = StudentFeePayment::query();
        
        if ($this->search) {
            $query->where('receipt_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('student.user', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }
        
        if ($this->filter_class) {
            $query->whereHas('student', function($q) {
                $q->where('my_class_id', $this->filter_class);
            });
        }
        
        if ($this->yearFilter) {
            $query->where('year', $this->yearFilter);
        }
        
        if ($this->termFilter) {
            $query->where('term', $this->termFilter);
        }
        
        if ($this->filter_status) {
            if ($this->filter_status === 'confirmed') {
                $query->where('is_confirmed', true);
            } elseif ($this->filter_status === 'pending') {
                $query->where('is_confirmed', false)
                      ->where('is_cancelled', false);
            } elseif ($this->filter_status === 'cancelled') {
                $query->where('is_cancelled', true);
            }
        }
        
        if ($this->payment_date) {
            $query->whereDate('payment_date', '>=', $this->payment_date);
        }
        
        if ($this->payment_date) {
            $query->whereDate('payment_date', '<=', $this->payment_date);
        }
        
        return $query;
    }
    
    /**
     * Toggle filters visibility
     */
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }
    
    /**
     * Apply filters and refresh data
     */
    public function applyFilters()
    {
        $this->resetPage();
        $this->loadSummaryData();
    }
    
    /**
     * Reset all filters
     */
    public function resetFilters()
    {
        $this->reset([
            'search', 'filter_class', 'yearFilter', 'termFilter',
            'filter_status', 'payment_date'
        ]);
        
        $this->resetPage();
        $this->loadSummaryData();
    }
    
    /**
     * Select a student to view or make payment
     */
    public function selectStudent($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->loadStudentDetails();
    }
    
    /**
     * Reset student selection
     */
    public function backToSelection()
    {
        $this->selectedStudentId = null;
        $this->selectedStudent = null;
        $this->student_name = null;
        $this->student_class = null;
        $this->resetPage();
    }
    
    /**
     * Load student details including fee information
     */
    public function loadStudentDetails()
    {
        if (!$this->selectedStudentId) {
            return;
        }
        
        $this->selectedStudent = StudentRecord::with(['user', 'myClass', 'payments' => function($query) {
            $query->where('year', $this->academic_year)
                  ->where('term', $this->term)
                  ->where('is_cancelled', false);
        }])->find($this->selectedStudentId);
        
        if (!$this->selectedStudent) {
            toast()->danger('Student not found')->push();
            $this->selectedStudentId = null;
            return;
        }
        
        // Set student name and class for display
        $this->student_name = $this->selectedStudent->user->name;
        $this->student_class = $this->selectedStudent->myClass->name;
        
        // Calculate fee information
        $this->calculateFeeInformation();
    }
    
    /**
     * Calculate fee information for the selected student
     */
    private function calculateFeeInformation()
    {
        if (!$this->selectedStudent) {
            return;
        }
        
        // Get fee structure for the student's class
        $feeStructure = FeeStructure::where('class_id', $this->selectedStudent->my_class_id)
                                   ->where('year', $this->academic_year)
                                   ->where('term', $this->term)
                                   ->first();
        
        // Calculate total fees
        $this->total_fees = $feeStructure ? $feeStructure->amount : 0;
        
        // Calculate total paid
        $this->paid_amount = $this->selectedStudent->payments
                               ->where('year', $this->academic_year)
                               ->where('term', $this->term)
                               ->where('is_confirmed', true)
                               ->where('is_cancelled', false)
                               ->sum('amount');
        
        // Calculate balance
        $this->fee_balance = max(0, $this->total_fees - $this->paid_amount);
        
        // Calculate payment percentage
        $this->paymentPercentage = $this->total_fees > 0 
            ? min(100, round(($this->paid_amount / $this->total_fees) * 100)) 
            : 0;
    }
    
    /**
     * Show receipt for a payment
     */
    public function viewReceipt($paymentId)
    {
        $this->receipt = StudentFeePayment::with(['student.user', 'student.myClass', 'createdBy'])
                                         ->find($paymentId);
        
        if (!$this->receipt) {
            toast()->danger('Payment not found')->push();
            return;
        }
        
        $this->showingReceipt = true;
    }
    
    /**
     * Close receipt modal
     */
    public function closeReceiptModal()
    {
        $this->showingReceipt = false;
        $this->receipt = null;
    }
    
    /**
     * Open payment confirmation modal
     */
    public function confirmPayment($paymentId)
    {
        if (!$this->hasManagePermission) {
            toast()->danger('You do not have permission to confirm payments')->push();
            return;
        }
        
        $payment = StudentFeePayment::find($paymentId);
        
        if (!$payment) {
            toast()->danger('Payment not found')->push();
            return;
        }
        
        if ($payment->is_confirmed) {
            toast()->info('This payment is already confirmed')->push();
            return;
        }
        
        if ($payment->is_cancelled) {
            toast()->danger('Cannot confirm a cancelled payment')->push();
            return;
        }
        
        $this->confirmingPaymentId = $paymentId;
        $this->payment_amount = $payment->amount;
        $this->student_name = $payment->student->user->name;
        $this->confirmationNote = '';
        $this->open_confirm_modal = true;
    }
    
    /**
     * Process payment confirmation
     */
    public function processConfirmPayment()
    {
        if (!$this->hasManagePermission) {
            toast()->danger('You do not have permission to confirm payments')->push();
            return;
        }
        
        $payment = StudentFeePayment::find($this->confirmingPaymentId);
        
        if (!$payment) {
            toast()->danger('Payment not found')->push();
            $this->open_confirm_modal = false;
            return;
        }
        
        $payment->is_confirmed = true;
        $payment->confirmed_by = Auth::id();
        $payment->confirmed_at = now();
        $payment->status = 'confirmed';
        
        if ($this->confirmationNote) {
            $payment->notes = $payment->notes 
                ? $payment->notes . "\n\nConfirmation Note: " . $this->confirmationNote
                : "Confirmation Note: " . $this->confirmationNote;
        }
        
        $payment->save();
        
        // Refresh student data if we're viewing that student
        if ($this->selectedStudentId && $payment->student_id == $this->selectedStudentId) {
            $this->loadStudentDetails();
        } else {
            $this->loadSummaryData();
        }
        
        toast()->success('Payment confirmed successfully')->push();
        $this->open_confirm_modal = false;
    }
    
    /**
     * Open payment cancellation modal
     */
    public function cancelPayment($paymentId)
    {
        if (!$this->hasManagePermission) {
            toast()->danger('You do not have permission to cancel payments')->push();
            return;
        }
        
        $payment = StudentFeePayment::find($paymentId);
        
        if (!$payment) {
            toast()->danger('Payment not found')->push();
            return;
        }
        
        if ($payment->is_cancelled) {
            toast()->info('This payment is already cancelled')->push();
            return;
        }
        
        $this->cancellingPaymentId = $paymentId;
        $this->payment_amount = $payment->amount;
        $this->student_name = $payment->student->user->name;
        $this->cancellation_reason = '';
        $this->open_cancel_modal = true;
    }
    
    /**
     * Process payment cancellation
     */
    public function processCancelPayment()
    {
        if (!$this->hasManagePermission) {
            toast()->danger('You do not have permission to cancel payments')->push();
            return;
        }
        
        $this->validate([
            'cancellation_reason' => 'required|min:3|max:255'
        ]);
        
        $payment = StudentFeePayment::find($this->cancellingPaymentId);
        
        if (!$payment) {
            toast()->danger('Payment not found')->push();
            $this->open_cancel_modal = false;
            return;
        }
        
        $payment->is_cancelled = true;
        $payment->cancelled_by = Auth::id();
        $payment->cancelled_at = now();
        $payment->cancel_reason = $this->cancellation_reason;
        $payment->status = 'cancelled';
        $payment->save();
        
        // Refresh student data if we're viewing that student
        if ($this->selectedStudentId && $payment->student_id == $this->selectedStudentId) {
            $this->loadStudentDetails();
        } else {
            $this->loadSummaryData();
        }
        
        toast()->success('Payment cancelled successfully')->push();
        $this->open_cancel_modal = false;
    }
    
    /**
     * Open payment edit modal
     */
    public function editPayment($paymentId)
    {
        if (!$this->hasManagePermission) {
            toast()->danger('You do not have permission to edit payments')->push();
            return;
        }
        
        $payment = StudentFeePayment::find($paymentId);
        
        if (!$payment) {
            toast()->danger('Payment not found')->push();
            return;
        }
        
        if ($payment->is_cancelled) {
            toast()->danger('Cannot edit a cancelled payment')->push();
            return;
        }
        
        $this->editingPaymentId = $paymentId;
        $this->editData = [
            'amount' => $payment->amount,
            'payment_date' => $payment->payment_date->format('Y-m-d'),
            'notes' => $payment->notes
        ];
        $this->editReason = '';
        $this->showingEditModal = true;
    }
    
    /**
     * Close edit modal
     */
    public function closeEditModal()
    {
        $this->showingEditModal = false;
        $this->editingPaymentId = null;
        $this->editData = [];
        $this->editReason = '';
    }
    
    /**
     * Process payment update
     */
    public function updatePayment()
    {
        if (!$this->hasManagePermission) {
            toast()->danger('You do not have permission to edit payments')->push();
            return;
        }
        
        $this->validate([
            'editData.amount' => 'required|numeric|min:1',
            'editData.payment_date' => 'required|date',
            'editData.notes' => 'nullable|string|max:500',
            'editReason' => 'required|min:3|max:255'
        ]);
        
        $payment = StudentFeePayment::find($this->editingPaymentId);
        
        if (!$payment) {
            toast()->danger('Payment not found')->push();
            $this->closeEditModal();
            return;
        }
        
        // Add audit trail
        $auditNote = "EDITED: " . now()->format('Y-m-d H:i:s') . " by " . Auth::user()->name . 
                    " (ID: " . Auth::id() . ")\nReason: " . $this->editReason . 
                    "\nPrevious values: Amount: " . $payment->amount . ", Date: " . 
                    $payment->payment_date->format('Y-m-d');
        
        // Update payment
        $payment->amount = $this->editData['amount'];
        $payment->payment_date = $this->editData['payment_date'];
        $payment->notes = $this->editData['notes'] ? 
                         ($payment->notes ? $payment->notes . "\n\n" . $auditNote : $auditNote . "\n\n" . $this->editData['notes']) : 
                         ($payment->notes ? $payment->notes . "\n\n" . $auditNote : $auditNote);
        $payment->save();
        
        // Refresh student data if we're viewing that student
        if ($this->selectedStudentId && $payment->student_id == $this->selectedStudentId) {
            $this->loadStudentDetails();
        } else {
            $this->loadSummaryData();
        }
        
        toast()->success('Payment updated successfully')->push();
        $this->closeEditModal();
    }
    
    /**
     * Save new payment
     */
    public function savePayment()
    {
        if (!$this->hasManagePermission) {
            toast()->danger('You do not have permission to create payments')->push();
            return;
        }
        
        if (!$this->selectedStudentId) {
            toast()->danger('No student selected')->push();
            return;
        }
        
        $this->validate($this->getRules());
        
        // Create payment record
        $payment = new StudentFeePayment();
        $payment->student_id = $this->selectedStudentId;
        $payment->receipt_number = $this->generateReceiptNumber();
        $payment->amount = $this->amount;
        $payment->year = $this->academic_year;
        $payment->term = $this->term;
        $payment->payment_date = $this->payment_date;
        $payment->payment_method = $this->payment_method;
        $payment->is_confirmed = $this->is_confirmed;
        $payment->confirmed_by = $this->is_confirmed ? Auth::id() : null;
        $payment->confirmed_at = $this->is_confirmed ? now() : null;
        $payment->created_by = Auth::id();
        $payment->notes = $this->notes;
        $payment->status = $this->is_confirmed ? 'confirmed' : 'pending';
        
        // Set payment method specific fields
        if ($this->payment_method === 'cheque') {
            $payment->cheque_number = $this->cheque_number;
            $payment->bank_name = $this->bank_name;
            $payment->cheque_date = $this->cheque_date;
        } elseif ($this->payment_method === 'bank_transfer') {
            $payment->bank_name = $this->bank_name;
            $payment->bank_slip_number = $this->bank_slip_number;
            $payment->bank_branch = $this->bank_branch;
        } elseif ($this->payment_method === 'mpesa') {
            $payment->mpesa_transaction_id = $this->mpesa_transaction_id;
            $payment->phone_number = $this->phone_number;
            $payment->mpesa_transaction_time = $this->mpesa_transaction_time;
        }
        
        // Save receipt attachment if provided
        if ($this->receipt_attachment) {
            $filename = 'receipt_' . time() . '_' . Str::random(10) . '.' . $this->receipt_attachment->getClientOriginalExtension();
            $path = $this->receipt_attachment->storeAs('receipts', $filename, 'public');
            $payment->receipt_attachment = $path;
        }
        
        $payment->save();
        
        // Refresh student data
        $this->loadStudentDetails();
        
        // Reset form fields
        $this->resetFormFields();
        
        toast()->success('Payment recorded successfully')->push();
    }
    
    /**
     * Generate a unique receipt number
     */
    private function generateReceiptNumber()
    {
        $prefix = 'RCPT';
        $date = date('Ymd');
        $random = strtoupper(Str::random(4));
        
        return $prefix . $date . $random;
    }
    
    /**
     * Reset form fields after submission
     */
    private function resetFormFields()
    {
        $this->reset([
            'amount', 'payment_method', 'cheque_number',
            'bank_name', 'cheque_date', 'bank_slip_number', 'bank_branch',
            'mpesa_transaction_id', 'phone_number', 'mpesa_transaction_time', 
            'notes', 'receipt_attachment', 'is_confirmed'
        ]);
        
        // Set defaults
        $this->payment_date = date('Y-m-d');
        $this->payment_method = 'cash';
        $this->is_confirmed = false;
        
        // Reset validation
        $this->resetValidation();
    }
    
    /**
     * Export payments to CSV
     */
    public function exportPayments()
    {
        if (!$this->hasManagePermission) {
            toast()->danger('You do not have permission to export payments')->push();
            return;
        }
        
        // The export functionality would be implemented here
        // For now, we'll just show a toast
        toast()->info('Export functionality will be implemented soon')->push();
    }
    
    public function render()
    {
        $paymentsQuery = $this->getPaymentsQuery();
        
        // Get previous payments for the selected student if any
        $previous_payments = $this->selectedStudentId 
            ? StudentFeePayment::where('student_id', $this->selectedStudentId)
                             ->where('year', $this->academic_year)
                             ->where('term', $this->term)
                             ->orderBy('payment_date', 'desc')
                             ->get()
            : collect([]);
        
        // Get all payments for the table view
        $payments = $paymentsQuery->with(['student.user', 'student.myClass'])
                               ->latest('payment_date')
                               ->paginate(10);
        
        return view('livewire.finance.student-fee-payments', [
            'payments' => $payments,
            'previous_payments' => $previous_payments,
        ]);
    }
} 