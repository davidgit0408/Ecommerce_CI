<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Api extends CI_Controller
{

    /*
---------------------------------------------------------------------------
Defined Methods:-
---------------------------------------------------------------------------
1. login
2  get_orders
3. get_order_items
4. update_order_item_status
5. get_categories
6. get_products
7. get_transactions
8. get_statistics
9. forgot_password
10. delete_order
11. verify_user
12. get_settings
13. update_fcm
14. get_cities
15. get_areas_by_city_id
16. get_zipcodes
17. get_taxes
18. send_withdrawal_request
19. get_withdrawal_request
20. get_attribute_set
21. get_attributes
22. get_attribute_values
23. add_products
24. get_media
26. get_seller_details
27. update_user
28. delete_product
29. update_products
30. get_delivery_boys
31. register
32. upload_media
33. get_product_rating
34. get_order_tracking
35. edit_order_tracking
36. get_sales_list
37. update_product_status
38. get_countries_data
39. get_brands_data
---------------------------------------------------------------------------
*/


    public function __construct()
    {
        parent::__construct();
        header("Content-Type: application/json");
        header("Expires: 0");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->load->library(['upload', 'jwt', 'ion_auth', 'form_validation']);
        $this->load->model(['order_model', 'category_model', 'transaction_model', 'Home_model', 'customer_model', 'ticket_model', 'delivery_boy_model', 'Area_model', 'Attribute_model', 'Product_model', 'media_model', 'Seller_model', 'rating_model', 'Invoice_model', 'product_model', 'product_faqs_model']);
        $this->load->helper([]);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        // date_default_timezone_set('America/New_York');
        $response = $temp = $bulkdata = array();
        $this->identity_column = $this->config->item('identity', 'ion_auth');
        // initialize db tables data
        $this->tables = $this->config->item('tables', 'ion_auth');
    }


    public function index()
    {
        $this->load->helper('file');
        $this->output->set_content_type(get_mime_by_extension(base_url('admin-api-doc.txt')));
        $this->output->set_output(file_get_contents(base_url('admin-api-doc.txt')));
    }

    public function generate_token()
    {
        $payload = [
            'iat' => time(), /* issued at time */
            'iss' => 'eshop',
            'exp' => time() + (30 * 60), /* expires after 1 minute */
            'sub' => 'eshop Authentication'
        ];
        $token = $this->jwt->encode($payload, JWT_SECRET_KEY);
        print_r(json_encode($token));
    }

    public function verify_token()
    {
        try {
            $token = $this->jwt->getBearerToken();
        } catch (Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            print_r(json_encode($response));
            return false;
        }

        if (!empty($token)) {
            $api_keys = fetch_details('client_api_keys', ['status' => 1]);
            if (empty($api_keys)) {
                $response['error'] = true;
                $response['message'] = 'No Client(s) Data Found !';
                print_r(json_encode($response));
                return false;
            }
            JWT::$leeway = 2000;
            $flag = true; //For payload indication that it return some data or throws an expection.
            $error = true; //It will indicate that the payload had verified the signature and hash is valid or not.
            foreach ($api_keys as $row) {
                $message = '';
                try {
                    $payload = $this->jwt->decode($token, $row['secret'], ['HS256']);
                    if (isset($payload->iss) && $payload->iss == 'eshop') {
                        $error = false;
                        $flag = false;
                    } else {
                        $error = true;
                        $flag = false;
                        $message = 'Invalid Hash';
                        break;
                    }
                } catch (Exception $e) {
                    $message = $e->getMessage();
                }
            }

            if ($flag) {
                $response['error'] = true;
                $response['message'] = $message;
                print_r(json_encode($response));
                return false;
            } else {
                if ($error == true) {
                    $response['error'] = true;
                    $response['message'] = $message;
                    print_r(json_encode($response));
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Unauthorized access not allowed";
            print_r(json_encode($response));
            return false;
        }
    }

    public function login()
    {
        /* Parameters to be passed
            mobile: 9874565478
            password: 12345678
            fcm_id: FCM_ID //{ optional }
        */
        if (!$this->verify_token()) {
            return false;
        }
        $identity_column = $this->config->item('identity', 'ion_auth');
        if ($identity_column == 'mobile') {
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|required|xss_clean');
        } elseif ($identity_column == 'email') {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
        } else {
            $this->form_validation->set_rules('identity', 'Identity', 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('fcm_id', 'FCM ID', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }

        $login = $this->ion_auth->login($this->input->post('mobile'), $this->input->post('password'), false);
        if ($login) {
            $data = fetch_details('users', ['mobile' => $this->input->post('mobile', true)]);
            foreach ($data as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = (isset($row['id']) && !empty($row['id'])) ? $row['id'] : '';
                $tempRow['ip_address'] = (isset($row['ip_address']) && !empty($row['ip_address'])) ? $row['ip_address'] : '';
                $tempRow['username'] = (isset($row['username']) && !empty($row['username'])) ? $row['username'] : '';
                $tempRow['email'] = (isset($row['email']) && !empty($row['email'])) ? $row['email'] : '';
                $tempRow['mobile'] = (isset($row['mobile']) && !empty($row['mobile'])) ? $row['mobile'] : '';
                if (empty($row['image']) || file_exists(FCPATH . USER_IMG_PATH . $row['image']) == FALSE) {
                    $tempRow['image'] = base_url() . NO_IMAGE;
                } else {
                    $tempRow['image'] = base_url() . USER_IMG_PATH .  $row['image'];
                }
                $tempRow['balance'] = (isset($row['balance']) && !empty($row['balance'])) ? $row['balance'] : "0";
                $tempRow['activation_selector'] = (isset($row['activation_selector']) && !empty($row['activation_selector'])) ? $row['activation_selector'] : '';
                $tempRow['activation_code'] = (isset($row['activation_code']) && !empty($row['activation_code'])) ? $row['activation_code'] : '';
                $tempRow['forgotten_password_selector'] = (isset($row['forgotten_password_selector']) && !empty($row['forgotten_password_selector'])) ? $row['forgotten_password_selector'] : '';
                $tempRow['forgotten_password_code'] = (isset($row['forgotten_password_code']) && !empty($row['forgotten_password_code'])) ? $row['forgotten_password_code'] : '';
                $tempRow['forgotten_password_time'] = (isset($row['forgotten_password_time']) && !empty($row['forgotten_password_time'])) ? $row['forgotten_password_time'] : '';
                $tempRow['remember_selector'] = (isset($row['remember_selector']) && !empty($row['remember_selector'])) ? $row['remember_selector'] : '';
                $tempRow['remember_code'] = (isset($row['remember_code']) && !empty($row['remember_code'])) ? $row['remember_code'] : '';
                $tempRow['created_on'] = (isset($row['created_on']) && !empty($row['created_on'])) ? $row['created_on'] : '';
                $tempRow['last_login'] = (isset($row['last_login']) && !empty($row['last_login'])) ? $row['last_login'] : '';
                $tempRow['active'] = (isset($row['active']) && !empty($row['active'])) ? $row['active'] : '';
                $tempRow['company'] = (isset($row['company']) && !empty($row['company'])) ? $row['company'] : '';
                $tempRow['address'] = (isset($row['address']) && !empty($row['address'])) ? $row['address'] : '';
                $tempRow['bonus'] = (isset($row['bonus']) && !empty($row['bonus'])) ? $row['bonus'] : '';
                $tempRow['cash_received'] = (isset($row['cash_received']) && !empty($row['cash_received'])) ? $row['cash_received'] : "0.00";
                $tempRow['dob'] = (isset($row['dob']) && !empty($row['dob'])) ? $row['dob'] : '';
                $tempRow['country_code'] = (isset($row['country_code']) && !empty($row['country_code'])) ? $row['country_code'] : '';
                $tempRow['city'] = (isset($row['city']) && !empty($row['city'])) ? $row['city'] : '';
                $tempRow['area'] = (isset($row['area']) && !empty($row['area'])) ? $row['area'] : '';
                $tempRow['street'] = (isset($row['street']) && !empty($row['street'])) ? $row['street'] : '';
                $tempRow['pincode'] = (isset($row['pincode']) && !empty($row['pincode'])) ? $row['pincode'] : '';
                $tempRow['apikey'] = (isset($row['apikey']) && !empty($row['apikey'])) ? $row['apikey'] : '';
                $tempRow['referral_code'] = (isset($row['referral_code']) && !empty($row['referral_code'])) ? $row['referral_code'] : '';
                $tempRow['friends_code'] = (isset($row['friends_code']) && !empty($row['friends_code'])) ? $row['friends_code '] : '';
                $tempRow['fcm_id'] = (isset($row['fcm_id']) && !empty($row['fcm_id'])) ? $row['fcm_id'] : '';
                $tempRow['latitude'] = (isset($row['latitude']) && !empty($row['latitude'])) ? $row['latitude'] : '';
                $tempRow['longitude'] = (isset($row['longitude']) && !empty($row['longitude'])) ? $row['longitude'] : '';
                $tempRow['created_at'] = (isset($row['created_at']) && !empty($row['created_at'])) ? $row['created_at'] : '';
                $rows[] = $tempRow;
            }
            $seller_data = fetch_details('seller_data', ['user_id' => $data[0]['id']]);

            $data = array_values(array_merge($rows, $seller_data));
            for ($i = 0; $i < count($seller_data); $i++) {
                $seller_data[$i]['logo'] = base_url() . $seller_data[$i]['logo'];
                $seller_data[$i]['national_identity_card'] = base_url() . $seller_data[$i]['national_identity_card'];
                $seller_data[$i]['address_proof'] = base_url() . $seller_data[$i]['address_proof'];
                $seller_data[$i]['permissions'] = json_decode($seller_data[$i]['permissions'], true);
            }
            $out = array();
            foreach ($data as $key => $value) {
                $out[] = (array)array_merge((array)$seller_data[$key], (array)$value);
            }
            if ($this->ion_auth->in_group('seller', $data[0]['id'])) {
                if (isset($_POST['fcm_id']) && $_POST['fcm_id'] != '') {
                    update_details(['fcm_id' => $_POST['fcm_id']], ['mobile' => $_POST['mobile']], 'users');
                }
                unset($data[0]['password']);

                $messages = array("0" => "Your acount is deactivated", "1" => "Logged in successfully", "2" => "Your account is not yet approved.", "7" => "Your account has been removed by the admin. Contact admin for more information.");
                //if the login is successful
                $response['error'] = (isset($seller_data[0]['status']) && $seller_data[0]['status'] != "" && ($seller_data[0]['status'] == 1 || $seller_data[0]['status'] == 0)) ? false : true;
                $response['message'] =  $messages[$seller_data[0]['status']];
                $response['data'] = (isset($seller_data[0]['status']) && $seller_data[0]['status'] != "" && ($seller_data[0]['status'] == 1 || $seller_data[0]['status'] == 0)) ?  $out     : [];
                echo json_encode($response);
                return false;
            } else {
                $response['error'] = true;
                $response['message'] = 'Incorrect Login.';
                echo json_encode($response);
                return false;
            }
        } else {
            // if the login was un-successful
            // just print json message
            $response['error'] = true;
            $response['message'] = strip_tags($this->ion_auth->errors());
            echo json_encode($response);
            return false;
        }
    }
    /* 2.get_orders

        seller_id:174 
        id:101 { optional }
        city_id:1 { optional }
        area_id:1 { optional }
        user_id:101 { optional }
        start_date : 2020-09-07 or 2020/09/07 { optional }
        end_date : 2021-03-15 or 2021/03/15 { optional }
        search:keyword      // optional
        limit:25            // { default - 25 } optional
        offset:0            // { default - 0 } optional
        sort: id / date_added // { default - id } optional
        order:DESC/ASC      // { default - DESC } optional
        order_type : digital/simple // if type is simple simple and variable product orders are showen AND if type is digital only digital product orders are showen 
        active_status: received  {received,delivered,cancelled,processed,returned}     // optional
    */

    public function get_orders()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
        $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
        $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'o.id';
        $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
        $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : '';
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('active_status', 'status', 'trim|xss_clean');
        $this->form_validation->set_rules('seller_id', 'Seller id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('order_type', 'Order Type', 'trim|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $seller_id = $this->input->post('seller_id', true);
            $this->db->select('count(DISTINCT `order_id`) total');
            if (!empty($seller_id)) {
                $this->db->where('seller_id', $seller_id);
                $this->db->where("active_status != 'awaiting' ");
            }
            $result = $this->db->from("order_items")->get()->result_array();

            // print_R($this->db->last_query());
            // print_R($result[0]['total']);
            $id = (isset($_POST['id']) && !empty($_POST['id'])) ? $_POST['id'] : false;
            $user_id = (isset($_POST['user_id']) && !empty($_POST['user_id'])) ? $_POST['user_id'] : false;
            $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date'])) ? $_POST['start_date'] : false;
            $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date'])) ? $_POST['end_date'] : false;
            $multiple_status =   (isset($_POST['active_status']) && !empty($_POST['active_status'])) ? explode(',', $_POST['active_status']) : false;
            $download_invoice =   (isset($_POST['download_invoice']) && !empty($_POST['download_invoice'])) ? $_POST['download_invoice'] : 1;
            $city_id =   (isset($_POST['city_id']) && !empty($_POST['city_id'])) ? $_POST['city_id'] : null;
            $area_id =   (isset($_POST['area_id']) && !empty($_POST['area_id'])) ? $_POST['area_id'] : null;
            $order_type =   (isset($_POST['order_type']) && !empty($_POST['order_type'])) ? strtolower($_POST['order_type']) : '';
            $order_details = fetch_orders(
                $id,
                $user_id,
                $multiple_status,
                false,
                $limit,
                $offset,
                $sort,
                $order,
                $download_invoice,
                $start_date,
                $end_date,
                $search,
                $city_id,
                $area_id,
                $seller_id,
                $order_type
            );
            if (!empty($order_details['order_data'])) {

                $this->response['error'] = false;
                $this->response['message'] = 'Data retrieved successfully';
                if (isset($order_type) && !empty($order_type)) {
                    $this->response['total'] = strval(count($order_details['order_data']));
                } else {
                    $this->response['total'] = $result[0]['total'];
                }
                $this->response['awaiting'] = strval(orders_count("awaiting", $seller_id, $order_type));
                $this->response['received'] = strval(orders_count("received", $seller_id, $order_type));
                $this->response['processed'] = strval(orders_count("processed", $seller_id, $order_type));
                $this->response['shipped'] = strval(orders_count("shipped", $seller_id, $order_type));
                $this->response['delivered'] = strval(orders_count("delivered", $seller_id, $order_type));
                $this->response['cancelled'] = strval(orders_count("cancelled", $seller_id, $order_type));
                $this->response['returned'] = strval(orders_count("returned", $seller_id, $order_type));
                $this->response['data'] = $order_details['order_data'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data Does Not Exists';
                $this->response['total'] = "0";
                $this->response['awaiting'] = "0";
                $this->response['received'] = "0";
                $this->response['processed'] = "0";
                $this->response['shipped'] = "0";
                $this->response['delivered'] = "0";
                $this->response['cancelled'] = "0";
                $this->response['returned'] = "0";
                $this->response['data'] = array();
            }
        }
        print_r(json_encode($this->response));
    }

    /* 3.get_order_items

        seller_id:174 
        id:101 { optional }
        user_id:101 { optional }
        order_id:101 { optional }
        active_status: received  {received,delivered,cancelled,processed,returned}     // optional
        start_date : 2020-09-07 or 2020/09/07 { optional }
        end_date : 2021-03-15 or 2021/03/15 { optional }
        search:keyword      // optional
        limit:25            // { default - 25 } optional
        offset:0            // { default - 0 } optional
        sort: oi.id / oi.date_added // { default - id } optional
        order:DESC/ASC      // { default - DESC } optional
    */
    public function get_order_items()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
        $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
        $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'oi.id';
        $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
        $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : '';

        $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('active_status', 'status', 'trim|xss_clean');
        $this->form_validation->set_rules('seller_id', 'Seller id', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            if (isset($_POST['active_status']) && !empty($_POST['active_status'])) {
                $where['active_status'] = $_POST['active_status'];
            }
            $seller_id = $this->input->post('seller_id', true);

            $id = (isset($_POST['id']) && !empty($_POST['id'])) ? $this->input->post('id', true) : false;
            $user_id = (isset($_POST['user_id']) && !empty($_POST['user_id'])) ? $this->input->post('user_id', true) : false;
            $order_id = (isset($_POST['order_id']) && !empty($_POST['order_id'])) ? $this->input->post('order_id', true) : false;
            $start_date = (isset($_POST['start_date']) && !empty($_POST['start_date'])) ? $this->input->post('start_date', true) : false;
            $end_date = (isset($_POST['end_date']) && !empty($_POST['end_date'])) ? $this->input->post('end_date', true) : false;
            $multiple_status =   (isset($_POST['active_status']) && !empty($_POST['active_status'])) ? explode(',', $this->input->post('active_status', true)) : false;
            $order_details = fetch_order_items($id, $user_id, $multiple_status, false, $limit, $offset, $sort, $order, $start_date, $end_date, $search, $seller_id, $order_id);
            if (!empty($order_details['order_data'])) {
                $this->response['error'] = false;
                $this->response['message'] = 'Data retrieved successfully';
                $this->response['total'] = $order_details['total'];
                $this->response['awaiting'] = strval(orders_count("awaiting", $seller_id));
                $this->response['received'] = strval(orders_count("received", $seller_id));
                $this->response['processed'] = strval(orders_count("processed", $seller_id));
                $this->response['shipped'] = strval(orders_count("shipped", $seller_id));
                $this->response['delivered'] = strval(orders_count("delivered", $seller_id));
                $this->response['cancelled'] = strval(orders_count("cancelled", $seller_id));
                $this->response['returned'] = strval(orders_count("returned", $seller_id));
                $this->response['data'] = $order_details['order_data'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data Does Not Exists';
                $this->response['total'] = "0";
                $this->response['awaiting'] = "0";
                $this->response['received'] = "0";
                $this->response['processed'] = "0";
                $this->response['shipped'] = "0";
                $this->response['delivered'] = "0";
                $this->response['cancelled'] = "0";
                $this->response['returned'] = "0";
                $this->response['data'] = array();
            }
        }
        print_r(json_encode($this->response));
    }

    //4. update_order_item_status
    /* to update the status of an individual order item */
    public function update_order_item_status()
    {
        /*
            order_item_id:1
            status : received / processed / shipped / delivered / cancelled / returned
            delivery_boy_id: 15 {optional}
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('order_item_id', 'Order Item ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('delivery_boy_id', 'Delvery Boy Id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean|in_list[received,processed,shipped,delivered,cancelled,returned]');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $order_itam_ids = explode(',', $_POST['order_item_id']);
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
            print_r(json_encode($this->response));
            return false;
        }
        if (count($order_itam_ids) != count($order_items)) {
            $this->response['error'] = true;
            $this->response['message'] = 'Some item was not found on status update';
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }

        // if delivery boy id passes
        $message = '';
        $delivery_boy_updated = 0;
        $delivery_boy_id = (isset($_POST['delivery_boy_id']) && !empty(trim($_POST['delivery_boy_id']))) ? $this->input->post('delivery_boy_id', true) : 0;
        if (!empty($delivery_boy_id)) {
            $delivery_boy = fetch_details('users', ['id' => trim($delivery_boy_id)], '*');
            if (empty($delivery_boy)) {
                $this->response['error'] = true;
                $this->response['message'] = "Invalid Delivery boy id";
                $this->response['data'] = array();
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

                    if (!empty($current_delivery_boy[0]) && count($current_delivery_boy) > 1) {
                        for ($i = 0; $i < count($current_delivery_boys); $i++) {
                            $hashtag_cutomer_name = '< cutomer_name >';
                            $hashtag_order_id = '< order_item_id >';
                            $hashtag_application_name = '< application_name >';
                            $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                            $hashtag = html_entity_decode($string);
                            $data = str_replace(array($hashtag_cutomer_name, $hashtag_order_id, $hashtag_application_name), array($user_res[$i]['username'], $order_items[0]['order_id'], $app_name), $hashtag);
                            $message = output_escaping(trim($data, '"'));
                            $customer_msg = (!empty($custom_notification)) ? $message :  'Hello Dear ' . $user_res[$i]['username'] . 'Order status updated to' . $_POST['val'] . ' for order ID #' . $order_items[0]['order_id'] . ' assigned to you please take note of it! Thank you. Regards ' . $app_name . '';
                            $fcmMsg = array(
                                'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                                'body' =>  $customer_msg,
                                'type' => "order",
                            );
                            if (!empty($user_res[$i]['fcm_id'])) {
                                $fcm_ids[0][] = $user_res[$i]['fcm_id'];
                            }
                        }
                        $message = 'Delivery Boy Updated.';
                        $delivery_boy_updated = 1;
                    } else {
                        if (isset($current_delivery_boy[0]['delivery_boy_id']) && $current_delivery_boy[0]['delivery_boy_id'] == $_POST['delivery_boy_id']) {
                            $hashtag_cutomer_name = '< cutomer_name >';
                            $hashtag_order_id = '< order_id >';
                            $hashtag_application_name = '< application_name >';
                            $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                            $hashtag = html_entity_decode($string);
                            $data = str_replace(array($hashtag_cutomer_name, $hashtag_order_id, $hashtag_application_name), array($user_res[0]['username'], $order_items[0]['order_id'], $app_name), $hashtag);
                            $message = output_escaping(trim($data, '"'));
                            $customer_msg = (!empty($custom_notification)) ? $message :  'Hello Dear ' . $user_res[0]['username'] . 'Order status updated to' . $_POST['val'] . ' for order ID #' . $order_items[0]['order_id'] . ' assigned to you please take note of it! Thank you. Regards ' . $app_name . '';
                            $fcmMsg = array(
                                'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Order status updated",
                                'body' => $customer_msg,
                                'type' => "order",
                            );
                            $message = 'Delivery Boy Updated.';
                            $delivery_boy_updated = 1;
                        } else {
                            $custom_notification = fetch_details('custom_notifications', ['type' => "delivery_boy_order_deliver"], '');
                            $hashtag_cutomer_name = '< cutomer_name >';
                            $hashtag_order_id = '< order_id >';
                            $hashtag_application_name = '< application_name >';
                            $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                            $hashtag = html_entity_decode($string);
                            $data = str_replace(array($hashtag_cutomer_name, $hashtag_order_id, $hashtag_application_name), array($user_res[0]['username'], $order_items[0]['order_id'], $app_name), $hashtag);
                            $message = output_escaping(trim($data, '"'));
                            $customer_msg = (!empty($custom_notification)) ? $message :  'Hello Dear ' . $user_res[0]['username'] . 'Order status updated to' . $_POST['val'] . ' for order ID #' . $order_items[0]['order_id'] . ' assigned to you please take note of it! Thank you. Regards ' . $app_name . '';
                            $fcmMsg = array(
                                'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "You have new order to deliver",
                                'body' => $customer_msg,
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
                if ($this->order_model->update_order(['delivery_boy_id' => $_POST['delivery_boy_id']], $order_itam_ids, false, 'order_items')) {
                    $delivery_error = false;
                }
            }
        }
        $res = validate_order_status($_POST['order_item_id'], $_POST['status']);
        if ($res['error']) {
            $this->response['error'] = $delivery_boy_updated == 1 ? false : true;
            $this->response['message'] = $message . $res['message'];
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
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }
                }

                // processing order items
                $order_item_res = $this->db->select(' * , (Select count(id) from order_items where order_id = oi.order_id ) as order_counter ,(Select count(active_status) from order_items where active_status ="cancelled" and order_id = oi.order_id ) as order_cancel_counter , (Select count(active_status) from order_items where active_status ="returned" and order_id = oi.order_id ) as order_return_counter,(Select count(active_status) from order_items where active_status ="delivered" and order_id = oi.order_id ) as order_delivered_counter , (Select count(active_status) from order_items where active_status ="processed" and order_id = oi.order_id ) as order_processed_counter , (Select count(active_status) from order_items where active_status ="shipped" and order_id = oi.order_id ) as order_shipped_counter , (Select status from orders where id = oi.order_id ) as order_status ')
                    ->where(['id' => $order_item_id])
                    ->get('order_items oi')->result_array();
                process_refund($order_item_res[0]['id'], $_POST['status'], 'order_items');
                if ($this->order_model->update_order(['status' => $_POST['status']], ['id' => $order_item_res[0]['id']], true, 'order_items')) {
                    $this->order_model->update_order(['active_status' => $_POST['status']], ['id' => $order_item_res[0]['id']], false, 'order_items');
                    if (($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_cancel_counter']) + 1 && $_POST['status'] == 'cancelled') ||  ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_return_counter']) + 1 && $_POST['status'] == 'returned') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_delivered_counter']) + 1 && $_POST['status'] == 'delivered') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_processed_counter']) + 1 && $_POST['status'] == 'processed') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_shipped_counter']) + 1 && $_POST['status'] == 'shipped')) {

                        /* process the refer and earn */
                        $user = fetch_details('orders', ['id' => $order_item_res[0]['order_id']], 'user_id');
                        $user_id = $user[0]['user_id'];
                        if (trim($_POST['status']) == 'cancelled' || trim($_POST['status']) == 'returned') {
                            $data = fetch_details('order_items', ['id' => $order_item_id], 'product_variant_id,quantity');
                            update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
                        }
                        $response = process_referral_bonus($user_id, $order_item_res[0]['order_id'], $_POST['status']);
                    }
                }
                // Update login id in order_item table
                update_details(['updated_by' => $order_items[0]['seller_id']], ['id' => $order_item_res[0]['id']], 'order_items');
            }
            $settings = get_settings('system_settings', true);
            $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
            $user_res = fetch_details('users', ['id' => $user_id], 'username,fcm_id');
            $fcm_ids = array();
            //custom send notifications
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
                    'type' => "order",
                );

                $fcm_ids[0][] = $user_res[0]['fcm_id'];
                send_notification($fcmMsg, $fcm_ids);
            }

            $this->response['error'] = false;
            $this->response['message'] = 'Status Updated Successfully';
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] =  "No item(s) selected to update";
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }


        return false;
    }

    // 5.get_categories
    public function get_categories()
    {
        /*
            seller_id:175  
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('seller_id', 'Seller Id', 'trim|required|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        }
        $this->response['message'] = "Cateogry(s) retrieved successfully!";
        $seller_id = (!empty($_POST['seller_id']) && isset($_POST['seller_id'])) ? $this->input->post('seller_id', true)  : '';
        $cat_res = $this->category_model->get_seller_categories($seller_id);
        $this->response['error'] = (empty($cat_res)) ? true : false;
        $this->response['message'] = (empty($cat_res)) ? 'Category does not exist' : 'Category retrieved successfully';
        $this->response['data'] = $cat_res;
        print_r(json_encode($this->response));
    }

    // 6.get_products
    public function get_products()
    {
        /*
        seller_id:175
        id:101              // optional
        category_id:29      // optional
        user_id:15          // optional
        search:keyword      // optional
        tags:multiword tag1, tag2, another tag      // optional
        flag:low/sold      // optional
        attribute_value_ids : 34,23,12 // { Use only for filteration } optional
        limit:25            // { default - 25 } optional
        offset:0            // { default - 0 } optional
        sort:p.id / p.date_added / pv.price
        order:DESC/ASC      // { default - DESC } optional
        is_similar_products:1 // { default - 0 } optional
        top_rated_product: 1 // { default - 0 } optional
        show_only_active_products:0 { default - 1 } optional

        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('seller_id', 'Seller ID', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('id', 'Product ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'Search', 'trim|xss_clean');
        $this->form_validation->set_rules('category_id', 'Category id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('attribute_value_ids', 'Attr Ids', 'trim|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean|alpha');
        $this->form_validation->set_rules('is_similar_products', 'Similar Products', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('top_rated_product', ' Top Rated Product ', 'trim|xss_clean|numeric');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $limit = (isset($_POST['limit'])) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset'])) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'ASC';
            $seller_id = (isset($_POST['seller_id']) && !empty(trim($_POST['seller_id']))) ?  $this->input->post('seller_id', true) : NULL;
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'p.row_order';
            $filters['search'] =  (isset($_POST['search'])) ? $_POST['search'] : null;
            $filters['tags'] =  (isset($_POST['tags'])) ? $_POST['tags'] : "";
            $filters['flag'] =  (isset($_POST['flag']) && !empty($_POST['flag'])) ? $_POST['flag'] : "";
            $filters['attribute_value_ids'] = (isset($_POST['attribute_value_ids'])) ? $_POST['attribute_value_ids'] : null;
            $filters['is_similar_products'] = (isset($_POST['is_similar_products'])) ? $_POST['is_similar_products'] : null;
            $filters['product_type'] = (isset($_POST['top_rated_product']) && $_POST['top_rated_product'] == 1) ? 'top_rated_product_including_all_products' : null;
            $filters['show_only_active_products'] = (isset($_POST['show_only_active_products'])) ? $_POST['show_only_active_products'] : true;
            $filters['show_only_stock_product'] = (isset($_POST['show_only_stock_product'])) ? $_POST['show_only_stock_product'] : false;
            $category_id = (isset($_POST['category_id'])) ? $_POST['category_id'] : null;
            $product_id = (isset($_POST['id'])) ? $_POST['id'] : null;
            $user_id = (isset($_POST['user_id'])) ? $_POST['user_id'] : null;

            $products = fetch_product($user_id, (isset($filters)) ? $filters : null, $product_id, $category_id, $limit, $offset, $sort, $order, null, null, $seller_id);

            if (!empty($products['product'])) {
                $this->response['error'] = false;
                $this->response['message'] = "Products retrieved successfully !";
                $this->response['filters'] = (isset($products['filters']) && !empty($products['filters'])) ? $products['filters'] : [];
                $this->response['total'] = (isset($products['total'])) ? strval($products['total']) : '';
                $this->response['offset'] = (isset($_POST['offset']) && !empty($_POST['offset'])) ? $_POST['offset'] : '0';
                $this->response['data'] = $products['product'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Products Not Found !";
                $this->response['data'] = array();
            }
        }
        print_r(json_encode($this->response));
    }

    // 8.get_transactions
    public function get_transactions()
    {
        /*
            user_id:73             
            id: 1001                // { optional}
            type : credit / debit - for wallet // { optional }
            search : Search keyword // { optional }
            limit:25                // { default - 25 } optional
            offset:0                // { default - 0 } optional
            sort: id / date_created // { default - id } optional
            order:DESC/ASC          // { default - DESC } optional
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('transaction_type', 'Transaction Type', 'trim|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
        $this->form_validation->set_rules('search', 'Search keyword', 'trim|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $user_id = (isset($_POST['user_id']) && is_numeric($_POST['user_id']) && !empty(trim($_POST['user_id']))) ? $this->input->post('user_id', true) : "";
            $id = (isset($_POST['id']) && is_numeric($_POST['id']) && !empty(trim($_POST['id']))) ? $this->input->post('id', true) : "";
            $type = (isset($_POST['type']) && !empty(trim($_POST['type']))) ? $this->input->post('type', true) : "";
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';
            $res = $this->transaction_model->get_transactions($id, $user_id, 'wallet', $type, $search, $offset, $limit, $sort, $order);
            $this->response['error'] = !empty($res['data']) ? false : true;
            $this->response['message'] = !empty($res['data']) ? 'Transactions Retrieved Successfully' : 'Transactions does not exists';
            $this->response['total'] = !empty($res['data']) ? $res['total'] : 0;
            $this->response['data'] = !empty($res['data']) ? $res['data'] : [];
        }

        print_r(json_encode($this->response));
    }

    //9. get_statistics
    public function get_statistics()
    {
        /* 
            seller_id:174
        */

        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('seller_id', 'Seller ID', 'trim|required|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $response['error'] = true;
            $response['message'] = strip_tags(validation_errors());
            $response['data'] = array();
            print_r(json_encode($response));
            return false;
        } else {

            $currency_symbol = get_settings('currency');
            $bulkData = $rows =  $tempRow =  $tempRow1 =  $tempRow2 = array();
            $bulkData['error'] = false;
            $bulkData['message'] = 'Data retrieved successfully';
            $bulkData['currency_symbol'] = !empty($currency_symbol) ? $currency_symbol : '';
            $user_id = $this->input->post('seller_id', true);
            $res = $this->db->select('c.name as name,count(c.id) as counter')->where(['p.status' => '1', 'c.status' => '1', 'p.seller_id' => $user_id])->join('products p', 'p.category_id=c.id')->group_by('c.id')->get('categories c')->result_array();
            foreach ($res as $row) {
                $tempRow['cat_name'][] = $row['name'];
                $tempRow['counter'][] = $row['counter'];
            }

            $rows[] = $tempRow;
            $bulkData['category_wise_product_count'] = $tempRow;

            // overall sale
            $overall_sale = $this->db->select("SUM(sub_total) as overall_sale")->where('seller_id = ' . $user_id)->where("active_status= 'delivered'")->get('`order_items`')->result_array();
            $overall_sale = !empty($overall_sale[0]['overall_sale']) ? intval($overall_sale[0]['overall_sale']) : 0;
            $tempRow1['overall_sale'] = $overall_sale;

            // daily earnings
            $day_res = $this->db->select("DAY(date_added) as date, SUM(sub_total) as total_sale")
                ->where('date_added >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)')
                ->where(['seller_id' => $user_id, 'active_status' => 'delivered'])
                ->group_by('day(date_added)')->get('`order_items`')->result_array();
            $day_wise_sales['total_sale'] = array_map('intval', array_column($day_res, 'total_sale'));
            $day_wise_sales['day'] = array_column($day_res, 'date');
            $tempRow1['daily_earnings'] = $day_wise_sales;

            // weekly earnings
            $d = strtotime("today");
            $start_week = strtotime("last sunday midnight", $d);
            $end_week = strtotime("next saturday", $d);
            $start = date("Y-m-d", $start_week);
            $end = date("Y-m-d", $end_week);
            $week_res = $this->db->select("DATE_FORMAT(date_added, '%d-%b') as date, SUM(sub_total) as total_sale")
                ->where("date(date_added) >='$start' and date(date_added) <= '$end' ")
                ->where(['seller_id' => $user_id, 'active_status' => 'delivered'])
                ->group_by('day(date_added)')->get('`order_items`')->result_array();
            $week_wise_sales['total_sale'] = array_map('intval', array_column($week_res, 'total_sale'));
            $week_wise_sales['week'] = array_column($week_res, 'date');
            $tempRow1['weekly_earnings'] = $week_wise_sales;

            // monthly earnings
            $month_res = $this->db->select('SUM(sub_total) AS total_sale,DATE_FORMAT(date_added,"%b") AS month_name ')
                ->where(['seller_id' => $user_id, 'active_status' => 'delivered'])
                ->group_by('year(CURDATE()),MONTH(date_added)')
                ->order_by('year(CURDATE()),MONTH(date_added)')
                ->get('`order_items`')->result_array();
            $month_wise_sales['total_sale'] = array_map('intval', array_column($month_res, 'total_sale'));
            $month_wise_sales['month_name'] = array_column($month_res, 'month_name');
            $tempRow1['monthly_earnings'] = $month_wise_sales;
            $rows1[] = $tempRow1;
            $bulkData['earnings'] = $rows1;

            // counts
            $count_products_low_status = $this->Home_model->count_products_stock_low_status($user_id);
            $count_products_sold_out_status = $this->Home_model->count_products_availability_status($user_id);
            $tempRow2['order_counter'] = strval(orders_count("", $user_id));
            $tempRow2['delivered_orders_counter'] = strval(orders_count("delivered", $user_id));
            $tempRow2['cancelled_orders_counter'] = strval(orders_count("cancelled", $user_id));
            $tempRow2['returned_orders_counter'] = strval(orders_count("returned", $user_id));
            $tempRow2['received_orders_counter'] = strval(orders_count("received", $user_id));
            $tempRow2['product_counter'] = $this->Home_model->count_products($user_id);
            $tempRow2['user_counter'] = (get_seller_permission($user_id, 'customer_privacy')) ? $this->Home_model->count_new_users() : "0";
            $tempRow2['permissions'] = get_seller_permission($user_id);
            $tempRow2['count_products_low_status'] = strval($count_products_low_status);
            $tempRow2['count_products_sold_out_status'] = (isset($count_products_sold_out_status) && ($count_products_sold_out_status != "")) ? strval($count_products_sold_out_status) : "0";
            $rows2[] = $tempRow2;
            $bulkData['counts'] = $rows2;
            print_r(json_encode($bulkData));
        }
    }

    //10. forgot_password
    public function forgot_password()
    {
        /* Parameters to be passed
            mobile_no:7894561235            
            new: pass@123
        */

        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|numeric|required|xss_clean|max_length[16]');
        $this->form_validation->set_rules('new', 'New Password', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }

        $identity_column = $this->config->item('identity', 'ion_auth');
        $res = fetch_details('users', ['mobile' => $_POST['mobile_no']]);
        if (!empty($res)) {
            $identity = ($identity_column  == 'email') ? $res[0]['email'] : $res[0]['mobile'];
            if (!$this->ion_auth->reset_password($identity, $_POST['new'])) {
                $response['error'] = true;
                $response['message'] = strip_tags($this->ion_auth->messages());;
                $response['data'] = array();
                echo json_encode($response);
                return false;
            } else {
                $response['error'] = false;
                $response['message'] = 'Reset Password Successfully';
                $response['data'] = array();
                echo json_encode($response);
                return false;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'User does not exists !';
            $response['data'] = array();
            echo json_encode($response);
            return false;
        }
    }

    //11. delete_order
    public function delete_order()
    {
        /*
            order_id:1
        */
        if (!$this->verify_token()) {
            return false;
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->response['error'] = true;
            $this->response['message'] = DEMO_VERSION_MSG;
            echo json_encode($this->response);
            return false;
            exit();
        }

        $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $order_id = $_POST['order_id'];
            delete_details(['id' => $order_id], 'orders');
            delete_details(['order_id' => $order_id], 'order_items');

            $this->response['error'] = false;
            $this->response['message'] = 'Order deleted successfully';
            $this->response['data'] = array();
        }
        print_r(json_encode($this->response));
    }

    //12. verify_user
    public function verify_user()
    {
        /* Parameters to be passed
            mobile: 9874565478
            email: test@gmail.com // { optional }
        */
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return;
        } else {
            if (isset($_POST['mobile']) && is_exist(['mobile' => $_POST['mobile']], 'users')) {
                $user_id = fetch_details('users', ['mobile' => $_POST['mobile']], 'id');

                //Check if this mobile no. is registered as a seller or not.
                if (!$this->ion_auth->in_group('seller', $user_id[0]['id'])) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Mobile number / email could not be found!';
                    print_r(json_encode($this->response));
                    return;
                } else {
                    $this->response['error'] = false;
                    $this->response['message'] = 'Mobile number is registered. ';
                    print_r(json_encode($this->response));
                    return;
                }
            }
            if (isset($_POST['email']) && is_exist(['email' => $_POST['email']], 'users')) {
                $this->response['error'] = false;
                $this->response['message'] = 'Email is registered.';
                print_r(json_encode($this->response));
                return;
            }

            $this->response['error'] = true;
            $this->response['message'] = 'Mobile number / email could not be found!';
            print_r(json_encode($this->response));
            return;
        }
    }

    // 13.get_settings
    public function get_settings()
    {
        /*
            type : payment_method // { default : all  } optional            
            user_id:  15 { optional }
        */
        if (!$this->verify_token()) {
            return false;
        }
        $type = (isset($_POST['type']) && $_POST['type'] == 'payment_method') ? 'payment_method' : 'all';
        $this->form_validation->set_rules('type', 'Setting Type', 'trim|xss_clean');


        if (!$this->form_validation->run()) {

            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $general_settings = array();

            if ($type == 'all' || $type == 'payment_method') {


                $settings = [
                    'logo' => 0,
                    'seller_privacy_policy' => 0,
                    'seller_terms_conditions' => 0,
                    'fcm_server_key' => 0,
                    'contact_us' => 0,
                    'payment_method' => 1,
                    'about_us' => 0,
                    'currency' => 0,
                    'time_slot_config' => 1,
                    'user_data' => 0,
                    'system_settings' => 1,
                    'shipping_policy' => 0,
                    'return_policy' => 0,
                ];

                if ($type == 'payment_method') {

                    $settings_res['payment_method'] = get_settings($type, $settings[$_POST['type']]);
                    $time_slot_config = get_settings('time_slot_config', $settings['time_slot_config']);

                    if (!empty($time_slot_config) && isset($time_slot_config)) {
                        $time_slot_config['delivery_starts_from'] = $time_slot_config['delivery_starts_from'] - 1;
                        $time_slot_config['starting_date'] = date('Y-m-d', strtotime(date('d-m-Y') . ' + ' . intval($time_slot_config['delivery_starts_from']) . ' days'));
                    }

                    $settings_res['time_slot_config'] = $time_slot_config;
                    $time_slots = fetch_details('time_slots', '',  '*', '', '', 'from_time', 'ASC');

                    if (!empty($time_slots)) {
                        for ($i = 0; $i < count($time_slots); $i++) {
                            $datetime = DateTime::createFromFormat("h:i:s a", $time_slots[$i]['from_time']);
                        }
                    }

                    $settings_res['time_slots'] = array_values($time_slots);
                    $general_settings = $settings_res;
                } else {

                    foreach ($settings as $type => $isjson) {
                        if ($type == 'payment_method') {
                            continue;
                        }
                        $general_settings[$type] = [];
                        $settings_res = get_settings($type, $isjson);

                        if ($type == 'logo') {
                            $settings_res = base_url() . $settings_res;
                        }
                        if ($type == 'user_data' && isset($_POST['user_id'])) {
                            $cart_total_response = get_cart_total($_POST['user_id'], false, 0);
                            $settings_res = fetch_users($_POST['user_id']);
                            $settings_res[0]['cities'] =  (isset($settings_res[0]['cities']) && $settings_res[0]['cities'] != null) ? $cart_total_response[0]['cities'] : '';
                            $settings_res[0]['street'] =  (isset($settings_res[0]['street']) && $settings_res[0]['street'] != null) ? $cart_total_response[0]['street'] : '';
                            $settings_res[0]['area'] =  (isset($settings_res[0]['area']) && $settings_res[0]['area'] != null) ? $cart_total_response[0]['area'] : '';
                            $settings_res[0]['cart_total_items'] = (isset($cart_total_response[0]) && $cart_total_response[0]['cart_count'] > 0) ? $cart_total_response[0]['cart_count'] : '0';
                            $settings_res = $settings_res[0];
                        } elseif ($type == 'user_data' && !isset($_POST['user_id'])) {
                            $settings_res = '';
                        }

                        //Strip tags in case of terms_conditions and privacy_policy
                        array_push($general_settings[$type], $settings_res);
                    }
                    $general_settings['privacy_policy'] = $general_settings['seller_privacy_policy'];
                    unset($general_settings['seller_privacy_policy']);
                    $general_settings['terms_conditions'] = $general_settings['seller_terms_conditions'];
                    unset($general_settings['seller_terms_conditions']);
                }

                $this->response['error'] = false;
                $this->response['message'] = 'Settings retrieved successfully';
                $this->response['data'] = $general_settings;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Settings Not Found';
                $this->response['data'] = array();
            }
            print_r(json_encode($this->response));
        }
    }

    // 14. update_fcm
    public function update_fcm()
    {

        /* Parameters to be passed
             user_id:12
             fcm_id: FCM_ID
         */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'Id', 'trim|numeric|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }

        $user_res = update_details(['fcm_id' => $_POST['fcm_id']], ['id' => $_POST['user_id']], 'users');

        if ($user_res) {
            $response['error'] = false;
            $response['message'] = 'Updated Successfully';
            $response['data'] = array();
            echo json_encode($response);
            return false;
        } else {
            $response['error'] = true;
            $response['message'] = 'Updation Failed !';
            $response['data'] = array();
            echo json_encode($response);
            return false;
        }
    }

    //15.get_cities
    public function get_cities()
    {
        /*
           sort:               // { c.name / c.id } optional
           order:DESC/ASC      // { default - ASC } optional
           search:value        // {optional} 
       */
        $this->form_validation->set_rules('sort', 'sort', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'search', 'trim|numeric|xss_clean');

        if (!$this->verify_token()) {
            return false;
        }
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        } else {
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'c.name';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'ASC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $id = $this->input->post('id', true);

            $result = $this->Area_model->get_cities($sort, $order, $search);
            print_r(json_encode($result));
        }
    }

    //16. get_areas_by_city_id
    public function get_areas_by_city_id()
    {
        /*  id:'57' 
                limit:25            // { default - 25 } optional
                offset:0            // { default - 0 } optional
                sort:               // { a.name / a.id } optional
                order:DESC/ASC      // { default - ASC } optional
                search:value        // {optional} 
            */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('id', 'City Id', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        } else {
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'a.name';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'ASC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $id = $this->input->post('id', true);
            $result = $this->Area_model->get_area_by_city($id, $sort, $order, $search);
            print_r(json_encode($result));
        }
    }

    //17.get_zipcodes
    public function get_zipcodes()
    {
        /*
             limit:10 {optional}
             offset:0 {optional}
             search:0 {optional}
         */
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
        } else {

            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $search = (isset($_POST['search']) &&  !empty(trim($_POST['search']))) ? $this->input->post('search', true) : '';
            $zipcodes = $this->Area_model->get_zipcodes($search, $limit, $offset);
            print_r(json_encode($zipcodes));
        }
    }

    //18. get_taxes
    public function get_taxes()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->db->select('*');
        $types = $this->db->get('taxes')->result_array();
        if (!empty($types)) {
            for ($i = 0; $i < count($types); $i++) {
                $types[$i] = output_escaping($types[$i]);
            }
        }
        $this->response['error'] = false;
        $this->response['message'] = 'Taxes fetched successfully';
        $this->response['data'] = $types;
        print_r(json_encode($this->response));
    }

    //19. send_withdrawal_request
    public function send_withdrawal_request()
    {
        /* 
            user_id:174
            payment_address: 12343535
            amount: 56
        */

        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('payment_address', 'Payment Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean|numeric|greater_than[0]');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $user_id = $this->input->post('user_id', true);
            $payment_address = $this->input->post('payment_address', true);
            $amount = $this->input->post('amount', true);
            $userData = fetch_details('users', ['id' => $_POST['user_id']], 'balance');

            if (!empty($userData)) {
                if ($_POST['amount'] <= $userData[0]['balance']) {
                    $data = [
                        'user_id' => $user_id,
                        'payment_address' => $payment_address,
                        'payment_type' => 'seller',
                        'amount_requested' => $amount,
                    ];

                    if (insert_details($data, 'payment_requests')) {
                        $this->delivery_boy_model->update_balance($amount, $user_id, 'deduct');
                        $userData = fetch_details('users', ['id' => $_POST['user_id']], 'balance');
                        $this->response['error'] = false;
                        $this->response['message'] = 'Withdrawal Request Sent Successfully';
                        $this->response['data'] = $userData[0]['balance'];
                    } else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Cannot sent Withdrawal Request.Please Try again later.';
                        $this->response['data'] = array();
                    }
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'You don\'t have enough balance to sent the withdraw request.';
                    $this->response['data'] = array();
                }

                print_r(json_encode($this->response));
            }
        }
    }

    //20. get_withdrawal_request
    public function get_withdrawal_request()
    {
        /* 
            user_id:15  
            limit:10  {optional}
            offset:10  {optional}
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('limit', 'Limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'Offset', 'trim|numeric|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {

            $limit = ($this->input->post('limit', true)) ? $this->input->post('limit', true) : null;
            $offset = ($this->input->post('offset', true)) ? $this->input->post('offset', true) : null;
            $userData = fetch_details('payment_requests', ['user_id' => $_POST['user_id']], '*', $limit, $offset);

            $bulkData = array();
            $rows = array();
            $tempRow = array();
            foreach ($userData as $row) {
                $row = output_escaping($row);

                $tempRow['id'] = $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['payment_type'] = $row['payment_type'];
                $tempRow['amount_requested'] = $row['amount_requested'];
                $tempRow['remarks'] = $row['remarks'];
                $tempRow['payment_address'] = $row['payment_address'];
                $status = [
                    '0' => 'pending',
                    '1' => 'approved',
                    '2' => 'rejected',
                ];
                $tempRow['status_code'] = $row['status'];
                $tempRow['status'] = $status[$row['status']];
                $tempRow['date_created'] = $row['date_created'];

                $rows[] = $tempRow;
            }
            //$bulkData['rows'] = $rows;
            $this->response['error'] = false;
            $this->response['message'] = 'Withdrawal Request Retrieved Successfully';
            $this->response['total'] = strval(count($userData));
            $this->response['data'] = $rows;
            print_r(json_encode($this->response));
        }
    }

    // 21. get_attribute_set
    public function get_attribute_set()
    {
        /*
            sort: ats.name              // { ats.name / ats.id } optional
            order:DESC/ASC      // { default - ASC } optional
            search:value        // {optional} 
            limit:10  {optional}
            offset:10  {optional}
       */
        $this->form_validation->set_rules('sort', 'sort', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'search', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('limit', 'Limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'Offset', 'trim|numeric|xss_clean');

        if (!$this->verify_token()) {
            return false;
        }
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        } else {
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'ats.name';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'ASC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = ($this->input->post('limit', true)) ? $this->input->post('limit', true) : NULL;
            $offset = ($this->input->post('offset', true)) ? $this->input->post('offset', true) : NULL;
            $result = $this->Attribute_model->get_attribute_set($sort, $order, $search, $limit, $offset);
            print_r(json_encode($result));
        }
    }

    //22. get_attributes
    public function get_attributes()
    {
        /*
            attribute_set_id:1  // {optional}
            sort: a.name              // { a.name / a.id } optional
            order:DESC/ASC      // { default - ASC } optional
            search:value        // {optional} 
            limit:10  {optional}
            offset:10  {optional}
       */
        $this->form_validation->set_rules('sort', 'sort', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');
        $this->form_validation->set_rules('attribute_set_id', 'attribute set id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('limit', 'Limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'Offset', 'trim|numeric|xss_clean');

        if (!$this->verify_token()) {
            return false;
        }
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        } else {
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'a.name';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'ASC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = ($this->input->post('limit', true)) ? $this->input->post('limit', true) : NULL;
            $offset = ($this->input->post('offset', true)) ? $this->input->post('offset', true) : NULL;
            $attribute_set_id = (isset($_POST['attribute_set_id']) && !empty(trim($_POST['attribute_set_id']))) ? $this->input->post('attribute_set_id', true) : "";
            $result = $this->Attribute_model->get_attributes($sort, $order, $search, $attribute_set_id, $limit, $offset);
            print_r(json_encode($result));
        }
    }

    //23. get_attribute_values
    public function get_attribute_values()
    {
        /*
            attribute_id:1  // {optional}
            sort:a.name               // { a.name / a.id } optional
            order:DESC/ASC      // { default - ASC } optional
            search:value        // {optional} 
            limit:10  {optional}
            offset:10  {optional}
       */
        $this->form_validation->set_rules('sort', 'sort', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');
        $this->form_validation->set_rules('attribute_id', 'attribute id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('limit', 'Limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'Offset', 'trim|numeric|xss_clean');

        if (!$this->verify_token()) {
            return false;
        }
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        } else {
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'a.name';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'ASC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = ($this->input->post('limit', true)) ? $this->input->post('limit', true) : NULL;
            $offset = ($this->input->post('offset', true)) ? $this->input->post('offset', true) : NULL;
            $attribute_id = (isset($_POST['attribute_id']) && !empty(trim($_POST['attribute_id']))) ? $this->input->post('attribute_id', true) : "";
            $result = $this->Attribute_model->get_attribute_value($sort, $order, $search, $attribute_id, $limit, $offset);
            print_r(json_encode($result));
        }
    }

    public function add_products()
    {
        /*
            seller_id:1255
            pro_input_name: product name
            short_description: description
            tags:tag1,tag2,tag3     //{comma saprated}
            pro_input_tax:tax_id
            indicator:1             //{ 0 - none | 1 - veg | 2 - non-veg }
            made_in: india          //{optional}
            hsn_code: 456789        //{optional}
            brand: adidas          //{optional}
            total_allowed_quantity:100
            minimum_order_quantity:12
            quantity_step_size:1
            warranty_period:1 month     {optional}
            guarantee_period:1 month   {optional}
            deliverable_type:1        //{0:none, 1:all, 2:include, 3:exclude}
            deliverable_zipcodes:1,2,3  //{NULL: if deliverable_type = 0 or 1}
            is_prices_inclusive_tax:0   //{1: inclusive | 0: exclusive}
            cod_allowed:1               //{ 1:allowed | 0:not-allowed }
            download_allowed:1               //{ 1:allowed | 0:not-allowed }
            download_link_type:self_hosted             //{ values : self_hosted | add_link }
            pro_input_zip:file              //when download type is self_hosted add file for download
            download_link : url             //{URL of download file}
            is_returnable:1             // { 1:returnable | 0:not-returnable } 
            is_cancelable:1             //{1:cancelable | 0:not-cancelable}
            cancelable_till:            //{received,processed,shipped}
            pro_input_image:file
            other_images: files
            video_type:                 // {values: vimeo | youtube}
            video:                      //{URL of video}
            pro_input_video: file
            pro_input_description:product's description 
            category_id:99
            attribute_values:1,2,3,4,5
            status:1/0 {optional}
            --------------------------------------------------------------------------------
            till above same params
            --------------------------------------------------------------------------------
            --------------------------------------------------------------------------------
            common param for simple and variable product 
            --------------------------------------------------------------------------------          
            product_type:simple_product | variable_product  |  digital_product
            variant_stock_level_type:product_level | variable_level
            
            if(product_type == variable_product):
                variants_ids:3 5,4 5,1 2
                variant_price:100,200
                variant_special_price:90,190
                variant_images:files              //{optional}

                sku_variant_type:test            //{if (variant_stock_level_type == product_level)}
                total_stock_variant_type:100     //{if (variant_stock_level_type == product_level)}
                variant_status:1                 //{if (variant_stock_level_type == product_level)}

                variant_sku:test,test             //{if(variant_stock_level_type == variable_level)}
                variant_total_stock:120,300       //{if(variant_stock_level_type == variable_level)}
                variant_level_stock_status:1,1    //{if(variant_stock_level_type == variable_level)}

            if(product_type == simple_product):
                simple_product_stock_status:null|0|1   {1=in stock | 0=out stock}
                simple_price:100
                simple_special_price:90
                product_sku:test                    {optional}
                product_total_stock:100             {optional}
                variant_stock_status: 0             {optional}//{0 =>'Simple_Product_Stock_Active' 1 => "Product_Level" 2 => "Variable_Level"	}
            
           if(product_type == digital_product):
                simple_price:100
                simple_special_price:90
                
       */
        if (!$this->verify_token()) {
            return false;
        }

        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->response['error'] = true;
            $this->response['message'] = DEMO_VERSION_MSG;
            echo json_encode($this->response);
            return false;
            exit();
        }


        $this->form_validation->set_rules('seller_id', 'Seller Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('pro_input_name', 'Product Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('short_description', 'Short Description', 'trim|required|xss_clean');
        $this->form_validation->set_rules('category_id', 'Category Id', 'trim|required|xss_clean', array('required' => 'Category is required'));
        $this->form_validation->set_rules('pro_input_tax', 'Tax', 'trim|xss_clean');
        $this->form_validation->set_rules('image', 'Image', 'trim|xss_clean', array('required' => 'Image is required'));
        $this->form_validation->set_rules('other_image', 'Other Image', 'trim|xss_clean');
        $this->form_validation->set_rules('made_in', 'Made In', 'trim|xss_clean');
        $this->form_validation->set_rules('hsn_code', 'HSN_Code', 'trim|xss_clean');
        $this->form_validation->set_rules('brand', 'Brand', 'trim|xss_clean');
        $this->form_validation->set_rules('product_type', 'Product type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('total_allowed_quantity', 'Total Allowed Quantity', 'trim|xss_clean');
        $this->form_validation->set_rules('minimum_order_quantity', 'Minimum Order Quantity', 'trim|xss_clean');
        $this->form_validation->set_rules('quantity_step_size', 'Quantity Step Size', 'trim|xss_clean');
        $this->form_validation->set_rules('warranty_period', 'Warranty Period', 'trim|xss_clean');
        $this->form_validation->set_rules('guarantee_period', 'Guarantee Period', 'trim|xss_clean');
        $this->form_validation->set_rules('video', 'Video', 'trim|xss_clean');
        $this->form_validation->set_rules('video_type', 'Video Type', 'trim|xss_clean');
        if (isset($_POST['product_type']) && $_POST['product_type'] == 'simple_product' || $_POST['product_type'] == 'variable_product') {
            $this->form_validation->set_rules('deliverable_type', 'Deliverable Type', 'required|trim|xss_clean');
        }
        $this->form_validation->set_rules('pro_input_image', 'Product Image', 'required|trim|xss_clean');
        $this->form_validation->set_rules('require_products_approval', 'Require Products Approval', 'trim|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean');

        if (isset($_POST['video_type']) && $_POST['video_type'] != '') {
            if ($_POST['video_type'] == 'youtube' || $_POST['video_type'] == 'vimeo') {
                $this->form_validation->set_rules('video', 'Video link', 'trim|required|xss_clean', array('required' => " Please paste a %s in the input box. "));
            } else {
                $this->form_validation->set_rules('pro_input_video', 'Video file', 'trim|required|xss_clean', array('required' => " Please choose a %s to be set. "));
            }
        }
        if (isset($_POST['download_allowed']) && $_POST['download_allowed'] != '' && !empty($_POST['download_allowed']) && $_POST['download_allowed'] == '1') {
            $this->form_validation->set_rules('download_link_type', 'Download Link Type', 'required|xss_clean');
            if (isset($_POST['download_link_type']) && $_POST['download_link_type'] != '' && !empty($_POST['download_link_type']) && $_POST['download_link_type'] == 'self_hosted') {
                $this->form_validation->set_rules('pro_input_zip', 'pro_input_zip', 'required|xss_clean');
            }
            if (isset($_POST['download_link_type']) && $_POST['download_link_type'] != '' && !empty($_POST['download_link_type']) && $_POST['download_link_type'] == 'add_link') {
                $this->form_validation->set_rules('download_link', 'Digital Product URL/Link', 'required|xss_clean');
            }
        }
        $_POST['variant_price'] = (isset($_POST['variant_price']) && !empty($_POST['variant_price'])) ?  explode(",", $this->input->post('variant_price', true)) : NULL;
        $_POST['variant_special_price'] = (isset($_POST['variant_special_price']) && !empty($_POST['variant_special_price'])) ?  explode(",", $this->input->post('variant_special_price', true)) : NULL;
        $_POST['variants_ids'] = (isset($_POST['variants_ids']) && !empty($_POST['variants_ids'])) ?  explode(",", $this->input->post('variants_ids', true)) : NULL;
        $_POST['variant_sku'] = (isset($_POST['variant_sku']) && !empty($_POST['variant_sku'])) ?  explode(",", $this->input->post('variant_sku', true)) : NULL;
        $_POST['variant_total_stock'] = (isset($_POST['variant_total_stock']) && !empty($_POST['variant_total_stock'])) ?  explode(",", $this->input->post('variant_total_stock', true)) : NULL;
        $_POST['variant_level_stock_status'] = (isset($_POST['variant_level_stock_status']) && !empty($_POST['variant_level_stock_status'])) ?  explode(",", $this->input->post('variant_level_stock_status', true)) : NULL;
        $_POST['other_images'] = (isset($_POST['other_images']) && !empty($_POST['other_images'])) ? explode(",", $this->input->post('other_images', true)) : [];
        $_POST['variant_images'] = (isset($_POST['variant_images']) && !empty($_POST['variant_images'])) ? json_decode($_POST['variant_images'], true) : [];
        $_POST['status'] = (isset($_POST['status']) && ($_POST['status'] != '')) ? $this->input->post('status', true) : 1;

        if (isset($_POST['is_cancelable']) && $_POST['is_cancelable'] == '1') {
            $this->form_validation->set_rules('cancelable_till', 'Till which status', 'trim|required|xss_clean|in_list[received,processed,shipped]');
        }

        if (isset($_POST['cod_allowed'])) {
            $this->form_validation->set_rules('cod_allowed', 'COD allowed', 'trim|xss_clean');
        }
        if (isset($_POST['is_prices_inclusive_tax'])) {
            $this->form_validation->set_rules('is_prices_inclusive_tax', 'Tax included in prices', 'trim|xss_clean');
        }
        if ($_POST['deliverable_type'] == INCLUDED || $_POST['deliverable_type'] == EXCLUDED) {
            $this->form_validation->set_rules('deliverable_zipcodes[]', 'Deliverable Zipcodes', 'trim|required|xss_clean');
        }

        // If product type is simple or digital	 		
        if (isset($_POST['product_type']) && $_POST['product_type'] == 'simple_product' || $_POST['product_type'] == 'digital_product') {

            $this->form_validation->set_rules('simple_price', 'Price', 'trim|required|numeric|greater_than_equal_to[' . $this->input->post('simple_special_price') . ']|xss_clean');
            $this->form_validation->set_rules('simple_special_price', 'Special Price', 'trim|numeric|less_than_equal_to[' . $this->input->post('simple_price') . ']|xss_clean');


            if (isset($_POST['simple_product_stock_status']) && in_array($_POST['simple_product_stock_status'], array('0', '1'))) {

                $this->form_validation->set_rules('product_sku', 'SKU', 'trim|xss_clean');
                $this->form_validation->set_rules('product_total_stock', 'Total Stock', 'trim|required|numeric|xss_clean');
                $this->form_validation->set_rules('simple_product_stock_status', 'Stock Status', 'trim|required|numeric|xss_clean');
            }
        } elseif (isset($_POST['product_type']) && $_POST['product_type'] == 'variable_product') { //If product type is variant	
            if (isset($_POST['variant_stock_status']) && $_POST['variant_stock_status'] == '0') {
                if ($_POST['variant_stock_level_type'] == "product_level") {

                    $this->form_validation->set_rules('sku_pro_type', 'SKU', 'trim|xss_clean');
                    $this->form_validation->set_rules('total_stock_variant_type', 'Total Stock', 'trim|required|xss_clean');
                    $this->form_validation->set_rules('variant_stock_status', 'Stock Status', 'trim|required|xss_clean');
                    if (isset($_POST['variant_price']) && isset($_POST['variant_special_price'])) {
                        foreach ($_POST['variant_price'] as $key => $value) {
                            $this->form_validation->set_rules('variant_price[' . $key . ']', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price[' . $key . ']') . ']');
                            $this->form_validation->set_rules('variant_special_price[' . $key . ']', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price[' . $key . ']') . ']');
                        }
                    } else {
                        $this->form_validation->set_rules('variant_price', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price') . ']');
                        $this->form_validation->set_rules('variant_special_price', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price') . ']');
                    }
                } else {
                    if (isset($_POST['variant_price']) && isset($_POST['variant_special_price']) && isset($_POST['variant_sku']) && isset($_POST['variant_total_stock']) && isset($_POST['variant_stock_status'])) {
                        foreach ($_POST['variant_price'] as $key => $value) {
                            $this->form_validation->set_rules('variant_price[' . $key . ']', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price[' . $key . ']') . ']');
                            $this->form_validation->set_rules('variant_special_price[' . $key . ']', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price[' . $key . ']') . ']');
                            $this->form_validation->set_rules('variant_sku[' . $key . ']', 'SKU', 'trim|xss_clean');
                            $this->form_validation->set_rules('variant_total_stock[' . $key . ']', 'Total Stock', 'trim|required|numeric|xss_clean');
                            $this->form_validation->set_rules('variant_level_stock_status[' . $key . ']', 'Stock Status', 'trim|required|numeric|xss_clean');
                        }
                    } else {
                        $this->form_validation->set_rules('variant_price', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price') . ']');
                        $this->form_validation->set_rules('variant_special_price', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price') . ']');
                        $this->form_validation->set_rules('variant_sku', 'SKU', 'trim|xss_clean');
                        $this->form_validation->set_rules('variant_total_stock', 'Total Stock', 'trim|required|numeric|xss_clean');
                        $this->form_validation->set_rules('variant_level_stock_status', 'Stock Status', 'trim|required|numeric|xss_clean');
                    }
                }
            } else {
                if (isset($_POST['variant_price']) && isset($_POST['variant_special_price'])) {
                    foreach ($_POST['variant_price'] as $key => $value) {
                        $this->form_validation->set_rules('variant_price[' . $key . ']', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price[' . $key . ']') . ']');
                        $this->form_validation->set_rules('variant_special_price[' . $key . ']', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price[' . $key . ']') . ']');
                    }
                } else {
                    $this->form_validation->set_rules('variant_price', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price') . ']');
                    $this->form_validation->set_rules('variant_special_price', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price') . ']');
                }
            }
        }

        if (!$this->form_validation->run()) {
            $response['error'] = true;
            $response['message'] = strip_tags(validation_errors());
            $response['data'] = array();
            echo json_encode($response);
        } else {

            // process image and other images

            $_POST['zipcodes'] = (!empty($_POST['deliverable_zipcodes'])) ?  $this->input->post('deliverable_zipcodes', true) : NULL;

            $this->Product_model->add_product($_POST);
            $response['error'] = false;
            $response['message'] = 'Product Added Successfully';
            echo json_encode($response);
            return;
        }
    }

    public function get_media()
    {
        /* 
        seller_id:1255       { optional }
        limit:25            // { default - 25 } optional
        offset:0            // { default - 0 } optional
        sort:               // { id } optional
        order:DESC/ASC      // { default - DESC } optional
        search:value        // {optional} 
        type:image          // {documents,spreadsheet,archive,video,audio,image}
        */
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('seller_id', 'Seller id', 'required|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            echo json_encode($this->response);
        } else {
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'id';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : '';
            $type = (isset($_POST['type']) && !empty(trim($_POST['type']))) ? $this->input->post('type', true) : '';
            $seller_id = (isset($_POST['seller_id']) && !empty(trim($_POST['seller_id']))) ? $this->input->post('seller_id', true) : '';
            $this->media_model->get_media($limit, $offset, $sort, $order, $search, $type, $seller_id);
        }
    }


    public function get_seller_details()
    {
        /* Parameters to be passed
            id:28
        */
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $id = $this->input->post('id', true);
        $data = fetch_details('users', ['id' => $id]);
        foreach ($data as $row) {
            $row = output_escaping($row);
            $tempRow['id'] = (isset($row['id']) && !empty($row['id'])) ? $row['id'] : '';
            $tempRow['ip_address'] = (isset($row['ip_address']) && !empty($row['ip_address'])) ? $row['ip_address'] : '';
            $tempRow['username'] = (isset($row['username']) && !empty($row['username'])) ? $row['username'] : '';
            $tempRow['email'] = (isset($row['email']) && !empty($row['email'])) ? $row['email'] : '';
            $tempRow['mobile'] = (isset($row['mobile']) && !empty($row['mobile'])) ? $row['mobile'] : '';
            if (empty($row['image']) || file_exists(FCPATH . USER_IMG_PATH . $row['image']) == FALSE) {
                $tempRow['image'] = base_url() . NO_IMAGE;
            } else {
                $tempRow['image'] = base_url() . USER_IMG_PATH .  $row['image'];
            }
            $tempRow['balance'] = (isset($row['balance']) && !empty($row['balance'])) ? $row['balance'] : "0";
            $tempRow['activation_selector'] = (isset($row['activation_selector']) && !empty($row['activation_selector'])) ? $row['activation_selector'] : '';
            $tempRow['activation_code'] = (isset($row['activation_code']) && !empty($row['activation_code'])) ? $row['activation_code'] : '';
            $tempRow['forgotten_password_selector'] = (isset($row['forgotten_password_selector']) && !empty($row['forgotten_password_selector'])) ? $row['forgotten_password_selector'] : '';
            $tempRow['forgotten_password_code'] = (isset($row['forgotten_password_code']) && !empty($row['forgotten_password_code'])) ? $row['forgotten_password_code'] : '';
            $tempRow['forgotten_password_time'] = (isset($row['forgotten_password_time']) && !empty($row['forgotten_password_time'])) ? $row['forgotten_password_time'] : '';
            $tempRow['remember_selector'] = (isset($row['remember_selector']) && !empty($row['remember_selector'])) ? $row['remember_selector'] : '';
            $tempRow['remember_code'] = (isset($row['remember_code']) && !empty($row['remember_code'])) ? $row['remember_code'] : '';
            $tempRow['created_on'] = (isset($row['created_on']) && !empty($row['created_on'])) ? $row['created_on'] : '';
            $tempRow['last_login'] = (isset($row['last_login']) && !empty($row['last_login'])) ? $row['last_login'] : '';
            $tempRow['active'] = (isset($row['active']) && !empty($row['active'])) ? $row['active'] : '';
            $tempRow['company'] = (isset($row['company']) && !empty($row['company'])) ? $row['company'] : '';
            $tempRow['address'] = (isset($row['address']) && !empty($row['address'])) ? $row['address'] : '';
            $tempRow['bonus'] = (isset($row['bonus']) && !empty($row['bonus'])) ? $row['bonus'] : '';
            $tempRow['cash_received'] = (isset($row['cash_received']) && !empty($row['cash_received'])) ? $row['cash_received'] : "0.00";
            $tempRow['dob'] = (isset($row['dob']) && !empty($row['dob'])) ? $row['dob'] : '';
            $tempRow['country_code'] = (isset($row['country_code']) && !empty($row['country_code'])) ? $row['country_code'] : '';
            $tempRow['city'] = (isset($row['city']) && !empty($row['city'])) ? $row['city'] : '';
            $tempRow['area'] = (isset($row['area']) && !empty($row['area'])) ? $row['area'] : '';
            $tempRow['street'] = (isset($row['street']) && !empty($row['street'])) ? $row['street'] : '';
            $tempRow['pincode'] = (isset($row['pincode']) && !empty($row['pincode'])) ? $row['pincode'] : '';
            $tempRow['apikey'] = (isset($row['apikey']) && !empty($row['apikey'])) ? $row['apikey'] : '';
            $tempRow['referral_code'] = (isset($row['referral_code']) && !empty($row['referral_code'])) ? $row['referral_code'] : '';
            $tempRow['friends_code'] = (isset($row['friends_code']) && !empty($row['friends_code'])) ? $row['friends_code '] : '';
            $tempRow['fcm_id'] = (isset($row['fcm_id']) && !empty($row['fcm_id'])) ? $row['fcm_id'] : '';
            $tempRow['latitude'] = (isset($row['latitude']) && !empty($row['latitude'])) ? $row['latitude'] : '';
            $tempRow['longitude'] = (isset($row['longitude']) && !empty($row['longitude'])) ? $row['longitude'] : '';
            $tempRow['created_at'] = (isset($row['created_at']) && !empty($row['created_at'])) ? $row['created_at'] : '';
            $rows[] = $tempRow;
        }
        $seller_data = fetch_details('seller_data', ['user_id' => $id]);
        $data = array_values(array_merge($rows, $seller_data));
        for ($i = 0; $i < count($seller_data); $i++) {
            $seller_data[$i]['logo'] = base_url() . $seller_data[$i]['logo'];
            $seller_data[$i]['national_identity_card'] = base_url() . $seller_data[$i]['national_identity_card'];
            $seller_data[$i]['address_proof'] = base_url() . $seller_data[$i]['address_proof'];
            $seller_data[$i]['permissions'] = json_decode($seller_data[$i]['permissions'], true);
        }
        $out = array();
        foreach ($data as $key => $value) {
            $out[] = (array)array_merge((array)$seller_data[$key], (array)$value);
        }
        unset($out[0]['password']);
        unset($out[1]);

        $response['error'] = false;
        $response['message'] = 'Data retrived successfully';
        $response['data'] = $out;
        print_r(json_encode($response));
        return false;
    }

    public function update_user()
    {
        /*
            id:34  {seller_id}
            name:hiten
            mobile:7852347890
            email:amangoswami@gmail.com	
            old:12345                       //{if want to change password}
            new:345234                      //{if want to change password}
            address:test
            store_name:storename
            store_url:url
            store_description:test
            account_number:123esdf
            account_name:name
            bank_code:INBsha23
            bank_name:bank name
            latitude:+37648
            longitude:-478237
            tax_name:GST
            tax_number:GSTIN6786
            pan_number:GNU876
            status:1 | 0                  //{1: active | 0:deactive}
            store_logo: file              // {pass if want to change}
            national_identity_card: file              // {pass if want to change}
            address_proof: file              // {pass if want to change}

        */
        if (!$this->verify_token()) {
            return false;
        }

        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->response['error'] = true;
            $this->response['message'] = DEMO_VERSION_MSG;
            echo json_encode($this->response);
            return false;
            exit();
        }

        $identity_column = $this->config->item('identity', 'ion_auth');
        $identity = $this->session->userdata('identity');
        $user = $this->ion_auth->user()->row();
        if ($identity_column == 'email') {
            $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
        } else {
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|xss_clean|trim|numeric');
        }
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Mail', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]');
        if (!empty($_POST['old']) || !empty($_POST['new'])) {
            $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
            $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
        }
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('store_name', 'Store Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tax_name', 'Tax Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tax_number', 'Tax Number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');

        if (!isset($_POST['id'])) {
            $this->form_validation->set_rules('store_logo', 'Store Logo', 'trim|xss_clean');
            $this->form_validation->set_rules('national_identity_card', 'National Identity Card', 'trim|xss_clean');
            $this->form_validation->set_rules('address_proof', 'Address Proof', 'trim|xss_clean');
        }

        if (!$this->form_validation->run()) {

            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            print_r(json_encode($this->response));
        } else {
            $id = $this->input->post('id', true);
            $seller_data_id = fetch_details('seller_data', ['user_id' => $id], 'id,address_proof,national_identity_card,logo');

            // process images of seller

            if (!file_exists(FCPATH . SELLER_DOCUMENTS_PATH)) {
                mkdir(FCPATH . SELLER_DOCUMENTS_PATH, 0777);
            }

            //process store logo
            $temp_array_logo = $store_logo_doc = array();
            $logo_files = $_FILES;
            $store_logo_error = "";
            $config = [
                'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 8000,
            ];
            if (isset($logo_files['store_logo']) && !empty($logo_files['store_logo']['name']) && isset($logo_files['store_logo']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if (isset($_POST['id']) && !empty($_POST['id']) && isset($seller_data_id[0]['logo']) && !empty($seller_data_id[0]['logo'])) {
                    $old_logo = explode('/', $seller_data_id[0]['logo']);
                    delete_images(SELLER_DOCUMENTS_PATH, $old_logo[2]);
                }

                if (!empty($logo_files['store_logo']['name'])) {

                    $_FILES['temp_image']['name'] = $logo_files['store_logo']['name'];
                    $_FILES['temp_image']['type'] = $logo_files['store_logo']['type'];
                    $_FILES['temp_image']['tmp_name'] = $logo_files['store_logo']['tmp_name'];
                    $_FILES['temp_image']['error'] = $logo_files['store_logo']['error'];
                    $_FILES['temp_image']['size'] = $logo_files['store_logo']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $store_logo_error = 'Images :' . $store_logo_error . ' ' . $other_img->display_errors();
                    } else {
                        $temp_array_logo = $other_img->data();
                        resize_review_images($temp_array_logo, FCPATH . SELLER_DOCUMENTS_PATH);
                        $store_logo_doc  = SELLER_DOCUMENTS_PATH . $temp_array_logo['file_name'];
                    }
                } else {
                    $_FILES['temp_image']['name'] = $logo_files['store_logo']['name'];
                    $_FILES['temp_image']['type'] = $logo_files['store_logo']['type'];
                    $_FILES['temp_image']['tmp_name'] = $logo_files['store_logo']['tmp_name'];
                    $_FILES['temp_image']['error'] = $logo_files['store_logo']['error'];
                    $_FILES['temp_image']['size'] = $logo_files['store_logo']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $store_logo_error = $other_img->display_errors();
                    }
                }
                //Deleting Uploaded Images if any overall error occured
                if ($store_logo_error != NULL || !$this->form_validation->run()) {
                    if (isset($store_logo_doc) && !empty($store_logo_doc || !$this->form_validation->run())) {
                        foreach ($store_logo_doc as $key => $val) {
                            unlink(FCPATH . SELLER_DOCUMENTS_PATH . $store_logo_doc[$key]);
                        }
                    }
                }
            }

            if ($store_logo_error != NULL) {
                $this->response['error'] = true;


                $this->response['message'] =  $store_logo_error;
                print_r(json_encode($this->response));
                return;
            }

            //process national_identity_card
            $temp_array_id_card = $id_card_doc = array();
            $id_card_files = $_FILES;
            $id_card_error = "";
            $config = [
                'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 8000,
            ];
            if (isset($id_card_files['national_identity_card']) &&  !empty($id_card_files['national_identity_card']['name']) && isset($id_card_files['national_identity_card']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if (isset($_POST['id']) && !empty($_POST['id']) && isset($seller_data_id[0]['national_identity_card']) && !empty($seller_data_id[0]['national_identity_card'])) {
                    $old_national_identity_card = explode('/', $seller_data_id[0]['national_identity_card']);
                    delete_images(SELLER_DOCUMENTS_PATH, $old_national_identity_card[2]);
                }

                if (!empty($id_card_files['national_identity_card']['name'])) {

                    $_FILES['temp_image']['name'] = $id_card_files['national_identity_card']['name'];
                    $_FILES['temp_image']['type'] = $id_card_files['national_identity_card']['type'];
                    $_FILES['temp_image']['tmp_name'] = $id_card_files['national_identity_card']['tmp_name'];
                    $_FILES['temp_image']['error'] = $id_card_files['national_identity_card']['error'];
                    $_FILES['temp_image']['size'] = $id_card_files['national_identity_card']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $id_card_error = 'Images :' . $id_card_error . ' ' . $other_img->display_errors();
                    } else {
                        $temp_array_id_card = $other_img->data();
                        resize_review_images($temp_array_id_card, FCPATH . SELLER_DOCUMENTS_PATH);
                        $id_card_doc  = SELLER_DOCUMENTS_PATH . $temp_array_id_card['file_name'];
                    }
                } else {
                    $_FILES['temp_image']['name'] = $id_card_files['national_identity_card']['name'];
                    $_FILES['temp_image']['type'] = $id_card_files['national_identity_card']['type'];
                    $_FILES['temp_image']['tmp_name'] = $id_card_files['national_identity_card']['tmp_name'];
                    $_FILES['temp_image']['error'] = $id_card_files['national_identity_card']['error'];
                    $_FILES['temp_image']['size'] = $id_card_files['national_identity_card']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $id_card_error = $other_img->display_errors();
                    }
                }
                //Deleting Uploaded Images if any overall error occured
                if ($id_card_error != NULL || !$this->form_validation->run()) {
                    if (isset($id_card_doc) && !empty($id_card_doc || !$this->form_validation->run())) {
                        foreach ($id_card_doc as $key => $val) {
                            unlink(FCPATH . SELLER_DOCUMENTS_PATH . $id_card_doc[$key]);
                        }
                    }
                }
            }

            if ($id_card_error != NULL) {
                $this->response['error'] = true;
                $this->response['message'] =  $id_card_error;
                print_r(json_encode($this->response));
                return;
            }

            //process address_proof
            $temp_array_proof = $proof_doc = array();
            $proof_files = $_FILES;
            $proof_error = "";
            $config = [
                'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 8000,
            ];
            if (isset($proof_files['address_proof']) && !empty($proof_files['address_proof']['name']) && isset($proof_files['address_proof']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if (isset($_POST['id']) && !empty($_POST['id']) && isset($seller_data_id[0]['address_proof']) && !empty($seller_data_id[0]['address_proof'])) {
                    $old_address_proof = explode('/', $seller_data_id[0]['address_proof']);
                    delete_images(SELLER_DOCUMENTS_PATH, $old_address_proof[2]);
                }

                if (!empty($proof_files['address_proof']['name'])) {

                    $_FILES['temp_image']['name'] = $proof_files['address_proof']['name'];
                    $_FILES['temp_image']['type'] = $proof_files['address_proof']['type'];
                    $_FILES['temp_image']['tmp_name'] = $proof_files['address_proof']['tmp_name'];
                    $_FILES['temp_image']['error'] = $proof_files['address_proof']['error'];
                    $_FILES['temp_image']['size'] = $proof_files['address_proof']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $proof_error = 'Images :' . $proof_error . ' ' . $other_img->display_errors();
                    } else {
                        $temp_array_proof = $other_img->data();
                        resize_review_images($temp_array_proof, FCPATH . SELLER_DOCUMENTS_PATH);
                        $proof_doc  = SELLER_DOCUMENTS_PATH . $temp_array_proof['file_name'];
                    }
                } else {
                    $_FILES['temp_image']['name'] = $proof_files['address_proof']['name'];
                    $_FILES['temp_image']['type'] = $proof_files['address_proof']['type'];
                    $_FILES['temp_image']['tmp_name'] = $proof_files['address_proof']['tmp_name'];
                    $_FILES['temp_image']['error'] = $proof_files['address_proof']['error'];
                    $_FILES['temp_image']['size'] = $proof_files['address_proof']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $proof_error = $other_img->display_errors();
                    }
                }
                //Deleting Uploaded Images if any overall error occured
                if ($proof_error != NULL || !$this->form_validation->run()) {
                    if (isset($proof_doc) && !empty($proof_doc || !$this->form_validation->run())) {
                        foreach ($proof_doc as $key => $val) {
                            unlink(FCPATH . SELLER_DOCUMENTS_PATH . $proof_doc[$key]);
                        }
                    }
                }
            }

            if ($proof_error != NULL) {
                $this->response['error'] = true;
                $this->response['message'] =  $proof_error;
                print_r(json_encode($this->response));
                return;
            }


            if (isset($_POST['id'])) {
                $seller_data = array(
                    'user_id' => $id,
                    'edit_seller_data_id' => $seller_data_id[0]['id'],
                    'address_proof' => (!empty($proof_doc)) ? $proof_doc : $seller_data_id[0]['address_proof'],
                    'national_identity_card' => (!empty($id_card_doc)) ? $id_card_doc : $seller_data_id[0]['national_identity_card'],
                    'store_logo' => (!empty($store_logo_doc)) ? $store_logo_doc : $seller_data_id[0]['logo'],
                    'status' => $this->input->post('status', true),
                    'pan_number' => $this->input->post('pan_number', true),
                    'tax_number' => $this->input->post('tax_number', true),
                    'tax_name' => $this->input->post('tax_name', true),
                    'bank_name' => $this->input->post('bank_name', true),
                    'bank_code' => $this->input->post('bank_code', true),
                    'account_name' => $this->input->post('account_name', true),
                    'account_number' => $this->input->post('account_number', true),
                    'store_description' => $this->input->post('store_description', true),
                    'store_url' => $this->input->post('store_url', true),
                    'store_name' => $this->input->post('store_name', true),
                    'categories' => 'seller_profile',
                );

                if (!empty($_POST['old']) || !empty($_POST['new'])) {
                    $identity = ($identity_column == 'mobile') ? 'mobile' : 'email';
                    $res = fetch_details('users', ['id' => $id], $identity);
                    if (!empty($res)) {
                        if (!$this->ion_auth->change_password($res[0][$identity], $this->input->post('old'), $this->input->post('new'))) {

                            // if the login was un-successful
                            $response['error'] = true;
                            $response['message'] = strip_tags($this->ion_auth->errors());
                            echo json_encode($response);
                            return;
                        } else {
                            $data = fetch_details('users', ['id' => $id]);
                            $seller_data = fetch_details('seller_data', ['user_id' => $id]);
                            $data = array_values(array_merge($data, $seller_data));
                            for ($i = 0; $i < count($seller_data); $i++) {
                                $seller_data[$i]['logo'] = base_url() . $seller_data[$i]['logo'];
                                $seller_data[$i]['national_identity_card'] = base_url() . $seller_data[$i]['national_identity_card'];
                                $seller_data[$i]['address_proof'] = base_url() . $seller_data[$i]['address_proof'];
                                $seller_data[$i]['permissions'] = json_decode($seller_data[$i]['permissions'], true);
                            }
                            $out = array();
                            foreach ($data as $key => $value) {
                                $out[] = (array)array_merge((array)$seller_data[$key], (array)$value);
                            }
                            unset($out[0]['password']);
                            unset($out[1]);
                            $response['error'] = false;
                            $response['message'] = 'Password Update Succesfully';
                            $response['data'] = $out;
                            echo json_encode($response);
                            return;
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'User not exists';
                        echo json_encode($response);
                        return;
                    }
                }
                $seller_profile = array(
                    'name' => $this->input->post('name', true),
                    'email' => $this->input->post('email', true),
                    'mobile' => $this->input->post('mobile', true),
                    'address' => $this->input->post('address', true),
                    'latitude' => $this->input->post('latitude', true),
                    'longitude' => $this->input->post('longitude', true)
                );

                if ($this->Seller_model->add_seller($seller_data, $seller_profile)) {
                    $data = fetch_details('users', ['id' => $id]);
                    $seller_data = fetch_details('seller_data', ['user_id' => $id]);
                    $data = array_values(array_merge($data, $seller_data));
                    for ($i = 0; $i < count($seller_data); $i++) {
                        $seller_data[$i]['logo'] = base_url() . $seller_data[$i]['logo'];
                        $seller_data[$i]['national_identity_card'] = base_url() . $seller_data[$i]['national_identity_card'];
                        $seller_data[$i]['address_proof'] = base_url() . $seller_data[$i]['address_proof'];
                        $seller_data[$i]['permissions'] = json_decode($seller_data[$i]['permissions'], true);
                    }
                    $out = array();
                    foreach ($data as $key => $value) {
                        $out[] = (array)array_merge((array)$seller_data[$key], (array)$value);
                    }
                    unset($out[0]['password']);
                    unset($out[1]);
                    $this->response['error'] = false;
                    $message = 'Seller Update Successfully';
                    $this->response['message'] = $message;
                    $this->response['data'] = $out;
                    print_r(json_encode($this->response));
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = "Seller data was not updated";
                    print_r(json_encode($this->response));
                }
            }
        }
    }

    public function delete_product()
    {
        /* Parameters to be passed
            product_id:28
        */
        if (!$this->verify_token()) {
            return false;
        }

        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->response['error'] = true;
            $this->response['message'] = DEMO_VERSION_MSG;
            echo json_encode($this->response);
            return false;
            exit();
        }
        $this->form_validation->set_rules('product_id', 'Product Id', 'trim|required|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $id = $this->input->post('product_id', true);
        if (delete_details(['product_id' => $id], 'product_variants')) {

            delete_details(['id' => $id], 'products');
            delete_details(['product_id' => $id], 'product_attributes');
            $response['error'] = false;
            $response['message'] = 'Deleted Succesfully';
        } else {
            $response['error'] = true;
            $response['message'] = 'Something Went Wrong';
        }
        print_r(json_encode($response));
    }

    public function update_products()
    {

        /*
            edit_product_id:74
            edit_variant_id:104,105
            variants_ids: new created with new attributes added


            seller_id:1255
            
            pro_input_name: product name
            short_description: description
            tags:tag1,tag2,tag3     //{comma saprated}
            pro_input_tax:tax_id
            indicator:1             //{ 0 - none | 1 - veg | 2 - non-veg }
            made_in: india          //{optional}
            hsn_code: 123456         //{optional}
            brand: adidas          //{optional}
            total_allowed_quantity:100
            minimum_order_quantity:12
            quantity_step_size:1
            warranty_period:1 month
            guarantee_period:1 month
            deliverable_type:1        //{0:none, 1:all, 2:include, 3:exclude}
            deliverable_zipcodes:1,2,3  //{NULL: if deliverable_type = 0 or 1}
            is_prices_inclusive_tax:0   //{1: inclusive | 0: exclusive}
            cod_allowed:1               //{ 1:allowed | 0:not-allowed }
            download_allowed:1               //{ 1:allowed | 0:not-allowed }
            download_link_type:self_hosted             //{ values : self_hosted | add_link }
            pro_input_zip:file              //when download type is self_hosted add file for download
            download_link : url             //{URL of download file}
            is_returnable:1             // { 1:returnable | 0:not-returnable } 
            is_cancelable:1             //{1:cancelable | 0:not-cancelable}
            cancelable_till:            //{received,processed,shipped}
            pro_input_image:file  
            other_images: files
            video_type:                 // {values: vimeo | youtube}
            video:                      //{URL of video}
            pro_input_video: file
            pro_input_description:product's description
            category_id:99
            attribute_values:1,2,3,4,5
            status :1/0 {optional}
            --------------------------------------------------------------------------------
            till above same params
            --------------------------------------------------------------------------------
            --------------------------------------------------------------------------------
            common param for simple and variable product
            --------------------------------------------------------------------------------          
            product_type:simple_product | variable_product  
            variant_stock_level_type:product_level | variable_level
            
            if(product_type == variable_product):
                variants_ids:3 5,4 5,1 2
                variant_price:100,200
                variant_special_price:90,190
                variant_images:files              //{optional}

                sku_variant_type:test            //{if (variant_stock_level_type == product_level)}
                total_stock_variant_type:100     //{if (variant_stock_level_type == product_level)}
                variant_status:1                 //{if (variant_stock_level_type == product_level)}

                variant_sku:test,test             //{if(variant_stock_level_type == variable_level)}
                variant_total_stock:120,300       //{if(variant_stock_level_type == variable_level)}
                variant_level_stock_status:1,1    //{if(variant_stock_level_type == variable_level)}

            if(product_type == simple_product):
                simple_product_stock_status:null|0|1   {1=in stock | 0=out stock}
                simple_price:100
                simple_special_price:90
                product_sku:test
                product_total_stock:100
                variant_stock_status: 0            //{0 =>'Simple_Product_Stock_Active' 1 => "Product_Level" 2 => "Variable_Level"	}
            if(product_type == digital_product):
                simple_price:100
                simple_special_price:90
       */
        if (!$this->verify_token()) {
            return false;
        }

        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->response['error'] = true;
            $this->response['message'] = DEMO_VERSION_MSG;
            echo json_encode($this->response);
            return false;
            exit();
        }
        $this->form_validation->set_rules('seller_id', 'Seller Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('edit_product_id', 'Edit Product Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('pro_input_name', 'Product Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('short_description', 'Short Description', 'trim|required|xss_clean');
        $this->form_validation->set_rules('category_id', 'Category Id', 'trim|required|xss_clean', array('required' => 'Category is required'));
        $this->form_validation->set_rules('pro_input_tax', 'Tax', 'trim|xss_clean');
        $this->form_validation->set_rules('image', 'Image', 'trim|xss_clean', array('required' => 'Image is required'));
        $this->form_validation->set_rules('other_image', 'Other Image', 'trim|xss_clean');
        $this->form_validation->set_rules('made_in', 'Made In', 'trim|xss_clean');
        $this->form_validation->set_rules('hsn_code', 'HSN_Code', 'trim|xss_clean');
        $this->form_validation->set_rules('brand', 'Brand', 'trim|xss_clean');
        $this->form_validation->set_rules('product_type', 'Product type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('total_allowed_quantity', 'Total Allowed Quantity', 'trim|xss_clean');
        $this->form_validation->set_rules('minimum_order_quantity', 'Minimum Order Quantity', 'trim|xss_clean');
        $this->form_validation->set_rules('quantity_step_size', 'Quantity Step Size', 'trim|xss_clean');
        $this->form_validation->set_rules('warranty_period', 'Warranty Period', 'trim|xss_clean');
        $this->form_validation->set_rules('guarantee_period', 'Guarantee Period', 'trim|xss_clean');
        $this->form_validation->set_rules('video', 'Video', 'trim|xss_clean');
        $this->form_validation->set_rules('video_type', 'Video Type', 'trim|xss_clean');
        if (isset($_POST['product_type']) && $_POST['product_type'] == 'simple_product' || $_POST['product_type'] == 'variable_product') {
            $this->form_validation->set_rules('deliverable_type', 'Deliverable Type', 'required|trim|xss_clean');
        }

        if (isset($_POST['video_type']) && $_POST['video_type'] != '') {
            if ($_POST['video_type'] == 'youtube' || $_POST['video_type'] == 'vimeo') {
                $this->form_validation->set_rules('video', 'Video link', 'trim|required|xss_clean', array('required' => " Please paste a %s in the input box. "));
            } else {
                $this->form_validation->set_rules('pro_input_video', 'Video file', 'trim|required|xss_clean', array('required' => " Please choose a %s to be set. "));
            }
        }

        if (isset($_POST['download_allowed']) && $_POST['download_allowed'] != '' && !empty($_POST['download_allowed']) && $_POST['download_allowed'] == '1') {
            $this->form_validation->set_rules('download_link_type', 'Download Link Type', 'required|xss_clean');
            if (isset($_POST['download_link_type']) && $_POST['download_link_type'] != '' && !empty($_POST['download_link_type']) && $_POST['download_link_type'] == 'self_hosted') {
                $this->form_validation->set_rules('pro_input_zip', 'pro_input_zip', 'required|xss_clean');
            }
            if (isset($_POST['download_link_type']) && $_POST['download_link_type'] != '' && !empty($_POST['download_link_type']) && $_POST['download_link_type'] == 'add_link') {
                $this->form_validation->set_rules('download_link', 'Digital Product URL/Link', 'required|xss_clean');
            }
        }
        $_POST['variant_price'] = (isset($_POST['variant_price']) && !empty($_POST['variant_price'])) ?  explode(",", $this->input->post('variant_price', true)) : NULL;
        $_POST['variant_special_price'] = (isset($_POST['variant_special_price']) && !empty($_POST['variant_special_price'])) ?  explode(",", $this->input->post('variant_special_price', true)) : NULL;
        $_POST['variants_ids'] = (isset($_POST['variants_ids']) && !empty($_POST['variants_ids'])) ?  explode(",", $this->input->post('variants_ids', true)) : NULL;
        $_POST['variant_sku'] = (isset($_POST['variant_sku']) && !empty($_POST['variant_sku'])) ?  explode(",", $this->input->post('variant_sku', true)) : NULL;
        $_POST['variant_total_stock'] = (isset($_POST['variant_total_stock']) && !empty($_POST['variant_total_stock'])) ?  explode(",", $this->input->post('variant_total_stock', true)) : NULL;
        $_POST['variant_level_stock_status'] = (isset($_POST['variant_level_stock_status']) && !empty($_POST['variant_level_stock_status'])) ?  explode(",", $this->input->post('variant_level_stock_status', true)) : NULL;
        $_POST['other_images'] = (isset($_POST['other_images']) && !empty($_POST['other_images'])) ? explode(",", $this->input->post('other_images', true)) : [];
        $_POST['variant_images'] = (isset($_POST['variant_images']) && !empty($_POST['variant_images'])) ? json_decode($_POST['variant_images'], true) : [];
        $_POST['edit_variant_id'] = (isset($_POST['edit_variant_id']) && !empty($_POST['edit_variant_id'])) ? explode(",", $this->input->post('edit_variant_id', true)) : [];
        $edit_status = fetch_details('products', ['id' => $_POST['edit_product_id']], 'status');
        $require_products_approval = $edit_status[0]['status'];
        $_POST['status'] = (isset($_POST['status']) && ($_POST['status'] != '')) ? $this->input->post('status', true) : $require_products_approval;


        if (isset($_POST['is_cancelable']) && $_POST['is_cancelable'] == '1') {
            $this->form_validation->set_rules('cancelable_till', 'Till which status', 'trim|required|xss_clean|in_list[received,processed,shipped]');
        }

        if (isset($_POST['cod_allowed'])) {
            $this->form_validation->set_rules('cod_allowed', 'COD allowed', 'trim|xss_clean');
        }
        if (isset($_POST['is_prices_inclusive_tax'])) {
            $this->form_validation->set_rules('is_prices_inclusive_tax', 'Tax included in prices', 'trim|xss_clean');
        }
        if ($_POST['deliverable_type'] == INCLUDED || $_POST['deliverable_type'] == EXCLUDED) {
            $this->form_validation->set_rules('deliverable_zipcodes[]', 'Deliverable Zipcodes', 'trim|required|xss_clean');
        }

        // If product type is simple			
        if (isset($_POST['product_type']) && $_POST['product_type'] == 'simple_product') {

            $this->form_validation->set_rules('simple_price', 'Price', 'trim|required|numeric|greater_than_equal_to[' . $this->input->post('simple_special_price') . ']|xss_clean');
            $this->form_validation->set_rules('simple_special_price', 'Special Price', 'trim|numeric|less_than_equal_to[' . $this->input->post('simple_price') . ']|xss_clean');


            if (isset($_POST['simple_product_stock_status']) && in_array($_POST['simple_product_stock_status'], array('0', '1'))) {

                $this->form_validation->set_rules('product_sku', 'SKU', 'trim|xss_clean');
                $this->form_validation->set_rules('product_total_stock', 'Total Stock', 'trim|required|numeric|xss_clean');
                $this->form_validation->set_rules('simple_product_stock_status', 'Stock Status', 'trim|required|numeric|xss_clean');
            }
        } elseif (isset($_POST['product_type']) && $_POST['product_type'] == 'variable_product') { //If product type is variant	
            if (isset($_POST['variant_stock_status']) && $_POST['variant_stock_status'] == '0') {
                if ($_POST['variant_stock_level_type'] == "product_level") {

                    $this->form_validation->set_rules('sku_pro_type', 'SKU', 'trim|xss_clean');
                    $this->form_validation->set_rules('total_stock_variant_type', 'Total Stock', 'trim|required|xss_clean');
                    $this->form_validation->set_rules('variant_stock_status', 'Stock Status', 'trim|required|xss_clean');
                    if (isset($_POST['variant_price']) && isset($_POST['variant_special_price'])) {
                        foreach ($_POST['variant_price'] as $key => $value) {
                            $this->form_validation->set_rules('variant_price[' . $key . ']', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price[' . $key . ']') . ']');
                            $this->form_validation->set_rules('variant_special_price[' . $key . ']', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price[' . $key . ']') . ']');
                        }
                    } else {
                        $this->form_validation->set_rules('variant_price', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price') . ']');
                        $this->form_validation->set_rules('variant_special_price', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price') . ']');
                    }
                } else {
                    if (isset($_POST['variant_price']) && isset($_POST['variant_special_price']) && isset($_POST['variant_sku']) && isset($_POST['variant_total_stock']) && isset($_POST['variant_stock_status'])) {
                        foreach ($_POST['variant_price'] as $key => $value) {
                            $this->form_validation->set_rules('variant_price[' . $key . ']', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price[' . $key . ']') . ']');
                            $this->form_validation->set_rules('variant_special_price[' . $key . ']', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price[' . $key . ']') . ']');
                            $this->form_validation->set_rules('variant_sku[' . $key . ']', 'SKU', 'trim|xss_clean');
                            $this->form_validation->set_rules('variant_total_stock[' . $key . ']', 'Total Stock', 'trim|required|numeric|xss_clean');
                            $this->form_validation->set_rules('variant_level_stock_status[' . $key . ']', 'Stock Status', 'trim|required|numeric|xss_clean');
                        }
                    } else {
                        $this->form_validation->set_rules('variant_price', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price') . ']');
                        $this->form_validation->set_rules('variant_special_price', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price') . ']');
                        $this->form_validation->set_rules('variant_sku', 'SKU', 'trim|xss_clean');
                        $this->form_validation->set_rules('variant_total_stock', 'Total Stock', 'trim|required|numeric|xss_clean');
                        $this->form_validation->set_rules('variant_level_stock_status', 'Stock Status', 'trim|required|numeric|xss_clean');
                    }
                }
            } else {
                if (isset($_POST['variant_price']) && isset($_POST['variant_special_price'])) {
                    foreach ($_POST['variant_price'] as $key => $value) {
                        $this->form_validation->set_rules('variant_price[' . $key . ']', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price[' . $key . ']') . ']');
                        $this->form_validation->set_rules('variant_special_price[' . $key . ']', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price[' . $key . ']') . ']');
                    }
                } else {
                    $this->form_validation->set_rules('variant_price', 'Price', 'trim|required|numeric|xss_clean|greater_than_equal_to[' . $this->input->post('variant_special_price') . ']');
                    $this->form_validation->set_rules('variant_special_price', 'Special Price', 'trim|numeric|xss_clean|less_than_equal_to[' . $this->input->post('variant_price') . ']');
                }
            }
        }
        if (!$this->form_validation->run()) {
            $response['error'] = true;
            $response['message'] = strip_tags(validation_errors());
            $response['data'] = array();
            echo json_encode($response);
        } else {

            $_POST['zipcodes'] = (!empty($_POST['deliverable_zipcodes'])) ?  $this->input->post('deliverable_zipcodes', true) : NULL;
            $this->Product_model->add_product($_POST);
            $response['error'] = false;
            $response['message'] = 'Product Update Successfully';
            echo json_encode($response);
            return;
        }
    }

    public function get_delivery_boys()
    {
        /*
            seller_id:1255
            id: 1001                // { optional}
            search : Search keyword // { optional }
            limit:25                // { default - 25 } optional
            offset:0                // { default - 0 } optional
            sort: id/username/email/mobile/area_name/city_name/date_created // { default - id } optional
            order:DESC/ASC          // { default - DESC } optional
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('id', 'ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('seller_id', 'Seller ID', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'Search keyword', 'trim|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            echo json_encode($this->response);
            return;
        } else {
            if (get_seller_permission($this->input->post('seller_id', true), 'assign_delivery_boy') == FALSE) {
                $this->response['error'] = true;
                $this->response['message'] = "You do not have permission to assign the delivery boy to orders.";
                $this->response['data'] = array();
                echo json_encode($this->response);
                return;
            }

            $id = (isset($_POST['id']) && is_numeric($_POST['id']) && !empty(trim($_POST['id']))) ? $this->input->post('id', true) : "";
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';
            $this->delivery_boy_model->get_delivery_boys($id, $search, $offset, $limit, $sort, $order);
        }
    }

    // register
    public function register()
    {
        if (!$this->verify_token()) {
            return false;
        }
        if (!isset($_POST['user_id'])) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('email', 'Mail', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required|matches[password]|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('user_name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('user_mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]');
        }
        $this->form_validation->set_rules('store_name', 'Store Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tax_name', 'Tax Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tax_number', 'Tax Number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('store_logo', 'Store Logo', 'trim|xss_clean');
        $this->form_validation->set_rules('national_identity_card', 'National Identity Card', 'trim|xss_clean');
        $this->form_validation->set_rules('address_proof', 'Address Proof', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            print_r(json_encode($this->response));
        } else {
            if (!file_exists(FCPATH . SELLER_DOCUMENTS_PATH)) {
                mkdir(FCPATH . SELLER_DOCUMENTS_PATH, 0777);
            }

            //process store logo
            $temp_array_logo = $store_logo_doc = array();
            $logo_files = $_FILES;
            $store_logo_error = "";
            $config = [
                'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 8000,
            ];
            if (isset($logo_files['store_logo']) && !empty($logo_files['store_logo']['name']) && isset($logo_files['store_logo']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if (isset($_POST['edit_seller']) && !empty($_POST['edit_seller']) && isset($_POST['old_store_logo']) && !empty($_POST['old_store_logo'])) {
                    $old_logo = explode('/', $this->input->post('old_store_logo', true));
                    delete_images(SELLER_DOCUMENTS_PATH, $old_logo[2]);
                }

                if (!empty($logo_files['store_logo']['name'])) {

                    $_FILES['temp_image']['name'] = $logo_files['store_logo']['name'];
                    $_FILES['temp_image']['type'] = $logo_files['store_logo']['type'];
                    $_FILES['temp_image']['tmp_name'] = $logo_files['store_logo']['tmp_name'];
                    $_FILES['temp_image']['error'] = $logo_files['store_logo']['error'];
                    $_FILES['temp_image']['size'] = $logo_files['store_logo']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $store_logo_error = 'Images :' . $store_logo_error . ' ' . $other_img->display_errors();
                    } else {
                        $temp_array_logo = $other_img->data();
                        resize_review_images($temp_array_logo, FCPATH . SELLER_DOCUMENTS_PATH);
                        $store_logo_doc  = SELLER_DOCUMENTS_PATH . $temp_array_logo['file_name'];
                    }
                } else {
                    $_FILES['temp_image']['name'] = $logo_files['store_logo']['name'];
                    $_FILES['temp_image']['type'] = $logo_files['store_logo']['type'];
                    $_FILES['temp_image']['tmp_name'] = $logo_files['store_logo']['tmp_name'];
                    $_FILES['temp_image']['error'] = $logo_files['store_logo']['error'];
                    $_FILES['temp_image']['size'] = $logo_files['store_logo']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $store_logo_error = $other_img->display_errors();
                    }
                }
                //Deleting Uploaded Images if any overall error occured
                if ($store_logo_error != NULL || !$this->form_validation->run()) {
                    if (isset($store_logo_doc) && !empty($store_logo_doc || !$this->form_validation->run())) {
                        foreach ($store_logo_doc as $key => $val) {
                            unlink(FCPATH . SELLER_DOCUMENTS_PATH . $store_logo_doc[$key]);
                        }
                    }
                }
            }

            if ($store_logo_error != NULL) {
                $this->response['error'] = true;
                $this->response['message'] =  $store_logo_error;
                print_r(json_encode($this->response));
                return;
            }

            //process national_identity_card
            $temp_array_id_card = $id_card_doc = array();
            $id_card_files = $_FILES;
            $id_card_error = "";
            $config = [
                'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 8000,
            ];
            if (isset($id_card_files['national_identity_card']) &&  !empty($id_card_files['national_identity_card']['name']) && isset($id_card_files['national_identity_card']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if (isset($_POST['edit_seller']) && !empty($_POST['edit_seller']) && isset($_POST['old_national_identity_card']) && !empty($_POST['old_national_identity_card'])) {
                    $old_national_identity_card = explode('/', $this->input->post('old_national_identity_card', true));
                    delete_images(SELLER_DOCUMENTS_PATH, $old_national_identity_card[2]);
                }

                if (!empty($id_card_files['national_identity_card']['name'])) {

                    $_FILES['temp_image']['name'] = $id_card_files['national_identity_card']['name'];
                    $_FILES['temp_image']['type'] = $id_card_files['national_identity_card']['type'];
                    $_FILES['temp_image']['tmp_name'] = $id_card_files['national_identity_card']['tmp_name'];
                    $_FILES['temp_image']['error'] = $id_card_files['national_identity_card']['error'];
                    $_FILES['temp_image']['size'] = $id_card_files['national_identity_card']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $id_card_error = 'Images :' . $id_card_error . ' ' . $other_img->display_errors();
                    } else {
                        $temp_array_id_card = $other_img->data();
                        resize_review_images($temp_array_id_card, FCPATH . SELLER_DOCUMENTS_PATH);
                        $id_card_doc  = SELLER_DOCUMENTS_PATH . $temp_array_id_card['file_name'];
                    }
                } else {
                    $_FILES['temp_image']['name'] = $id_card_files['national_identity_card']['name'];
                    $_FILES['temp_image']['type'] = $id_card_files['national_identity_card']['type'];
                    $_FILES['temp_image']['tmp_name'] = $id_card_files['national_identity_card']['tmp_name'];
                    $_FILES['temp_image']['error'] = $id_card_files['national_identity_card']['error'];
                    $_FILES['temp_image']['size'] = $id_card_files['national_identity_card']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $id_card_error = $other_img->display_errors();
                    }
                }
                //Deleting Uploaded Images if any overall error occured
                if ($id_card_error != NULL || !$this->form_validation->run()) {
                    if (isset($id_card_doc) && !empty($id_card_doc || !$this->form_validation->run())) {
                        foreach ($id_card_doc as $key => $val) {
                            unlink(FCPATH . SELLER_DOCUMENTS_PATH . $id_card_doc[$key]);
                        }
                    }
                }
            }

            if ($id_card_error != NULL) {
                $this->response['error'] = true;
                $this->response['message'] =  $id_card_error;
                print_r(json_encode($this->response));
                return;
            }

            //process address_proof
            $temp_array_proof = $proof_doc = array();
            $proof_files = $_FILES;
            $proof_error = "";
            $config = [
                'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 8000,
            ];
            if (isset($proof_files['address_proof']) && !empty($proof_files['address_proof']['name']) && isset($proof_files['address_proof']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if (isset($_POST['edit_seller']) && !empty($_POST['edit_seller']) && isset($_POST['old_address_proof']) && !empty($_POST['old_address_proof'])) {
                    $old_address_proof = explode('/', $this->input->post('old_address_proof', true));
                    delete_images(SELLER_DOCUMENTS_PATH, $old_address_proof[2]);
                }

                if (!empty($proof_files['address_proof']['name'])) {

                    $_FILES['temp_image']['name'] = $proof_files['address_proof']['name'];
                    $_FILES['temp_image']['type'] = $proof_files['address_proof']['type'];
                    $_FILES['temp_image']['tmp_name'] = $proof_files['address_proof']['tmp_name'];
                    $_FILES['temp_image']['error'] = $proof_files['address_proof']['error'];
                    $_FILES['temp_image']['size'] = $proof_files['address_proof']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $proof_error = 'Images :' . $proof_error . ' ' . $other_img->display_errors();
                    } else {
                        $temp_array_proof = $other_img->data();
                        resize_review_images($temp_array_proof, FCPATH . SELLER_DOCUMENTS_PATH);
                        $proof_doc  = SELLER_DOCUMENTS_PATH . $temp_array_proof['file_name'];
                    }
                } else {
                    $_FILES['temp_image']['name'] = $proof_files['address_proof']['name'];
                    $_FILES['temp_image']['type'] = $proof_files['address_proof']['type'];
                    $_FILES['temp_image']['tmp_name'] = $proof_files['address_proof']['tmp_name'];
                    $_FILES['temp_image']['error'] = $proof_files['address_proof']['error'];
                    $_FILES['temp_image']['size'] = $proof_files['address_proof']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $proof_error = $other_img->display_errors();
                    }
                }
                //Deleting Uploaded Images if any overall error occured
                if ($proof_error != NULL || !$this->form_validation->run()) {
                    if (isset($proof_doc) && !empty($proof_doc || !$this->form_validation->run())) {
                        foreach ($proof_doc as $key => $val) {
                            unlink(FCPATH . SELLER_DOCUMENTS_PATH . $proof_doc[$key]);
                        }
                    }
                }
            }

            if ($proof_error != NULL) {
                $this->response['error'] = true;
                $this->response['message'] =  $proof_error;
                print_r(json_encode($this->response));
                return;
            }
            if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {

                /* check whether user exist or not */
                $user_id_to_seller = $this->input->post('user_id', true);
                $user = fetch_users($this->input->post('user_id', true));
                if (empty($user)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "User not found!";
                    $this->response['data'] = [];
                    print_r(json_encode($this->response));
                    return false;
                }
                $seller_data = array(
                    'user_id' => $this->input->post('user_id', true),
                    'address_proof' => (!empty($proof_doc)) ? $proof_doc : null,
                    'national_identity_card' => (!empty($id_card_doc)) ? $id_card_doc : null,
                    'store_logo' => (!empty($store_logo_doc)) ? $store_logo_doc : null,
                    'pan_number' => (isset($_POST['pan_number']) && !empty($_POST['pan_number'])) ? $this->input->post('pan_number', true) : "",
                    'tax_number' => $this->input->post('tax_number', true),
                    'tax_name' => $this->input->post('tax_name', true),
                    'bank_name' => (isset($_POST['bank_name']) && !empty($_POST['bank_name'])) ? $this->input->post('bank_name', true) : "",
                    'bank_code' => (isset($_POST['bank_code']) && !empty($_POST['bank_code'])) ? $this->input->post('bank_code', true) : "",
                    'account_name' => (isset($_POST['account_name']) && !empty($_POST['account_name'])) ? $this->input->post('account_name', true) : "",
                    'account_number' => (isset($_POST['account_number']) && !empty($_POST['account_number'])) ? $this->input->post('account_number', true) : "",
                    'store_description' => (isset($_POST['store_description']) && !empty($_POST['store_description'])) ? $this->input->post('store_description', true) : "",
                    'store_url' => (isset($_POST['store_url']) && !empty($_POST['store_url'])) ? $this->input->post('store_url', true) : "",
                    'store_name' => (isset($_POST['store_name']) && !empty($_POST['store_name'])) ? $this->input->post('store_name', true) : "",
                    'slug' => create_unique_slug($this->input->post('store_name', true), 'seller_data')
                );


                if ($this->Seller_model->add_seller($seller_data)) {
                    $group_id = $this->ion_auth->get_users_groups($user_id_to_seller)->row()->id;
                    $this->ion_auth->remove_from_group($group_id, $user_id_to_seller);
                    $this->ion_auth->add_to_group('4', $user_id_to_seller);
                    $this->response['error'] = false;

                    $message = 'Seller Update Successfully';
                    $this->response['message'] = $message;
                    print_r(json_encode($this->response));
                } else {
                    $this->response['error'] = true;

                    $this->response['message'] = "Seller data was not updated";
                    print_r(json_encode($this->response));
                }
            } else {

                if (!$this->form_validation->is_unique($_POST['mobile'], 'users.mobile') || !$this->form_validation->is_unique($_POST['email'], 'users.email')) {
                    $response["error"]   = true;
                    $response["message"] = "Email or mobile already exists !";

                    $response["data"] = array();
                    echo json_encode($response);
                    return false;
                }

                $identity_column = $this->config->item('identity', 'ion_auth');
                $email = strtolower($this->input->post('email'));
                $mobile = $this->input->post('mobile');
                $identity = ($identity_column == 'mobile') ? $mobile : $email;
                $password = $this->input->post('password');

                $additional_data = [
                    'username' => $this->input->post('name', true),
                    'address' => $this->input->post('address', true),
                ];
                $this->ion_auth->register($identity, $password, $email, $additional_data, ['4']);
                if (update_details(['active' => 1], [$identity_column => $identity], 'users')) {
                    $user_id = fetch_details('users', ['mobile' => $mobile], 'id');

                    $data = array(
                        'user_id' => $user_id[0]['id'],
                        'address_proof' => (!empty($proof_doc)) ? $proof_doc : null,
                        'national_identity_card' => (!empty($id_card_doc)) ? $id_card_doc : null,
                        'store_logo' => (!empty($store_logo_doc)) ? $store_logo_doc : null,
                        'pan_number' => (isset($_POST['pan_number']) && !empty($_POST['pan_number'])) ? $this->input->post('pan_number', true) : "",
                        'tax_number' => $this->input->post('tax_number', true),
                        'tax_name' => $this->input->post('tax_name', true),
                        'bank_name' => (isset($_POST['bank_name']) && !empty($_POST['bank_name'])) ? $this->input->post('bank_name', true) : "",
                        'bank_code' => (isset($_POST['bank_code']) && !empty($_POST['bank_code'])) ? $this->input->post('bank_code', true) : "",
                        'account_name' => (isset($_POST['account_name']) && !empty($_POST['account_name'])) ? $this->input->post('account_name', true) : "",
                        'account_number' => (isset($_POST['account_number']) && !empty($_POST['account_number'])) ? $this->input->post('account_number', true) : "",
                        'store_description' => (isset($_POST['store_description']) && !empty($_POST['store_description'])) ? $this->input->post('store_description', true) : "",
                        'store_url' => (isset($_POST['store_url']) && !empty($_POST['store_url'])) ? $this->input->post('store_url', true) : "",
                        'store_name' => (isset($_POST['store_name']) && !empty($_POST['store_name'])) ? $this->input->post('store_name', true) : "",
                        'slug' => create_unique_slug($this->input->post('store_name', true), 'seller_data')
                    );
                    $insert_id = $this->Seller_model->add_seller($data);
                    if (!empty($insert_id)) {
                        $this->response['error'] = false;
                        $this->response['message'] = 'Seller registared Successfully. Wait for aprooval of admin.';
                        print_r(json_encode($this->response));
                    } else {
                        $this->response['error'] = true;
                        $this->response['message'] = "Seller data was not added";
                        print_r(json_encode($this->response));
                    }
                } else {
                    $this->response['error'] = true;

                    $message = (isset($_POST['edit_seller'])) ? 'Seller not Updated' : 'Seller not Registared.';
                    $this->response['message'] = $message;
                    print_r(json_encode($this->response));
                }
            }
        }
    }

    //upload media

    public function upload_media()
    {
        $this->form_validation->set_rules('seller_id', 'Seller id', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            print_r(json_encode($this->response));
            return;
        } else {
            if (empty($_FILES['documents']['name'][0])) {
                $this->response['error'] = true;
                $this->response['message'] = "Upload at least one media file !";
                print_r(json_encode($this->response));
                return;
            }
            $year = date('Y');
            $target_path = FCPATH . MEDIA_PATH . $year . '/';
            $sub_directory = MEDIA_PATH . $year . '/';

            if (!file_exists($target_path)) {
                mkdir($target_path, 0777, true);
            }

            $temp_array = $media_ids = $other_images_new_name = array();
            $files = $_FILES;
            $other_image_info_error = "";
            $allowed_media_types = implode('|', allowed_media_types());
            $config['upload_path'] = $target_path;
            $config['allowed_types'] = $allowed_media_types;
            $other_image_cnt = count($_FILES['documents']['name']);
            $other_img = $this->upload;
            $other_img->initialize($config);
            for ($i = 0; $i < $other_image_cnt; $i++) {
                if (!empty($_FILES['documents']['name'][$i])) {
                    $_FILES['temp_image']['name'] = $files['documents']['name'][$i];
                    $_FILES['temp_image']['type'] = $files['documents']['type'][$i];
                    $_FILES['temp_image']['tmp_name'] = $files['documents']['tmp_name'][$i];
                    $_FILES['temp_image']['error'] = $files['documents']['error'][$i];
                    $_FILES['temp_image']['size'] = $files['documents']['size'][$i];
                    if (!$other_img->do_upload('temp_image')) {
                        $other_image_info_error = $other_image_info_error . ' ' . $other_img->display_errors();
                    } else {
                        $temp_array = $other_img->data();
                        $temp_array['sub_directory'] = $sub_directory;
                        $seller_id =  (isset($_POST['seller_id'])  && !empty(trim($_POST['seller_id']))) ? $this->input->post('seller_id', true) : 0;
                        $media_ids[] = $media_id = $this->media_model->set_media($temp_array, $seller_id); /* set media in database */
                        resize_image($temp_array,  $target_path, $media_id);
                        $other_images_new_name[$i] = $temp_array['file_name'];
                    }
                } else {

                    $_FILES['temp_image']['name'] = $files['documents']['name'][$i];
                    $_FILES['temp_image']['type'] = $files['documents']['type'][$i];
                    $_FILES['temp_image']['tmp_name'] = $files['documents']['tmp_name'][$i];
                    $_FILES['temp_image']['error'] = $files['documents']['error'][$i];
                    $_FILES['temp_image']['size'] = $files['documents']['size'][$i];
                    if (!$other_img->do_upload('temp_image')) {
                        $other_image_info_error = $other_img->display_errors();
                    }
                }
            }
            // Deleting Uploaded Images if any overall error occured
            if ($other_image_info_error != NULL) {
                if (isset($other_images_new_name) && !empty($other_images_new_name)) {
                    foreach ($other_images_new_name as $key => $val) {
                        unlink($target_path . $other_images_new_name[$key]);
                    }
                }
            }

            if (empty($_FILES) || $other_image_info_error != NULL) {
                $this->response['error'] = true;
                $this->response['message'] = (empty($_FILES)) ? "Files not Uploaded Successfully..!" :  $other_image_info_error;
                print_r(json_encode($this->response));
            } else {
                $this->response['error'] = false;
                $this->response['message'] = "Files Uploaded Successfully..!";
                print_r(json_encode($this->response));
            }
        }
    }
    public function get_product_rating()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('product_id', 'Product Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        }
        $limit = (isset($_POST['limit'])  && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
        $offset = (isset($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
        $sort = (isset($_POST['sort(array)']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'id';
        $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
        $has_images = (isset($_POST['has_images']) && !empty(trim($_POST['has_images']))) ? 1 : 0;

        // update category clicks
        $category_id = fetch_details('products', ['id' => $this->input->post('product_id', true)], 'category_id');
        $this->db->set('clicks', 'clicks+1', FALSE);
        $this->db->where('id', $category_id[0]['category_id']);
        $this->db->update('categories');

        $pr_rating = fetch_details('products', ['id' => $this->input->post('product_id', true)], 'rating');


        $rating = $this->rating_model->fetch_rating((isset($_POST['product_id'])) ? $_POST['product_id'] : '', (isset($_POST['user_id'])) ? $_POST['user_id'] : '', $limit, $offset, $sort, $order, '', $has_images);
        if (!empty($rating)) {
            $response['error'] = false;
            $response['message'] = 'Rating retrieved successfully';
            $response['no_of_rating'] = (!empty($rating['rating'][0]['no_of_rating'])) ? $rating['rating'][0]['no_of_rating'] : 0;
            $response['total'] = $rating['total_reviews'];
            $response['star_1'] = $rating['star_1'];
            $response['star_2'] = $rating['star_2'];
            $response['star_3'] = $rating['star_3'];
            $response['star_4'] = $rating['star_4'];
            $response['star_5'] = $rating['star_5'];
            $response['total_images'] = $rating['total_images'];
            $response['product_rating'] = (!empty($pr_rating)) ? $pr_rating[0]['rating'] : "0";
            $response['data'] = $rating['product_rating'];
        } else {
            $response['error'] = true;
            $response['message'] = 'No ratings found !';
            $response['no_of_rating'] = array();
            $response['data'] = array();
        }
        echo json_encode($response);
    }
    public function get_order_tracking()
    {
        /* 
        seller_id:1 
        limit:25            // { default - 25 } optional
        offset:0            // { default - 0 } optional
        sort:               // { id } optional
        order:DESC/ASC      // { default - DESC } optional
        search:value        // {optional} 
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('seller_id', 'Seller Id', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'id';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : '';
            $tmpRow = $rows = array();
            $data = $this->order_model->get_seller_order_tracking_list($limit, $offset, $sort, $order, $search);
            if (isset($data['data']) && !empty($data['data'])) {
                foreach ($data['data'] as $row) {
                    $tempRow['id'] = $row['id'];
                    $tempRow['order_id'] = $row['order_id'];
                    $tempRow['order_item_id'] = $row['order_item_id'];
                    $tempRow['courier_agency'] = $row['courier_agency'];
                    $tempRow['tracking_id'] = $row['tracking_id'];
                    $tempRow['url'] = $row['url'];
                    $tempRow['date'] = $row['date_created'];
                    $rows[] = $tempRow;
                }
                if ($data['error'] == false) {
                    $data['data'] = $rows;
                } else {
                    $data['data'] = array();
                }
            }
        }
    }

    public function edit_order_tracking()
    {
        /*
            order_item_id:57 
            courier_agency:asd agency
            tracking_id:t_id123
            url:http://test.com
        */


        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('courier_agency', 'Courier Agency', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tracking_id', 'Tracking Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('url', 'url', 'trim|required|xss_clean');
        $this->form_validation->set_rules('order_item_id', 'order Item Id', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $order_item_id = $this->input->post('order_item_id', true);
            $courier_agency = $this->input->post('courier_agency', true);
            $tracking_id = $this->input->post('tracking_id', true);
            $url = $this->input->post('url', true);
            $data = array(
                'order_item_id' => $order_item_id,
                'courier_agency' => $courier_agency,
                'tracking_id' => $tracking_id,
                'url' => $url,
            );
            if (is_exist(['order_item_id' => $order_item_id], 'order_tracking', null)) {
                if (update_details($data, ['order_item_id' => $order_item_id], 'order_tracking') == TRUE) {
                    $this->response['error'] = false;
                    $this->response['message'] = "Tracking details Update Successfuly.";
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = "Not Updated. Try again later.";
                }
            } else {
                if (insert_details($data, 'order_tracking')) {
                    $this->response['error'] = false;
                    $this->response['message'] = "Tracking details Insert Successfuly.";
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = "Not Inserted. Try again later.";
                }
            }
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        }
    }
    public function get_sales_list()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('seller_id', 'Seller Id', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'id';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : '';
            return $this->Invoice_model->get_seller_sales_list();
        }
    }

    public function update_product_status()
    {
        /*
            product_id:10
            status:1     {1: active | 0: de-active}
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('product_id', 'Product ID', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            echo json_encode($this->response);
            return;
        } else {
            $status = $this->input->post("status", true);
            $product_id = $this->input->post("product_id", true);
            if (update_details(['status' => $status], ['id' => $product_id], "products")) {
                $this->response['error'] = false;
                $this->response['message'] = "Status Updated Successfully";
                $this->response['data'] = [];
                echo json_encode($this->response);
                return;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Status not Updated.";
                $this->response['data'] = array();
                echo json_encode($this->response);
                return;
            }
        }
    }

    public function get_countries_data()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');
        $this->form_validation->set_rules('offset', 'Offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('limit', 'Limit', 'trim|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $offset = ($this->input->post('offset', true)) ? $this->input->post('offset', true) : 0;
            $limit = ($this->input->post('limit', true)) ? $this->input->post('limit', true) : 25;
            $result = $this->Product_model->get_country_list($search, $offset, $limit);
            print_r(json_encode($result));
        }
    }

    public function get_brands_data()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');
        $this->form_validation->set_rules('offset', 'Offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('limit', 'Limit', 'trim|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $offset = ($this->input->post('offset', true)) ? $this->input->post('offset', true) : 0;
            $limit = ($this->input->post('limit', true)) ? $this->input->post('limit', true) : 25;
            $result = $this->Product_model->get_brand_list($search, $offset, $limit);
            print_r(json_encode($result));
        }
    }

    /* add_product_faqs */
    public function add_product_faqs()
    {
        $this->form_validation->set_rules('product_id', 'Product Id', 'trim|numeric|xss_clean|required');
        $this->form_validation->set_rules('seller_id', 'Seller id', 'trim|numeric|xss_clean|required');
        $this->form_validation->set_rules('question', 'Question', 'trim|xss_clean|required');
        $this->form_validation->set_rules('answer', 'Answer', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            $product_id = $this->input->post('product_id', true);
            $user_id = $this->input->post('seller_id', true);
            $question = $this->input->post('question', true);
            $answer = $this->input->post('answer', true);
            $user = fetch_users($user_id);
            if (empty($user)) {
                $this->response['error'] = true;
                $this->response['message'] = "Seller not found!";
                $this->response['data'] = [];
                print_r(json_encode($this->response));
                return false;
            }
            $data = array(
                'product_id' => $product_id,
                'user_id' => $user_id,
                'question' => $question,
                'answer' => (isset($answer) && !empty($answer)) ? $answer : "",
                'answer_by' => (isset($answer) && !empty($answer)) ? $user_id : "",
            );

            $insert_id = $this->product_model->add_product_faqs($data);
            if (!empty($insert_id)) {
                $result = $this->product_model->get_product_faqs($insert_id, $product_id, $user_id);
                $this->response['error'] = false;
                $this->response['message'] =  'FAQS added Successfully';
                $this->response['data'] = $result['data'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] =  'FAQS Not Added';
                $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
            }
            print_r(json_encode($this->response));
        }
    }

    /*  get_product_faqs */
    public function get_product_faqs()
    {
        /*
            id:2    // {optional}
            product_id:25   // {optional}
            seller_id:1       // {optional}
            search : Search keyword // { optional }
            limit:25                // { default - 10 } optional
            offset:0                // { default - 0 } optional
            sort: id                // { default - id } optional
            order:DESC/ASC          // { default - DESC } optional
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('id', 'FAQs ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('product_id', 'Product ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('seller_id', 'Seller ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'Search keyword', 'trim|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $id = (isset($_POST['id']) && is_numeric($_POST['id']) && !empty(trim($_POST['id']))) ? $this->input->post('id', true) : "";
            $product_id = (isset($_POST['product_id']) && is_numeric($_POST['product_id']) && !empty(trim($_POST['product_id']))) ? $this->input->post('product_id', true) : "";
            $user_id = (isset($_POST['seller_id']) && is_numeric($_POST['seller_id']) && !empty(trim($_POST['seller_id']))) ? $this->input->post('seller_id', true) : "";
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 10;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';

            $result = $this->product_model->get_product_faqs($id, $product_id, $user_id, $search, $offset, $limit, $sort, $order, true);
            print_r(json_encode($result));
        }
    }

    public function delete_product_faq()
    {
        $this->form_validation->set_rules('id', 'FAQ id', 'trim|xss_clean|required');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $this->product_faqs_model->delete_faq($_POST['id']);

            $this->response['error'] = false;
            $this->response['message'] = 'FAQ Deleted Succesfully';

            print_r(json_encode($this->response));
        }
    }


    public function edit_product_faq()
    {
        $this->form_validation->set_rules('seller_id', 'Seller id', 'trim|xss_clean|required');
        $this->form_validation->set_rules('id', 'FAQ id', 'trim|xss_clean|required');
        $this->form_validation->set_rules('answer', 'Answer', 'trim|xss_clean|required');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $edit_data = [
                'answer' => $_POST['answer'],
                'answered_by' => $_POST['seller_id'],
            ];
            $this->product_faqs_model->edit_product_faqs($edit_data, $_POST['id']);

            $this->response['error'] = false;
            $this->response['message'] = 'FAQ Update Succesfully';

            print_r(json_encode($this->response));
        }
    }

    public function delete_seller()
    {
        /*
            user_id:15
            mobile:9874563214
            password:12345695
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User ID', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            echo json_encode($this->response);
            return false;
        } else {

            $user_data = fetch_details('users', ['id' => $_POST['user_id'], 'mobile' => $_POST['mobile']], 'id,username,password,active,mobile');
            if ($user_data) {
                $login = $this->ion_auth->login($this->input->post('mobile'), $this->input->post('password'), false);
                if ($login) {
                    $user_group = fetch_details('users_groups', ['user_id' => $_POST['user_id']], 'group_id');
                    if ($user_group[0]['group_id'] == '4') {
                        $login = $this->ion_auth->login($this->input->post('mobile'), $this->input->post('password'), false);
                        if ($login) {
                            $delete = array(
                                "media" => 0,
                                "payment_requests" => 0,
                                "products" => 0,
                                "product_attributes" => 0,
                                "product_variants" => 0,
                                "order_items" => 0,
                                "orders" => 0,
                                "order_bank_transfer" => 0,
                                "seller_commission" => 0,
                                "seller_data" => 0,
                            );
                            $seller_media = fetch_details('seller_data', ['user_id' => $_POST['user_id']], 'id,logo,national_identity_card,address_proof');
                            if (!empty($seller_media)) {
                                (unlink(FCPATH . $seller_media[0]['logo']) != null) && !empty(unlink(FCPATH . $seller_media[0]['logo'])) ? unlink(FCPATH . $seller_media[0]['logo']) : "";
                                (unlink(FCPATH . $seller_media[0]['national_identity_card']) != null) && !empty(unlink(FCPATH . $seller_media[0]['national_identity_card'])) ? unlink(FCPATH . $seller_media[0]['national_identity_card']) : "";
                                (unlink(FCPATH . $seller_media[0]['address_proof']) != null) && !empty(unlink(FCPATH . $seller_media[0]['address_proof'])) ? unlink(unlink(FCPATH . $seller_media[0]['address_proof'])) : "";
                            }
                            if (update_details(['seller_id' => 0], ['seller_id' => $_POST['user_id']], 'media')) {
                                $delete['media'] = 1;
                            }
                            /* check for retur requesst if seller's product have */
                            $return_req = $this->db->where(['p.seller_id' => $_POST['user_id']])->join('products p', 'p.id=rr.product_id')->get('return_requests rr')->result_array();
                            if (!empty($return_req)) {
                                $this->response['error'] = true;
                                $this->response['message'] = 'Seller could not be deleted.Either found some order items which has return request.Finalize those before deleting it';
                                print_r(json_encode($this->response));
                                return;
                                exit();
                            }
                            $pr_ids = fetch_details("products", ['seller_id' => $_POST['user_id']], "id");
                            if (delete_details(['seller_id' => $_POST['user_id']], 'products')) {
                                $delete['products'] = 1;
                            }
                            foreach ($pr_ids as $row) {
                                if (delete_details(['product_id' => $row['id']], 'product_attributes')) {
                                    $delete['product_attributes'] = 1;
                                }
                                if (delete_details(['product_id' => $row['id']], 'product_variants')) {
                                    $delete['product_variants'] = 1;
                                }
                            }
                            /* check order items */
                            $order_items = fetch_details('order_items', ['seller_id' => $_POST['user_id']], 'id,order_id');
                            if (delete_details(['seller_id' => $_POST['user_id']], 'order_items')) {
                                $delete['order_items'] = 1;
                            }
                            if (!empty($order_items)) {
                                $res_order_id = array_values(array_unique(array_column($order_items, "order_id")));
                                for ($i = 0; $i < count($res_order_id); $i++) {
                                    $orders = $this->db->where('oi.seller_id != ' . $_POST['user_id'] . ' and oi.order_id=' . $res_order_id[$i])->join('orders o', 'o.id=oi.order_id', 'right')->get('order_items oi')->result_array();
                                    if (empty($orders)) {
                                        // delete orders
                                        if (delete_details(['seller_id' => $_POST['user_id']], 'order_items')) {
                                            $delete['order_items'] = 1;
                                        }
                                        if (delete_details(['id' => $res_order_id[$i]], 'orders')) {
                                            $delete['orders'] = 1;
                                        }
                                        if (delete_details(['order_id' => $res_order_id[$i]], 'order_bank_transfer')) {
                                            $delete['order_bank_transfer'] = 1;
                                        }
                                    }
                                }
                            } else {
                                $delete['order_items'] = 1;
                                $delete['orders'] = 1;
                                $delete['order_bank_transfer'] = 1;
                            }
                            if (!empty($res_order_id)) {

                                if (delete_details(['id' => $res_order_id[$i]], 'orders')) {
                                    $delete['orders'] = 1;
                                }
                            } else {
                                $delete['orders'] = 1;
                            }
                            if (delete_details(['seller_id' => $_POST['user_id']], 'seller_commission')) {
                                $delete['seller_commission'] = 1;
                            }
                            if (delete_details(['user_id' => $_POST['user_id']], 'seller_data')) {
                                $delete['seller_data'] = 1;
                            }
                            if (isset($delete['seller_data']) && !empty($delete['seller_data']) && isset($delete['seller_commission']) && !empty($delete['seller_commission'])) {
                                $deleted = TRUE;
                            }
                        }
                        delete_details(['id' => $_POST['user_id']], 'users');
                        delete_details(['user_id' => $_POST['user_id']], 'users_groups');
                        $response['error'] = false;
                        $response['message'] = 'Seller Deleted Succesfully';
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'Details Does\'s Match';
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Details Does\'s Match';
                }
            } else {
                $response['error'] = true;
                $response['message'] = 'User Not Found';
            }
            echo json_encode($response);
            return;
        }
    }

    public function manage_stock()
    {

        /*
            product_variant_id:156
            quantity:5
            type:add/subtract
        */

        if (!$this->verify_token()) {
            return false;
        }
        // $this->form_validation->set_rules('seller_id', 'Seller id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('product_variant_id', 'Product variant id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');



        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            if ((isset($_POST['type']) && $_POST['type'] == 'add')) {
                update_stock([$_POST['product_variant_id']], [$_POST['quantity']], 'plus');
                $this->response['error'] = false;
                $this->response['message'] = 'Stock Updated Successfully';
                print_r(json_encode($this->response));
                return false;
            } else if (isset($_POST['type']) && $_POST['type'] == 'subtract') {
                if ($_POST['quantity'] > $_POST['current_stock']) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Subtracted stock cannot be greater than current stock";
                    print_r(
                        json_encode($this->response)
                    );
                    return false;
                }
                update_stock([$_POST['product_variant_id']], [$_POST['quantity']]);
                $this->response['error'] = false;
                $this->response['message'] = 'Stock Updated Successfully';
                print_r(json_encode($this->response));
                return false;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Stock Not Updated';
                print_r(json_encode($this->response));
                return false;
            }
        }
    }

    public function send_digital_product_mail()
    {
        /*
             order_id : 1
             order_item_id : 101
             customer_email: abc123@gmail.com
             subject : this is test mail
             message : this is our first test mail for digital product
             username : Admin
             attachment : file url for attachment
      */
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('order_id', 'order item id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('order_item_id', 'order item id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('customer_email', 'customer email', 'trim|valid_email|required|xss_clean');
        $this->form_validation->set_rules('subject', 'subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', 'message', 'trim|required|xss_clean');
        $this->form_validation->set_rules('username', 'username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('attachment', 'attachment', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            echo json_encode($this->response);
            return false;
        } else {
            $mail_data = [
                'email' => $_POST['customer_email'],
                'subject' => $_POST['subject'],
                'message' => $_POST['message'],
                'username' => $_POST['username'],
                'pro_input_file' => $_POST['attachment'],
            ];
            $mail = $this->order_model->send_digital_product($mail_data);
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
                    'file_url' => $_POST['attachment'],
                );
                insert_details($data, 'digital_orders_mails');
                return false;
            }
        }
    }

    public function get_digital_order_mails()
    {
        /*
                order_id:156
                order_item_id:5
                search : Search keyword // { optional }
                limit:25                // { default - 10 } optional
                offset:0                // { default - 0 } optional
                sort: id                // { default - id } optional
                order:DESC/ASC          // { default - DESC } optional
    
         */
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('order_id', 'Order Id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order_item_id', 'order item id', 'trim|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $mail_data = $this->order_model->get_digital_order_mail_list(true);

            if (isset($mail_data['rows']) && !empty($mail_data['rows'])) {
                $this->response['error'] = false;
                $this->response['message'] = "Data retrived successfully.";
                $this->response['data'] = $mail_data;
                echo json_encode($this->response);
                return false;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data not retrived successfully.';
                $this->response['data'] = array();
                echo json_encode($this->response);
                return false;
            }
        }
    }
}
