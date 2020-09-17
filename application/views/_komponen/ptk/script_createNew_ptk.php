<!-- <script src="<?php // base_url('/assets/js/iframe-resize/iframeResizer.min.js'); ?>"></script> -->
<script>
    // $('#ptkForm').validate({
    //     rules: {
    //         entity: {
    //             required: true
    //         },
    //         job_position: {
    //             required: true
    //         },
    //         job_level: {
    //             required: true
    //         },
    //         division: {
    //             required: true
    //         },
    //         department: {
    //             required: true
    //         },
    //         work_location: {
    //             required: true
    //         },
    //         budget: {
    //             required: true
    //         },
    //         resources: {
    //             required: true
    //         },
    //         mpp_req: {
    //             required: true
    //         },
    //         emp_stats: {
    //             required: true
    //         },
    //         date_required: {
    //             required: true
    //         },
    //         education: {
    //             required: true
    //         },
    //         majoring: {
    //             required: true
    //         },
    //         preferred_age: {
    //             required: true
    //         },
    //         sex: {
    //             required: true
    //         },
    //         work_exp: {
    //             required: true
    //         },
    //         ska: {
    //             required: true
    //         },
    //         req_special: {
    //             required: true
    //         },
    //         outline: {
    //             required: true
    //         },
    //         main_responsibilities: {
    //             required: true
    //         },
    //         tasks: {
    //             required: true
    //         }
    //     },
    //     messages: {
    //         name: {
    //             required: "Please enter your Name",
    //             minlength: "Your Name must be at least 5 characters long."
    //         },
    //         email: {
    //             required: "Please enter your Email",
    //             email: "This is not the correct Email."
    //         },
    //         password_current: {
    //             required: "Please type your password to save changes.",
    //             minlength: "Your Password must be at least 8 characters long."
    //         },
    //         password: {
    //             minlength: "Your new Password must be at least 8 characters long."
    //         },
    //         password2:{
    //             minlength: "Your new Password must be at least 8 characters long.",
    //             equalTo: "Password doesn't match with the first one."
    //         }
    //     },
    //     errorElement: 'span',
    //     errorClass: 'text-right pr-2',
    //     errorPlacement: function (error, element) {
    //         error.addClass('invalid-feedback');
    //         element.closest('.form-group').append(error);
    //     },
    //     highlight: function (element, errorClass, validClass) {
    //         $(element).addClass('is-invalid');
    //     },
    //     unhighlight: function (element, errorClass, validClass) {
    //         $(element).removeClass('is-invalid');
    //     }
    // });

    /* -------------------------------------------------------------------------- */
    /*                           Customized Form Validation                       */
    /* -------------------------------------------------------------------------- */
    $('.submitPTK').on('click', function() {
        <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $position_my['hirarki_org'] == "N-1"): ?>
            // ambil data pada tombol save atau submit
            $('input[name="action"]').val($(this).data('id'));
            let action = $(this).data('id');
        <?php endif; ?>
        
        let validator = submit_validator(); // submit validator taken from .../application/views/_komponen/ptk/script_submitValidator_ptk.php
        let counter_validate = validator[0];
        let msg_validate = validator[1];
        // cek apa ada form error
        if(counter_validate != 0){
            // List empty form popup
            $(document).Toasts('create', {
                class: 'bg-danger', 
                title: 'List of Empty Form',
                subtitle: 'Lets fill it',
                position: 'bottomLeft',
                body: msg_validate + "Please look at red mark or border."
            });
            // tampilkan pesan error dalam swal
            Swal.fire({
                title: 'Form Validation Error',
                html: "Please fill the required input form.",
                icon: 'error',
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