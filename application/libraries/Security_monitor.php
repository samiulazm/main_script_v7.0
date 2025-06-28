<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Event Monitoring Library
 * 
 * Monitors and logs security events, provides alerts and analytics
 * 
 * @package    CodeIgniter
 * @subpackage Libraries
 * @category   Security
 * @author     RamomCoder
 * @version    7.0 - Modernized
 */

class Security_monitor {

    protected $CI;
    protected $config;
    protected $log_table = 'security_events';

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->config('security_modern');
        $this->CI->load->helper('security');
        $this->config = $this->CI->config->item('security_logging');
        
        // Create security events table if it doesn't exist
        $this->createSecurityTable();
        
        log_message('info', 'Security_monitor Library Initialized');
    }

    /**
     * Log security event
     */
    public function logEvent($event_type, $description, $severity = 'info', $context = []) {
        if (!$this->config['enabled']) {
            return false;
        }

        $event_data = [
            'event_type' => $event_type,
            'description' => $description,
            'severity' => $severity,
            'user_id' => $this->getCurrentUserId(),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'url' => current_url(),
            'context' => json_encode($context),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert into database
        $this->CI->db->insert($this->log_table, $event_data);
        
        // Also log to file
        $log_message = "Security Event: {$event_type} | {$description} | IP: {$event_data['ip_address']} | User: {$event_data['user_id']}";
        log_message($severity, $log_message);

        // Check for alert conditions
        $this->checkAlertConditions($event_type, $severity);

        return true;
    }

    /**
     * Monitor failed login attempts
     */
    public function monitorFailedLogin($email, $ip_address) {
        $this->logEvent(
            'failed_login',
            "Failed login attempt for email: {$email}",
            'warning',
            ['email' => $email, 'ip' => $ip_address]
        );

        // Check for brute force patterns
        $this->checkBruteForcePattern($email, $ip_address);
    }

    /**
     * Monitor successful logins
     */
    public function monitorSuccessfulLogin($user_id, $email) {
        if ($this->config['log_successful_logins']) {
            $this->logEvent(
                'successful_login',
                "Successful login for user: {$email}",
                'info',
                ['user_id' => $user_id, 'email' => $email]
            );
        }
    }

    /**
     * Monitor file upload attempts
     */
    public function monitorFileUpload($filename, $file_type, $success = true) {
        if ($this->config['log_file_uploads']) {
            $event_type = $success ? 'file_upload_success' : 'file_upload_blocked';
            $severity = $success ? 'info' : 'warning';
            $description = $success ? 
                "File uploaded: {$filename}" : 
                "File upload blocked: {$filename}";

            $this->logEvent(
                $event_type,
                $description,
                $severity,
                ['filename' => $filename, 'file_type' => $file_type]
            );
        }
    }

    /**
     * Monitor suspicious activity
     */
    public function monitorSuspiciousActivity($activity_type, $description, $context = []) {
        if ($this->config['log_suspicious_activity']) {
            $this->logEvent(
                'suspicious_activity',
                "{$activity_type}: {$description}",
                'warning',
                array_merge($context, ['activity_type' => $activity_type])
            );
        }
    }

    /**
     * Monitor permission changes
     */
    public function monitorPermissionChange($target_user, $permission, $action) {
        if ($this->config['log_permission_changes']) {
            $this->logEvent(
                'permission_change',
                "Permission {$action} for user {$target_user}: {$permission}",
                'info',
                [
                    'target_user' => $target_user,
                    'permission' => $permission,
                    'action' => $action
                ]
            );
        }
    }

    /**
     * Get security events
     */
    public function getEvents($limit = 100, $offset = 0, $filters = []) {
        $this->CI->db->select('*');
        $this->CI->db->from($this->log_table);

        // Apply filters
        if (!empty($filters['event_type'])) {
            $this->CI->db->where('event_type', $filters['event_type']);
        }
        if (!empty($filters['severity'])) {
            $this->CI->db->where('severity', $filters['severity']);
        }
        if (!empty($filters['user_id'])) {
            $this->CI->db->where('user_id', $filters['user_id']);
        }
        if (!empty($filters['ip_address'])) {
            $this->CI->db->where('ip_address', $filters['ip_address']);
        }
        if (!empty($filters['date_from'])) {
            $this->CI->db->where('created_at >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->CI->db->where('created_at <=', $filters['date_to']);
        }

        $this->CI->db->order_by('created_at', 'DESC');
        $this->CI->db->limit($limit, $offset);

        return $this->CI->db->get()->result_array();
    }

    /**
     * Get security statistics
     */
    public function getSecurityStats($days = 30) {
        $date_from = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        $stats = [];

        // Total events
        $this->CI->db->where('created_at >=', $date_from);
        $stats['total_events'] = $this->CI->db->count_all_results($this->log_table);

        // Events by type
        $this->CI->db->select('event_type, COUNT(*) as count');
        $this->CI->db->where('created_at >=', $date_from);
        $this->CI->db->group_by('event_type');
        $stats['events_by_type'] = $this->CI->db->get($this->log_table)->result_array();

        // Events by severity
        $this->CI->db->select('severity, COUNT(*) as count');
        $this->CI->db->where('created_at >=', $date_from);
        $this->CI->db->group_by('severity');
        $stats['events_by_severity'] = $this->CI->db->get($this->log_table)->result_array();

        // Top IP addresses
        $this->CI->db->select('ip_address, COUNT(*) as count');
        $this->CI->db->where('created_at >=', $date_from);
        $this->CI->db->group_by('ip_address');
        $this->CI->db->order_by('count', 'DESC');
        $this->CI->db->limit(10);
        $stats['top_ips'] = $this->CI->db->get($this->log_table)->result_array();

        // Failed login attempts
        $this->CI->db->where('event_type', 'failed_login');
        $this->CI->db->where('created_at >=', $date_from);
        $stats['failed_logins'] = $this->CI->db->count_all_results($this->log_table);

        return $stats;
    }

    /**
     * Check for brute force patterns
     */
    private function checkBruteForcePattern($email, $ip_address) {
        $time_window = 900; // 15 minutes
        $threshold = 5; // 5 attempts
        
        $date_from = date('Y-m-d H:i:s', strtotime("-{$time_window} seconds"));
        
        // Check by IP
        $this->CI->db->where('event_type', 'failed_login');
        $this->CI->db->where('ip_address', $ip_address);
        $this->CI->db->where('created_at >=', $date_from);
        $ip_attempts = $this->CI->db->count_all_results($this->log_table);
        
        if ($ip_attempts >= $threshold) {
            $this->logEvent(
                'brute_force_detected',
                "Brute force attack detected from IP: {$ip_address}",
                'critical',
                ['ip_address' => $ip_address, 'attempts' => $ip_attempts]
            );
        }

        // Check by email
        $this->CI->db->where('event_type', 'failed_login');
        $this->CI->db->like('context', $email);
        $this->CI->db->where('created_at >=', $date_from);
        $email_attempts = $this->CI->db->count_all_results($this->log_table);
        
        if ($email_attempts >= $threshold) {
            $this->logEvent(
                'account_attack_detected',
                "Account attack detected for email: {$email}",
                'critical',
                ['email' => $email, 'attempts' => $email_attempts]
            );
        }
    }

    /**
     * Check alert conditions
     */
    private function checkAlertConditions($event_type, $severity) {
        if (!isset($this->config['alert_threshold'])) {
            return;
        }

        $threshold = $this->config['alert_threshold'];
        $time_window = 3600; // 1 hour
        $date_from = date('Y-m-d H:i:s', strtotime("-{$time_window} seconds"));

        // Count recent events of same type
        $this->CI->db->where('event_type', $event_type);
        $this->CI->db->where('created_at >=', $date_from);
        $recent_count = $this->CI->db->count_all_results($this->log_table);

        if ($recent_count >= $threshold) {
            $this->sendAlert($event_type, $recent_count);
        }
    }

    /**
     * Send security alert
     */
    private function sendAlert($event_type, $count) {
        // Log the alert
        $this->logEvent(
            'security_alert',
            "Security alert: {$count} {$event_type} events in the last hour",
            'critical',
            ['event_type' => $event_type, 'count' => $count]
        );

        // Here you could implement email notifications, SMS alerts, etc.
        // For now, we'll just log it
        log_message('critical', "SECURITY ALERT: {$count} {$event_type} events detected");
    }

    /**
     * Clean old security events
     */
    public function cleanOldEvents() {
        if (!isset($this->config['retention_days'])) {
            return;
        }

        $retention_days = $this->config['retention_days'];
        $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$retention_days} days"));

        $this->CI->db->where('created_at <', $cutoff_date);
        $deleted = $this->CI->db->delete($this->log_table);

        if ($deleted) {
            $this->logEvent(
                'maintenance',
                "Cleaned security events older than {$retention_days} days",
                'info',
                ['retention_days' => $retention_days]
            );
        }
    }

    /**
     * Create security events table
     */
    private function createSecurityTable() {
        if (!$this->CI->db->table_exists($this->log_table)) {
            $this->CI->load->dbforge();
            
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'event_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'description' => [
                    'type' => 'TEXT',
                ],
                'severity' => [
                    'type' => 'ENUM',
                    'constraint' => ['info', 'warning', 'error', 'critical'],
                    'default' => 'info'
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ],
                'ip_address' => [
                    'type' => 'VARCHAR',
                    'constraint' => 45,
                ],
                'user_agent' => [
                    'type' => 'TEXT',
                    'null' => TRUE
                ],
                'url' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500,
                    'null' => TRUE
                ],
                'context' => [
                    'type' => 'JSON',
                    'null' => TRUE
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ];

            $this->CI->dbforge->add_field($fields);
            $this->CI->dbforge->add_key('id', TRUE);
            $this->CI->dbforge->add_key('event_type');
            $this->CI->dbforge->add_key('severity');
            $this->CI->dbforge->add_key('created_at');
            $this->CI->dbforge->create_table($this->log_table);
        }
    }

    /**
     * Get current user ID
     */
    private function getCurrentUserId() {
        if (function_exists('get_loggedin_user_id')) {
            return get_loggedin_user_id();
        }
        return null;
    }
}
