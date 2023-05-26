<!DOCTYPE html>
<html lang="en">

<?php

$settings = get_settings('web_settings', true);
$primary_colour = (isset($settings['primary_color']) && !empty($settings['primary_color'])) ?  $settings['primary_color'] : '#FF6A65';
$secondary_colour = (isset($settings['secondary_color']) && !empty($settings['secondary_color'])) ?  $settings['secondary_color'] : '#FF6A65';
$font_color = (isset($settings['font_color']) && !empty($settings['font_color'])) ?  $settings['font_color'] : '#FFF';


?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <meta name="keywords" content='<?= $keywords ?>'>
    <meta name="description" content='<?= $description ?>'>


    <?php $cookie_lang = $this->input->cookie('language', TRUE);
    $path = $is_rtl = "";
    if (!empty($cookie_lang)) {
        $language = get_languages(0, $cookie_lang, 0, 1);
        if (!empty($language)) {
            $path = ($language[0]['is_rtl'] == 1) ? 'rtl/' : "";
            $is_rtl =  ($language[0]['is_rtl'] == 1) ? true : false;
        }
    } else {
        /* read the default language */
        $lang = $this->config->item('language');
        $language = get_languages(0, $lang, 0, 1);
        if (!empty($language)) {
            $path = ($language[0]['is_rtl'] == 1) ? 'rtl/' : "";
            $is_rtl =  ($language[0]['is_rtl'] == 1) ? true : false;
        }
    }
    $data['is_rtl'] = $is_rtl;
    ?>
    <?php $this->load->view('front-end/' . THEME . '/include-css', $data); ?>
    <style>
        * {
            --primary-color: <?= $primary_colour ?>;
            --secondary-color: <?= $secondary_colour ?>;
            --font-color: <?= $font_color ?>;
        }
    </style>
</head>

<body id="body" data-is-rtl='<?= $is_rtl ?>'>
    <?php $this->load->view('front-end/' . THEME . '/header'); ?>
    <?php $this->load->view('front-end/' . THEME . '/pages/' . $main_page); ?>
    <?php $this->load->view('front-end/' . THEME . '/footer'); ?>
    <?php $this->load->view('front-end/' . THEME . '/include-script'); ?>

</body>


</html>