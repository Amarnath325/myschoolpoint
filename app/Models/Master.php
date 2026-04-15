<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Master extends Model
{
    protected $table = 'masters';
    protected $primaryKey = 'm_id';
    
    protected $fillable = [
        'm_id',
        'm_group',
        'm_name',
        'm_alias_name',
        'm_type',
        'm_other',
        'm_description'
    ];
    
    protected $casts = [
        'm_other' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Scopes for easy filtering
    public function scopeByGroup(Builder $query, string $group): Builder
    {
        return $query->where('m_group', strtoupper($group));
    }
    
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('m_type', $type);
    }
    
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('m_type', 'active');
    }
    
    // Helper methods
    public static function getByGroup(string $group): \Illuminate\Support\Collection
    {
        return self::where('m_group', strtoupper($group))
            ->orderBy('m_name')
            ->get();
    }
    
    public static function getByGroupAndType(string $group, string $type): \Illuminate\Support\Collection
    {
        return self::where('m_group', strtoupper($group))
            ->where('m_type', $type)
            ->orderBy('m_name')
            ->get();
    }
    
    public static function getOptions(string $group): array
    {
        return self::where('m_group', strtoupper($group))
            ->orderBy('m_name')
            ->pluck('m_alias_name', 'm_name')
            ->toArray();
    }
    
    public static function getValue(string $group, string $key): ?string
    {
        $record = self::where('m_group', strtoupper($group))
            ->where('m_name', strtoupper($key))
            ->first();
        
        return $record ? $record->m_alias_name ?? $record->m_name : null;
    }
    
    // Accessor for display name
    public function getDisplayNameAttribute(): string
    {
        return $this->m_alias_name ?? $this->m_name;
    }
}
