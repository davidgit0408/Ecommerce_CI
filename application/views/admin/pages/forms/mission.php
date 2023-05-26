<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add Mission</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Add Mission</li>
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
                        <form class="form-horizontal form-submit-event" action="#" url="<?= base_url('admin/delegate/add_mission'); ?>" method="POST" id="add_product_form">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="zipcode" class="col-form-label">Filter By Delegate</label>
                                        <select class='form-control' name='delegate_id' id="delegate_id">
                                            <option value="">Select Delegate </option>
                                            <?php foreach ($delegates as $delegate) { ?>
                                                <option value="<?= $delegate['delegate_id'] ?>"><?= $delegate['delegate_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="zipcode" class="col-form-label">Filter By Customer</label>
                                        <select class='form-control' name='customer_id' id="customer_id">
                                            <option value="">Select Delegate </option>
                                            <?php foreach ($customers as $customer) { ?>
                                                <option value="<?= $customer['customer_id'] ?>"><?= $customer['customer_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="zipcode" class="col-form-label">Visiting Day</label>
                                        <input type="datetime" name="visiting_day" id="visiting_day" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn">Add Mission</button>
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
                    setTimeout(function () { location.reload(); }, 600);
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