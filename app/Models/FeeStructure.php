<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeStructure extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'my_class_id',
        'academic_year',
        'term',
        'name',
        'category',
        'amount',
        'description',
        'is_mandatory',
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
        'amount' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'academic_year' => 'integer',
        'term' => 'integer',
    ];
    
    /**
     * Get the class that this fee structure belongs to.
     */
    public function myClass(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }
    
    /**
     * Get the total fee amount for a specific class, academic year and term.
     */
    public static function totalFeeAmount(int $classId, int $academicYear, int $term): float
    {
        return self::where('my_class_id', $classId)
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('is_active', true)
            ->where('is_mandatory', true)
            ->sum('amount');
    }
    
    /**
     * Get the mandatory fees for a specific class, academic year and term.
     */
    public static function getMandatoryFees(int $classId, int $academicYear, int $term)
    {
        return self::where('my_class_id', $classId)
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('is_active', true)
            ->where('is_mandatory', true)
            ->get();
    }
    
    /**
     * Get the optional fees for a specific class, academic year and term.
     */
    public static function getOptionalFees(int $classId, int $academicYear, int $term)
    {
        return self::where('my_class_id', $classId)
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('is_active', true)
            ->where('is_mandatory', false)
            ->get();
    }
}
