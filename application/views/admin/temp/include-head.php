<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?></title>
    <!-- Tell the browser to be responsive to screen width -->
<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <link rel="icon" href="<?= base_url() . get_settings('favicon') ?>" type="image/gif" sizes="16x16">

    <!-- BEGIN OF ORIGIN-->
    <?php
    $permits = array(
        "home",
        "tables/manage-orders",
        "tables/order-tracking",
        "tables/manage-system-notification",
        "tables/manage-category",
        "tables/category-order",
        "tables/manage-brands",
//        "forms/brand",
        "forms/brand-bulk-upload",
        "tables/category-order",
        "tables/manage-seller",
        "tables/seller-wallet",
        "tables/manage-attribute",
        "tables/manage-taxes",
        "forms/product"
    );

    if (1) { ?>
    <!-- Bootstrap Switch -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/bootstrap-switch.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/all.min.css') ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/ionicons.min.css') ?>">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/tempusdominus-bootstrap-4.min.css') ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/icheck-bootstrap.min.css') ?>">
    <!-- Dropzone -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/dropzone.css') ?>">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/jqvmap.min.css') ?>">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/ekko-lightbox/ekko-lightbox.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/dist/css/adminlte.css') ?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/OverlayScrollbars.min.css') ?>">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/daterangepicker.css') ?>">
    <!-- Tinymce -->
    <script src="<?= base_url('assets/admin/js/tinymce.min.js') ?>"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/iziToast.min.css') ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/select2-bootstrap4.min.css') ?>">
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/sweetalert2.min.css') ?>">
    <!-- Chartist -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/chartist.css') ?>">
    <!-- JS tree -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/style.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/star-rating.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/theme.css') ?>">
    <!-- intlTelInput -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/intlTelInput.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/lightbox.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/admin/css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/bootstrap-table.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/jquery.fancybox.min.css') ?>" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/custom/custom.css') ?>">
    <!-- END OF ORIGIN CSS -->

    <?php } ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/fonts/materialdesignicons.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/fonts/fontawesome.css') ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/libs/node-waves/node-waves.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/libs/typeahead-js/typeahead.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/libs/apex-charts/apex-charts.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/libs/swiper/swiper.css') ?>" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/css/pages/cards-statistics.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/css/pages/cards-analytics.css') ?>" />
    <!-- Helpers -->
    <script src="<?= base_url('assets/admin/vendor/js/helpers.js') ?>"></script>
    <script src="<?= base_url('assets/admin/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/admin/jquery-ui/jquery-ui.min.js') ?>"></script>


    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?= base_url('assets/admin/vendor/js/template-customizer.js') ?>"></script>


    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/css/rtl/core.css" class="template-customizer-core-css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/demo.css') ?> "/>

    <!-- ChartJS -->

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/libs/datatables-bs5/datatables.bootstrap5.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') ?>" />

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= base_url('assets/admin/js/config.js') ?>"></script>
    <!-- Star rating js -->
    <script type="text/javascript" src="<?= base_url('assets/admin/js/star-rating.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/admin/js/theme.min.js') ?>"></script>

    <script type="text/javascript">
        base_url = "<?= base_url() ?>";
        csrfName = "<?= $this->security->get_csrf_token_name() ?>";
        csrfHash = "<?= $this->security->get_csrf_hash() ?>";
        form_name = '<?= '#' . $main_page . '_form' ?>';
    </script>

    <style>
        #template-customizer {
            display: none !important;
        }
        .btn {
            min-height: 38px;
        }
        .chart-height {
            height: 500px;
        }
    </style>
</head>
