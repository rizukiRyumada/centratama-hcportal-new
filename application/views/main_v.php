<!DOCTYPE html>
<html lang="en">
<head>
    <!-- head settings -->
    <?php $this->load->view('komponen/main_head'); ?>
    <?php if(!empty($custom_styles)): ?>
        <?php foreach($custom_styles as $v): ?>
            <link rel="stylesheet" href="<?= base_url('assets/css/').$v.'.css'; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed" style="height: auto;">
    <!-- load preloader -->
    <?php $this->load->view('komponen/preloader_v'); ?>
    <!-- floating contact -->
    <?php $this->load->view('komponen/floating_contact') ?>
    <!-- page wrapper -->
    <div class="wrapper">
        <!-- main navbar -->
        <?php $this->load->view('komponen/main_navbar'); ?> 
        <!-- main sidebar -->
        <?php $this->load->view('komponen/main_sidebar'); ?>
        <!-- control sidebar -->
        <?php $this->load->view('komponen/main_control_sidebar'); ?>
        
        <!-- content wrapper -->
        <div class="content-wrapper">
            <!-- content header -->
            <?php $this->load->view('komponen/main_content_header'); ?>

            <!-- content -->
            <div class="content">
                <!-- container fluid -->
                <div class="container-fluid">
                    <!-- insert row and col here, then start your content -->
                        <?php $this->load->view($load_view);?>
                </div><!-- /container fluid -->
            </div><!-- /content -->
        </div><!-- content wrapper -->

        <!-- main footer -->
        <?php $this->load->view('komponen/main_footer'); ?>
    </div> <!-- /page wrapper -->

    <!-- main script file -->
    <?php $this->load->view('komponen/main_script'); ?>
    <!-- load other custom script -->
    <?php if(!empty($custom_script)): ?>
        <?php foreach($custom_script as $v): ?>
            <?php $this->load->view('komponen/'.$v); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- load preloader -->
    <?php $this->load->view('komponen/preloader_script'); ?>
</body>
</html>