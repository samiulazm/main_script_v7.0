<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Faq extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');
    }

    /**
     * Displays and manages the FAQ entries in the frontend CMS.
     *
     * Handles both GET and POST requests: displays the FAQ list and form on GET, and processes new FAQ submissions on POST with permission checks, CSRF protection, input validation, and secure data handling. Returns JSON responses for AJAX operations and loads the appropriate view for standard requests.
     */
    public function index()
    {
        // check access permission
        if (!get_permission('frontend_faq', 'is_view')) {
            access_denied();
        }
        // Modern POST handling with enhanced security
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            if (!get_permission('frontend_faq', 'is_add')) {
                access_denied();
            }

            // CSRF protection
            if (!$this->security->verify_csrf_token()) {
                $array = ['status' => 'fail', 'error' => ['csrf' => 'Security token mismatch']];
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($array));
                exit();
            }

            $this->services_validation();
            if ($this->form_validation->run() !== false) {
                // Secure input handling
                $post_data = $this->input->post(NULL, TRUE); // XSS clean all POST data

                // Save information in the database
                $this->frontend_model->save_faq($post_data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = ['status' => 'success'];
            } else {
                $error = $this->form_validation->error_array();
                $array = ['status' => 'fail', 'error' => $error];
            }

            // Secure JSON response
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($array));
            exit();
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
            ),
            'js' => array(
                'vendor/summernote/summernote.js',
            ),
        );
        $this->data['faqlist'] = $this->app_lib->getTable('front_cms_faq_list');
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/faq';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    // home features edit
    public function edit($id = '')
    {
        if (!get_permission('frontend_faq', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->services_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->frontend_model->save_faq($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('frontend/faq');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
            ),
            'js' => array(
                'vendor/summernote/summernote.js',
            ),
        );
        $this->data['faq'] = $this->app_lib->getTable('front_cms_faq_list', array('t.id' => $id), true);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/faq_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    // home features delete
    public function delete($id = '')
    {
        if (!get_permission('frontend_faq', 'is_delete')) {
            access_denied();
        }
        $this->db->where(array('id' => $id))->delete("front_cms_faq_list");
    }

    private function services_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('title', translate('title'), 'trim|required');
        $this->form_validation->set_rules('description', translate('description'), 'trim|required');
    }
}
