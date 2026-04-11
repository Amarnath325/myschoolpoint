<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\StudentAcademicHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentService
{
    /**
     * Create a new student with user account
     */
    public function createStudent(array $data, int $schoolId): Student
    {
        DB::beginTransaction();
        
        try {
            // Get current academic year
            $academicYear = AcademicYear::where('school_id', $schoolId)
                ->where('is_current', true)
                ->first();
            
            if (!$academicYear) {
                throw new \Exception('No active academic year found. Please create an academic year first.');
            }
            
            // Create user account
            $user = User::create([
                'school_id' => $schoolId,
                'user_type' => 'student',
                'username' => $data['email'],
                'email' => $data['email'],
                'mobile' => $data['mobile'] ?? null,
                'password' => Hash::make($data['password'] ?? 'password123'),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'country' => $data['country'] ?? null,
                'pincode' => $data['pincode'] ?? null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            // Generate roll number if not provided
            $rollNumber = $data['roll_number'] ?? $this->generateRollNumber($schoolId, $data['class_id']);
            
            // Create student record
            $student = Student::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'class_id' => $data['class_id'],
                'academic_year_id' => $academicYear->id,
                'admission_number' => $data['admission_number'],
                'roll_number' => $rollNumber,
                'admission_date' => $data['admission_date'] ?? now(),
                'father_name' => $data['father_name'] ?? null,
                'mother_name' => $data['mother_name'] ?? null,
                'parent_email' => $data['parent_email'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
                'blood_group' => $data['blood_group'] ?? null,
                'medical_info' => $data['medical_info'] ?? null,
                'transport_required' => $data['transport_required'] ?? false,
                'hostel_required' => $data['hostel_required'] ?? false,
                'previous_school' => $data['previous_school'] ?? null,
            ]);
            
            DB::commit();
            
            return $student->load(['user', 'class']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student creation failed: ' . $e->getMessage());
            throw new \Exception('Failed to create student: ' . $e->getMessage());
        }
    }
    
    /**
     * Update existing student
     */
    public function updateStudent(Student $student, array $data): Student
    {
        DB::beginTransaction();
        
        try {
            // Update user information
            if (isset($data['first_name']) || isset($data['last_name']) || isset($data['mobile']) || isset($data['date_of_birth'])) {
                $student->user->update([
                    'first_name' => $data['first_name'] ?? $student->user->first_name,
                    'last_name' => $data['last_name'] ?? $student->user->last_name,
                    'mobile' => $data['mobile'] ?? $student->user->mobile,
                    'date_of_birth' => $data['date_of_birth'] ?? $student->user->date_of_birth,
                    'gender' => $data['gender'] ?? $student->user->gender,
                    'address' => $data['address'] ?? $student->user->address,
                    'city' => $data['city'] ?? $student->user->city,
                    'state' => $data['state'] ?? $student->user->state,
                    'country' => $data['country'] ?? $student->user->country,
                    'pincode' => $data['pincode'] ?? $student->user->pincode,
                ]);
            }
            
            // Update student information
            $student->update([
                'class_id' => $data['class_id'] ?? $student->class_id,
                'father_name' => $data['father_name'] ?? $student->father_name,
                'mother_name' => $data['mother_name'] ?? $student->mother_name,
                'parent_email' => $data['parent_email'] ?? $student->parent_email,
                'parent_phone' => $data['parent_phone'] ?? $student->parent_phone,
                'blood_group' => $data['blood_group'] ?? $student->blood_group,
                'medical_info' => $data['medical_info'] ?? $student->medical_info,
                'transport_required' => $data['transport_required'] ?? $student->transport_required,
                'hostel_required' => $data['hostel_required'] ?? $student->hostel_required,
            ]);
            
            DB::commit();
            
            return $student->load(['user', 'class']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student update failed: ' . $e->getMessage());
            throw new \Exception('Failed to update student: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete student (soft delete recommended)
     */
    public function deleteStudent(Student $student): bool
    {
        DB::beginTransaction();
        
        try {
            // Delete user account
            $student->user->delete();
            
            // Delete student record
            $student->delete();
            
            DB::commit();
            
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student deletion failed: ' . $e->getMessage());
            throw new \Exception('Failed to delete student: ' . $e->getMessage());
        }
    }
    
    /**
     * Promote student to next class
     */
    public function promoteStudent(Student $student, int $newClassId, int $newAcademicYearId, string $result = 'Promoted'): Student
    {
        DB::beginTransaction();
        
        try {
            // Save academic history
            StudentAcademicHistory::create([
                'student_id' => $student->id,
                'school_id' => $student->school_id,
                'academic_year_id' => $student->academic_year_id,
                'class_id' => $student->class_id,
                'roll_number' => $student->roll_number,
                'is_promoted' => true,
                'promoted_to_class_id' => $newClassId,
                'result' => $result,
                'percentage' => $data['percentage'] ?? null,
            ]);
            
            // Generate new roll number for new class
            $newRollNumber = $this->generateRollNumber($student->school_id, $newClassId);
            
            // Update student
            $student->update([
                'class_id' => $newClassId,
                'academic_year_id' => $newAcademicYearId,
                'roll_number' => $newRollNumber,
            ]);
            
            DB::commit();
            
            return $student->load(['user', 'class']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student promotion failed: ' . $e->getMessage());
            throw new \Exception('Failed to promote student: ' . $e->getMessage());
        }
    }
    
    /**
     * Bulk import students from Excel/CSV
     */
    public function bulkImportStudents(array $studentsData, int $schoolId): array
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($studentsData as $index => $data) {
                try {
                    $this->createStudent($data, $schoolId);
                    $successCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            return [
                'success' => $successCount,
                'failed' => $failedCount,
                'errors' => $errors,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Bulk import failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get student dashboard data
     */
    public function getStudentDashboard(Student $student): array
    {
        // Get attendance summary
        $totalDays = $student->attendances()->count();
        $presentDays = $student->attendances()->where('status', 'present')->count();
        $attendancePercentage = $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;
        
        // Get marks summary
        $marks = $student->marks()->with(['exam', 'subject'])->get();
        $averagePercentage = $marks->avg('percentage') ?? 0;
        
        // Get fee summary
        $totalFees = $student->feePayments()->sum('amount');
        $paidFees = $student->feePayments()->where('payment_status', 'paid')->sum('amount');
        $pendingFees = $totalFees - $paidFees;
        
        // Get upcoming exams
        $upcomingExams = \App\Models\Exam::where('class_id', $student->class_id)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();
        
        // Get recent marks
        $recentMarks = $student->marks()
            ->with(['exam', 'subject'])
            ->latest()
            ->take(10)
            ->get();
        
        // Get recent attendance
        $recentAttendance = $student->attendances()
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();
        
        // Get notices
        $notices = \App\Models\Notice::where('school_id', $student->school_id)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->orderBy('priority', 'desc')
            ->take(5)
            ->get();
        
        return [
            'student_info' => [
                'id' => $student->id,
                'name' => $student->full_name,
                'admission_number' => $student->admission_number,
                'roll_number' => $student->roll_number,
                'class' => $student->class->full_name ?? 'Not Assigned',
                'father_name' => $student->father_name,
                'mother_name' => $student->mother_name,
                'parent_phone' => $student->parent_phone,
                'blood_group' => $student->blood_group,
            ],
            'attendance_summary' => [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $totalDays - $presentDays,
                'percentage' => round($attendancePercentage, 2),
                'recent_records' => $recentAttendance,
            ],
            'academic_summary' => [
                'average_percentage' => round($averagePercentage, 2),
                'total_exams' => $marks->groupBy('exam_id')->count(),
                'total_subjects' => $marks->groupBy('subject_id')->count(),
                'recent_marks' => $recentMarks,
            ],
            'fee_summary' => [
                'total_fees' => $totalFees,
                'paid_fees' => $paidFees,
                'pending_fees' => $pendingFees,
                'payment_percentage' => $totalFees > 0 ? round(($paidFees / $totalFees) * 100, 2) : 0,
            ],
            'upcoming_exams' => $upcomingExams,
            'recent_notices' => $notices,
        ];
    }
    
    /**
     * Generate unique roll number
     */
    private function generateRollNumber(int $schoolId, int $classId): string
    {
        $lastStudent = Student::where('school_id', $schoolId)
            ->where('class_id', $classId)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastStudent && $lastStudent->roll_number) {
            // Extract numeric part and increment
            $lastNumber = (int) substr($lastStudent->roll_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $year = date('Y');
        return $year . '/' . $classId . '/' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Search students by various criteria
     */
    public function searchStudents(int $schoolId, array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Student::where('school_id', $schoolId)
            ->with(['user', 'class']);
        
        // Filter by name
        if (!empty($filters['name'])) {
            $query->whereHas('user', function($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['name'] . '%')
                  ->orWhere('last_name', 'like', '%' . $filters['name'] . '%');
            });
        }
        
        // Filter by admission number
        if (!empty($filters['admission_number'])) {
            $query->where('admission_number', 'like', '%' . $filters['admission_number'] . '%');
        }
        
        // Filter by class
        if (!empty($filters['class_id'])) {
            $query->where('class_id', $filters['class_id']);
        }
        
        // Filter by blood group
        if (!empty($filters['blood_group'])) {
            $query->where('blood_group', $filters['blood_group']);
        }
        
        // Filter by admission date range
        if (!empty($filters['from_date'])) {
            $query->whereDate('admission_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('admission_date', '<=', $filters['to_date']);
        }
        
        // Sort
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        $perPage = $filters['per_page'] ?? 20;
        
        return $query->paginate($perPage);
    }
    
    /**
     * Get student statistics
     */
    public function getStudentStatistics(int $schoolId): array
    {
        $totalStudents = Student::where('school_id', $schoolId)->count();
        
        $classWiseCount = Student::where('school_id', $schoolId)
            ->select('class_id', DB::raw('count(*) as total'))
            ->with('class')
            ->groupBy('class_id')
            ->get()
            ->map(function($item) {
                return [
                    'class' => $item->class->full_name ?? 'Unknown',
                    'total' => $item->total,
                ];
            });
        
        $genderDistribution = Student::where('school_id', $schoolId)
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('users.gender', DB::raw('count(*) as total'))
            ->groupBy('users.gender')
            ->get();
        
        $bloodGroupDistribution = Student::where('school_id', $schoolId)
            ->select('blood_group', DB::raw('count(*) as total'))
            ->whereNotNull('blood_group')
            ->groupBy('blood_group')
            ->get();
        
        $monthlyAdmissions = Student::where('school_id', $schoolId)
            ->select(DB::raw('YEAR(admission_date) as year, MONTH(admission_date) as month'), DB::raw('count(*) as total'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
        
        return [
            'total_students' => $totalStudents,
            'class_wise_count' => $classWiseCount,
            'gender_distribution' => $genderDistribution,
            'blood_group_distribution' => $bloodGroupDistribution,
            'monthly_admissions' => $monthlyAdmissions,
        ];
    }
    
    /**
     * Get student by admission number
     */
    public function findByAdmissionNumber(string $admissionNumber, int $schoolId): ?Student
    {
        return Student::where('school_id', $schoolId)
            ->where('admission_number', $admissionNumber)
            ->with(['user', 'class'])
            ->first();
    }
    
    /**
     * Get students by class
     */
    public function getStudentsByClass(int $classId, int $schoolId): \Illuminate\Database\Eloquent\Collection
    {
        return Student::where('school_id', $schoolId)
            ->where('class_id', $classId)
            ->with(['user'])
            ->orderBy('roll_number')
            ->get();
    }
    
    /**
     * Reset student password
     */
    public function resetPassword(Student $student, string $newPassword = null): string
    {
        $password = $newPassword ?? $this->generateRandomPassword();
        
        $student->user->update([
            'password' => Hash::make($password),
        ]);
        
        return $password;
    }
    
    /**
     * Generate random password
     */
    private function generateRandomPassword(int $length = 8): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        return substr(str_shuffle($chars), 0, $length);
    }
    
    /**
     * Export students data
     */
    public function exportStudents(int $schoolId, array $filters = []): array
    {
        $students = $this->searchStudents($schoolId, array_merge($filters, ['per_page' => 9999]));
        
        $exportData = [];
        foreach ($students as $student) {
            $exportData[] = [
                'Admission Number' => $student->admission_number,
                'Roll Number' => $student->roll_number,
                'Student Name' => $student->full_name,
                'Class' => $student->class->full_name ?? 'N/A',
                'Father Name' => $student->father_name,
                'Mother Name' => $student->mother_name,
                'Parent Phone' => $student->parent_phone,
                'Parent Email' => $student->parent_email,
                'Student Email' => $student->user->email,
                'Student Mobile' => $student->user->mobile,
                'Blood Group' => $student->blood_group,
                'Admission Date' => $student->admission_date,
                'Date of Birth' => $student->user->date_of_birth,
                'Gender' => $student->user->gender,
                'Address' => $student->user->address,
                'City' => $student->user->city,
                'State' => $student->user->state,
                'Country' => $student->user->country,
                'Pincode' => $student->user->pincode,
            ];
        }
        
        return $exportData;
    }
}
