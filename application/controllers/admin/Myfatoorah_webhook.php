<?php defined('BASEPATH') or exit('No direct script access allowed');
class myfatoorah_webhook extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function myfatoorah()
    {
        //Debug in server first
      

        $txn_id = (isset($request['payload']['payment']['entity']['id'])) ? $request['payload']['payment']['entity']['id'] : "";

        


        if (!empty($request['payload']['payment']['entity']['id'])) {
            if (!empty($txn_id)) {
                $transaction = fetch_details('transactions', ['txn_id' => $txn_id], '*');
            }
            $amount = $request['payload']['payment']['entity']['amount'];
            $amount = ($amount / 100);
        } else {
            $amount = 0;
            $currency = (isset($request['payload']['payment']['entity']['currency'])) ? $request['payload']['payment']['entity']['currency'] : "";
        }


        if (!empty($transaction)) {
            $order_id = $transaction[0]['order_id'];
            $user_id = $transaction[0]['user_id'];
        } 
        else 
        {
            $order_id = 0;
            $order_id = (isset($request['payload']['order']['entity']['notes']['order_id'])) ? $request['payload']['order']['entity']['notes']['order_id'] : $request['payload']['payment']['entity']['notes']['order_id'];
        }

        $this->load->model('transaction_model');

        if ($http_razorpay_signature) {
            if ($request['event'] == 'payment.authorized') {
                $currency = (isset($request['payload']['payment']['entity']['currency'])) ? $request['payload']['payment']['entity']['currency'] : "INR";
                $this->load->library("razorpay");
                $response = $this->razorpay->capture_payment($amount * 100, $txn_id, $currency);
                return;
            }
            if ($request['event'] == 'payment.captured' || $request['event'] == 'order.paid') {
                if ($request['event'] == 'order.paid') {
                    $order_id = $request['payload']['order']['entity']['receipt'];
                    $order_data = fetch_orders($order_id);
                    $user_id = (isset($order_data['order_data'][0]['user_id'])) ? $order_data['order_data'][0]['user_id'] : "";
                }
                if (!empty($order_id)) {
                    /* To do the wallet recharge if the order id is set in the patter */
                    if (strpos($order_id, "wallet-refill-user") !== false) {
                        if (!is_numeric($order_id) && strpos($order_id, "wallet-refill-user") !== false) {
                            $temp = explode("-", $order_id);
                            if (isset($temp[3]) && is_numeric($temp[3]) && !empty($temp[3] && $temp[3] != '')) {
                                $user_id = $temp[3];
                            } else {
                                $user_id = 0;
                            }
                        }

                        $data['transaction_type'] = "wallet";
                        $data['user_id'] = $user_id;
                        $data['order_id'] = $order_id;
                        $data['type'] = "credit";
                        $data['txn_id'] = $txn_id;
                        $data['amount'] = $amount;
                        $data['status'] = "success";
                        $data['message'] = "Wallet refill successful";
                        log_message('error', 'Razorpay user ID -  transaction data--> ' . var_export($data, true));


                        $this->transaction_model->add_transaction($data);
                        log_message('error', 'Razorpay user ID - Add transaction --> ' . var_export($txn_id, true));


                        $this->load->model('customer_model');
                        if ($this->customer_model->update_balance($amount, $user_id, 'add')) {
                            $response['error'] = false;
                            $response['transaction_status'] = $request['event'];
                            $response['message'] = "Wallet recharged successfully!";
                            log_message('error', 'Razorpay user ID - Wallet recharged successfully --> ' . var_export($order_id, true));
                        } else {
                            $response['error'] = true;
                            $response['transaction_status'] = $request['event'];
                            $response['message'] = "Wallet could not be recharged!";
                            log_message('error', 'razorpay Webhook | wallet recharge failure --> ' . var_export($request['event'], true));
                        }
                        echo json_encode($response);
                        return false;
                    } else {

                        /* process the order and mark it as received */
                        $order = fetch_orders($order_id, false, false, false, false, false, false, false);
                        if (isset($order['order_data'][0]['user_id'])) {
                            $user = fetch_details('users', ['id' => $order['order_data'][0]['user_id']]);
                            $overall_total = array(
                                'total_amount' => $order['order_data'][0]['total'],
                                'delivery_charge' => $order['order_data'][0]['delivery_charge'],
                                'tax_amount' => $order['order_data'][0]['total_tax_amount'],
                                'tax_percentage' => $order['order_data'][0]['total_tax_percent'],
                                'discount' =>  $order['order_data'][0]['promo_discount'],
                                'wallet' =>  $order['order_data'][0]['wallet_balance'],
                                'final_total' =>  $order['order_data'][0]['final_total'],
                                'otp' => $order['order_data'][0]['otp'],
                                'address' =>  $order['order_data'][0]['address'],
                                'payment_method' => $order['order_data'][0]['payment_method']
                            );

                            $overall_order_data = array(
                                'cart_data' => $order['order_data'][0]['order_items'],
                                'order_data' => $overall_total,
                                'subject' => 'Order received successfully',
                                'user_data' => $user[0],
                                'system_settings' => $system_settings,
                                'user_msg' => 'Hello, Dear ' . ucfirst($user[0]['username']) . ', We have received your order successfully. Your order summaries are as followed',
                                'otp_msg' => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
                            );
                            if (isset($user[0]['email']) && !empty($user[0]['email'])) {
                                send_mail($user[0]['email'], 'Order received successfully', $this->load->view('admin/pages/view/email-template.php', $overall_order_data, TRUE));
                            }
                            /* No need to add because the transaction is already added just update the transaction status */
                            if (!empty($transaction)) {
                                $transaction_id = $transaction[0]['id'];
                                update_details(['status' => 'success'], ['id' => $transaction_id], 'transactions');
                            } else {
                                /* add transaction of the payment */
                                $amount = ($request['payload']['payment']['entity']['amount'] / 100);
                                $data = [
                                    'transaction_type' => 'transaction',
                                    'user_id' => $user_id,
                                    'order_id' => $order_id,
                                    'type' => 'razorpay',
                                    'txn_id' => $txn_id,
                                    'amount' => $amount,
                                    'status' => 'success',
                                    'message' => 'order placed successfully',
                                ];
                                $this->transaction_model->add_transaction($data);
                            }

                            update_details(['active_status' => 'received'], ['order_id' => $order_id], 'order_items');
                            $status = json_encode(array(array('received', date("d-m-Y h:i:sa"))));
                            update_details(['status' => $status], ['order_id' => $order_id], 'order_items', false);

                            // place order custome notification on payment success

                            $custom_notification = fetch_details('custom_notifications', ['type' => "place_order"], '');
                            $hashtag_order_id = '< order_id >';
                            $string = json_encode($custom_notification[0]['title'], JSON_UNESCAPED_UNICODE);
                            $hashtag = html_entity_decode($string);
                            $data1 = str_replace($hashtag_order_id, $order_id, $hashtag);
                            $title = output_escaping(trim($data1, '"'));
                            $hashtag_application_name = '< application_name >';
                            $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                            $hashtag = html_entity_decode($string);
                            $data2 = str_replace($hashtag_application_name, $system_settings['app_name'], $hashtag);
                            $message = output_escaping(trim($data2, '"'));

                            $fcm_admin_subject = (!empty($custom_notification)) ? $title : 'New order placed ID #' . $order_id;
                            $fcm_admin_msg = (!empty($custom_notification)) ? $message : 'New order received for  ' . $system_settings['app_name'] . ' please process it.';
                            $user_fcm = fetch_details('users', ['id' => $user_id], 'fcm_id');
                            $user_fcm_id[0][] = $user_fcm[0]['fcm_id'];
                            if (!empty($user_fcm_id)) {
                                $fcmMsg = array(
                                    'title' => $fcm_admin_subject,
                                    'body' => $fcm_admin_msg,
                                    'type' => "place_order",
                                    'content_available' => true
                                );
                                send_notification($fcmMsg, $user_fcm_id);
                            }
                            update_stock($order['order_data'][0]['product_variant_ids'], $order['order_data'][0]['quantity'], 'plus');
                        }
                    }
                } else {
                    log_message('error', 'Razorpay Order id not found --> ' . var_export($request, true));
                    /* No order ID found */
                }

                $response['error'] = false;
                $response['transaction_status'] = $request['event'];
                $response['message'] = "Transaction successfully done";
                echo json_encode($response);
                return false;
            } elseif ($request['event'] == 'payment.failed') {
                //$order = fetch_orders($order_id, false, false, false, false, false, false, false);

                if (!empty($order_id)) {
                    // update_stock($order['order_data'][0]['product_variant_ids'], $order['order_data'][0]['quantity'], 'plus');
                    update_details(['active_status' => 'cancelled'], ['order_id' => $order_id], 'order_items');
                }
                /* No need to add because the transaction is already added just update the transaction status */
                if (!empty($transaction)) {
                    $transaction_id = $transaction[0]['id'];
                    update_details(['status' => 'failed'], ['id' => $transaction_id], 'transactions');
                }
                $response['error'] = true;
                $response['transaction_status'] = $request['event'];
                $response['message'] = "Transaction is failed. ";
                log_message('error', 'Razorpay Webhook | Transaction is failed --> ' . var_export($request['event'], true));
                echo json_encode($response);
                return false;
            } elseif ($request['event'] == 'payment.authorized') {
                if (!empty($order_id)) {
                    update_details(['active_status' => 'awaiting'], ['order_id' => $order_id], 'order_items');
                }
            } elseif ($request['event'] == "refund.processed") {
                //Refund Successfully
                $transaction = fetch_details('transactions', ['txn_id' => $request['payload']['refund']['entity']['payment_id']]);
                if (empty($transaction)) {
                    return false;
                }
                process_refund($transaction[0]['id'], $transaction[0]['status']);
                $response['error'] = false;
                $response['transaction_status'] = $request['event'];
                $response['message'] = "Refund successfully done. ";
                log_message('error', 'Razorpay Webhook | Payment refund done --> ' . var_export($request['event'], true));
                echo json_encode($response);
                return false;
            } elseif ($request['event'] == "refund.failed") {
                $response['error'] = true;
                $response['transaction_status'] = $request['event'];
                $response['message'] = "Refund is failed. ";
                log_message('error', 'Razorpay Webhook | Payment refund failed --> ' . var_export($request['event'], true));
                echo json_encode($response);
                return false;
            } else {
                $response['error'] = true;
                $response['transaction_status'] = $request['event'];
                $response['message'] = "Transaction could not be detected.";
                log_message('error', 'Razorpay Webhook | Transaction could not be detected --> ' . var_export($request['event'], true));
                echo json_encode($response);
                return false;
            }
        } else {
            log_message('error', 'razorpay Webhook | Invalid Server Signature  --> ' . var_export($request['event'], true));
            return false;
        }
    }

    // public function test()
    // {
    //     $this->load->model('transaction_model');
    //    $data =[
    //         'transaction_type' => 'wallet',
    //         'user_id' => '167',
    //         'order_id' => 'wallet-refill-user-167-1660712582142-979',
    //         'type' => 'credit',
    //         'txn_id' => 'pay_K6ZtiikkdDsDPC',
    //         'amount' => 1000,
    //         'status' => 'success',
    //         'message' => 'Wallet refill successful',
    //    ];
    //    $this->transaction_model->add_transaction($data);
    // }
    public function edie($error_msg)
    {
        global $debug_email;
        $report =  "ERROR : " . $error_msg . "\n\n";
        $report .= "POST DATA\n\n";
        foreach ($_POST as $key => $value) {
            $report .= "|$key| = |$value| \n";
        }
        log_message('error', $report);
        die($error_msg);
    }
   
    




}
