<!-- script file -->
<!-- jquery -->
<script src="<?= base_url('/assets/vendor/node_modules/jquery/dist/jquery.min.js') ?>"></script>
<!-- bootstrap -->
<script src="<?= base_url('/assets/vendor/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- adminlte -->
<script src="<?= base_url('/assets/vendor/node_modules/admin-lte/dist/js/adminlte.min.js') ?>"></script>

<?php $this->load->view('_komponen/plugins/jqueryValidation/script_jqueryValidation'); ?>

<!-- custom script -->
<script>
    $(document).ready(function(){
        // set ukuran masing-masing tinggi konten
        let content_height = $("#content-wrap").css("min-height").replace(/[^-\d\.]/g, ''); // ambil ukuran content wrapper terus hilang kan string 'px'

        // bagi ukuran dan set ke masing2 konten
        let tanggal_height = (20 * content_height / 100);
        let aplikasi_height = (75 * content_height / 100);
        let footer_height = (0.75 * content_height / 100);
        /* ------------------- atur tinggi di masing-masing konten ------------------ */
        $('#content-tanggal').css('height', tanggal_height);
        $('#content-aplikasi').css('height', aplikasi_height);
        $('#content-footer').css('height', footer_height);

        $('#loginForm').validate({
            rules: {
                nik: {
                    required: true,
                    minlength: 8
                },
                password: {
                    required: true,
                    minlength: 8
                }
            },
            messages: {
                nik: {
                    required: "Please enter your NIK",
                    minlength: "Your NIK must be at least 8 characters long"
                },
                password: {
                    required: "Please enter your password",
                    minlength: "Your password must be at least 8 characters long"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
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

        // tampilkan modal login apabila ada error
        if(<?= $this->session->userdata('error'); ?> == 1){
            $('#loginModal').modal('show');
        } else if(getWidth() < 1080){
            $('#loginModal').modal('show');
        }
        // unset sesssion error biar modalnya ga ketampil lagi
        <?php $this->session->unset_userdata('error'); ?>
        
        

console.log('Width:  ' +  getWidth() );
console.log('Height: ' + getHeight() );
    });
</script><!-- /general script -->