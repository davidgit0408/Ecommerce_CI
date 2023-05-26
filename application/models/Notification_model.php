<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Notification_model extends CI_Model
{

    public function add_notification($data)
    {
        $data = escape_array($data);
        $notification_data = array(
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'send_to' => $data['send_to'],
            'users_id' => (isset($data['users_id']) && !empty($data['users_id'])) ? $data['users_id'] : 0,
        );

        if (isset($data['type']) && $data['type'] == 'categories') {
            $notification_data['type_id'] = $data['category_id'];
        }
        if (isset($data['type']) && $data['type'] == 'products') {
            $notification_data['type_id'] = $data['product_id'];
        }
        if (isset($data['send_to']) && $data['send_to'] == 'specific_user') {
            $notification_data['users_id'] = stripslashes($data['select_user_id']);
        }

        if (isset($data['image']) && !empty($data['image'])) {
            $notification_data['image'] = $data['image'];
        }
        return $this->db->insert('notifications', $notification_data);
    }

    function get_notifications($offset, $limit, $sort, $order)
    {
        $notification_data = [];
        $count_res = $this->db->select(' COUNT(id) as `total` ')->get('notifications')->result_array();
        $search_res = $this->db->select(' * ')->order_by($sort, $order)->limit($limit, $offset)->get('notifications')->result_array();
        for ($i = 0; $i < count($search_res); $i++) {
            $search_res[$i]['title'] = output_escaping($search_res[$i]['title']);
            $search_res[$i]['message'] = output_escaping($search_res[$i]['message']);
            $search_res[$i]['send_to'] = output_escaping($search_res[$i]['send_to']);
            $search_res[$i]['users_id'] = output_escaping($search_res[$i]['users_id']);
            if (empty($search_res[$i]['image'])) {
                $search_res[$i]['image'] = '';
            } else {
                if (file_exists(FCPATH . $search_res[$i]['image']) == FALSE) {
                    $search_res[$i]['image'] = base_url() . NO_IMAGE;
                } else {
                    $search_res[$i]['image'] = base_url() . $search_res[$i]['image'];
                }
            }
        }
        $notification_data['total'] = $count_res[0]['total'];
        $notification_data['data'] = $search_res;
        return  $notification_data;
    }
    public function get_notifications_data($offset = 0, $limit = 10, $sort = 'read_by', $order = 'ASC')
    {

        $multipleWhere = '';
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'read_by') {
                $sort = "read_by";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['id' => $search, 'title' => $search, 'message' => $search];
        }

        if (isset($_GET['message_type']) && ($_GET['message_type'] != '')) {
            $where = ('read_by =' . $_GET['message_type']);
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $city_count = $count_res->get('system_notification')->result_array();

        foreach ($city_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('system_notification')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);
            $operate = ' <a class="delete_system_noti action-btn  btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)"  data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            $operate .= '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['type_id'] . '&noti_id=' . $row['id'] . ' class="btn action-btn btn-primary btn-xs ml-1 mr-1 mb-1" title="View Order" ><i class="fa fa-eye"></i></a>';

            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['message'] = $row['message'];
            $tempRow['type'] = $row['type'];
            $tempRow['type_id'] = $row['type_id'];
            $tempRow['read_by'] = ($row['read_by'] == 1) ? '<label class="badge badge-primary">Read</label>' : '<label class="badge badge-danger">Un-Read</label>';
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    public function get_notification_list($offset = 0, $limit = 10, $sort = 'id', $order = 'ASC')
    {

        $multipleWhere = '';
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
            $multipleWhere = ['id' => $search, 'title' => $search, 'message' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $city_count = $count_res->get('notifications')->result_array();

        foreach ($city_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('notifications')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);
            $operate = ' <a class="delete_notifications btn btn-danger action-btn btn-xs mr-1 ml-1 mb-1" title="Delete" href="javascript:void(0)"  data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['type'] = $row['type'];
            $tempRow['message'] = $row['message'];
            $tempRow['send_to'] = ucwords(str_replace('_', " ", $row['send_to']));
            $tempRow['users_id'] = str_replace(array('[', ']', '"'), '', $row['users_id']);

            if (empty($row['image'])) {
                $row['image'] = '';
            } else {
                if (file_exists(FCPATH . $row['image']) == FALSE) {
                    $row['image'] = base_url() . NO_IMAGE;
                } else {
                    $row['image'] = base_url() . $row['image'];
                }
            }
            $tempRow['image_src'] = $row['image'];
            $tempRow['image'] = "<div class='image-box-100'><a href='" . $row['image'] . "' data-toggle='lightbox' >
      <img class='rounded'  src='"  . $row['image'] . "'></a></div>";
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
