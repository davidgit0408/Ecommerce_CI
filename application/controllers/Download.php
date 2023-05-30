<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Download extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->load->library(['pagination']);
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
        $this->load->helper('file');
        $this->load->helper('download');
    }

    public function download_reel($reel_id = '')
    {
        $res = $this->db->select('*')->where('id', $reel_id)->get('reel')->result_array();
        $data = file_get_contents(base_url().$res[0]['sub_directory'].$res[0]['name']);
        $name = $res[0]['name'];
        force_download($name, $data);
    }
}
