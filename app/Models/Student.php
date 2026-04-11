<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'school_id', 'class_id', 'academic_year_id', 'admission_number',
        'roll_number', 'admission_date', 'father_name', 'mother_name',
        'parent_email', 'parent_phone', 'blood_group', 'medical_info',
        'transport_required', 'hostel_required', 'previous_school'
    ];
    
    protected $casts = [
        'admission_date' => 'date',
        'transport_required' => 'boolean',
        'hostel_required' => 'boolean',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
    
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
    
    public function feePayments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }
    
    public function getFullNameAttribute(): string
    {
        return $this->user->full_name ?? '';
    }
    
    public function getAttendancePercentage($startDate = null, $endDate = null): float
    {
        $query = $this->attendances();
        
        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }
        
        $totalDays = $query->count();
        $presentDays = $query->where('status', 'present')->count();
        
        return $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;
    }
}
