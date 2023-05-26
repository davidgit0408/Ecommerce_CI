<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Return_request_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function get_return_request_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
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
            $multipleWhere = ['rr.`id`' => $search, 'oi.`order_id`' => $search, 'u.`username`' => $search, 'u.`email`' => $search, 'u.`mobile`' => $search, 'p.`name`' => $search, 'oi.`price`' => $search,];
        }

        $count_res = $this->db->select(' COUNT(rr.id) as `total` ')->join('users u', 'u.id=rr.user_id')->join('products p', 'p.id=rr.product_id')->join('order_items oi', 'oi.id=rr.order_item_id');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_where($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $request_count = $count_res->get('return_requests rr')->result_array();

        foreach ($request_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' rr.id,rr.remarks, oi.order_id, u.id as user_id,u.username as username ,p.name as product_name,oi.price,oi.discounted_price,oi.id as order_item_id,oi.quantity,oi.sub_total,rr.status')->join('users u', 'u.id=rr.user_id')->join('products p', 'p.id=rr.product_id')->join('order_items oi', 'oi.id=rr.order_item_id');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $offer_search_res = $search_res->order_by($sort, "desc")->limit($limit, $offset)->get('return_requests rr')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($offer_search_res as $row) {
            $row = output_escaping($row);

            $operate = '<a href="javascript:void(0)" class="edit_request action-btn btn btn-success btn-xs ml-1 mr-1 mb-1" title="Edit" data-target="#request_rating_modal" data-toggle="modal" ><i class="fa fa-pen"></i></a>';

            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['user_name'] = $row['username'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['product_name'] = $row['product_name'];
            $tempRow['price'] = $row['price'];
            $tempRow['discounted_price'] = $row['discounted_price'];
            $tempRow['quantity'] = $row['quantity'];
            $tempRow['sub_total'] = $row['sub_total'];
            $tempRow['status_digit'] = $row['status'];
            $status = [
                '0' => '<span class="badge badge-success">Pending</span>',
                '1' => '<span class="badge badge-primary">Approved</span>',
                '2' => '<span class="badge badge-danger">Rejected</span>',
            ];

            $tempRow['status'] = $status[$row['status']];
            $tempRow['remarks'] = $row['remarks'];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }


    function update_return_request($data)
    {

        $data = escape_array($data);
        $request = array(
            'status' => $data['status'],
            'remarks' => (isset($data['update_remarks']) && !empty($data['update_remarks'])) ? $data['update_remarks'] : null,
        );
        $item_id  = $data['order_item_id'];

        $this->db->where('id', $data['return_request_id'])->update('return_requests', $request);

        if ($data['status'] == '1') {
            $this->load->model('order_model');
            process_refund($data['order_item_id'], 'returned');
            $data = fetch_details('order_items', ['id' => $data['order_item_id']], 'product_variant_id,quantity');
            update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
            $this->order_model->update_order_item($item_id, 'returned', 1);
        }
    }
}
