<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<section class="my-account-section">
    <div class="main-content">
        <div class="col-md-12 mt-5 mb-3">
            <div class="user-detail align-items-center">
                <div class="ml-3">
                    <h6 class="text-muted mb-0"><?= !empty($this->lang->line('hello')) ? $this->lang->line('hello') : 'Hello' ?></h6>
                    <h5 class="mb-0"><?= $user->username ?></h5>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-4">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-8 col-12">
                <div class=' border-0'>
                    <div class="card-header bg-white">
                        <h1 class="h4"><?= !empty($this->lang->line('addresses')) ? $this->lang->line('addresses') : 'Addresses' ?></h1>
                    </div>
                    <form action="<?= base_url('my-account/add-address') ?>" method="POST" id="add-address-form" class="mt-4 p-4">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="name" class="control-label"><?= !empty($this->lang->line('name')) ? $this->lang->line('name') : 'Name' ?></label>
                                <input type="text" class="form-control" id="address_name" name="name" placeholder="Name" />
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="mobile_number" class="control-label"><?= !empty($this->lang->line('mobile_number')) ? $this->lang->line('mobile_number') : 'Mobile Number' ?></label>
                                <input type="text" class="form-control" id="mobile_number" name="mobile" placeholder="Mobile Number" />
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="address" class="control-label"><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?></label>
                                <textarea name="address" class="form-control" id="address" cols="30" rows="4" placeholder="#Door no, Street Address, Locality, Area, Pincode"></textarea>
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="city" class="control-label"><?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?></label>
                                <select name="city_id" id="city" class="form-control">
                                    <option value=""><?= !empty($this->lang->line('select_city')) ? $this->lang->line('select_city') : '--Select City--' ?></option>
                                    <?php foreach ($cities as $row) { ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                <label for="area" class="control-label"><?= !empty($this->lang->line('area')) ? $this->lang->line('area') : 'Area' ?></label>
                                <select name="area_id" id="area" class="form-control">
                                    <option value=""><?= !empty($this->lang->line('select_area')) ? $this->lang->line('select_area') : '--Select Area--' ?></option>
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                <label for="Zipcode" class="control-label"><?= !empty($this->lang->line('pincode')) ? $this->lang->line('pincode') : 'Zipcode' ?></label>
                                <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Zipcode" readonly />
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                <label for="state" class="control-label"><?= !empty($this->lang->line('state')) ? $this->lang->line('state') : 'State' ?></label>
                                <input type="text" class="form-control" id="state" name="state" placeholder="State" />
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                <label for="country" class="control-label"><?= !empty($this->lang->line('country')) ? $this->lang->line('country') : 'Country' ?></label>
                                <input type="text" class="form-control" name="country" id="country" placeholder="Country" />
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="country" class="control-label"><?= !empty($this->lang->line('type')) ? $this->lang->line('type') : 'Type : ' ?></label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="type" id="home" value="home" />
                                    <label for="home" class="form-check-label text-dark"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="type" id="office" value="office" placeholder="Office" />
                                    <label for="office" class="form-check-label text-dark"><?= !empty($this->lang->line('office')) ? $this->lang->line('office') : 'Office' ?></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="type" id="other" value="other" placeholder="Other" />
                                    <label for="other" class="form-check-label text-dark"><?= !empty($this->lang->line('other')) ? $this->lang->line('other') : 'Other' ?></label>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <input type="submit" class="btn btn-primary" id="save-address-submit-btn" value="Save" />
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <div id="save-address-result"></div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="card-body">
                        <table id="address_list_table" class='table-striped' data-toggle="table" data-url="<?= base_url('my-account/get-address-list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true"><?= !empty($this->lang->line('id')) ? $this->lang->line('id') : 'ID' ?></th>
                                    <th data-field="name" data-sortable="false"><?= !empty($this->lang->line('name')) ? $this->lang->line('name') : 'Name' ?></th>
                                    <th data-field="type" data-sortable="false" class="col-md-5"><?= !empty($this->lang->line('type')) ? $this->lang->line('type') : 'Type' ?></th>
                                    <th data-field="mobile" data-sortable="false"><?= !empty($this->lang->line('mobile_number')) ? $this->lang->line('mobile_number') : 'Mobile' ?></th>
                                    <th data-field="alternate_mobile" data-sortable="false"><?= !empty($this->lang->line('alternate_mobile')) ? $this->lang->line('alternate_mobile') : 'Alternate Mobile' ?></th>
                                    <th data-field="address" data-sortable="false"><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?></th>
                                    <th data-field="landmark" data-sortable="false"><?= !empty($this->lang->line('landmark')) ? $this->lang->line('landmark') : 'Landmark' ?></th>
                                    <th data-field="area" data-sortable="false"><?= !empty($this->lang->line('are')) ? $this->lang->line('area') : 'Area' ?></th>
                                    <th data-field="city" data-sortable="false"><?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?></th>
                                    <th data-field="state" data-sortable="false"><?= !empty($this->lang->line('state')) ? $this->lang->line('state') : 'State' ?></th>
                                    <th data-field="pincode" data-sortable="false"><?= !empty($this->lang->line('pincode')) ? $this->lang->line('pincode') : 'Pincode' ?></th>
                                    <th data-field="country" data-sortable="false"><?= !empty($this->lang->line('country')) ? $this->lang->line('country') : 'Country' ?></th>
                                    <th data-field="action" data-events="editAddress" data-sortable="true"><?= !empty($this->lang->line('action')) ? $this->lang->line('action') : 'Action' ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>
<div class="modal fade edit-modal-lg" id="address-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><?= !empty($this->lang->line('edit_address')) ? $this->lang->line('edit_address') : 'Edit Address' ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('my-account/edit-address') ?>" method="POST" id="edit-address-form" class="mt-4">
                    <input type="hidden" name="id" id="address_id" value="" />
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="name" class="control-label"><?= !empty($this->lang->line('name')) ? $this->lang->line('name') : 'Name' ?></label>
                            <input type="text" class="form-control" id="edit_name" name="name" placeholder="Name" />
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="mobile_number" class="control-label"><?= !empty($this->lang->line('mobile_number')) ? $this->lang->line('mobile_number') : 'Mobile Number' ?></label>
                            <input type="text" class="form-control" id="edit_mobile" name="mobile" placeholder="Mobile Number" />
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="address" class="control-label"><?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'Address' ?></label>
                            <input type="text" class="form-control" name="address" id="edit_address" placeholder="Address" />
                        </div>

                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="city" class="control-label"><?= !empty($this->lang->line('city')) ? $this->lang->line('city') : 'City' ?></label>
                            <select name="city_id" id="edit_city" class="form-control">
                                <option value=""><?= !empty($this->lang->line('select_city')) ? $this->lang->line('select_city') : '--Select City--' ?></option>
                                <?php foreach ($cities as $row) { ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label for="area" class="control-label"><?= !empty($this->lang->line('area')) ? $this->lang->line('area') : 'Area' ?></label>
                            <select name="area_id" id="edit_area" class="form-control">
                                <option value=""><?= !empty($this->lang->line('select_area')) ? $this->lang->line('select_area') : '--Select Area--' ?></option>

                            </select>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="pincode" class="control-label"><?= !empty($this->lang->line('pincode')) ? $this->lang->line('pincode') : 'Zipcode' ?></label>
                            <input type="text" class="form-control" id="edit_pincode" name="pincode" placeholder="Name" readonly />
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="state" class="control-label"><?= !empty($this->lang->line('state')) ? $this->lang->line('state') : 'State' ?></label>
                            <input type="text" class="form-control" id="edit_state" name="state" placeholder="State" />
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                            <label for="country" class="control-label"><?= !empty($this->lang->line('country')) ? $this->lang->line('country') : 'Country' ?></label>
                            <input type="text" class="form-control" name="country" id="edit_country" placeholder="Country" />
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label for="country" class="control-label"><?= !empty($this->lang->line('type')) ? $this->lang->line('type') : 'Type : ' ?></label>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="type" id="edit_home" value="home" />
                                <label for="home" class="form-check-label text-dark"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="type" id="edit_office" value="office" placeholder="Office" />
                                <label for="office" class="form-check-label text-dark"><?= !empty($this->lang->line('office')) ? $this->lang->line('office') : 'Office' ?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="type" id="edit_other" value="other" placeholder="Other" />
                                <label for="other" class="form-check-label text-dark"><?= !empty($this->lang->line('other')) ? $this->lang->line('other') : 'Other' ?></label>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <input type="submit" class="btn btn-primary" id="edit-address-submit-btn" value="Save" />
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center mt-2">
                            <div id="edit-address-result"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>