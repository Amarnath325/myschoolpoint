<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolBusinessDetail extends Model
{
    protected $table = 'school_business_details';
    
    protected $fillable = [
        'school_id', 'owner_name', 'owner_phone', 'owner_email', 'pan_number',
        'gst_number', 'bank_name', 'account_number', 'ifsc_code', 'upi_id',
        'contract_file', 'kyc_document', 'is_verified', 'verified_at'
    ];
    
    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];
    
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
