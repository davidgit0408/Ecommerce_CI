<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= isset($page_main_bread_crumb) ? $page_main_bread_crumb : 'Read Blog' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <?php if (isset($right_breadcrumb) && !empty($right_breadcrumb)) {
                    foreach ($right_breadcrumb as $row) {
                ?>
                        <li class="breadcrumb-item"><?= $row ?></li>
                <?php }
                } ?>
                <li class="breadcrumb-item active" aria-current="page"><?= !empty($this->lang->line('view-blog')) ? $this->lang->line('view-blog') : 'View-blog' ?></li>
            </ol>
        </nav>
    </div>
</section>
<!-- end breadcrumb -->

<section class="listing-page content main-content">
    <div class="product-listing card-solid py-4">
        <div class="container-fluid pt-3 pb-3">
            <div class="row w-100">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="blog-card">
                        <div class="mr-3 pt-3 pb-3">
                            <h3 class="text-primary float-right"><?= date('d M,Y', strtotime($blog[0]['date_added'])) ?></h3>
                        </div>
                        <div class="blog-card-img">
                            <a href="<?= base_url('blogs/') ?>">
                                <img src="<?= base_url() . $blog[0]['image'] ?>" alt="Card image">
                            </a>
                        </div>
                        <div class="card-body">
                            <h2 class="view-blog-title mb-2 mt-2"><?= $blog[0]['title'] ?></h2>
                            <p class="card-text mt-5"><?= str_replace('\r\n', '&#13;&#10;', $blog[0]['description']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>