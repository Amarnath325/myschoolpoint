<?php

namespace App\Services;

use App\Models\Master;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MasterService
{
    protected $cacheTtl = 3600; // 1 hour
    
    /**
     * Get all records by group (with caching)
     */
    public function getByGroup(string $group): Collection
    {
        return Cache::remember("master_group_{$group}", $this->cacheTtl, function () use ($group) {
            return Master::byGroup($group)->orderBy('m_name')->get();
        });
    }
    
    /**
     * Get options array for dropdown (id => name)
     */
    public function getOptions(string $group): array
    {
        return Cache::remember("master_options_{$group}", $this->cacheTtl, function () use ($group) {
            return Master::byGroup($group)
                ->orderBy('m_name')
                ->pluck('m_alias_name', 'm_name')
                ->toArray();
        });
    }
    
    /**
     * Get options with ID as key
     */
    public function getOptionsWithId(string $group): array
    {
        return Cache::remember("master_options_id_{$group}", $this->cacheTtl, function () use ($group) {
            return Master::byGroup($group)
                ->orderBy('m_name')
                ->pluck('m_alias_name', 'm_id')
                ->toArray();
        });
    }
    
    /**
     * Get records by group and type
     */
    public function getByGroupAndType(string $group, string $type): Collection
    {
        return Cache::remember("master_group_{$group}_type_{$type}", $this->cacheTtl, function () use ($group, $type) {
            return Master::byGroup($group)->byType($type)->orderBy('m_name')->get();
        });
    }
    
    /**
     * Get single value by group and key
     */
    public function getValue(string $group, string $key): ?string
    {
        return Master::getValue($group, $key);
    }
    
    /**
     * Get all active records by group
     */
    public function getActiveByGroup(string $group): Collection
    {
        return Cache::remember("master_group_{$group}_active", $this->cacheTtl, function () use ($group) {
            return Master::byGroup($group)->active()->orderBy('m_name')->get();
        });
    }
    
    /**
     * Create new master record
     */
    public function create(array $data): Master
    {
        $master = Master::create([
            'm_group' => strtoupper($data['m_group']),
            'm_name' => strtoupper($data['m_name']),
            'm_alias_name' => $data['m_alias_name'] ?? null,
            'm_type' => $data['m_type'] ?? 'active',
            'm_other' => $data['m_other'] ?? null,
            'm_description' => $data['m_description'] ?? null,
        ]);
        
        $this->clearCache($data['m_group']);
        return $master;
    }
    
    /**
     * Update master record
     */
    public function update(Master $master, array $data): Master
    {
        $updateData = [];
        
        if (isset($data['m_name'])) {
            $updateData['m_name'] = strtoupper($data['m_name']);
        }
        if (isset($data['m_alias_name'])) {
            $updateData['m_alias_name'] = $data['m_alias_name'];
        }
        if (isset($data['m_type'])) {
            $updateData['m_type'] = $data['m_type'];
        }
        if (isset($data['m_other'])) {
            $updateData['m_other'] = $data['m_other'];
        }
        if (isset($data['m_description'])) {
            $updateData['m_description'] = $data['m_description'];
        }
        
        $master->update($updateData);
        $this->clearCache($master->m_group);
        
        return $master;
    }
    
    /**
     * Delete master record
     */
    public function delete(Master $master): bool
    {
        $group = $master->m_group;
        $result = $master->delete();
        $this->clearCache($group);
        return $result;
    }
    
    /**
     * Clear cache for specific group
     */
    protected function clearCache(string $group): void
    {
        $groupUpper = strtoupper($group);
        Cache::forget("master_group_{$groupUpper}");
        Cache::forget("master_options_{$groupUpper}");
        Cache::forget("master_options_id_{$groupUpper}");
        Cache::forget("master_group_{$groupUpper}_active");
        Cache::forget("master_group_{$groupUpper}_type_active");
    }
    
    /**
     * Clear all master cache
     */
    public function clearAllCache(): void
    {
        $groups = Master::select('m_group')->distinct()->get();
        foreach ($groups as $group) {
            $this->clearCache($group->m_group);
        }
    }
    
    /**
     * Get all unique groups
     */
    public function getAllGroups(): Collection
    {
        return Cache::remember("master_all_groups", $this->cacheTtl, function () {
            return Master::select('m_group')->distinct()->orderBy('m_group')->get();
        });
    }
    
    /**
     * Get school types
     */
    public function getSchoolTypes(): array
    {
        return $this->getOptions('SCHOOL_TYPE');
    }
    
    /**
     * Get management types
     */
    public function getManagementTypes(): array
    {
        return $this->getOptions('MANAGEMENT_TYPE');
    }
    
    /**
     * Get affiliation boards
     */
    public function getAffiliationBoards(): array
    {
        return $this->getOptions('AFFILIATION_BOARD');
    }
    
    /**
     * Get all classes
     */
    public function getClasses(): array
    {
        return $this->getOptions('CLASS');
    }
    
    /**
     * Get streams
     */
    public function getStreams(): array
    {
        return $this->getOptions('STREAM');
    }
    
    /**
     * Get mediums
     */
    public function getMediums(): array
    {
        return $this->getOptions('MEDIUM');
    }
    
    /**
     * Get subscription plans
     */
    public function getSubscriptionPlans(): array
    {
        return $this->getOptions('SUBSCRIPTION_PLAN');
    }
    
    /**
     * Get genders
     */
    public function getGenders(): array
    {
        return $this->getOptions('GENDER');
    }
    
    /**
     * Get blood groups
     */
    public function getBloodGroups(): array
    {
        return $this->getOptions('BLOOD_GROUP');
    }
    
    /**
     * Get attendance status
     */
    public function getAttendanceStatus(): array
    {
        return $this->getOptions('ATTENDANCE_STATUS');
    }
    
    /**
     * Get leave types
     */
    public function getLeaveTypes(): array
    {
        return $this->getOptions('LEAVE_TYPE');
    }
    
    /**
     * Get fee frequencies
     */
    public function getFeeFrequencies(): array
    {
        return $this->getOptions('FEE_FREQUENCY');
    }
    
    /**
     * Get payment modes
     */
    public function getPaymentModes(): array
    {
        return $this->getOptions('PAYMENT_MODE');
    }
    
    /**
     * Get exam types
     */
    public function getExamTypes(): array
    {
        return $this->getOptions('EXAM_TYPE');
    }
    
    /**
     * Get religions
     */
    public function getReligions(): array
    {
        return $this->getOptions('RELIGION');
    }
    
    /**
     * Get categories
     */
    public function getCategories(): array
    {
        return $this->getOptions('CATEGORY');
    }
    
    /**
     * Get days of week
     */
    public function getDaysOfWeek(): array
    {
        return $this->getOptions('DAY');
    }
    
    /**
     * Get grades
     */
    public function getGrades(): array
    {
        return $this->getOptions('GRADE');
    }
    
    /**
     * Get status list
     */
    public function getStatuses(): array
    {
        return $this->getOptions('STATUS');
    }
}
