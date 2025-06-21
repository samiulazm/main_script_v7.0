<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Extended Model Class with modern PHP practices
 *
 * @package    CodeIgniter
 * @subpackage Core
 * @category   Model
 * @author     RamomCoder
 * @version    7.0 - Modernized
 */
class MY_Model extends CI_Model {

	/**
	 * Initializes the model by calling the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Generates a secure hash of the provided password.
	 *
	 * Uses PHP's password_hash() if available; otherwise, falls back to SHA-512 hashing with the application's encryption key for legacy compatibility.
	 *
	 * @param string $password The plain text password to hash.
	 * @return string The resulting hashed password.
	 */
	public function hash($password) {
		// Use modern password hashing for new passwords
		if (function_exists('password_hash')) {
			return password_hash($password, PASSWORD_DEFAULT);
		}
		// Fallback for legacy compatibility
		return hash("sha512", $password . config_item("encryption_key"));
	}

	/**
	 * Verifies a plain text password against a stored hash using modern or legacy hashing methods.
	 *
	 * Uses PHP's password_verify() for modern hashes, or compares a SHA-512 hash with the encryption key for legacy hashes.
	 *
	 * @param string $password The plain text password to verify.
	 * @param string $hash The stored password hash.
	 * @return bool True if the password matches the hash, false otherwise.
	 */
	public function verify_password($password, $hash) {
		// Check if it's a modern hash
		if (password_get_info($hash)['algo'] !== null) {
			return password_verify($password, $hash);
		}
		// Legacy hash verification
		return hash_equals($hash, hash("sha512", $password . config_item("encryption_key")));
	}
	
	/**
	 * Handles secure image uploads for a specified user role with validation.
	 *
	 * Validates the user role, enforces allowed image types and size limits, ensures the upload directory exists, and securely deletes the previous user photo if applicable. Returns the new uploaded filename, the previous photo if no new upload occurred, or a default image name on failure.
	 *
	 * @param string $role The user role used to determine the upload directory (must be one of: student, teacher, admin, parent, staff).
	 * @param string $fields The form field name containing the image file.
	 * @return string The uploaded file name, the previous photo name, or 'default.png' if upload fails.
	 */
	public function uploadImage($role, $fields = "user_photo") {
		$return_photo = 'default.png'; // Fixed typo
		$old_user_photo = $this->input->post('old_user_photo', TRUE); // XSS clean

		// Validate role parameter
		$allowed_roles = ['student', 'teacher', 'admin', 'parent', 'staff'];
		if (!in_array($role, $allowed_roles, true)) {
			log_message('error', 'Invalid role provided for image upload: ' . $role);
			return $return_photo;
		}

		if (isset($_FILES[$fields]) && !empty($_FILES[$fields]['name'])) {
			// Enhanced security configuration
			$config = [
				'upload_path' => './uploads/images/' . $role . '/',
				'allowed_types' => 'jpg|jpeg|png|gif|webp', // Specific allowed types
				'max_size' => 2048, // 2MB limit
				'max_width' => 2000,
				'max_height' => 2000,
				'overwrite' => FALSE,
				'encrypt_name' => TRUE,
				'remove_spaces' => TRUE
			];

			// Ensure upload directory exists
			if (!is_dir($config['upload_path'])) {
				mkdir($config['upload_path'], 0755, true);
			}

			$this->load->library('upload', $config);

			if ($this->upload->do_upload($fields)) {
				// Remove previous photo securely
				if (!empty($old_user_photo) && $old_user_photo !== 'default.png') {
					$unlink_path = 'uploads/images/' . $role . '/';
					$full_path = $unlink_path . $old_user_photo;
					if (file_exists($full_path) && is_file($full_path)) {
						unlink($full_path); // Removed @ suppression
					}
				}
				$return_photo = $this->upload->data('file_name');

				// Log successful upload
				log_message('info', 'Image uploaded successfully: ' . $return_photo);
			} else {
				// Log upload errors
				log_message('error', 'Image upload failed: ' . $this->upload->display_errors('', ''));
			}
		} else {
			if (!empty($old_user_photo)) {
				$return_photo = $old_user_photo;
			}
		}

		return $return_photo;
	}

	/**
	 * Retrieves records from a database table with optional filtering and branch-based access control.
	 *
	 * Supports selecting specific columns, applying where conditions, and returning either a single row or multiple rows. If branch filtering is enabled and the user is not a superadmin, results are limited to the current branch. Returns an empty structured array for single-row queries with no result.
	 *
	 * @param string $table The name of the database table.
	 * @param array|null $where_array Optional associative array of where conditions.
	 * @param bool $single If true, returns a single row as an associative array; otherwise, returns an array of rows.
	 * @param bool $branch If true, applies branch-based filtering for multi-tenant support.
	 * @param string $columns Columns to select, defaults to '*'.
	 * @return array The query result as an associative array or array of rows. Returns an empty array or structured array if no result is found.
	 */
	public function get($table, $where_array = NULL, $single = false, $branch = false, $columns = '*') {
		// Input validation
		if (empty($table) || !is_string($table)) {
			log_message('error', 'Invalid table name provided to get() method');
			return $single ? [] : [];
		}

		// Store raw table name and sanitize table name to prevent SQL injection
		$raw_table = $table;
		$table = $this->db->escape_identifiers($table);

		try {
			$this->db->select($columns);

			// Apply where conditions with proper escaping
			if (is_array($where_array) && !empty($where_array)) {
				foreach ($where_array as $key => $value) {
					$this->db->where($key, $value);
				}
			}

			// Apply branch filtering for multi-tenant support
			if ($branch === true) {
				if (!is_superadmin_loggedin()) {
					$branch_id = get_loggedin_branch_id();
					if (!empty($branch_id)) {
						$this->db->where("branch_id", $branch_id);
					}
				}
			}

			// Set return method and ordering
			if ($single === true) {
				$method = 'row_array';
			} else {
				$method = 'result_array';
				$this->db->order_by('id', 'ASC');
			}

			$result = $this->db->get($table)->$method();

			// Return empty structure for single row queries when no result found
			if (empty($result) && $single === true) {
				$fields = $this->db->list_fields($table);
				$config = [];
				foreach ($fields as $field) {
					$config[$field] = "";
				}
				return $config;
			}

			return $result;

		} catch (Exception $e) {
			log_message('error', 'Database error in get() method: ' . $e->getMessage());
			return $single ? [] : [];
		}
	}

	/**
	 * Retrieves a single record by ID from the specified database table.
	 *
	 * Validates the table name and ID, then queries the table for the record with the given ID. Returns either a single object or an array of results based on the $single flag. Returns null or an empty array if inputs are invalid or on error.
	 *
	 * @param string $table The name of the database table.
	 * @param int|null $id The ID of the record to retrieve.
	 * @param bool $single If true, returns a single object; otherwise, returns an array.
	 * @return mixed The query result as an object, array, null, or empty array depending on input and $single.
	 */
	public function getSingle($table, $id = NULL, $single = false) {
		// Input validation
		if (empty($table) || !is_string($table)) {
			log_message('error', 'Invalid table name provided to getSingle() method');
			return $single ? null : [];
		}

		if ($id === NULL || !is_numeric($id)) {
			log_message('error', 'Invalid ID provided to getSingle() method');
			return $single ? null : [];
		}

		try {
			// Use Query Builder instead of raw SQL for better security
			$this->db->select('*');
			$this->db->from($table);
			$this->db->where('id', (int)$id); // Cast to int for security
			$this->db->limit(1);

			$query = $this->db->get();

			if ($single === true) {
				return $query->row();
			} else {
				return $query->result();
			}

		} catch (Exception $e) {
			log_message('error', 'Database error in getSingle() method: ' . $e->getMessage());
			return $single ? null : [];
		}
	}

	/**
	 * Handles secure file uploads with validation and configurable options.
	 *
	 * Validates input parameters, allowed file types, file size, and upload directory. Ensures the file is uploaded via HTTP POST, manages old file deletion, and returns the uploaded filename or the previous filename on failure.
	 *
	 * @param string $media_name The form field name containing the file.
	 * @param string $upload_path The directory path where the file will be uploaded.
	 * @param string $old_file The filename of the previous file to be replaced, if any.
	 * @param bool $enc Whether to encrypt the uploaded filename.
	 * @param array $allowed_types List of allowed file extensions. Defaults to common document and image types if not specified.
	 * @param int $max_size Maximum allowed file size in kilobytes.
	 * @return string The uploaded filename on success, or the old filename if upload fails.
	 */
	public function fileupload($media_name, $upload_path = "", $old_file = '', $enc = true, $allowed_types = [], $max_size = 2048) {
		// Input validation
		if (empty($media_name) || empty($upload_path)) {
			log_message('error', 'Invalid parameters provided to fileupload() method');
			return !empty($old_file) ? $old_file : "";
		}

		// Check if file was uploaded
		if (!isset($_FILES[$media_name]) || $_FILES[$media_name]['error'] === UPLOAD_ERR_NO_FILE) {
			return !empty($old_file) ? $old_file : "";
		}

		// Check for upload errors
		if ($_FILES[$media_name]['error'] !== UPLOAD_ERR_OK) {
			log_message('error', 'File upload error: ' . $_FILES[$media_name]['error']);
			return !empty($old_file) ? $old_file : "";
		}

		// Validate file exists and is uploaded file
		if (!is_uploaded_file($_FILES[$media_name]['tmp_name'])) {
			log_message('error', 'Security violation: File not uploaded via HTTP POST');
			return !empty($old_file) ? $old_file : "";
		}

		// Set default allowed types if not provided
		if (empty($allowed_types)) {
			$allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];
		}

		// Enhanced upload configuration
		$config = [
			'upload_path' => rtrim($upload_path, '/') . '/',
			'allowed_types' => is_array($allowed_types) ? implode('|', $allowed_types) : $allowed_types,
			'max_size' => $max_size,
			'encrypt_name' => $enc,
			'overwrite' => FALSE,
			'remove_spaces' => TRUE
		];

		// Ensure upload directory exists and is writable
		if (!is_dir($config['upload_path'])) {
			if (!mkdir($config['upload_path'], 0755, true)) {
				log_message('error', 'Failed to create upload directory: ' . $config['upload_path']);
				return !empty($old_file) ? $old_file : "";
			}
		}

		if (!is_writable($config['upload_path'])) {
			log_message('error', 'Upload directory is not writable: ' . $config['upload_path']);
			return !empty($old_file) ? $old_file : "";
		}

		$this->load->library('upload', $config);

		if ($this->upload->do_upload($media_name)) {
			// Remove old file securely
			if (!empty($old_file)) {
				$old_file_path = $config['upload_path'] . $old_file;
				if (file_exists($old_file_path) && is_file($old_file_path)) {
					unlink($old_file_path);
				}
			}

			$uploaded_file = $this->upload->data('file_name');
			log_message('info', 'File uploaded successfully: ' . $uploaded_file);
			return $uploaded_file;
		} else {
			log_message('error', 'File upload failed: ' . $this->upload->display_errors('', ''));
			return !empty($old_file) ? $old_file : "";
		}
	}
}
