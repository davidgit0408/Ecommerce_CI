<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attribute_value extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['attribute_model', 'category_model']);

        if (!has_permissions('read', 'attribute_value')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'attribute-value';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Add Attribute Value | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Add Attribute Value  | ' . $settings['app_name'];
            if (isset($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details('attribute_values', ['id' => $_GET['edit_id']]);
            }
            $this->data['attributes'] = fetch_details('attributes', '');
            $this->data['categories'] = $this->category_model->get_categories();
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function manage_attribute_value()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-attribute-value';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Manage Attribute Value | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Manage Attribute Value  | ' . $settings['app_name'];
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function add_attribute_value()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (isset($_POST['edit_attribute_value'])) {
                if (print_msg(!has_permissions('update', 'attribute_value'), PERMISSION_ERROR_MSG, 'attribute_value')) {
                    return false;
                }
            } else {
                if (print_msg(!has_permissions('create', 'attribute_value'), PERMISSION_ERROR_MSG, 'attribute_value')) {
                    return false;
                }
            }

            $this->form_validation->set_rules('attributes_id', 'Attribute', 'trim|required|xss_clean');

            $this->form_validation->set_rules('value', 'Value', 'trim|required|xss_clean');
            $swatche_type = $this->input->post('swatche_type', true);
            if ($swatche_type != "") {
                if ($swatche_type == 0) {
                    $_POST['swatche_value'] = NULL;
                }
            }
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {

                if (isset($_POST['edit_attribute_value'])) {
                    if (is_exist(['attribute_id' => $_POST['attributes_id'], 'value' => $_POST['value']], 'attribute_values', $_POST['edit_attribute_value'])) {
                        $response["error"]   = true;
                        $response["message"] = "This combination already exist ! Please provide a new combination";
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }
                } else {
                    if (is_exist(['attribute_id' => $_POST['attributes_id'], 'value' => $_POST['value']], 'attribute_values')) {
                        $response["error"]   = true;
                        $response["message"] = "This combination already exist ! Please provide a new combination";
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }
                }


                $this->attribute_model->add_attribute_value($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $message = (isset($_POST['edit_attribute_value'])) ? 'Attribute Value Updated Successfully' : 'Attribute Value Added Successfully';
                $this->response['message'] = $message;
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function attribute_value_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->attribute_model->get_attribute_values();
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
