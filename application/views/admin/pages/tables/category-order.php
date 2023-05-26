<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Categories Order</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Categories Orders</li>
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
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12 offset-md-3">
                                        <label for="subcategory_id" class="col-form-label">Category List</label>
                                        <div class="row font-weight-bold">
                                            <div class="col-2">No.</div>
                                            <div class="col-4">Row Order</div>
                                            <div class="col-3">Name</div>
                                            <div class="col-3">Image</div>
                                        </div>
                                        <ul class="list-group bg-grey move order-container" id="sortable">
                                            <?php
                                            $i = 0;
                                            if (!empty($categories)) {
                                                foreach ($categories as $row) {
                                            ?>
                                                    <li class="list-group-item d-flex bg-gray-light align-items-center h-25" id="category_id-<?= $row['id'] ?>">
                                                        <div class="col-2"><span> <?= $i ?> </span></div>
                                                        <div class="col-4"><span> <?= $row['row_order'] ?> </span></div>
                                                        <div class="col-3"><span><?= $row['name'] ?></span></div>
                                                        <div class="col-3">
                                                            <img src="<?= $row['image'] ?>" class="image-box-100">
                                                        </div>
                                                    </li>
                                                <?php
                                                    $i++;
                                                }
                                            } else {
                                                ?>
                                                <li class="list-group-item text-center h-25"> No Categories Exist </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                        <button type="button" class="btn btn-block btn-success btn-lg mt-3" id="save_category_order">Save</button>
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