<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modern Security Configuration
 * 
 * @package    CodeIgniter
 * @subpackage Config
 * @category   Security
 * @author     RamomCoder
 * @version    7.0 - Modernized
 */

/*
|--------------------------------------------------------------------------
| Rate Limiting Configuration
|--------------------------------------------------------------------------
|
| Configure rate limiting for various actions
|
*/
$config['rate_limits'] = [
    'login' => [
        'max_attempts' => 5,
        'time_window' => 900, // 15 minutes
    ],
    'password_reset' => [
        'max_attempts' => 3,
        'time_window' => 3600, // 1 hour
    ],
    'contact_form' => [
        'max_attempts' => 3,
        'time_window' => 3600, // 1 hour
    ],
    'file_upload' => [
        'max_attempts' => 10,
        'time_window' => 300, // 5 minutes
    ]
];

/*
|--------------------------------------------------------------------------
| File Upload Security
|--------------------------------------------------------------------------
|
| Configure secure file upload settings
|
*/
$config['file_upload'] = [
    'max_size' => 2097152, // 2MB in bytes
    'allowed_types' => [
        'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'documents' => ['pdf', 'doc', 'docx', 'txt', 'rtf'],
        'spreadsheets' => ['xls', 'xlsx', 'csv'],
        'presentations' => ['ppt', 'pptx'],
        'archives' => ['zip', 'rar']
    ],
    'upload_paths' => [
        'student_documents' => './uploads/attachments/documents/',
        'student_images' => './uploads/images/student/',
        'teacher_images' => './uploads/images/teacher/',
        'admin_images' => './uploads/images/admin/',
        'certificates' => './uploads/certificate/',
        'marksheets' => './uploads/marksheet/'
    ],
    'scan_uploads' => true, // Enable virus scanning if available
    'quarantine_path' => './uploads/quarantine/'
];

/*
|--------------------------------------------------------------------------
| Password Policy
|--------------------------------------------------------------------------
|
| Configure password requirements
|
*/
$config['password_policy'] = [
    'min_length' => 8,
    'max_length' => 128,
    'require_uppercase' => true,
    'require_lowercase' => true,
    'require_numbers' => true,
    'require_special_chars' => true,
    'special_chars' => '!@#$%^&*()_+-=[]{}|;:,.<>?',
    'prevent_common_passwords' => true,
    'prevent_user_info' => true, // Prevent using username, email in password
    'password_history' => 5, // Remember last 5 passwords
    'max_age_days' => 90 // Force password change after 90 days
];

/*
|--------------------------------------------------------------------------
| Session Security
|--------------------------------------------------------------------------
|
| Enhanced session security settings
|
*/
$config['session_security'] = [
    'regenerate_on_login' => true,
    'regenerate_interval' => 300, // 5 minutes
    'timeout_warning' => 300, // Warn 5 minutes before timeout
    'concurrent_sessions' => 1, // Max concurrent sessions per user
    'ip_validation' => true,
    'user_agent_validation' => true
];

/*
|--------------------------------------------------------------------------
| Input Validation
|--------------------------------------------------------------------------
|
| Configure input validation and sanitization
|
*/
$config['input_validation'] = [
    'max_input_length' => 10000, // Maximum input field length
    'allowed_html_tags' => '<p><br><strong><em><u><ol><ul><li><a><img>',
    'strip_image_tags' => true,
    'xss_clean_uploads' => true,
    'validate_ip' => true,
    'block_suspicious_patterns' => [
        'script_tags' => '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
        'sql_injection' => '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION)\b)/i',
        'php_code' => '/<\?php|<\?=/i',
        'javascript' => '/javascript:/i'
    ]
];

/*
|--------------------------------------------------------------------------
| CSRF Protection
|--------------------------------------------------------------------------
|
| Enhanced CSRF protection settings
|
*/
$config['csrf_protection'] = [
    'enabled' => true,
    'token_name' => 'csrf_token',
    'cookie_name' => 'csrf_cookie',
    'expire' => 7200, // 2 hours
    'regenerate' => true,
    'exclude_uris' => [
        'api/webhook',
        'cron/tasks'
    ]
];

/*
|--------------------------------------------------------------------------
| Content Security Policy
|--------------------------------------------------------------------------
|
| Configure CSP headers
|
*/
$config['content_security_policy'] = [
    'enabled' => true,
    'report_only' => false,
    'directives' => [
        'default-src' => "'self'",
        'script-src' => "'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com",
        'style-src' => "'self' 'unsafe-inline' https://fonts.googleapis.com",
        'font-src' => "'self' https://fonts.gstatic.com",
        'img-src' => "'self' data: https:",
        'connect-src' => "'self'",
        'frame-src' => "'self' https://www.google.com",
        'object-src' => "'none'",
        'base-uri' => "'self'",
        'form-action' => "'self'"
    ]
];

/*
|--------------------------------------------------------------------------
| Security Headers
|--------------------------------------------------------------------------
|
| Configure additional security headers
|
*/
$config['security_headers'] = [
    'X-Content-Type-Options' => 'nosniff',
    'X-Frame-Options' => 'SAMEORIGIN',
    'X-XSS-Protection' => '1; mode=block',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
    'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains'
];

/*
|--------------------------------------------------------------------------
| Logging Configuration
|--------------------------------------------------------------------------
|
| Configure security event logging
|
*/
$config['security_logging'] = [
    'enabled' => true,
    'log_failed_logins' => true,
    'log_successful_logins' => true,
    'log_password_changes' => true,
    'log_permission_changes' => true,
    'log_file_uploads' => true,
    'log_file_downloads' => true,
    'log_suspicious_activity' => true,
    'retention_days' => 90,
    'alert_threshold' => 10 // Alert after 10 failed attempts
];

/*
|--------------------------------------------------------------------------
| IP Whitelist/Blacklist
|--------------------------------------------------------------------------
|
| Configure IP-based access control
|
*/
$config['ip_access_control'] = [
    'enabled' => false,
    'whitelist' => [
        // '192.168.1.0/24',
        // '10.0.0.0/8'
    ],
    'blacklist' => [
        // '192.168.1.100',
        // '10.0.0.50'
    ],
    'admin_whitelist' => [
        // Restrict admin access to specific IPs
        // '192.168.1.10',
        // '192.168.1.11'
    ]
];

/*
|--------------------------------------------------------------------------
| Two-Factor Authentication
|--------------------------------------------------------------------------
|
| Configure 2FA settings
|
*/
$config['two_factor_auth'] = [
    'enabled' => false,
    'required_for_admin' => true,
    'required_for_teachers' => false,
    'backup_codes_count' => 10,
    'totp_window' => 1, // Allow 1 time step tolerance
    'remember_device_days' => 30
];

/*
|--------------------------------------------------------------------------
| Account Lockout
|--------------------------------------------------------------------------
|
| Configure account lockout policies
|
*/
$config['account_lockout'] = [
    'enabled' => true,
    'max_failed_attempts' => 5,
    'lockout_duration' => 1800, // 30 minutes
    'progressive_lockout' => true, // Increase lockout time with repeated failures
    'notify_admin' => true,
    'auto_unlock' => true
];
