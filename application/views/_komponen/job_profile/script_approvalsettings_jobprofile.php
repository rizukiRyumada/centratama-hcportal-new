<script>
    //filter table dengan DOM
    $('#divisi').change(function(){
        $('#mainTable').DataTable().column(0).search(this.value).order([0, 'asc']).draw();// filter kolom pertama
        $('#mainTable').DataTable().column(4).search('').order([4, 'asc']).draw();// hapus filter kolom ke 5
        $('#mainTable').DataTable().column(1).search('').order([1, 'asc']).draw();// hapus filter kolom kedua
        $('#status').prop('selectedIndex',0);// kembalikan status ke default
    });
    $('#departement').change(function(){
        $('#mainTable').DataTable().column(1).search(this.value).order([1, 'asc']).draw();
        $('#mainTable').DataTable().column(4).search('').order([4, 'asc']).draw();
        $('#status').prop('selectedIndex',0);
    });
    $('#status').change(function(){
        $('#mainTable').DataTable().column(4).search(this.value).order([4, 'asc']).draw();
    });

    //mapping untuk pilihan departemen, supaya dapat tampil sesuai dengan divisi yang dipilih
    $('#divisi').change(function(){
        var dipilih = $(this).val(); //ambil value dari yang terpilih

        if(dipilih == ""){
            $('#mainTable').DataTable().column(1).search(dipilih).order([1, 'asc']).draw(); //kosongkan filter dom departement
        }

        $.ajax({
            url: "<?php echo base_url('job_profile/ajax_getdepartement'); ?>",
            data: {
                divisi: dipilih //kirim ke server php
            },
            method: "POST",
            success: function(data) { //jadi nanti dia balikin datanya dengan variable data
                $('#departement').empty().append('<option value="">All</option>'); //kosongkan selection value dan tambahkan satu selection option

                $.each(JSON.parse(data), function(i, v) {
                    $('#departement').append('<option value="dept-' + v.id + '">' + v.nama_departemen + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                });
                }
            });
        
        
    });

    $('#mainTable').DataTable().on('change', '.status_approval', function() {
        let id = $(this).data('id');
        let value = $(this).find("option:selected").val();

        if(value == 0){
            console.log('Need to be Submitted');
            $('.status_approval[data-id='+id+']').css('background-color', 'red');
            $('.status_approval[data-id='+id+']').css('color', 'white');
            $('.status_approval[data-id='+id+']').prop('selectedIndex',0);
        }else if(value == 1){
            console.log('Submitted');
            $('.status_approval[data-id='+id+']').css('background-color', 'yellow');
            $('.status_approval[data-id='+id+']').css('color', 'black');
            $('.status_approval[data-id='+id+']').prop('selectedIndex',1);
        }else if(value == 2){
            console.log('first Approval');
            $('.status_approval[data-id='+id+']').css('background-color', 'yellow');
            $('.status_approval[data-id='+id+']').css('color', 'black');
            $('.status_approval[data-id='+id+']').prop('selectedIndex',2);
        }else if(value == 3){
            console.log('Need to be revised');
            $('.status_approval[data-id='+id+']').css('background-color', 'orange');
            $('.status_approval[data-id='+id+']').css('color', 'white');
            $('.status_approval[data-id='+id+']').prop('selectedIndex',3);
        }else if(value == 4){
            console.log('Final Approval');
            $('.status_approval[data-id='+id+']').css('background-color', 'green');
            $('.status_approval[data-id='+id+']').css('color', 'white');
            $('.status_approval[data-id='+id+']').prop('selectedIndex',4);
        }

        $.ajax({
            url: '<?= base_url('job_profile/setStatusApproval') ?>',
            data: {
                id: id,
                status_approval: value
            },
            method: "POST",
            success: function(data) {
                Swal.fire(
                    'Berhasil!',
                    'Status Approval berhasil diubah.',
                    'success'
                );
            },
            error: function(data) {
                Swal.fire(
                    'ERROR!',
					'ERRRRRRROR',
					'error'
                );
            }
        });
        // console.log(id);
        // console.log(value);
    });

    $('#mainTable').DataTable().on('click', '.sendNotification', function() {
        let id_posisi = $(this).data('id');
        let nik = $(this).data('nik');

        Swal.fire({
            title: 'Apa anda yakin?',
            text: "Anda akan mengirimkan email notifikasi ke karyawan yang berada di posisi ini.",
            icon: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url('job_profile/sendNotification') ?>',
                    data: { 
                        id_posisi: id_posisi,
                        nik: nik
                    },
                    method: 'POST',
                    beforeSend: function(data) {
                        Swal.fire({
                            title: 'Harap Tunggu...',
                            html: 'Mengirim email ke karyawan pada posisi ini...',
                            allowOutsideClick: false,
                            // allowEscapeKey: false,
                            // timerProgressBar: true,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            }
                        });
                    },
                    success: function(data) {
                        if (data == ""){
                            Swal.fire(
                                'Tidak Terkirim!',
                                'Posisi ini tidak memiliki karyawan.',
                                'error'
                            );
                        } else {
                            Swal.close();
                            console.log(data);
                            Swal.fire(
                                'Terkirim!',
                                'Email notifikasi telah dikirimkan ke karyawan.',
                                'success'
                            );
                        }
                    },
                    error: function(data){
                        Swal.close();
                        console.log(data);
                        let error = JSON.parse(data.responseText);
                        Swal.fire(
                            error.header,
                            error.txtStatus,
                            'error'
                        );
                    }
                });
            } else {
                Swal.fire(
                    'Tidak Terkirim!',
                    'Email notifikasi tidak dikirimkan.',
                    'error'
                );
            }
        });
    });
</script>