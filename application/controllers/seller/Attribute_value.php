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
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = TABLES . 'manage-attribute-value';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Attribute Value | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Attribute Value  | ' . $settings['app_name'];
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function attribute_value_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            return $this->attribute_model->get_attribute_values();
        } else {
            redirect('seller/login', 'refresh');
        }
    }
}
