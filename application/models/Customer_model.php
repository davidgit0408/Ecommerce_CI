<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Customer_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    public function get_customer_list()
    {

        $offset = 0;
        $limit = 10;
        $sort = 'u.id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = ['ug.group_id' => 2];

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort'])) {
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        }
        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        }
        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                '`u.id`' => $search, '`u.username`' => $search, '`ug.by_seller_name`' => $search, '`u.email`' => $search, '`u.mobile`' => $search, '`c.name`' => $search, '`a.name`' => $search, '`u.street`' => $search
            ];
        }

        if (isset($_GET['order_status']) && ($_GET['order_status'] != '')) {
            $where['u.active'] = $_GET['order_status'];
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
        // echo $this->db->last_query();


        foreach ($cat_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' u.*,a.name as area_name,c.name as city_name,ug.by_seller_name')->join('cities c', 'u.city=c.id', 'left')->join('areas a', 'u.area=a.id', 'left');;
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $search_res->join('`users_groups` `ug`', '`u`.`id` = `ug`.`user_id`');

        $cat_search_res = $search_res->order_by($sort, "desc")->limit($limit, $offset)->get('users u')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($cat_search_res as $row) {
            $row = output_escaping($row);
            $qr_array=array(
                'app_name' => 'jumlla app',
                'shop_name' => $row['username'],
                'city' => $row['city'],
            );
            $qr_data = json_encode($qr_array);
            $qr_data = str_replace('"', "'", $qr_data);
            if (!$this->ion_auth->is_seller()) {
                $operate = '<a href="' . base_url('admin/orders?user_id=' . $row['id']) . '" class="btn btn-primary action-btn btn-xs mr-1 mb-1 ml-1" title="View Orders" ><i class="fa fa-eye"></i></a>';
                $operate .= '<a  href="' . base_url('admin/transaction/view-transaction?user_id=' . $row['id']) . '" class="btn btn-danger action-btn btn-xs mb-1 ml-1" title="View Transactions"  ><i class="fa fa-money-bill-wave"></i></a>';
                $operate .= ' <a href="javascript:void(0)" class="view_address  btn btn-warning btn-xs action-btn mr-1 mb-1 ml-1" title="View Address" data-id="' . $row['id'] . '"  data-toggle="modal" data-target="#customer-address-modal" ><i class="far fa-address-book"></i></a>';
                if ($row['active'] == '1') {
                    $operate .= '<a class="btn btn-success btn-xs action-btn update_active_status mr-1 mb-1 ml-1" data-table="users" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['active'] . '" ><i class="fa fa-toggle-on"></i></a>';
                } else {
                    $operate .= '<a class="btn btn-secondary mr-1 mb-1 ml-1 btn-xs update_active_status action-btn" data-table="users" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['active'] . '" ><i class="fa fa-toggle-off"></i></a>';
                }
            }
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['username'];
            $tempRow['mobile'] = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3) : $row['mobile'];
            $tempRow['email'] = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($row['email']) - 3) . substr($row['email'], -3) : $row['email'];
            $tempRow['balance'] = $row['balance'];
            $tempRow['city'] = $row['city_name'];
            $tempRow['qrcode'] = '<p id="qrcode" name="qrcode" class="qrcode btn btn-success btn-xs action-btn update_active_status mr-1 mb-1 ml-1" data-toggle="tooltip" data="' . $qr_data . '" data-placement="top" title="">QR</p>';
            $tempRow['by_seller_name'] = $row['by_seller_name'] == 0 ? '' : $row['by_seller_name'];
            $tempRow['area'] = $row['area_name'];
            $tempRow['street'] = $row['street'];
            $tempRow['status'] = ($row['active'] == '1') ? '<a class="badge badge-success text-white" >Active</a>' : '<a class="badge badge-danger text-white" >Inactive</a>';
            $tempRow['date'] = $row['created_at'];
            if (!$this->ion_auth->is_seller()) {
                $tempRow['actions'] = $operate;
            }

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_mission_list()
    {

        $settings = get_settings('system_settings', true);
        $low_stock_limit = isset($settings['low_stock_limit']) ? $settings['low_stock_limit'] : 5;
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
                $sort = "m.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = trim($_GET['search']);
            $multipleWhere = ['m.`id`' => $search, 'ud.`username`' => $search, 'uc.`username`' => $search];
        }

        $count_res = $this->db->select(' m.*,uc.username as customer_name,ud.username as delegate_name ')
            ->join('users uc', ' uc.id = m.customer_id ', 'left')
            ->join('users ud', ' ud.id = m.delegate_id ', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_Start();
            $count_res->or_like($multipleWhere);
            $count_res->group_End();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $product_count = $count_res->get('mission m')->result_array();
        foreach ($product_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select(' m.*,uc.username as customer_name,ud.username as delegate_name ')
            ->join('users uc', ' uc.id = m.customer_id ', 'left')
            ->join('users ud', ' ud.id = m.delegate_id ', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_Start();
            $search_res->or_like($multipleWhere);
            $search_res->group_End();
        }

        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $pro_search_res = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('mission m')->result_array();
        $currency = get_settings('currency');
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($pro_search_res as $row) {

            $row = output_escaping($row);

            $operate = '<div><a href="javascript:void(0)" id="delete-mission" data-id=' . $row['id'] . ' class="btn action-btn btn-danger mr-1 mb-1  btn-xs"><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row['id'];
            $tempRow['delegate_name'] = $row['delegate_name'];
            $tempRow['customer_name'] = $row['customer_name'];
            $tempRow['visiting_days'] = $row['visiting_day'];

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_delegate_list()
    {

        $offset = 0;
        $limit = 10;
        $sort = 'u.id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = ['ug.group_id' => 5];

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort'])) {
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        }
        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        }
        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                '`u.id`' => $search, '`u.username`' => $search, '`ug.by_seller_name`' => $search, '`u.email`' => $search, '`u.mobile`' => $search, '`c.name`' => $search, '`a.name`' => $search, '`u.street`' => $search
            ];
        }

        if (isset($_GET['order_status']) && ($_GET['order_status'] != '')) {
            $where['u.active'] = $_GET['order_status'];
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
        // echo $this->db->last_query();


        foreach ($cat_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' u.*,a.name as area_name,c.name as city_name,ug.by_seller_name')->join('cities c', 'u.city=c.id', 'left')->join('areas a', 'u.area=a.id', 'left');;
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $search_res->join('`users_groups` `ug`', '`u`.`id` = `ug`.`user_id`');

        $cat_search_res = $search_res->order_by($sort, "desc")->limit($limit, $offset)->get('users u')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($cat_search_res as $row) {
            $row = output_escaping($row);
            if (!$this->ion_auth->is_seller()) {
                $operate = '<a href="' . base_url('admin/orders?user_id=' . $row['id']) . '" class="btn btn-primary action-btn btn-xs mr-1 mb-1 ml-1" title="View Orders" ><i class="fa fa-eye"></i></a>';
                $operate .= '<a  href="' . base_url('admin/transaction/view-transaction?user_id=' . $row['id']) . '" class="btn btn-danger action-btn btn-xs mb-1 ml-1" title="View Transactions"  ><i class="fa fa-money-bill-wave"></i></a>';
                $operate .= ' <a href="javascript:void(0)" class="view_address  btn btn-warning btn-xs action-btn mr-1 mb-1 ml-1" title="View Address" data-id="' . $row['id'] . '"  data-toggle="modal" data-target="#customer-address-modal" ><i class="far fa-address-book"></i></a>';
                if ($row['active'] == '1') {
                    $operate .= '<a class="btn btn-success btn-xs action-btn update_active_status mr-1 mb-1 ml-1" data-table="users" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['active'] . '" ><i class="fa fa-toggle-on"></i></a>';
                } else {
                    $operate .= '<a class="btn btn-secondary mr-1 mb-1 ml-1 btn-xs update_active_status action-btn" data-table="users" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['active'] . '" ><i class="fa fa-toggle-off"></i></a>';
                }
            }
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['username'];
            $tempRow['mobile'] = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3) : $row['mobile'];
            $tempRow['email'] = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($row['email']) - 3) . substr($row['email'], -3) : $row['email'];
            $tempRow['city'] = $row['city_name'];
            $tempRow['area'] = $row['area_name'];
            $tempRow['street'] = $row['street'];
            $tempRow['status'] = ($row['active'] == '1') ? '<a class="badge badge-success text-white" >Active</a>' : '<a class="badge badge-danger text-white" >Inactive</a>';
            $tempRow['date'] = $row['created_at'];
            if (!$this->ion_auth->is_seller()) {
                $tempRow['actions'] = $operate;
            }

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function add_customer($data)
    {

        $data = escape_array($data);
        $customer_group_data = ['group_id' => '2'];

        if (isset($data['by_seller_id'])) {
            $customer_group_data['by_seller_id'] = $data['by_seller_id'];
        }
        if (isset($data['by_seller_name'])) {
            $customer_group_data['by_seller_name'] = $data['by_seller_name'];
        }
        $password = $this->ion_auth->hash_password($data['password']);
        $user_data = [
            'ip_address' => $this->input->ip_address(),
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'address' => $data['address'],
            'username' => $data['username'],
            'password' => $password,
            'active' => 1
        ];

        $this->db->insert('users', $user_data);
        $last_id = $this->db->insert_id();
        $customer_group_data['user_id'] = $last_id;
        $this->db->insert('users_groups', $customer_group_data);
    }

    function add_delegate($data)
    {

        $data = escape_array($data);
        $customer_group_data = ['group_id' => '5'];

        $password = $this->ion_auth->hash_password($data['password']);
        $user_data = [
            'ip_address' => $this->input->ip_address(),
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'address' => $data['address'],
            'username' => $data['username'],
            'password' => $password,
            'active' => 1
        ];

        $this->db->insert('users', $user_data);
        $last_id = $this->db->insert_id();
        $customer_group_data['user_id'] = $last_id;
        $this->db->insert('users_groups', $customer_group_data);
    }

    public function get_customer_list_by_seller()
    {

        $offset = 0;
        $limit = 10;
        $sort = 'u.id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = ['ug.group_id' => 2];
        $seller_id = $this->ion_auth->get_user_id();

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort'])) {
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        }
        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        }
        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                '`u.id`' => $search, '`u.username`' => $search, '`u.email`' => $search, '`u.mobile`' => $search, '`c.name`' => $search, '`a.name`' => $search, '`u.street`' => $search
            ];
        }

        if (isset($_GET['order_status']) && ($_GET['order_status'] != '')) {
            $where['u.active'] = $_GET['order_status'];
        }
        $where['ug.by_seller_id'] = $seller_id;

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
        // echo $this->db->last_query();


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

        $cat_search_res = $search_res->order_by($sort, "desc")->limit($limit, $offset)->get('users u')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($cat_search_res as $row) {
            $row = output_escaping($row);
            if (!$this->ion_auth->is_seller()) {
                $operate = '<a href="' . base_url('admin/orders?user_id=' . $row['id']) . '" class="btn btn-primary action-btn btn-xs mr-1 mb-1 ml-1" title="View Orders" ><i class="fa fa-eye"></i></a>';
                $operate .= '<a  href="' . base_url('admin/transaction/view-transaction?user_id=' . $row['id']) . '" class="btn btn-danger action-btn btn-xs mb-1 ml-1" title="View Transactions"  ><i class="fa fa-money-bill-wave"></i></a>';
                $operate .= ' <a href="javascript:void(0)" class="view_address  btn btn-warning btn-xs action-btn mr-1 mb-1 ml-1" title="View Address" data-id="' . $row['id'] . '"  data-toggle="modal" data-target="#customer-address-modal" ><i class="far fa-address-book"></i></a>';
                if ($row['active'] == '1') {
                    $operate .= '<a class="btn btn-success btn-xs action-btn update_active_status mr-1 mb-1 ml-1" data-table="users" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['active'] . '" ><i class="fa fa-toggle-on"></i></a>';
                } else {
                    $operate .= '<a class="btn btn-secondary mr-1 mb-1 ml-1 btn-xs update_active_status action-btn" data-table="users" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['active'] . '" ><i class="fa fa-toggle-off"></i></a>';
                }
            }
            $qr_array=array(
                'app_name' => 'jumlla app',
                'shop_name' => $row['username'],
                'city' => $row['city'],
            );
            $qr_data = json_encode($qr_array);
            $qr_data = str_replace('"', "'", $qr_data);
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['username'];
            $tempRow['mobile'] = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3) : $row['mobile'];
            $tempRow['email'] = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($row['email']) - 3) . substr($row['email'], -3) : $row['email'];
            $tempRow['balance'] = $row['balance'];
            $tempRow['city'] = $row['city_name'];
            $tempRow['qrcode'] = '<p id="qrcode" name="qrcode" class="qrcode btn btn-success btn-xs action-btn update_active_status mr-1 mb-1 ml-1" data-toggle="tooltip" data="' . $qr_data . '" data-placement="top" title="">QR</p>';
            $tempRow['area'] = $row['area_name'];
            $tempRow['street'] = $row['street'];
            $tempRow['status'] = ($row['active'] == '1') ? '<a class="badge badge-success text-white" >Active</a>' : '<a class="badge badge-danger text-white" >Inactive</a>';
            $tempRow['date'] = $row['created_at'];
            if (!$this->ion_auth->is_seller()) {
                $tempRow['actions'] = $operate;
            }

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

    public function get_customers($id, $search, $offset, $limit, $sort, $order)
    {
        $multipleWhere = '';
        $where['ug.group_id'] =  2;
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

        $search_res = $this->db->select(' u.*,a.name as area_name,c.name as city_name')->join('cities c', 'u.city=c.id', 'left')->join('areas a', 'u.area=a.id', 'left');
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
        $bulkData['message'] = (empty($cat_search_res)) ? 'Customer(s) does not exist' : 'Customers retrieved successfully';
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

    // withdrawal_request
    function update_balance_customer($amount, $user_id, $action)
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
        return $this->db->where('id', $user_id)->update('users');
    }
}
