<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryBook extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'subtitle',
        'isbn',
        'isbn13',
        'category_id',
        'publisher',
        'publication_date',
        'edition',
        'pages',
        'language',
        'cover_image',
        'table_of_contents',
        'copies_available',
        'total_copies',
        'is_reference_only',
        'is_featured',
        'is_active',
        'replacement_cost',
        'dewey_decimal',
        'call_number',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publication_date' => 'date',
        'pages' => 'integer',
        'copies_available' => 'integer',
        'total_copies' => 'integer',
        'is_reference_only' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'replacement_cost' => 'decimal:2',
        'metadata' => 'json',
    ];

    /**
     * Get the category that the book belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    /**
     * Get the authors for the book.
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(BookAuthor::class, 'book_author', 'book_id', 'author_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }
    
    /**
     * Get the primary author for the book.
     */
    public function primaryAuthor()
    {
        return $this->authors()->wherePivot('is_primary', true)->first();
    }

    /**
     * Get the copies for the book.
     */
    public function copies(): HasMany
    {
        return $this->hasMany(BookCopy::class, 'book_id');
    }
    
    /**
     * Get available copies
     */
    public function availableCopies()
    {
        return $this->copies()->where('status', 'Available');
    }

    /**
     * Get the loans associated with this book.
     */
    public function loans()
    {
        return $this->hasManyThrough(
            BookLoan::class,
            BookCopy::class,
            'book_id', // Foreign key on BookCopy table
            'book_copy_id', // Foreign key on BookLoan table
            'id', // Local key on LibraryBook table
            'id' // Local key on BookCopy table
        );
    }

    /**
     * Get the reservations for the book.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(BookReservation::class, 'book_id');
    }
    
    /**
     * Get active reservations
     */
    public function activeReservations()
    {
        return $this->reservations()->where('status', 'Pending');
    }
    
    /**
     * Get author names as string.
     */
    public function getAuthorNamesAttribute(): string
    {
        return $this->authors->pluck('name')->join(', ');
    }
    
    /**
     * Check if the book is available for loan.
     */
    public function getIsAvailableAttribute(): bool
    {
        return !$this->is_reference_only && $this->copies_available > 0;
    }
    
    /**
     * Get the publication year.
     */
    public function getPublicationYearAttribute()
    {
        return $this->publication_date ? $this->publication_date->format('Y') : null;
    }
    
    /**
     * Scope a query to only include active books.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to only include featured books.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    /**
     * Scope a query to only include available books.
     */
    public function scopeAvailable($query)
    {
        return $query->where('copies_available', '>', 0)
                     ->where('is_active', true);
    }
    
    /**
     * Scope a query to filter by category.
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
    
    /**
     * Scope a query to search books.
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('subtitle', 'like', "%{$searchTerm}%")
              ->orWhere('isbn', 'like', "%{$searchTerm}%")
              ->orWhere('isbn13', 'like', "%{$searchTerm}%")
              ->orWhere('publisher', 'like', "%{$searchTerm}%")
              ->orWhere('dewey_decimal', 'like', "%{$searchTerm}%")
              ->orWhere('call_number', 'like', "%{$searchTerm}%")
              ->orWhereHas('authors', function ($author) use ($searchTerm) {
                  $author->where('name', 'like', "%{$searchTerm}%");
              });
        });
    }
}
