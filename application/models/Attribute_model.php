<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Attribute_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }

    function add_attribute_set($data)
    {
        $data = escape_array($data);

        $attr_data = [
            'name' => $data['name']
        ];

        if (isset($data['edit_attribute_set'])) {
            $this->db->set($attr_data)->where('id', $data['edit_attribute_set'])->update('attribute_set');
        } else {
            $this->db->insert('attribute_set', $attr_data);
        }
    }

    function get_attribute_set_list(
        $offset = 0,
        $limit = 10,
        $sort = " id ",
        $order = 'ASC'
    ) {

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
            $multipleWhere = ['id' => $search, 'name' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');


        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $attr_count = $count_res->get('attribute_set')->result_array();

        foreach ($attr_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('attribute_set')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($city_search_res as $row) {
            $row = output_escaping($row);
            if (!$this->ion_auth->is_seller()) {
                $operate = ' <a href="javascript:void(0)" class="edit_btn btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/attribute_set/"><i class="fa fa-pen"></i></a>';
            }
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-warning btn-xs update_active_status action-btn mr-1 mb-1 ml-1" data-table="attribute_set" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye-slash"></i></a>';
                }
            } else {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-primary mr-1 mb-1 ml-1 btn-xs action-btn update_active_status" data-table="attribute_set" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye"></i></a>';
                }
            }
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['name'];
            if (!$this->ion_auth->is_seller()) {
                $tempRow['operate'] = $operate;
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function add_attributes($data)
    {
        $data = escape_array($data);
       
        $attr_data = [
            'name' => $data['name'],
            'attribute_set_id' => $data['attribute_set'],
            'status' => '1',
        ];
        if (isset($data['edit_attribute'])) {
            $this->db->set($attr_data)->where('id', $data['edit_attribute'])->update('attributes');
        } else {
            $this->db->insert('attributes', $attr_data);
        }

        $attribute_id = $this->db->get_where('attributes', array('name' => $data['name']))->result_array();
      
        for($i=0;$i < count($data['attribute_value']);$i++) {
         
            $attr_val = [
                'attribute_id' => $attribute_id[0]['id'],
                'value' => $data['attribute_value'][$i],
                'swatche_type' => $data['swatche_type'][$i],
                'swatche_value' => $data['swatche_value'][$i],
                'status' => '1',
            ];

            if (isset($data['edit_attribute_value'])) {
                $this->db->set($attr_val)->where('id', $data['edit_attribute_value'])->update('attribute_values');
            } else {
                $this->db->insert('attribute_values', $attr_val);
            }
        }
    }


    public function get_attribute_list(
        $offset = 0,
        $limit = 10,
        $sort = 'id',
        $order = 'ASC'
    ) {
        $multipleWhere = '';

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "attr.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['attr.id' => $search, 'attr_set.name' => $search, 'attr.name' => $search];
        }

        $count_res = $this->db->select(' COUNT(attr.id) as `total` ')->join('attribute_set attr_set', 'attr.attribute_set_id=attr_set.id', 'left');
                                                                    

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $attr_count = $count_res->get('attributes attr')->result_array();

        foreach ($attr_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' attr.*,attr_set.name as attr_set_name ')->join('attribute_set attr_set', 'attr.attribute_set_id=attr_set.id', 'left');
                                                                                 
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('attributes attr')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);
            if (!$this->ion_auth->is_seller()) {
                $operate = ' <a href="javascript:void(0)" class="edit_btn action-btn btn btn-success btn-xs mr-1 mb-1" title="View" data-id="' . $row['id'] . '" data-url="admin/attributes/"><i class="fa fa-pen"></i></a>';
            }
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-warning btn-xs action-btn update_active_status mr-1 ml-1 mb-1" data-table="attributes" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye-slash"></i></a>';
                }
            } else {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-primary mr-1 mb-1 ml-1 btn-xs action-btn update_active_status" data-table="attributes" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye"></i></a>';
                }
            }
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['name'];
            $tempRow['attribute_set'] = $row['attr_set_name'];
            if (!$this->ion_auth->is_seller()) {
                $tempRow['operate'] = $operate;
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function add_attribute_value($data)
    {
        $data = escape_array($data);
        $attr_data = [
            'attribute_id' => $data['attributes_id'],
            'value' => $data['value'],
            'swatche_type' => $data['swatche_type'],
            'swatche_value' => $data['swatche_value'],
            'status' => '1',
        ];

        if (isset($data['edit_attribute_value'])) {
            $this->db->set($attr_data)->where('id', $data['edit_attribute_value'])->update('attribute_values');
        } else {
            $this->db->insert('attribute_values', $attr_data);
        }
        
    }


    public function get_attribute_values(
        $offset = 0,
        $limit = 10,
        $sort = " id ",
        $order = 'ASC'
    ) {

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
            $multipleWhere = ['attr.id' => $search, 'attr.name' => $search, 'attr_vals.value' => $search];
        }

        $count_res = $this->db->select(' COUNT(attr_vals.id) as `total` ')
            ->join('attributes attr', 'attr.id=attr_vals.attribute_id', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $attr_count = $count_res->get('attribute_values attr_vals')->result_array();

        foreach ($attr_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' attr_vals.*,attr.name as attr_name')->join('attributes attr', 'attr.id=attr_vals.attribute_id', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('attribute_values attr_vals')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($city_search_res as $row) {
            $row = output_escaping($row);
            if (!$this->ion_auth->is_seller()) {
                $operate = ' <a href="javascript:void(0)" class="edit_btn btn btn-success action-btn btn-xs mr-1 mb-1" title="View" data-id="' . $row['id'] . '" data-url="admin/attribute_value/"><i class="fa fa-pen"></i></a>';
            }
            $tempRow['id'] = $row['id'];
            $tempRow['name'] = $row['value'];
            $tempRow['attributes'] = $row['attr_name'];
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-warning btn-xs action-btn update_active_status mr-1 ml-1 mb-1" data-table="attribute_values" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye-slash"></i></a>';
                }
            } else {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-primary mr-1 ml-1 mb-1 btn-xs action-btn update_active_status" data-table="attribute_values" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye"></i></a>';
                }
            }
            if (!$this->ion_auth->is_seller()) {
                $tempRow['operate'] = $operate;
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function get_attribute_set($sort = "ats.name", $order = "ASC", $search = "", $offset = NULL, $limit = NULL)
    {
        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                'ats.`name`' => $search
            ];
        }

        $search_res = $this->db->select('ats.*')->join('attributes a', 'ats.id=a.attribute_set_id')->join('attribute_values av', 'av.attribute_id=a.id');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        $attribute_set = $search_res->where("ats.status=1 and a.status=1")->group_by('ats.id')->order_by($sort, $order)->limit($offset, $limit)->get('`attribute_set` ats')->result_array();
        $bulkData = array();
        $bulkData['error'] = (empty($attribute_set)) ? true : false;
        if (!empty($attribute_set)) {
            for ($i = 0; $i < count($attribute_set); $i++) {
                $attribute_set[$i] = output_escaping($attribute_set[$i]);
            }
        }
        $bulkData['data'] = (empty($attribute_set)) ? [] : $attribute_set;
        $bulkData['message'] = (empty($attribute_set)) ? [] : "Attribute Set Retrived Successfully";
        return $bulkData;
    }

    function get_attributes($sort = "a.name", $order = "ASC", $search = "", $attribute_set_id = "", $offset = NULL, $limit = NULL)
    {
        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                '`a.name`' => $search,
                '`as.name`' => $search
            ];
        }

        $search_res = $this->db->select('a.*,as.name as attribute_set_name')->join('attribute_set as', 'as.id=a.attribute_set_id')->join('attribute_values av', 'av.attribute_id=a.id');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        if (isset($attribute_set_id) && !empty($attribute_set_id)) {
            $search_res->where('a.attribute_set_id = ' . $attribute_set_id);
        }
        $attribute_set = $search_res->where("a.status=1 and as.status=1")->group_by('a.id')->order_by($sort, $order)->limit($offset, $limit)->get('attributes a')->result_array();
        $bulkData = array();
        $bulkData['error'] = (empty($attribute_set)) ? true : false;
        $bulkData['message'] = (empty($attribute_set)) ? "Attributes Not Found" : "Attributes Retrivede Successfully";
        if (!empty($attribute_set)) {
            for ($i = 0; $i < count($attribute_set); $i++) {
                $attribute_set[$i] = output_escaping($attribute_set[$i]);
            }
        }
        $bulkData['data'] = (empty($attribute_set)) ? [] : $attribute_set;
        return $bulkData;
    }

    function get_attribute_value($sort = "av.id", $order = "ASC", $search = "", $attribute_id = "", $offset = NULL, $limit = NULL)
    {
        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                '`a.name`' => $search,
                '`av.value`' => $search,
                '`av.swatche_value`' => $search
            ];
        }
        $search_res = $this->db->select('av.*,a.name as attribute_name')->join('attributes a', 'a.id=av.attribute_id');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        if (isset($attribute_id) && !empty($attribute_id)) {
            $search_res->where('av.attribute_id = ' . $attribute_id);
        }
        $attribute_set = $search_res->where("av.status=1 and a.status=1")->group_by('av.id')->order_by($sort, $order)->limit($offset, $limit)->get('attribute_values av')->result_array();
        $bulkData = array();
        $bulkData['error'] = (empty($attribute_set)) ? true : false;
        $bulkData['message'] = (empty($attribute_set)) ? "Atributes Not Found" : "Attributes Retrived Successfully";
        if (!empty($attribute_set)) {
            for ($i = 0; $i < count($attribute_set); $i++) {
                $attribute_set[$i] = output_escaping($attribute_set[$i]);
            }
        }
        $bulkData['data'] = (empty($attribute_set)) ? [] : $attribute_set;
        return $bulkData;
    }
}
