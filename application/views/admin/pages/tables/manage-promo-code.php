<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4> Manage Promo Code</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Manage Promo Code</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title">Manage Promo Code</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="<?= base_url() . 'admin/promo-code/' ?>" class="btn btn-block btn-outline-primary btn-sm">Add Promo Code</a>
                            </div>
                        </div>
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/promo_code/view_promo_code') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "promocode-list",
                            "ignoreColumn": ["state"] 
                            }' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true" data-align='center'>ID</th>
                                        <th data-field="promo_code" data-sortable="false" data-align='center'>Promo Code</th>
                                        <th data-field="image" data-sortable="false" data-align='center'>Image</th>
                                        <th data-field="message" data-sortable="true" data-align='center'>Message</th>
                                        <th data-field="start_date" data-sortable="true" data-align='center'>Start Date</th>
                                        <th data-field="end_date" data-sortable="true" data-align='center'>End Date</th>
                                        <th data-field="no_of_users" data-sortable="true" data-visible='false' data-align='center'>No .of users</th>
                                        <th data-field="min_order_amt" data-sortable="true" data-visible='false' data-align='center'>Minimum order amount</th>
                                        <th data-field="discount" data-sortable="true" data-align='center'>Discount</th>
                                        <th data-field="discount_type" data-sortable="true" data-align='center'>Discount type</th>
                                        <th data-field="max_discount_amt" data-sortable="true" data-visible='false' data-align='center'>Max discount amount</th>
                                        <th data-field="repeat_usage" data-sortable="true" data-visible='false' data-align='center'>Repeat usage</th>
                                        <th data-field="no_of_repeat_usage" data-sortable="true" data-visible='false' data-align='center'>No of repeat usage</th>
                                        <th data-field="status" data-sortable="true" data-align='center'>Status</th>
                                        <th data-field="is_cashback" data-sortable="true" data-align='center'>Is Cashback</th>
                                        <th data-field="list_promocode" data-sortable="true" data-align='center'>View Promocode</th>
                                        <th data-field="operate" data-sortable="true" data-align='center'>Actions</th>
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