<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BookLoan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'book_copy_id',
        'user_id',
        'issued_by',
        'received_by',
        'issue_date',
        'due_date',
        'return_date',
        'status',
        'fine_amount',
        'is_fine_paid',
        'fine_paid_date',
        'fine_payment_reference',
        'notes',
        'condition_on_return',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_amount' => 'decimal:2',
        'is_fine_paid' => 'boolean',
        'fine_paid_date' => 'date',
    ];

    /**
     * Get the book copy that was loaned.
     */
    public function bookCopy(): BelongsTo
    {
        return $this->belongsTo(BookCopy::class, 'book_copy_id');
    }

    /**
     * Get the user who borrowed the book.
     */
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the librarian who issued the book.
     */
    public function issuedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get the librarian who received the book back.
     */
    public function receivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
    
    /**
     * Get the book details through the copy.
     */
    public function getBookAttribute()
    {
        return $this->bookCopy ? $this->bookCopy->book : null;
    }
    
    /**
     * Check if the loan is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'Returned') {
            return false;
        }
        
        return Carbon::now()->isAfter($this->due_date);
    }
    
    /**
     * Get the number of days overdue.
     */
    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        return Carbon::now()->diffInDays($this->due_date);
    }
    
    /**
     * Get the formatted issue date.
     */
    public function getFormattedIssueDateAttribute(): string
    {
        return $this->issue_date ? $this->issue_date->format('M d, Y') : '';
    }
    
    /**
     * Get the formatted due date.
     */
    public function getFormattedDueDateAttribute(): string
    {
        return $this->due_date ? $this->due_date->format('M d, Y') : '';
    }
    
    /**
     * Get the formatted return date.
     */
    public function getFormattedReturnDateAttribute(): string
    {
        return $this->return_date ? $this->return_date->format('M d, Y') : '';
    }
    
    /**
     * Mark the loan as returned.
     */
    public function returnBook($librarian_id, $condition = null, $notes = null)
    {
        $this->status = 'Returned';
        $this->return_date = Carbon::now();
        $this->received_by = $librarian_id;
        
        if ($condition) {
            $this->condition_on_return = $condition;
        }
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        $this->save();
        
        // Update book copy status
        $bookCopy = $this->bookCopy;
        $bookCopy->status = 'Available';
        $bookCopy->save();
        
        return $this;
    }
    
    /**
     * Calculate fine amount based on days overdue.
     */
    public function calculateFine($dailyRate = 0.50)
    {
        if ($this->is_overdue) {
            $this->fine_amount = $this->days_overdue * $dailyRate;
            $this->save();
        }
        
        return $this->fine_amount;
    }
    
    /**
     * Scope a query to only include active loans.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
    
    /**
     * Scope a query to only include overdue loans.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'Active')
                     ->where('due_date', '<', Carbon::now());
    }
    
    /**
     * Scope a query to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    /**
     * Updates the book copy status when a loan is created or deleted
     */
    protected static function booted()
    {
        static::created(function ($loan) {
            $bookCopy = $loan->bookCopy;
            $bookCopy->status = 'On Loan';
            $bookCopy->save();
        });
    }
}
