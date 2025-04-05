<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BookReservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'book_id',
        'user_id',
        'approved_by',
        'rejected_by',
        'reservation_date',
        'expiry_date',
        'status',
        'notes',
        'priority',
        'fulfillment_date',
        'loan_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reservation_date' => 'date',
        'expiry_date' => 'date',
        'fulfillment_date' => 'date',
    ];

    /**
     * Get the book being reserved.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    /**
     * Get the user who made the reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the librarian who approved the reservation.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the librarian who rejected the reservation.
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the loan that fulfilled this reservation.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(BookLoan::class, 'loan_id');
    }
    
    /**
     * Check if the reservation has expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        if ($this->status !== 'Approved') {
            return false;
        }
        
        return Carbon::now()->isAfter($this->expiry_date);
    }
    
    /**
     * Get the formatted reservation date.
     */
    public function getFormattedReservationDateAttribute(): string
    {
        return $this->reservation_date ? $this->reservation_date->format('M d, Y') : '';
    }
    
    /**
     * Get the formatted expiry date.
     */
    public function getFormattedExpiryDateAttribute(): string
    {
        return $this->expiry_date ? $this->expiry_date->format('M d, Y') : '';
    }
    
    /**
     * Approve the reservation.
     */
    public function approve($librarian_id, $expiryDays = 3)
    {
        $this->status = 'Approved';
        $this->approved_by = $librarian_id;
        $this->expiry_date = Carbon::now()->addDays($expiryDays);
        $this->save();
        
        return $this;
    }
    
    /**
     * Reject the reservation.
     */
    public function reject($librarian_id, $notes = null)
    {
        $this->status = 'Rejected';
        $this->rejected_by = $librarian_id;
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        $this->save();
        
        return $this;
    }
    
    /**
     * Cancel the reservation.
     */
    public function cancel($notes = null)
    {
        $this->status = 'Cancelled';
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        $this->save();
        
        return $this;
    }
    
    /**
     * Fulfill the reservation by creating a loan.
     */
    public function fulfill($bookCopyId, $librarian_id, $dueDays = 14)
    {
        // Create the loan
        $loan = BookLoan::create([
            'book_copy_id' => $bookCopyId,
            'user_id' => $this->user_id,
            'issued_by' => $librarian_id,
            'issue_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays($dueDays),
            'status' => 'Active',
        ]);
        
        // Mark the reservation as fulfilled
        $this->status = 'Fulfilled';
        $this->fulfillment_date = Carbon::now();
        $this->loan_id = $loan->id;
        $this->save();
        
        return $loan;
    }
    
    /**
     * Scope a query to only include pending reservations.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }
    
    /**
     * Scope a query to only include approved reservations.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }
    
    /**
     * Scope a query to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    /**
     * Scope a query to only include active reservations (pending or approved and not expired).
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'Pending')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'Approved')
                     ->where('expiry_date', '>=', Carbon::now());
              });
        });
    }
    
    /**
     * Scope a query to order by priority and then by reservation date.
     */
    public function scopeOrderByPriority($query)
    {
        return $query->orderByRaw("
            CASE 
                WHEN priority = 'High' THEN 1
                WHEN priority = 'Normal' THEN 2
                WHEN priority = 'Low' THEN 3
                ELSE 4
            END
        ")->orderBy('reservation_date');
    }
}
