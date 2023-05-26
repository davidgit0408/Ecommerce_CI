<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Point_of_sale extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload', 'pagination']);
        $this->load->helper(['url', 'language', 'file']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model(['point_of_sale_model', 'customer_model', 'ion_auth_model', 'transaction_model', 'order_model']);
        // if (!has_permissions('read', 'media')) {
        //     $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
        //     redirect('admin/home', 'refresh');
        // }
    }
    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = VIEW . 'point_of_sale';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Point of Sale | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Point of Sale |' . $settings['app_name'];

            $seller_id = $this->session->userdata('user_id');
            $this->data['categories'] = json_decode(json_encode($this->category_model->get_seller_categories($seller_id)), 1);
            $this->data['csrfName'] = $this->security->get_csrf_token_name();
            $this->data['csrfHash'] = $this->security->get_csrf_hash();
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function get_products()
    {
        $max_limit = 25;
        $seller_id = $_SESSION['user_id'];

        $category_id = (isset($_GET['category_id']) && !empty($_GET['category_id']) && is_numeric($_GET['category_id'])) ? $this->input->get('category_id', true) : "";
        $limit = (isset($_GET['limit']) && !empty($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] <= $max_limit) ? $this->input->get('limit') : $max_limit;
        $offset = (isset($_GET['offset']) && !empty($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0;
        $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : 'p.id';
        $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : 'desc';
        $filter['search'] = (isset($_GET['search']) && !empty($_GET['search'])) ? $_GET['search'] : '';
        $products =  $this->data['products'] = fetch_product("", $filter, "", $category_id, $limit, $offset, $sort, $order, "", "", $seller_id);
        $response['error'] = (!empty($products)) ? false : true;
        $response['message'] = (!empty($products)) ? "Products fetched successfully" : "No products found";
        $response['products'] = (!empty($products)) ? $products : [];
        print_r(json_encode($response));
    }

    public function get_users()
    {
        $search = $this->input->get('search');
        $response = $this->point_of_sale_model->get_users($search);
        echo json_encode($response);
    }
    public function register_user()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]|numeric|is_unique[users.mobile]', array('is_unique' => ' The mobile number is already registered . Please login'));
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
        if ($this->form_validation->run() == false) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
        } else {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $mobile = $this->input->post('mobile');
            $password = $this->input->post('password');
            $identity =  $mobile;
            $additional_data = [
                'username' => $this->input->post('name'),
                'active' => 1
            ];
            $res = $this->ion_auth->register($identity, $password, " ", $additional_data, ['2']);
            update_details(['active' => 1], [$identity_column => $identity], 'users');
            $data = $this->db->select('u.id,u.username,u.mobile')->where([$identity_column => $identity])->get('users u')->result_array();
            $this->response['error'] = (!empty($data)) ? false : true;
            $this->response['message'] = (!empty($data)) ? "Registered Successfully" : "Not Registered";
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['data'] = (!empty($data)) ? $data : [];
        }
        print_r(json_encode($this->response));
    }

    public function place_order()
    {
        if (!isset($_POST['data']) || empty($_POST['data'])) {
            $this->response['error'] = true;
            $this->response['message'] = "Pass the data";
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }

        $post_data = json_decode($_POST['data'], true);
        if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
            $this->response['error'] = true;
            $this->response['message'] = "Please select the customer!";
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {
            if (isset($post_data) && !empty($post_data)) {
                for ($i = 0; $i < count($post_data); $i++) {
                    if (!isset($post_data[$i]['variant_id']) || empty($post_data[$i]['variant_id'])) {
                        $this->response['error'] = true;
                        $this->response['message'] = "The variant ID field is required";
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }

                    if (!isset($post_data[$i]['quantity']) || empty($post_data[$i]['quantity'])) {
                        $this->response['error'] = true;
                        $this->response['message'] = "Please enter valid quantity for " . $post_data[$i]['title'];
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }
                }
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Pass the data";
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            // creating arr for place order
            $product_variant_id = array_column($post_data, "variant_id");
            $quantity = array_column($post_data, "quantity");
            $user_id = $_POST['user_id'];

            $place_order_data = array();
            $place_order_data['product_variant_id'] = implode(",", $product_variant_id);
            $place_order_data['quantity'] = implode(",", $quantity);
            $place_order_data['user_id'] = $user_id;
            $user_mobile = fetch_details("users", ['id' => $user_id], "mobile");
            $place_order_data['mobile'] = $user_mobile[0]['mobile'];
            $place_order_data['is_wallet_used'] = 0;
            $place_order_data['delivery_charge'] = $_POST['delivery_charges'];
            $place_order_data['discount'] = $_POST['discount'];
            $place_order_data['is_delivery_charge_returnable'] = 0;
            $place_order_data['wallet_balance_used'] = 0;
            $place_order_data['active_status'] = "delivered";
            $payment_method_name = (isset($_POST['payment_method_name']) && !empty($_POST['payment_method_name'])) ? $this->input->post('payment_method_name', true) : NULL;
            $place_order_data['payment_method'] = (isset($_POST['payment_method']) && !empty($_POST['payment_method']) && $_POST['payment_method'] != "other") ? $this->input->post('payment_method', true) : $payment_method_name;
            $txn_id = (isset($_POST['txn_id']) && !empty($_POST['txn_id'])) ? $this->input->post('txn_id', true) : NULL;

            $check_current_stock_status = validate_stock($product_variant_id, $quantity);
            if ($check_current_stock_status['error'] == true) {
                $this->response['error'] = true;
                $this->response['message'] = $check_current_stock_status['message'];
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            if (isset($_POST['payment_method']) && !empty($_POST['payment_method']) && $_POST['payment_method'] == "other" && empty($_POST['payment_method_name'])) {
                $this->response['error'] = true;
                $this->response['message'] = "Please enter payment method name";
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            for ($i = 0; $i < count($post_data); $i++) {
                $data = array(
                    'product_variant_id' => implode(",", $product_variant_id),
                    'qty' => implode(",", $quantity),
                    'user_id' => $user_id,
                );
                if ($this->cart_model->add_to_cart($data)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Item are Not Added";
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
            $cart = get_cart_total($user_id, false, '0', "", true);
            if (empty($cart)) {
                $this->response['error'] = true;
                $this->response['message'] = "Your Cart is empty.";
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $final_total = $cart['overall_amount'];
            $place_order_data['final_total'] = $final_total;
            $res = $this->order_model->place_order($place_order_data);
            if (isset($res) && !empty($res)) {
                // creating transaction record for card payments
                $trans_data = [
                    'transaction_type' => 'transaction',
                    'user_id' => $user_id,
                    'order_id' => $res['order_id'],
                    'type' => strtolower($place_order_data['payment_method']),
                    'txn_id' => $txn_id,
                    'amount' => $final_total,
                    'status' => "success",
                    'message' => "Order Delivered Successfully",
                ];
                $this->transaction_model->add_transaction($trans_data);
            }
            $data['order_id'] = $res['order_id'];
            $this->response['error'] = false;
            $this->response['message'] = "Order Delivered Successfully.";
            $this->response['data'] = $res;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            print_r(json_encode($this->response));
            return false;
        } else {
            return false;
        }
    }
}
