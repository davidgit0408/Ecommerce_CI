<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add Delegate</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Add Delegate</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="#" url="<?= base_url('admin/delegate/add_delegate'); ?>" method="POST" id="add_product_form">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="username" placeholder="User Name" name="username" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile" class="col-sm-2 col-form-label">Mobile <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="mobile" placeholder="Enter Mobile" name="mobile" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" value="">
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="password" class="col-sm-2 col-form-label">Password <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" placeholder="Enter Passsword" name="password" value="">
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="confirm_password" placeholder="Enter Confirm Password" name="confirm_password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="address" class="col-sm-2 col-form-label">Address <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address" placeholder="Enter Address" name="address" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn">Add Delegate</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                    <div class="card text-white d-none mb-3">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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
            url: $(this).attr('url'),
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