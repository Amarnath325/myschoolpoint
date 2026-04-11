<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryBook extends Model
{
    protected $fillable = [
        'school_id', 'book_code', 'title', 'author', 'publisher', 'isbn',
        'category', 'quantity', 'available_quantity', 'location',
        'purchase_date', 'price'
    ];
    
    protected $casts = [
        'quantity' => 'integer',
        'available_quantity' => 'integer',
        'purchase_date' => 'date',
        'price' => 'decimal:2',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    
    public function issues(): HasMany
    {
        return $this->hasMany(BookIssue::class);
    }
    
    public function isAvailable(): bool
    {
        return $this->available_quantity > 0;
    }
}