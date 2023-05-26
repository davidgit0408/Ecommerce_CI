
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_inventory_model extends CI_Model
{
    public function get_sales_inventory_list(
        $offset = 0,
        $limit = 10,
        $sort = " oi.id ",
        $order = 'DESC'
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
                'oi.id' => $search,
                'p.name' => $search,
            ];
        }
        $count_res = $this->db->select('oi.id')
            ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
            ->join('products p', 'p.id=pv.product_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $count_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($_GET['seller_id']) && $_GET['seller_id'] != null) {
            $count_res->where("oi.seller_id", $_GET['seller_id']);
        }

        $sales_count = $count_res->group_by('oi.product_variant_id')->get('order_items oi')->result_array();
        $total = count($sales_count);

        $search_res = $this->db->select('oi.id,oi.product_variant_id, p.name, SUM(oi.quantity) AS qty,(p.availability OR pv.availability ) AS availability,(CASE WHEN (p.stock OR pv.stock) THEN p.stock ELSE pv.stock END) AS stock')
            ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
            ->join('products p', 'p.id=pv.product_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($_GET['seller_id']) && !empty($_GET['seller_id'])) {
            $search_res->where("oi.seller_id", $_GET['seller_id']);
        }
        $user_details = $search_res->group_by('oi.product_variant_id')->order_by($sort, "ASC")->limit($limit, $offset)->get('order_items oi')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($user_details as $row) {
            if (isset($row['stock']) && $row['stock'] != '') {
                $stock = "<span class='badge badge-success'>" . $row['stock'] . "</span>";
            }else if (($row['availability'] <= 0) && $row['stock'] <= 0) {
                $stock = "<span class='badge badge-warning'>available</span>";
            }else {
                $stock = "<span class='badge badge-danger'>N/A</span>";
            }
            $tempRow['id'] = (isset($row['id']) && $row['id'] != '') ?  $row['id'] : "-";
            $tempRow['name'] = (isset($row['name']) && $row['name'] != '') ?  $row['name'] : "-";
            $tempRow['stock'] = $stock;
            $tempRow['qty'] = (isset($row['qty']) && $row['qty'] != '') ?  $row['qty'] : "-";
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_seller_sales_inventory_list(
        $offset = 0,
        $limit = 10,
        $sort = " oi.id ",
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
                'oi.id' => $search,
                'p.name' => $search,
            ];
        }

        $count_res = $this->db->select('oi.id')
            ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
            ->join('products p', 'p.id=pv.product_id', 'left')
            ->where("oi.seller_id=" . $_SESSION['user_id']);
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $count_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        $sales_count = $count_res->group_by('oi.product_variant_id')->get('order_items oi')->result_array();
        $total = count($sales_count);
        $search_res = $this->db->select('oi.id,oi.product_variant_id, p.name, SUM(oi.quantity) AS qty,(p.availability OR pv.availability ) AS availability,(CASE WHEN (p.stock OR pv.stock) <= 0 THEN p.stock ELSE pv.stock END) AS stock')
            ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
            ->join('products p', 'p.id=pv.product_id', 'left')
            ->where("oi.seller_id=" . $_SESSION['user_id']);
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        $user_details = $search_res->group_by('oi.product_variant_id')->order_by($sort, "ASC")->limit($limit, $offset)->get('order_items oi')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($user_details as $row) {
            if (isset($row['stock']) && $row['stock'] != '') {
                $stock = "<span class='badge badge-success'>" . $row['stock'] . "</span>";
            } else if (($row['availability'] <= 0) && $row['stock'] <= 0) {
                $stock = "<span class='badge badge-warning'>available</span>";
            } else {
                $stock = "<span class='badge badge-danger'>N/A</span>";
            }
            $tempRow['id'] = (isset($row['id']) && $row['id'] != '') ?  $row['id'] : "-";
            $tempRow['name'] = (isset($row['name']) && $row['name'] != '') ?  $row['name'] : "-";
            $tempRow['stock'] = $stock;
            $tempRow['qty'] = (isset($row['qty']) && $row['qty'] != '') ?  $row['qty'] : "-";
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
