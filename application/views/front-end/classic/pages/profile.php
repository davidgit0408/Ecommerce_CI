<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></a></li>
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
        <div class="row mb-5">
            <div class="col-md-4">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-8 col-12">
                <form class="form-submit-event" method="POST" action="<?= base_url('login/update_user') ?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="username" class="col-sm-12 col-form-label"><?= !empty($this->lang->line('username')) ? $this->lang->line('username') : 'Username' ?>*</label>
                            <input type="text" class="form-control" id="username" placeholder="Type Username here" name="username" value="<?= $users->username ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="mobile" class="col-sm-12 col-form-label"><?= !empty($this->lang->line('mobile')) ? $this->lang->line('mobile') : 'Mobile' ?>*</label>
                            <div>
                                <input type="phone" class="form-control" id="mobile" placeholder="Type Mobile No. here" name="mobile" value="<?= $users->mobile ?>">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="email" class="col-sm-12 col-form-label"><?= !empty($this->lang->line('email')) ? $this->lang->line('email') : 'Email' ?>*</label>
                            <input type="text" class="form-control" id="email" placeholder="Type Email here" name="email" value="<?= $users->email ?>">
                        </div>


                    </div>
                    <div class="form-group">
                        <label for="old" class="col-sm-12 col-form-label"><small><?= !empty($this->lang->line('old_password')) ? $this->lang->line('old_password') : 'Old Password' ?></small></label>
                        <input type="password" class="form-control" id="old" placeholder="Type Old Password here" name="old">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="new" class="col-sm-12 col-form-label"><?= !empty($this->lang->line('new_password')) ? $this->lang->line('new_password') : 'New Password' ?></label>
                            <input type="password" class="form-control" id="new" placeholder="Type New Password here" name="new">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="new_confirm" class="col-sm-12 col-form-label"><?= !empty($this->lang->line('confirm_new_password')) ? $this->lang->line('confirm_new_password') : 'Confirm New Password' ?></label>
                            <input type="password" class="form-control" id="new_confirm" placeholder="Type Confirm Password here" name="new_confirm">
                        </div>
                    </div>
                    <button type="submit" class="button button-success btn-5 submit_btn"><?= !empty($this->lang->line('save')) ? $this->lang->line('save') : 'Save' ?></button>
                    <div class="d-flex justify-content-center mt-3">
                        <div class="form-group" id="error_box">
                        </div>
                    </div>
                </form>
                <!--end profile -->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>