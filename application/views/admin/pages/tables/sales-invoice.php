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
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
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

                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/Invoice/get_sales_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="sales_invoice_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable='true'>Order ID</th>
                                        <th data-field="name" data-sortable='true'>User Name</th>
                                        <th data-field="mobile" data-sortable='true'>Mobile</th>
                                        <th data-field="address" data-sortable='true'>Address</th>
                                        <th data-field="final_total" data-sortable='true'>Final Total(₹)</th>
                                        <th data-field="date_added" data-sortable='true'>Order Date</th>
                                        <th data-field="operate" data-sortable='true'>Operate</th>
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