<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></a></li>
            </ol>
        </nav>
    </div>
</section>
<section id="content" class="pt-5 pb-5 ">
    <div class="main-content">
        <div class="row">
            <div class="col-md-7">
                <div class="sign-up-image">
                    <?php if (isset($web_settings['map_iframe']) && !empty($web_settings['map_iframe'])) {
                        echo html_entity_decode(stripcslashes($web_settings['map_iframe']));
                    } ?>
                </div>
            </div>
            <div class="col-md-5 login-form">
                <h2 class="form-text-style"><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?></h2>
                <form id="contact-us-form" action="<?= base_url('home/send-contact-us-email') ?>" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4"><?= !empty($this->lang->line('username')) ? $this->lang->line('username') : 'Username' ?></label>
                            <input type="text" class="form-control" id="inputEmail4" name="username" placeholder="Your Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4"><?= !empty($this->lang->line('email')) ? $this->lang->line('email') : 'Email' ?></label>
                            <input type="email" class="form-control" id="inputPassword4" name="email" placeholder="Your Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress"><?= !empty($this->lang->line('subject')) ? $this->lang->line('subject') : 'Subject' ?></label>
                        <input type="text" class="form-control" id="inputAddress" name="subject" placeholder="Subject">
                    </div>
                    <div class="form-group">
                        <label for="inputAddress"><?= !empty($this->lang->line('message')) ? $this->lang->line('message') : 'Message' ?></label>
                        <textarea class="form-control" name="message" rows="4" cols="58"></textarea>
                    </div>
                    <button id="contact-us-submit-btn" class="block btn-5"><?= !empty($this->lang->line('send_message')) ? $this->lang->line('send_message') : 'Send Message' ?></button>
                </form>
            </div>
        </div>
        <div class="row col-mb-50 mt-5">
            <?php if (isset($web_settings['address']) && !empty($web_settings['address'])) { ?>
                <div class="col-sm-6 col-md-4">
                    <div class="info-wrapper">
                        <div class="info-icon">
                            <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                        </div>
                        <div class="info-content">
                            <h3><?= !empty($this->lang->line('find_us')) ? $this->lang->line('find_us') : 'Find us' ?></h3>
                            <span class="subtitle"><?= output_escaping(str_replace('\r\n', '</br>', $web_settings['address'])) ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($web_settings['support_number']) && !empty($web_settings['support_number'])) { ?>
                <div class="col-sm-6 col-md-4">
                    <div class="info-wrapper">
                        <div class="info-icon">
                            <a href="#"><i class="fas fa-phone-alt"></i></a>
                        </div>
                        <div class="info-content">
                            <h3><?= !empty($this->lang->line('contact_us')) ? $this->lang->line('contact_us') : 'Contact Us' ?>
                                <span class="subtitle"><?= $web_settings['support_number'] ?></span>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($web_settings['support_email']) && !empty($web_settings['support_email'])) { ?>
                <div class="col-sm-6 col-md-4">
                    <div class="info-wrapper">
                        <div class="info-icon">
                            <a href="#"><i class="far fa-envelope-open"></i></a>
                        </div>
                        <div class="info-content">
                            <h3><?= !empty($this->lang->line('email_us')) ? $this->lang->line('email_us') : 'Email Us' ?>
                                <span class="subtitle"><?= $web_settings['support_email'] ?></span>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>