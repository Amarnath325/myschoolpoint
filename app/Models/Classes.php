<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classes extends Model
{
    protected $table = 'classes';
    
    protected $fillable = [
        'school_id', 'academic_year_id', 'name', 'section', 
        'numeric_value', 'capacity', 'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'numeric_value' => 'integer',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
    
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
    
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
    
    public function classTeacher()
    {
        return $this->hasOne(ClassTeacher::class)->where('is_active', true);
    }
    
    public function getFullNameAttribute(): string
    {
        return $this->name . ($this->section ? ' - ' . $this->section : '');
    }
}