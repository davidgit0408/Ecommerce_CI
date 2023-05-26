<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cart extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['cart', 'razorpay', 'stripe', 'paystack', 'flutterwave', 'midtrans', 'my_fatoorah']);
        $this->paystack->__construct('test');
        $this->load->model(['cart_model', 'address_model', 'order_model', 'Order_model', 'transaction_model']);
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
    }

    public function index()
    {
        if ($this->data['is_logged_in']) {
            $this->data['main_page'] = 'cart';
            $this->data['title'] = 'Product Cart | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Product Cart, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Product Cart | ' . $this->data['web_settings']['meta_description'];
            $this->data['cart'] = get_cart_total($this->data['user']->id);
            $this->data['save_for_later'] = get_cart_total($this->data['user']->id, false, '1');
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url());
        }
    }

    public function manage()
    {
        // print_R($_POST);
        // return;
        if ($this->data['is_logged_in']) {
            $this->form_validation->set_rules('product_variant_id', 'Product Variant', 'trim|required|xss_clean');
            $this->form_validation->set_rules('is_saved_for_later', 'Saved For Later', 'trim|xss_clean');
            $_POST['qty'] = (isset($_POST['qty']) && $_POST['qty'] != '') ? $_POST['qty'] : 1;
            $this->form_validation->set_rules('qty', 'Quantity', 'trim|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $data = array(
                'product_variant_id' => $this->input->post('product_variant_id', true),
                'qty' => $this->input->post('qty', true),
                'is_saved_for_later' => $this->input->post('is_saved_for_later', true),
                'user_id' => $this->data['user']->id,
            );
            $product_variant_id = explode(',', $_POST['product_variant_id']);
           
            $_POST['user_id'] = $this->data['user']->id;
            $settings = get_settings('system_settings', true);

            if ($settings['is_single_seller_order'] == 1) {
                if (!is_single_seller($product_variant_id, $_POST['user_id'])) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Only single seller items are allow in cart.You can remove privious item(s) and add this item.';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }

            //check for digital or phisical product in cart
            if (!is_single_product_type($product_variant_id[0], $_POST['user_id'])) {
                $this->response['error'] = true;
                $this->response['message'] = 'you can only add either digital product or physical product to cart';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            $_POST['user_id'] = $this->data['user']->id;
            $settings = get_settings('system_settings', true);
            $cart_count = get_cart_count($_POST['user_id']);
            $is_variant_available_in_cart = is_variant_available_in_cart($_POST['product_variant_id'], $_POST['user_id']);
            if (!$is_variant_available_in_cart) {
                if ($cart_count[0]['total'] >= $settings['max_items_cart']) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Maximum ' . $settings['max_items_cart'] . ' Item(s) Can Be Added Only!';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return;
                }
            }
            $saved_for_later = (isset($_POST['is_saved_for_later']) && $_POST['is_saved_for_later'] != "") ? $this->input->post('is_saved_for_later', true) : 0;
            $check_status = ($saved_for_later == 1) ? false : true;
            if (!$this->cart_model->add_to_cart($data, $check_status)) {
                if ($_POST['qty'] == 0) {
                    $res = get_cart_total($this->data['user']->id, false);
                } else {
                    $res = get_cart_total($this->data['user']->id, $_POST['product_variant_id']);
                }

                $this->response['error'] = false;
                $this->response['message'] = 'Item added to Cart.';
                $this->response['data'] = [
                    'total_quantity' => ($_POST['qty'] == 0) ? '0' : strval($_POST['qty']),
                    'sub_total' => strval($res['sub_total']),
                    'total_items' => (isset($res[0]['total_items'])) ? strval($res[0]['total_items']) : "0",
                    'tax_percentage' => (isset($res['tax_percentage'])) ? strval($res['tax_percentage']) : "0",
                    'tax_amount' => (isset($res['tax_amount'])) ? strval($res['tax_amount']) : "0",
                    'cart_count' => (isset($res[0]['cart_count'])) ? strval($res[0]['cart_count']) : "0",
                    'max_items_cart' => $this->data['settings']['max_items_cart'],
                    'overall_amount' => $res['overall_amount'],
                    'items' => $this->cart_model->get_user_cart($this->data['user']->id),
                ];
                print_r(json_encode($this->response));
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Please Login first to use Cart.';
            $this->response['data'] = $this->data;
            echo json_encode($this->response);
            return false;
        }
    }

    public function cart_sync()
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
        if (isset($post_data) && !empty($post_data)) {
            foreach ($post_data as $data) {
                if (!isset($data['product_variant_id']) || empty($data['product_variant_id']) || !is_numeric($data['product_variant_id'])) {
                    $this->response['error'] = true;
                    $this->response['message'] = "The variant ID field is required";
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                }
                if (!isset($data['qty']) || empty($data['qty']) || !is_numeric($data['qty'])) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Please enter valid quantity for " . $data['title'];
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                }
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = "Pass the data";
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $user_id = $this->data['user']->id;
        $product_variant_ids = array_column($post_data, "product_variant_id");
        $quantity = array_column($post_data, "qty");
        $place_order_data = array();
        $place_order_data['product_variant_id'] = implode(",", $product_variant_ids);
        $place_order_data['qty'] = implode(",", $quantity);
        $place_order_data['user_id'] =  $user_id;

        $settings = get_settings('system_settings', true);
        $cart_count = get_cart_count($user_id);
        foreach ($product_variant_ids as $variant_id) {
            $is_variant_available_in_cart = is_variant_available_in_cart($variant_id, $user_id);
            if (!$is_variant_available_in_cart) {
                if ($cart_count[0]['total'] >= $settings['max_items_cart']) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Maximum ' . $settings['max_items_cart'] . ' Item(s) Can Be Added Only!';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return;
                }
            }
        }
        $saved_for_later = (isset($_POST['is_saved_for_later']) && $_POST['is_saved_for_later'] != "") ? $this->input->post('is_saved_for_later', true) : 0;
        $check_status = ($saved_for_later == 1) ? false : true;
        if (!$this->cart_model->add_to_cart($place_order_data, $check_status)) {
            if ($_POST['qty'] == 0) {
                $res = get_cart_total($this->data['user']->id, false);
            } else {
                $res = get_cart_total($this->data['user']->id, $_POST['product_variant_id']);
            }
            $this->response['error'] = false;
            $this->response['message'] = 'Item added to Cart.';
            $this->response['data'] = [
                'total_quantity' => ($_POST['qty'] == 0) ? '0' : strval($_POST['qty']),
                'sub_total' => strval($res['sub_total']),
                'total_items' => (isset($res[0]['total_items'])) ? strval($res[0]['total_items']) : "0",
                'tax_percentage' => (isset($res['tax_percentage'])) ? strval($res['tax_percentage']) : "0",
                'tax_amount' => (isset($res['tax_amount'])) ? strval($res['tax_amount']) : "0",
                'cart_count' => (isset($res[0]['cart_count'])) ? strval($res[0]['cart_count']) : "0",
                'max_items_cart' => $this->data['settings']['max_items_cart'],
                'overall_amount' => $res['overall_amount'],
                'items' => $this->cart_model->get_user_cart($this->data['user']->id),
            ];
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Please Login first to use Cart.';
            $this->response['data'] = $this->data;
            echo json_encode($this->response);
            return false;
        }
    }


    // remove_from_cart
    public function remove()
    {
        $this->form_validation->set_rules('product_variant_id', 'Product Variant', 'trim|numeric|xss_clean|required');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            //Fetching cart items to check wheather cart is empty or not
            $cart_total_response = get_cart_total($this->data['user']->id);
            if (isset($_POST['is_save_for_later']) && empty($_POST['is_save_for_later'])) {
                if (!isset($cart_total_response[0]['total_items'])) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Cart Is Already Empty !';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }

            $data = array(
                'user_id' => $this->data['user']->id,
                'product_variant_id' => $this->input->post('product_variant_id', true),
            );
            if ($this->cart_model->remove_from_cart($data)) {
                $this->response['error'] = false;
                $this->response['message'] = 'Removed From Cart !';
                print_r(json_encode($this->response));
                return false;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Cannot remove this Item from cart.';
                echo json_encode($this->response);
                return false;
            }
        }
    }
    public function clear()
    {
        if ($this->data['is_logged_in']) {
            $cart_total_response = get_cart_total($this->data['user']->id);
            if (!isset($cart_total_response[0]['total_items'])) {
                $this->response['error'] = true;
                $this->response['message'] = 'Cart Is Already Empty !';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return;
            }

            $data = array(
                'user_id' => $this->data['user']->id,
            );
            if ($this->cart_model->remove_from_cart($data)) {
                $cart_total_response = get_cart_total($data['user_id']);
                $this->response['error'] = false;
                $this->response['message'] = 'Product Clear From Cart !';
                if (!empty($cart_total_response) && isset($cart_total_response)) {
                    $this->response['data'] = [
                        'total_quantity' => strval($cart_total_response['quantity']),
                        'sub_total' => strval($cart_total_response['sub_total']),
                        'total_items' => (isset($cart_total_response[0]['total_items'])) ? strval($cart_total_response[0]['total_items']) : "0",
                        'max_items_cart' => $this->data['settings']['max_items_cart']
                    ];
                } else {
                    $this->response['data'] = [];
                }
                print_r(json_encode($this->response));
                return false;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Cannot remove this Item from cart.';
                echo json_encode($this->response);
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Please Login first to use Cart.';
            echo json_encode($this->response);
            return false;
        }
    }

    public function get_user_cart()
    {
        if ($this->data['is_logged_in']) {
            $cart_user_data = $this->cart_model->get_user_cart($this->data['user']->id);
            $cart_total_response = get_cart_total($this->data['user']->id);
            $tmp_cart_user_data = $cart_user_data;

            if (!empty($tmp_cart_user_data)) {
                for ($i = 0; $i < count($tmp_cart_user_data); $i++) {

                    $product_data = fetch_details('product_variants', ['id' => $tmp_cart_user_data[$i]['product_variant_id']], 'product_id,availability');
                    $pro_details = fetch_product($this->data['user']->id, null, $product_data[0]['product_id']);
                    if (!empty($pro_details['product'])) {

                        if (trim($pro_details['product'][0]['availability']) == 0 && $pro_details['product'][0]['availability'] != null) {
                            unset($cart_user_data[$i]);
                            continue;
                        }
                        if (!empty($pro_details['product'])) {
                            $cart_user_data[$i]['product_details'] = $pro_details['product'];
                        } else {
                            unset($cart_user_data[$i]);
                            continue;
                        }
                    } else {
                        unset($cart_user_data[$i]);
                        continue;
                    }
                }
            }
            if (empty($cart_user_data)) {
                $this->response['error'] = true;
                $this->response['message'] = 'Cart Is Empty !';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return;
            }
            $this->response['error'] = false;
            $this->response['message'] = 'Product Retrived From Cart...!';
            $this->response['total_quantity'] = $cart_total_response['quantity'];
            $this->response['sub_total'] = $cart_total_response['sub_total'];
            $this->response['delivery_charge'] = $this->data['settings']['delivery_charge'];
            $this->response['tax_percentage'] = (isset($cart_total_response['tax_percentage'])) ? $cart_total_response['tax_percentage'] : "0";
            $this->response['tax_amount'] = (isset($cart_total_response['tax_amount'])) ? $cart_total_response['tax_amount'] : "0";
            $this->response['total_arr'] =  $cart_total_response['total_arr'];
            $this->response['variant_id'] =  $cart_total_response['variant_id'];
            $this->response['data'] = array_values($cart_user_data);
            print_r($this->response);
            return;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Please Login first to use Cart.';
            $this->response['data'] = $this->data;
            echo json_encode($this->response);
            return false;
        }
    }
    public function checkout()
    {
        if ($this->data['is_logged_in']) {
            $cart = $this->cart_model->get_user_cart($this->data['user']->id);
            if (empty($cart)) {
                redirect(base_url());
            }
            //check for digital or phisical product in cart
            //  if (!is_single_product_type($product_variant_id[0], $_POST['user_id'])) {
            //     $this->response['error'] = true;
            //     $this->response['message'] = 'you can only add either digital product or physical product to cart';
            //     $this->response['data'] = array();
            //     print_r(json_encode($this->response));
            //     return false;
            // }

            $this->data['time_slot_config'] = get_settings('time_slot_config', true);
            $payment_methods = get_settings('payment_method', true);
            $this->data['main_page'] = 'checkout';
            $this->data['title'] = 'Checkout | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Checkout, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Checkout | ' . $this->data['web_settings']['meta_description'];
            $cart_total_data = get_cart_total($this->data['user']->id);
            $this->data['cart'] = $cart_total_data;
            $this->data['payment_methods'] = get_settings('payment_method', true);
            $this->data['time_slots'] = fetch_details('time_slots', 'status=1', '*');
            $this->data['wallet_balance'] = fetch_details('users', 'id=' . $this->data['user']->id, 'balance,mobile');
            $this->data['default_address'] = $this->address_model->get_address($this->data['user']->id, NULL, NULL, TRUE);
            $this->data['payment_methods'] = $payment_methods;
            $settings = get_settings('system_settings', true);
            $this->data['support_email'] = (isset($settings['support_email']) && !empty($settings['support_email'])) ? $settings['support_email'] : 'abc@gmail.com';
            $currency = (isset($settings['currency']) && !empty($settings['currency'])) ? $settings['currency'] : '';
            $total = $this->data['cart']['total_arr'];
            if ($total < $settings['minimum_cart_amt']) {
                if (isset($settings['minimum_cart_amt']) && !empty($settings['minimum_cart_amt'])) {
                    $this->session->set_flashdata('message', 'Minimum total should be ' . $currency . ' ' . $settings['minimum_cart_amt']);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect(base_url('cart'), 'refresh');
                }
            }
            foreach ($cart_total_data as $row) {
                if (isset($row['availability'])  && empty($row['availability']) && $row['availability'] != "") {
                    $this->session->set_flashdata('message', 'Some of the product(s) are OUt of Stock. Please remove it from cart or save to later.');
                    $this->session->set_flashdata('message_type', 'error');
                    redirect(base_url('cart'), 'refresh');
                }
            }
            $this->data['currency'] = $currency;
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url());
        }
    }

    public function place_order()
    {

        if ($this->data['is_logged_in']) {
            /*
            mobile:9974692496
            product_variant_id: 1,2,3
            quantity: 3,3,1
            latitude:40.1451
            longitude:-45.4545
            promo_code:NEW20 {optional}
            payment_method: Paypal / Payumoney / COD / PAYTM
            address_id:17
            delivery_date:10/12/2012
            delivery_time:Today - Evening (4:00pm to 7:00pm)
            is_wallet_used:1 {By default 0}
            wallet_balance_used:1
            active_status:awaiting {optional}
      
        */
            // total:60.0
            // delivery_charge:20.0
            // tax_amount:10
            // tax_percentage:10
            // final_total:55

            $limit = (isset($_FILES['documents']['name'])) ? count($_FILES['documents']['name']) : 0;
            if ((!isset($_POST['address_id']) || empty($_POST['address_id'])) && $_POST['product_type'] != 'digital_product') {
                $this->response['error'] = true;
                $this->response['message'] = "Please choose address.";
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('product_variant_id', 'Product Variant Id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('quantity', 'Quantities', 'trim|required|xss_clean');
            $this->form_validation->set_rules('promo_code', 'Promo Code', 'trim|xss_clean');
            $this->form_validation->set_rules('order_note', 'Special Note', 'trim|xss_clean');

            /*
        ------------------------------
        If Wallet Balance Is Used
        ------------------------------
        */
            $this->form_validation->set_rules('latitude', 'Latitude', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('longitude', 'Longitude', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('delivery_date', 'Delivery Date', 'trim|xss_clean');
            $this->form_validation->set_rules('delivery_time', 'Delivery time', 'trim|xss_clean');
            if (isset($_POST['product_type']) && $_POST['product_type'] != 'digital_product') {
                $this->form_validation->set_rules('address_id', 'Address id', 'trim|required|numeric|xss_clean', array('required' => 'Please choose address'));
            }
            if (isset($_POST['product_type']) && $_POST['product_type'] == 'digital_product' && $_POST['download_allowed'] == 0) {
                $this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email|xss_clean', array('required' => 'Please Enter Email ID'));
            }
            if ($_POST['payment_method'] == "Razorpay") {
                $this->form_validation->set_rules('razorpay_order_id', 'Razorpay Order ID', 'trim|required|xss_clean');
                $this->form_validation->set_rules('razorpay_payment_id', 'Razorpay Payment ID', 'trim|required|xss_clean');
                $this->form_validation->set_rules('razorpay_signature', 'Razorpay Signature', 'trim|required|xss_clean');
            } else if ($_POST['payment_method'] == "Paystack") {
                $this->form_validation->set_rules('paystack_reference', 'Paystack Reference', 'trim|required|xss_clean');
            } else if ($_POST['payment_method'] == "Flutterwave") {
                $this->form_validation->set_rules('flutterwave_transaction_id', 'Flutterwave Transaction ID', 'trim|required|xss_clean');
                $this->form_validation->set_rules('flutterwave_transaction_ref', 'Flutterwave Transaction Refrence', 'trim|required|xss_clean');
            } else if ($_POST['payment_method'] == "Paytm") {
                $this->form_validation->set_rules('paytm_transaction_token', 'Paytm transaction token', 'trim|required|xss_clean');
                $this->form_validation->set_rules('paytm_order_id', 'Paytm order ID', 'trim|required|xss_clean');
            }

            $_POST['user_id'] = $this->data['user']->id;
            $_POST['customer_email'] = $this->data['user']->email;
            $_POST['is_wallet_used'] = 0;
            $data = array();
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return;
            } else {
                $_POST['order_note'] = (isset($_POST['order_note']) && !empty($_POST['order_note'])) ? $this->input->post("order_note", true) : NULL;
                //checking for product availability 
                if (isset($_POST['product_type']) && $_POST['product_type'] != 'digital_product') {
                    $area_id = fetch_details('addresses', ['id' => $_POST['address_id']], 'area_id');
                    $product_delivarable = check_cart_products_delivarable($area_id[0]['area_id'], $_POST['user_id']);
                    if (!empty($product_delivarable)) {
                        $product_not_delivarable = array_filter($product_delivarable, function ($var) {
                            return ($var['is_deliverable'] == false && $var['product_id'] != null);
                        });
                        $product_not_delivarable = array_values($product_not_delivarable);
                        $product_delivarable = array_filter($product_delivarable, function ($var) {
                            return ($var['product_id'] != null);
                        });
                        if (!empty($product_not_delivarable)) {
                            $this->response['error'] = true;
                            $this->response['message'] = "Some of the item(s) are not delivarable on selected address. Try changing address or modify your cart items.";
                            $this->response['data'] = array();
                            print_r(json_encode($this->response));
                            return;
                        }
                    }
                }
                if ($_POST['payment_method'] == 'COD') {
                    $product_variant_id = explode(',', $_POST['product_variant_id']);
                    for ($i = 0; $i < count($product_variant_id); $i++) {
                        $product_id = fetch_details("product_variants", ['id' => $product_variant_id[$i]], 'product_id');
                        $is_allowed = fetch_details("products", ['id' => $product_id[0]['product_id']], 'cod_allowed,name');
                        if ($is_allowed[0]['cod_allowed'] == 0) {
                            $this->response['error'] = true;
                            $this->response['message'] = "Cash On Delivery is not allow on the product " . $is_allowed[0]['name'];
                            $this->response['data'] = array();
                            print_r(json_encode($this->response));
                            return false;
                        }
                    }
                }
                $quantity = explode(',', $_POST['quantity']);
                if (isset($_POST['product_type']) && $_POST['product_type'] != 'digital_product') {
                    $check_current_stock_status = validate_stock($product_variant_id, $quantity);
                    if ($check_current_stock_status['error'] == true) {
                        $this->response['error'] = true;
                        $this->response['message'] = $check_current_stock_status['message'];
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }
                }

                $cart = get_cart_total($_POST['user_id'], false, '0', $_POST['address_id']);
                if (empty($cart)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Your Cart is empty.";
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }

                if (isset($_POST['product_type']) && $_POST['product_type'] != 'digital_product') {
                    $_POST['delivery_charge'] = get_delivery_charge($_POST['address_id'], $cart['total_arr']);
                    $_POST['delivery_charge'] = str_replace(',', '', $_POST['delivery_charge']);
                    $_POST['is_delivery_charge_returnable'] = intval($_POST['delivery_charge']) != 0 ? 1 : 0;
                }
                $wallet_balance = fetch_details('users', 'id=' . $_POST['user_id'], 'balance');
                $final_total = $cart['overall_amount'];
                $wallet_balance = $wallet_balance[0]['balance'];
                $_POST['wallet_balance_used'] = 0;
                if (isset($_POST['wallet_used']) && $_POST['wallet_used'] == 1) {
                    if ($wallet_balance != 0) {
                        $_POST['is_wallet_used'] = 1;
                        if ($wallet_balance >= $final_total) {
                            $_POST['wallet_balance_used'] = $final_total;
                            $_POST['payment_method'] = 'wallet';
                        } else {
                            $_POST['wallet_balance_used'] = $wallet_balance;
                        }
                    } else {
                        $this->response['error'] = true;
                        $this->response['message'] = "Insufficient balance";
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }
                }
                $promo_discount = 0;
                if (isset($_POST['promo_code']) && !empty($_POST['promo_code'])) {
                    $validate = validate_promo_code($_POST['promo_code'], $this->data['user']->id, $cart['total_arr']);
                    if ($validate['error']) {
                        $this->response['error'] = true;
                        $this->response['message'] = $validate['message'];
                        print_r(json_encode($this->response));
                        return false;
                    } else {
                        $promo_discount = $validate['data'][0]['final_discount'];
                    }
                }
                $_POST['final_total'] = $cart['overall_amount'] - $_POST['wallet_balance_used'] - $promo_discount;
                if ($_POST['payment_method'] == "Razorpay") {
                    if (!verify_payment_transaction($_POST['razorpay_payment_id'], 'razorpay')) {
                        $this->response['error'] = true;
                        $this->response['message'] = "Invalid Razorpay Payment Transaction.";
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }

                    $data['status'] = "success";
                    $data['txn_id'] = $_POST['razorpay_payment_id'];
                    $data['message'] = "Order Placed Successfully";
                } elseif ($_POST['payment_method'] == "Flutterwave") {
                    if (!verify_payment_transaction($_POST['flutterwave_transaction_id'], 'flutterwave')) {
                        $this->response['error'] = true;
                        $this->response['message'] = "Invalid Flutterwave Payment Transaction.";
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }

                    $data['status'] = "success";
                    $data['txn_id'] = $_POST['flutterwave_transaction_id'];
                    $data['message'] = "Order Placed Successfully";
                } elseif ($_POST['payment_method'] == "Paytm") {
                    $paytm_response = verify_payment_transaction($_POST['paytm_order_id'], 'paytm');
                    if ($paytm_response['error'] == true) {
                        $this->response['error'] = true;
                        $this->response['message'] = "Invalid Paytm Transaction.";
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }
                    $status = $paytm_response['data']['body']['resultInfo']['resultStatus'];

                    $_POST['active_status'] = $status == "TXN_SUCCESS" ? 'received' : 'awaiting';

                    $data['status'] = $status == "TXN_SUCCESS" ? 'Success' : 'Pending';
                    $data['txn_id'] = $_POST['paytm_order_id'];
                    $data['message'] = "Order Placed Successfully";
                } elseif ($_POST['payment_method'] == "Paystack") {
                    $transfer = verify_payment_transaction($_POST['paystack_reference'], 'paystack');
                    if (isset($transfer['data']['status']) && $transfer['data']['status']) {
                        if (isset($transfer['data']['data']['status']) && $transfer['data']['data']['status'] != "success") {
                            $this->response['error'] = true;
                            $this->response['message'] = "Invalid Paystack Transaction.";
                            $this->response['data'] = array();
                            print_r(json_encode($this->response));
                            return false;
                        }
                    } else {
                        $this->response['error'] = true;
                        $this->response['message'] = "Error While Fetching the Order Details.Contact Admin ASAP.";
                        $this->response['data'] = $transfer;
                        print_r(json_encode($this->response));
                        return false;
                    }

                    $data['txn_id'] = $_POST['paystack_reference'];
                    $data['message'] = "Order Placed Successfully";
                    $data['status'] = "success";
                } elseif ($_POST['payment_method'] == "Stripe") {

                    $_POST['active_status'] = "awaiting";

                    $data['status'] = "success";
                    $data['txn_id'] = $_POST['stripe_payment_id'];
                    $data['message'] = "Order Placed Successfully";
                } elseif ($_POST['payment_method'] == "Paypal") {

                    $_POST['active_status'] = "awaiting";

                    $data['status'] = "success";
                    $data['txn_id'] = null;
                    $data['message'] = null;
                } elseif ($_POST['payment_method'] == "COD") {
                    $_POST['active_status'] = "received";
                } elseif ($_POST['payment_method'] == "wallet") {
                    $data['status'] = "success";
                    $data['txn_id'] = null;
                    $data['message'] = 'Order Placed Successfully';
                } elseif ($_POST['payment_method'] == BANK_TRANSFER) {
                    $_POST['payment_method'] = "bank_transfer";
                    $_POST['active_status'] = "awaiting";
                    $data['status'] = "awaiting";
                    $data['txn_id'] = null;
                    $data['message'] = null;
                } elseif ($_POST['payment_method'] == "my_fatoorah") {

                    $_POST['active_status'] = "awaiting";

                    $data['status'] = "success";
                    $data['txn_id'] = null;
                    $data['message'] = null;
                }
                if (isset($_POST['product_type']) && $_POST['product_type'] != 'digital_product') {
                    $_POST['address_id'] = $_POST['address_id'];
                } else {
                    $_POST['address_id'] = '';
                }

                $res = $this->order_model->place_order($_POST);

                $order_item_id = fetch_details('order_items', ['order_id' => $res['order_id']], 'id,sub_total');
                for ($i = 0; $i < count($order_item_id); $i++) {
                    $data['status'] = $data['status'];
                    $data['txn_id'] = $data['txn_id'];
                    $data['message'] = $data['message'];
                    $data['order_id'] = $res['order_id'];
                    $data['user_id'] = $_POST['user_id'];
                    $data['type'] = $_POST['payment_method'];
                    $data['amount'] = $order_item_id[$i]['sub_total'];
                    $data['order_item_id'] = $order_item_id[$i]['id'];
                    if (($_POST['payment_method'] != "COD" && $_POST['payment_method'] != "Paypal") ||  $_POST['payment_method'] == "bank_transfer") {
                        $this->transaction_model->add_transaction($data);
                    }
                }
                $this->response['error'] = false;
                $this->response['message'] = "Order Placed Successfully.";
                $this->response['data'] = $res;
                print_r(json_encode($this->response));
                return false;
            }
        } else {
            return false;
        }
    }

    public function validate_promo_code()
    {
        if ($this->data['is_logged_in']) {
            /*
            promo_code:'NEWOFF10'
            user_id:28
            final_total:'300'

        */
            $this->form_validation->set_rules('promo_code', 'Promo Code', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            } else {


                $cart = get_cart_total($this->data['user']->id, false, '0', $_POST['address_id']);
                // print_r($cart);
                // return false;

                $validate = validate_promo_code($_POST['promo_code'], $this->data['user']->id, $cart['total_arr']);
                $this->response['error'] = $validate['error'];
                $this->response['message'] = $validate['message'];
                $this->response['data'] = $validate['data'];
                print_r(json_encode($this->response));
                return false;
            }
        } else {
            return false;
        }
    }

    public function pre_payment_setup()
    {
        $payment_settings = get_settings('payment_method', true);
        $country_code = $payment_settings['myfatoorah_country'];
        if ($this->data['is_logged_in']) {
            $_POST['user_id'] = $this->data['user']->id;
            $cart = get_cart_total($this->data['user']->id, false, '0', $_POST['address_id']);
            $wallet_balance = fetch_details('users', 'id=' . $this->data['user']->id, 'balance');
            $wallet_balance = $wallet_balance[0]['balance'];
            $overall_amount = $cart['overall_amount'];
            if ($_POST['wallet_used'] == 1 && $wallet_balance > 0) {
                $overall_amount = $overall_amount - $wallet_balance;
            }

            /* Don't validate in case of Myfatoorah, because order is already placed and promocode is already validated */
            if (!empty($_POST['promo_code']) && $_POST['payment_method'] != "my_fatoorah") {
                $validate = validate_promo_code($_POST['promo_code'], $this->data['user']->id, $cart['total_arr']);
                if ($validate['error']) {
                    $this->response['error'] = true;
                    $this->response['message'] = $validate['message'];
                    print_r(json_encode($this->response));
                    return false;
                } else {
                    $overall_amount = $overall_amount - $validate['data'][0]['final_discount'];
                }
            }
            if ($_POST['payment_method'] == "Razorpay") {
                $order = $this->razorpay->create_order(($overall_amount * 100));
                if (!isset($order['error'])) {
                    $this->response['order_id'] = $order['id'];
                    $this->response['error'] = false;
                    $this->response['message'] = "Client Secret Get Successfully.";
                    print_r(json_encode($this->response));
                    return false;
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = $order['error']['description'];
                    $this->response['details'] = $order;
                    print_r(json_encode($this->response));
                    return false;
                }
            } elseif ($_POST['payment_method'] == "Stripe") {
                $order = $this->stripe->create_payment_intent(array('amount' => ($overall_amount * 100)));
                $this->response['client_secret'] = $order['client_secret'];
                $this->response['id'] = $order['id'];
            } elseif ($_POST['payment_method'] == "my_fatoorah") {
                // print_r($validate);
                // return false;
                $order_id = $_POST['my_fatoorah_order_id'];
                $amount = fetch_details('orders', ['id' => $order_id], 'total_payable');
                $total_payable = $amount[0]['total_payable'];

                $order = $this->my_fatoorah->ExecutePayment($total_payable, 2, ["UserDefinedField" => $_POST['my_fatoorah_order_id']]);
                if (!empty($order->Data)) {
                    $this->response['error'] = false;
                    $this->response['PaymentURL'] = $order->Data->PaymentURL;
                    $this->response['message'] = "success";
                    print_r(json_encode($this->response));
                    return false;
                }
            } elseif ($_POST['payment_method'] == "Midtrans") {
                $order_id = "mdtrns-" . $this->data['user']->id . "-" . time() . "-" . rand("100", "999");

                $order = $this->midtrans->create_transaction($order_id, $overall_amount);
                $order['body'] = (isset($order['body']) && !empty($order['body'])) ? json_decode($order['body'], 1) : "";

                if (!empty($order['body'])) {
                    $this->response['error'] = false;
                    $this->response['order_id'] = $order_id;
                    $this->response['token'] = $order['body']['token'];
                    $this->response['redirect_url'] = $order['body']['redirect_url'];
                    $this->response['message'] = "Transaction Token generated successfully.";
                    print_r(json_encode($this->response));
                    return false;
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = "Oops! Token couldn't be generated! check your configurations!";
                    $this->response['details'] = $order;
                    print_r(json_encode($this->response));
                    return false;
                }
            } elseif ($_POST['payment_method'] == "Flutterwave" || $_POST['payment_method'] == "Paystack" || $_POST['payment_method'] == "Paytm") {
                $this->response['error'] = false;
                $this->response['final_amount'] = $overall_amount;
            }
            $this->response['error'] = false;
            $this->response['message'] = "Client Secret Get Successfully.";
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = "Unauthorised access is not allowed.";
            print_r(json_encode($this->response));
            return false;
        }
    }
    public function get_delivery_charge()
    {
        $delivery_charge = get_delivery_charge($_POST['address_id'], $_POST['total']);
        $this->response['delivery_charge'] = $delivery_charge;
        print_r(json_encode($this->response));
    }

    public function send_bank_receipt()
    {
        $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $order_id = $this->input->post('order_id', true);

            $order = fetch_details('orders', ['id' => $order_id], 'id');
            if (empty($order)) {
                $this->response['error'] = true;
                $this->response['message'] = "Order not found!";
                $this->response['data'] = [];
                print_r(json_encode($this->response));
                return false;
            }
            if (!file_exists(FCPATH . DIRECT_BANK_TRANSFER_IMG_PATH)) {
                mkdir(FCPATH . DIRECT_BANK_TRANSFER_IMG_PATH, 0777);
            }

            $temp_array = array();
            $files = $_FILES;
            $images_new_name_arr = array();
            $images_info_error = "";
            $allowed_media_types = implode('|', allowed_media_types());
            $config = [
                'upload_path' =>  FCPATH . DIRECT_BANK_TRANSFER_IMG_PATH,
                'allowed_types' => $allowed_media_types,
                'max_size' => 8000,
            ];


            if (!empty($_FILES['attachments']['name'][0]) && isset($_FILES['attachments']['name'])) {
                $other_image_cnt = count($_FILES['attachments']['name']);
                $other_img = $this->upload;
                $other_img->initialize($config);

                for ($i = 0; $i < $other_image_cnt; $i++) {

                    if (!empty($_FILES['attachments']['name'][$i])) {

                        $_FILES['temp_image']['name'] = $files['attachments']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['attachments']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['attachments']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['attachments']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . DIRECT_BANK_TRANSFER_IMG_PATH);
                            $images_new_name_arr[$i] = DIRECT_BANK_TRANSFER_IMG_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['attachments']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['attachments']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['attachments']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['attachments']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                }
                //Deleting Uploaded attachments if any overall error occured
                if ($images_info_error != NULL || !$this->form_validation->run()) {
                    if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                        foreach ($images_new_name_arr as $key => $val) {
                            unlink(FCPATH . DIRECT_BANK_TRANSFER_IMG_PATH . $images_new_name_arr[$key]);
                        }
                    }
                }
            }
            if ($images_info_error != NULL) {
                $this->response['error'] = true;
                $this->response['message'] =  $images_info_error;
                print_r(json_encode($this->response));
                return false;
            }
            $data = array(
                'order_id' => $order_id,
                'attachments' => $images_new_name_arr,
            );
            if ($this->Order_model->add_bank_transfer_proof($data)) {

                /* Send notification */
                $settings = get_settings('system_settings', true);
                $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                $user_roles = fetch_details("user_permissions", "", '*', '',  '', '', '');
                foreach ($user_roles as $user) {
                    $user_res = fetch_details('users', ['id' => $user['user_id']], 'fcm_id');
                    if ($user_res[0]['fcm_id'] != '') {
                        $fcm_ids[0][] = $user_res[0]['fcm_id'];
                    }
                }
                //custom message
                if (!empty($fcm_ids)) {
                    $custom_notification = fetch_details('custom_notifications', ['type' => "bank_transfer_proof"], '');
                    $hashtag_order_id = '< order_id >';
                    $hashtag_application_name = '< application_name >';
                    $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);
                    $data = str_replace(array($hashtag_order_id, $hashtag_application_name), array($order_id, $app_name), $hashtag);
                    $message = output_escaping(trim($data, '"'));
                    $customer_msg = (!empty($custom_notification)) ? $message : "Hello Dear Admin you have new order bank transfer proof. Order ID #" . $order_id . ' please take note of it! Thank you. Regards ' . $app_name . '';
                    $fcmMsg = array(
                        'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "You have new order proof",
                        'body' =>   $customer_msg,
                        'type' => "bank_transfer_proof",
                    );
                    send_notification($fcmMsg, $fcm_ids);
                }
                $this->response['error'] = false;
                $this->response['message'] =  'Bank Payment Receipt Added Successfully!';
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = (!empty($data)) ? $data : [];
                print_r(json_encode($this->response));
            } else {
                $this->response['error'] = true;
                $this->response['message'] =  'Bank Payment Receipt Was Not Added';
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
                print_r(json_encode($this->response));
            }
        }
    }

    public function check_product_availability()
    {
        $this->form_validation->set_rules('address_id', 'Address Id', 'trim|numeric|xss_clean|required');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            echo json_encode($this->response);
        } else {
            $product_delivarable = array();
            $address_id = $this->input->post('address_id', true);
            $area_id = fetch_details('addresses', ['id' => $address_id], 'area_id');
            $zipcode_id = fetch_details('areas', ['id' => $area_id[0]['area_id']], 'zipcode_id');
            $zipcode = fetch_details('zipcodes', ['id' => $zipcode_id[0]['zipcode_id']], 'zipcode');
            $product_delivarable = check_cart_products_delivarable($area_id[0]['area_id'], $this->data['user']->id);
            if (!empty($product_delivarable)) {
                $product_not_delivarable = array_filter($product_delivarable, function ($var) {
                    return ($var['is_deliverable'] == false);
                });

                $this->response['error'] = (empty($product_not_delivarable)) ? false : true;
                $this->response['message'] = (empty($product_not_delivarable)) ? "All the products are deliverable on the selected address" : "Some of the item(s) are not delivarable on selected address. Try changing address or modify your cart items.";
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = $product_delivarable;
                $this->response['zipcode'] = $zipcode[0]['zipcode'];
                echo json_encode($this->response);
                return false;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Cannot delivarable to "' . $zipcode[0]['zipcode'] . '" in selected address.';
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($this->response);
                return false;
            }
        }
    }
}
