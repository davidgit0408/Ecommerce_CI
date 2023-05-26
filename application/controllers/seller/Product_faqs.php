<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_faqs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload']);
        $this->load->helper(['url', 'language', 'file']);
        $this->load->model(['product_model', 'product_faqs_model']);
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {
            $this->data['main_page'] = TABLES . 'manage-product-faqs';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Product FAQS Management | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Product FAQs Management |' . $settings['app_name'];
            if (isset($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details('product_faqs', ['id' => $_GET['edit_id']]);
            }
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function get_faqs_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {

            return $this->product_faqs_model->get_faqs();
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function edit_product_faqs()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {
            $this->form_validation->set_rules('answer', 'Answer', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $this->product_faqs_model->add_product_faqs($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $message = (isset($_POST['edit_product_faq'])) ? 'FAQ Updated Successfully' : 'FAQ Added Successfully';
                $this->response['message'] = $message;
                print_r(json_encode($this->response));
            }
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function create_product_faqs()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {
            $this->data['main_page'] = FORMS . 'add-product-faqs';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Add Product FAQS Management | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Add Product FAQs Management |' . $settings['app_name'];
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function add_faqs()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {
            $this->product_faqs_model->add_product_faqs($_POST);
            $this->response['error'] = false;
            $this->response['message'] = 'Faq added Succesfully';
            print_r(json_encode($this->response));
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function delete_product_faq()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {

            $this->product_faqs_model->delete_faq($_GET['id']);

            $this->response['error'] = false;
            $this->response['message'] = 'Deleted Succesfully';

            print_r(json_encode($this->response));
        } else {
            redirect('seller/login', 'refresh');
        }
    }
}
