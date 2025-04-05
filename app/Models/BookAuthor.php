<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BookAuthor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'biography',
        'birth_date',
        'death_date',
        'nationality',
        'website',
        'email',
        'image_path',
        'is_featured',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the books for the author.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(LibraryBook::class, 'book_author', 'author_id', 'book_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }
    
    /**
     * Get only the primary books for the author.
     */
    public function primaryBooks(): BelongsToMany
    {
        return $this->books()->wherePivot('is_primary', true);
    }
    
    /**
     * Get full name with lifespan.
     */
    public function getFullNameWithLifespanAttribute(): string
    {
        $name = $this->name;
        
        if ($this->birth_date) {
            $birth = $this->birth_date->format('Y');
            $death = $this->death_date ? $this->death_date->format('Y') : 'present';
            $name .= " ({$birth} - {$death})";
        }
        
        return $name;
    }
    
    /**
     * Scope a query to only include active authors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to only include featured authors.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    /**
     * Scope a query to order alphabetically by name.
     */
    public function scopeAlphabetical($query)
    {
        return $query->orderBy('name');
    }
}
