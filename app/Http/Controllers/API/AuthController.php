<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register new school
    public function registerSchool(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|unique:schools',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'admin_name' => 'required|string',
            'admin_email' => 'required|email|unique:users',
            'admin_password' => 'required|min:6',
        ]);
        
        // Create school
        $school = School::create([
            'business_name' => $validated['business_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => $validated['country'],
            'status' => 'active',
            'subscription_plan' => 'trial',
            'subscription_end_date' => now()->addDays(30),
        ]);
        
        // Create school admin
        $admin = User::create([
            'school_id' => $school->id,
            'user_type' => 'school_admin',
            'username' => $validated['admin_email'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'first_name' => $validated['admin_name'],
            'is_active' => true,
        ]);
        
        return response()->json([
            'message' => 'School registered successfully',
            'school' => $school,
            'admin' => $admin,
        ], 201);
    }
    
    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'user_type' => $user->user_type,
            'school_id' => $user->school_id,
        ]);
    }
    
    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Logged out successfully']);
    }
    
    // Get authenticated user
    public function user(Request $request)
    {
        return response()->json($request->user()->load('school'));
    }
}
