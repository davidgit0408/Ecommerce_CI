<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Api extends CI_Controller
{

    /*
---------------------------------------------------------------------------
Defined Methods:-
---------------------------------------------------------------------------

    1. get_categories    
    2. get_areas_by_city_id
    3. get_cities
    4. get_products
    5. get_settings
    6. get_slider_images
    7. validate_promo_code
        
    8. user-order
        -place_order
        -get_orders
        -update_order_item_status
        -get_invoice_html   (not used)

    9. user-rating
        -set_product_rating
        -delete_product_rating
        -get_product_rating        
        -get_product_review_images        

   10. cart
        -get_user_cart
        -remove_from_cart
        -manage_cart(Add/Update)

   11. user-registration
        -login
        -update_fcm
        -reset_password
        -get_login_identity
        -verify_user
        -register_user
        -update_user
        -delete_user
    
   12. favorites
        -add_to_favorites
        -remove_from_favorites
        -get_favorites

   13. user_addresses
        -add_address
        -update_address
        -delete_address
        -get_address
    
   13. sections
        -get_sections
        -get_notifications


   14. make_payments
        -get_paypal_link
        -paypal_transaction_webview
        -app_payment_status
        -ipn

   15. add_transaction   

   16. get_offer_images

   17. get_faqs

   18. stripe_webhook
   19. transactions
   20. generate_paytm_checksum
   21. generate_paytm_txn_token
   22. validate_paytm_checksum
   23. validate_refer_code
   24. flutterwave_webview
   25. flutterwave_payment_response
   26. delete_order
   27. get_ticket_types
   28. add_ticket
   29. edit_ticket
   30. send_message
   31. get_tickets
   32. get_messages
   33. send_bank_transfer_proof
   34. get_zipcodes
   35. is_product_delivarable
   36. check_cart_products_delivarable
   37. get_sellers
   38. get_promo_codes
   39. add_product_faqs
   40. get_product_faqs
   41. send_withdrawal_request
   42. get_withdrawal_request
   43. rozarpay_create_order
   44. update_order_status
---------------------------------------------------------------------------
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

        $this->load->library(['upload', 'jwt', 'ion_auth', 'form_validation', 'paypal_lib']);
        $this->load->model(['category_model', 'order_model', 'rating_model', 'Area_model', 'cart_model', 'address_model', 'transaction_model', 'ticket_model', 'Order_model', 'notification_model', 'faq_model', 'Seller_model', 'Promo_code_model', 'media_model', 'product_model', 'Customer_model']);
        $this->load->helper(['language', 'string']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $response = $temp = $bulkdata = array();
        $this->identity_column = $this->config->item('identity', 'ion_auth');
        // initialize db tables data
        $this->tables = $this->config->item('tables', 'ion_auth');
    }
    public function index()
    {
        $this->load->helper('file');
        $this->output->set_content_type(get_mime_by_extension(base_url('api-doc.txt')));
        $this->output->set_output(file_get_contents(base_url('api-doc.txt')));
    }
    public function generate_token()
    {
        $payload = [
            'iat' => time(), /* issued at time */
            'iss' => 'eshop',
            'exp' => time() + (30 * 60), /* expires after 1 minute */
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
            JWT::$leeway = 6000000000;
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

    // 1.get_categories

    public function get_categories()
    {
        /*
            id:15               // optional
            limit:25            // { default - 25 } optional
            offset:0            // { default - 0 } optional
            sort:               id / name
                                // { default -row_id } optional
            order:DESC/ASC      // { default - ASC } optional
            has_child_or_item:false { default - true}  optional
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('id', 'Category Id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        $this->form_validation->set_rules('has_child_or_item', 'Child or Item', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        }
        $limit = (isset($_POST['limit'])  && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
        $offset = (isset($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
        $sort = (isset($_POST['sort(array)']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'row_order';
        $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'ASC';
        $has_child_or_item = (isset($_POST['has_child_or_item']) && !empty(trim($_POST['has_child_or_item']))) ? $this->input->post('has_child_or_item', true) : 'true';

        $this->response['message'] = "Cateogry(s) retrieved successfully!";
        $id = (!empty($_POST['id']) && isset($_POST['id'])) ? $_POST['id'] : '';
        $cat_res = $this->category_model->get_categories($id, $limit, $offset, $sort, $order, strval(trim($has_child_or_item)));

        $popular_categories = $this->category_model->get_categories(NULL, "", "", 'clicks', 'DESC', 'false', "", "", "");

        $this->response['error'] = (empty($cat_res)) ? true : false;
        $this->response['total'] = !empty($cat_res) ? $cat_res[0]['total'] : 0;
        $this->response['message'] = (empty($cat_res)) ? 'Category does not exist' : 'Category retrieved successfully';
        $this->response['data'] = $cat_res;
        $this->response['popular_categories'] = $popular_categories;

        print_r(json_encode($this->response));
    }

    //2. get_areas_by_city_id
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
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'Search keyword', 'trim|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            $limit = (isset($_POST['limit'])  && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'a.name';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'ASC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $id = $this->input->post('id', true);

            $result = $this->Area_model->get_area_by_city($id, $sort, $order, $search, $limit, $offset);
            print_r(json_encode($result));
        }
    }

    //3.get_cities
    public function get_cities()
    {
        /*
           sort:               // { c.name / c.id } optional
           order:DESC/ASC      // { default - ASC } optional
           search:value        // {optional} 
       */
        $this->form_validation->set_rules('sort', 'sort', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');

        if (!$this->verify_token()) {
            return false;
        }
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'c.name';
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'ASC';
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $id = $this->input->post('id', true);
            $result = $this->Area_model->get_cities($sort, $order, $search, $limit, $offset);
            print_r(json_encode($result));
        }
    }

    /* 4.get_products

        id:101              // optional
        category_id:29      // optional
        user_id:15          // optional
        search:keyword      // optional
        tags:multiword tag1, tag2, another tag      // optional
        attribute_value_ids : 34,23,12 // { Use only for filteration } optional
        limit:25            // { default - 25 } optional
        offset:0            // { default - 0 } optional
        sort:p.id / p.date_added / pv.price
                            // { default - p.id } optional
        order:DESC/ASC      // { default - DESC } optional
        is_similar_products:1 // { default - 0 } optional
        top_rated_product: 1 // { default - 0 } optional
        discount: 5             // optional
        min_price:10000          // optional
        max_price:50000          // optional
        seller_id:1255           //{optional}
        zipcode:1           //{optional}
        product_ids: 19,20             // optional
        product_variant_ids: 44,45,40             // optional

    */

    public function get_products()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('id', 'Product ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('product_ids', 'Product IDs', 'trim|xss_clean');
        $this->form_validation->set_rules('product_variant_ids', 'Product variant IDs', 'trim|xss_clean');
        $this->form_validation->set_rules('search', 'Search', 'trim|xss_clean');
        $this->form_validation->set_rules('category_id', 'Category id', 'trim|xss_clean');
        $this->form_validation->set_rules('attribute_value_ids', 'Attr Ids', 'trim|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean|alpha');
        $this->form_validation->set_rules('is_similar_products', 'Similar Products', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('top_rated_product', ' Top Rated Product ', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('min_price', ' Min Price ', 'trim|xss_clean|numeric|less_than_equal_to[' . $this->input->post('max_price') . ']');
        $this->form_validation->set_rules('max_price', ' Max Price ', 'trim|xss_clean|numeric|greater_than_equal_to[' . $this->input->post('min_price') . ']');
        $this->form_validation->set_rules('discount', ' Discount ', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('zipcode', ' zipcode ', 'trim|xss_clean');
        $tags = array();
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $limit = (isset($_POST['limit'])) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset'])) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'ASC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'p.row_order';
            $seller_id = (isset($_POST['seller_id']) && !empty(trim($_POST['seller_id']))) ?  $this->input->post('seller_id', true) : NULL;
            $filters['search'] =  (isset($_POST['search']) && !empty($_POST['search'])) ? $_POST['search'] : '';
            $filters['tags'] =  (isset($_POST['tags'])) ? $_POST['tags'] : "";
            $filters['attribute_value_ids'] = (isset($_POST['attribute_value_ids'])) ? $_POST['attribute_value_ids'] : null;
            $filters['is_similar_products'] = (isset($_POST['is_similar_products'])) ? $_POST['is_similar_products'] : null;
            $filters['discount'] = (isset($_POST['discount'])) ? $_POST['discount'] : 0;
            $filters['product_type'] = (isset($_POST['top_rated_product']) && $_POST['top_rated_product'] == 1) ? 'top_rated_product_including_all_products' : null;
            $filters['min_price'] = (isset($_POST['min_price']) && !empty($_POST['min_price'])) ? $this->input->post("min_price", true) : 0;
            $filters['max_price'] = (isset($_POST['max_price']) && !empty($_POST['max_price'])) ? $this->input->post("max_price", true) : 0;
            $zipcode = (isset($_POST['zipcode']) && !empty($_POST['zipcode'])) ? $this->input->post("zipcode", true) : 0;
            if (isset($_POST['zipcode']) && !empty($_POST['zipcode'])) {
                $zipcode = $this->input->post('zipcode', true);
                $is_pincode = is_exist(['zipcode' => $zipcode], 'zipcodes');
                if ($is_pincode) {
                    $zipcode_id = fetch_details('zipcodes', ['zipcode' => $zipcode], 'id');
                    $zipcode = $zipcode_id[0]['id'];
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Products Not Found !';
                    echo json_encode($this->response);
                    return false;
                }
            }
            $category_id = (isset($_POST['category_id'])) ? $_POST['category_id'] : null;
            $product_id = (isset($_POST['id'])) ? $_POST['id'] : null;
            $user_id = (isset($_POST['user_id'])) ? $_POST['user_id'] : null;
            $product_ids = (isset($_POST['product_ids'])) ? $_POST['product_ids'] : null;
            $product_variant_ids = (isset($_POST['product_variant_ids']) && !empty($_POST['product_variant_ids'])) ? $this->input->post("product_variant_ids", true) : null;
            if ($product_ids != null) {
                $product_id = explode(",", $product_ids);
            }
            if ($category_id != null) {
                $category_id = explode(",", $category_id);
            }
            if ($product_variant_ids != null) {
                $filters['product_variant_ids'] = explode(",", $product_variant_ids);
            }

            $products = fetch_product($user_id, (isset($filters)) ? $filters : null, $product_id, $category_id, $limit, $offset, $sort, $order, null, $zipcode, $seller_id);

            for ($i = 0; $i < count($products['product']); $i++) {
                if (!empty($products['product'][$i]['tags'])) {
                    $tags = array_values(array_unique(array_merge($tags, $products['product'][$i]['tags'])));
                }
            }

            if (!empty($products['product'])) {
                $this->response['error'] = false;
                $this->response['message'] = "Products retrieved successfully !";
                $this->response['min_price'] = (isset($products['min_price']) && !empty($products['min_price'])) ? strval($products['min_price']) : 0;
                $this->response['max_price'] = (isset($products['max_price']) && !empty($products['max_price'])) ? strval($products['max_price']) : 0;
                $this->response['search'] = (isset($_POST['search']) && !empty($_POST['search'])) ? $_POST['search'] : '';
                $this->response['filters'] = (isset($products['filters']) && !empty($products['filters'])) ? $products['filters'] : [];
                $this->response['tags'] = (!empty($tags)) ? $tags : [];
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


    //5.get_settings
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
        $this->form_validation->set_rules('user_id', 'User id', 'trim|numeric|xss_clean');


        if (!$this->form_validation->run()) {

            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $tags = array();

            $general_settings = array();
            if ($type == 'all' || $type == 'payment_method') {

                $limit = (isset($_POST['limit'])) ? $this->input->post('limit', true) : 30;
                $offset = (isset($_POST['offset'])) ? $this->input->post('offset', true) : 0;
                $filter = array('tags' => "a");
                $products = fetch_product(null, $filter, null, null, $limit, $offset, 'p.id', 'DESC', null);
                for ($i = 0; $i < count($products); $i++) {
                    if (!empty($products['product'][$i]['tags'])) {
                        $tags = array_merge($tags, $products['product'][$i]['tags']);
                    }
                }
                $settings = [
                    'logo' => 0,
                    'privacy_policy' => 0,
                    'terms_conditions' => 0,
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
                    $time_slots = fetch_details('time_slots', ['status' => '1'], '*', '', '', 'from_time', 'ASC');

                    if (!empty($time_slots)) {
                        for ($i = 0; $i < count($time_slots); $i++) {
                            $datetime = DateTime::createFromFormat("h:i:s a", $time_slots[$i]['from_time']);
                        }
                    }

                    $settings_res['time_slots'] = array_values($time_slots);
                    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
                        $cart_total_response = get_cart_total($_POST['user_id'], false, 0);
                        $cod_allowed = isset($cart_total_response[0]['is_cod_allowed']) ? $cart_total_response[0]['is_cod_allowed'] : 1;
                        $settings_res['is_cod_allowed'] = $cod_allowed;
                    } else {
                        $settings_res['is_cod_allowed'] = 1;
                    }

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
                        if ($type == 'user_data' && isset($_POST['user_id']) && !empty($_POST['user_id'])) {
                            $cart_total_response = get_cart_total($_POST['user_id'], false, 0);
                            $res = $this->address_model->get_address($_POST['user_id'], false, false, true);
                            if (!empty($res)) {
                                $zipcode_id = fetch_details('areas', ['id' => $res[0]['area_id']], 'zipcode_id');
                                if (!empty($zipcode_id)) {
                                    $zipcode = fetch_details('zipcodes', ['id' => $zipcode_id[0]['zipcode_id']], 'zipcode');
                                }
                            }
                            $settings_res = fetch_users($_POST['user_id']);
                            $settings_res[0]['cities'] =  (isset($settings_res[0]['cities']) && $settings_res[0]['cities'] != null) ? $cart_total_response[0]['cities'] : '';
                            $settings_res[0]['street'] =  (isset($settings_res[0]['street']) && $settings_res[0]['street'] != null) ? $cart_total_response[0]['street'] : '';
                            $settings_res[0]['area'] =  (isset($settings_res[0]['area']) && $settings_res[0]['area'] != null) ? $cart_total_response[0]['area'] : '';
                            $settings_res[0]['cart_total_items'] = (isset($cart_total_response[0]['cart_count']) && $cart_total_response[0]['cart_count'] > 0) ? $cart_total_response[0]['cart_count'] : '0';
                            $settings_res[0]['pincode'] =  (!empty($res) && !empty($zipcode)) ? $zipcode[0]['zipcode'] : '';
                            $settings_res = $settings_res[0];
                        } elseif ($type == 'user_data' && !isset($_POST['user_id'])) {
                            $settings_res = '';
                        }
                        //Strip tags in case of terms_conditions and privacy_policy
                        array_push($general_settings[$type], $settings_res);
                    }
                }

                $this->response['error'] = false;
                $this->response['message'] = 'Settings retrieved successfully';
                $this->response['data'] = $general_settings;
                $this->response['data']['tags'] = $tags;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Settings Not Found';
                $this->response['data'] = array();
            }
            print_r(json_encode($this->response));
        }
    }

    //6.get_slider_images
    public function get_slider_images()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $res = fetch_details('sliders', '');
        $i = 0;
        foreach ($res as $row) {
            $res[$i]['image'] = base_url($res[$i]['image']);

            if (strtolower($res[$i]['type']) == 'categories') {
                $id = (!empty($res[$i]['type_id']) && isset($res[$i]['type_id'])) ? $res[$i]['type_id'] : '';
                $cat_res = $this->category_model->get_categories($id);
                $res[$i]['data']  =  $cat_res;
            } else if (strtolower($res[$i]['type']) == 'products') {
                $id = (!empty($res[$i]['type_id']) && isset($res[$i]['type_id'])) ? $res[$i]['type_id'] : '';
                $pro_res = fetch_product(NULL, NULL, $id);
                $res[$i]['data']  =  $pro_res['product'];
            } else {
                $res[$i]['data']  =  [];
            }

            $i++;
        }
        $this->response['error'] = false;
        $this->response['data'] = $res;
        print_r(json_encode($this->response));
    }

    //7.validate_promo_code
    public function validate_promo_code()
    {
        /*
            promo_code:'NEWOFF10'
            user_id:28
            final_total:'300'

        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('promo_code', 'Promo Code', 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('final_total', 'Final Total', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            print_r(json_encode(validate_promo_code($_POST['promo_code'], $_POST['user_id'], $_POST['final_total'])));
        }
    }

    //8.place_order

    public function place_order()
    {
        /*
            user_id:5
            mobile:9974692496
            email:testmail123@gmail.com // only enter when ordered product is digital product and one of them is not downloadable(download_allowed = 0)
            product_variant_id: 1,2,3
            quantity: 3,3,1
            total:60.0
            delivery_charge:20.0
            tax_amount:10
            tax_percentage:10
            final_total:55
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
            order_note:text      //{optional}
      
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile Id', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('product_variant_id', 'Product Variant Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('quantity', 'Quantities', 'trim|required|xss_clean');
        $this->form_validation->set_rules('final_total', 'Final Total', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('promo_code', 'Promo Code', 'trim|xss_clean');
        $this->form_validation->set_rules('order_note', 'Order Note', 'trim|xss_clean');

        /*
        ------------------------------
        If Wallet Balance Is Used
        ------------------------------
        */
        $product_variant_id = explode(',', $_POST['product_variant_id']);
        $product_variant = $this->db->select('p.type,p.download_allowed')
            ->join('products p ', 'pv.product_id=p.id', 'left')
            ->where_in('pv.id', $product_variant_id)->order_by('FIELD(pv.id,' . $_POST['product_variant_id'] . ')')->get('product_variants pv')->result_array();
        if (!empty($product_variant)) {
            $product_type = array_values(array_unique(array_column($product_variant, "type")));
            $download_allowed = array_values(array_unique(array_column($product_variant, "download_allowed")));
        }

        if (in_array(0, $download_allowed) && $product_type[0] == "digital_product") {
            $this->form_validation->set_rules('email', 'Email ID', 'required|valid_email|trim|xss_clean');
        }

        $this->form_validation->set_rules('is_wallet_used', ' Wallet Balance Used', 'trim|required|numeric|xss_clean');
        if (isset($_POST['is_wallet_used']) && $_POST['is_wallet_used'] == '1') {
            $this->form_validation->set_rules('wallet_balance_used', ' Wallet Balance ', 'trim|required|numeric|xss_clean');
        }
        $this->form_validation->set_rules('latitude', 'Latitude', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('longitude', 'Longitude', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|required|xss_clean');
        $this->form_validation->set_rules('delivery_date', 'Delivery Date', 'trim|xss_clean');
        $this->form_validation->set_rules('delivery_time', 'Delivery time', 'trim|xss_clean');
        if ($product_type[0] == "variable_product" || $product_type[0] == "simple_product") {
            $this->form_validation->set_rules('address_id', 'Address id', 'trim|required|numeric|xss_clean');
        }

        $settings = get_settings('system_settings', true);
        $currency = isset($settings['currency']) && !empty($settings['currency']) ? $settings['currency'] : '';
        if (isset($settings['minimum_cart_amt']) && !empty($settings['minimum_cart_amt'])) {
            $this->form_validation->set_rules('total', 'Total', 'trim|xss_clean|greater_than_equal_to[' . $settings['minimum_cart_amt'] . ']', array('greater_than_equal_to' => 'Total amount should be greater or equal to ' . $currency . $settings['minimum_cart_amt'] . ' total is ' . $currency . $_POST['total'] . ''));
        }
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            $_POST['order_note'] = (isset($_POST['order_note']) && !empty($_POST['order_note'])) ? $this->input->post("order_note", true) : NULL;
            /* checking for product availability */
            $area_id = fetch_details('addresses', ['id' => $_POST['address_id']], 'area_id');
            $product_delivarable = check_cart_products_delivarable($area_id[0]['area_id'], $_POST['user_id']);
            if (!empty($product_delivarable) && ($product_type[0] == "variable_product" || $product_type[0] == "simple_product")) {
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
                    $this->response['data'] = $product_delivarable;
                    print_r(json_encode($this->response));
                    return;
                } else {
                    $data = array();
                    $_POST['is_delivery_charge_returnable'] = isset($_POST['delivery_charge']) && !empty($_POST['delivery_charge']) && $_POST['delivery_charge'] != '' && $_POST['delivery_charge'] > 0 ? 1 : 0;
                    $res = $this->order_model->place_order($_POST);

                    if (!empty($res)) {

                        if ($_POST['payment_method'] == "bank_transfer") {
                            $data['status'] = "awaiting";
                            $data['txn_id'] = null;
                            $data['message'] = null;
                            $data['order_id'] = $res['order_id'];
                            $data['user_id'] = $_POST['user_id'];
                            $data['type'] = $_POST['payment_method'];
                            $data['amount'] = $_POST['final_total'];

                            $this->transaction_model->add_transaction($data);
                        }
                    }
                    print_r(json_encode($res));
                }
            } else {
                $data = array();
                $_POST['is_delivery_charge_returnable'] = isset($_POST['delivery_charge']) && !empty($_POST['delivery_charge']) && $_POST['delivery_charge'] != '' && $_POST['delivery_charge'] > 0 ? 1 : 0;
                $res = $this->order_model->place_order($_POST);
                if (!empty($res)) {

                    if ($_POST['payment_method'] == "bank_transfer") {
                        $data['status'] = "awaiting";
                        $data['txn_id'] = null;
                        $data['message'] = null;
                        $data['order_id'] = $res['order_id'];
                        $data['user_id'] = $_POST['user_id'];
                        $data['type'] = $_POST['payment_method'];
                        $data['amount'] = $_POST['final_total'];
                        $this->transaction_model->add_transaction($data);
                    }
                }
                print_r(json_encode($res));
            }
        }
    }

    //get_orders
    public function get_orders()
    {
        // user_id:101
        // active_status: received  {received,delivered,cancelled,processed,returned}     // optional
        // limit:25            // { default - 25 } optional
        // offset:0            // { default - 0 } optional
        // sort: id / date_added // { default - id } optional
        // order:DESC/ASC      // { default - DESC } optional        
        // download_invoice:0 // { default - 0 } optional       

        if (!$this->verify_token()) {
            return false;
        }
        $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
        $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
        $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $this->input->post('sort', true) : 'o.id';
        $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $this->input->post('order', true) : 'DESC';
        $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : '';

        $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('active_status', 'status', 'trim|xss_clean');
        $this->form_validation->set_rules('download_invoice', 'Invoice', 'trim|numeric|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $multiple_status =   (isset($_POST['active_status']) && !empty($_POST['active_status'])) ? explode(',', $_POST['active_status']) : false;
            $download_invoice =   (isset($_POST['download_invoice']) && !empty($_POST['download_invoice'])) ? $_POST['download_invoice'] : 1;
            $order_details = fetch_orders(false, $_POST['user_id'], $multiple_status, false, $limit, $offset, $sort, $order, $download_invoice, false, false, $search);

            if (!empty($order_details)) {

                $this->response['error'] = false;
                $this->response['message'] = 'Data retrieved successfully';
                $this->response['total'] = $order_details['total'];
                $this->response['data'] = $order_details['order_data'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'No Order(s) Found !';
                $this->response['data'] = array();
            }
        }
        print_r(json_encode($this->response));
    }

    /*
        status: cancelled / returned
        order_id:1201
    */
    public function update_order_item_status()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
        $this->form_validation->set_rules('order_id', 'Order item id', 'trim|required|numeric|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            // check for bank receipt if available
            $order_item_data = fetch_details('order_items', ['id' => $_POST['order_id']], 'order_id');
            $order_method = fetch_details('orders', ['id' => $order_item_data[0]['order_id']], 'payment_method');

            if ($order_method[0]['payment_method'] == 'bank_transfer') {
                $bank_receipt = fetch_details('order_bank_transfer', ['order_id' => $_POST['order_id']]);
                $transaction_status = fetch_details('transactions', ['order_id' => $_POST['order_id']], 'status');
                if ($_POST['status'] != "cancelled" && (empty($bank_receipt) || strtolower($transaction_status[0]['status']) != 'success')) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order Status can not update, Bank verification is remain from transactions.";
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
            $this->response = $this->order_model->update_order_item($_POST['order_id'], trim($_POST['status']));
            if (trim($_POST['status']) != 'returned' && $this->response['error'] == false) {
                process_refund($_POST['order_id'], $_POST['status'], 'order_items');
            }
            if (trim($_POST['status']) == 'cancelled') {
                $data = fetch_details('order_items', ['id' => $_POST['order_id']], 'product_variant_id,quantity');
                update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
            }
        }
        print_r(json_encode($this->response));
        return false;
    }

    // get_invoice_html    
    // order_id:214
    public function get_invoice_html()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('order_id', 'Order id', 'trim|required|xss_clean');


        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {

            $this->data['main_page'] = VIEW . 'api-order-invoice';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Invoice Management |' . $settings['app_name'];
            $this->data['meta_description'] = $settings['app_name'] . ' | Invoice Management';
            if (isset($_POST['order_id']) && !empty($_POST['order_id'])) {
                $res = $this->order_model->get_order_details(['o.id' => $_POST['order_id']]);
                if (!empty($res)) {
                    $items = [];
                    $promo_code = [];
                    if (!empty($res[0]['promo_code'])) {
                        $promo_code = fetch_details('promo_codes', ['promo_code' => trim($res[0]['promo_code'])]);
                    }
                    foreach ($res as $row) {
                        $temp['product_id'] = $row['product_id'];
                        $temp['product_variant_id'] = $row['product_variant_id'];
                        $temp['pname'] = $row['pname'];
                        $temp['quantity'] = $row['quantity'];
                        $temp['discounted_price'] = $row['discounted_price'];
                        $temp['tax_percent'] = $row['tax_percent'];
                        $temp['tax_amount'] = $row['tax_amount'];
                        $temp['price'] = $row['price'];
                        $temp['delivery_boy'] = $row['delivery_boy'];
                        $temp['active_status'] = $row['oi_active_status'];
                        array_push($items, $temp);
                    }
                    $this->data['order_detls'] = $res;
                    $this->data['items'] = $items;
                    $this->data['promo_code'] = $promo_code;
                    $this->data['settings'] = get_settings('system_settings', true);
                    $response['error'] = false;
                    $response['message'] = 'Invoice Generated Successfully';
                    $response['data'] = $this->load->view('admin/invoice-template', $this->data, TRUE);
                } else {
                    $response['error'] = true;
                    $response['message'] = 'No Order Details Found !';
                    $response['data'] = [];
                }
            } else {
                $response['error'] = true;
                $response['message'] = 'No Order Details Found !';
                $response['data'] = [];
            }
            print_r(json_encode($response));
            return false;
        }
    }

    // 9 set_product_rating
    public function set_product_rating()
    {
        /*
            user_id: 21
            product_id: 33
            rating: 4.2
            comment: 'Done' {optional}
            images[]:[]
      */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('product_id', 'Product Id', 'trim|numeric|xss_clean|required');
        $this->form_validation->set_rules('rating', 'Rating', 'trim|numeric|xss_clean|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('comment', 'Comment', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $response['error'] = true;
            $response['message'] = strip_tags(validation_errors());
            $response['data'] = array();
            echo json_encode($response);
        } else {
            if (!file_exists(FCPATH . REVIEW_IMG_PATH)) {
                mkdir(FCPATH . REVIEW_IMG_PATH, 0777);
            }

            $temp_array = array();
            $files = $_FILES;
            $images_new_name_arr = array();
            $images_info_error = "";
            $config = [
                'upload_path' =>  FCPATH . REVIEW_IMG_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 8000,
            ];

            if (!empty($_FILES['images']['name'][0]) && isset($_FILES['images']['name'])) {
                $other_image_cnt = count($_FILES['images']['name']);
                $other_img = $this->upload;
                $other_img->initialize($config);

                for ($i = 0; $i < $other_image_cnt; $i++) {

                    if (!empty($_FILES['images']['name'][$i])) {

                        $_FILES['temp_image']['name'] = $files['images']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['images']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['images']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['images']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['images']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'Images :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . REVIEW_IMG_PATH);
                            $images_new_name_arr[$i] = REVIEW_IMG_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['images']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['images']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['images']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['images']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['images']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                }

                //Deleting Uploaded Images if any overall error occured
                if ($images_info_error != NULL || !$this->form_validation->run()) {
                    if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                        foreach ($images_new_name_arr as $key => $val) {
                            unlink(FCPATH . REVIEW_IMG_PATH . $images_new_name_arr[$key]);
                        }
                    }
                }
            }

            if ($images_info_error != NULL) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] =  $images_info_error;
                print_r(json_encode($this->response));
                return;
            }

            $res = $this->db->select('*')->join('product_variants pv', 'pv.id=oi.product_variant_id')->join('products p', 'p.id=pv.product_id')->where(['pv.product_id' => $_POST['product_id'], 'oi.user_id' => $_POST['user_id'], 'oi.active_status!=' => 'returned'])->limit(1)->get('order_items oi')->result_array();
            if (empty($res)) {
                $response['error'] = true;
                $response['message'] = 'You cannot review as the product is not purchased yet!';
                $response['data'] = array();
                echo json_encode($response);
                return;
            }

            $rating_data = fetch_details('product_rating', ['user_id' => $_POST['user_id'], 'product_id' => $_POST['product_id']], 'images');
            $rating_images = $images_new_name_arr;
            $_POST['images'] = $rating_images;
            $this->rating_model->set_rating($_POST);
            $rating_data = $this->rating_model->fetch_rating((isset($_POST['product_id'])) ? $_POST['product_id'] : '', '', '25', '0', 'id', 'DESC');
            $rating['product_rating'] = $rating_data['product_rating'];
            $rating['no_of_rating'] = (isset($rating['no_of_rating']) && (!empty($rating['no_of_rating']))) ? $rating_data['rating'][0]['no_of_rating'] : '';
            $response['error'] = false;
            $response['message'] = 'Product Rated Successfully';
            $response['data'] = $rating;
            echo json_encode($response);
            return;
        }
    }

    //  delete_product_rating
    public function delete_product_rating()
    {
        /*
        rating_id:32
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('rating_id', 'Rating Id', 'trim|numeric|required|xss_clean');

        if (!$this->form_validation->run()) {
            $response['error'] = true;
            $response['message'] = strip_tags(validation_errors());
            $response['data'] = array();
            echo json_encode($response);
        } else {
            $this->rating_model->delete_rating($_POST['rating_id']);
            $response['error'] = false;
            $response['message'] = 'Deleted Rating Successfully';
            $response['data'] = array();
            echo json_encode($response);
        }
    }

    // get_product_rating
    /*
    product_id : 12
    user_id : 1 		{optional}
    limit:25                // { default - 25 } optional
    offset:0                // { default - 0 } optional
    sort: type   			// { default - type } optional
    order:DESC/ASC          // { default - DESC } optional
  */
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

    //10. get_user_cart
    public function get_user_cart()
    {
        /*
          user_id:2
          is_saved_for_later: 1 { default:0 }
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('is_saved_for_later', 'Saved for later', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'Search keyword', 'trim|xss_clean');
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
        } else {
            $is_saved_for_later = (isset($_POST['is_saved_for_later']) && $_POST['is_saved_for_later'] == 1) ? $_POST['is_saved_for_later'] : 0;
            $cart_user_data = $this->cart_model->get_user_cart($_POST['user_id'], $is_saved_for_later);
            $cart_total_response = get_cart_total($_POST['user_id'], '', $is_saved_for_later);
            $settings = get_settings('system_settings', true);
            $tmp_cart_user_data = $cart_user_data;
            if (!empty($tmp_cart_user_data)) {
                for ($i = 0; $i < count($tmp_cart_user_data); $i++) {
                    $product_data = fetch_details('product_variants', ['id' => $tmp_cart_user_data[$i]['product_variant_id']],  'product_id,availability,price,special_price');
                    if (!empty($product_data[0]['product_id'])) {
                        $pro_details = fetch_product($_POST['user_id'], null, $product_data[0]['product_id']);

                        $price = $product_data[0]['special_price'] != '' && $product_data[0]['special_price'] != null && $product_data[0]['special_price'] > 0 && $product_data[0]['special_price'] < $product_data[0]['price'] ? $product_data[0]['special_price'] : $product_data[0]['price'];
                        if (!empty($pro_details['product'])) {
                            if (trim($pro_details['product'][0]['availability']) == 0 && $pro_details['product'][0]['availability'] != null) {
                                update_details(['is_saved_for_later' => '1'], $cart_user_data[$i]['id'], 'cart');
                                unset($cart_user_data[$i]);
                            }
                            $pro_details['product'][0]['net_amount'] = $cart_user_data[$i]['net_amount'];
                            if (!empty($pro_details['product'])) {
                                $cart_user_data[$i]['product_details'] = $pro_details['product'];
                            } else {
                                delete_details(['id' => $cart_user_data[$i]['id']], 'cart');
                                unset($cart_user_data[$i]);
                                continue;
                            }
                        } else {
                            delete_details(['id' => $cart_user_data[$i]['id']], 'cart');
                            unset($cart_user_data[$i]);
                            continue;
                        }
                    } else {
                        delete_details(['id' => $cart_user_data[$i]['id']], 'cart');
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
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';

            $this->response['error'] = false;
            $this->response['message'] = 'Data Retrieved From Cart !';
            $this->response['total_quantity'] = $cart_total_response['quantity'];
            $this->response['sub_total'] = $cart_total_response['sub_total'];
            $this->response['delivery_charge'] = $settings['delivery_charge'];
            $this->response['tax_percentage'] = (isset($cart_total_response['tax_percentage'])) ? $cart_total_response['tax_percentage'] : "0";
            $this->response['tax_amount'] = (isset($cart_total_response['tax_amount'])) ? $cart_total_response['tax_amount'] : "0";
            $this->response['overall_amount'] = $cart_total_response['overall_amount'];
            $this->response['total_arr'] =  $cart_total_response['total_arr'];
            $this->response['variant_id'] =  $cart_total_response['variant_id'];
            $this->response['data'] = array_values($cart_user_data);
            $result = $this->Promo_code_model->get_promo_codes($limit, $offset, $sort, $order, $search);

            $this->response['promo_codes'] = $result['data'];

            print_r(json_encode($this->response));
            return;
        }
    }

    // remove_from_cart - THIS API IS NOT USED ANYWHERE IN THE APP
    public function remove_from_cart()
    {
        /*
            user_id:2           
            product_variant_id:23 {optional} //if not passed all items in the cart will be removed.    
      */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('product_variant_id', 'Product Variant', 'trim|numeric|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            //Fetching cart items to check wheather cart is empty or not
            $cart_total_response = get_cart_total($_POST['user_id']);
            $settings = get_settings('system_settings', true);
            if (!isset($cart_total_response[0]['total_items'])) {
                $this->response['error'] = true;
                $this->response['message'] = 'Cart Is Already Empty !';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return;
            }

            $this->cart_model->remove_from_cart($_POST);

            //Fetching cart items to send the details to api after the item is removed
            $cart_total_response = get_cart_total($_POST['user_id']);
            $this->response['error'] = false;
            $this->response['message'] = 'Removed From Cart !';
            if (!empty($cart_total_response) && isset($cart_total_response)) {
                $this->response['data'] = [
                    'total_quantity' => strval($cart_total_response['quantity']),
                    'sub_total' => strval($cart_total_response['sub_total']),
                    'total_items' => (isset($cart_total_response[0]['total_items'])) ? strval($cart_total_response[0]['total_items']) : "0",
                    'max_items_cart' => $settings['max_items_cart']
                ];
            } else {
                $this->response['data'] = [];
            }

            print_r(json_encode($this->response));
            return;
        }
    }

    public function manage_cart()
    {
        /*
        Add/Update
        user_id:2
        product_variant_id:23
        is_saved_for_later: 1 { default:0 }
        qty:2 // pass 0 to remove qty
     */
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('user_id', 'User', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('product_variant_id', 'Product Variant', 'trim|required|xss_clean');
        $this->form_validation->set_rules('qty', 'Quantity', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('is_saved_for_later', 'Saved For Later', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            $product_variant_id = $this->input->post('product_variant_id', true);
            $user_id = $this->input->post('user_id', true);
            $settings = get_settings('system_settings', true);

            if (!is_exist(['id' => $product_variant_id], "product_variants")) {
                $this->response['error'] = true;
                $this->response['message'] = 'Product Varient not available.';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            // clear cart if user has multi restro items
            $clear_cart = (isset($_POST['clear_cart']) && $_POST['clear_cart'] != "") ? $this->input->post('clear_cart', true) : 0;
            if ($clear_cart == true) {
                if (!$this->cart_model->remove_from_cart(['user_id' => $user_id])) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Not able to remove existing seller items please try agian later.';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }

            if ($settings['is_single_seller_order'] == 1) {
                if (!is_single_seller($product_variant_id, $user_id)) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Only single seller items are allow in cart.You can remove privious item(s) and add this item.';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }

            //check for digital or phisical product in cart
            if (!is_single_product_type($product_variant_id, $user_id)) {
                $this->response['error'] = true;
                $this->response['message'] = 'you can only add either digital product or physical product to cart';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            $qty = $this->input->post('qty', true);
            $saved_for_later = (isset($_POST['is_saved_for_later']) && $_POST['is_saved_for_later'] != "") ? $this->input->post('is_saved_for_later', true) : 0;
            $check_status = ($qty == 0 || $saved_for_later == 1) ? false : true;
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
            if (!$this->cart_model->add_to_cart($_POST, $check_status)) {
                $response = get_cart_total($_POST['user_id'], false);
                $cart_user_data = $this->cart_model->get_user_cart($_POST['user_id'], 0);
                $product_type = array_values(array_unique(array_column($cart_user_data, "type")));

                $tmp_cart_user_data = $cart_user_data;
                if (!empty($tmp_cart_user_data)) {
                    for ($i = 0; $i < count($tmp_cart_user_data); $i++) {
                        $product_data = fetch_details('product_variants', ['id' => $tmp_cart_user_data[$i]['product_variant_id']],  'product_id,availability');
                        if (!empty($product_data[0]['product_id'])) {
                            $pro_details = fetch_product($_POST['user_id'], null, $product_data[0]['product_id']);
                            if (!empty($pro_details['product'])) {
                                if (trim($pro_details['product'][0]['availability']) == 0 && $pro_details['product'][0]['availability'] != null) {
                                    update_details(['is_saved_for_later' => '1'], $cart_user_data[$i]['id'], 'cart');
                                    unset($cart_user_data[$i]);
                                }

                                if (!empty($pro_details['product'])) {
                                    $cart_user_data[$i]['product_details'] = $pro_details['product'];
                                } else {
                                    delete_details(['id' => $cart_user_data[$i]['id']], 'cart');
                                    unset($cart_user_data[$i]);
                                    continue;
                                }
                            } else {
                                delete_details(['id' => $cart_user_data[$i]['id']], 'cart');
                                unset($cart_user_data[$i]);
                                continue;
                            }
                        } else {
                            delete_details(['id' => $cart_user_data[$i]['id']], 'cart');
                            unset($cart_user_data[$i]);
                            continue;
                        }
                    }
                }

                $this->response['error'] = false;
                $this->response['message'] = 'Cart Updated !';
                $this->response['cart'] = (isset($cart_user_data) && !empty($cart_user_data)) ? $cart_user_data : [];
                $this->response['data'] = [
                    'total_quantity' => ($_POST['qty'] == 0) ? '0' : strval($_POST['qty']),
                    'sub_total' => strval($response['sub_total']),
                    'total_items' => (isset($response[0]['total_items'])) ? strval($response[0]['total_items']) : "0",
                    'tax_percentage' => (isset($response['tax_percentage'])) ? strval($response['tax_percentage']) : "0",
                    'tax_amount' => (isset($response['tax_amount'])) ? strval($response['tax_amount']) : "0",
                    'cart_count' => (isset($response[0]['cart_count'])) ? strval($response[0]['cart_count']) : "0",
                    'max_items_cart' => $settings['max_items_cart'],
                    'overall_amount' => $response['overall_amount'],
                ];
                print_r(json_encode($this->response));
                return;
            }
        }
    }

    public function clear_cart()
    {

        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('user_id', 'User', 'trim|numeric|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            delete_details(['user_id' => $_POST['user_id']], 'cart');
            $this->response['error'] = false;
            $this->response['message'] = 'Data deleted successfully';
            print_r(json_encode($this->response));
            return;
        }
    }

    //11.login

    public function login()
    {
        /* Parameters to be passed
            mobile: 9874565478
            
            password: 12345678
            fcm_id: FCM_ID
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
        $user_data = fetch_details('users', ['mobile' => $this->input->post('mobile', true)]);

        if ($user_data[0]['active'] == 7) {
            $response['error'] = true;
            $response['message'] = 'User Not Found';
            echo json_encode($response);
            return false;
        }

        if ($login) {
            if (!$this->ion_auth->is_admin() && !$this->ion_auth->is_seller() && !$this->ion_auth->is_delivery_boy()) {

                if (isset($_POST['fcm_id']) && !empty($_POST['fcm_id'])) {
                    update_details(['fcm_id' => $_POST['fcm_id']], ['mobile' => $_POST['mobile']], 'users');
                }
                $data = fetch_details('users', ['mobile' => $this->input->post('mobile', true)]);
                unset($data[0]['password']);

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
                    $tempRow['fcm_id'] = $row['fcm_id'];
                    $tempRow['latitude'] = (isset($row['latitude']) && !empty($row['latitude'])) ? $row['latitude  '] : '';
                    $tempRow['longitude'] = (isset($row['longitude']) && !empty($row['longitude'])) ? $row['longitude  '] : '';
                    $tempRow['created_at'] = (isset($row['created_at']) && !empty($row['created_at'])) ? $row['created_at'] : '';

                    $rows[] = $tempRow;
                }
                //if the login is successful
                $response['error'] = false;
                $response['message'] = strip_tags($this->ion_auth->messages());
                $response['data'] = $rows;
                echo json_encode($response);
                return false;
            } else {
                //if the login is successful
                $response['error'] = true;
                $response['message'] = "Only customers can login here";
                $response['data'] = [];
                echo json_encode($response);
                return false;
            }
        } else {
            if (!is_exist(['mobile' => $_POST['mobile']], 'users')) {
                $response['error'] = true;
                $response['message'] = 'User does not exists !';
                echo json_encode($response);
                return false;
            }

            // if the login was un-successful
            // just print json message
            $response['error'] = true;
            $response['message'] = strip_tags($this->ion_auth->errors());
            echo json_encode($response);
            return false;
        }
    }

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
        $this->form_validation->set_rules('fcm_id', 'Fcm Id', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }

        if (isset($_POST['fcm_id']) && $_POST['fcm_id'] != NULL && !empty($_POST['fcm_id'])) {
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
    }

    public function reset_password()
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

    //get_login_identity
    public function get_login_identity()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $response['error'] = false;
        $response['message'] = 'Data Retrieved Successfully';
        $response['data'] = array('identity' => $this->config->item('identity', 'ion_auth'));
        echo json_encode($response);
        return false;
    }

    //verify-user


    public function verify_user()
    {
        /* Parameters to be passed
            mobile: 9874565478
            email: test@gmail.com 
        */
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            if (isset($_POST['mobile']) && is_exist(['mobile' => $_POST['mobile']], 'users')) {
                $this->response['error'] = true;
                $this->response['message'] = 'Mobile is already registered.Please login again !';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return;
            }

            if (isset($_POST['email']) && is_exist(['email' => $_POST['email']], 'users')) {
                $this->response['error'] = true;
                $this->response['message'] = 'Email is already registered.Please login again !';
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return;
            }

            $this->response['error'] = false;
            $this->response['message'] = 'Ready to sent OTP request!';
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        }
    }

    public function register_user()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Mail', 'trim|required|xss_clean|valid_email|is_unique[users.email]', array('is_unique' => ' The email is already registered . Please login'));
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|max_length[16]|numeric|is_unique[users.mobile]', array('is_unique' => ' The mobile number is already registered . Please login'));
        $this->form_validation->set_rules('country_code', 'Country Code', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of birth', 'trim|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('area', 'Area', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('street', 'Street', 'trim|xss_clean');
        $this->form_validation->set_rules('pincode', 'Pincode', 'trim|xss_clean');
        $this->form_validation->set_rules('fcm_id', 'Fcm Id', 'trim|xss_clean');
        $this->form_validation->set_rules('referral_code', 'Referral code', 'trim|is_unique[users.referral_code]|xss_clean');
        $this->form_validation->set_rules('friends_code', 'Friends code', 'trim|xss_clean');
        $this->form_validation->set_rules('latitude', 'Latitude', 'trim|xss_clean');
        $this->form_validation->set_rules('longitude', 'Longitude', 'trim|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            if (isset($_POST['friends_code']) && !empty($_POST['friends_code'])) {
                $friends_code = $_POST['friends_code'];
                $friend = fetch_details('users', ['referral_code' => $friends_code], '*');
                if (empty($friend)) {
                    $response["error"]   = true;
                    $response["message"] = "Invalid friends code! Please pass the valid referral code of the inviter";
                    $response["data"] = [];
                    echo json_encode($response);
                    return false;
                }
            }

            $identity_column = $this->config->item('identity', 'ion_auth');
            $email = strtolower($this->input->post('email'));
            $mobile = $this->input->post('mobile');
            $identity = ($identity_column == 'mobile') ? $mobile : $email;
            $password = $this->input->post('password');

            $additional_data = [
                'username' => $this->input->post('name'),
                'mobile' => $this->input->post('mobile'),
                'dob' => $this->input->post('dob'),
                'city' => $this->input->post('city'),
                'area' => $this->input->post('area'),
                'country_code' => $this->input->post('country_code'),
                'pincode' => $this->input->post('pincode'),
                'street' => $this->input->post('street'),
                'fcm_id' => $this->input->post('fcm_id'),
                'referral_code' => $this->input->post('referral_code', true),
                'friends_code' => $this->input->post('friends_code', true),
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude'),
                'active' => 1
            ];

            $res = $this->ion_auth->register($identity, $password, $email, $additional_data, ['2']);

            update_details(['active' => 1], [$identity_column => $identity], 'users');
            $data = $this->db->select('u.id,u.username,u.email,u.mobile,c.name as city_name,a.name as area_name')->where([$identity_column => $identity])->join('cities c', 'c.id=u.city', 'left')->join('areas a', 'a.city_id=c.id', 'left')->group_by('email')->get('users u')->result_array();

            foreach ($data as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = (isset($row['id']) && !empty($row['id'])) ? $row['id'] : '';
                $tempRow['username'] = (isset($row['username']) && !empty($row['username'])) ? $row['username'] : '';
                $tempRow['email'] = (isset($row['email']) && !empty($row['email'])) ? $row['email'] : '';
                $tempRow['mobile'] = (isset($row['mobile']) && !empty($row['mobile'])) ? $row['mobile'] : '';
                $tempRow['city_name'] = (isset($row['city_name']) && !empty($row['city_name'])) ? $row['city_name'] : '';
                $tempRow['area_name'] = (isset($row['area_name']) && !empty($row['area_name'])) ? $row['area_name'] : '';

                $rows[] = $tempRow;
            }
            $this->response['error'] = false;
            $this->response['message'] = 'Registered Successfully';
            $this->response['data'] = $rows;
        }
        print_r(json_encode($this->response));
    }

    //update_user
    public function update_user()
    {
        /*
            user_id:34
            username:hiten{optional}
            dob:12/5/1982{optional}
            mobile:7852347890 {optional}
            email:amangoswami@gmail.com	{optional}
            address:Time Square	{optional}
            area:ravalwadi	{optional}
            city:23	{optional}
            pincode:56	    {optional}
            latitude:45.453	{optional}
            longitude:45.453	{optional}
            //file
            image:[]
            //optional parameters
            referral_code:Userscode
            old:12345
            new:345234
        */
        if (!$this->verify_token()) {
            return false;
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0 && $_POST['user_id'] == "1") {
            $this->response['error'] = true;
            $this->response['message'] = DEMO_VERSION_MSG;
            echo json_encode($this->response);
            return false;
            exit();
        }

        $identity_column = $this->config->item('identity', 'ion_auth');

        $this->form_validation->set_rules('user_id', 'Id', 'required|xss_clean|numeric|trim');
        $this->form_validation->set_rules('email', 'Email', 'xss_clean|trim|valid_email|edit_unique[users.id.' . $this->input->post('user_id', true) . ']');
        $this->form_validation->set_rules('dob', 'DOB', 'xss_clean|trim');
        $this->form_validation->set_rules('city', 'City', 'xss_clean|numeric|trim');
        $this->form_validation->set_rules('area', 'Area', 'xss_clean|numeric|trim');
        $this->form_validation->set_rules('address', 'Address', 'xss_clean|trim');
        $this->form_validation->set_rules('pincode', 'Pincode', 'xss_clean|trim|numeric');
        $this->form_validation->set_rules('username', 'Username', 'xss_clean|trim');
        $this->form_validation->set_rules('latitude', 'Latitude', 'trim|xss_clean');
        $this->form_validation->set_rules('longitude', 'Longitude', 'trim|xss_clean');
        $this->form_validation->set_rules('referral_code', 'Referral code', 'trim|xss_clean');
        $this->form_validation->set_rules('image', 'Profile Image', 'trim|xss_clean');

        if (!empty($_POST['old']) || !empty($_POST['new'])) {
            $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
            $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[6]');
        }

        $tables = $this->config->item('tables', 'ion_auth');
        if (!$this->form_validation->run()) {
            if (validation_errors()) {
                $response['error'] = true;
                $response['message'] = validation_errors();
                echo json_encode($response);
                return false;
                exit();
            }
        } else {
            if (!empty($_POST['old']) || !empty($_POST['new'])) {
                $identity = ($identity_column == 'mobile') ? 'mobile' : 'email';
                $res = fetch_details('users', ['id' => $_POST['user_id']], $identity);
                if (!empty($res)) {
                    if (!$this->ion_auth->change_password($res[0][$identity], $this->input->post('old'), $this->input->post('new'))) {

                        // if the login was un-successful
                        $response['error'] = true;
                        $response['message'] = strip_tags($this->ion_auth->errors());
                        echo json_encode($response);
                        return;
                    } else {
                        $user_details = fetch_details('users', ['id' => $_POST['user_id']], "*");
                        if (empty($user_details[0]['image']) || file_exists(FCPATH . USER_IMG_PATH . $user_details[0]['image']) == FALSE) {
                            $user_details[0]['image'] = base_url() . NO_IMAGE;
                        } else {
                            $user_details[0]['image'] = base_url() . USER_IMG_PATH . $user_details[0]['image'];
                        }

                        $user_details[0]['image_sm'] = get_image_url(base_url() . USER_IMG_PATH . $user_details[0]['image'], 'thumb', 'sm');
                        $response['error'] = false;
                        $response['message'] = 'Password Update Succesfully';
                        $response['data'] = $user_details;
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

            $is_updated = false;
            /* update referral_code if it is empty in user's database */
            if (isset($_POST['referral_code']) && !empty($_POST['referral_code'])) {
                $user = fetch_details('users', ['id' => $_POST['user_id']], "referral_code");
                if (empty($user[0]['referral_code'])) {
                    update_details(['referral_code' => $_POST['referral_code']], ['id' => $_POST['user_id']], "users");
                    $is_updated = true;
                }
            }

            if (!file_exists(FCPATH . USER_IMG_PATH)) {
                mkdir(FCPATH . USER_IMG_PATH);
            }

            $config = [
                'upload_path' =>  FCPATH . USER_IMG_PATH,
                'allowed_types' => 'jpeg|gif|jpg|png',
            ];

            $image_new_name = '';
            $image_info_error = '';

            if (!empty($_FILES['image']['name']) && isset($_FILES['image']['name'])) {
                $this->upload->initialize($config);
                if ($this->upload->do_upload('image')) {
                    $image_data = $this->upload->data();
                    $image_new_name = $image_data['file_name'];
                    resize_image($image_data, FCPATH . USER_IMG_PATH);
                } else {
                    $image_info_error = 'Profile Image :' . $this->upload->display_errors();
                }

                if ($image_info_error != NULL || !$this->form_validation->run()) {
                    if (isset($image_new_name) && $image_new_name != NULL) {
                        unlink(FCPATH . USER_IMG_PATH . $image_new_name);
                    }
                }
            }

            if (isset($image_info_error) && !empty($image_info_error)) {
                $response['error'] = true;
                $response['message'] = $image_info_error;
                echo json_encode($response);
                return;
            }

            $set = [];
            if (isset($_POST['username']) && !empty($_POST['username'])) {
                $set['username'] = $this->input->post('username', true);
            }
            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $set['email'] = $this->input->post('email', true);
            }
            if (isset($_POST['dob']) && !empty($_POST['dob'])) {
                $set['dob'] = $this->input->post('dob', true);
            }
            if (isset($_POST['mobile']) && !empty($_POST['mobile'])) {
                $set['mobile'] = $this->input->post('mobile', true);
            }
            if (isset($_POST['address']) && !empty($_POST['address'])) {
                $set['address'] = $this->input->post('address', true);
            }
            if (isset($_POST['city']) && !empty($_POST['city'])) {
                $set['city'] = $this->input->post('city', true);
            }
            if (isset($_POST['area']) && !empty($_POST['area'])) {
                $set['area'] = $this->input->post('area', true);
            }
            if (isset($_POST['pincode']) && !empty($_POST['pincode'])) {
                $set['pincode'] = $this->input->post('pincode', true);
            }
            if (isset($_POST['latitude']) && !empty($_POST['latitude'])) {
                $set['latitude'] = $this->input->post('latitude', true);
            }
            if (isset($_POST['longitude']) && !empty($_POST['longitude'])) {
                $set['longitude'] = $this->input->post('longitude', true);
            }

            if (!empty($_FILES['image']['name']) && isset($_FILES['image']['name'])) {
                $set['image'] = $image_new_name;
            }

            if (!empty($set)) {
                $set = escape_array($set);
                $this->db->set($set)->where('id', $_POST['user_id'])->update($tables['login_users']);
                $user_details = fetch_details('users', ['id' => $_POST['user_id']], "*");

                foreach ($user_details as $row) {
                    $row = output_escaping($row);
                    $tempRow['id'] = (isset($row['id']) && !empty($row['id'])) ? $row['id'] : '';
                    $tempRow['ip_address'] = (isset($row['ip_address']) && !empty($row['ip_address'])) ? $row['ip_address'] : '';
                    $tempRow['username'] = (isset($row['username']) && !empty($row['username'])) ? $row['username'] : '';
                    $tempRow['password'] = (isset($row['password']) && !empty($row['password'])) ? $row['password'] : '';
                    $tempRow['email'] = (isset($row['email']) && !empty($row['email'])) ? $row['email'] : '';
                    $tempRow['mobile'] = (isset($row['mobile']) && !empty($row['mobile'])) ? $row['mobile'] : '';
                    if (empty($row['image']) || file_exists(FCPATH . USER_IMG_PATH . $row['image']) == FALSE) {
                        $tempRow['image'] = base_url() . NO_IMAGE;
                    } else {
                        $tempRow['image'] = base_url() . USER_IMG_PATH . $row['image'];
                    }
                    $tempRow['image_sm'] = get_image_url(base_url() . USER_IMG_PATH . $row[0][0]['image'], 'thumb', 'sm');
                    $tempRow['balance'] = (isset($row['balance']) && !empty($row['balance'])) ? $row['balance'] : '0';
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
                    $tempRow['cash_received'] = (isset($row['cash_received']) && !empty($row['cash_received'])) ? $row['cash_received'] : '0.00';
                    $tempRow['dob'] = (isset($row['dob']) && !empty($row['dob'])) ? $row['dob'] : '';
                    $tempRow['country_code'] = (isset($row['country_code']) && !empty($row['country_code'])) ? $row['country_code'] : '';
                    $tempRow['city'] = (isset($row['city']) && !empty($row['city'])) ? $row['city'] : '';
                    $tempRow['area'] = (isset($row['area']) && !empty($row['area'])) ? $row['area'] : '';
                    $tempRow['street'] = (isset($row['street']) && !empty($row['street'])) ? $row['street'] : '';
                    $tempRow['pincode'] = (isset($row['pincode']) && !empty($row['pincode'])) ? $row['pincode'] : '';
                    $tempRow['serviceable_zipcodes'] = (isset($row['serviceable_zipcodes']) && !empty($row['serviceable_zipcodes'])) ? $row['serviceable_zipcodes'] : '';
                    $tempRow['apikey'] = (isset($row['apikey']) && !empty($row['apikey'])) ? $row['apikey'] : '';
                    $tempRow['referral_code'] = (isset($row['referral_code']) && !empty($row['referral_code'])) ? $row['referral_code'] : '';
                    $tempRow['friends_code'] = (isset($row['friends_code']) && !empty($row['friends_code'])) ? $row['friends_code'] : '';
                    $tempRow['fcm_id'] = (isset($row['fcm_id']) && !empty($row['fcm_id'])) ? $row['fcm_id'] : '';
                    $tempRow['latitude'] = (isset($row['latitude']) && !empty($row['latitude'])) ? $row['latitude'] : '';
                    $tempRow['longitude'] = (isset($row['longitude']) && !empty($row['longitude'])) ? $row['longitude'] : '';
                    $tempRow['created_at'] = (isset($row['created_at']) && !empty($row['created_at'])) ? $row['created_at'] : '';
                    $rows[] = $tempRow;
                }

                $response['error'] = false;
                $response['message'] = 'Profile Update Succesfully';
                $response['data'] = $rows;
                echo json_encode($response);
                return;
            } else if ($is_updated == true) {
                $user_details = fetch_details('users', ['id' => $_POST['user_id']], "*");
                foreach ($user_details as $row) {
                    $row = output_escaping($row);
                    $tempRow['id'] = (isset($row['id']) && !empty($row['id'])) ? $row['id'] : '';
                    $tempRow['ip_address'] = (isset($row['ip_address']) && !empty($row['ip_address'])) ? $row['ip_address'] : '';
                    $tempRow['username'] = (isset($row['username']) && !empty($row['username'])) ? $row['username'] : '';
                    $tempRow['password'] = (isset($row['password']) && !empty($row['password'])) ? $row['password'] : '';
                    $tempRow['email'] = (isset($row['email']) && !empty($row['email'])) ? $row['email'] : '';
                    $tempRow['mobile'] = (isset($row['mobile']) && !empty($row['mobile'])) ? $row['mobile'] : '';
                    if (empty($row[0]['image']) || file_exists(FCPATH . USER_IMG_PATH . $row[0]['image']) == FALSE) {
                        $tempRow['image'] = base_url() . NO_IMAGE;
                    } else {
                        $tempRow['image'] = base_url() . USER_IMG_PATH . $row[0]['image'];
                    }
                    $tempRow['image_sm'] = get_image_url(base_url() . USER_IMG_PATH . $row[0][0]['image'], 'thumb', 'sm');
                    $tempRow['balance'] = (isset($row['balance']) && !empty($row['balance'])) ? $row['balance'] : '0';
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
                    $tempRow['cash_received'] = (isset($row['cash_received']) && !empty($row['cash_received'])) ? $row['cash_received'] : '0.00';
                    $tempRow['dob'] = (isset($row['dob']) && !empty($row['dob'])) ? $row['dob'] : '';
                    $tempRow['country_code'] = (isset($row['country_code']) && !empty($row['country_code'])) ? $row['country_code'] : '';
                    $tempRow['city'] = (isset($row['city']) && !empty($row['city'])) ? $row['city'] : '';
                    $tempRow['area'] = (isset($row['area']) && !empty($row['area'])) ? $row['area'] : '';
                    $tempRow['street'] = (isset($row['street']) && !empty($row['street'])) ? $row['street'] : '';
                    $tempRow['pincode'] = (isset($row['pincode']) && !empty($row['pincode'])) ? $row['pincode'] : '';
                    $tempRow['serviceable_zipcodes'] = (isset($row['serviceable_zipcodes']) && !empty($row['serviceable_zipcodes'])) ? $row['serviceable_zipcodes'] : '';
                    $tempRow['apikey'] = (isset($row['apikey']) && !empty($row['apikey'])) ? $row['apikey'] : '';
                    $tempRow['referral_code'] = (isset($row['referral_code']) && !empty($row['referral_code'])) ? $row['referral_code'] : '';
                    $tempRow['friends_code'] = (isset($row['friends_code']) && !empty($row['friends_code'])) ? $row['friends_code'] : '';
                    $tempRow['fcm_id'] = (isset($row['fcm_id']) && !empty($row['fcm_id'])) ? $row['fcm_id'] : '';
                    $tempRow['latitude'] = (isset($row['latitude']) && !empty($row['latitude'])) ? $row['latitude'] : '';
                    $tempRow['longitude'] = (isset($row['longitude']) && !empty($row['longitude'])) ? $row['longitude'] : '';
                    $tempRow['created_at'] = (isset($row['created_at']) && !empty($row['created_at'])) ? $row['created_at'] : '';
                    $rows[] = $tempRow;
                }

                $response['error'] = false;
                $response['message'] = 'Referel Code Update Succesfully';
                $response['data'] = $rows;
                echo json_encode($response);
                return;
            }
        }
    }

    //delete_user

    public function delete_user()
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
                    if ($user_group[0]['group_id'] == '2') {
                        $status =   'awaiting,received,processed,shipped';
                        $multiple_status = explode(',', $status);
                        $orders = fetch_orders('', $_POST['user_id'],  $multiple_status);

                        foreach ($orders['order_data'] as $order) {
                            if ($this->order_model->update_order(['status' => 'cancelled'], ['id' => $order['id']], true)) {
                                $this->order_model->update_order(['active_status' => 'cancelled'], ['id' => $order['id']], false);
                                if ($this->order_model->update_order(['status' => 'cancelled'], ['order_id' => $order['id']], true, 'order_items')) {
                                    $this->order_model->update_order(['active_status' => 'cancelled'], ['order_id' => $order['id']], false, 'order_items');
                                    process_refund($order['id'], 'cancelled', 'orders');
                                    $data = fetch_details('order_items', ['order_id' => $order['id']], 'product_variant_id,quantity');
                                    $product_variant_ids = [];
                                    $qtns = [];
                                    foreach ($data as $d) {
                                        array_push($product_variant_ids, $d['product_variant_id']);
                                        array_push($qtns, $d['quantity']);
                                    }

                                    update_stock($product_variant_ids, $qtns, 'plus');
                                }
                            }
                        }
                        delete_details(['id' => $_POST['user_id']], 'users');
                        delete_details(['user_id' => $_POST['user_id']], 'users_groups');
                        $response['error'] = false;
                        $response['message'] = 'User Deleted Succesfully';
                    } else if ($user_group[0]['group_id'] == '4') {
                        $login = $this->ion_auth->login($this->input->post('mobile'), $this->input->post('password'), false);
                        if ($login) {
                            $delete = array(
                                "media" => 0,
                                "payment_requests" => 0,
                                "products" => 0,
                                "product_attributes" => 0,
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
                    } else if ($user_group[0]['group_id'] == '3') {
                        delete_details(['id' => $_POST['user_id']], 'users');
                        delete_details(['user_id' => $_POST['user_id']], 'users_groups');
                        $response['error'] = false;
                        $response['message'] = 'Delivery Boy  Deleted Succesfully';
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

    //12. add_to_favorites
    public function add_to_favorites()
    {
        /*
            user_id:15
            product_id:60
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User ID', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('product_id', 'Product Id', 'trim|numeric|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {

            if (is_exist(['user_id' => $_POST['user_id'], 'product_id' => $_POST['product_id']], 'favorites')) {
                $response["error"]   = true;
                $response["message"] = "Already added to favorite !";
                $response["data"] = array();
                echo json_encode($response);
                return false;
            }

            $data = [
                'user_id' => $_POST['user_id'],
                'product_id' => $_POST['product_id'],
            ];
            $data = escape_array($data);
            $this->db->insert('favorites', $data);
            $this->response['error'] = false;
            $this->response['message'] = 'Added to favorite';
            $this->response['data'] = array();
        }
        print_r(json_encode($this->response));
    }

    //remove_from_favorites
    public function remove_from_favorites()
    {
        /*
         user_id:12
         product_id:23 {optional}
        */
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('product_id', 'Product Id', 'trim|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $data = ['user_id' => $_POST['user_id']];
            if (isset($_POST['product_id'])) {
                $data['product_id'] =  $_POST['product_id'];
                if (!is_exist(['user_id' => $_POST['user_id'], 'product_id' => $_POST['product_id']], 'favorites')) {
                    $response["error"]   = true;
                    $response["message"] = "Item not added as favorite !";
                    $response["data"] = array();
                    echo json_encode($response);
                    return false;
                }
            }

            $this->db->delete('favorites', $data);
            $this->response['error'] = false;
            $this->response['message'] = 'Removed from favorite';
            $this->response['data'] = array();
        }
        print_r(json_encode($this->response));
    }

    //get_favorites
    public function get_favorites()
    {
        /*
         user_id:12
         limit : 10 {optional}
         offset: 0 {optional}
        */
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $limit = (isset($_POST['limit'])  && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;

            $q = $this->db->join('products p', 'p.id=f.product_id')
                ->join('product_variants pv', 'pv.product_id=p.id')
                ->where('f.user_id', $_POST['user_id'])
                ->where('p.status', 1)
                ->select('(select count(id) from favorites where user_id=' . $_POST['user_id'] . ') as total, ,f.*')
                ->group_by('f.product_id')
                ->limit($limit, $offset)
                ->get('favorites f');
            $total = 0;
            $res1 = array();
            $res = $q->result_array();
            if (!empty($res)) {
                $total = $res[0]['total'];
                for ($i = 0; $i < count($res); $i++) {
                    unset($res[$i]['total']);
                    $pro_details = fetch_product($_POST['user_id'], null, (isset($res[$i]['product_id'])) ? $res[$i]['product_id'] : null);
                    if (!empty($pro_details)) {
                        $res1[] = $pro_details['product'][0];
                    }
                }
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'No Favourite(s) Product Are Added';
                $this->response['total'] = [];
                $this->response['data'] = [];
                print_r(json_encode($this->response));
                return;
            }
            $this->response['error'] = false;
            $this->response['message'] = 'Data Retrieved Successfully';
            $this->response['total'] = $total;
            $this->response['data'] = $res1;
        }
        print_r(json_encode($this->response));
    }


    //13. add_address
    public function add_address()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
        $this->form_validation->set_rules('country_code', 'Country Code', 'trim|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('alternate_mobile', 'Alternative Mobile', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('landmark', 'Landmark', 'trim|xss_clean');
        $this->form_validation->set_rules('area_id', 'Area', 'trim|xss_clean');
        $this->form_validation->set_rules('city_id', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('pincode', 'Pincode', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('state', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean');
        $this->form_validation->set_rules('latitude', 'Latitude', 'trim|xss_clean');
        $this->form_validation->set_rules('longitude', 'Longitude', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $this->address_model->set_address($_POST);
            $res = $this->address_model->get_address($_POST['user_id'], false, true);
            $this->response['error'] = false;
            $this->response['message'] = 'Address Added Successfully';
            $this->response['data'] = $res;
        }
        print_r(json_encode($this->response));
    }

    //update_address
    public function update_address()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
        $this->form_validation->set_rules('country_code', 'Country Code', 'trim|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('alternate_mobile', 'Alternative Mobile', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('landmark', 'Landmark', 'trim|xss_clean');
        $this->form_validation->set_rules('area_id', 'Area', 'trim|xss_clean');
        $this->form_validation->set_rules('city_id', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('pincode', 'Pincode', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('state', 'State', 'trim|xss_clean');
        $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $this->address_model->set_address($_POST);
            $res = $this->address_model->get_address(null, $_POST['id'], true);
            $this->response['error'] = false;
            $this->response['message'] = 'Address updated Successfully';
            $this->response['data'] = $res;
        }
        print_r(json_encode($this->response));
    }

    //delete_address
    public function delete_address()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $this->address_model->delete_address($_POST);
            $this->response['error'] = false;
            $this->response['message'] = 'Address Deleted Successfully';
            $this->response['data'] = array();
        }
        print_r(json_encode($this->response));
    }

    //get_address
    public function get_address()
    {
        /*
            user_id:3    
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_id', 'User id', 'trim|numeric|xss_clean|required');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $res = $this->address_model->get_address($_POST['user_id']);
            $is_default_counter = array_count_values(array_column($res, 'is_default'));

            if (!isset($is_default_counter['1']) && !empty($res)) {
                update_details(['is_default' => '1'], ['id' => $res[0]['id']], 'addresses');
                $res = $this->address_model->get_address($_POST['user_id']);
            }
            if (!empty($res)) {
                $this->response['error'] = false;
                $this->response['message'] = 'Address Retrieved Successfully';
                $this->response['data'] = $res;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "No Details Found !";
                $this->response['data'] = array();
            }
        }
        print_r(json_encode($this->response));
    }

    //13 get_sections
    public function get_sections()
    {
        /*
                limit:10            // { default - 25 } {optional}
                offset:0            // { default - 0 } {optional}
                user_id:12              {optional}
                section_id:4            {optional}
                attribute_value_ids : 34,23,12 // 
                top_rated_product: 1 // { default - 0 } optional
                p_limit:10          // { default - 10 } {optional}
                p_offset:10         // { default - 0 } {optional}    
                p_sort:pv.price      // { default - pid } {optional}
                p_order:asc         // { default - desc } {optional}
                discount: 5 // { default - 5 } optional
                min_price:10000          // optional
                max_price:50000          // optional
                zipcode:1          // optional

            */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('limit', 'Limit', 'trim|xss_clean');
        $this->form_validation->set_rules('offset', 'Offset', 'trim|xss_clean');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section Id', 'trim|xss_clean');
        $this->form_validation->set_rules('p_limit', 'Product Limit', 'trim|xss_clean');
        $this->form_validation->set_rules('p_offset', 'Product Offset', 'trim|xss_clean');
        $this->form_validation->set_rules('p_sort', 'Product Sort', 'trim|xss_clean');
        $this->form_validation->set_rules('p_order', 'Product Order', 'trim|xss_clean');
        $this->form_validation->set_rules('discount', ' Discount ', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('zipcode', ' Zipcode ', 'trim|xss_clean');
        $this->form_validation->set_rules('min_price', ' Min Price ', 'trim|xss_clean|numeric|less_than_equal_to[' . $this->input->post('max_price') . ']');
        $this->form_validation->set_rules('max_price', ' Max Price ', 'trim|xss_clean|numeric|greater_than_equal_to[' . $this->input->post('min_price') . ']');


        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        }
        $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
        $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
        $user_id = (isset($_POST['user_id']) && !empty(trim($_POST['user_id']))) ? $this->input->post('user_id', true) : 0;
        $section_id = (isset($_POST['section_id']) && !empty(trim($_POST['section_id']))) ? $this->input->post('section_id', true) : 0;
        $filters['attribute_value_ids'] = (isset($_POST['attribute_value_ids'])) ? $_POST['attribute_value_ids'] : null;
        $filters['product_type'] = (isset($_POST['top_rated_product']) && $_POST['top_rated_product'] == 1) ? 'top_rated_product_including_all_products' : null;
        $p_limit = (isset($_POST['p_limit']) && !empty(trim($_POST['p_limit']))) ? $this->input->post('p_limit', true) : 10;
        $p_offset = (isset($_POST['p_offset']) && !empty(trim($_POST['p_offset']))) ? $this->input->post('p_offset', true) : 0;
        $p_order = (isset($_POST['p_order']) && !empty(trim($_POST['p_order']))) ? $_POST['p_order'] : 'DESC';
        $p_sort = (isset($_POST['p_sort']) && !empty(trim($_POST['p_sort']))) ? $_POST['p_sort'] : 'p.id';
        $filters['discount'] = (isset($_POST['discount'])) ? $this->input->post("discount", true) : 0;
        $filters['min_price'] = (isset($_POST['min_price']) && !empty($_POST['min_price'])) ? $this->input->post("min_price", true) : 0;
        $filters['max_price'] = (isset($_POST['max_price']) && !empty($_POST['max_price'])) ? $this->input->post("max_price", true) : 0;
        $zipcode = (isset($_POST['zipcode']) && !empty($_POST['zipcode'])) ? $this->input->post("zipcode", true) : 0;
        if (isset($_POST['zipcode']) && !empty($_POST['zipcode'])) {
            $zipcode = $this->input->post('zipcode', true);
            $is_pincode = is_exist(['zipcode' => $zipcode], 'zipcodes');
            if ($is_pincode) {
                $zipcode_id = fetch_details('zipcodes', ['zipcode' => $zipcode], 'id');
                $zipcode = $zipcode_id[0]['id'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Products Not Found !';
                echo json_encode($this->response);
                return false;
            }
        }
        $this->db->select('*');
        if (isset($_POST['section_id']) && !empty($_POST['section_id'])) {
            $this->db->where('id', $section_id);
        }
        $this->db->limit($limit, $offset);
        $sections = $this->db->order_by('row_order')->get('sections')->result_array();

        if (!empty($sections)) {
            for ($i = 0; $i < count($sections); $i++) {
                $product_ids = explode(',', $sections[$i]['product_ids']);
                $product_ids = array_filter($product_ids);
                $filters['show_only_active_products'] = 1;
                if (isset($_POST['top_rated_product']) && !empty($_POST['top_rated_product'])) {
                    $filters['product_type'] = (isset($_POST['top_rated_product']) && $_POST['top_rated_product'] == 1) ? 'top_rated_product_including_all_products' : null;
                } else {
                    if (isset($sections[$i]['product_type']) && !empty($sections[$i]['product_type'])) {
                        $filters['product_type'] = (isset($sections[$i]['product_type'])) ? $sections[$i]['product_type'] : null;
                    }
                }
                $categories = (isset($sections[$i]['categories']) && !empty($sections[$i]['categories']) && $sections[$i]['categories'] != NULL) ? explode(',', $sections[$i]['categories']) : null;

                $products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids) && !empty($product_ids)) ? $product_ids : null, $categories, $p_limit, $p_offset, $p_sort, $p_order, NULL, $zipcode, NULL);
                if (!empty($products['product'])) {
                    $this->response['error'] = false;
                    $this->response['message'] = "Sections retrived successfully";
                    $this->response['min_price'] = (isset($products['min_price']) && !empty($products['min_price'])) ? strval($products['min_price']) : 0;
                    $this->response['max_price'] = (isset($products['max_price']) && !empty($products['max_price'])) ? strval($products['max_price']) : 0;
                    $sections[$i]['title'] =  output_escaping($sections[$i]['title']);
                    $sections[$i]['short_description'] =  output_escaping($sections[$i]['short_description']);
                    $sections[$i]['total'] =  strval($products['total']);
                    $sections[$i]['filters'] = (isset($products['filters'])) ? $products['filters'] : [];
                    $sections[$i]['product_details'] =  $products['product'];
                    $sections[$i]['product_ids'] = (isset($sections[$i]['product_ids']) && !empty($sections[$i]['product_ids'])) ? $sections[$i]['product_ids'] : '';
                    $sections[$i]['categories'] = (isset($sections[$i]['categories']) && !empty($sections[$i]['categories'])) ? $sections[$i]['categories'] : '';
                    unset($sections[$i]['product_details'][0]['total']);
                } else {
                    $this->response['error'] = false;
                    $this->response['message'] = "Sections retrived successfully";
                    $sections[$i]['total'] = "0";
                    $sections[$i]['product_ids'] = (isset($sections[$i]['product_ids']) && !empty($sections[$i]['product_ids'])) ? $sections[$i]['product_ids'] : '';
                    $sections[$i]['filters'] = [];
                    $sections[$i]['product_details'] =  [];
                }
            }
            $this->response['data'] = $sections;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = "No sections are available";
            $this->response['data'] = array();
        }
        print_r(json_encode($this->response));
    }

    //get_notifications()
    public function get_notifications()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';
            $res = $this->notification_model->get_notifications($offset, $limit, $sort, $order);
            $this->response['error'] = false;
            $this->response['message'] = 'Notification Retrieved Successfully';
            $this->response['total'] = $res['total'];
            $this->response['data'] = $res['data'];
        }
        print_r(json_encode($this->response));
    }

    public function get_paypal_link()
    {
        /*
            user_id : 2
            order_id : 1
            amount : 150
        */

        $this->form_validation->set_rules('user_id', 'User ID', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $user_id = $_POST['user_id'];
            $order_id = $_POST['order_id'];
            $amount = $_POST['amount'];
            if (!is_numeric($order_id)) {
                $this->response['error'] = false;
                $this->response['message'] = 'Order created for wallet!';
                $this->response['data'] = base_url('app/v1/api/paypal_transaction_webview?' . 'user_id=' . $user_id . '&order_id=' . $order_id . '&amount=' . $amount);
                print_r(json_encode($this->response));
                return false;
            }
            $this->response['error'] = false;
            $this->response['message'] = 'Order Detail Founded !';
            $this->response['data'] = base_url('app/v1/api/paypal_transaction_webview?' . 'user_id=' . $user_id . '&order_id=' . $order_id . '&amount=' . $amount);
        }
        print_r(json_encode($this->response));
    }

    //paypal_transaction_webview()
    public function paypal_transaction_webview()
    {
        /*
            user_id : 2
            order_id : 1
        */

        header("Content-Type: html");

        $this->form_validation->set_data($_GET);

        $this->form_validation->set_rules('user_id', 'User ID', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }

        $user_id = $_GET['user_id'];
        $order_id = $_GET['order_id'];
        $amount = $_GET['amount'];

        $q = $this->db->where('id', $user_id)->get('users')->result_array();
        if (empty($q) && !isset($q)) {
            echo "user error update";
            return false;
        }

        $order_res = $this->db->where('id', $order_id)->get('orders')->result_array();
        if (!empty($order_res)) {

            $data['user'] = $q[0];
            $data['order'] = $order_res[0];
            $data['payment_type'] = "paypal";
            // Set variables for paypal form
            $returnURL = base_url() . 'app/v1/api/app_payment_status';
            $cancelURL = base_url() . 'app/v1/api/app_payment_status';
            $notifyURL = base_url() . 'app/v1/api/ipn';
            $txn_id = time() . "-" . rand();
            // Get current user ID from the session
            $userID = $data['user']['id'];
            $order_id = $data['order']['id'];
            $payeremail = $data['user']['email'];
            // Add fields to paypal form
            $this->paypal_lib->add_field('return', $returnURL);
            $this->paypal_lib->add_field('cancel_return', $cancelURL);
            $this->paypal_lib->add_field('notify_url', $notifyURL);
            $this->paypal_lib->add_field('item_name', 'Test');
            $this->paypal_lib->add_field('custom', $userID . '|' . $payeremail);
            $this->paypal_lib->add_field('item_number', $order_id);
            $this->paypal_lib->add_field('amount', $amount);
            // Render paypal form
            $this->paypal_lib->paypal_auto_form();
        } else {
            $data['user'] = $q[0];
            $data['payment_type'] = "paypal";
            // Set variables for paypal form
            $returnURL = base_url() . 'app/v1/api/app_payment_status';
            $cancelURL = base_url() . 'app/v1/api/app_payment_status';
            $notifyURL = base_url() . 'app/v1/api/ipn';
            $txn_id = time() . "-" . rand();
            // Get current user ID from the session
            $userID = $data['user']['id'];
            $order_id = $order_id;
            $payeremail = $data['user']['email'];

            $this->paypal_lib->add_field('return', $returnURL);
            $this->paypal_lib->add_field('cancel_return', $cancelURL);
            $this->paypal_lib->add_field('notify_url', $notifyURL);
            $this->paypal_lib->add_field('item_name', 'Online shopping');
            $this->paypal_lib->add_field('custom', $userID . '|' . $payeremail);
            $this->paypal_lib->add_field('item_number', $order_id);
            $this->paypal_lib->add_field('amount', $amount);
            // Render paypal form
            $this->paypal_lib->paypal_auto_form();
        }
    }

    public function app_payment_status()
    {
        $paypalInfo = $this->input->get();

        if (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "completed") {
            $response['error'] = false;
            $response['message'] = "Payment Completed Successfully";
            $response['data'] = $paypalInfo;
        } elseif (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "authorized") {
            $response['error'] = false;
            $response['message'] = "Your payment is has been Authorized successfully. We will capture your transaction within 30 minutes, once we process your order. After successful capture coins wil be credited automatically.";
            $response['data'] = $paypalInfo;
        } elseif (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "Pending") {
            $response['error'] = false;
            $response['message'] = "Your payment is pending and is under process. We will notify you once the status is updated.";
            $response['data'] = $paypalInfo;
        } else {
            $response['error'] = true;
            $response['message'] = "Payment Cancelled / Declined ";
            $response['data'] = (isset($_GET)) ? $this->input->get() : "";
        }
        print_r(json_encode($response));
    }

    public function ipn()
    {
        // Paypal posts the transaction data
        $paypalInfo = $this->input->post();
        if (!empty($paypalInfo)) {
            // Validate and get the ipn response
            $ipnCheck = $this->paypal_lib->validate_ipn($paypalInfo);

            // Check whether the transaction is valid
            if ($ipnCheck) {

                $order_id = $paypalInfo["item_number"];
                /* if its not numeric then it is for the wallet recharge */
                if (
                    $paypalInfo["payment_status"] == 'Completed' &&
                    !is_numeric($order_id) && strpos($order_id, "wallet-refill-user") !== false
                ) {
                    $temp = explode("-", $order_id);   /* Order ID format for wallet refill >> wallet-refill-user-{user_id}-{system_time}-{3 random_number}  */
                    if (isset($temp[3]) && is_numeric($temp[3]) && !empty($temp[3] && $temp[3] != '')) {
                        $user_id = $temp[3];
                    } else {
                        $user_id = 0;
                    }
                    $amount = $paypalInfo["mc_gross"];
                    /* IPN for user wallet recharge */
                    $data['transaction_type'] = "wallet";
                    $data['user_id'] = $user_id;
                    $data['order_id'] = $order_id;
                    $data['type'] = "credit";
                    $data['txn_id'] = $paypalInfo["txn_id"];
                    $data['amount'] = $amount;
                    $data['status'] = "success";
                    $data['message'] = "Wallet refill successful";
                    $this->transaction_model->add_transaction($data);

                    $this->load->model('customer_model');
                    if ($this->customer_model->update_balance($amount, $user_id, 'add')) {
                        $response['error'] = false;
                        $response['transaction_status'] = "success";
                        $response['message'] = "Wallet recharged successfully!";
                    } else {
                        $response['error'] = true;
                        $response['transaction_status'] = "success";
                        $response['message'] = "Wallet could not be recharged!";
                        log_message('error', 'Paypal IPN | wallet recharge failure --> ' . var_export($paypalInfo, true));
                    }
                    echo json_encode($response);
                    return false;
                } else {
                    /* IPN for normal Order  */
                    // Insert the transaction data in the database
                    $userData = explode('|', $paypalInfo['custom']);

                    $data['transaction_type'] = 'Transaction';
                    $data['user_id'] = $userData[0];
                    $data['payer_email']  = $userData[1];
                    $data['order_id'] = $paypalInfo["item_number"];
                    $data['type'] = 'paypal';
                    $data['txn_id'] = $paypalInfo["txn_id"];
                    $data['amount'] = $paypalInfo["mc_gross"];
                    $data['currency_code'] = $paypalInfo["mc_currency"];
                    $data['status'] = 'success';
                    $data['message'] = 'Payment Verified';
                    if ($paypalInfo["payment_status"] == 'Completed') {

                        $user = fetch_details('users', ['id' => $userData[0]]);
                        $system_settings = get_settings('system_settings', true);
                        $orders = fetch_orders($paypalInfo["item_number"], $userData[0], false, false, false, false, false, false);

                        $overall_total = array(
                            'total_amount' => $orders['order_data'][0]['total'],
                            'delivery_charge' => $orders['order_data'][0]['delivery_charge'],
                            'tax_amount' => $orders['order_data'][0]['total_tax_amount'],
                            'tax_percentage' => $orders['order_data'][0]['total_tax_percent'],
                            'discount' =>  $orders['order_data'][0]['promo_discount'],
                            'wallet' =>  $orders['order_data'][0]['wallet_balance'],
                            'final_total' =>  $orders['order_data'][0]['final_total'],
                            'otp' => $orders['order_data'][0]['otp'],
                            'address' =>  $orders['order_data'][0]['address'],
                            'payment_method' => $orders['order_data'][0]['payment_method']
                        );

                        $overall_order_data = array(
                            'cart_data' => $orders['order_data'][0]['order_items'],
                            'order_data' => $overall_total,
                            'subject' => 'Order received successfully',
                            'user_data' => $user[0],
                            'system_settings' => $system_settings,
                            'user_msg' => 'Hello, Dear ' . ucfirst($user[0]['username']) . ', We have received your order successfully. Your order summaries are as followed',
                            'otp_msg' => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
                        );

                        send_mail($userData[1], 'Order received successfully', $this->load->view('admin/pages/view/email-template.php', $overall_order_data, TRUE));

                        $this->transaction_model->add_transaction($data);

                        update_details(['active_status' => 'received'], ['order_id' => $data['order_id']], 'order_items');

                        $status = json_encode(array(array('received', date("d-m-Y h:i:sa"))));
                        update_details(['status' => $status], ['order_id' => $data['order_id']], 'order_items', false);

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
                        $user_fcm = fetch_details('users', ['id' => $data['user_id']], 'fcm_id');
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
                    } else if (
                        $paypalInfo["payment_status"] == 'Expired' || $paypalInfo["payment_status"] == 'Failed'
                        || $paypalInfo["payment_status"] == 'Refunded' || $paypalInfo["payment_status"] == 'Reversed'
                    ) {
                        /* if transaction wasn't completed successfully then cancel the order and transaction */
                        $data['transaction_type'] = 'Transaction';
                        $data['user_id'] = $userData[0];
                        $data['payer_email']  = $userData[1];
                        $data['order_id'] = $paypalInfo["item_number"];
                        $data['type'] = 'paypal';
                        $data['txn_id'] = $paypalInfo["txn_id"];
                        $data['amount'] = $paypalInfo["mc_gross"];
                        $data['currency_code'] = $paypalInfo["mc_currency"];
                        $data['status'] = $paypalInfo["payment_status"];
                        $data['message'] = 'Payment could not be completed due to one or more reasons!';
                        $this->transaction_model->add_transaction($data);
                        update_details(['active_status' => 'cancelled'], ['order_id' => $data['order_id']], 'order_items');

                        $status = json_encode(array(array('cancelled', date("d-m-Y h:i:sa"))));
                        update_details(['status' => $status], ['order_id' => $data['order_id']], 'order_items', false);
                    }
                }
            }
        }
    }

    //15. add_transaction
    public function add_transaction()
    {
        /*
            transaction_type : transaction / wallet  // { optional - default is transaction }
            user_id : 15 
            order_id:  23
            type : COD / stripe / razorpay / paypal / paystack / flutterwave - for transaction | credit / debit - for wallet
            payment_method:razorpay / paystack / flutterwave        // used for waller credit option, required when transaction_type - wallet and type - credit
            txn_id : 201567892154 
            amount : 450
            status : success / failure
            message : Done 
            skip_verify_transaction:false   // { optional }
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('transaction_type', 'Transaction Type', 'trim|xss_clean');
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('order_id', 'order id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('txn_id', 'Txn', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', 'message', 'trim|required|xss_clean');
        $this->form_validation->set_rules('skip_verify_transaction', 'skip_verify_transaction', 'trim|xss_clean');
        if (isset($_POST['transaction_type']) && $_POST['transaction_type'] == "wallet" && $_POST['type'] == "credit") {
            $this->form_validation->set_rules('payment_method', 'Payment method', 'trim|required|xss_clean');
        }

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            // $user_id = (isset($_POST['user_id']) && is_numeric($_POST['user_id']) && !empty(trim($_POST['user_id']))) ? $this->input->post('user_id', true) : "";
            // $id = (isset($_POST['id']) && is_numeric($_POST['id']) && !empty(trim($_POST['id']))) ? $this->input->post('id', true) : "";
            // $transaction_type = (isset($_POST['transaction_type']) && !empty(trim($_POST['transaction_type']))) ? $this->input->post('transaction_type', true) : "transaction";
            $type = (isset($_POST['type']) && !empty(trim($_POST['type']))) ? $this->input->post('type', true) : "";
            $txn_id = (isset($_POST['txn_id']) && !empty(trim($_POST['txn_id']))) ? $this->input->post('txn_id', true) : "";
            $status = (isset($_POST['status']) && !empty(trim($_POST['status']))) ? $this->input->post('status', true) : "";
            $user_id = $this->input->post('user_id', true);
            $txn_id = $this->input->post('txn_id', true);
            $amount = $this->input->post('amount', true);

            // // $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            // // $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            // // $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            // // $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            // // $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';
            // $res = $this->transaction_model->get_transactions($id, $user_id, $transaction_type, $type, $search="", $offset=0, $limit=1, $sort="id", $order="DESC");
            /* if it's a wallet credit transaction then verify the payment with the help of txn_id */
            if (isset($_POST['transaction_type']) && $_POST['transaction_type'] == "wallet" && $_POST['type'] == "credit") {
                $payment_method = $this->input->post('payment_method', true);
                $payment_method = strtolower($payment_method);
                // $txn_id = $this->input->post('txn_id', true);
                //$user_id = $this->input->post('user_id', true);

                $user = fetch_users($user_id);
                if (empty($user)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "User not found!";
                    $this->response['data'] = [];
                    print_r(json_encode($this->response));
                    return false;
                }
                $old_balance = $user[0]['balance'];
                $skip_verify_transaction = (isset($_POST['skip_verify_transaction'])) ? $_POST['skip_verify_transaction'] : false;

                /* check if this transaction has already been added or not in transactions table */
                $transaction = fetch_details('transactions', ['txn_id' => $txn_id]);

                if (empty($transaction) || (isset($transaction[0]['status']) && strtolower($transaction[0]['status']) != 'success')) {

                    if ($skip_verify_transaction == false) {
                        $payment = verify_payment_transaction($txn_id, $payment_method); /* calling all in one verify payment transaction function */
                        if ($payment['error'] == false) {
                            $this->load->model('customer_model');
                            if (!$this->customer_model->update_balance($payment['amount'], $user_id, 'add')) {
                                $this->response['error'] = true;
                                $this->response['message'] = "Wallet could not be recharged due to database operation failure";
                                $this->response['amount'] = $payment['amount'];
                                $this->response['old_balance'] = "$old_balance";
                                $this->response['new_balance'] = "$old_balance";
                                $this->response['data'] = $payment['data'];
                                print_r(json_encode($this->response));
                                return false;
                            }
                            $new_balance = $old_balance + $payment['amount'];

                            $this->response['amount'] = $payment['amount'];
                            $this->response['old_balance'] = "$old_balance";
                            $this->response['new_balance'] = "$new_balance";
                            $_POST['message'] = "$payment_method - Wallet credited on successful payment confirmation.";
                            $_POST['amount'] = $payment['amount'];
                        } else {
                            $new_balance = $old_balance + $payment['amount'];
                            $this->response['error'] = true;
                            $this->response['message'] = "Wallet could not be recharged! " . $payment['message'];
                            $this->response['amount'] = $payment['amount'];
                            $this->response['old_balance'] = "$old_balance";
                            $this->response['new_balance'] = "$new_balance";
                            $this->response['data'] = [];
                            print_r(json_encode($this->response));
                            return false;
                        }
                    } else {
                        $this->response['error'] = false;
                        $this->response['message'] = "Wallet credited on successful payment confirmation.";
                        // $this->response['data'] = [];
                        $this->response['data'] = $skip_verify_transaction;

                        print_r(json_encode($this->response));
                        return false;
                    }
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = "Wallet could not be recharged! Transaction has already been added before";
                    $this->response['amount'] = 0;
                    $this->response['old_balance'] = "$old_balance";
                    $this->response['new_balance'] = "$old_balance";
                    //$this->response['data'] = [];
                    $this->response['data'] = $transaction;

                    print_r(json_encode($this->response));
                    return false;
                }
            }

            $transaction_type = (isset($_POST['transaction_type']) && !empty($_POST['transaction_type'])) ? $_POST['transaction_type'] : "transaction";
            //$this->transaction_model->add_transaction($_POST);
            $order_item_id = fetch_details('order_items', ['order_id' => $_POST['order_id']], 'id,sub_total');

            for ($i = 0; $i < count($order_item_id); $i++) {
                $_POST['order_item_id'] = $order_item_id[$i]['id'];
                $trans_data = [
                    'transaction_type' => $transaction_type,
                    'user_id' => $user_id,
                    'order_id' => $_POST['order_id'],
                    'order_item_id' => $_POST['order_item_id'],
                    'type' => $type,
                    'txn_id' =>  $txn_id,
                    'amount' => $amount,
                    'status' => $status,
                    'message' =>  $this->response['message'],
                ];
            }

            $res = $this->transaction_model->add_transaction($trans_data);


            // $res = $this->transaction_model->get_transactions($user_id, $transaction_type);
            $this->response['error'] = false;
            $this->response['message'] = ($transaction_type == "wallet") ? 'Wallet Transaction Added Successfully' : 'Transaction Added Successfully';
            // $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : $transaction;
            $this->response['data'] = $res;
        }
        print_r(json_encode($this->response));
    }

    //16. get_offer_images
    public function get_offer_images()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $res = fetch_details('offers', '');
        $i = 0;
        foreach ($res as $row) {
            $res[$i]['image'] = base_url($res[$i]['image']);

            if (strtolower($res[$i]['type']) == 'categories') {
                $id = (!empty($res[$i]['type_id']) && isset($res[$i]['type_id'])) ? $res[$i]['type_id'] : '';
                $cat_res = $this->category_model->get_categories($id);
                $res[$i]['data']  =  $cat_res;
            } else if (strtolower($res[$i]['type']) == 'products') {
                $id = (!empty($res[$i]['type_id']) && isset($res[$i]['type_id'])) ? $res[$i]['type_id'] : '';
                $pro_res = fetch_product(NULL, NULL, $id);
                $res[$i]['data']  =  $pro_res['product'];
            } else {
                $res[$i]['data']  =  [];
            }

            $i++;
        }
        $this->response['error'] = false;
        $this->response['data'] = $res;
        print_r(json_encode($this->response));
    }

    //17. get_faqs
    public function get_faqs()
    {
        if (!$this->verify_token()) {
            return false;
        }

        /*
            limit:25                // { default - 25 } optional
            offset:0                // { default - 0 } optional
            sort: id   			    // { default - id } optional
            order:DESC/ASC          // { default - DESC } optional
        */

        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';
            $res = $this->faq_model->get_faqs($offset, $limit, $sort, $order);
            $this->response['error'] = false;
            $this->response['message'] = 'FAQ(s) Retrieved Successfully';
            $this->response['total'] = $res['total'];
            $this->response['data'] = $res['data'];
        }

        print_r(json_encode($this->response));
    }

    public function stripe_webhook()
    {
        $this->load->library(['stripe']);
        $system_settings = get_settings('system_settings', true);
        $credentials = $this->stripe->get_credentials();
        $request_body = file_get_contents('php://input');

        $event = json_decode($request_body, FALSE);

        log_message('error', 'Stripe Webhook --> ' . var_export($event, true));

        if (!empty($event->data->object)) {
            $txn_id = (isset($event->data->object->payment_intent)) ? $event->data->object->payment_intent : "";
            if (!empty($txn_id)) {
                $transaction = fetch_details('transactions', ['txn_id' => $txn_id], '*');
                log_message('error', 'transaction --> ' . var_export($transaction, true));

                if (isset($transaction) && !empty($transaction)) {
                    $order_id = $transaction[0]['order_id'];
                    $user_id = $transaction[0]['user_id'];
                } else {
                    $order_id = $event->data->metadata->order_id;
                    $order_data = fetch_orders($order_id);
                    $user_id = $order_data['order_data'][0]['user_id'];
                }
            }
            $amount = $event->data->object->amount;
            $currency = $event->data->object->currency;
            $balance_transaction = $event->data->object->balance_transaction;
        } else {
            $order_id = 0;
            $amount = 0;
            $currency = (isset($event->data->object->currency)) ? $event->data->object->currency : "";
            $balance_transaction = 0;
        }


        /* Wallet refill has unique format for order ID - wallet-refill-user-{user_id}-{system_time}-{3 random_number}  */
        if (empty($order_id)) {
            $order_id = (!empty($event->data->object->metadata) && isset($event->data->object->metadata->order_id)) ? $event->data->object->metadata->order_id : 0;
        }

        if (!is_numeric($order_id) && strpos($order_id, "wallet-refill-user") !== false) {
            $temp = explode("-", $order_id);
            if (isset($temp[3]) && is_numeric($temp[3]) && !empty($temp[3] && $temp[3] != '')) {
                $user_id = $temp[3];
            } else {
                $user_id = 0;
            }
        }

        $http_stripe_signature = isset($_SERVER['HTTP_STRIPE_SIGNATURE']) ? $_SERVER['HTTP_STRIPE_SIGNATURE'] : "";
        $result = $this->stripe->construct_event($request_body, $http_stripe_signature, $credentials['webhook_key']);


        if ($result == "Matched") {
            if ($event->type == 'charge.succeeded') {
                if (!empty($order_id)) {
                    /* To do the wallet recharge if the order id is set in the above mentioned pattern */
                    if (strpos($order_id, "wallet-refill-user") !== false) {
                        $data['transaction_type'] = "wallet";
                        $data['user_id'] = $user_id;
                        $data['order_id'] = $order_id;
                        $data['type'] = "credit";
                        $data['txn_id'] = $txn_id;
                        $data['amount'] = $amount / 100;
                        $data['status'] = "success";
                        $data['message'] = "Wallet refill successful";
                        $this->transaction_model->add_transaction($data);

                        $this->load->model('customer_model');
                        if ($this->customer_model->update_balance($amount / 100, $user_id, 'add')) {
                            $response['error'] = false;
                            $response['transaction_status'] = $event->type;
                            $response['message'] = "Wallet recharged successfully!";
                        } else {
                            $response['error'] = true;
                            $response['transaction_status'] = $event->type;
                            $response['message'] = "Wallet could not be recharged!";
                            log_message('error', 'Stripe Webhook | wallet recharge failure --> ' . var_export($event, true));
                        }
                        echo json_encode($response);
                        return false;
                        exit();
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
                                update_details(['status' => 'success'], ['txn_id' => $txn_id], 'transactions');
                            } else {
                                /* add transaction of the payment */
                                $amount = ($event->data->object->amount / 100);
                                $data = [
                                    'transaction_type' => 'transaction',
                                    'user_id' => $user_id,
                                    'order_id' => $order_id,
                                    'type' => 'stripe',
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
                        }
                    }
                } else {
                    /* No order ID found / sending 304 error to payment gateway so it retries wenhook after sometime*/
                    log_message('error', 'Stripe Webhook | Order id not found --> ' . var_export($event, true));
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(304)
                        ->set_output(json_encode(array(
                            'message' => '304 Not Modified - order/transaction id not found',
                            'error' => true
                        )));
                }
                $response['error'] = false;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction successfully done";
                echo json_encode($response);
                return false;
                exit();
            } elseif ($event->type == 'charge.failed') {
                $order = fetch_orders($order_id, false, false, false, false, false, false, false);
                if (!empty($order_id)) {
                    update_details(['active_status' => 'cancelled'], ['order_id' => $order_id], 'order_items');
                    //update_stock($order['order_data'][0]['product_variant_ids'], $order['order_data'][0]['quantity'], 'plus');
                }
                /* No need to add because the transaction is already added just update the transaction status */
                if (!empty($transaction)) {
                    $transaction_id = $transaction[0]['id'];
                    update_details(['status' => 'failed'], ['id' => $transaction_id], 'transactions');
                }
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction is failed. ";
                log_message('error', 'Stripe Webhook | Transaction is failed --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
                exit();
            } elseif ($event->type == 'charge.pending') {
                $response['error'] = false;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Waiting customer to finish transaction ";
                log_message('error', 'Stripe Webhook | Waiting customer to finish transaction --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
                exit();
            } elseif ($event->type == 'charge.expired') {
                if (!empty($order_id)) {
                    update_details(['active_status' => 'cancelled'], ['order_id' => $order_id], 'order_items');
                }
                /* No need to add because the transaction is already added just update the transaction status */
                if (!empty($transaction)) {
                    $transaction_id = $transaction[0]['id'];
                    update_details(['status' => 'expired'], ['id' => $transaction_id], 'transactions');
                }
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction is expired.";
                log_message('error', 'Stripe Webhook | Transaction is expired --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
                exit();
            } elseif ($event->type == 'charge.refunded') {
                if (!empty($order_id)) {
                    update_details(['active_status' => 'cancelled'], ['order_id' => $order_id], 'order_items');
                }
                /* No need to add because the transaction is already added just update the transaction status */
                if (!empty($transaction)) {
                    $transaction_id = $transaction[0]['id'];
                    update_details(['status' => 'refunded'], ['id' => $transaction_id], 'transactions');
                }
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction is refunded.";
                log_message('error', 'Stripe Webhook | Transaction is refunded --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
                exit();
            } else {
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction could not be detected.";
                log_message('error', 'Stripe Webhook | Transaction could not be detected --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
                exit();
            }
        } else {
            log_message('error', 'Stripe Webhook | Invalid Server Signature  --> ' . var_export($result, true));
            return false;
            exit();
        }
    }

    public function transactions()
    {
        /*
            user_id:73 
            id: 1001                // { optional}
            transaction_type:transaction / wallet //razorpay { default - transaction } optional
            type : COD / stripe / razorpay / paypal / paystack /refund/ flutterwave - for transaction | credit / debit - for wallet // { optional }
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
            $transaction_type = (isset($_POST['transaction_type']) && !empty(trim($_POST['transaction_type']))) ? $this->input->post('transaction_type', true) : "transaction";
            $type = (isset($_POST['type']) && !empty(trim($_POST['type']))) ? $this->input->post('type', true) : "";
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';
            $res = $this->transaction_model->get_transactions($id, $user_id, $transaction_type, $type, $search, $offset, $limit, $sort, $order);
            $this->response['error'] = false;
            $this->response['message'] = 'Transactions Retrieved Successfully';
            $this->response['total'] = $res['total'];
            $this->response['balance'] = get_user_balance($user_id);
            $this->response['data'] = $res['data'];
        }

        print_r(json_encode($this->response));
    }

    public function generate_paytm_checksum()
    {
        /*
            order_id:1001
            amount:1099
            user_id:73              //{ optional } 
            industry_type:Industry  //{ optional } 
            channel_id:WAP          //{ optional }
            website:website link    //{ optional }
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->load->library(['paytm']);
        $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|xss_clean');
        $this->form_validation->set_rules('industry_type', 'Industry Type', 'trim|xss_clean');
        $this->form_validation->set_rules('channel_id', 'Channel ID', 'trim|xss_clean');
        $this->form_validation->set_rules('website', 'Website', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $settings = get_settings('payment_method', true);
            $credentials = $this->paytm->get_credentials();

            $paytm_params["MID"] = $settings['paytm_merchant_id'];

            $paytm_params["ORDER_ID"] = $this->input->post('order_id', true);
            $paytm_params["TXN_AMOUNT"] = $this->input->post('amount', true);
            $paytm_params["CUST_ID"] = $this->input->post('user_id', true);
            $paytm_params["WEBSITE"] = $this->input->post('website', true);
            $paytm_params["CALLBACK_URL"] = $credentials['url'] . "theia/paytmCallback?ORDER_ID=" . $paytm_params["ORDER_ID"];

            /**
             * Generate checksum by parameters we have
             * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
             */
            $paytm_checksum = $this->paytm->generateSignature($paytm_params, $settings['paytm_merchant_key']);

            // echo sprintf("generateSignature Returns: %s\n", $paytm_checksum);
            if (!empty($paytm_checksum)) {
                $response['error'] = false;
                $response['message'] = "Checksum created successfully";
                $response['order id'] = $paytm_params["ORDER_ID"];
                $response['data'] = $paytm_params;
                $response['signature'] = $paytm_checksum;
                print_r(json_encode($response));
                return false;
            } else {
                $response['error'] = true;
                $response['message'] = "Data not found!";
                print_r(json_encode($response));
                return false;
            }
        }
    }

    public function generate_paytm_txn_token()
    {
        /*
            amount:100.00
            order_id:102
            user_id:73
            industry_type:      //{optional}
            channel_id:      //{optional}
            website:      //{optional}
        */
        $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('industry_type', 'Industry Type', 'trim|xss_clean');
        $this->form_validation->set_rules('channel_id', 'Channel ID', 'trim|xss_clean');
        $this->form_validation->set_rules('website', 'Website', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->load->library('paytm');
            $credentials = $this->paytm->get_credentials();
            $order_id = $_POST['order_id'];
            $amount = $_POST['amount'];
            $user_id = $_POST['user_id'];
            $paytmParams = array();

            $paytmParams["body"] = array(
                "requestType"   => "Payment",
                "mid"           => $credentials['paytm_merchant_id'],
                "websiteName"   => "WEBSTAGING",
                "orderId"       => $order_id,
                "callbackUrl"   => $credentials['url'] . "theia/paytmCallback?ORDER_ID=" . $order_id,
                "txnAmount"     => array(
                    "value"     => $amount,
                    "currency"  => "INR",
                ),
                "userInfo"      => array(
                    "custId"    => $user_id,
                ),
            );

            /*
            * Generate checksum by parameters we have in body
            * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
            */
            $checksum = $this->paytm->generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $credentials['paytm_merchant_key']);

            $paytmParams["head"] = array(
                "signature"    => $checksum
            );

            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

            /* for Staging */
            $url = $credentials['url'] . "/theia/api/v1/initiateTransaction?mid=" . $credentials['paytm_merchant_id'] . "&orderId=" . $order_id;

            /* for Production */
            // $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            $paytm_response = curl_exec($ch);

            if (!empty($paytm_response)) {
                $paytm_response = json_decode($paytm_response, true);
                if (isset($paytm_response['body']['resultInfo']['resultMsg']) && ($paytm_response['body']['resultInfo']['resultMsg'] == "Success" || $paytm_response['body']['resultInfo']['resultMsg'] == "Success Idempotent")) {
                    $response['error'] = false;
                    $response['message'] = "Transaction token generated successfully";
                    $response['txn_token'] = $paytm_response['body']['txnToken'];
                    $response['paytm_response'] = $paytm_response;
                } else {
                    $response['error'] = true;
                    $response['message'] = $paytm_response['body']['resultInfo']['resultMsg'];
                    $response['txn_token'] = "";
                    $response['paytm_response'] = $paytm_response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Could not generate transaction token. Try again!";
                $response['txn_token'] = "";
                $response['paytm_response'] = $paytm_response;
            }
            print_r(json_encode($response));
        }
    }
    public function validate_paytm_checksum()
    {
        /*
            paytm_checksum:PAYTM_CHECKSUM
            order_id:1001
            amount:1099
            user_id:73              //{ optional } 
            industry_type:Industry  //{ optional } 
            channel_id:WAP          //{ optional }
            website:website link    //{ optional }
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->load->library(['paytm']);
        $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|xss_clean');
        $this->form_validation->set_rules('industry_type', 'Industry Type', 'trim|xss_clean');
        $this->form_validation->set_rules('channel_id', 'Channel ID', 'trim|xss_clean');
        $this->form_validation->set_rules('website', 'Website', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $settings = get_settings('payment_method', true);
            $credentials = $this->paytm->get_credentials();

            $paytm_checksum = $this->input->post('paytm_checksum', true);

            $paytm_params["MID"] = $settings['paytm_merchant_id'];
            $paytm_params["ORDER_ID"] = $this->input->post('order_id', true);
            $paytm_params["TXN_AMOUNT"] = $this->input->post('amount', true);

            $isVerifySignature = $this->paytm->verifySignature($paytm_params, $settings['paytm_merchant_key'], $paytm_checksum);
            if ($isVerifySignature) {
                $this->response['error'] = false;
                $this->response['message'] = "Checksum Matched";
                print_r(json_encode($this->response));
                return false;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Checksum Mismatched";
                print_r(json_encode($this->response));
                return false;
            }
        }
    }

    // validate_refer_code
    public function validate_refer_code()
    {
        /* 
            referral_code:USERS_CODE_TO_BE_VALIDATED
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('referral_code', 'Referral code', 'trim|required|is_unique[users.referral_code]|xss_clean');
        $this->form_validation->set_message('is_unique', 'This %s is already used by some other user.');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
        } else {
            $this->response['error'] = false;
            $this->response['message'] = "Referral Code is available to be used";
        }
        print_r(json_encode($this->response));
        return false;
    }

    public function flutterwave_webview()
    {
        /* 
            amount:100
            user_id:73
            order_id:101 (optional)
            reference:eShop-165232013-400  // { optional }
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('order_id', 'Order ID', 'trim|xss_clean');
        $this->form_validation->set_rules('reference', 'Reference', 'trim|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->load->library('Flutterwave');

            $app_settings = get_settings('system_settings', true);
            $payment_settings = get_settings('payment_method', true);
            $logo = base_url() . get_settings('favicon');
            $user_id = $this->input->post('user_id', true);
            $order_id = $this->input->post('order_id', true);
            $user = fetch_users($user_id);

            if (empty($user) || !isset($user[0]['mobile'])) {
                $response['error'] = true;
                $response['message'] = "User not found!";
                print_r(json_encode($response));
                return false;
            }

            $data['tx_ref'] = (isset($_POST['reference']) && !empty($_POST['reference'])) ? $_POST['reference'] : $app_settings['app_name'] . "-" . time() . "-" . rand(1000, 9999);
            $data['amount'] = $this->input->post('amount', true);
            $data['currency'] = (isset($payment_settings['flutterwave_currency_code']) && !empty($payment_settings['flutterwave_currency_code'])) ? $payment_settings['flutterwave_currency_code'] : "NGN";
            $data['redirect_url'] = base_url('app/v1/api/flutterwave-payment-response');
            $data['payment_options'] = "card";
            $data['meta']['user_id'] = $user_id;
            $data['meta'] = $order_id;
            $data['customer']['email'] = (!empty($user[0]['email'])) ? $user[0]['email'] : $app_settings['support_email'];
            $data['customer']['phonenumber'] = $user[0]['mobile'];
            $data['customer']['name'] = $user[0]['username'];
            $data['customizations']['title'] = $app_settings['app_name'] . " Payments ";
            $data['customizations']['description'] = "Online payments on " . $app_settings['app_name'];
            $data['customizations']['logo'] = (!empty($logo)) ? $logo : "";

            $payment = $this->flutterwave->create_payment($data);
            if (!empty($payment)) {
                $payment = json_decode($payment, true);
                if (isset($payment['status']) && $payment['status'] == 'success' && isset($payment['data']['link'])) {
                    $response['error'] = false;
                    $response['message'] = "Payment link generated. Follow the link to make the payment!";
                    $response['link'] = $payment['data']['link'];
                    print_r(json_encode($response));
                } else {
                    $response['error'] = true;
                    $response['message'] = "Could not initiate payment. " . $payment['message'];
                    $response['link'] = "";
                    print_r(json_encode($response));
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Could not initiate payment. Try again later! ";
                $response['link'] = "";
                print_r($response);
            }
        }
    }
    public function flutterwave_payment_response()
    {
        if (isset($_GET['transaction_id']) && !empty($_GET['transaction_id'])) {
            $this->load->library('flutterwave');
            $transaction_id = $_GET['transaction_id'];
            $transaction = $this->flutterwave->verify_transaction($transaction_id);
            if (!empty($transaction)) {
                $transaction = json_decode($transaction, true);
                if ($transaction['status'] == 'error') {
                    $response['error'] = true;
                    $response['message'] = $transaction['message'];
                    $response['amount'] = 0;
                    $response['status'] = "failed";
                    $response['currency'] = "NGN";
                    $response['transaction_id'] = $transaction_id;
                    $response['reference'] = "";
                    print_r(json_encode($response));
                    return false;
                }

                if ($transaction['status'] == 'success' && $transaction['data']['status'] == 'successful') {
                    $response['error'] = false;
                    $response['message'] = "Payment has been completed successfully";
                    $response['amount'] = $transaction['data']['amount'];
                    $response['currency'] = $transaction['data']['currency'];
                    $response['status'] = $transaction['data']['status'];
                    $response['transaction_id'] = $transaction['data']['id'];
                    $response['reference'] = $transaction['data']['tx_ref'];
                    print_r(json_encode($response));
                    return false;
                } else if ($transaction['status'] == 'success' && $transaction['data']['status'] != 'successful') {
                    $response['error'] = true;
                    $response['message'] = "Payment is " . $transaction['data']['status'];
                    $response['amount'] = $transaction['data']['amount'];
                    $response['currency'] = $transaction['data']['currency'];
                    $response['status'] = $transaction['data']['status'];
                    $response['transaction_id'] = $transaction['data']['id'];
                    $response['reference'] = $transaction['data']['tx_ref'];
                    print_r(json_encode($response));
                    return false;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Transaction not found";
                print_r(json_encode($response));
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Invalid request!";
            print_r(json_encode($response));
            return false;
        }
    }

    public function delete_order()
    {
        /*
            order_id:1
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $order_id = $_POST['order_id'];
            $order = fetch_orders($order_id, false, false, false, false, false, false, false);
            if ($order['order_data'][0]['order_items'][0]['status'][0][0] == 'awaiting') {
                update_stock($order['order_data'][0]['order_items'][0]['product_variant_id'], $order['order_data'][0]['order_items'][0]['quantity'], 'plus');
            }
            delete_details(['id' => $order_id], 'orders');
            delete_details(['order_id' => $order_id], 'order_items');

            $this->response['error'] = false;
            $this->response['message'] = 'Order deleted successfully';
            $this->response['data'] = array();
        }
        print_r(json_encode($this->response));
    }

    //27. get_ticket_types
    public function get_ticket_types()
    {
        if (!$this->verify_token()) {
            return false;
        }

        $this->db->select('*');
        $types = $this->db->get('ticket_types')->result_array();
        if (!empty($types)) {
            for ($i = 0; $i < count($types); $i++) {
                $types[$i] = output_escaping($types[$i]);
            }
        }
        $this->response['error'] = false;
        $this->response['message'] = 'Ticket types fetched successfully';
        $this->response['data'] = $types;
        print_r(json_encode($this->response));
    }

    //28. add_ticket
    public function add_ticket()
    {
        /*
            ticket_type_id:1
            subject:product_image not displying
            email:test@gmail.com
            description:its not showing images of products in web
            user_id:1
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('ticket_type_id', 'Ticket Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'description', 'trim|required|xss_clean');


        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $ticket_type_id = $this->input->post('ticket_type_id', true);
            $user_id = $this->input->post('user_id', true);
            $subject = $this->input->post('subject', true);
            $email = $this->input->post('email', true);
            $description = $this->input->post('description', true);
            $user = fetch_users($user_id);
            if (empty($user)) {
                $this->response['error'] = true;
                $this->response['message'] = "User not found!";
                $this->response['data'] = [];
                print_r(json_encode($this->response));
                return false;
            }
            $data = array(
                'ticket_type_id' => $ticket_type_id,
                'user_id' => $user_id,
                'subject' => $subject,
                'email' => $email,
                'description' => $description,
                'status' => PENDING,
            );
            $insert_id = $this->ticket_model->add_ticket($data);
            if (!empty($insert_id)) {
                $result = $this->ticket_model->get_tickets($insert_id, $ticket_type_id, $user_id);
                $this->response['error'] = false;
                $this->response['message'] =  'Ticket Added Successfully';
                $this->response['data'] = $result['data'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] =  'Ticket Not Added';
                $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
            }
        }
        print_r(json_encode($this->response));
    }
    //29. edit_ticket
    public function edit_ticket()
    {
        /*
            ticket_id:1
            ticket_type_id:1
            subject:product_image not displying
            email:test@gmail.com
            description:its not showing attachments of products in web
            user_id:1
            status:3 or 5 [3 -> resolved, 5 -> reopened]
            [1 -> pending, 2 -> opened, 3 -> resolved, 4 -> closed, 5 -> reopened]
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('ticket_type_id', 'Ticket Type Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ticket_id', 'Ticket Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');


        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $status = $this->input->post('status', true);
            $ticket_id = $this->input->post('ticket_id', true);
            $user_id = $this->input->post('user_id', true);
            $res = fetch_details('tickets', 'id=' . $ticket_id . ' and user_id=' . $user_id, '*');
            if (empty($res)) {
                $this->response['error'] = true;
                $this->response['message'] = "User id is changed you can not udpate the ticket.";
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            if ($status == RESOLVED && $res[0]['status'] == CLOSED) {
                $this->response['error'] = true;
                $this->response['message'] = "Current status is closed.";
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            if ($status == REOPEN && ($res[0]['status'] == PENDING || $res[0]['status'] == OPENED)) {
                $this->response['error'] = true;
                $this->response['message'] = "Current status is pending or opened.";
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $ticket_type_id = $this->input->post('ticket_type_id', true);
            $user_id = $this->input->post('user_id', true);
            $subject = $this->input->post('subject', true);
            $email = $this->input->post('email', true);
            $description = $this->input->post('description', true);
            $user = fetch_users($user_id);
            if (empty($user)) {
                $this->response['error'] = true;
                $this->response['message'] = "User not found!";
                $this->response['data'] = [];
                print_r(json_encode($this->response));
                return false;
            }
            $data = array(
                'ticket_type_id' => $ticket_type_id,
                'user_id' => $user_id,
                'subject' => $subject,
                'email' => $email,
                'description' => $description,
                'status' => $status,
                'ticket_id' => $ticket_id,
                'edit_ticket' => $ticket_id
            );
            if (!$this->ticket_model->add_ticket($data)) {
                $result = $this->ticket_model->get_tickets($ticket_id, $ticket_type_id, $user_id);
                $this->response['error'] = false;
                $this->response['message'] =  'Ticket updated Successfully';
                $this->response['data'] = $result['data'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] =  'Ticket Not Added';
                $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
            }
        }
        print_r(json_encode($this->response));
    }

    //30. send_message
    public function send_message()
    {
        /*
            user_type:user
            user_id:1
            ticket_id:1	
            message:test	
            attachments[]:files  {optional} {type allowed -> image,video,document,spreadsheet,archive}
        */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('user_type', 'User Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('ticket_id', 'Ticket id', 'trim|required|numeric|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $user_type = $this->input->post('user_type', true);
            $user_id = $this->input->post('user_id', true);
            $ticket_id = $this->input->post('ticket_id', true);
            $message = (isset($_POST['message']) && !empty(trim($_POST['message']))) ? $this->input->post('message', true) : "";


            $user = fetch_users($user_id);
            if (empty($user)) {
                $this->response['error'] = true;
                $this->response['message'] = "User not found!";
                $this->response['data'] = [];
                print_r(json_encode($this->response));
                return false;
            }
            if (!file_exists(FCPATH . TICKET_IMG_PATH)) {
                mkdir(FCPATH . TICKET_IMG_PATH, 0777);
            }

            $temp_array = array();
            $files = $_FILES;
            $images_new_name_arr = array();
            $images_info_error = "";
            $allowed_media_types = implode('|', allowed_media_types());
            $config = [
                'upload_path' =>  FCPATH . TICKET_IMG_PATH,
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
                            resize_review_images($temp_array, FCPATH . TICKET_IMG_PATH);
                            $images_new_name_arr[$i] = TICKET_IMG_PATH . $temp_array['file_name'];
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
                            unlink(FCPATH . TICKET_IMG_PATH . $images_new_name_arr[$key]);
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
                'user_type' => $user_type,
                'user_id' => $user_id,
                'ticket_id' => $ticket_id,
                'message' => $message
            );
            if (!empty($_FILES['attachments']['name'][0]) && isset($_FILES['attachments']['name'])) {
                $data['attachments'] = $images_new_name_arr;
            }
            $insert_id = $this->ticket_model->add_ticket_message($data);
            $app_settings = get_settings('system_settings', true);
            if (!empty($insert_id)) {
                $data1 = $this->config->item('type');
                $result = $this->ticket_model->get_messages($ticket_id, $user_id, "", "", "1", "", "", $data1, $insert_id);
                if (!empty($result)) {
                    //custom message
                    $settings = get_settings('system_settings', true);
                    $user_roles = fetch_details("user_permissions", "", '*', '',  '', '', '');
                    foreach ($user_roles as $user) {
                        $user_res = fetch_details('users', ['id' => $user['user_id']], 'fcm_id');
                        $fcm_ids[0][] = $user_res[0]['fcm_id'];
                    }
                    $custom_notification =  fetch_details('custom_notifications', ['type' => "ticket_message"], '');
                    $hashtag_application_name = '< application_name >';
                    $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);
                    $data = str_replace($hashtag_application_name, $app_settings['app_name'], $hashtag);
                    $message = output_escaping(trim($data, '"'));
                    $fcm_admin_subject = (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Attachments";
                    $fcm_admin_msg = (!empty($custom_notification)) ? $message : "Ticket Message";
                    if (!empty($fcm_ids)) {
                        $fcmMsg = array(
                            'title' => $fcm_admin_subject,
                            'body' => $fcm_admin_msg,
                            'type' => "ticket_message",
                            'type_id' => $ticket_id,
                            'chat' => json_encode($result['data']),
                            'content_available' => true
                        );
                        send_notification($fcmMsg, $fcm_ids);
                    }
                }
                $this->response['error'] = false;
                $this->response['message'] =  'Ticket Message Added Successfully!';
                $this->response['data'] = $result['data'][0];
            } else {
                $this->response['error'] = true;
                $this->response['message'] =  'Ticket Message Not Added';
                $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
            }
        }
        print_r(json_encode($this->response));
    }

    //31. get_tickets
    public function get_tickets()
    {
        /*
        31. get_tickets
            ticket_id: 1001                // { optional}
            ticket_type_id: 1001                // { optional}
            user_id: 1001                // { optional}
            status:   [1 -> pending, 2 -> opened, 3 -> resolved, 4 -> closed, 5 -> reopened]// { optional}
            search : Search keyword // { optional }
            limit:25                // { default - 25 } optional
            offset:0                // { default - 0 } optional
            sort: id | date_created | last_updated                // { default - id } optional
            order:DESC/ASC          // { default - DESC } optional
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('ticket_id', 'Ticket ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('ticket_type_id', 'Ticket Type ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('status', 'User ID', 'trim|numeric|xss_clean');
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
            $ticket_id = (isset($_POST['ticket_id']) && is_numeric($_POST['ticket_id']) && !empty(trim($_POST['ticket_id']))) ? $this->input->post('ticket_id', true) : "";
            $ticket_type_id = (isset($_POST['ticket_type_id']) && is_numeric($_POST['ticket_type_id']) && !empty(trim($_POST['ticket_type_id']))) ? $this->input->post('ticket_type_id', true) : "";
            $user_id = (isset($_POST['user_id']) && is_numeric($_POST['user_id']) && !empty(trim($_POST['user_id']))) ? $this->input->post('user_id', true) : "";
            $status = (isset($_POST['status']) && is_numeric($_POST['status']) && !empty(trim($_POST['status']))) ? $this->input->post('status', true) : "";
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 10;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';
            $result = $this->ticket_model->get_tickets($ticket_id, $ticket_type_id, $user_id, $status, $search, $offset, $limit, $sort, $order);
            print_r(json_encode($result));
        }
    }

    //32. get_messages
    public function get_messages()
    {
        /*
        32. get_messages
            ticket_id: 1001            
            user_type: 1001                // { optional}
            user_id: 1001                // { optional}
            search : Search keyword // { optional }
            limit:25                // { default - 25 } optional
            offset:0                // { default - 0 } optional
            sort: id | date_created | last_updated                // { default - id } optional
            order:DESC/ASC          // { default - DESC } optional
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('ticket_id', 'Ticket ID', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('status', 'User ID', 'trim|numeric|xss_clean');
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
            $ticket_id = (isset($_POST['ticket_id']) && is_numeric($_POST['ticket_id']) && !empty(trim($_POST['ticket_id']))) ? $this->input->post('ticket_id', true) : "";
            $user_id = (isset($_POST['user_id']) && is_numeric($_POST['user_id']) && !empty(trim($_POST['user_id']))) ? $this->input->post('user_id', true) : "";
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 10;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';
            $data = $this->config->item('type');
            $result = $this->ticket_model->get_messages($ticket_id, $user_id, $search, $offset, $limit, $sort, $order, $data, "");
            print_r(json_encode($result));
        }
    }

    //33. send_bank_transfer_proof
    public function send_bank_transfer_proof()
    {
        /*
        order_id:1
        attachments:file  {optional} {type allowed -> image,video,document,spreadsheet,archive}
      */

        if (!$this->verify_token()) {
            return false;
        }

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
                //custom message
                $settings = get_settings('system_settings', true);
                $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                $user_roles = fetch_details("user_permissions", "", '*', '',  '', '', '');
                foreach ($user_roles as $user) {
                    $user_res = fetch_details('users', ['id' => $user['user_id']], 'fcm_id');
                    if ($user_res[0]['fcm_id'] != '') {
                        $fcm_ids[0][] = $user_res[0]['fcm_id'];
                    }
                }
                if (!empty($fcm_ids)) {
                    $custom_notification =  fetch_details('custom_notifications', ['type' => "bank_transfer_proof"], '');
                    $hashtag_order_id = '< order_id >';
                    $hashtag_application_name = '< application_name >';
                    $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);
                    $data = str_replace(array($hashtag_order_id, $hashtag_application_name), array($order_id, $app_name), $hashtag);
                    $message = output_escaping(trim($data, '"'));
                    $customer_msg = (!empty($custom_notification)) ? $message : "Hello Dear Admin you have new order bank transfer proof. Order ID #" . $order_id . ' please take note of it! Thank you. Regards ' . $app_name . '';
                    $fcmMsg = array(
                        'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "You have new order proof",
                        'body' => $customer_msg,
                        'type' => "bank_transfer_proof",
                    );
                    send_notification($fcmMsg, $fcm_ids);
                }
                $this->response['error'] = false;
                $this->response['message'] =  'Bank Trasfer Proof Added Successfully!';
                $this->response['data'] = (!empty($data)) ? $data : [];
            } else {
                $this->response['error'] = true;
                $this->response['message'] =  'Bank Trasfer Proof Not Added';
                $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
            }
        }
        print_r(json_encode($this->response));
    }

    //31.get_zipcodes
    public function get_zipcodes()
    {
        /*
              limit:10 {optional}
              offset:0 {optional}
              search:0 {optional}
          */
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');

        if (!$this->verify_token()) {
            return false;
        }
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

    //32. is_product_delivarable
    public function is_product_delivarable()
    {
        /*
        32. is_product_delivarable
            product_id:10 
            zipcode:132456
        */
        $this->form_validation->set_rules('product_id', 'Product Id', 'trim|numeric|xss_clean|required');
        $this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|xss_clean|required');

        if (!$this->verify_token()) {
            return false;
        }

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            echo json_encode($this->response);
        } else {
            $zipcode = $this->input->post('zipcode', true);
            $is_pincode = is_exist(['zipcode' => $zipcode], 'zipcodes');
            $product_id = $this->input->post('product_id', true);
            if ($is_pincode) {
                $zipcode_id = fetch_details('zipcodes', ['zipcode' => $zipcode], 'id');
                $is_available = is_product_delivarable($type = 'zipcode', $zipcode_id[0]['id'], $product_id);
                if ($is_available) {
                    $this->response['error'] = false;
                    $this->response['message'] = 'Product is deliverable on ' . $zipcode . '.';
                    echo json_encode($this->response);
                    return false;
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Product is not deliverable on ' . $zipcode . '.';
                    echo json_encode($this->response);
                    return false;
                }
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Cannot deliver to "' . $zipcode . '".';
                echo json_encode($this->response);
                return false;
            }
        }
    }

    //33. check_cart_products_delivarable
    public function check_cart_products_delivarable()
    {
        /*
        33. check_cart_products_delivarable
            address_id:10 
            user_id:12
        */
        $this->form_validation->set_rules('address_id', 'Area Id', 'trim|numeric|xss_clean|required');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|xss_clean|required');


        if (!$this->verify_token()) {
            return false;
        }

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            echo json_encode($this->response);
        } else {
            $address_id = $this->input->post('address_id', true);
            $area_id = fetch_details('addresses', ['id' => $address_id], 'area_id');
            if (!empty($area_id)) {
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
                        $this->response['data'] = $product_delivarable;
                        print_r(json_encode($this->response));
                        return;
                    } else {
                        $this->response['error'] = false;
                        $this->response['message'] = "Product(s) are delivarable.";
                        $this->response['data'] = $product_delivarable;
                        print_r(json_encode($this->response));
                        return;
                    }
                } else {
                    $this->response['error'] = false;
                    $this->response['message'] = "Product(s) are delivarable";
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return;
                }
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Address not available.";
                $this->response['data'] =  array();
                print_r(json_encode($this->response));
                return;
            }
        }
    }

    public function get_sellers()
    {
        /*
            zipcode:1  //{optional}
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('zipcode_id', 'Zipcode ID', 'trim|numeric|xss_clean');
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
            if (isset($_POST['zipcode']) && !empty($_POST['zipcode'])) {
                $zipcode = $this->input->post('zipcode', true);
                $is_pincode = is_exist(['zipcode' => $zipcode], 'zipcodes');
                if ($is_pincode) {
                    $zipcode_ids = fetch_details('zipcodes', ['zipcode' => $zipcode], 'id');
                    $zipcode_id = $zipcode_ids[0]['id'];
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Sellers Not Found!';
                    $this->response['data'] =  array();
                    echo json_encode($this->response);
                    return false;
                }
            } else {
                $zipcode_id = "";
            }
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'u.id';
            $data = $this->Seller_model->get_sellers($zipcode_id, $limit, $offset, $sort, $order, $search);
            print_r(json_encode($data));
        }
    }
    public function get_promo_codes()
    {
        $this->form_validation->set_rules('search', 'Search keyword', 'trim|xss_clean');
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
        } else {
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';

            $this->response['error'] = false;
            $this->response['message'] = 'Promocodes retrived Successfully !';
            $result = $this->Promo_code_model->get_promo_codes($limit, $offset, $sort, $order, $search);
            $this->response['total'] = $result['total'];
            $this->response['offset'] = (isset($offset) && !empty($offset)) ? $offset : '0';
            $this->response['promo_codes'] = $result['data'];
            print_r(json_encode($this->response));
            return;
        }
    }

    /* add_product_faqs */
    public function add_product_faqs()
    {
        $this->form_validation->set_rules('product_id', 'Product Id', 'trim|numeric|xss_clean|required');
        $this->form_validation->set_rules('user_id', 'User_id', 'trim|numeric|xss_clean|required');
        $this->form_validation->set_rules('question', 'Question', 'trim|xss_clean|required');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            $product_id = $this->input->post('product_id', true);
            $user_id = $this->input->post('user_id', true);
            $question = $this->input->post('question', true);
            $user = fetch_users($user_id);
            if (empty($user)) {
                $this->response['error'] = true;
                $this->response['message'] = "User not found!";
                $this->response['data'] = [];
                print_r(json_encode($this->response));
                return false;
            }
            $data = array(
                'product_id' => $product_id,
                'user_id' => $user_id,
                'question' => $question,
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
            user_id:1       // {optional}
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
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|numeric|xss_clean');
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
            $user_id = (isset($_POST['user_id']) && is_numeric($_POST['user_id']) && !empty(trim($_POST['user_id']))) ? $this->input->post('user_id', true) : "";
            $search = (isset($_POST['search']) && !empty(trim($_POST['search']))) ? $this->input->post('search', true) : "";
            $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 10;
            $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
            $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
            $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';

            $result = $this->product_model->get_product_faqs($id, $product_id, $user_id, $search, $offset, $limit, $sort, $order);
            print_r(json_encode($result));
        }
    }

    //41.send_withdrawal_request
    public function send_withdrawal_request()
    {
        /* 
             user_id:15
             payment_address: 12343535
             amount: 560           
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
                        'payment_type' => 'customer',
                        'amount_requested' => $amount,
                    ];

                    if (insert_details($data, 'payment_requests')) {
                        $this->Customer_model->update_balance_customer($amount, $user_id, 'deduct');
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

    //42.get_withdrawal_request
    public function get_withdrawal_request()
    {
        /* 
             user_id:15
             limit:10
             offset:10
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
            $this->response['error'] = false;
            $this->response['message'] = 'Withdrawal Request Retrieved Successfully';
            $this->response['data'] = $userData;
            $this->response['total'] = strval(count($userData));
            print_r(json_encode($this->response));
        }
    }

    public function test($id = '', $type = '')
    {
        $this->Order_model->test($id, $type);
    }

    public function paystack_webhook()
    {
        $this->load->library(['paystack']);

        $system_settings = get_settings('system_settings', true);
        $credentials = $this->paystack->get_credentials();

        $secret_key = $credentials['secret_key'];

        $request_body = file_get_contents('php://input');
        $event = json_decode($request_body, true);
        log_message('error', 'paystack Webhook --> ' . var_export($event, true));
        log_message('error', 'paystack Webhook SERVER Variable --> ' . var_export($_SERVER, true));


        if (!empty($event['data'])) {

            $txn_id = (isset($event['data']['reference'])) ? $event['data']['reference'] : "";
            if (isset($txn_id) && !empty($txn_id)) {
                $transaction = fetch_details('transactions', ['txn_id' => $txn_id],  '*');
                if (!empty($transaction)) {
                    $order_id = $transaction[0]['order_id'];
                    $user_id = $transaction[0]['user_id'];
                } else {
                    // $order_id = 0;
                    $order_id = $event['data']['metadata']['order_id'];
                    $order_data = fetch_orders($order_id);
                    $user_id = $order_data['order_data'][0]['user_id'];
                }
            }
            $amount = $event['data']['amount'];
            $currency = $event['data']['currency'];
        } else {
            $order_id = 0;
            $amount = 0;
            $currency = (isset($event['data']['currency'])) ? $event['data']['currency'] : "";
        }

        /* Wallet refill has unique format for order ID - wallet-refill-user-{user_id}-{system_time}-{3 random_number}  */
        if (!is_numeric($order_id) && strpos($order_id, "wallet-refill-user") !== false) {

            $temp = explode("-", $order_id);
            if (isset($temp[3]) && is_numeric($temp[3]) && !empty($temp[3] && $temp[3] != '')) {
                $user_id = $temp[3];
            } else {
                $user_id = 0;
            }
        }

        // validate event do all at once to avoid timing attack
        if ($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $request_body, $secret_key)) {
            log_message('error', 'Paystack Webhook - Invalid Signature - JSON DATA --> ' . var_export($event, true));
            log_message('error', 'Paystack Server Variable invalid --> ' . var_export($_SERVER, true));
            exit();
        }

        if ($event['event'] == 'charge.success') {
            if (!empty($order_id)) {     /* To do the wallet recharge if the order id is set in the pattern */

                if (strpos($order_id, "wallet-refill-user") !== false) {

                    $data['transaction_type'] = "wallet";
                    $data['user_id'] = $user_id;
                    $data['order_id'] = $order_id;
                    $data['type'] = "credit";
                    $data['txn_id'] = $txn_id;
                    $data['amount'] = $amount;
                    $data['status'] = "success";
                    $data['message'] = "Wallet refill successful";
                    $this->transaction_model->add_transaction($data);

                    $this->load->model('customer_model');
                    if ($this->customer_model->update_balance($amount, $user_id, 'add')) {
                        $response['error'] = false;
                        $response['transaction_status'] = $event['type'];
                        $response['message'] = "Wallet recharged successfully!";
                    } else {
                        $response['error'] = true;
                        $response['transaction_status'] = $event['type'];
                        $response['message'] = "Wallet could not be recharged!";

                        log_message('error', 'Paystack Webhook | wallet recharge failure --> ' . var_export($event, true));
                    }
                    echo json_encode($response);
                    return false;
                } else {

                    /* process the order and mark it as received */
                    $order = fetch_orders($order_id, false, false, false, false, false, false, false);

                    log_message('error', 'Paystack Webhook | order --> ' . var_export($order, true));

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
                            $amount = ($event['data']['amount']);
                            $data = [
                                'transaction_type' => 'transaction',
                                'user_id' => $user_id,
                                'order_id' => $order_id,
                                'type' => 'paystack',
                                'txn_id' => $txn_id,
                                'amount' => $amount,
                                'status' => 'success',
                                'message' => 'order placed successfully',
                            ];
                            $this->transaction_model->add_transaction($data);
                        }



                        $status = json_encode(array(array('received', date("d-m-Y h:i:sa"))));
                        update_details(['status' => $status], ['order_id' => $order_id], 'order_items', false);
                        update_details(['active_status' => 'received'], ['order_id' => $order_id], 'order_items');

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

                        log_message('error', 'Paystack Webhook inner Success --> ' . var_export($event, true));
                    }
                    log_message('error', 'Paystack Webhook order Success --> ' . var_export($event, true));
                }
            } else {
                /* No order ID found / sending 304 error to payment gateway so it retries wenhook after sometime*/
                log_message('error', 'Paystack Webhook | Order id not found --> ' . var_export($event, true));
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(304)
                    ->set_output(json_encode(array(
                        'message' => '304 Not Modified - order/transaction id not found',
                        'error' => true
                    )));
            }

            $response['error'] = false;
            $response['transaction_status'] = $event['event'];
            $response['message'] = "Transaction successfully done";
            log_message('error', 'Paystack Transaction Successfully --> ' . var_export($event, true));
            echo json_encode($response);
            return false;
        } else if ($event['event'] == 'charge.dispute.create') {
            if (!empty($order_id) && is_numeric($order_id)) {
                $order = fetch_orders($order_id, false, false, false, false, false, false, false);

                if ($order['order_data']['0']['active_status'] == 'received' || $order['order_data']['0']['active_status'] == 'processed') {
                    update_details(['active_status' => 'awaiting'], ['order_id' => $order_id], 'order_items');
                }

                if (!empty($transaction)) {
                    $transaction_id = $transaction[0]['id'];
                    update_details(['status' => 'pending'], ['id' => $transaction_id], 'transactions');
                }

                log_message('error', 'Paystack Transaction is Pending --> ' . var_export($event, true));
            }
        } else {

            if (!empty($order_id) && is_numeric($order_id)) {
                update_details(['active_status' => 'cancelled'], ['order_id' => $order_id], 'order_items');
            }
            /* No need to add because the transaction is already added just update the transaction status */
            if (!empty($transaction)) {
                $transaction_id = $transaction[0]['id'];
                update_details(['status' => 'failed'], ['id' => $transaction_id], 'transactions');
            }

            $response['error'] = true;
            $response['transaction_status'] = $event['event'];
            $response['message'] = "Transaction could not be detected.";
            log_message('error', 'Paystack Webhook | Transaction could not be detected --> ' . var_export($event, true));
            echo json_encode($response);
            return false;
        }
    }

    public function flutterwave_webhook()
    {
        $this->load->library(['Flutterwave']);
        $system_settings = get_settings('system_settings', true);
        $credentials = $this->flutterwave->get_credentials();

        $local_secret_hash = $credentials['secret_hash'];


        $request_body = file_get_contents('php://input');
        $event = json_decode($request_body, FALSE);

        log_message('error', 'Flutterwave Webhook --> ' . var_export($event, true));
        log_message('error', 'Flutterwave Webhook SERVER Variable --> ' . var_export($_SERVER, true));

        if (!empty($event->data->id)) {
            $txn_id = (isset($event->data->id)) ? $event->data->id : "";
            if (!empty($txn_id)) {
                $transaction = fetch_details('transactions', ['txn_id' => $txn_id],  '*');
                if (!empty($transaction)) {
                    $order_id = $transaction[0]['order_id'];
                    $user_id = $transaction[0]['user_id'];
                } else {
                    $order_id = 0;
                }
            }
            $amount = $event->data->amount;
            $currency = $event->data->currency;
            log_message('error', 'Flutterwave Webhook order_id --> ' . var_export($order_id, true));
        } else {
            $order_id = 0;
            $amount = 0;
            $currency = (isset($event->data->currency)) ? $event->data->currency : "";
            $balance_transaction = 0;
        }

        /* Wallet refill has unique format for order ID - wallet-refill-user-{user_id}-{system_time}-{3 random_number}  */
        if (empty($order_id)) {
            $user_email = (!empty($event->data->customer->email) && isset($event->data->customer->email)) ? $event->data->customer->email : 0;
            $user_id = fetch_details('users', ['email' => $user_email], 'id');
            $user_main_id = $user_id[0]['id'];
            $currTime = date('Y-m-d H:i:s');
            $order_id = 'wallet-refill-user-' . $user_main_id . '-' . $currTime . '-' . rand(10, 1000);
            log_message('error', 'user id --> ' . var_export($order_id, true));
        }

        if (!is_numeric($order_id) && strpos($order_id, "wallet-refill-user") !== false) {
            $temp = explode("-", $order_id);
            if (isset($temp[3]) && is_numeric($temp[3]) && !empty($temp[3] && $temp[3] != '')) {
                $user_id = $temp[3];
            } else {
                $user_id = 0;
            }
        }


        $signature = (isset($_SERVER['HTTP_VERIF_HASH'])) ? $_SERVER['HTTP_VERIF_HASH'] : '';

        /* comparing our local signature with received signature */
        if (empty($signature) || $signature != $local_secret_hash) {
            log_message('error', 'FlutterWave Webhook - Invalid Signature - JSON DATA --> ' . var_export($event, true));
            log_message('error', 'FlutterWave Server Variable invalid --> ' . var_export($_SERVER, true));
        }


        if ($event->event == 'charge.completed' && $event->data->status == 'successful') {
            if (!empty($order_id)) {
                /* To do the wallet recharge if the order id is set in the patter */
                if (strpos($order_id, "wallet-refill-user") !== false) {
                    $data['transaction_type'] = "wallet";
                    $data['user_id'] = $user_id;
                    $data['order_id'] = $order_id;
                    $data['type'] = "credit";
                    $data['txn_id'] = $txn_id;
                    $data['amount'] = $amount;
                    $data['status'] = "success";
                    $data['message'] = "Wallet refill successful";
                    $this->transaction_model->add_transaction($data);
                    log_message('error', ' transaction data --> ' . var_export($data, true));

                    $this->load->model('customer_model');
                    if ($this->customer_model->update_balance($amount, $user_id, 'add')) {
                        $response['error'] = false;
                        $response['transaction_status'] = $event->data->status;
                        $response['message'] = "Wallet recharged successfully!";
                    } else {
                        $response['error'] = true;
                        $response['transaction_status'] = $event->data->status;
                        $response['message'] = "Wallet could not be recharged!";
                        log_message('error', 'Flutterwave Webhook | wallet recharge failure --> ' . var_export($event, true));
                    }
                    echo json_encode($response);
                    return false;
                } else {

                    /* process the order and mark it as received */
                    $order = fetch_orders($order_id, false, false, false, false, false, false, false);
                    log_message('error', 'Flutterwave Webhook user id --> ' . var_export($order['order_data'][0]['user_id'], true));

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
                            update_details(['status' => 'success'], ['txn_id' => $txn_id], 'transactions');
                        }

                        /* add transaction of the payment */

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

                        log_message('error', 'Flutterwave Webhook inner Success --> ' . var_export($event, true));
                    }
                    log_message('error', 'Flutterwave Webhook outer Success --> ' . var_export($event, true));
                }
            } else {
                /* No order ID found */
                log_message('error', 'Flutterwave Webhook | No Order ID found --> ' . var_export($event, true));
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(304)
                    ->set_output(json_encode(array(
                        'message' => '304 Not Modified - order/transaction id not found',
                        'error' => true
                    )));
            }

            $response['error'] = false;
            $response['transaction_status'] = $event->data->status;
            $response['message'] = "Transaction successfully done";
            log_message('error', 'Flutterwave Transaction Successfully --> ' . var_export($event, true));
            echo json_encode($response);
            return false;
        } else {
            $response['error'] = true;
            $response['transaction_status'] = $event->data->status;
            $response['message'] = "Transaction could not be detected.";
            log_message('error', 'Flutterwave Webhook | Transaction could not be detected --> ' . var_export($event, true));
            echo json_encode($response);
            return false;
        }
    }

    //rozarpay_create_order

    public function razorpay_create_order()
    {
        /* 
             order_id:15
         */

        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('order_id', 'Order ID', 'required|trim|numeric|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            $order_id = (isset($_POST['order_id'])) ? $_POST['order_id'] : null;
            $order = fetch_orders($order_id, false, false, false, false, false, false, false);
            $settings = get_settings('system_settings', true);

            if (!empty($order) && !empty($settings)) {
                $currency = $settings['supported_locals'];
                $price = $order['order_data'][0]['final_total'];
                $amount = intval($price * 100);

                $this->load->library(['razorpay']);
                $create_order = $this->razorpay->create_order($amount, $order_id, $currency);
                if (!empty($create_order)) {
                    $this->response['error'] = false;
                    $this->response['message'] = "razorpay order created";
                    $this->response['data'] = $create_order;
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = "razorpay order not created";
                    $this->response['data'] = array();
                }
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "details not found";
                $this->response['data'] = array();
            }
            print_r(json_encode($this->response));
            return;
        }
    }


    /*
        status: cancelled / returned
        order_id:1201
    */
    public function update_order_status()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
        $this->form_validation->set_rules('order_id', 'Order item id', 'trim|required|numeric|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $all_status = ['received', 'processed', 'shipped', 'delivered', 'cancelled', 'returned'];
            if (!in_array(strtolower($_POST['status']), $all_status)) {
                $this->response['error'] = true;
                $this->response['message'] = "Invalid Status supplied";
                print_r(json_encode($this->response));
                return false;
            }


            // check for bank receipt if available
            $order_method = fetch_details('orders', ['id' => $_POST['order_id']], 'payment_method');
            if ($order_method[0]['payment_method'] == 'bank_transfer') {
                $bank_receipt = fetch_details('order_bank_transfer', ['order_id' => $_POST['order_id']]);
                $transaction_status = fetch_details('transactions', ['order_id' => $_POST['order_id']], 'status');
                if ($_POST['status'] != "cancelled" && (empty($bank_receipt) || strtolower($transaction_status[0]['status']) != 'success')) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order Status can not update, Bank verification is remain from transactions.";
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }

            $this->response = $this->order_model->update_order_status($_POST['order_id'], trim($_POST['status']));
            if (trim($_POST['status']) != 'returned' && $this->response['error'] == false) {
                process_refund($_POST['order_id'], $_POST['status'], 'order_items');
            }
            if (trim($_POST['status']) == 'cancelled') {
                $data = fetch_details('order_items', ['id' => $_POST['order_id']], 'product_variant_id,quantity');
                update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
            }
        }
        print_r(json_encode($this->response));
        return false;
    }

    public function create_midtrans_transaction()
    {
        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('order_id', 'Order id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
        $order_id = $_POST['order_id'];
        $amount = $_POST['amount'];
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $this->load->library(['midtrans']);
            $transaction = $this->midtrans->create_transaction($order_id, $amount);
            if (!empty($transaction)) {
                $this->response['error'] = false;
                $this->response['message'] = "Token generate successfully";
                $this->response['data'] = json_decode($transaction['body'], 1);
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Token generation Failed";
                $this->response['data'] = array();
            }
        }
        print_r(json_encode($this->response));
    }
    public function get_midtrans_transaction_status()
    {

        if (!$this->verify_token()) {
            return false;
        }
        $this->form_validation->set_rules('order_id', 'Order id', 'trim|required|xss_clean');
        $order_id = $this->input->post('order_id', true);

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {

            $this->load->library(['midtrans']);
            $create_order = $this->midtrans->get_transaction_status($order_id);
            if (!empty($create_order)) {
                $this->response['error'] = false;
                $this->response['message'] = "Transaction Retrived Successfully";
                $this->response['data'] = $create_order;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Transaction Not Retrived";
                $this->response['data'] = array();
            }
        }
        print_r(json_encode($this->response));
    }

    public function midtrans_payment_process()
    {

        $midtransInfo = $this->input->get();

        if (!empty($midtransInfo) && isset($_GET['status_code']) && ($_GET['status_code']) == 200 && isset($_GET['transaction_status']) && strtolower($_GET['transaction_status']) == 'capture') {
            $response['error'] = false;
            $response['message'] = "Success, Credit card transaction is successful";
            $response['data'] = $midtransInfo;
        } elseif (!empty($midtransInfo) && isset($_GET['transaction_status']) && strtolower($_GET['transaction_status']) == "pending") {
            $response['error'] = false;
            $response['message'] = "Waiting customer to finish transaction order_id: " . $_GET['order_id'];
            $response['data'] = $midtransInfo;
        } elseif (!empty($midtransInfo) && isset($_GET['transaction_status']) && strtolower($_GET['transaction_status']) == "deny") {
            $response['error'] = false;
            $response['message'] = "Your payment of order_id: " . $_GET['order_id'] . " is denied";
            $response['data'] = $midtransInfo;
        } else {
            $response['error'] = true;
            $response['message'] = "Payment Cancelled / Declined ";
            $response['data'] = (isset($_GET)) ? $this->input->get() : "";
        }
        print_r(json_encode($response));
    }

    public function midtrans_webhook()
    {
        $system_settings = get_settings('system_settings', true);
        $this->form_validation->set_rules('order_id', 'Order id', 'trim|required|xss_clean');
        $order_id = $this->input->post('order_id', true);

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $this->load->library(['midtrans']);
            $transaction_response = $this->midtrans->get_transaction_status($order_id);
            $txn_order_id = ($transaction_response['order_id']) ? $transaction_response['order_id'] : "";
            if (!empty($txn_order_id)) {
                $transaction = fetch_details('transactions', ['order_id' => $txn_order_id], '*');
                if (isset($transaction) && !empty($transaction)) {
                    $order_id = $transaction[0]['order_id'];
                    $user_id = $transaction[0]['user_id'];
                } else {
                    $order_id = $transaction_response['order_id'];
                    $order_data = fetch_orders($order_id);
                    $user_id = $order_data['order_data'][0]['user_id'];
                }
            }



            if ($order_id != $transaction_response['order_id']) {
                $response['error'] = true;
                $response['message'] = "Order id is not matched with transaction order id.";
                echo json_encode($response);
                return false;
            }
            $res = fetch_details('orders', ['id' => $order_id], 'id');

            if (!empty($res) && isset($res[0]['id']) && is_numeric($res[0]['id'])) {
                $db_order_id = $res[0]['id'];
                if ($transaction_response['order_id'] != $db_order_id) {
                    $response['error'] = true;
                    $response['message'] = "Order id is not matched with orders.";
                    echo json_encode($response);
                    return false;
                } else {
                    $item_id = fetch_details('order_items', ['order_id' => $order_id], 'id');
                    $order_item_ids = array_column($item_id, "id");
                }
            }
            $type = $transaction_response['payment_type'];
            $gross_amount = $transaction_response['gross_amount'];
            if ($transaction_response['transaction_status'] == 'capture') {

                if ($transaction_response['fraud_status'] == 'challenge') {
                    $response['error'] = false;
                    $response['transaction_status'] = $transaction_response['fraud_status'];
                    $response['message'] = "Transaction order_id: " . $order_id . " is challenged by FDS";
                    log_message('error', "Transaction order_id: " . $order_id . " is challenged by FDS");
                    return false;
                } else {
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
                        $data['txn_id'] = '';
                        $data['amount'] = $gross_amount;
                        $data['status'] = "success";
                        $data['message'] = "Wallet refill successful";
                        log_message('error', 'Midtrans user ID -  transaction data--> ' . var_export($data, true));
                        $this->transaction_model->add_transaction($data);
                        log_message('error', 'Midtrans user ID - Add transaction ');

                        $this->load->model('customer_model');
                        if ($this->customer_model->update_balance($gross_amount, $user_id, 'add')) {
                            $response['error'] = false;
                            $response['transaction_status'] = $transaction_response['transaction_status'];
                            $response['message'] = "Wallet recharged successfully!";
                            log_message('error', 'Midtrans user ID - Wallet recharged successfully --> ' . var_export($order_id, true));
                        } else {
                            $response['error'] = true;
                            $response['transaction_status'] = $transaction_response['transaction_status'];
                            $response['message'] = "Wallet could not be recharged!";
                            log_message('error', 'Midtrans Webhook | wallet recharge failure --> ' . var_export($transaction_response['transaction_status'], true));
                        }
                        echo json_encode($response);
                        return false;
                    } else {

                        //update order and mark it as receive
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
                                $amount = ($transaction_response['gross_amount']);
                                $data = [
                                    'transaction_type' => 'transaction',
                                    'user_id' => $user_id,
                                    'order_id' => $order_id,
                                    'type' => 'midtrans',
                                    'txn_id' => '',
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
                        } else {
                            log_message('error', 'Order id not found');
                            /* No order ID found */
                        }
                        $response['error'] = false;
                        $response['transaction_status'] = $transaction_response['transaction_status'];
                        $response['message'] = "Transaction successfully done using " . $type;
                        log_message('error', "Transaction successfully done using: " . $type);
                        echo json_encode($response);
                        return false;
                    }
                }
            } else if ($transaction_response['transaction_status'] == 'pending') {
                $response['error'] = false;
                $response['transaction_status'] = $transaction_response['transaction_status'];
                $response['message'] = "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
                log_message('error', "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type);
                echo json_encode($response);
                return false;
            } else if ($transaction_response['transaction_status'] == 'deny') {

                $response['error'] = true;
                $response['transaction_status'] = $transaction_response['transaction_status'];
                $response['message'] = "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied. And" . $transaction_response['status_message'];
                log_message('error', "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied. And" . $transaction_response['status_message']);
                echo json_encode($response);
                return false;
            } else if ($transaction_response['transaction_status'] == 'expire') {
                $response['error'] = true;
                $response['transaction_status'] = $transaction_response['transaction_status'];
                $response['message'] = "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
                log_message('error', "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.");
                echo json_encode($response);
                return false;
            } else if ($transaction_response['transaction_status'] == 'cancel') {
                $response['error'] = true;
                $response['transaction_status'] = $transaction_response['transaction_status'];
                $response['message'] = "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
                log_message('error', "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.");
                echo json_encode($response);
                return false;
            }
        }
    }

    public function sign_up()
    {
        // if (!verify_tokens()) {
        //     return false;
        // }
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('fcm_id', 'FCM ID', 'trim|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }
        $email = (isset($_POST['email']) && (trim($_POST['email'])) != "") ? $this->input->post('email', true) : '';
        $mobile = (isset($_POST['mobile']) && (trim($_POST['mobile'])) != "") ? $this->input->post('mobile', true) : '';
        $res = $this->db->select("id,mobile,email")
            ->where("mobile ='$mobile' and email = '$email'")
            ->where_not_in('type', 'phone')
            ->get('`users`')->result_array();
        if (!empty($res)) {
            $is_exist = (!empty($mobile)) ? ['mobile' => $mobile] : ['email' => $email];
            $where = (!empty($mobile)) ? ['mobile' => $mobile] : ['email' => $email];
            // $token = (!empty($mobile)) ? generate_tokens($mobile) : generate_tokens($email);


            if (is_exist($is_exist, 'users')) {
                if (isset($_POST['fcm_id']) && !empty(($_POST['fcm_id']))) {
                    update_details(['fcm_id' => $this->input->post('fcm_id', true)], 'users', $where);
                }
                /** set user jwt token  */
                // update_details(['apikey' => $token], $where, "users");

                $data = fetch_details('users', $where);
                unset($data[0]['password']);
                unset($data[0]['apikey']);

                if (empty($data[0]['image']) || file_exists(FCPATH . USER_IMG_PATH . $data[0]['image']) == FALSE) {
                    $data[0]['image'] = base_url() . NO_IMAGE;
                } else {
                    $data[0]['image'] = base_url() . USER_IMG_PATH . $data[0]['image'];
                }
                $data = array_map(function ($value) {
                    return $value === NULL ? "" : $value;
                }, $data[0]);
                //if the login is successful
                $response['error'] = false;
                // $response['token'] = $token;
                $response['message'] = "User login successfully";
                $response['data'] = $data;
                echo json_encode($response);
                return false;
            } else {
                $response['error'] = true;
                $response['message'] = 'User does not exists !';
                $response['data'] = array();
                echo json_encode($response);
                return false;
            }
        } else {
            //register

            $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Mail', 'trim|xss_clean|valid_email|is_unique[users.email]', array('is_unique' => ' The email is already registered . Please login'));
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|xss_clean|max_length[16]|numeric|is_unique[users.mobile]', array('is_unique' => ' The mobile number is already registered . Please login'));
            $this->form_validation->set_rules('country_code', 'Country Code', 'trim|xss_clean');
            $this->form_validation->set_rules('fcm_id', 'Fcm Id', 'trim|xss_clean');
            $this->form_validation->set_rules('referral_code', 'Referral code', 'trim|is_unique[users.referral_code]|xss_clean');
            $this->form_validation->set_rules('friends_code', 'Friends code', 'trim|xss_clean');
            $this->form_validation->set_rules('latitude', 'Latitude', 'trim|xss_clean|numeric');
            $this->form_validation->set_rules('longitude', 'Longitude', 'trim|xss_clean|numeric');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['data'] = array();
            } else {
                if (isset($_POST['friends_code']) && !empty($_POST['friends_code'])) {
                    $friends_code = $this->input->post('friends_code', true);
                    $friend = fetch_details(['referral_code' => $friends_code], 'users', '*');
                    if (empty($friend)) {
                        $response["error"]   = true;
                        $response["message"] = "Invalid friends code! Please pass the valid referral code of the inviter";
                        $response["data"] = [];
                        echo json_encode($response);
                        return false;
                    }
                }
                $additional_data = [
                    'username' => $this->input->post('name', true),
                    'mobile' => $mobile,
                    'email' => $email,
                    'type' => $this->input->post('type', true),
                    'country_code' => $this->input->post('country_code', true),
                    'fcm_id' => $this->input->post('fcm_id', true),
                    'referral_code' => $this->input->post('referral_code', true),
                    'friends_code' => $this->input->post('friends_code', true),
                    'latitude' => $this->input->post('latitude', true),
                    'longitude' => $this->input->post('longitude', true),
                    'active' => 1
                ];
                $res =  insert_details($additional_data, "users");
                $user_id = $this->db->insert_id();
                $user_details = [
                    'user_id' => $user_id,
                    'group_id' => 2,
                ];
                insert_details($user_details, "users_groups");
                if ($res != FALSE) {
                    $where = (!empty($mobile)) ? ['mobile' => $mobile] : ['email' => $email];
                    // $token = (!empty($mobile)) ? generate_tokens($mobile) : generate_tokens($email);
                    // update_details(['apikey' => $token], $where, "users");
                    update_details(['active' => 1], $where, 'users');
                    $data = fetch_details('users', $where);

                    unset($data[0]['password']);
                    unset($data[0]['apikey']);

                    $data = array_map(function ($value) {
                        return $value === NULL ? "" : $value;
                    }, $data[0]);

                    $this->response['error'] = false;
                    // $this->response['token'] = $token;
                    $this->response['message'] = 'Registered Successfully';
                    $this->response['data'] = $data;
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Registered Faild';
                    $this->response['data'] = array();
                }
            }
            print_r(json_encode($this->response));
        }
    }

    public function download_link_hash()
    {
        /*
           order_item_id : 100
           user_id : 100
        */
        if (!$this->verify_token()) {
            return false;
        }

        $this->form_validation->set_rules('order_item_id', 'Order Item Id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }
        $order_item_id = (isset($_POST['order_item_id']) && (trim($_POST['order_item_id'])) != "") ? $this->input->post('order_item_id', true) : '';
        $user_id = (isset($_POST['user_id']) && (trim($_POST['user_id'])) != "") ? $this->input->post('user_id', true) : '';;
        $oreder_item_data = fetch_details('order_items', ['id' => $order_item_id], '*');
        $transaction_data = fetch_details('transactions', ['order_item_id' => $order_item_id], 'status');

        if (isset($order_item_id) && $order_item_id != '' && isset($user_id) && $user_id != '') {

            if (isset($oreder_item_data) && !empty($oreder_item_data) && isset($transaction_data) && !empty($transaction_data)) {
                if ($order_item_id == $oreder_item_data[0]['id'] && $user_id == $oreder_item_data[0]['user_id']) {
                    if (strtolower($transaction_data[0]['status']) == 'success' || strtolower($transaction_data[0]['status'] == 'received')) {
                        $file = $oreder_item_data[0]['hash_link'];
                        $file = explode("?", $file);
                        $url = $file[0];
                        if (preg_match('(http:|https:)', $url) === 1) {
                            $file_path = $url;
                        } else {
                            $file_path = base_url($url);
                        }
                        $this->response['error'] = false;
                        $this->response['message'] = 'Download fetch sucessfully';
                        $this->response['data'] = $file_path;
                    } else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Transaction is not successful for this order';
                    }
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'You are not authorized to download this file';
                }
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'No data found for this order';
            }
            print_r(json_encode($this->response));
        }
    }
}
