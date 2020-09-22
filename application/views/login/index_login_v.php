<style>
    /* #bannerPengumuman{
        -webkit-clip-path: url(#svgPath);
	    clip-path: url(#svgPath);
    } */
    /* html, body{
        min-width: 1280px;
        min-height: 650px;
    } */
</style>

<!-- banner -->
<div class="banner-wrapper">
    <!-- banner tema -->
    <svg class="banner-tema" viewBox="0 0 444 291" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <path d="M20.3976 -2L444 -1.99989V261.351C444 261.351 273.624 290 217.656 290C161.687 290 45.3154 269.846 12.7306 173.901C-19.8541 77.9548 20.3976 -2 20.3976 -2Z" fill="url(#pattern0)"/>
        <defs>
            <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#image0" transform="translate(-0.0113811) scale(0.00037232 0.00054615)"/>
            </pattern>
            <!-- <image id="image0" width="2747" height="1831" xlink:href="https://picsum.photos/1000/680" /> -->
            <image id="image0" width="2747" height="1831" xlink:href="<?= base_url('assets/'); ?>img/tema.jpg" />
        </defs>
    </svg>

    <!-- banner pengumuman -->
    <!-- <svg class="banner-pengumuman" width="305" height="277" viewBox="0 0 305 277" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path d="M0 2.11911C0 2.11911 181.059 -18.0491 255.238 66.0531C329.417 150.155 298.694 278 298.694 278H0V2.11911Z"
            fill="url(#pattern1)" />
        <defs>
            <pattern id="pattern1" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#image1" transform="translate(-0.307286) scale(0.00190398 0.00209644)"/>
            </pattern>
            <image id="image1" width="848" height="477" xlink:href="https://picsum.photos/1000/900" />
        </defs>
    </svg> -->

    <!-- <svg width="0" height="0" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <clipPath id="svgPath">
                <path fill="#FFFFFF" stroke="#000000" stroke-miterlimit="10" d="M0 2.11911C0 2.11911 181.059 -18.0491 255.238 66.0531C329.417 150.155 298.694 278 298.694 278H0V2.11911Z"></path>
            </clipPath>
        </defs>
    </svg> -->

    <!-- <svg height="0" width="0">
            <defs>
                <clipPath id="svgPath">
                    <path fill="#FFFFFF" stroke="#000000" stroke-miterlimit="10" d="M0 2.11911C0 2.11911 181.059 -18.0491 255.238 66.0531C329.417 150.155 298.694 278 298.694 278H0V2.11911Z" stroke-width="1.94"></path>
                </clipPath>
            </defs>
        </svg> -->

        <svg class="banner-pengumuman" width="305" height="277" viewBox="0 0 305 277" fill="none"
        xmlns="http://www.w3.org/2000/svg">
            <clippath id="cp-circle">
                <path d="M0 2.11911C0 2.11911 181.059 -18.0491 255.238 66.0531C329.417 150.155 298.694 278 298.694 278H0V2.11911Z" fill="url(#pattern1)" />
            </clippath>
            <g clip-path="url(#cp-circle)">   
   	            <foreignObject width="848" x="0" y="0" height="477">
                    <body xmlns="http://www.w3.org/1999/xhtml">
                        <!-- <iframe width="848" height="477" src="//www.examples.com" frameborder="0" allowfullscreen></iframe> -->
                    <div style="width: 36.30%;" id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <!-- <li data-target="#carouselExampleIndicators" data-slide-to="2"></li> -->
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <!-- <img class="d-block w-100" src="https://picsum.photos/1000/900" alt="First slide"> -->
                                <img class="d-block w-100" src="<?= base_url('assets/'); ?>img/pengumuman/1.jpg" alt="First slide">
                                
                                <div class="carousel-caption d-none d-md-block">
                                    <!-- <h5>Judul Pengumuman</h5>
                                    <p>Text Pengumuman</p> -->
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="<?= base_url('assets/'); ?>img/pengumuman/2.jpg" alt="Second slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <!-- <h5>Judul Pengumuman</h5>
                                    <p>Text Pengumuman</p> -->
                                </div>
                            </div>
                            <!-- <div class="carousel-item">
                                <img class="d-block w-100" src="<?= base_url('assets/'); ?>img/pengumuman/3.jpg" alt="Third slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Judul Pengumuman</h5>
                                    <p>Text Pengumuman</p>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    </body>
                </foreignObject>
            </g>	
        </svg>
    
    <!-- <div id="bannerPengumuman" class="carousel slide banner-pengumuman" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#bannerPengumuman" data-slide-to="0" class="active"></li>
            <li data-target="#bannerPengumuman" data-slide-to="1"></li>
            <li data-target="#bannerPengumuman" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="https://picsum.photos/1000/900" alt="First slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>...</h5>
                    <p>...</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="https://picsum.photos/1000/900" alt="Second slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>...</h5>
                    <p>...</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="https://picsum.photos/1000/900" alt="Third slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>...</h5>
                    <p>...</p>
                </div>
            </div>
        </div>
    </div> -->

    


</div>

<!-- wrapper page -->
<div class="wrapper h-100">
    <!-- navbar -->
    <nav class="main-header navbar navbar-expand-lg border-bottom-0">
        <div class="container-fluid">
            <a href="../../index3.html" class="navbar-brand d-flex">
                <img class="brand-image navbar-logo" src="<?= base_url('assets/'); ?>img/logo.png" alt="Centratama Group Logo">
                <span class="brand-text font-weight-light d-inline-block navbar-text-leftbordered font-berlinsans"><span class="text-nowrap">HC Portal</span></span>
            </a>
            
            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <!-- Left navbar links -->
                <!-- <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="index3.html" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Contact</a>
                    </li>
                </ul> -->
                
                <!-- SEARCH FORM -->
                <!-- <form class="form-inline ml-0 ml-md-3">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form> -->
            </div>
            
            <!-- Right navbar links -->
            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <li class="nav-item">
                    <!-- <a target="_blank" href="http://hcis.centratamagroup.com/" class="btn btn-navbar my-2 my-sm-0 mx-2 text-decoration-none" ><i class="fa fa-globe-asia text-white"></i> HCIS</a> -->
                    <a href="http://hcis.centratamagroup.com/" class="btn btn-primary" target="_blank"><i class="fa fa-globe-asia text-white"></i> HCIS</a>
                    
                </li>
                <li class="nav-item ml-3">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#loginModal"><i class="fa fa-sign-in-alt text-white"></i> LOGIN</a>
                    <!-- <button class="btn btn-navbar my-2 my-sm-0 mx-2" type="button" data-toggle="modal" data-target="#loginModal"><i class="fa fa-sign-in-alt text-white"></i> LOGIN</button> -->
                </li>
            </ul>
        </div>
    </nav><!-- /navbar -->

    <!-- content wrapper for more than 1080px -->
    <div class="content-wrapper bg-transparent" id="content-wrap">
        <!-- konten tanggal -->
        <div class="content" id="content-tanggal">
            <div class="container-fluid">
                <div class="row portal-body m-0 text-dark justify-content-start">
                    <div class="col-auto">
                        <div class="text-center align-middle">
                            <div class="container d-flex mx-0">
                                <div class="row justify-content-center align-self-center">
                                    <p style="font-size: 1.55em; margin: 0;"><?= date("l", time()) ?>,</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto px-3">
                        <div class="container d-flex mx-0 px-2">
                            <div class="row justify-content-center align-self-center">
                                <div class="col">
                                    <div class="row justify-content-bottom align-self-bottom">
                                        <p style="font-size: 1.35em; margin: 0; line-height: 1;"><?= date("j", time()) ?></p>
                                    </div>
                                    <div class="row justify-content-left align-self-left">
                                        <p style="font-size: 1.35em; margin: 0; line-height: 1;"><?= date("F", time()) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- konten aplikasi lainnya -->
        <div class="content" id="content-aplikasi">
            <div class="container-fluid align-items-end justify-content-end d-flex h-100">
                <!-- load view other application -->
                <?php $this->load->view('login/other_app_login_v') ?>
            </div>
        </div>
        <!-- konten footer -->
        <div class="content mt-2" id="content-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <!-- footer -->
                        <div class="portal-footer text-right text-dark">
                            <div class="portal-footer-text">
                                Copyright &copy; <?= date("Y", time()) ?> | Human Capital Centratama Group
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- content wrapper for less than 1080px -->
    <div class="content-wrapper bg-transparent h-100" id="content-wrap-smallscrenn">
        <div class="content">
            <div class="container-fluid align-items-center w-100 d-flex">
                <div class="row">
                    <div class="col">
                        <img class="card responsive-image" src="<?= base_url('assets/'); ?>img/tema.jpg" alt="tema" width="1000" height="700">
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6 text-center">
                        <a href="http://hcis.centratamagroup.com/" class="btn btn-primary" target="_blank"><i class="fa fa-globe-asia text-white"></i> HCIS</a>
                    </div>
                    <div class="col-6 text-center">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#loginModal"><i class="fa fa-sign-in-alt text-white"></i> LOGIN</a>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="portal-footer-text w-100 mt-4" style="position: absolute; bottom: 0;">
            <p class="text-center mb-0">Copyright &copy; <?= date("Y", time()) ?> | Human Capital Centratama Group</p>
        </div>
    </div>
</div>

<!-- modal login -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalTitle">Login to <b class="font-berlinsans" style="color: #0072C6">HC Portal</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-top: -0.5em;">
                    <div class="d-flex h-100 m-0 px-auto w-100"> <!-- this container make the element to vertically and horizontally centered -->
                        <div class="row justify-content-center align-self-center w-100 m-0">
                            <div class="form-login-wrapper row">
                                <div class="col-12">
                                    <?= $this->session->flashdata('message'); ?>
                                    
                                    <form id="loginForm" class="needs-validation" method="post" action="<?= base_url('login'); ?>">
                                        <div class="form-group">
                                            <label for="nik">Username</label>
                                            <input type="text" class="form-control" id="nik" placeholder="Enter Your NIK" name="nik"  required maxlength="8">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" aria-describedby="passwordhelp" placeholder="Enter Your Password" name="password" required>
                                            <!-- <small id="passwordhelp" class="form-text text-muted"><a href="https://wa.me/6287789902289/?text=Saya%20ada%20masalah%20saat%20login%20dengan%20akun%20saya%20ketika%20mencoba%20masuk%20ke%20aplikasi%20Service%20Excellence%20Survey.">Masalah dengan login?</a></small> -->
                                        </div>
                                    <!-- close form tag below the login -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" value="Login"><i class="fa fa-sign-in-alt color-white"></i> Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- TODO pasang ION Auth -->
<!-- TODO buat halaman dashboard -->
<!-- TODO buat halaman reset password -->
