<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attribute_set extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model('attribute_model');

        if (!has_permissions('read', 'attribute_set')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'attribute-set';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Add Attribute Set | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Add Attribute Set  | ' . $settings['app_name'];
            if (isset($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details('attribute_set', ['id' => $_GET['edit_id']]);
            }
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function manage_attribute_set()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-attribute-set';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Manage Attribute Set | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Manage Attribute Set  | ' . $settings['app_name'];
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function add_attribute_set()
    {

        if (isset($_POST['edit_attribute_set'])) {
            if (print_msg(!has_permissions('update', 'attribute_set'), PERMISSION_ERROR_MSG, 'attribute_set')) {
                return false;
            }
        } else {
            if (print_msg(!has_permissions('create', 'attribute_set'), PERMISSION_ERROR_MSG, 'attribute_set')) {
                return false;
            }
        }

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {

                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                if (isset($_POST['edit_attribute_set'])) {
                    if (is_exist(['name' => $_POST['name']], 'attribute_set', $_POST['edit_attribute_set'])) {
                        $response["error"]   = true;
                        $response["message"] = "Name Already Exist ! Provide a unique name";
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }
                } else {
                    if (!$this->form_validation->is_unique($_POST['name'], 'attribute_set.name')) {
                        $response["error"]   = true;
                        $response["message"] = "Name Already Exist ! Provide a unique name";
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }
                }

                $this->attribute_model->add_attribute_set($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = 'Attribute Added Succesfully';
                $message = (isset($_POST['edit_attribute_set'])) ? 'Attribute Set Updated Successfully' : 'Attribute Set Added Successfully';
                $this->response['message'] = $message;
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function attribute_set_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->attribute_model->get_attribute_set_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
