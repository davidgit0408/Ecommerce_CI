<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2>Orders</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('my-account') ?>"><?= !empty($this->lang->line('dashboard')) ? $this->lang->line('dashboard') : 'Dashboard' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'Orders' ?></a></li>
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
        <div class="row m5">
            <div class="col-md-4">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>

            <div class="col-md-8 col-12">
                <div class="card-header bg-white">
                    <h1 class="h4"><?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'Orders' ?></h1>
                </div>
                <div class="card-body orders-section p-2">
                    <?php
                    foreach ($orders['order_data'] as $row) {
    
                    ?>
                        <div class=" border-0">
                            <div class="card-header bg-white p-2">
                                <div class="row justify-content-between">
                                    <div class="col">
                                        <p class="text-muted"> <?= !empty($this->lang->line('order_id')) ? $this->lang->line('order_id') : 'Order ID' ?> <span class="font-weight-bold text-dark"> : <?= $row['id'] ?></span></p>
                                        <p class="text-muted"> <?= !empty($this->lang->line('place_on')) ? $this->lang->line('place_on') : 'Place On' ?> <span class="font-weight-bold text-dark"> : <?= $row['date_added'] ?></span> </p>
                                        <?php if ($row['otp'] != 0) { ?>
                                            <p class="text-muted"> <?= !empty($this->lang->line('otp')) ? $this->lang->line('otp') : 'OTP' ?> <span class="font-weight-bold text-dark"> : <?= $row['otp'] ?></span> </p>
                                        <?php } ?>
                                    </div>
                                    <div class="flex-col my-auto">
                                        <h6 class="ml-auto mr-3"> <a href="<?= base_url('my-account/order-details/' . $row['id']) ?>" class='button button-primary-outline'><?= !empty($this->lang->line('view_details')) ? $this->lang->line('view_details') : 'View Details' ?></a> </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-2">
                                <div class="media flex-column flex-sm-row">
                                    <div class="media-body ">
                                        <?php foreach ($row['order_items'] as $key => $item) {  ?>
                                            <h5 class="bold mt-1"><?= ($key + 1) . '. ' . $item['name'] ?></h5>
                                            <p class="text-muted"> <?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?> : <?= $item['quantity'] ?></p>
                                            <div class="col-md-12 pl-0 product-rating-small" dir="ltr">
                                                <input type="text" class="kv-fa rating-loading" value="<?= $item['product_rating'] ?>" data-size="xs" title="" readonly>
                                            </div>
                                        <?php } ?>
                                        <?php if ($item['otp'] != 0) { ?>
                                            <p class="text-muted"> <?= !empty($this->lang->line('otp')) ? $this->lang->line('otp') : 'OTP' ?> <span class="font-weight-bold text-dark"> : <?= $item['otp'] ?></span> </p>
                                        <?php } ?>
                                        <h4 class="mt-3 mb-4 bold"> <span class="mt-5"><i><?= $settings['currency'] ?></i></span> <?= number_format($row['final_total'], 2) ?> <span class="small text-muted"> <?= !empty($this->lang->line('via')) ? $this->lang->line('via') : 'via' ?> (<?= $row['payment_method'] ?>) </span></h4>
                                    </div>
                                    <?php if (count($row['order_items']) == 1) { ?>
                                        <img class="align-self-center img-fluid" src="<?= $row['order_items'][0]['image_sm'] ?>" width="180 " height="180">
                                    <?php } ?>
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
                                    if (!$item['is_already_cancelled'] && $item['is_cancelable'] && $active_index <= $cancellable_index && $row['type'] != 'digital_product') { ?>
                                        <div class="col my-auto">
                                            <h5>
                                                <a class="update-order block button-sm buttons btn-6-1 mt-3 m-0" data-status="cancelled" data-order-id="<?= $row['id'] ?>"><?= !empty($this->lang->line('cancel')) ? $this->lang->line('cancel') : 'Cancel' ?></a>
                                            </h5>
                                        </div>
                                    <?php } ?>
                                   
                                    <?php
                                    $order_date = $row['order_items'][0]['status'][3][1];
                                    if ($row['is_returnable'] && !$row['is_already_returned'] && isset($order_date) && !empty($order_date)) { ?>
                                        <?php
                                        $settings = get_settings('system_settings', true);
                                        $timestemp = strtotime($order_date);
                                        $date = date('Y-m-d', $timestemp);
                                        $today = date('Y-m-d');
                                        $return_till = date('Y-m-d', strtotime($order_date . ' + ' . $settings['max_product_return_days'] . ' days'));
                                        echo "<br>";
                                        if ($today < $return_till && $row['type'] != 'digital_product') { ?>
                                            <div class="col my-auto ">
                                                <a class="update-order block buttons button-sm btn-6-3 mt-3 m-0" data-status="returned" data-order-id="<?= $row['id'] ?>"><?= !empty($this->lang->line('return')) ? $this->lang->line('return') : 'Return' ?></a>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($row['payment_method'] == 'Bank Transfer') { ?>
                                        <div class="col my-auto ">
                                            <h5>
                                                <a class="block button-sm buttons btn-6-5 mt-3 m-0" href="<?= base_url('my-account/order-details/' . $row['id']) ?>"> Send Bank Payment Receipt</i>
                                                    <!-- <input type="file"  name="receipt" class="form-control"/>  -->
                                                </a>
                                            </h5>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="text-center">
                        <?= $links ?>
                    </div>
                </div>
                <!-- </div> -->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>