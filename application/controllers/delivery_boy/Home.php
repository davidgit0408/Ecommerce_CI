<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);
		$this->load->model('Home_model');
	}

	public function index()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
			$user_id = $this->session->userdata('user_id');
			$user_res = $this->db->select('balance,bonus,username')->where('id', $user_id)->get('users')->result_array();
			$this->data['main_page'] = FORMS . 'home';
			$settings = get_settings('system_settings', true);
			$this->data['curreny'] = get_settings('currency');
			$this->data['title'] = 'Delivery Boy Panel | ' . $settings['app_name'];
			$this->data['order_counter'] = $this->Home_model->count_new_orders();
			$this->data['balance'] = ($user_res[0]['balance'] == NULL) ? 0 : $user_res[0]['balance'];
			$this->data['bonus'] = ($user_res[0]['bonus'] == NULL)  ? 0 : $user_res[0]['bonus'];
			$this->data['username'] =  $user_res[0]['username'];
			$this->data['meta_description'] = 'Delivery Boy Panel | ' . $settings['app_name'];
			$this->load->view('delivery_boy/template', $this->data);
		} else {
			redirect('delivery_boy/login', 'refresh');
		}
	}

	public function profile()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_delivery_boy()) {
			$identity_column = $this->config->item('identity', 'ion_auth');
			$this->data['users'] = $this->ion_auth->user()->row();
			$settings = get_settings('system_settings', true);
			$this->data['identity_column'] = $identity_column;
			$this->data['main_page'] = FORMS . 'profile';
			$this->data['title'] = 'Change Password | ' . $settings['app_name'];
			$this->data['meta_description'] = 'Change Password | ' . $settings['app_name'];
			$this->load->view('delivery_boy/template', $this->data);
		} else {
			redirect('delivery_boy/home', 'refresh');
		}
	}

	public function logout()
	{
		$this->ion_auth->logout();
		redirect('delivery_boy/login', 'refresh');
	}
}
