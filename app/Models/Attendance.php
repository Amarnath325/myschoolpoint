<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'school_id', 'class_id', 'student_id', 'academic_year_id', 'date',
        'status', 'in_time', 'out_time', 'remarks', 'marked_by'
    ];
    
    protected $casts = [
        'date' => 'date',
        'in_time' => 'datetime',
        'out_time' => 'datetime',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
    
    public function marker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}