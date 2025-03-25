<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentVoucher extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'voucher_number',
        'votehead_id',
        'amount',
        'cheque_number',
        'recipient_name',
        'recipient_id_number',
        'recipient_phone',
        'recipient_address',
        'description',
        'purpose',
        'academic_year',
        'term',
        'payment_date',
        'status',
        'is_cancelled',
        'cancellation_reason',
        'cancelled_at',
        'cancelled_by',
        'payment_method',
        'approved_at',
        'approved_by',
        'paid_at',
        'paid_by',
        'created_by',
        'updated_by',
        'attachment_path',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'is_cancelled' => 'boolean',
        'payment_date' => 'date',
        'cancelled_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'academic_year' => 'integer',
        'term' => 'integer',
    ];
    
    /**
     * Get the votehead that this payment voucher belongs to.
     */
    public function votehead(): BelongsTo
    {
        return $this->belongsTo(AccountVotehead::class, 'votehead_id');
    }
    
    /**
     * Approve this payment voucher.
     */
    public function approve(string $approvedBy): void
    {
        if ($this->status !== 'pending') {
            throw new \Exception('Only pending vouchers can be approved.');
        }
        
        $this->status = 'approved';
        $this->approved_at = now();
        $this->approved_by = $approvedBy;
        $this->save();
    }
    
    /**
     * Mark this payment voucher as paid.
     */
    public function markAsPaid(string $paidBy): void
    {
        if ($this->status !== 'approved') {
            throw new \Exception('Only approved vouchers can be marked as paid.');
        }
        
        if ($this->is_cancelled) {
            throw new \Exception('Cancelled vouchers cannot be marked as paid.');
        }
        
        $this->status = 'paid';
        $this->paid_at = now();
        $this->paid_by = $paidBy;
        $this->save();
        
        // Update the spent_amount and balance in the votehead
        $votehead = $this->votehead;
        $votehead->spent_amount += $this->amount;
        $votehead->balance -= $this->amount;
        $votehead->save();
    }
    
    /**
     * Cancel this payment voucher.
     */
    public function cancel(string $cancelledBy, string $reason): void
    {
        if ($this->status === 'paid') {
            throw new \Exception('Paid vouchers cannot be cancelled.');
        }
        
        if ($this->is_cancelled) {
            throw new \Exception('This voucher is already cancelled.');
        }
        
        $this->status = 'cancelled';
        $this->is_cancelled = true;
        $this->cancellation_reason = $reason;
        $this->cancelled_at = now();
        $this->cancelled_by = $cancelledBy;
        $this->save();
    }
    
    /**
     * Generate a unique voucher number.
     */
    public static function generateVoucherNumber(): string
    {
        $prefix = 'PV';
        $year = date('Y');
        $month = date('m');
        
        $lastVoucher = self::where('voucher_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('id', 'desc')
            ->first();
            
        if ($lastVoucher) {
            $lastNumber = substr($lastVoucher->voucher_number, strlen($prefix . $year . $month));
            $nextNumber = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }
        
        return $prefix . $year . $month . $nextNumber;
    }
}
