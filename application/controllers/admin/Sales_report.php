<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Sales_report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['Sales_report_model', 'Order_model', 'Category_model']);
        $this->session->set_flashdata('authorize_flag', "");
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'sales-report';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Sales Report |' . $settings['app_name'];
            $this->data['meta_description'] = 'eShop - Multivendor';
            $this->data['sellers'] = $this->db->select(' u.username as seller_name,u.id as seller_id,sd.category_ids,sd.id as seller_data_id  ')
                ->join('users_groups ug', ' ug.user_id = u.id ')
                ->join('seller_data sd', ' sd.user_id = u.id ')
                ->where(['ug.group_id' => '4'])
                ->get('users u')->result_array();

            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function get_sales_report_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Sales_report_model->get_sales_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
