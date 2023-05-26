<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Products Order</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Products Orders</li>
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
                        <div class="card-header border-0">
                        </div>
                        <div class="card-innr">
                            <div class="card-head ">
                                <h4 class="card-title float-none mb-2">Filter By Product Category</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class=" col-md-4">
                                        <label for="subcategory_id" class="col-form-label">Category</label>
                                        <select name="category_parent" id="category_parent" class="form-control col-12">
                                            <option value="">--Select Category--</option>
                                            <option value="0" selected="">All</option>
                                            <?php
                                            echo get_categories_option_html($categories);

                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center pt-4">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="row_order_search" onclick="search_category_wise_products()">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>

                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                        </div>
                        <div class="card-innr">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12 offset-md-3">
                                        <label for="subcategory_id" class="col-form-label">Products List</label>
                                        <div class="row font-weight-bold">
                                            <div class="col-1">No.</div>
                                            <div class="col-3">Row Order Id</div>
                                            <div class="col-4">Product Name</div>
                                            <div class="col-4">Image</div>
                                        </div>
                                        <ul class="list-group bg-grey move order-container" id="sortable">
                                            <?php
                                            $i = 0;
                                            foreach ($product_result as $row) {
                                            ?>
                                                <li class="list-group-item d-flex bg-gray-light align-items-center h-25" id="product_id-<?= $row['id'] ?>">
                                                    <div class="col-md-1"><span> <?= $i ?> </span></div>
                                                    <div class="col-md-3"><span> <?= $row['row_order'] ?> </span></div>
                                                    <div class="col-md-4"><span><?= $row['name'] ?></span></div>
                                                    <div class="col-md-4">
                                                        <img src="<?= base_url() . $row['image'] ?>" class="image-box-100">
                                                    </div>
                                                </li>
                                            <?php
                                                $i++;
                                            }
                                            ?>
                                        </ul>
                                        <button type="button" class="btn btn-block btn-success btn-lg mt-3" id="save_product_order">Save</button>
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