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

    // Job Position Selector
    var input_jptext = $('input[name="job_position_text"]'); // selector job position text
    var input_jpchoose = $('select[name="job_position_choose"]'); // selector job position text
    // Job Position Form trigger from budget radio button
    $('input[name="budget"]').on('change', function() {
        $("#budgetAlert").hide(); // sembunyikan overlay job position alert
        // setting buat jquery validate
        $('input[name="budget"]').parent().parent().parent().parent().addClass('border-gray-light').removeClass('border-danger');
        $('input[name="budget"]').removeClass('is-invalid');
        $('#unbudgettedRadio').parent().remove('.invalid-tooltip');
        if($('input[name="budget"]:checked').val() == 0) { // cek jika unbudgeted
            input_jptext.fadeIn(); // tampilkan free text buat nulis nama job 
            input_jpchoose.hide(); // sembunyikan pilihan posisi job
            $('#positionInput').prop('selectedIndex',0);// kembalikan status ke default - position chooser

            // sembunyikan tab job Profile dan orgChart
            $('#tab_jobProfile').fadeOut();
            $('#tab_orgChart').fadeOut();

            // remove valid and invalid class
            input_jpchoose.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jpchoose.parent().remove('.invalid-tooltip'); // remove class invalid
        } else if($('input[name="budget"]:checked').val() == 1) { // cek jika budgeted
            input_jpchoose.fadeIn(); // tampilkan pilihan job position 
            input_jptext.hide(); // sembunyikan free text job profile
            input_jptext.val(''); // kosongkan kotak job_position_text

            // remove valid and invalid class
            input_jptext.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jptext.parent().remove('.invalid-tooltip'); // remove class invalid
        }
    });

    // Replacement form trigger
    $('input[name="replacement"]').on('change', () => {
        if($('input[name="replacement"]').prop("checked") == true) { // cek jika replacement check box dicek
            $('input[name="replacement_who"]').removeAttr('disabled'); // aktifkan form replacement who
        } else if($('input[name="replacement"]').prop("checked") == false) { // cek jika replacement check box tidak dicek
            $('input[name="replacement_who"]').attr('disabled', true); // nonaktifkan kotak replacement who
            $('input[name="replacement_who"]').val(''); // kosongkan kotak replacement who
        }
    });

    // Reource Radio button Internal form
    $('input[name="resources"]').on('change', function() {
        if($('input[name="resources"]:checked').val() == "int") { // cek jika internal radio button
            $('input[name="internal_who"]').slideDown(); // tampilkan input text internal_who
        } else if($('input[name="resources"]:checked').val() == "ext") { // cek jika external radio button
            $('input[name="internal_who"]').slideUp(); // sembunyikan input text internal_who
        }
    });

    // Work Experience Radio button Internal form
    $('input[name="work_exp"]').on('change', function() {
        if($('input[name="work_exp"]:checked').val() == 1) { // cek jika cekbox work experience
            $('#we_years').fadeIn(); // tampilkan kotak free text tahun
        } else if($('input[name="work_exp"]:checked').val() == 0) { // cek jika cekbox fresh graduate
            $('#we_years').fadeOut(); // sembunyikan kotak free text tahun
            $('input[name="work_exp_years"]').val(''); // kosongkan kotak we_years
        }
    });

    // work location selector
    var input_WLtext = $('input[name="work_location_text"]');
    var input_WLchoose = $('select[name="work_location_choose"]');
    var input_WLtrigger = $('#work_location_otherTrigger');
    // Work Locations Other input checkbox
    input_WLtrigger.on('change', function(){
        if(input_WLtrigger.prop('checked') == true) {
            // jika diceklis, tampilkan input free text work location
            input_WLtext.val('');
            input_WLtext.show();
            input_WLchoose.hide();
            // pilih, pilihan pertama selected option location list
            input_WLchoose.prop('selectedIndex', 0);
            $(input_WLchoose).removeClass('is-invalid').removeClass('is-valid'); // add class invalid
            $(input_WLchoose).parent().remove('.is-invalid'); // show error tooltip
        } else if(input_WLtrigger.prop('checked') == false) {
            // jika tidak diceklis, tampilkan pilihan work location
            input_WLchoose.show();
            input_WLtext.hide();
            // isi dummy text di input free text work location
            input_WLtext.val('');
            $(input_WLtext).removeClass('is-invalid').removeClass('is-valid'); // add class invalid
            $(input_WLtext).parent().remove('.is-invalid'); // show error tooltip
        }
    });

    // ambil data job profile ketika dropdown berubah
    $("#positionInput").on('change', function() {
        let id_posisi = $(this).children("option:selected").val();

        if(id_posisi != "" && $('input[name="budget"]:checked').val() == 1){
            // tampilkan tab job Profile dan orgChart
            $('#tab_jobProfile').fadeIn();
            $('#tab_orgChart').fadeIn();

            console.log(id_posisi);

            let jobprofile_viewer = $("#viewer_jobprofile");
            jobprofile_viewer.attr('src', '<?= base_url('ptk/viewer_jobProfile'); ?>/'+id_posisi);
            $('#viewer_jobprofile_orgchart').attr('src', '<?= base_url('ptk/viewer_jobProfile_orgchart'); ?>/'+id_posisi);
            // iFrameResize({log:true}, '#viewer_jobprofile');
            // iFrameResize({
            //     log: true, // Enable console logging
            //     inPageLinks: true,
            //     onResized: function(messageData) {
            //     // Callback fn when resize is received
            //     $('p#callback').html(
            //         '<b>Frame ID:</b> ' +
            //         messageData.iframe.id +
            //         ' <b>Height:</b> ' +
            //         messageData.height +
            //         ' <b>Width:</b> ' +
            //         messageData.width +
            //         ' <b>Event type:</b> ' +
            //         messageData.type
            //     )
            //     },
            //     onMessage: function(messageData) {
            //     // Callback fn when message is received
            //     $('p#callback').html(
            //         '<b>Frame ID:</b> ' +
            //         messageData.iframe.id +
            //         ' <b>Message:</b> ' +
            //         messageData.message
            //     )
            //     alert(messageData.message)
            //         document
            //         .getElementsByTagName('iframe')[0]
            //         .iFrameResizer.sendMessage('Hello back from parent page')
            //     },
            //     onClosed: function(id) {
            //         // Callback fn when iFrame is closed
            //         $('p#callback').html(
            //             '<b>IFrame (</b>' + id + '<b>) removed from page.</b>'
            //     )
            //     }
            // })
        } else {
            // sembunyikan tab job Profile dan orgChart
            $('#tab_jobProfile').fadeOut();
            $('#tab_orgChart').fadeOut();
        }
    });

    /* -------------------------------------------------------------------------- */
    /*                        Single input form validation                        */
    /* -------------------------------------------------------------------------- */
    // message validation
    var choose = "Please choose one.";
    var fill = "This field is required.";

    // tooltip validation
    var msg_choose = '<div class="invalid-tooltip">'+choose+'</div>' ;
    var msg_fill = '<div class="invalid-tooltip">'+fill+'</div>' ;

    var input_select = [
        {input: "entity", name: "Entity"},
        {input: "job_level", name: "Job Level"},
        {input: "emp_stats", name: "Status of Employement"},
        {input: "education", name: "Education"},
        {input: "sex", name: "Sex"}
    ];

    // validation entity
    let validate_entity = $('select[name="' + input_select[0].input + '"]');
    validate_entity.on('change', function() {
        validate_entity.removeClass('is-invalid'); // remove class invalid
        validate_entity.removeClass('is-valid'); // remove class invalid
        validate_entity.parent().remove('.invalid-tooltip'); // remove class invalid
        if($(this).val() != ""){
            validate_entity.addClass('is-valid'); // remove class invalid
            validate_entity.parent().remove('.invalid-tooltip'); // remove class invalid
        } else {
            validate_entity.addClass('is-invalid'); // remove class invalid
            validate_entity.parent().append(msg_choose); // show error tooltip
        }
    });

    // validation job_level
    let validate_job_level = $('select[name="' + input_select[1].input + '"]');
    validate_job_level.on('change', function() {
        validate_job_level.removeClass('is-invalid'); // remove class invalid
        validate_job_level.removeClass('is-valid'); // remove class invalid
        validate_job_level.parent().remove('.invalid-tooltip'); // remove class invalid
        if($(this).val() != ""){
            validate_job_level.addClass('is-valid'); // remove class invalid
            validate_job_level.parent().remove('.invalid-tooltip'); // remove class invalid
        } else {
            validate_job_level.addClass('is-invalid'); // remove class invalid
            validate_job_level.parent().append(msg_choose); // show error tooltip
        }
    });

    // validation emp_stats
    let validate_empstats = $('select[name="' + input_select[2].input + '"]');
    validate_empstats.on('change', function() {
        validate_empstats.removeClass('is-invalid'); // remove class invalid
        validate_empstats.removeClass('is-valid'); // remove class invalid
        validate_empstats.parent().remove('.invalid-tooltip'); // remove class invalid
        if($(this).val() != ""){
            validate_empstats.addClass('is-valid'); // remove class invalid
            validate_empstats.parent().remove('.invalid-tooltip'); // remove class invalid
        } else {
            validate_empstats.addClass('is-invalid'); // remove class invalid
            validate_empstats.parent().append(msg_choose); // show error tooltip
        }
    });

    // validation education
    let validate_education = $('select[name="' + input_select[3].input + '"]');
    validate_education.on('change', function() {
        validate_education.removeClass('is-invalid'); // remove class invalid
        validate_education.removeClass('is-valid'); // remove class invalid
        validate_education.parent().remove('.invalid-tooltip'); // remove class invalid
        if($(this).val() != ""){
            validate_education.addClass('is-valid'); // remove class invalid
            validate_education.parent().remove('.invalid-tooltip'); // remove class invalid
        } else {
            validate_education.addClass('is-invalid'); // remove class invalid
            validate_education.parent().append(msg_choose); // show error tooltip
        }
    });

    // validation sex
    let validate_sex = $('select[name="' + input_select[4].input + '"]');
    validate_sex.on('change', function() {
        validate_sex.removeClass('is-invalid'); // remove class invalid
        validate_sex.removeClass('is-valid'); // remove class invalid
        validate_sex.parent().remove('.invalid-tooltip'); // remove class invalid
        if($(this).val() != ""){
            validate_sex.addClass('is-valid'); // remove class invalid
            validate_sex.parent().remove('.invalid-tooltip'); // remove class invalid
        } else {
            validate_sex.addClass('is-invalid'); // remove class invalid
            validate_sex.parent().append(msg_choose); // show error tooltip
        }
    });

    // validate job profile free text
    input_jptext.on('keyup', function() {
        input_jptext.removeClass('is-invalid'); // remove class invalid
        input_jptext.removeClass('is-valid'); // remove class invalid
        input_jptext.parent().remove('.invalid-tooltip'); // remove class invalid
        if($(this).val() != ""){
            input_jptext.addClass('is-valid'); // remove class invalid
            input_jptext.parent().remove('.invalid-tooltip'); // remove class invalid
        } else {
            input_jptext.addClass('is-invalid'); // remove class invalid
            input_jptext.parent().append(msg_fill); // show error tooltip
        }
    });
    // validate job profile chooser
    input_jpchoose.on('change', function() {
        input_jpchoose.removeClass('is-invalid'); // remove class invalid
        input_jpchoose.removeClass('is-valid'); // remove class invalid
        input_jpchoose.parent().remove('.invalid-tooltip'); // remove class invalid
        if($(this).val() != ""){
            input_jpchoose.addClass('is-valid'); // remove class invalid
            input_jpchoose.parent().remove('.invalid-tooltip'); // remove class invalid
        } else {
            input_jpchoose.addClass('is-invalid'); // remove class invalid
            input_jpchoose.parent().append(msg_choose); // show error tooltip
        }
    });

    // validate work location text
    input_WLtext.on('keyup', function() {
        input_WLtext.removeClass('is-invalid'); // remove class invalid
        input_WLtext.removeClass('is-valid'); // remove class invalid
        input_WLtext.parent().remove('.invalid-tooltip'); // remove class invalid
        if($(this).val() != ""){
            input_WLtext.addClass('is-valid'); // remove class invalid
            input_WLtext.parent().remove('.invalid-tooltip'); // remove class invalid
        } else {
            input_WLtext.addClass('is-invalid'); // remove class invalid
            input_WLtext.parent().append(msg_fill); // show error tooltip
        }
    });
    // validate work location chooser
    input_WLchoose.on('change', function() {
        input_WLchoose.removeClass('is-invalid'); // remove class invalid
        input_WLchoose.removeClass('is-valid'); // remove class invalid
        input_WLchoose.parent().remove('.invalid-tooltip'); // remove class invalid
        if($(this).val() != ""){
            input_WLchoose.addClass('is-valid'); // remove class invalid
            input_WLchoose.parent().remove('.invalid-tooltip'); // remove class invalid
        } else {
            input_WLchoose.addClass('is-invalid'); // remove class invalid
            input_WLchoose.parent().append(msg_choose); // show error tooltip
        }
    });

    /* -------------------------------------------------------------------------- */
    /*                        // Customized Form Validation                       */
    /* -------------------------------------------------------------------------- */
    $('#ptkForm').submit(function() {
        // console.log('submitted');
        // validate counter and message variable
        let msg_validate = "<ul>";
        let letScroll = "";
        let counter_validate = 0;

        Swal.fire({
            icon: 'info',
            title: 'Checking form validation...',
            html: '<p>Validating the form before submitting to the server.<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
        });

        // cek validasi select form
        $.each(input_select, function(index, value){
            let now_validate = $('select[name="' + value.input + '"]');
            if(now_validate.val() == ""){
                now_validate.addClass('is-invalid'); // add class invalid
                now_validate.parent().append(msg_choose); // show error tooltip
                msg_validate += "<li>"+value.name+" is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // now_validate.removeClass('is-invalid'); // remove class invalid
                // nothing
            }
        });

        // cek validasi job position
        let msg_validate_jp = "<li>Job Position is empty</li>";
        if($('input[name="budget"]:checked').val() == 0) { // cek jika unbudgeted
            if($(input_jptext).val() == ""){
                $(input_jptext).addClass('is-invalid'); // add class invalid
                $(input_jptext).parent().append(msg_fill); // show error tooltip
                msg_validate += msg_validate_jp; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else if($('input[name="budget"]:checked').val() == 1) { // cek jika budgeted
            if(input_jpchoose.val() == ""){
                input_jpchoose.addClass('is-invalid'); // add class invalid
                input_jpchoose.parent().append(msg_choose); // show error tooltip
                msg_validate += msg_validate_jp; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else {
            $('input[name="budget"]').parent().parent().parent().parent().removeClass('border-gray-light').addClass('border-danger');
            $('input[name="budget"]').addClass('is-invalid');
            $('#unbudgettedRadio').parent().append(msg_choose);
        }

        // validate work location
        let msg_validate_wl = "<li>Work Location is empty</li>"
        if(input_WLtrigger.prop('checked') == true) {
            // jika diceklis, tampilkan input free text work location
            if($(input_WLtext).val() == ""){
                $(input_WLtext).addClass('is-invalid'); // add class invalid
                $(input_WLtext).parent().append(msg_fill); // show error tooltip
                msg_validate += msg_validate_wl; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else if(input_WLtrigger.prop('checked') == false) {
            // jika tidak diceklis, tampilkan pilihan work location
            if(input_WLchoose.val() == ""){
                input_WLchoose.addClass('is-invalid'); // add class invalid
                input_WLchoose.parent().append(msg_choose); // show error tooltip
                msg_validate += msg_validate_wl; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        }
        
        // tutup list error message validate
        msg_validate += "</ul>";
        // cek apa ada form error
        if(counter_validate != 0){
            // List empty form popup
            $(document).Toasts('create', {
                class: 'bg-danger', 
                title: 'List of Empty Form',
                subtitle: 'Lets fill it',
                position: 'bottomRight',
                body: msg_validate + "Please look at red mark or border."
            })
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
            // return true;

            // show submitting swal notification
            Swal.fire({
                icon: 'info',
                title: 'Submitting the form...',
                html: '<p>Form validation completed, Please Wait.<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                showConfirmButton: false,
                // allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });
            return false;
        }
    });
</script>