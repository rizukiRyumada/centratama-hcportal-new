<!-- main navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-primary ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- control sidebar trigger -->
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-cogs"></i>
            </a>
        </li>
        <!-- user image (optional) -->
        <!-- <li class="nav-item user-panel d-flex mt-1">
            <a class="nav-link image p-0">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
                <!-- <i class="fa fa-user-circle fa-2x"></i> -->
            <!-- </a>
        </li> -->
        <!-- user menu -->
        <!-- <li class="nav-item dropdown">
            <a id="userDropDown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="nav-link dropdown-toggle"><?= $user['emp_name'] ?></a>
            <ul aria-labelledby="userDropDown" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                <li><a href="<?= base_url('profile'); ?>" class="dropdown-item"><i class="fa fa-user"></i> <span class="ml-1">My Profile</span></a></li>
                <li class="dropdown-divider"></li>
                <li><a href="<?= base_url('login/logout') ?>" class="dropdown-item text-danger"><i class="fa fa-sign-out-alt"></i> <span class="ml-1">Logout</span></a></li>
            </ul>
        </li> -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="<?php if($user['exist_empPhoto'] == true){ echo base_url('/assets/img/employee/'.$this->session->userdata('nik').'.jpg'); } else { echo base_url('/assets/img/user.svg'); } ?>" class="user-image img-circle elevation-2" alt="img" width="160px" height="160px">
                <!-- PRODUCTION ganti alamat gambar jadi /user-img/ -->
                <!-- <img src="<?php if($user['exist_empPhoto'] == true){ echo base_url('/user-img/'.$this->session->userdata('nik').'.jpg'); } else { echo base_url('/assets/img/user.svg'); } ?>" class="user-image img-circle elevation-2" alt="img" width="160px" height="160px"> -->
                <span class="d-none d-md-inline"><?= $user['emp_name'] ?> <i class="fa fa-angle-down ml-2"></i></span>
            </a>
            <ul aria-labelledby="userDropDown" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                <li><a href="<?= base_url('profile'); ?>" class="dropdown-item"><i class="fa fa-user"></i> <span class="ml-1">My Profile</span></a></li>
                <li class="dropdown-divider"></li>
                <li><a href="<?= base_url('login/logout') ?>" class="dropdown-item text-danger"><i class="fa fa-sign-out-alt"></i> <span class="ml-1">Logout</span></a></li>
            </ul>
        </li>
    </ul>
</nav><!-- /main navbar -->