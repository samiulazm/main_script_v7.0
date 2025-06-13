<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Tipsoi_device_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get student by register number
     */
    public function get_student_by_register_no($register_no)
    {
        $this->db->select('*');
        $this->db->from('student');
        $this->db->where('register_no', $register_no);
        $this->db->where('active', 1);
        return $this->db->get()->row_array();
    }

    /**
     * Get student enrollment information
     */
    public function get_student_enrollment($student_id, $branch_id)
    {
        $this->db->select('*');
        $this->db->from('enroll');
        $this->db->where('student_id', $student_id);
        $this->db->where('branch_id', $branch_id);
        $this->db->where('session_id', get_session_id());
        return $this->db->get()->row_array();
    }

    /**
     * Get attendance record by enrollment ID and date
     */
    public function get_attendance_by_date($enroll_id, $date)
    {
        $this->db->select('*');
        $this->db->from('student_attendance');
        $this->db->where('enroll_id', $enroll_id);
        $this->db->where('date', $date);
        return $this->db->get()->row_array();
    }

    /**
     * Get device configuration for a branch
     */
    public function get_device_config($branch_id)
    {
        $this->db->select('*');
        $this->db->from('tipsoi_device_config');
        $this->db->where('branch_id', $branch_id);
        return $this->db->get()->row_array();
    }

    /**
     * Get sync logs for a branch
     */
    public function get_sync_logs($branch_id, $limit = 10)
    {
        $this->db->select('*');
        $this->db->from('tipsoi_sync_logs');
        $this->db->where('branch_id', $branch_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Log sync results
     */
    public function log_sync_result($branch_id, $sync_date, $synced_count, $error_count, $errors = [])
    {
        $log_data = [
            'branch_id' => $branch_id,
            'sync_date' => $sync_date,
            'synced_count' => $synced_count,
            'error_count' => $error_count,
            'errors' => json_encode($errors),
            'status' => ($error_count > 0) ? 'partial' : (($synced_count > 0) ? 'success' : 'failed'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('tipsoi_sync_logs', $log_data);
    }

    /**
     * Get all students with their register numbers for mapping
     */
    public function get_students_for_mapping($branch_id)
    {
        $this->db->select('s.id, s.register_no, s.first_name, s.last_name, e.class_id, e.section_id, c.name as class_name, sec.name as section_name');
        $this->db->from('student s');
        $this->db->join('enroll e', 'e.student_id = s.id', 'inner');
        $this->db->join('class c', 'c.id = e.class_id', 'left');
        $this->db->join('section sec', 'sec.id = e.section_id', 'left');
        $this->db->where('e.branch_id', $branch_id);
        $this->db->where('e.session_id', get_session_id());
        $this->db->where('s.active', 1);
        $this->db->order_by('s.register_no', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Get attendance statistics for dashboard
     */
    public function get_attendance_stats($branch_id, $date_from = null, $date_to = null)
    {
        if (!$date_from) $date_from = date('Y-m-01');
        if (!$date_to) $date_to = date('Y-m-d');

        // Total synced records
        $this->db->select('COUNT(*) as total_synced');
        $this->db->from('tipsoi_sync_logs');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('sync_date >=', $date_from);
        $this->db->where('sync_date <=', $date_to);
        $total_synced = $this->db->get()->row()->total_synced;

        // Successful syncs
        $this->db->select('SUM(synced_count) as successful_records');
        $this->db->from('tipsoi_sync_logs');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('sync_date >=', $date_from);
        $this->db->where('sync_date <=', $date_to);
        $successful_records = $this->db->get()->row()->successful_records ?: 0;

        // Failed syncs
        $this->db->select('SUM(error_count) as failed_records');
        $this->db->from('tipsoi_sync_logs');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('sync_date >=', $date_from);
        $this->db->where('sync_date <=', $date_to);
        $failed_records = $this->db->get()->row()->failed_records ?: 0;

        return [
            'total_synced' => $total_synced,
            'successful_records' => $successful_records,
            'failed_records' => $failed_records,
            'success_rate' => ($successful_records + $failed_records > 0) ? 
                round(($successful_records / ($successful_records + $failed_records)) * 100, 2) : 0
        ];
    }

    /**
     * Create database tables if they don't exist
     */
    public function create_tables()
    {
        // Create tipsoi_device_config table
        $config_table = "CREATE TABLE IF NOT EXISTS `tipsoi_device_config` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `branch_id` int(11) NOT NULL,
            `device_name` varchar(255) DEFAULT 'Tipsoi Device 1',
            `api_token` varchar(255) DEFAULT NULL COMMENT 'Branch-specific API token for Tipsoi device',
            `device_ip` varchar(45) DEFAULT NULL COMMENT 'Legacy field - not used with API integration',
            `device_port` int(11) DEFAULT NULL COMMENT 'Legacy field - not used with API integration',
            `sync_interval` int(11) DEFAULT 60 COMMENT 'Sync interval in minutes (1-1440)',
            `auto_sync_enabled` tinyint(1) DEFAULT 0 COMMENT 'Include in automated 24h cron sync',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `branch_id` (`branch_id`),
            CONSTRAINT `tipsoi_device_config_branch_fk` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;";

        // Create tipsoi_sync_logs table
        $logs_table = "CREATE TABLE IF NOT EXISTS `tipsoi_sync_logs` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `branch_id` int(11) NOT NULL,
            `sync_date` date NOT NULL,
            `synced_count` int(11) DEFAULT 0,
            `error_count` int(11) DEFAULT 0,
            `errors` text DEFAULT NULL,
            `status` enum('success','partial','failed') DEFAULT 'failed',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `branch_id` (`branch_id`),
            KEY `sync_date` (`sync_date`),
            CONSTRAINT `tipsoi_sync_logs_branch_fk` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;";

        $this->db->query($config_table);
        $this->db->query($logs_table);

        return true;
    }

    /**
     * Update existing table to add api_token field if it doesn't exist
     */
    public function update_table_schema()
    {
        // Check if api_token column exists
        $query = $this->db->query("SHOW COLUMNS FROM `tipsoi_device_config` LIKE 'api_token'");
        
        if ($query->num_rows() == 0) {
            // Add api_token column
            $alter_sql = "ALTER TABLE `tipsoi_device_config` 
                         ADD COLUMN `api_token` varchar(255) DEFAULT NULL COMMENT 'Branch-specific API token for Tipsoi device' 
                         AFTER `device_name`";
            $this->db->query($alter_sql);
            return true;
        }
        
        return false; // Column already exists
    }

    /**
     * Check if tables exist and create them if needed
     */
    public function ensure_tables_exist()
    {
        // Check if tables exist
        $config_exists = $this->db->query("SHOW TABLES LIKE 'tipsoi_device_config'")->num_rows() > 0;
        $logs_exists = $this->db->query("SHOW TABLES LIKE 'tipsoi_sync_logs'")->num_rows() > 0;

        if (!$config_exists || !$logs_exists) {
            return $this->create_tables();
        }

        return true;
    }

    /**
     * Get device status information
     */
    public function get_device_status($branch_id)
    {
        $config = $this->get_device_config($branch_id);
        
        if (!$config) {
            return [
                'status' => 'not_configured',
                'message' => 'Device not configured'
            ];
        }

        // Check last sync
        $this->db->select('*');
        $this->db->from('tipsoi_sync_logs');
        $this->db->where('branch_id', $branch_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        $last_sync = $this->db->get()->row_array();

        if (!$last_sync) {
            return [
                'status' => 'never_synced',
                'message' => 'No sync performed yet',
                'config' => $config
            ];
        }

        $last_sync_time = strtotime($last_sync['created_at']);
        $time_diff = time() - $last_sync_time;

        if ($time_diff > 3600) { // More than 1 hour
            $status = 'outdated';
            $message = 'Last sync was ' . $this->time_ago($last_sync_time);
        } elseif ($last_sync['status'] === 'failed') {
            $status = 'error';
            $message = 'Last sync failed';
        } else {
            $status = 'active';
            $message = 'Device is active';
        }

        return [
            'status' => $status,
            'message' => $message,
            'config' => $config,
            'last_sync' => $last_sync
        ];
    }

    /**
     * Helper function to format time ago
     */
    private function time_ago($timestamp)
    {
        $time_diff = time() - $timestamp;
        
        if ($time_diff < 60) {
            return $time_diff . ' seconds ago';
        } elseif ($time_diff < 3600) {
            return floor($time_diff / 60) . ' minutes ago';
        } elseif ($time_diff < 86400) {
            return floor($time_diff / 3600) . ' hours ago';
        } else {
            return floor($time_diff / 86400) . ' days ago';
        }
    }

    /**
     * Create cron log table if it doesn't exist
     */
    public function ensure_cron_log_table()
    {
        $cron_log_table = "CREATE TABLE IF NOT EXISTS `tipsoi_cron_logs` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cron_type` varchar(50) NOT NULL,
            `total_synced` int(11) DEFAULT 0,
            `total_errors` int(11) DEFAULT 0,
            `execution_time` datetime NOT NULL,
            `details` text DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `cron_type` (`cron_type`),
            KEY `execution_time` (`execution_time`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;";

        $this->db->query($cron_log_table);
        return true;
    }

    /**
     * Get cron logs
     */
    public function get_cron_logs($limit = 50)
    {
        $this->db->select('*');
        $this->db->from('tipsoi_cron_logs');
        $this->db->order_by('execution_time', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Get cron statistics
     */
    public function get_cron_stats($days = 30)
    {
        $date_from = date('Y-m-d', strtotime("-{$days} days"));
        
        // 24h sync stats
        $this->db->select('COUNT(*) as total_runs, SUM(total_synced) as total_synced, SUM(total_errors) as total_errors');
        $this->db->from('tipsoi_cron_logs');
        $this->db->where('cron_type', '24h_sync');
        $this->db->where('execution_time >=', $date_from);
        $sync_24h = $this->db->get()->row_array();

        // Monthly sync stats
        $this->db->select('COUNT(*) as total_runs, SUM(total_synced) as total_synced, SUM(total_errors) as total_errors');
        $this->db->from('tipsoi_cron_logs');
        $this->db->where('cron_type', 'monthly_sync');
        $this->db->where('execution_time >=', $date_from);
        $sync_monthly = $this->db->get()->row_array();

        return [
            '24h_sync' => $sync_24h,
            'monthly_sync' => $sync_monthly,
            'period_days' => $days
        ];
    }

    public function save_branch_api_token_config($branch_id, $api_token)
    {
        $existing_config = $this->get_device_config($branch_id);

        if ($existing_config) {
            // Update existing configuration
            $update_data = [
                'api_token' => $api_token,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->db->where('branch_id', $branch_id);
            return $this->db->update('tipsoi_device_config', $update_data);
        } else {
            // Create new configuration with defaults
            $insert_data = [
                'branch_id' => $branch_id,
                'api_token' => $api_token,
                'device_name' => 'Tipsoi - Branch ' . $branch_id, // Generic default name
                'sync_interval' => 60, // Default sync interval
                'auto_sync_enabled' => 0, // Default auto sync disabled
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            return $this->db->insert('tipsoi_device_config', $insert_data);
        }
    }
} 