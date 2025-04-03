<?php

namespace App\Livewire\Finance;

use App\Models\StudentFeePayment;
use App\Models\StudentRecord;
use App\Models\FeeStructure;
use App\Models\Setting;
use App\Models\User;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Helpers\Qs;

class FeePayment extends Component
{
    use LivewireAlert, WithFileUploads;

    // Student information
    public $student_id;
    public $student_name;
    public $student_class;
    public $admission_number;

    // Payment details
    public $amount;
    public $payment_method = 'mpesa';
    public $payment_date;
    public $notes;
    public $receipt_attachment;
    public $academic_year;
    public $term;

    // Fee information
    public $total_fees = 0;
    public $paid_amount = 0;
    public $fee_balance = 0;
    public $paymentPercentage = 0;

    // M-Pesa specific fields
    public $phone_number;
    public $mpesa_transaction_id;
    public $mpesa_transaction_time;
    public $mpesa_payment_status = 'pending';
    public $showMpesaPaymentForm = false;
    public $showDirectMpesaPayment = false;

    // Bank specific fields
    public $bank_name;
    public $bank_branch;
    public $bank_slip_number;

    // Cheque specific fields
    public $cheque_number;
    public $cheque_date;

    // Payment history
    public $recentPayments = [];
    public $showPaymentForm = true;
    public $hasPaymentPermission = false;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'payWithMpesa' => 'initiateDirectMpesaPayment'
    ];

    public function mount()
    {
        // Set default payment date to today
        $this->payment_date = date('Y-m-d');

        // Get current academic year and term from settings
        $this->academic_year = Setting::where('type', 'current_session')->first()->value ?? date('Y');
        $this->term = Setting::where('type', 'current_term')->first()->value ?? '1';

        // Check if user has permission to make payments
        $this->hasPaymentPermission = Qs::isStudent() || Qs::isParent() || Qs::isAdministrativeStaff();

        // If user is a student, auto-select themselves
        if (Auth::check() && Qs::isStudent()) {
            $studentRecord = StudentRecord::where('user_id', Auth::id())->first();
            if ($studentRecord) {
                $this->student_id = $studentRecord->id;
                $this->loadStudentDetails();
            }
        }

        $this->loadRecentPayments();
    }

    public function loadStudentDetails()
    {
        if (!$this->student_id) {
            return;
        }

        $student = StudentRecord::with(['user', 'myClass'])->find($this->student_id);

        if (!$student) {
            $this->alert('error', 'Student not found');
            return;
        }

        $this->student_name = $student->user->name;
        $this->student_class = $student->myClass->name ?? 'Unknown Class';
        $this->admission_number = $student->admission_number;

        // Load fee information
        $this->loadFeeInformation();
        $this->loadRecentPayments();
    }

    public function loadFeeInformation()
    {
        try {
            // Get fee structure for the student's class
            $student = StudentRecord::with('myClass')->find($this->student_id);
            if (!$student || !$student->myClass) {
                return;
            }

            // Get total fees from fee structure
            $feeStructure = FeeStructure::where('class_id', $student->my_class_id)
                ->where('academic_year', $this->academic_year)
                ->where('term', $this->term)
                ->first();

            $this->total_fees = $feeStructure ? $feeStructure->total_amount : 0;

            // Get paid amount from payments
            $this->paid_amount = StudentFeePayment::where('student_id', $this->student_id)
                ->where('academic_year', $this->academic_year)
                ->where('term', $this->term)
                ->where('status', 'completed')
                ->sum('amount');

            // Calculate balance
            $this->fee_balance = $this->total_fees - $this->paid_amount;

            // Calculate payment percentage
            $this->paymentPercentage = $this->total_fees > 0
                ? round(($this->paid_amount / $this->total_fees) * 100, 2)
                : 0;
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to load fee information: ' . $e->getMessage());
        }
    }

    public function loadRecentPayments()
    {
        if (!$this->student_id) {
            $this->recentPayments = [];
            return;
        }

        $this->recentPayments = StudentFeePayment::where('student_id', $this->student_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function savePayment()
    {
        // Validate the form data
        $this->validate([
            'student_id' => 'required|exists:student_records,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,cheque,bank_transfer,mpesa',
            'payment_date' => 'required|date',
            'academic_year' => 'required',
            'term' => 'required|in:1,2,3',
            // Conditional validation based on payment method
            'mpesa_transaction_id' => 'required_if:payment_method,mpesa',
            'phone_number' => 'required_if:payment_method,mpesa',
            'bank_name' => 'required_if:payment_method,bank_transfer,cheque',
            'bank_slip_number' => 'required_if:payment_method,bank_transfer',
            'cheque_number' => 'required_if:payment_method,cheque',
            'cheque_date' => 'required_if:payment_method,cheque',
            'receipt_attachment' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf',
        ]);

        try {
            // Create new payment record
            $payment = new StudentFeePayment();
            $payment->student_id = $this->student_id;
            $payment->amount = $this->amount;
            $payment->payment_method = $this->payment_method;
            $payment->payment_date = $this->payment_date;
            $payment->academic_year = $this->academic_year;
            $payment->term = $this->term;
            $payment->notes = $this->notes;
            $payment->created_by = Auth::id();

            // Set payment status based on method and user role
            if (($this->payment_method === 'mpesa' || $this->payment_method === 'bank_transfer' || $this->payment_method === 'cheque') && !Qs::isAdministrativeStaff()) {
                $payment->status = 'pending'; // Pending verification
            } else {
                $payment->status = 'completed'; // Admin/accountant recorded payments are auto-completed
            }

            // Set payment details based on method
            switch ($this->payment_method) {
                case 'mpesa':
                    $payment->transaction_id = $this->mpesa_transaction_id;
                    $payment->phone_number = $this->phone_number;
                    $payment->transaction_time = $this->mpesa_transaction_time;
                    break;

                case 'bank_transfer':
                    $payment->bank_name = $this->bank_name;
                    $payment->bank_branch = $this->bank_branch;
                    $payment->transaction_id = $this->bank_slip_number;
                    break;

                case 'cheque':
                    $payment->bank_name = $this->bank_name;
                    $payment->cheque_number = $this->cheque_number;
                    $payment->cheque_date = $this->cheque_date;
                    $payment->transaction_id = $this->cheque_number;
                    break;
            }

            // Handle receipt attachment
            if ($this->receipt_attachment) {
                $path = $this->receipt_attachment->store('receipts', 'public');
                $payment->receipt_path = $path;
            }

            $payment->save();

            // Reset form and refresh data
            $this->resetForm();
            $this->loadFeeInformation();
            $this->loadRecentPayments();

            $this->alert('success', 'Payment recorded successfully');
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    public function initiateDirectMpesaPayment()
    {
        $this->validate([
            'student_id' => 'required|exists:student_records,id',
            'amount' => 'required|numeric|min:1',
            'phone_number' => 'required|regex:/^2547[0-9]{8}$/',
        ], [
            'phone_number.regex' => 'Phone number must be in the format 2547XXXXXXXX'
        ]);

        try {
            $mpesaService = new MpesaService();
            $description = "School fees payment for {$this->student_name}, {$this->admission_number}";

            $response = $mpesaService->initiatePayment($this->amount, $this->phone_number, $description);

            if (isset($response['status']) && $response['status'] === 'error') {
                $this->alert('error', $response['message']);
                return;
            }

            // Create a pending payment record
            $payment = new StudentFeePayment();
            $payment->student_id = $this->student_id;
            $payment->amount = $this->amount;
            $payment->payment_method = 'mpesa';
            $payment->payment_date = date('Y-m-d');
            $payment->academic_year = $this->academic_year;
            $payment->term = $this->term;
            $payment->notes = "M-Pesa payment initiated through the system";
            $payment->created_by = Auth::id();
            $payment->status = 'pending';
            $payment->phone_number = $this->phone_number;
            $payment->transaction_id = $response['CheckoutRequestID'] ?? null;
            $payment->save();

            $this->alert('success', 'M-Pesa payment initiated. Please check your phone to complete the payment.');
            $this->showDirectMpesaPayment = false;
            $this->loadRecentPayments();
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to initiate M-Pesa payment: ' . $e->getMessage());
        }
    }

    public function toggleMpesaPaymentForm()
    {
        $this->showDirectMpesaPayment = !$this->showDirectMpesaPayment;
    }

    public function resetForm()
    {
        $this->amount = '';
        $this->notes = '';
        $this->receipt_attachment = null;
        $this->mpesa_transaction_id = '';
        $this->phone_number = '';
        $this->mpesa_transaction_time = '';
        $this->bank_name = '';
        $this->bank_branch = '';
        $this->bank_slip_number = '';
        $this->cheque_number = '';
        $this->cheque_date = '';
    }

    public function closePaymentForm()
    {
        $this->showPaymentForm = false;
    }

    public function openPaymentForm()
    {
        $this->showPaymentForm = true;
    }

    public function render()
    {
        return view('livewire.finance.fee-payment');
    }
}
