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
     * Recursively sanitizes input data according to the specified type.
     *
     * Supports sanitization for email, URL, integer, float, phone number, alphanumeric, filename, and generic string types. Arrays are sanitized recursively.
     *
     * @param mixed $data The input data to sanitize; can be a scalar value or an array.
     * @param string $type The type of sanitization to apply: 'email', 'url', 'int', 'float', 'phone', 'alphanumeric', 'filename', or 'string' (default).
     * @return mixed The sanitized data, matching the structure of the input.
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
     * Validates the CSRF token using CodeIgniter's security library.
     *
     * @return bool True if the CSRF token is valid, false otherwise.
     */
    function validate_csrf_token() {
        $CI =& get_instance();
        return $CI->security->verify_csrf_token();
    }
}

if (!function_exists('generate_secure_token')) {
    /**
     * Generates a cryptographically secure random token of the specified length.
     *
     * Uses the most secure available method for random byte generation, with a fallback for older PHP versions.
     *
     * @param int $length The desired length of the token.
     * @return string The generated secure token.
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
     * Validates and secures a file path, ensuring it is within a specified base directory.
     *
     * Removes directory traversal patterns and checks that the resolved path is inside the base directory.
     *
     * @param string $path The file path to validate.
     * @param string $base_path The base directory against which to validate the path.
     * @return string|false The resolved secure path if valid, or false if the path is invalid or outside the base directory.
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
     * Checks if the number of actions associated with a given key is within the allowed rate limit for the current session.
     *
     * Tracks the number of attempts and the time of the last attempt using session data. Resets the attempt count if the specified time window has elapsed.
     *
     * @param string $key Unique identifier for the rate-limited action.
     * @param int $max_attempts Maximum allowed attempts within the time window.
     * @param int $time_window Time window in seconds for rate limiting.
     * @return bool True if the action is allowed (under the limit), false if the rate limit has been exceeded.
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
     * Validates an uploaded file for security by checking upload status, size, allowed extensions, and MIME type.
     *
     * @param array $file The uploaded file array (e.g., from $_FILES).
     * @param array $allowed_types List of permitted file extensions (e.g., ['jpg', 'png']).
     * @param int $max_size Maximum allowed file size in bytes.
     * @return array Associative array with 'valid' (bool) and 'error' (string) keys indicating validation result.
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
     * Logs a security-related event with contextual information.
     *
     * Records the event description, user ID (if available), IP address, user agent, timestamp, and any additional context, and writes the data to the application log at the specified log level.
     *
     * @param string $event Description of the security event.
     * @param string $level Log level to use ('error', 'warning', 'info'). Defaults to 'warning'.
     * @param array $context Additional contextual data to include in the log entry.
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
     * Hashes a plaintext password using a secure algorithm.
     *
     * Uses PHP's `password_hash` with the default algorithm if available. Falls back to SHA-512 with the application's encryption key for older PHP versions.
     *
     * @param string $password The plaintext password to hash.
     * @return string The resulting password hash.
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
     * Verifies a plaintext password against a stored hash using modern or legacy methods.
     *
     * Uses `password_verify` for modern hashes, or SHA-512 with the application's encryption key for legacy hashes.
     *
     * @param string $password The plaintext password to verify.
     * @param string $hash The stored password hash.
     * @return bool True if the password matches the hash, false otherwise.
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
