<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if ($this->config->item('installed') == false) {
            redirect(site_url('install'));
        }
        
        // Check database connection first
        if (!$this->db->conn_id) {
            show_error('Database connection failed. Please check your database configuration.', 500, 'Database Error');
            return;
        }
        
        // Add error handling for database query
        try {
            $get_config = $this->db->get_where('global_settings', array('id' => 1))->row_array();
        } catch (Exception $e) {
            // Handle database error gracefully
            log_message('error', 'Database error in MY_Controller: ' . $e->getMessage());
            show_error('Database query failed: ' . $e->getMessage(), 500, 'Database Error');
            return;
        }
        
        // cache control
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        if (!is_null($get_config) && isset($get_config['cache_store']) && $get_config['cache_store'] == 0) {
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        } else {
            $this->output->set_header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        }
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        // checkout branch information
        $branchID = $this->application_model->get_branch_id();
        if (!empty($branchID)) {
            try {
                $branch = $this->db->select('currency_formats,symbol_position,symbol,currency,timezone')->where('id', $branchID)->get('branch')->row();
            } catch (Exception $e) {
                log_message('error', 'Branch data error in MY_Controller: ' . $e->getMessage());
                $branch = null;
            }
            if ($branch) {
                $get_config['currency'] = $branch->currency;
                $get_config['currency_symbol'] = $branch->symbol;
                $get_config['currency_formats'] = $branch->currency_formats;
                $get_config['symbol_position'] = $branch->symbol_position;
                if (!empty($branch->timezone)) {
                    $get_config['timezone'] = $branch->timezone;
                }
            }
        }
        
        // Ensure $get_config is an array to prevent undefined array key errors
        if (!is_array($get_config)) {
            $get_config = array();
        }
        
        $this->data['global_config'] = $get_config;
        try {
            $this->data['theme_config'] = $this->db->get_where('theme_settings', array('id' => 1))->row_array();
        } catch (Exception $e) {
            log_message('error', 'Theme config error in MY_Controller: ' . $e->getMessage());
            $this->data['theme_config'] = array();
        }
        if (!is_null($get_config) && isset($get_config['timezone'])) {
            date_default_timezone_set($get_config['timezone']);
        } else {
            date_default_timezone_set('UTC');
        }
    }

    public function get_payment_config()
    {
        $branchID = $this->application_model->get_branch_id();
        $this->db->where('branch_id', $branchID);
        $this->db->select('*')->from('payment_config');
        return $this->db->get()->row_array();
    }

    public function getBranchDetails()
    {
        $branchID = $this->application_model->get_branch_id();
        $this->db->select('*');
        $this->db->where('id', $branchID);
        $this->db->from('branch');
        $r = $this->db->get()->row_array();
        if (empty($r)) {
            return ['stu_generate' => "", 'grd_generate' => ""];
        } else {
            return $r;
        }
    }

    public function photoHandleUpload($str, $fields)
    {
        $allowedExts = array_map('trim', array_map('strtolower', explode(',', $this->data['global_config']['image_extension'])));
        $allowedSizeKB = $this->data['global_config']['image_size'];
        $allowedSize = floatval(1024 * $allowedSizeKB);
        if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {
            $file_size = $_FILES["$fields"]["size"];
            $file_name = $_FILES["$fields"]["name"];
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($files = filesize($_FILES["$fields"]['tmp_name'])) {
                if (!in_array(strtolower($extension), $allowedExts)) {
                    $this->form_validation->set_message('photoHandleUpload', translate('this_file_type_is_not_allowed'));
                    return false;
                }
                if ($file_size > $allowedSize) {
                    $this->form_validation->set_message('photoHandleUpload', translate('file_size_shoud_be_less_than') . " $allowedSizeKB KB.");
                    return false;
                }
            } else {
                $this->form_validation->set_message('photoHandleUpload', translate('error_reading_the_file'));
                return false;
            }
            return true;
        }
    }

    public function fileHandleUpload($str, $fields)
    {
        $allowedExts = array_map('trim', array_map('strtolower', explode(',', $this->data['global_config']['file_extension'])));
        $allowedSizeKB = $this->data['global_config']['file_size'];
        $allowedSize = floatval(1024 * $allowedSizeKB);
        if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {
            $file_size = $_FILES["$fields"]["size"];
            $file_name = $_FILES["$fields"]["name"];
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($files = filesize($_FILES["$fields"]['tmp_name'])) {
                if (!in_array(strtolower($extension), $allowedExts)) {
                    $this->form_validation->set_message('fileHandleUpload', translate('this_file_type_is_not_allowed'));
                    return false;
                }
                if ($file_size > $allowedSize) {
                    $this->form_validation->set_message('fileHandleUpload', translate('file_size_shoud_be_less_than') . " $allowedSizeKB KB.");
                    return false;
                }
            } else {
                $this->form_validation->set_message('fileHandleUpload', translate('error_reading_the_file'));
                return false;
            }
            return true;
        }
    }
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        
        // Temporary bypass for QR attendance Ajax methods
        $uri = $this->uri->uri_string();
        $is_qr_ajax = (strpos($uri, 'qrcode_attendance/getStuListDT') !== FALSE || strpos($uri, 'qrcode_attendance/getStaffListDT') !== FALSE);
        
        if (!is_loggedin() && !$is_qr_ajax) {
            $this->session->set_userdata('redirect_url', current_url());
            redirect(base_url('authentication'), 'refresh');
        }

        if (!$this->saas_model->checkSubscriptionValidity()) {
            redirect(base_url('dashboard'));
        }
    }
}

class Dashboard_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        if (!is_loggedin()) {
            $this->session->set_userdata('redirect_url', current_url());
            redirect(base_url('authentication'), 'refresh');
        }
    }
}

class User_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_student_loggedin() && !is_parent_loggedin()) {
            $this->session->set_userdata('redirect_url', current_url());
            redirect(base_url('authentication'), 'refresh');
        }
        $this->load->model('saas_model');
        if (!$this->saas_model->checkSubscriptionValidity()) {
            redirect(base_url('dashboard'));
        }
    }
}

class Authentication_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('authentication_model');
    }
}

class Frontend_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('saas_model');
        $branchID = $this->home_model->getDefaultBranch();
        $cms_setting = $this->db->get_where('front_cms_setting', array('branch_id' => $branchID))->row_array();
        if (!$cms_setting['cms_active']) {
            redirect(site_url('authentication'));
        } else {
            if (!$this->saas_model->checkSubscriptionValidity($branchID)) {
                $this->session->set_flashdata('website_expired_msg', '1');
                redirect(base_url());
            }
        }
        $this->data['cms_setting'] = $cms_setting;
    }
}
