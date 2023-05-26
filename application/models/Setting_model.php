<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper', 'timezone_helper']);
    }


    public function update_system_setting($post)
    {
        $post = escape_array($post);

        $system_data = [

            'system_configurations' => $post['system_configurations'],
            'system_timezone_gmt' => $post['system_timezone_gmt'],
            'system_configurations_id' => $post['system_configurations_id'],
            'app_name' => $post['app_name'],
            'support_number' => $post['support_number'],
            'support_email' => $post['support_email'],
            'current_version' => $post['current_version'],
            'current_version_ios' => $post['current_version_ios'],
            'is_version_system_on' => (isset($post['is_version_system_on'])) ? '1' : '0',
            'area_wise_delivery_charge' => (isset($post['area_wise_delivery_charge'])) ? '1' : '0',
            'currency' => $post['currency'],
            'delivery_charge' => $post['delivery_charge'],
            'min_amount' => $post['min_amount'],
            'system_timezone' => $post['system_timezone'],
            'is_refer_earn_on' => (isset($post['is_refer_earn_on'])) ? '1' : '0',
            'min_refer_earn_order_amount' => $post['min_refer_earn_order_amount'],
            'refer_earn_bonus' => $post['refer_earn_bonus'],
            'refer_earn_method' => $post['refer_earn_method'],
            'max_refer_earn_amount' => $post['max_refer_earn_amount'],
            'refer_earn_bonus_times' => $post['refer_earn_bonus_times'],
            'welcome_wallet_balance_on' => (isset($post['welcome_wallet_balance_on'])) ? '1' : '0',
            'wallet_balance_amount' => $post['wallet_balance_amount'],
            'minimum_cart_amt' => $post['minimum_cart_amt'],
            'low_stock_limit' => (isset($post['low_stock_limit'])) ? $post['low_stock_limit'] : '5',
            'max_items_cart' => $post['max_items_cart'],
            'delivery_boy_bonus_percentage' => $post['delivery_boy_bonus_percentage'],
            'max_product_return_days' => $post['max_product_return_days'],
            'is_delivery_boy_otp_setting_on' => (isset($post['is_delivery_boy_otp_setting_on'])) ? '1' : '0',
            'is_single_seller_order' => (isset($post['is_single_seller_order'])) ? '1' : '0',
            'is_customer_app_under_maintenance' => (isset($post['is_customer_app_under_maintenance'])) ? '1' : '0',
            'inspect_element' => (isset($post['inspect_element'])) ? '1' : '0',
            'is_seller_app_under_maintenance' => (isset($post['is_seller_app_under_maintenance'])) ? '1' : '0',
            'is_delivery_boy_app_under_maintenance' => (isset($post['is_delivery_boy_app_under_maintenance'])) ? '1' : '0',
            'message_for_customer_app' => $post['message_for_customer_app'],
            'message_for_seller_app' => $post['message_for_seller_app'],
            'message_for_delivery_boy_app' => $post['message_for_delivery_boy_app'],
            'cart_btn_on_list' => (isset($post['cart_btn_on_list'])) ? '1' : '0',
            'expand_product_images' => (isset($post['expand_product_images'])) ? '1' : '0',
            'tax_name' => $post['tax_name'],
            'tax_number' => $post['tax_number'],
            'company_name' => (isset($post['company_name'])) ?  $post['company_name'] : '',
            'company_url' => (isset($post['company_url'])) ?  $post['company_url'] : '',
            'supported_locals' => (isset($post['supported_locals'])) ?  $post['supported_locals'] : '',
            'decimal_point' => (isset($post['decimal_point'])) ?  $post['decimal_point'] : '',
        ];

        $main_image_name = $post['logo'];
        $favicon_image_name = $post['favicon'];

        $system_data = json_encode($system_data);
        $query = $this->db->get_where('settings', array(
            'variable' => 'system_settings'
        ));
        $count = $query->num_rows();
        if ($main_image_name != NULL && !empty($main_image_name)) {
            $logo_res = $this->db->get_where('settings', array(
                'variable' => 'logo'
            ));
            $logo_count = $logo_res->num_rows();
            if ($logo_count == 0) {
                $this->db->insert('settings', ['value' => $main_image_name, 'variable' => 'logo']);
            } else {
                $this->db->set('value', $main_image_name)->where('variable', 'logo')->update('settings');
            }
        }
        if ($favicon_image_name != NULL && !empty($favicon_image_name)) {
            $favicon_res = $this->db->get_where('settings', array(
                'variable' => 'favicon'
            ));
            $favicon_count = $favicon_res->num_rows();
            if ($favicon_count == 0) {
                $this->db->insert('settings', ['value' => $favicon_image_name, 'variable' => 'favicon']);
            } else {
                $this->db->set('value', $favicon_image_name)->where('variable', 'favicon')->update('settings');
            }
        }
        if ($count === 0) {
            $data = array(
                'variable' => 'system_settings',
                'value' => $system_data
            );
            $this->db->insert('settings', $data);
            $this->db->insert('settings', ['value' => $post['currency']]);
        } else {
            $this->db->set('value', $system_data)->where('variable', 'system_settings')->update('settings');
            $this->db->set('value', $post['currency'])->where('variable', 'currency')->update('settings');
        }
    }

    public function update_web_setting($post)
    {
        $post = escape_array($post);
        $post['app_download_section'] = (isset($post['app_download_section']) && !empty($post['app_download_section'])) ?: 0;
        $post['shipping_mode'] = (isset($post['shipping_mode']) && !empty($post['shipping_mode'])) ?: 0;
        $post['return_mode'] = (isset($post['return_mode']) && !empty($post['return_mode'])) ?: 0;
        $post['support_mode'] = (isset($post['support_mode']) && !empty($post['support_mode'])) ?: 0;
        $post['safety_security_mode'] = (isset($post['safety_security_mode']) && !empty($post['safety_security_mode'])) ?: 0;
        $main_image_name = (isset($post['logo']) && !empty($post['logo'])) ? $post['logo'] : "";
        $favicon_image_name = (isset($post['favicon']) && !empty($post['favicon'])) ? $post['favicon'] : "";
        $system_data = json_encode($post);
        $query = $this->db->get_where('settings', array(
            'variable' => 'web_settings'
        ));
        $count = $query->num_rows();
        if ($main_image_name != NULL && !empty($main_image_name)) {
            $logo_res = $this->db->get_where('settings', array(
                'variable' => 'web_logo'
            ));
            $logo_count = $logo_res->num_rows();
            if ($logo_count == 0) {
                $this->db->insert('settings', ['value' => $main_image_name, 'variable' => 'web_logo']);
            } else {
                $this->db->set('value', $main_image_name)->where('variable', 'web_logo')->update('settings');
            }
        }
        if ($favicon_image_name != NULL && !empty($favicon_image_name)) {
            $favicon_res = $this->db->get_where('settings', array(
                'variable' => 'web_favicon'
            ));
            $favicon_count = $favicon_res->num_rows();
            if ($favicon_count == 0) {
                $this->db->insert('settings', ['value' => $favicon_image_name, 'variable' => 'web_favicon']);
            } else {
                $this->db->set('value', $favicon_image_name)->where('variable', 'web_favicon')->update('settings');
            }
        }
        if ($count === 0) {
            $data = array(
                'variable' => 'web_settings',
                'value' => $system_data
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $system_data)->where('variable', 'web_settings')->update('settings');
        }
    }
    public function update_payment_method($post)
    {

        $post = escape_array($post);

        $payment_data = array();
        $payment_data['paypal_payment_method'] = isset($post['paypal_payment_method']) ? '1' : '0';
        $payment_data['paypal_mode'] = isset($post['paypal_mode']) && !empty($post['paypal_mode']) ? $post['paypal_mode'] : '';
        $payment_data['paypal_business_email'] = isset($post['paypal_business_email']) && !empty($post['paypal_business_email']) ? $post['paypal_business_email'] : '';
        $payment_data['currency_code'] = isset($post['currency_code']) && !empty($post['currency_code']) ? $post['currency_code'] : '';

        $payment_data['razorpay_payment_method'] = isset($post['razorpay_payment_method']) ? '1' : '0';
        $payment_data['razorpay_key_id'] = isset($post['razorpay_key_id']) && !empty($post['razorpay_key_id']) ? $post['razorpay_key_id'] : '';
        $payment_data['razorpay_secret_key'] = isset($post['razorpay_secret_key']) && !empty($post['razorpay_secret_key']) ? $post['razorpay_secret_key'] : '';
        $payment_data['refund_webhook_secret_key'] = isset($post['refund_webhook_secret_key']) && !empty($post['refund_webhook_secret_key']) ? $post['refund_webhook_secret_key'] : '';


        $payment_data['paystack_payment_method'] = isset($post['paystack_payment_method']) ? '1' : '0';
        $payment_data['paystack_key_id'] = isset($post['paystack_key_id']) && !empty($post['paystack_key_id']) ? $post['paystack_key_id'] : '';
        $payment_data['paystack_secret_key'] = isset($post['paystack_secret_key']) && !empty($post['paystack_secret_key']) ? $post['paystack_secret_key'] : '';


        $payment_data['stripe_payment_method'] = isset($post['stripe_payment_method']) ? '1' : '0';
        $payment_data['stripe_payment_mode'] = isset($post['stripe_payment_mode']) ? $post['stripe_payment_mode'] : 'test';
        $payment_data['stripe_publishable_key'] = isset($post['stripe_publishable_key']) && !empty($post['stripe_publishable_key']) ? $post['stripe_publishable_key'] : '';
        $payment_data['stripe_secret_key'] = isset($post['stripe_secret_key']) && !empty($post['stripe_secret_key']) ? $post['stripe_secret_key'] : '';
        $payment_data['stripe_webhook_secret_key'] = isset($post['stripe_webhook_secret_key']) && !empty($post['stripe_webhook_secret_key']) ? $post['stripe_webhook_secret_key'] : '';
        $payment_data['stripe_currency_code'] = isset($post['stripe_currency_code']) && !empty($post['stripe_currency_code']) ? $post['stripe_currency_code'] : '';

        $payment_data['flutterwave_payment_method'] = isset($post['flutterwave_payment_method']) ? '1' : '0';
        $payment_data['flutterwave_public_key'] = isset($post['flutterwave_public_key']) && !empty($post['flutterwave_public_key']) ? $post['flutterwave_public_key'] : '';
        $payment_data['flutterwave_secret_key'] = isset($post['flutterwave_secret_key']) && !empty($post['flutterwave_secret_key']) ? $post['flutterwave_secret_key'] : '';
        $payment_data['flutterwave_encryption_key'] = isset($post['flutterwave_encryption_key']) && !empty($post['flutterwave_encryption_key']) ? $post['flutterwave_encryption_key'] : '';
        $payment_data['flutterwave_webhook_secret_key'] = isset($post['flutterwave_webhook_secret_key']) && !empty($post['flutterwave_webhook_secret_key']) ? $post['flutterwave_webhook_secret_key'] : '';
        $payment_data['flutterwave_currency_code'] = isset($post['flutterwave_currency_code']) && !empty($post['flutterwave_currency_code']) ? $post['flutterwave_currency_code'] : '';

        $payment_data['paytm_payment_method'] = isset($post['paytm_payment_method']) ? '1' : '0';
        $payment_data['paytm_payment_mode'] = isset($post['paytm_payment_mode']) && !empty($post['paytm_payment_mode']) ? $post['paytm_payment_mode'] : '';
        $payment_data['paytm_merchant_key'] = isset($post['paytm_merchant_key']) && !empty($post['paytm_merchant_key']) ? $post['paytm_merchant_key'] : '';
        $payment_data['paytm_merchant_id'] = isset($post['paytm_merchant_id']) && !empty($post['paytm_merchant_id']) ? $post['paytm_merchant_id'] : '';
        $payment_data['paytm_website'] = isset($post['paytm_payment_mode']) && $post['paytm_payment_mode'] == 'production' ? $post['paytm_website'] : 'WEBSTAGING';
        $payment_data['paytm_industry_type_id'] = isset($post['paytm_payment_mode']) && $post['paytm_payment_mode'] == 'production' ? $post['paytm_industry_type_id'] : 'Retail';

        $payment_data['midtrans_payment_mode'] = isset($post['midtrans_payment_mode']) && !empty($post['midtrans_payment_mode']) ? $post['midtrans_payment_mode'] : '';
        $payment_data['midtrans_payment_method'] = isset($post['midtrans_payment_method']) ? '1' : '0';
        $payment_data['midtrans_client_key'] = isset($post['midtrans_client_key']) && !empty($post['midtrans_client_key']) ? $post['midtrans_client_key'] : '';
        $payment_data['midtrans_merchant_id'] = isset($post['midtrans_merchant_id']) && !empty($post['midtrans_merchant_id']) ? $post['midtrans_merchant_id'] : '';
        $payment_data['midtrans_server_key'] = isset($post['midtrans_server_key']) && !empty($post['midtrans_server_key']) ? $post['midtrans_server_key'] : '';

        $payment_data['direct_bank_transfer'] = isset($post['direct_bank_transfer']) ? '1' : '0';
        $payment_data['account_name'] = isset($post['account_name']) && !empty($post['account_name']) ? $post['account_name'] : '';
        $payment_data['account_number'] = isset($post['account_number']) && !empty($post['account_number']) ? $post['account_number'] : '';
        $payment_data['bank_name'] = isset($post['bank_name']) && !empty($post['bank_name']) ? $post['bank_name'] : '';
        $payment_data['bank_code'] = isset($post['bank_code']) && !empty($post['bank_code']) ? $post['bank_code'] : '';
        $payment_data['notes'] = isset($post['notes']) && !empty($post['notes']) ? $post['notes'] : '';

        $payment_data['myfaoorah_payment_method'] = isset($post['myfaoorah_payment_method']) && !empty($post['myfaoorah_payment_method']) ? '1' : '0';
        $payment_data['myfatoorah_token'] = isset($post['myfatoorah_token']) && !empty($post['myfatoorah_token']) ? $post['myfatoorah_token'] : '0';
        $payment_data['myfatoorah_payment_mode'] = isset($post['myfatoorah_payment_mode']) && !empty($post['myfatoorah_payment_mode']) ? $post['myfatoorah_payment_mode'] : '';
        $payment_data['myfatoorah__successUrl'] = isset($post['myfatoorah__successUrl']) && !empty($post['myfatoorah__successUrl']) ? $post['myfatoorah__successUrl'] : '';
        $payment_data['myfatoorah__errorUrl'] = isset($post['myfatoorah__errorUrl']) && !empty($post['myfatoorah__errorUrl']) ? $post['myfatoorah__errorUrl'] : '';
        $payment_data['myfatoorah_language'] = isset($post['myfatoorah_language']) && !empty($post['myfatoorah_language']) ? $post['myfatoorah_language'] : '';
        $payment_data['myfatoorah_country'] = isset($post['myfatoorah_country']) && !empty($post['myfatoorah_country']) ? $post['myfatoorah_country'] : '';
        $payment_data['myfatoorah__secret_key'] = isset($post['myfatoorah__secret_key']) && !empty($post['myfatoorah__secret_key']) ? $post['myfatoorah__secret_key'] : '';

        $payment_data['cod_method'] = isset($post['cod_method']) ? '1' : '0';

        $payment_data = json_encode($payment_data);

        $query = $this->db->get_where('settings', array(
            'variable' => 'payment_method'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'payment_method',
                'value' => $payment_data
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $payment_data)->where('variable', 'payment_method')->update('settings');
        }
    }

    public function update_time_slot($post)
    {
        $post = escape_array($post);

        $time_slot_data = [
            'title' => $post['title'],
            'from_time' => $post['from_time'],
            'to_time' => $post['to_time'],
            'last_order_time' => $post['last_order_time'],
            'status' => $post['status'],
        ];
        if (isset($post['edit_time_slot']) && !empty($post['edit_time_slot'])) {
            $this->db->set($time_slot_data)->where('id', $post['edit_time_slot'])->update('time_slots');
        } else {
            $this->db->insert('time_slots', $time_slot_data);
        }
    }


    public function update_time_slot_config($data)
    {
        $data = escape_array($data);

        $config_data = [
            'time_slot_config' => $data['time_slot_config'],
            'is_time_slots_enabled' => isset($data['is_time_slots_enabled']) ? '1' : '0',
            'delivery_starts_from' => $data['delivery_starts_from'],
            'allowed_days' => $data['allowed_days'],
        ];
        $config_data = json_encode($config_data);

        $query = $this->db->get_where('settings', array(
            'variable' => 'time_slot_config'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'time_slot_config',
                'value' => $config_data
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $config_data)->where('variable', 'time_slot_config')->update('settings');
        }
    }
    public function update_fcm_details($post)
    {
        $post = escape_array($post);

        $query = $this->db->get_where('settings', array(
            'variable' => 'fcm_server_key'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'fcm_server_key',
                'value' => $post['fcm_server_key']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $post['fcm_server_key'])->where('variable', 'fcm_server_key')->update('settings');
        }
    }

    public function update_contact_details($post)
    {
        $post = escape_array($post);

        $query = $this->db->get_where('settings', array(
            'variable' => 'contact_us'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'contact_us',
                'value' => $post['contact_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $post['contact_input_description'])->where('variable', 'contact_us')->update('settings');
        }
    }

    public function update_privacy_policy($post)
    {
        $post = escape_array($post);

        $query = $this->db->get_where('settings', array(
            'variable' => 'privacy_policy'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'privacy_policy',
                'value' => $post['privacy_policy_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $post['privacy_policy_input_description'])->where('variable', 'privacy_policy')->update('settings');
        }
    }


    public function update_shipping_policy($post)
    {
        $post = escape_array($post);

        $query = $this->db->get_where('settings', array(
            'variable' => 'shipping_policy'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'shipping_policy',
                'value' => $post['shipping_policy_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $post['shipping_policy_input_description'])->where('variable', 'shipping_policy')->update('settings');
        }
    }



    public function update_return_policy($post)
    {
        $post = escape_array($post);

        $query = $this->db->get_where('settings', array(
            'variable' => 'return_policy'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'return_policy',
                'value' => $post['return_policy_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $post['return_policy_input_description'])->where('variable', 'return_policy')->update('settings');
        }
    }


    public function update_terms_n_condtions($post)
    {
        $post = escape_array($post);

        $query = $this->db->get_where('settings', array(
            'variable' => 'terms_conditions'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'terms_conditions',
                'value' => $post['terms_n_conditions_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $post['terms_n_conditions_input_description'])->where('variable', 'terms_conditions')->update('settings');
        }
    }

    public function update_about_us($post)
    {
        $query = $this->db->get_where('settings', array(
            'variable' => 'about_us'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'about_us',
                'value' => $post['about_us_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $post['about_us_input_description'])->where('variable', 'about_us')->update('settings');
        }
    }

    public function update_email_settings($data)
    {
        $data = escape_array($data);
        $email_data = json_encode($data);
        $query = $this->db->get_where('settings', array(
            'variable' => 'email_settings'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'email_settings',
                'value' => $email_data
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $email_data)->where('variable', 'email_settings')->update('settings');
        }
    }



    public function update_delivery_boy_privacy_policy($data)
    {
        $data = escape_array($data);
        $query = $this->db->get_where('settings', array(
            'variable' => 'delivery_boy_privacy_policy'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'delivery_boy_privacy_policy',
                'value' => $data['privacy_policy_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $data['privacy_policy_input_description'])->where('variable', 'delivery_boy_privacy_policy')->update('settings');
        }
    }
    public function update_seller_privacy_policy($data)
    {
        $data = escape_array($data);
        $query = $this->db->get_where('settings', array(
            'variable' => 'seller_privacy_policy'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'seller_privacy_policy',
                'value' => $data['privacy_policy_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $data['privacy_policy_input_description'])->where('variable', 'seller_privacy_policy')->update('settings');
        }
    }

    public function update_delivery_boy_terms_n_condtions($data)
    {
        $data = escape_array($data);
        $query = $this->db->get_where('settings', array(
            'variable' => 'delivery_boy_terms_conditions'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'delivery_boy_terms_conditions',
                'value' => $data['terms_n_conditions_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $data['terms_n_conditions_input_description'])->where('variable', 'delivery_boy_terms_conditions')->update('settings');
        }
    }
    public function update_seller_terms_n_condtions($data)
    {
        $data = escape_array($data);
        $query = $this->db->get_where('settings', array(
            'variable' => 'seller_terms_conditions'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'seller_terms_conditions',
                'value' => $data['terms_n_conditions_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $data['terms_n_conditions_input_description'])->where('variable', 'seller_terms_conditions')->update('settings');
        }
    }
    public function update_admin_privacy_policy($data)
    {
        $data = escape_array($data);
        $query = $this->db->get_where('settings', array(
            'variable' => 'admin_privacy_policy'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'admin_privacy_policy',
                'value' => $data['privacy_policy_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $data['privacy_policy_input_description'])->where('variable', 'admin_privacy_policy')->update('settings');
        }
    }

    public function update_admin_terms_n_condtions($data)
    {
        $data = escape_array($data);
        $query = $this->db->get_where('settings', array(
            'variable' => 'admin_terms_conditions'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'admin_terms_conditions',
                'value' => $data['terms_n_conditions_input_description']
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $data['terms_n_conditions_input_description'])->where('variable', 'admin_terms_conditions')->update('settings');
        }
    }

    public function get_time_slot_details()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['`id`' => $search, '`title`' => $search, '`from_time`' => $search, '`to_time`' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_where($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $count = $count_res->get('time_slots')->result_array();

        foreach ($count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('time_slots')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($search_res as $row) {

            $operate = ' <a href="javascript:void(0)" class="edit_btn btn btn-primary action-btn btn-xs mr-1 mb-1 ml-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/time-slots/"><i class="fa fa-pen"></i></a>';
            $operate .= '<a class="btn btn-danger action-btn btn-xs mr-1 mb-1 ml-1" title="Delete" id="delete-time-slot" href="javascript:void(0)" data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['from_time'] = $row['from_time'];
            $tempRow['to_time'] = $row['to_time'];
            $tempRow['last_order_time'] = $row['last_order_time'];
            $tempRow['status'] = ($row['status'] == 1) ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Deactive</span>";
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_theme_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['id' => $search, 'name' => $search, 'slug' => $search, 'is_default' => $search, 'status' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total`');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $address_count = $count_res->get('themes')->result_array();

        foreach ($address_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select('*');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $theme = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('themes')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($theme as $row) {
            $row = output_escaping($row);
            $operate = '';
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['name'];
            $tempRow['image'] = "<div class='image-box-100'><a href='" . base_url('assets/front_end/' . $row['slug'] . '/preview-image/' . $row['image']) . "' data-toggle='lightbox' data-gallery='gallery'><img src='" . base_url('assets/front_end/' . $row['slug'] . '/preview-image/' . $row['image']) . "' class='rounded'></a></div>";
            if ($row['is_default'] == '1') {
                $tempRow['is_default'] = '<a class="badge badge-success text-white" >Yes</a>';
            } else {
                $tempRow['is_default'] = '<a class="badge badge-danger text-white" >No</a>';
                $operate .= '<a class="btn btn-success action-btn btn-xs update_default_theme mr-1 mb-1 ml-1" title="Default" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-check-circle"></i></a>';
            }
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                $operate .= '<a class="btn btn-warning btn-xs action-btn update_active_status mb-1 ml-1 mr-1" data-table="themes" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye-slash"></i></a>';
            } else {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                $operate .= '<a class="btn btn-primary mr-1 ml-1 mb-1 btn-xs action-btn update_active_status" data-table="themes" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye"></i></a>';
            }
            $tempRow['created_on'] = $row['created_on'];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function firebase_setting($post)
    {
        $post = escape_array($post);

        $system_data = json_encode($post);
        $query = $this->db->get_where('settings', array(
            'variable' => 'firebase_settings'
        ));
        $count = $query->num_rows();
        if ($count === 0) {
            $data = array(
                'variable' => 'firebase_settings',
                'value' => $system_data
            );
            $this->db->insert('settings', $data);
        } else {
            $this->db->set('value', $system_data)->where('variable', 'firebase_settings')->update('settings');
        }
    }
}
