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

    // CKEDITOR Instances
    CKEDITOR.replace( 'ska' );
    CKEDITOR.replace( 'req_special' );
    CKEDITOR.replace( 'outline' );
    CKEDITOR.replace( 'main_responsibilities' );
    CKEDITOR.replace( 'tasks' );

    // Job Position Selector
    var input_jptext = $('input[name="job_position_text"]'); // selector job position text
    var input_jpchoose = $('select[name="job_position_choose"]'); // selector job position text
    var input_budget = $('input[name="budget"]');
    var input_budget_checked = $('input[name="budget"]:checked');
    // Job Position Form trigger from budget radio button
    input_budget.on('change', function() {
        $("#budgetAlert").hide(); // sembunyikan overlay job position alert
        // setting buat jquery validate
        input_budget.parent().parent().parent().parent().addClass('border-gray-light').removeClass('border-danger');
        input_budget.removeClass('is-invalid');
        $('#unbudgettedRadio').siblings('.invalid-tooltip').remove(); // hapus tooltip invalid 
        
        console.log($('input[name="budget"]:checked').val());
        
        if($('input[name="budget"]:checked').val() == 0) { // cek jika unbudgeted
            input_jptext.fadeIn(); // tampilkan free text buat nulis nama job 
            input_jpchoose.hide(); // sembunyikan pilihan posisi job
            $('#positionInput').prop('selectedIndex',0);// kembalikan status ke default - position chooser

            // sembunyikan tab job Profile dan orgChart
            $('#tab_jobProfile').fadeOut();
            $('#tab_orgChart').fadeOut();

            // remove valid and invalid class
            input_jpchoose.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else if($('input[name="budget"]:checked').val() == 1) { // cek jika budgeted
            input_jpchoose.fadeIn(); // tampilkan pilihan job position 
            input_jptext.hide(); // sembunyikan free text job profile
            input_jptext.val(''); // kosongkan kotak job_position_text

            // remove valid and invalid class
            input_jptext.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jptext.siblings('.invalid-tooltip').remove(); // remove class invalid
        }
    });

    // replacement variable
    let input_replacement = $('input[name="replacement"]');
    let input_replacement_who = $('input[name="replacement_who"]');
    // Replacement form trigger
    input_replacement.on('change', () => {
        // remove validation class
        input_replacement_who.siblings('.invalid-tooltip').remove(); // remove class invalid
        input_replacement_who.removeClass('is-invalid'); // remove class invalid
        if(input_replacement.prop("checked") == true) { // cek jika replacement check box dicek
            input_replacement_who.removeAttr('disabled'); // aktifkan form replacement who
        } else if(input_replacement.prop("checked") == false) { // cek jika replacement check box tidak dicek
            input_replacement_who.attr('disabled', true); // nonaktifkan kotak replacement who
            input_replacement_who.val(''); // kosongkan kotak replacement who
        }
    });
    // Replacement who free text
    input_replacement_who.on('keyup', function() {
        // remove validation class
        input_replacement_who.siblings('.invalid-tooltip').remove(); // remove invalid tooltip
        input_replacement_who.removeClass('is-invalid'); // remove class invalid
        if(input_replacement_who.val() == ""){
            $(input_replacement_who).addClass('is-invalid'); // add class invalid
            $(input_replacement_who).parent().append(msg_fill); // show error tooltip
        } else {
            $(input_replacement_who).removeClass('is-invalid'); // remove class invalid
            input_replacement_who.siblings('.invalid-tooltip').remove(); // remove invalid tooltip
        }
    });

    // variable resource form
    var input_resource = $('input[name="resources"]');
    var input_resource_checked = $('input[name="resources"]:checked');
    var input_resource_internal = $('#internalForm');
    var input_resource_internalwho = $('input[name="internal_who"]');
    // Reource Radio button Internal form
    input_resource.on('change', function() {
        input_resource_internal.parent().parent().parent().removeClass('border border-danger');
        input_resource_internal.removeClass('is-invalid');
        input_resource_internal.siblings('.invalid-tooltip').remove();
        if($('input[name="resources"]:checked').val() == "int") { // cek jika internal radio button
            input_resource_internalwho.slideDown(); // tampilkan input text internal_who
        } else if($('input[name="resources"]:checked').val() == "ext") { // cek jika external radio button
            input_resource_internalwho.slideUp(); // sembunyikan input text internal_who
            input_resource_internalwho.removeClass('is-invalid');
            input_resource_internalwho.siblings('.invalid-tooltip').remove();
        }
    });
    input_resource_internalwho.on('keyup', function() {
        input_resource_internalwho.removeClass('is-invalid');
        input_resource_internalwho.siblings('.invalid-tooltip').remove();
        if(input_resource_internalwho.val() == ""){
            input_resource_internalwho.addClass('is-invalid'); // add class invalid
            input_resource_internalwho.parent().append(msg_fill); // show error tooltip
        }
    });

    // variabel input name Work Experience
    var input_workexp = $('input[name="work_exp"]');
    var input_workexp_checked = $('input[name="work_exp"]:checked');
    var input_workexp_years = $('#we_years');
    var input_workexp_yearstext = $('input[name="work_exp_years"]');
    // Work Experience Radio button Internal form
    input_workexp.on('change', function() {
        input_workexp.parent().parent().parent().parent().parent().removeClass('border border-danger'); // hapus border
        input_workexp.removeClass('is-invalid'); // hapus kelas invalid
        $('#experiencedRadio').siblings('.invalid-tooltip').remove(); // hapus tooltip invalid
        if($('input[name="work_exp"]:checked').val() == 1) { // cek jika cekbox work experience
            input_workexp_years.fadeIn(); // tampilkan kotak free text tahun
        } else if($('input[name="work_exp"]:checked').val() == 0) { // cek jika cekbox fresh graduate
            input_workexp_years.fadeOut(); // sembunyikan kotak free text tahun
            input_workexp_yearstext.val(''); // kosongkan kotak we_years
            // remove validation years text
            input_workexp_yearstext.removeClass('is-invalid'); // remove class invalid
            input_workexp_yearstext.siblings('.invalid-tooltip').remove(); // remove error tooltip
        }
    });
    // Work Experience Years text validation
    // input_workexp_yearstext.on('keyup', function() {
    //     input_workexp_yearstext.removeClass('is-invalid'); // remove class invalid
    //     input_workexp_yearstext.siblings('.invalid-tooltip').remove(); // remove error tooltip
    //     if(input_workexp_yearstext.val() == ""){
    //         input_workexp_yearstext.addClass('is-invalid'); // add class invalid
    //         input_workexp_yearstext.parent().append(msg_fill); // show error tooltip
    //     } else {
    //         // nothing
    //     }
    // });

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
            $(input_WLchoose).siblings('.invalid-tooltip').remove(); // show error tooltip
        } else if(input_WLtrigger.prop('checked') == false) {
            // jika tidak diceklis, tampilkan pilihan work location
            input_WLchoose.show();
            input_WLtext.hide();
            // isi dummy text di input free text work location
            input_WLtext.val('');
            $(input_WLtext).removeClass('is-invalid').removeClass('is-valid'); // add class invalid
            $(input_WLtext).siblings('.invalid-tooltip').remove(); // show error tooltip
        }
    });
    // validate work location text
    input_WLtext.on('keyup', function() {
        input_WLtext.removeClass('is-invalid'); // remove class invalid
        input_WLtext.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            input_WLtext.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else {
            input_WLtext.addClass('is-invalid'); // remove class invalid
            input_WLtext.parent().append(msg_fill); // show error tooltip
        }
    });
    // validate work location chooser
    input_WLchoose.on('change', function() {
        input_WLchoose.removeClass('is-invalid'); // remove class invalid
        input_WLchoose.removeClass('is-valid'); // remove class invalid
        input_WLchoose.closest('div.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            input_WLchoose.addClass('is-valid'); // remove class invalid
            input_WLchoose.closest('div.invalid-tooltip').remove(); // remove class invalid
        } else {
            input_WLchoose.addClass('is-invalid'); // remove class invalid
            input_WLchoose.parent().append(msg_choose); // show error tooltip
        }
    });

    // ambil data job profile ketika dropdown berubah
    $("#positionInput").on('change', function() {
        let id_posisi = $(this).children("option:selected").val();

        if(id_posisi != "" && $('input[name="budget"]:checked').val() == 1){
            // tampilkan tab job Profile dan orgChart
            $('#tab_jobProfile').fadeIn();
            $('#tab_orgChart').fadeIn();

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
    var fill   = "This field is required.";
    var number = "The input is required and should be number.";

    // tooltip validation
    var msg_choose = '<div class="invalid-tooltip">'+choose+'</div>' ;
    var msg_fill   = '<div class="invalid-tooltip">'+fill+'</div>' ;
    var msg_number = '<div class="invalid-tooltip">'+number+'</div>'

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
        validate_entity.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            validate_entity.addClass('is-valid'); // remove class invalid
            validate_entity.siblings('.invalid-tooltip').remove(); // remove class invalid
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
        validate_job_level.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            validate_job_level.addClass('is-valid'); // remove class invalid
            validate_job_level.siblings('.invalid-tooltip').remove(); // remove class invalid
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
        validate_empstats.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            validate_empstats.addClass('is-valid'); // remove class invalid
            validate_empstats.siblings('.invalid-tooltip').remove(); // remove class invalid
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
        validate_education.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            validate_education.addClass('is-valid'); // remove class invalid
            validate_education.siblings('.invalid-tooltip').remove(); // remove class invalid
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
        validate_sex.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            validate_sex.addClass('is-valid'); // remove class invalid
            validate_sex.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else {
            validate_sex.addClass('is-invalid'); // remove class invalid
            validate_sex.parent().append(msg_choose); // show error tooltip
        }
    });

    // validate job profile free text
    input_jptext.on('keyup', function() {
        input_jptext.removeClass('is-invalid'); // remove class invalid
        // input_jptext.removeClass('is-valid'); // remove class invalid
        input_jptext.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            // input_jptext.addClass('is-valid'); // remove class invalid
            input_jptext.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else {
            input_jptext.addClass('is-invalid'); // remove class invalid
            input_jptext.parent().append(msg_fill); // show error tooltip
        }
    });
    // validate job profile chooser
    input_jpchoose.on('change', function() {
        input_jpchoose.removeClass('is-invalid'); // remove class invalid
        input_jpchoose.removeClass('is-valid'); // remove class invalid
        input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            input_jpchoose.addClass('is-valid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else {
            input_jpchoose.addClass('is-invalid'); // remove class invalid
            input_jpchoose.parent().append(msg_choose); // show error tooltip
        }
    });

    // validate Date Required
    var input_daterequired = $('input[name="date_required"]');
    input_daterequired.on('keyup change', function(){
        input_daterequired.removeClass('is-invalid'); // hapus kelas is invalid
        input_daterequired.siblings('.invalid-tooltip').remove();
        if(input_daterequired.val() == ""){
            input_daterequired.addClass('is-invalid'); // tambah kelas invalid
            input_daterequired.parent().append(msg_fill); // tampilkan pesan error
        } else {
            // nothing
        }
    });

    // validate Majoring
    var input_majoring = $('input[name="majoring"]');
    input_majoring.on('keyup change', function(){
        input_majoring.removeClass('is-invalid'); // hapus kelas is invalid
        input_majoring.siblings('.invalid-tooltip').remove();
        if(input_majoring.val() == ""){
            input_majoring.addClass('is-invalid'); // tambah kelas invalid
            input_majoring.parent().append(msg_fill); // tampilkan pesan error
        } else {
            // nothing
        }
    });

    // validate interviewer
    var input_interviewer_name = $('#interviewer_name3');
    var input_interviewer_position = $('#interviewer_position3');
    // validate interviewer name
    input_interviewer_name.on('keyup change', function() {
        // hapus kelas validasi
        input_interviewer_position.removeClass('is-invalid'); // hapus kelas is invalid
        input_interviewer_position.siblings('.invalid-tooltip').remove();
        input_interviewer_name.removeClass('is-invalid'); // hapus kelas is invalid
        input_interviewer_name.siblings('.invalid-tooltip').remove();
        if(input_interviewer_name.val() != ""){
            if(input_interviewer_position.val() == ""){
                input_interviewer_position.addClass('is-invalid'); // tambah kelas invalid
                input_interviewer_position.parent().append(msg_fill); // tampilkan pesan error
                msg_validate += "<li>Interviewer Position is empty is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else {
            if(input_interviewer_position.val() != ""){
                if(input_interviewer_name.val() == ""){
                    input_interviewer_name.addClass('is-invalid'); // tambah kelas invalid
                    input_interviewer_name.parent().append(msg_fill); // tampilkan pesan error
                    msg_validate += "<li>Interviewer Name is empty</li>"; // pesan empty
                    counter_validate++; // validate counter add
                } else {
                    // nothing
                }
            }
        }
    });
    // validate interviewer position
    input_interviewer_position.on('keyup change', function() {
        // hapus kelas validasi
        input_interviewer_position.removeClass('is-invalid'); // hapus kelas is invalid
        input_interviewer_position.siblings('.invalid-tooltip').remove();
        input_interviewer_name.removeClass('is-invalid'); // hapus kelas is invalid
        input_interviewer_name.siblings('.invalid-tooltip').remove();
        if(input_interviewer_position.val() != ""){
            if(input_interviewer_name.val() == ""){
                input_interviewer_name.addClass('is-invalid'); // tambah kelas invalid
                input_interviewer_name.parent().append(msg_fill); // tampilkan pesan error
                msg_validate += "<li>Interviewer Position is empty is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else {
            if(input_interviewer_name.val() != ""){
                if(input_interviewer_position.val() == ""){
                    input_interviewer_position.addClass('is-invalid'); // tambah kelas invalid
                    input_interviewer_position.parent().append(msg_fill); // tampilkan pesan error
                    msg_validate += "<li>Interviewer Name is empty</li>"; // pesan empty
                    counter_validate++; // validate counter add
                } else {
                    // nothing
                }
            }
        }
    });

    // input type number validation
    $('input[type="number"]').on('change keyup', function() {
        $(this).removeClass('is-invalid'); // remove class invalid
        $(this).siblings('.invalid-tooltip').remove(); // remove error tooltip
        if($.isNumeric($(this).val()) != true) { // cek jika value kosong
            if($(this).val() == ""){ // cek value yang diinput user
                $(this).addClass('is-invalid'); // add class invalid
                $(this).parent().append(msg_number); // show error tooltip
            } else {
                $(this).addClass('is-invalid'); // add class invalid
                $(this).parent().append(msg_number); // show error tooltip
            }
        } else {
            // nothing
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
            input_budget.parent().parent().parent().parent().removeClass('border-gray-light').addClass('border-danger');
            input_budget.addClass('is-invalid');
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

        // validate replacement form
        if(input_replacement.prop("checked") == true) { // cek jika replacement check box dicek
            if(input_replacement_who.val() == ""){
                $(input_replacement_who).addClass('is-invalid'); // add class invalid
                $(input_replacement_who).parent().append(msg_fill); // show error tooltip
                msg_validate += "<li>Replacement is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            }
        } else if(input_replacement.prop("checked") == false) { // cek jika replacement check box tidak dicek
            // nothing
        }

        // validate resource form
        if($('input[name="resources"]:checked').val() == "int") {
            if(input_resource_internalwho.val() == ""){
                input_resource_internalwho.addClass('is-invalid'); // add class invalid
                input_resource_internalwho.parent().append(msg_fill); // show error tooltip
                msg_validate += "<li>Please type an employee name</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else if($('input[name="resources"]:checked').val() == "ext"){
            // nothing
        } else {
            input_resource_internal.parent().parent().parent().addClass('border border-danger');
            input_resource_internal.addClass('is-invalid');
            input_resource_internal.parent().append(msg_choose);
            msg_validate += "<li>Resource is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        }

        // variable man power required
        let input_mpp = $('input[name="mpp_req"]');
        // validate Man Power Required
        if(input_mpp.val() == ""){
            input_mpp.addClass('is-invalid'); // add class invalid
            input_mpp.parent().append(msg_number); // show error tooltip
            msg_validate += "<li>Man Power Required is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            // nothing
        }

        let input_preferage = $('input[name="preferred_age"]');
        // validate preferred age
        if(input_preferage.val() ==""){
            input_preferage.addClass('is-invalid'); // add class invalid
            input_preferage.parent().append(msg_number); // show error tooltip
            msg_validate += "<li>Preferred Age is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            // nothing
        }

        // validate work experience
        if($('input[name="work_exp"]:checked').val() == 1) { // cek jika cekbox work experience
            if(input_workexp_yearstext.val() == ""){
                input_workexp_yearstext.addClass('is-invalid'); // add class invalid
                input_workexp_yearstext.parent().append(msg_number); // show error tooltip
                msg_validate += "<li>Work Experience Years is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            }
        } else if($('input[name="work_exp"]:checked').val() == 0) { // cek jika cekbox fresh graduate
            input_workexp_yearstext.val('0'); // set value sama dengan nol
        } else {
            input_workexp.parent().parent().parent().parent().parent().addClass('border border-danger');
            input_workexp.addClass('is-invalid');
            $('#experiencedRadio').parent().append(msg_choose);
            msg_validate += "<li>Work Experience not choosen</li>"; // pesan empty
            counter_validate++; // validate counter add
        }

        // validate Date Required
        if(input_daterequired.val() == ""){
            input_daterequired.addClass('is-invalid'); // tambah kelas invalid
            input_daterequired.parent().append(msg_fill); // tampilkan pesan error
            msg_validate += "<li>Date Required is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            // nothing
        }

        // validate majoring
        if(input_majoring.val() == ""){
            input_majoring.addClass('is-invalid'); // tambah kelas invalid
            input_majoring.parent().append(msg_fill); // tampilkan pesan error
            msg_validate += "<li>Majoring is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            // nothing
        }

        // take data of ckeditor data
        let textarea_ska = CKEDITOR.instances['ska'].getData();
        let textarea_reqspecial = CKEDITOR.instances['req_special'].getData();
        let textarea_outline = CKEDITOR.instances['outline'].getData();
        let textarea_mainrespon = CKEDITOR.instances['main_responsibilities'].getData();
        let textarea_tasks = CKEDITOR.instances['tasks'].getData();
        
        // validate skill, knowledge, and abilities
        let textarea_selector_ska = $('textarea#ska');
        if(textarea_ska == ""){
            textarea_selector_ska.parent().parent().parent().addClass('border border-danger');
            msg_validate += "<li>Skill, Knowledge, and Abilities is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            textarea_selector_ska.parent().parent().parent().removeClass('border border-danger');
        }

        // validate special requirement
        // let textarea_selector_reqspecial = $('textarea#req_special');
        // if(textarea_reqspecial == ""){
        //     textarea_selector_reqspecial.parent().parent().parent().addClass('border border-danger');
        //     msg_validate += "<li>Special Requirement is empty</li>"; // pesan empty
        //     counter_validate++; // validate counter add
        // } else {
        //     textarea_selector_reqspecial.parent().parent().parent().removeClass('border border-danger');
        // }

        // validate outline textarea
        let textarea_selector_outline = $('textarea#outline');
        if(textarea_outline == ""){
            textarea_selector_outline.parent().parent().parent().addClass('border border-danger');
            msg_validate += "<li>Outline is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            textarea_selector_outline.parent().parent().parent().removeClass('border border-danger');
        }

        // validate main responsibilities textarea
        let textarea_selector_mainrespon = $('textarea#main_responsibilities');
        if(textarea_mainrespon == ""){
            textarea_selector_mainrespon.parent().parent().parent().addClass('border border-danger');
            msg_validate += "<li>Main Responsibilities is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            textarea_selector_mainrespon.parent().parent().parent().removeClass('border border-danger');
        }

        // validate tasks textarea
        let textarea_selector_tasks = $('textarea#tasks');
        if(textarea_tasks == ""){
            textarea_selector_tasks.parent().parent().parent().addClass('border border-danger');
            msg_validate += "<li>Tasks is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            textarea_selector_tasks.parent().parent().parent().removeClass('border border-danger');
        }

        // validate jika nama atau posisi di interviewer terisi
        if(input_interviewer_name.val() != ""){
            if(input_interviewer_position.val() == ""){
                input_interviewer_position.addClass('is-invalid'); // tambah kelas invalid
                input_interviewer_position.parent().append(msg_fill); // tampilkan pesan error
                msg_validate += "<li>Interviewer Position is empty is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else {
            if(input_interviewer_position.val() != ""){
                if(input_interviewer_name.val() == ""){
                    input_interviewer_name.addClass('is-invalid'); // tambah kelas invalid
                    input_interviewer_name.parent().append(msg_fill); // tampilkan pesan error
                    msg_validate += "<li>Interviewer Name is empty</li>"; // pesan empty
                    counter_validate++; // validate counter add
                } else {
                    // nothing
                }
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
            return true;
        }
    });

    /* -------------------------------------------------------------------------- */
    /*                          Tippy JS Tooltip trigger                          */
    /* -------------------------------------------------------------------------- */

    // With the above scripts loaded, you can call `tippy()` with a CSS
    // selector and a `content` prop:

    // Entity
    tippy('#entityInput', {
        content: 'Please choose one entity',
    });

    // Job Position
    // alert budget
    tippy('#budgetAlert', {
        content: 'Please Choose one Budget',
    });
    // Job Title free text
    tippy('#jobTitleInput', {
        content: 'Job Position Free Text',
    });
    // job Position selection
    tippy('#positionInput', {
        content: 'Job Position Selection',
    });

    // Job Level
    tippy('#jobLevelForm', {
        content: 'Job Level',
    });

    // Work Location
    // Work Location selection
    tippy('#work_location_choose', {
        content: 'Work Location selection',
    });
    // Work Location Text
    tippy('#work_location_text', {
        content: 'Work Location Text',
    });
    // Work Location Other Trigger
    tippy('#work_location_otherTrigger', {
        content: 'Work Location other trigger',
    });

    // budget
    tippy('#chooseBudget', {
        content: 'Budget',
    });

    // Replacement
    // replacement trigger
    tippy('#replace', {
        content: 'Replace',
    });
    // Replacement Who
    tippy('#replacement_who', {
        content: 'Replacement Who',
    });

    // Resource
    tippy('#resource', {
        content: 'Resource',
    });
    // Internal Who
    tippy('#internal_who', {
        content: 'Internal Who',
    });

    // Man Power Required
    tippy('#mppReq', {
        content: 'Man Power Required',
    });

    // Number of Incumbent
    tippy('#noiReq', {
        content: 'Number of Incumbent',
    });

    // Employement Status
    tippy('#emp_stats', {
        content: 'Employement Status',
    });

    // Date required
    tippy('#date_required', {
        content: 'Date Required',
    });

    // Education
    tippy('#education', {
        content: 'Education',
    });
    // Majoring
    tippy('#majoring', {
        content: 'Majoring',
    });

    // Age
    tippy('#age', {
        content: 'Preferred Age',
    });
    // Sex
    tippy('#sexForm', {
        content: 'Sex',
    });

    // Fresh Graduate
    tippy('#freshGradRadio', {
        content: 'Fresh Graduate',
    });
    // Experienced
    tippy('#experiencedRadio', {
        content: 'Experienced',
    });
    // Work Experience Years
    tippy('#we_years', {
        content: 'Work Experienced Years',
    });

    // Skill, Knowledge, and abilities (ska)
    tippy('#ska_label', {
        content: 'Skill, Knowledge, and abilities (ska)',
    });

    // Special Requirement
    tippy('#reqSpecial_label', {
        content: 'Special Requirement',
    });

    // Outline
    tippy('#outline_label', {
        content: 'Outline',
    });

    // Interviewer
    tippy('#interviewer_name3', {
        content: 'Interviewer Name',
    });
    tippy('#interviewer_position3', {
        content: 'Interviewer Position',
    });

    // Main Responsibilities
    tippy('#main_responsibilities_label', {
        content: 'main_responsibilities',
    });

    // Tasks
    tippy('#tasks_label', {
        content: 'Tasks',
    });
    

</script>