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

    /**
     * Initializes the Security_monitor library, loading configuration and helpers, and ensuring the security events table exists.
     */
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
     * Logs a security event to the database and application log.
     *
     * Records details such as event type, description, severity, user ID, IP address, user agent, URL, and additional context. Triggers alert checks based on configured thresholds.
     *
     * @param string $event_type The type of security event (e.g., 'failed_login', 'file_upload').
     * @param string $description A description of the event.
     * @param string $severity The severity level ('info', 'warning', 'critical'). Defaults to 'info'.
     * @param array $context Optional additional context data to store with the event.
     * @return bool True if the event was logged, false if logging is disabled.
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
     * Logs a failed login attempt and checks for brute force attack patterns.
     *
     * Records a warning event for a failed login with the provided email and IP address, then analyzes recent failed attempts to detect potential brute force or account attack activity.
     *
     * @param string $email The email address used in the failed login attempt.
     * @param string $ip_address The IP address from which the failed login attempt originated.
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
     * Logs a successful login event if configured to do so.
     *
     * @param int|string $user_id The ID of the user who logged in.
     * @param string $email The email address of the user who logged in.
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
     * Logs a file upload event, recording whether the upload was successful or blocked.
     *
     * If file upload logging is enabled in the configuration, this method logs an event with details about the file and its type. The event type and severity reflect whether the upload succeeded or was blocked.
     *
     * @param string $filename The name of the file involved in the upload attempt.
     * @param string $file_type The MIME type or file type of the uploaded file.
     * @param bool $success Indicates if the upload was successful (true) or blocked (false).
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
     * Logs a suspicious activity event with warning severity if enabled in configuration.
     *
     * @param string $activity_type The type of suspicious activity detected.
     * @param string $description A description of the suspicious activity.
     * @param array $context Optional additional context for the event.
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
     * Logs a permission change event for a specified user.
     *
     * Records an informational security event when a permission is added, removed, or modified for a user, if permission change logging is enabled in the configuration.
     *
     * @param mixed $target_user The identifier of the user whose permission was changed.
     * @param string $permission The name of the permission affected.
     * @param string $action The action performed on the permission (e.g., 'granted', 'revoked').
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
     * Retrieves security event records from the database with optional filtering and pagination.
     *
     * @param int $limit The maximum number of events to return.
     * @param int $offset The number of events to skip before starting to collect the result set.
     * @param array $filters Optional filters: 'event_type', 'severity', 'user_id', 'ip_address', 'date_from', 'date_to'.
     * @return array An array of security event records matching the specified criteria.
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
     * Retrieves aggregated security statistics for a specified time period.
     *
     * Computes totals and breakdowns of security events over the past given number of days, including total events, counts by event type and severity, top IP addresses, and failed login attempts.
     *
     * @param int $days Number of days in the past to include in the statistics (default is 30).
     * @return array Associative array containing security statistics.
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
     * Detects brute force attack patterns based on recent failed login attempts.
     *
     * Checks for multiple failed login attempts from the same IP address or targeting the same email within a 15-minute window. If the number of attempts meets or exceeds the threshold, logs a critical security event for brute force or account attack detection.
     *
     * @param string $email The email address associated with the failed login attempts.
     * @param string $ip_address The IP address from which the failed login attempts originated.
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
     * Checks if the number of recent events of a given type meets or exceeds the configured alert threshold and triggers an alert if necessary.
     *
     * @param string $event_type The type of event to evaluate for alerting.
     * @param string $severity The severity level of the event.
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
     * Logs a critical security alert event when a threshold for a specific event type is exceeded.
     *
     * @param string $event_type The type of event triggering the alert.
     * @param int $count The number of events detected within the alert window.
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
     * Deletes security events older than the configured retention period.
     *
     * If a retention period is set in the configuration, removes events from the security log table that are older than the specified number of days. Logs a maintenance event if any records are deleted.
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
     * Ensures the security events database table exists, creating it with the required schema if absent.
     *
     * Defines fields for event metadata, including event type, description, severity, user ID, IP address, user agent, URL, context, and timestamp, along with appropriate indexes.
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
     * Retrieves the current logged-in user ID if available.
     *
     * @return mixed The user ID of the currently logged-in user, or null if not available.
     */
    private function getCurrentUserId() {
        if (function_exists('get_loggedin_user_id')) {
            return get_loggedin_user_id();
        }
        return null;
    }
}
