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
        $("#nilai_keterangan").empty(); // hapus child keterangan
        var rerata_semua = (rerata_A + rerata_B)/2; // ambil nilai rerata A dan B lalu dibagi 2
        $('input[name="rerata_keseluruhan"]').val(rerata_semua.toFixed(2));
        // beri keterangan nilai dari hasil total nilai
        let keterangan = "NaN"; let warna = "danger";
        if(rerata_semua < 0.50){
            keterangan = "Tidak Mencapai/Gagal";
            warna = "danger";
        } else if(rerata_semua >= 0.50 && rerata_semua <= 1.50){
            keterangan = "Kurang Baik";
            warna = "danger";
        } else if(rerata_semua >= 1.51 && rerata_semua <= 2.50){
            keterangan = "Cukup Baik";
            warna = "warning";
        } else if(rerata_semua >= 2.51 && rerata_semua <= 3.50){
            keterangan = "Baik";
            warna = "warning";
        } else if(rerata_semua >= 3.51 && rerata_semua <= 4.50){
            keterangan = "Sangat baik";
            warna = "success";
        } else if(rerata_semua >= 4.51 && rerata_semua <= 5.00){
            keterangan = "Luar Biasa";
            warna = "success";
        } else {
            // nothing
        }
        $("#nilai_keterangan").append('<span class="rerata-keterangan badge badge-'+warna+'">'+keterangan+'</span>'); // tambah keterangan
    }
</script>

