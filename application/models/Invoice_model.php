<?php


defined('BASEPATH') or exit('No direct script access allowed');


class Invoice_model extends CI_Model
{
    public function get_sales_list(
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'ASC'
    ) {
        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $filters = [
                'u.username' => $search,
                'u.email' => $search,
                'u.mobile' => $search,
                'o.final_total' => $search,
                'o.date_added' => $search,
                'o.id' => $search,
            ];
        }

        $count_res = $this->db->select(' COUNT(o.id) as `total` ')->join(' `users` u', 'u.id= o.user_id');
        if (!empty($_GET['seller_id']) || !empty($_POST['seller_id'])) {
            $seller_id = (!empty($_GET['seller_id']) && isset($_GET['seller_id'])) ? $_GET['seller_id'] : $_POST['seller_id'];
            $count_res->join(' `order_items` oi', 'oi.order_id=o.id');
            $count_res->where("oi.seller_id=" . $seller_id);
        }
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }
        $sales_count = $count_res->get('`orders` o')->result_array();

        foreach ($sales_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select(' o.*,oitems.* , u.username ,u.email,u.mobile,sd.store_name,us.username as seller_name ')
            ->join('users u', 'u.id= o.user_id', 'left')
            ->join('order_items oitems', 'o.id=oitems.order_id', 'left');
        if (!empty($_GET['seller_id']) || !empty($_POST['seller_id'])) {
            $seller_id = (!empty($_GET['seller_id']) && isset($_GET['seller_id'])) ? $_GET['seller_id'] : $_POST['seller_id'];
            $search_res->where("oi.seller_id=" . $seller_id);
        }
        $search_res->join('order_items oi', 'oi.order_id=o.id', 'left');
        $search_res->join('seller_data sd', 'sd.user_id=oi.seller_id');
        $search_res->join('users us ', ' us.id = oi.seller_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }
        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }
        $search_res->group_by('o.id');
        $user_details = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $total_amount = 0;
        $final_total_amount = 0;
        $total_delivery_charge = 0;
        foreach ($user_details as $row) {
            if (!$this->ion_auth->is_seller()) {
                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                $operate .= '<a href="javascript:void(0)" class="delete-orders btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                $operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
            }
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['username'];
            if (!$this->ion_auth->is_seller()) {
                $tempRow['address'] = $row['address'];
            }
            if (!$this->ion_auth->is_seller()) {
                $tempRow['mobile'] = (ALLOW_MODIFICATION == 0 && !defined(ALLOW_MODIFICATION)) ? str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3) : $row['mobile'];
            }
            $tempRow['date_added'] = $row['date_added'];
            $tempRow['final_total'] = '<span class="badge badge-danger">' . $row['final_total'] . '</span>';
            $total_amount += intval($row['total']);
            $final_total_amount += intval($row['final_total']);
            $total_delivery_charge += intval($row['delivery_charge']);
            if ($this->ion_auth->is_seller()) {
                $tempRow['total'] = '<span class="badge badge-danger">' . $row['total'] . '</span>';
                $tempRow['payment_method'] = $row['payment_method'];
                $tempRow['tax_amount'] = $row['tax_amount'];
                $tempRow['discounted_price'] = (isset($row['discounted_price']) && $row['discounted_price'] != '') ? $row['discounted_price'] : 0;
                $tempRow['store_name'] =  $row['store_name'];
                $tempRow['delivery_charge'] =  $row['delivery_charge'];
                $tempRow['seller_name'] =  $row['seller_name'];
            }
            if (!$this->ion_auth->is_seller()) {
                $tempRow['operate'] = $operate;
            }
            $rows[] = $tempRow;
        }
        if (!empty($user_details)) {
            $tempRow['id'] = '-';
            $tempRow['name'] = '-';
            $tempRow['total'] =  '<span class="badge badge-success">' . $total_amount . '</span>';
            $tempRow['grand_total'] =  '<span class="badge badge-success">' . $total_amount . '</span>';
            $tempRow['tax_amount'] = '-';
            $tempRow['discounted_price'] = '-';
            $tempRow['delivery_charge'] = '<span class="badge badge-success">' .  $total_delivery_charge . '</span>';
            $tempRow['total_delivery_charge'] = '<span class="badge badge-success">' .  $total_delivery_charge . '</span>';
            $tempRow['grand_final_total'] =  '<span class="badge badge-success">' . $final_total_amount . '</span>';
            $tempRow['final_total'] =  '<span class="badge badge-success">' . $final_total_amount . '</span>';
            $tempRow['payment_method'] = '-';
            $tempRow['store_name'] = '-';
            $tempRow['seller_name'] = '-';
            $tempRow['date_added'] = '-';
            array_push($rows, $tempRow);
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_seller_sales_list(
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'ASC',
        $start_date = null,
        $end_date = null
    ) {
        if (isset($_POST['offset'])) {
            $offset = $_POST['offset'];
        }
        if (isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        }

        if (isset($_POST['search']) and $_POST['search'] != '') {
            $search = $_POST['search'];
            $filters = [
                'u.username' => $search,
                'u.email' => $search,
                'u.mobile' => $search,
                'o.final_total' => $search,
                'o.date_added' => $search,
                'o.id' => $search,
            ];
        }
        $date = date('Y-m-d');
        $count_res = $this->db->select(' COUNT(o.id) as `total` ')->join(' `users` u', 'u.id= o.user_id');
        if (!empty($_GET['seller_id']) || !empty($_POST['seller_id'])) {
            $seller_id = (!empty($_GET['seller_id']) && isset($_GET['seller_id'])) ? $_GET['seller_id'] : $_POST['seller_id'];
            $count_res->join(' `order_items` oi', 'oi.order_id=o.id');
            $count_res->where("oi.seller_id=" . $seller_id);
        }
        if (!empty($_GET['start_date']) && !empty($_GET['end_date']) || !empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            $start_date = (!empty($_GET['start_date']) && isset($_GET['start_date'])) ? $_GET['start_date'] : $_POST['start_date'];
            $end_date = (!empty($_GET['end_date']) && isset($_GET['end_date'])) ? $_GET['end_date'] : $_POST['end_date'];
            $count_res->where(" DATE(oi.date_added) >= DATE('" . $start_date . "') ");
            $count_res->where(" DATE(oi.date_added) <= DATE('" . $end_date . "') ");
        } else {
            $count_res->where(" DATE(oi.date_added) >= DATE('" . $date . "') ");
        }
        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }
        $sales_count = $count_res->get('`orders` o')->result_array();

        foreach ($sales_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select(' o.*,oitems.* , u.username ,u.email,u.mobile,sd.store_name,us.username as seller_name ')
            ->join('users u', 'u.id= o.user_id', 'left')
            ->join('order_items oitems', 'o.id=oitems.order_id', 'left');
        if (!empty($_GET['seller_id']) || !empty($_POST['seller_id'])) {
            $seller_id = (!empty($_GET['seller_id']) && isset($_GET['seller_id'])) ? $_GET['seller_id'] : $_POST['seller_id'];
            $search_res->join('order_items oi', 'oi.order_id=o.id', 'left');
            $search_res->join('seller_data sd', 'oi.seller_id=' . $seller_id, 'left');
            $search_res->join('users us ', ' us.id = oi.seller_id', 'left');
            $search_res->where("oi.seller_id=" . $seller_id);
        }
        if (!empty($_GET['start_date']) && !empty($_GET['end_date']) || !empty($_POST['start_date']) && !empty($_POST['end_date'])) {
            $start_date = (!empty($_GET['start_date']) && isset($_GET['start_date'])) ? $_GET['start_date'] : $_POST['start_date'];
            $end_date = (!empty($_GET['end_date']) && isset($_GET['end_date'])) ? $_GET['end_date'] : $_POST['end_date'];
            $search_res->where(" DATE(oi.date_added) >= DATE('" . $start_date . "') ");
            $search_res->where(" DATE(oi.date_added) <= DATE('" . $end_date . "') ");
        } else {
            $search_res->where(" DATE(oi.date_added) >= DATE('" . $date . "') ");
        }
        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }
        $search_res->group_by('o.id');
        $user_details = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();
        $bulkData = array();
        $rows = array();
        $tempRow = array();
        $total_amount = 0;
        $final_total_amount = 0;
        $total_delivery_charge = 0;
        foreach ($user_details as $row) {
            $total_amount += intval($row['total']);
            $final_total_amount += intval($row['final_total']);
            $total_delivery_charge += intval($row['delivery_charge']);
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['username'];
            $tempRow['total'] =  $row['total'];
            $tempRow['tax_amount'] = $row['tax_amount'];
            $tempRow['discounted_price'] = (isset($row['discounted_price']) && $row['discounted_price'] != '') ? $row['discounted_price'] : "0";
            $tempRow['delivery_charge'] =  $row['delivery_charge'];
            $tempRow['final_total'] = $row['final_total'];
            $tempRow['payment_method'] = $row['payment_method'];
            $tempRow['store_name'] =  $row['store_name'];
            $tempRow['seller_name'] =  $row['seller_name'];
            $tempRow['date_added'] = $row['date_added'];
            $rows[] = $tempRow;
        }
        $bulkData['error'] = false;
        $bulkData['message'] = "Data Retrived Successfully";
        $bulkData['total'] = $total;
        $bulkData['grand_total'] =  "$total_amount";
        $bulkData['total_delivery_charge'] = "$total_delivery_charge";
        $bulkData['grand_final_total'] =   "$final_total_amount";
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
