<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>View Order</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="transaction_modal" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="user_name">Order Tracking</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-info">
                                            <!-- form start -->
                                            <form class="form-horizontal " id="order_tracking_form" action="<?= base_url('admin/orders/update-order-tracking/'); ?>" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="order_id" id="order_id">
                                                <input type="hidden" name="order_item_id" id="order_item_id">
                                                <div class="card-body pad">
                                                    <div class="form-group ">
                                                        <label for="courier_agency">Courier Agency</label>
                                                        <input type="text" class="form-control" name="courier_agency" id="courier_agency" placeholder="Courier Agency" />
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="tracking_id">Tracking Id</label>
                                                        <input type="text" class="form-control" name="tracking_id" id="tracking_id" placeholder="Tracking Id" />
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="url">URL</label>
                                                        <input type="text" class="form-control" name="url" id="url" placeholder="URL" />
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="reset" class="btn btn-warning">Reset</button>
                                                        <button type="submit" class="btn btn-success" id="submit_btn">Save</button>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <div class="form-group" id="error_box">
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </form>
                                        </div>
                                        <!--/.card-->
                                    </div>
                                    <!--/.col-md-12-->
                                </div>
                                <!-- /.row -->

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                                    <td><?php echo "Account Holder Person : " . $order_detls[0]['uname'] . " | Order Recipient Person :  " . $order_detls[0]['user_name']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Email</th>
                                    <td><?= (!defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($order_detls[0]['email']) - 3) . substr($order_detls[0]['email'], -3) : $order_detls[0]['email']; ?></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Contact</th>
                                    <td><?= (!defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0)  ? str_repeat("X", strlen($order_detls[0]['mobile']) - 3) . substr($order_detls[0]['mobile'], -3) : $order_detls[0]['mobile']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Items</th>
                                    <td>
                                        <form id="update_form">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <?php $total = 0;
                                            $tax_amount = 0; ?>
                                            <div class="container-fluid">
                                                <?php if (isset($items[0]['product_type']) && $items[0]['product_type'] == 'digital_product') { ?>
                                                    <div class="row mb-5">
                                                        <div class="col-md-12 mb-2">
                                                            <lable class="badge badge-primary">Select status and square box of item which you want to update</lable>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select name="status" class="form-control status">
                                                                <option value=''>Select Status</option>
                                                                <option value="delivered">Delivered</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <a href="javascript:void(0);" title="Bulk Update" class="btn btn-primary col-sm-12 col-md-12 update_status_admin_bulk mr-1">
                                                                Bulk Update
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="row mb-5">
                                                        <div class="col-md-12 mb-2">
                                                            <lable class="badge badge-primary">Select status, delivery boy and square box of item which you want to update</lable>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select name="status" class="form-control status">
                                                                <option value=''>Select Status</option>
                                                                <option value="processed">Processed</option>
                                                                <option value="shipped">Shipped</option>
                                                                <option value="delivered">Delivered</option>
                                                                <option value="cancelled">Cancel</option>
                                                                <option value="returned">Returned</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select id='deliver_by' name='deliver_by' class='form-control ' required>
                                                                <option value=''>Select Delivery Boy</option>
                                                                <?php foreach ($delivery_res as $row) { ?>
                                                                    <option value="<?= $row['user_id'] ?>"><?= $row['username'] ?></option>
                                                                <?php  } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <a href="javascript:void(0);" title="Bulk Update" class="btn btn-primary col-sm-12 col-md-12 update_status_admin_bulk mr-1">
                                                                Bulk Update
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php foreach ($items as $item) {

                                                    $selected = "";
                                                    $item['discounted_price'] = ($item['discounted_price'] == '') ? 0 : $item['discounted_price'];
                                                    $total += $subtotal = ($item['quantity'] != 0 && ($item['discounted_price'] != '' && $item['discounted_price'] > 0) && $item['price'] > $item['discounted_price']) ? ($item['price'] - $item['discounted_price']) : ($item['price'] * $item['quantity']);
                                                    $tax_amount += $item['tax_amount'];
                                                    $total += $subtotal = $tax_amount;
                                                ?>
                                                    <div class="  card col-md-3 col-sm-12 p-3 mb-2 bg-white rounded m-1 grow">
                                                        <div class="mb-2">
                                                            <input type="checkbox" name="order_item_id[]" value=' <?= $item['id'] ?> '>
                                                        </div>
                                                        <div class="order-product-image">
                                                            <a href='<?= base_url() . $item['product_image'] ?>' data-toggle='lightbox' data-gallery='order-images'> <img src='<?= base_url() . $item['product_image'] ?>' class='h-75'></a>
                                                        </div>

                                                        <?php if (isset($item['product_type']) && $item['product_type'] != 'digital_product') {
                                                            if ($item['item_otp'] != 0) { ?>
                                                                <div><span class="text-bold">Item OTP : </span><span class="badge badge-warning"><?= $item['item_otp']; ?></span></div>
                                                        <?php }
                                                        } ?>
                                                        <div><span class="text-bold">Product Type : </span><small><?= ucwords(str_replace('_', ' ', $item['product_type'])); ?> </small></div>
                                                        <div><span class="text-bold">Variant ID : </span><?= $item['product_variant_id'] ?> </div>
                                                        <?php if (isset($item['product_variants']) && !empty($item['product_variants'])) { ?>
                                                            <div><span class="text-bold">Variants : </span><?= str_replace(',', ' | ', $item['product_variants'][0]['variant_values']) ?> </div>
                                                        <?php } ?>
                                                        <div><span class="text-bold">Name : </span><small><?= $item['pname'] ?> </small></div>
                                                        <div><span class="text-bold">Quantity : </span><?= $item['quantity'] ?> </div>
                                                        <!-- <div><span class="text-bold">Price : </span><?= $item['price'] + $item['tax_amount'] ?></div> -->
                                                        <div><span class="text-bold">Price : </span><?= $item['price'] ?></div>
                                                        <div><span class="text-bold">Discounted Price : </span> <?= $item['discounted_price'] ?> </div>
                                                        <div><span class="text-bold">Subtotal : </span><?= $item['price'] * $item['quantity'] ?> </div>
                                                        <?php
                                                        $badges = ["awaiting" => "secondary", "received" => "primary", "processed" => "info", "shipped" => "warning", "delivered" => "success", "returned" => "danger", "cancelled" => "danger"]
                                                        ?>
                                                        <?php if (isset($item['updated_by'])) { ?>
                                                            <div><span class="text-bold">Updated By : </span><?= $item['updated_by'] ?> </div>
                                                        <?php } ?>
                                                        <div><span class="text-bold">Active Status : </span> <span class="badge badge-<?= $badges[$item['active_status']] ?>"> <small><?= $item['active_status'] ?></small></span></div>
                                                        <?php if (isset($item['product_type']) && $item['product_type'] == 'digital_product') {
                                                        ?>
                                                            <div class="row mb-1 mt-1 order_item_status">
                                                                <div class="col-md-7 text-center"><select class="form-control-sm w-100">
                                                                        <option value="delivered" <?= (strtolower($item['active_status']) == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-5 d-flex align-items-center">
                                                                    <a href="javascript:void(0);" title="Update status" data-id=' <?= $item['id'] ?> ' class="btn btn-primary btn-xs action-btn ml-1 update_status_admin mr-1">
                                                                        <i class="far fa-arrow-alt-circle-up"></i>
                                                                    </a>
                                                                    <a href=" <?= BASE_URL('admin/product/view-product?edit_id=' . $item['product_id'] . '') ?> " title="View Product" class="btn action-btn ml-1 btn-primary btn-xs">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        <?php } else {
                                                        ?>
                                                            <div class="row mb-1 mt-1 order_item_status">
                                                                <div class="col-md-7 text-center"><select class="form-control-sm w-100">
                                                                        <option value="processed" <?= (strtolower($item['active_status']) == 'processed') ? 'selected' : '' ?>>Processed</option>
                                                                        <option value="shipped" <?= (strtolower($item['active_status']) == 'shipped') ? 'selected' : '' ?>>Shipped</option>
                                                                        <option value="delivered" <?= (strtolower($item['active_status']) == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                                                                        <option value="returned" <?= (strtolower($item['active_status']) == 'returned') ? 'selected' : '' ?>>Return</option>
                                                                        <option value="cancelled" <?= (strtolower($item['active_status']) == 'cancelled') ? 'selected' : '' ?>>Cancel</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-5 d-flex align-items-center">
                                                                    <a href="javascript:void(0);" title="Update status" data-id=' <?= $item['id'] ?> ' class="btn btn-primary btn-xs action-btn ml-1 update_status_admin mr-1">
                                                                        <i class="far fa-arrow-alt-circle-up"></i>
                                                                    </a>
                                                                    <a href=" <?= BASE_URL('admin/product/view-product?edit_id=' . $item['product_id'] . '') ?> " title="View Product" class="btn action-btn ml-1 btn-primary btn-xs">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-1 mt-1 delivery_boy">
                                                                <div class="col-md-7 text-center">
                                                                    <select name='single_deliver_by' class='form-control-sm w-100' required>
                                                                        <option value=''>Select Delivery Boy</option>
                                                                        <?php
                                                                        $delivery_boy_id = fetch_details('order_items', ['id' => $item['id']], 'delivery_boy_id');
                                                                        foreach ($delivery_res as $row) {
                                                                            $selected = (isset($delivery_boy_id) && !empty($delivery_boy_id) && $delivery_boy_id[0]['delivery_boy_id'] == $row['user_id']) ? 'selected' : '';
                                                                        ?>
                                                                            <option value="<?= $row['user_id'] ?>" <?= $selected ?>><?= $row['username'] ?></option>
                                                                        <?php  } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-5 d-flex align-items-center">
                                                                    <a href="javascript:void(0);" title="Update Delivery Boy" data-id=' <?= $item['id'] ?> ' class="btn btn-primary btn-xs action-btn ml-1 update_delivery_boy_admin mr-1">
                                                                        <i class="far fa-arrow-alt-circle-up"></i>
                                                                    </a>
                                                                    <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 action-btn ml-1 " title="Order Tracking" data-order_id=' <?= $order_detls[0]['id']; ?>' data-order_item_id=' <?= $item['id'] ?> ' data-courier_agency=' <?= $item['courier_agency'] ?> ' data-tracking_id=' <?= $item['tracking_id'] ?> ' data-url=' <?= $item['url'] ?> ' data-target="#transaction_modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>
                                                                    <?php
                                                                    $transaction_data = fetch_details('transactions', ['order_item_id' => $item['id']], 'txn_id,amount');
                                                                    if (($order_detls[0]['payment_method'] == 'RazorPay' || $order_detls[0]['payment_method'] == 'razorpay' || $order_detls[0]['payment_method'] == 'Razorpay') && $item['active_status'] == 'cancelled' || $item['active_status'] == 'returned') { ?>
                                                                        <a href="javascript:void(0)" class="edit_order_refund btn shipped-box btn-xs mr-1 " title="Refund" data-order_id=' <?= $order_detls[0]['id']; ?>' data-order_item_id=' <?= $item['id'] ?>' data-txn_id=' <?= $transaction_data[0]['txn_id'] ?>' data-txn_amount=' <?= $transaction_data[0]['amount'] ?>' data-target="#refund_modal" data-toggle="modal"><i class="fa fa-undo"></i></a>
                                                                    <?php }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                <?php

                                                }
                                                echo '</div>';
                                                ?>
                                                <div>
                                                </div>
                                        </form>
                                    </td>

                                </tr>
                                <tr>
                                    <th class="w-10px">Total(<?= $settings['currency'] ?>)</th>
                                    <td id=' amount'><?php echo $order_detls[0]['order_total'];
                                                        $total = $order_detls[0]['order_total'];
                                                        ?></td>
                                </tr>

                                <tr class="d-none">
                                    <th class="w-10px">Tax(<?= $settings['currency'] ?>)</th>
                                    <td id='amount'><?php echo $tax_amount;
                                                    ?></td>
                                </tr>
                                <?php if (isset($items[0]['product_type']) && $items[0]['product_type'] != 'digital_product') { ?>
                                    <tr>
                                        <th class="w-10px">Delivery Charge(<?= $settings['currency'] ?>)</th>
                                        <td id='delivery_charge'>
                                            <?php echo $order_detls[0]['delivery_charge'];
                                            $total = $total + $order_detls[0]['delivery_charge']; ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <tr>
                                    <th class="w-10px">Wallet Balance(<?= $settings['currency'] ?>)</th>
                                    <td><?php echo $order_detls[0]['wallet_balance'];
                                        $total = $total - $order_detls[0]['wallet_balance']; ?></td>
                                </tr>

                                <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $order_detls[0]['order_total'] + $order_detls[0]['delivery_charge'] ?>">
                                <input type="hidden" name="final_amount" id="final_amount" value="<?php echo $order_detls[0]['final_total']; ?>">

                                <tr>
                                    <th class="w-10px">Promo Code Discount (<?= $settings['currency'] ?>)</th>
                                    <td><?php echo $order_detls[0]['promo_discount'];
                                        $total = floatval($total -
                                            $order_detls[0]['promo_discount']); ?></td>
                                </tr>
                                <?php
                                if (isset($order_detls[0]['discount']) && $order_detls[0]['discount'] > 0) {
                                    $discount = $order_detls[0]['total_payable']  *  ($order_detls[0]['discount'] / 100);
                                    $total = round($order_detls[0]['total_payable'] - $discount, 2);
                                }
                                ?>
                                <tr>
                                    <th class="w-10px">Payable Total(<?= $settings['currency'] ?>)</th>
                                    <td><input type="text" class="form-control" id="final_total" name="final_total" value="<?= $total; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <th class="w-10px">Payment Method</th>
                                    <td><?php echo $order_detls[0]['payment_method']; ?></td>
                                </tr>
                                <?php
                                if (!empty($bank_transfer)) { ?>
                                    <tr>
                                        <th class="w-10px">Bank Transfers</th>
                                        <td>
                                            <div class="col-md-6">
                                                <?php $status = ["history", "ban", "check"]; ?>
                                                <a class="btn btn-primary btn-xs mr-1 mb-1 " title="Current Status" href="javascript:void(0)" data-id="<?= $order_detls[0]['id']; ?>"><i class="fa fa-<?= $status[$bank_transfer[0]['status']] ?>"></i></a>
                                                <?php $i = 1;
                                                foreach ($bank_transfer as $row1) { ?>
                                                    <small>[<a href="<?= base_url() . $row1['attachments'] ?>" target="_blank">Attachment <?= $i ?> </a>] </small>
                                                    <a class="delete-receipt btn btn-danger btn-xs mr-1 mb-1" title="Delete" href="javascript:void(0)" data-id="<?= $row1['id']; ?>"><i class="fa fa-trash"></i></a>
                                                <?php $i++;
                                                } ?>
                                                <select name="update_receipt_status" id="update_receipt_status" class="form-control status" data-id="<?= $order_detls[0]['id']; ?>" data-user_id="<?= $order_detls[0]['user_id']; ?>">
                                                    <option value=''>Select Status</option>
                                                    <option value="1" <?= (isset($bank_transfer[0]['status']) && $bank_transfer[0]['status'] == 1) ? "selected" : ""; ?>>Rejected</option>
                                                    <option value="2" <?= (isset($bank_transfer[0]['status']) && $bank_transfer[0]['status'] == 2) ? "selected" : ""; ?>>Accepted</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($items[0]['product_type']) && $items[0]['product_type'] != 'digital_product') { ?>
                                    <tr>
                                        <th class="w-10px">Address</th>
                                        <td><?php echo $order_detls[0]['address']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="w-10px">Delivery Date & Time</th>
                                        <td><?php echo date('d-M-Y', strtotime($order_detls[0]['delivery_date'])); ?> - <?= $order_detls[0]['delivery_time'] ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th class="w-10px">Order Date</th>
                                    <td><?php echo date('d-M-Y', strtotime($order_detls[0]['date_added'])); ?></td>
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
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="refund_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-header">
                <h5 class="modal-title" id="user_name">Payment Refund</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <!-- form start -->
                            <form class="form-horizontal " id="refund_form" action="<?= base_url('admin/orders/refund_payment'); ?>" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type="hidden" name=" <?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                    <input type="hidden" name="item_id" id="item_id">
                                </div>
                                <div class="card-body pad">
                                    <div class="form-group ">
                                        <label for="transaction_id">Transaction Id</label>
                                        <input type="text" class="form-control" name="transaction_id" id="transaction_id" placeholder="Transaction Id" disabled />
                                    </div>
                                    <div class="form-group ">
                                        <label for="txn_amount">Amount</label>
                                        <input type="text" class="form-control" name="txn_amount" id="txn_amount" placeholder="Amount" disabled />
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-secondary" id="submit_btn">Refund</button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div class="form-group" id="error_box">
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </form>
                        </div>
                        <!--/.card-->
                    </div>
                    <!--/.col-md-12-->
                </div>
                <!-- /.row -->

            </div>
            </form>
        </div>
    </div>
</div>