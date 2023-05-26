<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info " id="section-to-print">
                    <div class="row m-3">
                        <div class="col-md-12 d-flex justify-content-between">
                            <h2 class="text-left">
                                <img src="<?= base_url()  . get_settings('logo') ?>" class="d-block " style="max-width:250px;max-height:100px;">
                            </h2>
                            <h2 class="text-right">
                                Mo. <?= (isset($s_user_data[0]['country_code']) && !empty($s_user_data[0]['country_code']))  ? "+" . $s_user_data[0]['country_code'] . " " . $s_user_data[0]['mobile'] : "+91 " . $s_user_data[0]['mobile'] ?>
                            </h2>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row m-3 d-flex justify-content-between">
                        <div class="col-sm-4 invoice-col">Sold By <address>
                                <strong><?= $settings['app_name'] ?></strong><br>
                                Email: <?= $s_user_data[0]['email'] ?><br>
                                Customer Care : <?= (isset($s_user_data[0]['country_code']) && !empty($s_user_data[0]['country_code'])) ? "+" . $s_user_data[0]['country_code'] . " " . $s_user_data[0]['mobile'] : "+91 " . $s_user_data[0]['mobile'] ?><br>
                                <?php if (isset($seller_data[0]['store_name']) && !empty($seller_data[0]['store_name'])) { ?>
                                    <b>Store Name</b> : <?= $seller_data[0]['store_name'] ?><br>
                                <?php } ?>
                                <?php if (!empty($items[0]['delivery_boy'])) { ?>Delivery By: <?= $items[0]['delivery_boy'] ?><?php } ?><br>
                                Address : <?= $s_user_data[0]['address']; ?>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">Shipping Address <address>
                                <strong><?= ($order_detls[0]['user_name'] != "") ? $order_detls[0]['user_name'] : $order_detls[0]['uname'] ?></strong><br>
                                <?= $order_detls[0]['address'] ?><br>
                                <strong><?= ((!defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) || ($this->ion_auth->is_seller() && get_seller_permission($seller_id, 'customer_privacy') == false)) ? str_repeat("X", strlen($order_detls[0]['mobile']) - 3) . substr($order_detls[0]['mobile'], -3) : $order_detls[0]['mobile']; ?></strong><br>
                                <strong><?= ((!defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) || ($this->ion_auth->is_seller() && get_seller_permission($seller_id, 'customer_privacy') == false)) ? str_repeat("X", strlen($order_detls[0]['email']) - 3) . substr($order_detls[0]['email'], -3) : $order_detls[0]['email']; ?></strong><br>
                            </address>
                        </div>
                        <!-- /.col -->
                        <?php if (!empty($order_detls[0]['id'])) { ?>
                            <div class="col-sm-2 invoice-col">
                                <br> <b>Retail Invoice</b>
                                <br> <b>No : </b>#<?= $order_detls[0]['id'] ?>
                                <br> <b>Date: </b><?= $order_detls[0]['date_added'] ?>
                                <br>
                                <?php if (isset($seller_data[0]['tax_name']) && !empty($seller_data[0]['tax_name']) && isset($seller_data[0]['tax_number']) && !empty($seller_data[0]['tax_number'])) { ?>
                                    <b><?= $seller_data[0]['tax_name'] ?></b> : <?= $seller_data[0]['tax_number'] ?><br>
                                <?php } ?>
                                <?php if (isset($seller_data[0]['pan_number']) && !empty($seller_data[0]['pan_number'])) { ?>
                                    <b>PAN NO.</b> : <?= $seller_data[0]['pan_number'] ?><br>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- /.row -->
                    <!-- Table row -->
                    <div class="row m-3">
                        <div class="col-xs-12 table-responsive">
                            <table class="table borderless text-center text-sm">
                                <thead class="">
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Product Code</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Tax (%)</th>
                                        <th>Qty</th>
                                        <th class="d-none">Tax Amount (<?= $settings['currency'] ?>)</th>
                                        <th>SubTotal (<?= $settings['currency'] ?>)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    $total = $quantity = $total_tax = $total_discount = $cal_final_total = 0;

                                    foreach ($items as $row) {
                                        $tax_amount = ($row['tax_amount']) ? $row['tax_amount'] : '0';

                                        $total += floatval($row['price'] + $tax_amount) * floatval($row['quantity']);
                                        $quantity += floatval($row['quantity']);
                                        $total_tax += floatval($row['tax_amount']);
                                        $price_with_tax = $row['price'] + $tax_amount;
                                        $sub_total = floatval($row['price']) * $row['quantity'] + $tax_amount;

                                    ?>
                                        <tr>
                                            <td>
                                                <?= $i ?>
                                                <br>
                                            </td>
                                            <td>
                                                <?= $row['product_variant_id'] ?><br>
                                            </td>
                                            <td class="w-25">
                                                <?= $row['pname'] ?>
                                                <br>
                                            </td>
                                            <td>
                                                <?= $settings['currency'] . ' ' . number_format($row['price'], 2) ?>
                                                <br>
                                            </td>

                                            <td>
                                                <?= ($row['tax_percent']) ? $row['tax_percent'] : '0' ?>
                                                <br>
                                            </td>
                                            <td>
                                                <?= $row['quantity'] ?>
                                                <br>
                                            </td>
                                            <td class="d-none">
                                                <?= $tax_amount ?>
                                                <br>
                                            </td>
                                            <td>
                                                <?= $settings['currency'] . ' ' . $sub_total ?>
                                                <br>
                                            </td>
                                        </tr>
                                    <?php $i++;
                                        $cal_final_total += ($row['quantity'] * $sub_total);
                                    } ?>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Total</th>
                                        <th>
                                            <?= $quantity ?><br>
                                        </th>
                                        <th>
                                            <?= $settings['currency'] . ' ' . number_format($cal_final_total, 2)  ?>
                                            <br>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="row m-2 text-right">
                        <!-- accepted payments column -->
                        <div class="col-md-9 offset-md-2">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th>Total Order Price</th>
                                            <td>+
                                                <?= $settings['currency'] . ' ' . number_format($cal_final_total, 2) ?>
                                            </td>
                                        </tr>
                                        <?php if ($order_detls[0]['type'] != 'digital_product') { ?>
                                            <tr>
                                                <th>Delivery Charge</th>
                                                <td>+
                                                    <?php $cal_final_total += $order_detls[0]['delivery_charge'];
                                                    echo $settings['currency'] . ' ' . number_format($order_detls[0]['delivery_charge'], 2); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="d-none">
                                            <th>Tax - (<?= $items[0]['tax_percent'] ?>%)</th>
                                            <td>+
                                                <?php
                                                echo $settings['currency'] . ' ' . number_format($total_tax, 2); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Wallet Used</th>
                                            <td>-
                                                <?php $cal_final_total -= $order_detls[0]['wallet_balance'];
                                                echo  $settings['currency'] . ' ' . number_format($order_detls[0]['wallet_balance'], 2); ?>
                                            </td>
                                        </tr>
                                        <?php
                                        if (isset($promo_code[0]['promo_code'])) { ?>
                                            <tr>
                                                <th>Promo (
                                                    <?= $promo_code[0]['promo_code'] ?>) Discount (
                                                    <?= floatval($promo_code[0]['discount']); ?>
                                                    <?= ($promo_code[0]['discount_type'] == 'percentage') ? '%' : ' '; ?> )
                                                </th>
                                                <td>-
                                                    <?php
                                                    echo $order_detls[0]['promo_discount'];
                                                    $cal_final_total = $cal_final_total - $order_detls[0]['promo_discount'];
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                        if (isset($order_detls[0]['discount']) && $order_detls[0]['discount'] > 0 && $order_detls[0]['discount'] != NULL) { ?>
                                            <tr>
                                                <th>Special Discount
                                                    <?= $settings['currency'] ?>(<?= $order_detls[0]['discount'] ?> %)</th>
                                                <td>-
                                                    <?php echo $special_discount = round($cal_final_total * $order_detls[0]['discount'] / 100, 2);
                                                    $cal_final_total = floatval($cal_final_total - $special_discount);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr class="d-none">
                                            <th>Total Payable</th>
                                            <td>
                                                <?= $settings['currency'] . '  ' . number_format($cal_final_total, 2) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Final Total</th>
                                            <td>
                                                <?= $settings['currency'] . '  ' . number_format($cal_final_total, 2) ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <!--/.card-->
            </div>
            <!--/.col-md-12-->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    <?php if (isset($print_btn_enabled) && $print_btn_enabled) { ?>
        <div class="col-12">
            <div class="text-center">
                <button class="btn btn-primary" onclick="window.print();">Print</button>
            </div>
        </div>
    <?php } ?>
</section>