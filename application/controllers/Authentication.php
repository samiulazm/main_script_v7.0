<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 7.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Authentication.php
 * @copyright : Reserved RamomCoder Team
 */

class Authentication extends Authentication_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handles user login, authentication, and session initialization.
     *
     * Validates login credentials, applies rate limiting to prevent brute-force attacks, and manages user sessions for different roles (staff, parent, student). Supports multi-branch (school) environments, disables login for inactive accounts or branches, and integrates optional two-factor authentication if enabled. Redirects authenticated users to the dashboard or a previously stored URL. Loads school details for display on the login page.
     *
     * @param string $url_alias Optional URL alias to identify the school branch for login context.
     */
    public function index($url_alias = '')
    {
        if (is_loggedin()) {
            redirect(base_url('dashboard'));
        }

        // Modern POST data handling with proper validation
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Enhanced validation rules with modern syntax
            $rules = [
                [
                    'field' => 'email',
                    'label' => "Email",
                    'rules' => 'trim|required|valid_email|max_length[255]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please enter a valid email address',
                        'max_length' => 'Email must not exceed 255 characters'
                    ]
                ],
                [
                    'field' => 'password',
                    'label' => "Password",
                    'rules' => 'trim|required|min_length[6]|max_length[255]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 6 characters',
                        'max_length' => 'Password must not exceed 255 characters'
                    ]
                ],
            ];
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() !== false) {
                // Secure input handling with XSS protection
                $email = $this->input->post('email', TRUE); // XSS clean
                $password = $this->input->post('password', FALSE); // Don't XSS clean passwords

                // Load security helper for rate limiting
                $this->load->helper('security');

                // Rate limiting for login attempts
                if (!rate_limit_check('login_attempt', 5, 900)) {
                    $this->session->set_flashdata('error', 'Too many login attempts. Please try again later.');
                    redirect(base_url('authentication'));
                    return;
                }

                // Validate credentials
                $login_credential = $this->authentication_model->login_credential($email, $password);
                if ($login_credential) {
                    if ($login_credential->active) {
                        $getUser = $this->authentication_model->getUserNameByRoleID($login_credential->role, $login_credential->user_id);
                        $getConfig = $this->db->select('translation,session_id')->get_where('global_settings', array('id' => 1))->row();
                        $language = $getConfig->translation;
                        $sessionID = $getConfig->session_id;
                        if($this->app_lib->isExistingAddon('saas')) {
                            if ($login_credential->role != 1) {
                                $schoolSettings = $this->db->select('id,translation')->where(array('id' => $getUser['branch_id'], 'status' => 1))->get('branch')->row();
                                if (empty($schoolSettings)) {
                                    set_alert('error', translate('inactive_school'));
                                    redirect(base_url('authentication'));
                                    exit();
                                }
                            }
                            if ($login_credential->role != 1) {
                                $language = $schoolSettings->translation;
                            }
                        }
                        // login user type
                        if ($login_credential->role == 6) {
                            $userType = 'parent';
                            // check parent login status
                            $getParentLoginStatus = $this->authentication_model->getParentLoginStatus($getUser['branch_id']);
                            if ($getParentLoginStatus == 0) {
                                set_alert('error', translate('parent_login_has_been_disabled'));
                                redirect(base_url('authentication'));
                                exit();
                            }
                        } elseif($login_credential->role == 7) {
                            $studentID = $getUser['id'];
                            $this->session->set_userdata('student_id', $studentID);
                            // check student login status
                            $getStudentLoginStatus = $this->authentication_model->getStudentLoginStatus($getUser['branch_id']);
                            if ($getStudentLoginStatus == 0) {
                                set_alert('error', translate('student_login_has_been_disabled'));
                                redirect(base_url('authentication'));
                                exit();
                            }
                            $studentSession = $this->application_model->getEnrollID($studentID, $sessionID);
                            if (is_array($studentSession)) {
                                $this->session->set_userdata('enrollID', $studentSession['id']);
                                $sessionID = $studentSession['session_id'];
                            } else {
                                $this->session->set_userdata('enrollID', $studentSession);
                            }
                            $userType = 'student';
                        } else {
                            $userType = 'staff';
                        }
                        $isRTL = $this->app_lib->getRTLStatus($language);
                        // get logger name
                        $sessionData = array(
                            'name' => $getUser['name'],
                            'logger_photo' => $getUser['photo'],
                            'loggedin_branch' => $getUser['branch_id'],
                            'loggedin_email' => $getUser['email'],
                            'loggedin_id' => $login_credential->id,
                            'loggedin_userid' => $login_credential->user_id,
                            'loggedin_role_id' => $login_credential->role,
                            'loggedin_type' => $userType,
                            'set_lang' =>  $language,
                            'is_rtl' => $isRTL,
                            'set_session_id' => $sessionID,
                            'loggedin' => true,
                        );

                        // two factor authentication
                        if($this->app_lib->isExistingAddon('two_fa') && moduleIsEnabled('two_fa')) {
                            $this->load->model('two_fa_model');
                            $manage_2FA = $this->two_fa_model->manage_2fa($sessionData, $login_credential, $getUser['email']);
                            if ($manage_2FA) {
                                $this->session->set_userdata('2FA', $sessionData);
                                redirect(base_url($this->authentication_model->getSegment(1) . 'two_fa_verification'));
                            } else {
                                $this->authentication_model->sessionSet($sessionData);
                            }
                        } else {
                            $this->authentication_model->sessionSet($sessionData);
                        }
                        
                        // is logged in
                        if ($this->session->has_userdata('redirect_url')) {
                            redirect($this->session->userdata('redirect_url'));
                        } else {
                            redirect(base_url('dashboard'));
                        }
                    } else {
                        set_alert('error', translate('inactive_account'));
                        redirect(base_url('authentication'));
                    }
                } else {
                    set_alert('error', translate('username_password_incorrect'));
                    redirect(base_url('authentication'));
                }
            }
        }
        $this->data['branch_id'] = $this->authentication_model->urlaliasToBranch($url_alias);
        $schoolDeatls = $this->authentication_model->getSchoolDeatls($url_alias);
        if (!empty($schoolDeatls) && is_object($schoolDeatls)) {
            $this->data['global_config']['institute_name'] = $schoolDeatls->school_name;
            $this->data['global_config']['address'] = $schoolDeatls->address;
            $this->data['global_config']['facebook_url'] = $schoolDeatls->facebook_url;
            $this->data['global_config']['twitter_url'] = $schoolDeatls->twitter_url;
            $this->data['global_config']['linkedin_url'] = $schoolDeatls->linkedin_url;
            $this->data['global_config']['youtube_url'] = $schoolDeatls->youtube_url;
        }
        $this->load->view('authentication/login', $this->data);
    }

    /**
     * Handles the initiation of a password reset request for users who have forgotten their password.
     *
     * Validates the provided email address, applies rate limiting to prevent abuse, and triggers the password reset process if the email is valid. Displays appropriate feedback messages and loads the password reset request view.
     *
     * @param string $url_alias Optional URL alias used to load branch-specific school details.
     */
    public function forgot($url_alias = '')
    {
        if (is_loggedin()) {
            redirect(base_url('dashboard'), 'refresh');
        }

        // Modern POST handling for forgot password
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Enhanced validation with modern array syntax
            $config = [
                [
                    'field' => 'username',
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email|max_length[255]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please enter a valid email address',
                        'max_length' => 'Email must not exceed 255 characters'
                    ]
                ],
            ];
            $this->form_validation->set_rules($config);
            if ($this->form_validation->run() !== false) {
                // Secure input handling
                $username = $this->input->post('username', TRUE); // XSS clean

                // Rate limiting for password reset attempts
                if (!rate_limit_check('password_reset', 3, 3600)) {
                    $this->session->set_flashdata('reset_res', 'rate_limited');
                    redirect(base_url('authentication/forgot'));
                    return;
                }

                // Process password reset
                $res = $this->authentication_model->lose_password($username);

                if ($res === true) {
                    $this->session->set_flashdata('reset_res', 'true');
                } else {
                    $this->session->set_flashdata('reset_res', 'false');
                }

                redirect(base_url('authentication/forgot'));
            }
        }
        $this->data['branch_id'] = $this->authentication_model->urlaliasToBranch($url_alias);
        $schoolDeatls = $this->authentication_model->getSchoolDeatls($url_alias);
        if (!empty($schoolDeatls) && is_object($schoolDeatls)) {
            $this->data['global_config']['institute_name'] = $schoolDeatls->school_name;
            $this->data['global_config']['address'] = $schoolDeatls->address;
            $this->data['global_config']['facebook_url'] = $schoolDeatls->facebook_url;
            $this->data['global_config']['twitter_url'] = $schoolDeatls->twitter_url;
            $this->data['global_config']['linkedin_url'] = $schoolDeatls->linkedin_url;
            $this->data['global_config']['youtube_url'] = $schoolDeatls->youtube_url;
        }
        $this->load->view('authentication/forgot', $this->data);
    }

    /* password reset */
    public function pwreset()
    {
        if (is_loggedin()) {
            redirect(base_url('dashboard'), 'refresh');
        }

        $key = $this->input->get('key');
        if (!empty($key)) {
            $query = $this->db->get_where('reset_password', array('key' => $key));
            if ($query->num_rows() > 0) {
                if ($this->input->post()) {
                    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|matches[c_password]');
                    $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|min_length[4]');
                    if ($this->form_validation->run() !== false) {
                        $password = $this->app_lib->pass_hashed($this->input->post('password'));
                        $this->db->where('id', $query->row()->login_credential_id);
                        $this->db->update('login_credential', array('password' => $password));
                        $this->db->where('login_credential_id', $query->row()->login_credential_id);
                        $this->db->delete('reset_password');
                        set_alert('success', 'Password Reset Successfully');
                        redirect(base_url('authentication'));
                    }
                }
                $this->load->view('authentication/pwreset', $this->data);
            } else {
                set_alert('error', 'Token Has Expired');
                redirect(base_url('authentication'));
            }
        } else {
            set_alert('error', 'Token Has Expired');
            redirect(base_url('authentication'));
        }
    }

    /* session logout */
    public function logout()
    {
        $webURL = base_url();
        if (!is_superadmin_loggedin()) {
            $cmsRow = $this->db->select('cms_active,url_alias')
            ->where('branch_id', get_loggedin_branch_id())
            ->get('front_cms_setting')->row_array();
            if (isset($cmsRow['cms_active']) && $cmsRow['cms_active'] == 1) {
                $webURL = base_url((isset($cmsRow['url_alias']) ? $cmsRow['url_alias'] : '') );
            }
        }

        $this->session->unset_userdata('name');
        $this->session->unset_userdata('logger_photo');
        $this->session->unset_userdata('loggedin_id');
        $this->session->unset_userdata('loggedin_userid');
        $this->session->unset_userdata('loggedin_type');
        $this->session->unset_userdata('set_lang');
        $this->session->unset_userdata('set_session_id');
        $this->session->unset_userdata('loggedin_branch');
        $this->session->unset_userdata('loggedin');
        $this->session->sess_destroy();
        redirect($webURL, 'refresh');
    }

}
