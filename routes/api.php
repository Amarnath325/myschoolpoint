<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SchoolController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\API\ClassController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\ExamController;
use App\Http\Controllers\API\FeeController;
use App\Http\Controllers\API\MasterController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\DashboardController;

// Add CORS headers directly in route
Route::options('/{any}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', 'http://localhost:5173')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->header('Access-Control-Allow-Credentials', 'true');
})->where('any', '.*');

Route::get('/test', function () {
    return response()->json([
        'message' => 'Laravel backend is working!',
        'status' => 'success'
    ])->header('Access-Control-Allow-Origin', 'http://localhost:5173');
});

// Public routes
Route::post('register', [AuthController::class, 'registerSchool']);
Route::post('school/register', [SchoolController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Public Master Routes (For registration forms)
Route::prefix('master')->group(function () {
    // Get options for dropdowns
    Route::get('school-types', [MasterController::class, 'getSchoolTypes']);
    Route::get('management-types', [MasterController::class, 'getManagementTypes']);
    Route::get('affiliation-boards', [MasterController::class, 'getAffiliationBoards']);
    Route::get('affiliation-statuses', [MasterController::class, 'getAffiliationStatuses']);
    Route::get('classes', [MasterController::class, 'getClasses']);
    Route::get('streams', [MasterController::class, 'getStreams']);
    Route::get('mediums', [MasterController::class, 'getMediums']);
    Route::get('subscription-plans', [MasterController::class, 'getSubscriptionPlans']);
    Route::get('genders', [MasterController::class, 'getGenders']);
    Route::get('blood-groups', [MasterController::class, 'getBloodGroups']);
    Route::get('religions', [MasterController::class, 'getReligions']);
    Route::get('categories', [MasterController::class, 'getCategories']);
    
    // Generic endpoints
    Route::get('options/{group}', [MasterController::class, 'getOptions']);
    Route::get('options-id/{group}', [MasterController::class, 'getOptionsWithId']);
    Route::get('group/{group}', [MasterController::class, 'getByGroup']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    Route::get('/menus', [MenuController::class, 'getMenus']);
    Route::get('/menus/parent', [MenuController::class, 'getParentMenus']);
    Route::get('/menus/{parentId}/sub', [MenuController::class, 'getSubMenus']);

    // ============ DASHBOARD ROUTES (Add these) ============
    // Dashboard routes - accessible to all authenticated users
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('dashboard/recent-activities', [DashboardController::class, 'recentActivities']);
    Route::get('dashboard/upcoming-events', [DashboardController::class, 'upcomingEvents']);
    
    // Super Admin only routes
    Route::middleware('user_type:super_admin')->prefix('super-admin')->group(function () {
        Route::get('schools', [SchoolController::class, 'index']);
        Route::post('schools', [SchoolController::class, 'store']);
        Route::put('schools/{school}', [SchoolController::class, 'update']);
        Route::delete('schools/{school}', [SchoolController::class, 'destroy']);
        Route::get('dashboard/stats', [SchoolController::class, 'dashboardStats']);
    });
    
    // School Admin routes
    Route::middleware('user_type:school_admin')->prefix('school-admin')->group(function () {
        // Dashboard
        Route::get('dashboard', [SchoolController::class, 'dashboard']);
        
        // Teachers
        Route::get('teachers', [TeacherController::class, 'index']);
        Route::post('teachers', [TeacherController::class, 'store']);
        Route::get('teachers/{teacher}', [TeacherController::class, 'show']);
        Route::put('teachers/{teacher}', [TeacherController::class, 'update']);
        
        // Classes
        Route::get('classes', [ClassController::class, 'index']);
        Route::post('classes', [ClassController::class, 'store']);
        Route::get('classes/{class}', [ClassController::class, 'show']);
        Route::put('classes/{class}', [ClassController::class, 'update']);
        
        // Attendance
        Route::get('attendance/report', [AttendanceController::class, 'report']);
        
        // Exams
        Route::get('exams', [ExamController::class, 'index']);
        Route::post('exams', [ExamController::class, 'store']);
        
        // Fees
        Route::get('fees/collection', [FeeController::class, 'collectionReport']);
    });
    
    // Student routes (accessible by authenticated school_admin users)
    Route::middleware('user_type:school_admin')->group(function () {
        Route::get('students', [StudentController::class, 'index']);
        Route::post('students', [StudentController::class, 'store']);
        Route::get('students/{student}', [StudentController::class, 'show']);
        Route::put('students/{student}', [StudentController::class, 'update']);
        Route::delete('students/{student}', [StudentController::class, 'destroy']);
    });
    
    // Teacher routes
    Route::middleware('user_type:teacher')->prefix('teacher')->group(function () {
        Route::get('my-classes', [TeacherController::class, 'myClasses']);
        Route::get('my-students', [TeacherController::class, 'myStudents']);
        Route::post('attendance/mark', [AttendanceController::class, 'markAttendance']);
        Route::post('marks/enter', [ExamController::class, 'enterMarks']);
        Route::get('timetable', [TeacherController::class, 'myTimetable']);
    });
    
    // Student routes
    Route::middleware('user_type:student')->prefix('student')->group(function () {
        Route::get('dashboard', [StudentController::class, 'dashboard']);
        Route::get('attendance', [AttendanceController::class, 'myAttendance']);
        Route::get('marks', [ExamController::class, 'myMarks']);
        Route::get('fees', [FeeController::class, 'myFees']);
        Route::get('timetable', [StudentController::class, 'myTimetable']);
    });
});
