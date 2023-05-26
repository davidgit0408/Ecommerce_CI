<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('delivery_boy/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
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
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <input type="hidden" name="hidden" id="order_id" value="<?php echo $order_detls[0]['id']; ?>">
                                    <th class="w-10px">ID</th>
                                    <td><?php echo $order_detls[0]['id']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Name</th>
                                    <td><?php echo $order_detls[0]['uname']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Email</th>
                                    <td><?= (ALLOW_MODIFICATION == 0 && !defined(ALLOW_MODIFICATION)) ? str_repeat("X", strlen($order_detls[0]['email']) - 3) . substr($order_detls[0]['email'], -3) : $order_detls[0]['email']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Contact</th>
                                    <td><?= (ALLOW_MODIFICATION == 0 && !defined(ALLOW_MODIFICATION)) ? str_repeat("X", strlen($order_detls[0]['mobile']) - 3) . substr($order_detls[0]['mobile'], -3) : $order_detls[0]['mobile']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Items</th>
                                    <td><?php $total = 0;
                                        $tax_amount = 0;
                                        echo '<div class="container-fluid row">';
                                        foreach ($items as $item) {
                                            $item['discounted_price'] = ($item['discounted_price'] == '') ? 0 : $item['discounted_price'];
                                            $total += $subtotal = ($item['quantity'] != 0 && ($item['discounted_price'] != '' && $item['discounted_price'] > 0) && $item['price'] > $item['discounted_price']) ? ($item['price'] - $item['discounted_price']) : ($item['price'] * $item['quantity']);
                                            $tax_amount += $item['tax_amount'];
                                        ?>

                                            <div class=" card col-md-3 col-sm-12 p-3 mb-5 bg-white rounded m-4 grow">
                                                <div class="row mb-1">
                                                    <div class="col-md-7 text-center"><select class="form-control-sm w-100">
                                                            <option value="processed" <?= (strtolower($item['active_status']) == 'processed') ? 'selected' : '' ?>>Processed</option>
                                                            <option value="shipped" <?= (strtolower($item['active_status']) == 'shipped') ? 'selected' : '' ?>>Shipped</option>
                                                            <option value="delivered" <?= (strtolower($item['active_status']) == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                                                            <option value="returned" <?= (strtolower($item['active_status']) == 'returned') ? 'selected' : '' ?>>Return</option>
                                                            <option value="cancelled" <?= (strtolower($item['active_status']) == 'cancelled') ? 'selected' : '' ?>>Cancel</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5 d-flex align-items-center"><a href="javascript:void(0);" title="Update status" data-id=' <?= $item['id'] ?> ' data-otp-system='<?= $order_detls[0]['item_otp'] != 0 ? '1' : '0' ?>' class="btn btn-primary btn-xs update_status_delivery_boy mr-1"><i class="far fa-arrow-alt-circle-up"></i></a></div>
                                                </div>
                                                <div class="order-product-image">
                                                    <a href='<?= base_url() . $item['product_image'] ?>' data-toggle='lightbox' data-gallery='order-images'> <img src='<?= base_url() . $item['product_image'] ?>' class='h-75'></a>
                                                </div>
                                                <div><span class="text-bold">Product Type : </span><small><?= ucwords(str_replace('_', ' ', $item['product_type'])); ?> </small></div>
                                                <div><span class="text-bold">Variant ID : </span><?= $item['product_variant_id'] ?> </div>
                                                <?php if (isset($item['product_variants']) && !empty($item['product_variants'])) { ?>
                                                    <div><span class="text-bold">Variants : </span><?= str_replace(',', ' | ', $item['product_variants'][0]['variant_values']) ?> </div>
                                                <?php } ?>
                                                <div><span class="text-bold">Name : </span><small><?= $item['pname'] ?> </small></div>
                                                <div><span class="text-bold">Quantity : </span><?= $item['quantity'] ?> </div>
                                                <div><span class="text-bold">Price : </span><?= $item['price'] ?></div>
                                                <div><span class="text-bold">Discounted Price : </span> <?= $item['discounted_price'] ?> </div>
                                                <div><span class="text-bold">Subtotal : </span><?= $subtotal ?> </div>
                                                <?php $badge = 'danger';
                                                $badges = ["awaiting" => "secondary", "received" => "primary", "processed" => "info", "shipped" => "warning", "delivered" => "success", "returned" => "danger", "cancelled" => "danger"]
                                                ?>
                                                <?php if (isset($item['updated_by'])) { ?>
                                                    <div><span class="text-bold">Updated By : </span><?= $item['updated_by'] ?> </div>
                                                <?php } ?>
                                                <div><span class="text-bold">Active Status : </span> <span class="badge badge-<?= $badges[$item['active_status']] ?>"> <small><?= $item['active_status'] ?></small></span></div>
                                            </div>
                                        <?php

                                        }
                                        echo '</div>';
                                        ?>
                                        <div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Total(<?= $settings['currency'] ?>)</th>
                                    <td id='amount'><?php echo $total; ?></td>
                                </tr>

                                <tr>
                                    <th class="w-10px">Tax(<?= $settings['currency'] ?>)</th>
                                    <td id='amount'><?php echo $tax_amount;
                                                    ?></td>
                                </tr>

                                <tr>
                                    <th class="w-10px">Delivery Charge(<?= $settings['currency'] ?>)</th>
                                    <td id='delivery_charge'><?php echo $order_detls[0]['delivery_charge'];
                                                                $total = $total + $order_detls[0]['delivery_charge']; ?></td>
                                </tr>

                                <tr>
                                    <th class="w-10px">Wallet Balance(<?= $settings['currency'] ?>)</th>
                                    <td><?php echo $order_detls[0]['wallet_balance'];
                                        $total = $total - $order_detls[0]['wallet_balance']; ?></td>
                                </tr>

                                <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $order_detls[0]['order_total'] + $order_detls[0]['delivery_charge'] ?>">
                                <input type="hidden" name="final_amount" id="final_amount" value="<?php echo $order_detls[0]['final_total']; ?>">

                                <tr>
                                    <th class="w-10px">Discount %</th>
                                    <td>
                                        <?= $order_detls[0]['discount']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Promo Code Discount (<?= $settings['currency'] ?>)</th>
                                    <td><?php echo $order_detls[0]['promo_discount'];
                                        $total = floatval($total - $order_detls[0]['promo_discount']); ?></td>
                                </tr>
                                <?php
                                if (isset($order_detls[0]['discount']) && $order_detls[0]['discount'] > 0) {
                                    $discount = $order_detls[0]['total_payable']  *  ($order_detls[0]['discount'] / 100);
                                    $total = round($order_detls[0]['total_payable'] - $discount, 2);
                                } ?>
                                <tr>
                                    <th class="w-10px">Payable Total(<?= $settings['currency'] ?>)</th>
                                    <td><?= $total ?></td>
                                </tr>
                                <tr>
                                    <th>Deliver By</th>
                                    <td>
                                        <?php
                                        $delivery_res = fetch_details('users', ['id' => $order_detls[0]['delivery_boy_id']], 'username');
                                        echo $delivery_res[0]['username'];
                                        ?>

                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="w-10px">Payment Method</th>
                                    <td><?php echo $order_detls[0]['payment_method']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Address</th>
                                    <td><?php echo $order_detls[0]['address']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Delivery Date & Time</th>
                                    <td><?php echo date('d-M-Y', strtotime($order_detls[0]['delivery_date'])); ?> - <?= $order_detls[0]['delivery_time'] ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Order Date</th>
                                    <td><?php echo date('d-m-Y', strtotime($order_detls[0]['date_added'])); ?></td>
                                </tr>

                            </table>
                        </div>

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