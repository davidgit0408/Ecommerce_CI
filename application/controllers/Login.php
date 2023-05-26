<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->lang->load('auth');
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
        $this->data['settings'] = get_settings('system_settings', true);
    }

    public function login_check()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'home';
            $this->data['title'] = 'Login Panel | ' . $this->data['settings']['app_name'];
            $this->data['meta_description'] = 'Login Panel | ' . $this->data['settings']['app_name'];

            $identity_column = $this->config->item('identity', 'ion_auth');
            if ($identity_column == 'mobile') {
                $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|required|xss_clean');
            } elseif ($identity_column == 'email') {
                $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
            } else {
                $this->form_validation->set_rules('identity', 'Identity', 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

            $login = $this->ion_auth->login($this->input->post('mobile'), $this->input->post('password'));
            if ($login) {
                $data = fetch_details('users', ['mobile' => $this->input->post('mobile', true)]);
                $username = $this->session->set_userdata('username', $data[0]['username']);
                $this->response['error'] = false;
                $this->response['message'] = 'Login Succesfully';
                echo json_encode($this->response);
                return false;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Mobile Number or Password is wrong.';
                echo json_encode($this->response);
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'You are already logged in.';
            echo json_encode($this->response);
            return false;
        }
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect('home', 'refresh');
    }

    public function update_user()
    {
        if (print_msg(!has_permissions('update', 'profile'), PERMISSION_ERROR_MSG, 'payment_settings')) {
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
            $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email|edit_unique[users.email.' . $user->id . ']');
        } else {
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|xss_clean|trim|numeric|edit_unique[users.mobile.' . $user->id . ']');
        }
        $this->form_validation->set_rules('username', 'Username', 'required|xss_clean|trim');

        if (!empty($_POST['old']) || !empty($_POST['new']) || !empty($_POST['new_confirm'])) {
            $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
            $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
        }


        $tables = $this->config->item('tables', 'ion_auth');
        if (!$this->form_validation->run()) {
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
        } else {

            if (!empty($_POST['old']) || !empty($_POST['new']) || !empty($_POST['new_confirm'])) {
                if (!$this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'))) {
                    // if the login was un-successful
                    $this->response['error'] = true;
                    $this->response['message'] = $this->ion_auth->errors();
                    echo json_encode($this->response);
                    return false;
                }
            }
            $user_details = ['username' => $this->input->post('username'), 'email' => $this->input->post('email')];
            $user_details = escape_array($user_details);
            $this->db->set($user_details)->where($identity_column, $identity)->update($tables['login_users']);
            $this->response['error'] = false;
            $this->response['message'] = 'Profile Update Succesfully';
            echo json_encode($this->response);
            return false;
        }
    }
}
