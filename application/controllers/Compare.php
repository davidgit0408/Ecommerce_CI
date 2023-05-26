<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Compare extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form', 'language', 'timezone_helper']);
        $this->load->model(['product_model']);
        $this->load->library('session');
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
    }

    public function index()
    {
        $this->data['main_page'] = 'compare';
        $this->data['title'] = 'Compare | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Compare, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Compare | ' . $this->data['web_settings']['meta_description'];
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function add_to_compare()
    {
        $this->form_validation->set_rules('product_id', 'product id', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $product_id = (isset($_POST['product_id']) && !empty($_POST['product_id'])) ? $this->input->post('product_id', true) : '';
            $obj = json_decode($product_id, true);
            $obj1 = array_column($obj, 'product_id');

            $products = fetch_product("", "", (isset($obj1) && !empty($obj1)) ? $obj1 : redirect(base_url()));

            $this->response['error'] = false;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = 'Compare Product Added Successfully';
            $this->response['data'] = (!empty($products)) ? $products : [];
            print_r(json_encode($this->response));
        }
    }
}
