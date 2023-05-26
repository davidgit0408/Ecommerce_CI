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
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home'); ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Invoice</li>
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
                    <div class="card card-info " id="section-to-print">
                        <div class="row m-3">
                            <div class="col-md-12 d-flex justify-content-between">
                                <h2 class="text-left">
                                    <img src="<?= base_url()  . get_settings('logo') ?>" class="d-block" style="max-width: 250px;max-height: 100px;">
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
                                    <br> <b>No : </b>#
                                    <?= $order_detls[0]['id'] ?>
                                    <br> <b>Date: </b>
                                    <?= $order_detls[0]['date_added'] ?>
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
                                            <th>variants</th>
                                            <th>HSN Code</th>
                                            <th>Price</th>
                                            <th>Tax (%)</th>
                                            <th class="d-none">Tax Amount (
                                                <?= $settings['currency'] ?>)</th>
                                            <th>Qty</th>
                                            <th>SubTotal (
                                                <?= $settings['currency'] ?>)</th>
                                            <th class="d-none">Order Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        $total = $quantity = $total_tax = $total_discount = $cal_final_total = 0;
                                        foreach ($items as $row) {

                                            $product_variants = get_variants_values_by_id($row['product_variant_id']);
                                            $product_variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                                            $tax_amount = ($row['tax_amount']) ? $row['tax_amount'] : '0';
                                            $hsn_code = ($row['hsn_code']) ? $row['hsn_code'] : '-';
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
                                                    <?= $row['product_variant_id'] ?>
                                                    <br>
                                                </td>
                                                <td class="w-25">
                                                    <?= $row['pname'] ?>
                                                    <br>
                                                </td>
                                                <td class="w-25">
                                                    <?= $product_variants ?>
                                                    <br>
                                                </td>
                                                <td><?= $hsn_code ?><br></td>
                                                <td>
                                                    <?= $settings['currency'] . ' ' . number_format($price_with_tax, 2) ?>
                                                    <br>
                                                </td>

                                                <td>
                                                    <?= ($row['tax_percent']) ? $row['tax_percent'] : '0' ?>
                                                    <br>
                                                </td>
                                                <td class="d-none">
                                                    <?= $tax_amount ?>
                                                    <br>
                                                </td>
                                                <td>
                                                    <?= $row['quantity'] ?>
                                                    <br>
                                                </td>
                                                <td>
                                                    <?= $settings['currency'] . ' ' . number_format($sub_total, 2) ?>
                                                    <br>
                                                </td>
                                                <td class="d-none">
                                                    <?= $row['active_status'] ?>
                                                    <br>
                                                </td>
                                            </tr>
                                        <?php $i++;
                                            $cal_final_total += ($sub_total);
                                        }

                                        ?>
                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Total</th>
                                            <th class="d-none">
                                                <?= $total_tax ?>
                                            </th>
                                            <th>
                                                <?= $quantity ?>
                                                <br>
                                            </th>
                                            <th>
                                                <?= $settings['currency'] . ' ' . number_format($cal_final_total, 2);  ?>
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
                                                <th>Total Order Price (
                                                    <?= $settings['currency'] ?>)</th>
                                                <td>+
                                                    <?= number_format($cal_final_total, 2) ?>
                                                </td>
                                            </tr>
                                            <?php if ($order_detls[0]['type'] != 'digital_product') { ?>
                                                <tr>
                                                    <th>Delivery Charge (
                                                        <?= $settings['currency'] ?>)</th>
                                                    <td>+
                                                        <?php $cal_final_total += $order_detls[0]['delivery_charge'];
                                                        echo number_format($order_detls[0]['delivery_charge'], 2); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr class="d-none">
                                                <th>Tax
                                                    <?= $settings['currency'] ?></th>
                                                <td>+
                                                    <?php echo $total_tax; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Wallet Used (
                                                    <?= $settings['currency'] ?>)</th>
                                                <td><?php $cal_final_total -= $order_detls[0]['wallet_balance'];
                                                    echo  '- ' . $order_detls[0]['wallet_balance']; ?> </td>
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
                                                <th>Total Payable (
                                                    <?= $settings['currency'] ?>)</th>
                                                <td>
                                                    <?= $settings['currency'] . '  ' . number_format($cal_final_total, 2) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Final Total (
                                                    <?= $settings['currency'] ?>)</th>
                                                <td>

                                                    <?= $settings['currency'] . '  ' . number_format($cal_final_total, 2); ?>
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