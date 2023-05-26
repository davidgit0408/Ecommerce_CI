<!DOCTYPE html>
<html>
<?php $this->load->view('delivery_boy/include-head.php'); ?>
<div id="loading">
    <div class="lds-ring">
        <div></div>
    </div>
</div>

<body class="hold-transition sidebar-mini layout-fixed ">
    <div class=" wrapper ">
        <?php $this->load->view('delivery_boy/include-navbar.php') ?>
        <?php $this->load->view('delivery_boy/include-sidebar.php'); ?>
        <?php $this->load->view('delivery_boy/pages/' . $main_page); ?>
        <?php $this->load->view('delivery_boy/include-footer.php'); ?>
    </div>
    <?php $this->load->view('delivery_boy/include-script.php'); ?>
</body>

</html>