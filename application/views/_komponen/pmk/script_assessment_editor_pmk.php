<script>
    // variabel penyimpan hasil perhitungan
    var jawaban_rerata1 = 0; var jawaban_dijawab1 = 0; var jawaban_total1 = 0;
    var jawaban_rerata2 = 0; var jawaban_dijawab2 = 0; var jawaban_total2 = 0;
    var jawaban_rerata3 = 0; var jawaban_dijawab3 = 0; var jawaban_total3 = 0;
    var rerata_A = 0; // menghitung rerata di form A
    var rerata_B = 0; // variable penyimpan perhitungan rerarta di form B
    var rerata_semua = 0; // variable penyimpan rerata keseluruhan soal A dan B

    // preparation
    var id_pmk = "<?= $id_pmk; ?>";

    // message validation
    var choose = "Please choose one value.";
    var chooseAndFill = "Please fill this assessment at least one.";
    var fill   = "This field is required.";
    var number = "The input is required and should be number.";

    // tooltip validation
    // var msg_choose = '<div class="invalid-tooltip" style="display: block">'+choose+'</div>' ;
    var msg_fill   = '<div class="invalid-tooltip" style="display: block">'+fill+'</div>' ;
    var msg_number = '<div class="invalid-tooltip" style="display: block">'+number+'</div>';
    var msg_choose = '<div class="error-message row mt-2 py-2 bg-danger" ><div class="col text-center">'+choose+'</div></div>';
    var msg_chooseAndFill = '<div class="error-message row mt-2 py-2 bg-danger" ><div class="col text-center">'+chooseAndFill+'</div></div>';

    // jawaban<?php // $id_name[0].$id_name[1]; ?>
    
    // inisialisasi variable jawaban soal A dan trigger penghitung rata-rata soal A
    var jawabanA = [];
    <?php $jawaban_total1 = 0; $jawaban_total2 = 0; $jawaban_total3 = 0;
    foreach($pertanyaan as $k => $v): ?>
        <?php $id_name = explode("-", $v['id_pertanyaan']); ?>
        jawabanA.push(0);

        <?php if($v['id_pertanyaan_tipe'] == "A1"): ?>
            jawaban_total1 = jawaban_total1 + 1;
            <?php $jawaban_total1++; ?>
            // $('input[name="<?= $v['id_pertanyaan']; ?>"]:checked')
            $('input[name="<?= $v['id_pertanyaan']; ?>"]').on('change', function() {
                jawabanA[<?= $k; ?>] = parseInt($('input[name="<?= $v['id_pertanyaan']; ?>"]:checked').val());
                hitungSekarang1();
            });
        <?php endif; ?>
        <?php if($v['id_pertanyaan_tipe'] == "A2"): ?>
            jawaban_total2 = jawaban_total2 + 1;
            <?php $jawaban_total2++; ?>
            // $('input[name="<?= $v['id_pertanyaan']; ?>"]:checked')
            $('input[name="<?= $v['id_pertanyaan']; ?>"]').on('change', function() {
                jawabanA[<?= $k; ?>] = parseInt($('input[name="<?= $v['id_pertanyaan']; ?>"]:checked').val());
                hitungSekarang2();
            });
        <?php endif; ?>
        <?php if($v['id_pertanyaan_tipe'] == "A3"): ?>
            jawaban_total3 = jawaban_total3 + 1;
            <?php $jawaban_total3++; ?>
            // $('input[name="<?= $v['id_pertanyaan']; ?>"]:checked')
            $('input[name="<?= $v['id_pertanyaan']; ?>"]').on('change', function() {
                jawabanA[<?= $k; ?>] = parseInt($('input[name="<?= $v['id_pertanyaan']; ?>"]:checked').val());
                hitungSekarang3();
            });
        <?php endif; ?>
    <?php endforeach;?>

    // inisialisasi variable jawaban B dan trigger penghitung jawaban B
    var jawabanB0 = [];
    <?php for($x = 0; $x < 5; $x++): ?>
        jawabanB0.push(0);

        $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]').on('change', function() {
            let vya = $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]').val();
            if(vya != ""){
                jawabanB0[<?= $x; ?>] = $('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>"]:checked').val();
                hitungRerataB();
            }
        });
    <?php endfor; ?>

    // ajax untuk mengambil data jawaban assessment
    $(document).ready(() => {
        $.ajax({
            url: '<?= base_url("pmk/ajax_getAssessmentData"); ?>',
            data: {
                id: id_pmk
            },
            method: "POST",
            beforeSend: function(){
                $('.overlay').fadeIn();
            },
            complete: function(){
                $('.overlay').fadeOut();
            },
            success: function(data){
                let vya = JSON.parse(data);
                if(vya.status == 1){
                    let x = 0; let y = 0; // inisialisasi variable x dan y
                    $.each(vya.data, function(index, value){
                        if(value.id_pertanyaan.substring(0,2) == "B0"){ // liat wildcard
                            $('input[name="'+value.id_pertanyaan+'_pertanyaan"]').val(value.pertanyaan_kustom); // masukkan pertanyaan kustomnya
                            $('input[name="'+value.id_pertanyaan+'"][value="'+value.jawaban+'"]').attr('checked',true); // tandai jawaban yang dipilih
                            jawabanB0[x] = value.jawaban; // taruh jawaban B di variabel
                            $('input[name="B0-'+String("00" + x).slice(-2)+'"]').removeAttr('disabled');
                            x++; // increment
                        } else {
                            $('input[name="'+value.id_pertanyaan+'"][value="'+value.jawaban+'"]').attr('checked',true); // tandai jawaban yang dipilih
                            jawabanA[y] = value.jawaban; // taruh jawaban A di variabel
                            y++; // increment
                        }
                    });
                    hitungSekarang1();
                    hitungSekarang2();
                    hitungSekarang3();
                    hitungRerataB(); // hitung rata2nya
                    // ambil jawaban total dan beri rata-rata
                } else {
                    // console.log('no data');
                }
            },
            error: function(){
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                footer: '<a class="font-weight-bold" href="https://wa.me/6281384740074/?text=*%5BHC%20Portal%5D*" target="_blank"><i class="fa fa-whatsapp"></i> Contact HC Care</a>'
                })
            }
        });
    });

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
    $("#button_submit").on("click", function(e){
        e.preventDefault(); // prevent default action
        $('input[name="action"]').val("1");

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
/*                             penghitung rerata                              */
/* -------------------------------------------------------------------------- */
    // variable soal assessment A
    <?php if($level_personal < 10): ?>
        var soal_assessment = 1;
    <?php elseif($level_personal < 18): ?>
        var soal_assessment = 2;
    <?php else: ?>
        var soal_assessment = 3;
    <?php endif; ?>
    $('#jumlah_A').text("/"+soal_assessment); // letakkan jumlah soal assessment
    
    // tandai total jawaban
    $("#jumlah_A1").text("/"+jawaban_total1);
    $("#jumlah_A2").text("/"+jawaban_total2);
    $("#jumlah_A3").text("/"+jawaban_total3);

    <?php $x = 0; ?>
    function hitungSekarang1(){
        jawaban_dijawab1 = <?php $flag = 1; ?> <?php foreach($pertanyaan as $k => $v): ?> <?php if($v['id_pertanyaan_tipe'] == "A1"): ?> <?php $id_name = explode("-", $v['id_pertanyaan']); ?> parseInt(jawabanA[<?= $x; ?>]) <?php $x++; if($flag < $jawaban_total1): ?> + <?php else: ?> ; <?php endif; ?> <?php $flag++; ?> <?php endif; ?> <?php endforeach;?>
        jawaban_rerata1 = parseFloat(jawaban_dijawab1/jawaban_total1);
        $('input[name="rerata_A1"]').val(jawaban_rerata1.toFixed(2));
        hitungRerata();
    }

    <?php if($level_personal > 9): ?>
        function hitungSekarang2(){
            jawaban_dijawab2 = <?php $flag = 1; ?> <?php foreach($pertanyaan as $k => $v): ?> <?php if($v['id_pertanyaan_tipe'] == "A2"): ?> <?php $id_name = explode("-", $v['id_pertanyaan']); ?> parseInt(jawabanA[<?= $x; ?>]) <?php $x++; if($flag < $jawaban_total2): ?> + <?php else: ?> ; <?php endif; ?> <?php $flag++; ?> <?php endif; ?> <?php endforeach;?>
            jawaban_rerata2 = parseFloat(jawaban_dijawab2/jawaban_total2);
            $('input[name="rerata_A2"]').val(jawaban_rerata2.toFixed(2));
            hitungRerata();
        }
    <?php endif; ?>

    <?php if($level_personal > 17): ?>
        function hitungSekarang3(){
            jawaban_dijawab3 = <?php $flag = 1; ?> <?php foreach($pertanyaan as $k => $v): ?> <?php if($v['id_pertanyaan_tipe'] == "A3"): ?> <?php $id_name = explode("-", $v['id_pertanyaan']); ?> parseInt(jawabanA[<?= $x; ?>]) <?php $x++; if($flag < $jawaban_total3): ?> + <?php else: ?> ; <?php endif; ?> <?php $flag++; ?> <?php endif; ?> <?php endforeach;?>
            jawaban_rerata3 = parseFloat(jawaban_dijawab3/jawaban_total3);
            $('input[name="rerata_A3"]').val(jawaban_rerata3.toFixed(2));
            hitungRerata();
        }
    <?php endif; ?>

    function hitungRerata(){
        rerata_A = <?php if($level_personal < 10): ?>(jawaban_rerata1)<?php elseif($level_personal < 18): ?>(jawaban_rerata1 + jawaban_rerata2)<?php else: ?>(jawaban_rerata1 + jawaban_rerata2 + jawaban_rerata3)<?php endif; ?>/ soal_assessment;
        $('input[name="rerata_A"]').val(rerata_A.toFixed(2));
        hitungRerataTotal(); // panggil function rerata total
    }

    // hapus nilai variable penilaian B
    function removeVariableB(variable){
        <?php for($x = 0; $x < 5; $x++): ?>
            if(variable == 'B0<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>'){
                jawabanB0[<?= $x; ?>] = 0;
            }
        <?php endfor; ?>
    }

    // hitung rerata B
    function hitungRerataB(){
        let jawabanB_total = 0;
        <?php for($x = 0; $x < 5; $x++): ?>
            if($('input[name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan"]').val() != ""){
                jawabanB_total = jawabanB_total + 1;
            }
        <?php endfor; ?>

        rerata_B = (<?php for($x = 0; $x < 5; $x++): ?> parseInt(jawabanB0[<?= $x; ?>]) <?php if($x == 4): ?> <?php else: ?> + <?php endif; ?><?php endfor; ?>)/parseInt(jawabanB_total);

        $("#jumlah_B0").text("/"+jawabanB_total);
        $('input[name="rerata_B0"]').val(rerata_B.toFixed(2));

        hitungRerataTotal(); // panggil function rerata total
    }

    // function untuk rerata jawaban assessment
    function hitungRerataTotal(){
        var rerata_semua = (rerata_A + rerata_B)/2; // ambil nilai rerata A dan B lalu dibagi 2
        $('input[name="rerata_keseluruhan"]').val(rerata_semua.toFixed(2));
    }

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