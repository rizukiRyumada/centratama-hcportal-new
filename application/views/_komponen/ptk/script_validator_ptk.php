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
        
        // empty mpp form
        $('#noiReq').val('-');
        input_mpp.val(''); // kosongkan value mpp
        input_mpp.attr('max', '1');
        input_mpp.attr('disabled', true);
        // remove any validation class on mpp form
        input_mpp.removeClass('is-invalid');
        input_mpp.removeClass('valid');
        input_mpp.siblings('.invalid-tooltip').remove();
        // remove input replacement_who validation class
        select_replacement_who.siblings('.invalid-tooltip').remove(); // remove class invalid
        select_replacement_who.removeClass('is-invalid'); // remove class invalid

        get_jobLevel(""); // kosongkan job_level
        
        if($('input[name="budget"]:checked').val() == 0) { // cek jika unbudgeted
            if (input_jpchoose.hasClass("select2-hidden-accessible")) {
                // Select2 has been initialized
                input_jpchoose.select2("destroy"); // hancurkan select2 job position choose
            }
            input_jptext.fadeIn(); // tampilkan free text buat nulis nama job 
            input_jpchoose.hide(); // sembunyikan pilihan posisi job
            input_jpchoose.prop('selectedIndex',0);// kembalikan status ke default - position chooser

            // sembunyikan tab job Profile dan orgChart
            $('#tab_jobProfile').fadeOut();
            $('#tab_orgChart').fadeOut();
            hide_selectReplacementWho();

            // remove valid and invalid class
            input_jpchoose.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else if($('input[name="budget"]:checked').val() == 1 || $('input[name="budget"]:checked').val() == 2) { // cek jika budgeted atau replacement
            input_jpchoose.select2({theme: 'bootstrap4'}); // inisialisasi kalo job position choose itu select2
            input_jpchoose.fadeIn(); // tampilkan pilihan job position 
            input_jptext.hide(); // sembunyikan free text job profile
            input_jptext.val(''); // kosongkan kotak job_position_text

            // remove valid and invalid class
            input_jptext.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jptext.siblings('.invalid-tooltip').remove(); // remove class invalid

            // validator input jpchoose
            // input_jpchoose.addClass('is-invalid'); // remove class invalid
            // input_jpchoose.parent().append(msg_choose); // show error tooltip

            // cek position untuk update list
            let divisi = select_divisi.val();
            let departemen = $("#departementForm").val();
            let budget = $('input[name="budget"]:checked').val();
            if(divisi == "" || departemen == ""){
                // nothing
            } else {
                getPosition(divisi, departemen, "", budget); // get position and interviewer data
            }

            // script khusus budget replacement
            if($('input[name="budget"]:checked').val() == 2){
                get_selectReplacementWho(); // get employee for replacement_who from position and show it
            } else {
                hide_selectReplacementWho(); // hide replacement who
            }
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
        let id_posisi = $(this).val();
        input_jpchoose.removeClass('is-invalid'); // remove class invalid
        input_jpchoose.removeClass('is-valid'); // remove class invalid
        input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        if(id_posisi != ""){
            getNoi(id_posisi, $('input[name="budget"]:checked').val()); // ambil data mpp dan set mpp ke form
            getInterviewer(id_posisi)// ambil data interviewer
            // ambil data job_level dari job_grade di master position dan set job_levelnya di form

            input_jpchoose.addClass('is-valid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else {
            removeInterviewer(); // hapus interviewer

            $('#noiReq').val('-');
            input_mpp.val(''); // kosongkan value mpp
            input_mpp.attr('max', '1');
            input_mpp.attr('disabled', true);
            // remove any validation class on mpp form
            input_mpp.removeClass('is-invalid');
            input_mpp.removeClass('valid');
            input_mpp.siblings('.invalid-tooltip').remove();

            input_jpchoose.addClass('is-invalid'); // remove class invalid
            input_jpchoose.parent().append(msg_choose); // show error tooltip
        }
        get_jobLevel(id_posisi);
        showMeJobProfile(id_posisi); // tampilkan job profile tab
        if($('input[name="budget"]:checked').val() == 2){
            get_selectReplacementWho(id_posisi); // proses replacement_who
        }
    });

    //validate mpp request
    input_mpp.on('keyup change', function(){
        input_mpp.removeClass('is-invalid');
        input_mpp.removeClass('valid');
        input_mpp.siblings('.invalid-tooltip').remove();
        // cek jika mpp request < number of incumbent

        let mpp = $(this).val();
        let noiReq = $('#noiReq').data('empty');
        if(noiReq != '-'){
            if(mpp > 0 && mpp <= noiReq){
            // nothing
            } else {
                // jika inputnya bukan unbudgetted
                if($('input[name="budget"]:checked').val() != 0){
                    input_mpp.addClass('is-invalid'); // tambah kelas invalid
                    input_mpp.parent().append('<div class="invalid-tooltip">The man power required that you input should be number and in range one to less or equal to number of incumbent.</div>'); // show error tooltip
                }
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

    // Replacement who free text
    select_replacement_who.on('change', function() {
        // remove validation class
        select_replacement_who.siblings('.invalid-tooltip').remove(); // remove invalid tooltip
        select_replacement_who.removeClass('is-invalid'); // remove class invalid
        if(select_replacement_who.val() == ""){
            select_replacement_who.addClass('is-invalid'); // add class invalid
            select_replacement_who.parent().append(msg_fill); // show error tooltip
        } else {
            select_replacement_who.removeClass('is-invalid'); // remove class invalid
            select_replacement_who.siblings('.invalid-tooltip').remove(); // remove invalid tooltip
        }
    });

    // Reource Radio button Internal form
    input_resource.on('change', function() {
        input_resource_internal.parent().parent().parent().removeClass('border border-danger');
        input_resource_internal.removeClass('is-invalid');
        input_resource_internal.siblings('.invalid-tooltip').remove();
        if($('input[name="resources"]:checked').val() == "int") { // cek jika internal radio button
            input_resource_internalwho.slideDown(); // tampilkan input text internal_who

            // disable element yang tidak diperlukan
            // validate_empstats.attr('disabled', true);
            // validate_education.attr('disabled', true);
            // validate_sex.attr('disabled', true);
            // input_majoring.attr('disabled', true);
            // input_preferage.attr('disabled', true);
            // input_workexp.attr('disabled', true);
        } else if($('input[name="resources"]:checked').val() == "ext") { // cek jika external radio button
            input_resource_internalwho.slideUp(); // sembunyikan input text internal_who
            input_resource_internalwho.removeClass('is-invalid');
            input_resource_internalwho.siblings('.invalid-tooltip').remove();

            // hapus disable element 
            // validate_empstats.removeAttr('disabled');
            // validate_education.removeAttr('disabled');
            // validate_sex.removeAttr('disabled');
            // input_majoring.removeAttr('disabled');
            // input_preferage.removeAttr('disabled');
            // input_workexp.removeAttr('disabled');
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

    // preferred age
    input_preferage.on('change keyup', function() {
        input_preferage.removeClass('is-invalid');
        input_preferage.siblings('.invalid-tooltip').remove();
        if(input_preferage.val() == ""){
            input_preferage.addClass('is-invalid'); // add class invalid
            input_preferage.parent().append(msg_fill); // show error tooltip
            msg_validate += "<li>Preferred Age is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            // nothing
        }
    })

    // Work Experience Radio button Internal form
    input_workexp.on('change', function() {
        input_workexp.parent().parent().parent().parent().parent().removeClass('border border-danger'); // hapus border
        input_workexp.removeClass('is-invalid'); // hapus kelas invalid
        $('#experiencedRadio').siblings('.invalid-tooltip').remove(); // hapus tooltip invalid

        // remove validation years text
        input_workexp_yearstext.removeClass('is-invalid'); // remove class invalid
        input_workexp_yearstext.siblings('.invalid-tooltip').remove(); // remove error tooltip
        input_workexp_at.removeClass('is-invalid'); // remove class invalid
        input_workexp_at.siblings('.invalid-tooltip').remove(); // remove error tooltip
        if($('input[name="work_exp"]:checked').val() == 1) { // cek jika cekbox work experience
            input_workexp_years.fadeIn(); // tampilkan kotak free text tahun
            input_workexp_at_container.slideDown(); // tampilkan kotak experienced at

            // tambahkan validasi untuk input_workexp_years
            // input_workexp_yearstext.addClass('is-invalid'); // add class invalid
            // input_workexp_yearstext.parent().append(msg_number); // add class invalid

            // input_workexp_at.addClass('is-invalid'); // add class invalid
            // input_workexp_at.parent().append(msg_fill); // tambahkan pesan msg_fill
        } else if($('input[name="work_exp"]:checked').val() == 0) { // cek jika cekbox fresh graduate
            input_workexp_years.fadeOut(); // sembunyikan kotak free text tahun
            input_workexp_yearstext.val(''); // kosongkan kotak we_years
            input_workexp_at_container.slideUp(); // tampilkan kotak experienced at
            input_workexp_at.val(''); // kosongkan value experienced at

        }
    });
    // Work Experience Years text validation
    input_workexp_yearstext.on('keyup change', function() {
        input_workexp_yearstext.removeClass('is-invalid'); // remove class invalid
        input_workexp_yearstext.siblings('.invalid-tooltip').remove(); // remove error tooltip
        if($.isNumeric(input_workexp_yearstext.val()) != true) { // cek jika value kosong
            if(input_workexp_yearstext.val() == ""){ // cek value yang diinput user
                input_workexp_yearstext.addClass('is-invalid'); // add class invalid
                input_workexp_yearstext.parent().append(msg_number); // show error tooltip
            } else {
                input_workexp_yearstext.addClass('is-invalid'); // add class invalid
                input_workexp_yearstext.parent().append(msg_number); // show error tooltip
            }
        } else {
            // nothing
        }
    });
    // Work Experience at text validation
    input_workexp_at.on('keyup change', function() {
        input_workexp_at.removeClass('is-invalid'); // remove class invalid
        input_workexp_at.siblings('.invalid-tooltip').remove(); // remove error tooltip
        if(input_workexp_at.val() == ""){
            input_workexp_at.addClass('is-invalid'); // add class invalid
            input_workexp_at.parent().append(msg_fill); // show error tooltip
        } else {
            // nothing
        }
    });

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
    // validate_job_level.on('change', function() {
    //     validate_job_level.removeClass('is-invalid'); // remove class invalid
    //     validate_job_level.removeClass('is-valid'); // remove class invalid
    //     validate_job_level.siblings('.invalid-tooltip').remove(); // remove class invalid
    //     if($(this).val() != ""){
    //         validate_job_level.addClass('is-valid'); // remove class invalid
    //         validate_job_level.siblings('.invalid-tooltip').remove(); // remove class invalid
    //     } else {
    //         validate_job_level.addClass('is-invalid'); // remove class invalid
    //         validate_job_level.parent().append(msg_choose); // show error tooltip
    //     }
    // });

    // validation emp_stats
    validate_empstats.on('change', function() {
        validate_empstats.removeClass('is-invalid'); // remove class invalid
        validate_empstats.removeClass('is-valid'); // remove class invalid
        validate_empstats.siblings('.invalid-tooltip').remove(); // remove class invalid
        let value = $(this).val();
        if(value != ""){
            validate_empstats.addClass('is-valid'); // remove class invalid
            validate_empstats.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else {
            validate_empstats.addClass('is-invalid'); // remove class invalid
            validate_empstats.parent().append(msg_choose); // show error tooltip
        }

        // jika pilihan temporary yang dipilih
        if(value == "emp_stats-3"){
            select_temporary_container.slideDown();
        } else {
            select_temporary_container.slideUp();
            select_temporary.val("");
        }

        // remove invalid class and invalid tooltip
        input_daterequired.removeClass('is-invalid'); // hapus kelas is invalid
        input_daterequired.siblings('.invalid-tooltip').remove();

        // untuk mengisi tanggal date required, langsung isi dengan aturan
        if(value == "emp_stats-1" || value == "emp_stats-2" || value == "emp_stats-3" || value == "emp_stats-5"){
            input_daterequired.val(moment().add(60, 'days').calendar({sameElse: 'DD-MM-YYYY'}));
        } else if(value == "emp_stats-4") {
            input_daterequired.val(moment().add(14, 'days').calendar({sameElse: 'DD-MM-YYYY'}));
        } else {
            input_daterequired.val(''); // kosongkan date required
        }
    });
    // validate Date Required
    select_temporary.on('keyup change', function(){
        select_temporary.removeClass('is-invalid'); // hapus kelas is invalid
        select_temporary.siblings('.invalid-tooltip').remove();
        if(select_temporary.val() == ""){
            select_temporary.addClass('is-invalid'); // tambah kelas invalid
            select_temporary.parent().append(msg_fill); // tampilkan pesan error
        } else {
            // nothing
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

    // validate interviewer 3
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
    // validate interviewer 4
    // validate interviewer name
    input_interviewer_name2.on('keyup change', function() {
        // hapus kelas validasi
        input_interviewer_position2.removeClass('is-invalid'); // hapus kelas is invalid
        input_interviewer_position2.siblings('.invalid-tooltip').remove();
        input_interviewer_name2.removeClass('is-invalid'); // hapus kelas is invalid
        input_interviewer_name2.siblings('.invalid-tooltip').remove();
        if(input_interviewer_name2.val() != ""){
            if(input_interviewer_position2.val() == ""){
                input_interviewer_position2.addClass('is-invalid'); // tambah kelas invalid
                input_interviewer_position2.parent().append(msg_fill); // tampilkan pesan error
            } else {
                // nothing
            }
        } else {
            if(input_interviewer_position2.val() != ""){
                if(input_interviewer_name2.val() == ""){
                    input_interviewer_name2.addClass('is-invalid'); // tambah kelas invalid
                    input_interviewer_name2.parent().append(msg_fill); // tampilkan pesan error
                    msg_validate += "<li>Interviewer Name is empty</li>"; // pesan empty
                    counter_validate++; // validate counter add
                } else {
                    // nothing
                }
            }
        }
    });
    // validate interviewer position
    input_interviewer_position2.on('keyup change', function() {
        // hapus kelas validasi
        input_interviewer_position2.removeClass('is-invalid'); // hapus kelas is invalid
        input_interviewer_position2.siblings('.invalid-tooltip').remove();
        input_interviewer_name2.removeClass('is-invalid'); // hapus kelas is invalid
        input_interviewer_name2.siblings('.invalid-tooltip').remove();
        if(input_interviewer_position2.val() != ""){
            if(input_interviewer_name2.val() == ""){
                input_interviewer_name2.addClass('is-invalid'); // tambah kelas invalid
                input_interviewer_name2.parent().append(msg_fill); // tampilkan pesan error
                msg_validate += "<li>Interviewer Position is empty is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else {
            if(input_interviewer_name2.val() != ""){
                if(input_interviewer_position2.val() == ""){
                    input_interviewer_position2.addClass('is-invalid'); // tambah kelas invalid
                    input_interviewer_position2.parent().append(msg_fill); // tampilkan pesan error
                    msg_validate += "<li>Interviewer Name is empty</li>"; // pesan empty
                    counter_validate++; // validate counter add
                } else {
                    // nothing
                }
            }
        }
    });

    <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1): ?>
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

            // empty mpp form
            $('#noiReq').val('-');
            input_mpp.val(''); // kosongkan value mpp
            input_mpp.attr('max', '1');
            input_mpp.attr('disabled', true);
            // remove any validation class on mpp form
            input_mpp.removeClass('is-invalid');
            input_mpp.removeClass('valid');
            input_mpp.siblings('.invalid-tooltip').remove();

            removeInterviewer(); // hapus interviewer
            get_jobLevel(""); // kosongkan job_level

            if($(this).val() != ""){
                // validator
                select_divisi.addClass('is-valid'); // remove class invalid
                select_divisi.siblings('.invalid-tooltip').remove(); // remove class invalid

                // get department data
                getDepartment(dipilih);
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
            let budget = $('input[name="budget"]:checked').val();

            // position
            input_jpchoose.attr('disabled', "true"); // hapus attribut disabled
            input_jpchoose.empty().append('<option selected value="">Choose Department first...</option>'); //kosongkan selection value dan tambahkan satu selection option
            // remove valid and invalid class
            input_jpchoose.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid

            // empty mpp form
            $('#noiReq').val('-');
            input_mpp.val(''); // kosongkan value mpp
            input_mpp.attr('max', '1');
            input_mpp.attr('disabled', true);
            // remove any validation class on mpp form
            input_mpp.removeClass('is-invalid');
            input_mpp.removeClass('valid');
            input_mpp.siblings('.invalid-tooltip').remove();

            removeInterviewer(); // hapus interviewer
            get_jobLevel(""); // kosongkan job_level

            if($("#departementForm").val() != ""){
                // validator
                select_department.addClass('is-valid'); // remove class invalid
                select_department.siblings('.invalid-tooltip').remove(); // remove class invalid

                getPosition(divisi, departemen, "", budget); // get position and interviewer data
            } else {
                // hapus interviewer 2
                $("#interviewer_name2").val("");
                $("#interviewer_position2").val("");
                $("#interviewer_name2").removeAttr('readonly');
                $("#interviewer_position2").removeAttr('readonly');

                // validator
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

    // fungsi untuk mengolah departemen
    function getDepartment(divisi, choose = ""){
        $.ajax({
            url: "<?php echo base_url('job_profile/ajax_getdepartement'); ?>",
            data: {
                divisi: divisi //kirim ke server php
            },
            method: "POST",
            success: function(data) { //jadi nanti dia balikin datanya dengan variable data
                if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> == 1){
                    select_department.removeAttr('disabled'); // hapus attribut disabled
                }
                select_department.empty().append('<option value="">Choose Department...</option>'); //kosongkan selection value dan tambahkan satu selection option

                $.each(JSON.parse(data), function(i, v) {
                    select_department.append('<option value="' + v.id + '">' + v.nama_departemen + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                });

                // jika admin pilih valuenya
                if(choose != ""){
                    select_department.val(choose);
                } else {
                    // validator
                    select_department.addClass('is-invalid'); // remove class invalid
                    select_department.parent().append(msg_choose); // show error tooltip
                }
            }
        });
    }

    // ambil joblevel
    function get_jobLevel(id_posisi = ""){
        if(id_posisi != ""){
            $.ajax({
                url: "<?= base_url('ptk/ajax_getJobLevel'); ?>",
                data: {
                    id_posisi: id_posisi
                },
                method: "POST",
                beforeSend: function(){
                    // select_jobLevel.attr('disabled', true); // tambahkan attribut disabled
                },
                success: function(data){
                    let vya = JSON.parse(data);
                    if(vya.data == undefined || vya.data == null || vya.data == ""){
                        select_jobLevel.val(""); // pilih yang kosong
                    } else {
                        // select_jobLevel.removeAttr('disabled'); // hapus attribut disabled
                        select_jobLevel.val(vya.data);
                    }
                },
                error: function(){
                    select_jobLevel.val(""); // pilih yang kosong
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Sorry, something went wrong!, Please Contact HC Care to get help.'
                    });
                }
            });
        } else {
            select_jobLevel_view.attr('disabled', true); // tambahkan attribut disabled
            select_jobLevel_view.val(""); // pilih value yang kosong
            select_jobLevel.val(""); // pilih value yang kosong
        }
    }

    // mendapatkan employee ketika udh diisiin positionnya
    function get_selectReplacementWho(position = "", choose = ""){
        // aktifkan form replacement who
        $('#replace').slideDown(); //  tampilkan form replacement who
        // select_replacement_who.removeAttr('disabled'); // aktifkan form replacement who
        // tambahkan tooltip fill replacement who
        // select_replacement_who.addClass('is-invalid'); // add class invalid
        // select_replacement_who.parent().append(msg_fill); // show error tooltip
        select_replacement_who.select2({theme: 'bootstrap4'}); // inisialisasi kalo replacement_who itu select2
        // tambahkan pilihan

        // cek apa positionnya kosong kalo kosong ganti dengan pilihan select position first
        if(position == "" || position == null || position == undefined){
            select_replacement_who.attr('disabled', true); // nonaktifkan kotak replacement who
            select_replacement_who.empty().append('<option selected value="">Select Position first</option>'); //kosongkan selection value dan tambahkan satu selection option
        } else {
            select_replacement_who.removeAttr('disabled'); // aktifkan form replacement who

            $.ajax({
                url: "<?= base_url('ptk/ajax_getEmployeeList'); ?>",
                data: {
                    position: position
                },
                method: "POST",
                beforeSend: function(){
                    select_replacement_who.attr('disabled', true); // nonaktifkan kotak replacement who
                },
                success: function(data, status, jqXHR){
                    if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> == 1){
                        select_replacement_who.removeAttr('disabled'); // aktifkan form replacement who jika mode edit
                    }

                    if(data == undefined || data == null || data == ""){
                        // nothing
                    } else {
                        let vya = JSON.parse(data);
                        select_replacement_who.empty().append('<option value="">Replacement who?</option>'); //kosongkan selection value dan tambahkan satu selection option

                        $.each(vya.data, function(index, value){
                            select_replacement_who.append('<option value="'+value.value+'">'+value.text+'</option>'); //kosongkan selection value dan tambahkan satu selection option
                        });
                    }

                    if(choose == undefined || choose == null || choose == ""){
                        // nothing
                    } else {
                        select_replacement_who.val(choose);
                    }
                },
                error: function(jqXHR, error){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Sorry, something went wrong!, Please Contact HC Care to get help.'
                    });
                }
            });
        }
    }

    // function to get position and interviewer data
    function getPosition(divisi, departemen, choose = "", budget = ""){
        $.ajax({
            url: "<?= base_url('ptk/ajax_getPosition'); ?>",
            data: {
                budget: budget,
                divisi: divisi,
                departemen: departemen
            },
            method: "POST",
            beforeSend: function(){
                if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> == 1){
                    input_jpchoose.attr('disabled', true); // hapus attribut disabled
                }
            },
            success: (data) => {
                if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> == 1){
                    input_jpchoose.removeAttr('disabled'); // hapus attribut disabled
                }
                input_jpchoose.empty().append('<option value="">Choose Position...</option>'); //kosongkan selection value dan tambahkan satu selection option
                $.each(JSON.parse(data), function(i, v) {
                    input_jpchoose.append('<option value="' + v.id + '">' + v.position_name + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                });

                // jika choose tidak kosong, pilih valuenya
                if(choose != ""){
                    input_jpchoose.val(choose);
                }

                // validaotr
                // input_jpchoose.addClass('is-invalid'); // remove class invalid
                // input_jpchoose.parent().append(msg_choose); // show error tooltip
            }
        });
    }

    // menambahkan interviewer dengan melihat position
    function getInterviewer(position){
        // get interviewer data
        $.ajax({
            url: '<?= base_url("ptk/ajax_getInterviewer"); ?>',
            data: {
                position: position
            },
            method: "POST",
            success: function(data){
                let vya = JSON.parse(data);
                // cek apa ada data approver1nya
                if(vya.approver1 == "" || vya.approver1 == undefined || vya.approver1 == null){
                    // hapus attribut disabled
                    $("#interviewer_name1").val("");
                    $("#interviewer_position1").val("");
                    $("#interviewer_name1").attr('disabled', true);
                    $("#interviewer_name1").removeAttr('readonly');
                    $("#interviewer_name1").addClass('form-control');
                    $("#interviewer_name1").removeClass('form-control-plaintext');
                    $("#interviewer_position1").attr('disabled', true);
                    $("#interviewer_position1").removeAttr('readonly');
                    $("#interviewer_position1").addClass('form-control');
                    $("#interviewer_position1").removeClass('form-control-plaintext');
                } else {
                    // taruh data interviewer dengan data approver1
                    $("#interviewer_name1").val(vya.approver1.emp_name);
                    $("#interviewer_position1").val(vya.approver1.position_name);
                    $("#interviewer_name1").attr('readonly', true);
                    $("#interviewer_name1").removeAttr('disabled');
                    $("#interviewer_name1").removeClass('form-control');
                    $("#interviewer_name1").addClass('form-control-plaintext');
                    $("#interviewer_position1").attr('readonly', true);
                    $("#interviewer_position1").removeAttr('disabled');
                    $("#interviewer_position1").removeClass('form-control');
                    $("#interviewer_position1").addClass('form-control-plaintext');
                }
                // cek apa ada data approver2nya
                if(vya.approver2 == "" || vya.approver2 == undefined || vya.approver2 == null){
                    // hapus attribut disabled
                    $("#interviewer_name2").val("");
                    $("#interviewer_position2").val("");
                    $("#interviewer_name2").attr('disabled', true);
                    $("#interviewer_name2").removeAttr('readonly');
                    $("#interviewer_name2").addClass('form-control');
                    $("#interviewer_name2").removeClass('form-control-plaintext');
                    $("#interviewer_position2").attr('disabled', true);
                    $("#interviewer_position2").removeAttr('readonly');
                    $("#interviewer_position2").addClass('form-control');
                    $("#interviewer_position2").removeClass('form-control-plaintext');
                } else {
                    // taruh data interviewer approver2
                    $("#interviewer_name2").val(vya.approver2.emp_name);
                    $("#interviewer_position2").val(vya.approver2.position_name);
                    $("#interviewer_name2").attr('readonly', true);
                    $("#interviewer_name2").removeAttr('disabled');
                    $("#interviewer_name2").removeClass('form-control');
                    $("#interviewer_name2").addClass('form-control-plaintext');
                    $("#interviewer_position2").attr('readonly', true);
                    $("#interviewer_position2").removeAttr('disabled');
                    $("#interviewer_position2").removeClass('form-control');
                    $("#interviewer_position2").addClass('form-control-plaintext');
                }
            },
            error: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Sorry, something went wrong!, Please Contact HC Care to get help.'
                });
            }
        });
    }

    // get number of incumbent dengan id_posisi
    function getNoi(id_posisi, budget){
        $.ajax({
            url: '<?= base_url("ptk/ajax_getPositionMpp"); ?>',
            data: {
                id_posisi: id_posisi,
                budget: budget
            },
            method: "POST",
            success: function(data){
                let vya = JSON.parse(data);
                $('#noiReq').val(vya.noi);
                $('#noiReq').data('empty', vya.empty);
                input_mpp.attr('max', vya.empty);
                input_mpp.removeAttr('disabled');
            },
            error: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Sorry, something went wrong!, Please Contact HC Care to get help.'
                });
            }
        });
    }

    // hide replacement who and reset its value
    function hide_selectReplacementWho(){
        $('#replace').slideUp(); // sembunyikan form replacement who
        // select_replacement_who.attr('disabled', true); // nonaktifkan kotak replacement who
        select_replacement_who.empty().append('<option selected value="">Replacement who?</option>'); //kosongkan selection value dan tambahkan satu selection option
        select_replacement_who.val(''); // kosongkan kotak replacement who
        // select_replacement_who.select2("destroy"); // hancurkan select2 replacement_who
    }

    // function untuk mengaktifkan tab job profile
    function showMeJobProfile(id_posisi){
        if(id_posisi != "" && ($('input[name="budget"]:checked').val() == 1 || $('input[name="budget"]:checked').val() == 2)){
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

    // function untuk menghapus interviewer 1 dan 2
    function removeInterviewer(){
        // hapus nama di interviewer 1 dan 2
        $("#interviewer_name1").val("");
        $("#interviewer_position1").val("");
        // $("#interviewer_name1").removeAttr('readonly');
        $("#interviewer_name1").addClass('form-control');
        $("#interviewer_name1").removeClass('form-control-plaintext');
        // $("#interviewer_position1").removeAttr('readonly');
        $("#interviewer_position1").addClass('form-control');
        $("#interviewer_position1").removeClass('form-control-plaintext');
        $("#interviewer_name2").val("");
        $("#interviewer_position2").val("");
        // $("#interviewer_name2").removeAttr('readonly');
        $("#interviewer_name2").addClass('form-control');
        $("#interviewer_name2").removeClass('form-control-plaintext');
        // $("#interviewer_position2").removeAttr('readonly');
        $("#interviewer_position2").addClass('form-control');
        $("#interviewer_position2").removeClass('form-control-plaintext');
    }

    /* -------------------------------------------------------------------------- */
    /*                             datepicker setting                             */
    /* -------------------------------------------------------------------------- */
    $('.ptkpickdate').datepicker({
        format: "dd-mm-yyyy",
        weekStart: 1,
        startView: 1,
        multidate: false,
        daysOfWeekDisabled: "0,6",
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
        todayHighlight: true,
        startDate: 'default'
    });

    /* -------------------------------------------------------------------------- */
    /*                          Tippy JS Tooltip trigger                          */
    /* -------------------------------------------------------------------------- */
    // With the above scripts loaded, you can call `tippy()` with a CSS
    // selector and a `content` prop:

    // // Entity
    // tippy('#entityInput', {
    //     content: 'Please choose one entity',
    // });

    // // Job Position
    // // alert budget
    // tippy('#budgetAlert', {
    //     content: 'Please Choose one Budget',
    // });
    // // Job Title free text
    // tippy('#jobTitleInput', {
    //     content: 'Job Position Free Text',
    // });
    // // job Position selection
    // tippy('#positionInput', {
    //     content: 'Job Position Selection',
    // });

    // // Job Level
    // tippy('.jobLevelForm', {
    //     content: 'Job Level',
    // });

    // // Work Location
    // // Work Location selection
    // tippy('#work_location_choose', {
    //     content: 'Work Location selection',
    // });
    // // Work Location Text
    // tippy('#work_location_text', {
    //     content: 'Work Location Text',
    // });
    // // Work Location Other Trigger
    // tippy('#work_location_otherTrigger', {
    //     content: 'Work Location other trigger',
    // });

    // // budget
    // tippy('#chooseBudget', {
    //     content: 'Budget',
    // });

    // // Replacement
    // // replacement trigger
    // tippy('#replace', {
    //     content: 'Replace',
    // });
    // // Replacement Who
    // tippy('#replacement_who', {
    //     content: 'Replacement Who',
    // });

    // // Resource
    // tippy('#resource', {
    //     content: 'Resource',
    // });
    // // Internal Who
    // tippy('#internal_who', {
    //     content: 'Internal Who',
    // });

    // // Man Power Required
    // tippy('#mppReq', {
    //     content: 'Man Power Required',
    // });

    // // Number of Incumbent
    // tippy('#noiReq', {
    //     content: 'Number of Incumbent',
    // });

    // // Employement Status
    // tippy('#emp_stats', {
    //     content: 'Employement Status',
    // });

    // // Date required
    // tippy('#date_required', {
    //     content: 'Date Required',
    // });

    // // Education
    // tippy('#education', {
    //     content: 'Education',
    // });
    // // Majoring
    // tippy('#majoring', {
    //     content: 'Majoring',
    // });

    // // Age
    // tippy('#age', {
    //     content: 'Preferred Age',
    // });
    // // Sex
    // tippy('#sexForm', {
    //     content: 'Sex',
    // });

    // // Fresh Graduate
    // tippy('#freshGradRadio', {
    //     content: 'Fresh Graduate',
    // });
    // // Experienced
    // tippy('#experiencedRadio', {
    //     content: 'Experienced',
    // });
    // // Work Experience Years
    // tippy('#we_years', {
    //     content: 'Work Experienced Years',
    // });

    // // Skill, Knowledge, and abilities (ska)
    // tippy('#ska_label', {
    //     content: 'Skill, Knowledge, and abilities (ska)',
    // });

    // // Special Requirement
    // tippy('#reqSpecial_label', {
    //     content: 'Special Requirement',
    // });

    // // Outline
    // tippy('#outline_label', {
    //     content: 'Outline',
    // });

    // // Interviewer
    // tippy('#interviewer_name3', {
    //     content: 'Interviewer Name',
    // });
    // tippy('#interviewer_position3', {
    //     content: 'Interviewer Position',
    // });

    // // Main Responsibilities
    // tippy('#main_responsibilities_label', {
    //     content: 'main_responsibilities',
    // });

    // // Tasks
    // tippy('#tasks_label', {
    //     content: 'Tasks',
    // });
</script>