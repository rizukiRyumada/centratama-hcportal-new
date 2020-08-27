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

    // Job Position Form trigger from budget radio button
    $('input[name="budget"]').on('change', function() {
        $("#budgetAlert").hide();
        if($('input[name="budget"]:checked').val() == 0) { // cek jika unbudgeted
            $('input[name="job_position_text"]').fadeIn(); // tampilkan free text buat nulis nama job 
            $('select[name="job_position_choose"]').hide(); // sembunyikan pilihan posisi job
            $('#positionInput').prop('selectedIndex',0);// kembalikan status ke default - position chooser

            // sembunyikan tab job Profile dan orgChart
            $('#tab_jobProfile').fadeOut();
            $('#tab_orgChart').fadeOut();
        } else if($('input[name="budget"]:checked').val() == 1) { // cek jika budgeted
            $('select[name="job_position_choose"]').fadeIn(); // tampilkan pilihan job position 
            $('input[name="job_position_text"]').hide(); // sembunyikan free text job profile
            $('input[name="job_position_text"]').val(''); // kosongkan kotak job_position_text
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

    // Work Locations Other input checkbox
    $('#work_location_otherTrigger'). on('change', function(){
        if($('#work_location_otherTrigger').prop('checked') == true) {
            // jika diceklis, tampilkan input free text work location
            $('input[name="work_location_text"]').val('');
            $('input[name="work_location_text"]').show();
            $('select[name="work_location_choose"]').hide();
            // pilih, pilihan pertama selected option location list
            $('select[name="work_location_choose"]').prop('selectedIndex', 0);
        } else if($('#work_location_otherTrigger').prop('checked') == false) {
            // jika tidak diceklis, tampilkan pilihan work location
            $('select[name="work_location_choose"]').show();
            $('input[name="work_location_text"]').hide();
            // isi dummy text di input free text work location
            $('input[name="work_location_text"]').val('-');
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

    // Customized Form Validation
    $('#ptkForm').submit(function() {
        console.log('submitted');
    });

/* -------------------------------------------------------------------------- */
/*                                // Functions                                */
/* -------------------------------------------------------------------------- */
    
</script>