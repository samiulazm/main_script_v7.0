<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $config = array(
            'field' => 'alias',
            'title' => 'title',
            'table' => 'front_cms_news_list',
            'id'    => 'id',
        );
        $this->load->library('slug', $config);
        $this->load->model('news_model');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
                'vendor/summernote/summernote.css',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
                'vendor/summernote/summernote.js',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.js',
            ),
        );
    }

    private function news_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('news_title', translate('news_title'), 'trim|required');
        $this->form_validation->set_rules('description', translate('description'), 'trim|required');
        $this->form_validation->set_rules('date', translate('date'), 'trim|required');
        $this->form_validation->set_rules('image', translate('image'), 'trim|callback_photoHandleUpload[image]');
        if (empty($_FILES['image']['name']) &&  empty($this->input->post('old_photo'))) {
            $this->form_validation->set_rules('image', translate('image'), 'required');
        }
    }

    /**
     * Handles listing and creation of frontend news items.
     *
     * On GET requests, displays the list of news items. On POST requests, validates and sanitizes input, checks permissions and CSRF token, and creates a new news item, returning a JSON response indicating success or validation errors.
     */
    public function index()
    {
        // check access permission
        if (!get_permission('frontend_news', 'is_view')) {
            access_denied();
        }
        // Modern POST handling with enhanced security
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            if (!get_permission('frontend_news', 'is_add')) {
                access_denied();
            }

            // CSRF protection
            if (!$this->security->verify_csrf_token()) {
                $array = ['status' => 'fail', 'error' => ['csrf' => 'Security token mismatch']];
                echo json_encode($array);
                exit();
            }

            $this->news_validation();
            if ($this->form_validation->run() !== false) {
                // Secure input handling
                $post_data = $this->input->post(NULL, TRUE); // XSS clean all POST data

                // Additional input sanitization
                $post_data = $this->sanitizePostData($post_data);

                // Save information in the database
                $this->news_model->save($post_data);
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

        $this->data['newslist'] = $this->app_lib->getTable('front_cms_news_list');
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/news';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        if (!get_permission('frontend_news', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->news_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->news_model->save($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('frontend/news/index');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['gallery'] = $this->news_model->get('front_cms_news_list', array('id' => $id), true);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/news_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (!get_permission('frontend_news', 'is_delete')) {
            access_denied();
        }
        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $row = $this->db->get_where('front_cms_news_list', array('id' => $id))->row();
        if (!empty($row)) {
            if ($this->db->where(array('id' => $id))->delete("front_cms_news_list")) {
                // delete news user image
                $destination = './uploads/frontend/news/';
                if (file_exists($destination . $row->image)) {
                    @unlink($destination . $row->image);
                }
            }
        }
    }

    /**
     * Updates the visibility status of a news item on the public website.
     *
     * Accepts POST data with the news item ID and desired visibility status, updates the corresponding record in the database, and returns a JSON response indicating success.
     */
    public function show_website()
    {
        if ($_POST) {
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            if ($status == 'true') {
                $arrayData['show_web'] = 1;
            } else {
                $arrayData['show_web'] = 0;
            }
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->update('front_cms_news_list', $arrayData);
            $return = array('msg' => translate('information_has_been_updated_successfully'), 'status' => true);
            echo json_encode($return);
        }
    }

    /**
     * Recursively sanitizes POST data to remove potentially unsafe content.
     *
     * Applies specific sanitization for email, URL, and phone fields, and strips HTML tags from all other fields.
     *
     * @param array $data The POST data to sanitize.
     * @return array The sanitized data array.
     */
    private function sanitizePostData($data)
    {
        if (!is_array($data)) {
            return [];
        }

        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizePostData($value);
            } else {
                // Additional sanitization based on field type
                switch ($key) {
                    case 'email':
                        $sanitized[$key] = filter_var($value, FILTER_SANITIZE_EMAIL);
                        break;
                    case 'url':
                    case 'website':
                        $sanitized[$key] = filter_var($value, FILTER_SANITIZE_URL);
                        break;
                    case 'phone':
                    case 'mobile':
                        $sanitized[$key] = preg_replace('/[^0-9+\-\s()]/', '', $value);
                        break;
                    default:
                        // General HTML and script tag removal
                        $sanitized[$key] = strip_tags($value);
                        break;
                }
            }
        }

        return $sanitized;
    }
}