<?php

namespace App\Livewire\Finance;

use App\Models\FinanceAccount;
use App\Models\FeeStructure;
use App\Models\StudentFeePayment;
use App\Models\StudentArrear;
use App\Models\PaymentVoucher;
use App\Models\MyClass;
use App\Models\StudentRecord;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $currentYear;
    public $currentTerm = 1;
    public $dateRange = 'term'; // term, month, year, custom
    public $startDate;
    public $endDate;
    
    // User role properties
    public $isAdmin = false;
    public $isAccountant = false;
    public $isTeacher = false;
    public $isParent = false;
    public $isStudent = false;
    public $parentChildren = [];
    public $studentId = null;
    
    // For chart data
    public $paymentsByDay = [];
    public $expensesByDay = [];
    public $feeCollectionsByClass = [];
    public $arrearsByClass = [];
    
    public function mount()
    {
        // Set user role flags
        $this->isAdmin = Qs::isAdministrator() || Qs::isAdmin();
        $this->isAccountant = Qs::isAccountant();
        $this->isTeacher = Qs::isTeacher();
        $this->isParent = Qs::isParent();
        $this->isStudent = Qs::isStudent();
        
        // For parent users, get their children's IDs
        if ($this->isParent) {
            $this->parentChildren = Qs::findMyChildren(Auth::id())
                ->pluck('id')
                ->toArray();
        }
        
        // For student users, get their ID
        if ($this->isStudent) {
            $studentRecord = StudentRecord::where('user_id', Auth::id())->first();
            if ($studentRecord) {
                $this->studentId = $studentRecord->id;
            }
        }
        
        $this->currentYear = date('Y');
        
        // Default to current term dates
        $this->setDateRangeForTerm();
    }
    
    public function setDateRangeForTerm()
    {
        $termDates = $this->getTermDates($this->currentYear, $this->currentTerm);
        $this->startDate = $termDates['start'];
        $this->endDate = $termDates['end'];
    }
    
    public function setDateRangeForMonth()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }
    
    public function setDateRangeForYear()
    {
        $this->startDate = Carbon::createFromDate($this->currentYear, 1, 1)->format('Y-m-d');
        $this->endDate = Carbon::createFromDate($this->currentYear, 12, 31)->format('Y-m-d');
    }
    
    public function updatedDateRange()
    {
        switch ($this->dateRange) {
            case 'term':
                $this->setDateRangeForTerm();
                break;
            case 'month':
                $this->setDateRangeForMonth();
                break;
            case 'year':
                $this->setDateRangeForYear();
                break;
            // 'custom' case doesn't need to do anything as the user will set dates
        }
    }
    
    public function updatedCurrentTerm()
    {
        if ($this->dateRange === 'term') {
            $this->setDateRangeForTerm();
        }
    }
    
    public function updatedCurrentYear()
    {
        if ($this->dateRange === 'term') {
            $this->setDateRangeForTerm();
        } elseif ($this->dateRange === 'year') {
            $this->setDateRangeForYear();
        }
    }
    
    protected function getTermDates($year, $term)
    {
        // This is a simplified version - adapt to your school's actual term dates
        switch ($term) {
            case 1:
                return [
                    'start' => "{$year}-01-10",
                    'end' => "{$year}-04-15",
                ];
            case 2:
                return [
                    'start' => "{$year}-05-10",
                    'end' => "{$year}-08-15",
                ];
            case 3:
                return [
                    'start' => "{$year}-09-10",
                    'end' => "{$year}-12-15",
                ];
            default:
                return [
                    'start' => "{$year}-01-01",
                    'end' => "{$year}-12-31",
                ];
        }
    }
    
    public function applyDateFilter()
    {
        $this->loadChartData();
    }
    
    public function loadChartData()
    {
        // Only load chart data for administrators and accountants
        if (!$this->isAdmin && !$this->isAccountant) {
            return;
        }
        
        // Load payment data by day
        $this->loadPaymentsByDay();
        
        // Load expenses data by day
        $this->loadExpensesByDay();
        
        // Load fee collections by class
        $this->loadFeeCollectionsByClass();
        
        // Load arrears by class
        $this->loadArrearsByClass();
    }
    
    protected function loadPaymentsByDay()
    {
        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);
        
        // Get payments grouped by day
        $payments = StudentFeePayment::where('is_confirmed', true)
            ->where('is_cancelled', false)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->select(DB::raw('DATE(payment_date) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $this->paymentsByDay = $payments->pluck('total', 'date')->toArray();
    }
    
    protected function loadExpensesByDay()
    {
        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);
        
        // Get expenses grouped by day
        $expenses = PaymentVoucher::where('status', 'paid')
            ->where('is_cancelled', false)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->select(DB::raw('DATE(payment_date) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $this->expensesByDay = $expenses->pluck('total', 'date')->toArray();
    }
    
    protected function loadFeeCollectionsByClass()
    {
        $classes = MyClass::all();
        $results = [];
        
        foreach ($classes as $class) {
            $total = StudentFeePayment::whereHas('student', function ($query) use ($class) {
                    $query->where('my_class_id', $class->id);
                })
                ->where('is_confirmed', true)
                ->where('is_cancelled', false)
                ->where('academic_year', $this->currentYear)
                ->where('term', $this->currentTerm)
                ->sum('amount');
                
            $results[$class->name] = $total;
        }
        
        $this->feeCollectionsByClass = $results;
    }
    
    protected function loadArrearsByClass()
    {
        $classes = MyClass::all();
        $results = [];
        
        foreach ($classes as $class) {
            $total = StudentArrear::where('class_id', $class->id)
                ->where('is_cleared', false)
                ->sum('amount');
                
            $results[$class->name] = $total;
        }
        
        $this->arrearsByClass = $results;
    }
    
    public function render()
    {
        // Common data for all user types
        $data = [
            'isAdmin' => $this->isAdmin,
            'isAccountant' => $this->isAccountant,
            'isTeacher' => $this->isTeacher, 
            'isParent' => $this->isParent,
            'isStudent' => $this->isStudent,
        ];
        
        // Admin and Accountant view (full dashboard)
        if ($this->isAdmin || $this->isAccountant) {
            // Calculate summary statistics
            $totalAccountsBalance = FinanceAccount::where('is_active', true)->sum('current_balance');
            
            $totalExpectedFees = FeeStructure::where('academic_year', $this->currentYear)
                ->where('term', $this->currentTerm)
                ->where('is_active', true)
                ->where('is_mandatory', true)
                ->join('my_classes', 'fee_structures.my_class_id', '=', 'my_classes.id')
                ->join('student_records', 'my_classes.id', '=', 'student_records.my_class_id')
                ->where(function($query) {
                    $query->where('student_records.status', 'active')
                        ->orWhere('student_records.status', 'verified');
                })
                ->sum(DB::raw('fee_structures.amount'));
            
            $totalCollectedFees = StudentFeePayment::where('academic_year', $this->currentYear)
                ->where('term', $this->currentTerm)
                ->where('is_confirmed', true)
                ->where('is_cancelled', false)
                ->sum('amount');
            
            $totalArrears = StudentArrear::where('is_cleared', false)->sum('amount');
            
            $recentPayments = StudentFeePayment::with('student')
                ->where('is_confirmed', true)
                ->where('is_cancelled', false)
                ->orderBy('payment_date', 'desc')
                ->limit(5)
                ->get();
            
            $recentExpenses = PaymentVoucher::with('votehead')
                ->where('status', 'paid')
                ->where('is_cancelled', false)
                ->orderBy('payment_date', 'desc')
                ->limit(5)
                ->get();
            
            // Load chart data
            $this->loadChartData();
            
            // Add admin-specific data
            $data = array_merge($data, [
                'totalAccountsBalance' => $totalAccountsBalance,
                'totalExpectedFees' => $totalExpectedFees,
                'totalCollectedFees' => $totalCollectedFees,
                'totalArrears' => $totalArrears,
                'recentPayments' => $recentPayments,
                'recentExpenses' => $recentExpenses,
                'feeCollectionRate' => $totalExpectedFees > 0 ? ($totalCollectedFees / $totalExpectedFees) * 100 : 0,
                'paymentsByDay' => $this->paymentsByDay,
                'expensesByDay' => $this->expensesByDay,
                'feeCollectionsByClass' => $this->feeCollectionsByClass,
                'arrearsByClass' => $this->arrearsByClass,
            ]);
        }
        // Teacher view
        elseif ($this->isTeacher) {
            // Get classes taught by the teacher
            $teacherClasses = MyClass::whereHas('subjects', function ($query) {
                $query->where('teacher_id', Auth::id());
            })->pluck('id')->toArray();
            
            // Get class-specific fee data for classes taught by the teacher
            $classFeeData = [];
            foreach ($teacherClasses as $classId) {
                $class = MyClass::find($classId);
                if ($class) {
                    $totalExpected = FeeStructure::where('my_class_id', $classId)
                        ->where('academic_year', $this->currentYear)
                        ->where('term', $this->currentTerm)
                        ->where('is_active', true)
                        ->where('is_mandatory', true)
                        ->sum('amount');
                    
                    $totalCollected = StudentFeePayment::whereHas('student', function ($query) use ($classId) {
                            $query->where('my_class_id', $classId);
                        })
                        ->where('academic_year', $this->currentYear)
                        ->where('term', $this->currentTerm)
                        ->where('is_confirmed', true)
                        ->where('is_cancelled', false)
                        ->sum('amount');
                    
                    $totalArrears = StudentArrear::where('class_id', $classId)
                        ->where('is_cleared', false)
                        ->sum('amount');
                    
                    $classFeeData[$class->name] = [
                        'expected' => $totalExpected,
                        'collected' => $totalCollected,
                        'arrears' => $totalArrears,
                        'collection_rate' => $totalExpected > 0 ? ($totalCollected / $totalExpected) * 100 : 0,
                    ];
                }
            }
            
            // Add teacher-specific data
            $data['classFeeData'] = $classFeeData;
        }
        // Parent view
        elseif ($this->isParent) {
            // Get fee data for the parent's children
            $childrenFeeData = [];
            $totalOwedByChildren = 0;
            $totalPaidByChildren = 0;
            
            foreach ($this->parentChildren as $childId) {
                $child = StudentRecord::with('my_class')->find($childId);
                if ($child) {
                    $totalExpected = FeeStructure::where('my_class_id', $child->my_class_id)
                        ->where('academic_year', $this->currentYear)
                        ->where('term', $this->currentTerm)
                        ->where('is_active', true)
                        ->where('is_mandatory', true)
                        ->sum('amount');
                    
                    $totalPaid = StudentFeePayment::where('student_id', $childId)
                        ->where('academic_year', $this->currentYear)
                        ->where('term', $this->currentTerm)
                        ->where('is_confirmed', true)
                        ->where('is_cancelled', false)
                        ->sum('amount');
                    
                    $totalArrears = StudentArrear::where('student_id', $childId)
                        ->where('is_cleared', false)
                        ->sum('amount');
                    
                    $childrenFeeData[$childId] = [
                        'name' => $child->first_name . ' ' . $child->last_name,
                        'class' => $child->my_class->name,
                        'expected' => $totalExpected,
                        'paid' => $totalPaid,
                        'balance' => $totalExpected - $totalPaid,
                        'arrears' => $totalArrears,
                        'payment_history' => StudentFeePayment::where('student_id', $childId)
                            ->where('is_confirmed', true)
                            ->where('is_cancelled', false)
                            ->orderBy('payment_date', 'desc')
                            ->limit(3)
                            ->get(),
                    ];
                    
                    $totalOwedByChildren += ($totalExpected + $totalArrears);
                    $totalPaidByChildren += $totalPaid;
                }
            }
            
            // Add parent-specific data
            $data = array_merge($data, [
                'childrenFeeData' => $childrenFeeData,
                'totalOwedByChildren' => $totalOwedByChildren,
                'totalPaidByChildren' => $totalPaidByChildren,
                'paymentProgress' => $totalOwedByChildren > 0 ? ($totalPaidByChildren / $totalOwedByChildren) * 100 : 0,
            ]);
        }
        // Student view
        elseif ($this->isStudent && $this->studentId) {
            $student = StudentRecord::with('my_class')->find($this->studentId);
            
            if ($student) {
                // Get fee data for the student
                $totalExpected = FeeStructure::where('my_class_id', $student->my_class_id)
                    ->where('academic_year', $this->currentYear)
                    ->where('term', $this->currentTerm)
                    ->where('is_active', true)
                    ->where('is_mandatory', true)
                    ->sum('amount');
                
                $totalPaid = StudentFeePayment::where('student_id', $this->studentId)
                    ->where('academic_year', $this->currentYear)
                    ->where('term', $this->currentTerm)
                    ->where('is_confirmed', true)
                    ->where('is_cancelled', false)
                    ->sum('amount');
                
                $totalArrears = StudentArrear::where('student_id', $this->studentId)
                    ->where('is_cleared', false)
                    ->sum('amount');
                
                $paymentHistory = StudentFeePayment::where('student_id', $this->studentId)
                    ->where('is_confirmed', true)
                    ->where('is_cancelled', false)
                    ->orderBy('payment_date', 'desc')
                    ->limit(5)
                    ->get();
                
                // Get fee breakdown
                $feeBreakdown = FeeStructure::where('my_class_id', $student->my_class_id)
                    ->where('academic_year', $this->currentYear)
                    ->where('term', $this->currentTerm)
                    ->where('is_active', true)
                    ->get()
                    ->map(function ($fee) {
                        return [
                            'name' => $fee->name,
                            'amount' => $fee->amount,
                            'is_mandatory' => $fee->is_mandatory,
                        ];
                    });
                
                // Add student-specific data
                $data = array_merge($data, [
                    'student' => $student,
                    'totalExpected' => $totalExpected,
                    'totalPaid' => $totalPaid,
                    'balance' => $totalExpected - $totalPaid,
                    'totalArrears' => $totalArrears,
                    'paymentHistory' => $paymentHistory,
                    'feeBreakdown' => $feeBreakdown,
                    'paymentProgress' => $totalExpected > 0 ? ($totalPaid / $totalExpected) * 100 : 0,
                ]);
            }
        }
        
        return view('livewire.finance.dashboard', $data);
    }
} 