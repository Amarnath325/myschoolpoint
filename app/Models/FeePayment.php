<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeePayment extends Model
{
    protected $fillable = [
        'school_id', 'student_id', 'fee_structure_id', 'academic_year_id',
        'amount', 'payment_date', 'payment_mode', 'transaction_id',
        'cheque_number', 'bank_name', 'receipt_number', 'payment_status',
        'payment_for_month', 'remarks', 'collected_by'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class);
    }
    
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
    
    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}