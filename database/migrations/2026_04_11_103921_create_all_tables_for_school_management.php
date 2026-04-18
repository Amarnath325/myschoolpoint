<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============ 1. SCHOOLS TABLE (PEHLE) ============
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('business_name', 255);
            $table->string('registration_number', 100)->unique()->nullable();
            $table->string('tax_number', 100)->nullable();
            $table->string('email', 255)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('pincode', 20)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->year('established_year')->nullable();
            $table->string('affiliation_board', 100)->nullable();
            $table->unsignedInteger('school_type')->nullable();
            $table->enum('gender_type', ['coed', 'boys', 'girls'])->default('coed');
            $table->integer('status')->default(1);
            $table->unsignedInteger('subscription_plan')->nullable();
            $table->date('subscription_start_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'subscription_end_date']);
            $table->index('email');
        });

        // ============ 2. SCHOOL BUSINESS DETAILS ============
        Schema::create('school_business_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('owner_name', 255)->nullable();
            $table->string('owner_phone', 20)->nullable();
            $table->string('owner_email', 255)->nullable();
            $table->string('pan_number', 50)->nullable();
            $table->string('gst_number', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->string('upi_id', 100)->nullable();
            $table->string('contract_file', 255)->nullable();
            $table->string('kyc_document', 255)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index('school_id');
        });

        // ============ 3. USERS TABLE ============
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
            $table->enum('user_type', ['super_admin', 'school_admin', 'teacher', 'student', 'parent', 'accountant', 'librarian']);
            $table->string('username', 100)->unique();
            $table->string('email', 255)->unique();
            $table->string('mobile', 20)->nullable();
            $table->string('password');
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('profile_pic', 255)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('pincode', 20)->nullable();
            $table->timestamp('last_login')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->json('settings')->nullable();
            $table->timestamps();
            
            $table->index(['school_id', 'user_type']);
            $table->index('email');
            $table->index('username');
            $table->index('is_active');
        });

        // ============ 4. PASSWORD RESET TOKENS ============
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ============ 5. SESSIONS ============
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // ============ 6. ACADEMIC YEARS ============
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->enum('status', ['active', 'completed', 'upcoming'])->default('upcoming');
            $table->timestamps();
            
            $table->index(['school_id', 'is_current']);
            $table->unique(['school_id', 'name']);
        });

        // ============ 7. CLASSES ============
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->string('name', 50);
            $table->string('section', 10)->nullable();
            $table->integer('numeric_value')->nullable();
            $table->integer('capacity')->default(40);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'academic_year_id']);
            $table->unique(['school_id', 'academic_year_id', 'name', 'section'], 'unique_class_section');
        });

        // ============ 8. SUBJECTS ============
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('code', 50)->nullable();
            $table->enum('subject_type', ['compulsory', 'elective', 'optional'])->default('compulsory');
            $table->integer('max_marks')->default(100);
            $table->integer('passing_marks')->default(33);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'class_id']);
            $table->unique(['school_id', 'class_id', 'name']);
        });

        // ============ 9. TEACHERS ============
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('employee_id', 50)->unique();
            $table->text('qualification')->nullable();
            $table->string('specialization', 255)->nullable();
            $table->integer('experience_years')->default(0);
            $table->date('joining_date')->nullable();
            $table->string('department', 100)->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->boolean('is_class_teacher')->default(false);
            $table->timestamps();
            
            $table->index(['school_id', 'employee_id']);
            $table->unique(['user_id', 'school_id']);
        });

        // ============ 10. STUDENTS ============
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->string('admission_number', 50)->unique();
            $table->string('roll_number', 50)->nullable();
            $table->date('admission_date')->nullable();
            $table->string('father_name', 255)->nullable();
            $table->string('mother_name', 255)->nullable();
            $table->string('parent_email', 255)->nullable();
            $table->string('parent_phone', 20)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->text('medical_info')->nullable();
            $table->boolean('transport_required')->default(false);
            $table->boolean('hostel_required')->default(false);
            $table->text('previous_school')->nullable();
            $table->timestamps();
            
            $table->index(['school_id', 'class_id']);
            $table->index('admission_number');
            $table->unique(['user_id', 'school_id']);
        });

        // ============ 11. STUDENT ACADEMIC HISTORY ============
        Schema::create('student_academic_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->string('roll_number', 50)->nullable();
            $table->boolean('is_promoted')->default(false);
            $table->foreignId('promoted_to_class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->string('result', 50)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'academic_year_id']);
        });

        // ============ 12. CLASS TEACHERS ============
        Schema::create('class_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'academic_year_id']);
            $table->unique(['academic_year_id', 'class_id', 'teacher_id'], 'unique_class_teacher');
        });

        // ============ 13. TIMETABLE ============
        Schema::create('timetable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room_number', 50)->nullable();
            $table->boolean('is_break')->default(false);
            $table->timestamps();
            
            $table->index(['school_id', 'class_id', 'day_of_week']);
            $table->index(['teacher_id', 'day_of_week']);
        });

        // ============ 14. ATTENDANCE ============
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'holiday'])->default('absent');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('marked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['school_id', 'date']);
            $table->index(['student_id', 'date']);
            $table->unique(['student_id', 'date']);
        });

        // ============ 15. EXAMS ============
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->enum('exam_type', ['quarterly', 'half_yearly', 'annual', 'weekly_test', 'pre_board']);
            $table->enum('term', ['first', 'second', 'third', 'final']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_marks')->default(100);
            $table->integer('passing_marks')->default(33);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'academic_year_id']);
            $table->index(['start_date', 'end_date']);
        });

        // ============ 16. MARKS ============
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->decimal('marks_obtained', 5, 2)->nullable();
            $table->integer('total_marks')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('grade', 2)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('entered_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['exam_id', 'student_id']);
            $table->unique(['exam_id', 'student_id', 'subject_id'], 'unique_marks');
        });

        // ============ 17. FEE STRUCTURES ============
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->string('fee_head', 100);
            $table->decimal('amount', 10, 2);
            $table->enum('frequency', ['monthly', 'quarterly', 'half_yearly', 'yearly', 'one_time']);
            $table->date('due_date')->nullable();
            $table->decimal('late_fee_amount', 10, 2)->default(0);
            $table->boolean('is_optional')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'academic_year_id', 'class_id']);
        });

        // ============ 18. FEE PAYMENTS ============
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_structure_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_mode', ['cash', 'card', 'bank_transfer', 'cheque', 'online']);
            $table->string('transaction_id', 255)->nullable();
            $table->string('cheque_number', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('receipt_number', 50)->unique();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_for_month', 20)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['school_id', 'student_id']);
            $table->index('payment_date');
            $table->index('receipt_number');
        });

        // ============ 19. EXPENSE CATEGORIES ============
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['school_id', 'name']);
        });

        // ============ 20. EXPENSES ============
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('expense_category_id')->nullable()->constrained()->onDelete('set null');
            $table->date('expense_date');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('bill_image', 255)->nullable();
            $table->enum('payment_mode', ['cash', 'card', 'bank_transfer', 'cheque'])->nullable();
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['school_id', 'expense_date']);
        });

        // ============ 21. LIBRARY BOOKS ============
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('book_code', 50)->unique();
            $table->string('title', 255);
            $table->string('author', 255)->nullable();
            $table->string('publisher', 255)->nullable();
            $table->string('isbn', 50)->nullable();
            $table->string('category', 100)->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('available_quantity')->default(1);
            $table->string('location', 100)->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index(['school_id', 'book_code']);
            $table->index('title');
        });

        // ============ 22. BOOK ISSUES ============
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('library_books')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('issue_date');
            $table->date('return_date')->nullable();
            $table->date('due_date');
            $table->enum('status', ['issued', 'returned', 'lost', 'damaged'])->default('issued');
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['school_id', 'status']);
            $table->index('due_date');
        });

        // ============ 23. NOTICES ============
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('content');
            $table->json('target_audience')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('attachment', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'start_date', 'end_date']);
            $table->index('priority');
        });

        // ============ 24. EVENTS ============
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('event_type', ['academic', 'cultural', 'sports', 'holiday', 'meeting', 'other']);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('venue', 255)->nullable();
            $table->boolean('is_holiday')->default(false);
            $table->json('target_audience')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['school_id', 'start_datetime', 'end_datetime']);
        });

        // ============ 25. LEAVE APPLICATIONS ============
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('user_type', ['teacher', 'student']);
            $table->enum('leave_type', ['sick', 'casual', 'earned', 'emergency', 'study']);
            $table->date('from_date');
            $table->date('to_date');
            $table->text('reason')->nullable();
            $table->string('document', 255)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('approval_remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index(['school_id', 'status']);
            $table->index(['user_id', 'from_date', 'to_date']);
        });

        // ============ 26. TRANSPORT ROUTES ============
        Schema::create('transport_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('route_name', 100);
            $table->string('vehicle_number', 50)->nullable();
            $table->string('driver_name', 100)->nullable();
            $table->string('driver_phone', 20)->nullable();
            $table->integer('capacity')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index('school_id');
        });

        // ============ 27. TRANSPORT STOPS ============
        Schema::create('transport_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('transport_routes')->onDelete('cascade');
            $table->string('stop_name', 100);
            $table->integer('stop_order');
            $table->time('pickup_time')->nullable();
            $table->time('drop_time')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            
            $table->index('route_id');
        });

        // ============ 28. STUDENT TRANSPORT ============
        Schema::create('student_transport', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('route_id')->constrained('transport_routes')->onDelete('cascade');
            $table->foreignId('stop_id')->constrained('transport_stops')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_transport');
        Schema::dropIfExists('transport_stops');
        Schema::dropIfExists('transport_routes');
        Schema::dropIfExists('leave_applications');
        Schema::dropIfExists('events');
        Schema::dropIfExists('notices');
        Schema::dropIfExists('book_issues');
        Schema::dropIfExists('library_books');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('fee_payments');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('marks');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('timetable');
        Schema::dropIfExists('class_teachers');
        Schema::dropIfExists('student_academic_history');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('school_business_details');
        Schema::dropIfExists('schools');
    }
};
