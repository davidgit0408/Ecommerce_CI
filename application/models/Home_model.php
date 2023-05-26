<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Home_model extends CI_Model
{

    public function count_new_orders($type = '')
    {
        $res = $this->db->select('count(o.id) as counter');
        if (!empty($type) && $type != 'api') {
            if ($this->ion_auth->is_delivery_boy()) {
                $user_id = $this->session->userdata('user_id');
                $this->db->where('oi.delivery_boy_id', $user_id);
            }
        }
        if ($this->ion_auth->is_delivery_boy()) {
            $this->db->join('order_items oi', 'oi.order_id=o.id', 'left');
            $user_id = $this->session->userdata('user_id');
            $this->db->where('oi.delivery_boy_id', $user_id);
        }
        $res = $this->db->get('`orders` o')->result_array();
        
        return $res[0]['counter'];
    }

    public function count_orders_by_status($status)
    {
        $res = $this->db->select('count(id) as counter');
        $this->db->where('active_status', $status);
        $res = $this->db->get('`orders` o')->result_array();
        return $res[0]['counter'];
    }

    public function count_new_users()
    {
        $res = $this->db->select('count(u.id) as counter')->join('users_groups ug', ' ug.`user_id` = u.`id` ')
            ->where('ug.group_id=2')
            ->get('`users u`')->result_array();
        return $res[0]['counter'];
    }

    public function count_delivery_boys()
    {
        $res = $this->db->select('count(u.id) as counter')->where('ug.group_id', '3')->join('users_groups ug', 'ug.user_id=u.id')
            ->get('`users` u')->result_array();
        return $res[0]['counter'];
    }

    public function count_products($seller_id = "")
    {
        $res = $this->db->select('count(id) as counter ');
        if (!empty($seller_id) && $seller_id != '') {
            $res->where('seller_id=' . $seller_id);
        }
        $count = $res->get('`products`')->result_array();
        return $count[0]['counter'];
    }

    public function count_products_stock_low_status($seller_id = "")
    {
        $settings = get_settings('system_settings', true);
        $low_stock_limit = isset($settings['low_stock_limit']) ? $settings['low_stock_limit'] : 5;
        $count_res = $this->db->select(' COUNT( distinct(p.id)) as `total` ')->join('product_variants', 'product_variants.product_id = p.id');
        $where = "p.stock_type is  NOT NULL";

        $count_res->where($where);
        $count_res->group_Start();
        $count_res->where('p.stock  <=', $low_stock_limit);
        $count_res->where('p.availability  =', '1');
        $count_res->or_where('product_variants.stock  <=', $low_stock_limit);
        $count_res->where('product_variants.availability  =', '1');
        $count_res->group_End();
        if (!empty($seller_id) && $seller_id != '') {
            $count_res->where('p.seller_id  =', $seller_id);
        }
        $product_count = $count_res->get('products p')->result_array();
        return $product_count[0]['total'];
    }

    public function count_products_availability_status($seller_id = "")
    {
        $count_res = $this->db->select(' COUNT( distinct(p.id)) as `total` ')->join('product_variants', 'product_variants.product_id = p.id');
        $where = "p.stock_type is  NOT NULL";
        $count_res->where($where);
        $count_res->group_Start();
        $count_res->where('p.stock ', '0');
        $count_res->where('p.availability ', '0');
        $count_res->or_where('product_variants.stock ', '0');
        $count_res->where('product_variants.availability', '0');
        $count_res->group_End();
        if (!empty($seller_id) && $seller_id != '') {
            $count_res->where('p.seller_id  =', $seller_id);
        }
        $product_count = $count_res->get('products p')->result_array();

        return  $product_count[0]['total'];
    }

    public function approved_seller()
    {

        $query_approved_seller = $this->db->select('*')->where('status', '1')->get('seller_data');

        $approved_seller = $query_approved_seller->result_array();

        return $approved_seller;
    }


    public function count_approved_seller()
    {

        $query_approved_seller = $this->db->select('*')->where('status', '1')->get('seller_data');

        $count_approved_seller = $query_approved_seller->num_rows();

        return $count_approved_seller;
    }

    public function not_approved_seller()
    {

        $query_not_approved_seller = $this->db->select('*')->where('status', '2')->get('seller_data');

        $not_approved_seller = $query_not_approved_seller->result_array();

        return $not_approved_seller;
    }


    public function count_not_approved_seller()
    {

        $query_not_approved_seller = $this->db->select('*')->where('status', '2')->get('seller_data');

        $count_not_approved_seller = $query_not_approved_seller->num_rows();

        return $count_not_approved_seller;
    }

    public function deactive_seller()
    {

        $query_deactive_seller = $this->db->select('*')->where('status', '0')->get('seller_data');

        $deactive_seller = $query_deactive_seller->result_array();

        return $deactive_seller;
    }


    public function count_deactive_seller()
    {

        $query_deactive_seller = $this->db->select('*')->where('status', '')->get('seller_data');

        $count_deactive_seller = $query_deactive_seller->num_rows();

        return $count_deactive_seller;
    }

    public function total_earnings($type = "admin")
    {
        $select = "";
        if ($type == "admin") {
            $select = "SUM(admin_commission_amount) as total ";
        }
        if ($type == "seller") {
            $select = "SUM(seller_commission_amount) as total ";
        }
        if ($type == "overall") {
            $select = "SUM(sub_total) as total ";
        }
        $count_res = $this->db->select($select);
        $where = "is_credited=1";
        $count_res->where($where);

        $product_count = $count_res->get('order_items')->result_array();
        return $product_count[0]['total'];
    }
}
