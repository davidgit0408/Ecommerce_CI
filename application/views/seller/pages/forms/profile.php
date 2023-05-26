<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Seller Profile</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Seller</li>
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
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('seller/login/update_user'); ?>" method="POST" id="add_product_form">
                            <?php if (isset($fetched_data[0]['id'])) { ?>
                                <input type="hidden" name="edit_seller" value="<?= $fetched_data[0]['user_id'] ?>">
                                <input type="hidden" name="edit_seller_data_id" value="<?= $fetched_data[0]['id'] ?>">
                                <input type="hidden" name="old_address_proof" value="<?= $fetched_data[0]['address_proof'] ?>">
                                <input type="hidden" name="old_store_logo" value="<?= $fetched_data[0]['logo'] ?>">
                                <input type="hidden" name="old_national_identity_card" value="<?= $fetched_data[0]['national_identity_card'] ?>">
                            <?php
                            } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" placeholder="Seller Name" name="name" value="<?= @$fetched_data[0]['username'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile" class="col-sm-2 col-form-label">Mobile <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="mobile" placeholder="Enter Mobile" name="mobile" value="<?= @$fetched_data[0]['mobile'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" value="<?= @$fetched_data[0]['email'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="old" class="col-sm-2 col-form-label">Old Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="old" placeholder="Type Password here" name="old">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="new" class="col-sm-2 col-form-label">New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="new" placeholder="Type Password here" name="new">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="new_confirm" class="col-sm-2 col-form-label">Confirm New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="new_confirm" placeholder="Type Confirm Password here" name="new_confirm">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="address" class="col-sm-2 col-form-label">Address <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="address" placeholder="Enter Address" name="address"><?= isset($fetched_data[0]['address']) ? @$fetched_data[0]['address'] : ""; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="address_proof" class="col-sm-2 col-form-label">Address Proof <span class='text-danger text-sm'>*</span> </label>
                                    <div class="col-sm-10">
                                        <?php if (isset($fetched_data[0]['address_proof']) && !empty($fetched_data[0]['address_proof'])) { ?>
                                            <span class="text-danger">*Leave blank if there is no change</span>
                                        <?php } ?>
                                        <input type="file" class="form-control" name="address_proof" id="address_proof" accept="image/*" />
                                    </div>
                                </div>
                                <?php if (isset($fetched_data[0]['address_proof']) && !empty($fetched_data[0]['address_proof'])) { ?>
                                    <div class="form-group row">
                                        <div class="mx-auto product-image"><a href="<?= base_url($fetched_data[0]['address_proof']); ?>" data-toggle="lightbox" data-gallery="gallery_seller"><img src="<?= base_url($fetched_data[0]['address_proof']); ?>" class="img-fluid rounded"></a></div>
                                    </div>
                                <?php } ?>

                                <h4>Store Details</h4>
                                <hr>
                                <div class="form-group row">
                                    <label for="store_name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="store_name" placeholder="Store Name" name="store_name" value="<?= @$fetched_data[0]['store_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="store_url" class="col-sm-2 col-form-label">URL <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="store_url" placeholder="Store URL" name="store_url" value="<?= @$fetched_data[0]['store_url'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="store_description" class="col-sm-2 col-form-label">Description <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="store_description" placeholder="Store Description" name="store_description"><?= isset($fetched_data[0]['store_description']) ? @$fetched_data[0]['store_description'] : ""; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="logo" class="col-sm-2 col-form-label">Logo <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <?php if (isset($fetched_data[0]['logo']) && !empty($fetched_data[0]['logo'])) { ?>
                                            <span class="text-danger">*Leave blank if there is no change</span>
                                        <?php } ?>
                                        <input type="file" class="form-control" name="store_logo" id="store_logo" accept="image/*" />
                                    </div>
                                </div>
                                <?php if (isset($fetched_data[0]['logo']) && !empty($fetched_data[0]['logo'])) { ?>
                                    <div class="form-group row">
                                        <div class="mx-auto product-image"><a href="<?= base_url($fetched_data[0]['logo']); ?>" data-toggle="lightbox" data-gallery="gallery_seller"><img src="<?= base_url($fetched_data[0]['logo']); ?>" class="img-fluid rounded"></a></div>
                                    </div>
                                <?php } ?>
                                <h4>Bank Details</h4>
                                <hr>
                                <div class="form-group row">
                                    <label for="account_number" class="col-sm-2 col-form-label">Account Number <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="account_number" placeholder="Account Number" name="account_number" value="<?= @$fetched_data[0]['account_number'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="account_name" class="col-sm-2 col-form-label">Account Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="account_name" placeholder="Account Name" name="account_name" value="<?= @$fetched_data[0]['account_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bank_code" class="col-sm-2 col-form-label">Bank Code <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="bank_code" placeholder="Bank Code" name="bank_code" value="<?= @$fetched_data[0]['bank_code'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bank_name" class="col-sm-2 col-form-label">Bank Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="bank_name" placeholder="Bank Name" name="bank_name" value="<?= @$fetched_data[0]['bank_name'] ?>">
                                    </div>
                                </div>
                                <h4>Other Details</h4>
                                <hr>
                                <div class="form-group row">
                                    <label for="latitude" class="col-sm-2 col-form-label">Latitude <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="latitude" placeholder="Latitude" name="latitude" value="<?= @$fetched_data[0]['latitude'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="longitude" class="col-sm-2 col-form-label">Longitude <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="longitude" placeholder="Longitude" name="longitude" value="<?= @$fetched_data[0]['longitude'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax_name" class="col-sm-2 col-form-label">Tax Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="tax_name" placeholder="Tax Name" name="tax_name" value="<?= @$fetched_data[0]['tax_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax_number" class="col-sm-2 col-form-label">Tax Number <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="tax_number" placeholder="Tax Number" name="tax_number" value="<?= @$fetched_data[0]['tax_number'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pan_number" class="col-sm-2 col-form-label">Pan Number <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="pan_number" placeholder="Pan Number" name="pan_number" value="<?= @$fetched_data[0]['pan_number'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Status <span class='text-danger text-sm'>*</span></label>
                                    <div id="status" class="btn-group col-sm-4">
                                        <label class="btn btn-default" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0" <?= (isset($fetched_data[0]['status']) && $fetched_data[0]['status'] == '0') ? 'Checked' : '' ?>> Deactive
                                        </label>
                                        <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1" <?= (isset($fetched_data[0]['status']) && $fetched_data[0]['status'] == '1') ? 'Checked' : '' ?>> Active
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="national_identity_card" class="col-sm-2 col-form-label">National Identity Card <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <?php if (isset($fetched_data[0]['national_identity_card']) && !empty($fetched_data[0]['national_identity_card'])) { ?>
                                            <span class="text-danger">*Leave blank if there is no change</span>
                                        <?php } ?>
                                        <input type="file" class="form-control" name="national_identity_card" id="national_identity_card" accept="image/*" />
                                    </div>
                                </div>
                                <?php if (isset($fetched_data[0]['national_identity_card']) && !empty($fetched_data[0]['national_identity_card'])) { ?>
                                    <div class="form-group row">
                                        <div class="mx-auto product-image"><a href="<?= base_url($fetched_data[0]['national_identity_card']); ?>" data-toggle="lightbox" data-gallery="gallery_seller"><img src="<?= base_url($fetched_data[0]['national_identity_card']); ?>" class="img-fluid rounded"></a></div>
                                    </div>
                                <?php } ?>


                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn">Update Profile</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                    <div class="card text-white d-none mb-3">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-footer -->
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