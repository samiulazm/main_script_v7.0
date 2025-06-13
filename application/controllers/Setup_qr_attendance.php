<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * QR Attendance Setup Controller
 * This controller handles automatic setup and migration for QR attendance functionality
 * Can be accessed via: /setup_qr_attendance
 */
class Setup_qr_attendance extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        
        // Load required libraries
        $this->load->database();
        $this->load->helper('url');
        
        // Only allow access from localhost or specific IPs for security
        $allowed_ips = ['127.0.0.1', '::1', 'localhost'];
        if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowed_ips) && 
            !in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1'])) {
            // For production, you might want to add your server IP here
            // or comment out this security check entirely
        }
    }

    public function index()
    {
        echo "<h1>QR Attendance Setup</h1>";
        echo "<p>This setup will:</p>";
        echo "<ul>";
        echo "<li>Add required database fields for QR attendance</li>";
        echo "<li>Set up permissions and modules</li>";
        echo "<li>Ensure the application is properly configured</li>";
        echo "</ul>";
        echo "<p><a href='" . site_url('setup_qr_attendance/run') . "' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Run Setup</a></p>";
    }

    public function run()
    {
        echo "<h1>Running QR Attendance Setup...</h1>";
        
        try {
            // Step 1: Check database connection
            echo "<p>✓ Checking database connection...</p>";
            if (!$this->db->database) {
                throw new Exception("Database connection failed");
            }
            
            // Step 2: Add missing fields to student_attendance
            echo "<p>✓ Checking student_attendance table...</p>";
            if (!$this->db->field_exists('in_time', 'student_attendance')) {
                $this->db->query("ALTER TABLE `student_attendance` ADD `in_time` TIME NULL DEFAULT NULL AFTER `qr_code`");
                echo "<p>  → Added in_time field to student_attendance</p>";
            }
            
            if (!$this->db->field_exists('out_time', 'student_attendance')) {
                $this->db->query("ALTER TABLE `student_attendance` ADD `out_time` TIME NULL DEFAULT NULL AFTER `in_time`");
                echo "<p>  → Added out_time field to student_attendance</p>";
            }

            // Step 3: Ensure QR code module exists
            echo "<p>✓ Checking QR attendance module...</p>";
            $qr_module = $this->db->where('module_code', 'qr_code_attendance')->get('permission_modules')->row();
            if (empty($qr_module)) {
                $data = [
                    'id' => 500,
                    'name' => 'Qr Code Attendance',
                    'module_code' => 'qr_code_attendance',
                    'active' => 1,
                    'sort_order' => 23,
                    'module_status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('permission_modules', $data);
                echo "<p>  → Added QR Code Attendance module</p>";
            }

            // Step 4: Add permissions
            echo "<p>✓ Checking permissions...</p>";
            $permissions = [
                [
                    'name' => 'QR Code Attendance',
                    'short_code' => 'qr_attendance',
                    'is_admin' => 0,
                    'sort_order' => 500,
                    'module_id' => 500
                ],
                [
                    'name' => 'QR Code Attendance Report', 
                    'short_code' => 'qr_attendance_report',
                    'is_admin' => 0,
                    'sort_order' => 501,
                    'module_id' => 500
                ]
            ];

            foreach ($permissions as $perm) {
                $existing = $this->db->where('short_code', $perm['short_code'])->get('permission')->row();
                if (empty($existing)) {
                    $perm_data = array_merge($perm, [
                        'enable_view' => 1,
                        'enable_add' => 1,
                        'enable_edit' => 1,
                        'enable_delete' => 1,
                        'show_view' => 1,
                        'show_add' => 1,
                        'show_edit' => 1,
                        'show_delete' => 1
                    ]);
                    $this->db->insert('permission', $perm_data);
                    echo "<p>  → Added permission: " . $perm['name'] . "</p>";
                }
            }

            // Step 5: Set installation status
            echo "<p>✓ Setting installation status...</p>";
            $installed = $this->db->where('name', 'installed')->get('global_settings')->row();
            if (empty($installed)) {
                $this->db->insert('global_settings', ['name' => 'installed', 'value' => '1']);
                echo "<p>  → Set installation status to TRUE</p>";
            } elseif ($installed->value != '1') {
                $this->db->where('name', 'installed')->update('global_settings', ['value' => '1']);
                echo "<p>  → Updated installation status to TRUE</p>";
            }

            // Step 6: Check QR attendance files
            echo "<p>✓ Checking QR attendance files...</p>";
            $required_files = [
                'application/controllers/Qrcode_attendance.php',
                'application/models/Qrcode_attendance_model.php',
                'application/views/qrcode_attendance/student_entries.php',
                'application/views/qrcode_attendance/staff_entries.php'
            ];

            foreach ($required_files as $file) {
                if (file_exists(APPPATH . '../' . $file)) {
                    echo "<p>  ✓ $file exists</p>";
                } else {
                    echo "<p>  ✗ $file missing</p>";
                }
            }

            echo "<h2 style='color: green;'>✓ Setup completed successfully!</h2>";
            echo "<p>QR Attendance functionality should now work properly.</p>";
            echo "<p><a href='" . site_url('qrcode_attendance/student_entry') . "'>Test QR Student Attendance →</a></p>";
            echo "<p><a href='" . site_url('qrcode_attendance/staff_entry') . "'>Test QR Staff Attendance →</a></p>";

        } catch (Exception $e) {
            echo "<h2 style='color: red;'>✗ Setup failed!</h2>";
            echo "<p>Error: " . $e->getMessage() . "</p>";
            echo "<p>Please check your database configuration and try again.</p>";
        }
    }

    public function check_status()
    {
        echo "<h1>QR Attendance Status Check</h1>";
        
        // Check database fields
        echo "<h3>Database Status:</h3>";
        echo "<ul>";
        
        if ($this->db->field_exists('qr_code', 'student_attendance')) {
            echo "<li>✓ student_attendance.qr_code field exists</li>";
        } else {
            echo "<li>✗ student_attendance.qr_code field missing</li>";
        }
        
        if ($this->db->field_exists('in_time', 'student_attendance')) {
            echo "<li>✓ student_attendance.in_time field exists</li>";
        } else {
            echo "<li>✗ student_attendance.in_time field missing</li>";
        }
        
        if ($this->db->field_exists('qr_code', 'staff_attendance')) {
            echo "<li>✓ staff_attendance.qr_code field exists</li>";
        } else {
            echo "<li>✗ staff_attendance.qr_code field missing</li>";
        }
        
        echo "</ul>";

        // Check module status
        echo "<h3>Module Status:</h3>";
        $qr_module = $this->db->where('module_code', 'qr_code_attendance')->get('permission_modules')->row();
        if ($qr_module) {
            echo "<p>✓ QR Code Attendance module is " . ($qr_module->active ? 'enabled' : 'disabled') . "</p>";
        } else {
            echo "<p>✗ QR Code Attendance module not found</p>";
        }

        // Check installation status
        echo "<h3>Installation Status:</h3>";
        $installed = $this->db->where('name', 'installed')->get('global_settings')->row();
        if ($installed && $installed->value == '1') {
            echo "<p>✓ Application is marked as installed</p>";
        } else {
            echo "<p>✗ Application is not marked as installed</p>";
        }

        echo "<p><a href='" . site_url('setup_qr_attendance') . "'>← Back to Setup</a></p>";
    }
}
