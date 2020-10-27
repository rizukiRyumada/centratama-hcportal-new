<script>
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
                            jawabanB0[x] = value.jawaban; // taruh jawaban B di variabel
                            $('input[name="B0-'+String("00" + x).slice(-2)+'"]').removeAttr('disabled');
                            x++; // increment
                        } else {
                            jawabanA[y] = value.jawaban; // taruh jawaban A di variabel
                            y++; // increment
                        }
                        $('input[name="'+value.id_pertanyaan+'"][value="'+value.jawaban+'"]').attr('checked',true); // tandai jawaban yang dipilih
                        $('input[name="'+value.id_pertanyaan+'"][value="'+value.jawaban+'"]').removeAttr('disabled');
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
</script>