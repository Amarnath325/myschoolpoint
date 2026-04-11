<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassTeacher extends Model
{
    protected $fillable = ['school_id', 'academic_year_id', 'class_id', 'teacher_id', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function class(): BelongsTo { return $this->belongsTo(Classes::class); }
    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }
}