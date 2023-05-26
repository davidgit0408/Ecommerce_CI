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
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = TABLES . 'manage-orders';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'View Orders | ' . $settings['app_name'];
            $this->data['meta_description'] = ' View Order  | ' . $settings['app_name'];
            $this->data['about_us'] = get_settings('about_us');
            $this->data['curreny'] = get_settings('currency');
            if (isset($_GET['edit_id'])) {
                $order_item_data = fetch_details('order_items', ['id' => $_GET['edit_id']], 'order_id,product_name,user_id');
                $order_data = fetch_details('orders', ['id' => $order_item_data[0]['order_id']], 'email');
                $user_data = fetch_details('users', ['id' => $order_item_data[0]['user_id']], 'username');
                $this->data['fetched'] = $order_data;
                $this->data['order_item_data'] = $order_item_data;
                $this->data['user_data'] = $user_data[0];
                // $this->data['attribute'] = $attribute_value[0]['value'];
            }
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function view_orders()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $deliveryBoyId = $this->ion_auth->get_user_id();
            return $this->Order_model->get_orders_list($deliveryBoyId);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function view_order_items()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->ion_auth->get_user_id();
            return $this->Order_model->get_order_items_list(NULL, 0, 10, 'oi.id', 'DESC', $seller_id);
        } else {
            redirect('seller/login', 'refresh');
        }
    }


    public function edit_orders()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $bank_transfer = array();
            $this->data['main_page'] = FORMS . 'edit-orders';
            $settings = get_settings('system_settings', true);

            $this->data['title'] = 'View Order | ' . $settings['app_name'];
            $this->data['meta_description'] = 'View Order | ' . $settings['app_name'];
            $seller_id = $this->session->userdata('user_id');
            $res = $this->Order_model->get_order_details(['o.id' => $_GET['edit_id'], 'oi.seller_id' => $seller_id]);
            if (is_exist(['id' => $res[0]['address_id']], 'addresses')) {
                $area_id = fetch_details('addresses', ['id' => $res[0]['address_id']], 'area_id');
                if (!empty($area_id)) {
                    $zipcode_id = fetch_details('areas', ['id' => $area_id[0]['area_id']], 'zipcode_id');
                    $this->data['delivery_res'] = $this->db->where(['ug.group_id' => '3', 'u.active' => 1])->where('find_in_set(' . $zipcode_id[0]['zipcode_id'] . ', u.serviceable_zipcodes)!=', 0)->join('users_groups ug', 'ug.user_id = u.id')->get('users u')->result_array();
                }
            } else {
                $this->data['delivery_res'] = $this->db->where(['ug.group_id' => '3', 'u.active' => 1])->join('users_groups ug', 'ug.user_id = u.id')->get('users u')->result_array();
            }
            if ($res[0]['payment_method'] == "bank_transfer") {
                $bank_transfer = fetch_details('order_bank_transfer', ['order_id' => $res[0]['order_id']]);
            }
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id']) && !empty($res) && is_numeric($_GET['edit_id'])) {
                $items = [];
                foreach ($res as $row) {

                    $updated_username = fetch_details('users', 'id =' . $row['updated_by'], 'username');
                    $temp['id'] = $row['order_item_id'];
                    $temp['product_id'] = $row['product_id'];
                    $temp['item_otp'] = $row['item_otp'];
                    $temp['tracking_id'] = $row['tracking_id'];
                    $temp['courier_agency'] = $row['courier_agency'];
                    $temp['url'] = $row['url'];
                    $temp['product_variant_id'] = $row['product_variant_id'];
                    $temp['product_type'] = $row['type'];
                    $temp['pname'] = $row['pname'];
                    $temp['quantity'] = $row['quantity'];
                    $temp['is_cancelable'] = $row['is_cancelable'];
                    $temp['is_returnable'] = $row['is_returnable'];
                    $temp['tax_amount'] = $row['tax_amount'];
                    $temp['discounted_price'] = $row['discounted_price'];
                    $temp['price'] = $row['price'];
                    $temp['row_price'] = $row['row_price'];
                    $temp['active_status'] = $row['oi_active_status'];
                    $temp['updated_by'] = $updated_username[0]['username'];
                    $temp['product_image'] = $row['product_image'];
                    $temp['product_variants'] = get_variants_values_by_id($row['product_variant_id']);
                    $temp['product_type'] = $row['type'];
                    array_push($items, $temp);
                }
                $this->data['order_detls'] = $res;
                $this->data['bank_transfer'] = $bank_transfer;
                $this->data['items'] = $items;
                $this->data['seller_id'] = $seller_id;
                $this->data['settings'] = get_settings('system_settings', true);
                $this->load->view('seller/template', $this->data);
            } else {
                redirect('seller/orders/', 'refresh');
            }
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    /* To update the status of particular order item */
    public function update_order_status()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $this->form_validation->set_rules('order_item_id[]', 'Select one of the Order Items to update status ', 'trim|required|xss_clean');
            $this->form_validation->set_rules('deliver_by', 'Delvery Boy Id', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|in_list[received,processed,shipped,delivered,cancelled,returned]');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }
            $order_itam_ids = $_POST['order_item_id'];
            $order_items = fetch_details('order_items', "",  '*', "", "", "", "", "id", $order_itam_ids);
            if (isset($_POST['status']) && !empty($_POST['status']) && $_POST['status'] == 'delivered') {
                if (!get_seller_permission($order_items[0]['seller_id'], "view_order_otp")) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'You are not allowed to update delivered status on the item.';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
            if (empty($order_items)) {
                $this->response['error'] = true;
                $this->response['message'] = 'No Order Item Found';
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }

            if (count($order_itam_ids) != count($order_items)) {
                $this->response['error'] = true;
                $this->response['message'] = 'Some item was not found on status update';
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }
            // delivery boy update here
            $message = '';
            $delivery_boy_updated = 0;
            $delivery_boy_id = (isset($_POST['deliver_by']) && !empty(trim($_POST['deliver_by']))) ? $this->input->post('deliver_by', true) : 0;
            if (!empty($delivery_boy_id)) {
                $delivery_boy = fetch_details('users', ['id' => trim($delivery_boy_id)], '*');
                if (empty($delivery_boy)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Invalid Delivery Boy";
                    $this->response['data'] = array();
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    print_r(json_encode($this->response));
                    return false;
                } else {
                    $current_delivery_boys = fetch_details('order_items', "",  'delivery_boy_id', "", "", "", "", "id", $order_itam_ids);
                    $settings = get_settings('system_settings', true);
                    $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                    if (isset($current_delivery_boys[0]['delivery_boy_id']) && !empty($current_delivery_boys[0]['delivery_boy_id'])) {
                        $user_res = fetch_details('users', "",  'fcm_id,username', "", "", "", "", "id", array_column($current_delivery_boys, "delivery_boy_id"));
                    } else {
                        $user_res = fetch_details('users', ['id' => $delivery_boy_id], 'fcm_id,username');
                    }

                    $fcm_ids = array();
                    //custom message
                    if (isset($user_res[0]) && !empty($user_res[0])) {
                        $current_delivery_boy = array_column($current_delivery_boys, "delivery_boy_id");
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
                        $data = str_replace(array($hashtag_cutomer_name, $hashtag_order_id, $hashtag_application_name), array($user_res[0]['username'], $order_items[0]['order_id'], $app_name), $hashtag);
                        $message = output_escaping(trim($data, '"'));
                        if (!empty($current_delivery_boy[0]) && count($current_delivery_boy) > 1) {
                            for ($i = 0; $i < count($current_delivery_boys); $i++) {
                                $customer_msg = (!empty($custom_notification)) ? $message :  'Hello Dear ' . $user_res[$i]['username'] . 'Order status updated to' . $_POST['val'] . ' for order ID #' . $order_items[0]['order_id'] . ' assigned to you please take note of it! Thank you. Regards ' . $app_name . '';
                                $fcmMsg = array(
                                    'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                                    'body' => $customer_msg,
                                    'type' => "order",
                                );
                                if (!empty($user_res[$i]['fcm_id'])) {
                                    $fcm_ids[0][] = $user_res[$i]['fcm_id'];
                                }
                            }
                            $message = 'Delivery Boy Updated.';
                            $delivery_boy_updated = 1;
                        } else {
                            if (isset($current_delivery_boys[0]['delivery_boy_id']) && $current_delivery_boys[0]['delivery_boy_id'] == $_POST['deliver_by']) {
                                $customer_msg = (!empty($custom_notification)) ? $message :  'Hello Dear ' . $user_res[0]['username'] . 'Order status updated to' . $_POST['val'] . ' for order ID #' . $order_items[0]['order_id'] . ' assigned to you please take note of it! Thank you. Regards ' . $app_name . '';
                                $fcmMsg = array(
                                    'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                                    'body' => $customer_msg,
                                    'type' => "order",
                                );
                                $message = 'Delivery Boy Updated';
                                $delivery_boy_updated = 1;
                            } else {
                                $custom_notification =  fetch_details('custom_notifications',  ['type' => "delivery_boy_order_deliver"], '');
                                $customer_msg = (!empty($custom_notification)) ? $message : 'Hello Dear ' . $user_res[0]['username'] . 'you have new order to be deliver order ID #' . $order_items[0]['order_id'] . ' please take note of it! Thank you. Regards ' . $app_name . '';
                                $fcmMsg = array(
                                    'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "You have new order to deliver",
                                    'body' =>  $customer_msg,
                                    'type' => "order"
                                );
                                $message = 'Delivery Boy Updated.';
                                $delivery_boy_updated = 1;
                            }
                            if (!empty($user_res[0]['fcm_id'])) {
                                $fcm_ids[0][] = $user_res[0]['fcm_id'];
                            }
                        }
                    }
                    if (!empty($fcm_ids)) {
                        send_notification($fcmMsg, $fcm_ids);
                    }
                    if ($this->Order_model->update_order(['delivery_boy_id' => $delivery_boy_id], $order_itam_ids, false, 'order_items')) {
                        $delivery_error = false;
                    }
                }
            }

            $item_ids = implode(",", $_POST['order_item_id']);
            $res = validate_order_status($item_ids, $_POST['status']);

            if ($res['error']) {
                $this->response['error'] = $delivery_boy_updated == 1 ? false : true;
                $this->response['message'] = (isset($_POST['status']) && !empty($_POST['status'])) ? $message . $res['message'] :  $message;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            if (!empty($order_items)) {
                for ($j = 0; $j < count($order_items); $j++) {
                    $order_item_id = $order_items[$j]['id'];
                    /* velidate bank transfer method status */
                    $order_method = fetch_details('orders', ['id' => $order_items[$j]['order_id']], 'payment_method');
                    if ($order_method[0]['payment_method'] == 'bank_transfer') {
                        $bank_receipt = fetch_details('order_bank_transfer', ['order_id' => $order_items[$j]['order_id']]);
                        $transaction_status = fetch_details('transactions', ['order_id' => $order_items[$j]['order_id']], 'status');
                        if (empty($bank_receipt) || strtolower($transaction_status[$j]['status']) != 'success' || $bank_receipt[0]['status'] == "0" || $bank_receipt[0]['status'] == "1") {
                            $this->response['error'] = true;
                            $this->response['message'] = "Order item status can not update, Bank verification is remain from transactions for this order.";
                            $this->response['csrfName'] = $this->security->get_csrf_token_name();
                            $this->response['csrfHash'] = $this->security->get_csrf_hash();
                            $this->response['data'] = array();
                            print_r(json_encode($this->response));
                            return false;
                        }
                    }

                    // processing order items
                    $order_item_res = $this->db->select(' * , (Select count(id) from order_items where order_id = oi.order_id ) as order_counter ,(Select count(active_status) from order_items where active_status ="cancelled" and order_id = oi.order_id ) as order_cancel_counter , (Select count(active_status) from order_items where active_status ="returned" and order_id = oi.order_id ) as order_return_counter,(Select count(active_status) from order_items where active_status ="delivered" and order_id = oi.order_id ) as order_delivered_counter , (Select count(active_status) from order_items where active_status ="processed" and order_id = oi.order_id ) as order_processed_counter , (Select count(active_status) from order_items where active_status ="shipped" and order_id = oi.order_id ) as order_shipped_counter , (Select status from orders where id = oi.order_id ) as order_status ')
                        ->where(['id' => $order_item_id])
                        ->get('order_items oi')->result_array();

                    if ($this->Order_model->update_order(['status' => $_POST['status']], ['id' => $order_item_res[0]['id']], true, 'order_items')) {
                        $this->Order_model->update_order(['active_status' => $_POST['status']], ['id' => $order_item_res[0]['id']], false, 'order_items');
                        process_refund($order_item_res[0]['id'], $_POST['status'], 'order_items');
                        if (trim($_POST['status']) == 'cancelled' || trim($_POST['status']) == 'returned') {
                            $data = fetch_details('order_items', ['id' => $order_item_id], 'product_variant_id,quantity');
                            update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
                        }
                        if (($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_cancel_counter']) + 1 && $_POST['status'] == 'cancelled') ||  ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_return_counter']) + 1 && $_POST['status'] == 'returned') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_delivered_counter']) + 1 && $_POST['status'] == 'delivered') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_processed_counter']) + 1 && $_POST['status'] == 'processed') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_shipped_counter']) + 1 && $_POST['status'] == 'shipped')) {
                            /* process the refer and earn */
                            $user = fetch_details('orders', ['id' => $order_item_res[0]['order_id']], 'user_id');
                            $user_id = $user[0]['user_id'];
                            $response = process_referral_bonus($user_id, $order_item_res[0]['order_id'], $_POST['status']);
                        }
                    }
                    //Update login id in order_item table
                    update_details(['updated_by' => $order_items[0]['seller_id']], ['id' => $order_item_res[0]['id']], 'order_items');
                }
                $settings = get_settings('system_settings', true);
                $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                $user_res = fetch_details('users', ['id' => $user_id], 'username,fcm_id');
                $fcm_ids = array();
                //custom message
                if (!empty($user_res[0]['fcm_id'])) {
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
                    $data = str_replace(array($hashtag_cutomer_name, $hashtag_order_id, $hashtag_application_name), array($user_res[0]['username'], $order_items[0]['order_id'], $app_name), $hashtag);
                    $message = output_escaping(trim($data, '"'));
                    $customer_msg = (!empty($custom_notification)) ? $message :  'Hello Dear ' . $user_res[0]['username'] . 'Order status updated to' . $_POST['val'] . ' for order ID #' . $order_items[0]['order_id'] . ' assigned to you please take note of it! Thank you. Regards ' . $app_name . '';
                    $fcmMsg = array(
                        'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                        'body' => $customer_msg,
                        'type' => "order"
                    );

                    $fcm_ids[0][] = $user_res[0]['fcm_id'];
                    send_notification($fcmMsg, $fcm_ids);
                }

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

    public function get_order_tracking()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            return $this->Order_model->get_order_tracking_list();
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function update_order_tracking()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('courier_agency', 'courier_agency', 'trim|required|xss_clean');
            $this->form_validation->set_rules('tracking_id', 'tracking_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('url', 'url', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_item_id', 'order item id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id = $this->input->post('order_id', true);
                $order_item_id = $this->input->post('order_item_id', true);
                $courier_agency = $this->input->post('courier_agency', true);
                $tracking_id = $this->input->post('tracking_id', true);
                $url = $this->input->post('url', true);
                $data = array(
                    'order_id' => $order_id,
                    'order_item_id' => $order_item_id,
                    'courier_agency' => $courier_agency,
                    'tracking_id' => $tracking_id,
                    'url' => $url,
                );
                if (is_exist(['order_item_id' => $order_item_id, 'order_id' => $order_id], 'order_tracking', null)) {
                    if (update_details($data, ['order_id' => $order_id, 'order_item_id' => $order_item_id], 'order_tracking') == TRUE) {
                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Tracking details Update Successfuly.";
                    } else {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Not Updated. Try again later.";
                    }
                } else {
                    if (insert_details($data, 'order_tracking')) {
                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Tracking details Insert Successfuly.";
                    } else {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Not Inserted. Try again later.";
                    }
                }
                print_r(json_encode($this->response));
            }
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    function order_tracking()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = TABLES . 'order-tracking';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Order Tracking | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Order Tracking | ' . $settings['app_name'];
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function get_digital_order_mails()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            return $this->Order_model->get_digital_order_mail_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function send_digital_product()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');
            $this->form_validation->set_rules('pro_input_file', 'Attachment file', 'trim|required|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }
            $mail =  $this->Order_model->send_digital_product($_POST);
            if ($mail['error'] == true) {
                $this->response['error'] = true;
                $this->response['message'] = "Cannot send mail. You can try to send mail manually.";
                $this->response['data'] = $mail['message'];
                echo json_encode($this->response);
                return false;
            } else {
                $this->response['error'] = false;
                $this->response['message'] = 'Mail sent successfully.';
                $this->response['data'] = array();
                echo json_encode($this->response);
                update_details(['active_status' => 'delivered'], ['id' => $_POST['order_item_id']], 'order_items');
                update_details(['is_sent' => 1], ['id' => $_POST['order_item_id']], 'order_items');
                $data = array(
                    'order_id' => $_POST['order_id'],
                    'order_item_id' => $_POST['order_item_id'],
                    'subject' => $_POST['subject'],
                    'message' => $_POST['message'],
                    'file_url' => $_POST['pro_input_file'],
                );
                insert_details($data, 'digital_orders_mails');
                return false;
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
