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
<!-- swal -->
<script src="<?= base_url('/assets/vendor/node_modules/sweetalert2/dist/sweetalert2.all.min.js'); ?>" ></script>

<!-- general custom script -->
<script>
    // toaster popup notifications
    toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
    }
    // show the toastr notification
    <?= $this->session->flashdata('msg'); ?>

    // swal notification
    <?php if(!empty($this->session->flashdata('msg_swal'))): ?>
        $(document).ready(() => {
            Swal.fire({
                title: '<?= $this->session->flashdata('msg_swal')['title']; ?>',
                icon: '<?= $this->session->flashdata('msg_swal')['icon']; ?>',
                html: '<?= $this->session->flashdata('msg_swal')['msg']; ?>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: true,
                confirmButtonText: 'Ok',
                    // '<i class="fa fa-thumbs-up"></i> Great!',
                confirmButtonAriaLabel: 'Ok',
            });
        });
    <?php endif; ?>

    $(document).ready(function(){
        // $("body").overlayScrollbars({ 
        //     className : 'os-theme-dark'
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