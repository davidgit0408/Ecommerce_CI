<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('checkout')) ? $this->lang->line('checkout') : 'Checkout' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('checkout')) ? $this->lang->line('checkout') : 'Checkout' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<!-- checkout -->
<section>
    <div class="main-content">
        <form class="needs-validation" id="checkout_form" method="POST" action="<?= base_url('cart/place-order') ?>">
            <div class="row">
                <div class="col-xl-8 bg-white mt-5">
                    <h2 class="checkout-form-title"><?= !empty($this->lang->line('billing_details')) ? $this->lang->line('billing_details') : 'Billing Details' ?></h2>
                    <div class="ship-details-wrapper">
                        <input type="hidden" name="product_type" value="<?= $cart[0]['type']; ?>">
                        <input type="hidden" name="download_allowed" value="<?= in_array(0, $cart['download_allowed']) ? 0 : 1 ?>">
                        <?php if ($cart[0]['type'] != 'digital_product') { ?>
                            <div class="align-item-center ship-title-details justify-content-between user-add d-flex">
                                <h5 class="pb-3"><?= !empty($this->lang->line('shipping_address')) ? $this->lang->line('shipping_address') : 'Shipping Address' ?></h5>
                                <a href="#" data-izimodal-open=".address-modal"><i class="fas fa-edit edit-icon"></i></a>
                            </div>

                            <div class="shipped-details mt-3">
                                <p class="text-muted" id="address-name-type"><?= isset($default_address) && !empty($default_address) ? $default_address[0]['name'] . ' - ' . ucfirst($default_address[0]['type']) : '' ?></p>
                                <p class="text-muted" id="address-full"><?= isset($default_address) && !empty($default_address) ? $default_address[0]['area'] . ' , ' . $default_address[0]['city'] : '' ?></p>
                                <p class="text-muted" id="address-country"><?= isset($default_address) && !empty($default_address) ? $default_address[0]['state'] . ' , ' . $default_address[0]['country'] . ' - ' . $default_address[0]['pincode'] : '' ?></p>
                                <p class="text-muted" id="address-mobile"><?= isset($default_address) && !empty($default_address) ? $default_address[0]['mobile'] : '' ?></p>
                            </div>

                            </br>
                            <!-- checking product deliverable or not  -->
                            <div id="deliverable_status">
                                <?php
                                $product_not_delivarable = array();
                                if (isset($default_address) && !empty($default_address)) {
                                    $product_delivarable = check_cart_products_delivarable($default_address[0]['area_id'], $cart[0]['user_id']);
                                    if (!empty($product_delivarable)) {
                                        $product_not_delivarable = array_filter($product_delivarable, function ($var) {
                                            return ($var['is_deliverable'] == false);
                                        });
                                        $product_not_delivarable = array_values($product_not_delivarable);
                                        $deliverable_error_msg = "";
                                        foreach ($product_not_delivarable as $p_id) {
                                            if (!empty($p_id['product_id'])) {
                                                $deliverable_error_msg = (!empty($this->lang->line('product_not_delivarable_msg'))) ? $this->lang->line('product_not_delivarable_msg') : "Some of the item(s) are not delivarable on selected address. Try changing address or modify your cart items.";
                                                continue;
                                            }
                                        }
                                ?>
                                        <b class="text-danger"><?= $deliverable_error_msg ?></b>
                                <?php }
                                } ?>
                            </div>
                        <?php } ?>
                        <?php if (in_array(0, $cart['download_allowed']) && $cart[0]['type'] == 'digital_product') { ?>
                            <div class="input-group mt-3">
                                <input name="email" type="text" class="form-control" placeholder="Please enter your email ID ">
                            </div>
                        <?php } ?>

                        <input type="hidden" name="address_id" id="address_id" value="<?= isset($default_address) && !empty($default_address) ? $default_address[0]['id'] : '' ?>" />
                        <input type="hidden" name="mobile" id="mobile" value="<?= isset($default_address) && !empty($default_address) ? $default_address[0]['mobile'] : $wallet_balance[0]['mobile'] ?>" />
                    </div>
                    <hr>
                    <input type="hidden" name="total" value="<?= number_format($cart['sub_total'], 2) ?>">
                    <input type="hidden" id="temp_total" name="temp_total" value="<?= $cart['total_arr'] ?>">
                    <input type="hidden" name="product_variant_id" value="<?= implode(',', array_column($cart, 'id')) ?>">
                    <input type="hidden" name="quantity" value="<?= implode(',', array_column($cart, 'qty')) ?>">
                    <input type="hidden" id="current_wallet_balance" value="<?= number_format($wallet_balance[0]['balance'], 2) ?>">
                    <input type="hidden" id="wallet_used" name="wallet_used">
                    <input type="hidden" name="is_time_slots_enabled" id="is_time_slots_enabled" value="<?= (isset($time_slot_config['is_time_slots_enabled']) && $time_slot_config['is_time_slots_enabled'] == 1) ? 1 : 0 ?>">
                    <input type="hidden" name="product_type" id="product_type" value="<?= $cart[0]['type']?>">
                    <?php if (isset($time_slot_config['is_time_slots_enabled']) && $time_slot_config['is_time_slots_enabled'] == 1 && $cart[0]['type'] != 'digital_product') {
                        //If Time Slot is Enabled
                    ?>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Special Note for Order" name="order_note" id="order_note">
                        </div>
                        <hr>
                        <h4 class="mt-3"><?= !empty($this->lang->line('preferred_delivery_date_time')) ? $this->lang->line('preferred_delivery_date_time') : 'Preferred Delivery Date / Time' ?></h4>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                            </div>
                            <input type="text" class="form-control float-right" id="datepicker">
                            <input type="hidden" id="start_date" class="form-control float-right">
                        </div>
                        <div class="mt-3" id="time_slots">
                            <?php foreach ($time_slots as $row) { ?>
                                <div class="custom-control custom-radio">
                                    <input id="<?= $row['id'] ?>" name="delivery_time" type="radio" class="custom-control-input time-slot-inputs" data-last_order_time="<?= $row['last_order_time'] ?>" value="<?= $row['title'] ?>" required>
                                    <label class="custom-control-label" for="<?= $row['id'] ?>"><?= $row['title'] ?></label>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <hr>
                    <input type="hidden" name="delivery_date" id="delivery_date" class="form-control float-right">
                    <div class="align-item-center ship-title-details justify-content-between d-flex">
                        <h5><?= !empty($this->lang->line('wallet_balance')) ? $this->lang->line('wallet_balance') : 'Use wallet balance' ?></h5>
                    </div>
                    <?php $disabled = $wallet_balance[0]['balance'] == 0 ? 'disabled' : '' ?>
                    <div class="form-check d-flex">
                        <input class="form-check-input" type="checkbox" value="" id="wallet_balance" <?= $disabled ?>>
                        <label class="form-check-label d-flex" for="flexCheckDefault">
                            <?= !empty($this->lang->line('available_balance')) ? $this->lang->line('available_balance') : 'Available balance' ?> : <?= $currency . '<span id="available_balance">' . number_format($wallet_balance[0]['balance'], 2) . '</span>' ?>
                        </label>
                    </div>

                    <div class="ship-details-wrapper mt-3 payment-methods">
                        <div class="align-item-center ship-title-details justify-content-between d-flex">
                            <h5><?= !empty($this->lang->line('payment_method')) ? $this->lang->line('payment_method') : 'Payment Method' ?></h5>
                        </div>
                        <div class="shipped-details mt-3 col-md-6">
                            <table class="table table-step-shipping">
                                <tbody>
                                    <?php if (isset($payment_methods['cod_method']) && $payment_methods['cod_method'] == 1) { ?>
                                        <tr>
                                            <label for="cod">
                                                <td>
                                                    <label for="cod">
                                                        <input id="cod" title="<?= isset($cart[0]['is_cod_allowed']) && $cart[0]['is_cod_allowed'] == 0 ? 'Cash on delivery is not allowed for one of the item in your cart' : 'Please select one of this options.' ?>" name="payment_method" type="radio" value="COD" <?= isset($cart[0]['is_cod_allowed']) && $cart[0]['is_cod_allowed'] == 0 ? 'disabled' : '' ?>>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="cod">
                                                        <img src="<?= THEME_ASSETS_URL . 'images/cod.png' ?>" class="payment-gateway-images" alt="COD">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="cod">
                                                        <?= !empty($this->lang->line('cash_on_delivery')) ? $this->lang->line('cash_on_delivery') : 'Cash On Delivery' ?>
                                                    </label>
                                                </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['paypal_payment_method']) && $payment_methods['paypal_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="paypal">
                                                    <input id="paypal" name="payment_method" type="radio" value="Paypal" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paypal">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/paypal.png' ?>" class="payment-gateway-images" alt="Paypal">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paypal">
                                                    Paypal
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['razorpay_payment_method']) && $payment_methods['razorpay_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="razorpay">
                                                    <input id="razorpay" name="payment_method" type="radio" value="Razorpay" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="razorpay">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/razorpay.png' ?>" class="payment-gateway-images" alt="Razorpay">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="razorpay">
                                                    RazorPay
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>





                                    <?php if (isset($payment_methods['paystack_payment_method']) && $payment_methods['paystack_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="paystack">
                                                    <input id="paystack" name="payment_method" type="radio" value="Paystack" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paystack">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/paystack.png' ?>" class="payment-gateway-images" alt="Paystack">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paystack">
                                                    Paystack
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['payumoney_payment_method']) && $payment_methods['payumoney_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="payumoney">
                                                    <input id="payumoney" name="payment_method" type="radio" value="Payumoney" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="payumoney">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/payumoney.png' ?>" class="payment-gateway-images" alt="Payumoney">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="payumoney">
                                                    Payumoney
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['flutterwave_payment_method']) && $payment_methods['flutterwave_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="flutterwave">
                                                    <input id="flutterwave" name="payment_method" type="radio" value="Flutterwave" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="flutterwave">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/flutterwave.png' ?>" class="payment-gateway-images" alt="Flutterwave">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="flutterwave">
                                                    Flutterwave
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['paytm_payment_method']) && $payment_methods['paytm_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="paytm">
                                                    <input id="paytm" name="payment_method" type="radio" value="Paytm" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paytm">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/paytm.png' ?>" class="payment-gateway-images" alt="Paytm">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="paytm">
                                                    Paytm
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($payment_methods['stripe_payment_method']) && $payment_methods['stripe_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="stripe">
                                                    <input id="stripe" name="payment_method" type="radio" value="Stripe" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="stripe">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/stripe.png' ?>" class="payment-gateway-images" alt="Stripe">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="stripe">
                                                    Stripe
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>


                                    <?php if (isset($payment_methods['direct_bank_transfer']) && $payment_methods['direct_bank_transfer'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="bank_transfer">
                                                    <input id="bank_transfer" name="payment_method" type="radio" value="<?= BANK_TRANSFER ?>" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="bank_transfer">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/bank_transfer.png' ?>" class="payment-gateway-images" alt="Direct Bank Transfers">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="bank_transfer">
                                                    <?= !empty($this->lang->line('direct_bank_transfers')) ? $this->lang->line('direct_bank_transfers') : 'Direct Bank Transfers' ?>
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <!-- Midtrans -->
                                    <?php if (isset($payment_methods['midtrans_payment_method']) && $payment_methods['midtrans_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="midtrans">
                                                    <input id="midtrans" name="payment_method" type="radio" value="Midtrans" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="midtrans">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/midtrans.jpg' ?>" class="payment-gateway-images" alt="Midtrans">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="midtrans">
                                                    Midtrans
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <?php if (isset($payment_methods['myfaoorah_payment_method']) && $payment_methods['myfaoorah_payment_method'] == 1) { ?>
                                        <tr>
                                            <td>
                                                <label for="my_fatoorah">
                                                    <input id="my_fatoorah" name="payment_method" type="radio" value="my_fatoorah" required>
                                                </label>
                                            </td>
                                            <td>
                                                <label for="my_fatoorah">
                                                    <img src="<?= THEME_ASSETS_URL . 'images/myfatoorah.jpg' ?>" class="payment-gateway-images" alt="myfatoorah">
                                                </label>
                                            </td>
                                            <td>
                                                <label for="my_fatoorah">
                                                    My Fatoorah
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="stripe_div">
                        <div id="stripe-card-element">
                            <!--Stripe.js injects the Card Element-->
                        </div>
                        <p id="card-error" role="alert"></p>
                        <p class="result-message hidden"></p>
                    </div>

                    <div id="my_fatoorah_div">
                        <div id="card-element">
                            <!--Stripe.js injects the Card Element-->
                        </div>
                        <p id="card-error" role="alert"></p>
                        <p class="result-message hidden"></p>
                    </div>


                    <div id="bank_transfer_slide">
                        <div id="account_data" style="display: none;">
                            <?php if (isset($payment_methods['direct_bank_transfer']) && $payment_methods['direct_bank_transfer'] == 1) { ?>
                                <div class="row">
                                    <div class="alert alert-warning">
                                        <strong><?= !empty($this->lang->line('edit_address')) ? $this->lang->line('edit_address') : 'Instructions! Make your payment directly into our account. Your order will not further proceed until the funds have cleared in our account. <br> You have to send your payment receipt from order details page then admin will verify that.' ?> </strong>
                                    </div>
                                    <div class="alert alert-info col-md-12">
                                        <strong><?= !empty($this->lang->line('account_details')) ? $this->lang->line('account_details') : 'Account Details!' ?> </strong> <br><br>
                                        <ul>
                                            <li><?= !empty($this->lang->line('account_name')) ? $this->lang->line('account_name') : 'Account Name' ?>: <?= (isset($payment_methods['account_name'])) ? $payment_methods['account_name'] : "" ?></li>
                                            <li><?= !empty($this->lang->line('account_number')) ? $this->lang->line('account_number') : 'Account Number' ?>: <?= (isset($payment_methods['account_number'])) ? $payment_methods['account_number'] : "" ?></li>
                                            <li><?= !empty($this->lang->line('bank_name')) ? $this->lang->line('bank_name') : 'Bank Name' ?>: <?= (isset($payment_methods['bank_name'])) ? $payment_methods['bank_name'] : "" ?></li>
                                            <li><?= !empty($this->lang->line('bank_code')) ? $this->lang->line('bank_code') : 'Bank Code' ?>: <?= (isset($payment_methods['bank_code'])) ? $payment_methods['bank_code'] : "" ?></li>
                                        </ul>
                                    </div>
                                    <div class="alert alert-info col-md-12">
                                        <strong><?= !empty($this->lang->line('extra_details')) ? $this->lang->line('extra_details') : 'Extra Details' ?>! </strong> <br><br>
                                        <p><?= (isset($payment_methods['notes'])) ? $payment_methods['notes'] : "" ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <hr class="mb-2">
                    <input type="hidden" name="app_name" id="app_name" value="<?= $settings['app_name'] ?>" />
                    <input type="hidden" name="username" id="username" value="<?= $user->username ?>" />
                    <input type="hidden" id="user_id" value="<?= $user->id ?>" />
                    <input type="hidden" name="user_email" id="user_email" value="<?= isset($user->email) && !empty($user->email) ? $user->email : $support_email ?>" />
                    <input type="hidden" name="user_contact" id="user_contact" value="<?= $user->mobile ?>" />
                    <input type="hidden" name="logo" id="logo" value="<?= base_url(get_settings('web_logo')) ?>" />
                    <input type="hidden" name="order_amount" id="amount" value="" />
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="" />
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="" />
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature" value="" />

                    <input type="hidden" name="midtrans_order_id" id="midtrans_order_id" value="" />
                    <input type="hidden" name="midtrans_transaction_token" id="midtrans_transaction_token" value="" />

                    <input type="hidden" id="paytm_transaction_token" name="paytm_transaction_token" value="" />
                    <input type="hidden" id="paytm_order_id" name="paytm_order_id" value="" />

                    <input type="hidden" name="paystack_reference" id="paystack_reference" value="" />

                    <input type="hidden" name="stripe_client_secret" id="stripe_client_secret" value="" />
                    <input type="hidden" name="stripe_payment_id" id="stripe_payment_id" value="" />

                    <input type="hidden" name="my_fatoorah_order_id" id="my_fatoorah_order_id" value="" />
                    <input type="hidden" name="my_fatoorah_session_id" id="my_fatoorah_session_id" value="" />
                    <input type="hidden" name="my_fatoorah_payment_id" id="my_fatoorah_payment_id" value="" />

                    <input type="hidden" name="flutterwave_public_key" id="flutterwave_public_key" value="<?= $payment_methods['flutterwave_public_key'] ?>" />
                    <input type="hidden" id="flutterwave_currency" value="<?= $payment_methods['flutterwave_currency_code'] ?>" />
                    <input type="hidden" name="flutterwave_transaction_id" id="flutterwave_transaction_id" value="" />
                    <input type="hidden" name="flutterwave_transaction_ref" id="flutterwave_transaction_ref" value="" />
                    <input type="hidden" name="promo_set" id="promo_set" value="" />
                </div>
                <div class="col-xl-4 mt-5">
                    <div class="checkout-order-wrapper">
                        <div class="checkout-title">
                            <h1><?= !empty($this->lang->line('order_summary')) ? $this->lang->line('order_summary') : 'Order Summary' ?></h1>
                        </div>
                        <div class="order-details">
                            <div class="product-checkout-wrapper">
                                <div class="product-checkout-title">
                                    <h2 class="clearfix mb-0 text-muted">
                                        <a class="#"><?= isset($cart[0]['cart_count']) && !empty($cart[0]['cart_count']) ? $cart[0]['cart_count'] : 0 ?> Item(s) in Cart</a>
                                    </h2>
                                </div>
                                <div>
                                    <div class="product-checkout mt-4">
                                        <?php if (isset($cart) && !empty($cart)) {
                                            if ($cart[0]['type'] != 'digital_product') {
                                                $product_not_delivarable = array_column($product_not_delivarable, "product_id");
                                            }

                                            foreach ($cart as $row) {
                                                if (isset($row['qty']) && $row['qty'] != 0) {
                                                    $price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 && $row['special_price'] < $row['price'] ? $row['special_price'] : $row['price'];
                                                    $amount = $row['qty'] * $price;
                                        ?>
                                                    <div class="border-line">
                                                        <span class="product-wrap">
                                                            <div class="widget-image">
                                                                <a href="<?= base_url("products/details/" . $row['slug']) ?>">
                                                                    <img src="<?= $row['image_sm'] ?>" alt="">
                                                                </a>
                                                            </div>
                                                            <!-- checking product deliverable or not  -->
                                                            <span class="product-info text-left">
                                                                <a href="<?= base_url("products/details/" . $row['slug']) ?>" class="product-title text-muted"><?= output_escaping(str_replace('\r\n', '&#13;&#10;', $row['name'])) ?></a>
                                                                <?php if ($cart[0]['type'] != 'digital_product') { ?>
                                                                    <div id="p_<?= $row['product_id'] ?>" class="text-danger deliverable_status"><?= (isset($default_address) && !empty($default_address) && in_array($row['product_id'], $product_not_delivarable)) ? "Not deliverable" : "" ?></div>
                                                                <?php } ?>
                                                                <?php if (!empty($row['product_variants'])) { ?>
                                                                    <?= str_replace(',', ' | ', $row['product_variants'][0]['variant_values']) ?>
                                                                <?php } ?>
                                                                <div class="qty">
                                                                    <span class="text-muted"><?= !empty($this->lang->line('qty')) ? $this->lang->line('qty') : 'Qty' ?> :</span>
                                                                    <span class="text-muted"><?= $row['qty'] ?></span>
                                                                </div>
                                                                <?php if (isset($row['item_tax_percentage']) && !empty($row['item_tax_percentage'])) { ?>
                                                                    <div>
                                                                        <span class="text-muted"><?= !empty($this->lang->line('net_amountD')) ? $this->lang->line('net_amount') : 'Net Amount' ?> :<?= $settings['currency'] ?><?= number_format((($amount) - (calculate_tax_inclusive(($amount), $row['item_tax_percentage']))), 2) ?></i></span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-muted"><?= !empty($row['tax_title']) ? $row['tax_title'] : 'Tax' ?> :</span>
                                                                        <span class="text-muted"><?= $settings['currency'] ?><?= number_format(calculate_tax_inclusive(($amount), $row['item_tax_percentage']), 2) ?></span>

                                                                    </div>
                                                                <?php } ?>
                                                            </span>
                                                            <span class="item-price text-muted"><?= $settings['currency'] ?></i> <?= number_format($row['qty'] * $price, 2) ?></span>
                                                        </span>
                                                    </div>
                                        <?php }
                                            }
                                        } ?>
                                    </div>
                                </div>
                                <input type="hidden" id="sub_total" value="<?= $cart['sub_total'] ?>">
                                <div class="cart-total-price">
                                    <table class="table cart-products-table">
                                        <tbody>
                                            <tr>
                                                <td class="text-muted"><?= !empty($this->lang->line('subtotal')) ? $this->lang->line('subtotal') : 'Subtotal' ?></td>
                                                <td class="text-muted"><?= $settings['currency'] . ' <span class="sub_total">' . number_format($cart['sub_total'], 2) . '</span>' ?></td>
                                            </tr>


                                            <?php if (!empty($cart['tax_percentage'])) { ?>
                                                <tr class="cart-product-tax d-none">
                                                    <td class="text-muted"><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?> (<?= $cart['tax_percentage'] ?>%)</td>
                                                    <td class="text-muted"><?= $settings['currency'] . ' ' . number_format($cart['tax_amount'], 2) ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if ($cart[0]['type'] != 'digital_product') { ?>
                                                <tr>
                                                    <td class="text-muted"><?= !empty($this->lang->line('delivery_charge')) ? $this->lang->line('delivery_charge') : 'Delivery Charge' ?></td>
                                                    <td class="text-muted"><?= $settings['currency'] . ' ' ?><span class="delivery-charge"><?= $cart['delivery_charge'] ?></span></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td class="text-muted"><?= !empty($this->lang->line('wallet')) ? $this->lang->line('wallet') : 'Wallet' ?></td>
                                                <td class="text-muted"><?= $settings['currency'] ?> <span class="wallet_used">0.00<span></td>

                                            </tr>
                                            <tr id="promocode_div" class="d-none">
                                                <td class="text-muted"><?= !empty($this->lang->line('promocode')) ? $this->lang->line('promocode') : 'Promocode' ?> <span id="promocode"></span></td>
                                                <td class="text-muted"> <i><?= $settings['currency'] ?></i> <span id="promocode_amount"></span></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                            <tr class="total-price">
                                                <td><?= !empty($this->lang->line('total')) ? $this->lang->line('total') : 'Total' ?></td>
                                                <td><?= $settings['currency'] ?> <span id="final_total"></span></td>
                                            </tr>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="input-group">
                                    <a href="#" data-izimodal-open=".promo_code_modal" class="mb-4 pl-0"><?= !empty($this->lang->line('see_all_offers')) ? $this->lang->line('see_all_offers') : 'See All Offers' ?>(%)</i></a>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Promo code" id="promocode_input">
                                    <div class="input-group-append">
                                        <button class="button button-primary" id="redeem_btn"><?= !empty($this->lang->line('redeem')) ? $this->lang->line('redeem') : 'Redeem' ?></button>
                                        <button class="button button-danger d-none" id="clear_promo_btn"><?= !empty($this->lang->line('clear')) ? $this->lang->line('clear') : 'Clear' ?></button>
                                    </div>
                                </div>
                                <?php $is_disabled = false;
                                foreach ($product_not_delivarable as $p_id) {
                                    if (!empty($p_id['product_id'])) {
                                        $is_disabled = true;
                                        continue;
                                    }
                                } ?>
                                <button class="block" id="place_order_btn" type="submit" <?= ($is_disabled) ? "disabled" : ""; ?>><?= !empty($this->lang->line('place_order')) ? $this->lang->line('place_order') : 'Place Order' ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
</section>

<form action="<?= base_url('payment/paypal') ?>" id="paypal_form" method="POST">
    <input type="hidden" id="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="order_id" id="paypal_order_id" value="" />
</form>
<input type="hidden" name="stripe_key_id" id="stripe_key_id" value="<?= $payment_methods['stripe_publishable_key'] ?>" />
<input type="hidden" name="razorpay_key_id" id="razorpay_key_id" value="<?= $payment_methods['razorpay_key_id'] ?>" />
<input type="hidden" name="paystack_key_id" id="paystack_key_id" value="<?= $payment_methods['paystack_key_id'] ?>" />
<input type="hidden" id="delivery_starts_from" value="<?= (isset($time_slot_config['delivery_starts_from']) ? $time_slot_config['delivery_starts_from'] : '') ?>">
<input type="hidden" id="delivery_ends_in" value="<?= (isset($time_slot_config['allowed_days']) ? $time_slot_config['allowed_days'] : '') ?>">
<div id="modal-custom" class="address-modal" data-iziModal-group="grupo1">
    <button data-iziModal-close class="icon-close">x</button>
    <section id="address_form">
        <div class="h4"><?= !empty($this->lang->line('shipping_address')) ? $this->lang->line('shipping_address') : 'Shipping Address' ?></div>
        <ul id="address-list"></ul>
        <div class="col-12 text-right mt-2">
            <a target="_blank" href="<?= base_url('my-account/manage-address') ?>"><?= !empty($this->lang->line('create_a_new_address')) ? $this->lang->line('create_a_new_address') : 'Create a New Address' ?></a>
        </div>
        <footer class="mt-4">
            <button data-iziModal-close><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
            <button class="submit" id="select_address"><?= !empty($this->lang->line('save')) ? $this->lang->line('save') : 'Save' ?></button>
        </footer>
    </section>
</div>
<div id="modal-custom" class="promo_code_modal" data-iziModal-group="grupo1">
    <button data-iziModal-close class="icon-close">x</button>
    <section id="promo_code_form">
        <div class="h4"><?= !empty($this->lang->line('promocodes')) ? $this->lang->line('promocodes') : 'Promocodes' ?></div>
        <ul id="promocode-list"></ul>
    </section>
</div>
<?php if (isset($payment_methods['paytm_payment_method']) && $payment_methods['paytm_payment_method'] == 1) {
    $url = ($payment_methods['paytm_payment_mode'] == "production") ? "https://securegw.paytm.in/" : "https://securegw-stage.paytm.in/";
?>
    <script type="application/javascript" src="<?= $url ?>merchantpgpui/checkoutjs/merchants/<?= $payment_methods['paytm_merchant_id'] ?>.js"></script>
<?php } ?>

<script src="https://checkout.flutterwave.com/v3.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://demo.myfatoorah.com/cardview/v2/session.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<?php
$midtrans_url = $midtrans_client_key = "";
if (isset($payment_methods['midtrans_payment_mode'])) {
    $midtrans_url = (isset($payment_methods['midtrans_payment_mode']) && $payment_methods['midtrans_payment_mode'] == "sandbox") ? "https://app.sandbox.midtrans.com/snap/snap.js" : "https://app.midtrans.com/snap/snap.js";
    $midtrans_client_key = $payment_methods['midtrans_client_key'];
}

?>
<script type="text/javascript" src="<?= $midtrans_url ?>" data-client-key="<?= $midtrans_client_key ?>"></script>

<script src="<?= THEME_ASSETS_URL . '/js/checkout.js' ?>"></script>