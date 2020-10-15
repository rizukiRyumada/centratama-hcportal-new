<script>
    // preparation
    // message validation
    var choose = "Please choose one value.";
    var fill   = "This field is required.";
    var number = "The input is required and should be number.";

    // tooltip validation
    // var msg_choose = '<div class="invalid-tooltip" style="display: block">'+choose+'</div>' ;
    var msg_fill   = '<div class="invalid-tooltip" style="display: block">'+fill+'</div>' ;
    var msg_number = '<div class="invalid-tooltip" style="display: block">'+number+'</div>';
    var msg_choose = '<div class="error-message row mt-2 py-2 bg-danger" ><div class="col text-center">'+choose+'</div></div>';

    // form validation
    $("#button_save").on("click", function(e){
        e.preventDefault(); // prevent default action
        $('input[name="action"]').val("0"); // tandai flag action kalo form disave
        if(formValidate() == 0){ // jika tidak ada error
            // return true; // kirimkan form ke server
            $("#form_assessment").submit();
        } else {
            return false; // jangan kirimkan form
        }
    });
    $("#button_submit").on("click", function(e){
        e.preventDefault(); // prevent default action
        $('input[name="action"]').val("0");

        if(formValidate() == 0){ // jika tidak ada error
            Swal.fire({ 
                title: 'Are you sure?',
                text: "This assessment will be addressed to your superior, please give the assessment carefully, your assessment will have an effect for this employee in the future.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, I really sure!',
                cancelButtonText: 'No, give me more time'
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
                        Swal.fire(
                            'Okay',
                            'Please have a more time to review this employee assessment.',
                            'info'
                        )
                        return false;
                    }
            });
        } else {
            return false; // jangan kirimkan form
        }
    });

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

        $.each(input_value, (index, value) => {
            // untuk pertanyaan soft competency
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

            // untuk pertanyaan technical competency
            <?php for($x = 0; $x < 5; $x++): ?>
                if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]').val() != ""){
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
        });
        return validate;
    }

    // validator
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

    // ini untuk form pertanyaan technical
    <?php for($x = 0; $x < 5; $x++): ?>
        $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').on('change', function() {
            $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().removeClass('border border-danger my-3 pt-2');
            $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('py-2');
            // $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().parent().parent().addClass('py-2', {duration:500,effect:'fade'});
            $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').hide( "blind", 250, function () {
                $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').parent().parent().parent().siblings('.error-message').remove(); // show error tooltip
            });
        });

        // cek buat 
        $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]').on('keyup', function(){
            if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]:checked').val() == undefined){
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
        });

        
    <?php endfor; ?>
</script>