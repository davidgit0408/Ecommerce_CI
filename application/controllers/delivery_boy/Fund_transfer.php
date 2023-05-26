<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Fund_transfer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload']);
        $this->load->helper(['url', 'language', 'file']);
        $this->load->model('Fund_transfers_model');
        $this->load->model('Delivery_boy_model');
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
            $this->data['main_page'] = TABLES . 'manage-fund-transfers';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'View Fund Transfer | ' . $settings['app_name'];
            $this->data['meta_description'] = ' View Fund Transfer  | ' . $settings['app_name'];
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details('delivery_boys', ['id' => $_GET['edit_id'], 'status' => '1']);
            }
            $this->load->view('delivery_boy/template', $this->data);
        } else {
            redirect('delivery_boy/login', 'refresh');
        }
    }

    public function view_fund_transfers($user_id = '')
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
            if ($user_id == '' || $this->ion_auth->user()->row()->id != $user_id) {
                return false;
            }

            return $this->Fund_transfers_model->get_fund_transfers_list($user_id);
        } else {
            redirect('delivery_boy/login', 'refresh');
        }
    }

    public function manage_cash()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
            $this->data['main_page'] = TABLES . 'cash-collection';
            $settings = get_settings('system_settings', true);
            $user_id = $this->ion_auth->user()->row()->id;
            $this->data['curreny'] = $settings['currency'];
            $this->data['cash_in_hand'] = fetch_details("users", ['id' => $user_id], 'cash_received');
            $this->data['cash_collected'] =  $this->db->select(' SUM(amount) as total_amt ')->where(['type' => 'delivery_boy_cash_collection', 'user_id' => $user_id])->get('transactions')->result_array();
            $this->data['title'] = 'View Cash Collection | ' . $settings['app_name'];
            $this->data['meta_description'] = ' View Cash Collection  | ' . $settings['app_name'];
            $this->load->view('delivery_boy/template', $this->data);
        } else {
            redirect('delivery_boy/login', 'refresh');
        }
    }

    public function get_cash_collection()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
            return $this->Delivery_boy_model->get_cash_collection_list($this->ion_auth->user()->row()->id);
        } else {
            redirect('delivery_boy/login', 'refresh');
        }
    }
}
