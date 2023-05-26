<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->

<script src="<?= base_url('assets/admin/js/bootstrap.bundle.min.js') ?>"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Ekko Lightbox -->

<?php
    $permits = array(
        "home",
        "tables/manage-orders",
        "tables/order-tracking",
        "tables/manage-system-notification",
        "tables/manage-category",
        "tables/category-order",
        "tables/manage-brands",
        "forms/brand",
        "forms/brand-bulk-upload",
        "tables/category-order",
        "tables/manage-seller",
        "tables/seller-wallet",
        "tables/manage-attribute",
        "tables/manage-taxes",
        "forms/product"
    );

 if (1) { ?>

<script src=<?= base_url('assets/admin/ekko-lightbox/ekko-lightbox.min.js') ?>></script>
<!-- google translate library -->
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


<!-- ChartJS -->
<script src="<?= base_url('assets/admin/chart.js/Chart.min.js') ?>"></script>
<!-- Sparkline -->
<script src="<?= base_url('assets/admin/js/sparkline.js') ?>"></script>
<!-- JQVMap -->
<script src="<?= base_url('assets/admin/js/jquery.vmap.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/jquery.vmap.usa.js') ?>"></script>
<!-- jQuery Knob Chart -->
<script src="<?= base_url('assets/admin/js/jquery.knob.min.js') ?>"></script>
<!-- daterangepicker -->
<script src="<?= base_url('assets/admin/js/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/daterangepicker.js') ?>"></script>



<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url('assets/admin/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<!-- Toastr -->
<script src="<?= base_url('assets/admin/js/iziToast.min.js') ?>"></script>
<!-- Select -->
<script src="<?= base_url('assets/admin/js/select2.full.min.js') ?>"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url('assets/admin/js/jquery.overlayScrollbars.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/admin/dist/js/adminlte.js') ?>"></script>
<!-- Bootstrap Switch -->
<script src="<?= base_url('assets/admin/js/bootstrap-switch.min.js') ?>"></script>
<!-- Bootstrap Table -->
<script src="<?= base_url('assets/admin/js/bootstrap-table.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/tableExport.js') ?>"></script>
<script src="<?= base_url('assets/admin/js//bootstrap-table-export.min.js"') ?>"></script>
<!-- Jquery Fancybox -->
<script src="<?= base_url('assets/admin/js/jquery.fancybox.min.js') ?>"></script>


<!-- Sweeta Alert 2 -->
<script src="<?= base_url('assets/admin/js/sweetalert2.min.js') ?>"></script>
<!-- Block UI -->
<script src="<?= base_url('assets/admin/js/jquery.blockUI.js') ?>"></script>
<!-- JS tree -->
<script src="<?= base_url('assets/admin/js/jstree.min.js') ?>"></script>
<!-- Chartist -->
<script src="<?= base_url('assets/admin/js/chartist.js') ?>"></script>
<!-- Tool Tip -->
<script src="<?= base_url('assets/admin/js/tooltip.js') ?>"></script>
<!-- Loader Js -->
<script type="text/javascript" src="<?= base_url('assets/admin/js/loader.js') ?>"></script>
<!-- Dropzone -->
<script type="text/javascript" src="<?= base_url('assets/admin/js/dropzone.js') ?>"></script>
<!-- Sortable.JS -->
<script type="text/javascript" src="<?= base_url('assets/admin/js/sortable.js') ?>"></script>
<!-- Sortable.min.js -->
 <!-- QRCode.JS -->
 <script type="text/javascript" src="<?= base_url('assets/admin/js/qrcode.min.js') ?>"></script>
 <!-- QRCode.min.js -->
<script type="text/javascript" src="<?= base_url('assets/admin/js/jquery-sortable.js') ?>"></script>

<script type="text/javascript" src="<?= base_url('assets/admin/js/tagify.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/admin/js/jquery.validate.min.js') ?>"></script>
<!-- Firebase.js -->
<script type="text/javascript" src="<?= base_url('assets/admin/js/firebase-app.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/admin/js/firebase-auth.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('firebase-config.js') ?>"></script>
<!-- intlTelInput -->
<script type="text/javascript" src="<?= base_url('assets/admin/js/intlTelInput.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/admin/js/lightbox.js') ?>"></script>

<?php } ?>


<script src="<?= base_url('assets/admin/vendor/libs/popper/popper.js') ?>"></script>
<script src="<?= base_url('assets/admin/vendor/js/bootstrap.js') ?>"></script>
<script src="<?= base_url('assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') ?>"></script>
<script src="<?= base_url('assets/admin/vendor/libs/node-waves/node-waves.js') ?>"></script>

<script src="<?= base_url('assets/admin/vendor/libs/hammer/hammer.js') ?>"></script>
<script src="<?= base_url('assets/admin/vendor/libs/i18n/i18n.js') ?>"></script>
<script src="<?= base_url('assets/admin/vendor/libs/typeahead-js/typeahead.js') ?>"></script>

<script src="<?= base_url('assets/admin/vendor/js/menu.js') ?>"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="<?= base_url('assets/admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js"') ?>"></script>
<script src="<?= base_url('assets/admin/js/chartist.js') ?>"></script>
<script src="<?= base_url('assets/admin/vendor/libs/apex-charts/apexcharts.js') ?>"></script>
<script src="<?= base_url('assets/admin/vendor/libs/swiper/swiper.js')?>"></script>

<script src="<?= base_url('assets/admin/vendor/libs/chartjs/chartjs.js')?>"></script>

<!-- Main JS -->
<script src="<?= base_url('assets/admin/js/main.js')?>"></script>
<!-- Page JS -->
<script src="<?= base_url('assets/admin/custom/custom.js') ?>"></script>
<!-- Demo -->
<script src="<?= base_url('assets/admin/dist/js/demo.js') ?>"></script>

<script src="<?= base_url('assets/admin/pages/home.js')?>"></script>


<?php if ($this->session->flashdata('message')) { ?>
    <script>
        Swal.fire('<?= $this->session->flashdata('message_type') ?>', "<?= $this->session->flashdata('message') ?>", '<?= $this->session->flashdata('message_type') ?>');
    </script>
<?php } ?>


<?php
if ($this->session->flashdata('authorize_flag')) { ?>
    <script>
        Swal.fire('Warning', "<?= $this->session->flashdata('authorize_flag') ?>", 'warning');
    </script>
<?php }
$this->session->set_flashdata('authorize_flag', "");

?>