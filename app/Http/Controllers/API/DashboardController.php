<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\Exam;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            // If user is super admin
            if ($user->user_type === 'super_admin') {
                return $this->superAdminDashboard();
            }
            
            // For school admin, teacher, student
            $schoolId = $user->school_id;
            
            if (!$schoolId) {
                return response()->json([
                    'success' => true,
                    'data' => $this->getEmptyStats(),
                    'message' => 'No school assigned'
                ]);
            }
            
            $data = [
                'total_students' => Student::where('school_id', $schoolId)->count(),
                'total_teachers' => Teacher::where('school_id', $schoolId)->count(),
                'total_classes' => Classes::where('school_id', $schoolId)->count(),
                'total_parents' => Student::where('school_id', $schoolId)
                    ->whereNotNull('parent_phone')
                    ->distinct('parent_phone')
                    ->count('parent_phone'),
                'monthly_revenue' => (float) FeePayment::where('school_id', $schoolId)
                    ->whereMonth('payment_date', now()->month)
                    ->where('payment_status', 'paid')
                    ->sum('amount'),
                'attendance_today' => $this->getTodayAttendance($schoolId),
                'pending_fees' => (float) FeePayment::where('school_id', $schoolId)
                    ->where('payment_status', 'pending')
                    ->sum('amount'),
                'upcoming_exams' => Exam::where('school_id', $schoolId)
                    ->where('start_date', '>=', now())
                    ->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Dashboard data fetched successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => $this->getEmptyStats(),
                'message' => $e->getMessage()
            ]);
        }
    }
    
    private function getTodayAttendance($schoolId)
    {
        $today = now()->toDateString();
        $totalStudents = Student::where('school_id', $schoolId)->count();
        
        if ($totalStudents == 0) {
            return 0;
        }
        
        $presentToday = Attendance::where('school_id', $schoolId)
            ->where('date', $today)
            ->where('status', 'present')
            ->count();
        
        return round(($presentToday / $totalStudents) * 100, 2);
    }
    
    private function getEmptyStats()
    {
        return [
            'total_students' => 0,
            'total_teachers' => 0,
            'total_classes' => 0,
            'total_parents' => 0,
            'monthly_revenue' => 0,
            'attendance_today' => 0,
            'pending_fees' => 0,
            'upcoming_exams' => 0,
        ];
    }
    
    private function superAdminDashboard()
    {
        $data = [
            'total_schools' => School::count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_revenue' => (float) FeePayment::where('payment_status', 'paid')->sum('amount'),
            'active_subscriptions' => School::where('status', 'active')->count(),
            'pending_approvals' => School::where('status', 'inactive')->count(),
            'total_classes' => Classes::count(),
            'monthly_revenue' => (float) FeePayment::whereMonth('payment_date', now()->month)
                ->where('payment_status', 'paid')
                ->sum('amount'),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'user_type' => 'super_admin',
            'message' => 'Super admin dashboard data fetched successfully'
        ]);
    }
    
    public function stats(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id;
            
            if ($user->user_type === 'super_admin') {
                $stats = [
                    'students' => Student::count(),
                    'teachers' => Teacher::count(),
                    'classes' => Classes::count(),
                    'schools' => School::count(),
                    'revenue' => (float) FeePayment::where('payment_status', 'paid')->sum('amount'),
                ];
            } else {
                $stats = [
                    'students' => Student::where('school_id', $schoolId)->count(),
                    'teachers' => Teacher::where('school_id', $schoolId)->count(),
                    'classes' => Classes::where('school_id', $schoolId)->count(),
                    'revenue' => (float) FeePayment::where('school_id', $schoolId)
                        ->where('payment_status', 'paid')
                        ->sum('amount'),
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function recentActivities(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id;
            
            $activities = [];
            
            if ($user->user_type !== 'super_admin' && $schoolId) {
                // Recent students
                $recentStudents = Student::where('school_id', $schoolId)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function($student) {
                        return [
                            'id' => $student->id,
                            'type' => 'student',
                            'title' => 'New Student Added',
                            'description' => ($student->user->first_name ?? '') . ' ' . ($student->user->last_name ?? '') . ' was added',
                            'created_at' => $student->created_at,
                        ];
                    });
                
                // Recent teachers
                $recentTeachers = Teacher::where('school_id', $schoolId)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function($teacher) {
                        return [
                            'id' => $teacher->id,
                            'type' => 'teacher',
                            'title' => 'New Teacher Added',
                            'description' => ($teacher->user->first_name ?? '') . ' ' . ($teacher->user->last_name ?? '') . ' joined',
                            'created_at' => $teacher->created_at,
                        ];
                    });
                
                // Recent fee payments
                $recentPayments = FeePayment::where('school_id', $schoolId)
                    ->with('student.user')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function($payment) {
                        return [
                            'id' => $payment->id,
                            'type' => 'fee',
                            'title' => 'Fee Payment Received',
                            'description' => '₹' . number_format($payment->amount, 2) . ' received from ' . ($payment->student->user->first_name ?? 'Student'),
                            'created_at' => $payment->created_at,
                        ];
                    });
                
                $activities = $recentStudents->concat($recentTeachers)->concat($recentPayments)
                    ->sortByDesc('created_at')
                    ->take(10)
                    ->values();
            }
            
            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function upcomingEvents(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id;
            
            $events = [];
            
            if ($user->user_type !== 'super_admin' && $schoolId) {
                $exams = Exam::where('school_id', $schoolId)
                    ->where('start_date', '>=', now())
                    ->orderBy('start_date', 'asc')
                    ->limit(5)
                    ->get()
                    ->map(function($exam) {
                        return [
                            'id' => $exam->id,
                            'title' => $exam->name,
                            'date' => $exam->start_date,
                            'type' => 'Exam',
                        ];
                    });
                
                $events = $exams->values();
            }
            
            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage()
            ]);
        }
    }
}
