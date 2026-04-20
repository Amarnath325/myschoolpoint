<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use App\Models\Master;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // List all students
    public function index(Request $request)
    {
        $schoolId = $request->user()->school_id;
        
        $students = Student::where('school_id', $schoolId)
            ->with(['user', 'class'])
            ->orderBy('id', 'desc')
            ->paginate(20);
        
        return StudentResource::collection($students);
    }
    
    // Create new student
    public function store(StudentRequest $request)
    {
        $schoolId = $request->user()->school_id;
        
        DB::beginTransaction();
        
        try {
            // Get current academic year
            $academicYear = AcademicYear::where('school_id', $schoolId)
                ->where('is_current', true)
                ->first();
            
            if (!$academicYear) {
                return response()->json(['message' => 'No active academic year found'], 400);
            }
            
            // Create user
            $user = User::create([
                'school_id' => $schoolId,
                'user_type' => Master::where('m_group', 'USER_TYPE')
                    ->where('m_alias_name', 'student')
                    ->first()?->m_id ?? 4,  // Default to 4 if not found
                'username' => $request->email,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make('password123'), // Send via email
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'date_of_birth' => $request->date_of_birth,
                'is_active' => true,
            ]);
            
            // Create student
            $student = Student::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'class_id' => $request->class_id,
                'academic_year_id' => $academicYear->id,
                'admission_number' => $request->admission_number,
                'roll_number' => $request->roll_number ?? $this->generateRollNumber($schoolId, $request->class_id),
                'admission_date' => now(),
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'parent_email' => $request->parent_email,
                'parent_phone' => $request->parent_phone,
                'blood_group' => $request->blood_group,
            ]);
            
            DB::commit();
            
            return new StudentResource($student->load('user', 'class'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create student', 'error' => $e->getMessage()], 500);
        }
    }
    
    // Get single student
    public function show(Student $student)
    {
        $this->authorizeSchoolAccess($student);
        return new StudentResource($student->load('user', 'class', 'attendances', 'marks'));
    }
    
    // Update student
    public function update(StudentRequest $request, Student $student)
    {
        $this->authorizeSchoolAccess($student);
        
        DB::beginTransaction();
        
        try {
            // Update user
            $student->user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile' => $request->mobile,
                'date_of_birth' => $request->date_of_birth,
            ]);
            
            // Update student
            $student->update([
                'class_id' => $request->class_id,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'parent_email' => $request->parent_email,
                'parent_phone' => $request->parent_phone,
                'blood_group' => $request->blood_group,
            ]);
            
            DB::commit();
            
            return new StudentResource($student->load('user', 'class'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update student'], 500);
        }
    }
    
    // Delete student
    public function destroy(Student $student)
    {
        $this->authorizeSchoolAccess($student);
        
        DB::beginTransaction();
        
        try {
            $student->user->delete();
            $student->delete();
            
            DB::commit();
            
            return response()->json(['message' => 'Student deleted successfully']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete student'], 500);
        }
    }
    
    // Student Dashboard
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        
        $data = [
            'student' => new StudentResource($student->load('class')),
            'attendance_summary' => [
                'total_days' => $student->attendances()->count(),
                'present_days' => $student->attendances()->where('status', 'present')->count(),
                'absent_days' => $student->attendances()->where('status', 'absent')->count(),
                'percentage' => $student->getAttendancePercentage(),
            ],
            'recent_marks' => $student->marks()->with(['exam', 'subject'])->latest()->take(10)->get(),
            'upcoming_exams' => \App\Models\Exam::where('class_id', $student->class_id)
                ->where('start_date', '>=', now())
                ->orderBy('start_date')
                ->take(5)
                ->get(),
            'fee_summary' => [
                'total_fees' => $student->feePayments()->sum('amount'),
                'paid_fees' => $student->feePayments()->where('payment_status', 'paid')->sum('amount'),
                'pending_fees' => $student->feePayments()->where('payment_status', 'pending')->sum('amount'),
            ],
        ];
        
        return response()->json($data);
    }
    
    // Promote students to next class
    public function promote(Request $request, Student $student)
    {
        $request->validate([
            'new_class_id' => 'required|exists:classes,id',
            'new_academic_year_id' => 'required|exists:academic_years,id',
        ]);
        
        // Create academic history
        \App\Models\StudentAcademicHistory::create([
            'student_id' => $student->id,
            'school_id' => $student->school_id,
            'academic_year_id' => $student->academic_year_id,
            'class_id' => $student->class_id,
            'roll_number' => $student->roll_number,
            'is_promoted' => true,
            'promoted_to_class_id' => $request->new_class_id,
            'result' => 'Promoted',
        ]);
        
        // Update student
        $student->update([
            'class_id' => $request->new_class_id,
            'academic_year_id' => $request->new_academic_year_id,
        ]);
        
        return response()->json(['message' => 'Student promoted successfully']);
    }
    
    // Helper method
    private function generateRollNumber($schoolId, $classId)
    {
        $lastStudent = Student::where('school_id', $schoolId)
            ->where('class_id', $classId)
            ->orderBy('id', 'desc')
            ->first();
        
        $lastNumber = $lastStudent ? intval(substr($lastStudent->roll_number, -3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        
        return date('Y') . $classId . $newNumber;
    }
    
    // Authorization check
    private function authorizeSchoolAccess($student)
    {
        if (auth()->user()->school_id !== $student->school_id && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access');
        }
    }
}
