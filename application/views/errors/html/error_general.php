<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?= $heading; ?></title>

	<!-- /* --------------------------------- styles --------------------------------- */ -->
	<!-- favicon -->
	<link rel="shortcut icon" href="<?= base_url('assets/img/logo3.png'); ?>">
	<!-- icons -->
	<link rel="stylesheet" href="<?= base_url('assets/vendor/node_modules/@fortawesome/fontawesome-free/css/all.min.css') ?>">
	<!-- font -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700" rel="stylesheet"> -->
	<link href="<?= base_url('assets/font/open-sans.css'); ?>" rel="stylesheet">
	<!-- theme style -->
	<link rel="stylesheet" href="<?= base_url('/assets/vendor/node_modules/admin-lte/dist/css/adminlte.min.css'); ?>">
<style type="text/css">

		::selection { background-color: #E13300; color: white; }
		::-moz-selection { background-color: #E13300; color: white; }

		body {
			background-color: #fff;
			margin: 40px;
			font: 13px/20px normal Helvetica, Arial, sans-serif;
			color: #4F5155;
		}

		a {
			color: #003399;
			background-color: transparent;
			font-weight: normal;
		}

		h1 {
			color: #444;
			background-color: transparent;
			border-bottom: 1px solid #D0D0D0;
			font-size: 19px;
			font-weight: normal;
			margin: 0 0 14px 0;
			padding: 14px 15px 10px 15px;
		}

		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 12px;
			background-color: #f9f9f9;
			border: 1px solid #D0D0D0;
			color: #002166;
			display: block;
			margin: 14px 0 14px 0;
			padding: 12px 10px 12px 10px;
		}

		#container {
			margin: 10px;
			border: 1px solid #D0D0D0;
			box-shadow: 0 0 8px #D0D0D0;
		}

		p {
			margin: 12px 15px 12px 15px;
		}

	/* preloader */
	.preloader {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 9999;
		/* background-color: #1089FF; */
		background-image: url('../../../assets/img/background.jpg');
		background-size: cover;
	}
	.preloader .loading {
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%,-50%);
		font: 14px arial;
	}
</style>
</head>
<body>
	<div class="preloader card loading">
		<div class="d-flex h-100 m-0 px-auto w-100"> <!-- this container make the element to vertically and horizontally centered -->
			<div class="d-flex justify-content-center align-self-center w-100 m-0">
				<img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80">
			</div>
		</div>    
	</div>
	<!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline <?php 
			if($status_code < 500){
				echo"text-warning";
			} else {
				echo"text-danger";
			}
		?>"><?= $status_code; ?></h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle <?php 
			if($status_code < 500){
				echo"text-warning";
			} else {
				echo"text-danger";
			}
		?>"></i> <?= $heading; ?>.</h3>

		  <?= $message; ?>
          <p>
            Meanwhile, you may <a href="<?= base_url(); ?>">return to Job Profile</a>.
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>

	<!-- jquery -->
	<script src="<?= base_url('/assets/vendor/node_modules/jquery/dist/jquery.min.js') ?>"></script>
	<script>
		// preloader
		setTimeout(function(){
			$(".preloader").delay(150).fadeOut('slow');
		}, 500);
	</script>
</body>
</html>