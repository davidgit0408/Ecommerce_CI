<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add Product FAQs</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Add Product FAQs</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start  -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/product_faqs/add_faqs'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="attributes" class="col-sm-2 col-form-label">Select Product <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">

                                        <select name="product_id" class="search_admin_product w-100" data-placeholder=" Type to search and select products">
                                            <?php
                                            foreach ($product_details as $row) {  ?>
                                                <option value="<?= $row['id'] ?>" selected><?= $row['name'] ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="question" class="col-sm-2 col-form-label">Question<span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="question" placeholder="question" name="question">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="answer" class="col-sm-2 col-form-label">Answer<span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="answer" placeholder="answer" name="answer">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn">Add Product FAQs</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>