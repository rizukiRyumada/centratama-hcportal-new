<!DOCTYPE html>
<html lang="en">
<head>
      <!-- head settings -->
    <?php $this->load->view('_komponen/login/head_login'); ?>
</head>
<body class="layout-top-nav m-0" >
    <!-- load preloader -->
    <?php $this->load->view('_komponen/preloader_v'); ?>
    <!-- floating contact -->
    <?php $this->load->view('_komponen/floating_contact') ?>
    <!-- load view -->
    <?php $this->load->view($load_view);?>

</body>

<!-- login script file -->
<?php $this->load->view('_komponen/login/script_login'); ?>
<!-- load preloader -->
<?php $this->load->view('_komponen/preloader_script'); ?>

</html>