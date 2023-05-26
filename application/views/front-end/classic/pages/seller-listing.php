<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= isset($page_main_bread_crumb) ? $page_main_bread_crumb : 'Product Listing' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <?php if (isset($right_breadcrumb) && !empty($right_breadcrumb)) {
                    foreach ($right_breadcrumb as $row) {
                ?>
                        <li class="breadcrumb-item"><?= $row ?></li>
                <?php }
                } ?>
                <li class="breadcrumb-item active" aria-current="page"><?= !empty($this->lang->line('sellers')) ? $this->lang->line('sellers') : 'Sellers' ?></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<section class="listing-page content main-content">
    <div class="product-listing card-solid py-4">
        <div class="row mx-0">
            <!-- Dektop Sidebar -->
            <!-- remved filters -->
            <div class="col-md-12 order-md-2">
                <div class="container-fluid filter-section pt-3 pb-3">
                    <div class="col-12 pl-0">
                        <div class="dropdown">
                            <div class="filter-bars">
                                <div class="menu js-menu">
                                    <span class="menu__line"></span>
                                    <span class="menu__line"></span>
                                    <span class="menu__line"></span>

                                </div>
                            </div>
                            <?php if (isset($sellers) && !empty($sellers)) { ?>
                                <div class="dropdown float-md-right d-flex mb-4">
                                    <label class="mr-2 dropdown-label"> <?= !empty($this->lang->line('show')) ? $this->lang->line('show') : 'Show' ?>:</label>
                                    <a class="btn dropdown-border btn-lg dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= ($this->input->get('per-page', true) ? $this->input->get('per-page', true) : '12') ?> <span class="caret"></span></a>
                                    <a href="#" id="product_grid_view_btn" class="grid-view"><i class="fas fa-th"></i></a>
                                    <a href="#" id="product_list_view_btn" class="grid-view"><i class="fas fa-th-list"></i></a>
                                    <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="navbarDropdown" id="per_page_sellers">
                                        <a class="dropdown-item" href="#" data-value=12>12</a>
                                        <a class="dropdown-item" href="#" data-value=16>16</a>
                                        <a class="dropdown-item" href="#" data-value=20>20</a>
                                        <a class="dropdown-item" href="#" data-value=24>24</a>
                                    </div>
                                </div>
                                <div class="ele-wrapper d-flex ">
                                    <div class="form-group col-md-4 d-flex pl-0">
                                        <label for="product_sort_by"></label>
                                        <select id="product_sort_by" class="form-control">
                                            <option><?= !empty($this->lang->line('relevance')) ? $this->lang->line('relevance') : 'Relevance' ?></option>
                                            <option value="top-rated" <?= ($this->input->get('sort') == "top-rated") ? 'selected' : '' ?>><?= !empty($this->lang->line('top_rated')) ? $this->lang->line('top_rated') : 'Top Rated' ?></option>
                                            <option value="date-desc" <?= ($this->input->get('sort') == "date-desc") ? 'selected' : '' ?>><?= !empty($this->lang->line('newest_first')) ? $this->lang->line('newest_first') : 'Newest First' ?></option>
                                            <option value="date-asc" <?= ($this->input->get('sort') == "date-asc") ? 'selected' : '' ?>><?= !empty($this->lang->line('oldest_first')) ? $this->lang->line('oldest_first') : 'Oldest First' ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-5 d-flex">
                                        <label for="seller_search"></label>
                                        <input type="search" name="seller_search" class="form-control" id="seller_search" value="<?= (isset($seller_search) && !empty($seller_search)) ? $seller_search : "" ?>" placeholder="Search Seller">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if (isset($sellers) && !empty($sellers)) { ?>

                        <?php if (isset($_GET['type']) && $_GET['type'] == "list") { ?>
                            <div class="col-md-12 col-sm-6">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h1 class="h4"><?= !empty($this->lang->line('sellers')) ? $this->lang->line('sellers') : 'Sellers' ?></h4>
                                    </div>
                                    <?php foreach ($sellers as $row) { ?>
                                        <div class="col-md-3">
                                            <div class="product-grid">
                                                <div class="product-image">
                                                    <div class="product-image-container">
                                                        <a href="#">
                                                            <img class="pic-1 lazy" data-src="<?= $row['seller_profile'] ?>">
                                                            <?php $row['seller_profile']; ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="product-content">
                                                <h3 class="list-product-title title"><a href="#"><?= $row['seller_name'] ?></a></h3>
                                                <div class="rating">
                                                    <input type="text" class="kv-fa rating-loading" value="<?= number_format($row['seller_rating'], 1) ?>" data-size="sm" title="" readonly>
                                                </div>
                                                <p class="text-muted list-product-desc"><?= $row['store_description'] ?></p>
                                                <div class="price mb-2 list-view-price">
                                                    <?= $row['store_name'] ?>
                                                </div>
                                                <div class="button button-sm m-0 p-0">
                                                    <a class="add-to-cart view-products" href="<?= base_url('products?seller=' . $row['slug']) ?>">View Products</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                        <?php } else { ?>
                            <div class="row w-100">
                                <div class="col-12">
                                    <h1 class="h4"><?= !empty($this->lang->line('sellers')) ? $this->lang->line('sellers') : 'Sellers' ?></h4>
                                </div>
                                <?php foreach ($sellers as $row) { ?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="product-grid">
                                            <div class="product-image">
                                                <div class="product-image-container">
                                                    <a href="<?= base_url('sellers/') ?>">
                                                        <img class="pic-1 lazy" data-src="<?= $row['seller_profile'] ?>">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="rating">
                                                <input type="text" class="kv-fa rating-loading" value="<?= number_format($row['seller_rating'], 1) ?>" data-size="sm" title="" readonly>
                                            </div>
                                            <div class="product-content">
                                                <h3 class="title"><a href="<?= base_url('sellers/') ?>"><?= $row['seller_name'] ?></a></h3>
                                                <div class="price mb-2">
                                                    <?= $row['store_name'] ?>
                                                </div>
                                                <a class="add-to-cart view-products" href="<?= base_url('products?seller=' . $row['slug']) ?>"><?= !empty($this->lang->line('view_products')) ? $this->lang->line('view_products') : 'view_products' ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <?php if (!isset($sellers) || empty($sellers)) { ?>
                        <div class="col-12 text-center">
                            <h1 class="h2"><?= !empty($this->lang->line('no_sellers_found')) ? $this->lang->line('no_sellers_found') : 'No Sellers Found.' ?></h1>
                            <a href="<?= base_url('products') ?>" class="button button-rounded button-warning"><?= !empty($this->lang->line('go_to_shop')) ? $this->lang->line('go_to_shop') : 'Go to Shop' ?></a>
                        </div>
                    <?php } ?>
                    <nav class="text-center mt-4">
                        <?= (isset($links)) ? $links : '' ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>