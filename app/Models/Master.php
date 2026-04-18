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
        $records = self::where('m_group', strtoupper($group))
            ->get();
        return self::applySorting(strtoupper($group), $records);
    }

    public static function getByGroupAndType(string $group, string $type): \Illuminate\Support\Collection
    {
        $records = self::where('m_group', strtoupper($group))
            ->where('m_type', $type)
            ->get();
        return self::applySorting(strtoupper($group), $records);
    }

    public static function getOptions(string $group): array
    {
        $records = self::where('m_group', strtoupper($group))->get();
        $sorted = self::applySorting(strtoupper($group), $records);
        return $sorted->pluck('m_alias_name', 'm_name')->toArray();
    }

    public static function getValue(string $group, string $key): ?string
    {
        $record = self::where('m_group', strtoupper($group))
            ->where('m_name', strtoupper($key))
            ->first();
        
        return $record ? $record->m_alias_name ?? $record->m_name : null;
    }

    /**
     * Sort records with custom logic for CLASS group
     * Classes: Nursery, LKG, UKG first, then numeric classes
     */
    protected static function applySorting(string $group, \Illuminate\Support\Collection $records): \Illuminate\Support\Collection
    {
        // Only apply special sorting for CLASS group
        if ($group !== 'CLASS') {
            return $records->sortBy('m_name')->values();
        }

        return $records->sort(function ($a, $b) {
            $aName = strtoupper($a->m_name);
            $bName = strtoupper($b->m_name);

            // Define priority order for special classes
            $priorities = ['NURSERY' => 0, 'LKG' => 1, 'UKG' => 2];

            $aPriority = $priorities[$aName] ?? 3;
            $bPriority = $priorities[$bName] ?? 3;

            // If priorities are different, sort by priority
            if ($aPriority !== $bPriority) {
                return $aPriority - $bPriority;
            }

            // If both are numeric or both are special classes
            if ($aPriority === 3) {
                // Convert to number for sorting (e.g., "1", "2", "10")
                $aNum = (int)$aName;
                $bNum = (int)$bName;
                
                // If both are numeric, sort numerically
                if ($aNum > 0 && $bNum > 0) {
                    return $aNum - $bNum;
                }
            }

            // Default alphabetical sort
            return strcmp($aName, $bName);
        })->values();
    }
    
    // Accessor for display name
    public function getDisplayNameAttribute(): string
    {
        return $this->m_alias_name ?? $this->m_name;
    }
}
