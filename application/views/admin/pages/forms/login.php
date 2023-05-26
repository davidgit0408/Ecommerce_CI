<?php if (ALLOW_MODIFICATION == 0) { ?>
    <div class="alert alert-warning">
        Note: If you cannot login here, please close the codecanyon frame by clicking on x Remove Frame button from top right corner on the page or <a href="<?= base_url('/admin') ?>" target="_blank" class="text-danger">>> Click here << </a>
    </div>
<?php } ?>
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card container-fluid ">
        <div class="card-body login-card-body">
            <div class="login-logo">
                <a href="<?= base_url() . 'admin/login' ?>"><img src="<?= get_image_url($logo, 'thumb', 'sm'); ?>"></a>
            </div>
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="<?= base_url('auth/login') ?>" class='form-submit-event' method="post">
                <input type='hidden' name='<?= $this->security->get_csrf_token_name() ?>' value='<?= $this->security->get_csrf_hash() ?>'>
                <div class="input-group mb-3">
                    <input type="<?= $identity_column ?>" class="form-control" name="identity" placeholder="<?= ucfirst($identity_column)  ?>" value="">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas <?= ($identity_column == 'email') ? 'fa-envelope' : 'fa-mobile' ?> "></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" value="">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-12 mb-8 mb-3 mt-2 ml-4 text-end">
                        <a href="<?= base_url('/admin/login/forgot_password') ?>" ><?= !empty($this->lang->line('forgot_password')) ? $this->lang->line('forgot_password') : 'Forgot Password' ?> ?</a>
                    </div>
                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" id="submit_btn" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <div class="mt-2 col-md-12 text-center">
                        <div class="form-group" id="error_box">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<script>
    $(document).on('submit', '.form-submit-event', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var form_id = $(this).attr("id");
        var error_box = $('#error_box', this);
        var submit_btn = $(this).find('.submit_btn');
        var btn_html = $(this).find('.submit_btn').html();
        var btn_val = $(this).find('.submit_btn').val();
        var button_text = (btn_html != '' || btn_html != 'undefined') ? btn_html : btn_val;


        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            beforeSend: function () {
                submit_btn.html('Please Wait..');
                submit_btn.attr('disabled', true);
            },
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == true) {
                    error_box.addClass("rounded p-3 alert alert-danger").removeClass('d-none alert-success');
                    error_box.show().delay(5000).fadeOut();
                    error_box.html(result['message']);
                    submit_btn.html(button_text);
                    submit_btn.attr('disabled', false);
                } else {
                    error_box.addClass("rounded p-3 alert alert-success").removeClass('d-none alert-danger');
                    error_box.show().delay(3000).fadeOut();
                    error_box.html(result['message']);
                    submit_btn.html(button_text);
                    submit_btn.attr('disabled', false);
                    $('.form-submit-event')[0].reset();
                    if (form_id == 'login_form') {
                        cart_sync();
                    }
                    setTimeout(function () { location.reload(); }, 600);

                }
            }
        });
    });

</script>
<!-- /.login-box -->