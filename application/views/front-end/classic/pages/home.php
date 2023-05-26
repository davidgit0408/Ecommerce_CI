<section class="mt-2">
    <div class="main-content">
        <div class=''>
            <!-- Swiper -->
            <div class="swiper-container banner-swiper">
                <div class="swiper-wrapper">
                    <?php if (isset($sliders) && !empty($sliders)) { ?>
                        <?php foreach ($sliders as $row) { ?>
                            <div class="swiper-slide center-swiper-slide">
                                <a href="<?= $row['link'] ?>">
                                    <img src="<?= base_url($row['image']) ?>">
                                </a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination swiper1-pagination"></div>
                <!-- Add Pagination -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>
</section>

<section class="main-content mt-md-2 mt-sm-0">
    <div class="category-section container-fluid text-center dark-category-section icon-dark-sec">
        <div class="text-center text-white category-section-title">
            <h3><?= !empty($this->lang->line('category')) ? $this->lang->line('category') : 'Browse Categories' ?></h3>
        </div>
        <!-- Swiper -->
        <div class="swiper-container category-swiper swiper-container-initialized swiper-container-horizontal icon-swiper">
            <div class="swiper-wrapper categgory-bg">
                <?php foreach ($categories as $key => $row) { ?>
                    <div class="swiper-slide">
                        <div class="category-grid">
                            <div class="category-image">
                                <div class="category-image-container">
                                    <a href="<?= base_url('products/category/' . html_escape($row['slug'])) ?>">
                                        <img src="<?= $row['image'] ?>">
                                    </a>
                                    <div class="cat-font-color">
                                        <h4><?= html_escape($row['name']) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination category-swiper-pagination swiper-pagination-bullets"><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span></div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
        </div>
    </div>

    <?php $offer_counter = 0;
    $offers =  get_offers();

    foreach ($sections as $count_key => $row) {
        if (!empty($row['product_details'])) {
            if ($row['style'] == 'default') {
                if ($count_key != 0) {
                    $offer_counter++;
                    if (!empty($offers) && !empty($offers[$count_key - 1])) { ?>
                        <a href="<?= $offers[$count_key - 1]['link'] ?>">
                            <img class="img-fluid lazy" data-src="<?= base_url($offers[$count_key - 1]['image']) ?>">
                        </a>
                <?php }
                } ?>
                <!-- Default Style Design-->
                <div class="product-style-default product-section py-2 bg-white mt-2 mb-2">
                    <div class="swiper-container product-image-swiper">
                        <div class="my-4 featured-section-title">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="section-title"><?= ucfirst($row['title']) ?></h3>
                                </div>
                                <div class="text-left my-auto title-sm col-6"><?= strip_tags(output_escaping(str_replace('\r\n', '&#13;&#10;', $row['short_description']))) ?></div>
                                <div class="col-12 text-right"><a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>" class="featured-section-view-more"><?= !empty($this->lang->line('view_more')) ? $this->lang->line('view_more') : 'View More' ?></a>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div <?= ($is_rtl == true) ? "dir='rtl'" : ""; ?> class="swiper-wrapper">

                            <?php if (isset($row['product_details']) && !empty($row['product_details'])) { ?>
                                <?php foreach ($row['product_details'] as $product_row) { ?>
                                    <div class="swiper-slide">
                                        <div class="product-grid">
                                            <aside class="add-fav">
                                                <button type="button" class="btn <?= ($product_row['is_favorite'] == 1) ? '' : 'far' ?> fa-heart add-to-fav-btn <?= ($product_row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $product_row['id'] ?>"></button>
                                            </aside>
                                            <div class="product-image">
                                                <div class="product-image-container">
                                                    <a href="<?= base_url('products/details/' . $product_row['slug']) ?>">
                                                        <img class="pic-1" src="<?= $product_row['image_sm'] ?>">
                                                    </a>
                                                </div>
                                                <ul class="social">
                                                    <li>
                                                        <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $product_row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                                            <i class="fa fa-search"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?php
                                                        if (count($product_row['variants']) <= 1) {
                                                            $variant_id = $product_row['variants'][0]['id'];
                                                            $modal = "";
                                                        } else {
                                                            $variant_id = "";
                                                            $modal = "#quick-view";
                                                        }
                                                        ?>
                                                        <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                                        $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                                        $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                                        $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                                        ?>
                                                        <a href="#" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                                            <i class="fa fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?php
                                                        if (count($product_row['variants']) <= 1) {
                                                            $variant_id = $product_row['variants'][0]['id'];
                                                        } else {
                                                            $variant_id = "";
                                                        }
                                                        ?>
                                                        <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                            <i class="fa fa-random"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <?php if (isset($product_row['min_max_price']['special_price']) && $product_row['min_max_price']['special_price'] != '' && $product_row['min_max_price']['special_price'] != 0 && $product_row['min_max_price']['special_price'] < $product_row['min_max_price']['min_price']) { ?>
                                                    <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                    <span class="product-discount-label"><?= $product_row['min_max_price']['discount_in_percentage'] ?>%</span>
                                                <?php } ?>
                                            </div>
                                            <div class="col-md-12 mb-3 product-rating-small" dir="ltr">
                                                <input type="text" class="kv-fa rating-loading" value="<?= $product_row['rating'] ?>" data-size="sm" title="" readonly>
                                            </div>
                                            <div class="product-content">
                                                <h2 class="title" title="<?= output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name'])) ?>">
                                                    <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"><?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name']))) ?></a>
                                                </h2>
                                                <div class="price mb-1">
                                                    <?php $price = get_price_range_of_product($product_row['id']);
                                                    echo $price['range'];
                                                    ?>
                                                </div>
                                                <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                                $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                                $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                                $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                                ?>
                                                <a href="#" class="add-to-cart add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>

                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <div class="swiper-button-next product-image-swiper-next"></div>
                    <div class="swiper-button-prev product-image-swiper-prev"></div>
                </div>
                <?php } else if ($row['style'] == 'style_1') {
                if ($count_key != 0) {
                    if (!empty($offers) && !empty($offers[$count_key - 1])) { ?>
                        <a href="<?= $offers[$count_key - 1]['link'] ?>">
                            <img class="img-fluid lazy" data-src="<?= base_url($offers[$count_key - 1]['image']) ?>">
                        </a>
                <?php }
                }
                ?>
                <!-- Style 1 Design-->
                <div class="product-style-1 product-style-1-right product-section pt-4 pb-4 bg-white mt-2 mb-2 row ">
                    <div class="col-12 col-md-8 row products-list mx-auto">
                        <div class="col-12 my-4 featured-section-title pl-4 mx-0">
                            <div class="row">
                                <div class="col-md-12 px-0">
                                    <h3 class="section-title"><?= ucfirst($row['title']) ?></h3>
                                </div>
                                <div class="text-left my-auto title-sm col-6 pl-0"><?= strip_tags(output_escaping(str_replace('\r\n', '&#13;&#10;', $row['short_description']))) ?></div>
                                <div class="col-12 text-right"><a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>" class="featured-section-view-more"><?= !empty($this->lang->line('view_more')) ? $this->lang->line('view_more') : 'View More' ?></a>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <?php $product_count = count($row['product_details']) - 1; ?>
                        <?php $last_product = $row['product_details'][$product_count]; ?>
                        <?php if (isset($row['product_details']) && !empty($row['product_details'])) { ?>
                            <?php foreach ($row['product_details'] as $key => $product_row) { ?>
                                <?php if ($key != $product_count) { ?>
                                    <div class="col-md-4">
                                        <div class="product-grid">
                                            <aside class="add-fav"> <button type="button" class="btn <?= ($product_row['is_favorite'] == 1) ? '' : 'far' ?> fa-heart add-to-fav-btn <?= ($product_row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $product_row['id'] ?>"></button>
                                            </aside>
                                            <div class="product-image">
                                                <div class="product-image-container">
                                                    <a href="<?= base_url('products/details/' . $product_row['slug']) ?>">
                                                        <img class="pic-1 lazy" data-src="<?= $product_row['image_sm'] ?>">
                                                    </a>
                                                </div>
                                                <ul class="social">
                                                    <li>
                                                        <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $product_row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                                            <i class="fa fa-search"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?php
                                                        if (count($product_row['variants']) <= 1) {
                                                            $variant_id = $product_row['variants'][0]['id'];
                                                            $modal = "";
                                                        } else {
                                                            $variant_id = "";
                                                            $modal = "#quick-view";
                                                        }
                                                        ?>
                                                        <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                                        $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                                        $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                                        $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                                        ?>
                                                        <a href="#" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                                            <i class="fa fa-shopping-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?php
                                                        if (count($product_row['variants']) <= 1) {
                                                            $variant_id = $product_row['variants'][0]['id'];
                                                        } else {
                                                            $variant_id = "";
                                                        }
                                                        ?>
                                                        <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                            <i class="fa fa-random"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <?php if (isset($product_row['min_max_price']['special_price']) && $product_row['min_max_price']['special_price'] != '' && $product_row['min_max_price']['special_price'] != 0 && $product_row['min_max_price']['special_price'] < $product_row['min_max_price']['min_price']) { ?>
                                                    <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                                    <span class="product-discount-label"><?= $product_row['min_max_price']['discount_in_percentage'] ?>%</span>
                                                <?php } ?>
                                            </div>
                                            <div class="col-md-12 mb-3 product-rating-small" dir="ltr">
                                                <input type="text" class="kv-fa rating-loading" value="<?= $product_row['rating'] ?>" data-size="sm" title="" readonly>
                                            </div>
                                            <div class="product-content">
                                                <h2 class="title" title="<?= output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name'])) ?>">
                                                    <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"><?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name']))) ?></a>
                                                </h2>
                                                <div class="price mb-1">
                                                    <?php $price = get_price_range_of_product($product_row['id']);
                                                    echo $price['range'];
                                                    ?>
                                                </div>
                                                <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                                $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                                $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                                $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                                ?>
                                                <a href="#" class="add-to-cart add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                        <?php }
                        } ?>
                    </div>
                    <!-- Last Product -->
                    <div class="col-md-4 col-12 style-3-product-right-lg">
                        <div class="product-grid">
                            <aside class="add-fav"> <button type="button" class="btn <?= ($product_row['is_favorite'] == 1) ? '' : 'far' ?> fa-heart add-to-fav-btn <?= ($product_row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id=<?= $last_product['id'] ?>></button>
                            </aside>
                            <div class="product-image">
                                <div class="product-image-container d-flex align-items-center justify-content-center">
                                    <div>
                                        <a href="<?= base_url('products/details/' . $last_product['slug']) ?>">
                                            <img class="pic-1 lazy" data-src="<?= $last_product['image_sm'] ?>">
                                        </a>
                                    </div>
                                </div>
                                <ul class="social">
                                    <li>
                                        <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $last_product['id'] ?>" data-product-variant-id="<?= $last_product['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <?php
                                        if (count($last_product['variants']) <= 1) {
                                            $variant_id = $last_product['variants'][0]['id'];
                                            $modal = "";
                                        } else {
                                            $variant_id = "";
                                            $modal = "#quick-view";
                                        }
                                        ?>
                                        <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                        $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                        $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                        $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                        ?>
                                        <a href="#" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                            <i class="fa fa-shopping-cart"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <?php
                                        if (count($product_row['variants']) <= 1) {
                                            $variant_id = $product_row['variants'][0]['id'];
                                        } else {
                                            $variant_id = "";
                                        }
                                        ?>
                                        <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                            <i class="fa fa-random"></i>
                                        </a>
                                    </li>
                                </ul>
                                <?php if (isset($last_product['min_max_price']['special_price']) && $last_product['min_max_price']['special_price'] != '' && $last_product['min_max_price']['special_price'] != 0 && $last_product['min_max_price']['special_price'] < $last_product['min_max_price']['min_price']) { ?>
                                    <div class="home-badge"><span class="badge badge-pill badge-primary">Sale</span></div>
                                <?php } ?>
                            </div>
                            <div class="col-md-12 mb-3 product-rating-small" dir="ltr">
                                <input type="text" class="kv-fa rating-loading" value="<?= $last_product['rating'] ?>" data-size="sm" title="" readonly>
                            </div>
                            <div class="product-content">
                                <h2 class="title" title="<?= output_escaping(str_replace('\r\n', '&#13;&#10;', $last_product['name'])) ?>">
                                    <a href="<?= base_url('products/details/' . $last_product['slug']) ?>"><?= output_escaping(str_replace('\r\n', '&#13;&#10;', word_limit($last_product['name']))) ?></a>
                                </h2>
                                <div class="price mb-1">
                                    <?php $price = get_price_range_of_product($last_product['id']);
                                    echo $price['range'];
                                    ?>
                                </div>
                                <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                ?>
                                <a href="#" class="add-to-cart add_to_cart" data-product-id="<?= $last_product['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else if ($row['style'] == 'style_2') {
                if ($count_key != 0) {
                    if (!empty($offers) && !empty($offers[$count_key - 1])) { ?>
                        <a href="<?= $offers[$count_key - 1]['link'] ?>">
                            <img class="img-fluid lazy" data-src="<?= base_url($offers[$count_key - 1]['image']) ?>">
                        </a>
                <?php }
                }
                ?>
                <!-- Style 2 Design -->
                <!-- First Product -->
                <?php $first_product = $row['product_details'][0]; ?>
                <div class="product-style-1 product-style-1-left product-section pt-4 pb-4 bg-white mt-2 mb-2 row">
                    <div class="col-md-4 col-12 style-3-product-right-lg">
                        <div class="product-grid">
                            <aside class="add-fav">
                                <button type="button" class="btn <?= ($first_product['is_favorite'] == 1) ? '' : 'far' ?> fa-heart add-to-fav-btn <?= ($first_product['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $first_product['id'] ?>"></button>
                            </aside>
                            <?php if (isset($first_product['min_max_price']['special_price']) && $first_product['min_max_price']['special_price'] != '' && $first_product['min_max_price']['special_price'] != 0 && $first_product['min_max_price']['special_price'] < $first_product['min_max_price']['min_price']) { ?>
                                <div class="home-badge"><span class="badge badge-pill badge-primary">Sale</span></div>
                            <?php } ?>
                            <div class="product-image">
                                <div class="product-image-container d-flex align-items-center justify-content-center">
                                    <div>
                                        <a href="<?= base_url('products/details/' . $first_product['slug']) ?>">
                                            <img class="pic-1 lazy" data-src="<?= $first_product['image_sm'] ?>">
                                        </a>
                                    </div>
                                </div>
                                <ul class="social">
                                    <li>
                                        <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $first_product['id'] ?>" data-product-variant-id="<?= $first_product['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <?php
                                        if (count($first_product['variants']) <= 1) {
                                            $variant_id = $first_product['variants'][0]['id'];
                                            $modal = "";
                                        } else {
                                            $variant_id = "";
                                            $modal = "#quick-view";
                                        }
                                        ?>
                                        <?php $variant_price = ($first_product['variants'][0]['special_price'] > 0 && $first_product['variants'][0]['special_price'] != '') ? $first_product['variants'][0]['special_price'] : $first_product['variants'][0]['price'];
                                        $data_min = (isset($first_product['minimum_order_quantity']) && !empty($first_product['minimum_order_quantity'])) ? $first_product['minimum_order_quantity'] : 1;
                                        $data_step = (isset($first_product['minimum_order_quantity']) && !empty($first_product['quantity_step_size'])) ? $first_product['quantity_step_size'] : 1;
                                        $data_max = (isset($first_product['total_allowed_quantity']) && !empty($first_product['total_allowed_quantity'])) ? $first_product['total_allowed_quantity'] : 0;
                                        ?>
                                        <a href="#" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $first_product['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $first_product['name'] ?>" data-product-image="<?= $first_product['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $first_product['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                            <i class="fa fa-shopping-cart"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <?php
                                        if (isset($product_row['variants']) && !empty($product_row['variants'])) {
                                            if (count($product_row['variants']) <= 1) {
                                                $variant_id = $product_row['variants'][0]['id'];
                                            } else {
                                                $variant_id = "";
                                            }
                                        ?>
                                            <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                <i class="fa fa-random"></i>
                                            </a>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </div>
                            <div itemscope itemtype="https://schema.org/Product">
                                <?php if (isset($product_row['rating']) && isset($product_row['no_of_rating']) && !empty($product_row['no_of_rating']) &&  !empty($product_row['rating']) && $product_row['no_of_rating'] != "") { ?>
                                    <div class="col-md-12 mb-3 product-rating-small" dir="ltr" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                                        <meta itemprop="reviewCount" content="<?= $product_row['no_of_rating'] ?>" />
                                        <meta itemprop="ratingValue" content="<?= $product_row['rating'] ?>" />
                                        <input type="text" class="kv-fa rating-loading" value="<?= $product_row['rating'] ?>" data-size="sm" title="" readonly>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-md-12 mb-3 product-rating-small" dir="ltr">
                                        <input type="text" class="kv-fa rating-loading" value="<?= $first_product['rating'] ?>" data-size="sm" title="" readonly>
                                    </div>
                                <?php } ?>
                                <div class="product-content">
                                    <h3 class="title" title="<?= $first_product['name'] ?>" itemprop="name">
                                        <a href="<?= base_url('products/details/' . $first_product['slug']) ?>"><?= $first_product['name'] ?></a>
                                    </h3>
                                    <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                        <meta itemprop="price" content="<?= $first_product['variants'][0]['price']; ?>" />
                                    </div>
                                    <div class="price mb-1">
                                        <?php $price = get_price_range_of_product($first_product['id']);
                                        echo $price['range'];
                                        ?>
                                    </div>
                                    <?php $variant_price = ($first_product['variants'][0]['special_price'] > 0 && $first_product['variants'][0]['special_price'] != '') ? $first_product['variants'][0]['special_price'] : $first_product['variants'][0]['price'];
                                    $data_min = (isset($first_product['minimum_order_quantity']) && !empty($first_product['minimum_order_quantity'])) ? $first_product['minimum_order_quantity'] : 1;
                                    $data_step = (isset($first_product['minimum_order_quantity']) && !empty($first_product['quantity_step_size'])) ? $first_product['quantity_step_size'] : 1;
                                    $data_max = (isset($first_product['total_allowed_quantity']) && !empty($first_product['total_allowed_quantity'])) ? $first_product['total_allowed_quantity'] : 0;
                                    ?>
                                    <a href="#" class="add-to-cart add_to_cart" data-product-id="<?= $first_product['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $first_product['name'] ?>" data-product-image="<?= $first_product['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $first_product['short_description']; ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 row products-list mx-auto">
                        <div class="col-12 my-4 featured-section-title pl-4 mx-0">
                            <div class="row">
                                <div class="col-md-12 px-0">
                                    <h3 class="section-title"><?= ucfirst($row['title']) ?></h3>
                                </div>
                                <div class="text-left my-auto title-sm col-6"><?= strip_tags($row['short_description']) ?></div>
                                <div class="col-6 text-right"><a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>" class="featured-section-view-more"><?= !empty($this->lang->line('view_more')) ? $this->lang->line('view_more') : 'View More' ?></a>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <?php $product_count = count($row['product_details']) - 1; ?>
                        <?php foreach ($row['product_details'] as $key => $product_row) { ?>
                            <?php if ($key != 0) { ?>
                                <div class="col-md-4 col-6">
                                    <div class="product-grid">
                                        <aside class="add-fav">
                                            <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($product_row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $product_row['id'] ?>"></button>
                                        </aside>
                                        <div class="product-image">
                                            <div class="product-image-container">
                                                <a href="<?= base_url('products/details/' . $product_row['slug']) ?>">
                                                    <img class="pic-1 lazy" data-src="<?= $product_row['image_sm'] ?>">
                                                </a>
                                            </div>
                                            <ul class="social">
                                                <li>
                                                    <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $product_row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                                        <i class="fa fa-search"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <?php
                                                    if (count($product_row['variants']) <= 1) {
                                                        $variant_id = $product_row['variants'][0]['id'];
                                                        $modal = "";
                                                    } else {
                                                        $variant_id = "";
                                                        $modal = "#quick-view";
                                                    }
                                                    ?>
                                                    <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                                    $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                                    $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                                    $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                                    ?>
                                                    <a href="#" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <?php
                                                    if (count($product_row['variants']) <= 1) {
                                                        $variant_id = $product_row['variants'][0]['id'];
                                                    } else {
                                                        $variant_id = "";
                                                    }
                                                    ?>
                                                    <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                        <i class="fa fa-random"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                            <?php if (isset($product_row['min_max_price']['special_price']) && $product_row['min_max_price']['special_price'] != '' && $product_row['min_max_price']['special_price'] != 0 && $product_row['min_max_price']['special_price'] < $product_row['min_max_price']['min_price']) { ?>
                                                <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>

                                                <span class="product-discount-label"><?= $product_row['min_max_price']['discount_in_percentage'] ?>%</span>


                                            <?php } ?>
                                        </div>
                                        <div class="col-md-12 mb-3 product-rating-small" dir="ltr">
                                            <input type="text" class="kv-fa rating-loading" value="<?= $product_row['rating'] ?>" data-size="sm" title="" readonly>
                                        </div>
                                        <div class="product-content">
                                            <h3 class="title" title="<?= $product_row['name'] ?>">
                                                <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"><?= $product_row['name'] ?></a>
                                            </h3>
                                            <div class="price mb-1">
                                                <?php $price = get_price_range_of_product($product_row['id']);
                                                echo $price['range'];
                                                ?>
                                            </div>
                                            <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                            $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                            $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                            $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                            ?>
                                            <a href="#" class="add-to-cart add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <?php } else if ($row['style'] == 'style_3') {
                if ($count_key != 0) {
                    if (!empty($offers) && !empty($offers[$count_key - 1])) { ?>
                        <a href="<?= $offers[$count_key - 1]['link'] ?>">
                            <img class="img-fluid lazy" data-src="<?= base_url($offers[$count_key - 1]['image']) ?>">
                        </a>
                <?php }
                }
                ?>
                <!-- Style 3 Design -->
                <div class="product-style-2 product-style-2-left product-section py-2 bg-white mt-2 ">
                    <div class="col-12 col-md-12 row products-list mx-auto">
                        <?php foreach ($row['product_details'] as $product_row) { ?>
                            <div class="col-md-2">
                                <div class="product-grid">
                                    <aside class="add-fav">
                                        <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($product_row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $product_row['id'] ?>"></button>
                                    </aside>
                                    <div class="product-image">
                                        <div class="product-image-container">
                                            <a href="<?= base_url('products/details/' . $product_row['slug']) ?>">
                                                <img class="pic-1 lazy" data-src="<?= $product_row['image_sm'] ?>">
                                            </a>
                                        </div>
                                        <ul class="social">
                                            <li>
                                                <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $product_row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                                    <i class="fa fa-search"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <?php
                                                if (count($product_row['variants']) <= 1) {
                                                    $variant_id = $product_row['variants'][0]['id'];
                                                    $modal = "";
                                                } else {
                                                    $variant_id = "";
                                                    $modal = "#quick-view";
                                                }
                                                ?>
                                                <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                                $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                                $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                                $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                                ?>
                                                <a href="#" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                                    <i class="fa fa-shopping-cart"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <?php
                                                if (count($product_row['variants']) <= 1) {
                                                    $variant_id = $product_row['variants'][0]['id'];
                                                } else {
                                                    $variant_id = "";
                                                }
                                                ?>
                                                <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                    <i class="fa fa-random"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <?php if (isset($product_row['min_max_price']['special_price']) && $product_row['min_max_price']['special_price'] != '' && $product_row['min_max_price']['special_price'] != 0 && $product_row['min_max_price']['special_price'] < $product_row['min_max_price']['min_price']) { ?>
                                            <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                            <span class="product-discount-label"><?= $product_row['min_max_price']['discount_in_percentage'] ?>%</span>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-12 mb-3 product-rating-small" dir="ltr">
                                        <input type="text" class="kv-fa rating-loading" value="<?= $product_row['rating'] ?>" data-size="sm" title="" readonly>
                                    </div>
                                    <div class="product-content">
                                        <h2 class="title" title="<?= output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name'])) ?>">
                                            <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"><?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name']))) ?></a>
                                        </h2>
                                        <div class="price mb-1">
                                            <?php $price = get_price_range_of_product($product_row['id']);
                                            echo $price['range'];
                                            ?>
                                        </div>
                                        <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                        $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                        $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                        $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                        ?>
                                        <a href="#" class="add-to-cart add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-2 col-6">
                            <div class="product-grid">
                                <div class="product-image featured-section">
                                    <div class="product-image-container">
                                        <div class="featured-section-title">
                                            <div class="col-md-12 text-left">
                                                <h3 class="section-title "><?= ucfirst($row['title']) ?></h3>
                                                <div class="title-sm"><?= strip_tags(output_escaping(str_replace('\r\n', '&#13;&#10;', $row['short_description']))) ?></div>
                                                <div class="col-12"><a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>" class="featured-section-view-more"><?= !empty($this->lang->line('view_more')) ? $this->lang->line('view_more') : 'View More' ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else if ($row['style'] == 'style_4') {
                if ($count_key != 0) {
                    if (!empty($offers) && !empty($offers[$count_key - 1])) { ?>
                        <a href="<?= $offers[$count_key - 1]['link'] ?>">
                            <img class="img-fluid lazy" data-src="<?= base_url($offers[$count_key - 1]['image']) ?>">
                        </a>
                <?php }
                }
                ?>
                <!-- Style 4 Design -->
                <div class="product-style-2 product-style-2-left product-section py-2 bg-white mt-2 ">
                    <div class="col-12 col-md-12 row products-list mx-auto">
                        <div class="col-md-2">
                            <div class="product-grid">
                                <div class="product-image featured-section">
                                    <div class="product-image-container">
                                        <div class="featured-section-title">
                                            <div class="col-md-12 text-left">
                                                <h3 class="section-title "><?= ucfirst($row['title']) ?></h3>
                                                <div class="title-sm"><?= strip_tags(output_escaping(str_replace('\r\n', '&#13;&#10;', $row['short_description']))) ?></div>
                                                <div class="col-12"><a href="<?= base_url('products/section/' . $row['id'] . '/' . $row['slug']) ?>" class="featured-section-view-more"><?= !empty($this->lang->line('view_more')) ? $this->lang->line('view_more') : 'View More' ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php foreach ($row['product_details'] as $product_row) { ?>
                            <div class="col-md-2">
                                <div class="product-grid">
                                    <aside class="add-fav">
                                        <button type="button" class="btn far fa-heart add-to-fav-btn <?= ($product_row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-product-id="<?= $product_row['id'] ?>"></button>
                                    </aside>
                                    <div class="product-image">
                                        <div class="product-image-container">
                                            <a href="<?= base_url('products/details/' . $product_row['slug']) ?>">
                                                <img class="pic-1 lazy" data-src="<?= $product_row['image_sm'] ?>">
                                            </a>
                                        </div>
                                        <ul class="social">
                                            <li>
                                                <a href="#" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $product_row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view">
                                                    <i class="fa fa-search"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <?php
                                                if (count($product_row['variants']) <= 1) {
                                                    $variant_id = $product_row['variants'][0]['id'];
                                                    $modal = "";
                                                } else {
                                                    $variant_id = "";
                                                    $modal = "#quick-view";
                                                }
                                                ?>
                                                <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                                $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                                $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                                $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                                ?>
                                                <a href="#" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image']; ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>">
                                                    <i class="fa fa-shopping-cart"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <?php
                                                if (count($product_row['variants']) <= 1) {
                                                    $variant_id = $product_row['variants'][0]['id'];
                                                } else {
                                                    $variant_id = "";
                                                }
                                                ?>
                                                <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                    <i class="fa fa-random"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <?php if (isset($product_row['min_max_price']['special_price']) && $product_row['min_max_price']['special_price'] != '' && $product_row['min_max_price']['special_price'] != 0 && $product_row['min_max_price']['special_price'] < $product_row['min_max_price']['min_price']) { ?>
                                            <span class="product-new-label"><?= !empty($this->lang->line('sale')) ? $this->lang->line('sale') : 'Sale' ?></span>
                                            <span class="product-discount-label"><?= $product_row['min_max_price']['discount_in_percentage'] ?>%</span>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-12 mb-3 product-rating-small" dir="ltr">
                                        <input type="text" class="kv-fa rating-loading" value="<?= $product_row['rating'] ?>" data-size="sm" title="" readonly>
                                    </div>
                                    <div class="product-content">
                                        <h2 class="title" title="<?= output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name'])) ?>">
                                            <a href="<?= base_url('products/details/' . $product_row['slug']) ?>"><?= word_limit(output_escaping(str_replace('\r\n', '&#13;&#10;', $product_row['name']))) ?></a>
                                        </h2>
                                        <div class="price mb-1">
                                            <?php $price = get_price_range_of_product($product_row['id']);
                                            echo $price['range'];
                                            ?>
                                        </div>
                                        <?php $variant_price = ($product_row['variants'][0]['special_price'] > 0 && $product_row['variants'][0]['special_price'] != '') ? $product_row['variants'][0]['special_price'] : $product_row['variants'][0]['price'];
                                        $data_min = (isset($product_row['minimum_order_quantity']) && !empty($product_row['minimum_order_quantity'])) ? $product_row['minimum_order_quantity'] : 1;
                                        $data_step = (isset($product_row['minimum_order_quantity']) && !empty($product_row['quantity_step_size'])) ? $product_row['quantity_step_size'] : 1;
                                        $data_max = (isset($product_row['total_allowed_quantity']) && !empty($product_row['total_allowed_quantity'])) ? $product_row['total_allowed_quantity'] : 0;
                                        ?>
                                        <a href="#" class="add-to-cart add_to_cart" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-product-title="<?= $product_row['name'] ?>" data-product-image="<?= $product_row['image'] ?>" data-product-price="<?= $variant_price; ?>" data-min="<?= $data_min; ?>" data-step="<?= $data_step; ?>" data-product-description="<?= $product_row['short_description']; ?>" data-izimodal-open="<?= $modal ?>"><i class="fas fa-cart-plus"></i> <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
    <?php }
        $offer_counter++;
    } ?>

    <?php $web_settings = get_settings('web_settings', true); ?>
    <?php if (isset($web_settings['app_download_section']) && $web_settings['app_download_section'] == 1) { ?>
        <div class="container-fluid mobile-app call-to-action-section product-section pt-4 pb-4 bg-white mt-2 mb-2">
            <div class="row">
                <div class="col-md-6 col-lg-4 offset-lg-1">

                    <div class="mobile-app-wrapper">
                        <img src="<?= THEME_ASSETS_URL . 'demo/avtars/4861083.jpg' ?>" alt="">
                    </div>

                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="text-area">
                        <h2 class="mobile-app-text"><?= $web_settings['app_download_section_title'] ?></h2>
                        <h3 class="mt-3 header-p"><?= $web_settings['app_download_section_tagline'] ?></h3>
                        <p class="header-p"><?= $web_settings['app_download_section_short_description'] ?></p>
                        <div class="mt-3">
                            <a href="<?= $web_settings['app_download_section_appstore_url'] ?>" target="_blank"><img src="<?= THEME_ASSETS_URL . 'demo/app-store/app-store.png' ?>" alt="" class="download_section" width="150"></a>
                            <a href="<?= $web_settings['app_download_section_playstore_url'] ?>" target="_blank"><img src="<?= THEME_ASSETS_URL . 'demo/app-store/google-play-store.png' ?>" alt="" class="download_section" width="150"></a>
                        </div>
                    </div>
                </div>
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    <?php } ?>
</section>
<section class="freedel-sec">
    <div class="row row-fluid dark-footer-margin">
        <?php if (isset($web_settings['shipping_mode']) && $web_settings['shipping_mode'] == 1) { ?>
            <div class="col text-left text-md-center">
                <div class="column_inner custom_column">
                    <div class="wrapper">
                        <div class="info-box-wrapper inline-element">
                            <div class="box-icon info-box custom light-color">
                                <div class="icon-box-wrapper">
                                    <div class="info-box-icon">
                                        <div class="svg-wrapper">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="40pt" viewBox="1 -104 511.99975 511" width="100pt">
                                                <path class="cls-1" d="m203.84375 227.585938h156.140625c17.679687-52.050782 91.449219-51.550782 108.957031 0h13.046875v-75.027344c0-9.347656-7.660156-17.007813-17.003906-17.007813-38.347656 0-76.695313 0-115.042969 0-8.289062 0-15.007812-6.71875-15.007812-15.003906v-90.035156h-257.09375v18.003906h27.589844c19.753906 0 19.753906 30.011719 0 30.011719h-27.589844v29.011718h72.027344c19.757812 0 19.757812 30.011719 0 30.011719h-72.027344v90.03125h17.050781c17.675781-52.050781 91.449219-51.542969 108.953125.003907zm-156.015625-149.058594h-18.003906c-19.757813 0-19.757813-30.011719 0-30.011719h18.003906v-33.011719c0-8.285156 6.71875-15.003906 15.007813-15.003906 22.964843 0 275.351562.097656 275.351562 0 41.078125 0 76.859375 20.335938 97.914062 55.667969l29.421876 49.375c25.675781.289062 46.476562 21.292969 46.476562 47.011719v90.035156c0 8.285156-6.71875 15.003906-15.003906 15.003906h-26.160156c-5.378907 26.484375-28.78125 46.015625-56.371094 46.015625s-50.992188-19.53125-56.371094-46.015625h-152.355469c-5.378906 26.484375-28.78125 46.015625-56.371093 46.015625-27.589844 0-50.992188-19.53125-56.371094-46.015625h-30.160156c-8.289063 0-15.007813-6.71875-15.007813-15.003906v-45.019532h-33.011719c-19.753906 0-19.753906-30.007812 0-30.007812h33.011719v-30.011719h-33.011719c-19.753906 0-19.753906-30.011719 0-30.011719h33.011719zm386.089844 148.109375c10.742187 10.742187 10.742187 28.164062 0 38.90625-17.28125 17.28125-46.964844 4.988281-46.964844-19.453125s29.683594-36.734375 46.964844-19.453125zm-3.222657-121.097657h-65.75v-70.960937c19.144532 6.136719 34.800782 19.019531 45.480469 36.945313zm-261.875 121.097657c10.742188 10.742187 10.742188 28.164062 0 38.90625-17.28125 17.28125-46.964843 4.988281-46.964843-19.453125.003906-24.441406 29.683593-36.734375 46.964843-19.453125zm0 0" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="info-box-content">
                                    <h4 class="info-box-title"><?= $web_settings['shipping_title'] ?></h4>
                                    <div class="info-box-inner">
                                        <p><?= output_escaping(str_replace('\r\n', '&#13;&#10;', $web_settings['shipping_description'])) ?></p>
                                    </div>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($web_settings['return_mode']) && $web_settings['return_mode'] == 1) { ?>
            <div class="col text-left text-md-center">
                <div class="column_inner custom_column">
                    <div class="wrapper">
                        <div class="info-box-wrapper inline-element">
                            <div class="box-icon info-box custom light-color">
                                <div class="icon-box-wrapper">
                                    <div class="info-box-icon">
                                        <div class="svg-wrapper">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="40pt" viewBox="0 0 512 512" width="100pt">
                                                <path class="cls-1" d="m212 367h89c33.085938 0 60-26.914062 60-60v-43.402344c9.128906-1.851562 16-9.921875 16-19.597656v-70c0-11.046875-8.953125-20-20-20h-201c-11.046875 0-20 8.953125-20 20v70c0 9.675781 6.871094 17.746094 16 19.597656v43.402344c0 33.085938 26.914062 60 60 60zm89-40h-89c-11.027344 0-20-8.972656-20-20v-41h46v8c0 11.046875 8.953125 20 20 20s20-8.953125 20-20v-8h43v41c0 11.027344-8.972656 20-20 20zm-125-133h161v30h-161zm-176-60v-48c0-11.046875 8.953125-20 20-20s20 8.953125 20 20v32.535156c19.679688-30.890625 45.8125-57.316406 76.84375-77.445312 41.4375-26.878906 89.554688-41.089844 139.15625-41.089844 68.378906 0 132.667969 26.628906 181.019531 74.980469 48.351563 48.351562 74.980469 112.640625 74.980469 181.019531 0 11.046875-8.953125 20-20 20s-20-8.953125-20-20c0-119.101562-96.898438-216-216-216-75.664062 0-145.871094 40.15625-184.726562 104h26.726562c11.046875 0 20 8.953125 20 20s-8.953125 20-20 20h-48c-27.570312 0-50-22.429688-50-50zm512 244v47c0 11.046875-8.953125 20-20 20s-20-8.953125-20-20v-33.105469c-19.789062 31.570313-46.289062 58.542969-77.84375 79.011719-41.4375 26.882812-89.554688 41.09375-139.15625 41.09375-68.339844 0-132.464844-26.644531-180.5625-75.023438-48-48.285156-74.4375-112.554687-74.4375-180.976562 0-11.046875 8.953125-20 20-20s20 8.953125 20 20c0 119.101562 96.449219 216 215 216 75.667969 0 145.871094-40.15625 184.726562-104h-26.726562c-11.046875 0-20-8.953125-20-20s8.953125-20 20-20h49c27.570312 0 50 22.429688 50 50zm0 0">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="info-box-content">
                                    <h4 class="info-box-title"><?= $web_settings['return_title'] ?></h4>
                                    <div class="info-box-inner">
                                        <p><?= output_escaping(str_replace('\r\n', '&#13;&#10;', $web_settings['return_description'])) ?></p>
                                    </div>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($web_settings['support_mode']) && $web_settings['support_mode'] == 1) { ?>
            <div class="col text-left text-md-center">
                <div class="column_inner custom_column">
                    <div class="wrapper">
                        <div class="info-box-wrapper inline-element">
                            <div class="box-icon info-box custom light-color">
                                <div class="icon-box-wrapper">
                                    <div class="info-box-icon">
                                        <div class="svg-wrapper">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="40pt" viewBox="-21 -21 682.66669 682.66669" width="100pt">
                                                <path class="cls-1" d="m546.273438 93.726562c-60.4375-60.441406-140.800782-93.726562-226.273438-93.726562s-165.835938 33.285156-226.273438 93.726562c-60.441406 60.4375-93.726562 140.800782-93.726562 226.273438s33.285156 165.835938 93.726562 226.273438c60.4375 60.441406 140.800782 93.726562 226.273438 93.726562 2.574219 0 5.195312-.03125 7.78125-.09375 10.359375-.253906 18.546875-8.847656 18.292969-19.199219-.25-10.355469-8.808594-18.523437-19.199219-18.289062-2.285156.050781-4.601562.082031-6.875.082031-155.773438 0-282.5-126.726562-282.5-282.5s126.726562-282.5 282.5-282.5 282.5 126.726562 282.5 282.5c0 56.867188-16.898438 111.78125-48.867188 158.8125-6.230468 9.175781-14.601562 14.414062-25.570312 16.023438-10.976562 1.605468-20.492188-1.011719-29.097656-8.003907l-14.855469-12.074219c1.78125-1.429687 3.527344-2.898437 5.242187-4.421874 11.019532-9.785157 16.503907-23.269532 15.4375-37.972657-1.0625-14.703125-8.4375-27.253906-20.757812-35.351562l-59.386719-39.027344c-16.769531-11.023437-38.542969-10.058594-54.183593 2.386719-8.796876 6.996094-20.945313 6.308594-28.890626-1.636719l-60.304687-60.300781c-7.945313-7.945313-8.632813-20.097656-1.636719-28.894532 12.445313-15.636718 13.410156-37.414062 2.386719-54.183593l-39.027344-59.386719c-8.097656-12.320312-20.648437-19.6875-35.351562-20.757812-14.707031-1.054688-28.1875 4.417968-37.972657 15.4375-48.261718 54.347656-45.792968 137.199218 5.625 188.617187l125.445313 125.445313c26.75 26.753906 62.007813 40.257812 97.34375 40.253906 17.484375 0 34.972656-3.339844 51.46875-9.984375l25.757813 20.949219c16.730468 13.601562 36.851562 19.140624 58.191406 16.007812 21.339844-3.125 39.027344-14.207031 51.152344-32.039062 36.210937-53.277344 55.351562-115.484376 55.351562-179.898438 0-85.472656-33.285156-165.835938-93.726562-226.273438zm0 0" />
                                                <path class="cls-1" d="m537.148438 275.257812c-8.117188 0-15.59375-5.304687-17.988282-13.492187-24.882812-85.101563-101.113281-145-189.6875-149.046875-10.339844-.472656-18.34375-9.242188-17.871094-19.589844.472657-10.339844 9.238282-18.339844 19.585938-17.871094 104.578125 4.78125 194.585938 75.503907 223.964844 175.984376 2.902344 9.9375-2.792969 20.355468-12.734375 23.257812-1.753907.515625-3.527344.757812-5.269531.757812zm0 0" />
                                                <path class="cls-1" d="m465.160156 296.308594c-8.113281 0-15.59375-5.3125-17.988281-13.492188-15.886719-54.34375-64.558594-92.589844-121.121094-95.179687-10.34375-.46875-18.347656-9.238281-17.871093-19.585938.472656-10.347656 9.242187-18.347656 19.585937-17.871093 72.5625 3.316406 135.015625 52.390624 155.402344 122.109374 2.90625 9.941407-2.796875 20.351563-12.734375 23.257813-1.757813.515625-3.527344.761719-5.273438.761719zm0 0" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="info-box-content">
                                    <h4 class="info-box-title"><?= $web_settings['support_title'] ?></h4>
                                    <div class="info-box-inner">
                                        <p><?= output_escaping(str_replace('\r\n', '&#13;&#10;', $web_settings['support_description'])) ?></p>
                                    </div>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (isset($web_settings['safety_security_mode']) && $web_settings['safety_security_mode'] == 1) { ?>
            <div class="col text-left text-md-center">
                <div class="column_inner custom_column">
                    <div class="wrapper">
                        <div class="info-box-wrapper inline-element">
                            <div class="box-icon info-box custom light-color">
                                <div class="icon-box-wrapper">
                                    <div class="info-box-icon">
                                        <div class="svg-wrapper">
                                            <svg width="100pt" viewBox="-38 0 512 512.00142" version="1.1" height="40pt" xmlns="http://www.w3.org/2000/svg">
                                                <g id="surface1">
                                                    <path class="cls-1" d="M 435.488281 138.917969 L 435.472656 138.519531 C 435.25 133.601562 435.101562 128.398438 435.011719 122.609375 C 434.59375 94.378906 412.152344 71.027344 383.917969 69.449219 C 325.050781 66.164062 279.511719 46.96875 240.601562 9.042969 L 240.269531 8.726562 C 227.578125 -2.910156 208.433594 -2.910156 195.738281 8.726562 L 195.40625 9.042969 C 156.496094 46.96875 110.957031 66.164062 52.089844 69.453125 C 23.859375 71.027344 1.414062 94.378906 0.996094 122.613281 C 0.910156 128.363281 0.757812 133.566406 0.535156 138.519531 L 0.511719 139.445312 C -0.632812 199.472656 -2.054688 274.179688 22.9375 341.988281 C 36.679688 379.277344 57.492188 411.691406 84.792969 438.335938 C 115.886719 468.679688 156.613281 492.769531 205.839844 509.933594 C 207.441406 510.492188 209.105469 510.945312 210.800781 511.285156 C 213.191406 511.761719 215.597656 512 218.003906 512 C 220.410156 512 222.820312 511.761719 225.207031 511.285156 C 226.902344 510.945312 228.578125 510.488281 230.1875 509.925781 C 279.355469 492.730469 320.039062 468.628906 351.105469 438.289062 C 378.394531 411.636719 399.207031 379.214844 412.960938 341.917969 C 438.046875 273.90625 436.628906 199.058594 435.488281 138.917969 Z M 384.773438 331.523438 C 358.414062 402.992188 304.605469 452.074219 220.273438 481.566406 C 219.972656 481.667969 219.652344 481.757812 219.320312 481.824219 C 218.449219 481.996094 217.5625 481.996094 216.679688 481.820312 C 216.351562 481.753906 216.03125 481.667969 215.734375 481.566406 C 131.3125 452.128906 77.46875 403.074219 51.128906 331.601562 C 28.09375 269.097656 29.398438 200.519531 30.550781 140.019531 L 30.558594 139.683594 C 30.792969 134.484375 30.949219 129.039062 31.035156 123.054688 C 31.222656 110.519531 41.207031 100.148438 53.765625 99.449219 C 87.078125 97.589844 116.34375 91.152344 143.234375 79.769531 C 170.089844 68.402344 193.941406 52.378906 216.144531 30.785156 C 217.273438 29.832031 218.738281 29.828125 219.863281 30.785156 C 242.070312 52.378906 265.921875 68.402344 292.773438 79.769531 C 319.664062 91.152344 348.929688 97.589844 382.246094 99.449219 C 394.804688 100.148438 404.789062 110.519531 404.972656 123.058594 C 405.0625 129.074219 405.21875 134.519531 405.453125 139.683594 C 406.601562 200.253906 407.875 268.886719 384.773438 331.523438 Z M 384.773438 331.523438 ">
                                                    </path>
                                                    <path class="cls-1" d="M 217.996094 128.410156 C 147.636719 128.410156 90.398438 185.652344 90.398438 256.007812 C 90.398438 326.367188 147.636719 383.609375 217.996094 383.609375 C 288.351562 383.609375 345.59375 326.367188 345.59375 256.007812 C 345.59375 185.652344 288.351562 128.410156 217.996094 128.410156 Z M 217.996094 353.5625 C 164.203125 353.5625 120.441406 309.800781 120.441406 256.007812 C 120.441406 202.214844 164.203125 158.453125 217.996094 158.453125 C 271.785156 158.453125 315.546875 202.214844 315.546875 256.007812 C 315.546875 309.800781 271.785156 353.5625 217.996094 353.5625 Z M 217.996094 353.5625 ">
                                                    </path>
                                                    <path class="cls-1" d="M 254.667969 216.394531 L 195.402344 275.660156 L 179.316406 259.574219 C 173.449219 253.707031 163.9375 253.707031 158.070312 259.574219 C 152.207031 265.441406 152.207031 274.953125 158.070312 280.816406 L 184.78125 307.527344 C 187.714844 310.460938 191.558594 311.925781 195.402344 311.925781 C 199.246094 311.925781 203.089844 310.460938 206.023438 307.527344 L 275.914062 237.636719 C 281.777344 231.769531 281.777344 222.257812 275.914062 216.394531 C 270.046875 210.523438 260.535156 210.523438 254.667969 216.394531 Z M 254.667969 216.394531 ">
                                                    </path>
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="info-box-content">
                                    <h4 class="info-box-title"><?= $web_settings['safety_security_title'] ?></h4>
                                    <div class="info-box-inner">
                                        <p><?= output_escaping(str_replace('\r\n', '&#13;&#10;', $web_settings['safety_security_description'])) ?></p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>