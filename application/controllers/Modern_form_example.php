<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modern Form Example Controller
 * 
 * Demonstrates how to update existing forms with new validation patterns
 * 
 * @package    CodeIgniter
 * @subpackage Controllers
 * @category   Example
 * @author     RamomCoder
 * @version    7.0 - Modernized
 */

class Modern_form_example extends Admin_Controller {

    /**
     * Initializes the controller with security helpers, validation library, and security configuration.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('security');
        $this->load->library('Modern_validation');
        $this->load->config('security_modern');
    }

    /****
     * Handles student registration with validation, CSRF protection, and rate limiting.
     *
     * Processes POST requests for student registration by validating input data, enforcing security measures, and saving valid student records to the database. Returns JSON responses for success, validation errors, or security issues. For GET requests, loads the student registration form view with a CSRF token.
     */
    public function student_registration() {
        if (!get_permission('student', 'is_add')) {
            access_denied();
        }

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
            if (!rate_limit_check('student_registration', 5, 300)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Too many registration attempts. Please try again later.'
                    ]));
                return;
            }

            // Modern validation rules
            $validation_rules = [
                'first_name' => [
                    'label' => 'First Name',
                    'rules' => 'required|min_length[2]|max_length[50]|alpha'
                ],
                'last_name' => [
                    'label' => 'Last Name',
                    'rules' => 'required|min_length[2]|max_length[50]|alpha'
                ],
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email|max_length[255]|is_unique[students.email]'
                ],
                'phone' => [
                    'label' => 'Phone',
                    'rules' => 'required|min_length[10]|max_length[20]|regex_match[/^[0-9+\-\s()]+$/]'
                ],
                'date_of_birth' => [
                    'label' => 'Date of Birth',
                    'rules' => 'required|valid_date'
                ],
                'admission_number' => [
                    'label' => 'Admission Number',
                    'rules' => 'required|alpha_numeric|min_length[5]|max_length[20]|is_unique[students.admission_number]'
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|strong_password'
                ],
                'confirm_password' => [
                    'label' => 'Confirm Password',
                    'rules' => 'required|matches[password]'
                ]
            ];

            // Get and sanitize input data
            $input_data = $this->input->post(NULL, TRUE); // XSS clean
            $sanitized_data = $this->sanitizeFormData($input_data);

            // Run validation
            $validator = new Modern_validation();
            $validator->setRules($validation_rules)->setData($sanitized_data);

            if ($validator->run()) {
                // Process the form data
                $student_data = $this->prepareStudentData($sanitized_data);
                
                try {
                    // Save to database
                    $student_id = $this->saveStudent($student_data);
                    
                    // Log successful registration
                    log_security_event('Student registered successfully', 'info', [
                        'student_id' => $student_id,
                        'email' => $sanitized_data['email']
                    ]);

                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'status' => 'success',
                            'message' => 'Student registered successfully',
                            'student_id' => $student_id
                        ]));

                } catch (Exception $e) {
                    log_security_event('Student registration failed', 'error', [
                        'error' => $e->getMessage(),
                        'email' => $sanitized_data['email']
                    ]);

                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'status' => 'error',
                            'message' => 'Registration failed. Please try again.'
                        ]));
                }

            } else {
                // Validation failed
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'validation_error',
                        'errors' => $validator->getErrors()
                    ]));
            }
            return;
        }

        // Load the form view
        $this->data['page_title'] = 'Student Registration';
        $this->data['csrf_token'] = $this->security->get_csrf_hash();
        $this->data['main_contents'] = $this->load->view('admin/modern_student_form', $this->data, true);
        $this->load->view('admin/layout/index', $this->data);
    }

    /****
     * Handles secure document upload for students with validation, CSRF protection, and rate limiting.
     *
     * For POST requests, validates permissions, CSRF token, rate limits, and file upload parameters. Processes the file upload if valid and returns a JSON response indicating success or error. For non-POST requests, loads the document upload form view with a CSRF token.
     */
    public function document_upload() {
        if (!get_permission('student', 'is_edit')) {
            access_denied();
        }

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

            // Rate limiting for file uploads
            if (!rate_limit_check('file_upload', 10, 300)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Too many upload attempts. Please try again later.'
                    ]));
                return;
            }

            // Validate file upload
            if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Please select a valid file'
                    ]));
                return;
            }

            // Get file upload configuration
            $upload_config = $this->config->item('file_upload');
            $allowed_types = $upload_config['allowed_types']['documents'];
            $max_size = $upload_config['max_size'];

            // Validate file upload
            $validation_result = validate_file_upload($_FILES['document'], $allowed_types, $max_size);
            
            if (!$validation_result['valid']) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => $validation_result['error']
                    ]));
                return;
            }

            // Process file upload
            try {
                $upload_result = $this->processFileUpload($_FILES['document']);
                
                if ($upload_result['success']) {
                    log_security_event('Document uploaded successfully', 'info', [
                        'filename' => $upload_result['filename'],
                        'student_id' => $this->input->post('student_id', TRUE)
                    ]);

                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'status' => 'success',
                            'message' => 'Document uploaded successfully',
                            'filename' => $upload_result['filename']
                        ]));
                } else {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'status' => 'error',
                            'message' => $upload_result['error']
                        ]));
                }

            } catch (Exception $e) {
                log_security_event('Document upload failed', 'error', [
                    'error' => $e->getMessage()
                ]);

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Upload failed. Please try again.'
                    ]));
            }
            return;
        }

        // Load the upload form view
        $this->data['page_title'] = 'Document Upload';
        $this->data['csrf_token'] = $this->security->get_csrf_hash();
        $this->data['main_contents'] = $this->load->view('admin/modern_upload_form', $this->data, true);
        $this->load->view('admin/layout/index', $this->data);
    }

    /**
     * Returns a sanitized version of the provided form data array, applying field-specific sanitization rules for email, phone, admission number, and names.
     *
     * @param array $data The form data to sanitize.
     * @return array The sanitized data array.
     */
    private function sanitizeFormData($data) {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'email':
                    $sanitized[$key] = sanitize_input($value, 'email');
                    break;
                case 'phone':
                    $sanitized[$key] = sanitize_input($value, 'phone');
                    break;
                case 'admission_number':
                    $sanitized[$key] = sanitize_input($value, 'alphanumeric');
                    break;
                case 'first_name':
                case 'last_name':
                    $sanitized[$key] = sanitize_input($value, 'string');
                    break;
                default:
                    $sanitized[$key] = sanitize_input($value, 'string');
                    break;
            }
        }

        return $sanitized;
    }

    /**
     * Formats and prepares student registration data for database insertion.
     *
     * Hashes the password, sets the creation timestamp, and assigns the current user's branch ID.
     *
     * @param array $data The sanitized student registration data.
     * @return array The prepared data array ready for database insertion.
     */
    private function prepareStudentData($data) {
        return [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'date_of_birth' => $data['date_of_birth'],
            'admission_number' => $data['admission_number'],
            'password' => hash_password_modern($data['password']),
            'created_at' => date('Y-m-d H:i:s'),
            'branch_id' => get_loggedin_branch_id()
        ];
    }

    /**
     * Inserts a new student record into the database.
     *
     * @param array $data The student data to be inserted.
     * @return int The ID of the newly inserted student record.
     */
    private function saveStudent($data) {
        $this->db->insert('students', $data);
        return $this->db->insert_id();
    }

    /**
     * Handles uploading a file to the student documents directory with a secure, randomly generated filename.
     *
     * Moves the uploaded file to a secure directory, creating the directory if it does not exist. Returns an array indicating success with the new filename and original name, or failure with an error message.
     *
     * @param array $file The uploaded file information from the $_FILES array.
     * @return array An array with 'success' (bool), and on success, 'filename' and 'original_name'; on failure, 'error'.
     */
    private function processFileUpload($file) {
        $upload_path = './uploads/student_documents/';
        
        // Ensure directory exists
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        // Generate secure filename
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $secure_filename = generate_secure_token(16) . '.' . $file_extension;
        $full_path = $upload_path . $secure_filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $full_path)) {
            return [
                'success' => true,
                'filename' => $secure_filename,
                'original_name' => $file['name']
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Failed to save uploaded file'
            ];
        }
    }
}
