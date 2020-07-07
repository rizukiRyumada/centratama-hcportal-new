<!DOCTYPE html>
<html lang="en">
<head>
      <!-- head settings -->
    <?php $this->load->view('komponen/login/head_login'); ?>
</head>
<body class="layout-top-nav m-0" >
    <!-- load preloader -->
    <?php $this->load->view('komponen/preloader_v'); ?>
    <!-- floating contact -->
    <?php $this->load->view('komponen/floating_contact') ?>
    <!-- load view -->
    <?php $this->load->view($load_view);?>

</body>

<!-- main script file -->
<?php $this->load->view('komponen/main_script'); ?>
<!-- load preloader -->
<?php $this->load->view('komponen/preloader_script'); ?>
<!-- login script file -->
<?php $this->load->view('komponen/login/script_login'); ?>

</html>