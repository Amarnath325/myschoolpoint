<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeStructure extends Model
{
    protected $fillable = [
        'school_id', 'academic_year_id', 'class_id', 'fee_head', 'amount',
        'frequency', 'due_date', 'late_fee_amount', 'is_optional', 'is_active'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'late_fee_amount' => 'decimal:2',
        'due_date' => 'date',
        'is_optional' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
    
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    
    public function payments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }
}