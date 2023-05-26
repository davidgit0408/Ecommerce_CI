<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Invoice</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home'); ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Invoice</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <?php $sellers = array_values(array_unique(array_column($order_detls, "seller_id"))); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info " id="section-to-print">
                        <div class="row m-3">
                            <div class="col-md-12 d-flex justify-content-between">
                                <h2 class="text-left">
                                    <img src="<?= base_url()  . get_settings('logo') ?>" class="d-block" style="max-width: 250px;max-height: 100px;">
                                </h2>
                                <h2 class="text-right">
                                    Mo. <?= $settings['support_number'] ?>
                                </h2>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row m-3 d-flex justify-content-between">
                            <div class="col-sm-4 invoice-col">From <address>
                                    <strong><?= $settings['app_name'] ?></strong><br>
                                    Email: <?= $settings['support_email'] ?><br>
                                    Customer Care : <?= $settings['support_number'] ?><br>
                                    <?php if (isset($settings['tax_name']) && !empty($settings['tax_name'])) { ?>
                                        <b><?= $settings['tax_name'] ?></b> : <?= $settings['tax_number'] ?><br>
                                    <?php } ?>
                                    <?php if (!empty($items[0]['delivery_boy'])) { ?>Delivery By: <?= $items[0]['delivery_boy'] ?><?php } ?>

                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">Shipping Address <address>
                                    <strong><?= ($order_detls[0]['user_name'] != "") ? $order_detls[0]['user_name'] : $order_detls[0]['uname'] ?></strong><br>
                                    <?= $order_detls[0]['address'] ?><br>
                                    <strong><?= (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($order_detls[0]['mobile']) - 3) . substr($order_detls[0]['mobile'], -3) : $order_detls[0]['mobile']; ?></strong><br>
                                    <strong><?= (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($order_detls[0]['email']) - 3) . substr($order_detls[0]['email'], -3) : $order_detls[0]['email']; ?></strong><br>
                                </address>
                            </div>
                            <!-- /.col -->
                            <?php if (!empty($order_detls[0]['id'])) { ?>
                                <div class="col-sm-2 invoice-col">
                                    <br> <b>Retail Invoice</b>
                                    <br> <b>No : </b>#
                                    <?= $order_detls[0]['id'] ?>
                                    <br> <b>Date: </b>
                                    <?= $order_detls[0]['date_added'] ?>
                                    <br>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- /.row -->
                        <!-- Table row -->
                        <!-- seller container -->
                        <?php for ($i = 0; $i < count($sellers); $i++) {
                            $s_user_data = fetch_details('users', ['id' => $sellers[$i]], 'email,mobile,address,country_code');
                            $seller_data = fetch_details('seller_data', ['user_id' => $sellers[$i]], 'store_name,pan_number,tax_name,tax_number');
                        ?>
                            <div class="container-fluid bg-light">
                                <div class="row m-3">
                                    <div class="col-md-4">
                                        <p>Sold By</p>
                                        <strong><?= $seller_data[0]['store_name']; ?></strong>
                                        <p>Email: <?= $s_user_data[0]['email']; ?></p>
                                        <p> Customer Care : <?= $s_user_data[0]['mobile']; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>
                                            <?php if (isset($seller_data[0]['pan_number']) && !empty($seller_data[0]['pan_number'])) { ?>
                                                <p>Pan Number : <?= $seller_data[0]['pan_number']; ?></p>
                                            <?php } ?>
                                            <p><?= $seller_data[0]['tax_name']; ?> : <?= $seller_data[0]['tax_number']; ?></p>
                                        </strong>
                                        <?php if ($order_detls[0]['type'] != 'digital_product') { ?>
                                            <p>Delivery By : <?= $items[$i]['delivery_boy']; ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row m-3">
                                    <p>Product Details:</p>
                                </div>
                                <?php
                                if ($sellers[$i] == $items[$i]['seller_id']) { ?>
                                    <div class="row m-3">
                                        <div class="col-xs-12 table-responsive">
                                            <table class="table borderless text-center text-sm">
                                                <thead class="">
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>Product Code</th>
                                                        <th>Name</th>
                                                        <th>variants</th>
                                                        <th>HSN Code</th>
                                                        <th>Price</th>
                                                        <th>Tax (%)</th>
                                                        <th class="d-none">Tax Amount (₹)</th>
                                                        <th>Qty</th>
                                                        <th>SubTotal (₹)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $j = 1;
                                                    $total = $quantity = $total_tax = $total_discount = $final_sub_total = 0;
                                                    foreach ($items as $row) {

                                                        $total += floatval($row['price'] + $tax_amount) * floatval($row['quantity']);
                                                        if ($sellers[$i] == $row['seller_id']) {
                                                            $product_variants = get_variants_values_by_id($row['product_variant_id']);
                                                            $product_variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                                                            $tax_amount = ($row['tax_amount']) ? $row['tax_amount'] : '0';
                                                            $hsn_code = ($row['hsn_code']) ? $row['hsn_code'] : '-';
                                                            $quantity += floatval($row['quantity']);
                                                            $total_tax += floatval($row['tax_amount']);
                                                            // $price_with_tax = $row['price'] - $tax_amount;
                                                            $price_with_tax = $row['price'];
                                                            $sub_total = floatval($row['price']) * $row['quantity'];
                                                            $final_sub_total += $sub_total;
                                                    ?>
                                                            <tr>
                                                                <td><?= $j ?><br></td>
                                                                <td><?= $row['product_variant_id'] ?><br></td>
                                                                <td class="w-25"><?= $row['pname'] ?><br></td>
                                                                <td class="w-25"><?= $product_variants ?><br></td>
                                                                <td><?= $hsn_code ?><br></td>
                                                                <td><?= $settings['currency'] . ' ' . number_format($price_with_tax, 2) ?><br></td>
                                                                <td><?= ($row['tax_percent']) ? $row['tax_percent'] : '0' ?><br></td>
                                                                <td class="d-none"><?= $tax_amount ?><br></td>
                                                                <td><?= $row['quantity'] ?><br></td>
                                                                <td><?= $settings['currency'] . ' ' . number_format($sub_total, 2); ?><br></td>
                                                                <td class="d-none"><?= $row['active_status'] ?><br></td>
                                                            </tr>
                                                    <?php $j++;
                                                        }
                                                    } ?>
                                                </tbody>
                                                <tbody>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th> <?= $quantity ?>
                                                            <br>
                                                        </th>
                                                        <th> <?= $settings['currency'] . ' ' . number_format($final_sub_total, 2) ?><br></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                <?php } ?>
                            </div>
                            <hr>
                        <?php } ?>
                        <!-- seller container finished -->
                        <div class="row m-3">
                            <p><b>Payment Method : </b> <?= $order_detls[0]['payment_method'] ?></p>
                        </div>
                        <!-- /.row -->
                        <div class="row m-2 text-right">
                            <!-- accepted payments column -->
                            <div class="col-md-9 offset-md-2">
                                <!--<p class="lead">Payment Date: </p>-->
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th>Total Order Price (
                                                    <?= $settings['currency'] ?>)</th>
                                                <td>+
                                                    <?= number_format($final_sub_total, 2) ?>
                                                </td>
                                            </tr>
                                            <?php if ($order_detls[0]['type'] != 'digital_product') { ?>
                                            <tr>
                                                <th>Delivery Charge (
                                                    <?= $settings['currency'] ?>)</th>
                                                <td>+
                                                    <?php $total += $order_detls[0]['delivery_charge'];
                                                    echo number_format($order_detls[0]['delivery_charge'], 2); ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr class="d-none">
                                                <th>Tax
                                                    <?= $settings['currency'] ?></th>
                                                <td>+
                                                    echo $total_tax; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Wallet Used (
                                                    <?= $settings['currency'] ?>)</th>
                                                <td><?php $total -= $order_detls[0]['wallet_balance'];
                                                    echo  '- ' . number_format($order_detls[0]['wallet_balance'], 2); ?> </td>
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
                                                        $total = $total - $order_detls[0]['promo_discount'];
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
                                                        <?php echo $special_discount = round($total * $order_detls[0]['discount'] / 100, 2);
                                                        $total = floatval($total - $special_discount);
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr class="d-none">
                                                <th>Total Payable (
                                                    <?= $settings['currency'] ?>)</th>
                                                <td>
                                                    <?= $settings['currency'] . '  ' . $total ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Final Total (
                                                    <?= $settings['currency'] ?>)</th>
                                                <td>
                                                    <?php $final_total = $order_detls[0]['final_total'] - $order_detls[0]['wallet_balance']  - $order_detls[0]['discount']; ?>
                                                    <?= $settings['currency'] . '  ' . number_format($final_total, 2) ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        <!-- this row will not appear when printing -->
                        <div class="row m-3" id="section-not-to-print">
                            <div class="col-xs-12">
                                <button type='button' value='Print this page' onclick='{window.print()};' class="btn btn-default"><i class="fa fa-print"></i> Print</button>
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