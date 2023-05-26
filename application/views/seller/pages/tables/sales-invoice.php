<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>View Sales Invoice</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Sales Invoice</li>
                    </ol>
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
                                <div class="form-group col-md-4">
                                    <label>Date and time range:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="datepicker">
                                        <input type="hidden" id="start_date" class="form-control float-right">
                                        <input type="hidden" id="end_date" class="form-control float-right">
                                        <input type="hidden" id="seller_id" class="form-control float-right" value="<?= $_SESSION['user_id']; ?>">
                                    </div>
                                    <!-- /.input group -->
                                </div>

                                <div class="form-group col-md-4">
                                    <div class="row mt-2">
                                        <div class="col-md-4 d-flex align-items-center pt-4">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="status_date_wise_search()">Search</button>
                                        </div>
                                    </div>

                                    <!-- <div class="w-25 h-25">  -->
                                    <!-- </div> -->
                                </div>
                            </div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('seller/Invoice/get_sales_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="sales_invoice_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable='true'>Order ID</th>
                                        <th data-field="name" data-sortable='true'>User Name</th>
                                        <th data-field="total" data-sortable='true'>Total(₹)</th>
                                        <th data-field="tax_amount" data-sortable='true'>Tax Amount(₹)</th>
                                        <th data-field="discounted_price" data-sortable='true'>Discount(₹)</th>
                                        <th data-field="delivery_charge" data-sortable='true'>Delivery Charge(₹)</th>
                                        <th data-field="final_total" data-sortable='true'>Final Total(₹)</th>
                                        <th data-field="payment_method" data-sortable='true'>Payment Method</th>
                                        <th data-field="store_name" data-sortable='true'>Store Name</th>
                                        <th data-field="seller_name" data-sortable='true'>Sales Representative</th>
                                        <th data-field="date_added" data-sortable='true'>Order Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>