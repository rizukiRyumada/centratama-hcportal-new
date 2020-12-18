<script>
    var flag_page = 0; // penanda flag_page untuk ngasih tau kalo ini editor
</script>
<?php $this->load->view('_komponen/pmk/script_ajax_getAssessmentData'); // script ajax assessment ?>
<?php $this->load->view('_komponen/pmk/script_assessment_rerata'); // partial rerata counter assessment ?>

<script>
    // form validation
    $("#button_save").on("click", function(e){
        e.preventDefault(); // prevent default action
        $('input[name="action"]').val("0"); // tandai flag action kalo form disave
        if(formValidate() == 0){ // jika tidak ada error
            // return true; // kirimkan form ke server
            Swal.fire({
                icon: 'info',
                title: 'Please Wait',
                html: '<p>'+"Please don't close this tab and the browser, your assessment for this employee is being saved."+'<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });
            $("#form_assessment").submit();
        } else {
            return false; // jangan kirimkan form
        }
    });
    <?php if($is_access == 1): ?>
        $("#button_submit").on("click", function(e){
            e.preventDefault(); // prevent default action
            $('input[name="action"]').val("1");

            if(formValidate() == 0){ // jika tidak ada error
                Swal.fire({ 
                    title: 'Are you sure?',
                    // text: "This assessment will be addressed to your superior, please give the assessment carefully, your assessment will have an effect for this employee in the future.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Please Wait',
                            html: '<p>'+"Please don't close this tab and the browser, your assessment for this employee is being submitted."+'<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        });
                        $("#form_assessment").submit(); // submit form
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel) {
                            // Swal.fire(
                            //     'Okay',
                            //     'Please have a more time to review this employee assessment.',
                            //     'info'
                            // )
                            toastr["info"]("Please have a more time to review this employee assessment."); // tampilkan toastr error
                            // scroll top
                            // document.body.scrollTop = 0;
                            // document.documentElement.scrollTop = 0;
                            return false;
                        }
                });
            } else {
                return false; // jangan kirimkan form
            }
        });
    <?php endif; ?>

    // button used for delette pertanyaan form and its answer
    $('.btn-delete').on('click', function(){
        let pertanyaan = $(this).data('input');
        let jawaban = $(this).data('input_answer');
        console.log(jawaban);
        $('input[name="'+pertanyaan+'"]').val('');
        $('input[name="'+jawaban+'"]').prop("checked", false);
        removeVariableB($(this).data('input_choose'));
        removePesanError(pertanyaan, jawaban); // hapus pesan error
        hitungRerataB();
    });
     
/* -------------------------------------------------------------------------- */
/*                                // validator                                */
/* -------------------------------------------------------------------------- */
    <?php foreach($pertanyaan as $k => $v): ?>
        <?php $id_name = explode("-", $v['id_pertanyaan']); ?>
        // $('input[name="<?= $v['id_pertanyaan']; ?>"]:checked')
        $('input[name="<?= $v['id_pertanyaan']; ?>"]').on('change', function() {
            $(this).parent().parent().parent().parent().parent().removeClass('border border-danger my-3 pt-2');
            $(this).parent().parent().parent().parent().parent().addClass('py-2');
            // $(this).parent().parent().parent().parent().parent().addClass('py-2', {duration:500,effect:'fade'});
            $(this).parent().parent().parent().siblings('.error-message').hide( "blind", 250, function () {
                $(this).parent().parent().parent().siblings('.error-message').remove(); // show error tooltip
            });
        });
    <?php endforeach;?>

    // ini untuk form pertanyaan technical (assessment B)
    <?php for($x = 0; $x < 5; $x++): ?>
        $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').on('change', function() {
            if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]').val() != undefined){
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().removeClass('border border-danger my-3 pt-2');
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('py-2');
                // $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('py-2', {duration:500,effect:'fade'});
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').hide( "blind", 250, function () {
                    $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').remove(); // show error tooltip
                });
            }
        });

        // cek buat pertanyaan kustom
        $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]').on('keyup', function(){
            if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]:checked').val() == undefined){
                removePesanErrorTechnical('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]'); // hapus pesan error technical
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().removeClass('border border-danger my-3 pt-2'); // takut duplikat jadinya dihapus dulu
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('border border-danger my-3 pt-2'); // tambahkan kelas yang diperlukan
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().removeClass('py-2'); // hapus padding
                if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').is('div.error-message') == false){
                    $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().append(msg_choose) // show error tooltip
                }
            } else {
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().removeClass('border border-danger my-3 pt-2');
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('py-2');
                // $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('py-2', {duration:500,effect:'fade'});
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').hide( "blind", 250, function () {
                    $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').remove(); // show error tooltip
                });
            }

            if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]').val() == ""){
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().removeClass('border border-danger my-3 pt-2');
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('py-2');
                // $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('py-2', {duration:500,effect:'fade'});
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').hide( "blind", 250, function () {
                    $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').remove(); // show error tooltip
                });
            }

            let pertanyaan_kustom = $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]').val();
            if(pertanyaan_kustom == ""){
                jawabanB0[<?= $x; ?>] = 0;
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]:checked').prop("checked", false);
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').attr('disabled', true);
            } else {
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').removeAttr('disabled');
            }

            hitungRerataB(); // hitung reratanya
        });
    <?php endfor; ?>

    // function form validate
    function formValidate(){
        var validate = 0;
        var input_value = [
            <?php foreach($pertanyaan as $k => $v): ?>
                <?php $id_name = explode("-", $v['id_pertanyaan']); ?>
                <?php if(array_key_last($pertanyaan) == $k): ?>
                    {
                        key : "<?= $v['id_pertanyaan']; ?>",
                        value : $('input[name="<?= $v['id_pertanyaan']; ?>"]:checked').val()
                    }
                <?php else: ?>
                    {
                        key : "<?= $v['id_pertanyaan']; ?>",
                        value : $('input[name="<?= $v['id_pertanyaan']; ?>"]:checked').val()
                    },
                <?php endif; ?>
            <?php endforeach;?>
        ];

        let gulir = 0;

        // untuk pertanyaan soft competency
        $.each(input_value, (index, value) => {
            if(value.value == undefined){ // jika ada jawaban yang kosong
                // $('#'+value.key+'1').addClass('is-invalid');
                $('#'+value.key+'1').parent().parent().parent().parent().parent().removeClass('border border-danger my-3 pt-2'); // takut duplikat jadinya dihapus dulu
                $('#'+value.key+'1').parent().parent().parent().parent().parent().addClass('border border-danger my-3 pt-2'); // tambahkan kelas yang diperlukan
                $('#'+value.key+'1').parent().parent().parent().parent().parent().removeClass('py-2'); // hapus padding
                if($('#'+value.key+'1').parent().parent().parent().siblings('.error-message').is('div.error-message') == false){
                    $('#'+value.key+'1').parent().parent().parent().parent().append(msg_choose) // show error tooltip
                }
                // $('#'+value.key+'1').parent().parent().parent().siblings('.error-message').show("blind", 250); // show error tooltip with animation but first set element to style="display:none;"

                // untuk mengarahkan ke arah yang belum diisi
                if(gulir == 0){
                    var $window = $(window),
                        $element = $('#'+value.key+'1'),
                        elementTop = $element.offset().top,
                        elementHeight = $element.height(),
                        viewportHeight = $window.height(),
                        scrollIt = elementTop - ((viewportHeight - elementHeight) / 2);

                    $window.scrollTop(scrollIt);
                    gulir++; // flag untuk bergulir ke form yang masih kosong
                }
                validate++; // flag untuk validasi
            }
        });

        // untuk pertanyaan technical competency
        let validate_technical = 0;
        <?php for($x = 0; $x < 5; $x++): ?>
            if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]').val() != ""){
                validate_technical++;
                if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]:checked').val() == undefined){
                    $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().removeClass('border border-danger my-3 pt-2'); // takut duplikat jadinya dihapus dulu
                    $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('border border-danger my-3 pt-2'); // tambahkan kelas yang diperlukan
                    $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().removeClass('py-2'); // hapus padding
                    if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').is('div.error-message') == false){
                        $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().append(msg_choose) // show error tooltip
                    }
                    if(gulir == 0){
                        var $window = $(window),
                            $element = $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]'),
                            elementTop = $element.offset().top,
                            elementHeight = $element.height(),
                            viewportHeight = $window.height(),
                            scrollIt = elementTop - ((viewportHeight - elementHeight) / 2);
    
                        $window.scrollTop(scrollIt);
                        gulir++; // flag untuk bergulir ke form yang masih kosong
                    }
                    validate++; // flag untuk validasi
                }
            }
        <?php endfor; ?>
        // untuk melihat apa pertanyaan technical ada yang null
        if(validate_technical == 0){
            $('input[name="B0-<?= str_pad(1, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().parent().removeClass('border border-danger mb-0 pb-0'); // takut duplikat jadinya dihapus dulu
            $('input[name="B0-<?= str_pad(1, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().parent().addClass('border border-danger mb-0 pb-0'); // tambahkan kelas yang diperlukan
            // $('input[name="B0-<?= str_pad(1, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().parent().removeClass('py-2'); // hapus padding
            if($('input[name="B0-<?= str_pad(1, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().parent().siblings('.error-message').is('div.error-message') == false){
                $('input[name="B0-<?= str_pad(1, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().parent().append(msg_chooseAndFill); // show error tooltip
            }
            if(gulir == 0){
                var $window = $(window),
                    $element = $('input[name="B0-<?= str_pad(1, 2, '0', STR_PAD_LEFT); ?>"]'),
                    elementTop = $element.offset().top,
                    elementHeight = $element.height(),
                    viewportHeight = $window.height(),
                    scrollIt = elementTop - ((viewportHeight - elementHeight) / 2);
                $window.scrollTop(scrollIt);
                gulir++; // flag untuk bergulir ke form yang masih kosong
            }
            validate++; // flag untuk validasi
        }
        return validate;
    }

    // untuk menghapus pesan error pertanyaan kustom
    function removePesanError(input_name, input_answer){
        let pertanyaan_kustom = $('input[name="'+input_name+'_pertanyaan"]').val();
        if(pertanyaan_kustom == undefined){
            $('input[name="'+input_answer+'"]:checked').prop("checked", false);
            $('input[name="'+input_answer+'"]').attr('disabled', true);
        } else {
            $('input[name="'+input_answer+'"]').removeAttr('disabled');
        }
        hitungRerataB(); // hitung reratanya
        $('input[name="'+input_answer+'"]').parent().parent().parent().parent().parent().removeClass('border border-danger my-3 pt-2');
        $('input[name="'+input_answer+'"]').parent().parent().parent().parent().parent().addClass('py-2');
        // $('input[name="'+input_answer+'"]').parent().parent().parent().parent().parent().addClass('py-2', {duration:500,effect:'fade'});
        $('input[name="'+input_answer+'"]').parent().parent().parent().siblings('.error-message').hide( "blind", 250, function () {
            $('input[name="'+input_answer+'"]').parent().parent().parent().siblings('.error-message').remove(); // remove error message
        });
    }

    // untuk menghapus pesan error technical assessment
    function removePesanErrorTechnical(input_name){
        $(input_name).parent().parent().parent().parent().parent().parent().parent().removeClass('border border-danger mb-0 pb-0'); // takut duplikat jadinya dihapus dulu
        // $(input_name).parent().parent().parent().parent().parent().parent().removeClass('py-2'); // hapus padding
        $(input_name).parent().parent().parent().parent().parent().parent().siblings('.error-message').remove(); // remove error message
    }
</script>