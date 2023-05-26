<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>System Settings</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/setting/update_system_settings') ?>" method="POST" id="system_setting_form" enctype="multipart/form-data">
                            <input type="hidden" id="system_configurations" name="system_configurations" required="" value="1" aria-required="true">
                            <input type="hidden" id="system_timezone_gmt" name="system_timezone_gmt" value="<?= (isset($settings['system_timezone_gmt']) && !empty($settings['system_timezone_gmt'])) ? $settings['system_timezone_gmt'] : '+05:30'; ?>" aria-required="true">
                            <input type="hidden" id="system_configurations_id" name="system_configurations_id" value="13" aria-required="true">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="app_name">App Name <span class='text-danger text-xs'>*</span></label>
                                        <input type="text" class="form-control" name="app_name" value="<?= (isset($settings['app_name'])) ? $settings['app_name'] : '' ?>" placeholder="Name of the App - used in whole system" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="support_number">Support Number <span class='text-danger text-xs'>*</span></label>
                                        <input type="text" class="form-control" name="support_number" value="<?= (isset($settings['support_number'])) ? $settings['support_number'] : '' ?>" placeholder="Customer support mobile number - used in whole system" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="support_email">Support Email <span class='text-danger text-xs'>*</span></label>
                                        <input type="text" class="form-control" name="support_email" value="<?= (isset($settings['support_email'])) ? $settings['support_email'] : '' ?>" placeholder="Customer support email - used in whole system" />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="logo">Logo <span class='text-danger text-xs'>*</span><small>(Recommended Size : larger than 120 x 120 & smaller than 150 x 150 pixels.)</small></label>
                                                <div class="col-sm-10">
                                                    <div class='col-md-3'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='logo' data-isremovable='0' data-is-multiple-uploads-allowed='0' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                                    <?php
                                                    if (!empty($logo)) {
                                                    ?>
                                                        <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                                        <div class="container-fluid row image-upload-section">
                                                            <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                                <div class=''>
                                                                    <div class='upload-media-div'><img class="img-fluid mb-2" src="<?= BASE_URL() . $logo ?>" alt="Image Not Found"></div>
                                                                    <input type="hidden" name="logo" id='logo' value='<?= $logo ?>'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } else { ?>
                                                        <div class="container-fluid row image-upload-section">
                                                            <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none">
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="favicon">Favicon <span class='text-danger text-xs'>*</span></label>
                                                <div class="col-sm-10">
                                                    <div class='col-md-3'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='favicon' data-isremovable='0' data-is-multiple-uploads-allowed='0' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                                    <?php
                                                    if (!empty($favicon)) {
                                                    ?>
                                                        <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                                        <div class="container-fluid row image-upload-section">
                                                            <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                                <img class="img-fluid mb-2" src="<?= BASE_URL() . $favicon ?>" alt="Image Not Found">
                                                                <input type="hidden" name="favicon" id='favicon' value='<?= $favicon ?>'>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } else { ?>
                                                        <div class="container-fluid row image-upload-section">
                                                            <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded text-center grow image d-none">
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4>Version Settings</h4>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="current_version">Current Version Of Android APP <span class='text-danger text-xs'>*</span></label>
                                        <input type="text" class="form-control" name="current_version" value="<?= (isset($settings['current_version'])) ? $settings['current_version'] : '' ?>" placeholder='Current For Version For Android APP' />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="current_version">Current Version Of IOS APP <span class='text-danger text-xs'>*</span></label>
                                        <input type="text" class="form-control" name="current_version_ios" value="<?= (isset($settings['current_version_ios'])) ? $settings['current_version_ios'] : '' ?>" placeholder='Current Version For IOS APP' />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="is_version_system_on">Version System Status </label>
                                        <div class="card-body">
                                            <input type="checkbox" name="is_version_system_on" <?= (isset($settings['is_version_system_on']) && $settings['is_version_system_on'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                    </div>
                                    <hr>
                                    <?php $class = isset($settings['area_wise_delivery_charge']) && $settings['area_wise_delivery_charge'] == '1' ? 'col-md-6' : 'col-md-4' ?>
                                    <div class="form-group area_wise_delivery_charge <?= $class ?>">
                                        <label for="area_wise_delivery_charge">Area Wise Delivery Charge <small>( Enable / Disable )</small></label>
                                        <div class="card-body">
                                            <input type="checkbox" name="area_wise_delivery_charge" id="area_wise_delivery_charge" value="area_wise_delivery_charge" <?= (isset($settings['area_wise_delivery_charge']) && $settings['area_wise_delivery_charge'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                    </div>
                                    <?php $d_none = isset($settings['area_wise_delivery_charge']) && $settings['area_wise_delivery_charge'] == '1' ? 'd-none' : '' ?>
                                    <div class="form-group col-md-4 delivery_charge <?= $d_none ?>">
                                        <label for="delivery_charge">Delivery Charge Amount (<?= $currency ?>) <span class='text-danger text-xs'>*</span></label>
                                        <input type="number" class="form-control" name="delivery_charge" value="<?= (isset($settings['delivery_charge'])) ? $settings['delivery_charge'] : '' ?>" placeholder='Delivery Charge on Shopping' min='0' />
                                    </div>
                                    <div class="form-group col-md-4 min_amount <?= $d_none ?>">
                                        <label for="min_amount">Minimum Amount for Free Delivery (<?= $currency ?>) <span class='text-danger text-xs'>*</span>
                                        </label>
                                        <input type="number" class="form-control" name="min_amount" value="<?= (isset($settings['min_amount'])) ? $settings['min_amount'] : ''  ?>" placeholder='Minimum Order Amount for Free Delivery' min='0' />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="system_timezone" for="system_timezone">System Timezone <span class='text-danger text-xs'>*</span></label>
                                        <select id="system_timezone" name="system_timezone" required class="form-control col-md-12 select2">
                                            <option value=" ">--Select Timezones--</option>
                                            <?php
                                            foreach ($timezone as $t) { ?>
                                                ?>
                                                <option value="<?= $t["zone"] ?>" data-gmt="<?= $t['diff_from_GMT']; ?>" <?= (isset($settings['system_timezone']) && $settings['system_timezone'] == $t["zone"]) ? 'selected' : ''; ?>><?= $t['zone'] . ' - ' . $t['diff_from_GMT'] . ' - ' . $t['time']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="minimum_cart_amt">Minimum Cart Amount(<?= $currency ?>) <span class='text-danger text-xs'>*</span></label>
                                        <input type="number" class="form-control" name="minimum_cart_amt" value="<?= (isset($settings['minimum_cart_amt'])) ? $settings['minimum_cart_amt'] : '' ?>" placeholder='Minimum Cart Amount' min='0' />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="max_items_cart"> Maximum Items Allowed In Cart <span class='text-danger text-xs'>*</span></label>
                                        <input type="number" class="form-control" name="max_items_cart" value="<?= (isset($settings['max_items_cart'])) ? $settings['max_items_cart'] : '' ?>" placeholder='Maximum Items Allowed In Cart' min='0' />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="max_items_cart"> Low stock limit <small>(Product will be considered as low stock)</small> </label>
                                        <input type="number" class="form-control" name="low_stock_limit" value="<?= (isset($settings['low_stock_limit'])) ? $settings['low_stock_limit'] : '5' ?>" placeholder='Product low stock limit' min='1' />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Max days to return item</label>
                                        <input type="number" class="form-control" name="max_product_return_days" value="<?= (isset($settings['max_product_return_days'])) ? $settings['max_product_return_days'] : '' ?>" placeholder='Max days to return item' />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Delivery Boy Bonus (%)</label>
                                        <input type="number" class="form-control" name="delivery_boy_bonus_percentage" value="<?= (isset($settings['delivery_boy_bonus_percentage'])) ? $settings['delivery_boy_bonus_percentage'] : '' ?>" placeholder='Delivery Boy Bonus' />
                                    </div>
                                </div>
                                <hr>
                                <h4>Delivery Boy Settings</h4>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label for="is_delivery_boy_otp_setting_on"> Order Delivery OTP System
                                        </label>
                                        <div class="card-body">
                                            <input type="checkbox" name="is_delivery_boy_otp_setting_on" <?= (isset($settings['is_delivery_boy_otp_setting_on']) && $settings['is_delivery_boy_otp_setting_on'] == '1') ? 'Checked' : ''  ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                    </div>
                                </div>
                                <h4>App & System Settings</h4>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="cart_btn_on_list"> Enable Cart Button on Products List view? </label>
                                        <div class="card-body">
                                            <input type="checkbox" name="cart_btn_on_list" <?= (isset($settings['cart_btn_on_list']) && $settings['cart_btn_on_list'] == '1') ? 'Checked' : ''  ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="expand_product_images"> Expand Product Images? <small>( Image will be stretched in the product image boxes )</small> </label>
                                        <div class="card-body">
                                            <input type="checkbox" name="expand_product_images" <?= (isset($settings['expand_product_images']) && $settings['expand_product_images'] == '1') ? 'Checked' : ''  ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tax_name">Tax Name <small>( This will be visible on your invoice )</small></label>
                                        <input type="text" class="form-control" name="tax_name" value="<?= (isset($settings['tax_name'])) ? $settings['tax_name'] : '' ?>" placeholder='Example : GST Number / VAT / TIN Number' />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="tax_number">Tax Number </label>
                                        <input type="text" class="form-control" name="tax_number" value="<?= (isset($settings['tax_number'])) ? $settings['tax_number'] : '' ?>" placeholder='Example : GSTIN240000120' />
                                    </div>
                                </div>
                                <h4>Refer & Earn Settings</h4>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="is_refer_earn_on"> Refer & Earn Status? </label>
                                        <div class="card-body">
                                            <input type="checkbox" name="is_refer_earn_on" <?= (isset($settings['is_refer_earn_on']) && $settings['is_refer_earn_on'] == '1') ? 'Checked' : ''  ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="min_refer_earn_order_amount"> Minimum Refer & Earn Order Amount (<?= $currency ?>) </label>
                                        <input type="text" name="min_refer_earn_order_amount" class="form-control" value="<?= (isset($settings['min_refer_earn_order_amount']) && $settings['min_refer_earn_order_amount'] != '') ? $settings['min_refer_earn_order_amount'] : ''  ?>" placeholder="Amount of order eligible for bonus" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="refer_earn_bonus">Refer & Earn Bonus (<?= $currency ?> OR %)</label>
                                        <input type="text" class="form-control" name="refer_earn_bonus" value="<?= (isset($settings['refer_earn_bonus'])) ? $settings['refer_earn_bonus'] : '' ?>" placeholder='In amount or percentages' />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="refer_earn_method">Refer & Earn Method </label>
                                        <select name="refer_earn_method" class="form-control">
                                            <option value="">Select</option>
                                            <option value="percentage" <?= (isset($settings['refer_earn_method']) && $settings['refer_earn_method'] == "percentage") ? "selected" : "" ?>>Percentage</option>
                                            <option value="amount" <?= (isset($settings['refer_earn_method']) && $settings['refer_earn_method'] == "amount") ? "selected" : "" ?>>Amount</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="max_refer_earn_amount">Maximum Refer & Earn Amount (<?= $currency ?>)</label>
                                        <input type="text" class="form-control" name="max_refer_earn_amount" value="<?= (isset($settings['max_refer_earn_amount'])) ? $settings['max_refer_earn_amount'] : '' ?>" placeholder='Maximum Refer & Earn Bonus Amount' />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="refer_earn_bonus_times">Number of times Bonus to be given to the cusomer</label>
                                        <input type="text" class="form-control" name="refer_earn_bonus_times" value="<?= (isset($settings['refer_earn_bonus_times'])) ? $settings['refer_earn_bonus_times'] : '' ?>" placeholder='No of times customer will get bonus' />
                                    </div>
                                </div>

                                <span class="d-flex align-items-center ">
                                    <h4>Welcome Wallet Balance &nbsp;</h4>
                                </span>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="welcome_wallet_balance_on"> Wallet Balance Status? </label>
                                        <div class="card-body">
                                            <input type="checkbox" name="welcome_wallet_balance_on" <?= (isset($settings['welcome_wallet_balance_on']) && $settings['welcome_wallet_balance_on'] == '1') ? 'Checked' : ''  ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="wallet_balance_amount"> Wallet Balance Amount (<?= $currency ?>) </label>
                                        <input type="text" name="wallet_balance_amount" class="form-control" value="<?= (isset($settings['wallet_balance_amount']) && $settings['wallet_balance_amount'] != '') ? $settings['wallet_balance_amount'] : ''  ?>" placeholder="Amount of Welcome Wallet Balance" />
                                    </div>
                                </div>
                                <span class="d-flex align-items-center ">
                                    <h4>Country Currency &nbsp;</h4>
                                </span>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="supported_locals">Country Currency Code</label>
                                        <select name="supported_locals" class="form-control">
                                            <?php
                                            $CI = &get_instance();
                                            $CI->config->load('eshop');
                                            $supported_methods = $CI->config->item('supported_locales_list');
                                            foreach ($supported_methods as $key => $value) {
                                                $text = "$key - $value "; ?>
                                                <option value="<?= $key ?>" <?= (isset($settings['supported_locals']) && !empty($settings['supported_locals']) && $key == $settings['supported_locals']) ? "selected" : "" ?>><?= $key . ' - ' . $value ?></option>
                                            <?php  }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="currency">Store Currency ( Symbol or Code - $ or USD - Anyone ) <span class='text-danger text-xs'>*</span></label>
                                        <input type="text" class="form-control" name="currency" value="<?= (isset($settings['currency'])) ? $settings['currency'] : '' ?>" placeholder="Either Symbol or Code - For Example $ or USD" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="currency">Decimal Point</label>
                                        <select name="decimal_point" class="form-control">
                                            <?php
                                            $CI = &get_instance();
                                            $CI->config->load('eshop');
                                            $decimal_points = $CI->config->item('decimal_point');
                                            foreach ($decimal_points as $key => $value) {
                                                $text = "$key - $value "; ?>
                                                <option value="<?= $key ?>" <?= (isset($settings['decimal_point']) && !empty($settings['decimal_point']) && $key == $settings['decimal_point']) ? "selected" : "" ?>><?= $value ?></option>
                                            <?php  }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <h4>Order Settings</h4>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label for="is_single_seller_order"> Single Seller Order System
                                        </label>
                                        <div class="card-body">
                                            <input type="checkbox" name="is_single_seller_order" <?= (isset($settings['is_single_seller_order']) && $settings['is_single_seller_order'] == '1') ? 'Checked' : ''  ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                    </div>
                                </div>
                                <h4>Maintenance Mode</h4><small>(If you Enable Maintenance Mode of App your App Will be in "Under Maintenance")</small>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="is_customer_app_under_maintenance"> Customer App</label>
                                        <div class="card-body pl-0">
                                            <input type="checkbox" name="is_customer_app_under_maintenance" <?= (isset($settings['is_customer_app_under_maintenance']) && $settings['is_customer_app_under_maintenance'] == '1') ? 'Checked' : ''  ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                        <label for="message_for_customer_app"> Message for Customer App</label>
                                        <div class="card-body pl-0">
                                            <textarea type="text" class="form-control" id="message_for_customer_app" placeholder="Message for Customer App" name="message_for_customer_app"><?= isset($settings['message_for_customer_app']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['message_for_customer_app'])) : ""; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="is_seller_app_under_maintenance"> Seller App</label>
                                        <div class="card-body pl-0">
                                            <input type="checkbox" name="is_seller_app_under_maintenance" <?= (isset($settings['is_seller_app_under_maintenance']) && $settings['is_seller_app_under_maintenance'] == '1') ? 'Checked' : ''  ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                        <label for="message_for_seller_app"> Message for Seller App</label>
                                        <div class="card-body pl-0">
                                            <textarea type="text" class="form-control" id="message_for_seller_app" placeholder="Message for Seller App" name="message_for_seller_app"><?= isset($settings['message_for_seller_app']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['message_for_seller_app'])) : ""; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="is_delivery_boy_app_under_maintenance"> Delivery boy App</label>
                                        <div class="card-body pl-0">
                                            <input type="checkbox" name="is_delivery_boy_app_under_maintenance" <?= (isset($settings['is_delivery_boy_app_under_maintenance']) && $settings['is_delivery_boy_app_under_maintenance'] == '1') ? 'Checked' : ''  ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                        </div>
                                        <label for="message_for_delivery_boy_app"> Message for Delivery boy App</label>
                                        <div class="card-body pl-0">
                                            <textarea type="text" class="form-control" id="message_for_delivery_boy_app" placeholder="Message for Delivery boy App" name="message_for_delivery_boy_app"><?= isset($settings['message_for_delivery_boy_app']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $settings['message_for_delivery_boy_app'])) : ""; ?></textarea>
                                        </div>
                                    </div>

                                </div>
                                <h4>Cron Job URL for Seller commission</h4>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label for="app_name">Cron Job URL <span class='text-danger text-xs'>*</span> <small>(Set this URL at your server cron job list for "once a day")</small></label>
                                        <a class="btn btn-xs btn-primary text-white mb-2" data-toggle="modal" data-target="#howItWorksModal" title="How it works">How seller commission works?</a>
                                        <input type="text" class="form-control" name="app_name" value="<?= base_url('admin/cron-job/settle-seller-commission') ?>" disabled />
                                    </div>
                                </div>
                                <h4>Cron Job URL for Add Promo Code Discount</h4>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label for="app_name">Add Promo Code Discount URL <span class='text-danger text-xs'>*</span> <small>(Set this URL at your server cron job list for "once a day")</small></label>
                                        <a class="btn btn-xs btn-primary text-white mb-2" data-toggle="modal" data-target="#howItWorksModal1" title="How it works">How Promo Code Discount works?</a>
                                        <input type="text" class="form-control" name="app_name" value="<?= base_url('admin/cron_job/settle_cashback_discount') ?>" disabled />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div class="form-group" id="error_box">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn">Update Settings</button>
                                </div>
                            </div>
                        </form>
                        <div class="modal fade" id="howItWorksModal1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content p-3 p-md-5">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">How Promo Code Discount will get credited?</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body ">
                                        <ol>
                                            <li>Cron job must be set on your server for Promo Code Discount to be work.</li>

                                            <li> Cron job will run every mid night at 12:00 AM. </li>

                                            <li> Formula for Add Promo Code Discount is <b>Sub total (Excluding delivery charge) - promo code discount percentage / Amount</b> </li>

                                            <li> For example sub total is 1300 and promo code discount is 100 then 1300 - 100 = 1200 so 100 will get credited into Users's wallet </li>

                                            <li> If Order status is delivered And Return Policy is expired then only users will get Promo Code Discount. </li>

                                            <li> Ex - 1. Order placed on 10-Sep-22 and return policy days are set to 1 so 10-Sep + 1 days = 11-Sep Promo code discount will get credited on 11-Sep-22 at 12:00 AM (Mid night) </li>

                                            <li> If Promo Code Discount doesn't works make sure cron job is set properly and it is working. If you don't know how to set cron job for once in a day please take help of server support or do search for it. </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="howItWorksModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content p-3 p-md-5">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">How seller commission will get credited?</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body ">
                                        <ol>
                                            <li>
                                                Cron job must be set (For once in a day) on your server for seller commission to be work.
                                            </li>
                                            <li>
                                                Cron job will run every mid night at 12:00 AM.
                                            </li>
                                            <li>
                                                Formula for seller commision is <b>Sub total (Excluding delivery charge) / 100 * seller commission percentage</b>
                                            </li>
                                            <li>
                                                For example sub total is 1378 and seller commission is 20% then 1378 / 100 X 20 = 275.6 so 1378 - 275.6 = 1102.4 will get credited into seller's wallet
                                            </li>
                                            <li>
                                                If Order item's status is delivered then only seller will get commisison.
                                            </li>
                                            <li>
                                                Ex - 1. Order placed on 11-Aug-21 and product return days are set to 0 so 11-Aug + 0 days = 11-Aug seller commission will get credited on 12-Aug-21 at 12:00 AM (Mid night)
                                            </li>
                                            <li>
                                                Ex - 2. Order placed on 11-Aug-21 and product return days are set to 7 so 11-Aug + 7 days = 18-Aug seller commission will get credited on 19-Aug-21 at 12:00 AM (Mid night)
                                            </li>
                                            <li>
                                                If seller commission doesn't works make sure cron job is set properly and it is working. If you don't know how to set cron job for once in a day please take help of server support or do search for it.
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/.card-->
                    </div>
                    <!--/.col-md-12-->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>