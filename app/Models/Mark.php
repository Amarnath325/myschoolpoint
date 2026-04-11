<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{
    protected $fillable = [
        'school_id', 'exam_id', 'student_id', 'subject_id', 'marks_obtained',
        'total_marks', 'percentage', 'grade', 'remarks', 'entered_by'
    ];
    
    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'total_marks' => 'integer',
        'percentage' => 'decimal:2',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }
    
    protected static function booted()
    {
        static::saving(function ($mark) {
            if ($mark->total_marks && $mark->marks_obtained) {
                $mark->percentage = ($mark->marks_obtained / $mark->total_marks) * 100;
                $mark->grade = self::calculateGrade($mark->percentage);
            }
        });
    }
    
    private static function calculateGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 33) return 'D';
        return 'F';
    }
}