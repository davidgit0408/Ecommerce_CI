<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['address_model', 'category_model', 'cart_model', 'faq_model', 'blog_model']);
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
    }

    public function index()
    {
        $this->data['main_page'] = 'home';
        $this->data['title'] = 'Home | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Home, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Home | ' . $this->data['web_settings']['meta_description'];

        $limit =  12;
        $offset =  0;
        $sort = 'row_order';
        $order =  'ASC';
        $has_child_or_item = 'false';
        $filters = [];
        /* Fetching Categories Sections */
        $categories = $this->category_model->get_categories('', $limit, $offset, $sort, $order, 'false');
        /* Fetching Featured Sections */

        $sections = $this->db->limit($limit, $offset)->order_by('row_order')->get('sections')->result_array();

        $user_id = NULL;
        if ($this->data['is_logged_in']) {
            $user_id = $this->data['user']->id;
        }
        $filters['show_only_active_products'] = true;
        if (!empty($sections)) {
            for ($i = 0; $i < count($sections); $i++) {
                $product_ids = isset($sections[$i]['product_ids']) ? explode(',', (string)$sections[$i]['product_ids']) : '';
                $product_ids = array_filter((array)$product_ids);

                $product_categories = (isset($sections[$i]['categories']) && !empty($sections[$i]['categories']) && $sections[$i]['categories'] != NULL) ? explode(',', $sections[$i]['categories']) : null;
                if (isset($sections[$i]['product_type']) && !empty($sections[$i]['product_type'])) {
                    $filters['product_type'] = (isset($sections[$i]['product_type'])) ? $sections[$i]['product_type'] : null;
                }

                if ($sections[$i]['style'] == "default") {
                    $limit = 10;
                } elseif ($sections[$i]['style'] == "style_1" || $sections[$i]['style'] == "style_2") {
                    $limit = 7;
                } elseif ($sections[$i]['style'] == "style_3" || $sections[$i]['style'] == "style_4") {
                    $limit = 5;
                } else {
                    $limit = null;
                }
                $products = fetch_product($user_id, (isset($filters)) ? $filters : null, (isset($product_ids)) ? $product_ids : null, $product_categories, $limit, null, null, null);
                // print_R($products);
                $sections[$i]['title'] =  output_escaping($sections[$i]['title']);
                $sections[$i]['slug'] =  url_title($sections[$i]['title'], 'dash', true);
                $sections[$i]['short_description'] =  output_escaping($sections[$i]['short_description']);
                $sections[$i]['filters'] = (isset($products['filters'])) ? $products['filters'] : [];
                $sections[$i]['product_details'] =  $products['product'];
                unset($sections[$i]['product_details'][0]['total']);
                $sections[$i]['product_details'] = $products['product'];
                unset($product_details);
            }
        }

        $this->data['sections'] = $sections;
        $this->data['categories'] = $categories;
        $this->data['username'] = $this->session->userdata('username');
        $this->data['sliders'] = get_sliders();
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function error_404()
    {
        $this->data['main_page'] = 'error_404';
        $this->data['title'] = 'Product cart | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Product cart, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Product cart | ' . $this->data['web_settings']['meta_description'];
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function categories()
    {
        $limit =  50;
        $offset =  0;
        $sort = 'row_order';
        $order =  'ASC';
        $has_child_or_item = 'false';
        $this->data['main_page'] = 'categories';
        $this->data['title'] = 'Categories | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Categories, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Categories | ' . $this->data['web_settings']['meta_description'];
        $this->data['categories'] = $this->category_model->get_categories('', $limit, $offset, $sort, $order, 'false');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function get_products()
    {

        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('id', 'Product ID', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('search', 'Search', 'trim|xss_clean');
        $this->form_validation->set_rules('category_id', 'Category id', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
        $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('order', 'order', 'trim|xss_clean|alpha');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {

            $limit = (isset($_GET['limit'])) ? $this->input->post('limit', true) : 25;
            $offset = (isset($_GET['offset'])) ? $this->input->post('offset', true) : 0;
            $order = (isset($_GET['order']) && !empty(trim($_GET['order']))) ? $_GET['order'] : 'DESC';
            $sort = (isset($_GET['sort']) && !empty(trim($_GET['sort']))) ? $_GET['sort'] : 'p.id';
            $filters['search'] =  (isset($_GET['search'])) ? $_GET['search'] : null;
            $filters['attribute_value_ids'] = (isset($_GET['attribute_value_ids'])) ? $_GET['attribute_value_ids'] : null;
            $category_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : null;
            $product_id = (isset($_GET['id'])) ? $_GET['id'] : null;
            $user_id = (isset($_GET['user_id'])) ? $_GET['user_id'] : null;

            $products = fetch_product($user_id, (isset($filters)) ? $filters : null, $product_id, $category_id, $limit, $offset, $sort, $order);
            $first_search_option[0] = array(
                'id' => 0,
                'image_sm' => base_url(get_settings('favicon')),
                'name' => 'Search Result for ' . $_GET['search'],
                'category_name' => 'all categories',
                'link' => base_url('products/search?q=' . $_GET['search']),
            );
            if (!empty($products['product'])) {
                $products['product'] = array_map(function ($d) {
                    $d['link'] = base_url('products/details/' . $d['slug']);
                    return $d;
                }, $products['product']);
                $this->response['error'] = false;
                $this->response['message'] = "Products retrieved successfully !";
                $this->response['filters'] = (isset($products['filters']) && !empty($products['filters'])) ? $products['filters'] : [];
                $this->response['total'] = (isset($products['total'])) ? strval($products['total']) : '';
                $this->response['offset'] = (isset($_GET['offset']) && !empty($_GET['offset'])) ? $_GET['offset'] : '0';
                $products['product'] = array_merge($first_search_option, $products['product']);
                $this->response['data'] = $products['product'];
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "Products Not Found !";
                $this->response['data'] =  $first_search_option;
            }
        }
        print_r(json_encode($this->response));
    }
    public function get_product_faqs()
    {
        $filters['search'] =  (isset($_GET['search'])) ? $_GET['search'] : null;
        $first_search_option[0] = array(
            'id' => 0,
            'image_sm' => base_url(get_settings('favicon')),
            'name' => 'Search Result for ' . $_GET['search'],
            'category_name' => 'all categories',
            'link' => base_url('products/search?q=' . $_GET['search']),
        );
    }
    public function address_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->address_model->get_address_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function checkout()
    {
        $this->data['main_page'] = 'checkout';
        $this->data['title'] = 'Checkout | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Checkout, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Checkout | ' . $this->data['web_settings']['meta_description'];
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function terms_and_conditions()
    {
        $this->data['main_page'] = 'terms-and-conditions';
        $this->data['title'] = 'Terms & Conditions | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Terms & Conditions, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Terms & Conditions | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Terms & Conditions | ' . $this->data['web_settings']['site_title'];
        $this->data['terms_and_conditions'] = get_settings('terms_conditions');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function privacy_policy()
    {
        $this->data['main_page'] = 'privacy-policy';
        $this->data['title'] = 'Privacy Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Privacy Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Privacy Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Privacy Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['privacy_policy'] = get_settings('privacy_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    public function shipping_policy()
    {
        $this->data['main_page'] = 'shipping-policy';
        $this->data['title'] = 'Shipping Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Shipping Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Shipping Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Shipping Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['shipping_policy'] = get_settings('shipping_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    public function return_policy()
    {
        $this->data['main_page'] = 'return-policy';
        $this->data['title'] = 'Return Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Return Policy, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Return Policy | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Return Policy | ' . $this->data['web_settings']['site_title'];
        $this->data['return_policy'] = get_settings('return_policy');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    public function about_us()
    {
        $this->data['main_page'] = 'about-us';
        $this->data['title'] = 'About US | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'About US, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'About US | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'About US | ' . $this->data['web_settings']['site_title'];
        $this->data['about_us'] = get_settings('about_us');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function contact_us()
    {
        $this->data['main_page'] = 'contact-us';
        $this->data['title'] = 'Contact US | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Contact US, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Contact US | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Contact US | ' . $this->data['web_settings']['site_title'];
        $this->data['contact_us'] = get_settings('contact_us');
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function faq()
    {
        $this->data['main_page'] = 'faq';
        $this->data['title'] = 'FAQ | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'FAQ, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'FAQ | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'FAQ | ' . $this->data['web_settings']['site_title'];
        $this->data['faq'] = $this->faq_model->get_faqs(null, null, null, null);
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function blogs()
    {
        $this->data['main_page'] = 'blogs';
        $this->data['title'] = 'Blogs | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Blogs, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Blogs | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Blogs | ' . $this->data['web_settings']['site_title'];
        $this->data['blogs'] = $this->blog_model->get_blogs(null, null, null, null);
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    /**
     * Log the user in
     */
    public function login()
    {
        $this->data['title'] = $this->lang->line('login_heading');
        $identity_column = $this->config->item('identity', 'ion_auth');
        // validate form input
        $this->form_validation->set_rules('identity', ucfirst($identity_column), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

        if ($this->form_validation->run() === TRUE) {
            $tables = $this->config->item('tables', 'ion_auth');
            $identity = $this->input->post('identity', true);
            $res = $this->db->select('id')->where($identity_column, $identity)->get($tables['login_users'])->result_array();

            $user_data = fetch_details('users', ['mobile' => $identity]);

            if (isset($user_data[0]['active']) && !empty($user_data) && $user_data[0]['active'] == 7) {
                $response['error'] = true;
                $response['message'] = 'User Not Found';
                echo json_encode($response);
                return false;
            }
            if (!empty($res)) {
                // check to see if the user is logging in
                // check for "remember me"
                $remember = (bool)$this->input->post('remember');

                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                    //if the login is successful
                    if (!$this->input->is_ajax_request()) {
                        redirect('admin/home', 'refresh');
                    }
                    $this->response['error'] = false;
                    $this->response['message'] = $this->ion_auth->messages();
                    echo json_encode($this->response);
                } else {
                    // if the login was un-successful
                    $this->response['error'] = true;
                    $this->response['message'] = $this->ion_auth->errors();
                    echo json_encode($this->response);
                }
            } else {
                $this->response['error'] = true;
                $this->response['message'] = '<div>Incorrect Login</div>';
                echo json_encode($this->response);
            }
        } else {
            // the user is not logging in so display the login page
            if (validation_errors()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                echo json_encode($this->response);
                return false;
                exit();
            }
            if ($this->session->flashdata('message')) {
                $this->response['error'] = false;
                $this->response['message'] = $this->session->flashdata('message');
                echo json_encode($this->response);
                return false;
                exit();
            }

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
            ];

            $this->data['password'] = [
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
            ];

            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'login', $this->data);
        }
    }

    public function lang($lang_name = '')
    {
        if (empty($lang_name)) {
            redirect(base_url());
        }

        $language = get_languages(null, $lang_name);
        if (empty($language)) {
            redirect(base_url());
        }
        $this->lang->load('web_labels_lang', $lang_name);
        $cookie = array(
            'name'   => 'language',
            'value'  => $lang_name,
            'expire' => time() + 1000
        );
        $this->input->set_cookie($cookie);
        if (isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(base_url());
        }
    }

    public function reset_password()
    {
        /* Parameters to be passed
            mobile_no:7894561235            
            new: pass@123
        */
        $this->form_validation->set_rules('mobile', 'Mobile No', 'trim|numeric|required|xss_clean|max_length[16]');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            print_r(json_encode($this->response));
            return false;
        }

        $identity_column = $this->config->item('identity', 'ion_auth');
        $res = fetch_details('users', ['mobile' => $_POST['mobile']]);
        if (!empty($res)) {
            $identity = ($identity_column  == 'email') ? $res[0]['email'] : $res[0]['mobile'];
            if (!$this->ion_auth->reset_password($identity, $_POST['new_password'])) {
                $this->response['error'] = true;
                $this->response['message'] = $this->ion_auth->messages();
                $this->response['data'] = array();
                echo json_encode($this->response);
                return false;
            } else {
                $this->response['error'] = false;
                $this->response['message'] = 'Reset Password Successfully';
                $this->response['data'] = array();
                echo json_encode($this->response);
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'User does not exists !';
            $this->response['data'] = array();
            echo json_encode($this->response);
            return false;
        }
    }

    public function send_contact_us_email()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            print_r(json_encode($this->response));
            return false;
        }

        $username = $this->input->post('username', true);
        $email = $this->input->post('email', true);
        $subject = $this->input->post('subject', true);
        $message = $this->input->post('message', true);
        $web_settings = get_settings('web_settings', true);
        $to = $web_settings['support_email'];
        $email_message = array(
            'username' => 'Hello, Dear <b>' . ucfirst($username) . '</b>, ',
            'subject' => $subject,
            'email' =>  'email : ' . $email,
            'message' => $message
        );
        $mail = send_mail($to,  $subject, $this->load->view('admin/pages/view/contact-email-template', $email_message, TRUE));
        if ($mail['error'] == true) {
            $this->response['error'] = true;
            $this->response['message'] = "Cannot send mail. Please try again later.";
            $this->response['data'] = $mail['message'];
            echo json_encode($this->response);
            return false;
        } else {
            $this->response['error'] = false;
            $this->response['message'] = 'Mail sent successfully. We will get back to you soon.';
            $this->response['data'] = array();
            echo json_encode($this->response);
            return false;
        }
    }
}
