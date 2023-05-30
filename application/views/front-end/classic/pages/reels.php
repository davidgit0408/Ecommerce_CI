<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= isset($page_main_bread_crumb) ? $page_main_bread_crumb : 'Reels Listing' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <?php if (isset($right_breadcrumb) && !empty($right_breadcrumb)) {
                    foreach ($right_breadcrumb as $row) {
                ?>
                        <li class="breadcrumb-item"><?= $row ?></li>
                <?php }
                } ?>
                <li class="breadcrumb-item active" aria-current="page"><?= !empty($this->lang->line('reels')) ? $this->lang->line('reels') : 'Reels' ?></li>
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
                            <div class="dropdown float-md-right d-flex mb-4">
                                <label class="mr-2 dropdown-label"> <?= !empty($this->lang->line('show')) ? $this->lang->line('show') : 'Show' ?>:</label>
                                <a class="btn dropdown-border btn-lg dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= ($this->input->get('per-page', true) ? $this->input->get('per-page', true) : '12') ?> <span class="caret"></span></a>
                                <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="navbarDropdown" id="per_page_sellers">
                                    <a class="dropdown-item" href="#" data-value=6>6</a>
                                    <a class="dropdown-item" href="#" data-value=12>12</a>
                                    <a class="dropdown-item" href="#" data-value=16>16</a>
                                    <a class="dropdown-item" href="#" data-value=20>20</a>
                                    <a class="dropdown-item" href="#" data-value=24>24</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row w-100">
                        <?php foreach ($reels['data'] as $row) { ?>
                            <div class="col-md-6">
                                <div class="product-grid">
                                    <div class="product-image">
                                        <div>
                                            <video class="pic-1" controls width="320" height="240" src="<?= base_url() . $row['sub_directory'] . '/' . $row['name'] ?>">
                                                <?= !empty($this->lang->line('no_video_tag_support')) ? $this->lang->line('no_video_tag_support') : 'Your browser does not support the video tag.' ?>
                                            </video>
                                        </div>
                                        <span class="product-new-label">new</span>
                                        <aside class="add-favorite">
                                            <button type="button" class="btn far fa-heart add-to-fav-reel-btn <?= ($row['is_favorite'] == 1) ? 'fa text-danger' : '' ?>" data-reel-id="<?= $row['id'] ?>"></button>
                                        </aside>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="title" style="font-size: 1.5rem; font-weight: 700;"><?= $row['title'] ?></h3>
                                        <div class="price">
                                            <a class="add-to-cart view-products" href="<?= base_url('download/download_reel/') . $row['id'] ?>">Download Reel</a>
                                            <i class="menu-icon fas fa-star text-danger"></i>
                                            <?php echo $row['favorites_count'];?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <nav class="text-center mt-4">
                        <?= (isset($links)) ? $links : '' ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>