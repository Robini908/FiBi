<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentFeePayment extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'receipt_number',
        'amount',
        'academic_year',
        'term',
        'payment_date',
        'payment_method',
        'received_by',
        'cheque_number',
        'bank_name',
        'cheque_date',
        'bank_slip_number',
        'bank_branch',
        'bank_transaction_date',
        'mpesa_transaction_id',
        'mpesa_phone_number',
        'mpesa_transaction_time',
        'notes',
        'is_confirmed',
        'is_cancelled',
        'cancellation_reason',
        'cancelled_at',
        'cancelled_by',
        'created_by',
        'updated_by',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'cheque_date' => 'date',
        'bank_transaction_date' => 'date',
        'mpesa_transaction_time' => 'datetime',
        'is_confirmed' => 'boolean',
        'is_cancelled' => 'boolean',
        'cancelled_at' => 'datetime',
        'academic_year' => 'integer',
        'term' => 'integer',
    ];
    
    /**
     * Get the student that this payment belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }
    
    /**
     * Cancel this fee payment.
     */
    public function cancel(string $cancelledBy, string $reason): void
    {
        if ($this->is_cancelled) {
            throw new \Exception('This payment is already cancelled.');
        }
        
        $this->is_cancelled = true;
        $this->cancellation_reason = $reason;
        $this->cancelled_at = now();
        $this->cancelled_by = $cancelledBy;
        $this->save();
    }
    
    /**
     * Confirm this fee payment.
     */
    public function confirm(string $confirmedBy): void
    {
        if ($this->is_confirmed) {
            throw new \Exception('This payment is already confirmed.');
        }
        
        $this->is_confirmed = true;
        $this->updated_by = $confirmedBy;
        $this->save();
    }
    
    /**
     * Generate a unique receipt number.
     */
    public static function generateReceiptNumber(): string
    {
        $prefix = 'RCP';
        $year = date('Y');
        $month = date('m');
        
        $lastReceipt = self::where('receipt_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('id', 'desc')
            ->first();
            
        if ($lastReceipt) {
            $lastNumber = substr($lastReceipt->receipt_number, strlen($prefix . $year . $month));
            $nextNumber = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }
        
        return $prefix . $year . $month . $nextNumber;
    }
    
    /**
     * Get the total fee paid for a specific student, academic year and term.
     */
    public static function totalPaid(int $studentId, int $academicYear, int $term): float
    {
        return self::where('student_id', $studentId)
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('is_confirmed', true)
            ->where('is_cancelled', false)
            ->sum('amount');
    }
    
    /**
     * Get the fee balance for a specific student, academic year and term.
     */
    public static function feeBalance(int $studentId, int $academicYear, int $term): float
    {
        $student = StudentRecord::find($studentId);
        
        if (!$student) {
            return 0;
        }
        
        $totalFee = FeeStructure::totalFeeAmount($student->my_class_id, $academicYear, $term);
        $totalPaid = self::totalPaid($studentId, $academicYear, $term);
        
        // Include any arrears for the student
        $arrears = StudentArrear::where('student_id', $studentId)
            ->where('is_cleared', false)
            ->sum('amount');
        
        return ($totalFee + $arrears) - $totalPaid;
    }
}
