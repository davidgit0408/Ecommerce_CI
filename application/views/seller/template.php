<!DOCTYPE html>
<html
        lang="en"
        class="light-style layout-navbar-fixed layout-menu-fixed"
        dir="ltr"
        data-theme="theme-default"
        data-assets-path="<?= base_url('assets/admin/') ?>"
        data-template="vertical-menu-template">
<?php
$this->load->view('seller/temp/include-head.php');
$prefix = 'seller/temp/';
?>
<div id="loading">
    <div class="lds-ring">
        <div></div>
    </div>
</div>
<body class="hold-transition sidebar-mini layout-fixed ">

<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <!-- Menu -->
        <?php $this->load->view($prefix . 'include-sidebar.php'); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

            <!-- Navbar -->
            <?php $this->load->view($prefix . 'include-navbar.php'); ?>
            <!-- / Navbar -->


            <!-- Content wrapper -->
            <div>

                <!-- Content -->
                <?php  $this->load->view('seller/pages/' . $main_page); ?>
                <!-- / Content -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    <!-- Footer -->
                    <?php $this->load->view($prefix . 'include-footer.php'); ?>
                    <!-- / Footer -->
                </div>

                <div class="content-backdrop fade"></div>

            </div>
            <!-- Content wrapper -->

        </div>
        <!-- / Layout page -->

    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->

<?php $this->load->view($prefix . 'include-script.php'); ?>
</body>

</html>