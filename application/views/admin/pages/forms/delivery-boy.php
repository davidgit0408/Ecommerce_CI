<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add Delivery Boy</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Delivery Boy</li>
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
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/delivery_boys/add_delivery_boy'); ?>" method="POST" id="add_product_form">
                            <?php if (isset($fetched_data[0]['id'])) { ?>
                                <input type="hidden" name="edit_delivery_boy" value="<?= $fetched_data[0]['id'] ?>">
                            <?php
                            } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" placeholder="Deleivery Boy Name" name="name" value="<?= @$fetched_data[0]['username'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile" class="col-sm-2 col-form-label">Mobile <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="mobile" placeholder="Enter Mobile" name="mobile" value="<?= @$fetched_data[0]['mobile'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" value="<?= @$fetched_data[0]['email'] ?>">
                                    </div>
                                </div>
                                <?php
                                if (!isset($fetched_data[0]['id'])) {
                                ?>
                                    <div class="form-group row ">
                                        <label for="password" class="col-sm-2 col-form-label">Password <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="password" placeholder="Enter Passsword" name="password" value="<?= @$fetched_data[0]['password'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="confirm_password" placeholder="Enter Confirm Password" name="confirm_password">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="form-group row">
                                    <label for="address" class="col-sm-2 col-form-label">Address <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address" placeholder="Enter Address" name="address" value="<?= @$fetched_data[0]['address'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <?php
                                    $bonus_type = ['fixed_amount_per_order', 'percentage_per_order'];
                                    ?>
                                    <label for="bonus_type" class="col-sm-2 control-label">Bonus Types <span class='text-danger text-sm'> * </span></label>
                                    <div class="col-sm-10">
                                        <select name="bonus_type" class="form-control bonus_type">
                                            <option value=" ">Select Types</option>
                                            <?php foreach ($bonus_type as $row) { ?>
                                                <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) &&  $fetched_data[0]['bonus_type'] == $row) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $row)) ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                        <?php ?>
                                    </div>
                                </div>
                                <div class="form-group row fixed_amount_per_order <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['bonus_type'] == 'fixed_amount_per_order') ? '' : 'd-none' ?>">
                                <label for="bonus" class="col-sm-2 col-form-label">Amount <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="bonus_amount" placeholder="Enter amount to be given to the delivery boy on successful order delivery" name="bonus_amount" value="<?= @$fetched_data[0]['bonus'] ?>">
                                    </div>
                                </div>  
                                <div class="form-group row percentage_per_order <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['bonus_type'] == 'percentage_per_order') ? '' : 'd-none' ?>">
                                <label for="bonus" class="col-sm-2 col-form-label">Bonus(%) <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="bonus_percentage" placeholder="Enter Bonus(%) to be given to the delivery boy on successful order delivery" name="bonus_percentage" value="<?= @$fetched_data[0]['bonus'] ?>">
                                    </div>
                                </div>  
                                <?php
                                $zipcodes = (isset($fetched_data[0]['serviceable_zipcodes']) &&  $fetched_data[0]['serviceable_zipcodes'] != NULL) ? explode(",", $fetched_data[0]['serviceable_zipcodes']) : "";
                                ?>
                                <div class="form-group row">
                                    <label for="zipcodes" class="col-sm-2 col-form-label">Serviceable Zipcodes <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <select name="serviceable_zipcodes[]" class="search_zipcode w-100" multiple onload="multiselect()" id="deliverable_zipcodes">
                                            <?php
                                            if (isset($zipcodes) && !empty($zipcodes)) {
                                                $zipcodes_name =  fetch_details('zipcodes', "",  'zipcode,id', "", "", "", "", "id", $zipcodes);
                                                foreach ($zipcodes_name as $row) {
                                            ?>
                                                    <option value=<?= $row['id'] ?> <?= (!empty($zipcodes) && in_array($row['id'], $zipcodes)) ? 'selected' : ''; ?>> <?= $row['zipcode'] ?></option>
                                            <?php }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Delivery Boy' : 'Add Delivery Boy' ?></button>
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