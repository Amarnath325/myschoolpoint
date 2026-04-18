<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate table before seeding
        Schema::disableForeignKeyConstraints();
        DB::table('menus')->truncate();
        Schema::enableForeignKeyConstraints();

        // Parent Menus
        $parentMenus = [
            [
                'menu_id' => 1,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Student Management Module',
                'menu_icon' => '👨‍🎓',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/students',
                'menu_group' => 'academic',
                'menu_sequence' => 1,
            ],
            [
                'menu_id' => 2,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Teacher & Staff Management Module',
                'menu_icon' => '👩‍🏫',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/teachers',
                'menu_group' => 'staff',
                'menu_sequence' => 2,
            ],
            [
                'menu_id' => 3,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Attendance Management Module',
                'menu_icon' => '📊',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/attendance',
                'menu_group' => 'academic',
                'menu_sequence' => 3,
            ],
            [
                'menu_id' => 4,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Academic & Timetable Module',
                'menu_icon' => '📚',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/academic',
                'menu_group' => 'academic',
                'menu_sequence' => 4,
            ],
            [
                'menu_id' => 5,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Examination & Result Module',
                'menu_icon' => '📝',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/examinations',
                'menu_group' => 'academic',
                'menu_sequence' => 5,
            ],
            [
                'menu_id' => 6,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Fee & Finance Module',
                'menu_icon' => '💰',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/fees',
                'menu_group' => 'finance',
                'menu_sequence' => 6,
            ],
            [
                'menu_id' => 7,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Library Management Module',
                'menu_icon' => '📖',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/library',
                'menu_group' => 'infrastructure',
                'menu_sequence' => 7,
            ],
            [
                'menu_id' => 8,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Transport Management Module',
                'menu_icon' => '🚌',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/transport',
                'menu_group' => 'infrastructure',
                'menu_sequence' => 8,
            ],
            [
                'menu_id' => 9,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Communication Module',
                'menu_icon' => '💬',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/communication',
                'menu_group' => 'communication',
                'menu_sequence' => 9,
            ],
            [
                'menu_id' => 10,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Hostel Management Module',
                'menu_icon' => '🏠',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/hostel',
                'menu_group' => 'infrastructure',
                'menu_sequence' => 10,
            ],
            [
                'menu_id' => 11,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Reports & Analytics Module',
                'menu_icon' => '📈',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/reports',
                'menu_group' => 'reports',
                'menu_sequence' => 11,
            ],
            [
                'menu_id' => 12,
                'menu_p_id' => null,
                'menu_route_type_id' => null,
                'menu_name' => 'Admin & System Configuration Module',
                'menu_icon' => '⚙️',
                'menu_status' => 1,
                'menu_sub_status' => 0,
                'menu_route' => '/admin',
                'menu_group' => 'admin',
                'menu_sequence' => 12,
            ],
        ];

        // Insert parent menus
        foreach ($parentMenus as $menu) {
            DB::table('menus')->insert($menu);
        }

        // Child Menus (Sub-menus)
        $childMenus = [
            // Student Management Module (menu_p_id = 1)
            [
                'menu_p_id' => 1,
                'menu_name' => 'All Students',
                'menu_icon' => '👨‍🎓',
                'menu_route' => '/students/all',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 1,
                'menu_name' => 'Add New Student',
                'menu_icon' => '➕',
                'menu_route' => '/students/add',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 1,
                'menu_name' => 'Student Promotion',
                'menu_icon' => '📈',
                'menu_route' => '/students/promotion',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 1,
                'menu_name' => 'Student ID Cards',
                'menu_icon' => '🪪',
                'menu_route' => '/students/id-cards',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 1,
                'menu_name' => 'Student Categories',
                'menu_icon' => '🏷️',
                'menu_route' => '/students/categories',
                'menu_sequence' => 5,
            ],
            
            // Teacher & Staff Management Module (menu_p_id = 2)
            [
                'menu_p_id' => 2,
                'menu_name' => 'All Teachers',
                'menu_icon' => '👩‍🏫',
                'menu_route' => '/teachers/all',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 2,
                'menu_name' => 'Add New Teacher',
                'menu_icon' => '➕',
                'menu_route' => '/teachers/add',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 2,
                'menu_name' => 'Staff Attendance',
                'menu_icon' => '📊',
                'menu_route' => '/teachers/attendance',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 2,
                'menu_name' => 'Teacher ID Cards',
                'menu_icon' => '🪪',
                'menu_route' => '/teachers/id-cards',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 2,
                'menu_name' => 'Salary Management',
                'menu_icon' => '💰',
                'menu_route' => '/teachers/salary',
                'menu_sequence' => 5,
            ],
            [
                'menu_p_id' => 2,
                'menu_name' => 'Leave Management',
                'menu_icon' => '🏖️',
                'menu_route' => '/teachers/leave',
                'menu_sequence' => 6,
            ],
            
            // Attendance Management Module (menu_p_id = 3)
            [
                'menu_p_id' => 3,
                'menu_name' => 'Mark Attendance',
                'menu_icon' => '✓',
                'menu_route' => '/attendance/mark',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 3,
                'menu_name' => 'Attendance Report',
                'menu_icon' => '📊',
                'menu_route' => '/attendance/report',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 3,
                'menu_name' => 'Monthly Attendance',
                'menu_icon' => '📅',
                'menu_route' => '/attendance/monthly',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 3,
                'menu_name' => 'Attendance Settings',
                'menu_icon' => '⚙️',
                'menu_route' => '/attendance/settings',
                'menu_sequence' => 4,
            ],
            
            // Academic & Timetable Module (menu_p_id = 4)
            [
                'menu_p_id' => 4,
                'menu_name' => 'Classes & Sections',
                'menu_icon' => '📚',
                'menu_route' => '/academic/classes',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 4,
                'menu_name' => 'Subjects Management',
                'menu_icon' => '📖',
                'menu_route' => '/academic/subjects',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 4,
                'menu_name' => 'Timetable Generator',
                'menu_icon' => '⏰',
                'menu_route' => '/academic/timetable',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 4,
                'menu_name' => 'Assign Class Teacher',
                'menu_icon' => '👩‍🏫',
                'menu_route' => '/academic/class-teacher',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 4,
                'menu_name' => 'Holidays List',
                'menu_icon' => '🎉',
                'menu_route' => '/academic/holidays',
                'menu_sequence' => 5,
            ],
            [
                'menu_p_id' => 4,
                'menu_name' => 'Academic Calendar',
                'menu_icon' => '📅',
                'menu_route' => '/academic/calendar',
                'menu_sequence' => 6,
            ],
            
            // Examination & Result Module (menu_p_id = 5)
            [
                'menu_p_id' => 5,
                'menu_name' => 'Exam Schedule',
                'menu_icon' => '📅',
                'menu_route' => '/exams/schedule',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 5,
                'menu_name' => 'Create Exam',
                'menu_icon' => '➕',
                'menu_route' => '/exams/create',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 5,
                'menu_name' => 'Marks Entry',
                'menu_icon' => '✏️',
                'menu_route' => '/exams/marks-entry',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 5,
                'menu_name' => 'Generate Report Card',
                'menu_icon' => '📜',
                'menu_route' => '/exams/report-card',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 5,
                'menu_name' => 'Result Analysis',
                'menu_icon' => '📊',
                'menu_route' => '/exams/analysis',
                'menu_sequence' => 5,
            ],
            [
                'menu_p_id' => 5,
                'menu_name' => 'Grade System',
                'menu_icon' => '⭐',
                'menu_route' => '/exams/grades',
                'menu_sequence' => 6,
            ],
            
            // Fee & Finance Module (menu_p_id = 6)
            [
                'menu_p_id' => 6,
                'menu_name' => 'Fee Structure',
                'menu_icon' => '💰',
                'menu_route' => '/fees/structure',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 6,
                'menu_name' => 'Collect Fees',
                'menu_icon' => '💳',
                'menu_route' => '/fees/collect',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 6,
                'menu_name' => 'Fee Reports',
                'menu_icon' => '📊',
                'menu_route' => '/fees/reports',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 6,
                'menu_name' => 'Expense Management',
                'menu_icon' => '📉',
                'menu_route' => '/fees/expenses',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 6,
                'menu_name' => 'Payment History',
                'menu_icon' => '📜',
                'menu_route' => '/fees/history',
                'menu_sequence' => 5,
            ],
            [
                'menu_p_id' => 6,
                'menu_name' => 'Generate Receipts',
                'menu_icon' => '🧾',
                'menu_route' => '/fees/receipts',
                'menu_sequence' => 6,
            ],
            
            // Library Management Module (menu_p_id = 7)
            [
                'menu_p_id' => 7,
                'menu_name' => 'Book Catalog',
                'menu_icon' => '📚',
                'menu_route' => '/library/books',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 7,
                'menu_name' => 'Add New Book',
                'menu_icon' => '➕',
                'menu_route' => '/library/books/add',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 7,
                'menu_name' => 'Issue Book',
                'menu_icon' => '📤',
                'menu_route' => '/library/issue',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 7,
                'menu_name' => 'Return Book',
                'menu_icon' => '📥',
                'menu_route' => '/library/return',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 7,
                'menu_name' => 'Library Reports',
                'menu_icon' => '📊',
                'menu_route' => '/library/reports',
                'menu_sequence' => 5,
            ],
            
            // Transport Management Module (menu_p_id = 8)
            [
                'menu_p_id' => 8,
                'menu_name' => 'Routes Management',
                'menu_icon' => '🗺️',
                'menu_route' => '/transport/routes',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 8,
                'menu_name' => 'Vehicles Management',
                'menu_icon' => '🚌',
                'menu_route' => '/transport/vehicles',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 8,
                'menu_name' => 'Assign Transport',
                'menu_icon' => '👨‍🎓',
                'menu_route' => '/transport/assign',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 8,
                'menu_name' => 'Transport Fee',
                'menu_icon' => '💰',
                'menu_route' => '/transport/fees',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 8,
                'menu_name' => 'Attendance Tracking',
                'menu_icon' => '✓',
                'menu_route' => '/transport/attendance',
                'menu_sequence' => 5,
            ],
            
            // Communication Module (menu_p_id = 9)
            [
                'menu_p_id' => 9,
                'menu_name' => 'Send Notices',
                'menu_icon' => '📢',
                'menu_route' => '/communication/notices',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 9,
                'menu_name' => 'Events Calendar',
                'menu_icon' => '📅',
                'menu_route' => '/communication/events',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 9,
                'menu_name' => 'Send SMS',
                'menu_icon' => '💬',
                'menu_route' => '/communication/sms',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 9,
                'menu_name' => 'Email Templates',
                'menu_icon' => '✉️',
                'menu_route' => '/communication/emails',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 9,
                'menu_name' => 'Complaints/Suggestions',
                'menu_icon' => '📝',
                'menu_route' => '/communication/complaints',
                'menu_sequence' => 5,
            ],
            
            // Hostel Management Module (menu_p_id = 10)
            [
                'menu_p_id' => 10,
                'menu_name' => 'Hostel Rooms',
                'menu_icon' => '🏠',
                'menu_route' => '/hostel/rooms',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 10,
                'menu_name' => 'Room Allocation',
                'menu_icon' => '👨‍🎓',
                'menu_route' => '/hostel/allocate',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 10,
                'menu_name' => 'Hostel Attendance',
                'menu_icon' => '📊',
                'menu_route' => '/hostel/attendance',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 10,
                'menu_name' => 'Hostel Fees',
                'menu_icon' => '💰',
                'menu_route' => '/hostel/fees',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 10,
                'menu_name' => 'Visitor Management',
                'menu_icon' => '👥',
                'menu_route' => '/hostel/visitors',
                'menu_sequence' => 5,
            ],
            
            // Reports & Analytics Module (menu_p_id = 11)
            [
                'menu_p_id' => 11,
                'menu_name' => 'Student Reports',
                'menu_icon' => '📊',
                'menu_route' => '/reports/students',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 11,
                'menu_name' => 'Teacher Reports',
                'menu_icon' => '📈',
                'menu_route' => '/reports/teachers',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 11,
                'menu_name' => 'Financial Reports',
                'menu_icon' => '💰',
                'menu_route' => '/reports/finance',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 11,
                'menu_name' => 'Attendance Analytics',
                'menu_icon' => '📉',
                'menu_route' => '/reports/attendance',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 11,
                'menu_name' => 'Exam Analytics',
                'menu_icon' => '📝',
                'menu_route' => '/reports/exams',
                'menu_sequence' => 5,
            ],
            [
                'menu_p_id' => 11,
                'menu_name' => 'Export Reports',
                'menu_icon' => '📤',
                'menu_route' => '/reports/export',
                'menu_sequence' => 6,
            ],
            
            // Admin & System Configuration Module (menu_p_id = 12)
            [
                'menu_p_id' => 12,
                'menu_name' => 'School Settings',
                'menu_icon' => '⚙️',
                'menu_route' => '/admin/settings',
                'menu_sequence' => 1,
            ],
            [
                'menu_p_id' => 12,
                'menu_name' => 'User Management',
                'menu_icon' => '👥',
                'menu_route' => '/admin/users',
                'menu_sequence' => 2,
            ],
            [
                'menu_p_id' => 12,
                'menu_name' => 'Role & Permissions',
                'menu_icon' => '🔐',
                'menu_route' => '/admin/roles',
                'menu_sequence' => 3,
            ],
            [
                'menu_p_id' => 12,
                'menu_name' => 'Backup & Restore',
                'menu_icon' => '💾',
                'menu_route' => '/admin/backup',
                'menu_sequence' => 4,
            ],
            [
                'menu_p_id' => 12,
                'menu_name' => 'System Logs',
                'menu_icon' => '📋',
                'menu_route' => '/admin/logs',
                'menu_sequence' => 5,
            ],
            [
                'menu_p_id' => 12,
                'menu_name' => 'Subscription Plans',
                'menu_icon' => '💎',
                'menu_route' => '/admin/subscriptions',
                'menu_sequence' => 6,
            ],
            [
                'menu_p_id' => 12,
                'menu_name' => 'API Settings',
                'menu_icon' => '🔌',
                'menu_route' => '/admin/api',
                'menu_sequence' => 7,
            ],
            [
                'menu_p_id' => 12,
                'menu_name' => 'Database Backup',
                'menu_icon' => '🗄️',
                'menu_route' => '/admin/database',
                'menu_sequence' => 8,
            ],
        ];

        // Insert child menus
        foreach ($childMenus as $menu) {
            DB::table('menus')->insert([
                'menu_p_id' => $menu['menu_p_id'],
                'menu_route_type_id' => null,
                'menu_name' => $menu['menu_name'],
                'menu_icon' => $menu['menu_icon'],
                'menu_status' => 1,
                'menu_sub_status' => 1,
                'menu_route' => $menu['menu_route'],
                'menu_group' => null,
                'menu_sequence' => $menu['menu_sequence'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Menus seeded successfully!');
        $this->command->info('Total Parent Menus: 12');
        $this->command->info('Total Child Menus: ' . count($childMenus));
    }
};
