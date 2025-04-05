<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookCategory extends Model
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
        'description',
        'parent_id',
        'color_code',
        'display_order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(BookCategory::class, 'parent_id');
    }

    /**
     * Get the books in this category.
     */
    public function books(): HasMany
    {
        return $this->hasMany(LibraryBook::class, 'category_id');
    }

    /**
     * Get all available books in this category (including subcategories)
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getAllBooks()
    {
        $books = $this->books()->where('is_active', true)->get();
        
        foreach ($this->children as $child) {
            $books = $books->merge($child->getAllBooks());
        }
        
        return $books;
    }
    
    /**
     * Get the category's full hierarchy path
     * 
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        $path = $this->name;
        $category = $this;
        
        while ($category->parent) {
            $category = $category->parent;
            $path = $category->name . ' > ' . $path;
        }
        
        return $path;
    }
    
    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }
    
    /**
     * Scope a query to only include root categories (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
