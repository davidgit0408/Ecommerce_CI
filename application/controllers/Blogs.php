<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blogs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['blog_model']);
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->load->library(['pagination']);
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
    }

    public function index()
    {
        $limit = ($this->input->get('per-page')) ? $this->input->get('per-page', true) : 12;
        $category_id = ($this->input->get('category_id')) ? $this->input->get('category_id', true) : NULL;
        $blog_search = ($this->input->get('blog_search')) ? $this->input->get('blog_search', true) : '';
        $config['base_url'] = base_url('blogs');
        $total_rows = $this->blog_model->get_blogs(null, null, null, null, $blog_search, $category_id);
        $config['total_rows'] = $total_rows['total'];
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
            redirect(base_url('blogs'));
        }

        $offset = ($page_no - 1) * $limit;
        $this->pagination->initialize($config);
        $this->data['links'] =  $this->pagination->create_links();
        $this->data['main_page'] = 'blogs';
        $this->data['title'] = 'Blogs | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Blogs, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Blogs | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Blogs | ' . $this->data['web_settings']['site_title'];
        $this->data['blog_search'] = $blog_search;
        $this->data['blogs'] = $this->blog_model->get_blogs($offset, $limit, null, null, $blog_search, $category_id);
        $this->data['fetched_data'] = fetch_details('blog_categories');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function view_detail()
    {
        $this->data['main_page'] = 'view_blog';
        $this->data['title'] = 'View Blog | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'View Blog, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'View Blog | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'View Blog | ' . $this->data['web_settings']['site_title'];
        $blog_slug = $this->uri->segment(3);
        $this->data['blog'] = fetch_details('blogs', ['slug' => $blog_slug], 'id,title,description,image,slug,date_added');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
}
