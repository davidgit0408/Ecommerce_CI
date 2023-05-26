<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Delegate extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['Customer_model', 'address_model', 'Order_model']);
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-delegate';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'View Delegate | ' . $settings['app_name'];
            $this->data['meta_description'] = ' View Delegate  | ' . $settings['app_name'];
            $this->data['about_us'] = get_settings('about_us');
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function view_delegate()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Customer_model->get_delegate_list();
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function manage_delegate()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'manage_delegate';
            $this->data['title'] = 'Add Delegate';
            $this->data['meta_description'] = 'Add Delegate';
            $seller_id = $this->ion_auth->get_user_id();
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function add_delegate()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('username', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Mail', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required|matches[password]|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }

            $arr = $this->input->post(null, true);
            $this->Customer_model->add_delegate($arr);
            $this->response['error'] = false;
            $this->response['message'] = 'Delegate Added Successfully';
//            $this->response['data'] = $res;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function visit_delegate()
    {

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'visit-delegate';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Visit Delegate | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Visit Delegate  | ' . $settings['app_name'];
            $this->data['about_us'] = get_settings('about_us');
            $this->data['curreny'] = get_settings('currency');

            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function mission_delegate()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'mission-delegate';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Mission Delegate | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Mission Delegate |' . $settings['app_name'];

            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function get_delegate_data()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Customer_model->get_mission_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function delete_mission()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (print_msg(!has_permissions('delete', 'mission'), PERMISSION_ERROR_MSG, 'mission')) {
                return false;
            }
            if (delete_details(['id' => $_GET['id']], 'mission')) {

                $response['error'] = false;
                $response['message'] = 'Deleted Succesfully';
            } else {
                $response['error'] = true;
                $response['message'] = 'Something Went Wrong';
            }
            print_r(json_encode($response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function create_mission()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'mission';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Add Mission | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Add Mission  | ' . $settings['app_name'];
            $this->data['delegates'] = $this->db->select(' u.username as delegate_name,u.id as delegate_id ')
                ->join('users_groups ug', ' ug.user_id = u.id ')
                ->where(['ug.group_id' => '2'])
                ->get('users u')->result_array();
            $this->data['customers'] = $this->db->select(' u.username as customer_name,u.id as customer_id ')
                ->join('users_groups ug', ' ug.user_id = u.id ')
                ->where(['ug.group_id' => '4'])
                ->get('users u')->result_array();
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function add_mission()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('delegate_id', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('customer_id', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('visiting_day', 'Name', 'trim|required|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }

            $arr = $this->input->post(null, true);
            $mission_data = [
                'delegate_id' => $arr['delegate_id'],
                'customer_id' => $arr['customer_id'],
                'visiting_day' => strtotime($arr['visiting_days'])
            ];

            $this->db->insert('mission', $mission_data);
            $this->response['error'] = false;
            $this->response['message'] = 'Customer Added Successfully';
//            $this->response['data'] = $res;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            print_r(json_encode($this->response));
            return false;
        }
    }

    
}
