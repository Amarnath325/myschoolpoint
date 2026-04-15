<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master;

class MasterTableSeeder extends Seeder
{
    public function run(): void
    {
        // SCHOOL TYPES
        $schoolTypes = [
            ['m_group' => 'SCHOOL_TYPE', 'm_name' => 'Day School', 'm_alias_name' => 'Day', 'm_type' => 'active', 'm_description' => 'Regular day school without boarding facilities'],
            ['m_group' => 'SCHOOL_TYPE', 'm_name' => 'Boarding School', 'm_alias_name' => 'Boarding', 'm_type' => 'active', 'm_description' => 'Residential school with hostel facilities'],
            ['m_group' => 'SCHOOL_TYPE', 'm_name' => 'Day & Boarding', 'm_alias_name' => 'DayBoarding', 'm_type' => 'active', 'm_description' => 'Both day and boarding facilities available'],
        ];
        
        // MANAGEMENT TYPES
        $managementTypes = [
            ['m_group' => 'MANAGEMENT_TYPE', 'm_name' => 'Private', 'm_alias_name' => 'Private', 'm_type' => 'active', 'm_description' => 'Privately managed school'],
            ['m_group' => 'MANAGEMENT_TYPE', 'm_name' => 'Government', 'm_alias_name' => 'Govt', 'm_type' => 'active', 'm_description' => 'Government managed school'],
            ['m_group' => 'MANAGEMENT_TYPE', 'm_name' => 'Aided', 'm_alias_name' => 'Aided', 'm_type' => 'active', 'm_description' => 'Government aided private school'],
        ];
        
        // AFFILIATION BOARDS
        $affiliationBoards = [
            ['m_group' => 'AFFILIATION_BOARD', 'm_name' => 'CBSE', 'm_alias_name' => 'Central Board of Secondary Education', 'm_type' => 'active', 'm_description' => 'Central Board of Secondary Education'],
            ['m_group' => 'AFFILIATION_BOARD', 'm_name' => 'ICSE', 'm_alias_name' => 'Council for Indian School Certificate', 'm_type' => 'active', 'm_description' => 'Council for the Indian School Certificate Examinations'],
            ['m_group' => 'AFFILIATION_BOARD', 'm_name' => 'State Board', 'm_alias_name' => 'State Board', 'm_type' => 'active', 'm_description' => 'State Board of Education'],
            ['m_group' => 'AFFILIATION_BOARD', 'm_name' => 'IB', 'm_alias_name' => 'International Baccalaureate', 'm_type' => 'active', 'm_description' => 'International Baccalaureate'],
            ['m_group' => 'AFFILIATION_BOARD', 'm_name' => 'Cambridge', 'm_alias_name' => 'Cambridge International', 'm_type' => 'active', 'm_description' => 'Cambridge International Examinations'],
        ];
        
        // CLASSES
        $classes = [
            ['m_group' => 'CLASS', 'm_name' => 'NURSERY', 'm_alias_name' => 'Nursery', 'm_type' => 'active', 'm_other' => json_encode(['order' => 1, 'category' => 'pre_primary'])],
            ['m_group' => 'CLASS', 'm_name' => 'LKG', 'm_alias_name' => 'LKG', 'm_type' => 'active', 'm_other' => json_encode(['order' => 2, 'category' => 'pre_primary'])],
            ['m_group' => 'CLASS', 'm_name' => 'UKG', 'm_alias_name' => 'UKG', 'm_type' => 'active', 'm_other' => json_encode(['order' => 3, 'category' => 'pre_primary'])],
            ['m_group' => 'CLASS', 'm_name' => '1', 'm_alias_name' => 'Class 1', 'm_type' => 'active', 'm_other' => json_encode(['order' => 4, 'category' => 'primary'])],
            ['m_group' => 'CLASS', 'm_name' => '2', 'm_alias_name' => 'Class 2', 'm_type' => 'active', 'm_other' => json_encode(['order' => 5, 'category' => 'primary'])],
            ['m_group' => 'CLASS', 'm_name' => '3', 'm_alias_name' => 'Class 3', 'm_type' => 'active', 'm_other' => json_encode(['order' => 6, 'category' => 'primary'])],
            ['m_group' => 'CLASS', 'm_name' => '4', 'm_alias_name' => 'Class 4', 'm_type' => 'active', 'm_other' => json_encode(['order' => 7, 'category' => 'primary'])],
            ['m_group' => 'CLASS', 'm_name' => '5', 'm_alias_name' => 'Class 5', 'm_type' => 'active', 'm_other' => json_encode(['order' => 8, 'category' => 'primary'])],
            ['m_group' => 'CLASS', 'm_name' => '6', 'm_alias_name' => 'Class 6', 'm_type' => 'active', 'm_other' => json_encode(['order' => 9, 'category' => 'middle'])],
            ['m_group' => 'CLASS', 'm_name' => '7', 'm_alias_name' => 'Class 7', 'm_type' => 'active', 'm_other' => json_encode(['order' => 10, 'category' => 'middle'])],
            ['m_group' => 'CLASS', 'm_name' => '8', 'm_alias_name' => 'Class 8', 'm_type' => 'active', 'm_other' => json_encode(['order' => 11, 'category' => 'middle'])],
            ['m_group' => 'CLASS', 'm_name' => '9', 'm_alias_name' => 'Class 9', 'm_type' => 'active', 'm_other' => json_encode(['order' => 12, 'category' => 'secondary'])],
            ['m_group' => 'CLASS', 'm_name' => '10', 'm_alias_name' => 'Class 10', 'm_type' => 'active', 'm_other' => json_encode(['order' => 13, 'category' => 'secondary'])],
            ['m_group' => 'CLASS', 'm_name' => '11', 'm_alias_name' => 'Class 11', 'm_type' => 'active', 'm_other' => json_encode(['order' => 14, 'category' => 'senior_secondary'])],
            ['m_group' => 'CLASS', 'm_name' => '12', 'm_alias_name' => 'Class 12', 'm_type' => 'active', 'm_other' => json_encode(['order' => 15, 'category' => 'senior_secondary'])],
        ];
        
        // STREAMS
        $streams = [
            ['m_group' => 'STREAM', 'm_name' => 'SCIENCE', 'm_alias_name' => 'Science', 'm_type' => 'active', 'm_description' => 'Physics, Chemistry, Biology, Mathematics'],
            ['m_group' => 'STREAM', 'm_name' => 'COMMERCE', 'm_alias_name' => 'Commerce', 'm_type' => 'active', 'm_description' => 'Accountancy, Business Studies, Economics'],
            ['m_group' => 'STREAM', 'm_name' => 'ARTS', 'm_alias_name' => 'Arts/Humanities', 'm_type' => 'active', 'm_description' => 'History, Geography, Political Science, Sociology'],
            ['m_group' => 'STREAM', 'm_name' => 'VOCATIONAL', 'm_alias_name' => 'Vocational', 'm_type' => 'active', 'm_description' => 'Vocational courses and skill development'],
        ];
        
        // MEDIUM OF INSTRUCTION
        $mediums = [
            ['m_group' => 'MEDIUM', 'm_name' => 'ENGLISH', 'm_alias_name' => 'English Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'HINDI', 'm_alias_name' => 'Hindi Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'MARATHI', 'm_alias_name' => 'Marathi Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'TAMIL', 'm_alias_name' => 'Tamil Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'TELUGU', 'm_alias_name' => 'Telugu Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'BENGALI', 'm_alias_name' => 'Bengali Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'GUJARATI', 'm_alias_name' => 'Gujarati Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'KANNADA', 'm_alias_name' => 'Kannada Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'MALAYALAM', 'm_alias_name' => 'Malayalam Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'PUNJABI', 'm_alias_name' => 'Punjabi Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'URDU', 'm_alias_name' => 'Urdu Medium', 'm_type' => 'active'],
            ['m_group' => 'MEDIUM', 'm_name' => 'OTHER', 'm_alias_name' => 'Other Medium', 'm_type' => 'active'],
        ];
        
        // SUBSCRIPTION PLANS
        $subscriptionPlans = [
            ['m_group' => 'SUBSCRIPTION_PLAN', 'm_name' => 'FREE', 'm_alias_name' => 'Free Plan', 'm_type' => 'active', 
             'm_other' => json_encode(['price' => 0, 'duration_days' => 30, 'students_limit' => 100, 'features' => ['Basic Features', 'Email Support']])],
            ['m_group' => 'SUBSCRIPTION_PLAN', 'm_name' => 'BASIC', 'm_alias_name' => 'Basic Plan', 'm_type' => 'active', 
             'm_other' => json_encode(['price' => 999, 'duration_days' => 30, 'students_limit' => 500, 'features' => ['All Free Features', 'Priority Support', 'Reports']])],
            ['m_group' => 'SUBSCRIPTION_PLAN', 'm_name' => 'PREMIUM', 'm_alias_name' => 'Premium Plan', 'm_type' => 'active', 
             'm_other' => json_encode(['price' => 2499, 'duration_days' => 30, 'students_limit' => 9999, 'features' => ['All Basic Features', '24/7 Support', 'Advanced Analytics', 'SMS Integration']])],
            ['m_group' => 'SUBSCRIPTION_PLAN', 'm_name' => 'ENTERPRISE', 'm_alias_name' => 'Enterprise Plan', 'm_type' => 'active', 
             'm_other' => json_encode(['price' => 4999, 'duration_days' => 30, 'students_limit' => -1, 'features' => ['All Premium Features', 'Custom Development', 'Dedicated Manager']])],
            ['m_group' => 'SUBSCRIPTION_PLAN', 'm_name' => 'TRIAL', 'm_alias_name' => 'Trial Plan', 'm_type' => 'active', 
             'm_other' => json_encode(['price' => 0, 'duration_days' => 14, 'students_limit' => 50, 'features' => ['Limited Features', 'Email Support']])],
        ];
        
        // STATUS TYPES
        $statusTypes = [
            ['m_group' => 'STATUS', 'm_name' => 'ACTIVE', 'm_alias_name' => 'Active', 'm_type' => 'active', 'm_description' => 'Active status'],
            ['m_group' => 'STATUS', 'm_name' => 'INACTIVE', 'm_alias_name' => 'Inactive', 'm_type' => 'inactive', 'm_description' => 'Inactive status'],
            ['m_group' => 'STATUS', 'm_name' => 'PENDING', 'm_alias_name' => 'Pending', 'm_type' => 'pending', 'm_description' => 'Pending approval'],
            ['m_group' => 'STATUS', 'm_name' => 'SUSPENDED', 'm_alias_name' => 'Suspended', 'm_type' => 'suspended', 'm_description' => 'Suspended status'],
            ['m_group' => 'STATUS', 'm_name' => 'EXPIRED', 'm_alias_name' => 'Expired', 'm_type' => 'expired', 'm_description' => 'Expired status'],
            ['m_group' => 'STATUS', 'm_name' => 'APPROVED', 'm_alias_name' => 'Approved', 'm_type' => 'active', 'm_description' => 'Approved status'],
            ['m_group' => 'STATUS', 'm_name' => 'REJECTED', 'm_alias_name' => 'Rejected', 'm_type' => 'inactive', 'm_description' => 'Rejected status'],
        ];
        
        // GENDER TYPES
        $genders = [
            ['m_group' => 'GENDER', 'm_name' => 'MALE', 'm_alias_name' => 'Male', 'm_type' => 'active'],
            ['m_group' => 'GENDER', 'm_name' => 'FEMALE', 'm_alias_name' => 'Female', 'm_type' => 'active'],
            ['m_group' => 'GENDER', 'm_name' => 'OTHER', 'm_alias_name' => 'Other', 'm_type' => 'active'],
        ];
        
        // BLOOD GROUPS
        $bloodGroups = [
            ['m_group' => 'BLOOD_GROUP', 'm_name' => 'A_POSITIVE', 'm_alias_name' => 'A+', 'm_type' => 'active', 'm_description' => 'A Positive'],
            ['m_group' => 'BLOOD_GROUP', 'm_name' => 'A_NEGATIVE', 'm_alias_name' => 'A-', 'm_type' => 'active', 'm_description' => 'A Negative'],
            ['m_group' => 'BLOOD_GROUP', 'm_name' => 'B_POSITIVE', 'm_alias_name' => 'B+', 'm_type' => 'active', 'm_description' => 'B Positive'],
            ['m_group' => 'BLOOD_GROUP', 'm_name' => 'B_NEGATIVE', 'm_alias_name' => 'B-', 'm_type' => 'active', 'm_description' => 'B Negative'],
            ['m_group' => 'BLOOD_GROUP', 'm_name' => 'AB_POSITIVE', 'm_alias_name' => 'AB+', 'm_type' => 'active', 'm_description' => 'AB Positive'],
            ['m_group' => 'BLOOD_GROUP', 'm_name' => 'AB_NEGATIVE', 'm_alias_name' => 'AB-', 'm_type' => 'active', 'm_description' => 'AB Negative'],
            ['m_group' => 'BLOOD_GROUP', 'm_name' => 'O_POSITIVE', 'm_alias_name' => 'O+', 'm_type' => 'active', 'm_description' => 'O Positive'],
            ['m_group' => 'BLOOD_GROUP', 'm_name' => 'O_NEGATIVE', 'm_alias_name' => 'O-', 'm_type' => 'active', 'm_description' => 'O Negative'],
        ];
        
        // ATTENDANCE STATUS
        $attendanceStatus = [
            ['m_group' => 'ATTENDANCE_STATUS', 'm_name' => 'PRESENT', 'm_alias_name' => 'Present', 'm_type' => 'active'],
            ['m_group' => 'ATTENDANCE_STATUS', 'm_name' => 'ABSENT', 'm_alias_name' => 'Absent', 'm_type' => 'active'],
            ['m_group' => 'ATTENDANCE_STATUS', 'm_name' => 'LATE', 'm_alias_name' => 'Late', 'm_type' => 'active'],
            ['m_group' => 'ATTENDANCE_STATUS', 'm_name' => 'HALF_DAY', 'm_alias_name' => 'Half Day', 'm_type' => 'active'],
            ['m_group' => 'ATTENDANCE_STATUS', 'm_name' => 'HOLIDAY', 'm_alias_name' => 'Holiday', 'm_type' => 'active'],
            ['m_group' => 'ATTENDANCE_STATUS', 'm_name' => 'LEAVE', 'm_alias_name' => 'Leave', 'm_type' => 'active'],
        ];
        
        // LEAVE TYPES
        $leaveTypes = [
            ['m_group' => 'LEAVE_TYPE', 'm_name' => 'SICK_LEAVE', 'm_alias_name' => 'Sick Leave', 'm_type' => 'active', 'm_description' => 'Medical or health-related leave'],
            ['m_group' => 'LEAVE_TYPE', 'm_name' => 'CASUAL_LEAVE', 'm_alias_name' => 'Casual Leave', 'm_type' => 'active', 'm_description' => 'Personal or casual leave'],
            ['m_group' => 'LEAVE_TYPE', 'm_name' => 'EARNED_LEAVE', 'm_alias_name' => 'Earned Leave', 'm_type' => 'active', 'm_description' => 'Earned/privilege leave'],
            ['m_group' => 'LEAVE_TYPE', 'm_name' => 'EMERGENCY_LEAVE', 'm_alias_name' => 'Emergency Leave', 'm_type' => 'active', 'm_description' => 'Emergency situation leave'],
            ['m_group' => 'LEAVE_TYPE', 'm_name' => 'STUDY_LEAVE', 'm_alias_name' => 'Study Leave', 'm_type' => 'active', 'm_description' => 'Leave for exam preparation'],
            ['m_group' => 'LEAVE_TYPE', 'm_name' => 'MATERNITY_LEAVE', 'm_alias_name' => 'Maternity Leave', 'm_type' => 'active', 'm_description' => 'Maternity leave'],
            ['m_group' => 'LEAVE_TYPE', 'm_name' => 'PATERNITY_LEAVE', 'm_alias_name' => 'Paternity Leave', 'm_type' => 'active', 'm_description' => 'Paternity leave'],
            ['m_group' => 'LEAVE_TYPE', 'm_name' => 'BEREAVEMENT_LEAVE', 'm_alias_name' => 'Bereavement Leave', 'm_type' => 'active', 'm_description' => 'Leave due to family member demise'],
        ];
        
        // FEE FREQUENCY
        $feeFrequencies = [
            ['m_group' => 'FEE_FREQUENCY', 'm_name' => 'MONTHLY', 'm_alias_name' => 'Monthly', 'm_type' => 'active', 'm_description' => 'Pay every month'],
            ['m_group' => 'FEE_FREQUENCY', 'm_name' => 'QUARTERLY', 'm_alias_name' => 'Quarterly', 'm_type' => 'active', 'm_description' => 'Pay every 3 months'],
            ['m_group' => 'FEE_FREQUENCY', 'm_name' => 'HALF_YEARLY', 'm_alias_name' => 'Half Yearly', 'm_type' => 'active', 'm_description' => 'Pay every 6 months'],
            ['m_group' => 'FEE_FREQUENCY', 'm_name' => 'YEARLY', 'm_alias_name' => 'Yearly', 'm_type' => 'active', 'm_description' => 'Pay annually'],
            ['m_group' => 'FEE_FREQUENCY', 'm_name' => 'ONE_TIME', 'm_alias_name' => 'One Time', 'm_type' => 'active', 'm_description' => 'One time payment'],
        ];
        
        // PAYMENT MODES
        $paymentModes = [
            ['m_group' => 'PAYMENT_MODE', 'm_name' => 'CASH', 'm_alias_name' => 'Cash', 'm_type' => 'active'],
            ['m_group' => 'PAYMENT_MODE', 'm_name' => 'CARD', 'm_alias_name' => 'Credit/Debit Card', 'm_type' => 'active'],
            ['m_group' => 'PAYMENT_MODE', 'm_name' => 'BANK_TRANSFER', 'm_alias_name' => 'Bank Transfer', 'm_type' => 'active'],
            ['m_group' => 'PAYMENT_MODE', 'm_name' => 'CHEQUE', 'm_alias_name' => 'Cheque', 'm_type' => 'active'],
            ['m_group' => 'PAYMENT_MODE', 'm_name' => 'ONLINE', 'm_alias_name' => 'Online Payment', 'm_type' => 'active'],
            ['m_group' => 'PAYMENT_MODE', 'm_name' => 'UPI', 'm_alias_name' => 'UPI', 'm_type' => 'active'],
            ['m_group' => 'PAYMENT_MODE', 'm_name' => 'NEFT', 'm_alias_name' => 'NEFT', 'm_type' => 'active'],
            ['m_group' => 'PAYMENT_MODE', 'm_name' => 'RTGS', 'm_alias_name' => 'RTGS', 'm_type' => 'active'],
            ['m_group' => 'PAYMENT_MODE', 'm_name' => 'IMPS', 'm_alias_name' => 'IMPS', 'm_type' => 'active'],
        ];
        
        // EXAM TYPES
        $examTypes = [
            ['m_group' => 'EXAM_TYPE', 'm_name' => 'QUARTERLY', 'm_alias_name' => 'Quarterly Exam', 'm_type' => 'active', 'm_description' => 'First quarterly examination'],
            ['m_group' => 'EXAM_TYPE', 'm_name' => 'HALF_YEARLY', 'm_alias_name' => 'Half Yearly Exam', 'm_type' => 'active', 'm_description' => 'Half yearly examination'],
            ['m_group' => 'EXAM_TYPE', 'm_name' => 'ANNUAL', 'm_alias_name' => 'Annual Exam', 'm_type' => 'active', 'm_description' => 'Annual/final examination'],
            ['m_group' => 'EXAM_TYPE', 'm_name' => 'WEEKLY_TEST', 'm_alias_name' => 'Weekly Test', 'm_type' => 'active', 'm_description' => 'Weekly class test'],
            ['m_group' => 'EXAM_TYPE', 'm_name' => 'PRE_BOARD', 'm_alias_name' => 'Pre-Board Exam', 'm_type' => 'active', 'm_description' => 'Pre-board examination'],
            ['m_group' => 'EXAM_TYPE', 'm_name' => 'UNIT_TEST', 'm_alias_name' => 'Unit Test', 'm_type' => 'active', 'm_description' => 'Unit/periodic test'],
            ['m_group' => 'EXAM_TYPE', 'm_name' => 'PRACTICAL', 'm_alias_name' => 'Practical Exam', 'm_type' => 'active', 'm_description' => 'Practical examination'],
            ['m_group' => 'EXAM_TYPE', 'm_name' => 'VIVA', 'm_alias_name' => 'Viva Voce', 'm_type' => 'active', 'm_description' => 'Oral examination'],
        ];
        
        // RELIGIONS
        $religions = [
            ['m_group' => 'RELIGION', 'm_name' => 'HINDU', 'm_alias_name' => 'Hindu', 'm_type' => 'active'],
            ['m_group' => 'RELIGION', 'm_name' => 'MUSLIM', 'm_alias_name' => 'Muslim', 'm_type' => 'active'],
            ['m_group' => 'RELIGION', 'm_name' => 'CHRISTIAN', 'm_alias_name' => 'Christian', 'm_type' => 'active'],
            ['m_group' => 'RELIGION', 'm_name' => 'SIKH', 'm_alias_name' => 'Sikh', 'm_type' => 'active'],
            ['m_group' => 'RELIGION', 'm_name' => 'BUDDHIST', 'm_alias_name' => 'Buddhist', 'm_type' => 'active'],
            ['m_group' => 'RELIGION', 'm_name' => 'JAIN', 'm_alias_name' => 'Jain', 'm_type' => 'active'],
            ['m_group' => 'RELIGION', 'm_name' => 'PARSI', 'm_alias_name' => 'Parsi', 'm_type' => 'active'],
            ['m_group' => 'RELIGION', 'm_name' => 'OTHER', 'm_alias_name' => 'Other', 'm_type' => 'active'],
        ];
        
        // CATEGORIES (Caste)
        $categories = [
            ['m_group' => 'CATEGORY', 'm_name' => 'GENERAL', 'm_alias_name' => 'General', 'm_type' => 'active'],
            ['m_group' => 'CATEGORY', 'm_name' => 'OBC', 'm_alias_name' => 'OBC', 'm_type' => 'active', 'm_description' => 'Other Backward Class'],
            ['m_group' => 'CATEGORY', 'm_name' => 'SC', 'm_alias_name' => 'SC', 'm_type' => 'active', 'm_description' => 'Scheduled Caste'],
            ['m_group' => 'CATEGORY', 'm_name' => 'ST', 'm_alias_name' => 'ST', 'm_type' => 'active', 'm_description' => 'Scheduled Tribe'],
            ['m_group' => 'CATEGORY', 'm_name' => 'EWS', 'm_alias_name' => 'EWS', 'm_type' => 'active', 'm_description' => 'Economically Weaker Section'],
        ];
        
        // DAYS OF WEEK
        $daysOfWeek = [
            ['m_group' => 'DAY', 'm_name' => 'MONDAY', 'm_alias_name' => 'Monday', 'm_type' => 'active', 'm_other' => json_encode(['order' => 1])],
            ['m_group' => 'DAY', 'm_name' => 'TUESDAY', 'm_alias_name' => 'Tuesday', 'm_type' => 'active', 'm_other' => json_encode(['order' => 2])],
            ['m_group' => 'DAY', 'm_name' => 'WEDNESDAY', 'm_alias_name' => 'Wednesday', 'm_type' => 'active', 'm_other' => json_encode(['order' => 3])],
            ['m_group' => 'DAY', 'm_name' => 'THURSDAY', 'm_alias_name' => 'Thursday', 'm_type' => 'active', 'm_other' => json_encode(['order' => 4])],
            ['m_group' => 'DAY', 'm_name' => 'FRIDAY', 'm_alias_name' => 'Friday', 'm_type' => 'active', 'm_other' => json_encode(['order' => 5])],
            ['m_group' => 'DAY', 'm_name' => 'SATURDAY', 'm_alias_name' => 'Saturday', 'm_type' => 'active', 'm_other' => json_encode(['order' => 6])],
            ['m_group' => 'DAY', 'm_name' => 'SUNDAY', 'm_alias_name' => 'Sunday', 'm_type' => 'active', 'm_other' => json_encode(['order' => 7])],
        ];
        
        // GRADE SYSTEM
        $grades = [
            ['m_group' => 'GRADE', 'm_name' => 'A_PLUS', 'm_alias_name' => 'A+', 'm_type' => 'active', 'm_other' => json_encode(['min_percentage' => 90, 'max_percentage' => 100])],
            ['m_group' => 'GRADE', 'm_name' => 'A', 'm_alias_name' => 'A', 'm_type' => 'active', 'm_other' => json_encode(['min_percentage' => 80, 'max_percentage' => 89])],
            ['m_group' => 'GRADE', 'm_name' => 'B_PLUS', 'm_alias_name' => 'B+', 'm_type' => 'active', 'm_other' => json_encode(['min_percentage' => 70, 'max_percentage' => 79])],
            ['m_group' => 'GRADE', 'm_name' => 'B', 'm_alias_name' => 'B', 'm_type' => 'active', 'm_other' => json_encode(['min_percentage' => 60, 'max_percentage' => 69])],
            ['m_group' => 'GRADE', 'm_name' => 'C_PLUS', 'm_alias_name' => 'C+', 'm_type' => 'active', 'm_other' => json_encode(['min_percentage' => 50, 'max_percentage' => 59])],
            ['m_group' => 'GRADE', 'm_name' => 'C', 'm_alias_name' => 'C', 'm_type' => 'active', 'm_other' => json_encode(['min_percentage' => 40, 'max_percentage' => 49])],
            ['m_group' => 'GRADE', 'm_name' => 'D', 'm_alias_name' => 'D', 'm_type' => 'active', 'm_other' => json_encode(['min_percentage' => 33, 'max_percentage' => 39])],
            ['m_group' => 'GRADE', 'm_name' => 'F', 'm_alias_name' => 'F', 'm_type' => 'active', 'm_other' => json_encode(['min_percentage' => 0, 'max_percentage' => 32])],
        ];
        
        // Merge all data
        $allData = array_merge(
            $schoolTypes,
            $managementTypes,
            $affiliationBoards,
            $classes,
            $streams,
            $mediums,
            $subscriptionPlans,
            $statusTypes,
            $genders,
            $bloodGroups,
            $attendanceStatus,
            $leaveTypes,
            $feeFrequencies,
            $paymentModes,
            $examTypes,
            $religions,
            $categories,
            $daysOfWeek,
            $grades
        );
        
        foreach ($allData as $data) {
            Master::updateOrCreate(
                [
                    'm_group' => $data['m_group'],
                    'm_name' => $data['m_name']
                ],
                [
                    'm_alias_name' => $data['m_alias_name'] ?? null,
                    'm_type' => $data['m_type'] ?? 'active',
                    'm_other' => $data['m_other'] ?? null,
                    'm_description' => $data['m_description'] ?? null,
                ]
            );
        }
        
        $this->command->info('=====================================');
        $this->command->info('Master table seeded successfully!');
        $this->command->info('Total records: ' . Master::count());
        $this->command->info('Groups available:');
        
        $groups = Master::select('m_group')->distinct()->orderBy('m_group')->get();
        foreach ($groups as $group) {
            $count = Master::where('m_group', $group->m_group)->count();
            $this->command->info("  - {$group->m_group}: {$count} records");
        }
        $this->command->info('=====================================');
    }
}
