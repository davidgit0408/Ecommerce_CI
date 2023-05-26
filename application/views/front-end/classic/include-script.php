<!-- IziModal -->

<script src="<?= THEME_ASSETS_URL . 'js/eshop-bundle-js.js' ?>"></script>

<!-- Firebase.js -->
<script src="<?= THEME_ASSETS_URL . 'js/firebase-app.js' ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'js/firebase-auth.js' ?>"></script>
<script src="<?= base_url('firebase-config.js') ?>"></script>
<!-- Custom -->
<?php if ($this->session->flashdata('message')) { ?>
    <script>
        Toast.fire({
            icon: '<?= $this->session->flashdata('message_type'); ?>',
            title: "<?= $this->session->flashdata('message'); ?>"
        });
    </script>
<?php } ?>

<!-- Dark mode -->
<script src="<?= THEME_ASSETS_URL . 'js/darkmode-min.js' ?>"></script>
