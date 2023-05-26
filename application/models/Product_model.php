<?php


defined('BASEPATH') or exit('No direct script access allowed');


class Product_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }
    public function add_product($data)
    {
        $data = escape_array($data);


        if ($data['product_type'] == 'simple_product' || $data['product_type'] == 'variable_product') {
            $pro_type = ($data['product_type'] == 'simple_product') ? 'simple_product' : 'variable_product';
        } else {
            $pro_type = ($data['product_type'] == 'digital_product') ? 'digital_product' : '';
        }
        $short_description = $data['short_description'];
        $category_id = $data['category_id'];
        $seller_id = $data['seller_id'];

        // get seller product release permission
        $permits = fetch_details('seller_data', ['user_id' => $seller_id], 'permissions');
        $s_permits = json_decode($permits[0]['permissions'], true);
        if (isset($data['edit_product_id']) && !empty($data['edit_product_id'])) {
            $edit_status = fetch_details('products', ['id' => $data['edit_product_id']], 'status');
            $require_products_approval = isset($data['status']) && ($data['status'] != '') ? $data['status'] : $edit_status[0]['status'];
        } else {
            $is_permit = (isset($s_permits['require_products_approval']) && $s_permits['require_products_approval'] == 0) ? 1 : 2;
            $require_products_approval = $is_permit;
        }
        $made_in = (isset($data['made_in'])) ? $data['made_in'] : null;
        $brand = (isset($data['brand'])) ? $data['brand'] : null;
        $indicator = $data['indicator'];
        $description = $data['pro_input_description'];
        $tags = (!empty($data['tags'])) ? $data['tags'] : "";
        $slug   = create_unique_slug($data['pro_input_name'], 'products');
        $main_image_name = $data['pro_input_image'];
        $other_images = (isset($data['other_images']) && !empty($data['other_images'])) ? $data['other_images'] : [];
        if (isset($data['product_type']) && $data['product_type'] == 'digital_product') {
            $total_allowed_quantity = 1;
        } else {
            $total_allowed_quantity = (isset($data['total_allowed_quantity']) && !empty($data['total_allowed_quantity'])) ? $data['total_allowed_quantity'] : null;
        }

        $minimum_order_quantity = (isset($data['minimum_order_quantity']) && !empty($data['minimum_order_quantity'])) ? $data['minimum_order_quantity'] : 1;
        $quantity_step_size = (isset($data['quantity_step_size']) && !empty($data['quantity_step_size'])) ? $data['quantity_step_size'] : 1;
        $warranty_period = (isset($data['warranty_period']) && !empty($data['warranty_period'])) ? $data['warranty_period'] : "";
        $guarantee_period = (isset($data['guarantee_period']) && !empty($data['guarantee_period'])) ? $data['guarantee_period'] : "";
        $tax = (isset($data['pro_input_tax']) && $data['pro_input_tax'] != 0 && !empty($data['pro_input_tax'])) ? $data['pro_input_tax'] : 0;
        $video_type = (isset($data['video_type']) && !empty($data['video_type'])) ? $data['video_type'] : "";
        $video = (!empty($video_type)) ? (($video_type == 'youtube' || $video_type == 'vimeo') ? $data['video'] : $data['pro_input_video']) : "";
        $hsn_code = (isset($data['hsn_code']) && !empty($data['hsn_code'])) ? $data['hsn_code'] : "";
        $download_type = (isset($data['download_link_type']) && !empty($data['download_link_type'])) ? $data['download_link_type'] : "";
        $download_link = (!empty($download_type)) ? (($download_type == 'add_link') ? $data['download_link'] : $data['pro_input_zip']) : "";

        $pro_data = [
            'name' => $data['pro_input_name'],
            'short_description' => $short_description,
            'slug' => $slug,
            'type' => $pro_type,
            'tax' => $tax,
            'category_id' => $category_id,
            'seller_id' => $seller_id,
            'made_in' => $made_in,
            'brand' => $brand,
            'indicator' => $indicator,
            'image' => $main_image_name,
            'total_allowed_quantity' => $total_allowed_quantity,
            'minimum_order_quantity' => $minimum_order_quantity,
            'quantity_step_size' => $quantity_step_size,
            'warranty_period' => $warranty_period,
            'guarantee_period' => $guarantee_period,
            'other_images' => $other_images,
            'video_type' => $video_type,
            'video' => $video,
            'tags' => $tags,
            'status' => $require_products_approval,
            'description' => $description,
            'deliverable_type' => isset($data['deliverable_type']) && !empty($data['deliverable_type']) ? $data['deliverable_type'] : 0,
            'deliverable_zipcodes' => ($data['deliverable_type'] == ALL || $data['deliverable_type'] == NONE) ? NULL : $data['zipcodes'],
            'hsn_code' => $hsn_code,
        ];
        if ($data['product_type'] == 'simple_product') {

            if (isset($data['simple_product_stock_status']) && empty($data['simple_product_stock_status'])) {
                $pro_data['stock_type'] = NULL;
            }

            if (isset($data['simple_product_stock_status'])  && in_array($data['simple_product_stock_status'], array('0', '1'))) {
                $pro_data['stock_type'] = '0';
            }

            if (isset($data['simple_product_stock_status'])  && in_array($data['simple_product_stock_status'], array('0', '1'))) {
                if (!empty($data['product_sku'])) {
                    $pro_data['sku'] = $data['product_sku'];
                }
                $pro_data['stock'] = $data['product_total_stock'];
                $pro_data['availability'] = $data['simple_product_stock_status'];
            }
        }

        if ((isset($data['variant_stock_status']) ||  $data['variant_stock_status'] == '' || empty($data['variant_stock_status']) || $data['variant_stock_status'] == ' ') && $data['product_type'] == 'variable_product') {
            $pro_data['stock_type'] = NULL;
        }

        if (isset($data['variant_stock_level_type']) && !empty($data['variant_stock_level_type']) && $data['product_type'] != 'digital_product') {
            $pro_data['stock_type'] = ($data['variant_stock_level_type'] == 'product_level') ? 1 : 2;
        }
        if ($data['product_type'] != 'digital_product' && isset($data['is_returnable'])  && $data['is_returnable'] != "" && ($data['is_returnable'] == "on" || $data['is_returnable'] == '1')) {
            $pro_data['is_returnable'] = '1';
        } else {
            $pro_data['is_returnable'] = '0';
        }

        if ($data['product_type'] != 'digital_product' && isset($data['is_cancelable'])  && $data['is_cancelable'] != "" && ($data['is_cancelable'] == "on" || $data['is_cancelable'] == '1')) {
            $pro_data['is_cancelable'] = '1';
            $pro_data['cancelable_till'] = $data['cancelable_till'];
        } else {
            $pro_data['is_cancelable'] = '0';
            $pro_data['cancelable_till'] = '';
        }
        if (isset($data['download_allowed'])  && $data['download_allowed'] != "" && ($data['download_allowed'] == "on" || $data['download_allowed'] == '1')) {
            $pro_data['download_allowed'] = '1';
            $pro_data['download_type'] = $download_type;
            $pro_data['download_link'] = $download_link;
        } else {
            $pro_data['download_allowed'] = '0';
            $pro_data['download_type'] = '';
            $pro_data['download_link'] = '';
        }
        if ($data['product_type'] != 'digital_product' && isset($data['cod_allowed'])  && $data['cod_allowed'] != "" && ($data['cod_allowed'] == "on" || $data['cod_allowed'] == '1')) {
            $pro_data['cod_allowed'] = '1';
        } else {
            $pro_data['cod_allowed'] = '0';
        }
        if (isset($data['is_prices_inclusive_tax']) && $data['is_prices_inclusive_tax'] != "" && ($data['is_prices_inclusive_tax'] == "on" || $data['is_prices_inclusive_tax'] == '1')) {
            $pro_data['is_prices_inclusive_tax'] = '1';
        } else {
            $pro_data['is_prices_inclusive_tax'] = '0';
        }
        $variant_images = (!empty($data['variant_images']) && isset($data['variant_images'])) ? $data['variant_images'] : [];

        if (isset($data['edit_product_id'])) {
            if (empty($main_image_name)) {
                unset($pro_data['image']);
            }
            $pro_data['other_images'] = json_encode($other_images, 1);

            $this->db->set($pro_data)->where('id', $data['edit_product_id'])->update('products');
        } else {

            $pro_data['other_images'] = json_encode($other_images, 1);
           
            $this->db->insert('products', $pro_data);
        }

        $p_id = (isset($data['edit_product_id'])) ? $data['edit_product_id'] : $this->db->insert_id();
        $pro_variance_data['product_id'] = $p_id;
        $pro_attr_data = [

            'product_id' => $p_id,
            'attribute_value_ids' => strval($data['attribute_values']),

        ];

        if (isset($data['edit_product_id'])) {
            $this->db->where('product_id', $data['edit_product_id'])->update('product_attributes', $pro_attr_data);
        } else {
            $this->db->insert('product_attributes', $pro_attr_data);
        }
        if ($pro_type == 'simple_product') {
            $pro_variance_data = [
                'product_id' => $p_id,
                'price' => $data['simple_price'],
                'special_price' => (isset($data['simple_special_price']) && !empty($data['simple_special_price'])) ? $data['simple_special_price'] : '0',
            ];

            if (isset($data['edit_product_id'])) {

                if (isset($_POST['reset_settings']) && trim($_POST['reset_settings']) == '1') {
                    $this->db->insert('product_variants', $pro_variance_data);
                } else {
                    $this->db->where('product_id', $data['edit_product_id'])->update('product_variants', $pro_variance_data);
                }
            } else {
                $this->db->insert('product_variants', $pro_variance_data);
            }
        } elseif ($pro_type == 'digital_product') {
            $pro_variance_data = [
                'product_id' => $p_id,
                'price' => $data['simple_price'],
                'special_price' => (isset($data['simple_special_price']) && !empty($data['simple_special_price'])) ? $data['simple_special_price'] : '0',
            ];

            if (isset($data['edit_product_id'])) {

                if (isset($_POST['reset_settings']) && trim($_POST['reset_settings']) == '1') {
                    $this->db->insert('product_variants', $pro_variance_data);
                } else {
                    $this->db->where('product_id', $data['edit_product_id'])->update('product_variants', $pro_variance_data);
                }
            } else {
                $this->db->insert('product_variants', $pro_variance_data);
            }
        } else {
            $flag = " ";
            if (isset($data['variant_stock_status']) && $data['variant_stock_status'] == '0') {

                if ($data['variant_stock_level_type'] == "product_level") {
                    $flag = "product_level";
                    $pro_variance_data['sku'] = $data['sku_variant_type'];
                    $pro_variance_data['stock'] = $data['total_stock_variant_type'];
                    $pro_variance_data['availability']  = $data['variant_status'];
                    $variant_price = $data['variant_price'];
                    $variant_special_price = (isset($data['variant_special_price']) && !empty($data['variant_special_price'])) ? $data['variant_special_price'] : '0';
                } else {
                    $flag = "variant_level";
                    $variant_price = $data['variant_price'];
                    $variant_special_price =  (isset($data['variant_special_price']) && !empty($data['variant_special_price'])) ? $data['variant_special_price'] : '0';
                    $variant_sku = $data['variant_sku'];
                    $variant_total_stock = $data['variant_total_stock'];
                    $variant_stock_status = $data['variant_level_stock_status'];
                }
            } else {
                $variant_price = $data['variant_price'];
                $variant_special_price = (isset($data['variant_special_price']) && !empty($data['variant_special_price'])) ? $data['variant_special_price'] : '0';
            }

            if (!empty($data['variants_ids'])) {
                $variants_ids = $data['variants_ids'];
                if (isset($data['edit_variant_id']) && !empty($data['edit_variant_id'])) {
                    $this->db->set('status', 7)->where('product_id', $data['edit_product_id'])->where('status !=', 0)->where_not_in('id', $data['edit_variant_id'])->update('product_variants');
                }

                if (!isset($data['edit_variant_id']) && isset($data['edit_product_id'])) {
                    $this->db->set('status', 7)->where('product_id', $data['edit_product_id'])->where('status !=', 0)->update('product_variants');
                }

                for ($i = 0; $i < count($variants_ids); $i++) {

                    $value = str_replace(' ', ',', trim($variants_ids[$i]));
                    if ($flag == "variant_level") {
                        $pro_variance_data['price'] = $variant_price[$i];
                        $pro_variance_data['special_price'] =  (isset($variant_special_price[$i]) && !empty($variant_special_price[$i])) ? $variant_special_price[$i] : '0';
                        $pro_variance_data['sku'] = $variant_sku[$i];
                        $pro_variance_data['stock'] = $variant_total_stock[$i];
                        $pro_variance_data['availability'] = $variant_stock_status[$i];
                    } else {
                        $pro_variance_data['price'] = $variant_price[$i];
                        $pro_variance_data['special_price'] = (isset($variant_special_price[$i]) && !empty($variant_special_price[$i])) ? $variant_special_price[$i] : '0';
                    }
                    if (isset($variant_images[$i]) && !empty($variant_images[$i])) {
                        $pro_variance_data['images'] = json_encode($variant_images[$i]);
                    } else {
                        $pro_variance_data['images'] = '[]';
                    }
                    $pro_variance_data['attribute_value_ids'] = $value;
                    if (isset($data['edit_variant_id'][$i]) && !empty($data['edit_variant_id'][$i])) {
                        $this->db->where('id', $data['edit_variant_id'][$i])->update('product_variants', $pro_variance_data);
                    } else {
                        $this->db->insert('product_variants', $pro_variance_data);
                    }
                }
            }
        }
    }

    public function get_product_details($flag = NULL, $seller_id = NULL, $p_status = NULL)
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
                $sort = "product_variants.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = trim($_GET['search']);
            $multipleWhere = ['p.`id`' => $search, 'p.`name`' => $search, 'p.`description`' => $search, 'p.`short_description`' => $search, 'c.name' => $search];
        }

        if (isset($_GET['category_id']) || isset($_GET['search'])) {
            if (isset($_GET['search']) and $_GET['search'] != '') {
                $multipleWhere['p.`category_id`'] = $search;
            }

            if (isset($_GET['category_id']) and $_GET['category_id'] != '') {
                $category_id = $_GET['category_id'];
            }
        }

        $count_res = $this->db->select(' COUNT( distinct(p.id)) as `total` ')->join(" categories c", "p.category_id=c.id ")->join('product_variants', 'product_variants.product_id = p.id');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_Start();
            $count_res->or_like($multipleWhere);
            $count_res->group_End();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        if ($flag == 'low') {
            $count_res->group_Start();
            $where = "p.stock_type is  NOT NULL";
            $count_res->where($where);
            $count_res->where('p.stock <=', $low_stock_limit);
            $count_res->where('p.availability  =', '1');
            $count_res->or_where('product_variants.stock <=', $low_stock_limit);
            $count_res->where('product_variants.availability  =', '1');
            $count_res->group_End();
        }

        if (isset($seller_id) && $seller_id != "") {
            $count_res->where("p.seller_id", $seller_id);
        }
        if (isset($p_status) && $p_status != "") {
            $count_res->where("p.status", $p_status);
        }

        if ($flag == 'sold') {
            $count_res->group_Start();
            $where = "p.stock_type is  NOT NULL";
            $count_res->where($where);
            $count_res->where('p.stock ', '0');
            $count_res->where('p.availability ', '0');
            $count_res->or_where('product_variants.stock ', '0');
            $count_res->where('product_variants.availability ', '0');
            $count_res->group_End();
        }

        if (isset($category_id) && !empty($category_id)) {
            $count_res->group_Start();
            $count_res->or_where('p.category_id', $category_id);
            $count_res->or_where('c.parent_id', $category_id);
            $count_res->group_End();
        }

        $product_count = $count_res->get('products p')->result_array();
        foreach ($product_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select('product_variants.id AS id,c.name as category_name,sd.store_name, p.id as pid,p.rating,p.no_of_ratings,p.name, p.type, p.image, p.status,p.brand,product_variants.price , product_variants.special_price, product_variants.stock')
            ->join("categories c", "p.category_id=c.id")
            ->join("seller_data sd", "sd.user_id=p.seller_id ")
            ->join('product_variants', 'product_variants.product_id = p.id');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_Start();
            $search_res->or_like($multipleWhere);
            $search_res->group_End();
        }

        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        if ($flag != null && $flag == 'low') {

            $search_res->group_Start();
            $where = "p.stock_type is  NOT NULL";
            $search_res->where($where);
            $search_res->where('p.stock <=', $low_stock_limit);
            $search_res->where('p.availability  =', '1');
            $search_res->or_where('product_variants.stock <=', $low_stock_limit);
            $search_res->where('product_variants.availability  =', '1');
            $search_res->group_End();
        }
        if ($flag != null && $flag == 'sold') {
            $search_res->group_Start();
            $where = "p.stock_type is  NOT NULL";
            $search_res->where($where);
            $search_res->where('p.stock ', '0');
            $search_res->where('p.availability ', '0');
            $search_res->or_where('product_variants.stock ', '0');
            $search_res->where('product_variants.availability ', '0');

            $search_res->group_End();
        }

        if (isset($category_id) && !empty($category_id)) {
            //category select where
            $search_res->group_Start();
            $search_res->or_where('p.category_id', $category_id);
            $search_res->or_where('c.parent_id', $category_id);
            $search_res->group_End();
        }
        if (isset($seller_id) && $seller_id != "") {
            $count_res->where("p.seller_id", $seller_id);
        }

        if (isset($p_status) && $p_status != "") {
            $count_res->where("p.status", $p_status);
        }
        $pro_search_res = $search_res->group_by('pid')->order_by($sort, "DESC")->limit($limit, $offset)->get('products p')->result_array();
        $currency = get_settings('currency');
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($pro_search_res as $row) {

            $row = output_escaping($row);
            $operate = "<div><a href='view-product?edit_id=" . $row['pid'] . "'  class='btn action-btn btn-primary btn-xs mr-1 mb-1' title='View'><i class='fa fa-eye'></i></a>";
            $operate .= " <a href='create-product?edit_id=" . $row['pid'] . "' data-id=" . $row['pid'] . " class='btn action-btn btn-success btn-xs mr-1 mb-1' title='Edit' ><i class='fa fa-pen'></i></a>";
            if ($row['status'] == '2') {
                $tempRow['status'] = '<a class="badge badge-danger text-white">Not-Approved</a>';
                if ($this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-secondary action-btn mr-1 mb-1 ml-1 btn-xs" data-table="products" href="javascript:void(0)" title="Not-Approved" ><i class="fa fa-ban"></i></a>';
                } else {
                    $operate .= '<a class="btn btn-secondary mr-1 mb-1 action-btn ml-1 btn-xs update_active_status" data-table="products" href="javascript:void(0)" title="Approve" data-id="' . $row['pid'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-ban"></i></a>';
                }
            }
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                $operate .= '<a class="btn btn-warning action-btn btn-xs update_active_status mr-1 mb-1 ml-1" data-table="products" title="Deactivate" href="javascript:void(0)" data-id="' . $row['pid'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-toggle-on"></i></a>';
            } else  if ($row['status'] == '0') {

                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                $operate .= '<a class="btn btn-secondary action-btn mr-1 mb-1 ml-1 btn-xs update_active_status" data-table="products" href="javascript:void(0)" title="Active" data-id="' . $row['pid'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-toggle-off"></i></a></div>';
            }
            $operate .= '<div><a href="javascript:void(0)" id="delete-product" data-id=' . $row['pid'] . ' class="btn action-btn btn-danger mr-1 mb-1  btn-xs"><i class="fa fa-trash"></i></a>';
            $operate .= " <a href='javascript:void(0)' data-id=" . $row['pid'] . " data-toggle='modal' data-target='#product-rating-modal' class='btn action-btn btn-success btn-xs mr-1 mb-1' title='View Ratings' ><i class='fa fa-star'></i></a>";
            $operate .= "<a href='javascript:void(0)' data-id=" . $row['pid'] . " data-toggle='modal' data-target='#product-faqs-modal' class='btn action-btn btn-info btn-xs mr-1 mb-1 ml-1' title='View FAQs' ><i class='fas fa-question-circle'></i></a></div>";

            $attr_values  =  get_variants_values_by_pid($row['pid']);
            $tempRow['id'] = $row['pid'];
            $tempRow['varaint_id'] = $row['id'];
            $tempRow['name'] = $row['name'] . '<br><small>' . ucwords(str_replace('_', ' ', $row['type'])) . '</small><br><small> By </small><b>' . $row['store_name'] . '</b>';
            $tempRow['type'] = $row['type'];
            $tempRow['brand'] = $row['brand'];
            $tempRow['category_name'] = $row['category_name'];
            $tempRow['price'] =  ($row['special_price'] == null || $row['special_price'] == '0') ? $currency . $row['price'] : $currency . $row['special_price'];
            $tempRow['stock'] = $row['stock'];
            $variations = '';
            foreach ($attr_values as $variants) {

                if (isset($attr_values[0]['attr_name'])) {

                    if (!empty($variations)) {
                        $variations .= '---------------------<br>';
                    }

                    $attr_name = explode(',', $variants['attr_name']);
                    $varaint_values = explode(',', $variants['variant_values']);
                    for ($i = 0; $i < count($attr_name); $i++) {
                        $variations .= '<b>' . $attr_name[$i] . '</b> : ' . $varaint_values[$i] . '&nbsp;&nbsp;<b> Varient id : </b>' . $variants['id'] . '<br>';
                    }
                }
            }

            $tempRow['variations'] = (!empty($variations)) ? $variations : '-';
            $row['image'] = get_image_url($row['image'], 'thumb', 'sm');
            $tempRow['image'] = '<div class="mx-auto product-image image-box-100"><a href=' . $row['image'] . ' data-toggle="lightbox" data-gallery="gallery">
        <img src=' . $row['image'] . ' class="rounded"></a></div>';

            $tempRow['rating'] = '<input type="text" class="kv-fa rating-loading" value="' . $row['rating'] . '" data-size="xs" title="" readonly> <span> (' . $row['rating'] . '/' . $row['no_of_ratings'] . ') </span>';

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    public function get_digital_product_details($flag = NULL, $seller_id = NULL, $p_status = NULL)
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
                $sort = "product_variants.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = trim($_GET['search']);
            $multipleWhere = ['p.`id`' => $search, 'p.`name`' => $search, 'p.`description`' => $search, 'p.`short_description`' => $search, 'c.name' => $search];
        }

        if (isset($_GET['category_id']) || isset($_GET['search'])) {
            if (isset($_GET['search']) and $_GET['search'] != '') {
                $multipleWhere['p.`category_id`'] = $search;
            }

            if (isset($_GET['category_id']) and $_GET['category_id'] != '') {
                $category_id = $_GET['category_id'];
            }
        }

        $count_res = $this->db->select(' COUNT( distinct(p.id)) as `total` ')->join(" categories c", "p.category_id=c.id ")->join('product_variants', 'product_variants.product_id = p.id');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_Start();
            $count_res->or_like($multipleWhere);
            $count_res->group_End();
        }
        $where = ['p.`type` =' => 'digital_product'];
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        if (isset($p_status) && $p_status != "") {
            $count_res->where("p.status", 1);
        }

        $product_count = $count_res->get('products p')->result_array();
        foreach ($product_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select('product_variants.id AS id,c.name as category_name,sd.store_name, p.id as pid,p.rating,p.no_of_ratings,p.name, p.type, p.image, p.status,p.brand,product_variants.price , product_variants.special_price, product_variants.stock')
            ->join("categories c", "p.category_id=c.id")
            ->join("seller_data sd", "sd.user_id=p.seller_id ")
            ->join('product_variants', 'product_variants.product_id = p.id');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_Start();
            $search_res->or_like($multipleWhere);
            $search_res->group_End();
        }

        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }


        $pro_search_res = $search_res->group_by('pid')->order_by($sort, "DESC")->limit($limit, $offset)->get('products p')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($pro_search_res as $row) {

            $row = output_escaping($row);

            $attr_values  =  get_variants_values_by_pid($row['pid']);
            $tempRow['id'] = $row['pid'];
            $tempRow['varaint_id'] = $row['id'];
            $tempRow['name'] = $row['name'] . '<br><small>' . ucwords(str_replace('_', ' ', $row['type'])) . '</small><br><small> By </small><b>' . $row['store_name'] . '</b>';



            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function get_countries($search_term = "")
    {
        // Fetch users
        $this->db->select('*');
        $this->db->where("name like '%" . $search_term . "%'");
        $fetched_records = $this->db->get('countries');
        $countries = $fetched_records->result_array();

        // Initialize Array with fetched data
        $data = array();
        foreach ($countries as $country) {
            $data[] = array("id" => $country['name'], "text" => $country['name']);
        }
        return $data;
    }

    function get_brands($search_term = "")
    {
        // Fetch users
        $this->db->select('*');
        $this->db->where("name like '%" . $search_term . "%'");
        $fetched_records = $this->db->get('brands');
        $brands = $fetched_records->result_array();

        // Initialize Array with fetched data
        $data = array();
        foreach ($brands as $brand) {
            $data[] = array("id" => $brand['name'], "text" => $brand['name']);
        }
        return $data;
    }

    function get_faqs_data($search_term = "")
    {
        // Fetch users
        $this->db->select('*');
        $this->db->where("question like '%" . $search_term . "%'");
        $fetched_records = $this->db->get('product_faqs');
        $faqs = $fetched_records->result_array();

        // Initialize Array with fetched data
        $data = array();
        foreach ($faqs as $faq) {
            $data[] = array("id" => $faq['id'], "text" => $faq['question']);
        }
        return $data;
    }

    function get_country_list($search = "", $offset = 0, $limit = 25)
    {
        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                '`name`' => $search,
            ];
        }
        $search_res = $this->db->select('id,name');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        $countries = $search_res->limit($limit, $offset)->get('countries')->result_array();
        $bulkData = array();
        $bulkData['error'] = (empty($countries)) ? true : false;
        $bulkData['message'] = (empty($countries)) ? "Countries Not Found" : "Countries Retrived Successfully";
        if (!empty($countries)) {
            for ($i = 0; $i < count($countries); $i++) {
                $countries[$i] = output_escaping($countries[$i]);
            }
        }
        $bulkData['data'] = (empty($countries)) ? [] : $countries;
        return $bulkData;
    }

    function get_brand_list($search = "", $offset = 0, $limit = 25)
    {
        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                '`name`' => $search,
            ];
        }
        $search_res = $this->db->select('id,name,image');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        $brands = $search_res->limit($limit, $offset)->get('brands')->result_array();

        $bulkData = array();
        $bulkData['error'] = (empty($brands)) ? true : false;
        $bulkData['message'] = (empty($brands)) ? "Brands Not Found" : "Brands Retrived Successfully";
        if (!empty($brands)) {
            for ($i = 0; $i < count($brands); $i++) {
                $brands[$i] = output_escaping($brands[$i]);
                $brands[$i]['image'] = base_url() . $brands[$i]['image'];
            }
        }
        $bulkData['data'] = (empty($brands)) ? [] : $brands;
        return $bulkData;
    }

    /* add_product_faqs */
    function add_product_faqs($data)
    {
        $answered_by = fetch_details('users', 'id=' . $_SESSION['user_id'], 'username');
        $data = escape_array($data);
        if (isset($data['edit_product_faq'])) {
            $edit_data = [
                'answer' => $data['answer'],
                'answered_by' => $_SESSION['user_id'],
            ];
            $this->db->set($edit_data)->where('id', $data['edit_product_faq'])->update('product_faqs');
        } else {
            $faq_data = [
                'product_id' => $data['product_id'],
                'user_id' => $data['user_id'],
                'question' => $data['question'],
                'answer' => $data['answer'],
                'answered_by' => (isset($data['answer']) && ($data['answer']) != "") ? $data['answer_by'] : 0,
            ];
            $this->db->insert('product_faqs', $faq_data);
            return $this->db->insert_id();
        }
    }

    /* get_product_faqs */
    function get_product_faqs($id = '', $product_id = '', $user_id = '', $search = '', $offset = '0', $limit = '10', $sort = 'id', $order = 'DESC', $is_seller = false, $seller_id = '')
    {
        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                '`pf.id`' => $search, '`pf.product_id`' => $search, '`pf.user_id`' => $search, '`pf.question`' => $search, '`pf.answer`' => $search
            ];
        }
        if (!empty($id)) {
            $where['pf.id'] = $id;
        }
        if (!empty($product_id)) {
            $where['pf.product_id'] = $product_id;
        }
        if (!empty($user_id)) {
            $where['pf.user_id'] = $user_id;
        }
        if (!empty($seller_id)) {
            $where['p.seller_id'] = $seller_id;
        }

        //  count of total product faqs
        $count_res = $this->db->select(' COUNT(pf.id) as `total`')
            ->join('users u', 'u.id=pf.user_id', 'left')
            ->join('products p', 'p.id=pf.product_id', 'left');

        // return;
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $cat_count = $count_res->get('product_faqs pf')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }
        // get product faqs data
        $search_res = $this->db->select('pf.*,u.username')
            ->join('users u', 'u.id=pf.user_id', 'left')
            ->join('products p', 'p.id=pf.product_id', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        $faq_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('product_faqs pf')->result_array();
        $rows = $tempRow = $bulkData = array();
        $bulkData['error'] = (empty($faq_search_res)) ? true : false;
        $bulkData['message'] = (empty($faq_search_res)) ? 'FAQs does not exist' : 'FAQs retrieved successfully';
        $bulkData['total'] = (empty($faq_search_res)) ? 0 : $total;
        if (!empty($faq_search_res)) {
            foreach ($faq_search_res as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = $row['id'];
                $tempRow['product_id'] = $row['product_id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['username'] = $row['username'];
                $tempRow['question'] = $row['question'];
                $tempRow['votes'] = $row['votes'];
                $tempRow['answered_by'] = (isset($row['answered_by']) && $row['answered_by'] != '') ? $row['answered_by'] : '';
                $ans_by_name = fetch_details('users', 'id=' . $row['answered_by'], 'username');
                $tempRow['answered_by_name'] = (isset($row['answered_by']) && $row['answered_by'] != '' && !empty($ans_by_name[0]['username'])) ? $ans_by_name[0]['username'] : '';
                $tempRow['date_added'] = $row['date_added'];
                if (isset($is_seller) && (($is_seller == FALSE) && ((isset($row['answer']) && $row['answer'] == '')))) {
                    unset($tempRow);
                } else {
                    $tempRow['answer'] = (isset($row['answer']) && $row['answer'] != '') ? $row['answer'] : "";
                }
                if (isset($tempRow) && !empty($tempRow)) {
                    $rows[] = $tempRow;
                }
            }
            $bulkData['data'] = $rows;
        } else {
            $bulkData['data'] = [];
        }
        return $bulkData;
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
            $where['user_id'] = $_GET['user_id'];
        }

        $count_res = $this->db->select(' COUNT(pf.id) as total  ')->join('users u', 'u.id=pf.user_id');
        if (isset($_GET['search']) && trim($_GET['search'])) {
            $search = trim($_GET['search']);
            $multipleWhere = ['pf.id' => $search, 'pf.product_id' => $search, 'pf.user_id' => $search];
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
            if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                $operate = ' <a href="javascript:void(0)" class="edit_btn btn btn-success btn-xs mr-1 mb-1" title="View" data-id="' . $row['id'] . '" data-url="admin/product/"><i class="fa fa-edit"></i></a>';
                $operate .= '<a class="btn btn-danger btn-xs mr-1 mb-1 delete-product-faq" href="javascript:void(0)" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            } else {
                $operate = ' <a href="javascript:void(0)" class="edit_btn btn btn-success btn-xs mr-1 mb-1" title="View" data-id="' . $row['id'] . '" data-url="seller/product/"><i class="fa fa-edit"></i></a>';
                $operate .= '<a class="btn btn-danger btn-xs mr-1 mb-1 delete-seller-product-faq" href="javascript:void(0)" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            }
            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['product_id'] = $row['product_id'];
            $tempRow['votes'] = $row['votes'];
            $tempRow['question'] = $row['question'];
            $tempRow['answer'] = $row['answer'];
            $tempRow['answered_by'] = $row['answered_by'];
            $tempRow['username'] = $row['user_name'];
            $tempRow['date_added'] = $date->format('d-M-Y');
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $i++;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_stock_details()
    {
        $filters['show_only_stock_product'] = true;
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $filters['search'] =  (isset($_GET['search'])) ? $_GET['search'] : null;
        // $filter['search'] = (isset($_GET['search']) && !empty($_GET['search'])) ? $_GET['search'] : '';
        if (isset($_GET['seller_id'])) {
            $seller_id = $_GET['seller_id'];
        }
        if (isset($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
        }
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];
        if (isset($_GET['order']))
            $order = $_GET['order'];
        $products = fetch_product("", (isset($filters)) ? $filters : null, "", isset($category_id) ? $category_id : null, $limit, $offset, $sort, $order, "", "", isset($seller_id) ? $seller_id : null);
        $total = $products['total'];
        $bulkData = $rows = $tempRow = array();
        $bulkData['total'] = $total;


        foreach ($products['product'] as $product) {
            $category_id = $product['category_id'];
            $category_name = fetch_details('categories', ['id' => $category_id], 'name');
            $operate = $stock = "";
            $variants = get_variants_values_by_pid($product['id']);
            $stock = implode("<br/>", array_column($variants, 'stock'));

            $tempRow['id'] = $product['variants'][0]['id'];
            $tempRow['name'] = $product['name'];
            $tempRow['seller_name'] = $product['seller_name'];
            $tempRow['category_name'] = $category_name[0]['name'];
            $tempRow['image'] = '<div class="mx-auto product-image image-box-100"><a href=' . $product['image'] . ' data-toggle="lightbox" data-gallery="gallery"><img src=' . $product['image'] . ' class="rounded"></a></div>';
            $operate = "<table class='table-borderless table-sm w-100'>";
            for ($i = 0; $i < count($variants); $i++) {
                $edit = '<a href="javascript:void(0)" class="edit_btn btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="' . $variants[$i]['id'] . '" data-url="admin/manage_stock/"><i class="fa fa-pen"></i></a>';
                $operate .= "<tr> <th>" . str_replace(",", ", ", $variants[$i]['variant_values'])  . '</th>';
                if ($product['stock_type'] != 1) {
                    $operate .= '<td><b>' . str_replace(",", ", ", $variants[$i]['stock']) . '</b></td>';
                    $operate .= '<td><b>' . $edit  . '</b></td></tr>';
                } else {
                    if ($i == 0) {
                        $operate .= '<td rowspan="' . count($variants) . '"><b>' .  $variants[$i]['stock'] . '</b></td>';
                        $operate .= '<td rowspan="' . count($variants) . '"><b>' . $edit  . '</b></td></tr>';
                    }
                }
            }
            $operate .= "</table>";
            $tempRow['operate'] = (isset($product['stock']) && !empty($product['stock'])) ? '<table class="table-borderless table-sm w-100"><tr><th><b>'  . 'Simple Product' . '</b></th><td> <b>'  . ($product['stock']) . '</b></td><td>' . ' ' . $edit . "</td></tr></table>" : $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;

        print_r(json_encode($bulkData));
    }

    public function get_seller_stock_details()
    {
        $seller_id = $_SESSION['user_id'];
        $filters['show_only_stock_product'] = true;
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $filters['search'] =  (isset($_GET['search'])) ? $_GET['search'] : null;
        // $filter['search'] = (isset($_GET['search']) && !empty($_GET['search'])) ? $_GET['search'] : '';
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];
        if (isset($_GET['order']))
            $order = $_GET['order'];
        if (isset($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
        }

        $products = fetch_product("", (isset($filters)) ? $filters : null, "", isset($category_id) ? $category_id : '', $limit, $offset, $sort, $order, "", "", $seller_id);
        $total = $products['total'];
        $bulkData = $rows = $tempRow = array();
        $bulkData['total'] = $total;


        foreach ($products['product'] as $product) {
            $category_id = $product['category_id'];
            $category_name = fetch_details('categories', ['id' => $category_id], 'name');
            $operate = $stock = "";
            $variants = get_variants_values_by_pid($product['id']);
            $stock = implode("<br/>", array_column($variants, 'stock'));

            $tempRow['id'] = $product['variants'][0]['id'];
            $tempRow['name'] = $product['name'];
            $tempRow['seller_name'] = $product['seller_name'];
            $tempRow['category_name'] = $category_name[0]['name'];
            $tempRow['image'] = '<div class="mx-auto product-image image-box-100"><a href=' . $product['image'] . ' data-toggle="lightbox" data-gallery="gallery"><img src=' . $product['image'] . ' class="rounded"></a></div>';
            $operate = "<table class='table-borderless table-sm w-100'>";
            for ($i = 0; $i < count($variants); $i++) {
                $edit = '<a href="javascript:void(0)" class="edit_btn btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="' . $variants[$i]['id'] . '" data-url="seller/manage_stock/"><i class="fa fa-pen"></i> Edit</a>';
                $operate .= "<tr> <th>" . str_replace(",", ", ", $variants[$i]['variant_values'])  . '</th>';
                if ($product['stock_type'] != 1) {
                    $operate .= '<td><b>' . str_replace(",", ", ", $variants[$i]['stock']) . '</b></td>';
                    $operate .= '<td><b>' . $edit  . '</b></td></tr>';
                } else {
                    if ($i == 0) {
                        $operate .= '<td rowspan="' . count($variants) . '"><b>' .  $variants[$i]['stock'] . '</b></td>';
                        $operate .= '<td rowspan="' . count($variants) . '"><b>' . $edit  . '</b></td></tr>';
                    }
                }
            }
            $operate .= "</table>";
            $tempRow['operate'] = (isset($product['stock']) && !empty($product['stock'])) ? '<table class="table-borderless table-sm w-100"><tr><th><b>'  . 'Simple Product' . '</b></th><td> <b>'  . ($product['stock']) . '</b></td><td>' . ' ' . $edit . "</td></tr></table>" : $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;

        print_r(json_encode($bulkData));
    }
}
