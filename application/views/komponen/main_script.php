<!-- script file -->
<!-- jquery -->
<script src="<?= base_url('/assets/vendor/node_modules/jquery/dist/jquery.min.js') ?>"></script>
<!-- bootstrap -->
<script src="<?= base_url('/assets/vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- adminlte -->
<script src="<?= base_url('/assets/vendor/node_modules/admin-lte/dist/js/adminlte.min.js') ?>"></script>
<!-- adminlte for demo -->
<script src="<?= base_url('/assets/vendor/node_modules/admin-lte/dist/js/demo.js') ?>"></script>
<!-- toaster -->
<script src="<?= base_url('/assets/vendor/node_modules/toastr/build/toastr.min.js') ?>"></script>
<!-- overlay Scrollbar -->
<script src="<?= base_url('/assets/vendor/node_modules/overlayscrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- select2 -->
<script src="<?= base_url('assets/vendor/node_modules/admin-lte/plugins/select2/js/select2.full.min.js'); ?>"></script>

<!-- general custom script -->
<script>
    $(document).ready(function(){
        // $("body").overlayScrollbars({ 
        //     // className : 'os-theme-dark'
        // }); // set overlay scrollbar to body tag html
        $(".sidebar").overlayScrollbars({
            className : "os-theme-dark"
        }); // set overlay sidebar scrollbar color to dark
    });

    // dapatkan lebar browser
    function getWidth() {
        return Math.max(
            document.body.scrollWidth,
            document.documentElement.scrollWidth,
            document.body.offsetWidth,
            document.documentElement.offsetWidth,
            document.documentElement.clientWidth
        );
    }

    // dapatkan tinggi browser
    function getHeight() {
        return Math.max(
            document.body.scrollHeight,
            document.documentElement.scrollHeight,
            document.body.offsetHeight,
            document.documentElement.offsetHeight,
            document.documentElement.clientHeight
        );
    }
</script><!-- /general script -->