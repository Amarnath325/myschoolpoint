<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            // Basic Information
            'school_name' => 'required|string|max:255',
            'school_code' => 'required|string|unique:schools',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'school_type' => 'required|in:day,boarding,day_boarding',
            'management_type' => 'required|in:private,government,aided',
            
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
            'affiliation_board' => 'required|string',
            'affiliation_number' => 'nullable|string',
            'affiliation_status' => 'required|in:active,pending,expired',
            
            // Academic
            'classes_available' => 'nullable|array',
            'streams_available' => 'nullable|array',
            'medium_of_instruction' => 'nullable|array',
            
            // Infrastructure
            'has_labs' => 'boolean',
            'has_library' => 'boolean',
            'has_sports' => 'boolean',
            'has_hostel' => 'boolean',
            'has_transport' => 'boolean',
            
            // Subscription
            'subscription_plan' => 'required|in:free,basic,premium',
            
            // About
            'about_school' => 'nullable|string',
            
            // Files
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'affiliation_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Handle file uploads
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('schools/logos', 'public');
            }
            
            // Create School
            $school = School::create([
                // Basic Information
                'business_name' => $validated['school_name'],
                'school_code' => $validated['school_code'],
                'established_year' => $validated['established_year'] ?? null,
                'school_type' => $validated['school_type'],
                'management_type' => $validated['management_type'],
                
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
                'affiliation_board' => $validated['affiliation_board'],
                'affiliation_number' => $validated['affiliation_number'] ?? null,
                'affiliation_status' => $validated['affiliation_status'],
                
                // Academic
                'classes_available' => $validated['classes_available'] ?? [],
                'streams_available' => $validated['streams_available'] ?? [],
                'medium_of_instruction' => $validated['medium_of_instruction'] ?? ['english'],
                
                // Infrastructure
                'has_labs' => $validated['has_labs'] ?? false,
                'has_library' => $validated['has_library'] ?? false,
                'has_sports' => $validated['has_sports'] ?? false,
                'has_hostel' => $validated['has_hostel'] ?? false,
                'has_transport' => $validated['has_transport'] ?? false,
                
                // Subscription
                'subscription_plan' => $validated['subscription_plan'],
                'subscription_start_date' => now(),
                'subscription_end_date' => $validated['subscription_plan'] === 'free' ? now()->addDays(30) : now()->addYear(),
                
                // About
                'about_school' => $validated['about_school'] ?? null,
                
                // Status
                'status' => 'active',
                'logo' => $logoPath,
            ]);
            
            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                $galleryPaths = [];
                foreach ($request->file('gallery_images') as $image) {
                    $path = $image->store('schools/gallery', 'public');
                    $galleryPaths[] = $path;
                }
                $school->settings = array_merge($school->settings ?? [], ['gallery' => $galleryPaths]);
                $school->save();
            }
            
            // Handle certificates
            $certificates = [];
            if ($request->hasFile('affiliation_certificate')) {
                $certificates['affiliation'] = $request->file('affiliation_certificate')->store('schools/certificates', 'public');
            }
            if ($request->hasFile('registration_certificate')) {
                $certificates['registration'] = $request->file('registration_certificate')->store('schools/certificates', 'public');
            }
            if (!empty($certificates)) {
                $school->settings = array_merge($school->settings ?? [], ['certificates' => $certificates]);
                $school->save();
            }
            
            DB::commit();
            
            return response()->json([
                'message' => 'School registered successfully!',
                'school' => $school,
                'redirect' => '/login'
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Registration failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
