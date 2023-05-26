<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'Favorites' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'favorites' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<section class="my-account-section">
    <div class="main-content">
        <div class="col-md-12 mt-5 mb-3">
            <div class="user-detail align-items-center">
                <div class="ml-3">
                    <h6 class="text-muted mb-0"><?= !empty($this->lang->line('hello')) ? $this->lang->line('hello') : 'Hello' ?></h6>
                    <h5 class="mb-0"><?= $user->username ?></h5>
                </div>
            </div>
        </div>
        <div class="row m5">
            <div class="col-md-4">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>

            <div class="col-md-8 col-12">
                <div class=' border-0'>
                    <div class="card-header bg-white">
                        <h1 class="h4"><?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'favorites' ?></h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if (isset($products) && !empty($products)) {
                                foreach ($products as $row) { ?>
                                    <div class="col-lg-4 col-sm-6 mt-5">
                                        <div class="product-grid">
                                            <aside class="add-favorite">
                                                <button type="button" class="btn fa-heart add-to-fav-btn fa text-danger" data-product-id="<?= $row['id'] ?>"></button>
                                            </aside>
                                            <div class="product-image">
                                                <div class="product-image-container">
                                                    <a href="#">
                                                        <img class="pic-1" src="<?= $row['image_sm'] ?>">
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
                                                    <li><a href="" class="quick-view-btn" data-tip="Quick View" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $row['variants'][0]['id'] ?>" data-izimodal-open="#quick-view"><i class="fa fa-search"></i></a></li>
                                                    <li><a href="" data-tip="Add to Cart" class="add_to_cart" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>"><i class="fa fa-shopping-cart"></i></a></li>
                                                    <li>
                                                        <?php $variant_id = (count((array)$product_row['variants']) <= 1) ? $product_row['variants'][0]['id'] : ""; ?>
                                                        <a href="#" class="compare" data-tip="Compare" data-product-id="<?= $product_row['id'] ?>" data-product-variant-id="<?= $variant_id ?>">
                                                            <i class="fa fa-random"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="rating">
                                                <input type="text" class="kv-fa rating-loading" value="<?= $row['rating'] ?>" data-size="sm" title="" readonly>
                                            </div>
                                            <div class="product-content">
                                                <h3 class="title"><a href="<?= base_url('products/details/' . $row['slug']) ?>"><?= $row['name'] ?></a></h3>
                                                <div class="price"><i></i><?php $price = get_price_range_of_product($row['id']);
                                                                            echo $price['range'];
                                                                            ?></span>
                                                </div>
                                                <a class="add-to-cart add_to_cart" href="" data-product-id="<?= $row['id'] ?>" data-product-variant-id="<?= $variant_id ?>" data-izimodal-open="<?= $modal ?>">+ <?= !empty($this->lang->line('add_to_cart')) ? $this->lang->line('add_to_cart') : 'Add To Cart' ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } else { ?>
                                <div class="col-12 m-5">
                                    <div class="text-center">
                                        <h1 class="h2"><?= !empty($this->lang->line('no_favorite_products_found')) ? $this->lang->line('no_favorite_products_found') : 'No Favorite Products Found' ?>.</h1>
                                        <a href="<?= base_url('products') ?>" class="button button-rounded button-warning"><?= !empty($this->lang->line('go_to_shop')) ? $this->lang->line('go_to_shop') : 'Go to Shop' ?></a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>