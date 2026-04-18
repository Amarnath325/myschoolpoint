<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenus()
    {
        try {
            $menus = Menu::active()
                ->orderBy('menu_sequence')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $menus,
                'message' => 'Menus fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch menus',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getParentMenus()
    {
        try {
            $menus = Menu::parentMenus()->get();
            
            return response()->json([
                'success' => true,
                'data' => $menus,
                'message' => 'Parent menus fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch parent menus',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getSubMenus($parentId)
    {
        try {
            $menus = Menu::where('menu_p_id', $parentId)
                ->active()
                ->orderBy('menu_sequence')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $menus,
                'message' => 'Sub menus fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sub menus',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
