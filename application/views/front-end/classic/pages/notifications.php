<!-- breadcrumb -->
<section class="breadcrumb-title-bar colored-breadcrumb">
    <div class="main-content responsive-breadcrumb">
        <h2><?= !empty($this->lang->line('notification')) ? $this->lang->line('notification') : 'Notifications' ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= !empty($this->lang->line('home')) ? $this->lang->line('home') : 'Home' ?>
                <li class="breadcrumb-item"><a href="#"><?= !empty($this->lang->line('notification')) ? $this->lang->line('notification') : 'Notifications' ?></a></li>
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
                <div class=' border-0'>
                    <div class="card-header bg-white">
                        <h1 class="h4"><?= !empty($this->lang->line('notification')) ? $this->lang->line('notification') : 'Notifications' ?></h1>
                    </div>
                    <div class="card-body">
                        <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/Notification_settings/get_notification_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true"><?= !empty($this->lang->line('id')) ? $this->lang->line('id') : 'ID' ?></th>
                                    <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('title')) ? $this->lang->line('title') : 'Title' ?></th>
                                    <th data-field="message" data-sortable="true"><?= !empty($this->lang->line('message')) ? $this->lang->line('message') : 'Message' ?></th>
                                    <th data-field="image" data-sortable="false" class="col-md-5"><?= !empty($this->lang->line('image')) ? $this->lang->line('image') : 'Image' ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>