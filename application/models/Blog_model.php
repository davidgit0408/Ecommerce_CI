<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Blog_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    public function get_categories($id = NULL, $limit = '', $offset = '', $sort = 'row_order', $order = 'ASC', $has_child_or_item = 'true', $slug = '', $ignore_status = '', $seller_id = '')
    {
        $level = 0;
        if ($ignore_status == 1) {
            $where = (isset($id) && !empty($id)) ? ['c1.id' => $id] : ['c1.parent_id' => 0];
        } else {
            $where = (isset($id) && !empty($id)) ? ['c1.id' => $id, 'c1.status' => 1] : ['c1.parent_id' => 0, 'c1.status' => 1];
        }

        $this->db->select('c1.*');
        $this->db->where($where);
        if (!empty($slug)) {
            $this->db->where('c1.slug', $slug);
        }

        if (!empty($limit) || !empty($offset)) {
            $this->db->offset($offset);
            $this->db->limit($limit);
        }

        $this->db->order_by($sort, $order);

        $parent = $this->db->get('categories c1');
        $categories = $parent->result();
        $count_res = $this->db->count_all_results('categories c1');
        $i = 0;
        foreach ($categories as $p_cat) {
            $categories[$i]->text = output_escaping($p_cat->name);
            $categories[$i]->name = output_escaping($categories[$i]->name);
            $categories[$i]->state = ['opened' => true];
            $categories[$i]->icon = "jstree-folder";
            $categories[$i]->level = $level;
            $categories[$i]->image = get_image_url($categories[$i]->image, 'thumb', 'sm');
            $categories[$i]->banner = get_image_url($categories[$i]->banner, 'thumb', 'md');
            $i++;
        }
        if (isset($categories[0])) {
            $categories[0]->total = $count_res;
        }
        return  json_decode(json_encode($categories), 1);
    }

    public function add_category($data)
    {
        $data = escape_array($data);

        $cat_data = [
            'name' => $data['category_input_name'],
            'slug' => create_unique_slug($data['category_input_name'], 'blog_categories'),
            'status' => '1',
        ];

        if (isset($data['edit_category'])) {
            unset($cat_data['status']);
            if (isset($data['category_input_image'])) {
                $cat_data['image'] = $data['category_input_image'];
            }

            $cat_data['banner'] = (isset($data['banner'])) ? $data['banner'] : '';

            $this->db->set($cat_data)->where('id', $data['edit_category'])->update('blog_categories');
        } else {
            if (isset($data['category_input_image'])) {
                $cat_data['image'] = $data['category_input_image'];
            }
            if (isset($data['banner'])) {
                $cat_data['banner'] = (isset($data['banner']) && !empty($data['banner'])) ? $data['banner'] : '';
            }
            $this->db->insert('blog_categories', $cat_data);
        }
    }



    public function get_category_list($seller_id = NULL)
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';
        if (isset($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
        }

        if (isset($_GET['id']))
            $where['parent_id'] = $_GET['id'];
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
        if (isset($seller_id) && $seller_id != "") {
            $this->db->select('category_ids');
            $where1 = 'user_id = ' . $seller_id;
            $this->db->where($where1);
            $result = $this->db->get('seller_data')->result_array();
            $cat_ids = explode(',', $result[0]['category_ids']);
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }

        if (isset($seller_id) && $seller_id != "") {
            $count_res->where_in('id', $cat_ids);
        }

        $cat_count = $count_res->get('blog_categories')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        if (isset($seller_id) && $seller_id != "") {
            $count_res->where_in('id', $cat_ids);
        }

        $cat_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('blog_categories')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($cat_search_res as $row) {

            if (!$this->ion_auth->is_seller()) {
                $operate = '<a href="' . base_url('admin/blogs/create_category' . '?edit_id=' . $row['id']) . '" class=" btn action-btn btn-success btn-xs ml-1 mr-1 mb-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/blogs/create_category"><i class="fa fa-pen"></i></a>';
                $operate .= '<a class="delete-blog-category action-btn btn btn-danger btn-xs ml-1 mr-1 mb-1" title="Delete" href="javascript:void(0)" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            }

            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-warning btn-xs action-btn update_active_status ml-1 mr-1 mb-1" data-table="blog_categories" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye-slash"></i></a>';
                }
            } else {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-primary mr-1 mb-1 ml-1 action-btn btn-xs update_active_status" data-table="blog_categories" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye"></i></a>';
                }
            }

            $tempRow['id'] = $row['id'];
            $tempRow['name'] = '<a href="' . base_url() . 'admin/category?id=' . $row['id'] . '">' . output_escaping($row['name']) . '</a>';

            if (empty($row['image']) || file_exists(FCPATH  . $row['image']) == FALSE) {
                $row['image'] = base_url() . NO_IMAGE;
                $row['image_main'] = base_url() . NO_IMAGE;
            } else {
                $row['image_main'] = base_url($row['image']);
                $row['image'] = get_image_url($row['image'], 'thumb', 'sm');
            }
            $tempRow['image'] = "<div class='image-box-100' ><a href='" . $row['image_main'] . "' data-toggle='lightbox' data-gallery='gallery'> <img class='rounded' src='" . $row['image'] . "' ></a></div>";

            if (empty($row['banner']) || file_exists(FCPATH  . $row['banner']) == FALSE) {
                $row['banner'] = base_url() . NO_IMAGE;
                $row['banner_main'] = base_url() . NO_IMAGE;
            } else {
                $row['banner_main'] = base_url($row['banner']);
                $row['banner'] = get_image_url($row['banner'], 'thumb', 'sm');
            }
            $tempRow['banner'] = "<div class='image-box-100' ><a href='" . $row['banner_main'] . "' data-toggle='lightbox' data-gallery='gallery'> <img src='" . $row['banner'] . "' class='rounded'></a></div>";

            if (!$this->ion_auth->is_seller()) {
                $tempRow['operate'] = $operate;
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }



    public function add_blog($data)
    {
        $data = escape_array($data);
        $blog_data = [
            'title' => $data['blog_title'],
            'category_id' => $data['blog_category'],
            'image' => $data['blog_image'],
            'description' => $data['blog_description'],
            'slug' => create_unique_slug($data['blog_title'], 'blogs'),
            'status' => '1',
        ];


        if (isset($data['edit_blog'])) {
            unset($blog_data['status']);
            if (isset($data['category_input_image'])) {
                $blog_data['image'] = $data['category_input_image'];
            }


            $this->db->set($blog_data)->where('id', $data['edit_blog'])->update('blogs');
        } else {
            if (isset($data['category_input_image'])) {
                $blog_data['image'] = $data['category_input_image'];
            }
            $this->db->insert('blogs', $blog_data);
        }
    }

    function get_blog_category($search_term = "")
    {
        // Fetch users
        $this->db->select('name,id');
        $this->db->where("name like '%" . $search_term . "%'");
        $this->db->where("status", 1);

        $fetched_records = $this->db->get('blog_categories');
        $categories = $fetched_records->result_array();
        // print_R($this->db->last_query());
        // return;
        // Initialize Array with fetched data
        $data = array();
        foreach ($categories as $categories) {
            $data[] = array("id" => $categories['id'], "text" => $categories['name']);
        }
        return $data;
    }


    public function get_blogs_list($seller_id = NULL)
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';
        $category_id = $_GET['category_id'];

        isset($category_id) && !empty($category_id) ? $where['category_id'] = $category_id : '';

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
            $multipleWhere = ['`id`' => $search, '`description`' => $search, '`category_id`' => $search, '`title`' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }

        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $cat_count = $count_res->get('blogs')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset, $category_id)->get('blogs')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($cat_search_res as $row) {
            $category_id = $row['category_id'];
            $category_name =  fetch_details('blog_categories', "", 'name,id', "", "", "", "", "id", $category_id);
            // print_r($category_name[0]['name']);
            if (!$this->ion_auth->is_seller()) {
                $operate = '<a href="' . base_url('admin/blogs/create_blog' . '?edit_id=' . $row['id']) . '" class=" btn btn-success btn-xs action-btn mr-1 mb-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/blogs/create_blog"><i class="fa fa-pen"></i></a>';
                $operate .= '<a class="delete-blog btn action-btn btn-danger btn-xs mr-1 mb-1" title="Delete" href="javascript:void(0)" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            }

            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn action-btn btn-warning btn-xs update_active_status mr-1 mb-1" data-table="blogs" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye-slash"></i></a>';
                }
            } else {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn action-btn btn-primary mr-1 mb-1 btn-xs update_active_status" data-table="blogs" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye"></i></a>';
                }
            }

            $tempRow['id'] = $row['id'];
            foreach ($category_name as $categories) {

                $tempRow['blog_category'] = $categories['name'];
            }
            $tempRow['title'] = $row['title'];
            $tempRow['description'] = description_word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $row['description'])));

            if (empty($row['image']) || file_exists(FCPATH  . $row['image']) == FALSE) {
                $row['image'] = base_url() . NO_IMAGE;
                $row['image_main'] = base_url() . NO_IMAGE;
            } else {
                $row['image_main'] = base_url($row['image']);
                $row['image'] = get_image_url($row['image'], 'thumb', 'sm');
            }
            $tempRow['image'] = "<div class='image-box-100' ><a href='" . $row['image_main'] . "' data-toggle='lightbox' data-gallery='gallery'> <img class='rounded' src='" . $row['image'] . "' ></a></div>";


            if (!$this->ion_auth->is_seller()) {
                $tempRow['operate'] = $operate;
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }


    function get_blogs($offset, $limit, $sort, $order, $search = NULL, $category_id = NULL)
    {
        $blog_data = [];
        $multipleWhere = '';

        $where['status'] = '1';
        if (isset($category_id) && !empty($category_id)) {
            $where['category_id'] = $category_id;
        }
        if (isset($search) and $search != '') {
            $multipleWhere = ['title' => $search, 'slug' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $count_res = $count_res->get('blogs')->result_array();
        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        $search_res = $search_res->order_by((string)$sort, (string)$order)->limit($limit, $offset)->get('blogs')->result_array();
        if (!empty($search_res)) {
            for ($i = 0; $i < count($search_res); $i++) {
                $search_res[$i] = output_escaping($search_res[$i]);
            }
        }
        $blog_data['total'] = $count_res[0]['total'];
        $blog_data['data'] = $search_res;
        return  $blog_data;
    }
}
