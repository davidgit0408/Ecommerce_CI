<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Custom message </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Custom message </li>
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
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/custom_notification/add_notification'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php
                            if (isset($fetched_data[0]['id'])) {
                            ?>
                                <input type="hidden" id="edit_custom_notification" name="edit_custom_notification" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                                <input type="hidden" id="udt_title" value="<?= @$fetched_data[0]['title'] ?>">
                            <?php
                            }
                            ?>
                            <div class=" card-body">
                                <?php
                                $type = ['place_order', 'settle_cashback_discount', 'settle_seller_commission',  'customer_order_received', 'customer_order_processed', 'customer_order_shipped', 'customer_order_delivered', 'customer_order_cancelled', 'customer_order_returned', 'delivery_boy_order_deliver', 'wallet_transaction', 'ticket_status', 'ticket_message', 'bank_transfer_receipt_status', 'bank_transfer_proof'];
                                ?>
                                <div class="form-group row">
                                    <label for="type" class="col-sm-2 control-label">Types <span class='text-danger text-sm'> * </span></label>
                                    <div class="col-sm-10">
                                        <select name="type" class="form-control type">
                                            <option value=" ">Select Types</option>
                                            <?php foreach ($type as $row) { ?>
                                                <option value="<?= $row ?>" <?= (isset($fetched_data[0]['id']) &&  $fetched_data[0]['type'] == $row) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $row)) ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                        <?php ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="title" id="update_title" class="form-control update_title" placeholder="Title Name" value="<?= (isset($fetched_data[0]['title'])) ? $fetched_data[0]['title'] : ""; ?>" />
                                    </div>
                                </div>
                                <div class="form-group row place_order <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'place_order') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< order_id >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag_input"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row">
                                    <label for="message" class="col-sm-2 col-form-label">Message<span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="message" id="text-box" class="form-control" placeholder="Place some text here"><?= (isset($fetched_data[0]['id'])) ? $fetched_data[0]['message'] : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row place_order <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'place_order') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>

                                    <?php
                                    $hashtag = ['< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row settle_cashback_discount <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'settle_cashback_discount') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row settle_seller_commission <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'settle_seller_commission') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_received <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_received') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_processed <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_processed') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_shipped <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_shipped') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_delivered <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_delivered') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_cancelled <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_cancelled') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_returned <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_returned') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row delivery_boy_order_deliver <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'delivery_boy_order_deliver') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row wallet_transaction <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'wallet_transaction') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< currency >', '< returnable_amount >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row ticket_status <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'ticket_status') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row ticket_message <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'ticket_message') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row bank_transfer_receipt_status <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'bank_transfer_receipt_status') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< status >', '< order_id >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row bank_transfer_proof <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'bank_transfer_proof') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< order_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Custom message ' : 'Add Custom message ' ?></button>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div class="form-group" id="error_box">
                                    </div>
                                </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Custom message </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-head">
                            <h4 class="card-title text-center">Custom message List</h4>
                        </div>
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/custom_notification/view_notification') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "custom-notifications-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="title" data-sortable="false">Title</th>
                                        <th data-field="type" data-sortable="true">Type</th>
                                        <th data-field="message" data-sortable="true">Message</th>
                                        <th data-field="operate" data-sortable="true">Action</th>
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