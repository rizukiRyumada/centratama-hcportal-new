<script>
    // siapkan variable penampung
    var survey_selected = "";
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
                    Swal.fire({
                        icon: 'info',
                        title: 'Please Wait',
                        html: '<p>'+"Please don't close this tab and the browser, the survey data is being moved to archives database."+'<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                        showConfirmButton: false,
                        // allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false
                    });
                },
                success: (data) => {
                    input_typeit.removeClass('is-invalid is-valid'); // remove class valid
                    input_typeit.val(""); // kosongkan input type
                    Swal.fire(
                        'New Period Started',
                        'The Survey Data has been archived to hcportal_archives and new period of survey has been started',
                        'success'
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