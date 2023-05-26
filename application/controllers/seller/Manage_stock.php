<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_stock extends CI_Controller
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
            $this->data['main_page'] = TABLES . 'manage_stock';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Stock Management| ' . $settings['app_name'];
            $this->data['meta_description'] = 'Stock Management |' . $settings['app_name'];
            if (isset($_GET['edit_id'])) {

                $stock = fetch_details("product_variants", ['id' => $_GET['edit_id']], ['stock', 'product_id', 'attribute_value_ids']);
                // $attribute_value_id = $stock['attribute_value_ids'];
                $attribute_value = fetch_details("attribute_values", ['id' => $stock[0]['attribute_value_ids']], ['value']);
              
                $id = $stock[0]['product_id'];
                $this->data['fetched_data'] = fetch_product("", "", $id);
                $this->data['fetched'] = $stock[0]['stock'];
                $this->data['attribute'] = $attribute_value;
            }
            $seller_id = $_SESSION['user_id'];
            $this->data['categories'] = $this->category_model->get_categories('', '', '', '', '', '', '', '', $seller_id);
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function get_stock_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {

            return $this->product_model->get_seller_stock_details();
        } else {
            redirect('seller/login', 'refresh');
        }
    }


    public function update_stock()
    {

        $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('current_stock', 'Current Stock', 'trim|required|xss_clean');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {

            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = validation_errors();
            print_r(json_encode($this->response));
        } else {
            if ($_POST['type'] == 'add') {
                update_stock([$_POST['variant_id']], [$_POST['quantity']], 'plus');
            } else {
                if ($_POST['type'] == 'subtract') {

                    if (
                        $_POST['quantity'] > $_POST['current_stock']
                    ) {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Subtracted stock cannot be greater than current stock";
                        print_r(
                            json_encode($this->response)
                        );
                        return;
                    }
                }
                update_stock([$_POST['variant_id']], [$_POST['quantity']]);
            }

            $this->response['error'] = false;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = 'Stock Updated Successfully';
            print_r(json_encode($this->response));
        }
    }
}
