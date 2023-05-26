<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Area extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model('Area_model');
    }

    public function manage_areas()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = TABLES . 'manage-area';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Area Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Area Management  | ' . $settings['app_name'];
            $this->data['city'] = fetch_details('cities', '');
            $this->data['zipcodes'] = fetch_details('zipcodes', '');
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function manage_countries()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {
            $this->data['main_page'] = TABLES . 'manage-countries';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Countries Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Countries Management  | ' . $settings['app_name'];
            $this->data['countries'] = fetch_details('countries', '');
       
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function country_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {
            return $this->Area_model->get_countries_list();
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function view_area()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            return $this->Area_model->get_list($table = 'areas');
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function manage_cities()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $this->data['main_page'] = TABLES . 'manage-city';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'City Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' City Management  | ' . $settings['app_name'];

            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function view_city()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            return $this->Area_model->get_list($table = 'cities');
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function manage_zipcodes()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $this->data['main_page'] = TABLES . 'manage-zipcodes';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Zipcodes Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Zipcode Management  | ' . $settings['app_name'];
            if (isset($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details('zipcodes', ['id' => $_GET['edit_id']]);
            }
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function view_zipcodes()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->session->userdata('user_id');

            return $this->Area_model->get_zipcode_list($seller_id);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function get_zipcodes()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $limit = (isset($_GET['limit'])) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_GET['offset'])) ? $this->input->post('offset', true) : 0;
            $search =  (isset($_GET['search'])) ? $_GET['search'] : null;
            $zipcodes = $this->Area_model->get_zipcodes($search, $limit, $offset);
            $this->response['data'] = $zipcodes['data'];
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            print_r(json_encode($this->response));
        } else {
            redirect('seller/login', 'refresh');
        }
    }
}
