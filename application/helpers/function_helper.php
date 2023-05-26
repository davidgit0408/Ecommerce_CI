<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
	1. create_unique_slug($string,$table,$field='slug',$key=NULL,$value=NULL)
	2. ($type = 'store_settings', $is_json = false)
	3. get_logo()
	4. fetch_details($where = NULL,$table,$fields = '*')
	5. fetch_product($user_id = NULL, $filter = NULL, $id = NULL, $category_id = NULL, $limit = NULL, $offset = NULL, $sort = NULL, $order = NULL, $return_count = NULL)
	6. update_details($set,$where,$table)
	7. delete_image($id,$path,$field,$img_name,$table_name,$isjson = TRUE)
	8. delete_details($where,$table)
	9. is_json($data=NULL)
   10. validate_promo_code($promo_code,$user_id,$final_total)
   11. update_wallet_balance($operation,$user_id,$amount,$message="Balance Debited")
   12. send_notification($fcmMsg, $registrationIDs_chunks)
   13. get_attribute_values_by_pid($id)
   14. get_attribute_values_by_id($id)
   15. get_variants_values_by_pid($id)
   16. update_stock($product_variant_ids, $qtns)
   17. validate_stock($product_variant_ids, $qtns)
   18. stock_status($product_variant_id)
   19. verify_user($data)
   20. edit_unique($field,$table,$except)
   21. validate_order_status($order_ids, $status, $table = 'order_items', $user_id = null)
   22. is_exist($where,$table) 
   23. get_categories_option_html($categories, $selected_vals = null)
   24. get_subcategory_option_html($subcategories, $selected_vals)
   25. get_cart_total($user_id,$product_variant_id)
   26. get_frontend_categories_html()
   27. get_frontend_subcategories_html($subcategories)
   28. resize_image($image_data, $source_path, $id = false)
   29. has_permissions($role,$module) 
   30. print_msg($error,$message)
   31. get_system_update_info()
   32. send_mail($to,$subject,$message)
   33. fetch_orders($order_id = NULL, $user_id = NULL, $status = NULL, $delivery_boy_id = NULL, $limit = NULL, $offset = NULL, $sort = NULL, $order = NULL, $download_invoice = false)
   34. find_media_type($extenstion)
   35. formatBytes($size, $precision = 2)
   36. delete_images($subdirectory, $image_name)
   37. get_image_url($path, $image_type = '', $image_size = '')
   38. fetch_users($id)
   39. escape_array($array)
   40. allowed_media_types()
   41. get_current_version()
   42. resize_review_images($image_data, $source_path, $id = false)
   43. get_invoice_html($order_id)
   44. is_modification_allowed($module)
   45. output_escaping($array)
   46. get_min_max_price_of_product($product_id = '')
   47. find_discount_in_percentage($special_price, $price)
   48. get_attribute_ids_by_value($values,$names)
   49. insert_details($data,$table)
   50. get_category_id_by_slug($slug)
   51. get_variant_attributes($product_id)
   52. get_product_variant_details($product_variant_id)
   53. get_cities($id = NULL, $limit = NULL, $offset = NULL)
   54. get_favorites($user_id, $limit = NULL, $offset = NULL)
   55. current_theme($id='',$name='',$slug='',$is_default=1,$status='')
   56. get_languages($id='',$language_name='',$code='',$is_rtl='')
   60. verify_payment_transaction($txn_id,$payment_method)
   61. process_referral_bonus($user_id, $order_id, $status)
   62. process_refund($id, $status, $type = 'order_items')
   63. get_user_balance($id)
   64. get_stock()
   65. get_delivery_charge($address_id)
   66. validate_otp($order_id, $otp)
   67. is_product_delivarable($type, $type_id, $product_id)
   68. check_cart_products_delivarable($area_id, $user_id)
   69. orders_count($status = "")
   70. curl($url, $method = 'GET', $data = [], $authorization = "")
   71. get_seller_permission($seller_id, $permit = NULL)
   72. get_price($type = "max")
   73. check_for_parent_id($category_id)
   74. update_balance($amount, $delivery_boy_id, $action)
*/

function create_unique_slug($string, $table, $field = 'slug', $key = NULL, $value = NULL)
{
    $t = &get_instance();
    $slug = url_title($string);
    $slug = strtolower($slug);
    $i = 0;
    $params = array();
    $params[$field] = $slug;

    if ($key) $params["$key !="] = $value;

    while ($t->db->where($params)->get($table)->num_rows()) {
        if (!preg_match('/-{1}[0-9]+$/', $slug))
            $slug .= '-' . ++$i;
        else
            $slug = preg_replace('/[0-9]+$/', ++$i, $slug);

        $params[$field] = $slug;
    }
    return $slug;
}

function get_settings($type = 'system_settings', $is_json = false)
{
    $t = &get_instance();

    $res = $t->db->select(' * ')->where('variable', $type)->get('settings')->result_array();
    if (!empty($res)) {
        if ($is_json) {
            return json_decode($res[0]['value'], true);
        } else {
            return output_escaping($res[0]['value']);
        }
    }
}

function get_logo()
{
    $t = &get_instance();
    $res = $t->db->select(' * ')->where('variable', 'logo')->get('settings')->result_array();
    if (!empty($res)) {
        $logo['is_null'] = FALSE;
        $logo['value'] = base_url() . $res[0]['value'];
    } else {
        $logo['is_null'] = TRUE;
        $logo['value'] = base_url() . NO_IMAGE;
    }
    return $logo;
}

function fetch_details($table, $where = NULL, $fields = '*', $limit = '', $offset = '', $sort = '', $order = '', $where_in_key = '', $where_in_value = '')
{
    $t = &get_instance();
    $t->db->select($fields);
    if (!empty($where)) {
        $t->db->where($where);
    }

    if (!empty($where_in_key) && !empty($where_in_value)) {
        $t->db->where_in($where_in_key, $where_in_value);
    }

    if (!empty($limit)) {
        $t->db->limit($limit);
    }

    if (!empty($offset)) {
        $t->db->offset($offset);
    }

    if (!empty($order) && !empty($sort)) {
        $t->db->order_by($sort, $order);
    }

    $res = $t->db->get($table)->result_array();
    return $res;
}

function fetch_product($user_id = NULL, $filter = NULL, $id = NULL, $category_id = NULL, $limit = NULL, $offset = NULL, $sort = NULL, $order = NULL, $return_count = NULL, $is_deliverable = NULL, $seller_id = NULL)
{

    $settings = get_settings('system_settings', true);
    $low_stock_limit = isset($settings['low_stock_limit']) ? $settings['low_stock_limit'] : 5;
    $t = &get_instance();

    if ($sort == 'pv.price' && !empty($sort) && $sort != NULL) {
        $t->db->order_by("IF( pv.special_price > 0 , pv.special_price , pv.price )" . $order, False);
    }
    if (isset($filter['show_only_active_products']) && $filter['show_only_active_products'] == 0) {
        $where = [];
    } else {
        $where = ['p.status' => '1', 'pv.status' => 1, 'sd.status' => 1];
    }

    $discount_filter_data = (isset($filter['discount']) && !empty($filter['discount'])) ? ' pv.*,( if(pv.special_price > 0,( (pv.price-pv.special_price)/pv.price)*100,0)) as cal_discount_percentage, ' : '';

    $t->db->select($discount_filter_data . ' (select count(id)  from products where products.category_id=c.id ) as total,count(p.id) as sales, p.stock_type ,
     p.is_prices_inclusive_tax, p.type ,GROUP_CONCAT(DISTINCT(pa.attribute_value_ids)) as attr_value_ids,sd.rating as seller_rating,sd.slug as seller_slug,sd.no_of_ratings as seller_no_of_ratings,sd.logo as seller_profile, sd.store_name as store_name,sd.store_description, p.seller_id, u.username as seller_name,
     p.id,p.stock,p.name,p.category_id,p.short_description,p.slug,p.description,p.total_allowed_quantity,p.status,p.deliverable_type,p.deliverable_zipcodes,p.minimum_order_quantity,p.sku,
     p.quantity_step_size,p.cod_allowed,p.row_order,p.rating,p.no_of_ratings,p.image,p.is_returnable,p.is_cancelable,p.cancelable_till,p.indicator,p.other_images, 
     p.video_type, p.video, p.tags, p.warranty_period, p.guarantee_period, p.made_in,p.hsn_code,p.download_allowed,p.download_type,p.download_link,p.brand,p.availability,c.name as category_name,tax.percentage as tax_percentage ,tax.id as tax_id ')
        ->join(" categories c", "p.category_id=c.id ", 'LEFT')
        ->join(" seller_data sd", "p.seller_id=sd.user_id ", 'LEFT')
        ->join(" users u", "p.seller_id=u.id", 'LEFT')
        ->join('`product_variants` pv', 'p.id = pv.product_id', 'LEFT')
        ->join('`taxes` tax', 'tax.id = p.tax', 'LEFT')
        ->join('`product_attributes` pa', ' pa.product_id = p.id ', 'LEFT');


    if (isset($filter['show_only_stock_product']) && $filter['show_only_stock_product'] == 1) {
        $t->db->where('(p.stock != "" or pv.stock != "")');
    }

    if (isset($filter) && !empty($filter['product_type']) && strtolower($filter['product_type']) == 'most_selling_products') {
        $t->db->join('`order_items` oi', 'oi.product_variant_id = pv.id', 'LEFT');
        $sort = 'count(p.id)';
        $order = 'DESC';
    }

    if (isset($filter) && !empty($filter['search'])) {
        $tags = explode(" ", $filter['search']);
        $t->db->group_Start();
        foreach ($tags as $i => $tag) {
            if ($i == 0) {
                $t->db->like('p.tags', trim($tag));
            } else {
                $t->db->or_like('p.tags', trim($tag));
            }
        }
        $t->db->or_like('p.name', trim($filter['search']));
        $t->db->group_end();
    }
    if (isset($filter) && !empty($filter['flag']) && $filter['flag'] != "null" && $filter['flag'] != "") {
        $flag = $filter['flag'];
        if ($flag == 'low') {
            $t->db->group_Start();
            $where1 = "p.stock_type is  NOT NULL";
            $t->db->where($where1);
            $t->db->where('p.stock <=', $low_stock_limit);
            $t->db->where('p.availability =', '1');
            $t->db->or_where('pv.stock <=', $low_stock_limit);
            $t->db->where('pv.availability =', '1');
            $t->db->group_End();
        } else {
            $t->db->group_Start();
            $t->db->or_where('p.availability ', '0');
            $t->db->or_where('pv.availability ', '0');
            $t->db->where('p.stock ', '0');
            $t->db->or_where('pv.stock ', '0');
            $t->db->group_End();
        }
    }
    if (isset($filter['min_price']) && $filter['min_price'] > 0) {
        $min_price = $filter['min_price'];
        $where_min = "if( pv.special_price > 0 , pv.special_price , pv.price ) >=$min_price";
        $t->db->group_Start();
        $t->db->where($where_min);
        $t->db->group_End();
    }
    if (isset($filter['max_price']) && $filter['max_price'] > 0 && isset($filter['min_price']) && $filter['min_price'] > 0) {
        $max_price = $filter['max_price'];
        $where_max = "if( pv.special_price > 0 , pv.special_price , pv.price ) <=$max_price";
        $t->db->group_Start();
        $t->db->where($where_max);
        $t->db->group_End();
    }

    if (isset($filter) && !empty($filter['tags'])) {
        $tags = explode(",", $filter['tags']);
        $t->db->group_Start();
        foreach ($tags as $i => $tag) {
            if ($i == 0) {
                $t->db->like('p.tags', trim($tag));
            } else {
                $t->db->or_like('p.tags', trim($tag));
            }
        }
        $t->db->group_end();
    }

    if (isset($filter) && !empty($filter['slug'])) {
        $where['p.slug'] = $filter['slug'];
    }
    if (isset($seller_id) && !empty($seller_id) && $seller_id != "") {
        $where['p.seller_id'] = $seller_id;
    }


    if (isset($filter) && !empty($filter['attribute_value_ids'])) {
        /* https://stackoverflow.com/questions/5015403/mysql-find-in-set-with-multiple-search-string */
        $str = str_replace(',', '|', $filter['attribute_value_ids']); //str_replace(find,replace,string,count)
        $t->db->where('CONCAT(",", pa.attribute_value_ids , ",") REGEXP ",(' . $str . ')," !=', 0, false);
    }

    if (isset($category_id) && !empty($category_id)) {
        if (is_array($category_id) && !empty($category_id)) {
            $t->db->group_Start();
            $t->db->where_in('p.category_id', $category_id);
            $t->db->or_where_in('c.parent_id', $category_id);
            $t->db->group_End();
            $t->db->where($where);
        } else {
            $where['p.category_id'] = $category_id;
        }
    }

    if (isset($filter) && !empty($filter['product_type']) && strtolower($filter['product_type']) == 'products_on_sale') {
        $t->db->where('pv.special_price >', '0');
    }

    if (isset($filter) && !empty($filter['product_type']) && strtolower($filter['product_type']) == 'top_rated_products') {
        $sort = null;
        $order = null;
        $t->db->order_by("p.rating", "desc");
        $t->db->order_by("p.no_of_ratings", "desc");
        $where = ['p.no_of_ratings > ' => 0];
    }



    if (isset($filter) && !empty($filter['product_type']) && strtolower($filter['product_type']) == 'top_rated_product_including_all_products') {
        $sort = null;
        $order = null;
        $t->db->order_by("p.rating", "desc");
        $t->db->order_by("p.no_of_ratings", "desc");
    }

    if (isset($filter) && !empty($filter['product_type']) && $filter['product_type'] == 'new_added_products') {
        $sort = 'p.id';
        $order = 'desc';
    }

    if (isset($filter) && !empty($filter['product_variant_ids'])) {
        if (is_array($filter['product_variant_ids'])) {
            $t->db->where_in('pv.id', $filter['product_variant_ids']);
        }
    }

    if (isset($id) && !empty($id) && $id != null) {
        if (is_array($id) && !empty($id)) {
            $t->db->where_in('p.id', $id);
            $t->db->where($where);
        } else {
            if (isset($filter) && !empty($filter['is_similar_products']) && $filter['is_similar_products'] == '1') {
                $where[' p.id != '] = $id;
            } else {
                $where['p.id'] = $id;
            }
            $t->db->where($where);
        }
    } else {
        $t->db->where($where);
    }
    if (!isset($filter['flag']) && empty($filter['flag'])) {
        $t->db->group_Start();
        $t->db->or_where('c.status', '1');
        $t->db->or_where('c.status', '0');
        $t->db->group_End();
    }
    if (isset($filter['discount']) && !empty($filter['discount']) && $filter['discount'] != "") {
        $discount_pr = $filter['discount'];
        $t->db->group_by('p.id')->having("cal_discount_percentage  <= " . $discount_pr, null, false)->having("cal_discount_percentage  > 0 ", null, false);
    } else {
        $t->db->group_by('p.id');
    }


    if ($limit != null || $offset != null) {
        $t->db->limit($limit, $offset);
    }
    if (isset($filter['discount']) && !empty($filter['discount']) && $filter['discount'] != "") {
        $t->db->order_by('cal_discount_percentage', 'DESC');
    } else {
        if ($sort != null || $order != null && $sort != 'pv.price') {
            $t->db->order_by($sort, $order);
        }
        $t->db->order_by('p.row_order', 'ASC');
    }


    if (!empty($return_count)) {
        return $t->db->count_all_results('products p');
    } else {
        $product = $t->db->get('products p')->result_array();
        
    }
    $count = isset($filter) && !empty($filter['flag']) ? 'count(DISTINCT(p.id))' : 'count(DISTINCT(p.id))';
    $discount_filter = (isset($filter['discount']) && !empty($filter['discount'])) ? ' , GROUP_CONCAT( IF( ( IF( pv.special_price > 0, ((pv.price - pv.special_price) / pv.price) * 100, 0 ) ) > ' . $filter['discount'] . ', ( IF( pv.special_price > 0, ((pv.price - pv.special_price) / pv.price) * 100, 0 ) ), 0 ) ) AS cal_discount_percentage ' : '';
    $product_count = $t->db->select('count(DISTINCT(p.id)) as total , GROUP_CONCAT(pa.attribute_value_ids) as attr_value_ids' . $discount_filter)
        ->join(" categories c", "p.category_id=c.id ", 'LEFT')
        ->join(" seller_data sd", "p.seller_id=sd.user_id ")
        ->join('`product_variants` pv', 'p.id = pv.product_id', 'LEFT')
        ->join('`product_attributes` pa', ' pa.product_id = p.id ', 'LEFT');

    if (isset($filter) && !empty($filter['search'])) {
        $tags = explode(" ", $filter['search']);
        $t->db->group_Start();
        foreach ($tags as $i => $tag) {
            if ($i == 0) {
                $t->db->like('p.tags', trim($tag));
            } else {
                $t->db->or_like('p.tags', trim($tag));
            }
        }
        $product_count->or_like('p.name', $filter['search']);
        $t->db->group_End();
    }
    if (isset($filter) && !empty($filter['flag'])) {
        $flag = $filter['flag'];
        if ($flag == 'low') {
            $t->db->group_Start();
            $where1 = "p.stock_type is  NOT NULL";
            $t->db->where($where1);
            $t->db->where('p.stock <=', $low_stock_limit);
            $t->db->where('p.availability =', '1');
            $t->db->or_where('pv.stock <=', $low_stock_limit);
            $t->db->where('pv.availability =', '1');
            $t->db->group_End();
        } else {
            $t->db->group_Start();
            $t->db->or_where('p.availability ', '0');
            $t->db->or_where('pv.availability ', '0');
            $t->db->where('p.stock ', '0');
            $t->db->or_where('pv.stock ', '0');
            $t->db->group_End();
        }
    }

    if (isset($filter) && !empty($filter['tags'])) {
        $tags = explode(",", $filter['tags']);
        $t->db->group_Start();
        foreach ($tags as $i => $tag) {
            if ($i == 0) {
                $t->db->like('p.tags', trim($tag));
            } else {
                $t->db->or_like('p.tags', trim($tag));
            }
        }
        $t->db->group_End();
    }

    if (isset($filter) && !empty($filter['attribute_value_ids'])) {
        $str = str_replace(',', '|', $filter['attribute_value_ids']); // Ids should be in string and comma separated 
        $product_count->where('CONCAT(",", pa.attribute_value_ids, ",") REGEXP ",(' . $str . ')," !=', 0, false);
    }
    if (isset($filter) && !empty($filter['product_type']) && strtolower($filter['product_type']) == 'most_selling_products') {
        $product_count->join('`order_items` oi', 'oi.product_variant_id = pv.id', 'LEFT');
    }
    if (isset($category_id) && !empty($category_id)) {
        if (is_array($category_id) && !empty($category_id)) {
            $product_count->where_in('p.category_id', $category_id);
            $product_count->or_where_in('c.parent_id', $category_id);
            $product_count->where($where);
        }
    }

    if (isset($filter) && !empty($filter['product_type']) && strtolower($filter['product_type']) == 'products_on_sale') {
        $product_count->where('pv.special_price >=', '0');
    }
    if (isset($id) && !empty($id) && $id != null) {
        if (is_array($id) && !empty($id)) {
            $product_count->where_in('p.id', $id);
        }
    }
    if (isset($seller_id) && !empty($seller_id) && $seller_id != "") {
        $where['p.seller_id'] = $seller_id;
    }
    if (isset($seller_id) && !empty($seller_id) && $seller_id != "") {
        if (isset($filter['show_only_stock_product']) && $filter['show_only_stock_product'] == 1) {
            $t->db->where('(p.stock != "" or pv.stock != "")');
        }
    }
    if (isset($filter['show_only_stock_product']) && $filter['show_only_stock_product'] == 1) {
        $t->db->where('(p.stock != "" or pv.stock != "")');
    }
    $product_count->where($where);
    if (!isset($filter['flag']) && empty($filter['flag'])) {
        $product_count->group_Start();
        $product_count->or_where('c.status', '1');
        $product_count->or_where('c.status', '0');
        $product_count->group_End();
    }

    $count_res = $product_count->get('products p')->result_array();
    // echo $t->db->last_query();
    // return;
    $attribute_values_ids = array();
    $temp = [];
    $min_price = get_price('min');
    $max_price = get_price('max');

    //  print_r($product);


    if (!empty($product)) {
        $t->load->model('rating_model');
        for ($i = 0; $i < count($product); $i++) {

            $rating = $t->rating_model->fetch_rating($product[$i]['id'], '', 8, 0, 'pr.id', 'desc', '', 1);
            $product[$i]['review_images'] = (!empty($rating)) ? [$rating] : array();

            $product[$i]['tax_percentage'] = (isset($product[$i]['tax_percentage']) && intval($product[$i]['tax_percentage']) > 0) ? $product[$i]['tax_percentage'] : '0';
            $product[$i]['tax_id'] = ((isset($product[$i]['tax_id']) && intval($product[$i]['tax_id']) > 0) && $product[$i]['tax_id'] != "") ? $product[$i]['tax_id'] : '0';
            $product[$i]['attributes'] = get_attribute_values_by_pid($product[$i]['id']);
            $product[$i]['variants'] = get_variants_values_by_pid($product[$i]['id']);
            $variants =   get_variants_values_by_pid($product[$i]['id']);
            $total_stock = 0;
            foreach ($variants as $variant) {
                $stock = (isset($variant['stock']) && !empty($variant['stock'])) ? $variant['stock'] : 0;
                $total_stock  += $stock;
                $product[$i]['total_stock'] = isset($total_stock) && !empty($total_stock) ? $total_stock : '';
            }
            $product[$i]['min_max_price'] = get_min_max_price_of_product($product[$i]['id']);
            $product[$i]['stock_type'] = isset($product[$i]['stock_type']) && ($product[$i]['stock_type'] != '') ? $product[$i]['stock_type'] : '';
            $product[$i]['stock'] = isset($product[$i]['stock']) && !empty($product[$i]['stock']) ? $product[$i]['stock'] : '';
            $product[$i]['relative_path'] = isset($product[$i]['image']) && !empty($product[$i]['image']) ? $product[$i]['image'] : '';
            $product[$i]['other_images_relative_path'] = isset($product[$i]['other_images']) && !empty($product[$i]['other_images']) ? json_decode($product[$i]['other_images']) : [];
            $product[$i]['video_relative_path'] = (isset($product[$i]['video']) && (!empty($product[$i]['video']))) ? $product[$i]['video'] : "";
            $product[$i]['video_type'] = isset($product[$i]['video_type']) && !empty($product[$i]['video_type']) ? $product[$i]['video_type'] : '';
            $product[$i]['attr_value_ids'] = isset($product[$i]['attr_value_ids']) && !empty($product[$i]['attr_value_ids']) ? $product[$i]['attr_value_ids'] : '';
            $product[$i]['made_in'] = isset($product[$i]['made_in']) && !empty($product[$i]['made_in']) ? $product[$i]['made_in'] : '';
            $product[$i]['hsn_code'] = isset($product[$i]['hsn_code']) && !empty($product[$i]['hsn_code']) ? $product[$i]['hsn_code'] : '';
            $product[$i]['brand'] = isset($product[$i]['brand']) && !empty($product[$i]['brand']) ? $product[$i]['brand'] : '';
            $product[$i]['warranty_period'] = isset($product[$i]['warranty_period']) && !empty($product[$i]['warranty_period']) ? $product[$i]['warranty_period'] : '';
            $product[$i]['guarantee_period'] = isset($product[$i]['guarantee_period']) && !empty($product[$i]['guarantee_period']) ? $product[$i]['guarantee_period'] : '';
            $product[$i]['total_allowed_quantity'] = isset($product[$i]['total_allowed_quantity']) && !empty($product[$i]['total_allowed_quantity']) ? $product[$i]['total_allowed_quantity'] : '';
            $product[$i]['download_allowed'] = isset($product[$i]['download_allowed']) && !empty($product[$i]['download_allowed']) ? $product[$i]['download_allowed'] : '';
            $product[$i]['download_type'] = isset($product[$i]['download_type']) && !empty($product[$i]['download_type']) ? $product[$i]['download_type'] : '';
            $product[$i]['download_link'] = isset($product[$i]['download_link']) && !empty($product[$i]['download_link']) ? $product[$i]['download_link'] : '';
            $product[$i]['status'] = isset($product[$i]['status']) && !empty($product[$i]['status']) ? $product[$i]['status'] : '';
            $total_product = $t->db->query("select count(id) as total  from products where products.seller_id=" . $product[$i]['seller_id'] . " AND products.status='1'")->result_array();

            /* outputing escaped data */
            $product[$i]['name'] = output_escaping($product[$i]['name']);
            $product[$i]['total_product'] = ($total_product[0]['total']);
            $product[$i]['store_name'] = output_escaping($product[$i]['store_name']);
            $product[$i]['seller_rating'] = (isset($product[$i]['seller_rating']) && !empty($product[$i]['seller_rating'])) ? output_escaping(number_format($product[$i]['seller_rating'], 1)) : 0;
            $product[$i]['store_description'] = (isset($product[$i]['store_description']) && !empty($product[$i]['store_description'])) ? output_escaping($product[$i]['store_description']) : "";
            $product[$i]['seller_profile'] = output_escaping(base_url() . $product[$i]['seller_profile']);
            $product[$i]['seller_name'] = output_escaping($product[$i]['seller_name']);
            $product[$i]['short_description'] = output_escaping($product[$i]['short_description']);
            $product[$i]['description'] = (isset($product[$i]['description']) && !empty($product[$i]['description'])) ? output_escaping($product[$i]['description']) : "";
            $product[$i]['seller_slug'] = isset($product[$i]['seller_slug']) && !empty($product[$i]['seller_slug']) ? output_escaping($product[$i]['seller_slug']) : "";
            $product[$i]['deliverable_type'] = $product[$i]['deliverable_type'];
            $product[$i]['deliverable_zipcodes_ids'] = output_escaping($product[$i]['deliverable_zipcodes']);
            if (isset($filter['discount']) && !empty($filter['discount']) && $filter['discount'] != "") {
                $product[$i]['cal_discount_percentage'] = output_escaping(number_format($product[$i]['cal_discount_percentage'], 2));
            }
            $product[$i]['cancelable_till'] = isset($product[$i]['cancelable_till']) && !empty($product[$i]['cancelable_till']) ? $product[$i]['cancelable_till'] : '';
            $product[$i]['indicator'] = isset($product[$i]['indicator']) && !empty($product[$i]['indicator']) ? $product[$i]['indicator'] : '0';
            $product[$i]['deliverable_zipcodes_ids'] = isset($product[$i]['deliverable_zipcodes_ids']) && !empty($product[$i]['deliverable_zipcodes_ids']) ? $product[$i]['deliverable_zipcodes_ids'] : '';
            $product[$i]['rating'] = output_escaping(number_format($product[$i]['rating'], 2));
            $product[$i]['availability'] = isset($product[$i]['availability']) && ($product[$i]['availability'] != "") ? $product[$i]['availability'] : '';
            $product[$i]['sku'] = isset($product[$i]['sku']) && ($product[$i]['sku'] != "") ? $product[$i]['sku'] : '';

            /* getting zipcodes from ids */
            if ($product[$i]['deliverable_type'] != NONE && $product[$i]['deliverable_type'] != ALL) {
                $zipcodes = array();
                $zipcode_ids = explode(",", $product[$i]['deliverable_zipcodes_ids']);
                // $t->db->select('zipcode');
                // $t->db->where_in('id', $zipcode_ids);
                // $zipcodes = $t->db->get('zipcodes')->result_array();
                $zipcodes = array_column($zipcodes, "zipcode");
                $product[$i]['deliverable_zipcodes'] = implode(",", $zipcode_ids);
            } else {
                $product[$i]['deliverable_zipcodes'] = '';
            }
            $product[$i]['category_name'] = (isset($product[$i]['category_name']) && !empty($product[$i]['category_name'])) ? output_escaping($product[$i]['category_name']) : '';
            /* check product delivrable or not */
            if ($is_deliverable != NULL) {
                $zipcode = fetch_details('zipcodes', ['zipcode' => $is_deliverable], 'id');
                if (!empty($zipcode)) {
                    $product[$i]['is_deliverable'] = is_product_delivarable($type = 'zipcode', $zipcode[0]['id'], $product[$i]['id']);
                } else {
                    $product[$i]['is_deliverable'] = false;
                }
            } else {
                $product[$i]['is_deliverable'] = false;
            }

            if ($product[$i]['deliverable_type'] == 1) {
                $product[$i]['is_deliverable'] = true;
            }


            $product[$i]['tags'] = (!empty($product[$i]['tags'])) ? explode(",", $product[$i]['tags']) : [];

            $product[$i]['video'] = (isset($product[$i]['video_type']) && (!empty($product[$i]['video_type']) || $product[$i]['video_type'] != NULL)) ? (($product[$i]['video_type'] == 'youtube' || $product[$i]['video_type'] == 'vimeo') ? $product[$i]['video'] : base_url($product[$i]['video'])) : "";
            $product[$i]['minimum_order_quantity'] = isset($product[$i]['minimum_order_quantity']) && (!empty($product[$i]['minimum_order_quantity'])) ? $product[$i]['minimum_order_quantity'] : 1;
            $product[$i]['quantity_step_size'] = isset($product[$i]['quantity_step_size']) && (!empty($product[$i]['quantity_step_size'])) ? $product[$i]['quantity_step_size'] : 1;
            if (!empty($product[$i]['variants'])) {
                $count_stock = array();
                $is_purchased_count = array();
                for ($k = 0; $k < count($product[$i]['variants']); $k++) {
                    $variant_other_images = $variant_other_images_sm = $variant_other_images_md = json_decode((string)$product[$i]['variants'][$k]['images'], 1);

                    if (!empty($variant_other_images)) {
                        $product[$i]['variants'][$k]['variant_relative_path'] = isset($product[$i]['variants'][$k]['images']) && !empty($product[$i]['variants'][$k]['images']) ? json_decode($product[$i]['variants'][$k]['images']) : [];
                        $counter = 0;
                        foreach ($variant_other_images_md as $row) {
                            $variant_other_images_md[$counter] = get_image_url($variant_other_images_md[$counter], 'thumb', 'md');
                            $counter++;
                        }
                        $product[$i]['variants'][$k]['images_md'] = $variant_other_images_md;

                        $counter = 0;
                        foreach ($variant_other_images_sm as $row) {
                            $variant_other_images_sm[$counter] = get_image_url($variant_other_images_sm[$counter], 'thumb', 'sm');
                            $counter++;
                        }
                        $product[$i]['variants'][$k]['images_sm'] = $variant_other_images_sm;

                        $counter = 0;
                        foreach ($variant_other_images as $row) {
                            $variant_other_images[$counter] = get_image_url($variant_other_images[$counter]);
                            $counter++;
                        }
                        $product[$i]['variants'][$k]['images'] = $variant_other_images;
                    } else {
                        $product[$i]['variants'][$k]['images'] = array();
                        $product[$i]['variants'][$k]['images_md'] = array();
                        $product[$i]['variants'][$k]['images_sm'] = array();
                        $product[$i]['variants'][$k]['variant_relative_path'] = array();
                    }
                    $product[$i]['variants'][$k]['swatche_type'] = (!empty($product[$i]['variants'][$k]['swatche_type'])) ? $product[$i]['variants'][$k]['swatche_type'] : "0";
                    $product[$i]['variants'][$k]['swatche_value'] = (!empty($product[$i]['variants'][$k]['swatche_value'])) ? $product[$i]['variants'][$k]['swatche_value'] : "0";
                    if (($product[$i]['stock_type'] == 0  || $product[$i]['stock_type'] == null)) {
                        if ($product[$i]['availability'] != null) {
                            $product[$i]['variants'][$k]['availability'] = $product[$i]['availability'];
                        }
                    } else {
                        $product[$i]['variants'][$k]['availability'] = ($product[$i]['variants'][$k]['availability'] != null) ? $product[$i]['variants'][$k]['availability'] : 1;
                        array_push($count_stock, $product[$i]['variants'][$k]['availability']);
                    }
                    if (($product[$i]['stock_type'] == 0)) {
                        $product[$i]['variants'][$k]['stock'] = isset($product[$i]['variants'][$k]['stock']) && !empty($product[$i]['variants'][$k]['stock']) ? get_stock($product[$i]['id'], 'product') : '';
                    } else {
                        $product[$i]['variants'][$k]['stock'] = isset($product[$i]['variants'][$k]['stock']) && !empty($product[$i]['variants'][$k]['stock']) ? get_stock($product[$i]['variants'][$k]['id'], 'variant') : '';
                    }
                    $percentage = (isset($product[$i]['tax_percentage']) && intval($product[$i]['tax_percentage']) > 0 && $product[$i]['tax_percentage'] != null) ? $product[$i]['tax_percentage'] : '0';
                    if ((isset($product[$i]['is_prices_inclusive_tax']) && $product[$i]['is_prices_inclusive_tax'] == 0) || (!isset($product[$i]['is_prices_inclusive_tax'])) && $percentage > 0) {
                        $price_tax_amount = $product[$i]['variants'][$k]['price'] * ($percentage / 100);
                        $product[$i]['variants'][$k]['price'] =  strval($product[$i]['variants'][$k]['price'] + $price_tax_amount);
                        $special_price_tax_amount = $product[$i]['variants'][$k]['special_price'] * ($percentage / 100);
                        $product[$i]['variants'][$k]['special_price'] =  strval($product[$i]['variants'][$k]['special_price'] + $special_price_tax_amount);
                    } else {
                        $product[$i]['variants'][$k]['price'] =  strval($product[$i]['variants'][$k]['price']);
                        $product[$i]['variants'][$k]['special_price'] =  strval($product[$i]['variants'][$k]['special_price']);
                    }
                    if (isset($user_id) && $user_id != NULL) {
                        $user_cart_data = $t->db->select('qty as cart_count')->where(['product_variant_id' => $product[$i]['variants'][$k]['id'], 'user_id' => $user_id, 'is_saved_for_later' => 0])->get('cart')->result_array();
                        if (!empty($user_cart_data)) {
                            $product[$i]['variants'][$k]['cart_count'] = $user_cart_data[0]['cart_count'];
                        } else {
                            $product[$i]['variants'][$k]['cart_count'] = "0";
                        }
                        $is_purchased = $t->db->where(['oi.product_variant_id' => $product[$i]['variants'][$k]['id'], 'oi.user_id' => $user_id])->limit(1)->get('order_items oi')->result_array();
                        if (!empty($is_purchased)) {
                            array_push($is_purchased_count, 1);
                            $product[$i]['variants'][$k]['is_purchased'] = 1;
                        } else {
                            array_push($is_purchased_count, 0);
                            $product[$i]['variants'][$k]['is_purchased'] = 0;
                        }

                        $user_rating = $t->db->select('rating,comment')->where(['user_id' => $user_id, 'product_id' => $product[$i]['id']])->get('product_rating')->result_array();
                        if (!empty($user_rating)) {
                            $product[$i]['user']['user_rating'] =   (isset($product[$i]['user']['user_rating']) && (!empty($product[$i]['user']['user_rating']))) ? $user_rating[0]['rating'] : '';
                            $product[$i]['user']['user_comment'] =   (isset($product[$i]['user']['user_comment']) && (!empty($product[$i]['user']['user_comment']))) ? $user_rating[0]['user_comment'] : '';
                        }
                    } else {
                        $product[$i]['variants'][$k]['cart_count'] = "0";
                    }
                }
            }

            $is_purchased_count = array_count_values($is_purchased_count);
            $is_purchased_count = array_keys($is_purchased_count);
            $product[$i]['is_purchased'] = (isset($is_purchased) && array_sum($is_purchased_count) == 1) ? true : false;

            if (($product[$i]['stock_type'] != null && !empty($product[$i]['stock_type']))) {


                //Case 2 & 3 : Product level(variable product) ||  Variant level(variable product)
                if ($product[$i]['stock_type'] == 1 || $product[$i]['stock_type'] == 2) {
                    $counts = array_count_values($count_stock);
                    $counts = array_keys($counts);
                    if (isset($counts)) {
                        $product[$i]['availability'] = array_sum($counts);
                    }
                }
            }

            if (isset($user_id) && $user_id != null) {
                $fav = $t->db->where(['product_id' => $product[$i]['id'], 'user_id' => $user_id])->get('favorites')->num_rows();
                $product[$i]['is_favorite'] = $fav;
            } else {
                $product[$i]['is_favorite'] = '0';
            }

            $product[$i]['image_md'] = get_image_url($product[$i]['image'], 'thumb', 'md');
            $product[$i]['image_sm'] = get_image_url($product[$i]['image'], 'thumb', 'sm');
            $product[$i]['image'] = get_image_url($product[$i]['image']);
            $other_images = $other_images_sm =  $other_images_md = json_decode($product[$i]['other_images'], 1);

            if (!empty($other_images)) {

                $k = 0;
                foreach ($other_images_md as $row) {
                    $other_images_md[$k] = get_image_url($row, 'thumb', 'md');
                    $k++;
                }
                $other_images_md = (array) $other_images_md;
                $other_images_md = array_values($other_images_md);
                $product[$i]['other_images_md'] = $other_images_md;

                $k = 0;
                foreach ($other_images_sm as $row) {
                    $other_images_sm[$k] = get_image_url($row, 'thumb', 'sm');
                    $k++;
                }
                $other_images_sm = (array) $other_images_sm;
                $other_images_sm = array_values($other_images_sm);
                $product[$i]['other_images_sm'] = $other_images_sm;

                $k = 0;
                foreach ($other_images as $row) {
                    $other_images[$k] = get_image_url($row);
                    $k++;
                }
                $other_images = (array) $other_images;
                $other_images = array_values($other_images);
                $product[$i]['other_images'] = $other_images;
            } else {
                $product[$i]['other_images'] = array();
                $product[$i]['other_images_sm'] = array();
                $product[$i]['other_images_md'] = array();
            }
            $tags_to_strip = array("table", "<th>", "<td>");
            $replace_with = array("", "h3", "p");
            $n = 0;
            foreach ($tags_to_strip as $tag) {
                $product[$i]['description'] = output_escaping(str_replace('\r\n', '&#13;&#10;', (string)$product[$i]['description']));
                $n++;
            }
            $variant_attributes = [];
            $attributes_array = explode(',', $product[$i]['variants'][0]['attr_name']);

            foreach ($attributes_array as $attribute) {
                $attribute = trim($attribute);
                $key = array_search($attribute, array_column($product[$i]['attributes'], 'name'), false);
                if (($key === 0 || !empty($key)) && isset($product[0]['attributes'][$key])) {
                    $variant_attributes[$key]['ids'] = $product[0]['attributes'][$key]['ids'];
                    $variant_attributes[$key]['values'] = $product[0]['attributes'][$key]['value'];
                    $variant_attributes[$key]['swatche_type'] = $product[0]['attributes'][$key]['swatche_type'];
                    $variant_attributes[$key]['swatche_value'] = $product[0]['attributes'][$key]['swatche_value'];
                    $variant_attributes[$key]['attr_name'] = $attribute;
                }
            }
            $product[$i]['variant_attributes'] = $variant_attributes;
        }
        if (isset($count_res[0]['cal_discount_percentage'])) {
            $dicounted_total = array_values(array_filter(explode(',', $count_res[0]['cal_discount_percentage'])));
        } else {
            $dicounted_total = 0;
        }
        $response['total'] = (isset($filter) && !empty($filter['discount'])) ? count($dicounted_total) : $count_res[0]['total'];

        array_push($attribute_values_ids, $count_res[0]['attr_value_ids']);
        $attribute_values_ids = implode(",", $attribute_values_ids);
        $attr_value_ids = array_filter(array_unique(explode(',', $attribute_values_ids)));
    }

    $response['min_price'] = $min_price;
    $response['max_price'] = $max_price;
    $response['product'] = $product;
    if (isset($filter) && $filter != null) {
        if (!empty($attr_value_ids)) {
            $response['filters'] = get_attribute_values_by_id($attr_value_ids);
        }
    } else {
        $response['filters'] = [];
    }

    return $response;
}

function update_details($set, $where, $table, $escape = true)
{
    $t = &get_instance();
    $t->db->trans_start();
    if ($escape) {
        $set = escape_array($set);
    }
    $t->db->set($set)->where($where)->update($table);
    $t->db->trans_complete();
    $response = FALSE;
    if ($t->db->trans_status() === TRUE) {
        $response = TRUE;
    }
    return $response;
}

function delete_image($id, $path, $field, $img_name, $table_name, $isjson = TRUE)
{
    $t = &get_instance();
    $t->db->trans_start();
    if ($isjson == TRUE) {
        $image_set = fetch_details($table_name, ['id' => $id], $field);
        $new_image_set = escape_array(array_diff(json_decode($image_set[0][$field]), array($img_name)));
        $new_image_set = json_encode($new_image_set);
        $t->db->set([$field => $new_image_set])->where('id', $id)->update($table_name);
        $t->db->trans_complete();
        $response = FALSE;
        if ($t->db->trans_status() === TRUE) {
            $response = TRUE;
        }
    } else {
        $t->db->set([$field => ' '])->where(['id' => $id])->update($table_name);
        $t->db->trans_complete();
        $response = FALSE;
        if ($t->db->trans_status() === TRUE) {
            $response = TRUE;
        }
    }
    return $response;
}

function delete_details($where, $table)
{
    $t = &get_instance();
    if ($t->db->where($where)->delete($table)) {
        return true;
    } else {
        return false;
    }
}

//JSON Validator function
function is_json($data = NULL)
{
    if (!empty($data)) {
        @json_decode($data);
        return (json_last_error() === JSON_ERROR_NONE);
    }
    return false;
}

//validate_promo_code
function validate_promo_code($promo_code, $user_id, $final_total)
{

    if (isset($promo_code) && !empty($promo_code)) {
        $t = &get_instance();

        //Fetch Promo Code Details
        $promo_code = $t->db->select('pc.*,count(o.id) as promo_used_counter ,( SELECT count(user_id) from orders where user_id =' . $user_id . ' and promo_code ="' . $promo_code . '") as user_promo_usage_counter ')
            ->join('orders o', 'o.promo_code=pc.promo_code', 'left')
            ->where(['pc.promo_code' => $promo_code, 'pc.status' => '1', ' start_date <= ' => date('Y-m-d'), '  end_date >= ' => date('Y-m-d')])
            ->get('promo_codes pc')->result_array();
        if (!empty($promo_code[0]['id'])) {

            if (intval($promo_code[0]['promo_used_counter']) < intval($promo_code[0]['no_of_users'])) {

                if ($final_total >= intval($promo_code[0]['minimum_order_amount'])) {

                    if ($promo_code[0]['repeat_usage'] == 1 && ($promo_code[0]['user_promo_usage_counter'] <= $promo_code[0]['no_of_repeat_usage'])) {
                        if (intval($promo_code[0]['user_promo_usage_counter']) <= intval($promo_code[0]['no_of_repeat_usage'])) {

                            $response['error'] = false;
                            $response['message'] = 'The promo code is valid';

                            if ($promo_code[0]['discount_type'] == 'percentage') {
                                $promo_code_discount =  floatval($final_total  * $promo_code[0]['discount'] / 100);
                            } else {
                                $promo_code_discount = $promo_code[0]['discount'];
                            }
                            if ($promo_code_discount <= $promo_code[0]['max_discount_amount']) {
                                $total = (isset($promo_code[0]['is_cashback']) && $promo_code[0]['is_cashback'] == 0) ? floatval($final_total) - $promo_code_discount : floatval($final_total);
                            } else {
                                $total = (isset($promo_code[0]['is_cashback']) && $promo_code[0]['is_cashback'] == 0) ? floatval($final_total) - $promo_code[0]['max_discount_amount'] : floatval($final_total);
                                $promo_code_discount = $promo_code[0]['max_discount_amount'];
                            }
                            $promo_code[0]['final_total'] = strval(floatval($total));
                            $promo_code[0]['image'] = (isset($promo_code[0]['image']) && !empty($promo_code[0]['image'])) ? $promo_code[0]['image'] : '';
                            $promo_code[0]['final_discount'] = strval(floatval($promo_code_discount));
                            $response['data'] = $promo_code;
                            return $response;
                        } else {

                            $response['error'] = true;
                            $response['message'] = 'This promo code cannot be redeemed as it exceeds the usage limit';
                            $response['data']['final_total'] = strval(floatval($final_total));
                            return $response;
                        }
                    } else if ($promo_code[0]['repeat_usage'] == 0 && ($promo_code[0]['user_promo_usage_counter'] <= 0)) {
                        if (intval($promo_code[0]['user_promo_usage_counter']) <= intval($promo_code[0]['no_of_repeat_usage'])) {

                            $response['error'] = false;
                            $response['message'] = 'The promo code is valid';

                            if ($promo_code[0]['discount_type'] == 'percentage') {
                                $promo_code_discount =   floatval($final_total  * $promo_code[0]['discount'] / 100);
                            } else {
                                $promo_code_discount =  floatval($final_total - $promo_code[0]['discount']);
                            }
                            if ($promo_code_discount <= $promo_code[0]['max_discount_amount']) {
                                $total = (isset($promo_code[0]['is_cashback']) && $promo_code[0]['is_cashback'] == 0) ? floatval($final_total) - $promo_code_discount : floatval($final_total);
                            } else {
                                $total = (isset($promo_code[0]['is_cashback']) && $promo_code[0]['is_cashback'] == 0) ? floatval($final_total) - $promo_code[0]['max_discount_amount'] : floatval($final_total);
                                $promo_code_discount = $promo_code[0]['max_discount_amount'];
                            }
                            $promo_code[0]['final_total'] = strval(floatval($total));
                            $promo_code[0]['final_discount'] = strval(floatval($promo_code_discount));
                            $response['data'] = $promo_code;
                            return $response;
                        } else {

                            $response['error'] = true;
                            $response['message'] = 'This promo code cannot be redeemed as it exceeds the usage limit';
                            $response['data']['final_total'] = strval(floatval($final_total));
                            return $response;
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'The promo has already been redeemed. cannot be reused';
                        $response['data']['final_total'] = strval(floatval($final_total));
                        return $response;
                    }
                } else {

                    $response['error'] = true;
                    $response['message'] = 'This promo code is applicable only for amount greater than or equal to ' . $promo_code[0]['minimum_order_amount'];
                    $response['data']['final_total'] = strval(floatval($final_total));
                    return $response;
                }
            } else {

                $response['error'] = true;
                $response['message'] = "This promo code is applicable only for first " . $promo_code[0]['no_of_users'] . " users";
                $response['data']['final_total'] = strval(floatval($final_total));
                return $response;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'The promo code is not available or expired';
            $response['data']['final_total'] = strval(floatval($final_total));
            return $response;
        }
    }
}

//update_wallet_balance
function update_wallet_balance($operation, $user_id, $amount, $message = "Balance Debited", $order_item_id = "", $is_refund = 0, $transaction_type = 'wallet')
{

    $t = &get_instance();
    $user_balance = $t->db->select('balance')->where(['id' => $user_id])->get('users')->result_array();
    if (!empty($user_balance)) {
        if ($operation == 'debit' && $amount > $user_balance[0]['balance']) {
            $response['error'] = true;
            $response['message'] = "Debited amount can't exceeds the user balance !";
            $response['data'] = array();
            return $response;
        }

        if ($amount == 0) {
            $response['error'] = true;
            $response['message'] = "Amount can't be Zero !";
            $response['data'] = array();
            return $response;
        }

        if ($user_balance[0]['balance'] >= 0) {
            $t = &get_instance();
            $data = [
                'transaction_type' => $transaction_type,
                'user_id' => $user_id,
                'type' => $operation,
                'amount' => $amount,
                'message' => $message,
                'order_item_id' => $order_item_id,
                'is_refund' => $is_refund,
            ];
            $payment_data =  fetch_details('transactions', ['order_item_id' => $order_item_id], 'type');
            if ($operation == 'debit') {
                $data['message'] = (isset($message)) ? $message : 'Balance Debited';
                $data['type'] = 'debit';
                $t->db->set('balance', '`balance` - ' . $amount, false)->where('id', $user_id)->update('users');
            } else if ($operation == 'credit') {
                $data['message'] = (isset($message)) ? $message : 'Balance Credited';
                $data['type'] = 'credit';
                // $t->db->set('balance', '`balance` + ' . $amount, false)->where('id', $user_id)->update('users');
                if ($payment_data[0]['type'] != 'razorpay') {
                    $t->db->set('balance', '`balance` + ' . $amount, false)->where('id', $user_id)->update('users');
                }
            } else {
                $data['message'] = (isset($message)) ? $message : 'Balance refuned';
                $data['type'] = 'refund';
                if ($payment_data[0]['type'] != 'razorpay') {
                    $t->db->set('balance', '`balance` + ' . $amount, false)->where('id', $user_id)->update('users');
                }
            }
            $data = escape_array($data);
            $t->db->insert('transactions', $data);
            $response['error'] = false;
            $response['message'] = "Balance Update Successfully";
            $response['data'] = array();
        } else {
            $response['error'] = true;
            $response['message'] = ($user_balance[0]['balance'] != 0) ? "User's Wallet balance less than " . $user_balance[0]['balance'] . " can be used only" : "Doesn't have sufficient wallet balance to proceed further.";
            $response['data'] = array();
        }
    } else {
        $response['error'] = true;
        $response['message'] = "User does not exist";
        $response['data'] = array();
    }
    return $response;
}

function send_notification($fcmMsg, $registrationIDs_chunks)
{
    $fcmFields = [];
    foreach ($registrationIDs_chunks as $registrationIDs) {
        $fcmFields = array(
            'registration_ids' => $registrationIDs,  // expects an array of ids
            'priority' => 'high',
            'notification' => $fcmMsg,
            'data' => $fcmMsg,
        );

        $headers = array(
            'Authorization: key=' . get_settings('fcm_server_key'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
        curl_close($ch);
    }
    return $fcmFields;
}

function get_attribute_values_by_pid($id)
{
    $t = &get_instance();
    $swatche_type = $swatche_values1 =  array();
    $attribute_values = $t->db->select(" group_concat(`av`.`id`) as ids,group_concat(' ',`av`.`value`) as value ,`a`.`name` as attr_name, a.name, GROUP_CONCAT(av.swatche_type ORDER BY av.id ASC ) as swatche_type , GROUP_CONCAT(av.swatche_value  ) as swatche_value")
        ->join('attribute_values av ', 'FIND_IN_SET(av.id, pa.attribute_value_ids ) > 0', 'inner')
        ->join('attributes a', 'a.id = av.attribute_id', 'inner')
        ->where('pa.product_id', $id)->group_by('`a`.`name`')->get('product_attributes pa')->result_array();
    if (!empty($attribute_values)) {
        for ($i = 0; $i < count($attribute_values); $i++) {
            $swatche_type = array();
            $swatche_values1 = array();
            $swatche_type =  explode(",", $attribute_values[$i]['swatche_type']);
            $swatche_values =  explode(",", $attribute_values[$i]['swatche_value']);
            for ($j = 0; $j < count($swatche_type); $j++) {
                if ($swatche_type[$j] == "2") {
                    $swatche_values1[$j]  = get_image_url($swatche_values[$j], 'thumb', 'sm');
                } else if ($swatche_type[$j] == "0") {
                    $swatche_values1[$j] = '0';
                } else if ($swatche_type[$j] == "1") {
                    $swatche_values1[$j] = $swatche_values[$j];
                }
                $row = implode(',', $swatche_values1);
                $attribute_values[$i]['swatche_value'] = $row;
            }
            $attribute_values[$i] = output_escaping($attribute_values[$i]);
        }
    }
    return $attribute_values;
}

function get_attribute_values_by_id($id)
{
    $t = &get_instance();
    $attribute_values = $t->db->select(" GROUP_CONCAT(av.value  ORDER BY av.id ASC) as attribute_values ,GROUP_CONCAT(av.id ORDER BY av.id ASC ) as attribute_values_id ,a.name , GROUP_CONCAT(av.swatche_type ORDER BY av.id ASC ) as swatche_type , GROUP_CONCAT(av.swatche_value ORDER BY av.id ASC ) as swatche_value")
        ->join(' attributes a ', 'av.attribute_id = a.id ', 'inner')
        ->where_in('av.id', $id)->group_by('`a`.`name`')->get('attribute_values av')->result_array();
    if (!empty($attribute_values)) {
        for ($i = 0; $i < count($attribute_values); $i++) {
            if ($attribute_values[$i]['swatche_type'] != "") {
                $swatche_type = array();
                $swatche_values1 = array();
                $swatche_type =  explode(",", $attribute_values[$i]['swatche_type']);
                $swatche_values =  explode(",", $attribute_values[$i]['swatche_value']);

                for ($j = 0; $j < count($swatche_type); $j++) {
                    if ($swatche_type[$j] == "2") {
                        $swatche_values1[$j]  = get_image_url($swatche_values[$j], 'thumb', 'sm');
                    } else if ($swatche_type[$j] == "0") {
                        $swatche_values1[$j] = '0';
                    } else if ($swatche_type[$j] == "1") {
                        $swatche_values1[$j] = $swatche_values[$j];
                    }
                    $row = implode(',', $swatche_values1);
                    $attribute_values[$i]['swatche_value'] = $row;
                }
            }
            $attribute_values[$i] = output_escaping($attribute_values[$i]);
        }
    }
    return $attribute_values;
}

function get_variants_values_by_pid($id, $status = [1])
{


    $t = &get_instance();
    $varaint_values = $t->db->select("pv.*,pv.`product_id`,group_concat(`av`.`id`  ORDER BY av.id ASC) as variant_ids,group_concat( ' ' ,`a`.`name` ORDER BY av.id ASC) as attr_name, group_concat(`av`.`value` ORDER BY av.id ASC) as variant_values , pv.price as price , GROUP_CONCAT(av.swatche_type ORDER BY av.id ASC ) as swatche_type , GROUP_CONCAT(av.swatche_value ORDER BY av.id ASC ) as swatche_value")
        ->join('attribute_values av ', 'FIND_IN_SET(av.id, pv.attribute_value_ids ) > 0', 'left')
        ->join('attributes a', 'a.id = av.attribute_id', 'left')
        ->where(['pv.product_id' => $id])->where_in('pv.status', $status)->group_by('`pv`.`id`')->order_by('pv.id')->get('product_variants pv')->result_array();
    if (!empty($varaint_values)) {
        for ($i = 0; $i < count($varaint_values); $i++) {
            if ($varaint_values[$i]['swatche_type'] != "") {
                $swatche_type = array();
                $swatche_values1 = array();
                $swatche_type =  explode(",", $varaint_values[$i]['swatche_type']);
                $swatche_values =  explode(",", $varaint_values[$i]['swatche_value']);

                for ($j = 0; $j < count($swatche_type); $j++) {
                    if ($swatche_type[$j] == "2") {
                        $swatche_values1[$j]  = get_image_url($swatche_values[$j], 'thumb', 'sm');
                    } else if ($swatche_type[$j] == "0") {
                        $swatche_values1[$j] = '0';
                    } else if ($swatche_type[$j] == "1") {
                        $swatche_values1[$j] = $swatche_values[$j];
                    }
                    $row = implode(',', $swatche_values1);
                    $varaint_values[$i]['swatche_value'] = $row;
                }
            }
            $varaint_values[$i] = output_escaping($varaint_values[$i]);
            $varaint_values[$i]['availability'] = isset($varaint_values[$i]['availability']) && ($varaint_values[$i]['availability'] != "") ? $varaint_values[$i]['availability'] : '';
        }
    }
    return $varaint_values;
}

function get_variants_values_by_id($id)
{
    $t = &get_instance();
    $varaint_values = $t->db->select("pv.*,pv.`product_id`,group_concat(`av`.`id` separator ', ') as varaint_ids,group_concat(`a`.`name` separator ', ') as attr_name, group_concat(`av`.`value` separator ', ') as variant_values")
        ->join('attribute_values av ', 'FIND_IN_SET(av.id, pv.attribute_value_ids ) > 0', 'inner')
        ->join('attributes a', 'a.id = av.attribute_id', 'inner')
        ->where('pv.id', $id)->group_by('`pv`.`id`')->order_by('pv.id')->get('product_variants pv')->result_array();
    if (!empty($varaint_values)) {
        for ($i = 0; $i < count($varaint_values); $i++) {
            $varaint_values[$i] = output_escaping($varaint_values[$i]);
            $varaint_values[$i]['availability'] = isset($varaint_values[$i]['availability']) && ($varaint_values[$i]['availability'] != "") ? $varaint_values[$i]['availability'] : '';
            $varaint_values[$i]['images'] = isset($varaint_values[$i]['images']) && (!empty($varaint_values[$i]['images'])) ? $varaint_values[$i]['images'] : '';
        }
    }
    return $varaint_values;
}

//Used in form validation(API)
function userrating_check()
{
    $t = &get_instance();
    $user_id = $t->input->post('user_id', true);
    $product_id = $t->input->post('product_id', true);
    $res = $t->db->select('*')->where(['user_id' => $user_id, 'product_id' => $product_id])->get('product_rating');
    if ($res->num_rows() > 0) {
        return false;
    } else {
        return true;
    }
}

//update_stock()
function update_stock($product_variant_ids, $qtns, $type = '')
{
    /*
		--First Check => Is stock management active (Stock type != NULL) 
		Case 1 : Simple Product 		
		Case 2 : Variable Product (Product Level,Variant Level) 			

		Stock Type :
			0 => Simple Product(simple product)
			  	-Stock will be stored in (product)master table	
			1 => Product level(variable product)
				-Stock will be stored in product_variant table	
			2 => Variant level(variable product)		
				-Stock will be stored in product_variant table	
		*/
    $t = &get_instance();
    $res = $t->db->select('p.*,pv.*,p.id as p_id,pv.id as pv_id,p.stock as p_stock,pv.stock as pv_stock')->where_in('pv.id', $product_variant_ids)->join('products p', 'pv.product_id = p.id')->get('product_variants pv')->result_array();

    for ($i = 0; $i < count($res); $i++) {
        if (($res[$i]['stock_type'] != null || $res[$i]['stock_type'] != "")) {

            /* Case 1 : Simple Product(simple product) */
            if ($res[$i]['stock_type'] == 0) {
                if ($type == 'plus') {
                    if ($res[$i]['p_stock'] != null) {
                        $stock = intval($res[$i]['p_stock']) + intval($qtns[$i]);
                        $t->db->where('id', $res[$i]['p_id'])->update('products', ['stock' => $stock]);
                        if ($stock > 0) {
                            $t->db->where('id', $res[$i]['p_id'])->update('products', ['availability' => '1']);
                        }
                    }
                } else {
                    if ($res[$i]['p_stock'] != null && $res[$i]['p_stock'] > 0) {
                        $stock = intval($res[$i]['p_stock']) - intval($qtns[$i]);
                        $t->db->where('id', $res[$i]['p_id'])->update('products', ['stock' => $stock]);
                        if ($stock == 0) {
                            $t->db->where('id', $res[$i]['p_id'])->update('products', ['availability' => '0']);
                        }
                    }
                }
            }

            /* Case 2 : Product level(variable product) */
            if ($res[$i]['stock_type'] == 1) {
                if ($type == 'plus') {
                    if ($res[$i]['pv_stock'] != null) {
                        $stock = intval($res[$i]['pv_stock']) + intval($qtns[$i]);
                        $t->db->where('product_id', $res[$i]['p_id'])->update('product_variants', ['stock' => $stock]);
                        if ($stock > 0) {
                            $t->db->where('product_id', $res[$i]['p_id'])->update('product_variants', ['availability' => '1']);
                        }
                    }
                } else {
                    if ($res[$i]['pv_stock'] != null && $res[$i]['pv_stock'] > 0) {
                        $stock = intval($res[$i]['pv_stock']) - intval($qtns[$i]);
                        $t->db->where('product_id', $res[$i]['p_id'])->update('product_variants', ['stock' => $stock]);
                        if ($stock == 0) {
                            $t->db->where('product_id', $res[$i]['p_id'])->update('product_variants', ['availability' => '0']);
                        }
                    }
                }
            }

            /* Case 3 : Variant level(variable product) */
            if ($res[$i]['stock_type'] == 2) {
                if ($type == 'plus') {
                    if ($res[$i]['pv_stock'] != null) {

                        $stock = intval($res[$i]['pv_stock']) + intval($qtns[$i]);
                        $t->db->where('id', $res[$i]['id'])->update('product_variants', ['stock' => $stock]);
                        if ($stock > 0) {
                            $t->db->where('id', $res[$i]['id'])->update('product_variants', ['availability' => '1']);
                        }
                    }
                } else {
                    if ($res[$i]['pv_stock'] != null && $res[$i]['pv_stock'] > 0) {

                        $stock = intval($res[$i]['pv_stock']) - intval($qtns[$i]);
                        $t->db->where('id', $res[$i]['id'])->update('product_variants', ['stock' => $stock]);
                        if ($stock == 0) {
                            $t->db->where('id', $res[$i]['id'])->update('product_variants', ['availability' => '0']);
                        }
                    }
                }
            }
        }
    }
}

function validate_stock($product_variant_ids, $qtns)
{
    /*
		--First Check => Is stock management active (Stock type != NULL) 
		Case 1 : Simple Product 		
		Case 2 : Variable Product (Product Level,Variant Level) 			

		Stock Type :
			0 => Simple Product(simple product)
			  	-Stock will be stored in (product)master table	
			1 => Product level(variable product)
				-Stock will be stored in product_variant table	
			2 => Variant level(variable product)		
				-Stock will be stored in product_variant table	
		*/
    $t = &get_instance();
    $response = array();
    $is_exceed_allowed_quantity_limit = false;
    $error = false;
    $count = isset($product_variant_ids) ? count($product_variant_ids) : '';
    for ($i = 0; $i < $count; $i++) {
        $res = $t->db->select('p.*,pv.*,pv.id as pv_id,p.stock as p_stock,p.availability as p_availability,pv.stock as pv_stock,pv.availability as pv_availability')->where('pv.id = ', $product_variant_ids[$i])->join('products p', 'pv.product_id = p.id')->get('product_variants pv')->result_array();
        if ($res[0]['total_allowed_quantity'] != null && $res[0]['total_allowed_quantity'] >= 0) {
            $total_allowed_quantity = intval($res[0]['total_allowed_quantity']) - intval($qtns[$i]);
            if ($total_allowed_quantity < 0) {
                $error = true;
                $is_exceed_allowed_quantity_limit = true;
                break;
            }
        }

        if (($res[0]['stock_type'] != null && $res[0]['stock_type'] != '')) {
            //Case 1 : Simple Product(simple product)
            if ($res[0]['stock_type'] == 0) {
                if ($res[0]['p_stock'] != null && $res[0]['p_stock'] != '') {
                    $stock = intval($res[0]['p_stock']) - intval($qtns[$i]);
                    if ($stock < 0 || $res[0]['p_availability'] == 0) {
                        $error = true;
                        break;
                    }
                }
            }
            //Case 2 & 3 : Product level(variable product) ||  Variant level(variable product)
            if ($res[0]['stock_type'] == 1 || $res[0]['stock_type'] == 2) {
                if ($res[0]['pv_stock'] != null && $res[0]['pv_stock'] != '') {
                    $stock = intval($res[0]['pv_stock']) - intval($qtns[$i]);
                    if ($stock < 0 || $res[0]['pv_availability'] == 0) {
                        $error = true;
                        break;
                    }
                }
            }
        }
    }

    if ($error) {
        $response['error'] = true;
        if ($is_exceed_allowed_quantity_limit) {
            $response['message'] = "One of the products quantity exceeds the allowed limit.Please deduct some quanity in order to purchase the item";
        } else {
            $response['message'] = "One of the product is out of stock.";
        }
    } else {
        $response['error'] = false;
        $response['message'] = "Stock available for purchasing.";
    }
    return $response;
}

//stock_status()
function stock_status($product_variant_id)
{
    /*
		--First Check => Is stock management active (Stock type != NULL) 
		Case 1 : Simple Product 		
		Case 2 : Variable Product (Product Level,Variant Level) 			

		Stock Type :
			0 => Simple Product(simple product)
			  	-Stock will be stored in (product)master table	
			1 => Product level(variable product)
				-Stock will be stored in product_variant table	
			2 => Variant level(variable product)		
				-Stock will be stored in product_variant table	
		*/
    $t = &get_instance();
    $res = $t->db->select('p.*,pv.*,pv.id as pv_id,p.stock as p_stock,pv.stock as pv_stock')->where_in('pv.id', $product_variant_id)->join('products p', 'pv.product_id = p.id')->get('product_variants pv')->result_array();
    $out_of_stock = false;
    for ($i = 0; $i < count($res); $i++) {
        if (($res[$i]['stock_type'] != null && !empty($res[$i]['stock_type']))) {
            //Case 1 : Simple Product(simple product)
            if ($res[$i]['stock_type'] == 0) {

                if ($res[$i]['p_stock'] == null || $res[$i]['p_stock'] == 0) {
                    $out_of_stock = true;
                    break;
                }
            }
            //Case 2 & 3 : Product level(variable product) ||  Variant level(variable product)
            if ($res[$i]['stock_type'] == 1 || $res[$i]['stock_type'] == 2) {
                if ($res[$i]['pv_stock'] == null || $res[$i]['pv_stock'] == 0) {
                    $out_of_stock = true;
                    break;
                }
            }
        }
    }
    return $out_of_stock;
}

//verify_user()
function verify_user($data)
{
    $t = &get_instance();
    $res = $t->db->where('mobile', $data['mobile'])->get('users')->result_array();
    return $res;
}

//edit_unique($value, $params)
function edit_unique($value, $params)
{
    $CI = &get_instance();

    $CI->form_validation->set_message('edit_unique', "Sorry, that %s is already being used.");

    list($table, $field, $current_id) = explode(".", $params);

    $query = $CI->db->select()->from($table)->where($field, $value)->limit(1)->get();
    if ($query->row() && $query->row()->id != $current_id) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function validate_order_status($order_ids, $status, $table = 'order_items', $user_id = null)
{
    $t = &get_instance();
    $error = 0;
    $cancelable_till = '';
    $returnable_till = '';
    $is_already_returned = 0;
    $is_already_cancelled = 0;
    $is_returnable = 0;
    $is_cancelable = 0;
    $returnable_count = 0;
    $cancelable_count = 0;
    $return_request = 0;
    $check_status = ['received', 'processed', 'shipped', 'delivered', 'cancelled', 'returned'];
    $group = array('admin', 'delivery_boy');
    if (in_array(strtolower(trim($status)), $check_status)) {
        if ($table == 'order_items') {
            $t->db->select('active_status');
            $t->db->where_in('id', explode(',', $order_ids));
            $active_status = $t->db->get('order_items')->result_array();
            $active_status = array_column($active_status, 'active_status');
            if (in_array("cancelled", $active_status) || in_array("returned", $active_status)) {
                $response['error'] = true;
                $response['message'] = "You can't update status once item cancelled / returned";
                $response['data'] = array();
                return $response;
            }
        }

        $t->db->select('p.*,oi.active_status,pv.*,oi.id as order_item_id,oi.user_id as user_id,oi.product_variant_id as product_variant_id,oi.order_id as order_id, oi.status as order_item_status')
            ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
            ->join('products p', 'pv.product_id=p.id', 'left');
        if ($table == 'orders') {
            $t->db->where('oi.order_id', $order_ids);
        } else {
            $t->db->where_in('oi.id', explode(',', $order_ids));
        }
        $product_data = $t->db->get('order_items oi')->result_array();

        $priority_status = [
            'received' => 0,
            'processed' => 1,
            'shipped' => 2,
            'delivered' => 3,
            'cancelled' => 4,
            'returned' => 5,
        ];

        $is_posted_status_set = $canceling_delivered_item = $returning_non_delivered_item = false;
        $is_posted_status_set_count = 0;
        for ($i = 0; $i < count($product_data); $i++) {
            /* check if there are any products returnable or cancellable products available in the list or not */
            if ($product_data[$i]['is_returnable'] == 1) {
                $returnable_count += 1;
            }
            if ($product_data[$i]['is_cancelable'] == 1) {
                $cancelable_count += 1;
            }

            /* check if the posted status is present in any of the variants */
            $product_data[$i]['order_item_status'] = json_decode($product_data[$i]['order_item_status'], true);
            $order_item_status = array_column($product_data[$i]['order_item_status'], '0');

            /* check if posted status is already present in how many of the order items */
            if (in_array($status, $order_item_status)) {
                $is_posted_status_set_count++;
            }
            /* if all are marked as same as posted status set the flag */
            if ($is_posted_status_set_count == count($product_data)) {
                $is_posted_status_set = true;
            }

            /* check if user is cancelling the order after it is delivered */
            if (($status == "cancelled") && (in_array("delivered", $order_item_status) || in_array("returned", $order_item_status))) {
                $canceling_delivered_item = true;
            }

            /* check if user is returning non delivered item */
            if (($status == "returned") && !in_array("delivered", $order_item_status)) {
                $returning_non_delivered_item = true;
            }
        }

        if ($is_posted_status_set == true) {
            /* status posted is already present in any of the order item */
            $response['error'] = true;
            $response['message'] = "Order is already marked as $status. You cannot set it again!";
            $response['data'] = array();
            return $response;
        }

        if ($canceling_delivered_item == true) {
            /* when user is trying cancel delivered order / item */
            $response['error'] = true;
            $response['message'] = "You cannot cancel delivered or returned order / item. You can only return that!";
            $response['data'] = array();
            return $response;
        }
        if ($returning_non_delivered_item == true) {
            /* when user is trying return non delivered order / item */
            $response['error'] = true;
            $response['message'] = "You cannot return a non-delivered order / item. First it has to be marked as delivered and then you can return it!";
            $response['data'] = array();
            return $response;
        }

        $is_returnable = ($returnable_count >= 1) ? 1 : 0;
        $is_cancelable = ($cancelable_count >= 1) ? 1 : 0;

        for ($i = 0; $i < count($product_data); $i++) {
            if ($product_data[$i]['active_status'] == 'returned') {
                $error = 1;
                $is_already_returned = 1;
                break;
            }

            if ($product_data[$i]['active_status'] == 'cancelled') {
                $error = 1;
                $is_already_cancelled = 1;
                break;
            }

            if ($status == 'returned' && $product_data[$i]['is_returnable'] == 0) {
                $error = 1;
                break;
            }

            if ($status == 'returned' && $product_data[$i]['is_returnable'] == 1 && $priority_status[$product_data[$i]['active_status']] < 3) {
                $error = 1;
                $returnable_till = 'delivery';
                break;
            }

            if ($status == 'cancelled' && $product_data[$i]['is_cancelable'] == 1) {
                $max = $priority_status[$product_data[$i]['cancelable_till']];
                $min = $priority_status[$product_data[$i]['active_status']];

                if ($min > $max) {
                    $error = 1;
                    $cancelable_till = $product_data[$i]['cancelable_till'];
                    break;
                }
            }

            if ($status == 'cancelled' && $product_data[$i]['is_cancelable'] == 0) {
                $error = 1;
                break;
            }
        }

        if ($status == 'returned'  && $error == 1 && !empty($returnable_till)) {
            $response['error'] = true;
            $response['message'] = (count($product_data) > 1) ? "One of the order item is not delivered yet !" : "The order item is not delivered yet !";
            $response['data'] = array();
            return $response;
        }
        if ($status == 'returned'  && $error == 1 && !$t->ion_auth->logged_in() && !$t->ion_auth->in_group($group, $user_id)) {
            $response['error'] = true;
            $response['message'] = (count($product_data) > 1) ? "One of the order item can't be returned !" : "The order item can't be returned !";
            $response['data'] = $product_data;
            return $response;
        }

        if ($status == 'cancelled' && $error == 1 && !empty($cancelable_till) && !$t->ion_auth->logged_in() && !$t->ion_auth->in_group($group, $user_id)) {
            $response['error'] = true;
            $response['message'] = (count($product_data) > 1) ? " One of the order item can be cancelled till " . $cancelable_till . " only " : "The order item can be cancelled till " . $cancelable_till . " only";
            $response['data'] = array();
            return $response;
        }

        if ($status == 'cancelled' && $error == 1 && !$t->ion_auth->logged_in() && !$t->ion_auth->in_group($group, $user_id)) {
            $response['error'] = true;
            $response['message'] = (count($product_data) > 1) ? "One of the order item can't be cancelled !" : "The order item can't be cancelled !";
            $response['data'] = array();
            return $response;
        }

        for ($i = 0; $i < count($product_data); $i++) {

            if ($status == 'returned' && $product_data[$i]['is_returnable'] == 1 && $error == 0) {
                $error = 1;
                $return_request_flag = 1;

                $return_status = [
                    'is_already_returned' =>  $is_already_returned,
                    'is_already_cancelled' =>  $is_already_cancelled,
                    'return_request_submitted' =>  $return_request,
                    'is_returnable' =>  $is_returnable,
                    'is_cancelable' =>  $is_cancelable,
                ];

                if ($table == 'order_items') {
                    if (is_exist(['user_id' => $product_data[$i]['user_id'], 'order_item_id' => $product_data[$i]['order_item_id'], 'order_id' => $product_data[$i]['order_id']], 'return_requests')) {

                        $response['error'] = true;
                        $response['message'] =  "Return request already submitted !";
                        $response['data'] = array();
                        $response['return_status'] =  $return_status;
                        return $response;
                    }
                    $request_data_item_data = $product_data[$i];
                    set_user_return_request($request_data_item_data, $table);
                } else {
                    for ($j = 0; $j < count($product_data); $j++) {
                        if (is_exist(['user_id' => $product_data[$i]['user_id'], 'order_item_id' => $product_data[$i]['order_item_id'], 'order_id' => $product_data[$i]['order_id']], 'return_requests')) {

                            $response['error'] = true;
                            $response['message'] =  "Return request already submitted !";
                            $response['data'] = array();
                            $response['return_status'] =  $return_status;
                            return $response;
                        }
                    }
                    $request_data_overall_item_data = $product_data;
                    set_user_return_request($request_data_overall_item_data, $table);
                }

                $response['error'] = false;
                $response['message'] =  "Return request submitted successfully !";
                $response['return_request_flag'] =  1;
                $response['data'] = array();
                return $response;
            }
        }

        $response['error'] = false;
        $response['message'] = " ";
        $response['data'] = array();

        return $response;
    } else {
        $response['error'] = true;
        $response['message'] = "Invalid Status Passed";
        $response['data'] = array();
        return $response;
    }
}

function is_exist($where, $table, $update_id = null)
{
    $t = &get_instance();
    $where_tmp = [];
    foreach ($where as $key => $val) {
        $where_tmp[$key] = $val;
    }

    if (($update_id == null)  ? $t->db->where($where_tmp)->get($table)->num_rows() > 0 : $t->db->where($where_tmp)->where_not_in('id', $update_id)->get($table)->num_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

function set_user_return_request($data, $table = 'orders')
{
    $data = escape_array($data);

    $t = &get_instance();

    if ($table == 'orders') {
        for ($i = 0; $i < count($data); $i++) {
            $request_data = [
                'user_id' => $data[$i]['user_id'],
                'product_id' => $data[$i]['product_id'],
                'product_variant_id' => $data[$i]['product_variant_id'],
                'order_id' => $data[$i]['order_id'],
                'order_item_id' => $data[$i]['order_item_id']
            ];
            $t->db->insert('return_requests', $request_data);
        }
    } else {
        $request_data = [
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id'],
            'product_variant_id' => $data['product_variant_id'],
            'order_id' => $data['order_id'],
            'order_item_id' => $data['order_item_id']
        ];
        $t->db->insert('return_requests', $request_data);
    }
}

function get_categories_option_html($categories, $selected_vals = null)
{
    $html = "";
    for ($i = 0; $i < count($categories); $i++) {
        $pre_selected = (!empty($selected_vals) && in_array($categories[$i]['id'], $selected_vals)) ? "selected" : "";
        $html .= '<option value="' . $categories[$i]['id'] . '" class="l' . $categories[$i]['level'] . '" ' . $pre_selected . '  >' . output_escaping($categories[$i]['name']) . '</option>';
        if (!empty($categories[$i]['children'])) {
            $html .= get_subcategory_option_html($categories[$i]['children'], $selected_vals);
        }
    }

    return $html;
}

function get_subcategory_option_html($subcategories, $selected_vals)
{
    $html = "";
    for ($i = 0; $i < count($subcategories); $i++) {
        $pre_selected = (!empty($selected_vals) && in_array($subcategories[$i]['id'], $selected_vals)) ? "selected" : "";
        $html .= '<option value="' . $subcategories[$i]['id'] . '" class="l' . $subcategories[$i]['level'] . '" ' . $pre_selected . '  >' . $subcategories[$i]['name'] . '</option>';
        if (!empty($subcategories[$i]['children'])) {
            $html .=  get_subcategory_option_html($subcategories[$i]['children'], $selected_vals);
        }
    }
    return $html;
}

function get_cart_total($user_id, $product_variant_id = false, $is_saved_for_later = '0', $address_id = '')
{
    $t = &get_instance();
    $t->db->select('(select sum(c.qty)  from cart c join product_variants pv on c.product_variant_id=pv.id join products p on p.id=pv.product_id join seller_data sd on sd.user_id=p.seller_id  where c.user_id="' . $user_id . '" and qty!=0  and  is_saved_for_later = "' . $is_saved_for_later . '" and p.status=1 AND pv.status=1 AND sd.status=1) as total_items,(select count(c.id) from cart c join product_variants pv on c.product_variant_id=pv.id join products p on p.id=pv.product_id join seller_data sd on sd.user_id=p.seller_id where c.user_id="' . $user_id . '" and qty!=0 and  is_saved_for_later = "' . $is_saved_for_later . '" and p.status=1 AND pv.status=1 AND sd.status=1) as cart_count,`c`.qty,p.is_prices_inclusive_tax,p.cod_allowed,p.type,p.download_allowed,p.minimum_order_quantity,p.slug,p.quantity_step_size,p.total_allowed_quantity, p.name, p.image,p.short_description,`c`.user_id,pv.*,tax.percentage as tax_percentage,tax.title as tax_title');

    if ($product_variant_id == true) {
        $t->db->where(['c.product_variant_id' => $product_variant_id, 'c.user_id' => $user_id, 'c.qty !=' => '0']);
    } else {
        $t->db->where(['c.user_id' => $user_id, 'c.qty !=' => '0']);
    }

    if ($is_saved_for_later == 0) {
        $t->db->where('is_saved_for_later', 0);
    } else {
        $t->db->where('is_saved_for_later', 1);
    }

    $t->db->join('product_variants pv', 'pv.id=c.product_variant_id');
    $t->db->join('products p ', 'pv.product_id=p.id');
    $t->db->join('seller_data sd ', 'sd.user_id=p.seller_id');
    $t->db->join('`taxes` tax', 'tax.id = p.tax', 'LEFT');
    $t->db->join('categories ctg', 'p.category_id = ctg.id', 'left');
    $t->db->where(['p.status' => '1', 'pv.status' => 1, 'sd.status' => 1]);
    $t->db->group_by('c.id')->order_by('c.id', "DESC");
    $data = $t->db->get('cart c')->result_array();
    $total = array();
    $variant_id = array();
    $quantity = array();
    $percentage = array();
    $amount = array();
    $cod_allowed = 1;
    $download_allowed = array();
    for ($i = 0; $i < count($data); $i++) {

        $tax_title = (isset($data[$i]['tax_title']) && !empty($data[$i]['tax_title'])) ? $data[$i]['tax_title'] : '';
        $prctg = (isset($data[$i]['tax_percentage']) && intval($data[$i]['tax_percentage']) > 0 && $data[$i]['tax_percentage'] != null) ? $data[$i]['tax_percentage'] : '0';
        $data[$i]['item_tax_percentage'] = $prctg;
        $data[$i]['tax_title'] = $tax_title;
        if ((isset($data[$i]['is_prices_inclusive_tax']) && $data[$i]['is_prices_inclusive_tax'] == 0) || (!isset($data[$i]['is_prices_inclusive_tax'])) && $prctg > 0) {
            $price_tax_amount = $data[$i]['price'] * ($prctg / 100);
            $special_price_tax_amount = $data[$i]['special_price'] * ($prctg / 100);
        } else {
            $price_tax_amount = 0;
            $special_price_tax_amount = 0;
        }
        $data[$i]['image_sm'] = get_image_url($data[$i]['image'], 'thumb', 'sm');
        $data[$i]['image_md'] = get_image_url($data[$i]['image'], 'thumb', 'md');
        $data[$i]['image'] = get_image_url($data[$i]['image']);
        if ($data[$i]['cod_allowed'] == 0) {
            $cod_allowed = 0;
        }
        $variant_id[$i] = $data[$i]['id'];
        $quantity[$i] = intval($data[$i]['qty']);

        if (floatval($data[$i]['special_price']) > 0) {
            $total[$i] = floatval($data[$i]['special_price'] + $special_price_tax_amount) * $data[$i]['qty'];
        } else {
            $total[$i] = floatval($data[$i]['price'] + $price_tax_amount) * $data[$i]['qty'];
        }
        $data[$i]['special_price'] = $data[$i]['special_price'] + $special_price_tax_amount;
        $data[$i]['price'] = $data[$i]['price'] + $price_tax_amount;

        $percentage[$i] = (isset($data[$i]['tax_percentage']) && floatval($data[$i]['tax_percentage']) > 0) ? $data[$i]['tax_percentage'] : 0;
        if ($percentage[$i] != NUll && $percentage[$i] > 0) {
            $amount[$i] = (!empty($special_price_tax_amount)) ? $special_price_tax_amount : $price_tax_amount;
        } else {
            $amount[$i] = 0;
            $percentage[$i] = 0;
        }

        $data[$i]['product_variants'] = get_variants_values_by_id($data[$i]['id']);
        array_push($download_allowed, $data[$i]['download_allowed']);
    }
    $total = array_sum($total);

    $system_settings = get_settings('system_settings', true);
    $delivery_charge = $system_settings['delivery_charge'];
    if (!empty($address_id)) {
        $delivery_charge = get_delivery_charge($address_id, $total);
    }
    $delivery_charge = str_replace(",", "", $delivery_charge);
    $overall_amt = 0;
    $tax_amount = array_sum($amount);
    $overall_amt = $total + $delivery_charge;
    $data[0]['is_cod_allowed'] = $cod_allowed;
    $data['sub_total'] = strval($total);
    $data['quantity'] = strval(array_sum($quantity));
    $data['tax_percentage'] = strval(array_sum($percentage));
    $data['tax_amount'] = strval(array_sum($amount));
    $data['total_arr'] = $total;
    $data['variant_id'] = $variant_id;
    $data['delivery_charge'] = $delivery_charge;
    $data['overall_amount'] = strval($overall_amt);
    $data['amount_inclusive_tax'] = strval($overall_amt + $tax_amount);
    $data['download_allowed'] = $download_allowed;
    return $data;
}

function get_frontend_categories_html()
{
    $t = &get_instance();
    $t->load->model('category_model');

    $limit =  8;
    $offset =  0;
    $sort = 'row_order';
    $order =  'ASC';
    $has_child_or_item = 'false';


    $categories = $t->category_model->get_categories('', $limit, $offset, $sort, $order, trim($has_child_or_item));
    $nav = '<div class="cd-morph-dropdown"><a href="#0" class="nav-trigger">Open Nav<span aria-hidden="true"></span></a><nav class="main-nav"><ul>';
    $html = "<div class='morph-dropdown-wrapper'><div class='dropdown-list'><ul>";

    for ($i = 0; $i < count($categories); $i++) {
        $nav .= '<li class="has-dropdown" data-content="' . str_replace(' ', '', str_replace('&', '-', trim(strtolower(strip_tags(str_replace('\'', '', $categories[$i]['name'])))))) . '">';
        $nav .= '<a href="' . base_url('products/category/' . $categories[$i]['slug']) . '">' . Ucfirst($categories[$i]['name']) . '</a></li>';
        $html .= "<li id='" . str_replace(' ', '', str_replace('&', '-', trim(strtolower(strip_tags($categories[$i]['name']))))) . "' class='dropdown'> <a href='#0' class='label'>" . $categories[$i]['name'] . "</a><div class='content'><ul>";

        if (!empty($categories[$i]['children'])) {
            $html .= get_frontend_subcategories_html($categories[$i]['children']);
        }
        $html .= "</ul></div>";
    }
    $nav .= '<li><a href="' . base_url('home/categories') . '">See All</a></li>';
    $html .= "</ul><div class='bg-layer' aria-hidden='true'></div></div></div></div>";
    $nav .= '</ul></nav>';
    return $nav . $html;
}

function get_frontend_subcategories_html($subcategories)
{
    $html = "";

    for ($i = 0; $i < count($subcategories); $i++) {
        $html .= "<li><a href='#0'>" . $subcategories[$i]['name'] . "</a>";
        if (!empty($subcategories[$i]['children'])) {
            $html .= '<ul>' . get_frontend_subcategories_html($subcategories[$i]['children']) . '</ul>';
        }
        $html .= "</li>";
    }

    return $html;
}

function resize_image($image_data, $source_path, $id = false)
{
    if ($image_data['is_image']) {

        $t = &get_instance();

        $image_type = ['thumb', 'cropped'];
        $image_size = ['md' => array('width' => 800, 'height' => 800), 'sm' => array('width' => 450, 'height' => 450)];
        $target_path = $source_path; // Target path will be under source path
        $image_name = $image_data['file_name']; // original image's name    
        $w = $image_data['image_width']; // original image's width    
        $h = $image_data['image_height']; // original images's height 

        $t->load->library('image_lib');

        if ($id != false && is_numeric($id)) {
            // Resize the original images            
            $config['maintain_ratio'] = true;
            $config['create_thumb'] = FALSE;
            $config['source_image'] =  $source_path . $image_name;
            $config['new_image'] = $target_path . $image_name;
            $config['quality'] = '80%';
            $config['width'] = $w - 1;
            $config['height'] = $h - 1;
            $t->image_lib->initialize($config);
            if ($t->image_lib->resize()) {

                $size = filesize($config['new_image']);
                update_details(['size' => $size], ['id' => $id], 'media');
            } else {
                return $t->image_lib->display_errors();
            }
            $t->image_lib->clear();
        }

        for ($i = 0; $i < count($image_type); $i++) {

            if (file_exists($source_path . $image_name)) {  //check if the image file exist 
                foreach ($image_size as $image_size_key => $image_size_value) {
                    if (!file_exists($target_path . $image_type[$i] . '-' . $image_size_key)) {
                        mkdir($target_path . $image_type[$i] . '-' . $image_size_key, 0777);
                    }

                    $n_w = $image_size_value['width']; // destination image's width //800
                    $n_h = $image_size_value['height']; // destination image's height //800
                    $config['image_library'] = 'gd2';
                    $config['create_thumb'] = FALSE;
                    $config['source_image'] =  $source_path . $image_name;
                    $config['new_image'] = $target_path . $image_type[$i] . '-' . $image_size_key . '/' . $image_name;
                    if (($w >= $n_w || $h >= $n_h) && $image_type[$i] == 'cropped') {
                        $y = date('Y');
                        $thumb_type = ($image_size_key == 'sm') ? 'thumb-sm/' : 'thumb-md/';
                        $thumb_path = $source_path . $thumb_type . $image_name;

                        $data = getimagesize($thumb_path);
                        $width = $data[0];
                        $height = $data[1];
                        $config['source_image'] = (file_exists($thumb_path)) ?  $thumb_path : $image_name;

                        /*  x-axis : (left)   
                        width : (right)   
                        y-axis : (top)    
                        height : (bottom) */
                        $config['maintain_ratio'] = false;

                        if ($width > $height) {
                            $config['width'] = $height;
                            $config['height'] = round($height);
                            $config['x_axis'] = (($width / 4) - ($n_w / 4));
                        } else {
                            $config['width'] = $width;
                            $config['height'] = $width;
                            $config['y_axis'] = (($height / 4) - ($n_h / 4));
                        }

                        $t->image_lib->initialize($config);
                        $t->image_lib->crop();
                        $t->image_lib->clear();
                    }

                    if (($w >= $n_w || $h >= $n_h) && $image_type[$i] == 'thumb') {
                        $config['maintain_ratio'] = true;
                        $config['create_thumb'] = FALSE;
                        $config['width'] = $n_w;
                        $config['height'] = $n_h;
                        $t->image_lib->initialize($config);
                        if (!$t->image_lib->resize()) {
                            return $t->image_lib->display_errors();
                        }
                        $t->image_lib->clear();
                    }
                }
            }
        }
    }
}

function get_user_permissions($id)
{
    $userData = fetch_details('user_permissions', ['user_id' => $id]);
    return $userData;
}

function has_permissions($role, $module)
{
    $role = trim($role);
    $module = trim($module);

    if (!is_modification_allowed($module) && in_array($role, ['create', 'update', 'delete'])) {
        return false; //Modification not allowed
    }
    $t = &get_instance();
    $id = $t->session->userdata('user_id');
    $t->load->config('eshop');
    $general_system_permissions  = $t->config->item('system_modules');
    $userData = get_user_permissions($id);
    if (!empty($userData)) {

        if (intval($userData[0]['role']) > 0) {
            $permissions = json_decode($userData[0]['permissions'], 1);
            if (array_key_exists($module, $general_system_permissions) && array_key_exists($module, $permissions)) {
                if (array_key_exists($module, $permissions)) {
                    if (in_array($role, $general_system_permissions[$module])) {
                        if (!array_key_exists($role, $permissions[$module])) {
                            return false; //User has no permission
                        }
                    }
                }
            } else {
                return false; //User has no permission
            }
        }
        return true; //User has permission
    }
}


function print_msg($error, $message, $module = false, $is_csrf_enabled = true)
{
    $t = &get_instance();
    if ($error) {

        $response['error'] = true;
        $response['message'] = (is_modification_allowed($module)) ? $message : DEMO_VERSION_MSG;
        if ($is_csrf_enabled) {
            $response['csrfName'] = $t->security->get_csrf_token_name();
            $response['csrfHash'] = $t->security->get_csrf_hash();
        }
        print_r(json_encode($response));
        return true;
    }
}

function get_system_update_info()
{
    $t = &get_instance();
    $db_version_data = $t->db->from('updates')->order_by("id", "desc")->get()->result_array();
    if (!empty($db_version_data) && isset($db_version_data[0]['version'])) {
        $db_current_version = $db_version_data[0]['version'];
    }
    if ($t->db->table_exists('updates') && !empty($db_current_version)) {
        $data['db_current_version'] = $db_current_version;
    } else {
        $data['db_current_version'] = $db_current_version = 1.0;
    }

    if (file_exists(UPDATE_PATH . "update/updater.txt") || file_exists(UPDATE_PATH . "updater.txt")) {
        $sub_directory = (file_exists(UPDATE_PATH . "update/folders.json")) ? "update/" : "";
        $lines_array = file(UPDATE_PATH . $sub_directory . "updater.txt");

        $search_string = "version";

        foreach ($lines_array as $line) {
            if (strpos($line, $search_string) !== false) {
                list(, $new_str) = explode(":", $line);
                // If you don't want the space before the word bong, uncomment the following line.
                $new_str = trim($new_str);
            }
        }
        $data['file_current_version'] = $file_current_version = $new_str;
    } else {
        $data['file_current_version'] = $file_current_version = false;
    }

    if ($file_current_version != false && $file_current_version > $db_current_version) {

        $data['is_updatable'] =  true;
    } else {
        $data['is_updatable'] =  false;
    }

    return $data;
}

function send_mail($to, $subject, $message)
{
    $t = &get_instance();
    $settings = get_settings('system_settings', true);
    $t->load->library('email');
    $config = $t->config->item('email_config');
    $t->email->initialize($config);
    $t->email->set_newline("\r\n");

    $t->email->from($config['smtp_user'], $settings['app_name']);
    $t->email->to($to);
    $t->email->subject($subject);
    $t->email->message($message);
    if ($t->email->send()) {
        $response['error'] = false;
        $response['config'] = $config;
        $response['message'] = 'Email Sent';
    } else {
        $response['error'] = true;
        $response['config'] = $config;
        $response['message'] = $t->email->print_debugger();
    }

    return $response;
}
function send_digital_product_mail($to, $subject, $message, $attachment)
{
    $t = &get_instance();
    $settings = get_settings('system_settings', true);
    $t->load->library('email');
    $config = $t->config->item('email_config');
    $config['mailtype'] = 'html';
    $t->email->initialize($config);
    $t->email->set_newline("\r\n");

    $t->email->from($config['smtp_user'], $settings['app_name']);
    $t->email->to($to);
    $t->email->subject($subject);
    $t->email->message($message);
    $t->email->attach($attachment);
    if ($t->email->send()) {
        $response['error'] = false;
        $response['config'] = $config;
        $response['message'] = 'Email Sent';
    } else {
        $response['error'] = true;
        $response['config'] = $config;
        $response['message'] = $t->email->print_debugger();
    }

    return $response;
}

function fetch_orders($order_id = NULL, $user_id = NULL, $status = NULL, $delivery_boy_id = NULL, $limit = NULL, $offset = NULL, $sort = NULL, $order = NULL, $download_invoice = false, $start_date = null, $end_date = null, $search = null, $city_id = null, $area_id = null, $seller_id = null, $order_type = '')
{

    $t = &get_instance();
    $where = [];

    $count_res = $t->db->select(' COUNT(distinct o.id) as `total`')
        ->join(' `users` u', 'u.id= o.user_id', 'left')
        ->join(' `order_items` oi', 'o.id= oi.order_id', 'left')
        ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
        ->join('products p', 'pv.product_id=p.id', 'left')
        ->join('order_tracking ot ', ' ot.order_item_id = oi.id', 'left')
        ->join('addresses a', 'a.id=o.address_id', 'left');
    if (isset($order_id) && $order_id != null) {
        $where['o.id'] = $order_id;
    }

    if (isset($delivery_boy_id) && $delivery_boy_id != NULL) {
        $where['oi.delivery_boy_id'] = $delivery_boy_id;
    }

    if (isset($user_id) && $user_id != null) {
        $where['o.user_id'] = $user_id;
    }
    if (isset($city_id) && $city_id != null) {
        $where['a.city_id'] = $city_id;
    }
    if (isset($area_id) && $area_id != null) {
        $where['a.area_id'] = $area_id;
    }
    if (isset($seller_id) && $seller_id != null) {
        $where['oi.seller_id'] = $seller_id;
    }
    if (isset($order_type) && $order_type != '' && $order_type == 'digital') {
        $where['p.type'] = 'digital_product';
    }
    if (isset($order_type) && $order_type != '' && $order_type == 'simple') {
        $where['p.type'] != 'digital_product';
    }


    if (isset($status) &&  is_array($status) &&  count($status) > 0) {
        $status = array_map('trim', $status);
        $count_res->where_in('oi.active_status', $status);
    }

    if (isset($start_date) && $start_date != null && isset($end_date) && $end_date != null) {
        $count_res->where(" DATE(o.date_added) >= DATE('" . $start_date . "') ");
        $count_res->where(" DATE(o.date_added) <= DATE('" . $end_date . "') ");
    }

    if (isset($search) and $search != null) {

        $filters = [
            'u.username' => $search,
            'u.email' => $search,
            'o.id' => $search,
            'o.mobile' => $search,
            'o.address' => $search,
            'o.payment_method' => $search,
            'o.delivery_time' => $search,
            'o.date_added' => $search,
            'p.name' => $search,
            'oi.active_status' => $search,
        ];
    }
    if (isset($filters) && !empty($filters)) {
        $count_res->group_Start();
        $count_res->or_like($filters);
        $count_res->group_End();
    }


    $count_res->where($where);

    if (isset($seller_id) && $seller_id != null) {
        $count_res->where("oi.active_status != 'awaiting'");
    }
    if ($sort == 'date_added') {
        $sort = 'o.date_added';
    }
    $count_res->order_by($sort, $order);

    $order_count = $count_res->get('`orders` o')->result_array();
    $total = "0";
    foreach ($order_count as $row) {
        $total = $row['total'];
    }

    $search_res = $t->db->select(' o.*, u.username,u.country_code, p.name,p.type,p.download_allowed,a.name as order_recipient_person,pv.special_price,pv.price')
        ->join(' `users` u', 'u.id= o.user_id', 'left')
        ->join(' `order_items` oi', 'o.id= oi.order_id', 'left')
        ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
        ->join('addresses a', 'a.id=o.address_id', 'left')
        ->join('products p', 'pv.product_id=p.id', 'left');
    $search_res->where($where);
    if (isset($seller_id) && $seller_id != null) {
        $search_res->where("oi.active_status != 'awaiting'");
    }
    if (isset($start_date) && $start_date != null && isset($end_date) && $end_date != null) {
        $search_res->where(" DATE(o.date_added) >= DATE('" . $start_date . "') ");
        $search_res->where(" DATE(o.date_added) <= DATE('" . $end_date . "') ");
    }
    if (isset($order_type) && $order_type != '' && $order_type == 'digital') {
        $search_res->where("p.type = 'digital_product'");
    }
    if (isset($order_type) && $order_type != '' && $order_type == 'simple') {
        $search_res->where("p.type != 'digital_product'");
    }
    if (isset($status) &&  is_array($status) &&  count($status) > 0) {
        $status = array_map('trim', $status);
        $count_res->where_in('oi.active_status', $status);
    }

    if (isset($filters) && !empty($filters)) {
        $search_res->group_Start();
        $search_res->or_like($filters);
        $search_res->group_End();
    }

    if (empty($sort)) {
        $sort = `o.date_added`;
    }
    $search_res->group_by('o.id');
    $search_res->order_by($sort, $order);
    if ($limit != null || $offset != null) {
        $search_res->limit($limit, $offset);
    }

    $order_details = $search_res->get('`orders` o')->result_array();

    for ($i = 0; $i < count($order_details); $i++) {


        $pr_condition = ($user_id != NULL && !empty(trim($user_id)) && is_numeric($user_id)) ? " and pr.user_id = $user_id " : "";
        $t->db->select('oi.*,p.id as product_id,p.is_cancelable,p.cancelable_till,p.type,p.slug,p.download_allowed,p.download_link,sd.store_name,u.longitude as seller_longitude,u.mobile as seller_mobile,u.address as seller_address,u.latitude as seller_latitude,(select username from users where id=oi.delivery_boy_id) as delivery_boy_name ,sd.store_description,sd.rating as seller_rating,sd.logo as seller_profile,ot.courier_agency,ot.tracking_id,ot.url,u.username as seller_name,p.is_returnable,
        pv.special_price,pv.price as main_price,p.image,p.name,p.rating as product_rating,p.type,pr.rating as user_rating, pr.images as user_rating_images, pr.comment as user_rating_comment,oi.status as status,
        (Select count(id) from order_items where order_id = oi.order_id ) as order_counter ,
        (Select count(active_status) from order_items where active_status ="cancelled" and order_id = oi.order_id ) as order_cancel_counter , (Select count(active_status) from order_items where active_status ="returned" and order_id = oi.order_id ) as order_return_counter ')
            ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
            ->join('products p', 'pv.product_id=p.id', 'left')
            ->join('product_rating pr', 'pv.product_id=pr.product_id ' . $pr_condition, 'left')
            ->join('seller_data sd', 'sd.user_id=oi.seller_id')
            ->join('order_tracking ot ', ' ot.order_item_id = oi.id', 'left')
            ->join('users u', 'u.id=oi.seller_id');
        $t->db->or_where_in('oi.order_id', $order_details[$i]['id']);
        if (isset($seller_id) && $seller_id != null) {
            $t->db->where('oi.seller_id=' . $seller_id);
            $t->db->where("oi.active_status != 'awaiting'");
        }
        if (isset($order_type) && $order_type != '' && $order_type == 'digital') {
            $t->db->where("p.type = 'digital_product'");
        }
        if (isset($order_type) && $order_type != '' && $order_type == 'simple') {
            $t->db->where("p.type != 'digital_product'");
        }
        if (isset($delivery_boy_id) && $delivery_boy_id != null) {
            $t->db->where('oi.delivery_boy_id=' . $delivery_boy_id);
        }
        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        $t->db->group_by('oi.id');
        $order_item_data = $t->db->get('order_items oi')->result_array();

        $return_request = fetch_details('return_requests', ['user_id' => $user_id]);
        if ($order_details[$i]['payment_method'] == "bank_transfer") {
            $bank_transfer = fetch_details('order_bank_transfer', ['order_id' => $order_details[$i]['id']], 'attachments,id,status');
            if (!empty($bank_transfer)) {
                $bank_transfer = array_map(function ($attachment) {
                    $temp['id'] = $attachment['id'];
                    $temp['attachment'] = base_url($attachment['attachments']);
                    $temp['banktransfer_status'] = $attachment['status'];
                    return $temp;
                }, $bank_transfer);
            }
        }
        $order_details[$i]['latitude'] = (isset($order_details[$i]['latitude']) && !empty($order_details[$i]['latitude'])) ? $order_details[$i]['latitude'] : "";
        $order_details[$i]['longitude'] = (isset($order_details[$i]['longitude']) && !empty($order_details[$i]['longitude'])) ? $order_details[$i]['longitude'] : "";
        $order_details[$i]['order_recipient_person'] = (isset($order_details[$i]['order_recipient_person']) && !empty($order_details[$i]['order_recipient_person'])) ? $order_details[$i]['order_recipient_person'] : "";
        $order_details[$i]['attachments'] = (isset($bank_transfer) && !empty($bank_transfer)) ? $bank_transfer : [];
        $order_details[$i]['notes'] = (isset($order_details[$i]['notes']) && !empty($order_details[$i]['notes'])) ? $order_details[$i]['notes'] : "";
        $order_details[$i]['payment_method'] = ($order_details[$i]['payment_method'] == 'bank_transfer') ? ucwords(str_replace('_', " ", $order_details[$i]['payment_method'])) : $order_details[$i]['payment_method'];
        $order_details[$i]['courier_agency'] = "";
        $order_details[$i]['tracking_id'] = "";
        $order_details[$i]['url'] = "";

        $returnable_count = 0;
        $cancelable_count = 0;
        $already_returned_count = 0;
        $already_cancelled_count = 0;
        $return_request_submitted_count = 0;
        $total_tax_percent = $total_tax_amount = $item_subtotal = 0;
        $download_allowed = array();
        for ($k = 0; $k < count($order_item_data); $k++) {
            array_push($download_allowed, $order_item_data[$k]['download_allowed']);
            // $download_allowed = array_values(array_unique(array_column($order_item_data[$k], "download_allowed")));


            if (isset($order_item_data[$k]['quantity']) && $order_item_data[$k]['quantity'] != 0) {
                $price = $order_item_data[$k]['special_price'] != '' && $order_item_data[$k]['special_price'] != null && $order_item_data[$k]['special_price'] > 0 && $order_item_data[$k]['special_price'] < $order_item_data[$k]['main_price'] ? $order_item_data[$k]['special_price'] : $order_item_data[$k]['main_price'];
                $amount = $order_item_data[$k]['quantity'] * $price;
            }
            if (!empty($order_item_data)) {

                $user_rating_images = json_decode($order_item_data[$k]['user_rating_images'], true);
                $order_item_data[$k]['user_rating_images'] = array();
                if (!empty($user_rating_images)) {
                    for ($f = 0; $f < count($user_rating_images); $f++) {
                        $order_item_data[$k]['user_rating_images'][] = base_url($user_rating_images[$f]);
                    }
                }
                $price_tax_amount = $price * ($order_item_data[$k]['tax_percent'] / 100);
                $order_item_data[$k]['tax_amount'] = isset($price_tax_amount) && !empty($price_tax_amount) ? $price_tax_amount : '0';
                $order_item_data[$k]['net_amount'] = $order_item_data[$k]['price'] - $order_item_data[$k]['tax_amount'];
                $item_subtotal += $order_item_data[$k]['sub_total'];
                $order_item_data[$k]['seller_name'] = (!empty($order_item_data[$k]['seller_name'])) ? $order_item_data[$k]['seller_name'] : '';
                $order_item_data[$k]['store_description'] = (!empty($order_item_data[$k]['store_description'])) ? $order_item_data[$k]['store_description'] : '';
                $order_item_data[$k]['seller_rating'] = (!empty($order_item_data[$k]['seller_rating'])) ? number_format($order_item_data[$k]['seller_rating'], 1) : "0";
                $order_item_data[$k]['seller_profile'] = (!empty($order_item_data[$k]['seller_profile'])) ? base_url() . $order_item_data[$k]['seller_profile'] : '';
                $order_item_data[$k]['seller_latitude'] = (isset($order_item_data[$k]['seller_latitude']) && !empty($order_item_data[$k]['seller_latitude'])) ? $order_item_data[$k]['seller_latitude'] : '';
                $order_item_data[$k]['seller_longitude'] = (isset($order_item_data[$k]['seller_longitude']) && !empty($order_item_data[$k]['seller_longitude'])) ? $order_item_data[$k]['seller_longitude'] : '';
                $order_item_data[$k]['seller_address'] = (isset($order_item_data[$k]['seller_address']) && !empty($order_item_data[$k]['seller_address'])) ? $order_item_data[$k]['seller_address'] : '';
                $order_item_data[$k]['seller_mobile'] = (isset($order_item_data[$k]['seller_mobile']) && !empty($order_item_data[$k]['seller_mobile'])) ? $order_item_data[$k]['seller_mobile'] : '';

                if (isset($seller_id) && $seller_id != null) {
                    $order_item_data[$k]['otp'] = (get_seller_permission($order_item_data[$k]['seller_id'], "view_order_otp")) ? $order_item_data[$k]['otp'] : "0";
                }

                $varaint_data = get_variants_values_by_id($order_item_data[$k]['product_variant_id']);
                $order_item_data[$k]['varaint_ids'] = (!empty($varaint_data)) ? $varaint_data[0]['varaint_ids'] : '';
                $order_item_data[$k]['variant_values'] = (!empty($varaint_data)) ? $varaint_data[0]['variant_values'] : '';
                $order_item_data[$k]['attr_name'] = (!empty($varaint_data)) ? $varaint_data[0]['attr_name'] : '';
                $order_item_data[$k]['product_rating'] = (!empty($order_item_data[$k]['product_rating'])) ? number_format($order_item_data[$k]['product_rating'], 1) : "0";
                $order_item_data[$k]['name'] = (!empty($order_item_data[$k]['name'])) ? $order_item_data[$k]['name'] : $order_item_data[$k]['product_name'];
                $order_item_data[$k]['variant_values'] = (!empty($order_item_data[$k]['variant_values'])) ? $order_item_data[$k]['variant_values'] : $order_item_data[$k]['variant_name'];
                $order_item_data[$k]['user_rating'] = (!empty($order_item_data[$k]['user_rating'])) ? $order_item_data[$k]['user_rating'] : '0';
                $order_item_data[$k]['user_rating_comment'] = (!empty($order_item_data[$k]['user_rating_comment'])) ? $order_item_data[$k]['user_rating_comment'] : '';
                $order_item_data[$k]['status'] = json_decode($order_item_data[$k]['status']);
                if (!in_array($order_item_data[$k]['active_status'], ['returned', 'cancelled'])) {
                    $total_tax_percent = $total_tax_percent +  $order_item_data[$k]['tax_percent'];
                    // $total_tax_amount  = $total_tax_amount + $order_item_data[$k]['tax_amount'];
                    $total_tax_amount  =  $order_item_data[$k]['tax_amount'] * $order_item_data[$k]['quantity'];
                }
                $order_item_data[$k]['image_sm'] = (empty($order_item_data[$k]['image']) || file_exists(FCPATH . $order_item_data[$k]['image']) == FALSE) ? base_url(NO_IMAGE) : get_image_url($order_item_data[$k]['image'], 'thumb', 'sm');
                $order_item_data[$k]['image_md'] = (empty($order_item_data[$k]['image']) || file_exists(FCPATH . $order_item_data[$k]['image']) == FALSE) ? base_url(NO_IMAGE) : get_image_url($order_item_data[$k]['image'], 'thumb', 'md');
                $order_item_data[$k]['image'] = (empty($order_item_data[$k]['image']) || file_exists(FCPATH . $order_item_data[$k]['image']) == FALSE) ? base_url(NO_IMAGE) : get_image_url($order_item_data[$k]['image']);
                $order_item_data[$k]['is_already_returned'] =  ($order_item_data[$k]['active_status'] == 'returned') ? '1' : '0';
                $order_item_data[$k]['is_already_cancelled'] = ($order_item_data[$k]['active_status'] == 'cancelled') ? '1' : '0';
                $return_request_key = array_search($order_item_data[$k]['id'], array_column($return_request, 'order_item_id'));
                if ($return_request_key !== false) {
                    $order_item_data[$k]['return_request_submitted'] = $return_request[$return_request_key]['status'];
                    if ($order_item_data[$k]['return_request_submitted'] == '1') {
                        $return_request_submitted_count += $order_item_data[$k]['return_request_submitted'];
                    }
                } else {
                    $order_item_data[$k]['return_request_submitted'] = '';
                    $return_request_submitted_count = null;
                }
                $order_item_data[$k]['courier_agency'] = (isset($order_item_data[$k]['courier_agency']) && !empty($order_item_data[$k]['courier_agency'])) ?  $order_item_data[$k]['courier_agency'] : "";
                $order_item_data[$k]['tracking_id'] = (isset($order_item_data[$k]['tracking_id']) && !empty($order_item_data[$k]['tracking_id'])) ? $order_item_data[$k]['tracking_id'] : "";
                $order_item_data[$k]['url'] = (isset($order_item_data[$k]['url']) && !empty($order_item_data[$k]['url'])) ? $order_item_data[$k]['url'] : "";
                $order_item_data[$k]['deliver_by'] = (isset($order_item_data[$k]['delivery_boy_name']) && !empty($order_item_data[$k]['delivery_boy_name'])) ? $order_item_data[$k]['delivery_boy_name'] : "";
                $order_item_data[$k]['delivery_boy_id'] = (isset($order_item_data[$k]['delivery_boy_id']) && !empty($order_item_data[$k]['delivery_boy_id'])) ? $order_item_data[$k]['delivery_boy_id'] : "";
                $order_item_data[$k]['discounted_price'] = (isset($order_item_data[$k]['discounted_price']) && !empty($order_item_data[$k]['discounted_price'])) ? $order_item_data[$k]['discounted_price'] : "";
                $order_item_data[$k]['delivery_boy_name'] = (isset($order_item_data[$k]['delivery_boy_name']) && !empty($order_item_data[$k]['delivery_boy_name'])) ? $order_item_data[$k]['delivery_boy_name'] : "";
                if (($order_details[$i]['type'] == 'digital_product' && in_array(0, $download_allowed)) ||  ($order_details[$i]['type'] != 'digital_product' && in_array(0, $download_allowed))) {
                    $order_details[$i]['download_allowed'] = '0';
                    $order_item_data[$k]['download_link'] = '';
                } else {
                    $order_details[$i]['download_allowed'] = '1';
                    $order_item_data[$k]['download_link'] = $order_item_data[$k]['download_link'];
                }
                $order_item_data[$k]['email'] = (isset($order_details[$i]['email']) && !empty($order_details[$i]['email']) ? $order_details[$i]['email'] : '');

                $returnable_count += $order_item_data[$k]['is_returnable'];
                $cancelable_count += $order_item_data[$k]['is_cancelable'];
                $already_returned_count += $order_item_data[$k]['is_already_returned'];
                $already_cancelled_count += $order_item_data[$k]['is_already_cancelled'];
                $delivery_date = $order_item_data[$k]['status'][3][1];
                $settings = get_settings('system_settings', true);
                $timestemp = strtotime($delivery_date);
                $today = date('Y-m-d');
                $return_till = date('Y-m-d', strtotime($delivery_date . ' + ' . $settings['max_product_return_days'] . ' days'));
            }
        }

        $order_details[$i]['delivery_time'] = (isset($order_details[$i]['delivery_time']) && !empty($order_details[$i]['delivery_time'])) ? $order_details[$i]['delivery_time'] : "";
        $order_details[$i]['delivery_date'] = (isset($order_details[$i]['delivery_date']) && !empty($order_details[$i]['delivery_date'])) ? $order_details[$i]['delivery_date'] : "";




        $order_details[$i]['is_returnable'] = ($returnable_count >= 1 && isset($delivery_date) && !empty($delivery_date) && $today < $return_till) ? '1' : '0';
        $order_details[$i]['is_cancelable'] = ($cancelable_count >= 1) ? '1' : '0';
        $order_details[$i]['is_already_returned'] = ($already_returned_count == count($order_item_data)) ? '1' : '0';
        $order_details[$i]['is_already_cancelled'] = ($already_cancelled_count == count($order_item_data)) ? '1' : '0';
        if ($return_request_submitted_count == null) {
            $order_details[$i]['return_request_submitted'] = '';
        } else {
            $order_details[$i]['return_request_submitted'] = ($return_request_submitted_count == count($order_item_data)) ? '1' : '0';
        }
        if ((isset($delivery_boy_id) && $delivery_boy_id != null) || (isset($seller_id) && $seller_id != null)) {
            $order_details[$i]['total'] = strval($item_subtotal - $total_tax_amount);

            $order_details[$i]['final_total'] = strval($item_subtotal - $total_tax_amount +  $order_details[$i]['delivery_charge']);
            $order_details[$i]['total_payable'] = strval($item_subtotal - $total_tax_amount +  $order_details[$i]['delivery_charge']);
        } else {
            $order_details[$i]['total'] = strval($order_details[$i]['total']);
        }
        $order_details[$i]['address'] = (isset($order_details[$i]['address']) && !empty($order_details[$i]['address'])) ? output_escaping($order_details[$i]['address']) : "";
        $order_details[$i]['username'] = output_escaping($order_details[$i]['username']);
        $order_details[$i]['country_code'] = (isset($order_details[$i]['country_code']) && !empty($order_details[$i]['country_code'])) ? $order_details[$i]['country_code'] : '';
        $order_details[$i]['total_tax_percent'] = strval($total_tax_percent);
        $order_details[$i]['total_tax_amount'] = strval($total_tax_amount);
        if (isset($seller_id) && $seller_id != null) {
            if ($download_invoice == true || $download_invoice == 1) {
                $order_details[$i]['invoice_html'] =  get_seller_invoice_html($order_details[$i]['id'], $seller_id);
            }
        } else {
            if ($download_invoice == true || $download_invoice == 1) {
                $order_details[$i]['invoice_html'] =  get_invoice_html($order_details[$i]['id']);
            }
        }
        if (!empty($order_item_data)) {
            $order_details[$i]['order_items'] = $order_item_data;
        } else {
            $order_details[$i]['order_items'] =  [];
        }
    }

    $order_data['total'] = $total;
    $order_data['order_data'] = array_values($order_details);
    return $order_data;
}

function fetch_order_items($order_item_id = NULL, $user_id = NULL, $status = NULL, $delivery_boy_id = NULL, $limit = NULL, $offset = NULL, $sort = NULL, $order = NULL, $start_date = null, $end_date = null, $search = null, $seller_id = null, $order_id = null)
{

    $t = &get_instance();
    $where = [];

    $count_res = $t->db->select(' COUNT(o.id) as `total` ')
        ->join(' `users` u', 'u.id= oi.delivery_boy_id', 'left')
        ->join('users us ', ' us.id = oi.seller_id', 'left')
        ->join(' `orders` o', 'o.id= oi.order_id')
        ->join('users un ', ' un.id = o.user_id', 'left')
        ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
        ->join('products p', 'pv.product_id=p.id', 'left')
        ->join('seller_data sd', 'sd.user_id=p.seller_id');
    if (isset($order_item_id) && $order_item_id != null) {
        $where['oi.id'] = $order_item_id;
    }
    if (isset($order_id) && $order_id != null) {
        $where['oi.order_id'] = $order_id;
    }

    if (isset($delivery_boy_id) && $delivery_boy_id != null) {
        $where['oi.delivery_boy_id'] = $delivery_boy_id;
    }

    $where['oi.seller_id'] = $seller_id;

    if (isset($start_date) && $start_date != null && isset($end_date) && $end_date != null) {
        $count_res->where(" DATE(oi.date_added) >= DATE('" . $start_date . "') ");
        $count_res->where(" DATE(oi.date_added) <= DATE('" . $end_date . "') ");
    }

    if (isset($search) and $search != null) {

        $filters = [
            'u.username' => $search,
            'u.email' => $search,
            'oi.id' => $search,
            'p.name' => $search
        ];
    }
    if (isset($filters) && !empty($filters)) {
        $count_res->group_Start();
        $count_res->or_like($filters);
        $count_res->group_End();
    }

    $count_res->where($where);
    if ($sort == 'date_added') {
        $sort = 'oi.date_added';
    }
    $count_res->order_by($sort, $order);

    $order_count = $count_res->get('order_items oi')->result_array();

    $total = "0";
    foreach ($order_count as $row) {
        $total = $row['total'];
    }

    $search_res = $t->db->select('oi.*,p.id as product_id,p.is_cancelable,sd.store_name,p.is_returnable,p.image,p.name,p.type,oi.status as status,(Select count(id) from order_items where order_id = oi.order_id ) as order_counter ,(Select count(active_status) from order_items where active_status ="cancelled" and order_id = oi.order_id ) as order_cancel_counter , (Select count(active_status) from order_items where active_status ="returned" and order_id = oi.order_id ) as order_return_counter ')
        ->join(' `users` u', 'u.id= oi.delivery_boy_id', 'left')
        ->join('users us ', ' us.id = oi.seller_id', 'left')
        ->join(' `orders` o', 'o.id= oi.order_id')
        ->join('users un ', ' un.id = o.user_id', 'left')
        ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
        ->join('products p', 'pv.product_id=p.id', 'left')
        ->join('seller_data sd', 'sd.user_id=p.seller_id');
    $search_res->where($where);
    if (isset($start_date) && $start_date != null && isset($end_date) && $end_date != null) {
        $search_res->where(" DATE(oi.date_added) >= DATE('" . $start_date . "') ");
        $search_res->where(" DATE(oi.date_added) <= DATE('" . $end_date . "') ");
    }
    if (isset($filters) && !empty($filters)) {
        $search_res->group_Start();
        $search_res->or_like($filters);
        $search_res->group_End();
    }
    if (empty($sort)) {
        $sort = `oi.date_added`;
    }
    $search_res->group_by('oi.id');
    $search_res->order_by($sort, $order);
    if ($limit != null || $offset != null) {
        $search_res->limit($limit, $offset);
    }

    $order_item_data = $search_res->get('order_items oi')->result_array();
    for ($k = 0; $k < count($order_item_data); $k++) {

        $return_request = fetch_details('return_requests', ['user_id' => $user_id]);

        $order_item_data[$k]['status'] = json_decode($order_item_data[$k]['status']);
        $order_item_data[$k]['delivery_boy_id'] = (isset($order_item_data[$k]['delivery_boy_id']) && !empty($order_item_data[$k]['delivery_boy_id'])) ? $order_item_data[$k]['delivery_boy_id'] : '';
        $order_item_data[$k]['discounted_price'] = (isset($order_item_data[$k]['discounted_price']) && !empty($order_item_data[$k]['discounted_price'])) ? $order_item_data[$k]['discounted_price'] : '';
        $order_item_data[$k]['deliver_by'] = (isset($order_item_data[$k]['deliver_by']) && !empty($order_item_data[$k]['deliver_by'])) ? $order_item_data[$k]['deliver_by'] : '';

        for ($j = 0; $j < count($order_item_data[$k]['status']); $j++) {
            $order_item_data[$k]['status'][$j][1] = date('d-m-Y h:i:sa', strtotime($order_item_data[$k]['status'][$j][1]));
        }

        $returnable_count = 0;
        $cancelable_count = 0;
        $already_returned_count = 0;
        $already_cancelled_count = 0;
        $return_request_submitted_count = 0;
        $total_tax_percent = $total_tax_amount = 0;

        $varaint_data = get_variants_values_by_id($order_item_data[$k]['product_variant_id']);
        // varient ids
        $order_item_data[$k]['varaint_ids'] = (!empty($varaint_data)) ? $varaint_data[0]['varaint_ids'] : '';
        $order_item_data[$k]['variant_values'] = (!empty($varaint_data)) ? $varaint_data[0]['variant_values'] : '';
        $order_item_data[$k]['attr_name'] = (!empty($varaint_data)) ? $varaint_data[0]['attr_name'] : '';

        $order_item_data[$k]['name'] = (!empty($order_item_data[$k]['name'])) ? $order_item_data[$k]['name'] : $order_item_data[$k]['product_name'];
        $order_item_data[$k]['variant_values'] = (!empty($order_item_data[$k]['variant_values'])) ? $order_item_data[$k]['variant_values'] : $order_item_data[$k]['variant_name'];

        if (!in_array($order_item_data[$k]['active_status'], ['returned', 'cancelled'])) {
            $total_tax_percent = $total_tax_percent +  $order_item_data[$k]['tax_percent'];
            $total_tax_amount  = $total_tax_amount + $order_item_data[$k]['tax_amount'];
        }

        for ($j = 0; $j < count($order_item_data[$k]['status']); $j++) {
            $order_item_data[$k]['status'][$j][1] = date('d-m-Y h:i:sa', strtotime($order_item_data[$k]['status'][$j][1]));
        }

        $order_item_data[$k]['image_sm'] = (empty($order_item_data[$k]['image']) || file_exists(FCPATH . $order_item_data[$k]['image']) == FALSE) ? base_url(NO_IMAGE) : get_image_url($order_item_data[$k]['image'], 'thumb', 'sm');
        $order_item_data[$k]['image_md'] = (empty($order_item_data[$k]['image']) || file_exists(FCPATH . $order_item_data[$k]['image']) == FALSE) ? base_url(NO_IMAGE) : get_image_url($order_item_data[$k]['image'], 'thumb', 'md');
        $order_item_data[$k]['image'] = (empty($order_item_data[$k]['image']) || file_exists(FCPATH . $order_item_data[$k]['image']) == FALSE) ? base_url(NO_IMAGE) : get_image_url($order_item_data[$k]['image']);
        $order_item_data[$k]['is_already_returned'] =  ($order_item_data[$k]['active_status'] == 'returned') ? '1' : '0';
        $order_item_data[$k]['is_already_cancelled'] = ($order_item_data[$k]['active_status'] == 'cancelled') ? '1' : '0';
        $return_request_key = array_search($order_item_data[$k]['id'], array_column($return_request, 'order_item_id'));
        if ($return_request_key !== false) {
            $order_item_data[$k]['return_request_submitted'] =  $return_request[$return_request_key]['status'];
            if ($order_item_data[$k]['return_request_submitted'] == '1') {
                $return_request_submitted_count += $order_item_data[$k]['return_request_submitted'];
            }
        } else {
            $order_item_data[$k]['return_request_submitted'] = '';
            $return_request_submitted_count = null;
        }

        $returnable_count += $order_item_data[$k]['is_returnable'];
        $cancelable_count += $order_item_data[$k]['is_cancelable'];
        $already_returned_count += $order_item_data[$k]['is_already_returned'];
        $already_cancelled_count += $order_item_data[$k]['is_already_cancelled'];

        $order_details[$k]['is_returnable'] = ($returnable_count >= 1) ? '1' : '0';
        $order_details[$k]['is_cancelable'] = ($cancelable_count >= 1) ? '1' : '0';
        $order_details[$k]['is_already_returned'] = ($already_returned_count == count($order_item_data)) ? '1' : '0';
        $order_details[$k]['is_already_cancelled'] = ($already_cancelled_count == count($order_item_data)) ? '1' : '0';
        if ($return_request_submitted_count == null) {
            $order_details[$k]['return_request_submitted'] = null;
        } else {
            $order_details[$k]['return_request_submitted'] = ($return_request_submitted_count == count($order_item_data)) ? '1' : '0';
        }
        $order_details[$k]['username'] = output_escaping($order_details[$k]['username']);
        $order_details[$k]['total_tax_percent'] = strval($total_tax_percent);
        $order_details[$k]['total_tax_amount'] = strval($total_tax_amount);
    }

    $order_data['total'] = $total;
    $order_data['order_data'] = (!empty($order_item_data)) ? array_values($order_item_data) : [];
    return $order_data;
}

function find_media_type($extenstion)
{
    $t = &get_instance();
    $t->config->load('eshop');
    $type = $t->config->item('type');
    foreach ($type as $main_type => $extenstions) {
        foreach ($extenstions['types'] as $k => $v) {
            if ($v === strtolower($extenstion)) {
                return array($main_type, $extenstions['icon']);
            }
        }
    }
    return false;
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

function delete_images($subdirectory, $image_name)
{
    $image_types = ['thumb-md/', 'thumb-sm/', 'cropped-md/', 'cropped-sm/'];
    $main_dir = FCPATH . $subdirectory;

    foreach ($image_types as $types) {
        $path = $main_dir . $types . $image_name;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    if (file_exists($main_dir . $image_name)) {
        unlink($main_dir . $image_name);
    }
}

function get_image_url($path, $image_type = '', $image_size = '', $file_type = 'image')
{
    $path = explode('/', (string)$path);
    $subdirectory = '';
    for ($i = 0; $i < count($path) - 1; $i++) {
        $subdirectory .= $path[$i] . '/';
    }
    $image_name = end($path);

    $file_main_dir = FCPATH . $subdirectory;
    $image_main_dir = base_url() . $subdirectory;
    if ($file_type == 'image') {
        $types = ['thumb', 'cropped'];
        $sizes = ['md', 'sm'];
        if (in_array(trim(strtolower($image_type)), $types) &&  in_array(trim(strtolower($image_size)), $sizes)) {
            $filepath = $file_main_dir . $image_type . '-' . $image_size . '/' . $image_name;
            $imagepath = $image_main_dir . $image_type . '-' . $image_size . '/' . $image_name;
            if (file_exists($filepath)) {
                return  $imagepath;
            } else if (file_exists($file_main_dir . $image_name)) {
                return  $image_main_dir . $image_name;
            } else {
                return  base_url() . NO_IMAGE;
            }
        } else {
            if (file_exists($file_main_dir . $image_name)) {
                return  $image_main_dir . $image_name;
            } else {
                return  base_url() . NO_IMAGE;
            }
        }
    } else {
        $file = new SplFileInfo($file_main_dir . $image_name);
        $ext  = $file->getExtension();

        $media_data =  find_media_type($ext);
        $image_placeholder = $media_data[1];
        $filepath = FCPATH .  $image_placeholder;
        $extensionpath = base_url() .  $image_placeholder;
        if (file_exists($filepath)) {
            return  $extensionpath;
        } else {
            return  base_url() . NO_IMAGE;
        }
    }
}

function fetch_users($id)
{
    $t = &get_instance();
    $user_details = $t->db->select('u.id,username,email,mobile,balance,dob, referral_code, friends_code, c.name as cities,a.name as area,street,pincode')
        ->join('areas a', 'u.area = a.name', 'left')
        ->join('cities c', 'u.city = c.name', 'left')
        ->where('u.id', $id)->get('users u')
        ->result_array();
    return $user_details;
}

function escape_array($array)
{
    $t = &get_instance();
    $posts = array();
    if (!empty($array)) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $posts[$key] = $t->db->escape_str($value ?? '');
            }
        } else {
            return $t->db->escape_str($array);
        }
    }
    return $posts;
}

function allowed_media_types()
{
    $t = &get_instance();
    $t->config->load('eshop');
    $type = $t->config->item('type');
    $general = [];
    foreach ($type as $main_type => $extenstions) {
        $general = array_merge_recursive($general, $extenstions['types']);
    }
    return $general;
}

function get_current_version()
{
    $t = &get_instance();
    $version = $t->db->select('max(version) as version')->get('updates')->result_array();
    return $version[0]['version'];
}

function resize_review_images($image_data, $source_path, $id = false)
{
    if ($image_data['is_image']) {

        $t = &get_instance();

        $target_path = $source_path; // Target path will be under source path        
        $image_name = $image_data['file_name']; // original image's name    
        $w = $image_data['image_width']; // original image's width    
        $h = $image_data['image_height']; // original images's height 

        $t->load->library('image_lib');

        if (file_exists($source_path . $image_name)) {  //check if the image file exist 

            if (!file_exists($target_path)) {
                mkdir($target_path, 0777);
            }

            $n_w = 800;
            $n_h = 800;
            $config['image_library'] = 'gd2';
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['quality'] = '90%';
            $config['source_image'] =  $source_path . $image_name;
            $config['new_image'] = $target_path . $image_name;
            $config['width'] = $n_w;
            $config['height'] = $n_h;
            $t->image_lib->clear();
            $t->image_lib->initialize($config);
            if (!$t->image_lib->resize()) {
                return $t->image_lib->display_errors();
            }
        }
    }
}

function get_invoice_html($order_id)
{
    $t = &get_instance();
    $invoice_generated_html = '';
    $t->data['main_page'] = VIEW . 'api-order-invoice';
    $settings = get_settings('system_settings', true);
    $t->data['title'] = 'Invoice Management |' . $settings['app_name'];
    $t->data['meta_description'] = 'Ekart | Invoice Management';
    if (isset($order_id) && !empty($order_id)) {
        $res = $t->Order_model->get_order_details(['o.id' => $order_id], true);
        if (!empty($res)) {
            $items = [];
            $promo_code = [];
            if (!empty($res[0]['promo_code'])) {
                $promo_code = fetch_details('promo_codes', ['promo_code' => trim($res[0]['promo_code'])]);
            }
            foreach ($res as $row) {
                $row = output_escaping($row);
                $temp['product_id'] = $row['product_id'];
                $temp['seller_id'] = $row['seller_id'];
                $temp['product_variant_id'] = $row['product_variant_id'];
                $temp['pname'] = $row['pname'];
                $temp['quantity'] = $row['quantity'];
                $temp['discounted_price'] = $row['discounted_price'];
                $temp['tax_percent'] = $row['tax_percent'];
                $temp['tax_amount'] = $row['tax_amount'];
                $temp['price'] = $row['price'];
                $temp['delivery_boy'] = $row['delivery_boy'];
                $temp['active_status'] = $row['oi_active_status'];
                array_push($items, $temp);
            }
            $t->data['order_detls'] = $res;
            $t->data['items'] = $items;
            $t->data['promo_code'] = $promo_code;
            $t->data['settings'] = get_settings('system_settings', true);
            $invoice_generated_html = $t->load->view('admin/invoice-template', $t->data, TRUE);
        } else {
            $invoice_generated_html = '';
        }
    } else {
        $invoice_generated_html = '';
    }
    return $invoice_generated_html;
}

function get_seller_invoice_html($order_id, $seller_id)
{
    $t = &get_instance();
    $invoice_generated_html = '';
    $t->data['main_page'] = VIEW . 'api-order-invoice';
    $settings = get_settings('system_settings', true);
    $t->data['title'] = 'Invoice Management |' . $settings['app_name'];
    $t->data['meta_description'] = 'Ekart | Invoice Management';
    if (isset($order_id) && !empty($order_id) && isset($seller_id) && !empty($seller_id)) {
        $s_user_data = fetch_details('users', ['id' => $seller_id], 'email,mobile,address,country_code');
        $seller_data = fetch_details('seller_data', ['user_id' => $seller_id], 'store_name,pan_number,tax_name,tax_number');
        $res = $t->order_model->get_order_details(['o.id' => $order_id, 'oi.seller_id' => $seller_id], true);
        if (!empty($res)) {
            $items = [];
            $promo_code = [];
            if (!empty($res[0]['promo_code'])) {
                $promo_code = fetch_details('promo_codes', ['promo_code' => trim($res[0]['promo_code'])]);
            }
            foreach ($res as $row) {
                $row = output_escaping($row);
                $temp['product_id'] = $row['product_id'];
                $temp['product_variant_id'] = $row['product_variant_id'];
                $temp['pname'] = $row['pname'];
                $temp['quantity'] = $row['quantity'];
                $temp['discounted_price'] = $row['discounted_price'];
                $temp['tax_percent'] = $row['tax_percent'];
                $temp['tax_amount'] = $row['tax_amount'];
                $temp['price'] = $row['price'];
                $temp['delivery_boy'] = $row['delivery_boy'];
                $temp['active_status'] = $row['oi_active_status'];
                array_push($items, $temp);
            }
            $t->data['order_detls'] = $res;
            $t->data['items'] = $items;
            $t->data['s_user_data'] = $s_user_data;
            $t->data['seller_data'] = $seller_data;
            $t->data['promo_code'] = $promo_code;
            $t->data['settings'] = get_settings('system_settings', true);
            $invoice_generated_html = $t->load->view('seller/invoice-template', $t->data, TRUE);
        } else {
            $invoice_generated_html = '';
        }
    } else {
        $invoice_generated_html = '';
    }
    return $invoice_generated_html;
}

function is_modification_allowed($module)
{
    $allow_modification = ALLOW_MODIFICATION;
    $allow_modification = ($allow_modification == 0) ? 0 : 1;
    $excluded_modules = ['orders'];
    if (isset($allow_modification) && $allow_modification == 0) {
        if (!in_array(strtolower($module), $excluded_modules)) {
            return false;
        }
    }
    return true;
}
function output_escaping($array)
{
    $exclude_fields = ["images", "other_images"];
    $t = &get_instance();

    if (!empty($array)) {
        if (is_array($array)) {
            $data = array();
            foreach ($array as $key => $value) {
                if (!in_array($key, $exclude_fields)) {
                    $data[$key] = stripcslashes((string)$value);
                } else {
                    $data[$key] = $value;
                }
            }
            return $data;
        } else if (is_object($array)) {
            $data = new stdClass();
            foreach ($array as $key => $value) {
                if (!in_array($key, $exclude_fields)) {
                    $data->$key = stripcslashes($value);
                } else {
                    $data[$key] = $value;
                }
            }
            return $data;
        } else {
            return stripcslashes($array);
        }
    }
}
function get_min_max_price_of_product($product_id = '')
{
    $t = &get_instance();
    $t->db->join('`product_variants` pv', 'p.id = pv.product_id')->join('`taxes` tax', 'tax.id = p.tax', 'LEFT');
    if (!empty($product_id)) {
        $t->db->where('p.id', $product_id);
    }
    $response = $t->db->select('is_prices_inclusive_tax,price,special_price,tax.percentage as tax_percentage')->get('products p')->result_array();
    $percentage = (isset($response[0]['tax_percentage']) && intval($response[0]['tax_percentage']) > 0 && $response[0]['tax_percentage'] != null) ? $response[0]['tax_percentage'] : '0';
    if ((isset($response[0]['is_prices_inclusive_tax']) && $response[0]['is_prices_inclusive_tax'] == 0) || (!isset($response[0]['is_prices_inclusive_tax'])) && $percentage > 0) {
        $price_tax_amount = $response[0]['price'] * ($percentage / 100);
        $special_price_tax_amount = $response[0]['special_price'] * ($percentage / 100);
    } else {
        $price_tax_amount = 0;
        $special_price_tax_amount = 0;
    }
    $data['min_price'] = min(array_column($response, 'price')) + $price_tax_amount;
    $data['max_price'] = max(array_column($response, 'price')) + $price_tax_amount;
    $data['special_price'] = min(array_column($response, 'special_price')) + $special_price_tax_amount;
    $data['max_special_price'] = max(array_column($response, 'special_price')) + $special_price_tax_amount;
    $data['discount_in_percentage'] = find_discount_in_percentage($data['special_price'] + $special_price_tax_amount, $data['min_price'] + $price_tax_amount);
    return $data;
}
function get_price_range_of_product($product_id = '')
{
    $system_settings = get_settings('system_settings', true);
    $currency = (isset($system_settings['currency']) && !empty($system_settings['currency'])) ? $system_settings['currency'] : '';
    $t = &get_instance();
    $t->db->join('`product_variants` pv', 'p.id = pv.product_id')->join('`taxes` tax', 'tax.id = p.tax', 'LEFT');
    if (!empty($product_id)) {
        $t->db->where('p.id', $product_id);
    }
    $response = $t->db->select('is_prices_inclusive_tax,price,special_price,tax.percentage as tax_percentage')->get('products p')->result_array();

    if (count($response) == 1) {
        $percentage = (isset($response[0]['tax_percentage']) && intval($response[0]['tax_percentage']) > 0 && $response[0]['tax_percentage'] != null) ? $response[0]['tax_percentage'] : '0';
        if ((isset($response[0]['is_prices_inclusive_tax']) && $response[0]['is_prices_inclusive_tax'] == 0) || (!isset($response[0]['is_prices_inclusive_tax'])) && $percentage > 0) {
            $price_tax_amount = $response[0]['price'] * ($percentage / 100);
            $special_price_tax_amount = $response[0]['special_price'] * ($percentage / 100);
        } else {
            $price_tax_amount = 0;
            $special_price_tax_amount = 0;
        }
        $price_tax_amount = $price_tax_amount;
        $special_price_tax_amount = $special_price_tax_amount;
        $price = $response[0]['special_price'] == 0 ? $response[0]['price'] + $price_tax_amount : $response[0]['special_price'] + $special_price_tax_amount;
        $data['range'] =  $currency . ' ' . number_format($price, 2);
    } else {
        for ($i = 0; $i < count($response); $i++) {
            $is_all_specical_price_zero = 1;
            if ($response[$i]['special_price'] != 0) {
                $is_all_specical_price_zero = 0;
            }

            if ($is_all_specical_price_zero == 1) {
                $min = min(array_column($response, 'price'));
                $max = max(array_column($response, 'price'));
                $percentage = (isset($response[$i]['tax_percentage']) && intval($response[$i]['tax_percentage']) > 0 && $response[$i]['tax_percentage'] != null) ? $response[$i]['tax_percentage'] : '0';
                if ((isset($response[$i]['is_prices_inclusive_tax']) && $response[$i]['is_prices_inclusive_tax'] == 0) || (!isset($response[$i]['is_prices_inclusive_tax'])) && $percentage > 0) {
                    $min_price_tax_amount = $min * ($percentage / 100);
                    $min = $min + $min_price_tax_amount;

                    $max_price_tax_amount = $max * ($percentage / 100);
                    $max = $max + $max_price_tax_amount;
                }

                $data['range'] = $currency . ' ' . number_format($min, 2) . ' - ' . $currency . ' ' . number_format($max, 2);
            } else {

                $min_special_price = array_column($response, 'special_price');
                for ($j = 0; $j < count($min_special_price); $j++) {
                    if ($min_special_price[$j] == 0) {
                        unset($min_special_price[$j]);
                    }
                }
                $min_special_price = min($min_special_price);
                $max = max(array_column($response, 'price'));
                $percentage = (isset($response[$i]['tax_percentage']) && intval($response[$i]['tax_percentage']) > 0 && $response[$i]['tax_percentage'] != null) ? $response[$i]['tax_percentage'] : '0';
                if ((isset($response[$i]['is_prices_inclusive_tax']) && $response[$i]['is_prices_inclusive_tax'] == 0) || (!isset($response[$i]['is_prices_inclusive_tax'])) && $percentage > 0) {
                    $min_price_tax_amount = $min_special_price * ($percentage / 100);
                    $min_special_price = $min_special_price + $min_price_tax_amount;
                    $max_price_tax_amount = $max * ($percentage / 100);
                    $max = $max + $max_price_tax_amount;
                }
                $data['range'] = $currency . ' ' . number_format($min_special_price, 2) . ' - ' . $currency . ' ' . number_format($max, 2);
            }
        }
    }

    return $data;
}
function find_discount_in_percentage($special_price, $price)
{
    $diff_amount = $price - $special_price;
    return intval(($diff_amount * 100) / $price);
}
function get_attribute_ids_by_value($values, $names)
{
    $t = &get_instance();
    $attribute_ids = $t->db->select("av.id")
        ->join('attributes a ', 'av.attribute_id = a.id ')
        ->where_in('av.value', $values)
        ->where_in('a.name', $names)
        ->get('attribute_values av')->result_array();
    return array_column($attribute_ids, 'id');
}

function insert_details($data, $table)
{
    $t = &get_instance();
    return $t->db->insert($table, $data);
}

function get_category_id_by_slug($slug)
{
    $t = &get_instance();
    $slug = urldecode($slug);
    return $t->db->select("id")
        ->where('slug', $slug)
        ->get('categories')->row_array()['id'];
}

function get_variant_attributes($product_id)
{
    $product = fetch_product(NULL, NULL, $product_id);
    if (!empty($product['product'][0]['variants']) && isset($product['product'][0]['variants'])) {
        $attributes_array = explode(',', $product['product'][0]['variants'][0]['attr_name']);
        $variant_attributes = [];
        foreach ($attributes_array as $attribute) {
            $attribute = trim($attribute);

            $key = array_search($attribute, array_column($product['product'][0]['attributes'], 'name'), false);
            if ($key === 0 || !empty(strval($key))) {
                $variant_attributes[$key]['ids'] = $product['product'][0]['attributes'][$key]['ids'];
                $variant_attributes[$key]['values'] = $product['product'][0]['attributes'][$key]['value'];
                $variant_attributes[$key]['attr_name'] = $attribute;
            }
        }
        return $variant_attributes;
    }
}

function get_product_variant_details($product_variant_id)
{
    $CI = &get_instance();
    $res = $CI->db->join('products p', 'p.id=pv.product_id')
        ->where('pv.id', $product_variant_id)
        ->select('p.name,p.id,p.image,p.short_description,pv.*')->get('product_variants pv')->result_array();

    if (!empty($res)) {
        $res = array_map(function ($d) {
            $d['image_sm'] = get_image_url($d['image'], 'sm');
            $d['image_md'] = get_image_url($d['image'], 'md');
            $d['image'] = get_image_url($d['image']);
            return $d;
        }, $res);
    } else {
        return null;
    }
    return $res[0];
}

function get_cities($id = NULL, $limit = NULL, $offset = NULL)
{
    $CI = &get_instance();
    if (!empty($limit) || !empty($offset)) {
        $CI->db->limit($limit, $offset);
    }
    return $CI->db->get('cities')->result_array();
}

function get_favorites($user_id, $limit = NULL, $offset = NULL)
{
    $CI = &get_instance();
    if (!empty($limit) || !empty($offset)) {
        $CI->db->limit($limit, $offset);
    }
    $res = $CI->db->join('products p', 'p.id=f.product_id')
        ->where('f.user_id', $user_id)
        ->select('p.*')
        ->order_by('f.id', "DESC")
        ->get('favorites f')->result_array();

    $res = array_map(function ($d) {
        $d['image_md'] = get_image_url($d['image'], 'thumb', 'md');
        $d['image_sm'] = get_image_url($d['image'], 'thumb', 'sm');
        $d['image'] = get_image_url($d['image']);
        $d['variants'] = get_variants_values_by_pid($d['id']);
        $d['min_max_price'] = get_min_max_price_of_product($d['id']);
        return $d;
    }, $res);
    return $res;
}
function current_theme($id = '', $name = '', $slug = '', $is_default = 1, $status = '')
{
    //If don't pass any params then this function will return the current theme.
    $CI = &get_instance();
    if (!empty($id)) {
        $CI->db->where('id', $id);
    }
    if (!empty($name)) {
        $CI->db->where('name', $name);
    }
    if (!empty($slug)) {
        $CI->db->where('slug', $slug);
    }
    if (!empty($is_default)) {
        $CI->db->where('is_default', $is_default);
    }
    if (!empty($status)) {
        $CI->db->where('status', $status);
    }
    $res = $CI->db->get('themes')->result_array();
    $res = array_map(function ($d) {
        $d['image'] = base_url('assets/front_end/theme-images/' . $d['image']);
        return $d;
    }, $res);
    return $res;
}
function get_languages($id = '', $language_name = '', $code = '', $is_rtl = '')
{
    $CI = &get_instance();
    if (!empty($id)) {
        $CI->db->where('id', $id);
    }
    if (!empty($language_name)) {
        $CI->db->where('language', $language_name);
    }
    if (!empty($code)) {
        $CI->db->where('code', $code);
    }
    if (!empty($is_rtl)) {
        $CI->db->where('is_rtl', $is_rtl);
    }
    $res = $CI->db->get('languages')->result_array();
    return $res;
}

function verify_payment_transaction($txn_id, $payment_method, $additional_data = [])
{
    if (empty(trim($txn_id))) {
        $response['error'] = true;
        $response['message'] = "Transaction ID is required";
        return $response;
    }

    $CI = &get_instance();
    $CI->config->load('eshop');
    $supported_methods = $CI->config->item('supported_payment_methods');

    if (empty(trim($payment_method)) || !in_array($payment_method, $supported_methods)) {
        $response['error'] = true;
        $response['message'] = "Invalid payment method supplied";
        return $response;
    }
    switch ($payment_method) {
        case 'razorpay':
            $CI->load->library("razorpay");
            $payment = $CI->razorpay->fetch_payments($txn_id);
            if (!empty($payment) && isset($payment['status'])) {
                if ($payment['status'] == 'authorized') {

                    /* if the payment is authorized try to capture it using the API */
                    $capture_response = $CI->razorpay->capture_payment($payment['amount'], $txn_id, $payment['currency']);
                    if ($capture_response['status'] == 'captured') {
                        $response['error'] = false;
                        $response['message'] = "Payment captured successfully";
                        $response['amount'] = $capture_response['amount'] / 100;
                        $response['data'] = $capture_response;
                        return $response;
                    } else if ($capture_response['status'] == 'refunded') {
                        $response['error'] = true;
                        $response['message'] = "Payment is refunded.";
                        $response['amount'] = $capture_response['amount'] / 100;
                        $response['data'] = $capture_response;
                        return $response;
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Payment could not be captured.";
                        $response['amount'] = (isset($capture_response['amount'])) ? $capture_response['amount'] / 100 : 0;
                        $response['data'] = $capture_response;
                        return $response;
                    }
                } else if ($payment['status'] == 'captured') {
                    $response['error'] = false;
                    $response['message'] = "Payment captured successfully";
                    $response['amount'] = $payment['amount'] / 100;
                    $response['data'] = $payment;
                    return $response;
                } else if ($payment['status'] == 'created') {
                    $response['error'] = true;
                    $response['message'] = "Payment is just created and yet not authorized / captured!";
                    $response['amount'] = $payment['amount'] / 100;
                    $response['data'] = $payment;
                    return $response;
                } else {
                    $response['error'] = true;
                    $response['message'] = "Payment is " . ucwords($payment['status']) . "! ";
                    $response['amount'] = (isset($payment['amount'])) ? $payment['amount'] / 100 : 0;
                    $response['data'] = $payment;
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Payment not found by the transaction ID!";
                $response['amount'] = 0;
                $response['data'] = [];
                return $response;
            }
            break;
        case 'paystack':
            $CI->load->library("paystack");
            $payment = $CI->paystack->verify_transation($txn_id);
            if (!empty($payment)) {
                $payment = json_decode($payment, true);
                if (isset($payment['data']['status']) && $payment['data']['status'] == 'success') {
                    $response['error'] = false;
                    $response['message'] = "Payment is successful";
                    $response['amount'] = (isset($payment['data']['amount'])) ? $payment['data']['amount'] / 100 : 0;
                    $response['data'] = $payment;
                    return $response;
                } elseif (isset($payment['data']['status']) && $payment['data']['status'] != 'success') {
                    $response['error'] = true;
                    $response['message'] = "Payment is " . ucwords($payment['data']['status']) . "! ";
                    $response['amount'] = (isset($payment['data']['amount'])) ? $payment['data']['amount'] / 100 : 0;
                    $response['data'] = $payment;
                    return $response;
                } else {
                    $response['error'] = true;
                    $response['message'] = "Payment is unsuccessful! ";
                    $response['amount'] = (isset($payment['data']['amount'])) ? $payment['data']['amount'] / 100 : 0;
                    $response['data'] = $payment;
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Payment not found by the transaction ID!";
                $response['amount'] = 0;
                $response['data'] = [];
                return $response;
            }
            break;

        case 'flutterwave':
            $CI->load->library("flutterwave");
            $transaction = $CI->flutterwave->verify_transaction($txn_id);
            if (!empty($transaction)) {
                $transaction = json_decode($transaction, true);
                if ($transaction['status'] == 'error') {
                    $response['error'] = true;
                    $response['message'] = $transaction['message'];
                    $response['amount'] = (isset($transaction['data']['amount'])) ? $transaction['data']['amount'] : 0;
                    $response['data'] = $transaction;
                    return $response;
                }

                if ($transaction['status'] == 'success' && $transaction['data']['status'] == 'successful') {
                    $response['error'] = false;
                    $response['message'] = "Payment has been completed successfully";
                    $response['amount'] = $transaction['data']['amount'];
                    $response['data'] = $transaction;
                    return $response;
                } else if ($transaction['status'] == 'success' && $transaction['data']['status'] != 'successful') {
                    $response['error'] = true;
                    $response['message'] = "Payment is " . $transaction['data']['status'];
                    $response['amount'] = $transaction['data']['amount'];
                    $response['data'] = $transaction;
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Payment not found by the transaction ID!";
                $response['amount'] = 0;
                $response['data'] = [];
                return $response;
            }
            break;

        case 'stripe':
            # code...
            return "stripe is supplied";
            break;


        case 'paytm':
            $CI->load->library('paytm');
            $payment = $CI->paytm->transaction_status($txn_id); /* We are using order_id created during the generation of txn token */
            if (!empty($payment)) {
                $payment = json_decode($payment, true);
                if (
                    isset($payment['body']['resultInfo']['resultCode'])
                    && ($payment['body']['resultInfo']['resultCode'] == '01' && $payment['body']['resultInfo']['resultStatus'] == 'TXN_SUCCESS')
                ) {
                    $response['error'] = false;
                    $response['message'] = "Payment is successful";
                    $response['amount'] = (isset($payment['body']['txnAmount'])) ? $payment['body']['txnAmount'] : 0;
                    $response['data'] = $payment;
                    return $response;
                } elseif (
                    isset($payment['body']['resultInfo']['resultCode'])
                    && ($payment['body']['resultInfo']['resultStatus'] == 'TXN_FAILURE')
                ) {
                    $response['error'] = true;
                    $response['message'] = $payment['body']['resultInfo']['resultMsg'];
                    $response['amount'] = (isset($payment['body']['txnAmount'])) ? $payment['body']['txnAmount'] : 0;
                    $response['data'] = $payment;
                    return $response;
                } else if (
                    isset($payment['body']['resultInfo']['resultCode'])
                    && ($payment['body']['resultInfo']['resultStatus'] == 'PENDING')
                ) {
                    $response['error'] = true;
                    $response['message'] = $payment['body']['resultInfo']['resultMsg'];
                    $response['amount'] = (isset($payment['body']['txnAmount'])) ? $payment['body']['txnAmount'] : 0;
                    $response['data'] = $payment;
                    return $response;
                } else {
                    $response['error'] = true;
                    $response['message'] = "Payment is unsuccessful!";
                    $response['amount'] = (isset($payment['body']['txnAmount'])) ? $payment['body']['txnAmount'] : 0;
                    $response['data'] = $payment;
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Payment not found by the Order ID!";
                $response['amount'] = 0;
                $response['data'] = [];
                return $response;
            }
            break;

        case 'paypal':
            # code...
            return "paypal is supplied";
            break;

        default:
            # code...
            $response['error'] = true;
            $response['message'] = "Could not validate the transaction with the supplied payment method";
            return $response;
            break;
    }
}

function process_referral_bonus($user_id, $order_id, $status)
{
    /* 
        $user_id = 99;              << user ID of the person whose order is being marked not the friend's ID who is going to get the bonus  
        $status = "delivered";      << current status of the order 
        $order_id = 644;            << Order which is being marked as delivered

    */
    $CI = &get_instance();
    $settings = get_settings('system_settings', true);
    if (isset($settings['is_refer_earn_on']) && $settings['is_refer_earn_on'] == 1 && $status == "delivered") {
        $user = fetch_users($user_id);

        /* check if user has set friends code or not */
        if (isset($user[0]['friends_code']) && !empty($user[0]['friends_code'])) {

            /* find number of previous orders of the user */
            $total_orders = fetch_details('orders', ['user_id' => $user_id], 'COUNT(id) as total');
            $total_orders = $total_orders[0]['total'];

            if ($total_orders < $settings['refer_earn_bonus_times']) {

                /* find a friends account details */
                $friend_user = fetch_details('users', ['referral_code' => $user[0]['friends_code']], 'id,username,email,mobile,balance');
                if (!empty($friend_user)) {
                    $order = fetch_orders($order_id);
                    $final_total = $order['order_data'][0]['final_total'];
                    if ($final_total >= $settings['min_refer_earn_order_amount']) {
                        $referral_bonus = 0;
                        if ($settings['refer_earn_method'] == 'percentage') {
                            $referral_bonus = $final_total * ($settings['refer_earn_bonus'] / 100);
                            if ($referral_bonus > $settings['max_refer_earn_amount']) {
                                $referral_bonus = $settings['max_refer_earn_amount'];
                            }
                        } else {
                            $referral_bonus = $settings['refer_earn_bonus'];
                        }

                        $referral_id = "refer-and-earn-" . $order_id;
                        $previous_referral = fetch_details('transactions', ['order_id' => $referral_id], 'id,amount');
                        if (empty($previous_referral)) {
                            $CI->load->model("transaction_model");
                            $transaction_data = [
                                'transaction_type' => "wallet",
                                'user_id' => $friend_user[0]['id'],
                                'order_id' => $referral_id,
                                'type' => "credit",
                                'txn_id' => "",
                                'amount' => $referral_bonus,
                                'status' => "success",
                                'message' => "Refer and Earn bonus on " . $user[0]['username'] . "'s order",
                            ];
                            $CI->transaction_model->add_transaction($transaction_data);
                            $CI->load->model('customer_model');
                            if ($CI->customer_model->update_balance($referral_bonus, $friend_user[0]['id'], 'add')) {
                                $response['error'] = false;
                                $response['message'] = "User's wallet credited successfully";
                                return $response;
                            }
                        } else {
                            $response['error'] = true;
                            $response['message'] = "Bonus is already given for the following order!";
                            return $response;
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = "This order amount is not eligible refer and earn bonus!";
                        return $response;
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Friend user not found for the used referral code!";
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Number of orders have exceeded the eligible first few orders!";
                return $response;
            }
        } else {
            $response['error'] = true;
            $response['message'] = "No friends code found!";
            return $response;
        }
    } else {
        if ($status == "delivered") {
            $response['error'] = true;
            $response['message'] = "Referred and earn system is turned off";
            return $response;
        } else {
            $response['error'] = true;
            $response['message'] = "Status must be set to delivered to get the bonus";
            return $response;
        }
    }
}

function process_refund($id, $status, $type = 'order_items')
{
    $possible_status = array("cancelled", "returned");
    if (!in_array($status, $possible_status)) {
        $response['error'] = true;
        $response['message'] = 'Refund cannot be processed. Invalid status';
        $response['data'] = array();
        return $response;
    }
    if ($type == 'order_items') {
        /* fetch order_id */
        $order_item_details = fetch_details('order_items', ['id' => $id], 'order_id,id');
        /* fetch order and its complete details with order_items */
        $order_id = $order_item_details[0]['order_id'];
        $order_details =  fetch_orders($order_id);
        $order_details = $order_details['order_data'];
        $order_items_details = $order_details[0]['order_items'];

        $key = array_search($id, array_column($order_items_details, 'id'));
        $current_price = $order_items_details[$key]['sub_total'];
        $order_item_id = $order_items_details[$key]['id'];
        $currency = (isset($system_settings['currency']) && !empty($system_settings['currency'])) ? $system_settings['currency'] : '';
        $payment_method = $order_details[0]['payment_method'];
        $total = $order_details[0]['total'];
        $is_delivery_charge_returnable = isset($order_details[0]['is_delivery_charge_returnable']) && $order_details[0]['is_delivery_charge_returnable'] == 1 ? '1' : '0';
        $delivery_charge = (isset($order_details[0]['delivery_charge']) && !empty($order_details[0]['delivery_charge'])) ? $order_details[0]['delivery_charge'] : 0;
        $promo_code = $order_details[0]['promo_code'];
        $promo_discount = $order_details[0]['promo_discount'];
        $final_total = $order_details[0]['final_total'];
        $wallet_balance = $order_details[0]['wallet_balance'];
        $total_payable = $order_details[0]['total_payable'];
        $user_id = $order_details[0]['user_id'];

        $order_items_count = $order_details[0]['order_items'][0]['order_counter'];
        $cancelled_items_count = $order_details[0]['order_items'][0]['order_cancel_counter'];
        $returned_items_count = $order_details[0]['order_items'][0]['order_return_counter'];
        $last_item = 0;

        $user_res = fetch_details('users', ['id' => $user_id],  'fcm_id');
        $fcm_ids = array();
        if (!empty($user_res[0]['fcm_id'])) {
            $fcm_ids[0][] = $user_res[0]['fcm_id'];
        }

        if (($cancelled_items_count + $returned_items_count) == $order_items_count) {
            $last_item = 1;
        }
        $new_total = $total - $current_price;
        /* recalculate delivery charge */
        $new_delivery_charge = ($new_total > 0) ? recalulate_delivery_charge($order_details[0]['address_id'], $new_total, $delivery_charge) : 0;
        /* recalculate promo discount */
        $new_promo_discount = recalculate_promo_discount($promo_code, $promo_discount, $user_id, $new_total, $payment_method, $new_delivery_charge, $wallet_balance);
        $new_final_total = $new_total + $new_delivery_charge - $new_promo_discount;
        $bank_receipt = fetch_details('order_bank_transfer', ['order_id' => $order_item_details[0]['order_id']]);
        $bank_receipt_status = (isset($bank_receipt[0]['status'])) ? $bank_receipt[0]['status'] : "";

        /* find returnable_amount, new_wallet_balance
        condition : 1
        */
        if (trim(strtolower($payment_method)) == 'cod' || $payment_method == 'Bank Transfer') {
            /* when payment method is COD or Bank Transfer and payment is not yet done */
            if (trim(strtolower($payment_method)) == 'cod' || ($payment_method == 'Bank Transfer' && (empty($bank_receipt_status) || $bank_receipt_status == "0" || $bank_receipt_status == "1"))) {
                $returnable_amount = ($wallet_balance <= $current_price) ? $wallet_balance : (($wallet_balance > 0) ? $current_price : 0);
                $returnable_amount = ($promo_discount != $new_promo_discount && $last_item == 0) ? $returnable_amount - $promo_discount + $new_promo_discount : $returnable_amount; /* if the new promo discount changed then adjust that here */
                $returnable_amount = ($returnable_amount < 0) ? 0 : $returnable_amount;

                /* if returnable_amount is 0 then don't change he wallet_balance */
                $new_wallet_balance = ($returnable_amount > 0) ? (($wallet_balance <= $current_price) ? 0 : (($wallet_balance - $current_price > 0) ? $wallet_balance - $current_price : 0)) : $wallet_balance;
            }
            /* if it is bank transfer and payment is already done by bank transfer 
            same as condition : 2
            */
        }

        /* if it is any other payment method or bank transfer with accepted receipts then payment is already done 
        condition : 2
        */
        if ((trim(strtolower($payment_method)) != 'cod' && $payment_method != 'Bank Transfer') || ($payment_method == 'Bank Transfer' && $bank_receipt_status == 2)) {
            $returnable_amount = $current_price;
            $returnable_amount = ($promo_discount != $new_promo_discount) ? $returnable_amount - $promo_discount + $new_promo_discount : $returnable_amount;
            $returnable_amount = ($last_item == 1 && $is_delivery_charge_returnable == 1) ? $returnable_amount + $delivery_charge : $returnable_amount;  /* if its the last item getting cancelled then check if we have to return delivery charge or not */
            $returnable_amount = ($returnable_amount < 0) ? 0 : $returnable_amount;
            $new_wallet_balance = ($last_item == 1) ? 0 : (($wallet_balance - $returnable_amount < 0) ? 0 : $wallet_balance - $returnable_amount);
        }

        /* find new_total_payable */
        if (trim(strtolower($payment_method)) != 'cod' && $payment_method != 'Bank Transfer') {
            /* online payment or any other payment method is used. and payment is already done */
            $new_total_payable = 0;
        } else {
            if ($bank_receipt_status == 2) {
                $new_total_payable = 0;
            } else {
                $new_total_payable = $new_final_total - $new_wallet_balance;
            }
        }

        if ($new_total == 0) {
            $new_total = $new_wallet_balance = $new_delivery_charge = $new_final_total = $new_total_payable = 0;
        }

        //custom message
        $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
        $hashtag_currency = '< currency >';
        $hashtag_returnable_amount = '< returnable_amount >';
        $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
        $hashtag = html_entity_decode($string);
        $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
        $message = output_escaping(trim($data, '"'));
        if ($returnable_amount > 0) {
            $fcmMsg = array(
                'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                'type' => "wallet",
            );
            send_notification($fcmMsg, $fcm_ids);
            //update_wallet_balance('credit', $user_id, $returnable_amount, 'Refund Amount Credited for Order Item ID  : ' . $id, $order_item_id);
            if ($order_details[0]['payment_method'] == 'RazorPay' || $order_details[0]['payment_method'] == 'razorpay' || $order_details[0]['payment_method'] == 'Razorpay') {

                update_wallet_balance('refund', $user_id, $returnable_amount, 'Amount Refund for Order Item ID  : ' . $id, $order_item_id, '', 'razorpay');
            } else {

                update_wallet_balance('credit', $user_id, $returnable_amount, 'Refund Amount Credited for Order Item ID  : ' . $id, $order_item_id);
            }
        }
        $set =  [
            'total' => $new_total,
            'final_total' => $new_final_total,
            'total_payable' => $new_total_payable,
            'promo_discount' => (!empty($new_promo_discount) && $new_promo_discount > 0) ? $new_promo_discount : 0,
            'delivery_charge' => $new_delivery_charge,
            'wallet_balance' => $new_wallet_balance
        ];
        update_details($set, ['id' => $order_id], 'orders');
        $response['error'] = false;
        $response['message'] = 'Status Updated Successfully';
        $response['data'] = array();
        return $response;
    } elseif ($type == 'orders') {
        /* if complete order is getting cancelled */
        $order_details =  fetch_orders($id);
        $order_item_details = fetch_details('order_items', ['order_id' => $order_details['order_data'][0]['id']], 'sum(tax_amount) as total_tax');
        $order_details = $order_details['order_data'];
        $payment_method = $order_details[0]['payment_method'];

        $wallet_refund = true;
        $bank_receipt = fetch_details('order_bank_transfer', ['order_id' => $id]);

        $is_transfer_accepted = 0;

        if ($payment_method == 'Bank Transfer') {
            if (!empty($bank_receipt)) {
                foreach ($bank_receipt as $receipt) {
                    if ($receipt['status'] == 2) {
                        $is_transfer_accepted = 1;
                        break;
                    }
                }
            }
        }
        if ($order_details[0]['wallet_balance'] == 0 && $status == 'cancelled' && $payment_method == 'Bank Transfer' && (!$is_transfer_accepted || empty($bank_receipt))) {
            $wallet_refund = false;
        } else {
            $wallet_refund = true;
        }

        $promo_discount = $order_details[0]['promo_discount'];
        $final_total = $order_details[0]['final_total'];
        $is_delivery_charge_returnable = isset($order_details[0]['is_delivery_charge_returnable']) && $order_details[0]['is_delivery_charge_returnable'] == 1 ? '1' : '0';
        $payment_method = trim(strtolower($payment_method));
        $total_tax_amount = $order_item_details[0]['total_tax'];
        $wallet_balance = $order_details[0]['wallet_balance'];
        $currency = (isset($system_settings['currency']) && !empty($system_settings['currency'])) ? $system_settings['currency'] : '';
        $user_id = $order_details[0]['user_id'];
        $fcmMsg = array(
            'title' => "Amount Credited To Wallet",
        );
        $user_res = fetch_details('users', ['id' => $user_id],  'fcm_id');
        $fcm_ids = array();
        if (!empty($user_res[0]['fcm_id'])) {
            $fcm_ids[0][] = $user_res[0]['fcm_id'];
        }
        if ($wallet_refund == true) {
            if ($payment_method != 'cod') {
                /* update user's wallet */
                if ($is_delivery_charge_returnable == 1) {
                    $returnable_amount =  $order_details[0]['total'] +  $order_details[0]['delivery_charge'];
                } else {
                    $returnable_amount =  $order_details[0]['total'];
                }

                if ($payment_method == 'bank transfer' && !$is_transfer_accepted) {
                    $returnable_amount =  $returnable_amount - $order_details[0]['total_payable'];
                }
                //send custom notifications
                $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
                $hashtag_currency = '< currency >';
                $hashtag_returnable_amount = '< returnable_amount >';
                $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                $hashtag = html_entity_decode($string);
                $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
                $message = output_escaping(trim($data, '"'));
                $fcmMsg = array(
                    'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                    'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                    'type' => "wallet",
                );
                send_notification($fcmMsg, $fcm_ids);

                update_wallet_balance('credit', $user_id, $returnable_amount, 'Wallet Amount Credited for Order Item ID  : ' . $id);
            } else {
                if ($wallet_balance != 0) {
                    /* update user's wallet */
                    $returnable_amount = $wallet_balance;
                    //send custom notifications
                    $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
                    $hashtag_currency = '< currency >';
                    $hashtag_returnable_amount = '< returnable_amount >';
                    $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                    $hashtag = html_entity_decode($string);
                    $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
                    $message = output_escaping(trim($data, '"'));
                    $fcmMsg = array(
                        'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                        'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                        'type' => "wallet",
                    );
                    send_notification($fcmMsg, $fcm_ids);

                    update_wallet_balance('credit', $user_id, $returnable_amount, 'Wallet Amount Credited for Order Item ID  : ' . $id);
                }
            }
        }
    }
}

function recalulate_delivery_charge($address_id, $total, $old_delivery_charge)
{
    $system_settings = get_settings('system_settings', true);
    $min_amount = $system_settings['min_amount'];
    $d_charge = $old_delivery_charge;

    if ((isset($system_settings['area_wise_delivery_charge']) && !empty($system_settings['area_wise_delivery_charge']))) {
        if (isset($address_id) && !empty($address_id)) {
            $address = fetch_details('addresses', ['id' => $address_id],  'area_id');
            if (isset($address[0]['area_id']) && !empty($address[0]['area_id'])) {
                $area = fetch_details('areas', ['id' => $address[0]['area_id']], 'minimum_free_delivery_order_amount');
                if (isset($area[0]['minimum_free_delivery_order_amount'])) {
                    $min_amount = $area[0]['minimum_free_delivery_order_amount'];
                }
            }
        }
    }
    if ($total < $min_amount) {
        if ($old_delivery_charge == 0) {
            if (isset($address_id) && !empty($address_id)) {
                $d_charge = get_delivery_charge($address_id);
            } else {
                $d_charge = $system_settings['delivery_charge'];
            }
        }
    }
    return $d_charge;
}

function recalculate_promo_discount($promo_code, $promo_discount, $user_id, $total, $payment_method, $delivery_charge, $wallet_balance)
{
    /* recalculate promocode discount if the status of the order_items is cancelled or returned */
    $promo_code_discount = $promo_discount;
    if (isset($promo_code) && !empty($promo_code)) {
        $promo_code = validate_promo_code($promo_code, $user_id, $total, true);
        if ($promo_code['error'] == false) {

            if ($promo_code['data'][0]['discount_type'] == 'percentage') {
                $promo_code_discount =  floatval($total  * $promo_code['data'][0]['discount'] / 100);
            } else {
                $promo_code_discount = $promo_code['data'][0]['discount'];
            }
            if (trim(strtolower($payment_method)) != 'cod'  && $payment_method != 'Bank Transfer') {
                /* If any other payment methods are used like razorpay, paytm, flutterwave or stripe then 
                    obviously customer would have paid complete amount so making total_payable = 0*/
                $total_payable = 0;
                if ($promo_code_discount > $promo_code['data'][0]['max_discount_amount']) {
                    $promo_code_discount = $promo_code['data'][0]['max_discount_amount'];
                }
            } else {
                /* also check if the previous discount and recalculated discount are 
                    different or not, then only modify total_payable*/
                if ($promo_code_discount <= $promo_code['data'][0]['max_discount_amount'] && $promo_discount != $promo_code_discount) {
                    $total_payable = floatval($total) + $delivery_charge - $promo_code_discount - $wallet_balance;
                } else if ($promo_discount != $promo_code_discount) {
                    $total_payable = floatval($total) + $delivery_charge - $promo_code['data'][0]['max_discount_amount'] - $wallet_balance;
                    $promo_code_discount = $promo_code['data'][0]['max_discount_amount'];
                }
            }
        } else {
            $promo_code_discount = 0;
        }
    }
    return $promo_code_discount;
}

function process_refund_old($id, $status, $type = 'order_items')
{
    /**
     * @param
     * type : orders / order_items
     */
    $possible_status = array("cancelled", "returned");
    if (!in_array($status, $possible_status)) {
        $response['error'] = true;
        $response['message'] = 'Refund cannot be processed. Invalid status';
        $response['data'] = array();
        return $response;
    }
    if ($type == 'order_items') {
        $order_item_details = fetch_details('order_items', ['id' => $id], 'order_id');
        $order_details =  fetch_orders($order_item_details[0]['order_id']);
        if (!empty($order_details) && !empty($order_item_details)) {
            $order_details = $order_details['order_data'];
            $wallet_refund = true;
            $wallet_balance = 0;
            $wallet_balance = $order_details[0]['wallet_balance'];
            $payment_method = $order_details[0]['payment_method'];
            $bank_receipt = fetch_details('order_bank_transfer', ['order_id' => $order_item_details[0]['order_id']]);
            if ($status == 'cancelled' && $payment_method == 'Bank Transfer' && ($bank_receipt[0]['status'] == "0" || $bank_receipt[0]['status'] == "1" || empty($bank_receipt))) {
                if ($wallet_balance == "" || empty($wallet_balance)) {
                    $wallet_refund = false;
                } else {
                    $wallet_refund = true;
                }
            } else {
                $wallet_refund = true;
            }

            $order_items_details = $order_details[0]['order_items'];
            $is_delivery_charge_returnable = isset($order_details[0]['is_delivery_charge_returnable']) && $order_details[0]['is_delivery_charge_returnable'] == 1 ? '1' : '0';
            $total_tax_amount = $order_details[0]['total_tax_amount'];
            $final_total = $order_details[0]['final_total'];
            $total = $order_details[0]['total'] + $total_tax_amount;
            $total_payable = $order_details[0]['total_payable'];
            $key = array_search($id, array_column($order_items_details, 'id'));
            $order_id = $order_details[0]['id'];
            $promo_discount = $order_details[0]['promo_discount'];
            $user_id = $order_details[0]['user_id'];
            $system_settings = get_settings('system_settings', true);
            $currency = (isset($system_settings['currency']) && !empty($system_settings['currency'])) ? $system_settings['currency'] : '';
            $delivery_charge = (isset($order_details[0]['delivery_charge']) && !empty($order_details[0]['delivery_charge'])) ? $order_details[0]['delivery_charge'] : 0;
            $current_price = $order_items_details[$key]['sub_total'];
            $tax_amount = $order_items_details[$key]['tax_amount'];
            $order_counter = $order_items_details[$key]['order_counter'];
            $order_cancel_counter = $order_items_details[$key]['order_cancel_counter'];
            $order_return_counter = $order_items_details[$key]['order_return_counter'];
            $returnable_amount = 0;
            $user_res = fetch_details('users', ['id' => $user_id], 'fcm_id');
            $fcm_ids = array();
            if (!empty($user_res[0]['fcm_id'])) {
                $fcm_ids[0][] = $user_res[0]['fcm_id'];
            }

            if ($wallet_refund == true) {
                $new_final_total = floatval($final_total - $current_price);
                if ($new_final_total >= $promo_discount) {
                    if (trim(strtolower($payment_method)) != 'cod' && $payment_method != 'Bank Transfer') {
                        if ((($order_counter == $order_cancel_counter && $status == 'cancelled') ||  ($order_counter == $order_return_counter && $status == 'returned')) && $is_delivery_charge_returnable == 1) {
                            $returnable_amount = $current_price - $promo_discount + $delivery_charge;
                        } else {
                            $returnable_amount = $current_price - $promo_discount;
                        }
                        //send custom notifications
                        $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
                        $hashtag_currency = '< currency >';
                        $hashtag_returnable_amount = '< returnable_amount >';
                        $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                        $hashtag = html_entity_decode($string);
                        $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
                        $message = output_escaping(trim($data, '"'));
                        $fcmMsg = array(
                            'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                            'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                            'type' => "wallet",
                        );
                        send_notification($fcmMsg, $fcm_ids);
                        update_wallet_balance('credit', $user_id, $returnable_amount, 'Refund Amount Credited for Order Item ID  : ' . $id);
                        if ($wallet_balance != 0) {
                            $wallet_balance = $wallet_balance >= $returnable_amount ? $wallet_balance - $returnable_amount : 0;
                        }
                        $total = $total - $returnable_amount < 0 ? 0 : $total - $returnable_amount;
                        $final_total = $final_total - $returnable_amount < 0 ? 0 : $final_total - $returnable_amount;
                        $total_payable = $total_payable - $returnable_amount < 0 ? 0 : $total_payable - $returnable_amount;
                    } else {
                        if ($current_price <=  $wallet_balance) {
                            if ((($order_counter == $order_cancel_counter && $status == 'cancelled') ||  ($order_counter == $order_return_counter && $status == 'returned')) && $is_delivery_charge_returnable == 1) {
                                $returnable_amount = $current_price - $promo_discount + $delivery_charge;
                            } else {
                                $returnable_amount = $current_price - $promo_discount;
                            }
                            //send custom notifications
                            $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
                            $hashtag_currency = '< currency >';
                            $hashtag_returnable_amount = '< returnable_amount >';
                            $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                            $hashtag = html_entity_decode($string);
                            $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
                            $message = output_escaping(trim($data, '"'));
                            $fcmMsg = array(
                                'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                                'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                                'type' => "wallet",
                            );
                            send_notification($fcmMsg, $fcm_ids);
                            update_wallet_balance('credit', $user_id, $returnable_amount, 'Wallet Amount Credited for Order Item ID  : ' . $id);

                            if ($wallet_balance != 0) {
                                $wallet_balance = $wallet_balance >= $returnable_amount ? $wallet_balance - $returnable_amount : 0;
                            }
                            $total = $total - $returnable_amount < 0 ? 0 : $total - $returnable_amount;
                            $final_total = $final_total - $returnable_amount < 0 ? 0 : $final_total - $returnable_amount;
                            $total_payable = $total_payable - $returnable_amount < 0 ? 0 : $total_payable - $returnable_amount;
                        } else {
                            if ($wallet_balance > 0) {
                                if ($wallet_balance <= $current_price) {
                                    $returnable_amount = $wallet_balance;
                                    //send custom notifications
                                    $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
                                    $hashtag_currency = '< currency >';
                                    $hashtag_returnable_amount = '< returnable_amount >';
                                    $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                                    $hashtag = html_entity_decode($string);
                                    $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
                                    $message = output_escaping(trim($data, '"'));
                                    $fcmMsg = array(
                                        'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                                        'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                                        'type' => "wallet",
                                    );
                                    send_notification($fcmMsg, $fcm_ids);
                                    update_wallet_balance('credit', $user_id, $returnable_amount, 'Wallet Amount Credited for Order Item ID  : ' . $id);
                                    $wallet_balance = 0;
                                    $total = $total - $current_price < 0 ? 0 : $total - $current_price;
                                    $final_total = $final_total - $current_price < 0 ? 0 : $final_total - $current_price;
                                    $total_payable = $total_payable - $current_price < 0 ? 0 : $total_payable - $current_price;
                                } else {
                                    $returnable_amount = $current_price;
                                    //send custom notifications
                                    $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
                                    $hashtag_currency = '< currency >';
                                    $hashtag_returnable_amount = '< returnable_amount >';
                                    $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                                    $hashtag = html_entity_decode($string);
                                    $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
                                    $message = output_escaping(trim($data, '"'));
                                    $fcmMsg = array(
                                        'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                                        'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                                        'type' => "wallet",
                                    );
                                    send_notification($fcmMsg, $fcm_ids);
                                    update_wallet_balance('credit', $user_id, $returnable_amount, 'Wallet Amount Credited for Order Item ID  : ' . $id);
                                    $wallet_balance = $wallet_balance - $returnable_amount >= 0 ? $wallet_balance - $returnable_amount : 0;
                                    $total = $total - $returnable_amount < 0 ? 0 : $total - $returnable_amount;
                                    $final_total = $final_total - $returnable_amount < 0 ? 0 : $final_total - $returnable_amount;
                                    $total_payable = $total_payable - $returnable_amount < 0 ? 0 : $total_payable - $returnable_amount;
                                }
                            } else {
                                $total = $total - $current_price < 0 ? 0 : $total - $current_price;
                                $final_total = $final_total - $current_price < 0 ? 0 : $final_total - $current_price;
                                $total_payable = $total_payable - $current_price < 0 ? 0 : $total_payable - $current_price;
                            }
                        }
                    }
                } else {

                    if (trim(strtolower($payment_method)) != 'cod') {
                        if ((($order_counter == $order_cancel_counter && $status == 'cancelled') ||  ($order_counter == $order_return_counter && $status == 'returned')) && $is_delivery_charge_returnable == 1) {
                            $returnable_amount = $current_price - $promo_discount + $delivery_charge;
                        } else {
                            $returnable_amount = $current_price - $promo_discount;
                        }
                        //send custom notifications
                        $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
                        $hashtag_currency = '< currency >';
                        $hashtag_returnable_amount = '< returnable_amount >';
                        $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                        $hashtag = html_entity_decode($string);
                        $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
                        $message = output_escaping(trim($data, '"'));
                        $fcmMsg = array(
                            'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                            'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                            'type' => "wallet",
                        );
                        send_notification($fcmMsg, $fcm_ids);
                        update_wallet_balance('credit', $user_id, $returnable_amount, 'Refund Amount Credited for Order Item ID  : ' . $id);
                        if ($wallet_balance != 0) {
                            $wallet_balance = $wallet_balance >= $returnable_amount ? $wallet_balance - $returnable_amount : 0;
                        }
                        $total = $total - $returnable_amount < 0 ? 0 : $total - $returnable_amount;
                        $final_total = $final_total - $returnable_amount < 0 ? 0 : $final_total - $returnable_amount;
                        $total_payable = $total_payable - $returnable_amount < 0 ? 0 : $total_payable - $returnable_amount;
                    } else {

                        if ($current_price <=  $wallet_balance) {
                            if ($wallet_balance > 0) {
                                if ($wallet_balance <= $current_price) {
                                    $returnable_amount = $wallet_balance;
                                    //send custom notifications
                                    $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
                                    $hashtag_currency = '< currency >';
                                    $hashtag_returnable_amount = '< returnable_amount >';
                                    $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                                    $hashtag = html_entity_decode($string);
                                    $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
                                    $message = output_escaping(trim($data, '"'));
                                    $fcmMsg = array(
                                        'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                                        'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                                        'type' => "wallet",
                                    );
                                    send_notification($fcmMsg, $fcm_ids);
                                    update_wallet_balance('credit', $user_id, $returnable_amount, 'Wallet Amount Credited for Order Item ID  : ' . $id);

                                    $wallet_balance = 0;
                                    $total = $total - $returnable_amount < 0 ? 0 : $total - $returnable_amount;
                                    $final_total = $final_total - $returnable_amount < 0 ? 0 : $final_total - $returnable_amount;
                                    $total_payable = $total_payable - $returnable_amount < 0 ? 0 : $total_payable - $returnable_amount;
                                } else {
                                    $returnable_amount = $current_price;
                                    //send custom notifications
                                    $custom_notification = fetch_details('custom_notifications', ['type' => "wallet_transaction"], '');
                                    $hashtag_currency = '< currency >';
                                    $hashtag_returnable_amount = '< returnable_amount >';
                                    $string = json_encode($custom_notification[0]['message'], JSON_UNESCAPED_UNICODE);
                                    $hashtag = html_entity_decode($string);
                                    $data = str_replace(array($hashtag_currency, $hashtag_returnable_amount), array($currency, $returnable_amount), $hashtag);
                                    $message = output_escaping(trim($data, '"'));
                                    $fcmMsg = array(
                                        'title' => (!empty($custom_notification)) ? $custom_notification[0]['title'] : "Amount Credited To Wallet",
                                        'body' => (!empty($custom_notification)) ? $message : $currency . ' ' . $returnable_amount,
                                        'type' => "wallet",
                                    );
                                    send_notification($fcmMsg, $fcm_ids);
                                    update_wallet_balance('credit', $user_id, $returnable_amount, 'Wallet Amount Credited for Order Item ID  : ' . $id);

                                    $wallet_balance = $wallet_balance - $returnable_amount >= 0 ? $wallet_balance - $returnable_amount : 0;
                                    $total = $total - $returnable_amount < 0 ? 0 : $total - $returnable_amount;
                                    $final_total = $final_total - $returnable_amount < 0 ? 0 : $final_total - $returnable_amount;
                                    $total_payable = $total_payable - $returnable_amount < 0 ? 0 : $total_payable - $returnable_amount;
                                }
                            } else {
                                $total = $total - $current_price < 0 ? 0 : $total - $current_price;
                                $final_total = $final_total - $current_price < 0 ? 0 : $final_total - $current_price;
                                $total_payable = $total_payable - $current_price < 0 ? 0 : $total_payable - $current_price;
                            }
                        } else {
                            $total = $total - $current_price < 0 ? 0 : $total - $current_price;
                            $final_total = $final_total - $current_price < 0 ? 0 : $final_total - $current_price;
                            $total_payable = $total_payable - $current_price < 0 ? 0 : $total_payable - $current_price;
                        }
                    }
                }
            }
            $system_settings = get_settings('system_settings', true);
            $min_amount = $system_settings['min_amount'];
            if ((isset($system_settings['area_wise_delivery_charge']) && !empty($system_settings['area_wise_delivery_charge']))) {
                if (isset($order_details[0]['address_id']) && !empty($order_details[0]['address_id'])) {
                    $address = fetch_details('addresses', ['id' => $order_details[0]['address_id']], 'area_id');
                    if (isset($address[0]['area_id']) && !empty($address[0]['area_id'])) {
                        $area = fetch_details('areas', ['id' => $address[0]['area_id']], 'minimum_free_delivery_order_amount');
                        if (isset($area[0]['minimum_free_delivery_order_amount'])) {
                            $min_amount = $area[0]['minimum_free_delivery_order_amount'];
                        }
                    }
                }
            }
            if ($total < $min_amount) {
                if ($delivery_charge == 0) {
                    if (isset($order_details[0]['address_id']) && !empty($order_details[0]['address_id'])) {
                        $d_charge = get_delivery_charge($order_details[0]['address_id']);
                    } else {
                        $d_charge = $system_settings['delivery_charge'];
                    }
                    $delivery_charge = $d_charge;
                    $final_total += $d_charge;
                    $total_payable += $d_charge;
                }
            }

            if ($total == 0) {
                $total = $wallet_balance = $delivery_charge = $final_total = $total_payable = 0;
            }

            $set =  [
                'total' => $total,
                'final_total' => $final_total,
                'total_payable' => $total_payable,
                'delivery_charge' => $delivery_charge,
                'wallet_balance' => $wallet_balance
            ];

            update_details($set, ['id' => $order_id], 'orders');

            $response['error'] = false;
            $response['message'] = 'Status Updated Successfully';
            $response['data'] = array();
            return $response;
        }
    }
}

function get_sliders($id = '', $type = '', $type_id = '')
{
    $ci = &get_instance();
    if (!empty($id)) {
        $ci->db->where('id', $id);
    }
    if (!empty($type)) {
        $ci->db->where('type', $type);
    }
    if (!empty($type_id)) {
        $ci->db->where('type_id', $type_id);
    }
    $res = $ci->db->get('sliders')->result_array();
    $res = array_map(function ($d) {
        $ci = &get_instance();
        $d['link'] = '';
        if (!empty($d['type'])) {
            if ($d['type'] == "categories") {
                $type_details = $ci->db->where('id', $d['type_id'])->select('slug')->get('categories')->row_array();
                if (!empty($type_details)) {
                    $d['link'] = base_url('products/category/' . $type_details['slug']);
                }
            } elseif ($d['type'] == "products") {
                $type_details = $ci->db->where('id', $d['type_id'])->select('slug')->get('products')->row_array();
                if (!empty($type_details)) {
                    $d['link'] = base_url('products/details/' . $type_details['slug']);
                }
            }
        }
        return $d;
    }, $res);
    return $res;
}

function get_offers($id = '', $type = '', $type_id = '')
{
    $ci = &get_instance();
    if (!empty($id)) {
        $ci->db->where('id', $id);
    }
    if (!empty($type)) {
        $ci->db->where('type', $type);
    }
    if (!empty($type_id)) {
        $ci->db->where('type_id', $type_id);
    }
    $res = $ci->db->get('offers')->result_array();
    $res = array_map(function ($d) {
        $ci = &get_instance();
        $d['link'] = '';
        if (!empty($d['type'])) {
            if ($d['type'] == "categories") {
                $type_details = $ci->db->where('id', $d['type_id'])->select('slug')->get('categories')->row_array();
                if (!empty($type_details)) {
                    $d['link'] = base_url('products/category/' . $type_details['slug']);
                }
            } elseif ($d['type'] == "products") {
                $type_details = $ci->db->where('id', $d['type_id'])->select('slug')->get('products')->row_array();
                if (!empty($type_details)) {
                    $d['link'] = base_url('products/details/' . $type_details['slug']);
                }
            }
        }
        return $d;
    }, $res);
    return $res;
}
function get_cart_count($user_id)
{
    $ci = &get_instance();
    if (!empty($user_id)) {
        $ci->db->where('user_id', $user_id);
    }
    $ci->db->where('qty !=', 0);
    $ci->db->where('is_saved_for_later =', 0);
    $ci->db->distinct();
    $ci->db->select('count(id) as total');
    $res = $ci->db->get('cart')->result_array();
    return $res;
}
function is_variant_available_in_cart($product_variant_id, $user_id)
{
    $ci = &get_instance();
    $ci->db->where('product_variant_id', $product_variant_id);
    $ci->db->where('user_id', $user_id);
    $ci->db->where('qty !=', 0);
    $ci->db->where('is_saved_for_later =', 0);
    $ci->db->select('id');
    $res = $ci->db->get('cart')->result_array();
    if (!empty($res[0]['id'])) {
        return true;
    } else {
        return false;
    }
}
function get_user_balance($user_id)
{
    $ci = &get_instance();
    $ci->db->where('id', $user_id);
    $ci->db->select('balance');
    $res = $ci->db->get('users')->result_array();
    if (!empty($res[0]['balance'])) {
        return $res[0]['balance'];
    } else {
        return "0";
    }
}

function get_stock($id, $type)
{
    $t = &get_instance();
    $t->db->where('id', $id);
    if ($type == 'variant') {
        $response = $t->db->select('stock')->get('product_variants')->result_array();
    } else {
        $response = $t->db->select('stock')->get('products')->result_array();
    }
    $stock = isset($response[0]['stock']) ? $response[0]['stock'] : null;
    return $stock;
}
function get_delivery_charge($address_id, $total = 0)
{
    $total = str_replace(',', '', $total);
    $system_settings = get_settings('system_settings', true);
    $address = fetch_details('addresses', ['id' => $address_id], 'area_id');
    $min_amount = $system_settings['min_amount'];
    $delivery_charge = $system_settings['delivery_charge'];
    if ((isset($system_settings['area_wise_delivery_charge']) && !empty($system_settings['area_wise_delivery_charge']))) {
        if (isset($address[0]['area_id']) && !empty($address[0]['area_id'])) {
            $area = fetch_details('areas', ['id' => $address[0]['area_id']], 'delivery_charges,minimum_free_delivery_order_amount');
            if (isset($area[0]['minimum_free_delivery_order_amount'])) {
                $min_amount = $area[0]['minimum_free_delivery_order_amount'];
                $delivery_charge = $area[0]['delivery_charges'];
            }
        }
    }
    if ($total < $min_amount || $total = 0) {
        $d_charge = $delivery_charge;
    } else {
        $d_charge = 0;
    }

    return number_format($d_charge, 2);
}
function validate_otp($order_item_id, $otp)
{
    $res = fetch_details('order_items', ['id' => $order_item_id], 'otp');
    if ($res[0]['otp'] == 0 || $res[0]['otp'] == $otp) {
        return true;
    } else {
        return false;
    }
}

function is_product_delivarable($type, $type_id, $product_id)
{
    $ci = &get_instance();
    $zipcode_id = 0;
    if ($type == 'zipcode') {
        $zipcode_id = $type_id;
    } else if ($type == 'area') {
        $res = fetch_details('areas', ['id' => $type_id], 'zipcode_id');
        $zipcode_id = $res[0]['zipcode_id'];
    } else {
        return false;
    }
    if (!empty($zipcode_id)) {
        $ci->db->select('id');
        $ci->db->group_Start();
        $where = "((deliverable_type='2' and FIND_IN_SET('$zipcode_id', deliverable_zipcodes)) or deliverable_type = '1') OR (deliverable_type='3' and NOT FIND_IN_SET('$zipcode_id', deliverable_zipcodes)) ";
        $ci->db->where($where);
        $ci->db->group_End();
        $ci->db->where("id = $product_id");
        $product = $ci->db->get('products')->num_rows();
        if ($product > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function check_cart_products_delivarable($area_id, $user_id)
{
    $products = $tmpRow = array();
    $cart = get_cart_total($user_id);
    if (!empty($cart)) {
        for ($i = 0; $i < $cart[0]['cart_count']; $i++) {
            $tmpRow['product_id'] = $cart[$i]['product_id'];
            $tmpRow['variant_id'] = $cart[$i]['id'];
            $tmpRow['name'] = $cart[$i]['name'];
            $tmpRow['is_deliverable'] = (is_product_delivarable($type = 'area', $area_id, $cart[$i]['product_id'])) ? true : false;
            $products[] = $tmpRow;
        }
        if (!empty($products)) {
            return $products;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function orders_count($status = "", $seller_id = "", $order_type = "")
{

    $t = &get_instance();
    // $t->db->select('count(DISTINCT `order_id`) total');
    // if (!empty($status)) {
    //     $t->db->where('active_status', $status);
    // }
    // if (!empty($seller_id)) {
    //     $t->db->where('seller_id', $seller_id);
    //     // $t->db->where("active_status != 'awaiting' ");
    // }
    // $result = $t->db->from("order_items")->get()->result_array();
    // return $result[0]['total'];
    $where = [];
    $count_res = $t->db->select(' COUNT(distinct oi.order_id) as `total`')
        ->join(' `orders` o', 'o.id= oi.order_id', 'left')
        ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
        ->join('products p', 'pv.product_id=p.id', 'left');

    if (isset($order_type) && $order_type != '' && $order_type == 'digital') {
        $where['p.type'] = 'digital_product';
        $where['oi.active_status'] = $status;
    }
    if (isset($order_type) && $order_type != '' && $order_type == 'simple') {
        $where['p.type!='] = 'digital_product';
        $where['oi.active_status'] = $status;
    }
    if ($order_type == '' && !empty($status)) {
        $where['oi.active_status'] = $status;
    }
   
    if (!empty($seller_id)) {
        $where['oi.seller_id'] = $seller_id;
        $where['oi.active_status'] != 'awaiting';
    }
   
    $count_res->where($where);
    $result =  $count_res->get('`order_items` oi')->result_array();
    return $result[0]['total'];
}

function delivery_boy_orders_count($status = "", $delivery_boy_id = "")
{
    $t = &get_instance();
    $t->db->select('count(DISTINCT `order_id`) total');
    if (!empty($status)) {
        $t->db->where('active_status', $status);
    }
    if (!empty($delivery_boy_id)) {
        $t->db->where('delivery_boy_id', $delivery_boy_id);
        // $t->db->where("active_status != 'awaiting' ");
    }
    $result = $t->db->from("order_items")->get()->result_array();
    return $result[0]['total'];
}

function curl($url, $method = 'GET', $data = [], $authorization = "")
{
    $ch = curl_init();
    $curl_options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
        )
    );

    if (!empty($authorization)) {
        $curl_options['CURLOPT_HTTPHEADER'][] = $authorization;
    }

    if (strtolower($method) == 'post') {
        $curl_options[CURLOPT_POST] = 1;
        $curl_options[CURLOPT_POSTFIELDS] = http_build_query($data);
    } else {
        $curl_options[CURLOPT_CUSTOMREQUEST] = 'GET';
    }
    curl_setopt_array($ch, $curl_options);

    $result = array(
        'body' => json_decode(curl_exec($ch), true),
        'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
    );
    return $result;
}

function get_seller_permission($seller_id, $permit = NULL)
{
    $t = &get_instance();
    $seller_id = (isset($seller_id) && !empty($seller_id)) ? $seller_id : $t->session->userdata('user_id');
    $permits = fetch_details('seller_data', ['user_id' => $seller_id], 'permissions');
    if (!empty($permit)) {
        $s_permits = json_decode($permits[0]['permissions'], true);
        return $s_permits[$permit];
    } else {
        return json_decode($permits[0]['permissions']);
    }
}

function get_price($type = "max")
{
    $t = &get_instance();
    $t->db->select('IF( pv.special_price > 0, `pv`.`special_price`, pv.price ) as pr_price')
        ->join(" categories c", "p.category_id=c.id ", 'LEFT')
        ->join(" seller_data sd", "p.seller_id=sd.user_id ")
        ->join('`product_variants` pv', 'p.id = pv.product_id', 'LEFT')
        ->join('`product_attributes` pa', ' pa.product_id = p.id ', 'LEFT');
    $t->db->where(" `p`.`status` = '1' AND `pv`.`status` = 1 AND `sd`.`status` = 1 AND   (`c`.`status` = '1' OR `c`.`status` = '0')");
    $result = $t->db->from("products p ")->get()->result_array();
    if (isset($result) && !empty($result)) {
        $pr_price = array_column($result, 'pr_price');
        $data = ($type == "min") ? min($pr_price) : max($pr_price);
    } else {
        $data = 0;
    }
    return $data;
}

function check_for_parent_id($category_id)
{
    $t = &get_instance();
    $t->db->select('id,parent_id,name');
    $t->db->where('id', $category_id);
    $result = $t->db->from("categories")->get()->result_array();
    if (!empty($result)) {
        return $result;
    } else {
        return false;
    }
}

function update_balance($amount, $delivery_boy_id, $action)
{
    $t = &get_instance();

    if ($action == "add") {
        $t->db->set('balance', 'balance+' . $amount, FALSE);
    } elseif ($action == "deduct") {
        $t->db->set('balance', 'balance-' . $amount, FALSE);
    }
    return $t->db->where('id', $delivery_boy_id)->update('users');
}

function update_cash_received($amount, $delivery_boy_id, $action)
{
    $t = &get_instance();
    if ($action == "add") {
        $t->db->set('cash_received', 'cash_received+' . $amount, FALSE);
    } elseif ($action == "deduct") {
        $t->db->set('cash_received', 'cash_received-' . $amount, FALSE);
    }
    return $t->db->where('id', $delivery_boy_id)->update('users');
}

function word_limit($string, $length = WORD_LIMIT, $dots = "...")
{
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}
function description_word_limit($string, $length = DESCRIPTION_WORD_LIMIT, $dots = "...")
{
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}
function calculate_tax_inclusive($original_cost, $tax)
{
    $tax_amount = ($original_cost * (100 / (100 + $tax)));
    $Net_price = $original_cost - $tax_amount;
    return $Net_price;
}
function labels($label, $alt = '')
{
    $label = trim($label);
    if (lang('Text.' . $label) != 'Text.' . $label) {
        if (lang('Text.' . $label) == '') {
            return $alt;
        }
        return trim(lang('Text.' . $label));
    } else {
        return trim($alt);
    }
}


function is_single_seller($product_variant_id, $user_id)
{
    $t = &get_instance();
    if (isset($product_variant_id) && !empty($product_variant_id) && $product_variant_id != "" && isset($user_id) && !empty($user_id) && $user_id != "") {
        $pv_id = (strpos($product_variant_id, ",")) ? explode(",", $product_variant_id) : $product_variant_id;

        // get exist data from cart if any 
        $exist_data = $t->db->select('`c`.product_variant_id,p.seller_id')
            ->join('product_variants pv ', 'pv.id=c.product_variant_id')
            ->join('products p ', 'pv.product_id=p.id')
            ->where(['user_id' => $user_id, 'is_saved_for_later' => 0])->group_by('p.seller_id')->get('cart c')->result_array();
        if (!empty($exist_data)) {
            $seller_id = array_values(array_unique(array_column($exist_data, "seller_id")));
        } else {
            // clear to add cart
            return true;
        }
        // get seller ids of varients
        $new_data = $t->db->select('p.seller_id')
            ->join('products p ', 'pv.product_id=p.id')
            ->where_in('pv.id', $pv_id)->get('product_variants pv')->result_array();
        $new_seller_id = $new_data[0]["seller_id"];
        if (!empty($seller_id) && !empty($new_seller_id)) {
            if (in_array($new_seller_id, $seller_id)) {
                // clear to add to cart
                return true;
            } else {
                // another seller id verient, give single seller error
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function is_single_product_type($product_variant_id, $user_id)
{
    $t = &get_instance();
    if (isset($product_variant_id) && !empty($product_variant_id) && $product_variant_id != "" && isset($user_id) && !empty($user_id) && $user_id != "") {
        $pv_id = (strpos($product_variant_id, ",")) ? explode(",", $product_variant_id) : $product_variant_id;

        // get exist data from cart if any 
        $exist_data = $t->db->select('`c`.product_variant_id,p.type')
            ->join('product_variants pv ', 'pv.id=c.product_variant_id')
            ->join('products p ', 'pv.product_id=p.id')
            ->where(['user_id' => $user_id, 'is_saved_for_later' => 0])->group_by('p.type')->get('cart c')->result_array();
        if (!empty($exist_data)) {
            $product_type = array_values(array_unique(array_column($exist_data, "type")));
        } else {
            // clear to add cart
            return true;
        }
        // get product types of varients
        $new_data = $t->db->select('p.type')
            ->join('products p ', 'pv.product_id=p.id')
            ->where_in('pv.id', $pv_id)->get('product_variants pv')->result_array();
        $new_product_type = $new_data[0]["type"];
        if (!empty($product_type) && !empty($new_product_type)) {
            if (in_array($new_product_type, $product_type)) {
                // clear to add to cart
                return true;
            } else {
                if (!in_array("digital_product", $product_type) && ($new_product_type == "variable_product" || $new_product_type == "simple_product")) {
                    return true;
                } else {
                    // another product type, give single product type
                    return false;
                }
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
