<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinanceAccount extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'initial_balance',
        'current_balance',
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
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the voteheads for this finance account.
     */
    public function voteheads(): HasMany
    {
        return $this->hasMany(AccountVotehead::class, 'finance_account_id');
    }
    
    /**
     * Get the allocations for this finance account.
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(FeeAllocation::class, 'finance_account_id');
    }
    
    /**
     * Get the total allocated amount for this account for a specific academic year and term.
     */
    public function totalAllocated(int $academicYear, int $term): float
    {
        return $this->allocations()
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('is_approved', true)
            ->sum('amount');
    }
    
    /**
     * Get the total unallocated amount for this account for a specific academic year and term.
     */
    public function unallocatedAmount(int $academicYear, int $term): float
    {
        $totalAllocated = $this->totalAllocated($academicYear, $term);
        return $this->current_balance - $totalAllocated;
    }
    
    /**
     * Get the total unspent allocations for this account.
     */
    public function totalUnspent(int $academicYear, int $term): float
    {
        return $this->voteheads()
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('is_active', true)
            ->sum('balance');
    }
    
    /**
     * Get the total amount available (unspent allocations + unallocated funds)
     */
    public function totalAvailable(int $academicYear, int $term): float
    {
        return $this->totalUnspent($academicYear, $term) + $this->unallocatedAmount($academicYear, $term);
    }
}
