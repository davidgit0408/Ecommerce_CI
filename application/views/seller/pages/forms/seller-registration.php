<div>
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('seller/auth/create-seller'); ?>" method="POST" id="add_product_form">
                            <?php if (isset($user_data) && !empty($user_data)) { ?>
                                <input type="hidden" name="user_id" value="<?= $user_data['to_be_seller_id'] ?>">
                                <input type='hidden' name='user_name' value='<?= $user_data['to_be_seller_name'] ?>'>
                                <input type='hidden' name='user_mobile' value='<?= $user_data['to_be_seller_mobile'] ?>'>
                            <?php
                            } ?>
                            <div class="card-body">
                                <div class="login-logo">
                                    <a href="<?= base_url() . 'seller/login' ?>"><img src="<?= base_url() . $logo ?>"></a>
                                </div>
                                <h4 class="mb-4">Seller Registration</h4>
                                <h5>Personal Details</h5>
                                <hr>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" placeholder="Seller Name" name="name" <?= (isset($user_data) && !empty($user_data) && !empty($user_data['to_be_seller_id'])) ? 'disabled' : ''; ?> value="<?= @$user_data['to_be_seller_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile" class="col-sm-2 col-form-label">Mobile <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="mobile" placeholder="Enter Mobile" name="mobile" <?= (isset($user_data) && !empty($user_data) && !empty($user_data['to_be_seller_id'])) ? 'disabled' : ''; ?> value="<?= @$user_data['to_be_seller_mobile'] ?>">
                                    </div>
                                </div>

                                <?php
                                if (!isset($user_data) && empty($user_data)) {
                                ?>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Email <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email">
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="password" class="col-sm-2 col-form-label">Password <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="password" placeholder="Enter Passsword" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="confirm_password" placeholder="Enter Confirm Password" name="confirm_password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="address" class="col-sm-2 col-form-label">Address <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control" id="address" placeholder="Enter Address" name="address"></textarea>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group row">
                                    <label for="address_proof" class="col-sm-2 col-form-label">Address Proof <span class='text-danger text-sm'>*</span> </label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" name="address_proof" id="address_proof" accept="image/*" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="national_identity_card" class="col-sm-2 col-form-label">National Identity Card <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" name="national_identity_card" id="national_identity_card" accept="image/*" />
                                    </div>
                                </div>

                                <h5>Store Details</h5>
                                <hr>
                                <div class="form-group row">
                                    <label for="store_name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="store_name" placeholder="Store Name" name="store_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="logo" class="col-sm-2 col-form-label">Logo <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" name="store_logo" id="store_logo" accept="image/*" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="store_url" class="col-sm-2 col-form-label">URL </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="store_url" placeholder="Store URL" name="store_url">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="store_description" class="col-sm-3 col-form-label">Description </label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="store_description" placeholder="Store Description" name="store_description"></textarea>
                                    </div>
                                </div>

                                <h5>Store Tax Details</h5>
                                <hr>
                                <div class="form-group row">
                                    <label for="tax_name" class="col-sm-2 col-form-label">Tax Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="tax_name" placeholder="GST" name="tax_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tax_number" class="col-sm-2 col-form-label">Tax Number <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="tax_number" placeholder="GSTIN1234" name="tax_number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pan_number" class="col-sm-2 col-form-label">PAN Number</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="pan_number" placeholder="Personal or Store's PAN Number" name="pan_number">
                                    </div>
                                </div>

                                <h5>Bank Details</h5>
                                <hr>
                                <div class="form-group row">
                                    <label for="account_number" class="col-sm-2 col-form-label">Account Number </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="account_number" placeholder="Account Number" name="account_number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="account_name" class="col-sm-2 col-form-label">Account Name </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="account_name" placeholder="Account Name" name="account_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bank_code" class="col-sm-2 col-form-label">Bank Code </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="bank_code" placeholder="Bank Code" name="bank_code">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bank_name" class="col-sm-2 col-form-label">Bank Name </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="bank_name" placeholder="Bank Name" name="bank_name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn">Submit</button>
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