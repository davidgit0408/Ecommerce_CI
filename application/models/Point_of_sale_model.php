<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Point_of_sale_model extends CI_Model
{
    function get_users($search_term = "")
    {
        // Fetch users
        $this->db->select('*');
        $this->db->where("username like '%" . $search_term . "%'");
        $this->db->or_where("id like '%" . $search_term . "%'");
        $this->db->or_where("mobile like '%" . $search_term . "%'");
        $this->db->or_where("email like '%" . $search_term . "%'");
        $fetched_records = $this->db->get('users');
        $users = $fetched_records->result_array();


        // Initialize Array with fetched data
        $data = array();
        foreach ($users as $user) {
            $data[] = array("id" => $user['id'], "text" => $user['username'] . " | " . $user['mobile'] . " | " . $user['email'], "number" => $user['mobile'], "email" => $user['email'], "name" => $user['username']);
        }
        return $data;
    }
}
