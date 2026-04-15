<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Services\MasterService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterController extends Controller
{
    protected $masterService;
    
    public function __construct(MasterService $masterService)
    {
        $this->masterService = $masterService;
    }
    
    /**
     * Get all records by group
     */
    public function getByGroup($group)
    {
        try {
            $records = $this->masterService->getByGroup($group);
            
            return response()->json([
                'success' => true,
                'data' => $records,
                'group' => strtoupper($group),
                'total' => $records->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch records',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get options for dropdown
     */
    public function getOptions($group)
    {
        try {
            $options = $this->masterService->getOptions($group);
            
            return response()->json([
                'success' => true,
                'data' => $options,
                'group' => strtoupper($group)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch options',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get options with ID as key
     */
    public function getOptionsWithId($group)
    {
        try {
            $options = $this->masterService->getOptionsWithId($group);
            
            return response()->json([
                'success' => true,
                'data' => $options,
                'group' => strtoupper($group)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch options',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all groups
     */
    public function getGroups()
    {
        try {
            $groups = $this->masterService->getAllGroups();
            
            return response()->json([
                'success' => true,
                'data' => $groups,
                'total' => $groups->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch groups',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create new master record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'm_group' => 'required|string|max:100',
            'm_name' => 'required|string|max:255|unique:masters,m_name,NULL,m_id,m_group,' . strtoupper($request->m_group),
            'm_alias_name' => 'nullable|string|max:255',
            'm_type' => 'nullable|string|max:50',
            'm_other' => 'nullable|array',
            'm_description' => 'nullable|string',
        ]);
        
        try {
            $master = $this->masterService->create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Master record created successfully',
                'data' => $master
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update master record
     */
    public function update(Request $request, Master $master)
    {
        $validated = $request->validate([
            'm_name' => 'sometimes|string|max:255|unique:masters,m_name,' . $master->m_id . ',m_id,m_group,' . $master->m_group,
            'm_alias_name' => 'nullable|string|max:255',
            'm_type' => 'nullable|string|max:50',
            'm_other' => 'nullable|array',
            'm_description' => 'nullable|string',
        ]);
        
        try {
            $master = $this->masterService->update($master, $validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Master record updated successfully',
                'data' => $master
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete master record
     */
    public function destroy(Master $master)
    {
        try {
            $this->masterService->delete($master);
            
            return response()->json([
                'success' => true,
                'message' => 'Master record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete record',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Show single master record
     */
    public function show(Master $master)
    {
        return response()->json([
            'success' => true,
            'data' => $master
        ]);
    }
    
    /**
     * Clear all master cache
     */
    public function clearCache()
    {
        try {
            $this->masterService->clearAllCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // ============ SPECIFIC GROUP ENDPOINTS ============
    
    public function getSchoolTypes()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getSchoolTypes()
        ]);
    }
    
    public function getManagementTypes()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getManagementTypes()
        ]);
    }
    
    public function getAffiliationBoards()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getAffiliationBoards()
        ]);
    }
    
    public function getClasses()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getClasses()
        ]);
    }
    
    public function getStreams()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getStreams()
        ]);
    }
    
    public function getMediums()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getMediums()
        ]);
    }
    
    public function getSubscriptionPlans()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getSubscriptionPlans()
        ]);
    }
    
    public function getGenders()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getGenders()
        ]);
    }
    
    public function getBloodGroups()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getBloodGroups()
        ]);
    }
    
    public function getAttendanceStatus()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getAttendanceStatus()
        ]);
    }
    
    public function getLeaveTypes()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getLeaveTypes()
        ]);
    }
    
    public function getFeeFrequencies()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getFeeFrequencies()
        ]);
    }
    
    public function getPaymentModes()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getPaymentModes()
        ]);
    }
    
    public function getExamTypes()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getExamTypes()
        ]);
    }
    
    public function getReligions()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getReligions()
        ]);
    }
    
    public function getCategories()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getCategories()
        ]);
    }
    
    public function getDaysOfWeek()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getDaysOfWeek()
        ]);
    }
    
    public function getGrades()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getGrades()
        ]);
    }
    
    public function getStatuses()
    {
        return response()->json([
            'success' => true,
            'data' => $this->masterService->getStatuses()
        ]);
    }
}
