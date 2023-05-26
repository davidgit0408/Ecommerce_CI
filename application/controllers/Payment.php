<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['Paypal_lib', 'paytm', 'my_fatoorah']);
        $this->load->model(['cart_model', 'address_model', 'order_model', 'transaction_model']);
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
    }

    public function paypal()
    {
        $this->form_validation->set_rules('order_id', 'Order', 'trim|required|xss_clean|numeric');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $user_id = $this->data['user']->id;
        $order_id = $this->input->post('order_id', true);

        $order = $this->db->where('id', $order_id)->get('orders')->row_array();
        if (empty($order)) {
            $this->response['error'] = true;
            $this->response['message'] = "No Order Found.";
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        // Set variables for paypal form
        $returnURL = base_url() . 'payment/success';
        $cancelURL = base_url() . 'payment/cancel';
        $notifyURL = base_url() . 'app/v1/api/ipn';
        $txn_id = time() . "-" . rand();

        $this->paypal_lib->add_field('return', $returnURL);
        $this->paypal_lib->add_field('cancel_return', $cancelURL);
        $this->paypal_lib->add_field('notify_url', $notifyURL);
        $this->paypal_lib->add_field('item_name', 'Test');
        $this->paypal_lib->add_field('custom', $this->data['user']->id . '|' . $this->data['user']->email);
        $this->paypal_lib->add_field('item_number', $order['id']);
        $this->paypal_lib->add_field('amount', $order['total_payable']);
        // Render paypal form
        $this->paypal_lib->paypal_auto_form();
    }
    public function paytm()
    {

        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('promo_code', 'Promo Code', 'trim|xss_clean');
            $this->form_validation->set_rules('latitude', 'Latitude', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('longitude', 'Longitude', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('deliver_date', 'Delivery Date', 'trim|xss_clean');
            $this->form_validation->set_rules('deliver_time', 'Delivery time', 'trim|xss_clean');
            $this->form_validation->set_rules('address_id', 'Address id', 'trim|required|numeric|xss_clean', array('required' => 'Please choose address'));
            $this->form_validation->set_rules('wallet_used', 'Wallet used', 'trim|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return;
            }
            $settings = get_settings('system_settings', true);
            $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : 'eShop - ecommerce';
            $app_name = str_replace(" ", "", $settings['app_name']);
            $order_id = $app_name . "-" . time() . rand(1000, 9999);
            $credentials = $this->paytm->get_credentials();
            $mid =  $credentials['paytm_merchant_id'];
            $cart = get_cart_total($this->data['user']->id, false, '0', $_POST['address_id']);
            $promo_discount = 0;
            if (isset($_POST['promo_code']) && !empty($_POST['promo_code'])) {
                $validate = validate_promo_code($_POST['promo_code'], $this->data['user']->id, $cart['total_arr']);
                if ($validate['error'] == false) {
                    $promo_discount = $validate['data'][0]['final_discount'];
                } else {
                    $this->session->set_flashdata('message', $validate['message']);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect(base_url('cart/checkout'), 'refresh');
                }
            }
            $wallet_amount = 0;
            if (isset($_POST['wallet_used']) && $_POST['wallet_used'] == 1) {
                $wallet_balance = fetch_details('users', 'id=' . $this->data['user']->id, 'balance');
                $wallet_balance = $wallet_balance[0]['balance'];
                $final_total = $cart['overall_amount'];
                if ($wallet_balance > 0) {
                    if ($wallet_balance >= $final_total) {
                        $wallet_amount = $final_total;
                    } else {
                        $wallet_amount = $wallet_balance;
                    }
                } else {
                    $this->session->set_flashdata('message', 'Insufficient balance');
                    $this->session->set_flashdata('message_type', 'error');
                    redirect(base_url('cart/checkout'), 'refresh');
                }
            }
            $overall_amount = $cart['overall_amount'] - $wallet_amount - $promo_discount;
            $overall_amount = number_format($overall_amount, 2, '.', '');

            $paramList = array();
            $paramList["MID"] = $mid;
            $paramList["ORDER_ID"] = $order_id;
            $paramList["CUST_ID"] = $this->data['user']->id;
            $paramList["INDUSTRY_TYPE_ID"] = $credentials['paytm_industry_type_id'];
            $paramList["CHANNEL_ID"] = "WEB";
            $paramList["TXN_AMOUNT"] = $overall_amount;
            $paramList["WEBSITE"] = $credentials['paytm_website'];
            $paramList["CALLBACK_URL"] = base_url("payment/paytm-response");
            $paramList["MERC_UNQ_REF"] = $_POST['address_id'] . '|' . $_POST['wallet_used'] . '|' . $_POST['promo_code'] . '|' . $_POST['latitude'] . '|' . $_POST['longitude'] . '|' . $_POST['deliver_date'] . '|' . $_POST['deliver_time'] . '|' . $_POST['mobile'];


            $checksum = $this->paytm->generateSignature($paramList, $credentials['paytm_merchant_key']);

            $form_html = "<body>
        <table align='center' cellspacing='4'>
            <tr>
                <td align='center'><STRONG>Transaction is being processed,</STRONG></td>
            </tr>
            <tr>
                <td align='center'>
                    <font color='blue'>Please wait ...</font>
                </td>
            </tr>
            <tr>
                <td align='center'>(Please do not press 'Refresh' or 'Back' button)</td>
            </tr>
            <tr>
                <td align='center'><img src=" . base_url('assets/old-pre-loader.gif') . " alt='Please wait.. Loading' title='Please wait.. Loading..' width='140px' /></td>
            </tr>
            <tr>
                <td align='center'><a href='#' style='padding: 8px 12px;background-color: #008CBA;color:white;text-decoration:none;' onclick='document.forms[\"payment_form\"].submit();'>Click here if you are not automatically redirected..</a></td>
            </tr>
            
        </table>
        <FORM NAME='payment_form' ACTION='https://securegw-stage.paytm.in/theia/processTransaction' METHOD='POST'>
            <input type='hidden' name='MID' value='" . $credentials['paytm_merchant_id'] . "'>
            <input type='hidden' name='WEBSITE' value='" . $credentials['paytm_website'] . "'>
            <input type='hidden' name='ORDER_ID' value='" . $order_id . "'>
            <input type='hidden' name='CUST_ID' value='" . $this->data['user']->id . "'>
            <input type='hidden' name='INDUSTRY_TYPE_ID' value='" . $credentials['paytm_industry_type_id'] . "'>
            <input type='hidden' name='CHANNEL_ID' value='WEB'>
            <input type='hidden' name='TXN_AMOUNT' value='" . $overall_amount . "'>
            <input type='hidden' name='CALLBACK_URL' value='" . $paramList['CALLBACK_URL'] . "'>
            <input type='hidden' name='CHECKSUMHASH' value='" . $checksum . "'>
            <input type='hidden' name='MERC_UNQ_REF' value='" . $_POST['address_id'] . '|' . $_POST['wallet_used'] . '|' . $_POST['promo_code'] . '|' . $_POST['latitude'] . '|' . $_POST['longitude'] . '|' . $_POST['deliver_date'] . '|' . $_POST['deliver_time'] . '|' . $_POST['mobile'] . "'>
        </FORM>
    </body>
    <script type='text/javascript'>
        document.forms[0].submit();
    </script>";
            echo $form_html;
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function initiate_paytm_transaction()
    {
        if ($this->data['is_logged_in']) {
            $_POST['user_id'] = $this->data['user']->id;
            $cart = get_cart_total($this->data['user']->id, false, '0', $_POST['address_id']);
            $wallet_balance = fetch_details('users', 'id=' . $this->data['user']->id, 'balance');
            $wallet_balance = $wallet_balance[0]['balance'];
            $overall_amount = $cart['overall_amount'];
            if ($_POST['wallet_used'] == 1 && $wallet_balance > 0) {
                $overall_amount = $overall_amount - $wallet_balance;
            }
            if (!empty($_POST['promo_code'])) {
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
            $amount = $overall_amount;
            $user_id = $this->data['user']->id;
            $settings = get_settings('system_settings', true);
            $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : 'eShop - ecommerce';
            $app_name = str_replace(" ", "", $settings['app_name']);
            $order_id = $app_name . "-" . time() . rand(1000, 9999);
            $paytmParams = array();

            $paytmParams["body"] = array(
                "requestType"   => "Payment",
                "websiteName"   => "WEBSTAGING",
                "orderId"       => $order_id,
                "txnAmount"     => array(
                    "value"     => $amount,
                    "currency"  => "INR",
                ),
                "callbackUrl"   => base_url('payment/paytm_response'),
                "userInfo"      => array(
                    "custId"    => $user_id,
                ),
            );
            $res = $this->paytm->initiate_transaction($paytmParams);
            $this->response['error'] = false;
            $this->response['message'] = 'trasaction initiated successfully';
            $this->response['data'] = $res;
            $this->response['data']['order_id'] = $order_id;
            print_r(json_encode($this->response));
            return;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = "Unauthorised access is not allowed.";
            print_r(json_encode($this->response));
            return false;
        }
    }
    public function paytm_response()
    {

        if ($this->ion_auth->logged_in()) {
            $credentials = $this->paytm->get_credentials();
            $paytmChecksum = "";
            $paramList = array();
            $isValidChecksum = "FALSE";

            $paramList = $_POST;

            $paytmChecksum = isset($paramList["CHECKSUMHASH"]) ? $paramList["CHECKSUMHASH"] : ""; //Sent by Paytm pg
            $isValidChecksum = $this->paytm->verifySignature($paramList, $credentials['paytm_merchant_key'], $paytmChecksum); //will return TRUE or FALSE string.
            if ($isValidChecksum == "TRUE") {
                $response = verify_payment_transaction($paramList['ORDERID'], 'paytm');
                $txn = fetch_details('transactions', ['txn_id' => $response['data']['body']['orderId']], 'COUNT(id) as total');
                $total_txn = $txn[0]['total'];
                if ($total_txn == 0) {
                    $status = $response['data']['body']['resultInfo']['resultStatus'];
                    $custom_data = $response['data']['body']['merchantUniqueReference'];
                    $custom_data = explode("|", $custom_data);
                    $address_id = $custom_data[0];
                    $wallet_used = $custom_data[1];
                    $promo_code = $custom_data[2];
                    $latitude = $custom_data[3];
                    $longitude = $custom_data[4];
                    $delivery_date = $custom_data[5];
                    $delivery_time = $custom_data[6];
                    $mobile = $custom_data[7];
                    if ($status == "TXN_SUCCESS" || $status == "PENDING") {
                        $cart = get_cart_total($this->data['user']->id, false, '0', $address_id);

                        $_POST['delivery_charge'] = get_delivery_charge($address_id, $cart['total_arr']);
                        $_POST['address_id'] = $address_id;
                        $_POST['latitude'] = $latitude;
                        $_POST['longitude'] = $longitude;
                        $_POST['delivery_date'] = $delivery_date;
                        $_POST['delivery_time'] = $delivery_time;
                        $_POST['delivery_charge'] = str_replace(',', '', $_POST['delivery_charge']);
                        $_POST['is_delivery_charge_returnable'] = intval($_POST['delivery_charge']) != 0 ? 1 : 0;
                        $quantity = implode(',', array_column($cart, 'qty'));
                        $_POST['product_variant_id'] = implode(',', array_column($cart, 'id'));
                        $_POST['quantity'] = $quantity;
                        $_POST['user_id'] = $this->data['user']->id;
                        $_POST['promo_code'] = $promo_code ? $promo_code : '';
                        $final_total = $cart['overall_amount'];
                        $wallet_balance = fetch_details('users', 'id=' . $_POST['user_id'], 'balance');
                        $_POST['mobile'] = $mobile;
                        $wallet_balance = $wallet_balance[0]['balance'];
                        $_POST['wallet_balance_used'] = 0;
                        $_POST['payment_method'] = 'Paytm';
                        if ($wallet_used == 1) {
                            $_POST['is_wallet_used'] = 1;
                            if ($wallet_balance >= $final_total) {
                                $_POST['wallet_balance_used'] = $final_total;
                                $_POST['payment_method'] = 'wallet';
                            } else {
                                $_POST['wallet_balance_used'] = $wallet_balance;
                            }
                        }

                        $promo_discount = 0;
                        if (isset($_POST['promo_code']) && !empty($_POST['promo_code'])) {
                            $validate = validate_promo_code($_POST['promo_code'], $this->data['user']->id, $cart['total_arr']);
                            if ($validate['error'] == false) {
                                $promo_discount = $validate['data'][0]['final_discount'];
                                $_POST['promo_discount'] = $validate['data'][0]['final_discount'];
                            }
                        }
                        $_POST['final_total'] = $cart['overall_amount'] - $_POST['wallet_balance_used'] - $promo_discount;

                        $_POST['active_status'] = $status == "TXN_SUCCESS" ? 'received' : 'awaiting';
                        $data['status'] = $status == "TXN_SUCCESS" ? 'Success' : 'Pending';
                        $data['txn_id'] = $response['data']['body']['orderId'];
                        $data['message'] = 'Order placed Successfully';
                        $res = $this->order_model->place_order($_POST);
                        $data['order_id'] = $res['order_id'];
                        $data['user_id'] = $this->data['user']->id;
                        $data['type'] = 'Paytm';
                        $data['amount'] = $response['data']['body']['txnAmount'];
                        if ($res['error'] == false) {
                            $this->transaction_model->add_transaction($data);
                        }
                        redirect(base_url('payment/success'), 'refresh');
                    } else {
                        redirect(base_url('payment/cancel'), 'refresh');
                    }
                } else {
                    redirect(base_url('payment/success'), 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', 'Order already exists with this transaction ID');
                $this->session->set_flashdata('message_type', 'error');
                redirect(base_url(), 'refresh');
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function process_myfatoorah()
    {
        /**
         * step 1 : validate transaction
         * step 2: if successful - place order
         * step 3: add transaction
         * step 4: if failed
         * step 5: just add transaction and don't place order
         * step 6: redirect based on the status to payment/success or payment/cancel
         */



        $payment_id = (isset($_GET['paymentId']) && !empty($_GET['paymentId'])) ? trim($_GET['paymentId']) : "";

        if (empty($payment_id)) {
            redirect(base_url("payment/cancel"));
            return;
        }
        $status = $this->my_fatoorah->getPaymentStatus($payment_id);
        $invoice_status = (isset($status->Data->InvoiceStatus) && !empty($status->Data->InvoiceStatus)) ? $status->Data->InvoiceStatus : "";

        if ($invoice_status == 'Paid') {
            update_details(['active_status' => 'received'], ['order_id' => $status->Data->UserDefinedField], 'order_items');
            $order_status = json_encode(array(array('received', date("d-m-Y h:i:sa"))));
            update_details(['status' => $order_status], ['order_id' => $status->Data->UserDefinedField], 'order_items', false);

            update_details(['payu_txn_id' => $payment_id], ['order_id' => $status->Data->UserDefinedField], 'transactions');
            redirect(base_url("payment/success"));
            return;
        } else {
            redirect(base_url("payment/cancel"));
        }
    }

    public function success()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }

        $this->data['main_page'] = 'payment-success';
        $this->data['title'] = 'Payment Success | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Payment Success, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Payment Success | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = '';
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function cancel()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        $this->data['main_page'] = 'payment-cancel';
        $this->data['title'] = 'Payment Cancel | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Payment Cancel, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Payment Cancel | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = '';
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }



    function app_payment_status()
    {


        $paypalInfo = $this->input->get();


        if (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "completed") {
            $response['error'] = false;
            $response['message'] = "Pagesa është kryer me sukses.";
            $response['data'] = $paypalInfo;
        } elseif (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "authorized") {
            $response['error'] = false;
            $response['message'] = "Your payment has been Authorized successfully. We will capture your transaction within 30 minutes, once we process your order. After successful capture Ads wil be credited automatically.";
            $response['data'] = $paypalInfo;
        } else {
            $response['error'] = true;
            $response['message'] = "Pagesa është refuzuar. ";
            $response['data'] = (isset($_GET)) ? $this->input->get() : "";
        }
        print_r(json_encode($response));
    }
    /* Capture all the authorized transactions
        We are using another library and API for this operation
        
    */
    function do_capture()
    {
        // Load PayPal library
        $this->config->load('paypal_lib');


        $config = array(
            'Sandbox' => $this->config->item('Sandbox'),             // Sandbox / testing mode option.
            'APIUsername' => $this->config->item('APIUsername'),     // PayPal API username of the API caller
            'APIPassword' => $this->config->item('APIPassword'),     // PayPal API password of the API caller
            'APISignature' => $this->config->item('APISignature'),     // PayPal API signature of the API caller
            'APISubject' => '',                                     // PayPal API subject (email address of 3rd party user that has granted API permission for your app)
            'APIVersion' => $this->config->item('APIVersion')        // API version you'd like to use for your call.  You can set a default version in the class and leave this blank if you want.
        );

        // Show Errors
        if ($config['Sandbox']) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }

        $this->load->library('Paypal_pro', $config);

        $where = " `payment_type` = 'paypal' and (status = 'Pending' or status = 'pending') ";
        $q = $this->db->get_where('transaction', $where);
        $paypal_txns = $q->result_array();

        foreach ($paypal_txns as $transaction) {
            $DCFields = array(
                'authorizationid' => $transaction['transaction_id'],                 // Required. The authorization identification number of the payment you want to capture. This is the transaction ID returned from DoExpressCheckoutPayment or DoDirectPayment.
                'amt' => $transaction['amount'] . '.00',                             // Required. Must have two decimal places.  Decimal separator must be a period (.) and optional thousands separator must be a comma (,)
                'completetype' => 'Complete',                     // Required.  The value Complete indiciates that this is the last capture you intend to make.  The value NotComplete indicates that you intend to make additional captures.
                'currencycode' => 'USD',                     // Three-character currency code
                'invnum' => 'NonVoIP#' . $transaction['id'],                         // Your invoice number
                'note' => 'Transaction captured by nonVoIP system',       // Informational note about this setlement that is displayed to the buyer in an email and in his transaction history.  255 character max.
                'softdescriptor' => 'Transaction captured by nonVoIP system',                 // Per transaction description of the payment that is passed to the customer's credit card statement.
                'storeid' => '',                         // ID of the merchant store.  This field is required for point-of-sale transactions.  Max: 50 char
                'terminalid' => ''                        // ID of the terminal.  50 char max.  
            );

            $PayPalRequestData = array('DCFields' => $DCFields);
            $PayPalResult = $this->paypal_pro->DoCapture($PayPalRequestData);

            if (!$this->paypal_pro->APICallSuccessful($PayPalResult['ACK'])) {
                /* some error occured please display the approriate message */
            } else {
                /* Successful call.  Load view or whatever you need to do here. */
            }
        }
    }
}
