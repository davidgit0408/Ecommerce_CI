<!-- Demo header-->
<section class="header settings-tab">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12 orders-section settings-tab-content">
                <div class="mb-4  border-0">
                    <div class="card-header bg-white">
                        <div class="row justify-content-between">
                            <div class="col">
                                <p class="text-muted"> <?= !empty($this->lang->line('order_id')) ? $this->lang->line('order_id') : 'Order ID' ?><span class="font-weight-bold text-dark"> : <?= $order['id'] ?></span></p>
                                <p class="text-muted"> <?= !empty($this->lang->line('place_on')) ? $this->lang->line('place_on') : 'Place On' ?><span class="font-weight-bold text-dark"> : <?= $order['date_added'] ?></span> </p>
                            </div>

                            <div class="flex-col my-auto">
                                <h6 class="ml-auto mr-3">
                                    <a target="_blank" href="<?= base_url('my-account/order-invoice/' . $order['id']) ?>" class='button button-primary-outline'><?= !empty($this->lang->line('invoice')) ? $this->lang->line('invoice') : 'Invoice' ?></a>
                                    <a href="<?= base_url('my-account/orders/') ?>" class='button button-danger-outline'><?= !empty($this->lang->line('back_to_list')) ? $this->lang->line('back_to_list') : 'Back to List' ?></a>
                                </h6>
                            </div>
                        </div>
                        <br>
                        <?php if ($order['payment_method'] == "Bank Transfer") { ?>
                            <div class="row">
                                <form class="form-horizontal " id="send_bank_receipt_form" action="<?= base_url('cart/send-bank-receipt'); ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <div class="form-group ">
                                        <label for="receipt"> <strong> <?= !empty($this->lang->line('bank_payment_receipt')) ? $this->lang->line('bank_payment_receipt') : 'Bank Payment Receipt' ?></strong> </label>
                                        <input type="file" class="form-control" name="attachments[]" id="receipt" multiple />
                                    </div>
                                    <div class="form-group">
                                        <button type="reset" class="button button-warning-outline"><?= !empty($this->lang->line('reset')) ? $this->lang->line('reset') : 'Reset' ?></button>
                                        <button type="submit" class="button button-success-outline" id="submit_btn"><?= !empty($this->lang->line('send')) ? $this->lang->line('send') : 'Send' ?></button>
                                    </div>
                                </form>

                            </div>
                        <?php } ?>
                        <div class="row">
                            <?php if (!empty($bank_transfer)) { ?>
                                <div class="col-md-6">
                                    <?php $i = 1;
                                    foreach ($bank_transfer as $row1) { ?>
                                        <small>[<a href="<?= base_url() . $row1['attachments'] ?>" target="_blank"><?= !empty($this->lang->line('attachment')) ? $this->lang->line('attachment') : 'Attachment' ?> <?= $i ?> </a>]</small>
                                    <?php $i++;
                                    }
                                    if ($bank_transfer[0]['status'] == 0) { ?>
                                        <label class="badge badge-warning"><?= !empty($this->lang->line('pending')) ? $this->lang->line('pending') : 'Pending' ?></label>
                                    <?php } else if ($bank_transfer[0]['status'] == 1) { ?>
                                        <label class="badge badge-danger"><?= !empty($this->lang->line('rejected')) ? $this->lang->line('rejected') : 'Rejected' ?></label>
                                    <?php } else if ($bank_transfer[0]['status'] == 2) { ?>
                                        <label class="badge badge-primary"><?= !empty($this->lang->line('accepted')) ? $this->lang->line('accepted') : 'Accepted' ?></label>
                                    <?php } else { ?>
                                        <label class="badge badge-danger"><?= !empty($this->lang->line('invalid_value')) ? $this->lang->line('invalid_value') : 'Invalid Value' ?></label>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php foreach ($order['order_items'] as $key => $item) { ?>

                            <div class="media flex-column flex-sm-row">
                                <div class="media-body ">
                                  
                                    <a href="<?= base_url('products/details/' . $item['slug']) ?>"><h5 class="bold"><?= ($key + 1) . '. ' . $item['name'] ?></h5></a>
                                    <p class="text-muted"> <?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?> : <?= $item['quantity'] ?></p>
                                    <?php if ($item['otp'] != 0) { ?>
                                        <p class="text-muted"> <?= !empty($this->lang->line('otp')) ? $this->lang->line('otp') : 'OTP' ?> <span class="font-weight-bold text-dark"> : <?= $item['otp'] ?></span> </p>
                                    <?php } ?>
                                    <?php if (isset($item['courier_agency']) && !empty($item['courier_agency'])) { ?>
                                        <p> <span class="text-muted"> <?= !empty($this->lang->line('courier_agency')) ? $this->lang->line('courier_agency') : 'Courier Agency' ?> : </span><a href="<?= $item['url'] ?>" title="click here to trace the order"><?= $item['courier_agency'] ?></a> </p>
                                        <p class="text-muted" data-toggle="tooltip" data-placement="top" title="Copy this Tracking ID and trace your order with Courier Agency."> <?= !empty($this->lang->line('tracking_id')) ? $this->lang->line('tracking_id') : 'Tracking ID' ?> <span class="font-weight-bold text-dark"> : <?= $item['tracking_id'] ?></span> </p>
                                    <?php } ?>
                                    <h4 class="mt-3 mb-2 bold"> <span class="mt-5"><i><?= $settings['currency'] ?></i></span> <?= number_format(($item['price'] * $item['quantity']), 2) ?> <span class="small text-muted"></span></h4>
                                    <?php
                                    $status = ["awaiting", "received", "processed", "shipped", "delivered", "cancelled", "returned"];
                                    $cancelable_till = $item['cancelable_till'];
                                    $active_status = $item['active_status'];
                                    $cancellable_index = array_search($cancelable_till, $status);
                                    $active_index = array_search($active_status, $status);
                                    if (!$item['is_already_cancelled'] && $item['is_cancelable'] && $active_index <= $cancellable_index && $item['type'] != 'digital_product') { ?>
                                        <button class="button button-danger button-sm update-order-item" data-status="cancelled" data-item-id="<?= $item['id'] ?>"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></button>
                                    <?php } ?>
                                    <?php $order_date = $order['order_items'][0]['status'][3][1];
                                    if ($order['is_returnable'] && !$order['is_already_returned'] && isset($order_date) && !empty($order_date)) { ?>
                                        <?php
                                        $settings = get_settings('system_settings', true);
                                        $timestemp = strtotime($order_date);
                                        $date = date('Y-m-d', $timestemp);
                                        $today = date('Y-m-d');
                                        $return_till = date('Y-m-d', strtotime($order_date . ' + ' . $settings['max_product_return_days'] . ' days'));
                                        echo "<br>";
                                        if ($today < $return_till && $item['type'] != 'digital_product') { ?>
                                            <div class="col my-auto ">
                                                <a class="update-order block buttons button-sm btn-6-3 mt-3 m-0" data-status="returned" data-order-id="<?= $order['id'] ?>"><?= !empty($this->lang->line('return')) ? $this->lang->line('return') : 'Return' ?></a>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    
                                    <?php if ($item['type'] == 'digital_product' &&  $item['download_allowed'] == 1 && ($item['active_status'] == 'received' || $item['active_status'] == 'delivered')) {
                                        $download_link = $item['hash_link'];
                                    ?>
                                        <div class="media-body mt-3">
                                            <a href="<?= base_url('products/download_link_hash/' . $item['id']) ?>" title="Download Product" class="btn btn-outline-info"><i class="fas fa-download"></i> Download</a>
                                        </div>
                                    <?php } 
                                    if($item['type'] == 'digital_product' &&  $item['download_allowed'] == 0) { ?>
                                        <div class="media-body mt-3">
                                            <span class="text-danger">You will receive this item from seller via email.</span>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="align-self-center img-fluid" >
                                <a href="<?= base_url('products/details/' . $item['slug']) ?>"><img src="<?= $item['image_sm'] ?>" width="180 " height="180"></a>
                                </div>
                            </div>
                            <hr>
                            <?php if ($item['type'] != 'digital_product') { ?>
                                <div class="row">
                                    <div class="col">
                                        <ul id="progressbar">
                                            <?php
                                            $status = array('received', 'processed', 'shipped', 'delivered');
                                            $i = 1;
                                            foreach ($item['status'] as $value) { ?>
                                                <?php
                                                $class = '';
                                                if ($value[0] == "cancelled" || $value[0] == "returned") {
                                                    $class = 'cancel';
                                                    $status = array();
                                                } elseif (($ar_key = array_search($value[0], $status)) !== false) {
                                                    unset($status[$ar_key]);
                                                }
                                                ?>
                                                <li class="active <?= $class ?>" id="step<?= $i ?>">
                                                    <p><?= strtoupper($value[0]) ?></p>
                                                    <p><?= $value[1] ?></p>
                                                </li>
                                            <?php
                                                $i++;
                                            } ?>
                                            <?php foreach ($status as $value) { ?>
                                                <li id="step<?= $i ?>">
                                                    <p><?= strtoupper($value) ?></p>
                                                </li>
                                            <?php $i++;
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                        <?php }
                        } ?>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="h5"><?= !empty($this->lang->line('shipping_details')) ? $this->lang->line('shipping_details') : 'Shipping Details' ?></h6>
                                <hr>
                                <span><?= $order['username'] ?></span> <br>
                                <span><?= $order['address'] ?></span> <br>
                                <span><?= $order['mobile'] ?></span> <br>
                                <span><?= $order['delivery_time'] ?></span> <br>
                                <span><?= $order['delivery_date'] ?></span> <br>
                            </div>
                            <div class="col-md-6">
                                <h6 class="h5"><?= !empty($this->lang->line('price_details')) ? $this->lang->line('price_details') : 'Price Details' ?></h6>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th><?= !empty($this->lang->line('total_order_price')) ? $this->lang->line('total_order_price') : 'Total Order Price' ?></th>
                                                <td>+ <?= $settings['currency'] . ' ' . number_format($order['total'], 2) ?></td>
                                            </tr>
                                            <?php if ($item['type'] != 'digital_product') { ?>
                                                <tr>
                                                    <th><?= !empty($this->lang->line('delivery_charge')) ? $this->lang->line('delivery_charge') : 'Delivery Charge' ?></th>
                                                    <td>+ <?= $settings['currency'] . ' ' . number_format($order['delivery_charge'], 2) ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr class="d-none">
                                                <th><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?> - (<?= $order['total_tax_percent'] ?>%)</th>
                                                <td>+ <?= $settings['currency'] . ' ' . number_format($order['total_tax_amount'], 2) ?></td>
                                            </tr>
                                            <?php if (!empty($order['promo_code']) && !empty($order['promo_discount'])) { ?>
                                                <tr>
                                                    <th><?= !empty($this->lang->line('promocode_discount')) ? $this->lang->line('promocode_discount') : 'Promocode Discount' ?> - (<?= $order['promo_code'] ?>)
                                                    </th>
                                                    <td>- <?= $settings['currency'] . ' ' . number_format($order['promo_discount'], 2) ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <th><?= !empty($this->lang->line('wallet_used')) ? $this->lang->line('wallet_used') : 'Wallet Used' ?></th>
                                                <td>- <?= $settings['currency'] . ' ' . number_format($order['wallet_balance'], 2) ?></td>
                                            </tr>
                                            <tr class="h6">
                                                <th><?= !empty($this->lang->line('final_total')) ? $this->lang->line('final_total') : 'Final Total' ?></th>
                                                <td><?= $settings['currency'] . ' ' . number_format($order['final_total'], 2) ?><span class="small text-muted"> <?= !empty($this->lang->line('via')) ? $this->lang->line('via') : 'via' ?> (<?= $order['payment_method'] ?>) </span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- /.col -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white px-sm-3 pt-sm-4 px-0">
                        <div class="row text-center ">
                            <?php
                            $status = ["awaiting", "received", "processed", "shipped", "delivered", "cancelled", "returned"];
                            $cancelable_till = $item['cancelable_till'];
                            $active_status = $item['active_status'];
                            $cancellable_index = array_search($cancelable_till, $status);
                            $active_index = array_search($active_status, $status);
                            if (!$item['is_already_cancelled'] && $item['is_cancelable'] && $active_index <= $cancellable_index) { ?>
                                <div class="col my-auto">
                                    <a class="update-order block button-sm buttons btn-6-1 mt-3 m-0" data-status="cancelled" data-order-id="<?= $order['id'] ?>"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></a>
                                </div>
                            <?php } ?>
                            <?php
                            $order_date = $order['order_items'][0]['status'][3][1];
                            if ($order['is_returnable'] && !$order['is_already_returned'] && isset($order_date) && !empty($order_date)) { ?>
                                <?php
                                $settings = get_settings('system_settings', true);

                                $timestemp = strtotime($order_date);
                                $date = date('Y-m-d', $timestemp);
                                $today = date('Y-m-d');
                                $return_till = date('Y-m-d', strtotime($order_date . ' + ' . $settings['max_product_return_days'] . ' days'));
                                echo "<br>";
                                if ($today < $return_till) { ?>
                                    <div class="col my-auto ">
                                        <a class="update-order block buttons button-sm btn-6-3 mt-3 m-0" data-status="returned" data-order-id="<?= $order['id'] ?>"><?= !empty($this->lang->line('return')) ? $this->lang->line('return') : 'Return' ?></a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>