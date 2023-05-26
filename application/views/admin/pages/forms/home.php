<?php
$settings = get_settings('system_settings', true);
$currency = (isset($settings['currency']) && !empty($settings['currency'])) ? $settings['currency'] : '';
?>
<style>
    .tab-content > .fade {
        display: none !important;
    }
    .tab-content > .active {
        display: block !important;
    }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row gy-4 mb-4">
        <!-- Sales Overview-->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-2">Overview</h4>
                        <div class="dropdown">
                            <button
                                    class="btn p-0"
                                    type="button"
                                    id="salesOverview"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesOverview">
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <small class="me-2">New Signups</small>
                        <div class="d-flex align-items-center text-success">
                            <p class="mb-0">+<?= $user_counter ?></p>
                            <i class="mdi mdi-chevron-up"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body row">
                    <div class="d-flex gap-3 col-4">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
                                <i class="mdi mdi-cart mdi-24px"></i>
                            </div>
                        </div>
                        <div class="card-info">
                            <h4 class="mb-0"><?= $order_counter ?></h4>
                            <small class="text-muted">Orders</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 col-4">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
                                <i class="mdi mdi-account-group mdi-24px"></i>
                            </div>
                        </div>
                        <div class="card-info">
                            <h4 class="mb-0"><?= $delivery_boy_counter ?></h4>
                            <small class="text-muted">Delivery Boys</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 col-4">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-info rounded">
                                <i class="mdi mdi-folder mdi-24px"></i>
                            </div>
                        </div>
                        <div class="card-info">
                            <h4 class="mb-0"><?= $product_counter ?></h4>
                            <small class="text-muted">Products</small>
                        </div>
                    </div>
                </div>
                <div class="card-body row pt-0">
                    <div class="d-flex gap-3 col-4">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-danger rounded">
                                <i class="mdi mdi mdi-cash-100 mdi-24px"></i>
                            </div>
                        </div>
                        <div class="card-info">
                            <h4 class="mb-0"><?= $total_earnings ?>(<?= $currency ?>)</h4>
                            <small class="text-muted">Total Earnings </small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 col-4">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-slack rounded">
                                <i class="mdi mdi mdi-cash-100 mdi-24px"></i>
                            </div>
                        </div>
                        <div class="card-info">
                            <h4 class="mb-0"><?= $admin_earnings ?>(<?= $currency ?>)</h4>
                            <small class="text-muted">Admin Earnings  </small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 col-4">
                        <div class="avatar">
                            <div class="avatar-initial bg-label-whatsapp rounded">
                                <i class="mdi mdi mdi-cash-100 mdi-24px"></i>
                            </div>
                        </div>
                        <div class="card-info">
                            <h4 class="mb-0"><?= $seller_earnings ?>(<?= $currency ?>)</h4>
                            <small class="text-muted">Seller Earnings </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Sales Overview-->

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-2">Infos</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3 p-2">
                        <div class="card-info">
                            <h4 class="mb-0 ml-2"><?= $count_products_availability_status ?> Product(s) sold out!</h4>
                            <a
                                class="text-success"
                                href="<?= base_url('admin/product/?flag=sold') ?>"  class="text-decoration-none small-box-footer">
                                More info
                                <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex gap-3 p-2">
                        <div class="card-info">
                            <h4 class="mb-0 ml-2">
                                <?= $count_products_low_status ?> Product(s) low in stock!
                            </h4>
                            <div class="text-muted"> (Low stock limit <?= isset($settings['low_stock_limit']) ? $settings['low_stock_limit'] : '5' ?>)</div>
                            <a class="text-success"
                               href="<?= base_url('admin/product/?flag=low') ?>" class="text-decoration-none small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="h-100 text-end d-flex align-items-end justify-content-end">
                        <div class="card-body pb-0 pt-3 position-absolute bottom-0">
                            <img
                                    src="<?= base_url('assets/admin/img/illustrations/account-settings-security-illustration.png') ?>"
                                    alt="Ratings"
                                    width="140" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row gy-4 mb-4">
        <div class="col-lg-8 col-12 mb-4" id="ecommerceChartView">
            <div class="card card-shadow chart-height">
                <div class="card-header flex-nowrap header-elements">
                    <h5 class="card-title mb-0">Product Sales</h5>
                    <div class="card-header-elements ms-auto py-0 d-none d-sm-block">
                        <ul class="nav nav-pills nav-pills-rounded chart-action float-right btn-group sales-tab" role="group">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" data-target="#scoreLineToDay1">Day</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" data-target="#scoreLineToWeek1">Week</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" data-target="#scoreLineToMonth1">Month</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body tab-content p-3 w-100" id="nav-tabContent">
                        <canvas id="scoreLineToDay1" class="chartjs mb-4 tab-pane fade active show"  role="tabpanel" data-height="350"></canvas>
                        <canvas id="scoreLineToWeek1" class="chartjs mb-4 tab-pane fade"  role="tabpanel" data-height="350"></canvas>
                        <canvas id="scoreLineToMonth1" class="chartjs mb-4 tab-pane fade"  role="tabpanel" data-height="350"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-4">
            <div class="card">
                <h5 class="card-header">Category Wise Product's Count</h5>
                <div class="card-body">
                    <canvas id="doughnutChart" class="chartjs mb-4" data-height="350"></canvas>
                    <ul id="doughnutChart_content" class="doughnut-legend d-flex justify-content-around ps-0 mb-2 pt-1">
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-bold py-3 mb-4">Sellers Details</h4>

    <div class="row gy-4 mb-4">
        <div class="col-lg-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="row">
                    <div class="col-6">
                        <div class="card-body">
                            <div class="card-info mb-3 py-2 mb-lg-1 mb-xl-3">
                                <h5 class="mb-3 mb-lg-2 mb-xl-3 text-nowrap">Approved sellers</h5>
                                <div class="badge bg-label-primary rounded-pill lh-xs">Approved</div>
                            </div>
                            <div class="d-flex align-items-end flex-wrap gap-1">
                                <h4 class="mb-0 me-2"><?= (isset($count_approved_sellers) && !empty($count_approved_sellers)) ?  $count_approved_sellers : 0; ?></h4>
                                <!--                                    <small class="text-success">+15.6%</small>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-6 text-end d-flex align-items-end justify-content-center">
                        <div class="card-body pb-0 pt-3 position-absolute bottom-0">
                            <img
                                    src="<?= base_url('assets/admin/img/illustrations/trophy.png') ?>"
                                    alt="Ratings"
                                    width="95" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="row">
                    <div class="col-6">
                        <div class="card-body">
                            <div class="card-info mb-3 py-2 mb-lg-1 mb-xl-3">
                                <h5 class="mb-3 mb-lg-2 mb-xl-3 text-nowrap">Not Approved Sellers</h5>
                                <div class="badge bg-label-secondary rounded-pill lh-xs">Not Approved</div>
                            </div>
                            <div class="d-flex align-items-end flex-wrap gap-1">
                                <h4 class="mb-0 me-2"><?= (isset($count_not_approved_sellers) && !empty($count_not_approved_sellers)) ?  $count_not_approved_sellers : 0; ?></h4>
                                <!--                                    <small class="text-success">+15.6%</small>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-6 text-end d-flex align-items-end justify-content-center">
                        <div class="card-body pb-0 pt-3 position-absolute bottom-0">
                            <img
                                    src="<?= base_url('assets/admin/img/illustrations/card-session-illustration.png') ?>"
                                    alt="Ratings"
                                    width="95" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="row">
                    <div class="col-6">
                        <div class="card-body">
                            <div class="card-info mb-3 py-2 mb-lg-1 mb-xl-3">
                                <h5 class="mb-3 mb-lg-2 mb-xl-3 text-nowrap">Deactiveted sellers</h5>
                                <div class="badge bg-label-danger rounded-pill lh-xs">Deactiveted</div>
                            </div>
                            <div class="d-flex align-items-end flex-wrap gap-1">
                                <h4 class="mb-0 me-2"><?= (isset($count_deactive_sellers) && !empty($count_deactive_sellers)) ?  $count_deactive_sellers : 0; ?></h4>
                                <!--                                    <small class="text-success">+15.6%</small>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-6 text-end d-flex align-items-end justify-content-center">
                        <div class="card-body pb-0 pt-3 position-absolute bottom-0">
                            <img
                                    src="<?= base_url('assets/admin/img/illustrations/card-customers-illustration.png') ?>"
                                    alt="Ratings"
                                    width="95" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row gy-4 mb-4">
        <div class="col-lg-6 col-sm-12 mb-4">
            <!-- DataTable with Buttons -->
            <div class="card p-2">
                <h5 class="card-title mb-0 ml-3 mt-3">Top Sellers</h5>
                <div class="card-datatable table-responsive pt-0">
                    <table class='datatables-basic table table-bordered' id='top_sellers_table'
                           data-column-names="seller_id,seller_name,store_name,total"
                           data-title="Top Sellers"
                           data-toggle="table" data-url="<?= base_url('admin/sellers/top_seller') ?>" data-click-to-select="true" data-side-pagination="server" data-show-columns="true" data-show-refresh="true" data-sort-name="sd.id" data-sort-order="DESC" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                        <thead>
                        <tr>
                            <th data-field="seller_id" data-sortable="true">ID</th>
                            <th data-field="seller_name" data-sortable="true">Seller name</th>
                            <th data-field="store_name" data-sortable="false">Store name</th>
                            <th data-field="total" data-sortable="false">Total</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 mb-4">
            <!-- DataTable with Buttons -->
            <div class="card p-2">
                <h5 class="card-title mb-0 ml-3 mt-3">Top Categories</h5>
                <div class="card-datatable table-responsive pt-0">
                    <table class='datatables-basic table table-bordered' id='top_categories_table'
                           data-column-names="seller_id,seller_name,store_name,total"
                           data-title="Top Categories"
                           data-toggle="table" data-url="<?= base_url('admin/sellers/top_seller') ?>" data-click-to-select="true" data-side-pagination="server" data-show-columns="true" data-show-refresh="true" data-sort-name="sd.id" data-sort-order="DESC" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                        <thead>
                        <tr>
                            <th data-field="seller_id" data-sortable="true">ID</th>
                            <th data-field="seller_name" data-sortable="true">Seller name</th>
                            <th data-field="store_name" data-sortable="false">Store name</th>
                            <th data-field="total" data-sortable="false">Total</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <h4 class="fw-bold py-3 mb-4">Order Outlines</h4>
    <div class="row gy-4 mb-4">
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="d-flex">
                    <div class="avatar h-100 w-px-75">
                        <div class="avatar-initial bg-label-primary rounded">
                            <i class="mdi mdi-cart mdi-24px"></i>
                        </div>
                    </div>
                    <div class="card-body" style="width:calc(100% - 75px)">
                        <h1 class="mb-2"><?= $status_counts['awaiting'] ?></h1>
                        <h4 class="text-muted mt-2">Awaiting</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="d-flex">
                    <div class="avatar h-100 w-px-75">
                        <div class="avatar-initial bg-label-whatsapp rounded">
                            <i class="mdi mdi-call-received mdi-24px"></i>
                        </div>
                    </div>
                    <div class="card-body" style="width:calc(100% - 75px)">
                        <h1 class="mb-2"><?= $status_counts['received'] ?></h1>
                        <h4 class="text-muted mt-2">Received</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="d-flex">
                    <div class="avatar h-100 w-px-75">
                        <div class="avatar-initial bg-label-pinterest rounded">
                            <i class="mdi mdi-chat-processing mdi-24px"></i>
                        </div>
                    </div>
                    <div class="card-body" style="width:calc(100% - 75px)">
                        <h1 class="mb-2"><?= $status_counts['processed'] ?></h1>
                        <h4 class="text-muted mt-2">Processed</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="d-flex">
                    <div class="avatar h-100 w-px-75">
                        <div class="avatar-initial bg-label-success rounded">
                            <i class="mdi mdi-shield mdi-24px"></i>
                        </div>
                    </div>
                    <div class="card-body" style="width:calc(100% - 75px)">
                        <h1 class="mb-2"><?= $status_counts['processed'] ?></h1>
                        <h4 class="text-muted mt-2">Shipped</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="d-flex">
                    <div class="avatar h-100 w-px-75">
                        <div class="avatar-initial bg-label-vimeo rounded">
                            <i class="mdi mdi-truck-delivery mdi-24px"></i>
                        </div>
                    </div>
                    <div class="card-body" style="width:calc(100% - 75px)">
                        <h1 class="mb-2"><?= $status_counts['delivered'] ?></h1>
                        <h4 class="text-muted mt-2">Delivered</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="d-flex">
                    <div class="avatar h-100 w-px-75">
                        <div class="avatar-initial bg-label-warning rounded">
                            <i class="mdi mdi-cancel mdi-24px"></i>
                        </div>
                    </div>
                    <div class="card-body" style="width:calc(100% - 75px)">
                        <h1 class="mb-2"><?= $status_counts['cancelled'] ?></h1>
                        <h4 class="text-muted mt-2">Cancelled</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="d-flex">
                    <div class="avatar h-100 w-px-75">
                        <div class="avatar-initial bg-label-twitter rounded">
                            <i class="mdi mdi-phone-return mdi-24px"></i>
                        </div>
                    </div>
                    <div class="card-body" style="width:calc(100% - 75px)">
                        <h1 class="mb-2"><?= $status_counts['returned'] ?></h1>
                        <h4 class="text-muted mt-2">Returned</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gy-4 mb-4 main-content">
        <div class="card content-area p-4">
            <div class="card-innr">
                <div class="gaps-1-5x row d-flex adjust-items-center">
                    <div class="row col-md-12">
                        <div class="form-group col-md-4">
                            <label>Date and time range:</label>
                            <div class="input-group col-md-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="text" class="form-control float-right" id="datepicker">
                                <input type="hidden" id="start_date" class="form-control float-right">
                                <input type="hidden" id="end_date" class="form-control float-right">
                            </div>
                            <!-- /.input group -->
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
                                    <option value="Flutterwave">Flutterwave</option>`
                                    <option value="Paytm">Paytm</option>
                                    <option value="Stripe">Stripe</option>
                                    <option value="bank_transfer">Direct Bank Transfers</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center pt-4">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="status_date_wise_search()">Filter</button>
                        </div>
                    </div>
                </div>
                <table class='datatables-basic table table-bordered'
                       data-column-names="qty,name,mobile,items,total,delivery_charge,wallet_balance,promo_code,promo_discount,final_total,deliver_by,payment_method,address,delivery_date,delivery_time,notes,date_added,operate"
                       id="bottom_table"
                       data-toggle="table" data-url="<?= base_url('admin/orders/view_orders') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{
                        "fileName": "order-list",
                        "ignoreColumn": ["state"]
                        }' data-query-params="home_query_params">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable='true' data-footer-formatter="totalFormatter">Order ID</th>
                        <th data-field="user_id" data-sortable='true' data-visible="false">User ID</th>
                        <th data-field="sellers" data-sortable='true'>Sellers</th>
                        <th data-field="qty" data-sortable='true' data-visible="false">Qty</th>
                        <th data-field="name" data-sortable='true'>User Name</th>
                        <th data-field="mobile" data-sortable='true' data-visible="false">Mobile</th>
                        <th data-field="items" data-sortable='true' data-visible="false">Items</th>
                        <th data-field="total" data-sortable='true' data-visible="true">Total(<?= $curreny ?>)</th>
                        <th data-field="delivery_charge" data-sortable='true' data-footer-formatter="delivery_chargeFormatter" data-visible="true">D.Charge</th>
                        <th data-field="wallet_balance" data-sortable='true' data-visible="true">Wallet Used(<?= $curreny ?>)</th>
                        <th data-field="promo_code" data-sortable='true' data-visible="false">Promo Code</th>
                        <th data-field="promo_discount" data-sortable='true' data-visible="true">Promo disc.(<?= $curreny ?>)</th>
                        <!-- <th data-field="discount" data-sortable='true' data-visible="false">Discount <?= $curreny ?>(%)</th> -->
                        <th data-field="final_total" data-sortable='true'>Final Total(<?= $curreny ?>)</th>
                        <th data-field="deliver_by" data-sortable='true' data-visible='false'>Deliver By</th>
                        <th data-field="payment_method" data-sortable='true' data-visible="true">Payment Method</th>
                        <th data-field="address" data-sortable='true'>Address</th>
                        <th data-field="delivery_date" data-sortable='true' data-visible='false'>Delivery Date</th>
                        <th data-field="delivery_time" data-sortable='true' data-visible='false'>Delivery Time</th>
                        <th data-field="notes" data-sortable='false' data-visible='false'>O. Notes</th>
                        <th data-field="date_added" data-sortable='true'>Order Date</th>
                        <th data-field="operate">Action</th>
                    </tr>
                    </thead>
                </table>
            </div><!-- .card-innr -->
        </div><!-- .card -->
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
</div>
<!-- Modal -->
<div class="modal fade" id="deactive_sellers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-3 p-md-5 ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Deactivate Sellers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class='table-striped' id='seller_table' data-toggle="table" data-url="<?= base_url('admin/sellers/deactive_sellers') ?>" data-click-to-select="true" data-side-pagination="" data-pagination="true" data-page-list="[1,2,3,4]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="sd.id" data-sort-order="DESC" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="false">Name</th>
                            <th data-field="mobile" data-sortable="true">Mobile No</th>
                            <th data-field="date" data-sortable="true">Date</th>
                            <th data-field="operate">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>
<div class="modal col-12 fade" id="approved_sellers" tabindex="-1" role="dialog" aria-labelledby="approved_sellers" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Approved seller</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class='table-striped' id='seller_table' data-toggle="table" data-url="<?= base_url('admin/sellers/approved_sellers') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5,10,15,20,25]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="sd.id" data-sort-order="DESC" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="false">Name</th>
                            <th data-field="mobile" data-sortable="true">Mobile No</th>
                            <th data-field="date" data-sortable="true">Date</th>
                            <th data-field="operate">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>
<div class="modal col-12 fade" id="not_approved_sellers" tabindex="-1" role="dialog" aria-labelledby="approved_sellers" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-3 p-md-5 modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Not Approved Sellers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class='table-striped' id='seller_table' data-toggle="table" data-url="<?= base_url('admin/sellers/not_approved_sellers') ?>" data-click-to-select="true" data-side-pagination="client" data-pagination="true" data-page-list="[1,3,5,7,10]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="sd.id" data-sort-order="DESC" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="false">Name</th>
                            <th data-field="mobile" data-sortable="true">Mobile No</th>
                            <th data-field="date" data-sortable="true">Date</th>
                            <th data-field="operate">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>
