<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Category</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Category</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade edit-modal-lg" id="category_form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="card content-area p-4">
                        <div class="col-md-12">
                            <div class="btn-group float-right" role="group">
                                <button type="button" class="btn btn-primary " autofocus="autofocus" id='list_view'><i class="fas fa-list"></i> List View</button>
                                <button type="button" class="btn btn-primary" id='tree_view'><i class="fas fa-stream"></i> Tree View</button>
                            </div>
                        </div>

                        <div class="card-innr" id="list_view_html">
                            <div class="card-head">
                                <h4 class="card-title">Category</h4>
                            </div>
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' id='category_table' data-toggle="table" data-url="<?= base_url('seller/category/category_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{
                        "fileName": "categories-list",
                        "ignoreColumn": ["state"] 
                        }' data-query-params="category_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true" data-visible='false' data-align='center'>ID</th>
                                        <th data-field="name" data-sortable="false" data-align='center'>Name</th>
                                        <th data-field="image" data-sortable="true" data-align='center'>Image</th>
                                        <th data-field="banner" data-sortable="true" data-align='center'>Banner</th>
                                        <th data-field="status" data-sortable="true" data-align='center'>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                        <div id="tree_view_html">
                        </div>
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>