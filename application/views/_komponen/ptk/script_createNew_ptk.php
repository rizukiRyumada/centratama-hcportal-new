<!-- <script src="<?php // base_url('/assets/js/iframe-resize/iframeResizer.min.js'); ?>"></script> -->
<script>
    $(document).ready(function(){
        // ambil job position jika ada divisi dan posisinya
        let divisi = select_divisi.val();
        let department = select_department.val();

        if(divisi != "" && department != ""){
            getPosition(divisi, department); // get position and interviewer data
        }
    });

    /* -------------------------------------------------------------------------- */
    /*                           Customized Form Validation                       */
    /* -------------------------------------------------------------------------- */
    $('.submitPTK').on('click', function() {
        // ambil data pada tombol save atau submit
        $('input[name="action"]').val($(this).data('id'));
        let action = $(this).data('id');
        
        let validator = submit_validator(); // submit validator taken from .../application/views/_komponen/ptk/script_submitValidator_ptk.php
        let counter_validate = validator[0];
        let msg_validate = validator[1];
        // cek apa ada form error
        if(counter_validate != 0){
            // List empty form popup
            // $(document).Toasts('create', {
            //     class: 'bg-danger', 
            //     title: 'List of Empty Form',
            //     subtitle: 'Lets fill it',
            //     position: 'bottomLeft',
            //     body: msg_validate + "Please look at red mark or border."
            // });
            // tampilkan pesan error dalam swal
            Swal.fire({
                title: 'Form Validation Error',
                html: "Please fill the required input form.",
                icon: 'warning',
                showCancelButton: false,
                // confirmButtonColor: '#99FF99',
                // cancelButtonColor: '#d33',
                confirmButtonText: 'Ok, I wiil check it.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            }).then((result) => {
                if (result.value) {
                    var el = $('select#entityInput');
                    var elOffset = el.offset().top;
                    var elHeight = el.height();
                    var windowHeight = $(window).height();
                    var offset;

                    if (elHeight < windowHeight) {
                        offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
                    }
                    else {
                        offset = elOffset;
                    }
                    $([document.documentElement, document.body]).animate({ //for animation
                        scrollTop: offset
                    }, 750);
                }
            });

            // batalkan pengiriman form
            return false;
        } else {
            // kirimkan form

            <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $position_my['hirarki_org'] == "N-1"): ?>
                let text_title = "";
                if(action == "save"){
                    text_title = 'Saving the form...';
                } else {
                    text_title = 'Submitting the form...';
                }
            <?php else: ?>
                let text_title = 'Saving the form...';
            <?php endif; ?>

            // show submitting swal notification
            Swal.fire({
                icon: 'info',
                title: text_title,
                html: '<p>Form validation completed, Please Wait.<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                showConfirmButton: false,
                // allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });
            
            $('#ptkForm').submit(); // submit form if validation success
        }
    });
</script>