<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Products Stock</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Product Stock</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div id="product_faq_value_id" class="modal fade edit-modal-lg " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-m ">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Manage Stock</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body ">
                                <form class="form-horizontal form-submit-event" id="stock_adjustment_form" action="<?= base_url('admin/manage_stock/update_stock'); ?>" method="POST" enctype="multipart/form-data">

                                    <div class="card-body">
                                        <?php if (isset($fetched_data['product'][0]['id'])) { ?>
                                            <input type="hidden" name="variant_id" value="<?= $this->input->get('edit_id') ?>">
                                        <?php  } ?>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="product_name">Product </label>
                                                    <input type="text" class="form-control" id="product_name" placeholder="Product name" name="product_name" value="<?= (isset($attribute[0]['value']) && !empty($attribute[0]['value']) && $fetched_data['product'][0]['stock_type'] != 1) ? $fetched_data['product'][0]['name']  . ' - ' . ' ' . $attribute[0]['value'] : $fetched_data['product'][0]['name']  ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="current_stock"><?= labels('current_stock', 'Current Stock') ?></label>

                                                    <input type="text" class="form-control current_stock" name="current_stock" id="current_stock" value="<?= (isset($fetched_data['product'][0]['stock']) && !empty($fetched_data['product'][0]['stock'])) ? $fetched_data['product'][0]['stock'] : $fetched ?>" readonly>


                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="quantity"><?= labels('quantity', 'Quantity') ?></label><span class="asterisk text-danger">*</span>
                                                    <input type="number" class="form-control" name="quantity" id="quantity" min=1>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="type"><?= labels('type', 'Type') ?></label>
                                                    <select class="form-control" id="type" name="type">
                                                        <option value='add'><?= labels('add', 'Add') ?></option>
                                                        <option value='subtract'><?= labels('subtract', 'Subtract') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-success" value="Save"><?= labels('update_stock', 'Update Stock') ?></button>
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
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="zipcode" class="col-form-label">Filter By Seller</label>
                                <select class='form-control' name='seller_id' id="seller_filter">
                                    <option value="">Select Seller </option>
                                    <?php foreach ($sellers as $seller) { ?>
                                        <option value="<?= $seller['seller_id'] ?>" <?= (isset($product_details[0]['seller_id']) && $product_details[0]['seller_id'] == $seller['seller_id']) ? 'selected' : "" ?>><?= $seller['seller_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="category_parent" class="col-form-label">Filter By Product Category</label>
                                <select id="category_parent" name="category_parent">
                                    <option value=""><?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                    </option>
                                    <?php
                                    echo get_categories_option_html($categories);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' id='products_table' data-toggle="table" data-url="<?= isset($_GET['flag']) ? base_url('admin/manage_stock/get_stock_list?flag=') . $_GET['flag'] : base_url('admin/manage_stock/get_stock_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{"fileName": "products-list","ignoreColumn": ["state"] }' data-query-params="stock_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true" data-align='center'>Variant ID</th>
                                        <th data-field="name" data-sortable="false" data-align='center'>Name</th>
                                        <th data-field="seller_name" data-sortable="false" data-visible="false">Seller Name</th>
                                        <th data-field="category_name" data-sortable="false" data-visible="false">Category</th>
                                        <th data-field="image" data-sortable="false" data-align='center'>Image</th>
                                        <th data-field="operate" data-sortable="true" data-align='center'>Variants - Stock</th>
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