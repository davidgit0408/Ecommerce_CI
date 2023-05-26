<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>View Sale Reports</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Sales Reports</li>
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
                                <div class="row col-md-12">
                                    <div class="form-group col-md-4">
                                        <label>From & To Date</label>
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
                                    <div class="form-group col-md-4 d-flex align-items-center pt-4">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="status_date_wise_search()">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped" data-detail-view="true" data-detail-formatter="salesReport" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url('seller/Sales_report/get_seller_sales_report_list') ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-trim-on-search="false" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-mobile-responsive="true" data-sort-name="id" data-sort-order="DESC" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="sales_report_query_params" data-export-types='["txt","excel"]'> 
                            <thead>
                                <tr>
                               
                                    <th data-field="id" data-sortable='true'> <?= labels('id', 'Order item ID') ?></th>
                                    <th data-field="product_name" data-sortable='true'><?= labels('product_name', 'Product name') ?></th>
                                    <th data-field="final_total" data-sortable='true'><?= labels('final_total', 'Final Total') ?></th>
                                    <th data-field="payment_method" data-sortable='true'><?= labels('payment_method', 'Payment Method') ?></th>
                                    <th data-field="store_name" data-sortable='true'><?= labels('store_name', 'Store Name') ?></th>
                                    <th data-field="seller_name" data-sortable='true'><?= labels('seller_name', 'Sales Representative') ?></th>
                                    <th data-field="date_added" data-sortable='true'><?= labels('date_added', 'Order Date') ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div><!-- .card-innr -->
                </div><!-- .card -->
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>