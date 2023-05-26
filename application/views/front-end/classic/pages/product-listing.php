<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= isset($page_main_bread_crumb) ? $page_main_bread_crumb : 'Products' ?><?= (isset($seller) && !empty($seller[0]['store_name'])) ? " By " . $seller[0]['store_name'] : '' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <?php if (isset($right_breadcrumb) && !empty($right_breadcrumb)) {
                    foreach ($right_breadcrumb as $row) {
                ?>
                        <li class="breadcrumb-item"><?= $row ?></li>
                <?php }
                } ?>
                <li class="breadcrumb-item active" aria-current="page"><?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<input type="hidden" id="product-filters" value='<?= (!empty($filters)) ? escape_array($filters) : ""  ?>' data-key="<?= $filters_key ?>" />
<section class="listing-page content main-content">
    <div class="product-listing card-solid py-4">
        <div class="row mx-0">
            <!-- Dektop Sidebar -->
            <?php if (isset($products['filters']) && !empty($products['filters'])) { ?>
                <div class=" order-md-1 col-lg-3 filter-section sidebar-filter-sm container pt-2 pb-2 filter-sidebar-view">
                    <div id="product-filters-desktop">
                        <?php foreach ($products['filters'] as $key => $row) {
                            $row_attr_name = str_replace(' ', '-', $row['name']);
                            $attribute_name = isset($_GET[strtolower('filter-' . $row_attr_name)]) ? $this->input->get(strtolower('filter-' . $row_attr_name), true) : 'null';
                            $selected_attributes = explode('|', $attribute_name);
                            $attribute_values = explode(',', $row['attribute_values']);
                            $attribute_values_id = explode(',', $row['attribute_values_id']);
                        ?>
                            <div class="card-custom">
                                <div class="card-header-custom" id="h1">
                                    <h2 class="clearfix mb-0">
                                        <a class="collapse-arrow btn btn-link collapsed" data-toggle="collapse" data-target="#c<?= $key ?>" aria-expanded="true" aria-controls="collapseone"><?= html_escape($row['name']) ?><i class="fa fa-angle-down rotate"></i></a>
                                    </h2>
                                </div>
                                <div id="c<?= $key ?>" class="collapse <?= ($attribute_name != 'null') ? 'show' : '' ?>" aria-labelledby="h1" data-parent="#accordionExample">
                                    <div class="card-body-custom">
                                        <?php foreach ($attribute_values as $key => $values) {
                                            $values = strtolower($values);
                                        ?>
                                            <div class="input-container d-flex">
                                                <?= form_checkbox(
                                                    $values,
                                                    $values,
                                                    (in_array($values, $selected_attributes)) ? TRUE : FALSE,
                                                    array(
                                                        'class' => 'toggle-input product_attributes',
                                                        'id' => $row_attr_name . ' ' . $values,
                                                        'data-attribute' => strtolower($row['name']),
                                                    )
                                                ) ?>
                                                <label class="toggle checkbox" for="<?= $row_attr_name . ' ' . $values ?>">
                                                    <div class="toggle-inner"></div>
                                                </label>
                                                <?= form_label($values, $row_attr_name . ' ' . $values, array('class' => 'text-label')) ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="text-center">
                        <button class="button button-rounded button-warning product_filter_btn">Filter</button>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-12 order-md-2 <?= (isset($products['filters']) && !empty($products['filters'])) ? "col-lg-9" : "col-lg-12" ?>">
                <div class="container-fluid filter-section pt-3 pb-3 ">
                    <div class="col-12 pl-0">
                        <div class="dropdown">
                            <div class="filter-bars">
                                <div class="menu js-menu">
                                    <span class="menu__line"></span>
                                    <span class="menu__line"></span>
                                    <span class="menu__line"></span>

                                </div>
                            </div>
                            <div class="col-12 sort-by py-3 pl-0">
                                <?php if (isset($products) && !empty($products['product'])) { ?>
                                    <div class="dropdown float-md-right d-flex mb-4">
                                        <label class="mr-2 dropdown-label"> <?= !empty($this->lang->line('show')) ? $this->lang->line('show') : 'Show' ?>:</label>
                                        <a class="btn dropdown-border btn-lg dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= ($this->input->get('per-page', true) ? $this->input->get('per-page', true) : '12') ?> <span class="caret"></span></a>
                                        <a href="#" id="product_grid_view_btn" class="grid-view"><i class="fas fa-th"></i></a>
                                        <a href="#" id="product_list_view_btn" class="grid-view"><i class="fas fa-th-list"></i></a>
                                        <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="navbarDropdown" id="per_page_products">
                                            <a class="dropdown-item" href="#" data-value=12>12</a>
                                            <a class="dropdown-item" href="#" data-value=16>16</a>
                                            <a class="dropdown-item" href="#" data-value=20>20</a>
                                            <a class="dropdown-item" href="#" data-value=24>24</a>
                                        </div>
                                    </div>
                                    <div class="ele-wrapper">
                                        <div class="form-group col-md-4 d-flex pl-0">
                                            <label for="product_sort_by"></label>
                                            <select id="product_sort_by" class="form-control">
                                                <option><?= !empty($this->lang->line('relevance')) ? $this->lang->line('relevance') : 'Relevance' ?></option>
                                                <option value="top-rated" <?= ($this->input->get('sort') == "top-rated") ? 'selected' : '' ?>><?= !empty($this->lang->line('top_rated')) ? $this->lang->line('top_rated') : 'Top Rated' ?></option>
                                                <option value="date-desc" <?= ($this->input->get('sort') == "date-desc") ? 'selected' : '' ?>><?= !empty($this->lang->line('newest_first')) ? $this->lang->line('newest_first') : 'Newest First' ?></option>
                                                <option value="date-asc" <?= ($this->input->get('sort') == "date-asc") ? 'selected' : '' ?>><?= !empty($this->lang->line('oldest_first')) ? $this->lang->line('oldest_first') : 'Oldest First' ?></option>
                                                <option value="price-asc" <?= ($this->input->get('sort') == "price-asc") ? 'selected' : '' ?>><?= !empty($this->lang->line('price_low_to_high')) ? $this->lang->line('price_low_to_high') : 'Price - Low To High' ?></option>
                                                <option value="price-desc" <?= ($this->input->get('sort') == "price-desc") ? 'selected' : '' ?>><?= !empty($this->lang->line('price_high_to_low')) ? $this->lang->line('price_high_to_low') : 'Price - High To Low' ?></option>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($sub_categories) && !empty($sub_categories)) { ?>
                        <div class="col-md-9 col-sm-12 text-left py-3">
                            <?php if (isset($single_category) && !empty($single_category)) { ?>
                                <span class="h3"><?= $single_category['name'] ?> <?= !empty($this->lang->line('category')) ? $this->lang->line('category') : 'Category' ?></span>
                            <?php } ?>
                        </div>
                        <div class="category-section container-fluid text-center">
                            <div class="row">
                                <?php foreach ($sub_categories as $key => $row) { ?>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="category-image w-75">
                                            <a href="<?= base_url('products/category/' . html_escape($row->slug)) ?>">
                                                <img class="pic-1 lazy" data-src="<?= $row->image ?>">
                                            </a>
                                            <div class="social">
                                                <span><?= html_escape($row->name) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($products) && !empty($products['product'])) { ?>

                        <?php if (isset($_GET['type']) && $_GET['type'] == "list") { ?>
                            <div class="col-md-12 ">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h4 class="h4"><?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></h4>
                                    </div>
                                    <?php foreach ($products['product'] as $row) { ?>
                                       
                                        <div class="col-md-3">
                                            <div class="product-grid">
                                                <div class="product-image">
                                                    <div class="product-image-container">
                                                        <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                                            <img class="pic-1 lazy" data-src="<?= $row['image_sm'] ?>">
                                                        </a>
                                                    </div>
                                                    <ul class="social">
                                                        <?php
                                                        if (count($row['variants']) <= 1) {
                                                            $variant_id = $row['variants'][0]['id'];
                                                            $modal = "";
                                                        } else {
                                                            $variant_id = "";
                                                            $modal = "#quick-view";
                                                        }
                                                        ?>
                                                        <?php $variant_price = ($row['variants'][0]['special_price'] > 0 && $row['variants'][0]['special_price'] != '') ? $row['variants'][0]['special_price'] : $row['variants'][0]['price'];
                                                        $data_min = (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1;
                                                        $data_step = (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1;
                                                        $data_max = (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : 0;
                                                        ?>
                                                        <li><a href="" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view"><i class="fa fa-search"></i></a></li>
                                                        <li>
                                                            <a href="" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $row['name'] ?>" data-product-image="<?= $row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <?php $variant_id = (count($row['variants']) <= 1) ? $row['variants'][0]['id'] : ""; ?>
                                                        
                                                            <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                                <i class="fa fa-random"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <?php if (isset($row['min_max_price']['special_price']) && $row['min_max_price']['special_price'] != '' && $row['min_max_price']['special_price'] != 0 && $row['min_max_price']['special_price'] < $row['min_max_price']['min_price']) { ?>
                                                        <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                        <span class="product-discount-label"><?= $row['min_max_price']['discount_in_percentage'] ?>%</span>
                                                    <?php } ?>
                                                    <aside class="add-favorite">
                                                        <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $row['id'] ?>"></button>
                                                    </aside>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="product-content">
                                                <h2 class="list-product-title title"><a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a></h2>
                                                <div class="rating">
                                                    <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                                                </div>
                                                <p class="text-muted list-product-desc"><?= $row['short_description'] ?></p>
                                                <div class="price mb-2 list-view-price">
                                                    <?php if (!empty($row['min_max_price']['special_price'])) { ?>
                                                        <?= $settings['currency'] ?></i><?= number_format($row['min_max_price']['special_price']) ?>
                                                        <span class="striped-price"><?= $settings['currency'] . ' ' . number_format($row['min_max_price']['min_price']) ?></span>
                                                    <?php } else { ?>
                                                        <?= $settings['currency'] ?></i><?= number_format($row['min_max_price']['min_price']) ?>
                                                    <?php } ?>
                                                </div>
                                                <div class="button button-sm m-0 p-0">
                                                    <?php $variant_price = ($row['variants'][0]['special_price'] > 0 && $row['variants'][0]['special_price'] != '') ? $row['variants'][0]['special_price'] : $row['variants'][0]['price'];
                                                    $data_min = (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1;
                                                    $data_step = (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1;
                                                    $data_max = (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : 0;
                                                    ?>
                                                    <a class="add-to-cart add_to_cart" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $row['name'] ?>" data-product-image="<?= $row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="row w-100">
                                <div class="col-12">
                                    <h4 class="h4"><?= !empty($this->lang->line('products')) ? $this->lang->line('products') : 'Products' ?></h4>
                                </div>
                                <?php foreach ($products['product'] as $row) { ?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="product-grid">
                                            <aside class="add-favorite">
                                                <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $row['id'] ?>"></button>
                                            </aside>
                                            <div class="product-image">
                                                <div class="product-image-container">
                                                    <a href="<?= base_url('products/details/' . $row['slug']) ?>">
                                                        <img class="pic-1 lazy" data-src="<?= $row['image_sm'] ?>">
                                                    </a>
                                                </div>
                                                <ul class="social">
                                                    <?php
                                                    if (count($row['variants']) <= 1) {
                                                        $variant_id = $row['variants'][0]['id'];
                                                        $modal = "";
                                                    } else {
                                                        $variant_id = "";
                                                        $modal = "#quick-view";
                                                    }
                                                    ?>
                                                    <?php $variant_price = ($row['variants'][0]['special_price'] > 0 && $row['variants'][0]['special_price'] != '') ? $row['variants'][0]['special_price'] : $row['variants'][0]['price'];
                                                    $data_min = (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1;
                                                    $data_step = (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1;
                                                    $data_max = (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : 0;
                                                    ?>
                                                    <li><a href="" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view"><i class="fa fa-search"></i></a></li>
                                                    <li>
                                                        <a href="" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $row['name'] ?>" data-product-image="<?= $row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                                            <i class="fa fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?php $variant_id = (count($row['variants']) <= 1) ? $row['variants'][0]['id'] : ""; ?>

                                                        <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                            <i class="fa fa-random"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <?php if (isset($row['min_max_price']['special_price']) && $row['min_max_price']['special_price'] != '' && $row['min_max_price']['special_price'] != 0 && $row['min_max_price']['special_price'] < $row['min_max_price']['min_price']) { ?>
                                                    <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                    <span class="product-discount-label"><?= $row['min_max_price']['discount_in_percentage'] ?>%</span>
                                                <?php } ?>
                                            </div>
                                            <div class="rating">
                                                <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                                            </div>
                                            <div class="product-content">
                                                <h2 class="title"><a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a></h2>
                                                <div class="price">
                                                    <?php $price = get_price_range_of_product($row['id']);
                                                    echo $price['range'];
                                                    ?>
                                                </div>
                                                <?php $variant_price = ($row['variants'][0]['special_price'] > 0 && $row['variants'][0]['special_price'] != '') ? $row['variants'][0]['special_price'] : $row['variants'][0]['price'];
                                                $data_min = (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1;
                                                $data_step = (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1;
                                                $data_max = (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : 0;
                                                ?>
                                                <a class="add-to-cart add_to_cart" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $row['name'] ?>" data-product-image="<?= $row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <?php if ((!isset($sub_categories) || empty($sub_categories)) && (!isset($products) || empty($products['product']))) { ?>
                        <div class="col-12 text-center">
                            <h1 class="h2">No Products Found.</h1>
                            <a href="<?= base_url('products') ?>" class="button button-rounded button-warning"><?= !empty($this->lang->line('go_to_shop')) ? $this->lang->line('go_to_shop') : 'Go to Shop' ?></a>
                        </div>
                    <?php } ?>
                    <nav class="text-center mt-4">
                        <?= (isset($links)) ? $links : '' ?>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Mobile Filter Menu -->
        <div class="filter-nav js-filter-nav filter-nav-sm">
            <div class="filter-nav__list js-filter-nav__list">
                <h3 class="mt-0">Showing <span class="text-primary">12</span> Products</h3>
                <div class="col-md-4 order-md-1 col-lg-3">
                    <div id="product-filters-mobile">
                        <?php if (isset($products['filters']) && !empty($products['filters'])) { ?>
                            <div class="accordion" id="accordionExample">
                                <?php foreach ($products['filters'] as $key => $row) {
                                    $row_attr_name = str_replace(' ', '-', $row['name']);
                                    $attribute_name = isset($_GET[strtolower('filter-' . $row_attr_name)]) ? $this->input->get(strtolower('filter-' . $row_attr_name), true) : 'null';
                                    $selected_attributes = explode('|', $attribute_name);
                                    $attribute_values = explode(',', $row['attribute_values']);
                                    $attribute_values_id = explode(',', $row['attribute_values_id']);
                                ?>
                                    <div class="card-custom">
                                        <div class="card-header-custom" id="headingOne">
                                            <h2 class="mb-0">
                                                <a class="collapse-arrow btn btn-link collapsed" data-toggle="collapse" data-target="#m<?= $key ?>" aria-expanded="false" aria-controls="#m<?= $key ?>"><?= html_escape($row['name']) ?><i class="fa fa-angle-down rotate"></i></a>
                                            </h2>
                                        </div>
                                        <div id="m<?= $key ?>" class="collapse <?= ($attribute_name != 'null') ? 'show' : '' ?>" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body-custom">
                                                <?php foreach ($attribute_values as $key => $values) {
                                                    $values = strtolower($values);
                                                ?>
                                                    <div class="input-container d-flex">
                                                        <?= form_checkbox(
                                                            $values,
                                                            $values,
                                                            (in_array($values, $selected_attributes)) ? TRUE : FALSE,
                                                            array(
                                                                'class' => 'toggle-input product_attributes',
                                                                'id' => 'm' . $row_attr_name . ' ' . $values,
                                                                'data-attribute' => strtolower($row['name']),
                                                            )
                                                        ) ?>
                                                        <label class="toggle checkbox" for="<?= 'm' . $values ?>">
                                                            <div class="toggle-inner"></div>
                                                        </label>
                                                        <?= form_label($values, 'm' . $row_attr_name . ' ' . $values, array('class' => 'text-label')) ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="text-center">
                        <button class="button button-rounded button-warning product_filter_btn"><?= !empty($this->lang->line('filter')) ? $this->lang->line('filter') : 'Filter' ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>