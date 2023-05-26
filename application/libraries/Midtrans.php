<?php
class Midtrans
{
    function __construct()
    {
        $settings = get_settings('payment_method', true);
        $system_settings = get_settings('system_settings', true);

        $this->client_key = (isset($settings['midtrans_client_key'])) ? $settings['midtrans_client_key'] : "";
        $this->marchant_id = (isset($settings['midtrans_merchant_id'])) ? $settings['midtrans_merchant_id'] : "";
        $this->server_key = (isset($settings['midtrans_server_key'])) ? $settings['midtrans_server_key'] : "";
        $this->payment_mode = (isset($settings['midtrans_payment_mode'])) ? $settings['midtrans_payment_mode'] : "";
        $this->url = (isset($settings['midtrans_payment_mode']) && $settings['midtrans_payment_mode'] == "sandbox") ? 'https://app.sandbox.midtrans.com/' : 'https://app.midtrans.com/';
        $this->api_url = (isset($settings['midtrans_payment_mode']) && $settings['midtrans_payment_mode'] == "sandbox") ? 'https://api.sandbox.midtrans.com/' : 'https://api.midtrans.com/';
    }


    public function get_credentials()
    {
        $data['midtrans_client_key'] = $this->client_key;
        $data['midtrans_merchant_id'] = $this->marchant_id;
        $data['midtrans_server_key'] = $this->server_key;
        $data['url'] = $this->url;
        return $data;
    }
    public function create_transaction($order_id, $amount)
    {
        $data = array(
            'order_id' => $order_id,
            'gross_amount' => intval($amount),
        );
        $final_data['transaction_details'] = $data;
        $url = $this->url . 'snap/v1/transactions';

        $method = 'POST';
        $response = $this->curl($url, $method, $final_data);
        return $response;
    }


    public function get_transaction_status($order_id)
    {
        $url = $this->api_url . 'v2/' . $order_id . '/status';
        $response = $this->curl($url);
        $res = json_decode($response['body'], true);
        return $res;
    }


    public function curl($url, $method = 'GET', $data = [])
    {
        $ch = curl_init();
        $curl_options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 0,
            // Add header to the request, including Authorization generated from server key
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($this->server_key . ':')
            )
        );
        if (strtolower($method) == 'post') {
            $curl_options[CURLOPT_POST] = 1;
            $curl_options[CURLOPT_POSTFIELDS] = json_encode($data);
        } else {
            $curl_options[CURLOPT_CUSTOMREQUEST] = 'GET';
        }
        curl_setopt_array($ch, $curl_options);
        $result = array(
            'body' => curl_exec($ch),
            'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        );
        return $result;
    }
}
