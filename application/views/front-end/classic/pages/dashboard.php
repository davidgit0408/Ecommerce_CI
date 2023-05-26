<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('my_account')) ? $this->lang->line('my_account') : 'My Account' ?></a></li>
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
        <div class="row mb-5">
            <div class="col-md-4">
                <?php $this->load->view('front-end/' . THEME . '/pages/my-account-sidebar') ?>
            </div>
            <div class="col-md-8 col-12 row">
                <div class='col-md-3 card text-center border-0 mr-3 mb-3'>
                    <a href='<?= base_url('my-account/profile') ?>' class="link-color">
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('profile')) ? $this->lang->line('profile') : 'PROFILE' ?>
                        </div>
                        <div class='card-body'>
                            <i class="fa fa-user-circle dashboard-icon link-color fa-lg"></i>
                        </div>
                    </a>
                </div>
                <div class='col-md-3 card text-center border-0 mr-3 mb-3'>
                    <a href='<?= base_url('my-account/orders') ?>' class="link-color">
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('orders')) ? $this->lang->line('orders') : 'ORDERS' ?>
                        </div>
                        <div class='card-body'>
                            <i class="fas fa-history dashboard-icon link-color fa-lg"></i>
                        </div>
                    </a>
                </div>
                <div class='col-md-3 card text-center border-0 mr-3 mb-3'>
                    <a href='<?= base_url('my-account/notifications') ?>' class="link-color">
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('notification')) ? $this->lang->line('notification') : 'NOTIFICATION' ?>
                        </div>
                        <div class='card-body'>
                            <i class="far fa-bell dashboard-icon link-color fa-lg"></i>
                        </div>
                    </a>
                </div>
                <div class='col-md-3 card text-center border-0 mr-3 mb-3'>
                    <a href='<?= base_url('my-account/Favorite') ?>' class="link-color">
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('favorite')) ? $this->lang->line('favorite') : 'Favorite' ?>
                        </div>
                        <div class='card-body'>
                            <i class="far fa-heart dashboard-icon link-color fa-lg"></i>
                        </div>
                    </a>
                </div>
                <div class='col-md-3 card text-center border-0 mr-3 mb-3'>
                    <a href='<?= base_url('my-account/manage-address') ?>' class="link-color">
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('address')) ? $this->lang->line('address') : 'ADDRESS' ?>
                        </div>
                        <div class='card-body'>
                            <i class="far fa-id-badge dashboard-icon link-color fa-lg"></i>
                        </div>
                    </a>
                </div>
                <div class='col-md-3 card text-center border-0 mr-3 mb-3'>
                    <a href='<?= base_url('my-account/wallet') ?>' class="link-color">
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('wallet')) ? $this->lang->line('wallet') : 'WALLET' ?>
                        </div>
                        <div class='card-body'>
                            <i class="fa fa-wallet dashboard-icon link-color fa-lg"></i>
                        </div>
                    </a>
                </div>
                <div class='col-md-3 card text-center border-0 mr-3 mb-3'>
                    <a href='<?= base_url('my-account/transactions') ?>' class="link-color">
                        <div class='card-header bg-transparent'>
                            <?= !empty($this->lang->line('transaction')) ? $this->lang->line('transaction') : 'TRANSACTION' ?>
                        </div>
                        <div class='card-body'>
                            <i class="fas fa-exchange-alt dashboard-icon link-color fa-lg"></i>
                        </div>
                    </a>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>