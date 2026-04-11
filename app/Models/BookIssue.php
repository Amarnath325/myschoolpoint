<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookIssue extends Model
{
    protected $fillable = [
        'school_id', 'book_id', 'student_id', 'issue_date', 'return_date',
        'due_date', 'status', 'fine_amount', 'remarks', 'issued_by'
    ];
    
    protected $casts = [
        'issue_date' => 'date',
        'return_date' => 'date',
        'due_date' => 'date',
        'fine_amount' => 'decimal:2',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function book(): BelongsTo
    {
        return $this->belongsTo(LibraryBook::class);
    }
    
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
    
    public function calculateFine(): float
    {
        if ($this->status !== 'issued' || !$this->due_date) {
            return 0;
        }
        
        $today = now()->startOfDay();
        $dueDate = $this->due_date;
        
        if ($today <= $dueDate) {
            return 0;
        }
        
        $daysLate = $today->diffInDays($dueDate);
        return $daysLate * 5; // ₹5 per day late fine
    }
}