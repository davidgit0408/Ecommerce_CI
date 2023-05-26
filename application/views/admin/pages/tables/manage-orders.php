<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Orders</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <!-- modal for show digital order mails -->

                <div id="digital-order-mails" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Digital Order Mails</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body ">
                                <input type="hidden" name="order_id" id="order_id">
                                <input type="hidden" name="order_item_id" id="order_item_id">
                                <table class='table-striped' id="digital_order_mail_table" data-toggle="table" data-url="<?= base_url('admin/orders/get-digital-order-mails') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="digital_order_mails_query_params">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable="true">ID</th>
                                            <th data-field="order_id" data-sortable="true">Order ID</th>
                                            <th data-field="order_item_id" data-sortable="false">Order Item ID</th>
                                            <th data-field="subject" data-sortable="false">Subject</th>
                                            <th data-field="message" data-sortable="false" data-visible="false">Message</th>
                                            <th data-field="file_url" data-sortable="false">URL</th>
                                            <th data-field="date_added" data-sortable="false" data-visible="false">Date</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal for send mail for digital orders -->

                <div id="product_faq_value_id" class="modal fade edit-modal-lg " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg ">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Manage Digital Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body ">
                                <form class="form-horizontal form-submit-event" action="<?= base_url('admin/orders/send_digital_product'); ?>" method="POST" enctype="multipart/form-data">

                                    <div class="card-body">
                                     
                                        <input type="hidden" name="order_id" value="<?= $order_item_data[0]['order_id'] ?>">
                                        <input type="hidden" name="order_item_id" value="<?= $this->input->get('edit_id') ?>">
                                        <input type="hidden" name="username" value="<?= $user_data['username']  ?>">
                                        <div class="row form-group">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="product_name">Customer Email-ID </label>
                                                    <input type="text" class="form-control" id="email" name="email" value="<?= $fetched[0]['email'] ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="product_name">Subject </label>
                                                    <input type="text" class="form-control" id="subject" placeholder="Enter Subject for email" name="subject" value="">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="product_name">Message </label>
                                                    <textarea class="textarea addr_editor" placeholder="Message for Email" name="message"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-12 mt-2" id="digital_media_container">
                                                <label for="image" class="ml-2">File <span class='text-danger text-sm'>*</span></label>
                                                <div class='col-md-6'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='pro_input_file' data-isremovable='1' data-media_type='archive,document' data-is-multiple-uploads-allowed='0' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                                <div class="container-fluid row image-upload-section">
                                                    <div class="col-md-6 col-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success mt-3" id="submit_btn" value="Save"><?= labels('send_mail', 'Send Mail') ?></button>
                                    </div>
                                </form>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal for assign tracking data for order -->
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="transaction_modal" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="user_name">Order Tracking</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-info">
                                            <!-- form start -->
                                            <form class="form-horizontal " id="order_tracking_form" action="<?= base_url('admin/orders/update-order-tracking/'); ?>" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="order_id" id="order_id">
                                                <input type="hidden" name="order_item_id" id="order_item_id">
                                                <div class="card-body pad">
                                                    <div class="form-group ">
                                                        <label for="courier_agency">Courier Agency</label>
                                                        <input type="text" class="form-control" name="courier_agency" id="courier_agency" placeholder="Courier Agency" />
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="tracking_id">Tracking Id</label>
                                                        <input type="text" class="form-control" name="tracking_id" id="tracking_id" placeholder="Tracking Id" />
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="url">URL</label>
                                                        <input type="text" class="form-control" name="url" id="url" placeholder="URL" />
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="reset" class="btn btn-warning">Reset</button>
                                                        <button type="submit" class="btn btn-success" id="submit_btn">Save</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <div class="form-group" id="error_box">
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </form>
                                        </div>
                                        <!--/.card-->
                                    </div>
                                    <!--/.col-md-12-->
                                </div>
                                <!-- /.row -->
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="order-tracking-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title">View Order Tracking</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="tab-pane " role="tabpanel" aria-labelledby="product-rating-tab">
                                    <input type="hidden" name="order_id" id="order_id">
                                    <table class='table-striped' id="order_tracking_table" data-toggle="table" data-url="<?= base_url('admin/orders/get-order-tracking') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="order_tracking_query_params">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable="true">ID</th>
                                                <th data-field="order_id" data-sortable="true">Order ID</th>
                                                <th data-field="order_item_id" data-sortable="false">Order Item ID</th>
                                                <th data-field="courier_agency" data-sortable="false">courier_agency</th>
                                                <th data-field="tracking_id" data-sortable="false">tracking_id</th>
                                                <th data-field="url" data-sortable="false">URL</th>
                                                <th data-field="date" data-sortable="false">Date</th>
                                                <th data-field="operate" data-sortable="true">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-innr">
                            <div class="gaps-1-5x row d-flex adjust-items-center">
                                <h5 class="col">Order Outlines</h5>
                                <div class="row col-12 d-flex">
                                    <div class="col-md-3">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3><?= $status_counts['awaiting'] ?></h3>
                                                <p>Awaiting</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-xs fa-history"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3><?= $status_counts['received'] ?></h3>
                                                <p>Received</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-xs fa-level-down-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3><?= $status_counts['processed'] ?></h3>
                                                <p>Processed</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-xs fa-people-carry"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3><?= $status_counts['shipped'] ?></h3>
                                                <p>Shipped</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-xs fa-shipping-fast"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3><?= $status_counts['delivered'] ?></h3>
                                                <p>Delivered</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-xs fa-user-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3><?= $status_counts['cancelled'] ?></h3>
                                                <p>Cancelled</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-xs fa-times-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3><?= $status_counts['returned'] ?></h3>
                                                <p>Returned</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-xs fa-level-up-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-md-12">
                                    <div class="form-group col-md-3">
                                        <label>Date and time range:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                            </div>
                                            <input type="text" class="form-control float-right" id="datepicker">
                                            <input type="hidden" id="start_date" class="form-control float-right">
                                            <input type="hidden" id="end_date" class="form-control float-right">
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div>
                                            <label>Filter Order Items By status</label>
                                            <select id="order_status" name="order_status" placeholder="Select Status" required="" class="form-control">
                                                <option value="">All Orders</option>
                                                <option value="awaiting">Awaiting</option>
                                                <option value="received">Received</option>
                                                <option value="processed">Processed</option>
                                                <option value="shipped">Shipped</option>
                                                <option value="delivered">Delivered</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="returned">Returned</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Filter By payment  -->
                                    <div class="form-group col-md-3">
                                        <div>
                                            <label>Filter By Payment Method</label>
                                            <select id="payment_method" name="payment_method" placeholder="Select Payment Method" required="" class="form-control">
                                                <option value="">All Payment Methods</option>
                                                <option value="COD">Cash On Delivery</option>
                                                <option value="Paypal">Paypal</option>
                                                <option value="RazorPay">RazorPay</option>
                                                <option value="Paystack">Paystack</option>
                                                <option value="Flutterwave">Flutterwave</option>
                                                <option value="Paytm">Paytm</option>
                                                <option value="Stripe">Stripe</option>
                                                <option value="bank_transfer">Direct Bank Transfers</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div>
                                            <label>Filter By Order Based on Product Type</label>
                                            <select id="order_type" name="order_type" placeholder="Select Order Type" required="" class="form-control">
                                                <option value="">All Orders</option>
                                                <option value="physical_order">Physical Orders</option>
                                                <option value="digital_order">Digital Orders</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 d-flex align-items-center pt-4">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="status_date_wise_search()">Filter</button>
                                    </div>
                                </div>
                            </div>
                            <input type='hidden' id='order_user_id' value='<?= (isset($_GET['user_id']) && !empty($_GET['user_id'])) ? $_GET['user_id'] : '' ?>'>
                            <input type='hidden' id='order_seller_id' value='<?= (isset($_GET['seller_id']) && !empty($_GET['seller_id'])) ? $_GET['seller_id'] : '' ?>'>
                            <div class="row col-md-6">
                                <div class="row col-md-4 pull-right">
                                    <a href="#" class="btn btn-primary btn-sm add_promo_code_discount" title="If you found Promo Code Discount not crediting using cron job you can update Promo Code Discount from here!">Settle Promo Code Discount</a>
                                </div>
                            </div>
                            <hr>
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" data-target="#orders_table">Orders</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" data-target="#order_items_table">Order Items</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="orders_table" class="tab-pane active show"><br>
                                    <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/orders/view_orders') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="o.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "orders-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter">Order ID</th>
                                                <th data-field="user_id" data-sortable='true' data-visible="false">User ID</th>
                                                <th data-field="qty" data-sortable='true' data-visible="false">Qty</th>
                                                <th data-field="name" data-sortable='true'>User Name</th>
                                                <th data-field="sellers" data-sortable='true'>Sellers</th>
                                                <th data-field="mobile" data-sortable='true' data-visible='false'>Mobile</th>
                                                <th data-field="notes" data-sortable='false' data-visible='true'>O. Notes</th>
                                                <th data-field="items" data-sortable='true' data-visible="false">Items</th>
                                                <th data-field="total" data-sortable='true' data-visible="true">Total(<?= $curreny ?>)</th>
                                                <th data-field="delivery_charge" data-sortable='true' data-footer-formatter="delivery_chargeFormatter">D.Charge</th>
                                                <th data-field="wallet_balance" data-sortable='true' data-visible="true">Wallet Used(<?= $curreny ?>)</th>
                                                <th data-field="promo_code" data-sortable='true' data-visible="false">Promo Code</th>
                                                <th data-field="promo_discount" data-sortable='true' data-visible="true">Promo disc.(<?= $curreny ?>)</th>
                                                <!-- <th data-field="discount" data-sortable='true' data-visible="true">Discount <?= $curreny ?>(%)</th> -->
                                                <th data-field="final_total" data-sortable='true'>Final Total(<?= $curreny ?>)</th>
                                                <th data-field="payment_method" data-sortable='true' data-visible="true">Payment Method</th>
                                                <th data-field="address" data-sortable='true' data-visible='false'>Address</th>
                                                <th data-field="delivery_date" data-sortable='true' data-visible='false'>Delivery Date</th>
                                                <th data-field="delivery_time" data-sortable='true' data-visible='false'>Delivery Time</th>
                                                <th data-field="date_added" data-sortable='true'>Order Date</th>
                                                <th data-field="operate">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="order_items_table" class="tab-pane fade"><br>
                                    <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/orders/view_order_items') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="oi.id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "order-item-list","ignoreColumn": ["state"] }' data-query-params="orders_query_params">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter">ID</th>
                                                <th data-field="order_item_id" data-sortable='true'>Order Item ID</th>
                                                <th data-field="order_id" data-sortable='true'>Order ID</th>
                                                <th data-field="user_id" data-sortable='true' data-visible="false">User ID</th>
                                                <th data-field="seller_id" data-sortable='true' data-visible="false">Seller ID</th>
                                                <th data-field="is_credited" data-sortable='true' data-visible="false">Commission</th>
                                                <th data-field="quantity" data-sortable='true' data-visible="false">Quantity</th>
                                                <th data-field="username" data-sortable='true'>User Name</th>
                                                <th data-field="seller_name" data-sortable='true'>Seller Name</th>
                                                <th data-field="product_name" data-sortable='true'>Product Name</th>
                                                <th data-field="mobile" data-sortable='true' data-visible='false'>Mobile</th>
                                                <th data-field="sub_total" data-sortable='true' data-visible="true">Total(<?= $curreny ?>)</th>
                                                <th data-field="delivery_boy" data-sortable='true' data-visible='false'>Deliver By</th>
                                                <th data-field="delivery_boy_id" data-sortable='true' data-visible='false'>Delivery Boy Id</th>
                                                <th data-field="product_variant_id" data-sortable='true' data-visible='false'>Product Variant Id</th>
                                                <th data-field="delivery_date" data-sortable='true' data-visible='false'>Delivery Date</th>
                                                <th data-field="delivery_time" data-sortable='true' data-visible='false'>Delivery Time</th>
                                                <th data-field="updated_by" data-sortable='true' data-visible="false">Updated by</th>
                                                <th data-field="status" data-sortable='true' data-visible='false'>Status</th>
                                                <th data-field="active_status" data-sortable='true' data-visible='true'>Active Status</th>
                                                <th data-field="transaction_status" data-sortable='true' data-visible='true'>Transaction Status</th>
                                                <th data-field="date_added" data-sortable='true'>Order Date</th>
                                                <th data-field="operate">Action</th>
                                                <th data-field="mail_status">Mail Status</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>