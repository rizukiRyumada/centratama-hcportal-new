<script>
    /* -------------------------------------------------------------------------- */
    /*                           single form validation                           */
    /* -------------------------------------------------------------------------- */
    // Job Position Form trigger from budget radio button
    input_budget.on('change', function() {
        $("#budgetAlert").hide(); // sembunyikan overlay job position alert
        // setting buat jquery validate
        input_budget.parent().parent().parent().parent().addClass('border-gray-light').removeClass('border-danger');
        input_budget.removeClass('is-invalid');
        $('#unbudgettedRadio').siblings('.invalid-tooltip').remove(); // hapus tooltip invalid 
        
        console.log($('input[name="budget"]:checked').val());

        // number of incumbent
        $('#noiReq').val('-');
        input_mpp.val('');
        
        if($('input[name="budget"]:checked').val() == 0) { // cek jika unbudgeted
            input_jptext.fadeIn(); // tampilkan free text buat nulis nama job 
            input_jpchoose.hide(); // sembunyikan pilihan posisi job
            input_jpchoose.prop('selectedIndex',0);// kembalikan status ke default - position chooser

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

            // validator input jpchoose
            input_jpchoose.addClass('is-invalid'); // remove class invalid
            input_jpchoose.parent().append(msg_choose); // show error tooltip
        }
    });
    // validate job profile free text
    input_jptext.on('keyup', function() {
        input_jptext.removeClass('is-invalid'); // remove class invalid
        // input_jptext.removeClass('is-valid'); // remove class invalid
        input_jptext.siblings('.invalid-tooltip').remove(); // remove class invalid
        input_mpp.removeAttr('max'); //  hapus attribute max
        if($(this).val() != ""){
            // input_jptext.addClass('is-valid'); // remove class invalid
            input_jptext.siblings('.invalid-tooltip').remove(); // remove class invalid
            input_mpp.removeAttr('disabled'); // hapus attribute disable
        } else {
            input_jptext.addClass('is-invalid'); // remove class invalid
            input_jptext.parent().append(msg_fill); // show error tooltip
            input_mpp.val(''); // kosongkan value mpp
            input_mpp.attr('disabled', true); // tambahkan atribute disable
        }
    });
    // validate job profile chooser
    input_jpchoose.on('change', function() {
        input_jpchoose.removeClass('is-invalid'); // remove class invalid
        input_jpchoose.removeClass('is-valid'); // remove class invalid
        input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            $.ajax({
                url: '<?= base_url("ptk/ajax_getPositionMpp"); ?>',
                data: {
                    id_posisi: $(this).val()
                },
                method: "POST",
                success: function(data){
                    let vya = JSON.parse(data);
                    $('#noiReq').val(vya.mpp);
                    input_mpp.attr('max', vya.mpp);
                    input_mpp.removeAttr('disabled');
                }
            });

            input_jpchoose.addClass('is-valid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else {
            $('#noiReq').val('-');
            input_mpp.val(''); // kosongkan value mpp
            input_mpp.attr('max', '1');
            input_mpp.attr('disabled', true);

            input_jpchoose.addClass('is-invalid'); // remove class invalid
            input_jpchoose.parent().append(msg_choose); // show error tooltip
        }
    });

    //validate mpp request
    input_mpp.on('keyup', function(){
        input_mpp.removeClass('is-invalid');
        input_mpp.removeClass('valid');
        input_mpp.siblings('.invalid-tooltip').remove();
        // cek jika mpp request < number of incumbent

        console.log($(this).val());
        console.log($('#noiReq').val());

        let mpp = $(this).val();
        let noiReq = $('#noiReq').val();
        if(noiReq != '-'){
            if(mpp > 0 && mpp <= noiReq){
            // nothing
            } else {
                input_mpp.addClass('is-invalid'); // tambah kelas invalid
                input_mpp.parent().append('<div class="invalid-tooltip">The man power required that you input should be number and in range one to less or equal to number of incumbent.</div>'); // show error tooltip
            }
        }
    });

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

    // validation entity
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

    // validate Date Required
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

    <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1): ?>
        // division, department, and position filter and validator
        var select_department = $('#departementForm');
        var select_divisi = $('#divisionForm');

        // Filter Divisi
        select_divisi.change(function(){
            // validator
            select_divisi.removeClass('is-invalid'); // remove class invalid
            select_divisi.removeClass('is-valid'); // remove class invalid
            select_divisi.siblings('.invalid-tooltip').remove(); // remove class invalid

            var dipilih = "div-"+$(this).val(); //ambil value dari yang terpilih
            // position
            input_jpchoose.attr('disabled', "true"); // hapus attribut disabled
            input_jpchoose.empty().append('<option selected value="">Choose Department first...</option>'); //kosongkan selection value dan tambahkan satu selection option
            // remove valid and invalid class
            select_department.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            select_department.siblings('.invalid-tooltip').remove(); // remove class invalid
            input_jpchoose.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid

            if($(this).val() != ""){
                // validator
                select_divisi.addClass('is-valid'); // remove class invalid
                select_divisi.siblings('.invalid-tooltip').remove(); // remove class invalid

                // get department data
                $.ajax({
                    url: "<?php echo base_url('job_profile/ajax_getdepartement'); ?>",
                    data: {
                        divisi: dipilih //kirim ke server php
                    },
                    method: "POST",
                    success: function(data) { //jadi nanti dia balikin datanya dengan variable data
                        select_department.removeAttr('disabled'); // hapus attribut disabled
                        select_department.empty().append('<option value="">Choose Department...</option>'); //kosongkan selection value dan tambahkan satu selection option

                        $.each(JSON.parse(data), function(i, v) {
                            select_department.append('<option value="' + v.id + '">' + v.nama_departemen + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                        });

                        // validaotr
                        select_department.addClass('is-invalid'); // remove class invalid
                        select_department.parent().append(msg_choose); // show error tooltip
                    }
                });
            } else {
                // validator
                select_divisi.addClass('is-invalid'); // remove class invalid
                select_divisi.parent().append(msg_choose); // show error tooltip

                // department
                select_department.attr('disabled', "true"); // hapus attribut disabled
                select_department.empty().append('<option selected value="">Choose Division first...</option>'); //kosongkan selection value dan tambahkan satu selection option
            }
        });
        // Filter Departemen
        select_department.change(() => {
            // validator
            select_department.removeClass('is-invalid'); // remove class invalid
            select_department.removeClass('is-valid'); // remove class invalid
            select_department.siblings('.invalid-tooltip').remove(); // remove class invalid
            
            let divisi = select_divisi.val();
            let departemen = $("#departementForm").val();

            // position
            input_jpchoose.attr('disabled', "true"); // hapus attribut disabled
            input_jpchoose.empty().append('<option selected value="">Choose Department first...</option>'); //kosongkan selection value dan tambahkan satu selection option
            // remove valid and invalid class
            input_jpchoose.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid

            if($("#departementForm").val() != ""){
                // validator
                select_department.addClass('is-valid'); // remove class invalid
                select_department.siblings('.invalid-tooltip').remove(); // remove class invalid

                // get popsition data
                $.ajax({
                    url: "<?= base_url('job_profile/ajax_getPosition'); ?>",
                    data: {
                        divisi: divisi,
                        departemen: departemen
                    },
                    method: "POST",
                    success: (data) => {
                        input_jpchoose.removeAttr('disabled'); // hapus attribut disabled
                        input_jpchoose.empty().append('<option value="">Choose Position...</option>'); //kosongkan selection value dan tambahkan satu selection option

                        $.each(JSON.parse(data), function(i, v) {
                            input_jpchoose.append('<option value="' + v.id + '">' + v.position_name + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                        });

                        // validaotr
                        // input_jpchoose.addClass('is-invalid'); // remove class invalid
                        // input_jpchoose.parent().append(msg_choose); // show error tooltip
                    }
                });
            } else {
                // validaotr
                select_department.addClass('is-invalid'); // remove class invalid
                select_department.parent().append(msg_choose); // show error tooltip

                // case for jpchoose
                input_jpchoose.attr('disabled', "true"); // hapus attribut disabled
                input_jpchoose.empty().append('<option selected value="">Choose Department first...</option>'); //kosongkan selection value dan tambahkan satu selection option
            }
        });

        // validator

    <?php endif; ?>

    // input type number validation
    // $('input[type="number"]').on('change keyup', function() {
    //     $(this).removeClass('is-invalid'); // remove class invalid
    //     $(this).siblings('.invalid-tooltip').remove(); // remove error tooltip
    //     if($.isNumeric($(this).val()) != true) { // cek jika value kosong
    //         if($(this).val() == ""){ // cek value yang diinput user
    //             $(this).addClass('is-invalid'); // add class invalid
    //             $(this).parent().append(msg_number); // show error tooltip
    //         } else {
    //             $(this).addClass('is-invalid'); // add class invalid
    //             $(this).parent().append(msg_number); // show error tooltip
    //         }
    //     } else {
    //         // nothing
    //     }
    // });

    /* -------------------------------------------------------------------------- */
    /*                           Job Profile Tab Trigger                          */
    /* -------------------------------------------------------------------------- */
    // ambil data job profile ketika dropdown berubah
    $("#positionInput").on('change', function() {
        let id_posisi = $(this).children("option:selected").val();

        showMeJobProfile(id_posisi);
    });

    function showMeJobProfile(id_posisi){
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
    }

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