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
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    
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
        
        // Students
        Route::get('students', [StudentController::class, 'index']);
        Route::post('students', [StudentController::class, 'store']);
        Route::get('students/{student}', [StudentController::class, 'show']);
        Route::put('students/{student}', [StudentController::class, 'update']);
        Route::delete('students/{student}', [StudentController::class, 'destroy']);
        
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
