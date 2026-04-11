<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAcademicHistory extends Model
{
    protected $fillable = [
        'student_id', 'school_id', 'academic_year_id', 'class_id', 'roll_number',
        'is_promoted', 'promoted_to_class_id', 'result', 'percentage', 'remarks'
    ];
    
    protected $casts = [
        'is_promoted' => 'boolean',
        'percentage' => 'decimal:2',
    ];
    
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function class(): BelongsTo { return $this->belongsTo(Classes::class); }
    public function promotedToClass(): BelongsTo { return $this->belongsTo(Classes::class, 'promoted_to_class_id'); }
}