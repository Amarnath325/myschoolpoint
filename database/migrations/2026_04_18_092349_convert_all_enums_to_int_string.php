<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove enum columns from schools table - Replace with INT
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('gender_type'); // Will be recreated as unsignedInteger
        });
        Schema::table('schools', function (Blueprint $table) {
            $table->unsignedInteger('gender_type')->nullable();
        });

        // Remove enum columns from users table - Replace with INT
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'gender']); // Will be recreated as unsignedInteger
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('user_type')->nullable();
            $table->unsignedInteger('gender')->nullable();
        });

        // academic_years table
        if (Schema::hasTable('academic_years')) {
            Schema::table('academic_years', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            Schema::table('academic_years', function (Blueprint $table) {
                $table->unsignedInteger('status')->nullable();
            });
        }

        // subjects table
        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropColumn('subject_type');
            });
            Schema::table('subjects', function (Blueprint $table) {
                $table->unsignedInteger('subject_type')->nullable();
            });
        }

        // timetable table
        if (Schema::hasTable('timetable')) {
            Schema::table('timetable', function (Blueprint $table) {
                $table->dropColumn('day_of_week');
            });
            Schema::table('timetable', function (Blueprint $table) {
                $table->unsignedInteger('day_of_week')->nullable();
            });
        }

        // attendance table
        if (Schema::hasTable('attendance')) {
            Schema::table('attendance', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            Schema::table('attendance', function (Blueprint $table) {
                $table->unsignedInteger('status')->nullable();
            });
        }

        // exams table
        if (Schema::hasTable('exams')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropColumn(['exam_type', 'term']);
            });
            Schema::table('exams', function (Blueprint $table) {
                $table->unsignedInteger('exam_type')->nullable();
                $table->unsignedInteger('term')->nullable();
            });
        }

        // fee_structures table
        if (Schema::hasTable('fee_structures')) {
            Schema::table('fee_structures', function (Blueprint $table) {
                $table->dropColumn('frequency');
            });
            Schema::table('fee_structures', function (Blueprint $table) {
                $table->unsignedInteger('frequency')->nullable();
            });
        }

        // fee_payments table
        if (Schema::hasTable('fee_payments')) {
            Schema::table('fee_payments', function (Blueprint $table) {
                $table->dropColumn(['payment_mode', 'payment_status']);
            });
            Schema::table('fee_payments', function (Blueprint $table) {
                $table->unsignedInteger('payment_mode')->nullable();
                $table->unsignedInteger('payment_status')->nullable();
            });
        }

        // expenses table
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropColumn('payment_mode');
            });
            Schema::table('expenses', function (Blueprint $table) {
                $table->unsignedInteger('payment_mode')->nullable();
            });
        }

        // book_issues table
        if (Schema::hasTable('book_issues')) {
            Schema::table('book_issues', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            Schema::table('book_issues', function (Blueprint $table) {
                $table->unsignedInteger('status')->nullable();
            });
        }

        // notices table
        if (Schema::hasTable('notices')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
            Schema::table('notices', function (Blueprint $table) {
                $table->unsignedInteger('priority')->nullable();
            });
        }

        // events table
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('event_type');
            });
            Schema::table('events', function (Blueprint $table) {
                $table->unsignedInteger('event_type')->nullable();
            });
        }

        // leave_applications table
        if (Schema::hasTable('leave_applications')) {
            Schema::table('leave_applications', function (Blueprint $table) {
                $table->dropColumn(['user_type', 'leave_type', 'status']);
            });
            Schema::table('leave_applications', function (Blueprint $table) {
                $table->unsignedInteger('user_type')->nullable();
                $table->unsignedInteger('leave_type')->nullable();
                $table->unsignedInteger('status')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse is complex - we're converting to simpler types
        // No easy rollback from INT to ENUM
    }
};
