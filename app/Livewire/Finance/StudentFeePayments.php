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
    public $isParent = false;
    public $classFilter = '';
    public $recentPayments = [];
    protected $paginatedPayments;


    public $hasManagePermission = false;
    public $hasPaymentPermission = false;
    public $showPaymentForm = false;

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

    // Additional filter properties
    public $paymentStatusFilter = '';
    public $startDate = null;
    public $endDate = null;

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

        // Initialize recentPayments as an empty array to prevent serialization errors
        $this->recentPayments = [];

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

        // Handle different user roles
        if ($this->isStudent) {
            // For students, automatically select their own record
            $studentRecord = StudentRecord::where('user_id', Auth::id())->first();
            if ($studentRecord) {
                $this->selectedStudentId = $studentRecord->id;
                $this->loadStudentDetails();
            } else {
                // Student record not found - should not happen in normal circumstances
                toast()->warning('Your student record was not found. Please contact administration.')->push();
            }
        }
        elseif ($this->isParent) {
            // For parents, always load their children regardless of view type
            $this->loadParentChildren();

            // If view_type is specified, it might contain additional parameters
            $viewType = request()->input('view_type');
            if ($viewType === 'my_children') {
                // Any special handling for the my_children view can go here
            }
        }
        else {
            // For administrators and accountants, preload some students
            $this->loadStudents(true); // true flag indicates initial load with limited results
        }

        // Pre-fill form if student_id parameter was passed (URL parameter takes precedence)
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
        $this->isParent = Qs::isParent();

        // Determine if user has permission to manage payments
        $this->hasManagePermission = $this->isAdmin || $this->isAccountant;

        // Determine if user has permission to make payments
        // Admin, accountant, parent and student can make payments
        $this->hasPaymentPermission = $this->isAdmin || $this->isAccountant || $this->isStudent || $this->isParent;
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

        // Load recent payments with pagination
        $this->paginatedPayments = $query
            ->latest('payment_date')
            ->paginate(10);

        // Convert paginated data to array for Livewire
        $this->recentPayments = $this->paginatedPayments->items();
    }

    /**
     * Build the query for payments with filters
     */
    private function getPaymentsQuery()
    {
        $query = StudentFeePayment::query()
            ->with(['student.user', 'student.my_class'])
            ->when($this->selectedStudentId, function ($query) {
                $query->where('student_id', $this->selectedStudentId);
            })
            ->when($this->yearFilter, function ($query) {
                $query->where('academic_year', $this->yearFilter);
            })
            ->when($this->termFilter, function ($query) {
                $query->where('term', $this->termFilter);
            })
            ->when($this->paymentStatusFilter, function ($query) {
                $this->applyStatusFilter($query, $this->paymentStatusFilter);
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('payment_date', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('payment_date', '<=', $this->endDate);
            });

        return $query;
    }

    /**
     * Apply status filter to query
     */
    private function applyStatusFilter($query, $statusFilter)
    {
        if ($statusFilter === 'confirmed') {
            $query->where('is_confirmed', true);
        } elseif ($statusFilter === 'pending') {
            $query->where('is_confirmed', false)
                  ->where('is_cancelled', false);
        } elseif ($statusFilter === 'cancelled') {
            $query->where('is_cancelled', true);
        }
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
            'search', 'filter_class', 'classFilter', 'yearFilter', 'termFilter',
            'filter_status', 'paymentStatusFilter', 'payment_date', 'startDate', 'endDate'
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
        $this->loadSummaryData();
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
     * Load students based on search and class filter
     */
    private function loadStudents($initialLoad = false)
    {
        $query = StudentRecord::query()
            ->with(['user', 'my_class', 'section']) // Eager load relationships
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('adm_no', 'like', '%' . $search . '%')
                        ->orWhere('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($this->classFilter, function ($query, $classId) {
                $query->where('my_class_id', $classId);
            });

        // For initial load, limit results
        $students = $initialLoad ? $query->take(10)->get() : $query->get();

        // Transform student records to simple array format with required fields
        $this->students = $students->map(function ($student) {
            // Get student name directly from StudentRecord first, then fallback to User if needed
            $name = 'Unknown';
            if ($student->first_name || $student->last_name) {
                $name = trim($student->first_name . ' ' . $student->last_name);
            } elseif ($student->user && $student->user->name) {
                $name = $student->user->name;
            } elseif ($student->admission_number) {
                $name = 'Student #' . $student->admission_number;
            }

            // Get class name
            $className = 'Unknown Class';
            if ($student->my_class && $student->my_class->name) {
                $className = $student->my_class->name;
            }

            return [
                'id' => $student->id,
                'name' => $name,
                'class' => $className,
                'admission_number' => $student->admission_number ?? 'N/A'
            ];
        })->toArray();

        // If only one student is found and we're searching, auto-select that student
        if ($this->search && count($this->students) === 1) {
            $this->selectedStudentId = $this->students[0]['id'];
            $this->loadStudentDetails();
        }
    }

    /**
     * Load student details including fee information
     */
    public function loadStudentDetails()
    {
        try {
            $student = StudentRecord::with(['user', 'my_class', 'section'])
                ->findOrFail($this->selectedStudentId);

            $this->selectedStudent = $student;

            // Get student name directly from StudentRecord first, then fallback to User if needed
            if ($student->first_name || $student->last_name) {
                $this->student_name = trim($student->first_name . ' ' . $student->last_name);
            } elseif ($student->user && $student->user->name) {
                $this->student_name = $student->user->name;
            } elseif ($student->admission_number) {
                $this->student_name = 'Student #' . $student->admission_number;
            } else {
                $this->student_name = 'Unknown';
            }

            $this->student_class = $student->my_class->name ?? 'Unknown Class';

            $this->calculateFeeInformation();
        } catch (\Exception $e) {
            $this->selectedStudentId = null;
            $this->selectedStudent = null;
            toast()->warning('Failed to load student details. Please try again.')->push();
        }
    }

    /**
     * Calculate fee information for the selected student
     */
    private function calculateFeeInformation()
    {
        if (!$this->selectedStudent) {
            return;
        }

        try {
            // Get fee structure for the student's class
            $feeStructure = FeeStructure::where('class_id', $this->selectedStudent->my_class_id)
                ->where('academic_year', $this->academic_year)
                ->where('term', $this->term)
                ->first();

            // Calculate total fees
            $this->total_fees = $feeStructure ? $feeStructure->total_amount : 0;

            // Calculate paid amount
            $this->paid_amount = StudentFeePayment::where('student_id', $this->selectedStudent->id)
                ->where('academic_year', $this->academic_year)
                ->where('term', $this->term)
                ->where('is_confirmed', true)
                ->sum('amount');

            // Calculate balance
            $this->fee_balance = $this->total_fees - $this->paid_amount;

            // Calculate payment percentage
            $this->paymentPercentage = $this->total_fees > 0
                ? round(($this->paid_amount / $this->total_fees) * 100, 2)
                : 0;
        } catch (\Exception $e) {
            toast()->warning('Failed to calculate fee information. Please try again.')->push();
        }
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

        // Close any other open modals first
        $this->closeAllModals();

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

        // Close any other open modals first
        $this->closeAllModals();

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
            $this->closeConfirmModal();
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
        $this->closeConfirmModal();
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

        // Close any other open modals first
        $this->closeAllModals();

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
            $this->closeCancelModal();
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
        $this->closeCancelModal();
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

        // Close any other open modals first
        $this->closeAllModals();

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
        $this->resetValidation(['editReason', 'editData.*']);
    }

    /**
     * Close all modals to prevent conflicts
     */
    private function closeAllModals()
    {
        $this->open_confirm_modal = false;
        $this->open_cancel_modal = false;
        $this->showingEditModal = false;
        $this->showingReceipt = false;

        $this->confirmingPaymentId = null;
        $this->cancellingPaymentId = null;
        $this->editingPaymentId = null;
        $this->receipt = null;

        $this->confirmationNote = '';
        $this->cancellation_reason = '';
        $this->editReason = '';
        $this->editData = [];

        $this->resetValidation();
    }

    /**
     * Return the state of the cancel modal for Alpine.js
     */
    public function showingCancelModal()
    {
        return $this->open_cancel_modal;
    }

    /**
     * Show cancel payment modal
     */
    public function showCancelModal($paymentId)
    {
        $this->cancelPayment($paymentId);
    }

    /**
     * Close cancel payment modal
     */
    public function closeCancelModal()
    {
        $this->open_cancel_modal = false;
        $this->cancellingPaymentId = null;
        $this->cancellation_reason = '';
        $this->resetValidation(['cancellation_reason']);
    }

    /**
     * Return the state of the confirm modal for Alpine.js
     */
    public function showingConfirmModal()
    {
        return $this->open_confirm_modal;
    }

    /**
     * Return the state of the edit modal for Alpine.js
     */
    public function showingEditModal()
    {
        return $this->showingEditModal;
    }

    /**
     * Close confirm payment modal
     */
    public function closeConfirmModal()
    {
        $this->open_confirm_modal = false;
        $this->confirmingPaymentId = null;
        $this->confirmationNote = '';
        $this->resetValidation(['confirmationNote']);
    }

    /**
     * Alias for processConfirmPayment to match the view's method name
     */
    public function confirmPaymentAction()
    {
        $this->processConfirmPayment();
    }

    /**
     * Handle the cancel payment action from the modal
     * This is needed because wire:click="cancelPayment" in the view
     * conflicts with the cancelPayment($paymentId) method
     */
    public function processCancelPaymentAction()
    {
        $this->processCancelPayment();
    }

    /**
     * Open the payment form
     */
    public function openPaymentForm()
    {
        if(!$this->selectedStudentId) {
            toast()->warning('Please select a student first')->push();
            return;
        }

        if(!$this->hasPaymentPermission) {
            toast()->danger('You do not have permission to make payments')->push();
            return;
        }

        $this->showPaymentForm = true;
    }

    /**
     * Close the payment form
     */
    public function closePaymentForm()
    {
        $this->showPaymentForm = false;
    }

    /**
     * Save a new payment record
     */
    public function savePayment()
    {
        if(!$this->hasPaymentPermission) {
            toast()->danger('You do not have permission to make payments')->push();
            return;
        }

        // Get dynamic validation rules based on the selected payment method
        $rules = $this->getRules();

        // Validate the form data
        $this->validate($rules);

        try {
            // Generate a unique receipt number
            $receiptNumber = 'FEE-' . strtoupper(substr(md5(time() . rand(1000, 9999)), 0, 8));

            // Create new payment record
            $payment = new StudentFeePayment();
            $payment->student_id = $this->selectedStudentId;
            $payment->amount = $this->amount;
            $payment->payment_date = $this->payment_date;
            $payment->year = $this->academic_year;
            $payment->term = $this->term;
            $payment->payment_method = $this->payment_method;
            $payment->receipt_number = $receiptNumber;
            $payment->notes = $this->notes;
            $payment->created_by = Auth::id();

            // Set method-specific details
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

            // Determine if the payment should be auto-confirmed
            // Administrators and accountants can make auto-confirmed payments
            if ($this->isAdmin || $this->isAccountant) {
                $payment->is_confirmed = true;
                $payment->confirmed_by = Auth::id();
                $payment->confirmed_at = now();
                $payment->status = 'confirmed';
            } else {
                $payment->is_confirmed = false;
                $payment->status = 'pending';
            }

            // Save the payment record
            $payment->save();

            // Handle file uploads
            if ($this->receipt_attachment) {
                $filename = $receiptNumber . '.' . $this->receipt_attachment->getClientOriginalExtension();
                $path = $this->receipt_attachment->storeAs('receipts', $filename, 'public');
                $payment->receipt_path = $path;
                $payment->save();
            }

            // Reset form fields
            $this->resetFormFields();

            // Refresh student data to show updated balance
            $this->loadStudentDetails();

            // Show success message
            toast()->success('Payment recorded successfully')->push();

            // Close the payment form
            $this->closePaymentForm();

        } catch (\Exception $e) {
            toast()->danger('Error recording payment: ' . $e->getMessage())->push();
        }
    }

    /**
     * Reset form fields after payment submission
     */
    private function resetFormFields()
    {
        $this->amount = null;
        $this->payment_method = 'cash';
        $this->cheque_number = null;
        $this->bank_name = null;
        $this->cheque_date = null;
        $this->bank_slip_number = null;
        $this->bank_branch = null;
        $this->mpesa_transaction_id = null;
        $this->phone_number = null;
        $this->mpesa_transaction_time = null;
        $this->notes = null;
        $this->receipt_attachment = null;

        // Reset validation errors
        $this->resetValidation();
    }

    /**
     * Load parent's children from the database
     */
    private function loadParentChildren()
    {
        $parentId = Auth::id();

        // Find all children associated with this parent
        $children = StudentRecord::whereHas('user')
            ->where('parent_id_no', $parentId)
            ->with(['user', 'myClass'])
            ->get();

        if ($children->isEmpty()) {
            $this->students = [];

            // Add a detailed toast notification with instructions
            toast()
                ->info('No children are associated with your account. Please contact the school administration to link your children to your account.')
                ->push();
            return;
        }

        // Transform student records to simple array format
        $this->students = $children->map(function ($student) {
            // Get student name directly from StudentRecord first, then fallback to User if needed
            $name = 'Unknown';
            if ($student->first_name || $student->last_name) {
                $name = trim($student->first_name . ' ' . $student->last_name);
            } elseif ($student->user && $student->user->name) {
                $name = $student->user->name;
            } elseif ($student->admission_number) {
                $name = 'Student #' . $student->admission_number;
            }

            return [
                'id' => $student->id,
                'name' => $name,
                'class' => $student->myClass->name ?? 'Unknown Class',
                'admission_number' => $student->admission_number ?? 'N/A'
            ];
        })->toArray();

        // If there's only one child, auto-select them
        if (count($this->students) === 1) {
            $this->selectedStudentId = $this->students[0]['id'];
            $this->loadStudentDetails();
        }
    }

    public function render()
    {
        return view('livewire.finance.student-fee-payments', [
            'payments' => $this->paginatedPayments ?? collect([])
        ]);
    }

    // Update the search property to trigger real-time search
    public function updatedSearch()
    {
        $this->loadStudents(false);
    }

    // Update the classFilter property to trigger real-time filtering
    public function updatedClassFilter()
    {
        $this->loadStudents(false);
    }

    // Add real-time validation for date range
    public function updatedStartDate($value)
    {
        if ($this->endDate && $value > $this->endDate) {
            $this->addError('startDate', 'Start date cannot be later than end date');
            return;
        }
        $this->loadSummaryData();
    }

    public function updatedEndDate($value)
    {
        if ($this->startDate && $value < $this->startDate) {
            $this->addError('endDate', 'End date cannot be earlier than start date');
            return;
        }
        $this->loadSummaryData();
    }

    // Add methods to handle real-time filter updates
    public function updatedYearFilter()
    {
        $this->loadSummaryData();
    }

    public function updatedTermFilter()
    {
        $this->loadSummaryData();
    }

    public function updatedPaymentStatusFilter()
    {
        $this->loadSummaryData();
    }
}