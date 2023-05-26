<?php


defined('BASEPATH') or exit('No direct script access allowed');


class Product_faqs_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function edit_product_faqs($data, $id)
    {
        $data = escape_array($data);
        $this->db->set($data)->where('id', $id)->update('product_faqs');
    }

    function add_product_faqs($data)
    {
        $answered_by = fetch_details('users', 'id=' . $_SESSION['user_id'], 'username');
        $data = escape_array($data);
        $faq_data = [
            'product_id' => $data['product_id'],
            'user_id' => isset($data['user_id']) && !empty($data['user_id']) ? $data['user_id'] : $_SESSION['user_id'],
            'seller_id' => isset($data['seller_id']) && !empty($data['seller_id']) ? $data['seller_id'] : 0,
            'question' => $data['question'],
            'answer' => isset($data['answer']) && !empty($data['answer']) ? $data['answer'] : "",
            'answered_by' => isset($data['answer']) && !empty($data['answer']) ? $_SESSION['user_id'] : 0,
        ];
        $this->db->insert('product_faqs', $faq_data);
        return $this->db->insert_id();
    }


    public function delete_faq($faq_id)
    {
        $faq_id = escape_array($faq_id);
        $this->db->delete('product_faqs', ['id' => $faq_id]);
    }
    public function get_faqs()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';

        $multipleWhere = '';

        if (isset($offset))
            $offset = $_GET['offset'];
        if (isset($limit))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($sort == 'id') {
                $sort = "id";
            } else {
                $sort = $sort;
            }

        if (isset($order) and $order != '') {
            $search = $order;
        }
        if (isset($_GET['product_id']) && $_GET['product_id'] != null) {
            $where['product_id'] = $_GET['product_id'];
        }
        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $where['seller_id'] = $_GET['user_id'];
        }
        $count_res = $this->db->select(' COUNT(pf.id) as total  ')->join('users u', 'u.id=pf.user_id');
        if (isset($_GET['search']) && trim($_GET['search'])) {
            $search = trim($_GET['search']);
            $multipleWhere = ['pf.id' => $search, 'pf.product_id' => $search, 'pf.user_id' => $search, 'pf.question' => $search, 'pf.answer' => $search];
        }
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_start();
            $count_res->or_like($multipleWhere);
            $this->db->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $rating_count = $count_res->get('product_faqs pf')->result_array();
        foreach ($rating_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select('pf.*,u.username as user_name')->join('users u', 'u.id=pf.user_id');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_start();
            $search_res->or_like($multipleWhere);
            $this->db->group_end();
        }

        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $rating_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('product_faqs pf')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        $i = 0;
        foreach ($rating_search_res as $row) {

            $row = output_escaping($row);
            $date = new DateTime($row['date_added']);

            $answered_by = fetch_details('users', 'id=' . $row['answered_by'], 'username');
            if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                $operate = ' <a href="javascript:void(0)" class="edit_btn action-btn btn btn-success btn-xs mr-1 mb-1 ml-1" title="View" data-id="' . $row['id'] . '" data-url="admin/product_faqs/"><i class="fa fa-edit"></i></a>';
                $operate .= '<a class="btn btn-danger btn-xs mr-1 mb-1 ml-1 action-btn delete-product-faq" href="javascript:void(0)" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            } else {
                $operate = ' <a href="javascript:void(0)" class="edit_btn action-btn btn btn-success btn-xs mr-1 mb-1 ml-1" title="View" data-id="' . $row['id'] . '" data-url="seller/product_faqs/"><i class="fa fa-edit"></i></a>';
                $operate .= '<a class="btn btn-danger btn-xs mr-1 mb-1 ml-1 action-btn delete-seller-product-faq" href="javascript:void(0)" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            }

            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['product_id'] = $row['product_id'];
            $tempRow['votes'] = $row['votes'];
            $tempRow['question'] = $row['question'];
            $tempRow['answer'] = $row['answer'];
            $tempRow['answered_by'] = $row['answered_by'];
            $tempRow['answered_by_name'] = (isset($answered_by[0]['username']) && !empty($answered_by)) ? $answered_by[0]['username'] : '';
            $tempRow['username'] = $row['user_name'];
            $tempRow['date_added'] = $date->format('d-M-Y');
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $i++;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
