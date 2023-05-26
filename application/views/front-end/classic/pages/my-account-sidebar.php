<?php $current_url = current_url(); ?>
<ul class="nav nav-pills nav-justified flex-column bg-white rounded shadow p-3 mb-0 my-account-tab" id="pills-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link rounded <?= ($current_url == base_url('my-account/profile')) ? 'active' : '' ?>" id="dashboard" href="<?= base_url('my-account/profile') ?>">
            <div class="text-left py-1 px-3 sidebar-text">
                <h6 class="mb-0">
                    <i class="fas fa-user-circle fa-lg left-aside"></i> <?= !empty($this->lang->line('profile')) ? $this->lang->line('profile') : 'PROFILE' ?>
                </h6>
            </div>
        </a>
    </li>
    <li class="nav-item mt-2">
        <a class="nav-link rounded <?= ($current_url == base_url('my-account/orders')) ? 'active' : '' ?>" id="order-history" href="<?= base_url('my-account/orders') ?>">
            <div class="text-left py-1 px-3 sidebar-text">
                <h6 class="mb-0">
                    <i class="fas fa-history fa-lg left-aside"></i> <?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'ORDERS' ?>
                </h6>
            </div>
        </a>
    </li>
    <li class="nav-item mt-2">
        <a class="nav-link rounded <?= ($current_url == base_url('my-account/notifications')) ? 'active' : '' ?>" id="notification" href="<?= base_url('my-account/notifications') ?>">
            <div class="text-left py-1 px-3 sidebar-text">
                <h6 class="mb-0">
                    <i class="fas fa-bell fa-lg left-aside"></i> <?= !empty($this->lang->line('notification')) ? $this->lang->line('notification') : 'NOTIFICATION' ?>
                </h6>
            </div>
        </a>
    </li>
    <li class="nav-item mt-2">
        <a class="nav-link rounded <?= ($current_url == base_url('my-account/favorites')) ? 'active' : '' ?>" id="wishlist" href="<?= base_url('my-account/favorites') ?>">
            <div class="text-left py-1 px-3 sidebar-text">
                <h6 class="mb-0">
                    <i class="far fa-heart fa-lg left-aside"></i> <?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'Favorite' ?>
                </h6>
            </div>
        </a>
    </li>
    <li class="nav-item mt-2">
        <a class="nav-link rounded <?= ($current_url == base_url('my-account/manage-address')) ? 'active' : '' ?>" id="v-pills-settings-tab" href="<?= base_url('my-account/manage-address') ?>" id="addresses" href="<?= base_url('my-account/manage-address') ?>">
            <div class="text-left py-1 px-3 sidebar-text">
                <h6 class="mb-0">
                    <i class="fas fa-map-marked-alt fa-lg left-aside"></i> <?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'ADDRESS' ?>
                </h6>
            </div>
        </a>
    </li>
    <li class="nav-item mt-2">
        <a class="nav-link rounded <?= ($current_url == base_url('my-account/wallet')) ? 'active' : '' ?>" id="wallet-details" href="<?= base_url('my-account/wallet') ?>">
            <div class="text-left py-1 px-3 sidebar-text">
                <h6 class="mb-0">
                    <i class="fas fa-wallet fa-lg left-aside"></i> <?= !empty($this->lang->line('wallet')) ? $this->lang->line('wallet') : 'WALLET' ?>
                </h6>
            </div>
        </a>
    </li>
    <li class="nav-item mt-2">
        <a class="nav-link rounded <?= ($current_url == base_url('my-account/transactions')) ? 'active' : '' ?>" id="transaction-details" href="<?= base_url('my-account/transactions') ?>">
            <div class="text-left py-1 px-3 sidebar-text">
                <h6 class="mb-0">
                    <i class="far fa-money-bill-alt fa-lg left-aside"></i> <?= !empty($this->lang->line('transaction')) ? $this->lang->line('transaction') : 'TRANSACTION' ?>
                </h6>
            </div>
        </a>
    </li>
    <li class="nav-item mt-2">
        <a class="nav-link rounded <?= ($current_url == base_url('login/logout')) ? 'active' : '' ?>" id="logout_btn" href="<?= base_url('login/logout') ?>">
            <div class="text-left py-1 px-3 sidebar-text">
                <h6 class="mb-0">
                    <i class="fas fa-sign-out-alt fa-lg left-aside"></i> <?= !empty($this->lang->line('logout')) ? $this->lang->line('logout') : 'LOGOUT' ?>
                </h6>
            </div>
        </a>
    </li>
</ul>