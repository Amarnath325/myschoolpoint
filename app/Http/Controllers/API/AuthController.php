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
            // School Information
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|unique:schools,email',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'affiliation_board' => 'nullable|string|max:100',
            'school_type' => 'required|in:day,boarding,day_boarding',
            'gender_type' => 'required|in:coed,boys,girls',
            
            // Address
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
            
            // Admin Information
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_phone' => 'required|string|max:20',
            'admin_password' => 'required|min:6|confirmed',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create School
            $school = School::create([
                'business_name' => $validated['business_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'mobile' => $validated['mobile'],
                'website' => $validated['website'] ?? null,
                'established_year' => $validated['established_year'] ?? null,
                'affiliation_board' => $validated['affiliation_board'] ?? null,
                'school_type' => $validated['school_type'],
                'gender_type' => $validated['gender_type'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'country' => $validated['country'],
                'pincode' => $validated['pincode'],
                'status' => 'active',
                'subscription_plan' => 'trial',
                'subscription_end_date' => now()->addDays(30),
            ]);
            
            // Create Admin User
            $admin = User::create([
                'school_id' => $school->id,
                'user_type' => 'school_admin',
                'username' => $validated['admin_email'],
                'email' => $validated['admin_email'],
                'mobile' => $validated['admin_phone'],
                'password' => Hash::make($validated['admin_password']),
                'first_name' => $validated['admin_name'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            // Assign role if role system is implemented
            if (class_exists('Spatie\Permission\Models\Role')) {
                $admin->assignRole('school_admin');
            }
            
            DB::commit();
            
            return response()->json([
                'message' => 'School registered successfully!',
                'school' => $school,
                'admin' => $admin,
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Registration failed!',
                'error' => $e->getMessage()
            ], 500);
        }
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
