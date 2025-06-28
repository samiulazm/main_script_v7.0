<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modern Security Helper Functions
 * 
 * @package    CodeIgniter
 * @subpackage Helpers
 * @category   Security
 * @author     RamomCoder
 * @version    7.0 - Modernized
 */

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize input data with multiple filters
     * 
     * @param mixed $data Input data to sanitize
     * @param string $type Type of sanitization (email, url, string, int, float)
     * @return mixed Sanitized data
     */
    function sanitize_input($data, $type = 'string') {
        if (is_array($data)) {
            $sanitized = [];
            foreach ($data as $key => $value) {
                $sanitized[$key] = sanitize_input($value, $type);
            }
            return $sanitized;
        }
        
        switch ($type) {
            case 'email':
                return filter_var($data, FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var($data, FILTER_SANITIZE_URL);
            case 'int':
                return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'phone':
                return preg_replace('/[^0-9+\-\s()]/', '', $data);
            case 'alphanumeric':
                return preg_replace('/[^a-zA-Z0-9]/', '', $data);
            case 'filename':
                return preg_replace('/[^a-zA-Z0-9._-]/', '', $data);
            case 'string':
            default:
                return strip_tags(trim($data));
        }
    }
}

if (!function_exists('validate_csrf_token')) {
    /**
     * Validate CSRF token
     * 
     * @return bool True if valid
     */
    function validate_csrf_token() {
        $CI =& get_instance();
        return $CI->security->verify_csrf_token();
    }
}

if (!function_exists('generate_secure_token')) {
    /**
     * Generate a secure random token
     * 
     * @param int $length Token length
     * @return string Secure token
     */
    function generate_secure_token($length = 32) {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length / 2));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length / 2));
        } else {
            // Fallback for older PHP versions
            return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $length)), 0, $length);
        }
    }
}

if (!function_exists('secure_file_path')) {
    /**
     * Validate and secure file path
     * 
     * @param string $path File path to validate
     * @param string $base_path Base directory path
     * @return string|false Secure path or false if invalid
     */
    function secure_file_path($path, $base_path) {
        // Remove any directory traversal attempts
        $path = str_replace(['../', '..\\', '../', '..\\'], '', $path);
        
        // Get real paths
        $real_path = realpath($base_path . '/' . $path);
        $real_base = realpath($base_path);
        
        // Ensure the file is within the base directory
        if ($real_path && $real_base && strpos($real_path, $real_base) === 0) {
            return $real_path;
        }
        
        return false;
    }
}

if (!function_exists('rate_limit_check')) {
    /**
     * Simple rate limiting check
     * 
     * @param string $key Rate limit key
     * @param int $max_attempts Maximum attempts
     * @param int $time_window Time window in seconds
     * @return bool True if within limits
     */
    function rate_limit_check($key, $max_attempts = 5, $time_window = 900) {
        $CI =& get_instance();
        $CI->load->library('session');
        
        $attempts_key = 'rate_limit_' . $key . '_attempts';
        $time_key = 'rate_limit_' . $key . '_time';
        
        $attempts = $CI->session->userdata($attempts_key) ?: 0;
        $last_attempt = $CI->session->userdata($time_key) ?: 0;
        
        // Reset if time window has passed
        if ((time() - $last_attempt) > $time_window) {
            $attempts = 0;
        }
        
        // Check if within limits
        if ($attempts >= $max_attempts) {
            return false;
        }
        
        // Increment attempts
        $CI->session->set_userdata([
            $attempts_key => $attempts + 1,
            $time_key => time()
        ]);
        
        return true;
    }
}

if (!function_exists('validate_file_upload')) {
    /**
     * Validate file upload security
     * 
     * @param array $file $_FILES array element
     * @param array $allowed_types Allowed file types
     * @param int $max_size Maximum file size in bytes
     * @return array Validation result
     */
    function validate_file_upload($file, $allowed_types = [], $max_size = 2097152) {
        $result = ['valid' => false, 'error' => ''];
        
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $result['error'] = 'Invalid file upload';
            return $result;
        }
        
        // Check file size
        if ($file['size'] > $max_size) {
            $result['error'] = 'File size exceeds limit';
            return $result;
        }
        
        // Check file type
        if (!empty($allowed_types)) {
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($file_ext, $allowed_types, true)) {
                $result['error'] = 'File type not allowed';
                return $result;
            }
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowed_mimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain'
        ];
        
        if (!empty($allowed_types)) {
            $valid_mime = false;
            foreach ($allowed_types as $ext) {
                if (isset($allowed_mimes[$ext]) && $allowed_mimes[$ext] === $mime_type) {
                    $valid_mime = true;
                    break;
                }
            }
            
            if (!$valid_mime) {
                $result['error'] = 'Invalid file type';
                return $result;
            }
        }
        
        $result['valid'] = true;
        return $result;
    }
}

if (!function_exists('log_security_event')) {
    /**
     * Log security events
     * 
     * @param string $event Event description
     * @param string $level Log level (error, warning, info)
     * @param array $context Additional context
     */
    function log_security_event($event, $level = 'warning', $context = []) {
        $CI =& get_instance();
        
        $log_data = [
            'event' => $event,
            'user_id' => function_exists('get_loggedin_user_id') ? get_loggedin_user_id() : null,
            'ip_address' => $CI->input->ip_address(),
            'user_agent' => $CI->input->user_agent(),
            'timestamp' => date('Y-m-d H:i:s'),
            'context' => $context
        ];
        
        $message = 'Security Event: ' . $event . ' | Data: ' . json_encode($log_data);
        log_message($level, $message);
    }
}

if (!function_exists('hash_password_modern')) {
    /**
     * Modern password hashing
     * 
     * @param string $password Plain text password
     * @return string Hashed password
     */
    function hash_password_modern($password) {
        if (function_exists('password_hash')) {
            return password_hash($password, PASSWORD_DEFAULT);
        }
        
        // Fallback for older PHP versions
        $CI =& get_instance();
        return hash("sha512", $password . $CI->config->item("encryption_key"));
    }
}

if (!function_exists('verify_password_modern')) {
    /**
     * Modern password verification
     * 
     * @param string $password Plain text password
     * @param string $hash Stored hash
     * @return bool True if password matches
     */
    function verify_password_modern($password, $hash) {
        // Check if it's a modern hash
        if (function_exists('password_verify') && password_get_info($hash)['algo'] !== null) {
            return password_verify($password, $hash);
        }
        
        // Legacy hash verification
        $CI =& get_instance();
        $legacy_hash = hash("sha512", $password . $CI->config->item("encryption_key"));
        return hash_equals($hash, $legacy_hash);
    }
}
