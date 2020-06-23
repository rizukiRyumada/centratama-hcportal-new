<!DOCTYPE html>
<html lang="en">
<head>

<!-- /* -------------------------------------------------------------------------- */ -->
<!-- /*                                  main head                                 */ -->
<!-- /* -------------------------------------------------------------------------- */ -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<!-- /* --------------------------------- styles --------------------------------- */ -->
    <!-- favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/img/logo3.png'); ?>">
    <!-- icons -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/node_modules/@fortawesome/fontawesome-free/css/all.min.css') ?>">
    <!-- Overlay Scrollbar -->
    <link rel="stylesheet" href="<?= base_url('/assets/vendor/node_modules/overlayscrollbars/css/OverlayScrollbars.min.css'); ?>">
    <!-- font -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700" rel="stylesheet"> -->
    <link href="<?= base_url('assets/font/open-sans.css'); ?>" rel="stylesheet">
    <!-- theme style -->
    <link rel="stylesheet" href="<?= base_url('/assets/vendor/node_modules/admin-lte/dist/css/adminlte.min.css'); ?>">
    <!-- toastr -->
    <link rel="stylesheet" href="<?= base_url('/assets/vendor/node_modules/toastr/build/toastr.min.css'); ?>">
    <!-- custom css style -->
    <link rel="stylesheet" href="<?= base_url('/assets/css/main_styles.css'); ?>">
    
    <link type="text/css" rel="stylesheet" id="light-mode-general-link">
    <link type="text/css" rel="stylesheet" id="light-mode-custom-link">
    <style type="text/css" id="light-mode-custom-style"></style>

</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed" style="height: auto;">
    
    <!-- website wrapper -->
    <div class="wrapper">
        
        <!-- main navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-primary ">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="../../index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li> -->
            </ul>
            
            <!-- SEARCH FORM -->
            <!-- <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form> -->
            
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li> -->
                <!-- control sidebar trigger -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-cogs"></i>
                    </a>
                </li>
                <!-- Sidebar user panel (optional) -->
                <li class="nav-item user-panel d-flex mt-1">
                    <a class="nav-link image p-0">
                        <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
                        <i class="fa fa-user-circle fa-2x"></i>
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a id="userDropDown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="nav-link dropdown-toggle">Karyawan</a>
                    <ul aria-labelledby="userDropDown" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                        <li><a href="#" class="dropdown-item"><i class="fa fa-user"></i> <span class="ml-1">My Profile</span></a></li>
                        <li class="dropdown-divider"></li>
                        <li><a href="#" class="dropdown-item text-danger"><i class="fa fa-sign-out-alt"></i> <span class="ml-1">Logout</span></a></li>
                    </ul>
                </li>

                
            </ul>
        </nav><!-- /main navbar -->

        <!-- main sidebar -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= base_url('survey'); ?>" class="brand-link">
                <img src="<?= base_url('assets'); ?>/img/logo3.png" alt="AdminLTE Logo" class="brand-image" style="opacity: .8" width="70px">
                <span class="brand-text font-weight-light"><img src="<?= base_url('assets'); ?>/img/logo2.png" alt="" width="120px"></span>
            </a>
            
            <!-- Sidebar -->
            <div class="sidebar nav-collapse-hide-child nav-pills">
                <!-- Sidebar user panel (optional) -->
                <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Alexander Pierce</a>
                    </div>
                </div> -->
                
                <!-- Sidebar Menu -->
                <nav class="mt-3">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu">
                        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                        <li class="nav-item has-treeview menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Survey
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link active">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Service Excellence</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Service Engagement</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>360 Review</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Simple Link
                                    <span class="right badge badge-danger">New</span>
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- control sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        
        <!-- content -->
        <div class="content-wrapper">
            <!-- content header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Starter Page</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Starter Page</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            
            <!-- content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- tema -->
                    <!-- penjelasan -->
                    <dv class="row m-3">
                            <div class="col-12 d-flex justify-content-center">
                                <div class="card form-card-wrapper p-4 border-top">
                                    <div class="row">
                                        <div class="col-12">
                                            <h1>Quarterly Service Excellence Survey for Q1 - 2020</h1>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <p>Survey Kepuasan ini dilakukan untuk menilai dan memberikan masukan bagi Departement terkait dalam 
                                                melaksanakan tugas dan fungsi Departementnya untuk mendukung dan/atau menunjang kinerja Departement 
                                                lainnya:</p>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <p class="keterangan-penilaian">Keterangan Penilaian: <br/>
                                                <span class="badge badge-danger">1</span> = 0%  |  Jauh Dibawah Harapan  |  "JDH" <br/>
                                                <span class="badge badge-warning">2</span> = 35%  |  Kurang Sesuai Harapan  |  "KSH" <br/>
                                                <span class="badge badge-info">3</span> = 70%  |  Sesuai Harapan  |  "SH" <br/>
                                                <span class="badge badge-success">4</span> = 100%  |  Melebihi Harapan  |  "MH"
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </dv>

                    <?php $x=1; //index buat pertanyaan ?>
                    <form id="formSurvey" action="<?= base_url('survey/'); ?>submitSurvey" method="post" autocomplete="on">
                        <!-- pertanyaan kepuasan -->
                        <?php foreach($survey1 as $v): ?>
                            <div class="row m-3">
                                <div class="col-12 d-flex justify-content-center">
                                    <div class="card form-card-wrapper"id="<?= $v['id']; ?>">
                                        <div class="card-body">
                                            <div class="row mt-2">
                                                <div class="pertanyaan-wrapper">
                                                    <div class="col-1 pertanyaan-index-wrapper pr-0"><b><?= $x; $x++; ?>.</b></div>
                                                    <div class="col-11 pertanyaan-text-wrapper pl-0">
                                                        <p><?= $v['pertanyaan'] ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <strong class="text-danger">*)Wajib diisi</strong>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <div class="form-survey-wrapper">
                                                        <div class="form-survey">
                                                            <div class="row row-survey row-survey-striped">
                                                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"></div>
                                                                <div class="col-7 departemen-name d-flex align-items-center m-0 p-0"><div class="text-center">Jawaban untuk<br/>Departemen</div></div>
                                                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><p class="text-center m-0 badge badge-danger">1<br/>0%</p></div>
                                                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><p class="text-center m-0 badge badge-warning">2<br/>35%</p></div>
                                                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><p class="text-center m-0 badge badge-info">3<br/>70%</p></div>
                                                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><p class="text-center m-0 badge badge-success">4<br/>100%</p></div>
                                                            </div>
                                                            <?php $y=1; ?>
                                                            <?php foreach($departemen as $value):
                                                                if($value['id'] != 0):?>
                                                                <div class="row row-survey">
                                                                    <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center">
                                                                        <p class="p-0 m-0">
                                                                            <?php switch($y){ 
                                                                                case 1:
                                                                                    echo "a.";
                                                                                    break;
                                                                                case 2:
                                                                                    echo "b.";
                                                                                    break;
                                                                                case 3:
                                                                                    echo "c.";
                                                                                    break;
                                                                                case 4:
                                                                                    echo "d.";
                                                                                    break;
                                                                                case 5:
                                                                                    echo "e.";
                                                                                    break;
                                                                                case 6:
                                                                                    echo "f.";
                                                                                    break;
                                                                            }
                                                                            $y++; ?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-7 departemen-name d-flex align-items-center pl-0"><?= $value['nama'] ?></div>
                                                                    <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check"><input class="form-check-input" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="1" required></div></div>
                                                                    <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check"><input class="form-check-input" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="2" required></div></div>
                                                                    <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check"><input class="form-check-input" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="3" required></div></div>
                                                                    <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check"><input class="form-check-input" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="4" required></div></div>
                                                                </div>
                                                                <?php endif;
                                                            endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>

                        <!-- pertanyaan kepuasan tipe 2 dengan dropdown list -->
                        <!-- <div class="row m-3">
                            <div class="col-12 d-flex justify-content-center">
                                <div class="card form-card-wrapper p-4">
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <p class="pertanyaan">Penilaian terhadap aspek Kecepatan Departement Berikut dalam menanggapi setiap bantuan dan pertanyaan dalam rangka 
                                                mendukung dan/atau menunjang kinerja Departement Saya</p>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <div class="form-survey-wrapper">
                                                <div class="form-survey">
                                                    <div class="row row-survey-striped">
                                                        <div class="col-7 departemen-name d-flex align-items-center">Departemen</div>
                                                        <div class="col-5 d-flex align-items-center text-center">Penilaian</div>
                                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-7 departemen-name d-flex align-items-center">Compensation & Benefit</div>
                                                        <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                                    </div>
                                                    <div class="row row-survey-striped">
                                                        <div class="col-7 departemen-name d-flex align-items-center">Organization Development</div>
                                                        <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-7 departemen-name d-flex align-items-center">General Affairs</div>
                                                        <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                                    </div>
                                                    <div class="row row-survey-striped">
                                                        <div class="col-7 departemen-name d-flex align-items-center">Procurement & Supply Chain</div>
                                                        <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-7 departemen-name d-flex align-items-center">Legal & Corsec</div>
                                                        <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center "><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                                    </div>
                                                    <div class="row row-survey-striped mb-3">
                                                        <div class="col-7 departemen-name d-flex align-items-center">Information Technology</div>
                                                        <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> ->

                        <!-- pertanyaan isian -->
                        <?php foreach($survey2 as $v): ?>
                            <div class="row m-3" id="<?= $v['id']; ?>">
                                <div class="col-12 d-flex justify-content-center">
                                    <div class="card form-card-wrapper">
                                        <div class="card-body">
                                            <div class="row mt-2">
                                                <div class="col-1 pertanyaan-index-wrapper pr-0"><b class="pertanyaan-index"><?= $x; $x++; ?>.</b></div>
                                                <div class="col-11 pertanyaan-text-wrapper pl-0">
                                                    <p class="pertanyaan"><?= $v['pertanyaan']; ?></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <strong class="text-danger">*)Wajib diisi</strong>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <textarea id="<?= $v['id_departemen']; ?>" name="<?= $v['id']; ?>_<?= $v['id_departemen'] ?>" class="form-control" rows="5" required placeholder="Jawaban untuk <?= $v['nama_departemen'] ?>" required></textarea>
                                                        <small id="feedback-<?= $v['id_departemen'] ?>" class="float-right">0/1000 Karakter</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>

                        <!-- tombol logout & submit -->
                        <div class="row m-3">
                            <div class="col-12 d-flex justify-content-center">
                                <div class="card form-card-wrapper p-4 w-100">
                                    <div class="row mt-2">
                                        <div class="col-6 d-block">
                                            <button id="logoutButton" type="button" class="btn btn-danger float-left" data-toggle="modal" data-target="#logoutModal"><i class="fa fa-sign-out-alt color-white fa-rotate-180"></i> Logout</button>
                                        </div>
                                        <div class="col-6 d-block">
                                            <button id="submitForm" type="submit" class="btn btn-success float-right d-none"  value="Submit">Submit <i class="fa fa-paper-plane color-white"></i></button>
                                            <button id="cekForm" type="button" class="btn btn-success float-right">Submit <i class="fa fa-paper-plane color-white"></i></button>
                                        </div>
                                        <!-- <div class="col-12">
                                            <div class="card d-none" id="konfirmSubmit">
                                                <div class="card-body">
                                                    Jawaban anda akan disimpan dan anda tidak dapat mengisi form ini lagi. <br/><br/>
                                                    
                                                    Silakan tunggu arahan apabila ada jadwal untuk mengisi form survey ini lagi, terima kasih.
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-success" value="Submit"><i class="fa fa-paper-plane color-white"></i> Ya, Submit</button>
                                                    <button id="cekLagiForm" type="button" class="btn btn-secondary" data-dismiss="card">Cek lagi <i class="fa fa-chevron-up color-white"></i></button>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                        
                    <!-- content -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    
                                    <p class="card-text">
                                        Some quick example text to build on the card title and make up the bulk of the card's
                                        content.
                                    </p>
                                    
                                    <a href="#" class="card-link">Card link</a>
                                    <a href="#" class="card-link">Another link</a>
                                </div>
                            </div>
                            
                            <div class="card card-primary card-outline">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    
                                    <p class="card-text">
                                        Some quick example text to build on the card title and make up the bulk of the card's
                                        content.
                                    </p>
                                    <a href="#" class="card-link">Card link</a>
                                    <a href="#" class="card-link">Another link</a>
                                </div>
                            </div><!-- /.card -->
                        </div>
                        <!-- /.col-md-6 -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="m-0">Featured</h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">Special title treatment</h6>
                                    
                                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                            
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Featured</h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">Special title treatment</h6>
                                    
                                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div><!-- /.col-md-6 -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div><!-- /content -->
        </div><!-- /content wrapper -->

        <!-- main footer -->
        <footer class="main-footer text-sm text-center">
            <!-- To the right -->
            <!-- <div class="float-right d-none d-sm-inline">
                Anything you want
            </div> -->
            <!-- Default to the left -->
            <strong>Copyright © 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
    </div><!-- /page wrapper -->
        
    <!-- script file -->
    <!-- jquery -->
    <script src="<?= base_url('/assets/vendor/node_modules/jquery/dist/jquery.min.js') ?>"></script>
    <!-- bootstrap -->
    <script src="<?= base_url('/assets/vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- adminlte -->
    <script src="<?= base_url('/assets/vendor/node_modules/admin-lte/dist/js/adminlte.min.js') ?>"></script>
    <!-- adminlte for demo -->
    <script src="<?= base_url('/assets/vendor/node_modules/admin-lte/dist/js/demo.js') ?>"></script>
    <script src="<?= base_url('/assets/vendor/node_modules/toastr/build/toastr.min.js') ?>"></script>
    <script src="<?= base_url('/assets/vendor/node_modules/overlayscrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>

    <!-- general script -->
    <script>
        $(document).ready(function(){
            // $("body").overlayScrollbars({ 
            //     // className : 'os-theme-dark'
            // }); // set overlay scrollbar to body tag html
            $(".sidebar").overlayScrollbars({
                className : "os-theme-dark"
            }); // set overlay sidebar scrollbar color to dark
        });
    </script>
</body>

</html>