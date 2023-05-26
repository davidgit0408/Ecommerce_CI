<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_code extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model('Setting_model');

        if (!has_permissions('read', 'contact_us')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'purchase-code';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'System Regsitration | Purchase Code Validation | ' . $settings['app_name'];
            $this->data['meta_description'] = 'System Regsitration | Purchase Code Validation |  | ' . $settings['app_name'];
            $this->data['doctor_brown'] = get_settings('doctor_brown');
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function validator()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (defined('SEMI_DEMO_MODE') && SEMI_DEMO_MODE == 0) {
                $this->response['error'] = true;
                $this->response['message'] = SEMI_DEMO_MODE_MSG;
                echo json_encode($this->response);
                return false;
                exit();
            }
            $this->form_validation->set_rules('purchase_code', 'Purchase Code', 'trim|required|xss_clean');

                $purchase_code = $this->input->post("purchase_code", true);


                        $doctor_brown = get_settings('doctor_brown');
                        if (empty($doctor_brown)) {
                            $doctor_brown['code_bravo'] = 'Nulled by codingshop.net';
                            $doctor_brown['time_check'] = 1111;
                            $doctor_brown['code_adam'] = 'Nulled by codingshop.net';
                            $doctor_brown['dr_firestone'] = 11;

                            $data['variable'] = "doctor_brown";
                            $data['value'] = json_encode($doctor_brown);
                            insert_details($data, 'settings');
                        }
                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = 'done! Nulled';
                        print_r(json_encode($this->response));


            
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
