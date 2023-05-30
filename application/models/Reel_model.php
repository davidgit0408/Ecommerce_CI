<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reel_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function set_reel($data, $seller_id = 0)
    {
        $data = escape_array($data);
        $extenstion = trim($data['file_ext'], '.');
        $extenstionData = find_media_type($extenstion);
        $reel_type = $extenstionData[0];
        if (empty($seller_id))
            $seller_id = ($this->ion_auth->is_seller()) ? $this->session->userdata('user_id') : 0;
        $data = [
            'name' => $data['file_name'],
            'seller_id' => $seller_id,
            'extension' => ltrim($data['file_ext'], '.'),
            'title' => $data['raw_name'],
            'type' => ($reel_type != false) ? $reel_type : 'other',
            'size' => $data['file_size'],
            'sub_directory' => $data['sub_directory'],
        ];

        $this->db->insert('reel', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function get_reel_by_id($id)
    {
        $this->db->where('id', $id);
        $q = $this->db->get('reel');
        return $q->result_array();
    }


    public function fetch_reel()
    {
        if (($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) || ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0))) {

            $multipleWhere = $where_in = '';

            if (isset($_GET['offset']))
                $offset = $_GET['offset'];
            if (isset($_GET['limit']))
                $limit = $_GET['limit'];

            if (isset($_GET['sort']))
                if ($_GET['sort'] == 'id') {
                    $sort = "id";
                } else {
                    $sort = $_GET['sort'];
                }
            if (isset($_GET['order']))
                $order = $_GET['order'];

            if (isset($_GET['search']) and $_GET['search'] != '') {
                $search = $_GET['search'];
                $multipleWhere = ['id' => $search, 'name' => $search];
            }
            if (isset($_GET['type']) and $_GET['type'] != '') {
                // $where['type'] = trim(strtolower($_GET['type']));
                $type = explode(",", $this->input->get('type'));
                $where_in = $type;
            }
            if (isset($this->ion_auth->user()->row()->id) && $this->ion_auth->user()->row()->id != null) {
                $where['seller_id'] = $this->ion_auth->user()->row()->id;
            }
            $count_res = $this->db->select(' COUNT(id) as `total` ');

            if (isset($multipleWhere) && !empty($multipleWhere)) {
                $count_res->or_like($multipleWhere);
            }
            if (isset($where) && !empty($where)) {
                $count_res->where($where);
            }
            if(isset($where_in) && !empty($where_in)){
                $count_res->where_in("type", $where_in);
            }
            if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

                $count_res->where(" DATE(date_created) >= DATE('" . $_GET['start_date'] . "') ");
                $count_res->where(" DATE(date_created) <= DATE('" . $_GET['end_date'] . "') ");
            }
            $attr_count = $count_res->get('reel')->result_array();

            foreach ($attr_count as $row) {
                $total = $row['total'];
            }

            $search_res = $this->db->select('*');
            if (isset($multipleWhere) && !empty($multipleWhere)) {
                $search_res->or_like($multipleWhere);
            }
            if (isset($where) && !empty($where)) {
                $search_res->where($where);
            }

            if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

                $search_res->where(" DATE(date_created) >= DATE('" . $_GET['start_date'] . "') ");
                $search_res->where(" DATE(date_created) <= DATE('" . $_GET['end_date'] . "') ");
            }
            
            if(isset($where_in) && !empty($where_in)){
                $search_res->where_in("type", $where_in);
            }

            $city_search_res = $search_res->order_by($sort, 'desc')->limit($limit, $offset)->get('reel as r')->result_array();
            $bulkData = array();
            $bulkData['total'] = $total;
            $rows = array();
            $tempRow = array();

            $i = 0;
            foreach ($city_search_res as $row) {
                $operate = "";
                if ($this->ion_auth->is_seller() && $row['seller_id'] == $this->session->userdata('user_id')) {
                    $operate = '<a href="javascript:void(0);" class="delete-reel action-btn btn btn-danger btn-xs ml-1 mr-1 mb-1" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
                }
                if ($this->ion_auth->is_admin()) {
                    $operate = '<a href="javascript:void(0);" class="delete-reel action-btn btn btn-danger btn-xs ml-1 mr-1 mb-1" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
                }
                if ($row['status'] == '1') {
                    $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                    $operate .= '<a class="btn btn-warning action-btn btn-xs update_active_status mr-1 mb-1 ml-1" data-table="reel" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-toggle-on"></i></a>';
                } else  if ($row['status'] == '0') {

                    $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                    $operate .= '<a class="btn btn-secondary action-btn mr-1 mb-1 ml-1 btn-xs update_active_status" data-table="reel" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-toggle-off"></i></a></div>';
                }
                $operate .= '<a href="javascript:void(0);" class="copy-to-clipboard btn btn-primary btn-xs action-btn ml-1 mr-1 mb-1" title="Copy to clipboard" ><i class="fa fa-copy"></i></a>';
                $operate .= "<a href='javascript:void(0);' class='btn btn-info btn-xs mr-1 mb-1 ml-1 action-btn copy-relative-path' data-path=" . $row['sub_directory'] . $row['name'] . " title='Copy image path for csv file'><i class='fa fa-copy'></i></a>";

                $tempRow['id'] = $row['id'];
                $tempRow['seller_id'] = $row['seller_id'];
                $tempRow['name'] = $row['name'];
                if (file_exists(FCPATH . $row['sub_directory'] . $row['name'])) {
                    $row['image'] = get_image_url($row['sub_directory'] . $row['name'], 'thumb', 'sm', trim(strtolower($row['type'])));
                } else {
                    $row['image'] = base_url() . NO_IMAGE;
                }

                $tempRow['image'] = '<div class="image-upload-div image-box-100 text-center"><span class="path d-none">' . base_url() . $row['sub_directory'] . $row['name'] . '</span><span class="relative-path d-none">' . $row['sub_directory'] . $row['name'] . '</span><a href="' . $row['image'] . '" data-toggle="lightbox" data-gallery="gallery" ><img class="rounded" src="' .  $row['image'] . '" ></a></div>';


                $favorites = $this->db->select(' COUNT(id) as `total_favorites` ')->where(array('reel_id' => $row['id']))->get(' reel_favorites ')->result_array();
                $tempRow['favorites_count'] = $favorites[0]['total_favorites'];
                $tempRow['extension'] = $row['extension'];
                $tempRow['seller_id'] = $row['seller_id'];
                $tempRow['sub_directory'] = $row['sub_directory'];
                $tempRow['size'] = ($row['size'] > 1) ? formatBytes($row['size']) : $row['size'];
                $tempRow['status'] = ($row['status'] == '1') ? '<a class="badge badge-success text-white" >Active</a>' : '<a class="badge badge-danger text-white" >Inactive</a>';
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
                $i++;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function get_reel($limit = "", $offset = '', $sort = 'id', $order = 'DESC', $search = NULL, $type = "", $seller_id = NULL)
    {

        $multipleWhere = '';

        if (isset($search) and $search != '') {
            $multipleWhere = ['id' => $search, 'name' => $search];
        }

        // if (isset($type) and $type != '') {
        //     $where['type'] = trim(strtolower($type));
        // }
        if (isset($type) and $type != '') {
            // $where['type'] = trim(strtolower($_GET['type']));
            $reel_type = explode(",", $type);
            $where_in = $reel_type;
        }
        $where['extension'] = 'mp4';
        $where['status'] = 1;
        if (isset($seller_id) and $seller_id != '') {
            $where['seller_id'] = $seller_id;
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        if(isset($where_in) && !empty($where_in)){
            $count_res->where_in("type", $where_in);
        }
        $attr_count = $count_res->get('reel')->result_array();

        foreach ($attr_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select('*');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        if(isset($where_in) && !empty($where_in)){
            $search_res->where_in("type", $where_in);
        }

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('reel')->result_array();
        $bulkData = array();
        $bulkData['error'] = (empty($city_search_res)) ? true : false;
        $bulkData['message'] = (empty($city_search_res)) ? 'Reel(s) does not exist' : 'Reel retrieved successfully';
        $bulkData['total'] = (empty($city_search_res)) ? 0 : $total;
        $rows = $tempRow = array();
        $i = 0;
        foreach ($city_search_res as $row) {
            $tempRow['id'] = $row['id'];
            $tempRow['seller_id'] = $row['seller_id'];
            $favorites = $this->db->select(' COUNT(id) as `total_favorites` ')->where(array('reel_id' => $row['id']))->get(' reel_favorites ')->result_array();
            $tempRow['favorites_count'] = $favorites[0]['total_favorites'];
            if (isset($this->ion_auth->user()->row()->id) && $this->ion_auth->user()->row()->id != null) {
                $fav = $this->db->where(['reel_id' => $row['id'], 'user_id' => $this->ion_auth->user()->row()->id])->get('reel_favorites')->num_rows();
                $tempRow['is_favorite'] = $fav;
            } else {
                $tempRow['is_favorite'] = '0';
            }
            $tempRow['name'] = $row['name'];
            if (file_exists(FCPATH . $row['sub_directory'] . $row['name'])) {
                $row['image'] = get_image_url($row['sub_directory'] . $row['name'], 'thumb', 'sm', trim(strtolower($row['type'])));
            } else {
                $row['image'] = base_url() . NO_IMAGE;
            }
            $tempRow['image'] =  base_url() . $row['sub_directory'] . $row['name'];
            $tempRow['extension'] = $row['extension'];
            $tempRow['title'] = $row['title'];
            $tempRow['name'] = $row['name'];
            $tempRow['sub_directory'] = $row['sub_directory'];
            $tempRow['seller_id'] = $row['seller_id'];
            $tempRow['sub_directory'] = $row['sub_directory'];
            $tempRow['relative_path'] = $row['sub_directory'] . $row['name'];
            $tempRow['size'] = ($row['size'] > 1) ? formatBytes($row['size']) : $row['size'];
            $rows[] = $tempRow;
            $i++;
        }
        $bulkData['data'] = $rows;
        return  $bulkData;
    }
}
