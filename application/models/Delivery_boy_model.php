<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Delivery_boy_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function update_delivery_boy($data)
    {
        $data = escape_array($data);
        $bonus = ($data['bonus_type'] == 'fixed_amount_per_order') ? $data['bonus_amount'] : $data['bonus_percentage'];
        $delivery_boy_data = [
            'username' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'address' => $data['address'],
            'bonus_type' => $data['bonus_type'],
            'bonus' => $bonus,
            'serviceable_zipcodes' => $data['serviceable_zipcodes'],
        ];
        $this->db->set($delivery_boy_data)->where('id', $data['edit_delivery_boy'])->update('users');
        
    }

    function get_delivery_boys_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'u.id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = ['u.active' => 1];

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "u.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['u.`id`' => $search, 'u.`username`' => $search, 'u.`email`' => $search, 'u.`mobile`' => $search, 'u.`address`' => $search, 'u.`balance`' => $search];
        }

        $count_res = $this->db->select(' COUNT(u.id) as `total` ')->join('users_groups ug', ' ug.user_id = u.id ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $where['ug.group_id'] = '3';
            $count_res->where($where);
        }

        $offer_count = $count_res->get('users u')->result_array();

        foreach ($offer_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' u.* ')->join('users_groups ug', ' ug.user_id = u.id ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $where['ug.group_id'] = '3';
            $search_res->where($where);
        }

        $offer_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('users u')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($offer_search_res as $row) {
            $row = output_escaping($row);
            $operate = '<a href="javascript:void(0)" class="edit_btn btn action-btn btn-primary btn-xs mr-1 ml-1 mb-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/delivery_boys/"><i class="fa fa-pen"></i></a>';
            $operate .= '<a  href="javascript:void(0)" class="btn btn-danger action-btn btn-xs mr-1 mb-1 ml-1" title="Delete" id="delete-delivery-boys"  data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            $operate .= '<a href="javascript:void(0)" class=" fund_transfer action-btn btn btn-info btn-xs mr-1 mb-1 ml-1" title="Fund Transfer" data-target="#fund_transfer_delivery_boy"   data-toggle="modal" data-id="' . $row['id'] . '" ><i class="fa fa-arrow-alt-circle-right"></i></a>';

            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['username'];
            $tempRow['email'] = $row['email'];
            $tempRow['mobile'] = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3) : $row['mobile'];
            if (isset($row['email']) && !empty($row['email']) && $row['email'] != "") {
                $tempRow['email'] = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($row['email']) - 3) . substr($row['email'], -3) : $row['email'];
            } else {
                $tempRow['email'] = "";
            }
            $tempRow['address'] = $row['address'];
            $tempRow['bonus_type'] = ucwords(str_replace('_'," ",$row['bonus_type']));
            $tempRow['bonus'] = $row['bonus'];
            $tempRow['balance'] = $row['balance'];
            $tempRow['cash_received'] = $row['cash_received'];
            $tempRow['balance'] =  $row['balance'] == null || $row['balance'] == 0 || empty($row['balance']) ? "0" : $row['balance'];
            $tempRow['date'] = $row['created_at'];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function update_balance($amount, $delivery_boy_id, $action)
    {
        /**
         * @param
         * action = deduct / add
         */

        if ($action == "add") {
            $this->db->set('balance', 'balance+' . $amount, FALSE);
        } elseif ($action == "deduct") {
            $this->db->set('balance', 'balance-' . $amount, FALSE);
        }
        return $this->db->where('id', $delivery_boy_id)->update('users');
    }
    public function get_delivery_boys($id, $search, $offset, $limit, $sort, $order)
    {
        $multipleWhere = '';
        $where['ug.group_id'] =  3;
        if (!empty($search)) {
            $multipleWhere = [
                '`u.id`' => $search, '`u.username`' => $search, '`u.email`' => $search, '`u.mobile`' => $search, '`c.name`' => $search, '`a.name`' => $search, '`u.street`' => $search
            ];
        }
        if (!empty($id)) {
            $where['u.id'] = $id;
        }

        $count_res = $this->db->select(' COUNT(u.id) as `total` ,a.name as area_name,c.name as city_name')->join('cities c', 'u.city=c.id', 'left')->join('areas a', 'u.area=a.id', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $count_res->join('`users_groups` `ug`', '`u`.`id` = `ug`.`user_id`');

        $cat_count = $count_res->get('users u')->result_array();

        foreach ($cat_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' u.*,a.name as area_name,c.name as city_name')->join('cities c', 'u.city=c.id', 'left')->join('areas a', 'u.area=a.id', 'left');;
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $search_res->join('`users_groups` `ug`', '`u`.`id` = `ug`.`user_id`');

        $cat_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('users u')->result_array();
        $rows = array();
        $tempRow = array();
        $bulkData = array();
        $bulkData['error'] = (empty($cat_search_res)) ? true : false;
        $bulkData['message'] = (empty($cat_search_res)) ? 'Delivery(s) does not exist' : 'Delivery boys retrieved successfully';
        $bulkData['total'] = (empty($cat_search_res)) ? 0 : $total;
        if (!empty($cat_search_res)) {
            foreach ($cat_search_res as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = $row['id'];
                $tempRow['name'] = $row['username'];
                $tempRow['mobile'] = $row['mobile'];
                $tempRow['email'] = $row['email'];
                $tempRow['balance'] = $row['balance'];
                $tempRow['city'] = $row['city_name'];
                $tempRow['image'] = isset($row['image']) && $row['image'] != '' ? base_url(USER_IMG_PATH . '/' . $row['image']) : '';
                if (empty($row['image']) || file_exists(FCPATH . USER_IMG_PATH . $row['image']) == FALSE) {
                    $tempRow['image'] = base_url() . NO_IMAGE;
                } else {
                    $tempRow['image'] = base_url() . USER_IMG_PATH . $row['image'];
                }
                $tempRow['area'] = $row['area_name'];
                $tempRow['street'] = $row['street'];
                $tempRow['status'] = $row['active'];
                $tempRow['date'] = $row['created_at'];

                $rows[] = $tempRow;
            }
            $bulkData['data'] = $rows;
        } else {
            $bulkData['data'] = [];
        }
        print_r(json_encode($bulkData));
    }

    function get_cash_collection_list($user_id = '')
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = [];

        if (isset($_GET['filter_date']) && $_GET['filter_date'] != NULL)
            $where = ['DATE(transactions.transaction_date)' => $_GET['filter_date']];

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
            $multipleWhere = ['`transactions.id`' => $search, '`transactions.amount`' => $search, '`transactions.date_created`' => $search, 'users.username' => $search, 'users.mobile' => $search, 'users.email' => $search, 'type' => $search, 'transactions.status' => $search];
        }
        if (isset($_GET['filter_d_boy']) && !empty($_GET['filter_d_boy']) && $_GET['filter_d_boy'] != NULL) {
            $where = ['users.id' => $_GET['filter_d_boy']];
        }
        if (isset($_GET['filter_status']) && !empty($_GET['filter_status'])) {
            $where = ['transactions.type' => $_GET['filter_status']];
        }
        if (!empty($user_id)) {
            $user_where = ['users.id' => $user_id];
        }



        $count_res = $this->db->select(' COUNT(transactions.id) as `total` ')->join('users', ' transactions.user_id = users.id', 'left')->where('transactions.status = 1');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $count_res->where(" DATE(transactions.transaction_date) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(transactions.transaction_date) <= DATE('" . $_GET['end_date'] . "') ");
        }
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $count_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        if (isset($user_where) && !empty($user_where)) {
            $count_res->where($user_where);
        }

        $txn_count = $count_res->get('transactions')->result_array();

        foreach ($txn_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' transactions.*,users.username as name,users.mobile,users.cash_received');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(transactions.transaction_date) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(transactions.transaction_date) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $search_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        if (isset($user_where) && !empty($user_where)) {
            $search_res->where($user_where);
        }
        $search_res->join('users', ' transactions.user_id = users.id', 'left')->where('transactions.status = 1');
        $txn_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('transactions')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($txn_search_res as $row) {
            $row = output_escaping($row);
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['name'];
            $tempRow['mobile'] = $row['mobile'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['cash_received'] = $row['cash_received'];
            $tempRow['type'] = (isset($row['type']) && $row['type'] == "delivery_boy_cash") ? '<label class="badge badge-danger">Received</label>' : '<label class="badge badge-success">Collected</label>';
            $tempRow['amount'] = $row['amount'];
            $tempRow['message'] = $row['message'];
            $tempRow['txn_date'] = $row['transaction_date'];
            $tempRow['date'] = $row['date_created'];

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_delivery_boy_cash_collection($limit = "", $offset = '', $sort = 'transactions.id', $order = 'DESC', $search = NULL, $filter = "")
    {

        $multipleWhere = '';

        if (isset($search) and $search != '') {
            $multipleWhere = ['`transactions.id`' => $search, '`transactions.amount`' => $search, '`transactions.date_created`' => $search, 'users.username' => $search, 'users.mobile' => $search, 'users.email' => $search, 'type' => $search, 'transactions.status' => $search];
        }

        if (isset($filter['status']) && !empty($filter['status'])) {
            $where = ['transactions.type' => $filter['status']];
        }
        if (isset($filter['delivery_boy_id']) && !empty($filter['delivery_boy_id'])) {
            $user_where = ['users.id' => $filter['delivery_boy_id']];
        }

        $count_res = $this->db->select(' COUNT(transactions.id) as `total` ')->join('users', ' transactions.user_id = users.id', 'left')->where('transactions.status = 1');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $count_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        if (isset($user_where) && !empty($user_where)) {
            $count_res->where($user_where);
        }

        $txn_count = $count_res->get('transactions')->result_array();

        foreach ($txn_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' transactions.*,users.username as name,users.mobile,users.cash_received');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $search_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        if (isset($user_where) && !empty($user_where)) {
            $search_res->where($user_where);
        }
        $search_res->join('users', ' transactions.user_id = users.id', 'left')->where('transactions.status = 1');
        $txn_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('transactions')->result_array();

        $bulkData = array();
        $bulkData = array();
        $bulkData['error'] = (empty($txn_search_res)) ? true : false;
        $bulkData['message'] = (empty($txn_search_res)) ? 'Cash collection does not exist' : 'Cash collection are retrieve successfully';
        $bulkData['total'] = (empty($txn_search_res)) ? 0 : $total;
        $rows = array();
        $tempRow = array();

        foreach ($txn_search_res as $row) {
            $row = output_escaping($row);
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['name'];
            $tempRow['mobile'] = $row['mobile'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['cash_received'] = $row['cash_received'];
            $tempRow['type'] = (isset($row['type']) && $row['type'] == "delivery_boy_cash") ? 'Received' : 'Collected';
            $tempRow['amount'] = $row['amount'];
            $tempRow['message'] = $row['message'];
            $tempRow['transaction_date'] = $row['transaction_date'];
            $tempRow['date'] = $row['date_created'];

            $rows[] = $tempRow;
        }
        $bulkData['data'] = $rows;
        return $bulkData;
    }
}
