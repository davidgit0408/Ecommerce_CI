<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item active"><?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?></li>
            </ol>
        </nav>
    </div>
</section>
<section class="main-content py-5 my-4">
    <div class="text-center">
        <h1 class="h2"><?= !empty($this->lang->line('about_us')) ? $this->lang->line('about_us') : 'About Us' ?></h1>
    </div>
    <div class="text-justify">
        <div class="hrDiv">
            <p>
                <?= $about_us ?>
            </p>
        </div>
    </div>
</section>