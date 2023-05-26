<!-- Izimodal -->
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'css/iziModal.min.css' ?>" />
<!-- Favicon -->
<?php $favicon = get_settings('web_favicon');

$path = ($is_rtl == 1) ? 'rtl/' : "";
?>
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'css/eshop-bundle.css' ?>" />
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'css/' . $path . 'eshop-bundle-main.css' ?>">
<link rel="icon" href="<?= base_url($favicon) ?>" type="image/gif" sizes="16x16">

<!-- Color CSS -->
<link rel="stylesheet" href="<?= THEME_ASSETS_URL . 'css/colors/peach.css' ?>" id="color-switcher">

<!-- Jquery -->
<script src="<?= THEME_ASSETS_URL . 'js/jquery.min.js' ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'js/eshop-bundle-top-js.js' ?>"></script>
<script type="text/javascript">
    base_url = "<?= base_url() ?>";
    currency = "<?= $settings['currency'] ?>";
    csrfName = "<?= $this->security->get_csrf_token_name() ?>";
    csrfHash = "<?= $this->security->get_csrf_hash() ?>";
</script>