<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 6.9.1
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Tipsoi_device.php
 * @copyright : Reserved RamomCoder Team
 */

class Tipsoi_device extends Admin_Controller
{
    protected $default_api_token;
    protected $api_base_url;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('attendance_model');
        $this->load->model('tipsoi_device_model');
        if (!moduleIsEnabled('attendance')) {
            access_denied();
        }
        
        // API Configuration
        $this->default_api_token = 'd8dd-cfbf-fa81-e1b8-54ee-fbbe-cb7a-8026-48d7-1653-4942-f499-6b04-a0b9-89c9-7309';
        $this->api_base_url = 'https://api-inovace360.com';
    }

    /**
     * Get API token for a specific branch
     */
    private function get_api_token($branch_id)
    {
        $device_config = $this->tipsoi_device_model->get_device_config($branch_id);
        
        // Return branch-specific token if available, otherwise use default
        if ($device_config && !empty($device_config['api_token'])) {
            return $device_config['api_token'];
        }
        
        return $this->default_api_token;
    }

    public function index()
    {
        if (!get_permission('student_attendance', 'is_add')) {
            access_denied();
        }

        // Ensure database tables exist and schema is updated
        $this->tipsoi_device_model->ensure_tables_exist();
        $this->tipsoi_device_model->update_table_schema();

        $branchID = $this->application_model->get_branch_id();
        
        // Handle form submission for syncing attendance
        if ($this->input->post('sync_attendance')) {
            $this->sync_attendance_from_api();
        }

        // Handle form submission for device configuration
        if ($this->input->post('save_config')) {
            $this->save_device_config();
        }

        // Get device configuration
        $this->data['device_config'] = $this->tipsoi_device_model->get_device_config($branchID);
        
        // Get recent sync logs
        $this->data['sync_logs'] = $this->tipsoi_device_model->get_sync_logs($branchID, 10);
        
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Tipsoi Device Management';
        $this->data['sub_page'] = 'tipsoi_device/index';
        $this->data['main_menu'] = 'attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function sync_attendance_from_api()
    {
        if (!get_permission('student_attendance', 'is_add')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $date = $this->input->post('sync_date') ?: date('Y-m-d');
        
        try {
            // Get attendance data from Tipsoi API
            $attendance_data = $this->fetch_attendance_from_api($date, $branchID);
            
            if ($attendance_data && isset($attendance_data['attendances']['data'])) {
                $daily_result = $this->process_daily_attendance($attendance_data['attendances']['data'], $date, $branchID);
                $synced_count = $daily_result['synced'];
                $error_count = $daily_result['errors'];
                $errors = $daily_result['error_messages'];

                // Log sync results
                $this->tipsoi_device_model->log_sync_result($branchID, $date, $synced_count, $error_count, $errors);

                if ($synced_count > 0) {
                    set_alert('success', "Successfully synced {$synced_count} attendance records.");
                }
                if ($error_count > 0) {
                    set_alert('warning', "Failed to sync {$error_count} records. Check sync logs for details.");
                }
            } else {
                set_alert('error', 'No attendance data received from API.');
            }
        } catch (Exception $e) {
            $this->tipsoi_device_model->log_sync_result($branchID, $date, 0, 1, [$e->getMessage()]);
            set_alert('error', 'API sync failed: ' . $e->getMessage());
        }

        redirect(current_url());
    }

    private function fetch_attendance_from_api($date, $branch_id)
    {
        // Get branch-specific API token
        $api_token = $this->get_api_token($branch_id);
        
        // Build URL with query parameters for the correct API endpoint
        $url = $this->api_base_url . '/api/v1/attendance_logs';
        $url .= '?api_token=' . urlencode($api_token);
        $url .= '&start=' . urlencode($date);
        $url .= '&end=' . urlencode($date);
        
        $headers = [
            'Accept: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new Exception('CURL Error: ' . curl_error($ch));
        }
        
        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception('API returned HTTP ' . $http_code . ': ' . $response);
        }

        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from API');
        }

        return $data;
    }

    private function determine_attendance_status($timestamp, $device_id)
    {
        // Convert timestamp to time
        $time = date('H:i:s', strtotime($timestamp));
        $hour = (int)date('H', strtotime($timestamp));

        // Basic logic - can be customized based on school requirements
        if ($hour < 8) {
            return 'P'; // Present - Early arrival
        } elseif ($hour >= 8 && $hour < 10) {
            return 'P'; // Present - On time
        } elseif ($hour >= 10 && $hour < 12) {
            return 'L'; // Late
        } else {
            return 'H'; // Half day
        }
    }

    private function save_device_config()
    {
        $branchID = $this->application_model->get_branch_id();
        
        $config_data = [
            'branch_id' => $branchID,
            'device_name' => $this->input->post('device_name'),
            'api_token' => $this->input->post('api_token'),
            'sync_interval' => $this->input->post('sync_interval'),
            'auto_sync_enabled' => $this->input->post('auto_sync_enabled') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $existing_config = $this->tipsoi_device_model->get_device_config($branchID);
        
        if ($existing_config) {
            $this->db->where('branch_id', $branchID);
            $this->db->update('tipsoi_device_config', $config_data);
        } else {
            $config_data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('tipsoi_device_config', $config_data);
        }

        set_alert('success', 'Device configuration saved successfully.');
        redirect(current_url());
    }

    public function manual_sync()
    {
        if (!get_permission('student_attendance', 'is_add')) {
            access_denied();
        }

        $date = $this->input->get('date') ?: date('Y-m-d');
        
        // Perform manual sync
        $_POST['sync_date'] = $date;
        $this->sync_attendance_from_api();
    }

    public function view_sync_logs()
    {
        if (!get_permission('student_attendance', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $this->data['sync_logs'] = $this->tipsoi_device_model->get_sync_logs($branchID, 50);
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Tipsoi Device Sync Logs';
        $this->data['sub_page'] = 'tipsoi_device/sync_logs';
        $this->data['main_menu'] = 'attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function test_api_connection()
    {
        if (!get_permission('student_attendance', 'is_add')) {
            access_denied();
        }

        try {
            $branchID = $this->application_model->get_branch_id();
            $api_token = $this->get_api_token($branchID);
            
            // Test with a small date range to check API connectivity
            $test_date = date('Y-m-d');
            $url = $this->api_base_url . '/api/v1/attendance_logs';
            $url .= '?api_token=' . urlencode($api_token);
            $url .= '&start=' . urlencode($test_date);
            $url .= '&end=' . urlencode($test_date);
            
            $headers = [
                'Accept: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                throw new Exception('CURL Error: ' . curl_error($ch));
            }
            
            curl_close($ch);

            if ($http_code === 200) {
                $data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    echo json_encode(['status' => 'success', 'message' => 'API connection successful', 'response' => $response]);
                } else {
                    echo json_encode(['status' => 'warning', 'message' => 'API connected but returned invalid JSON', 'response' => $response]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'API returned HTTP ' . $http_code, 'response' => $response]);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Cron job for syncing last 24 hours attendance data
     * Runs every 10 minutes
     */
    public function cron_sync_24hours()
    {
        // Verify this is being called from cron or authorized source
        if (!$this->is_cron_authorized()) {
            show_404();
            return;
        }

        $this->load->model('tipsoi_device_model');
        $this->tipsoi_device_model->ensure_tables_exist();

        // Get all branches with auto sync enabled
        $branches = $this->get_auto_sync_branches();
        
        $total_synced = 0;
        $total_errors = 0;
        $sync_results = [];

        foreach ($branches as $branch) {
            try {
                // Sync last 24 hours
                $date_from = date('Y-m-d', strtotime('-24 hours'));
                $date_to = date('Y-m-d');
                
                $result = $this->sync_date_range($branch['id'], $date_from, $date_to, 'cron_24h');
                $total_synced += $result['synced'];
                $total_errors += $result['errors'];
                $sync_results[] = $result;
                
            } catch (Exception $e) {
                $this->tipsoi_device_model->log_sync_result($branch['id'], date('Y-m-d'), 0, 1, ['Cron 24h sync error: ' . $e->getMessage()]);
                $total_errors++;
            }
        }

        // Log overall cron result
        $this->log_cron_execution('24h_sync', $total_synced, $total_errors, $sync_results);
        
        echo json_encode([
            'status' => 'success',
            'message' => "24h Cron sync completed. Synced: {$total_synced}, Errors: {$total_errors}",
            'details' => $sync_results
        ]);
    }

    /**
     * Cron job for syncing monthly attendance data
     * Runs monthly for data from one month ago
     */
    public function cron_sync_monthly()
    {
        // Verify this is being called from cron or authorized source
        if (!$this->is_cron_authorized()) {
            show_404();
            return;
        }

        $this->load->model('tipsoi_device_model');
        $this->tipsoi_device_model->ensure_tables_exist();

        // Get all branches
        $branches = $this->get_all_branches();
        
        $total_synced = 0;
        $total_errors = 0;
        $sync_results = [];

        foreach ($branches as $branch) {
            try {
                // Sync one month ago data
                $one_month_ago = date('Y-m-d', strtotime('-1 month'));
                $month_start = date('Y-m-01', strtotime($one_month_ago));
                $month_end = date('Y-m-t', strtotime($one_month_ago));
                
                $result = $this->sync_date_range($branch['id'], $month_start, $month_end, 'cron_monthly');
                $total_synced += $result['synced'];
                $total_errors += $result['errors'];
                $sync_results[] = $result;
                
            } catch (Exception $e) {
                $this->tipsoi_device_model->log_sync_result($branch['id'], date('Y-m-d'), 0, 1, ['Cron monthly sync error: ' . $e->getMessage()]);
                $total_errors++;
            }
        }

        // Log overall cron result
        $this->log_cron_execution('monthly_sync', $total_synced, $total_errors, $sync_results);
        
        echo json_encode([
            'status' => 'success',
            'message' => "Monthly Cron sync completed. Synced: {$total_synced}, Errors: {$total_errors}",
            'details' => $sync_results
        ]);
    }

    /**
     * Sync attendance for a date range
     */
    private function sync_date_range($branch_id, $date_from, $date_to, $sync_type = 'manual')
    {
        $synced_count = 0;
        $error_count = 0;
        $all_errors = [];

        $current_date = $date_from;
        while ($current_date <= $date_to) {
            try {
                $attendance_data = $this->fetch_attendance_from_api($current_date, $branch_id);
                
                if ($attendance_data && isset($attendance_data['attendances']['data'])) {
                    $daily_result = $this->process_daily_attendance($attendance_data['attendances']['data'], $current_date, $branch_id);
                    $synced_count += $daily_result['synced'];
                    $error_count += $daily_result['errors'];
                    $all_errors = array_merge($all_errors, $daily_result['error_messages']);
                }
                
                // Small delay to avoid overwhelming the API
                usleep(100000); // 0.1 second delay
                
            } catch (Exception $e) {
                $error_count++;
                $all_errors[] = "Error syncing {$current_date}: " . $e->getMessage();
            }
            
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }

        // Log the sync result
        $this->tipsoi_device_model->log_sync_result($branch_id, $date_from . ' to ' . $date_to, $synced_count, $error_count, $all_errors);

        return [
            'branch_id' => $branch_id,
            'date_range' => $date_from . ' to ' . $date_to,
            'synced' => $synced_count,
            'errors' => $error_count,
            'sync_type' => $sync_type
        ];
    }

    /**
     * Process daily attendance data with first-attendance-only logic
     */
    private function process_daily_attendance($attendance_records, $date, $branch_id)
    {
        $synced_count = 0;
        $error_count = 0;
        $errors = [];
        $processed_students = []; // Track students already processed for this date

        foreach ($attendance_records as $record) {
            try {
                // Skip if we already processed this student for this date
                if (in_array($record['person_identifier'], $processed_students)) {
                    continue; // Ignore subsequent attendances for the same day
                }

                // Check if this person has attendance logs for the requested date
                if (!isset($record['logs'][$date])) {
                    continue; // No attendance for this date
                }

                $attendance_log = $record['logs'][$date];
                
                // Skip if no start time
                if (!isset($attendance_log['start'])) {
                    continue;
                }

                // Match person_identifier with register_no
                $student = $this->tipsoi_device_model->get_student_by_register_no($record['person_identifier']);
                
                if ($student) {
                    // Get enrollment info
                    $enrollment = $this->tipsoi_device_model->get_student_enrollment($student['id'], $branch_id);
                    
                    if ($enrollment) {
                        // Check if attendance already exists for this date
                        $existing = $this->tipsoi_device_model->get_attendance_by_date($enrollment['id'], $date);
                        
                        if (!$existing) {
                            // Convert timestamp to attendance status
                            $status = $this->determine_attendance_status($attendance_log['start'], 'tipsoi_device');
                            
                            // Save attendance record
                            $attendance_record = [
                                'enroll_id' => $enrollment['id'],
                                'date' => $date,
                                'status' => $status,
                                'remark' => 'Synced from Tipsoi Device at ' . $attendance_log['start'],
                                'branch_id' => $branch_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ];

                            $this->db->insert('student_attendance', $attendance_record);
                            $synced_count++;
                        }
                        
                        // Mark this student as processed for this date
                        $processed_students[] = $record['person_identifier'];
                        
                    } else {
                        $errors[] = "No enrollment found for student: " . $record['person_identifier'];
                        $error_count++;
                    }
                } else {
                    $errors[] = "No student found with register_no: " . $record['person_identifier'];
                    $error_count++;
                }
            } catch (Exception $e) {
                $errors[] = "Error processing record for " . $record['person_identifier'] . ": " . $e->getMessage();
                $error_count++;
            }
        }

        return [
            'synced' => $synced_count,
            'errors' => $error_count,
            'error_messages' => $errors
        ];
    }

    /**
     * Check if cron request is authorized
     */
    private function is_cron_authorized()
    {
        // Check for cron key in URL or header
        $cron_key = $this->input->get('cron_key') ?: $this->input->get_request_header('X-Cron-Key');
        $expected_key = 'tipsoi_cron_' . md5($this->default_api_token);
        
        // Also allow localhost and server IP
        $allowed_ips = ['127.0.0.1', '::1', $_SERVER['SERVER_ADDR']];
        $client_ip = $this->input->ip_address();
        
        return ($cron_key === $expected_key) || in_array($client_ip, $allowed_ips);
    }

    /**
     * Get branches with auto sync enabled
     */
    private function get_auto_sync_branches()
    {
        $this->db->select('branch.id, branch.name, tdc.auto_sync_enabled');
        $this->db->from('branch');
        $this->db->join('tipsoi_device_config tdc', 'tdc.branch_id = branch.id', 'inner');
        $this->db->where('tdc.auto_sync_enabled', 1);
        return $this->db->get()->result_array();
    }

    /**
     * Get all branches
     */
    private function get_all_branches()
    {
        $this->db->select('id, name');
        $this->db->from('branch');
        return $this->db->get()->result_array();
    }

    /**
     * Log cron execution results
     */
    private function log_cron_execution($cron_type, $total_synced, $total_errors, $details)
    {
        $log_data = [
            'cron_type' => $cron_type,
            'total_synced' => $total_synced,
            'total_errors' => $total_errors,
            'execution_time' => date('Y-m-d H:i:s'),
            'details' => json_encode($details)
        ];

        // Create cron log table if it doesn't exist
        $this->tipsoi_device_model->ensure_cron_log_table();
        
        $this->db->insert('tipsoi_cron_logs', $log_data);
    }

    /**
     * View cron job logs
     */
    public function cron_logs()
    {
        if (!get_permission('student_attendance', 'is_view')) {
            access_denied();
        }

        $this->data['cron_logs'] = $this->tipsoi_device_model->get_cron_logs(50);
        $this->data['title'] = 'Tipsoi Device Cron Logs';
        $this->data['sub_page'] = 'tipsoi_device/cron_logs';
        $this->data['main_menu'] = 'attendance';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Manual trigger for testing cron jobs
     */
    public function test_cron()
    {
        if (!get_permission('student_attendance', 'is_add')) {
            access_denied();
        }

        $cron_type = $this->input->get('type');
        
        if ($cron_type === '24h') {
            $this->cron_sync_24hours();
        } elseif ($cron_type === 'monthly') {
            $this->cron_sync_monthly();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid cron type']);
        }
    }

    public function api_token_settings()
    {
        if (!get_permission('student_attendance', 'is_add')) { // Or a more specific config permission
            access_denied();
        }

        $branches = $this->get_all_branches(); // Assuming get_all_branches is accessible
        $branch_configs = [];
        foreach ($branches as $branch) {
            $config = $this->tipsoi_device_model->get_device_config($branch['id']);
            $branch_configs[$branch['id']] = [
                'id' => $branch['id'],
                'name' => $branch['name'],
                'api_token' => isset($config['api_token']) ? $config['api_token'] : '',
                'device_name' => isset($config['device_name']) ? $config['device_name'] : 'Tipsoi - ' . $branch['name']
            ];
        }

        $this->data['branch_configs'] = $branch_configs;
        $this->data['title'] = 'Tipsoi API Token Settings';
        $this->data['sub_page'] = 'tipsoi_device/api_token_settings';
        $this->data['main_menu'] = 'attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function save_branch_api_token()
    {
        if (!get_permission('student_attendance', 'is_add')) { // Or a more specific config permission
            access_denied();
        }

        if ($this->input->post('save_api_token')) {
            $branch_id = $this->input->post('branch_id');
            $api_token = $this->input->post('api_token');

            if (empty($branch_id)) {
                set_alert('error', 'Branch ID is missing.');
                redirect(base_url('tipsoi_device/api_token_settings'));
                return;
            }
            
            // Consider adding validation for the API token format if necessary

            $this->tipsoi_device_model->save_branch_api_token_config($branch_id, $api_token);
            set_alert('success', 'API Token updated successfully for the branch.');
        }
        redirect(base_url('tipsoi_device/api_token_settings'));
    }
} 