# Security Training Guide for Modernized CodeIgniter 3

## 🎯 Overview

This guide provides comprehensive training for your development team on the new security practices and modernized components implemented in your CodeIgniter 3 application.

## 📚 Table of Contents

1. [New Security Components](#new-security-components)
2. [Modern Form Handling](#modern-form-handling)
3. [Secure File Operations](#secure-file-operations)
4. [Password Management](#password-management)
5. [Input Validation](#input-validation)
6. [Security Monitoring](#security-monitoring)
7. [Best Practices](#best-practices)
8. [Common Pitfalls](#common-pitfalls)

## 🔧 New Security Components

### Security Helper Functions

**Location:** `application/helpers/security_helper.php`

#### Key Functions to Use:

```php
// Input sanitization
$clean_email = sanitize_input($user_input, 'email');
$clean_string = sanitize_input($user_input, 'string');
$clean_phone = sanitize_input($user_input, 'phone');

// CSRF validation
if (!validate_csrf_token()) {
    // Handle CSRF attack
}

// Rate limiting
if (!rate_limit_check('login_attempt', 5, 900)) {
    // Too many attempts
}

// Secure file paths
$safe_path = secure_file_path($user_path, $base_directory);

// Modern password handling
$hash = hash_password_modern($password);
$is_valid = verify_password_modern($password, $stored_hash);
```

### Modern Validation Library

**Location:** `application/libraries/Modern_validation.php`

#### Usage Example:

```php
$this->load->library('Modern_validation');

$rules = [
    'email' => [
        'label' => 'Email',
        'rules' => 'required|valid_email|max_length[255]'
    ],
    'password' => [
        'label' => 'Password',
        'rules' => 'required|strong_password'
    ]
];

$validator = new Modern_validation();
$validator->setRules($rules)->setData($post_data);

if ($validator->run()) {
    // Validation passed
} else {
    $errors = $validator->getErrors();
}
```

## 📝 Modern Form Handling

### ❌ OLD WAY (Don't Use):
```php
if ($_POST) {
    $email = $this->input->post('email');
    $this->form_validation->set_rules('email', 'Email', 'required');
    // ... rest of validation
}
```

### ✅ NEW WAY (Use This):
```php
// Modern POST handling
if ($this->input->server('REQUEST_METHOD') === 'POST') {
    
    // CSRF Protection
    if (!validate_csrf_token()) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'error',
                'message' => 'Security token mismatch'
            ]));
        return;
    }

    // Rate limiting
    if (!rate_limit_check('form_submission', 5, 300)) {
        // Handle rate limit exceeded
        return;
    }

    // Secure input handling
    $email = $this->input->post('email', TRUE); // XSS clean
    
    // Modern validation
    $validation_rules = [
        'email' => [
            'label' => 'Email',
            'rules' => 'required|valid_email|max_length[255]'
        ]
    ];

    $validator = new Modern_validation();
    $validator->setRules($validation_rules)->setData($this->input->post(NULL, TRUE));

    if ($validator->run()) {
        // Process form
    } else {
        // Handle validation errors
        $errors = $validator->getErrors();
    }
}
```

### Key Changes:
1. **Always check REQUEST_METHOD** instead of using `$_POST`
2. **Validate CSRF tokens** on all forms
3. **Implement rate limiting** for sensitive operations
4. **Use XSS cleaning** with `TRUE` parameter
5. **Use modern validation library** for enhanced security

## 🔒 Secure File Operations

### File Upload Security

#### ❌ OLD WAY:
```php
$config['allowed_types'] = '*';
$this->upload->do_upload('file');
```

#### ✅ NEW WAY:
```php
// Validate file upload first
$validation_result = validate_file_upload($_FILES['document'], ['pdf', 'doc', 'docx'], 2097152);

if (!$validation_result['valid']) {
    // Handle validation error
    return;
}

// Secure upload configuration
$config = [
    'upload_path' => './uploads/documents/',
    'allowed_types' => 'pdf|doc|docx',
    'max_size' => 2048, // 2MB
    'encrypt_name' => TRUE,
    'remove_spaces' => TRUE
];

$this->load->library('upload', $config);
if ($this->upload->do_upload('document')) {
    // Success
} else {
    // Handle upload error
}
```

### File Download Security

#### ❌ OLD WAY:
```php
$file = $this->input->get('file');
force_download($file, file_get_contents('./uploads/' . $file));
```

#### ✅ NEW WAY:
```php
$filename = $this->input->get('file', TRUE);

// Validate filename
if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
    show_404();
    return;
}

// Secure file path
$safe_path = secure_file_path($filename, './uploads/documents/');
if (!$safe_path || !file_exists($safe_path)) {
    show_404();
    return;
}

// Check permissions
if (!$this->canUserAccessFile($safe_path)) {
    show_error('Access denied', 403);
    return;
}

// Secure download
$this->load->helper('download');
force_download(basename($safe_path), file_get_contents($safe_path));
```

## 🔐 Password Management

### Password Hashing

#### ❌ OLD WAY:
```php
$password = hash("sha512", $password . config_item("encryption_key"));
```

#### ✅ NEW WAY:
```php
// For new passwords
$hashed_password = hash_password_modern($password);

// For verification
if (verify_password_modern($password, $stored_hash)) {
    // Password is correct
}
```

### Password Validation

```php
// Strong password validation
$validation_rules = [
    'password' => [
        'label' => 'Password',
        'rules' => 'required|strong_password'
    ]
];

// This checks for:
// - Minimum 8 characters
// - At least one uppercase letter
// - At least one lowercase letter
// - At least one number
// - At least one special character
```

## ✅ Input Validation Best Practices

### 1. Always Sanitize Input
```php
// Sanitize based on expected data type
$email = sanitize_input($input, 'email');
$phone = sanitize_input($input, 'phone');
$filename = sanitize_input($input, 'filename');
$string = sanitize_input($input, 'string');
```

### 2. Use Specific Validation Rules
```php
$rules = [
    'email' => 'required|valid_email|max_length[255]',
    'phone' => 'required|regex_match[/^[0-9+\-\s()]+$/]',
    'name' => 'required|alpha|min_length[2]|max_length[50]',
    'age' => 'required|integer|greater_than[0]|less_than[150]'
];
```

### 3. Implement Rate Limiting
```php
// For login attempts
if (!rate_limit_check('login_' . $ip_address, 5, 900)) {
    // Block for 15 minutes after 5 attempts
}

// For form submissions
if (!rate_limit_check('contact_form', 3, 3600)) {
    // Block for 1 hour after 3 submissions
}
```

## 📊 Security Monitoring

### Using the Security Monitor

```php
// Load the security monitor
$this->load->library('Security_monitor');

// Log security events
$this->security_monitor->logEvent(
    'user_action',
    'User updated profile',
    'info',
    ['user_id' => $user_id]
);

// Monitor failed logins
$this->security_monitor->monitorFailedLogin($email, $ip_address);

// Monitor file uploads
$this->security_monitor->monitorFileUpload($filename, $file_type, $success);

// Monitor suspicious activity
$this->security_monitor->monitorSuspiciousActivity(
    'multiple_failed_attempts',
    'User attempted login 10 times in 5 minutes'
);
```

### Viewing Security Events

```php
// Get recent events
$events = $this->security_monitor->getEvents(50, 0, [
    'severity' => 'warning',
    'date_from' => date('Y-m-d', strtotime('-7 days'))
]);

// Get security statistics
$stats = $this->security_monitor->getSecurityStats(30);
```

## 🚨 Best Practices Checklist

### For Every Form:
- [ ] Use `REQUEST_METHOD` check instead of `$_POST`
- [ ] Implement CSRF protection
- [ ] Add rate limiting
- [ ] Use XSS cleaning on inputs
- [ ] Use modern validation library
- [ ] Sanitize inputs based on type
- [ ] Log security events

### For File Operations:
- [ ] Validate file types and sizes
- [ ] Use secure file paths
- [ ] Check user permissions
- [ ] Generate secure filenames
- [ ] Log file operations
- [ ] Implement virus scanning if possible

### For Authentication:
- [ ] Use modern password hashing
- [ ] Implement account lockout
- [ ] Log login attempts
- [ ] Use strong password policies
- [ ] Implement session security

### For Database Operations:
- [ ] Use parameterized queries
- [ ] Validate all inputs
- [ ] Implement proper error handling
- [ ] Log sensitive operations
- [ ] Use transactions where appropriate

## ⚠️ Common Pitfalls to Avoid

### 1. Don't Use Direct Superglobals
```php
// ❌ DON'T DO THIS
$email = $_POST['email'];
$file = $_GET['file'];

// ✅ DO THIS INSTEAD
$email = $this->input->post('email', TRUE);
$file = $this->input->get('file', TRUE);
```

### 2. Don't Skip CSRF Validation
```php
// ❌ DON'T DO THIS
if ($_POST) {
    // Process form without CSRF check
}

// ✅ DO THIS INSTEAD
if ($this->input->server('REQUEST_METHOD') === 'POST') {
    if (!validate_csrf_token()) {
        // Handle CSRF attack
        return;
    }
    // Process form
}
```

### 3. Don't Use Weak File Validation
```php
// ❌ DON'T DO THIS
$config['allowed_types'] = '*';

// ✅ DO THIS INSTEAD
$config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
$config['max_size'] = 2048;
```

### 4. Don't Ignore Rate Limiting
```php
// ❌ DON'T DO THIS
// Process login without rate limiting

// ✅ DO THIS INSTEAD
if (!rate_limit_check('login_attempt', 5, 900)) {
    // Handle rate limit exceeded
    return;
}
```

## 🔍 Testing Your Implementation

### 1. Run the Test Suite
```bash
php run_modernization_tests.php
```

### 2. Manual Security Checks
- Try SQL injection in forms
- Test file upload with malicious files
- Attempt CSRF attacks
- Test rate limiting
- Verify password policies

### 3. Monitor Security Events
- Check the security dashboard regularly
- Review failed login attempts
- Monitor file upload activities
- Watch for suspicious patterns

## 📞 Getting Help

### Resources:
1. **Security Configuration:** `application/config/security_modern.php`
2. **Helper Functions:** `application/helpers/security_helper.php`
3. **Validation Library:** `application/libraries/Modern_validation.php`
4. **Security Monitor:** `application/libraries/Security_monitor.php`
5. **Example Implementation:** `application/controllers/Modern_form_example.php`

### When to Escalate:
- Multiple failed security tests
- Suspicious activity patterns
- Performance issues with new security measures
- Questions about implementation

## 🎓 Training Completion

After completing this training, team members should be able to:
- [ ] Implement secure form handling
- [ ] Use the new validation library
- [ ] Handle file uploads securely
- [ ] Monitor security events
- [ ] Follow security best practices
- [ ] Identify and avoid common pitfalls

**Remember:** Security is everyone's responsibility. When in doubt, choose the more secure option!
