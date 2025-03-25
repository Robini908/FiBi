<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountVotehead extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'finance_account_id',
        'name',
        'code',
        'description',
        'allocated_amount',
        'spent_amount',
        'balance',
        'academic_year',
        'term',
        'is_active',
        'created_by',
        'updated_by',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
        'academic_year' => 'integer',
        'term' => 'integer',
    ];
    
    /**
     * Get the account that this votehead belongs to.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(FinanceAccount::class, 'finance_account_id');
    }
    
    /**
     * Get the allocations for this votehead.
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(FeeAllocation::class, 'votehead_id');
    }
    
    /**
     * Get the payment vouchers for this votehead.
     */
    public function paymentVouchers(): HasMany
    {
        return $this->hasMany(PaymentVoucher::class, 'votehead_id');
    }
    
    /**
     * Calculate the remaining balance for this votehead.
     * This is a dynamic calculation and not stored in the database.
     */
    public function calculateRemainingBalance(): float
    {
        return $this->allocated_amount - $this->spent_amount;
    }
    
    /**
     * Get the total allocation for a specific academic year and term.
     */
    public function totalAllocation(int $academicYear, int $term): float
    {
        return $this->allocations()
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('is_approved', true)
            ->sum('amount');
    }
    
    /**
     * Get the total spent for a specific academic year and term.
     */
    public function totalSpent(int $academicYear, int $term): float
    {
        return $this->paymentVouchers()
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('status', 'paid')
            ->where('is_cancelled', false)
            ->sum('amount');
    }
    
    /**
     * Update the balance of this votehead.
     * This should be called whenever an allocation is made or a payment is processed.
     */
    public function updateBalance(): void
    {
        $this->balance = $this->allocated_amount - $this->spent_amount;
        $this->save();
    }
}
