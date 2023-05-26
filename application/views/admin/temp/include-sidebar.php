<?php
    $settings = get_settings('system_settings', true);
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme sidebar">

    <!-- Brand Logo -->
    <div class="app-brand demo">
        <a href="<?= base_url('admin/home') ?>" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span style="color: var(--bs-primary)">
                    <img src="<?= base_url() . get_settings('favicon') ?>" alt="<?= $settings['app_name']; ?>"
                         style="width:38px;"
                         title="<?= $settings['app_name']; ?>" class="brand-image">
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2"><?= $settings['app_name']; ?></span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                        fill="currentColor"
                        fill-opacity="0.6"/>
                <path
                        d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                        fill="currentColor"
                        fill-opacity="0.38"/>
            </svg>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <!-- Sidebar -->
    <ul class="menu-inner py-1">

        <li class="menu-item">
            <a href="<?= base_url('/admin/home') ?>" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>

        <?php if (has_permissions('read', 'orders')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-shopping-cart text-warning"></i>
                    <div data-i18n="Orders">Orders</div>
                </a>
                <ul class="menu-sub">
                    <?php if (has_permissions('read', 'orders')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/orders/') ?>" class="menu-link">
                                <i class="fa fa-shopping-cart menu-icon"></i>
                                <div data-i18n="Orders">Orders</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'orders')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/orders/order-tracking') ?>" class="menu-link">
                                <i class="fa fa-map-marker-alt menu-icon"></i>
                                <div data-i18n="Order Tracking">Order Tracking</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'orders')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/notification_settings/manage_system_notifications') ?>"
                               class="menu-link">
                                <i class="fas fa-bell menu-icon"></i>
                                <div data-i18n="System Notifications">System Notifications</div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

        <?php if (has_permissions('read', 'categories')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-bullseye text-success"></i>
                    <div data-i18n="Categories">Categories</div>
                </a>
                <ul class="menu-sub">
                    <?php if (has_permissions('read', 'categories')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/category/') ?>" class="menu-link">
                                <i class="fa fa-bullseye menu-icon"></i>
                                <div data-i18n="Categories">Categories</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'category_order')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/category/category-order') ?>" class="menu-link">
                                <i class="fa fa-bars menu-icon"></i>
                                <div data-i18n="Category Order">Category Order</div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

        <?php if (has_permissions('read', 'brands')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fab fa-adversal text-primary"></i>
                    <div data-i18n="Brands">Brands</div>
                </a>
                <ul class="menu-sub">
                    <?php if (has_permissions('read', 'brands')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/brand/') ?>" class="menu-link">
                                <i class="fab fa-adversal menu-icon"></i>
                                <div data-i18n="brands">brands</div>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="<?= base_url('admin/brand/bulk-upload') ?>" class="menu-link">
                                <i class="fas fa-upload menu-icon"></i>
                                <div data-i18n="Bulk upload">Bulk upload</div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

        <?php if (has_permissions('read', 'seller')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-store text-danger"></i>
                    <div data-i18n="Sellers">Sellers</div>
                </a>
                <ul class="menu-sub">
                    <?php if (has_permissions('read', 'seller')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/sellers/') ?>" class="menu-link">
                                <i class="fa fa-store menu-icon"></i>
                                <div data-i18n="Sellers">Sellers</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'seller')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/transaction/wallet-transactions') ?>"
                               class="menu-link">
                                <i class="fa fa-wallet menu-icon"></i>
                                <div data-i18n="Wallet Transactions">Wallet Transactions</div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

        <?php if (has_permissions('read', 'product') || has_permissions('read', 'attribute') || has_permissions('read', 'attribute_set') || has_permissions('read', 'attribute_value') || has_permissions('read', 'tax') || has_permissions('read', 'product_order')) { ?>
            <li class="menu-item ">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-cubes text-primary"></i>
                    <div data-i18n="Products">Products</div>
                </a>

                <ul class="menu-sub">

                    <!-- <?php if (has_permissions('read', 'attribute_set')) { ?>
                            <li class="menu-item">
                                <a href="<?= base_url('admin/attribute_set/manage-attribute-set') ?>menu-link menu-toggle<?php } ?>  -->

                    <?php if (has_permissions('read', 'attribute')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/attributes/manage-attribute') ?>"
                               class="menu-link">
                                <i class="fas fa-sliders-h menu-icon"></i>
                                <div data-i18n="Attributes">Attributes</div>
                            </a>
                        </li>
                    <?php } ?>


                    <!-- <?php if (has_permissions('read', 'attribute_value')) { ?>

                            <li class="menu-item">
                                <a href="<?= base_url('admin/attribute_value/manage-attribute-value') ?>menu-link<?php } ?> -->

                    <?php if (has_permissions('read', 'tax')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/taxes/manage-taxes') ?>" class="menu-link">
                                <i class="fas fa-percentage menu-icon"></i>
                                <div data-i8n="Tax">Tax</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'product')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/product/create-product') ?>" class="menu-link">
                                <i class="fas fa-plus-square menu-icon"></i>
                                <div data-i8n="Add Products">Add Products</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'product')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/product/bulk-upload') ?>" class="menu-link">
                                <i class="fas fa-upload menu-icon"></i>
                                <div data-i8n="Bulk upload">Bulk upload</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'product')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/product/') ?>" class="menu-link">
                                <i class="fas fa-boxes menu-icon"></i>
                                <div data-i8n="Manage Products">Manage Products</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'product_faqs')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/product_faqs/') ?>" class="menu-link">
                                <i class="fas fa-question-circle menu-icon"></i>
                                <div data-i8n="Product FAQs">Product FAQs</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'product_order')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/product/product-order') ?>" class="menu-link">
                                <i class="fa fa-bars menu-icon"></i>
                                <div data-i8n="Products Order">Products Order</div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

        <?php if (has_permissions('read', 'media')) { ?>
            <li class="menu-item">
                <a href="<?= base_url('admin/media/') ?>" class="menu-link">
                    <i class="menu-icon fas fa-icons text-danger"></i>
                    <div data-i18n="Media">Media</div>
                </a>
            </li>
        <?php } ?>
        <?php if (has_permissions('read', 'home_slider_images')) { ?>
            <li class="menu-item">
                <a href="<?= base_url('admin/slider/manage-slider') ?>" class="menu-link">
                    <i class="menu-icon far fa-image text-success"></i>
                    <div data-i18n="Sliders">Sliders</div>
                </a>
            </li>
        <?php } ?>

        <?php if (has_permissions('read', 'new_offer_images')) { ?>
            <li class="menu-item">
                <a href="<?= base_url('admin/offer/manage-offer') ?>" class="menu-link">
                    <i class="menu-icon fa fa-gift text-primary"></i>
                    <div data-i18n="Offers">Offers</div>
                </a>
            </li>
        <?php } ?>

        <!-- manage stock -->
        <?php if (has_permissions('read', 'manage_stock')) { ?>
            <li class="menu-item">
                <a href="<?= base_url('admin/manage_stock') ?>" class="menu-link">
                    <i class="menu-icon fa fa-cube text-success"></i>
                    <div data-i18n="Stock">Stock</div>
                </a>
            </li>
        <?php } ?>


        <?php if (has_permissions('read', 'support_tickets')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-ticket-alt text-danger"></i>
                    <div data-i18n="Tickets">Tickets</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="<?= base_url('admin/tickets/ticket-types') ?>" class="menu-link">
                            <i class="fas fa-money-bill-wave menu-icon"></i>
                            <div data-i8n="Ticket Types">Ticket Types</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/tickets') ?>" class="menu-link">
                            <i class="fas fa-ticket-alt menu-icon"></i>
                            <div data-i8n="Tickets">Tickets</div>
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>
        <?php if (has_permissions('read', 'promo_code')) { ?>
            <li class="menu-item">
                <a href="<?= base_url('admin/promo-code/manage-promo-code') ?>" class="menu-link">
                    <i class="menu-icon fa fa-puzzle-piece text-warning"></i>
                    <div data-i18n="code">code</div>
                </a>
            </li>
        <?php } ?>
        <?php if (has_permissions('read', 'featured_section')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-layer-group text-danger"></i>
                    <div data-i18n="Sections">Sections</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="<?= base_url('admin/featured-sections/') ?>" class="menu-link">
                            <i class="fas fa-folder-plus menu-icon"></i>
                            <div data-i8n="Manage Sections">Manage Sections</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/featured-sections/section-order') ?>"
                           class="menu-link">
                            <i class="fa fa-bars menu-icon"></i>
                            <div data-i8n="Sections Order">Sections Order</div>
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>
        <?php if (has_permissions('read', 'customers')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fa fa-user text-success"></i>
                    <div data-i18n="Customer">Customer</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="<?= base_url('admin/customer/') ?>" class="menu-link">
                            <i class="fas fa-users menu-icon"></i>
                            <div data-i8n=" View Customers "> View Customers </div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/customer/addresses') ?>" class="menu-link">
                            <i class="far fa-address-book menu-icon"></i>
                            <div data-i8n=" Addresses "> Addresses </div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/transaction/view-transaction') ?>" class="menu-link">
                            <i class="fas fa-money-bill-wave menu-icon "></i>
                            <div data-i8n=" Transactions "> Transactions </div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/transaction/customer-wallet') ?>" class="menu-link">
                            <i class="fas fa-wallet menu-icon "></i>
                            <div data-i8n="Wallet Transactions">Wallet Transactions</div>
                        </a>
                    </li>

                </ul>
            </li>
        <?php } ?>
        <?php if (has_permissions('read', 'delegates')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fa fa-qrcode text-success"></i>
                    <div data-i18n="Delegate">Delegate</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="<?= base_url('admin/delegate/') ?>" class="menu-link">
                            <i class="fas fa-users menu-icon"></i>
                            <div data-i8n=" View Delegates "> View Delegates </div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/delegate/visit_delegate') ?>" class="menu-link">
                            <i class="far fa-eye-low-vision menu-icon"></i>
                            <div data-i8n=" Visit Delegate "> Visit Delegate </div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/delegate/mission_delegate') ?>" class="menu-link">
                            <i class="fas fa-money-bill-wave menu-icon "></i>
                            <div data-i8n=" mission_delegate "> mission_delegate </div>
                        </a>
                    </li>

                </ul>
            </li>
        <?php } ?>
        <?php if (has_permissions('read', 'delivery_boy') || has_permissions('read', 'fund_transfer')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-id-card-alt text-info"></i>
                    <div data-i18n="Boys">Boys</div>
                </a>
                <ul class="menu-sub">
                    <?php if (has_permissions('read', 'delivery_boy')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/delivery-boys/manage-delivery-boy') ?>" class="menu-link text-sm">
                                <i class="fas fa-user-cog menu-icon "></i>
                                <div data-i18n="Manage Delivery Boys "> Manage Delivery Boys </div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'fund_transfer')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/fund-transfer/') ?>" class="menu-link">
                                <i class="fa fa-rupee-sign menu-icon "></i>
                                <div data-i18n="Fund Transfer">Fund Transfer</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'delivery_boy')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/delivery-boys/manage-cash') ?>" class="menu-link text-sm">
                                <i class="fas fa-money-bill-alt menu-icon "></i>
                                <div data-i18n="Manage Cash Collection "> Manage Cash Collection </div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

        <?php if (has_permissions('read', 'send_notification')) { ?>
            <li class="menu-item">
                <a href="<?= base_url('admin/Notification-settings/manage-notifications') ?>"
                   class="menu-link">
                    <i class="menu-icon fas fa-paper-plane text-success"></i>
                    <div data-i18n="Notification">Notification</div>
                </a>
            </li>
        <?php } ?>
        <?php if (has_permissions('read', 'settings')) { ?>
            <li class="menu-item">
                <a href="<?= base_url('admin/custom_notification') ?>" class="menu-link">
                    <i class="menu-icon fas fa-bell text-info"></i>
                    <div data-i18n="message">message</div>
                </a>
            </li>
        <?php } ?>
        <?php if (has_permissions('read', 'settings')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fa fa-wrench text-primary"></i>
                    <div data-i18n="System">System</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="<?= base_url('admin/setting') ?>" class="menu-link">
                            <i class="fas fa-store menu-icon "></i>
                            <div data-i8n="Store Settings">Store Settings</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/email-settings') ?>" class="menu-link">
                            <i class="fas fa-envelope-open-text menu-icon "></i>
                            <div data-i8n="Email Settings">Email Settings</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/payment-settings') ?>" class="menu-link">
                            <i class="fas fa-rupee-sign menu-icon "></i>
                            <div data-i8n="Payment Methods">Payment Methods</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/time-slots') ?>" class="menu-link">
                            <i class="fas fa-calendar-alt menu-icon "></i>
                            <div data-i8n="Time Slots">Time Slots</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/notification-settings') ?>" class="menu-link">
                            <i class="fa fa-bell menu-icon "></i>
                            <div data-i8n="Notification Settings">Notification Settings</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/contact-us') ?>" class="menu-link">
                            <i class="fa fa-phone-alt menu-icon "></i>
                            <div data-i8n="Contact Us">Contact Us</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/about-us') ?>" class="menu-link">
                            <i class="fas fa-info-circle menu-icon "></i>
                            <div data-i8n="About Us">About Us</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/privacy-policy') ?>" class="menu-link">
                            <i class="fa fa-user-secret menu-icon "></i>
                            <div data-i8n="Privacy Policy">Privacy Policy</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/privacy-policy/shipping-policy') ?>" class="menu-link">
                            <i class="fa fa-shipping-fast menu-icon "></i>
                            <div data-i8n="Shipping Policy">Shipping Policy</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/privacy-policy/return-policy') ?>" class="menu-link">
                            <i class="fa fa-undo menu-icon "></i>
                            <div data-i8n="Return Policy">Return Policy</div>
                        </a>
                    </li>
                    <li class="menu-item text-sm">
                        <a href="<?= base_url('admin/admin-privacy-policy') ?>" class="menu-link">
                            <i class="fa fa-exclamation-triangle menu-icon  "></i>
                            <div data-i8n="Admin Policies">Admin Policies</div>
                        </a>
                    </li>
                    <li class="menu-item text-sm">
                        <a href="<?= base_url('admin/delivery-boy-privacy-policy') ?>" class="menu-link">
                            <i class="fa fa-exclamation-triangle menu-icon  "></i>
                            <div data-i8n="Delivery Boy Policies">Delivery Boy Policies</div>
                        </a>
                    </li>
                    <li class="menu-item text-sm">
                        <a href="<?= base_url('admin/seller-privacy-policy') ?>" class="menu-link">
                            <i class="fa fa-exclamation-triangle menu-icon  "></i>
                            <div data-i8n="Seller Policies">Seller Policies</div>
                        </a>
                    </li>
                    <li class="menu-item text-sm">
                        <a href="<?= base_url('admin/client-api-keys/') ?>" class="menu-link">
                            <i class="fa fa-key menu-icon  "></i>
                            <div data-i8n="Client Api Keys">Client Api Keys</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/updater') ?>" class="menu-link">
                            <i class="fas fa-sync menu-icon "></i>
                            <div data-i8n="System Updater">System Updater</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/purchase-code') ?>" class="menu-link">
                            <i class="fas fa-check menu-icon"></i>
                            <div data-i8n="System Registration">System Registration</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fa fa-globe-asia text-warning"></i>
                    <div data-i18n="Settings">Settings</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="<?= base_url('admin/web-setting') ?>" class="menu-link">
                            <i class="fa fa-laptop menu-icon "></i>
                            <div data-i8n="General Settings">General Settings</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/themes') ?>" class="menu-link">
                            <i class="fa fa-palette menu-icon "></i>
                            <div data-i8n="Themes">Themes</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/language') ?>" class="menu-link">
                            <i class="fa fa-language menu-icon "></i>
                            <div data-i8n="Languages">Languages</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('admin/web-setting/firebase') ?>" class="menu-link">
                            <i class="fa fa-language menu-icon "></i>
                            <div data-i8n="Firebase">Firebase</div>
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>
        <?php if (has_permissions('read', 'area') || has_permissions('read', 'city') || has_permissions('read', 'zipcodes')) { ?>
            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-map-marked-alt text-danger"></i>
                    <div data-i18n="Location">Location</div>
                </a>
                <ul class="menu-sub">
                    <?php if (has_permissions('read', 'zipcodes')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/area/manage-zipcodes') ?>" class="menu-link">
                                <i class="fa fa-map-pin menu-icon "></i>
                                <div data-i8n="Zipcodes">Zipcodes</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'city')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/area/manage-cities') ?>" class="menu-link">
                                <i class="fa fa-location-arrow menu-icon "></i>
                                <div data-i8n="City">City</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'area')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/area/manage-areas') ?>" class="menu-link">
                                <i class="fas fa-street-view menu-icon "></i>
                                <div data-i18n="Areas">Areas</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'area')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/area/manage_countries') ?>" class="menu-link">
                                <i class="fas fa-solid fa-globe menu-icon "></i>
                                <div data-i18n="Countries">Countries</div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (has_permissions('read', 'area') && has_permissions('read', 'city') && has_permissions('read', 'zipcodes')) { ?>
                        <li class="menu-item">
                            <a href="<?= base_url('admin/area/location-bulk-upload') ?>" class="menu-link">
                                <i class="fas fa-upload menu-icon"></i>
                                <div data-i8n="Bulk upload">Bulk upload</div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

        <li class="menu-item">
            <a href="#" class="menu-link menu-toggle">
                <i class="fas fa-chart-pie menu-icon text-primary"></i>
                <div data-18n="Reports">Reports</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="<?= base_url('admin/sales-report') ?>" class="menu-link">
                        <i class="fa fa-chart-line menu-icon "></i>
                        <div data-18n="Sales Report">Sales Report</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= base_url('admin/sales-inventory') ?>" class="menu-link">
                        <i class="fa fa-chart-line menu-icon "></i>
                        <div data-18n="Sale Inventory Reports">Sale Inventory Reports</div>
                    </a>
                </li>
            </ul>
        </li>

        <?php if (has_permissions('read', 'faq')) { ?>
            <li class="menu-item">
                <a href="<?= base_url('admin/faq/') ?>" class="menu-link">
                    <i class="menu-icon fas fa-question-circle text-warning"></i>
                    <div data-18n="FAQ">FAQ</div>
                </a>
            </li>
        <?php }
        $userData = get_user_permissions($this->session->userdata('user_id'));
        if (!empty($userData)) {
            if ($userData[0]['role'] == 0 || $userData[0]['role'] == 1) {
                ?>
                <li class="menu-item mb-4">
                    <a href="<?= base_url('admin/system-users/') ?>" class="menu-link">
                        <i class="menu-icon fas fa-user-tie text-danger"></i>
                        <div data-18n="System Users">System Users</div>
                    </a>
                </li>
                <?php
            }
        } ?>

    </ul>
    <!-- /.sidebar -->
</aside>