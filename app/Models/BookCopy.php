<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookCopy extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'copy_number',
        'book_id',
        'barcode',
        'rfid_tag',
        'acquisition_date',
        'price',
        'acquisition_source',
        'shelf_location',
        'condition',
        'condition_notes',
        'status',
        'last_inventory_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'acquisition_date' => 'date',
        'price' => 'decimal:2',
        'last_inventory_date' => 'date',
    ];

    /**
     * Get the book that owns the copy.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    /**
     * Get the loans for the copy.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(BookLoan::class, 'book_copy_id');
    }
    
    /**
     * Get the current active loan for this copy, if any.
     */
    public function activeLoan()
    {
        return $this->loans()->where('status', 'Active')->first();
    }
    
    /**
     * Get the current borrower, if any.
     */
    public function currentBorrower()
    {
        $activeLoan = $this->activeLoan();
        return $activeLoan ? $activeLoan->borrower : null;
    }
    
    /**
     * Check if the copy is available.
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'Available';
    }
    
    /**
     * Get the full identifier including book title.
     */
    public function getFullIdentifierAttribute(): string
    {
        return $this->book->title . ' (Copy #' . $this->copy_number . ')';
    }
    
    /**
     * Scope a query to only include available copies.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available');
    }
    
    /**
     * Scope a query to only include copies on loan.
     */
    public function scopeOnLoan($query)
    {
        return $query->where('status', 'On Loan');
    }
    
    /**
     * Scope a query to filter by condition.
     */
    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }
    
    /**
     * Updates related book counts when a copy's status changes
     */
    protected static function booted()
    {
        static::created(function ($copy) {
            $book = $copy->book;
            $book->total_copies += 1;
            if ($copy->status === 'Available') {
                $book->copies_available += 1;
            }
            $book->save();
        });
        
        static::updated(function ($copy) {
            $book = $copy->book;
            
            // If status changed to/from Available, update the available count
            if ($copy->isDirty('status')) {
                $wasAvailable = $copy->getOriginal('status') === 'Available';
                $isAvailable = $copy->status === 'Available';
                
                if ($wasAvailable && !$isAvailable) {
                    $book->copies_available = max(0, $book->copies_available - 1);
                } elseif (!$wasAvailable && $isAvailable) {
                    $book->copies_available += 1;
                }
                
                $book->save();
            }
        });
        
        static::deleted(function ($copy) {
            $book = $copy->book;
            $book->total_copies = max(0, $book->total_copies - 1);
            if ($copy->status === 'Available') {
                $book->copies_available = max(0, $book->copies_available - 1);
            }
            $book->save();
        });
    }
}
