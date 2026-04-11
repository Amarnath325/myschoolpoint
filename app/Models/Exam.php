<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'school_id', 'academic_year_id', 'class_id', 'name', 'exam_type',
        'term', 'start_date', 'end_date', 'max_marks', 'passing_marks', 'is_active'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'max_marks' => 'integer',
        'passing_marks' => 'integer',
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
    
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}   