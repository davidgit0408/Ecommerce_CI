<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Brand_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }


    public function add_brand($data)
    {
        $data = escape_array($data);

        $brands_data = [
            'name' => $data['brand_input_name'],
            'slug' => create_unique_slug($data['brand_input_name'], 'brands'),
            'status' => '1',
        ];

        if (isset($data['edit_brand'])) {
            unset($brands_data['status']);
            if (isset($data['brand_input_image'])) {
                $brands_data['image'] = $data['brand_input_image'];
            }
            $this->db->set($brands_data)->where('id', $data['edit_brand'])->update('brands');
        } else {
            if (isset($data['brand_input_image'])) {
                $brands_data['image'] = $data['brand_input_image'];
            }
            $this->db->insert('brands', $brands_data);
        }
    }



    public function delete_brand($id)
    {
        $id = escape_array($id);
        $this->db->delete('brands', ['id' => $id]);
        $response = TRUE;
        return $response;
    }

    public function get_brand_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = ['status !=' => NULL];

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
            $multipleWhere = ['`id`' => $search, '`name`' => $search];
        }
        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        $brand_count = $count_res->get('brands')->result_array();
        foreach ($brand_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $brand_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('brands')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($brand_search_res as $row) {

            if (!$this->ion_auth->is_seller()) {
                $operate = '<a href="' . base_url('admin/brand/create_brand' . '?edit_id=' . $row['id']) . '" class=" btn action-btn btn-success btn-xs mr-1 mb-1 ml-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/brand/create_brand"><i class="fa fa-pen"></i></a>';
                $operate .= '<a class="delete-brand btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            }
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn action-btn btn-warning btn-xs update_active_status mr-1 mb-1 ml-1" data-table="brands" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye-slash"></i></a>';
                }
            } else {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn action-btn btn-primary mr-1 mb-1 btn-xs update_active_status ml-1" data-table="brands" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye"></i></a>';
                }
            }

            $tempRow['id'] = $row['id'];
            $tempRow['name'] = output_escaping($row['name']) . '</a>';

            if (empty($row['image']) || file_exists(FCPATH  . $row['image']) == FALSE) {
                $row['image'] = base_url() . NO_IMAGE;
                $row['image_main'] = base_url() . NO_IMAGE;
            } else {
                $row['image_main'] = base_url($row['image']);
                $row['image'] = get_image_url($row['image'], 'thumb', 'sm');
            }
            $tempRow['image'] = "<div class='image-box-100'><a href='" . $row['image_main'] . "' data-toggle='lightbox' data-gallery='gallery'> <img class='rounded' src='" . $row['image'] . "'></a></div>";
            if (!$this->ion_auth->is_seller()) {
                $tempRow['operate'] = $operate;
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function delete_blog()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('delete', 'blogs'), PERMISSION_ERROR_MSG, 'blogs')) {
                return false;
            }
            if ($this->blog_model->delete_brand($_GET['id']) == TRUE) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = 'Deleted Succesfully';
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
