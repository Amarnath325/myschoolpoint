<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = $request->user();
        
        if (!$user || !in_array($user->user_type, $types)) {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        return $next($request);
    }
}