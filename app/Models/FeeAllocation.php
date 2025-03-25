<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeAllocation extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'finance_account_id',
        'votehead_id',
        'amount',
        'academic_year',
        'term',
        'description',
        'is_approved',
        'approved_at',
        'approved_by',
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
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'academic_year' => 'integer',
        'term' => 'integer',
    ];
    
    /**
     * Get the finance account that this allocation belongs to.
     */
    public function financeAccount(): BelongsTo
    {
        return $this->belongsTo(FinanceAccount::class, 'finance_account_id');
    }
    
    /**
     * Get the votehead that this allocation belongs to.
     */
    public function votehead(): BelongsTo
    {
        return $this->belongsTo(AccountVotehead::class, 'votehead_id');
    }
    
    /**
     * Approve this allocation.
     */
    public function approve(string $approvedBy): void
    {
        $this->is_approved = true;
        $this->approved_at = now();
        $this->approved_by = $approvedBy;
        $this->save();
        
        // Update the allocated_amount and balance in the votehead
        $votehead = $this->votehead;
        $votehead->allocated_amount += $this->amount;
        $votehead->balance += $this->amount;
        $votehead->save();
    }
    
    /**
     * Get total allocations for a specific votehead, academic year and term.
     */
    public static function totalAllocations(int $voteheadId, int $academicYear, int $term): float
    {
        return self::where('votehead_id', $voteheadId)
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('is_approved', true)
            ->sum('amount');
    }
}
