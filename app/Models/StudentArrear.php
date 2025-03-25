<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentArrear extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'class_id',
        'amount',
        'previous_year',
        'previous_term',
        'description',
        'is_cleared',
        'cleared_at',
        'cleared_by',
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
        'is_cleared' => 'boolean',
        'cleared_at' => 'datetime',
        'previous_year' => 'integer',
        'previous_term' => 'integer',
    ];
    
    /**
     * Get the student that this arrear belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }
    
    /**
     * Get the class that this arrear is associated with.
     */
    public function myClass(): BelongsTo
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }
    
    /**
     * Mark this arrear as cleared.
     */
    public function markAsCleared(string $clearedBy): void
    {
        if ($this->is_cleared) {
            throw new \Exception('This arrear is already cleared.');
        }
        
        $this->is_cleared = true;
        $this->cleared_at = now();
        $this->cleared_by = $clearedBy;
        $this->save();
    }
    
    /**
     * Get the total arrears for a specific student.
     */
    public static function totalArrears(int $studentId): float
    {
        return self::where('student_id', $studentId)
            ->where('is_cleared', false)
            ->sum('amount');
    }
    
    /**
     * Get the total arrears for a specific class.
     */
    public static function totalClassArrears(int $classId): float
    {
        return self::where('class_id', $classId)
            ->where('is_cleared', false)
            ->sum('amount');
    }
    
    /**
     * Get the total arrears for all classes in the school.
     */
    public static function totalSchoolArrears(): float
    {
        return self::where('is_cleared', false)
            ->sum('amount');
    }
}
