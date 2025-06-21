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

    /**
     * Initializes the Modern_validation library and loads required helpers.
     *
     * Sets up the CodeIgniter instance and loads the security helper for use in validation routines.
     */
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('security');
        log_message('info', 'Modern_validation Library Initialized');
    }

    /**
     * Sets the validation rules for the current validation session.
     *
     * Accepts an array of rules, where each rule defines the field, validation criteria, and optional label.
     * Enables method chaining.
     *
     * @param array $rules Array of validation rules.
     * @return self
     */
    public function setRules(array $rules) {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Assigns the data array to be validated.
     *
     * @param array $data The data to validate.
     * @return $this The current instance for method chaining.
     */
    public function setData(array $data) {
        $this->data = $data;
        return $this;
    }

    /**
     * Executes all defined validation rules against the provided data.
     *
     * Iterates through each field and its associated rules, applying validation and collecting errors. Returns true if all validations pass; otherwise, returns false.
     *
     * @return bool True if all validations succeed; false if any validation fails.
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
     * Applies all validation rules to a single field and records the first error encountered.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field to validate.
     * @param array $rule_set The set of validation rules and optional label for the field.
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
     * Applies a single validation rule to a field value.
     *
     * Determines the rule type and invokes the corresponding validation method. Returns true if the value passes the rule, or false if it fails. Unknown rules are considered as passed.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $rule The validation rule to apply, possibly with parameters.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value passes the rule, false otherwise.
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
     * Validates that a value is present and not empty, except for the string '0'.
     *
     * Sets an error message if the value is missing or empty.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is present; false otherwise.
     */
    protected function validateRequired($field, $value, $label) {
        if (empty($value) && $value !== '0') {
            $this->errors[$field] = "{$label} is required";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value's length is at least the specified minimum.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to check.
     * @param int|string $param The minimum required length.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value meets the minimum length, false otherwise.
     */
    protected function validateMinLength($field, $value, $param, $label) {
        if (strlen($value) < (int)$param) {
            $this->errors[$field] = "{$label} must be at least {$param} characters";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value does not exceed the specified maximum length.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to check.
     * @param int|string $param The maximum allowed length.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value's length is less than or equal to the maximum, false otherwise.
     */
    protected function validateMaxLength($field, $value, $param, $label) {
        if (strlen($value) > (int)$param) {
            $this->errors[$field] = "{$label} must not exceed {$param} characters";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value has exactly the specified number of characters.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to check.
     * @param int|string $param The required exact length.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value's length matches the specified length, false otherwise.
     */
    protected function validateExactLength($field, $value, $param, $label) {
        if (strlen($value) !== (int)$param) {
            $this->errors[$field] = "{$label} must be exactly {$param} characters";
            return false;
        }
        return true;
    }

    /**
     * Validates that the given value is a properly formatted email address.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is a valid email address, false otherwise.
     */
    protected function validateEmail($field, $value, $label) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} must be a valid email address";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value is a properly formatted URL.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is a valid URL, false otherwise.
     */
    protected function validateUrl($field, $value, $label) {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = "{$label} must be a valid URL";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value is numeric.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to check.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is numeric, false otherwise.
     */
    protected function validateNumeric($field, $value, $label) {
        if (!is_numeric($value)) {
            $this->errors[$field] = "{$label} must be numeric";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value is an integer.
     *
     * Sets an error message if the value is not a valid integer.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is an integer, false otherwise.
     */
    protected function validateInteger($field, $value, $label) {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$field] = "{$label} must be an integer";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value contains only alphabetic characters.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is alphabetic, false otherwise.
     */
    protected function validateAlpha($field, $value, $label) {
        if (!preg_match('/^[a-zA-Z]+$/', $value)) {
            $this->errors[$field] = "{$label} may only contain alphabetic characters";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value contains only alphanumeric characters.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is alphanumeric, false otherwise.
     */
    protected function validateAlphaNumeric($field, $value, $label) {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $value)) {
            $this->errors[$field] = "{$label} may only contain alphanumeric characters";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value contains only alphanumeric characters, dashes, or underscores.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is valid; false otherwise.
     */
    protected function validateAlphaDash($field, $value, $label) {
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            $this->errors[$field] = "{$label} may only contain alphanumeric characters, dashes, and underscores";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value of a field matches the value of another specified field.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field being validated.
     * @param string $param The name of the field to match against.
     * @param string $label The label of the field being validated.
     * @return bool True if the values match, false otherwise.
     */
    protected function validateMatches($field, $value, $param, $label) {
        $match_value = $this->data[$param] ?? null;
        if ($value !== $match_value) {
            $this->errors[$field] = "{$label} must match {$param}";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value of the given field is different from the value of another specified field.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field being validated.
     * @param string $param The name of the field to compare against.
     * @param string $label The label of the field being validated.
     * @return bool True if the values differ, false otherwise.
     */
    protected function validateDiffers($field, $value, $param, $label) {
        $compare_value = $this->data[$param] ?? null;
        if ($value === $compare_value) {
            $this->errors[$field] = "{$label} must differ from {$param}";
            return false;
        }
        return true;
    }

    /**
     * Checks if a value is unique in a specified database table and column.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to check for uniqueness.
     * @param string $param The table and column in the format 'table.column'.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is unique, false if it already exists.
     */
    protected function validateUnique($field, $value, $param, $label) {
        list($table, $column) = explode('.', $param);
        $query = $this->CI->db->get_where($table, [$column => $value]);
        if ($query->num_rows() > 0) {
            $this->errors[$field] = "{$label} already exists";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value is greater than the specified parameter.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to check.
     * @param mixed $param The value to compare against.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is greater than the parameter, false otherwise.
     */
    protected function validateGreaterThan($field, $value, $param, $label) {
        if ((float)$value <= (float)$param) {
            $this->errors[$field] = "{$label} must be greater than {$param}";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value is numerically less than the specified parameter.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param mixed $param The value to compare against.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is less than the parameter, false otherwise.
     */
    protected function validateLessThan($field, $value, $param, $label) {
        if ((float)$value >= (float)$param) {
            $this->errors[$field] = "{$label} must be less than {$param}";
            return false;
        }
        return true;
    }

    /**
     * Validates that a value matches a specified regular expression pattern.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $param The regular expression pattern to match.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value matches the pattern, false otherwise.
     */
    protected function validateRegex($field, $value, $param, $label) {
        if (!preg_match($param, $value)) {
            $this->errors[$field] = "{$label} format is invalid";
            return false;
        }
        return true;
    }

    /**
     * Validates that a value is a valid date in 'YYYY-MM-DD' format.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate.
     * @param string $label The human-readable label for the field.
     * @return bool True if the value is a valid date in the specified format, false otherwise.
     */
    protected function validateDate($field, $value, $label) {
        $date = DateTime::createFromFormat('Y-m-d', $value);
        if (!$date || $date->format('Y-m-d') !== $value) {
            $this->errors[$field] = "{$label} must be a valid date (YYYY-MM-DD)";
            return false;
        }
        return true;
    }

    /**
     * Validates that a value meets strong password requirements.
     *
     * The password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.
     *
     * @param string $field The name of the field being validated.
     * @param string $value The password value to validate.
     * @param string $label The human-readable label for the field.
     * @return bool True if the password meets all strength requirements, false otherwise.
     */
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

    /**
     * Validates that a filename contains only allowed characters (letters, numbers, dots, underscores, and dashes).
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to validate as a filename.
     * @param string $label The human-readable label for the field.
     * @return bool True if the filename is safe, false otherwise.
     */
    protected function validateSafeFilename($field, $value, $label) {
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $value)) {
            $this->errors[$field] = "{$label} contains invalid characters";
            return false;
        }
        return true;
    }

    /**
     * Validates that the value does not contain any <script> tags.
     *
     * Returns false and sets an error if the value includes script tags, helping to prevent script injection.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value to check for script tags.
     * @param string $label The human-readable label for the field.
     * @return bool True if no script tags are present, false otherwise.
     */
    protected function validateNoScriptTags($field, $value, $label) {
        if (preg_match('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', $value)) {
            $this->errors[$field] = "{$label} contains prohibited content";
            return false;
        }
        return true;
    }

    /**
     * Returns all validation error messages.
     *
     * @return array An associative array of field names to error messages.
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Retrieves the validation error message for a specific field.
     *
     * @param string $field The name of the field.
     * @return string|null The error message for the field, or null if no error exists.
     */
    public function getError($field) {
        return $this->errors[$field] ?? null;
    }

    /**
     * Determines whether any validation errors exist.
     *
     * @return bool True if there are validation errors; otherwise, false.
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
}
