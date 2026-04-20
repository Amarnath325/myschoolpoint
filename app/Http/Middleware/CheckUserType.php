<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Master;

class CheckUserType
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized - User not found'
            ], 403);
        }
        
        // Convert string aliases to m_id values
        $userTypeIds = [];
        foreach ($types as $type) {
            // Check if it's an alias (string) and convert to m_id
            $master = Master::where('m_group', 'USER_TYPE')
                ->where('m_alias_name', $type)
                ->first();
            
            if ($master) {
                $userTypeIds[] = $master->m_id;
            } else {
                // If not found in Master, treat as numeric m_id
                $userTypeIds[] = (int)$type;
            }
        }
        
        // Check if user's type is in allowed types
        if (!in_array($user->user_type, $userTypeIds)) {
            return response()->json([
                'message' => 'Unauthorized - User type not allowed',
                'user_type' => $user->user_type,
                'allowed' => $userTypeIds
            ], 403);
        }
        
        return $next($request);
    }
}