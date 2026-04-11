<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'user_id', 'school_id', 'employee_id', 'qualification', 'specialization',
        'experience_years', 'joining_date', 'department', 'salary', 'is_class_teacher'
    ];
    
    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'experience_years' => 'integer',
        'is_class_teacher' => 'boolean',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function classTeachers(): HasMany
    {
        return $this->hasMany(ClassTeacher::class);
    }
    
    public function timetable(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }
    
    public function getFullNameAttribute(): string
    {
        return $this->user->full_name ?? '';
    }
}
