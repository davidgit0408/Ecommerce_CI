<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Products</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id='product-faqs-modal'>
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">View Products Faqs</h5>
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
                                                <table class='table-striped' id='product-faqs-table' data-toggle="table" data-url="<?= base_url('admin/product/get_faqs_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "product-faqs-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="queryParams">
                                                    <thead>
                                                        <tr>
                                                            <th data-field="id" data-sortable="true">ID</th>
                                                            <th data-field="user_id" data-sortable="false">User Id</th>
                                                            <th data-field="product_id" data-sortable="false">Product Id</th>
                                                            <th data-field="votes" data-sortable="false">Votes</th>
                                                            <th data-field="question" data-sortable="false">Question</th>
                                                            <th data-field="answer" data-sortable="false">Answer</th>
                                                            <th data-field="answered_by" data-sortable="false">Answered by</th>
                                                            <th data-field="username" data-width='500' data-sortable="false" class="col-md-6">Username</th>
                                                            <th data-field="date_added" data-sortable="false">Date added</th>
                                                            <th data-field="operate" data-sortable="false">Operate</th>
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
            </div>
            <div id="product_faq_value_id" class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
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

            <div class="modal fade" id="product-rating-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Product Rating</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="tab-pane " role="tabpanel" aria-labelledby="product-rating-tab">
                                <table class='table-striped' id="product-rating-table" data-toggle="table" data-url="<?= base_url('admin/product/get_rating_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="ratingParams">
                                    <thead>
                                        <tr>
                                            <th data-field="id" data-sortable="true">ID</th>
                                            <th data-field="username" data-width='500' data-sortable="false" class="col-md-6">Username</th>
                                            <th data-field="rating" data-sortable="false">Rating</th>
                                            <th data-field="comment" data-sortable="false">Comment</th>
                                            <th data-field="images" data-sortable="true">Images</th>
                                            <th data-field="data_added" data-sortable="false">Data added</th>
                                            <th data-field="operate" data-sortable="false">Operate</th>
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
                    <div class="card-header border-0">
                        <div class="card-tools">
                            <a href="<?= base_url() . 'admin/product/create_product' ?>" class="btn btn-block btn-outline-primary btn-sm">Add Product</a>
                        </div>
                    </div>
                    <div class="card-innr">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="zipcode" class="col-form-label">Filter By Product Category</label>
                                <select id="category_parent" name="category_parent">
                                    <option value=""><?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                    </option>
                                    <?php
                                    echo get_categories_option_html($categories);
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="zipcode" class="col-form-label">Filter By Product Status</label>
                                <select class='form-control' name='status' id="status_filter">
                                    <option value=''>Select Status</option>
                                    <option value='1'>Approved</option>
                                    <option value='2'>Not-Approved</option>
                                    <option value='0'>Deactivated</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="zipcode" class="col-form-label">Filter By Seller</label>
                                <select class='form-control' name='seller_id' id="seller_filter">
                                    <option value="">Select Seller </option>
                                    <?php foreach ($sellers as $seller) { ?>
                                        <option value="<?= $seller['seller_id'] ?>" <?= (isset($product_details[0]['seller_id']) && $product_details[0]['seller_id'] == $seller['seller_id']) ? 'selected' : "" ?>><?= $seller['seller_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="gaps-1-5x"></div>
                        <table class='table-striped' id='products_table' data-toggle="table" data-url="<?= isset($_GET['flag']) ? base_url('admin/product/get_product_data?flag=') . $_GET['flag'] : base_url('admin/product/get_product_data') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{
                            "fileName": "products-list",
                            "ignoreColumn": ["state"] 
                            }' data-query-params="product_query_params">
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true" data-visible='false' data-align='center'>ID</th>
                                    <th data-field="image" data-sortable="true" data-align='center'>Image</th>
                                    <th data-field="name" data-sortable="false" data-align='center'>Name</th>
                                    <th data-field="brand" data-sortable="false" data-align='center'>Brand</th>
                                    <th data-field="category_name" data-sortable="false" data-align='center'>Category Name</th>
                                    <th data-field="rating" data-sortable="true" data-align='center'>Rating</th>
                                    <th data-field="variations" data-sortable="true" data-visible='false' data-align='center'>Variations</th>
                                    <th data-field="operate" data-sortable="true" data-align='center'>Action</th>
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