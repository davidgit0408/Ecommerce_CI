<?php
    $settings = get_settings('system_settings', true);
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme sidebar">

    <!-- Brand Logo -->
    <div class="app-brand demo">
        <a href="<?= base_url('seller/home') ?>" class="app-brand-link">
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
            <a href="<?= base_url('/seller/home') ?>" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div data-i18n="Home">Home</div>
            </a>
        </li>

            <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-shopping-cart text-warning"></i>
                    <div data-i18n="Orders">Orders</div>
                </a>
                <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="<?= base_url('seller/orders/') ?>" class="menu-link">
                                <i class="fa fa-shopping-cart menu-icon"></i>
                                <div data-i18n="Orders">Orders</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('seller/orders/order-tracking') ?>" class="menu-link">
                                <i class="fa fa-map-marker-alt menu-icon"></i>
                                <div data-i18n="Order Tracking">Order Tracking</div>
                            </a>
                        </li>
                </ul>
            </li>

            <li class="menu-item">
                <a href="<?= base_url('seller/category/') ?>" class="menu-link">
                    <i class="menu-icon fas fa-bullseye text-success"></i>
                    <div data-i18n="Categories">Categories</div>
                </a>
            </li>

        <li class="menu-item">
            <a href="<?= base_url('seller/point_of_sale/') ?>" class="menu-link">
                <i class="menu-icon fas fa-calculator"></i>
                <div data-i18n="Point Of Sale">Point Of Sale</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="<?= base_url('seller/reel/') ?>" class="menu-link">
                <i class="menu-icon fas fa-video text-danger"></i>
                <div data-i18n="Reels">Reels</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="<?= base_url('seller/manage_stock') ?>" class="menu-link">
                <i class="menu-icon fa fa-cube text-success"></i>
                <div data-i18n="Manage Stock">Manage Stock</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon fa fa-user text-success"></i>
                <div data-i18n="Customer">Customer</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="<?= base_url('seller/customer') ?>" class="menu-link">
                        <i class="fas fa-users menu-icon"></i>
                        <div data-i8n=" View Customers "> View Customers </div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item ">
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-cubes text-primary"></i>
                <div data-i18n="Products">Products</div>
            </a>

            <ul class="menu-sub">

                <li class="menu-item">
                    <a href="<?= base_url('seller/attribute_set/') ?>"
                       class="menu-link">
                        <i class="fa fa-cogs menu-icon"></i>
                        <div data-i18n="Attribute Sets">Attribute Sets</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= base_url('seller/attributes/') ?>"
                       class="menu-link">
                        <i class="fas fa-sliders-h menu-icon"></i>
                        <div data-i18n="Attributes">Attributes</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= base_url('seller/attribute_value/') ?>"
                       class="menu-link">
                        <i class="fas fa-sliders-h menu-icon"></i>
                        <div data-i18n="Attribute Values">Attribute Values</div>
                    </a>
                </li>
                    <li class="menu-item">
                        <a href="<?= base_url('seller/taxes/') ?>" class="menu-link">
                            <i class="fas fa-percentage menu-icon"></i>
                            <div data-i8n="Tax">Tax</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('seller/product/create-product') ?>" class="menu-link">
                            <i class="fas fa-plus-square menu-icon"></i>
                            <div data-i8n="Add Products">Add Products</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('seller/product/bulk-upload') ?>" class="menu-link">
                            <i class="fas fa-upload menu-icon"></i>
                            <div data-i8n="Bulk upload">Bulk upload</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('seller/product/') ?>" class="menu-link">
                            <i class="fas fa-boxes menu-icon"></i>
                            <div data-i8n="Manage Products">Manage Products</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= base_url('seller/product_faqs/') ?>" class="menu-link">
                            <i class="fas fa-question-circle menu-icon"></i>
                            <div data-i8n="Product FAQs">Product FAQs</div>
                        </a>
                    </li>
            </ul>
        </li>

        <li class="menu-item">
            <a href="<?= base_url('seller/media/') ?>" class="menu-link">
                <i class="menu-icon fas fa-icons text-danger"></i>
                <div data-i18n="Media">Media</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="<?= base_url('seller/transaction/wallet-transactions') ?>" class="menu-link">
                <i class="menu-icon fa fa-rupee-sign text-warning"></i>
                <div data-i18n="Wallet Transactions">Wallet Transactions</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="<?= base_url('seller/payment-request/withdrawal-requests') ?>" class="menu-link">
                <i class="menu-icon fas fa-money-bill-wave text-danger"></i>
                <div data-i18n="Withdrawal Requests">Withdrawal Requests</div>
            </a>
        </li>

        <li class="menu-item">
                <a href="#" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-map-marked-alt text-danger"></i>
                    <div data-i18n="Location">Location</div>
                </a>
                <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="<?= base_url('seller/area/manage-zipcodes') ?>" class="menu-link">
                                <i class="fa fa-map-pin menu-icon "></i>
                                <div data-i8n="Zipcodes">Zipcodes</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('seller/area/manage-cities') ?>" class="menu-link">
                                <i class="fa fa-location-arrow menu-icon "></i>
                                <div data-i8n="City">City</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('seller/area/manage-areas') ?>" class="menu-link">
                                <i class="fas fa-street-view menu-icon "></i>
                                <div data-i18n="Areas">Areas</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= base_url('seller/area/manage_countries') ?>" class="menu-link">
                                <i class="fas fa-solid fa-globe menu-icon "></i>
                                <div data-i18n="Countries">Countries</div>
                            </a>
                        </li>
                </ul>
            </li>

        <li class="menu-item">
            <a href="#" class="menu-link menu-toggle">
                <i class="fas fa-chart-pie menu-icon text-primary"></i>
                <div data-18n="Reports">Reports</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="<?= base_url('seller/sales-report') ?>" class="menu-link">
                        <i class="fa fa-chart-line menu-icon "></i>
                        <div data-18n="Sales Report">Sales Report</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= base_url('seller/sales-inventory') ?>" class="menu-link">
                        <i class="fa fa-chart-line menu-icon "></i>
                        <div data-18n="Sale Inventory Reports">Sale Inventory Reports</div>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
    <!-- /.sidebar -->
</aside>