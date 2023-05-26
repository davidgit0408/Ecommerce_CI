<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sellers extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['cart_model', 'category_model', 'rating_model','Home_model','Seller_model']);
        $this->load->library(['pagination']);
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
    }

    public function index()
    {
        $this->form_validation->set_data($this->input->get(null, true));
        $this->form_validation->set_rules('per-page', 'Per Page', 'trim|numeric|xss_clean');

        if (!empty($_GET) && !$this->form_validation->run()) {
            redirect(base_url('sellers'));
        }
        $sellers = $this->Seller_model->get_sellers();
        $limit = ($this->input->get('per-page')) ? $this->input->get('per-page', true) : 12;
        $sort_by = ($this->input->get('sort')) ? $this->input->get('sort', true) : '';
        $seller_search = ($this->input->get('seller_search')) ? $this->input->get('seller_search', true) : '';
        if (!empty($category_id)) {
            $category_id = explode('|', $category_id);
        }

        //Seller Sorting
        $sort = $order = '';
        if ($sort_by == "top-rated") {
            $sort = 'rating';
            $order = 'DESC';
        } elseif ($sort_by == "date-desc") {
            $sort = 'u.id';
            $order = 'desc';
        } elseif ($sort_by == "date-asc") {
            $sort = 'u.id';
            $order = 'asc';
        }

        $config['base_url'] = base_url('sellers');
        $config['total_rows'] = $sellers['total'];
        $config['per_page'] = $limit;
        $config['num_links'] = 7;
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['page_query_string'] = FALSE;

        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';

        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_link'] = 'First';
        $config['first_tag_close'] = '</li>';

        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_link'] = 'Last';
        $config['last_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_link'] = '<i class="fa fa-arrow-left"></i>';
        $config['prev_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_link'] = '<i class="fa fa-arrow-right"></i>';
        $config['next_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $page_no = (empty($this->uri->segment(2))) ? 1 : $this->uri->segment(2);
        if (!is_numeric($page_no)) {
            redirect(base_url('sellers'));
        }
        $offset = ($page_no - 1) * $limit;
        $this->pagination->initialize($config);
        $this->data['links'] =  $this->pagination->create_links();

        $this->data['main_page'] = 'seller-listing';
        $this->data['title'] = 'Seller Listing | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Seller Listing, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Seller Listing | ' . $this->data['web_settings']['meta_description'];
        $this->data['seller_search'] = $seller_search;
        $sellers = $this->Seller_model->get_sellers("",$limit,$offset,$sort,$order,$seller_search);
        $this->data['sellers'] = $sellers['data'];
        $this->data['page_main_bread_crumb'] = "Seller Listing";
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    

}
