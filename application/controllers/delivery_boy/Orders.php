<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Orders extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model('Order_model');
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
            $this->data['main_page'] = TABLES . 'manage-orders';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'View Orders | ' . $settings['app_name'];
            $this->data['meta_description'] = ' View Order  | ' . $settings['app_name'];
            $this->data['about_us'] = get_settings('about_us');
            $this->data['curreny'] = get_settings('currency');
            $this->load->view('delivery_boy/template', $this->data);
        } else {
            redirect('delivery_boy/login', 'refresh');
        }
    }

    public function view_orders()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
            $deliveryBoyId = $this->ion_auth->get_user_id();
            return $this->Order_model->get_order_items_list($deliveryBoyId);
        } else {
            redirect('delivery_boy/login', 'refresh');
        }
    }

    public function edit_orders()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
            $delivery_boy = $this->ion_auth->user()->row();
            $this->data['main_page'] = FORMS . 'edit-orders';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'View Order | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Eshop  | View Order | ' . $settings['app_name'];
            $res = $this->Order_model->get_order_details(['o.id' => $_GET['edit_id']]);
            if ($delivery_boy->id == $res[0]['delivery_boy_id'] && isset($_GET['edit_id']) && !empty($_GET['edit_id']) && !empty($res) && is_numeric($_GET['edit_id'])) {
                $items = [];
                foreach ($res as $row) {
                    if ($delivery_boy->id == $row['delivery_boy_id']) {
                        $updated_username = fetch_details('users', 'id =' . $row['updated_by'], 'username');
                        $temp['id'] = $row['order_item_id'];
                        $temp['product_id'] = $row['product_id'];
                        $temp['product_variant_id'] = $row['product_variant_id'];
                        $temp['product_type'] = $row['type'];
                        $temp['pname'] = $row['pname'];
                        $temp['quantity'] = $row['quantity'];
                        $temp['tax_amount'] = $row['tax_amount'];
                        $temp['discounted_price'] = $row['discounted_price'];
                        $temp['price'] = $row['price'];
                        $temp['active_status'] = $row['oi_active_status'];
                        $temp['product_image'] = $row['product_image'];
                        $temp['updated_by'] = $updated_username[0]['username'];
                        array_push($items, $temp);
                    }
                }
                $this->data['order_detls'] = $res;
                $this->data['items'] = $items;
                $this->data['settings'] = get_settings('system_settings', true);
                $this->load->view('delivery_boy/template', $this->data);
            } else {
                redirect('delivery_boy/orders/', 'refresh');
            }
        } else {
            redirect('delivery_boy/login', 'refresh');
        }
    }

    /* To update the status of particular order item */
    public function update_order_status()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
            $res = validate_order_status($_GET['id'], $_GET['status']);
            if ($res['error']) {
                $this->response['error'] = true;
                $this->response['message'] = $res['message'];
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            $order_item_res = $this->db->select(' * ,oi.id as order_item_id, (Select count(id) from order_items where order_id = oi.order_id ) as order_counter ,(Select count(active_status) from order_items where active_status ="cancelled" and order_id = oi.order_id ) as order_cancel_counter , (Select count(active_status) from order_items where active_status ="returned" and order_id = oi.order_id ) as order_return_counter,(Select count(active_status) from order_items where active_status ="delivered" and order_id = oi.order_id ) as order_delivered_counter , (Select count(active_status) from order_items where active_status ="processed" and order_id = oi.order_id ) as order_processed_counter , (Select count(active_status) from order_items where active_status ="shipped" and order_id = oi.order_id ) as order_shipped_counter , (Select status from orders where id = oi.order_id ) as order_status ')
                ->where(['id' => $_GET['id']])
                ->get('order_items oi')->result_array();
            if ($_GET['status'] == 'delivered') {
                if (!validate_otp($order_item_res[0]['order_item_id'], $_GET['otp'])) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Invalid OTP supplied!';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
            $order_id = fetch_details('order_items', ['id' => $_GET['id']], 'order_id');
            $order_method = fetch_details('orders', ['id' => $order_id[0]['order_id']], 'payment_method');
            if ($order_method[0]['payment_method'] == 'bank_transfer') {
                $bank_receipt = fetch_details('order_bank_transfer', ['order_id' => $order_id[0]['order_id']]);
                $transaction_status = fetch_details('transactions', ['order_id' => $order_id[0]['order_id']], 'status');
                if (empty($bank_receipt) || strtolower($transaction_status[0]['status']) != 'success') {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order Status can not update, Bank verification is remain from transactions.";
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
            if ($this->Order_model->update_order(['status' => $_GET['status']], ['id' => $order_item_res[0]['id']], true, 'order_items')) {

                $this->Order_model->update_order(['active_status' => $_GET['status']], ['id' => $order_item_res[0]['id']], false, 'order_items');
                process_refund($order_item_res[0]['id'], $_GET['status'], 'order_items');
                if (($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_cancel_counter']) + 1 && $_GET['status'] == 'cancelled') ||  ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_return_counter']) + 1 && $_GET['status'] == 'returned') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_delivered_counter']) + 1 && $_GET['status'] == 'delivered') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_processed_counter']) + 1 && $_GET['status'] == 'processed') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_shipped_counter']) + 1 && $_GET['status'] == 'shipped')) {
                    /* process the refer and earn */
                    if (trim($_GET['status']) == 'cancelled') {
                        $data = fetch_details('order_items', ['id' => $_GET['id']], 'product_variant_id,quantity');
                        update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
                    }

                    $user = fetch_details('orders', ['id' => $order_item_res[0]['order_id']], 'user_id');
                    $user_id = $user[0]['user_id'];
                    $response = process_referral_bonus($user_id, $order_item_res[0]['order_id'], $_GET['status']);
                    $settings = get_settings('system_settings', true);
                    $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                    $user_res = fetch_details('users', ['id' => $user_id], 'username,fcm_id');
                    $fcm_ids = array();
                    //custom message
                    if ($_POST['status'] == 'received') {
                        $type = ['type' => "customer_order_received"];
                    } elseif ($_POST['status'] == 'processed') {
                        $type = ['type' => "customer_order_processed"];
                    } elseif ($_POST['status'] == 'shipped') {
                        $type = ['type' => "customer_order_shipped"];
                    } elseif ($_POST['status'] == 'delivered') {
                        $type = ['type' => "customer_order_delivered"];
                    } elseif ($_POST['status'] == 'cancelled') {
                        $type = ['type' => "customer_order_cancelled"];
                    } elseif ($_POST['status'] == 'returned') {
                        $type = ['type' => "customer_order_returned"];
                    }
                    $custom_notification = fetch_details('custom_notifications', $type, '');
                    $hashtag_cutomer_name = '< cutomer_name >';
                    $hashtag_order_id = '< order_item_id >';
                    $hashtag_application_name = '< application_name >';
                    $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);
                    $data = str_replace(array($hashtag_cutomer_name, $hashtag_order_id, $hashtag_application_name), array($user_res[0]['username'], $order_item_res[0]['order_id'], $app_name), $hashtag);
                    $message = output_escaping(trim($data, '"'));
                    $customer_msg = (!empty($custom_notification)) ? $message :  'Hello Dear ' . $user_res[0]['username'] . 'Order status updated to' . $_GET['status'] . ' for your order ID #' . $order_item_res[0]['order_id'] . ' please take note of it! Thank you for shopping with us. Regards ' . $app_name . '';

                    if (!empty($user_res[0]['fcm_id'])) {
                        $fcmMsg = array(
                            'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                            'body' => $customer_msg,
                            'type' => "order",
                        );

                        $fcm_ids[0][] = $user_res[0]['fcm_id'];
                        send_notification($fcmMsg, $fcm_ids);
                    }
                }
                // Update login id in order_item table
                update_details(['updated_by' => $_SESSION['user_id']], ['id' =>  $_GET['id']], 'order_items');
                $this->response['error'] = false;
                $this->response['message'] = 'Status Updated Successfully';
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access not allowed!';
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
    }
}
