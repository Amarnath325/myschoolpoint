<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    public function getSchoolTypes()
    {
        $schoolTypes = Master::getByGroup('SCHOOL_TYPE');
        return response()->json([
            'status' => true,
            'message' => 'School type data fetched successfully',
            'result' => $schoolTypes
        ]);
    }

    public function getManagementTypes()
    {
        $managementTypes = Master::getByGroup('MANAGEMENT_TYPE');
        return response()->json([
            'status' => true,
            'message' => 'Management type data fetched successfully',
            'result' => $managementTypes
        ]);
    }

    public function getAffiliationBoards()
    {
        $affiliationBoards = Master::getByGroup('AFFILIATION_BOARD');
        return response()->json([
            'status' => true,
            'message' => 'Affiliation board data fetched successfully',
            'result' => $affiliationBoards
        ]);
    }

    public function getStatus()
    {
        $statuses = Master::getByGroup('STATUS');
        return response()->json([
            'status' => true,
            'message' => 'Status data fetched successfully',
            'result' => $statuses
        ]);
    }

    public function getClass()
    {
        $classes = Master::getByGroup('CLASS');
        return response()->json([
            'status' => true,
            'message' => 'Class data fetched successfully',
            'result' => $classes
        ]);
    }

    public function getStream()
    {
        $streams = Master::getByGroup('STREAM');
        return response()->json([
            'status' => true,
            'message' => 'Stream data fetched successfully',
            'result' => $streams
        ]);
    }

    public function getMedium()
    {
        $medium = Master::getByGroup('MEDIUM');
        return response()->json([
            'status' => true,
            'message' => 'Medium data fetched successfully',
            'result' => $medium
        ]);
    }

    public function getSubscriptionPlan()
    {
        $subscriptionPlans = Master::getByGroup('SUBSCRIPTION_PLAN');
        return response()->json([
            'status' => true,
            'message' => 'Subscription plan data fetched successfully',
            'result' => $subscriptionPlans
        ]);
    }

    public function register(Request $request)
    {
        // Get valid values from Master data for dynamic validation
        // Frontend now sends m_id as the value (numeric IDs)
        $schoolTypes = Master::getByGroup('SCHOOL_TYPE')->pluck('m_id')->toArray();
        $managementTypes = Master::getByGroup('MANAGEMENT_TYPE')->pluck('m_id')->toArray();
        $affiliationBoards = Master::getByGroup('AFFILIATION_BOARD')->pluck('m_id')->toArray();
        $classes = Master::getByGroup('CLASS')->pluck('m_id')->toArray();
        $streams = Master::getByGroup('STREAM')->pluck('m_id')->toArray();
        $mediums = Master::getByGroup('MEDIUM')->pluck('m_id')->toArray();
        $subscriptionPlans = Master::getByGroup('SUBSCRIPTION_PLAN')->pluck('m_id')->toArray();
        
        // Get affiliation statuses - support both m_id values and hardcoded strings for backward compatibility
        $affiliationStatusMasters = Master::getByGroup('AFFILIATION_STATUS')->pluck('m_id')->toArray();
        // If Master data exists, use m_ids; otherwise accept any values and let the field store as integer
        $affiliationStatuses = !empty($affiliationStatusMasters) 
            ? $affiliationStatusMasters 
            : '1,2,3'; // Default numeric values if master data not present
        
        // Convert affiliationStatuses array to string for 'in' validation rule
        if (is_array($affiliationStatuses)) {
            $affiliationStatuses = implode(',', array_map('strval', $affiliationStatuses));
        }
        
        $validated = $request->validate([
            // Basic Information
            'school_name' => 'required|string|max:255',
            'school_code' => 'required|string|unique:schools',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'school_type' => 'required|in:' . implode(',', $schoolTypes),
            'management_type' => 'required|in:' . implode(',', $managementTypes),
            
            // Location
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
            'full_address' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            
            // Contact
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|unique:schools',
            'website' => 'nullable|url',
            
            // Affiliation
            'affiliation_board' => 'required|in:' . implode(',', $affiliationBoards),
            'affiliation_number' => 'nullable|string',
            'affiliation_status' => 'required|in:' . $affiliationStatuses,
            
            // Academic - Laravel automatically parses bracket notation into arrays
            'classes_available' => 'nullable|array',
            'classes_available.*' => 'in:' . implode(',', $classes),
            'streams_available' => 'nullable|array',
            'streams_available.*' => 'in:' . implode(',', $streams),
            'medium_of_instruction' => 'nullable|array',
            'medium_of_instruction.*' => 'in:' . implode(',', $mediums),
            
            // Infrastructure
            'has_labs' => 'boolean',
            'has_library' => 'boolean',
            'has_sports' => 'boolean',
            'has_hostel' => 'boolean',
            'has_transport' => 'boolean',
            
            // Subscription
            'subscription_plan' => 'required|in:' . implode(',', $subscriptionPlans),
            
            // About
            'about_school' => 'nullable|string',
            
            // Files
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'affiliation_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        // Convert array values - store as comma-separated m_ids (NOT JSON)
        $validated['classes_available'] = $validated['classes_available'] ? implode(',', $validated['classes_available']) : '';
        $validated['streams_available'] = $validated['streams_available'] ? implode(',', $validated['streams_available']) : '';
        $validated['medium_of_instruction'] = $validated['medium_of_instruction'] ? implode(',', $validated['medium_of_instruction']) : '';
        
        // Ensure affiliation_status is stored as integer
        $validated['affiliation_status'] = (int)$validated['affiliation_status'];
        
        // All other values (school_type, management_type, affiliation_board, subscription_plan, affiliation_status) 
        // are already m_id values and will be stored as integers directly
        
        DB::beginTransaction();
        
        try {
            // Handle file uploads
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('schools/logos', 'public');
            }
            
            // Create School - store m_id values and JSON arrays directly
            $school = School::create([
                // Basic Information
                'business_name' => $validated['school_name'],
                'school_code' => $validated['school_code'],
                'established_year' => $validated['established_year'] ?? null,
                'school_type' => $validated['school_type'],           // m_id (int)
                'management_type' => $validated['management_type'],   // m_id (int)
                
                // Location
                'country' => $validated['country'],
                'state' => $validated['state'],
                'city' => $validated['city'],
                'pincode' => $validated['pincode'],
                'full_address' => $validated['full_address'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                
                // Contact
                'contact_number' => $validated['contact_number'],
                'email' => $validated['email'],
                'website' => $validated['website'] ?? null,
                
                // Affiliation
                'affiliation_board' => $validated['affiliation_board'],       // m_id (int)
                'affiliation_number' => $validated['affiliation_number'] ?? null,
                'affiliation_status' => $validated['affiliation_status'],   // m_id (int)
                
                // Academic - stored as JSON
                'classes_available' => $validated['classes_available'],
                'streams_available' => $validated['streams_available'],
                'medium_of_instruction' => $validated['medium_of_instruction'],
                
                // Infrastructure
                'has_labs' => $validated['has_labs'] ?? false,
                'has_library' => $validated['has_library'] ?? false,
                'has_sports' => $validated['has_sports'] ?? false,
                'has_hostel' => $validated['has_hostel'] ?? false,
                'has_transport' => $validated['has_transport'] ?? false,
                
                // Subscription
                'subscription_plan' => $validated['subscription_plan'],      // m_id (int)
                'subscription_start_date' => now(),
                'subscription_end_date' => now()->addYear(),
                
                // About
                'about_school' => $validated['about_school'] ?? null,
                
                // Status
                'status' => 1,
                'school_logo' => $logoPath,
            ]);
            
            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                $galleryPaths = [];
                foreach ($request->file('gallery_images') as $image) {
                    $path = $image->store('schools/gallery', 'public');
                    $galleryPaths[] = $path;
                }
                $school->school_gallery = json_encode($galleryPaths);
                $school->save();
            }
            
            // Handle certificates
            $certificatePaths = [];
            if ($request->hasFile('affiliation_certificate')) {
                $certificatePaths['affiliation'] = $request->file('affiliation_certificate')->store('schools/certificates', 'public');
            }
            if ($request->hasFile('registration_certificate')) {
                $certificatePaths['registration'] = $request->file('registration_certificate')->store('schools/certificates', 'public');
            }
            if (!empty($certificatePaths)) {
                $school->affiliate_certificate = $certificatePaths['affiliation'] ?? null;
                $school->registration_certificate = $certificatePaths['registration'] ?? null;
                $school->save();
            }
            
            DB::commit();
            
            // Get school_admin m_id from Master table
            $adminUserType = Master::where('m_group', 'USER_TYPE')
                ->where('m_alias_name', 'school_admin')
                ->first();
            $userTypeId = $adminUserType ? $adminUserType->m_id : 2; // Default to 2 if not found
            
            // Create admin user for this school with auto-login
            $adminUser = User::create([
                'school_id' => $school->school_id,
                'user_type' => $userTypeId,  // Store m_id (integer), not string
                'username' => strtolower(str_replace(' ', '_', $validated['school_name'])) . '_admin',
                'email' => $validated['email'],
                'mobile' => $validated['contact_number'],
                'password' => Hash::make('Admin@' . $school->school_code),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'is_active' => true,
            ]);
            
            // Generate auth token for auto-login
            $token = $adminUser->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'message' => 'School registered successfully!',
                'school' => $school,
                'user' => $adminUser,
                'token' => $token,
                'redirect' => '/dashboard'
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Registration failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
