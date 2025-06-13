-- SQL script to fix QR attendance database issues
-- Add missing columns to student_attendance table

-- Check if in_time column exists, if not add it
ALTER TABLE `student_attendance` 
ADD COLUMN IF NOT EXISTS `in_time` TIME NULL DEFAULT NULL AFTER `qr_code`;

-- Check if out_time column exists, if not add it  
ALTER TABLE `student_attendance` 
ADD COLUMN IF NOT EXISTS `out_time` TIME NULL DEFAULT NULL AFTER `in_time`;

-- Check if qr_code column exists in staff_attendance table
ALTER TABLE `staff_attendance` 
ADD COLUMN IF NOT EXISTS `qr_code` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`;

-- Check if in_time column exists in staff_attendance table
ALTER TABLE `staff_attendance` 
ADD COLUMN IF NOT EXISTS `in_time` TIME NULL DEFAULT NULL AFTER `qr_code`;

-- Check if out_time column exists in staff_attendance table
ALTER TABLE `staff_attendance` 
ADD COLUMN IF NOT EXISTS `out_time` TIME NULL DEFAULT NULL AFTER `in_time`;

-- Ensure QR code attendance module exists in permission_modules
INSERT IGNORE INTO `permission_modules` (`id`, `name`, `module_code`, `active`, `sort_order`, `module_status`, `created_at`) 
VALUES (500, 'Qr Code Attendance', 'qr_code_attendance', 1, 23, 1, NOW());

-- Insert QR attendance permissions if they don't exist
INSERT IGNORE INTO `permission` (`id`, `module_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) VALUES
(402, 500, 'QR Code Settings', 'qr_code_settings', 1, 1, 0, 0, NOW()),
(403, 500, 'QR Code Student Attendance', 'qr_code_student_attendance', 0, 1, 0, 0, NOW()),
(404, 500, 'QR Code Employee Attendance', 'qr_code_employee_attendance', 0, 1, 0, 0, NOW()),
(405, 500, 'QR Code Student Report', 'qr_code_student_attendance_report', 1, 0, 0, 0, NOW()),
(406, 500, 'QR Code Employee Report', 'qr_code_employee_attendance_report', 1, 0, 0, 0, NOW());

-- Update global_settings to mark QR attendance as installed
INSERT INTO `global_settings` (`name`, `value`) VALUES ('qr_attendance_installed', '1') 
ON DUPLICATE KEY UPDATE `value` = '1';
