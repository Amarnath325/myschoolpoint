<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'school_id', 'class_id', 'name', 'code', 'subject_type',
        'max_marks', 'passing_marks', 'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'max_marks' => 'integer',
        'passing_marks' => 'integer',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
    
    public function timetable(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }
}