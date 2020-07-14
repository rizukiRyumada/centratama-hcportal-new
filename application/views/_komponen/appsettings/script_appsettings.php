<script>
    $('.sendNotificatiOnStatus').click(function(){
        let status = $(this).data('status');

        if(status == 0){
            statusText = "<b class='badge badge-danger'>Need To Submit</b>";
        } else if(status == 1 || status == 2){
            statusText = "<b class='badge badge-warning text-dark'>Need Approval</b>";
        } else if(status == 3){
            statusText = "<b class='badge badge-info'>Need Revise</b>";
        } else if(status == 4){
            statusText = "<b class='badge badge-success'>Approved</b>";
        }

        Swal.fire({
            title: 'Apa anda yakin?',
            html: "Anda akan mengirimkan email notifikasi ke posisi dengan status " + statusText + ". <br/><br/><b class='badge badge-warning text-dark'>Proses ini akan memakan waktu lama.</b>",
            icon: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }). then((result) => {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url('job_profile/sendNotificatiOnStatus') ?>',
                    data: {
                        status: status
                    },
                    method: 'POST',
                    beforeSend: (data) => { //sama kayak beforeSend: function(data){}
                    Swal.fire({
                            title: 'Harap Tunggu...',
                            html: 'Mengirim email ke karyawan pada posisi ini...',
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            }
                        });
                    },
                    success: (data) => {
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

                        $.ajax({
                            url: "<?= base_url('job_profile/getDate') ?>",
                            success: data => {
                                if(status == 0){
                                    $('#status0').html(data);
                                } else if(status == 1){
                                    $('#status1').html(data);
                                } else if(status == 2){
                                    $('#status2').html(data);
                                } else if(status == 3){
                                    $('#status3').html(data);
                                } else if(status == 4){
                                    $('#status4').html(data);
                                }
                            }
                        });
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
                })
            }
        })
    });
</script>