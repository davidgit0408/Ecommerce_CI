<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Custom_notification_model extends CI_Model
{

    public function add_custom_notification($data)
    {
        $data = escape_array($data);
        $custom_notification_data = [
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type']
        ];
        if (isset($data['edit_custom_notification'])) {
            $this->db->set($custom_notification_data)->where('id', $data['edit_custom_notification'])->update('custom_notifications');
        } else {
            $this->db->insert('custom_notifications', $custom_notification_data);
        }
    }

    public function get_custom_notifications_data($offset = 0, $limit = 10, $sort = 'id', $order = 'ASC')
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
        $city_count = $count_res->get('custom_notifications')->result_array();

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

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('custom_notifications')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);
            $operate = ' <a class="delete_custom_notification btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)"  data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            $operate .= '<a href="javascript:void(0)" class="edit_btn action-btn btn btn-primary btn-xs mr-1 mb-1 ml-1" data-id="' . $row['id'] . '" data-url="admin/custom_notification" title="View Order" ><i class="fa fa-pen"></i></a>';

            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['message'] = $row['message'];
            $tempRow['type'] = ucwords(str_replace('_', " ", $row['type']));
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
