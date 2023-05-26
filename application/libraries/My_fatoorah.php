<?php
class My_fatoorah
{

    function __construct()
    {
        $settings = get_settings('payment_method', true);
        $system_settings = get_settings('system_settings', true);

        $this->secret_key = (isset($settings['myfatoorah__secret_key'])) ? $settings['myfatoorah__secret_key'] : "";
        $this->language = (isset($settings['myfatoorah_language'])) ? $settings['myfatoorah_language'] : "";
        $this->country = (isset($settings['myfatoorah_country'])) ? ($settings['myfatoorah_country']) : "";
        $this->url = (isset($settings['myfatoorah_payment_mode'])) ? 'https://apitest.myfatoorah.com' : 'https://apitest.myfatoorah.com';
        $this->apiKey = (isset($settings['myfatoorah_token'])) ? ($settings['myfatoorah_token']) : "";
    }

    public function get_credentials()
    {
        $data['myfatoorah__secret_key'] = $this->secret_key;
        $data['myfatoorah_language'] = $this->language;
        $data['myfatoorah_country'] = $this->country;
        $data['url'] = $this->url;
        $data['myfatoorah_token'] = $this->apiKey;

        return $data;
    }


    public function ExecutePayment($amount, $payment_method = 2, $additional_data = [])
    {
        $postFields = array(
            'paymentMethodId' => $payment_method,
            'InvoiceValue' => intval($amount),
            'CallBackUrl'     => base_url("payment/process_myfatoorah"),
            'ErrorUrl'        => base_url("payment/process_myfatoorah"),

            // 'CallBackUrl'     => 'http://vendoreshop.wrteam.co.in/payment/process_myfatoorah',
            // 'ErrorUrl'        => 'http://vendoreshop.wrteam.co.in/payment/process_myfatoorah',
        );



        $postFields = (!empty($additional_data)) ? array_merge($postFields, $additional_data) : $postFields;

        $url = $this->url . '/v2/ExecutePayment';
        $apiKey = $this->apiKey;

        $response = $this->callAPI($url, $apiKey, $postFields);
        return $response;
    }

    public function getPaymentStatus($key, $keyType = "PaymentId")
    {
        $url = $this->url . '/v2/getPaymentStatus';
        $apiKey = $this->apiKey;

        $postFields = [
            "Key" => $key,
            "KeyType" => $keyType
        ];

        $response = $this->callAPI($url, $apiKey, $postFields);
        return $response;
    }

    public function callAPI($url, $apiKey, $postFields = [], $requestType = 'POST')
    {

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST  => $requestType,
            CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);

        curl_close($curl);

        if ($curlErr) {
            //Curl is not working in your server
            die("Curl Error: $curlErr");
        }

        $error = $this->handleError($response);
        if ($error) {
            die("Error: $error");
        }

        return json_decode($response);
    }


    public function handleError($response)
    {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }

        //Check for the errors
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }

        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }

        return $error;
    }
}
