<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modern Validation Library
 * 
 * Enhanced validation with modern PHP practices and security features
 * 
 * @package    CodeIgniter
 * @subpackage Libraries
 * @category   Validation
 * @author     RamomCoder
 * @version    7.0 - Modernized
 */
class Modern_validation {

    protected $CI;
    protected $errors = [];
    protected $rules = [];
    protected $data = [];

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('security');
        log_message('info', 'Modern_validation Library Initialized');
    }

    /**
     * Set validation rules with modern syntax
     * 
     * @param array $rules Validation rules
     * @return $this
     */
    public function setRules(array $rules) {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Set data to validate
     * 
     * @param array $data Data to validate
     * @return $this
     */
    public function setData(array $data) {
        $this->data = $data;
        return $this;
    }

    /**
     * Run validation
     * 
     * @return bool True if validation passes
     */
    public function run() {
        $this->errors = [];

        foreach ($this->rules as $field => $rule_set) {
            $value = $this->data[$field] ?? null;
            $this->validateField($field, $value, $rule_set);
        }

        return empty($this->errors);
    }

    /**
     * Validate individual field
     * 
     * @param string $field Field name
     * @param mixed $value Field value
     * @param array $rule_set Rule configuration
     */
    protected function validateField($field, $value, $rule_set) {
        $rules = explode('|', $rule_set['rules'] ?? '');
        $label = $rule_set['label'] ?? $field;

        foreach ($rules as $rule) {
            if (!$this->applyRule($field, $value, $rule, $label)) {
                break; // Stop on first error for this field
            }
        }
    }

    /**
     * Apply individual validation rule
     * 
     * @param string $field Field name
     * @param mixed $value Field value
     * @param string $rule Rule to apply
     * @param string $label Field label
     * @return bool True if rule passes
     */
    protected function applyRule($field, $value, $rule, $label) {
        // Parse rule and parameters
        $rule_parts = explode('[', $rule);
        $rule_name = $rule_parts[0];
        $param = isset($rule_parts[1]) ? rtrim($rule_parts[1], ']') : null;

        switch ($rule_name) {
            case 'required':
                return $this->validateRequired($field, $value, $label);
            
            case 'min_length':
                return $this->validateMinLength($field, $value, $param, $label);
            
            case 'max_length':
                return $this->validateMaxLength($field, $value, $param, $label);
            
            case 'exact_length':
                return $this->validateExactLength($field, $value, $param, $label);
            
            case 'valid_email':
                return $this->validateEmail($field, $value, $label);
            
            case 'valid_url':
                return $this->validateUrl($field, $value, $label);
            
            case 'numeric':
                return $this->validateNumeric($field, $value, $label);
            
            case 'integer':
                return $this->validateInteger($field, $value, $label);
            
            case 'alpha':
                return $this->validateAlpha($field, $value, $label);
            
            case 'alpha_numeric':
                return $this->validateAlphaNumeric($field, $value, $label);
            
            case 'alpha_dash':
                return $this->validateAlphaDash($field, $value, $label);
            
            case 'matches':
                return $this->validateMatches($field, $value, $param, $label);
            
            case 'differs':
                return $this->validateDiffers($field, $value, $param, $label);
            
            case 'is_unique':
                return $this->validateUnique($field, $value, $param, $label);
            
            case 'greater_than':
                return $this->validateGreaterThan($field, $value, $param, $label);
            
            case 'less_than':
                return $this->validateLessThan($field, $value, $param, $label);
            
            case 'regex_match':
                return $this->validateRegex($field, $value, $param, $label);
            
            case 'valid_date':
                return $this->validateDate($field, $value, $label);
            
            case 'strong_password':
                return $this->validateStrongPassword($field, $value, $label);
            
            case 'safe_filename':
                return $this->validateSafeFilename($field, $value, $label);
            
            case 'no_script_tags':
                return $this->validateNoScriptTags($field, $value, $label);
            
            default:
                return true; // Unknown rule passes by default
        }
    }

    /**
     * Validation rule implementations
     */
    protected function validateRequired($field, $value, $label) {
        if (empty($value) && $value !== '0') {
            $this->errors[$field] = "{$label} is required";
            return false;
        }
        return true;
    }

    protected function validateMinLength($field, $value, $param, $label) {
        if (strlen($value) < (int)$param) {
            $this->errors[$field] = "{$label} must be at least {$param} characters";
            return false;
        }
        return true;
    }

    protected function validateMaxLength($field, $value, $param, $label) {
        if (strlen($value) > (int)$param) {
            $this->errors[$field] = "{$label} must not exceed {$param} characters";
            return false;
        }
        return true;
    }

    protected function validateExactLength($field, $value, $param, $label) {
        if (strlen($value) !== (int)$param) {
            $this->errors[$field] = "{$label} must be exactly {$param} characters";
            return false;
        }
        return true;
    }

    protected function validateEmail($field, $value, $label) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} must be a valid email address";
            return false;
        }
        return true;
    }

    protected function validateUrl($field, $value, $label) {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = "{$label} must be a valid URL";
            return false;
        }
        return true;
    }

    protected function validateNumeric($field, $value, $label) {
        if (!is_numeric($value)) {
            $this->errors[$field] = "{$label} must be numeric";
            return false;
        }
        return true;
    }

    protected function validateInteger($field, $value, $label) {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$field] = "{$label} must be an integer";
            return false;
        }
        return true;
    }

    protected function validateAlpha($field, $value, $label) {
        if (!preg_match('/^[a-zA-Z]+$/', $value)) {
            $this->errors[$field] = "{$label} may only contain alphabetic characters";
            return false;
        }
        return true;
    }

    protected function validateAlphaNumeric($field, $value, $label) {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $value)) {
            $this->errors[$field] = "{$label} may only contain alphanumeric characters";
            return false;
        }
        return true;
    }

    protected function validateAlphaDash($field, $value, $label) {
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            $this->errors[$field] = "{$label} may only contain alphanumeric characters, dashes, and underscores";
            return false;
        }
        return true;
    }

    protected function validateMatches($field, $value, $param, $label) {
        $match_value = $this->data[$param] ?? null;
        if ($value !== $match_value) {
            $this->errors[$field] = "{$label} must match {$param}";
            return false;
        }
        return true;
    }

    protected function validateDiffers($field, $value, $param, $label) {
        $compare_value = $this->data[$param] ?? null;
        if ($value === $compare_value) {
            $this->errors[$field] = "{$label} must differ from {$param}";
            return false;
        }
        return true;
    }

    protected function validateUnique($field, $value, $param, $label) {
        list($table, $column) = explode('.', $param);
        $query = $this->CI->db->get_where($table, [$column => $value]);
        if ($query->num_rows() > 0) {
            $this->errors[$field] = "{$label} already exists";
            return false;
        }
        return true;
    }

    protected function validateGreaterThan($field, $value, $param, $label) {
        if ((float)$value <= (float)$param) {
            $this->errors[$field] = "{$label} must be greater than {$param}";
            return false;
        }
        return true;
    }

    protected function validateLessThan($field, $value, $param, $label) {
        if ((float)$value >= (float)$param) {
            $this->errors[$field] = "{$label} must be less than {$param}";
            return false;
        }
        return true;
    }

    protected function validateRegex($field, $value, $param, $label) {
        if (!preg_match($param, $value)) {
            $this->errors[$field] = "{$label} format is invalid";
            return false;
        }
        return true;
    }

    protected function validateDate($field, $value, $label) {
        $date = DateTime::createFromFormat('Y-m-d', $value);
        if (!$date || $date->format('Y-m-d') !== $value) {
            $this->errors[$field] = "{$label} must be a valid date (YYYY-MM-DD)";
            return false;
        }
        return true;
    }

    protected function validateStrongPassword($field, $value, $label) {
        $errors = [];
        
        if (strlen($value) < 8) {
            $errors[] = "at least 8 characters";
        }
        if (!preg_match('/[A-Z]/', $value)) {
            $errors[] = "at least one uppercase letter";
        }
        if (!preg_match('/[a-z]/', $value)) {
            $errors[] = "at least one lowercase letter";
        }
        if (!preg_match('/[0-9]/', $value)) {
            $errors[] = "at least one number";
        }
        if (!preg_match('/[^a-zA-Z0-9]/', $value)) {
            $errors[] = "at least one special character";
        }
        
        if (!empty($errors)) {
            $this->errors[$field] = "{$label} must contain " . implode(', ', $errors);
            return false;
        }
        return true;
    }

    protected function validateSafeFilename($field, $value, $label) {
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $value)) {
            $this->errors[$field] = "{$label} contains invalid characters";
            return false;
        }
        return true;
    }

    protected function validateNoScriptTags($field, $value, $label) {
        if (preg_match('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', $value)) {
            $this->errors[$field] = "{$label} contains prohibited content";
            return false;
        }
        return true;
    }

    /**
     * Get validation errors
     * 
     * @return array Validation errors
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Get error for specific field
     * 
     * @param string $field Field name
     * @return string|null Error message
     */
    public function getError($field) {
        return $this->errors[$field] ?? null;
    }

    /**
     * Check if validation has errors
     * 
     * @return bool True if has errors
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
}
