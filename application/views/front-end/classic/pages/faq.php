<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2>FAQ'S</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('faq')) ? $this->lang->line('faq') : 'FAQ' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<section class="home_faq_sec mt-5" id="faq_sec">
    <div class="main-content">
        <div class="row">
            <div class="home_faq col-md-7">
                <h2 class="h6"><span class="span-color"><?= !empty($this->lang->line('faq')) ? $this->lang->line('faq') : 'FAQ' ?></span></h2>
                <div class="accordion mt-5 pl-0" id="accordionExample">
                    <?php foreach ($faq['data'] as $row) { ?>
                        <div class="card">
                            <div class="card-header" id="<?= "h-" . $row['id'] ?>">
                                <h2 class="clearfix mb-0">
                                    <a class="home_faq_btn pl-0 collapsed faq-btn-text" data-toggle="collapse" data-target="#<?= "c-" . $row['id'] ?>" aria-expanded="true" aria-controls="collapseone">
                                        <?= html_escape($row['question']) ?>
                                        <i class="fa fa-angle-down rotate"></i></a>
                                </h2>
                            </div>
                            <div id="<?= "c-" . $row['id'] ?>" class="collapse " aria-labelledby="<?= "h-" . $row['id'] ?>" data-parent="#accordionExample">
                                <div class="card-body"><?= html_escape($row['answer']) ?></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-5">
                <div class="faq_image">
                    <img src="<?= THEME_ASSETS_URL . 'demo/faq1.png' ?>" alt="faq">
                </div>
            </div>
        </div>
    </div>
</section>