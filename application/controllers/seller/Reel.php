<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload']);
        $this->load->helper(['url', 'language', 'file']);
        $this->load->model(['reel_model']);

    }
    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = VIEW . 'reel-gallary';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Media | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Media |' . $settings['app_name'];
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function upload()
    {
        if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_seller()) {
            redirect('seller/login', 'refresh');
            exit();
        }
//        if (print_msg(!has_permissions('create', 'reel'), PERMISSION_ERROR_MSG, 'reel')) {
//            return false;
//        }
        $year = date('Y');
        $target_path = FCPATH . MEDIA_PATH . $year . '/';
        $sub_directory = MEDIA_PATH . $year . '/';

        if (!file_exists($target_path)) {
            mkdir($target_path, 0777, true);
        }

        $temp_array = $reel_ids = $other_images_new_name = array();
        $files = $_FILES;
        $other_image_info_error = "";
        $allowed_reel_types = implode('|', allowed_reel_types());
        $config['upload_path'] = $target_path;
        $config['allowed_types'] = $allowed_reel_types;
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
                    $reel_ids[] = $reel_id = $this->reel_model->set_reel($temp_array); /* set reel in database */
                    if (strtolower($temp_array['image_type']) != 'gif')
                        resize_image($temp_array,  $target_path, $reel_id);
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
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['file_name'] = '';
            $this->response['message'] = (empty($_FILES)) ? "Files not Uploaded Successfully..!" :  $other_image_info_error;
            print_r(json_encode($this->response));
        } else {
            $this->response['error'] = false;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['file_name'] = $_FILES['documents']['name'][0];
            $this->response['message'] = "Files Uploaded Successfully..!";
            $this->response['error'] = $other_image_info_error;
            print_r(json_encode($this->response));
        }
    }

    function delete($reelid = false)
    {
        if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_seller()) {
            redirect('seller/login', 'refresh');
            exit();
        }
        if (print_msg(!has_permissions('delete', 'reel'), PERMISSION_ERROR_MSG, 'reel')) {
            return false;
        }
        $urlid = $this->uri->segment(4);
        $id = (isset($urlid)  && !empty($urlid)) ? $urlid : $reelid;
        /* check if id is not empty or invalid */
        if (!is_numeric($id) && $id == '') {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = "Something went wrong! Try again!";
            print_r(json_encode($this->response));
            return false;
        }
        $reel = $this->reel_model->get_reel_by_id($id);
        /* check if reel actually exists */
        if (empty($reel)) {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = "Media does not exist!";
            print_r(json_encode($this->response));
            return false;
        }
        $path = FCPATH . $reel[0]['sub_directory'] . $reel[0]['name'];
        $where = array('id' => $id);

        if (delete_details($where, 'reel')) {

            delete_images($reel[0]['sub_directory'], $reel[0]['name']);
            $this->response['error'] = false;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = "Media deleted successfully!";
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['message'] = "Media could not be deleted!";
            print_r(json_encode($this->response));
            return false;
        }
    }

    function fetch()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            return $this->reel_model->fetch_reel();
        } else {
            redirect('seller/login', 'refresh');
        }
    }
}
