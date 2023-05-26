<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Customer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['Customer_model', 'address_model']);
    }

    public function index()
    {
            if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->ion_auth->get_user_id();
//            if(get_seller_permission($seller_id, 'customer_privacy')){
                $this->data['main_page'] = TABLES . 'manage-customer';
                $settings = get_settings('system_settings', true);
                $this->data['title'] = 'View Customer | ' . $settings['app_name'];
                $this->data['meta_description'] = ' View Customer  | ' . $settings['app_name'];
                $this->data['about_us'] = get_settings('about_us');
                $this->load->view('seller/template', $this->data);
//            }else{
//                redirect('seller/login', 'refresh');
//            }
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function view_customer()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            return $this->Customer_model->get_customer_list_by_seller();
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function manage_customer()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = FORMS . 'manage_customer';
            $this->data['title'] = 'Add Customer';
            $this->data['meta_description'] = 'Add Customer';
            $seller_id = $this->ion_auth->get_user_id();
            $seller = $this->db->select(' u.* ')
                ->where(['u.id' => $seller_id])
                ->get('users u')
                ->result_array();

            $this->data['by_seller_id'] = $seller_id;
            $this->data['by_seller_name'] = $seller[0]['username'];
//            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
//                $this->data['fetched_data'] = $this->db->select(' u.* ')
//                    ->join('users_groups ug', ' ug.user_id = u.id ')
//                    ->where(['ug.group_id' => '3', 'ug.user_id' => $_GET['edit_id']])
//                    ->get('users u')
//                    ->result_array();
//            }
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function add_customer()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('by_seller_id', 'by_seller_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('by_seller_name', 'by_seller_name', 'trim|required|xss_clean');
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
            $this->Customer_model->add_customer($arr);
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
