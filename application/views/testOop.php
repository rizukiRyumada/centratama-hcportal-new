<!DOCTYPE html>
<html lang="en">
<head>
    <!-- head settings -->
    <?php $this->load->view('_komponen/main_head'); ?>
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed" style="height: auto;">
    <!-- page wrapper -->
    <div class="wrapper">
        <!-- main navbar -->
        <?php $this->load->view('_komponen/main_navbar'); ?> 
        <!-- main sidebar -->
        <?php $this->load->view('_komponen/main_sidebar'); ?>
        <!-- control sidebar -->
        <?php $this->load->view('_komponen/main_control_sidebar'); ?>
        
        <!-- content wrapper -->
        <div class="content-wrapper">
            <!-- content header -->
            <?php $this->load->view('_komponen/main_content_header'); ?>

            <!-- content -->
            <div class="content">
                <!-- container fluid -->
                <div class="container-fluid">
                    <!-- insert row here and start your content -->
                    <!-- bootstrap examples -->
                        <?php $this->load->view('_komponen/bootstrap_examples');?>
                </div><!-- /container fluid -->
            </div><!-- /content -->
        </div><!-- content wrapper -->

        <!-- main footer -->
        <?php $this->load->view('_komponen/main_footer'); ?>
    </div> <!-- /page wrapper -->

    <!-- main script file -->
    <?php $this->load->view('_komponen/main_script'); ?>
</body>
</html>