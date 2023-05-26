<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>View Customers</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Customers</li>
                    </ol>
                </div>
            </div>
            <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id='customer-address-modal'>
                <div class="modal-dialog modal-xl">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-header">
                            <h5 class="modal-title">View Address Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="row">
                                <div class="col-md-12 main-content">
                                    <div class="card content-area p-4">
                                        <div class="card-innr">
                                            <div class="gaps-1-5x"></div>
                                            <table class='table-striped' id='customer-address-table' data-toggle="table" data-url="<?= base_url('admin/customer/get_address') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="queryParams">
                                                <thead>
                                                    <tr>
                                                        <th data-field="id" data-sortable="true" data-align='center'>Id</th>
                                                        <th data-field="name" data-sortable="false" data-align='center'>User Name</th>
                                                        <th data-field="type" data-sortable="false" data-align='center'>Type</th>
                                                        <th data-field="mobile" data-sortable="false" data-align='center'>mobile</th>
                                                        <th data-field="alternate_mobile" data-sortable="false" data-align='center'>Alternate mobile</th>
                                                        <th data-field="address" data-sortable="false" data-visible="false" data-align='center'>Address</th>
                                                        <th data-field="landmark" data-sortable="false" data-align='center'>Landmark</th>
                                                        <th data-field="area" data-sortable="false" data-align='center'>Area</th>
                                                        <th data-field="city" data-sortable="false" data-align='center'>City</th>
                                                        <th data-field="state" data-sortable="false" data-align='center'>State</th>
                                                        <th data-field="pincode" data-sortable="false" data-align='center'>Pincode</th>
                                                        <th data-field="country" data-sortable="false" data-align='center'>Country</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div><!-- .card-innr -->
                                    </div><!-- .card -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-innr">
                            <div class="gaps-1-5x row d-flex adjust-items-center">
                            </div>
                            <table class='table-striped' id="customer_table" data-toggle="table" data-url="<?= base_url('admin/customer/view_customer') ?>" data-side-pagination="server" data-click-to-select="true" data-pagination="true" data-id-field="id" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="#toolbar" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="name" data-sortable="false">Name</th>
                                        <th data-field="email" data-sortable="true">Email</th>
                                        <th data-field="mobile" data-sortable="true">Mobile No</th>
                                        <th data-field="balance" data-sortable="true">Balance</th>
                                        <th data-field="street" data-sortable="true">Street</th>
                                        <th data-field="area" data-sortable="true">Area</th>
                                        <th data-field="city" data-sortable="true">City</th>
                                        <th data-field="qrcode" data-sortable="true">QR</th>
                                        <th data-field="by_seller_name" data-sortable="true">by_seller_name</th>
                                        <th data-field="date" data-sortable="true">Date</th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="actions" data-sortable="true">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                            <img class="tooltip-image" src="path/to/image.png" style="display: none">
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <img src="path/to/tooltip-image.png" alt="Tooltip">
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<style>
    .qrcode {
        position: relative;
    }

    .qrcode img {
        position: absolute;
        top: -50px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 100px;
        opacity: 1;
        transition: opacity 0.3s ease-in-out;
    }

    .qrcode:hover img {
        opacity: 1;
    }
</style>
<script>
    $(document).on('click', '.qrcode', function() {
        let value = $(this).attr('data');
        var qrcode = new QRCode($(this), {
            text: value,
            width: 128,
            height: 128,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });

    $(document).on('change', '#customer_table', function() {
        console.log('aaa')
        // let value = $(this).attr('data');
        // var qrcode = new QRCode($(this), {
        //     text: value,
        //     width: 128,
        //     height: 128,
        //     colorDark : "#000000",
        //     colorLight : "#ffffff",
        //     correctLevel : QRCode.CorrectLevel.H
        // });
    });

        // $(".qrcode").hover(
        //     function() {
        //         console.log($(this))
        //         $("img[alt='Tooltip']").attr('src', 'new-image-url.jpg');
        //     },
        //     function() {
        //     }
        // );
</script>