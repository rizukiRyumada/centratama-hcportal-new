<script>
    // siapkan variable penampung
    var survey_selected = "";

    $(document).ready(() => {
        $.ajax({
            url: "<?= base_url('appSettings/ajax_getStatusSuvey'); ?>",
            success: (data) => {
                let vya = JSON.parse(data);
                let msg_1 = '<span class="badge badge-success">Running Period</span>';
                let msg_0 = '<span class="badge badge-danger">Outdated Period</span>';

                let statusEng = $('#statusEng');
                if(vya.eng == 1){
                    statusEng.children('.fa').remove();
                    statusEng.append(msg_1);
                } else {
                    statusEng.children('.fa').remove();
                    statusEng.append(msg_0);
                    statusEng.parent().parent().append('<a href="javascript:changePeriods'+"('eng')"+'" class="btn btn-light text-dark w-100"><i class="fas fa-plus-circle text-success"></i> New Period</a>');
                }
                let statusExc = $('#statusExc');
                if(vya.exc == 1){
                    statusExc.children('.fa').remove();
                    statusExc.append(msg_1);
                } else {
                    statusExc.children('.fa').remove();
                    statusExc.append(msg_0);
                    statusExc.parent().parent().append('<a href="javascript:changePeriods'+"('exc')"+'" class="btn btn-light text-dark w-100"><i class="fas fa-plus-circle text-success"></i> New Period</a>');
                }
                let status360 = $('#status360');
                if(vya.f360 == 1){
                    status360.children('.fa').remove();
                    status360.append(msg_1);
                } else {
                    status360.children('.fa').remove();
                    status360.append(msg_0);
                    status360.parent().parent().append('<a href="javascript:changePeriods'+"('360')"+'" class="btn btn-light text-dark w-100"><i class="fas fa-plus-circle text-success"></i> New Period</a>');
                }
            }
        });

        toggleUpdate(); // toggle update view
    });

    // untuk menerima event klik dari tag a
    function changePeriods(survey){
        // set jenis survey yang mau diatur
        survey_selected = survey;

        // swal are you sure
        Swal.fire({
            title: 'Are you sure?',
            html: "<p>You won't be able to revert this!</p>"+'<small class="font-weight-bold">The survey data will be moved to archives database (hcportal_archives).</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#0072c6',
            confirmButtonText: 'Yes, Start new periods!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                // tampilkan modal tantangan typeit challenge
                $('#typeItModal').modal('show');
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel) {
                // jika tidak tampilkan pesan gagal
                Swal.fire(
                    'Cancelled',
                    "Okay I'll make everything untounchable.",
                    'error'
                )
            }
        });
    }

    // variable preparation
    let input_typeit = $('input[name="typeit"]'); // selector input typeit
    let msg = ['<span id="exampleInputEmail1-error" class="error invalid-feedback">', '</span>'];
    let msg_empty = msg[0]+'Please enter the phare above.'+msg[1];
    let msg_notmatch = msg[0]+'The Phrase you typed is not match, please try again.'+msg[1];
    // modal dialog submit challenge
    $('#checkInput').on('click', function(){
        let typed = input_typeit.val(); // ambil data yang diinput
        // validate input type it first
        if(validate_input_typeit() == true){
            $.ajax({
                url: "<?= base_url('appSettings/ajax_survey_newPeriods'); ?>",
                data: {
                    survey: survey_selected
                },
                method: "POST",
                beforeSend: () => {
                    $('#typeItModal').modal('hide'); // hide the modal
                    // remove typeit attribute
                    input_typeit.removeClass('is-invalid is-valid');
                    input_typeit.siblings('.invalid-feedback').remove();
                    input_typeit.val(""); // kosongkan typeit form
                    Swal.fire({
                        icon: 'info',
                        title: 'Please Wait',
                        html: '<p>'+"Please don't close this tab and the browser, the survey data is being archived."+'<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false
                    });
                },
                success: (data) => {
                    input_typeit.removeClass('is-invalid is-valid'); // remove class valid
                    input_typeit.val(""); // kosongkan input type

                    // cek buat nampilin pesan
                    if(data == 1){
                        Swal.fire({
                            icon: 'success',
                            title: 'New Period Started',
                            html: '<p>'+"The Survey Data has been archived to hcportal_archives and new period of survey has been started."+'<br/><br/>Refreshing this page...<br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        });
                        // refresh the page
                        setTimeout(function() {
							location.reload();
						}, 2000);
                    } else if(data == 2) {
                        Swal.fire(
                            'Survey Data is Null',
                            'There is no employee fill the survey, cannot continue without survey data.',
                            'error'
                        )
                    } else if(data == 0){
                        Swal.fire(
                            'Now is still the period of this Survey',
                            'Cannot start new survey period because the period is still on the way.',
                            'error'
                        )
                    } else {
                        Swal.fire(
                            '404 Unknown Error',
                            'There is an unknown error, please contact HC Care to get more assistance.',
                            'error'
                        )
                    }
                },
                error: (data) => {
                    Swal.fire(
                        'Error',
                        'There is an error occured, please contact HC Care.',
                        'error'
                    )
                }
            });
        }
    });

    //validator input typeit
    input_typeit.on('keyup', function(){
        // validate input type it first
        validate_input_typeit();
    });

    // toggle suvey
    $("#toggle_eng").on('click', function(){
        if($(this).prop("checked") == true) {
            id_survey = $(this).data('survey');
            is_period = 1;
        } else if($(this).prop("checked") == false) {
            id_survey = $(this).data('survey');
            is_period = 0;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                footer: '<a href>Why do I have this issue?</a>'
            });
            return false;
        }
        $(".toggle-loading").show(); // sembunyikan toggle loading
        $(".toggle-area").hide(); // tampilkan toggle area
        togglePeriods(id_survey, is_period); 
    });
    $("#toggle_exc").on('click', function(){
        if($(this).prop("checked") == true) {
            id_survey = $(this).data('survey');
            is_period = 1;
        } else if($(this).prop("checked") == false) {
            id_survey = $(this).data('survey');
            is_period = 0;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                footer: '<a href>Why do I have this issue?</a>'
            });
            return false;
        }
        $(".toggle-loading").show(); // sembunyikan toggle loading
        $(".toggle-area").hide(); // tampilkan toggle area
        togglePeriods(id_survey, is_period); 
    });
    $("#toggle_360").on('click', function(){
        if($(this).prop("checked") == true) {
            id_survey = $(this).data('survey');
            is_period = 1;
        } else if($(this).prop("checked") == false) {
            id_survey = $(this).data('survey');
            is_period = 0;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                footer: '<a href>Why do I have this issue?</a>'
            });
            return false;
        }
        $(".toggle-loading").show(); // sembunyikan toggle loading
        $(".toggle-area").hide(); // tampilkan toggle area
        togglePeriods(id_survey, is_period); 
    });   
    
    function togglePeriods(id_survey, is_period){
        $.ajax({
            url: "<?= base_url('appSettings/ajax_periodToggle'); ?>",
            data:{
                id_survey: id_survey,
                is_period: is_period
            },
            method: "POST",
            success: function() {
                toggleUpdate(); // toggle update view
            }
        });
    }

    // this function to change toggle view
    function toggleUpdate(){
        $.ajax({
            url: "<?= base_url("appSettings/ajax_periodToggleIsPeriod"); ?>",
            beforeSend: () => {
                // $(".toggle-loading").show(); // sembunyikan toggle loading
                // $(".toggle-area").hide(); // tampilkan toggle area
            },
            success: (data) => {
                let vya = JSON.parse(data);

                // ganti toggle eng
                if(vya.eng == 0){
                    $('#toggle_eng').prop('checked', false).change();
                    $('#surveyCards_eng').addClass("bg-light");
                    $('#surveyCards_eng').removeClass("bg-blue");
                } else {
                    $('#toggle_eng').prop('checked', true).change();
                    $('#surveyCards_eng').addClass("bg-blue");
                    $('#surveyCards_eng').removeClass("bg-light");
                }
                // ganti toggle exc
                if(vya.exc == 0){
                    $('#toggle_exc').prop('checked', false).change();
                    $('#surveyCards_exc').addClass("bg-light");
                    $('#surveyCards_exc').removeClass("bg-orange");
                } else {
                    $('#toggle_exc').prop('checked', true).change();
                    $('#surveyCards_exc').addClass("bg-orange");
                    $('#surveyCards_exc').removeClass("bg-light");
                }
                // ganti toggle f360
                console.log(vya.f360);
                if(vya.f360 == 0){
                    $('#toggle_360').prop('checked', false).change();
                    $('#surveyCards_360').addClass("bg-light");
                    $('#surveyCards_360').removeClass("bg-yellow");
                } else {
                    $('#toggle_360').prop('checked', true).change();
                    $('#surveyCards_360').addClass("bg-yellow");
                    $('#surveyCards_360').removeClass("bg-light");
                }
                $(".toggle-loading").hide(); // sembunyikan toggle loading
                $(".toggle-area").show(); // tampilkan toggle area
            }
        });
    }

    function validate_input_typeit(){
        let typed = input_typeit.val(); // ambil data yang diinput
        input_typeit.removeClass('is-invalid is-valid');
        input_typeit.siblings('.invalid-feedback').remove();
        if(typed == "" || typed == undefined || typed == null){
            input_typeit.addClass('is-invalid');
            input_typeit.parent().append(msg_empty);
            return false;
        } else if(typed != "saya yakin untuk memulai periode baru"){
            input_typeit.addClass('is-invalid');
            input_typeit.parent().append(msg_notmatch);
            return false;
        } else {
            input_typeit.addClass('is-valid');
            return true;
        }
    }
    
    // jika tidak sama teksnya minta user buat ketikkan lagi
    // jika sama peringatkan kembali
    // jika iya proses dengan ajax
    // tampilkan swal loading dengan pesan jangan tutup browser
    // jika gagal tampilkan swal error
    // jika berhasil tampilkan pesan sukses dan refresh halaman.
</script>