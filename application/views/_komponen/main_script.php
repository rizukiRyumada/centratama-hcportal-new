<!-- script file -->
<!-- jquery -->
<script src="<?= base_url('/assets/vendor/node_modules/jquery/dist/jquery.min.js') ?>"></script>
<!-- Moment js -->
<script src="<?= base_url('/assets/vendor/node_modules/moment/min/moment.min.js'); ?>"></script>
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

    // toastr notification
    <?php if(!empty($this->session->userdata('msg'))): ?>
        <?php if(!empty($this->session->userdata('msg')['title'])): ?>
            toastr["<?= $this->session->userdata('msg')['icon']; ?>"]("<?= $this->session->userdata('msg')['title']; ?>", "<?= $this->session->userdata('msg')['msg']; ?>");
        <?php else: ?>
            toastr["<?= $this->session->userdata('msg')['icon']; ?>"]("<?= $this->session->userdata('msg')['msg']; ?>");
        <?php endif; ?>
    <?php endif; ?>
    // unset toastr Notification
    <?= $this->session->unset_userdata('msg'); ?>

    // swal notification
    <?php if(!empty($this->session->userdata('msg_swal'))): ?>
        $(document).ready(() => {
            Swal.fire({
                title: "<?= $this->session->userdata('msg_swal')['title']; ?>",
                icon: "<?= $this->session->userdata('msg_swal')['icon']; ?>",
                html: "<?= $this->session->userdata('msg_swal')['msg']; ?>",
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: true,
                confirmButtonText: 'Ok',
                    // '<i class="fa fa-thumbs-up"></i> Great!',
                confirmButtonAriaLabel: 'Ok',
            });
        });
    <?php endif; ?>
    // unset swal notification
    <?php $this->session->unset_userdata('msg_swal'); ?>

    $(document).ready(function(){
        // $("body").overlayScrollbars({ 
        //     className : 'os-theme-dark'
        // }); // set overlay scrollbar to body tag html
        $(".sidebar").overlayScrollbars({
            className : "os-theme-dark"
        }); // set overlay sidebar scrollbar color to dark
    });

    
</script><!-- /general script -->
