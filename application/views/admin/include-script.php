<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- Bootstrap 4 -->


<script src="<?= base_url('assets/admin/js/bootstrap.bundle.min.js') ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url('assets/admin/jquery-ui/jquery-ui.min.js') ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Ekko Lightbox -->

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
<script type="text/javascript" src="<?= base_url('assets/admin/js/qrcode.js') ?>"></script>
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
<!-- Custom -->
<script src="<?= base_url('assets/admin/custom/custom.js') ?>"></script>
<!-- Demo -->
<script src="<?= base_url('assets/admin/dist/js/demo.js') ?>"></script>



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