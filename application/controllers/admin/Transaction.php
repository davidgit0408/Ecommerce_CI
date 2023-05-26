<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library(['ion_auth', 'form_validation','upload']);
		$this->load->helper(['url', 'language','file']);		
        $this->load->model('Transaction_model');	
	}

	public function customer_wallet()
	{
		if($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->data['main_page'] = TABLES.'customer-wallet';
			$settings=get_settings('system_settings',true);
			$this->data['title'] = 'Customer wallet | '.$settings['app_name'];
			$this->data['meta_description'] = ' Customer wallet  | '.$settings['app_name'];	
			$this->load->view('admin/template',$this->data);
		}
		else{
			redirect('admin/login','refresh');
		}
	}

    public function wallet_transactions()
	{
		if($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->data['main_page'] = TABLES.'seller-wallet';
			$settings=get_settings('system_settings',true);
			$this->data['title'] = 'Seller wallet | '.$settings['app_name'];
			$this->data['meta_description'] = ' Seller wallet  | '.$settings['app_name'];	
			$this->load->view('admin/template',$this->data);
		}
		else{
			redirect('admin/login','refresh');
		}
	}

	public function view_transaction()
	{
		if($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->data['main_page'] = TABLES.'transaction';
			$settings=get_settings('system_settings',true);
			$this->data['title'] = 'View Transaction | '.$settings['app_name'];
			$this->data['meta_description'] = ' View Transaction  | '.$settings['app_name'];	
			$this->load->view('admin/template',$this->data);
		}
		else{
			redirect('admin/login','refresh');
		}
	}

	public function view_transactions()
	{
		if($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{			
			return $this->Transaction_model->get_transactions_list();
		}
		else{
			redirect('admin/login','refresh');
		}
	}
    public function edit_transactions()
    {
        if($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{			
            $this->form_validation->set_rules('status', 'status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('txn_id', 'txn_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id', 'id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $_POST['message'] = (isset($_POST['message']) && trim($_POST['message']) != "") ? $this->input->post('message', true) : "";
                $this->Transaction_model->edit_transactions($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = "Transaction Updated Successfuly.";
                print_r(json_encode($this->response));
            }
		}
		else{
			redirect('admin/login','refresh');
		}
    }
}	

?>