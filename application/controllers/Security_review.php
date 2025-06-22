<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Configuration Review Controller
 * 
 * @package    CodeIgniter
 * @subpackage Controllers
 * @category   Security
 * @author     RamomCoder
 * @version    7.0 - Modernized
 */

class Security_review extends Admin_Controller {

    /**
     * Initializes the Security_review controller by loading security helpers and configuration.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('security');
        $this->load->config('security_modern');
    }

    /**
     * Displays the Security Configuration Review dashboard for superadmins.
     *
     * Prepares and renders the security review interface, including overall security status, actionable recommendations, and recent security events. Access is restricted to superadmin users.
     */
    public function index() {
        if (!is_superadmin_loggedin()) {
            access_denied();
        }

        $this->data['page_title'] = 'Security Configuration Review';
        $this->data['security_status'] = $this->getSecurityStatus();
        $this->data['recommendations'] = $this->getSecurityRecommendations();
        $this->data['recent_events'] = $this->getRecentSecurityEvents();
        
        $this->data['main_contents'] = $this->load->view('admin/security_review', $this->data, true);
        $this->load->view('admin/layout/index', $this->data);
    }

    /**
     * Evaluates and returns the current security configuration status.
     *
     * Assesses key security features such as rate limiting, file upload security, password policy, CSRF protection, session security, security headers, security logging, and account lockout based on configuration settings. Returns an array with individual check results, scores, and an overall security score as a percentage.
     *
     * @return array An array containing the status of each security check, total and maximum possible scores, and the overall security score percentage.
     */
    private function getSecurityStatus() {
        $status = [
            'overall_score' => 0,
            'checks' => []
        ];

        // Check rate limiting configuration
        $rate_limits = $this->config->item('rate_limits');
        $status['checks']['rate_limiting'] = [
            'name' => 'Rate Limiting',
            'status' => !empty($rate_limits) ? 'enabled' : 'disabled',
            'score' => !empty($rate_limits) ? 10 : 0,
            'description' => 'Protects against brute force attacks'
        ];

        // Check file upload security
        $file_upload = $this->config->item('file_upload');
        $upload_secure = !empty($file_upload) && 
                        isset($file_upload['max_size']) && 
                        !empty($file_upload['allowed_types']);
        $status['checks']['file_upload'] = [
            'name' => 'File Upload Security',
            'status' => $upload_secure ? 'secure' : 'needs_attention',
            'score' => $upload_secure ? 15 : 5,
            'description' => 'Validates file types and sizes'
        ];

        // Check password policy
        $password_policy = $this->config->item('password_policy');
        $policy_strong = !empty($password_policy) && 
                        ($password_policy['min_length'] >= 8) &&
                        $password_policy['require_uppercase'] &&
                        $password_policy['require_numbers'];
        $status['checks']['password_policy'] = [
            'name' => 'Password Policy',
            'status' => $policy_strong ? 'strong' : 'weak',
            'score' => $policy_strong ? 20 : 5,
            'description' => 'Enforces strong password requirements'
        ];

        // Check CSRF protection
        $csrf_enabled = $this->config->item('csrf_protection')['enabled'] ?? false;
        $status['checks']['csrf_protection'] = [
            'name' => 'CSRF Protection',
            'status' => $csrf_enabled ? 'enabled' : 'disabled',
            'score' => $csrf_enabled ? 15 : 0,
            'description' => 'Prevents cross-site request forgery'
        ];

        // Check session security
        $session_security = $this->config->item('session_security');
        $session_secure = !empty($session_security) && 
                         $session_security['regenerate_on_login'] &&
                         $session_security['ip_validation'];
        $status['checks']['session_security'] = [
            'name' => 'Session Security',
            'status' => $session_secure ? 'secure' : 'needs_attention',
            'score' => $session_secure ? 15 : 5,
            'description' => 'Secures user sessions'
        ];

        // Check security headers
        $security_headers = $this->config->item('security_headers');
        $headers_configured = !empty($security_headers) && 
                             isset($security_headers['X-Content-Type-Options']) &&
                             isset($security_headers['X-Frame-Options']);
        $status['checks']['security_headers'] = [
            'name' => 'Security Headers',
            'status' => $headers_configured ? 'configured' : 'missing',
            'score' => $headers_configured ? 10 : 0,
            'description' => 'HTTP security headers'
        ];

        // Check logging configuration
        $logging = $this->config->item('security_logging');
        $logging_enabled = !empty($logging) && $logging['enabled'];
        $status['checks']['security_logging'] = [
            'name' => 'Security Logging',
            'status' => $logging_enabled ? 'enabled' : 'disabled',
            'score' => $logging_enabled ? 10 : 0,
            'description' => 'Logs security events'
        ];

        // Check account lockout
        $lockout = $this->config->item('account_lockout');
        $lockout_enabled = !empty($lockout) && $lockout['enabled'];
        $status['checks']['account_lockout'] = [
            'name' => 'Account Lockout',
            'status' => $lockout_enabled ? 'enabled' : 'disabled',
            'score' => $lockout_enabled ? 5 : 0,
            'description' => 'Locks accounts after failed attempts'
        ];

        // Calculate overall score
        $total_score = 0;
        $max_score = 0;
        foreach ($status['checks'] as $check) {
            $total_score += $check['score'];
            $max_score += ($check['name'] === 'Password Policy') ? 20 : 
                         (($check['name'] === 'File Upload Security') ? 15 : 
                         (($check['name'] === 'CSRF Protection' || $check['name'] === 'Session Security') ? 15 : 10));
        }

        $status['overall_score'] = round(($total_score / $max_score) * 100);
        $status['total_score'] = $total_score;
        $status['max_score'] = $max_score;

        return $status;
    }

    /**
     * Generates prioritized security improvement recommendations based on current configuration status.
     *
     * Analyzes the results of security checks and returns actionable recommendations for any areas scoring below the threshold, including suggested configuration changes and general audit advice if the overall security score is low.
     *
     * @return array List of recommendations, each containing priority, title, description, and suggested action.
     */
    private function getSecurityRecommendations() {
        $recommendations = [];
        $status = $this->getSecurityStatus();

        foreach ($status['checks'] as $key => $check) {
            if ($check['score'] < 10) {
                switch ($key) {
                    case 'rate_limiting':
                        $recommendations[] = [
                            'priority' => 'high',
                            'title' => 'Enable Rate Limiting',
                            'description' => 'Configure rate limiting in security_modern.php to prevent brute force attacks',
                            'action' => 'Update $config[\'rate_limits\'] settings'
                        ];
                        break;
                    case 'file_upload':
                        $recommendations[] = [
                            'priority' => 'high',
                            'title' => 'Secure File Uploads',
                            'description' => 'Configure file upload restrictions and validation',
                            'action' => 'Update $config[\'file_upload\'] settings'
                        ];
                        break;
                    case 'password_policy':
                        $recommendations[] = [
                            'priority' => 'critical',
                            'title' => 'Strengthen Password Policy',
                            'description' => 'Enforce stronger password requirements',
                            'action' => 'Update $config[\'password_policy\'] settings'
                        ];
                        break;
                    case 'csrf_protection':
                        $recommendations[] = [
                            'priority' => 'critical',
                            'title' => 'Enable CSRF Protection',
                            'description' => 'Protect against cross-site request forgery attacks',
                            'action' => 'Set $config[\'csrf_protection\'][\'enabled\'] = true'
                        ];
                        break;
                    case 'session_security':
                        $recommendations[] = [
                            'priority' => 'high',
                            'title' => 'Enhance Session Security',
                            'description' => 'Configure secure session handling',
                            'action' => 'Update $config[\'session_security\'] settings'
                        ];
                        break;
                    case 'security_headers':
                        $recommendations[] = [
                            'priority' => 'medium',
                            'title' => 'Configure Security Headers',
                            'description' => 'Add HTTP security headers for better protection',
                            'action' => 'Update $config[\'security_headers\'] settings'
                        ];
                        break;
                    case 'security_logging':
                        $recommendations[] = [
                            'priority' => 'medium',
                            'title' => 'Enable Security Logging',
                            'description' => 'Log security events for monitoring',
                            'action' => 'Set $config[\'security_logging\'][\'enabled\'] = true'
                        ];
                        break;
                    case 'account_lockout':
                        $recommendations[] = [
                            'priority' => 'medium',
                            'title' => 'Enable Account Lockout',
                            'description' => 'Prevent brute force attacks on user accounts',
                            'action' => 'Set $config[\'account_lockout\'][\'enabled\'] = true'
                        ];
                        break;
                }
            }
        }

        // Additional general recommendations
        if ($status['overall_score'] < 80) {
            $recommendations[] = [
                'priority' => 'high',
                'title' => 'Regular Security Audits',
                'description' => 'Conduct regular security reviews and updates',
                'action' => 'Schedule monthly security reviews'
            ];
        }

        return $recommendations;
    }

    /**
     * Returns a list of recent security events using mock data for demonstration purposes.
     *
     * @return array An array of recent security event records, each containing a timestamp, event description, IP address, and severity level.
     */
    private function getRecentSecurityEvents() {
        // In a real implementation, this would query the security logs
        return [
            [
                'timestamp' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'event' => 'Failed login attempt',
                'ip' => '192.168.1.100',
                'severity' => 'warning'
            ],
            [
                'timestamp' => date('Y-m-d H:i:s', strtotime('-4 hours')),
                'event' => 'Successful admin login',
                'ip' => '192.168.1.10',
                'severity' => 'info'
            ],
            [
                'timestamp' => date('Y-m-d H:i:s', strtotime('-6 hours')),
                'event' => 'File upload attempt blocked',
                'ip' => '203.0.113.50',
                'severity' => 'warning'
            ]
        ];
    }

    /**
     * Exports the current security configuration as a JSON file for download.
     *
     * Only accessible to superadmins. The exported file includes key security settings such as rate limits, file upload rules, password policy, session security, CSRF protection, security headers, logging, and account lockout configuration.
     */
    public function export_config() {
        if (!is_superadmin_loggedin()) {
            access_denied();
        }

        $config_data = [
            'rate_limits' => $this->config->item('rate_limits'),
            'file_upload' => $this->config->item('file_upload'),
            'password_policy' => $this->config->item('password_policy'),
            'session_security' => $this->config->item('session_security'),
            'csrf_protection' => $this->config->item('csrf_protection'),
            'security_headers' => $this->config->item('security_headers'),
            'security_logging' => $this->config->item('security_logging'),
            'account_lockout' => $this->config->item('account_lockout')
        ];

        $filename = 'security_config_' . date('Y-m-d_H-i-s') . '.json';
        
        $this->load->helper('download');
        force_download($filename, json_encode($config_data, JSON_PRETTY_PRINT));
    }

    /**
     * Performs runtime tests on key security features and returns the results as a JSON response.
     *
     * Tests include rate limiting, password hashing and verification, input sanitization, and secure file path validation. Only accessible to superadmins.
     */
    public function test_config() {
        if (!is_superadmin_loggedin()) {
            access_denied();
        }

        $tests = [];

        // Test rate limiting
        $tests['rate_limiting'] = rate_limit_check('test_config', 1, 60);

        // Test password hashing
        $test_password = 'TestPassword123!';
        $hash = hash_password_modern($test_password);
        $tests['password_hashing'] = verify_password_modern($test_password, $hash);

        // Test input sanitization
        $malicious_input = '<script>alert("xss")</script>test@example.com';
        $sanitized = sanitize_input($malicious_input, 'email');
        $tests['input_sanitization'] = (strpos($sanitized, '<script>') === false);

        // Test file path validation
        $tests['file_path_validation'] = (secure_file_path('../../../etc/passwd', './uploads/') === false);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'tests' => $tests,
                'message' => 'Security configuration tests completed'
            ]));
    }
}
