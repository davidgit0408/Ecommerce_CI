<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification_settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['Setting_model', 'notification_model', 'category_model']);
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!has_permissions('read', 'notification_setting')) {
                $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
                redirect('admin/home', 'refresh');
            }
            $this->data['main_page'] = FORMS . 'notification-settings';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Update Notification Settings | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Update Notification Settings  | ' . $settings['app_name'];
            $this->data['fcm_server_key'] = get_settings('fcm_server_key');
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function manage_notifications()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!has_permissions('read', 'send_notification')) {
                $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
                redirect('admin/home', 'refresh');
            }

            $this->data['main_page'] = TABLES . 'manage-notifications';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Send Notification | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Send Notification | ' . $settings['app_name'];
            $this->data['categories'] = $this->category_model->get_categories();
            if (isset($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details('notifications', ['id' => $_GET['edit_id']]);
            }
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function get_notification_list()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->notification_model->get_notification_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    public function get_notifications_data()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->notification_model->get_notifications_data();
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    public function manage_system_notifications()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!has_permissions('read', 'send_notification')) {
                $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
                redirect('admin/home', 'refresh');
            }

            $this->data['main_page'] = TABLES . 'manage-system-notification';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'System Notification | ' . $settings['app_name'];
            $this->data['meta_description'] = ' System Notification | ' . $settings['app_name'];

            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    public function delete_notification()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('delete', 'send_notification'), PERMISSION_ERROR_MSG, 'send_notification', false)) {
                return true;
            }

            if (delete_details(['id' => $_GET['id']], 'notifications')) {
                $response['error'] = false;
                $response['message'] = 'Deleted Succesfully';
            } else {
                $response['error'] = true;
                $response['message'] = 'Something Went Wrong';
            }
            echo json_encode($response);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function update_notification_settings()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (!has_permissions('read', 'notification_setting')) {
                $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
                redirect('admin/home', 'refresh');
            }
            if (defined('SEMI_DEMO_MODE') && SEMI_DEMO_MODE == 0) {
                $this->response['error'] = true;
                $this->response['message'] = SEMI_DEMO_MODE_MSG;
                echo json_encode($this->response);
                return false;
                exit();
            }
            if (print_msg(!has_permissions('update', 'notification_setting'), PERMISSION_ERROR_MSG, 'notification_setting')) {
                return false;
            }

            $this->form_validation->set_rules('fcm_server_key', 'Fcm Server Key', 'trim|required|xss_clean');

            if (!$this->form_validation->run()) {

                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $this->Setting_model->update_fcm_details($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = 'System Setting Updated Successfully';
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function send_notifications()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('create', 'send_notification'), PERMISSION_ERROR_MSG, 'send_notification')) {
                return false;
            }
            $is_image_included = (isset($_POST['image_checkbox']) && $_POST['image_checkbox'] == 'on') ? TRUE : FALSE;
            if ($is_image_included) {
                $this->form_validation->set_rules('image', 'Image', 'trim|required|xss_clean', array('required' => 'Image is required'));
            }
            $this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
            $this->form_validation->set_rules('send_to', 'Send To', 'trim|required|xss_clean');
            $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
            $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');

            if (isset($_POST['type']) && $_POST['type'] == 'categories') {
                $this->form_validation->set_rules('category_id', 'Category', 'trim|required|xss_clean');
            }

            if (isset($_POST['type']) && $_POST['type'] == 'products') {
                $this->form_validation->set_rules('product_id', 'Product', 'trim|required|xss_clean');
            }
            if (isset($_POST['send_to']) && $_POST['send_to'] == 'specific_user') {
                // send to specific user
                $this->form_validation->set_rules('select_user_id[]', 'User', 'trim|required|xss_clean', ["required" => "Please select atleast one user"]);
            }

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return;
            }
            $fcm_key = get_settings('fcm_server_key');

            if (empty($fcm_key)) {
                $this->response['error'] = true;
                $this->response['message'] = "No FCM Key Found";
                print_r(json_encode($this->response));
                return;
            }

            //creating a new push
            $data = $this->input->post(null, true);
            $title = $this->input->post('title', true);
            $send_to = $this->input->post('send_to', true);
            $type = $this->input->post('type', true);
            $message = $this->input->post('message', true);
            $users = 'all';
            $type_ids = '';
            if (isset($_POST['type']) && $_POST['type'] == 'categories') {
                $type_id = $this->input->post('category_id', true);
            } elseif (isset($_POST['type']) && $_POST['type'] == 'products') {
                $type_id = $this->input->post('product_id', true);
            } else {
                $type_id = '';
                $type_ids = '';
            }

            if (isset($send_to) && $send_to == 'specific_user') {
                /* select user's FCM IDs */
                $user_ids = $this->input->post("select_user_id[]", true);
                $results = fetch_details('users', null, 'fcm_id', 10000, 0, '', '', "id", $user_ids);
                $result = array();
                for ($i = 0; $i <= count($results); $i++) {
                    if (isset($results[$i]['fcm_id']) && !empty($results[$i]['fcm_id']) && ($results[$i]['fcm_id'] != 'NULL')) {
                        $res = array_merge($result, $results);
                    }
                }
            } else {
                /* To all users */
                $this->ion_auth->select(["fcm_id"]);
                $res = $this->ion_auth->users('members')->result_array();
            }

            if (empty($res)) {
                $this->response['notification'] = [];
                $this->response['data'] = [];
                $this->response['error'] = true;
                $this->response['message'] = 'There is no users to send notification.';
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($this->response);
                return;
            }

            $fcm_ids = array();
            foreach ($res as $fcm_id) {
                if (!empty($fcm_id)) {
                    $fcm_ids[] = $fcm_id['fcm_id'];
                }
            }
            $registrationIDs = $fcm_ids;
            if (isset($_POST['send_to']) && $_POST['send_to'] == 'specific_user') {
                $data['select_user_id'] = (isset($data['select_user_id'])) ? json_encode($data['select_user_id']) : json_encode([]);
            }
            if ($is_image_included) {
                $notification_image_name =  $_POST['image'];
                $data['image'] = $_POST['image'];
                $this->notification_model->add_notification($data);
            } else {
                $this->notification_model->add_notification($data);
            }
            //first check if the push has an image with it
            if ($is_image_included) {
                $fcmMsg = array(
                    'content_available' => true,
                    'title' => "$title",
                    'body' => "$message",
                    'type' => "$type",
                    'type_id' => "$type_ids",
                    'image' => base_url()  . $notification_image_name,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                );
            } else {
                //if the push don't have an image give null in place of image
                $fcmMsg = array(
                    'content_available' => true,
                    'title' => "$title",
                    'body' => "$message",
                    'image' => '',
                    'type' => "$type",
                    'type_id' => "$type_ids",
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                );
            }

            $registrationIDs_chunks = array_chunk($registrationIDs, 1000);
            $fcmFields = send_notification($fcmMsg, $registrationIDs_chunks);

            $this->response['notification'] = $fcmFields['notification'];
            $this->response['data'] = $fcmFields['data'];
            $this->response['error'] = false;
            $this->response['message'] = 'Notification Sended Successfully';
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($this->response);
            return;
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    public function delete_system_notification()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('delete', 'send_notification'), PERMISSION_ERROR_MSG, 'send_notification', false)) {
                return true;
            }

            if (delete_details(['id' => $_GET['id']], 'system_notification')) {
                $response['error'] = false;
                $response['message'] = 'Deleted Succesfully';
            } else {
                $response['error'] = true;
                $response['message'] = 'Something Went Wrong';
            }
            echo json_encode($response);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
