<section class="main-content mt-md-5 mt-sm-0 mb-5">
    <div class="category-section container-fluid text-center dark-category-section icon-dark-sec">
        <div class='my-4 featured-section-title'>
            <div class='col-md-12'>
                <h3 class='section-title text-white'><?= !empty($this->lang->line('category')) ? $this->lang->line('category') : 'Browse Categories' ?></h3>
            </div>
            <hr>
        </div>
        <div class="row">
            <?php foreach ($categories as $key => $row) { ?>
                <div class="category-grid col-md-2 col-sm-6">
                    <div class="category-image justify-content-center d-flex w-50">
                        <div class="category-image-container">
                            <a href="<?= base_url('products/category/' . html_escape($row['slug'])) ?>">
                                <img src="<?= $row['image'] ?>">
                            </a>
                            <div class="cat-font-color">
                                <span><?= html_escape($row['name']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>