<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Products FAQs</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Product FAQs</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div id="product_faq_value_id" class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Product FAQs</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body p-0">
                                <form class="form-horizontal form-submit-event" id="product_edit_faq_form" action="<?= base_url('admin/product/edit_product_faqs'); ?>" method="POST" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <?php
                                        if (isset($fetched_data[0]['id'])) { ?>
                                            <input type="hidden" name="edit_product_faq" value="<?= @$fetched_data[0]['id'] ?>">
                                        <?php  } ?>
                                        <div class="form-group row">
                                            <label for="question" class="col-sm-2 col-form-label">Question </label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="question" placeholder="question" name="question" value="<?= @$fetched_data[0]['question'] ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="answer" class="col-sm-2 col-form-label">Answer <span class='text-danger text-sm'>*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="answer" placeholder="Answer" name="answer" value="<?= @$fetched_data[0]['answer'] ?>">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <button type="reset" class="btn btn-warning">Reset</button>
                                            <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Product Faq' : 'Add Product FAQ' ?></button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="form-group" id="error_box">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="<?= base_url() . 'admin/product_faqs/create_product_faqs' ?>" class="btn btn-block btn-outline-primary btn-sm">Add Product FAQs</a>
                            </div>
                        </div>
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' id='products_faqs_table' data-toggle="table" data-url="<?= base_url('admin/product_faqs/get_faqs_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{
                            "fileName": "products-list",
                            "ignoreColumn": ["state"] 
                            }' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true" data-align='center'>ID</th>
                                        <th data-field="user_id" data-sortable="false" data-visible='false' data-align='center'>User Id</th>
                                        <th data-field="product_id" data-sortable="false" data-visible='false' data-align='center'>Product Id</th>
                                        <th data-field="question" data-sortable="false" data-align='center'>Question</th>
                                        <th data-field="answer" data-sortable="false">Answer</th>
                                        <th data-field="answered_by" data-sortable="false" data-visible='false' data-align='center'>Answered by</th>
                                        <th data-field="answered_by_name" data-sortable="false" data-align='center'>Answered by Name</th>
                                        <th data-field="username" data-width='500' data-sortable="false" class="col-md-6" data-align='center'>Username</th>
                                        <th data-field="date_added" data-sortable="false" data-align='center'>Date added</th>
                                        <th data-field="operate" data-sortable="false" data-align='center'>Operate</th>
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