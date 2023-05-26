<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('cart')) ? $this->lang->line('cart') : 'Cart' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('cart')) ? $this->lang->line('cart') : 'Cart' ?></a></li>
            </ol>
        </nav>
    </div>

</section>
<!-- end breadcrumb -->
<!-- add to cart -->
<div class="wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-xl-8 mt-5 bg-white">
                <div class="cart-table-wrapper">
                    <div class="text-right">
                        <button name="clear_cart" id="clear_cart" class="button button-danger mt-3 mb-4"><?= !empty($this->lang->line('clear_cart')) ? $this->lang->line('clear_cart') : 'Clear Cart' ?></button>
                    </div>
                    <table id="cart_item_table" class="table table-responsive table-cart-product">
                        <thead>
                            <tr>
                                <th class="text-muted"><?= !empty($this->lang->line('image')) ? $this->lang->line('image') : 'Image' ?></th>
                                <th class="text-muted"><?= !empty($this->lang->line('product')) ? $this->lang->line('product') : 'Product' ?></th>
                                <th class="text-muted"><?= !empty($this->lang->line('price')) ? $this->lang->line('price') : 'Price' ?></th>
                                <th class="text-muted"><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?>(%)</th>
                                <th class="text-muted"><?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?></th>
                                <th class="text-muted"><?= !empty($this->lang->line('subtotal')) ? $this->lang->line('subtotal') : 'Subtotal' ?></th>
                                <th class="text-muted"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $key => $row) {
                                if (isset($row['qty']) && $row['qty'] != 0) {
                                    $price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 ? $row['special_price'] : $row['price'];
                            ?>
                                    <tr class="cart-product-desc-list">
                                        <td>
                                            <div class="widget-image">
                                                <a href="<?= base_url('products/details/' . $row['slug']) ?>" target="_blank">
                                                    <img src="<?= $row['image'] ?>" alt="<?= $row['name']; ?>"></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="id">
                                                <input type="hidden" name="<?= 'id[' . $key . ']' ?>" id="id" value="<?= $row['id'] ?>">
                                            </div>
                                            <h2 class="cart-product-title">
                                                <a href="<?= base_url('products/details/' . $row['slug']) ?>" target="_blank"><?= output_escaping(str_replace('\r\n', '&#13;&#10;', $row['name'])); ?></a>
                                                <?php if (!empty($row['product_variants'])) { ?>
                                                    <br><?= str_replace(',', ' | ', $row['product_variants'][0]['variant_values']) ?>
                                                <?php } ?>
                                            </h2>
                                            <button class="btn save-for-later remove-product button button-primary button-sm" data-id="<?= $row['id']; ?>"><?= !empty($this->lang->line('save_for_later')) ? $this->lang->line('save_for_later') : 'Save For Later' ?></button>
                                        </td>
                                        <td class="text-muted p-0"><?= $settings['currency'] . '' . number_format($price, 2) ?></td>
                                        <td class="text-muted text-center p-0"><?= isset($row['tax_percentage']) && !empty($row['tax_percentage']) ? $row['tax_percentage'] : '-' ?></td>
                                        <td class="item-quantity">
                                            <div class="num-block skin-2 product-quantity">
                                                <?php $check_current_stock_status = validate_stock([$row['id']], [$row['qty']]); ?>
                                                <?php if (isset($check_current_stock_status['error'])  && $check_current_stock_status['error'] == TRUE) { ?>
                                                    <div><span class='text text-danger'> Out of Stock </span></div>
                                                <?php } else { ?>
                                                    <div class="num-in">
                                                        <?php $price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 ? $row['special_price'] : $row['price']; ?>
                                                        <span class="minus dis" data-min="<?= (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1 ?>" data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>"></span>
                                                        <input type="text" class="in-num itemQty" data-page="cart" data-id="<?= $row['id']; ?>" value="<?= $row['qty'] ?>" data-price="<?= $price ?>" data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>" data-min="<?= (isset($row['minimum_order_quantity']) && !empty($row['minimum_order_quantity'])) ? $row['minimum_order_quantity'] : 1 ?>" data-max="<?= (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : '' ?>">
                                                        <span class="plus" data-max="<?= (isset($row['total_allowed_quantity']) && !empty($row['total_allowed_quantity'])) ? $row['total_allowed_quantity'] : '0' ?> " data-step="<?= (isset($row['minimum_order_quantity']) && !empty($row['quantity_step_size'])) ? $row['quantity_step_size'] : 1 ?>"></span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="text-muted p-0 total-price"><span class="product-line-price"> <?= $settings['currency'] . '' . number_format(($row['qty'] * $price), 2) ?></span></td>
                                        <td>
                                            <div class="product-removal">
                                                <i class="remove-product fas fa-trash-alt text-danger" name="remove_inventory" id="remove_inventory" data-id="<?= $row['id']; ?>" title="Remove From Cart"></i>
                                            </div>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <?php if (!empty($save_for_later['variant_id'])) { ?>
                    <div class="cart-table-wrapper">
                        <table class="table table-responsive-sm table-cart-product">
                            <h4 class="h4"><?= !empty($this->lang->line('save_for_later')) ? $this->lang->line('save_for_later') : 'Save For Later' ?></h1>
                                <thead>
                                    <tr>
                                        <th class="text-muted"><?= !empty($this->lang->line('image')) ? $this->lang->line('image') : 'Image' ?></th>
                                        <th class="text-muted"><?= !empty($this->lang->line('product')) ? $this->lang->line('product') : 'Product' ?></th>
                                        <th class="text-muted"><?= !empty($this->lang->line('price')) ? $this->lang->line('price') : 'Price' ?></th>
                                        <th class="text-muted"><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?>(%)</th>
                                        <th class="text-muted"><?= !empty($this->lang->line('quantity')) ? $this->lang->line('quantity') : 'Quantity' ?></th>
                                        <th class="text-muted"><?= !empty($this->lang->line('subtotal')) ? $this->lang->line('subtotal') : 'Subtotal' ?></th>
                                        <th class="text-muted"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($save_for_later as $key => $row) {
                                        if (isset($row['qty']) && $row['qty'] != 0) {
                                            $price = $row['special_price'] != '' && $row['special_price'] != null && $row['special_price'] > 0 && $row['special_price'] < $row['price'] ? $row['special_price'] : $row['price'];
                                    ?>
                                            <tr class="cart-product-desc-list">
                                                <td>
                                                    <div class="cart-product-image">
                                                        <a href="<?= base_url('products/details/' . $row['slug']) ?>" target="_blank">
                                                            <img src="<?= $row['image'] ?>" alt="">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="id">
                                                        <input type="hidden" name="<?= 'id[' . $key . ']' ?>" id="id" value="<?= $row['id'] ?>">
                                                    </div>
                                                    <h2 class="cart-product-title">
                                                        <a href="<?= base_url('products/details/' . $row['slug']) ?>" target="_blank"><?= output_escaping(str_replace('\r\n', '&#13;&#10;', $row['name'])); ?></a>
                                                        <?php if (!empty($row['product_variants'])) { ?>
                                                            <br><?= str_replace(',', ' | ', $row['product_variants'][0]['variant_values']) ?>
                                                        <?php } ?>
                                                        <br><button class="btn remove-product button button-warning move-to-cart button-sm" data-id="<?= $row['id']; ?>"><?= !empty($this->lang->line('move_to_cart')) ? $this->lang->line('move_to_cart') : 'Move to cart' ?></button>
                                                    </h2>
                                                </td>
                                                <td class="text-muted p-0"><?= $settings['currency'] . ' ' . number_format($price, 2) ?></td>
                                                <td class="text-muted p-0 text-center"><?= isset($row['tax_percentage']) && !empty($row['tax_percentage']) ? $row['tax_percentage'] : '-' ?></td>
                                                <td class="itemQty">
                                                    <?php $check_current_stock_status = validate_stock([$row['id']], [$row['qty']]); ?>
                                                    <?php if (isset($check_current_stock_status['error'])  && $check_current_stock_status['error'] == TRUE) { ?>
                                                        <div><span class='text text-danger'> <?= !empty($this->lang->line('out_of_stock')) ? $this->lang->line('out_of_stock') : 'Out of Stock' ?> </span></div>
                                                    <?php } else { ?>
                                                        <?= $row['qty'] ?>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-muted p-0"><?= $settings['currency'] . ' ' . number_format($price, 2) ?></td>
                                                <td>

                                                    <div class="product-removal">
                                                        <i class="remove-product fas fa-trash-alt text-danger" name="remove_inventory" id="remove_inventory" data-id="<?= $row['id']; ?>" data-is-save-for-later="1"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
            <div class="col-xl-4 mt-5">
                <div class="cart-product-summary">
                    <h3><?= !empty($this->lang->line('cart_total')) ? $this->lang->line('cart_total') : 'Cart total' ?></h3>
                    <div class="cart-total-price">
                        <table class="table cart-products-table">
                            <tbody>
                                <tr class="d-none">
                                    <td class="text-muted"><?= !empty($this->lang->line('subtotal')) ? $this->lang->line('subtotal') : 'Subtotal' ?></td>
                                    <td class="text-muted"><?= $settings['currency'] . ' ' . number_format($cart['sub_total'], 2) ?></td>
                                </tr>
                                <?php if (!empty($cart['tax_percentage'])) { ?>
                                    <tr class="cart-product-tax d-none">
                                        <td class="text-muted"><?= !empty($this->lang->line('tax')) ? $this->lang->line('tax') : 'Tax' ?> (<?= $cart['tax_percentage'] ?>%)</td>
                                        <td class="text-muted"><?= $settings['currency'] . ' ' . number_format($cart['tax_amount'], 2) ?></td>
                                    </tr>
                                <?php } ?>
                                <?php $delivery_charge = !empty($cart['sub_total']) ? number_format($cart['delivery_charge'], 2) : 0 ?>
                                <tr class="d-none">
                                    <td class="text-muted"><?= !empty($this->lang->line('delivery_charge')) ? $this->lang->line('delivery_charge') : 'Delivery Charge' ?></td>
                                    <td class="text-muted"><?= $settings['currency'] . ' ' . $delivery_charge ?></td>
                                </tr>
                            </tbody>
                            <tfoot>

                                <?php $total = !empty($cart['sub_total']) ? number_format($cart['overall_amount'] - $cart['delivery_charge'], 2) : 0 ?>
                                <tr>
                                <tr class="total-price">
                                    <td><?= !empty($this->lang->line('total')) ? $this->lang->line('total') : 'Total' ?></td>
                                    <td><?= $settings['currency'] . '<span id="final_total"> ' . $total . '</span>' ?></td>
                                </tr>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php $disabled = empty($cart['sub_total']) ? 'disabled' : '' ?>
                    <div class="checkout-method">
                        <a href="<?= base_url('cart/checkout') ?>" id="checkout"> <button class="block" <?= $disabled ?>><?= !empty($this->lang->line('go_to_checkout')) ? $this->lang->line('go_to_checkout') : 'Go To Checkout' ?></button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>