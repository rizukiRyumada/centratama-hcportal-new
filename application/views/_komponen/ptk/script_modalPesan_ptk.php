<script>
    // initialize revise_to
    $('select[name="revise_to"]').select2({theme: 'bootstrap4'});

    /* -------------------------------------------------------------------------- */
    /*                           Customized Form Validation                       */
    /* -------------------------------------------------------------------------- */
    $('.submitPTK').on('click', function() {
        action = $(this).data('id');
        let status = $(this).data('status');
        let action_msg = "";
        let css_color = "";
        
        // jika tombol accept yang dippilih
        if(action == 1){
            if(status == "ptk_stats-1" || status == "" || status == undefined || status == null){
                action_msg = 'Submit';
                css_color = "success";
            } else {
                action_msg = 'Accept';
                css_color = "success";
            }
        // jika tombol denied yang dipilih
        } else if(action == 0){
            action_msg = 'Reject';
            css_color = "danger";
        // jika tombol revise yang dipilih
        } else if(action == 2){
            action_msg = 'Revise';
            css_color = "warning";
        // jika tombol save yang dipilih
        } else if(action == 3) {
            action_msg = 'Save';
            css_color = "warning";
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Button Not Found!',
            });
            return false;
        }

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
            // return false;
        } else { // jika form validation berhasil
            Swal.fire({
                title: 'Are you sure?',
                // html: "You want to <span class='label text-"+css_color+" font-weight-bold'>"+action_msg+"</span> this form.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    // ubah form action dan status
                    $('input[name="action"]').val(action);
                    $('input[name="status_now"]').val(status);
                    if(action == 2){
                        $('#container_reviseto').show();
                    } else {
                        // $('select[name="revise_to"]').select2("destroy");
                        $('#container_reviseto').hide();
                        // nothing
                    }
                    // tampilkan modal pesan revisi
                    $('#pesanKomentar').modal('show');
                }
            });
        }
    });

    $("#submitPesanKomentar").on('click', function() {
        let textarea_pesanKomentar = CKEDITOR.instances['textareaPesanKomentar'].getData();

        if(textarea_pesanKomentar == "" || textarea_pesanKomentar.length < 2){
            // tampilkan pesan error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please write a comment and your comment must be at least 2 characters long!',
            });
        } else {
            $('input[name="pesan_komentar"]').val(textarea_pesanKomentar); // taruh pesan revisi di form
            // jika actionnya revise, ambil value select revise to masukkan ke form hidden input
            if(action == 2){
                let revise_to = $('select[name="revise_to"]').val();
                $('input[name="revise_to"]').val(revise_to);
            }
            $('#pesanKomentar').modal('hide'); // tutup modal pesan revisi
            // pergi ke function submit
            letSubmitForm(action);
        }
    });

    function letSubmitForm(action){
        let text_title = "";
        if(action == 0){
            text_title = 'Rejecting the form...';
        } else if(action == 2){
            text_title = 'Revising the form...';
        } else if(action == 3){
            text_title = 'Saving the form...';
        } else {
            text_title = 'Submitting the form...';
        }
        
        // show submitting swal notification
        Swal.fire({
            icon: 'info',
            title: text_title,
            html: '<p>Please Wait.<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
            showConfirmButton: false,
            // allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
        });
        $('#ptkForm').submit(); // submit form if validation success
    }
</script>